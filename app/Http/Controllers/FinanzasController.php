<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GastoCategoria;
use App\GastoOperativo;
use App\CtaPagar;
use App\CtaPagarPago;
use App\Compra;
use App\Proveedor;
use App\Sucursal;
use App\Empresa;
use App\Apertura;
use App\MovimientoCaja;
use App\Reparacion;
use App\ReparacionGarantia;
use App\CelularImei;
use App\ComisionLiquidacion;
use App\Support\FinanzasSettings;
use App\Support\FinanzasSchema;
use DB;
use Auth;
use Exception;

class FinanzasController extends Controller
{
    public function __construct()
    {
        FinanzasSchema::ensureExists();

        $this->middleware('auth');

        // Verificar si el módulo está habilitado por el Superadministrador
        $this->middleware(function ($request, $next) {
            $actionMethod = $request->route() ? $request->route()->getActionMethod() : '';
            if ($actionMethod === 'toggleModulo') {
                return $next($request);
            }

            if (!FinanzasSettings::isModuloActivo() && !Auth::user()->esSuperAdministrador()) {
                $msg = 'El módulo de Gastos, Cuentas a Pagar y Estado de Resultados se encuentra deshabilitado. Solo el Superadministrador del sistema puede habilitarlo.';
                if ($request->ajax() || $request->expectsJson() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'error'   => $msg,
                        'message' => $msg,
                    ], 403);
                }
                return redirect()->route('home')->with('error', $msg);
            }

            return $next($request);
        });

        $this->middleware('permiso:finanzas,open')->only([
            'index', 'getEstadoResultados', 'showCtaPagar'
        ]);
        $this->middleware('permiso:finanzas,add')->only([
            'storeGasto', 'storeCategoria', 'storeCtaPagar', 'pagarCtaPagar'
        ]);
        $this->middleware('permiso:finanzas,del')->only([
            'anularGasto', 'anularCtaPagar'
        ]);
        $this->middleware('permiso:finanzas,export')->only([
            'pdfEstadoResultados', 'pdfReciboPagoProveedor'
        ]);
    }

    /**
     * Habilita o deshabilita el módulo (Exclusivo Superadministrador).
     */
    public function toggleModulo(Request $request)
    {
        if (!Auth::user()->esSuperAdministrador()) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso exclusivo para el Superadministrador del sistema.',
            ], 403);
        }

        $nuevoEstado = $request->has('activo')
            ? (bool) $request->input('activo')
            : !FinanzasSettings::isModuloActivo();

        $activo = FinanzasSettings::setModuloActivo($nuevoEstado);

        return response()->json([
            'success' => true,
            'activo'  => $activo,
            'message' => $activo
                ? 'Módulo de Gastos, Cuentas a Pagar y Resultados HABILITADO.'
                : 'Módulo de Gastos, Cuentas a Pagar y Resultados DESHABILITADO.',
        ]);
    }

    /**
     * Sincroniza automáticamente las compras a crédito (compra_tipo_factura = 2) hacia la tabla ctas_pagar.
     */
    public static function sincronizarComprasCredito()
    {
        try {
            $comprasCredito = DB::table('compra as c')
                ->where('c.compra_tipo_factura', '2')
                ->where('c.estado', '!=', '0')
                ->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                      ->from('ctas_pagar as cp')
                      ->whereRaw('cp.compra_cod = c.compra_cod');
                })
                ->get();

            foreach ($comprasCredito as $c) {
                $subtotal = (float) DB::table('detalle_compra')
                    ->where('compra_cod', $c->compra_cod)
                    ->sum(DB::raw('compra_precio * compra_cantidad'));

                $total = max(0, $subtotal - (float) ($c->compra_descuento ?? 0));
                if ($total <= 0) {
                    continue;
                }

                $fechaEmision = !empty($c->compra_fecha) ? substr($c->compra_fecha, 0, 10) : date('Y-m-d');
                $venc = (!empty($c->compra_intervalo_venc) && $c->compra_intervalo_venc !== '2030-01-01')
                    ? $c->compra_intervalo_venc
                    : date('Y-m-d', strtotime('+30 days', strtotime($fechaEmision)));

                $nextId = (int) (CtaPagar::max('id') ?: 0) + 1;
                $codCta = 'CP-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
                while (CtaPagar::where('codigo_cta', $codCta)->exists()) {
                    $nextId++;
                    $codCta = 'CP-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
                }

                CtaPagar::create([
                    'codigo_cta'            => $codCta,
                    'compra_cod'            => $c->compra_cod,
                    'PROVEEDOR_cod'         => $c->PROVEEDOR_cod,
                    'suc_cod'               => $c->suc_cod ?: 1,
                    'nro_factura_proveedor' => $c->compra_factura ?: ('Compra #' . $c->compra_cod),
                    'concepto'              => 'Compra de Mercadería a Crédito #' . $c->compra_cod . ' (Fact. ' . ($c->compra_factura ?: 'S/N') . ')',
                    'fecha_emision'         => $fechaEmision,
                    'fecha_vencimiento'     => $venc,
                    'nro_cuota'             => 1,
                    'total_cuotas'          => 1,
                    'monto_original'        => $total,
                    'monto_pagado'          => 0,
                    'monto_saldo'           => $total,
                    'estado'                => 'PENDIENTE',
                    'cod_usuarios'          => $c->cod_usuarios ?? null,
                ]);
            }
        } catch (Exception $e) {
            // Silencioso
        }
    }

    /**
     * Vista principal y listado AJAX de Gastos Operativos y Cuentas a Pagar.
     */
    public function index(Request $request)
    {
        self::sincronizarComprasCredito();

        if ($request->ajax() || $request->wantsJson()) {
            $tab = $request->get('tab', 'gastos');
            $desde = $request->get('desde', date('Y-m-01'));
            $hasta = $request->get('hasta', date('Y-m-d'));
            $sucursal = $request->get('suc_cod', '0');
            $q = trim((string) $request->get('q', ''));

            if ($tab === 'ctas_pagar') {
                $estadoCta = $request->get('estado', 'PENDIENTES');
                $provCod = $request->get('proveedor_cod', '0');

                $cpQuery = CtaPagar::with([
                    'proveedor:PROVEEDOR_cod,proveedor_nombre,proveedor_ruc,proveedor_telef',
                    'sucursal:suc_cod,suc_desc',
                ])->where('estado', '!=', 'ANULADO');

                if ($estadoCta === 'PENDIENTES') {
                    $cpQuery->whereIn('estado', ['PENDIENTE', 'PARCIAL']);
                } elseif ($estadoCta === 'VENCIDAS') {
                    $cpQuery->whereIn('estado', ['PENDIENTE', 'PARCIAL'])
                            ->where('fecha_vencimiento', '<', date('Y-m-d'));
                } elseif ($estadoCta !== 'TODOS') {
                    $cpQuery->where('estado', $estadoCta);
                }

                if (!empty($sucursal) && $sucursal != '0') {
                    $cpQuery->where('suc_cod', $sucursal);
                }
                if (!empty($provCod) && $provCod != '0') {
                    $cpQuery->where('PROVEEDOR_cod', $provCod);
                }
                if ($q !== '') {
                    $cpQuery->where(function ($w) use ($q) {
                        $w->where('codigo_cta', 'like', "%{$q}%")
                          ->orWhere('nro_factura_proveedor', 'like', "%{$q}%")
                          ->orWhere('concepto', 'like', "%{$q}%")
                          ->orWhereHas('proveedor', function ($p) use ($q) {
                              $p->where('proveedor_nombre', 'like', "%{$q}%")
                                ->orWhere('proveedor_ruc', 'like', "%{$q}%");
                          });
                    });
                }

                $cuentas = $cpQuery->orderBy('fecha_vencimiento', 'asc')->paginate(25);

                $hoy = date('Y-m-d');
                $diasAlerta = FinanzasSettings::all()['dias_alerta_vencimiento'] ?? 7;
                $limiteAlerta = date('Y-m-d', strtotime("+{$diasAlerta} days"));

                $baseCp = CtaPagar::whereIn('estado', ['PENDIENTE', 'PARCIAL']);
                if (!empty($sucursal) && $sucursal != '0') {
                    $baseCp->where('suc_cod', $sucursal);
                }

                $kpisCtas = [
                    'deuda_total_pendiente' => (float) (clone $baseCp)->sum('monto_saldo'),
                    'vencidas_monto'        => (float) (clone $baseCp)->where('fecha_vencimiento', '<', $hoy)->sum('monto_saldo'),
                    'vencidas_cant'         => (clone $baseCp)->where('fecha_vencimiento', '<', $hoy)->count(),
                    'por_vencer_cant'       => (clone $baseCp)->whereBetween('fecha_vencimiento', [$hoy, $limiteAlerta])->count(),
                    'pagado_mes'            => (float) CtaPagarPago::whereBetween(DB::raw('DATE(fecha_pago)'), [$desde, $hasta])->sum('monto'),
                ];

                return response()->json([
                    'cuentas'   => $cuentas,
                    'kpis_ctas' => $kpisCtas,
                ]);
            }

            // Tab Gastos Operativos
            $catId = $request->get('categoria_id', '0');
            $gQuery = GastoOperativo::with([
                'categoria',
                'sucursal:suc_cod,suc_desc',
                'proveedor:PROVEEDOR_cod,proveedor_nombre',
                'usuario:cod_usuarios,nom_usuarios',
            ])->where('estado', 'ACTIVO');

            if (!empty($desde) && !empty($hasta)) {
                $gQuery->whereBetween(DB::raw('DATE(fecha_gasto)'), [$desde, $hasta]);
            }
            if (!empty($sucursal) && $sucursal != '0') {
                $gQuery->where('suc_cod', $sucursal);
            }
            if (!empty($catId) && $catId != '0') {
                $gQuery->where('categoria_id', $catId);
            }
            if ($q !== '') {
                $gQuery->where(function ($w) use ($q) {
                    $w->where('codigo_gasto', 'like', "%{$q}%")
                      ->orWhere('concepto', 'like', "%{$q}%")
                      ->orWhere('nro_comprobante', 'like', "%{$q}%");
                });
            }

            $gastos = $gQuery->orderBy('fecha_gasto', 'desc')->orderBy('id', 'desc')->paginate(25);

            return response()->json([
                'gastos' => $gastos,
            ]);
        }

        $categorias = GastoCategoria::where('activo', 1)->orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('proveedor_nombre')->get(['PROVEEDOR_cod', 'proveedor_nombre', 'proveedor_ruc']);
        $sucursales = Sucursal::all();
        $empresa = Empresa::first();
        $user = Auth::user();
        $esAdministrador = $user->esAdministrador();
        $esSuperAdministrador = $user->esSuperAdministrador();
        $sucursalActual = session('sucursal_actual') ?? ($user->suc_cod ?? ($sucursales->first()->suc_cod ?? 1));
        $finanzasConfig = FinanzasSettings::all();

        $permisosModulo = [
            'open'            => $user->tienePermiso('finanzas', 'open'),
            'add'             => $user->tienePermiso('finanzas', 'add'),
            'edit'            => $user->tienePermiso('finanzas', 'edit'),
            'del'             => $user->tienePermiso('finanzas', 'del'),
            'export'          => $user->tienePermiso('finanzas', 'export'),
            'registrar_gasto' => $user->tieneAccion('finanzas_registrar_gasto'),
            'cuentas_pagar'   => $user->tieneAccion('finanzas_cuentas_pagar'),
            'ver_resultados'  => $user->tieneAccion('finanzas_ver_resultados'),
        ];

        return view('finanzas', compact(
            'categorias',
            'proveedores',
            'sucursales',
            'empresa',
            'esAdministrador',
            'esSuperAdministrador',
            'sucursalActual',
            'finanzasConfig',
            'permisosModulo'
        ));
    }

    /**
     * Calcula el Estado de Resultados (Ganancia Neta Real del Período).
     */
    public function getEstadoResultados(Request $request)
    {
        if (!Auth::user()->tieneAccion('finanzas_ver_resultados')) {
            return response()->json([
                'success' => false,
                'message' => 'No cuenta con autorización para visualizar el Estado de Resultados.',
            ], 403);
        }

        $desde = $request->get('desde', date('Y-m-01'));
        $hasta = $request->get('hasta', date('Y-m-d'));
        $sucCod = $request->get('suc_cod', '0');

        return response()->json([
            'success'    => true,
            'resultados' => $this->calcularEstadoResultadosInterno($desde, $hasta, $sucCod),
        ]);
    }

    protected function calcularEstadoResultadosInterno($desde, $hasta, $sucCod = '0')
    {
        // IDs de facturas que corresponden a Reparaciones o Equipos por IMEI (para no duplicar en Ventas POS)
        $facturasRep = DB::table('reparaciones')
            ->whereNotNull('nro_fact_ventas')
            ->where('estado', '!=', 'ANULADO')
            ->pluck('nro_fact_ventas')
            ->toArray();

        $facturasImei = DB::table('celulares_imei')
            ->whereNotNull('nro_fact_ventas')
            ->where('estado', 'VENDIDO')
            ->pluck('nro_fact_ventas')
            ->toArray();

        $facturasExcluidas = array_unique(array_merge($facturasRep, $facturasImei));

        // 1. VENTAS POS (MERCADERÍA GENERAL Y ACCESORIOS)
        $ventasPosQuery = DB::table('ventas as v')
            ->where('v.venta_estado', '!=', '0')
            ->whereBetween(DB::raw('DATE(v.venta_fecha)'), [$desde, $hasta]);

        if (!empty($sucCod) && $sucCod != '0') {
            $ventasPosQuery->where('v.suc_cod', $sucCod);
        }
        if (!empty($facturasExcluidas)) {
            $ventasPosQuery->whereNotIn('v.nro_fact_ventas', $facturasExcluidas);
        }

        $ingresosVentasPos = (float) (clone $ventasPosQuery)->sum('v.venta_total');
        $cantVentasPos = (clone $ventasPosQuery)->count();

        $costoVentasPosQuery = DB::table('detalle_venta as dv')
            ->join('ventas as v', 'dv.nro_fact_ventas', '=', 'v.nro_fact_ventas')
            ->where('v.venta_estado', '!=', '0')
            ->whereBetween(DB::raw('DATE(v.venta_fecha)'), [$desde, $hasta]);

        if (!empty($sucCod) && $sucCod != '0') {
            $costoVentasPosQuery->where('v.suc_cod', $sucCod);
        }
        if (!empty($facturasExcluidas)) {
            $costoVentasPosQuery->whereNotIn('v.nro_fact_ventas', $facturasExcluidas);
        }

        $costoVentasPos = (float) $costoVentasPosQuery->sum(DB::raw('COALESCE(dv.precio_compra, 0) * dv.venta_cantidad'));
        $utilidadVentasPos = $ingresosVentasPos - $costoVentasPos;

        // 2. SERVICIO TÉCNICO (REPARACIONES DE CELULARES)
        $repQuery = Reparacion::whereIn('estado', ['REPARADO', 'ENTREGADO'])
            ->where('estado_pago', '!=', 'PENDIENTE')
            ->whereBetween(DB::raw('DATE(COALESCE(fecha_entrega, fecha_reparado, fecha_recepcion))'), [$desde, $hasta]);

        if (!empty($sucCod) && $sucCod != '0') {
            $repQuery->where('suc_cod', $sucCod);
        }

        $ingresosTaller = (float) (clone $repQuery)->sum('total_pagado');
        $costoRepuestosTaller = (float) (clone $repQuery)->sum('costo_repuestos');
        $cantReparaciones = (clone $repQuery)->count();

        $costoGarantiasTaller = (float) ReparacionGarantia::whereBetween(DB::raw('DATE(fecha_reclamo)'), [$desde, $hasta])
            ->sum('costo_asumido_taller');

        $costoTotalTaller = $costoRepuestosTaller + $costoGarantiasTaller;
        $utilidadTaller = $ingresosTaller - $costoTotalTaller;

        // 3. VENTA DE EQUIPOS POR IMEI Y PLAN CANJE
        $imeiQuery = CelularImei::where('estado', 'VENDIDO')
            ->whereBetween(DB::raw('DATE(fecha_venta)'), [$desde, $hasta]);

        if (!empty($sucCod) && $sucCod != '0') {
            $imeiQuery->where('suc_cod', $sucCod);
        }

        $equiposVendidos = $imeiQuery->get();
        $cantEquiposImei = $equiposVendidos->count();
        $ingresosImei = 0;
        $costoImei = 0;

        foreach ($equiposVendidos as $eq) {
            $precioReal = max(0, ((float) $eq->precio_vendido ?: (float) $eq->precio_venta_contado) - (float) $eq->descuento_venta);
            $ingresosImei += $precioReal;
            $costoImei += (float) $eq->costo_total;
        }
        $utilidadImei = $ingresosImei - $costoImei;

        // (=) TOTAL INGRESOS Y UTILIDAD BRUTA OPERATIVA
        $ingresosTotales = $ingresosVentasPos + $ingresosTaller + $ingresosImei;
        $costosDirectosTotales = $costoVentasPos + $costoTotalTaller + $costoImei;
        $utilidadBrutaTotal = $utilidadVentasPos + $utilidadTaller + $utilidadImei;

        // 4. (-) COMISIONES LIQUIDADAS A TÉCNICOS Y VENDEDORES
        $comQuery = ComisionLiquidacion::where('estado', 'PAGADO')
            ->whereBetween(DB::raw('DATE(fecha_liquidacion)'), [$desde, $hasta]);
        if (!empty($sucCod) && $sucCod != '0') {
            $comQuery->where('suc_cod', $sucCod);
        }
        $totalComisiones = (float) $comQuery->sum('total_neto_pagar');

        // 5. (-) GASTOS OPERATIVOS POR CATEGORÍA
        $gastosPorCat = DB::table('gastos_operativos as g')
            ->join('gasto_categorias as c', 'g.categoria_id', '=', 'c.id')
            ->where('g.estado', 'ACTIVO')
            ->whereBetween(DB::raw('DATE(g.fecha_gasto)'), [$desde, $hasta])
            ->when(!empty($sucCod) && $sucCod != '0', function ($q) use ($sucCod) {
                $q->where('g.suc_cod', $sucCod);
            })
            ->select(
                'c.id',
                'c.nombre',
                'c.tipo',
                'c.icono',
                'c.color',
                DB::raw('COUNT(g.id) as cantidad'),
                DB::raw('SUM(g.monto) as total')
            )
            ->groupBy('c.id', 'c.nombre', 'c.tipo', 'c.icono', 'c.color')
            ->orderByDesc('total')
            ->get();

        $totalGastosOperativos = 0;
        foreach ($gastosPorCat as $gc) {
            $gc->total = (float) $gc->total;
            $totalGastosOperativos += $gc->total;
        }

        // (=) GANANCIA NETA REAL DEL PERÍODO
        $gananciaNetaReal = $utilidadBrutaTotal - $totalComisiones - $totalGastosOperativos;
        $margenNetoPorc = $ingresosTotales > 0
            ? round(($gananciaNetaReal / $ingresosTotales) * 100, 1)
            : 0;

        // Resumen de Cuentas a Pagar y Cobrar
        $pagosProveedoresPeriodo = (float) CtaPagarPago::whereBetween(DB::raw('DATE(fecha_pago)'), [$desde, $hasta])
            ->when(!empty($sucCod) && $sucCod != '0', function ($q) use ($sucCod) {
                $q->where('suc_cod', $sucCod);
            })
            ->sum('monto');

        $deudaProveedoresActual = (float) CtaPagar::whereIn('estado', ['PENDIENTE', 'PARCIAL'])
            ->when(!empty($sucCod) && $sucCod != '0', function ($q) use ($sucCod) {
                $q->where('suc_cod', $sucCod);
            })
            ->sum('monto_saldo');

        return [
            'desde'                    => $desde,
            'hasta'                    => $hasta,
            // 1. Ventas POS
            'cant_ventas_pos'          => $cantVentasPos,
            'ingresos_ventas_pos'      => $ingresosVentasPos,
            'costo_ventas_pos'         => $costoVentasPos,
            'utilidad_ventas_pos'      => $utilidadVentasPos,
            // 2. Servicio Técnico
            'cant_reparaciones'        => $cantReparaciones,
            'ingresos_taller'          => $ingresosTaller,
            'costo_repuestos_taller'   => $costoRepuestosTaller,
            'costo_garantias_taller'   => $costoGarantiasTaller,
            'utilidad_taller'          => $utilidadTaller,
            // 3. Equipos por IMEI
            'cant_equipos_imei'        => $cantEquiposImei,
            'ingresos_imei'            => $ingresosImei,
            'costo_imei'               => $costoImei,
            'utilidad_imei'            => $utilidadImei,
            // Consolidado Bruto
            'ingresos_totales'         => $ingresosTotales,
            'costos_directos_totales'  => $costosDirectosTotales,
            'utilidad_bruta_total'     => $utilidadBrutaTotal,
            // Egresos Operativos
            'total_comisiones'         => $totalComisiones,
            'gastos_por_categoria'     => $gastosPorCat,
            'total_gastos_operativos'  => $totalGastosOperativos,
            // Resultado Neto Final
            'ganancia_neta_real'       => $gananciaNetaReal,
            'margen_neto_porc'         => $margenNetoPorc,
            // Proveedores
            'pagos_proveedores_periodo'=> $pagosProveedoresPeriodo,
            'deuda_proveedores_actual' => $deudaProveedoresActual,
        ];
    }

    /**
     * Registra un nuevo Gasto Operativo.
     */
    public function storeGasto(Request $request)
    {
        if (!Auth::user()->tieneAccion('finanzas_registrar_gasto')) {
            return response()->json([
                'success' => false,
                'message' => 'No cuenta con autorización para registrar gastos operativos.',
            ], 403);
        }

        $request->validate([
            'categoria_id' => 'required|integer',
            'concepto'     => 'required|string|max:255',
            'monto'        => 'required|numeric|min:1',
        ], [
            'categoria_id.required' => 'Seleccioná la categoría del gasto.',
            'concepto.required'     => 'Ingresá la descripción o concepto del gasto.',
            'monto.required'        => 'Ingresá el monto del gasto.',
        ]);

        DB::beginTransaction();
        try {
            $nextId = (int) (GastoOperativo::max('id') ?: 0) + 1;
            $codGasto = 'GS-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            while (GastoOperativo::where('codigo_gasto', $codGasto)->exists()) {
                $nextId++;
                $codGasto = 'GS-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
            }

            $sucCod = (int) ($request->input('suc_cod') ?: 1);
            $monto = (float) $request->input('monto');
            $registrarCaja = (bool) $request->input('registrar_caja', false);
            $nroOpCaja = null;

            if ($registrarCaja && $monto > 0) {
                $nroOpCaja = $this->registrarSalidaCaja(
                    $sucCod,
                    $monto,
                    "Gasto Operativo {$codGasto}: " . trim($request->input('concepto'))
                );
            }

            $gasto = GastoOperativo::create([
                'codigo_gasto'       => $codGasto,
                'categoria_id'       => (int) $request->input('categoria_id'),
                'suc_cod'            => $sucCod,
                'PROVEEDOR_cod'      => $request->filled('PROVEEDOR_cod') ? (int) $request->input('PROVEEDOR_cod') : null,
                'fecha_gasto'        => $request->filled('fecha_gasto') ? ($request->input('fecha_gasto') . ' ' . date('H:i:s')) : date('Y-m-d H:i:s'),
                'nro_comprobante'    => trim((string) $request->input('nro_comprobante', '')),
                'concepto'           => trim($request->input('concepto')),
                'monto'              => $monto,
                'iva_incluido'       => (int) $request->input('iva_incluido', 10),
                'forma_pago'         => (int) $request->input('forma_pago', 1),
                'registrar_caja'     => $nroOpCaja !== null,
                'nro_operacion_caja' => $nroOpCaja,
                'estado'             => 'ACTIVO',
                'observaciones'      => trim((string) $request->input('observaciones', '')),
                'cod_usuarios'       => Auth::user()->cod_usuarios,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'gasto'   => $gasto->load('categoria'),
                'message' => "Gasto {$gasto->codigo_gasto} registrado correctamente.",
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar gasto: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Crea una nueva categoría de gasto operativo.
     */
    public function storeCategoria(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo'   => 'nullable|in:FIJO,VARIABLE,TALLER,IMPUESTOS,PERSONAL',
        ]);

        $cat = GastoCategoria::create([
            'nombre' => trim($request->input('nombre')),
            'tipo'   => $request->input('tipo', 'VARIABLE'),
            'icono'  => $request->input('icono', 'fas fa-receipt'),
            'color'  => $request->input('color', '#0a4d36'),
            'activo' => true,
        ]);

        return response()->json([
            'success'   => true,
            'categoria' => $cat,
            'message'   => 'Categoría de gasto creada.',
        ]);
    }

    /**
     * Anula un gasto operativo.
     */
    public function anularGasto($id)
    {
        $gasto = GastoOperativo::findOrFail($id);
        $gasto->estado = 'ANULADO';
        $gasto->save();

        return response()->json([
            'success' => true,
            'message' => "Gasto {$gasto->codigo_gasto} anulado.",
        ]);
    }

    /**
     * Registra una nueva Cuenta a Pagar a Proveedor (soporta 1 o múltiples cuotas automáticas).
     */
    public function storeCtaPagar(Request $request)
    {
        if (!Auth::user()->tieneAccion('finanzas_cuentas_pagar')) {
            return response()->json([
                'success' => false,
                'message' => 'No cuenta con autorización para registrar cuentas a pagar.',
            ], 403);
        }

        $request->validate([
            'PROVEEDOR_cod'     => 'required|integer',
            'concepto'          => 'required|string|max:255',
            'monto_total'       => 'required|numeric|min:1',
            'fecha_vencimiento' => 'required|date',
        ], [
            'PROVEEDOR_cod.required'     => 'Seleccioná el proveedor acreedor.',
            'concepto.required'          => 'Ingresá el concepto o detalle de la factura.',
            'monto_total.required'       => 'Ingresá el monto total de la obligación.',
            'fecha_vencimiento.required' => 'Indicá la fecha del primer vencimiento.',
        ]);

        DB::beginTransaction();
        try {
            $provCod = (int) $request->input('PROVEEDOR_cod');
            $sucCod = (int) ($request->input('suc_cod') ?: 1);
            $nroFact = trim((string) $request->input('nro_factura_proveedor', 'S/N'));
            $conceptoBase = trim($request->input('concepto'));
            $montoTotal = (float) $request->input('monto_total');
            $cantCuotas = max(1, min(36, (int) $request->input('cant_cuotas', 1)));
            $intervaloDias = max(1, (int) $request->input('intervalo_dias', 30));
            $fechaEmision = $request->input('fecha_emision', date('Y-m-d'));
            $primerVenc = $request->input('fecha_vencimiento');

            $montoPorCuota = round($montoTotal / $cantCuotas);
            $acumulado = 0;

            for ($i = 1; $i <= $cantCuotas; $i++) {
                $montoCuota = ($i === $cantCuotas) ? ($montoTotal - $acumulado) : $montoPorCuota;
                $acumulado += $montoCuota;

                $diasSumar = ($i - 1) * $intervaloDias;
                $vencCuota = ($i === 1)
                    ? $primerVenc
                    : date('Y-m-d', strtotime("+{$diasSumar} days", strtotime($primerVenc)));

                $nextId = (int) (CtaPagar::max('id') ?: 0) + 1;
                $codCta = 'CP-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
                while (CtaPagar::where('codigo_cta', $codCta)->exists()) {
                    $nextId++;
                    $codCta = 'CP-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
                }

                $conceptoCuota = $cantCuotas > 1
                    ? "{$conceptoBase} (Cuota {$i}/{$cantCuotas})"
                    : $conceptoBase;

                CtaPagar::create([
                    'codigo_cta'            => $codCta,
                    'PROVEEDOR_cod'         => $provCod,
                    'suc_cod'               => $sucCod,
                    'nro_factura_proveedor' => $nroFact,
                    'concepto'              => $conceptoCuota,
                    'fecha_emision'         => $fechaEmision,
                    'fecha_vencimiento'     => $vencCuota,
                    'nro_cuota'             => $i,
                    'total_cuotas'          => $cantCuotas,
                    'monto_original'        => $montoCuota,
                    'monto_pagado'          => 0,
                    'monto_saldo'           => $montoCuota,
                    'estado'                => 'PENDIENTE',
                    'observaciones'         => trim((string) $request->input('observaciones', '')),
                    'cod_usuarios'          => Auth::user()->cod_usuarios,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $cantCuotas > 1
                    ? "Se generaron {$cantCuotas} cuotas de Cuenta a Pagar exitosamente."
                    : 'Cuenta a Pagar a proveedor registrada correctamente.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar cuenta a pagar: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Detalle de una Cuenta a Pagar con su historial de pagos/abonos.
     */
    public function showCtaPagar($id)
    {
        $cta = CtaPagar::with([
            'proveedor',
            'sucursal',
            'pagos.usuario:cod_usuarios,nom_usuarios',
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'cuenta'  => $cta,
        ]);
    }

    /**
     * Registra un pago total o abono parcial sobre una Cuenta a Pagar a Proveedor.
     */
    public function pagarCtaPagar(Request $request, $id)
    {
        if (!Auth::user()->tieneAccion('finanzas_cuentas_pagar')) {
            return response()->json([
                'success' => false,
                'message' => 'No cuenta con autorización para registrar pagos a proveedores.',
            ], 403);
        }

        $cta = CtaPagar::with('proveedor')->findOrFail($id);

        $request->validate([
            'monto' => 'required|numeric|min:1',
        ]);

        $monto = (float) $request->input('monto');
        if ($monto > ((float) $cta->monto_saldo + 1)) {
            return response()->json([
                'success' => false,
                'message' => 'El monto a pagar supera el saldo pendiente de la factura (Gs. ' . number_format($cta->monto_saldo, 0, ',', '.') . ').',
            ], 422);
        }

        DB::beginTransaction();
        try {
            $registrarCaja = (bool) $request->input('registrar_caja', false);
            $nroOpCaja = null;
            $provNom = $cta->proveedor ? $cta->proveedor->proveedor_nombre : 'Proveedor';

            if ($registrarCaja && $monto > 0) {
                $nroOpCaja = $this->registrarSalidaCaja(
                    $cta->suc_cod,
                    $monto,
                    "Pago a Proveedor {$provNom} ({$cta->codigo_cta} - Fact. {$cta->nro_factura_proveedor})"
                );
            }

            $pago = CtaPagarPago::create([
                'cta_pagar_id'           => $cta->id,
                'PROVEEDOR_cod'          => $cta->PROVEEDOR_cod,
                'suc_cod'                => $cta->suc_cod,
                'fecha_pago'             => date('Y-m-d H:i:s'),
                'monto'                  => $monto,
                'forma_pago'             => (int) $request->input('forma_pago', 1),
                'nro_recibo_comprobante' => trim((string) $request->input('nro_recibo_comprobante', '')),
                'registrar_caja'         => $nroOpCaja !== null,
                'nro_operacion_caja'     => $nroOpCaja,
                'observacion'            => trim((string) $request->input('observacion', 'Pago de factura a proveedor')),
                'cod_usuarios'           => Auth::user()->cod_usuarios,
            ]);

            $cta->recalcularSaldo();

            DB::commit();

            return response()->json([
                'success' => true,
                'cuenta'  => $cta->fresh(['proveedor', 'pagos.usuario']),
                'pago_id' => $pago->id,
                'message' => 'Pago a proveedor registrado correctamente.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar pago: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Anula una Cuenta a Pagar.
     */
    public function anularCtaPagar($id)
    {
        $cta = CtaPagar::findOrFail($id);
        $cta->estado = 'ANULADO';
        $cta->save();

        return response()->json([
            'success' => true,
            'message' => "Cuenta a Pagar {$cta->codigo_cta} anulada.",
        ]);
    }

    /**
     * Imprime el Informe Oficial de Estado de Resultados (Ganancia Neta Real) en A4.
     */
    public function pdfEstadoResultados(Request $request)
    {
        $desde = $request->get('desde', date('Y-m-01'));
        $hasta = $request->get('hasta', date('Y-m-d'));
        $sucCod = $request->get('suc_cod', '0');

        $resultados = $this->calcularEstadoResultadosInterno($desde, $hasta, $sucCod);
        $empresa = Empresa::first();
        $sucursal = (!empty($sucCod) && $sucCod != '0') ? Sucursal::find($sucCod) : null;

        return view('pdf.finanzas_resultados', compact('resultados', 'empresa', 'sucursal'));
    }

    protected function registrarSalidaCaja($sucCod, $monto, $concepto)
    {
        $apertura = Apertura::where('suc_cod', $sucCod)
            ->where('apert_estado', '1')
            ->orderBy('nro_operacion', 'desc')
            ->first();

        if (!$apertura) {
            return null;
        }

        $mov = new MovimientoCaja();
        $mov->nro_operacion   = $apertura->nro_operacion;
        $mov->mov_fecha       = date('Y-m-d H:i');
        $mov->mov_concepto    = mb_substr($concepto, 0, 250);
        $mov->mov_tipo        = 'Salida';
        $mov->mov_monto       = $monto;
        $mov->nro_fact_ventas = '-';
        $mov->suc_cod         = $sucCod;
        $mov->save();

        return $apertura->nro_operacion;
    }
}
