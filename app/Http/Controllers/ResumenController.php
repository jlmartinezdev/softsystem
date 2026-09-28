<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Articulo;
use App\Stock;
use App\Compra;
use App\Venta;
use App\Sucursal;
use App\Empresa;
use App\Support\MailSettings;
use App\Mail\ResumenGerencialMail;
use DB;
use Mail;
use Auth;
use Log;

class ResumenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permiso:inf_resumen,open')->only(['index', 'resumen']);
        $this->middleware('permiso:inf_resumen,export')->only(['enviarCorreo']);
    }
    public function index()
    {   
        $sucursales = Sucursal::all();
        $defaultMail = MailSettings::all()['to'] ?? '';
        return view('informes.resumen', compact('sucursales', 'defaultMail'));
    }
    public function resumen(Request $request)
    {
        $sucursal = $request->idsucursal;
        $fecha_inicio = $request->desde;
        $fecha_fin = $request->hasta;

        // Ventas
        $ventasQuery = Venta::select('ventas.venta_total', 'ventas.venta_descuento', 'ventas.tipo_factura')
            ->whereBetween(DB::raw("date(ventas.venta_fecha)"), [$fecha_inicio, $fecha_fin]);
        if ($sucursal != '0' && !empty($sucursal)) {
            $ventasQuery->where('ventas.suc_cod', $sucursal);
        }
        $ventas = $ventasQuery->get();

        $d_ventas = ['contado' => 0, 'credito' => 0, 'total' => 0, 'descuento' => 0, 'ganancia' => 0];
        foreach ($ventas as $venta) {
            $monto = (float) $venta->venta_total;
            if ($venta->tipo_factura == 1) {
                $d_ventas['contado'] += $monto;
            } else {
                $d_ventas['credito'] += $monto;
            }
            $d_ventas['total'] += $monto;
            $d_ventas['descuento'] += (float) $venta->venta_descuento;
        }
        $d_ventas['cantidad'] = count($ventas);

        // Ganancia Bruta en Ventas
        if ($sucursal != '0' && !empty($sucursal)) {
            $ganancia = DB::select(
                "SELECT SUM(dv.venta_cantidad * (dv.venta_precio - dv.precio_compra)) AS ganancia
                 FROM ventas v
                 INNER JOIN detalle_venta dv ON v.nro_fact_ventas = dv.nro_fact_ventas
                 WHERE v.suc_cod = ? AND DATE(v.venta_fecha) BETWEEN ? AND ?",
                [$sucursal, $fecha_inicio, $fecha_fin]
            );
        } else {
            $ganancia = DB::select(
                "SELECT SUM(dv.venta_cantidad * (dv.venta_precio - dv.precio_compra)) AS ganancia
                 FROM ventas v
                 INNER JOIN detalle_venta dv ON v.nro_fact_ventas = dv.nro_fact_ventas
                 WHERE DATE(v.venta_fecha) BETWEEN ? AND ?",
                [$fecha_inicio, $fecha_fin]
            );
        }
        if (!empty($ganancia) && isset($ganancia[0]->ganancia)) {
            $d_ventas['ganancia'] = (float) $ganancia[0]->ganancia;
        }

        // Compras
        $comprasQuery = Compra::select(
            'compra.compra_cod',
            'compra.compra_tipo_factura',
            DB::raw('SUM(dc.compra_cantidad * dc.compra_precio) as total'),
            'compra.compra_descuento'
        )
        ->join('detalle_compra as dc', 'compra.compra_cod', '=', 'dc.compra_cod')
        ->whereBetween('compra_fecha', [$fecha_inicio, $fecha_fin])
        ->groupBy('compra.compra_cod', 'compra.compra_tipo_factura', 'compra.compra_descuento');

        if ($sucursal != '0' && !empty($sucursal)) {
            $comprasQuery->where('compra.suc_cod', $sucursal);
        }
        $compras = $comprasQuery->get();

        $d_compras = ['contado' => 0, 'credito' => 0, 'total' => 0, 'descuento' => 0];
        foreach ($compras as $compra) {
            $sub = (float) $compra->total;
            if ($compra->compra_tipo_factura == 1) {
                $d_compras['contado'] += $sub;
            } else {
                $d_compras['credito'] += $sub;
            }
            $d_compras['total'] += $sub;
            $d_compras['descuento'] += (float) $compra->compra_descuento;
        }
        $d_compras['cantidad'] = count($compras);

        // Artículos e Inventario
        $d_articulos = ['cantidad' => 0, 'stock' => 0, 'costo' => 0, 'venta' => 0, 'sin_stock' => 0, 'potencial' => 0];
        $articulosQuery = Articulo::select(
            'articulos.ARTICULOS_cod',
            'articulos.producto_costo_compra',
            'articulos.pre_venta1',
            DB::raw('SUM(stock.cantidad) as stock')
        )->join('stock', 'articulos.ARTICULOS_cod', '=', 'stock.ARTICULOS_cod');

        if ($sucursal != '0' && !empty($sucursal)) {
            $articulosQuery->where('stock.suc_cod', $sucursal);
        }
        $articulos = $articulosQuery->groupBy('articulos.ARTICULOS_cod', 'articulos.producto_costo_compra', 'articulos.pre_venta1')->get();

        foreach ($articulos as $articulo) {
            $cantStock = (float) ($articulo->stock ?? 0);
            $costoUnit = (float) ($articulo->producto_costo_compra ?? 0);
            $ventaUnit = (float) ($articulo->pre_venta1 ?? 0);

            $d_articulos['cantidad']++;
            $d_articulos['stock'] += $cantStock;
            $d_articulos['costo'] += ($costoUnit * $cantStock);
            $d_articulos['venta'] += ($ventaUnit * $cantStock);
            if ($cantStock <= 0) {
                $d_articulos['sin_stock']++;
            }
        }
        $d_articulos['costo'] = round($d_articulos['costo'], 0);
        $d_articulos['venta'] = round($d_articulos['venta'], 0);
        $d_articulos['potencial'] = max(0, $d_articulos['venta'] - $d_articulos['costo']);

        // Top 5 Productos del Período
        $topQuery = DB::table('detalle_venta as dv')
            ->join('ventas as v', 'v.nro_fact_ventas', '=', 'dv.nro_fact_ventas')
            ->join('articulos as a', 'a.ARTICULOS_cod', '=', 'dv.ARTICULOS_cod')
            ->select(
                'a.ARTICULOS_cod',
                'a.producto_nombre',
                DB::raw('SUM(dv.venta_cantidad) as cant_total'),
                DB::raw('SUM(dv.venta_cantidad * dv.venta_precio) as monto_total')
            )
            ->whereBetween(DB::raw("date(v.venta_fecha)"), [$fecha_inicio, $fecha_fin]);

        if ($sucursal != '0' && !empty($sucursal)) {
            $topQuery->where('v.suc_cod', $sucursal);
        }

        $topProductos = $topQuery->groupBy('a.ARTICULOS_cod', 'a.producto_nombre')
            ->orderByDesc('monto_total')
            ->limit(5)
            ->get();

        // Resumen Financiero Ejecutivo
        $margenPct = $d_ventas['total'] > 0
            ? round(($d_ventas['ganancia'] / $d_ventas['total']) * 100, 1)
            : 0;

        $balance = [
            'total_ventas' => $d_ventas['total'],
            'total_compras' => $d_compras['total'],
            'flujo_neto' => $d_ventas['total'] - $d_compras['total'],
            'ganancia_bruta' => $d_ventas['ganancia'],
            'margen_pct' => $margenPct
        ];

        return [
            'venta' => $d_ventas,
            'compra' => $d_compras,
            'articulo' => $d_articulos,
            'balance' => $balance,
            'top_productos' => $topProductos
        ];
    }

    public function enviarCorreo(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'desde' => 'required|date',
            'hasta' => 'required|date',
        ]);

        $cfg = MailSettings::all();
        if (empty($cfg['host']) || empty($cfg['username'])) {
            return response()->json([
                'ok' => false,
                'message' => 'El servidor de correo no está configurado. Verifique los ajustes SMTP en Configuración > Correo.'
            ], 422);
        }

        // Parse recipients (comma or semicolon separated)
        $rawRecipients = $request->email;
        $parts = preg_split('/[;,]+/', $rawRecipients);
        $recipients = [];
        foreach ($parts as $part) {
            $email = trim($part);
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $recipients[] = $email;
            }
        }

        if (empty($recipients)) {
            return response()->json([
                'ok' => false,
                'message' => 'Por favor ingrese al menos una dirección de correo válida.'
            ], 422);
        }

        try {
            // Obtenemos los datos calculados del período y sucursal
            $datosResumen = $this->resumen($request);

            $empresa = Empresa::first();
            $empresaNombre = $empresa ? $empresa->emp_nombre : 'SoftSystem';

            $sucursalNombre = 'Todas las Sucursales (Consolidado)';
            if (!empty($request->idsucursal) && $request->idsucursal != '0') {
                $suc = Sucursal::find($request->idsucursal);
                if ($suc) {
                    $sucursalNombre = $suc->suc_desc;
                }
            }

            $datosMail = [
                'empresa' => $empresaNombre,
                'desde' => date('d/m/Y', strtotime($request->desde)),
                'hasta' => date('d/m/Y', strtotime($request->hasta)),
                'sucursal_nombre' => $sucursalNombre,
                'generado_por' => Auth::check() ? Auth::user()->nom_usuarios : 'Sistema',
                'fecha_hora' => date('d/m/Y H:i'),
                'venta' => $datosResumen['venta'],
                'compra' => $datosResumen['compra'],
                'articulo' => $datosResumen['articulo'],
                'balance' => $datosResumen['balance'],
                'top_productos' => $datosResumen['top_productos'],
            ];

            MailSettings::apply();

            Mail::to($recipients)->send(new ResumenGerencialMail($datosMail));

            return response()->json([
                'ok' => true,
                'message' => 'El informe gerencial fue enviado con éxito a: ' . implode(', ', $recipients)
            ]);
        } catch (\Throwable $e) {
            Log::error('Error al enviar resumen gerencial por correo: ' . $e->getMessage());
            return response()->json([
                'ok' => false,
                'message' => 'No se pudo enviar el correo: ' . $e->getMessage()
            ], 500);
        }
    }
}

