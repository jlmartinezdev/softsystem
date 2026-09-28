<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Presupuesto;
use App\PresupuestoDetalle;
use App\Sucursal;
use App\Empresa;
use App\Cliente;
use App\Articulo;
use DB;
use Auth;
use Exception;

class PresupuestoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permiso:presupuesto,open')->only(['index', 'show']);
        $this->middleware('permiso:presupuesto,add')->only(['create', 'store']);
        $this->middleware('permiso:presupuesto,edit')->only(['cambiarEstado']);
        $this->middleware('permiso:presupuesto,del')->only(['destroy']);
        $this->middleware('permiso:presupuesto,export')->only(['pdf']);
    }

    /**
     * Display a listing or the main view.
     */
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $desde = $request->get('desde', date('Y-m-01'));
            $hasta = $request->get('hasta', date('Y-m-d'));
            $estado = $request->get('estado', 'TODOS');
            $sucursal = $request->get('sucursal', '0');
            $q = $request->get('q', '');

            $query = Presupuesto::with([
                'cliente' => function ($c) {
                    $c->select('CLIENTES_cod', 'cliente_nombre', 'cliente_ruc', 'cliente_ci', 'cliente_cel', 'cliente_telef');
                },
                'usuario' => function ($u) {
                    $u->select('cod_usuarios', 'nom_usuarios');
                },
                'sucursal' => function ($s) {
                    $s->select('suc_cod', 'suc_desc');
                },
                'detalles.articulo' => function ($a) {
                    $a->select('articulos_cod', 'producto_nombre', 'producto_c_barra');
                }
            ])
            ->filtroFecha($desde, $hasta)
            ->filtroEstado($estado)
            ->filtroSucursal($sucursal)
            ->filtroBusqueda($q)
            ->orderBy('pre_numero', 'desc');

            $presupuestos = $query->paginate(25);
            return response()->json($presupuestos);
        }

        $sucursales = Sucursal::all();
        $empresa = Empresa::first();
        $esAdministrador = Auth::user()->esAdministrador();
        $sucursalActual = session('sucursal_actual') ?? (Auth::user()->suc_cod ?? ($sucursales->first()->suc_cod ?? 1));
        $articuloLibreId = \App\Support\ArticuloLibre::id();

        return view('presupuesto', compact('sucursales', 'empresa', 'esAdministrador', 'sucursalActual', 'articuloLibreId'));
    }

    /**
     * Return create view or redirect to index.
     */
    public function create()
    {
        return redirect()->route('presupuesto.index');
    }

    /**
     * Store a newly created quotation in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cabecera' => 'required|array',
            'detalle' => 'required|array|min:1',
        ]);

        $cab = $request->input('cabecera', []);
        $items = $request->input('detalle', []);

        if (empty($items)) {
            return response()->json([
                'success' => false,
                'message' => 'El presupuesto debe contener al menos un artículo.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            $validezDias = (int) ($cab['validez_dias'] ?? 15);
            if ($validezDias < 1) {
                $validezDias = 15;
            }

            $fechaEmision = !empty($cab['fecha']) ? $cab['fecha'] . date(' H:i:s') : date('Y-m-d H:i:s');
            $vencimiento = !empty($cab['vencimiento'])
                ? $cab['vencimiento']
                : date('Y-m-d', strtotime("+$validezDias days", strtotime($fechaEmision)));

            // Calcular subtotales e impuestos
            $subtotalTotal = 0;
            $exentaTotal = 0;
            $gravada5Total = 0;
            $gravada10Total = 0;

            foreach ($items as $item) {
                $cantidad = max(0.001, (float) ($item['cantidad'] ?? 1));
                $precio = max(0, (float) ($item['precio'] ?? 0));
                $descItem = max(0, (float) ($item['descuento'] ?? 0));
                $subItem = max(0, ($cantidad * $precio) - $descItem);
                $subtotalTotal += $subItem;

                $ivaTipo = (int) ($item['iva'] ?? 10);
                if ($ivaTipo === 0) {
                    $exentaTotal += $subItem;
                } elseif ($ivaTipo === 5) {
                    $gravada5Total += $subItem;
                } else {
                    $gravada10Total += $subItem;
                }
            }

            $descuentoGeneral = max(0, (float) ($cab['descuento'] ?? 0));
            $granTotal = max(0, $subtotalTotal - $descuentoGeneral);

            // Liquidación de IVA (Régimen Paraguay: IVA 5% = total5 / 21, IVA 10% = total10 / 11)
            $iva5Liquidado = round($gravada5Total / 21, 2);
            $iva10Liquidado = round($gravada10Total / 11, 2);
            $totalIva = $iva5Liquidado + $iva10Liquidado;

            // Header Presupuesto
            $presupuesto = new Presupuesto();
            $presupuesto->CLIENTES_cod = !empty($cab['clienteId']) ? $cab['clienteId'] : null;
            $presupuesto->cod_usuarios = Auth::user()->cod_usuarios;
            $presupuesto->suc_cod = !empty($cab['idSucursal']) ? $cab['idSucursal'] : 1;
            $presupuesto->pre_fecha = $fechaEmision;
            $presupuesto->pre_validez_dias = $validezDias;
            $presupuesto->pre_vencimiento = $vencimiento;
            $presupuesto->pre_tipo = (int) ($cab['condicion'] ?? 1); // 1: Contado, 2: Credito
            $presupuesto->estado = 'PENDIENTE';
            $presupuesto->descuento = $descuentoGeneral;
            $presupuesto->subtotal = $subtotalTotal;
            $presupuesto->total = $granTotal;
            $presupuesto->total_exenta = $exentaTotal;
            $presupuesto->total_iva5 = $iva5Liquidado;
            $presupuesto->total_iva10 = $iva10Liquidado;
            $presupuesto->total_iva = $totalIva;
            $presupuesto->observaciones = !empty($cab['observaciones']) ? trim($cab['observaciones']) : null;
            $presupuesto->save();

            // Guardar renglones de detalle
            foreach ($items as $item) {
                $cantidad = max(0.001, (float) ($item['cantidad'] ?? 1));
                $precio = max(0, (float) ($item['precio'] ?? 0));
                $descItem = max(0, (float) ($item['descuento'] ?? 0));
                $subItem = max(0, ($cantidad * $precio) - $descItem);

                $ivaTipo = (int) ($item['iva'] ?? 10);
                $exenta = ($ivaTipo === 0) ? $subItem : 0;
                $grav5 = ($ivaTipo === 5) ? $subItem : 0;
                $grav10 = ($ivaTipo === 10 || ($ivaTipo !== 0 && $ivaTipo !== 5)) ? $subItem : 0;

                $detalle = new PresupuestoDetalle();
                $detalle->pre_numero = $presupuesto->pre_numero;
                $detalle->ARTICULOS_cod = $item['codigo'];
                $detalle->descripcion_libre = !empty($item['descripcion_libre']) ? trim($item['descripcion_libre']) : null;
                $detalle->pre_det_cantidad = $cantidad;
                $detalle->pre_det_precio = $precio;
                $detalle->pre_det_descuento = $descItem;
                $detalle->pre_det_exenta = $exenta;
                $detalle->pre_det_gravada5 = $grav5;
                $detalle->pre_det_gravada = $grav10;
                $detalle->pre_det_subtotal = $subItem;
                $detalle->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'pre_numero' => $presupuesto->pre_numero,
                'message' => 'Presupuesto Nº ' . $presupuesto->pre_numero . ' guardado exitosamente.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar presupuesto: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show details of a specific quotation.
     */
    public function show($id)
    {
        $presupuesto = Presupuesto::with([
            'cliente',
            'usuario',
            'sucursal',
            'detalles.articulo' => function ($q) {
                $q->join('presentacion', 'articulos.present_cod', '=', 'presentacion.present_cod')
                  ->leftJoin('unidad', 'articulos.uni_codigo', '=', 'unidad.uni_codigo')
                  ->select(
                      'articulos.articulos_cod',
                      'articulos.producto_nombre',
                      'articulos.producto_c_barra',
                      'presentacion.iva',
                      'unidad.uni_nombre as uni_desc'
                  );
            }
        ])->findOrFail($id);

        return response()->json($presupuesto);
    }

    /**
     * Update status (PENDIENTE, APROBADO, FACTURADO, RECHAZADO, ANULADO).
     */
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:PENDIENTE,APROBADO,FACTURADO,RECHAZADO,ANULADO',
        ]);

        $presupuesto = Presupuesto::findOrFail($id);
        $presupuesto->estado = $request->estado;
        $presupuesto->save();

        return response()->json([
            'success' => true,
            'message' => 'Estado de Presupuesto Nº ' . $id . ' actualizado a ' . $request->estado . '.',
            'estado' => $presupuesto->estado,
        ]);
    }

    /**
     * Delete or cancel quotation.
     */
    public function destroy($id)
    {
        $presupuesto = Presupuesto::findOrFail($id);
        $presupuesto->estado = 'ANULADO';
        $presupuesto->save();

        return response()->json([
            'success' => true,
            'message' => 'Presupuesto Nº ' . $id . ' anulado correctamente.',
        ]);
    }

    /**
     * Generate printable budget / quotation document.
     */
    public function pdf($id)
    {
        $presupuesto = Presupuesto::with([
            'cliente',
            'usuario',
            'sucursal',
            'detalles.articulo' => function ($q) {
                $q->join('presentacion', 'articulos.present_cod', '=', 'presentacion.present_cod')
                  ->leftJoin('unidad', 'articulos.uni_codigo', '=', 'unidad.uni_codigo')
                  ->select(
                      'articulos.articulos_cod',
                      'articulos.producto_nombre',
                      'articulos.producto_c_barra',
                      'presentacion.iva',
                      'unidad.uni_nombre as uni_desc'
                  );
            }
        ])->findOrFail($id);

        $empresa = Empresa::first();

        return view('pdf.presupuesto', compact('presupuesto', 'empresa'));
    }

    /**
     * Prepare quotation items formatted for loading into sales POS cart.
     */
    public function getParaVenta($id)
    {
        $presupuesto = Presupuesto::with([
            'cliente',
            'detalles.articulo' => function ($q) {
                $q->join('presentacion', 'articulos.present_cod', '=', 'presentacion.present_cod')
                  ->leftJoin('unidad', 'articulos.uni_codigo', '=', 'unidad.uni_codigo')
                  ->select(
                      'articulos.articulos_cod',
                      'articulos.producto_nombre',
                      'articulos.producto_c_barra',
                      'articulos.producto_costo_compra',
                      'presentacion.iva',
                      'unidad.uni_nombre as uni_desc'
                  );
            }
        ])->findOrFail($id);

        $items = [];
        foreach ($presupuesto->detalles as $det) {
            $art = $det->articulo;
            $items[] = [
                'codigo' => $det->ARTICULOS_cod,
                'c_barra' => $art->producto_c_barra ?? '',
                'descripcion' => !empty($det->descripcion_libre) ? $det->descripcion_libre : ($art->producto_nombre ?? 'Artículo'),
                'cantidad' => (float) $det->pre_det_cantidad,
                'precio' => (float) $det->pre_det_precio,
                'descuento' => (float) $det->pre_det_descuento,
                'iva' => (int) ($art->iva ?? 10),
                'costo' => (float) ($art->producto_costo_compra ?? 0),
                'subtotal' => (float) $det->pre_det_subtotal,
            ];
        }

        return response()->json([
            'success' => true,
            'presupuesto' => [
                'pre_numero' => $presupuesto->pre_numero,
                'cliente' => $presupuesto->cliente,
                'condicion' => $presupuesto->pre_tipo,
                'descuento' => (float) $presupuesto->descuento,
                'observaciones' => $presupuesto->observaciones,
            ],
            'items' => $items,
        ]);
    }
}
