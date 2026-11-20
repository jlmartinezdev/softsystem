<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ComisionConfigUsuario;
use App\ComisionLiquidacion;
use App\ComisionLiquidacionDetalle;
use App\Reparacion;
use App\ReparacionGarantia;
use App\CelularImei;
use App\Venta;
use App\Cobro;
use App\User;
use App\Sucursal;
use App\Empresa;
use App\Apertura;
use App\MovimientoCaja;
use App\Support\ComisionSettings;
use App\Support\ComisionSchema;
use DB;
use Auth;
use Exception;

class ComisionController extends Controller
{
    public function __construct()
    {
        ComisionSchema::ensureExists();

        $this->middleware('auth');

        // Verificar si el módulo está habilitado por el Superadministrador
        $this->middleware(function ($request, $next) {
            $actionMethod = $request->route() ? $request->route()->getActionMethod() : '';
            if ($actionMethod === 'toggleModulo') {
                return $next($request);
            }

            if (!ComisionSettings::isModuloActivo() && !Auth::user()->esSuperAdministrador()) {
                $msg = 'El módulo de Comisiones de Técnicos y Vendedores se encuentra deshabilitado. Solo el Superadministrador del sistema puede habilitarlo.';
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

        $this->middleware('permiso:comisiones,open')->only([
            'index', 'calcularPendientes', 'showLiquidacion'
        ]);
        $this->middleware('permiso:comisiones,add')->only([
            'storeLiquidacion'
        ]);
        $this->middleware('permiso:comisiones,edit')->only([
            'guardarConfigUsuario'
        ]);
        $this->middleware('permiso:comisiones,del')->only([
            'anularLiquidacion'
        ]);
        $this->middleware('permiso:comisiones,export')->only([
            'pdfLiquidacion'
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
            : !ComisionSettings::isModuloActivo();

        $activo = ComisionSettings::setModuloActivo($nuevoEstado);

        return response()->json([
            'success' => true,
            'activo'  => $activo,
            'message' => $activo
                ? 'Módulo de Comisiones de Técnicos y Vendedores HABILITADO.'
                : 'Módulo de Comisiones de Técnicos y Vendedores DESHABILITADO.',
        ]);
    }

    /**
     * Asegura que cada usuario activo tenga su fila de configuración de comisiones.
     */
    protected function sincronizarConfiguracionesUsuarios()
    {
        $defaults = ComisionSettings::all();
        $usuarios = User::where('activo', 1)->sinSuperAdmin()->get();

        foreach ($usuarios as $u) {
            $exists = ComisionConfigUsuario::where('cod_usuarios', $u->cod_usuarios)->exists();
            if (!$exists) {
                ComisionConfigUsuario::create([
                    'cod_usuarios'               => $u->cod_usuarios,
                    'activo_comision'            => true,
                    'comisiona_taller'           => true,
                    'porc_taller'                => $defaults['porc_taller_default'],
                    'base_taller'                => $defaults['base_taller_default'],
                    'descontar_garantias_taller' => true,
                    'comisiona_ventas'           => true,
                    'porc_venta_contado'         => $defaults['porc_venta_contado_default'],
                    'porc_venta_credito'         => $defaults['porc_venta_credito_default'],
                    'comisiona_imei'             => true,
                    'porc_imei'                  => $defaults['porc_imei_default'],
                    'base_imei'                  => $defaults['base_imei_default'],
                    'bono_fijo_imei'             => 0,
                    'comisiona_cobranzas'        => true,
                    'porc_cobranzas'             => $defaults['porc_cobranza_default'],
                    'sueldo_base_referencia'     => 0,
                ]);
            }
        }
    }

    /**
     * Vista principal y listado de liquidaciones / configuraciones por empleado.
     */
    public function index(Request $request)
    {
        $this->sincronizarConfiguracionesUsuarios();

        $user = Auth::user();
        $puedeVerTodos = $user->esAdministrador() || $user->tieneAccion('comision_ver_todos');

        if ($request->ajax() || $request->wantsJson()) {
            $codUsuarioFiltro = $request->get('cod_usuarios', '0');
            $estadoFiltro = $request->get('estado', 'TODOS');
            $desde = $request->get('desde', '');
            $hasta = $request->get('hasta', '');

            $liqQuery = ComisionLiquidacion::with([
                'empleado:cod_usuarios,nom_usuarios,user_usuarios',
                'liquidador:cod_usuarios,nom_usuarios',
                'sucursal:suc_cod,suc_desc',
            ])->withCount('detalles');

            if (!$puedeVerTodos) {
                $liqQuery->where('cod_usuarios', $user->cod_usuarios);
            } elseif (!empty($codUsuarioFiltro) && $codUsuarioFiltro != '0') {
                $liqQuery->where('cod_usuarios', (int) $codUsuarioFiltro);
            }

            if (!empty($estadoFiltro) && $estadoFiltro !== 'TODOS') {
                $liqQuery->where('estado', $estadoFiltro);
            }
            if (!empty($desde) && !empty($hasta)) {
                $liqQuery->whereBetween(DB::raw('DATE(fecha_liquidacion)'), [$desde, $hasta]);
            }

            $liquidaciones = $liqQuery->orderBy('id', 'desc')->paginate(20);

            // KPIs del mes actual
            $mesActual = date('Y-m');
            $kpiBase = ComisionLiquidacion::where('estado', 'PAGADO');
            if (!$puedeVerTodos) {
                $kpiBase->where('cod_usuarios', $user->cod_usuarios);
            }

            $kpis = [
                'total_pagado_mes'   => (float) (clone $kpiBase)->whereRaw("DATE_FORMAT(fecha_liquidacion, '%Y-%m') = ?", [$mesActual])->sum('total_neto_pagar'),
                'liquidaciones_mes'  => (clone $kpiBase)->whereRaw("DATE_FORMAT(fecha_liquidacion, '%Y-%m') = ?", [$mesActual])->count(),
                'total_taller_mes'   => (float) (clone $kpiBase)->whereRaw("DATE_FORMAT(fecha_liquidacion, '%Y-%m') = ?", [$mesActual])->sum('comision_taller'),
                'total_ventas_mes'   => (float) (clone $kpiBase)->whereRaw("DATE_FORMAT(fecha_liquidacion, '%Y-%m') = ?", [$mesActual])->sum(DB::raw('comision_ventas + comision_imei + comision_cobranzas')),
            ];

            return response()->json([
                'liquidaciones' => $liquidaciones,
                'kpis'          => $kpis,
            ]);
        }

        $empleadosQuery = User::leftJoin('roles', 'usuarios.cod_rol', '=', 'roles.cod_rol')
            ->where('usuarios.activo', 1)
            ->sinSuperAdmin()
            ->select('usuarios.cod_usuarios', 'usuarios.nom_usuarios', 'usuarios.user_usuarios', 'roles.nom_rol');

        if (!$puedeVerTodos) {
            $empleadosQuery->where('usuarios.cod_usuarios', $user->cod_usuarios);
        }

        $empleados = $empleadosQuery->orderBy('usuarios.nom_usuarios')->get();
        $configsUsuarios = ComisionConfigUsuario::with('usuario.roles')
            ->when(!$puedeVerTodos, function ($q) use ($user) {
                $q->where('cod_usuarios', $user->cod_usuarios);
            })
            ->get()
            ->keyBy('cod_usuarios');

        $sucursales = Sucursal::all();
        $empresa = Empresa::first();
        $esAdministrador = $user->esAdministrador();
        $esSuperAdministrador = $user->esSuperAdministrador();
        $sucursalActual = session('sucursal_actual') ?? ($user->suc_cod ?? ($sucursales->first()->suc_cod ?? 1));
        $comisionConfig = ComisionSettings::all();

        $permisosModulo = [
            'open'                => $user->tienePermiso('comisiones', 'open'),
            'add'                 => $user->tienePermiso('comisiones', 'add'),
            'edit'                => $user->tienePermiso('comisiones', 'edit'),
            'del'                 => $user->tienePermiso('comisiones', 'del'),
            'export'              => $user->tienePermiso('comisiones', 'export'),
            'configurar_tasas'    => $user->tieneAccion('comision_configurar_tasas'),
            'generar_liquidacion' => $user->tieneAccion('comision_generar_liquidacion'),
            'ver_todos'           => $puedeVerTodos,
        ];

        return view('comisiones', compact(
            'empleados',
            'configsUsuarios',
            'sucursales',
            'empresa',
            'esAdministrador',
            'esSuperAdministrador',
            'sucursalActual',
            'comisionConfig',
            'permisosModulo'
        ));
    }

    /**
     * Actualiza los porcentajes y reglas de comisión de un Técnico / Vendedor.
     */
    public function guardarConfigUsuario(Request $request, $cod_usuarios)
    {
        if (!Auth::user()->tieneAccion('comision_configurar_tasas')) {
            return response()->json([
                'success' => false,
                'message' => 'No cuenta con autorización para modificar porcentajes de comisión.',
            ], 403);
        }

        $cfg = ComisionConfigUsuario::firstOrNew(['cod_usuarios' => (int) $cod_usuarios]);
        $cfg->activo_comision            = (bool) $request->input('activo_comision', true);
        $cfg->comisiona_taller           = (bool) $request->input('comisiona_taller', true);
        $cfg->porc_taller                = max(0, min(100, (float) $request->input('porc_taller', 40)));
        $cfg->base_taller                = in_array($request->input('base_taller'), ['MANO_OBRA', 'UTILIDAD_NETA', 'TOTAL_ORDEN'])
            ? $request->input('base_taller')
            : 'MANO_OBRA';
        $cfg->descontar_garantias_taller = (bool) $request->input('descontar_garantias_taller', true);

        $cfg->comisiona_ventas           = (bool) $request->input('comisiona_ventas', true);
        $cfg->porc_venta_contado         = max(0, min(100, (float) $request->input('porc_venta_contado', 3)));
        $cfg->porc_venta_credito         = max(0, min(100, (float) $request->input('porc_venta_credito', 2)));

        $cfg->comisiona_imei             = (bool) $request->input('comisiona_imei', true);
        $cfg->porc_imei                  = max(0, min(100, (float) $request->input('porc_imei', 5)));
        $cfg->base_imei                  = in_array($request->input('base_imei'), ['UTILIDAD_NETA', 'PRECIO_VENTA'])
            ? $request->input('base_imei')
            : 'UTILIDAD_NETA';
        $cfg->bono_fijo_imei             = max(0, (float) $request->input('bono_fijo_imei', 0));

        $cfg->comisiona_cobranzas        = (bool) $request->input('comisiona_cobranzas', true);
        $cfg->porc_cobranzas             = max(0, min(100, (float) $request->input('porc_cobranzas', 2)));
        $cfg->sueldo_base_referencia     = max(0, (float) $request->input('sueldo_base_referencia', 0));
        $cfg->observaciones              = trim((string) $request->input('observaciones', ''));
        $cfg->save();

        return response()->json([
            'success' => true,
            'config'  => $cfg,
            'message' => 'Esquema de comisiones actualizado correctamente.',
        ]);
    }

    /**
     * Calcula todas las operaciones pendientes de liquidar para un Técnico / Vendedor en un rango de fechas.
     */
    public function calcularPendientes(Request $request)
    {
        $user = Auth::user();
        $puedeVerTodos = $user->esAdministrador() || $user->tieneAccion('comision_ver_todos');

        $codUsuario = (int) $request->get('cod_usuarios', $user->cod_usuarios);
        if (!$puedeVerTodos && $codUsuario !== (int) $user->cod_usuarios) {
            $codUsuario = (int) $user->cod_usuarios;
        }

        $desde = $request->get('desde', date('Y-m-01'));
        $hasta = $request->get('hasta', date('Y-m-d'));
        $sucCod = $request->get('suc_cod', '0');

        $this->sincronizarConfiguracionesUsuarios();
        $cfg = ComisionConfigUsuario::where('cod_usuarios', $codUsuario)->first();
        $empleado = User::find($codUsuario);

        if (!$cfg || !$empleado) {
            return response()->json([
                'success' => false,
                'message' => 'Empleado no encontrado.',
            ], 404);
        }

        // Obtener IDs ya liquidados (en liquidaciones no anuladas)
        $liquidadosRaw = DB::table('comision_liquidacion_detalles as d')
            ->join('comision_liquidaciones as l', 'd.liquidacion_id', '=', 'l.id')
            ->where('l.estado', '!=', 'ANULADO')
            ->select('d.origen_modulo', 'd.referencia_id')
            ->get();

        $yaLiquidados = [
            'TALLER'      => [],
            'EQUIPO_IMEI' => [],
            'VENTA_POS'   => [],
            'COBRANZA'    => [],
        ];
        foreach ($liquidadosRaw as $lr) {
            $yaLiquidados[$lr->origen_modulo][(int) $lr->referencia_id] = true;
        }

        $items = [];
        $facturasYaComisionadas = [];
        $descuentoGarantiasSugerido = 0;

        // =========================================================================
        // 1. SERVICIO TÉCNICO / REPARACIONES ENTREGADAS Y COBRADAS (TALLER)
        // =========================================================================
        if ($cfg->comisiona_taller) {
            $repQuery = Reparacion::with('cliente:CLIENTES_cod,cliente_nombre')
                ->where('cod_usuario_tecnico', $codUsuario)
                ->where('estado', 'ENTREGADO')
                ->where('estado_pago', 'PAGADO')
                ->where('es_reingreso_garantia', 0)
                ->whereBetween(DB::raw('DATE(COALESCE(fecha_entrega, fecha_reparado, fecha_recepcion))'), [$desde, $hasta]);

            if (!empty($sucCod) && $sucCod != '0') {
                $repQuery->where('suc_cod', $sucCod);
            }

            $reparaciones = $repQuery->orderBy('id', 'asc')->get();

            foreach ($reparaciones as $rep) {
                if (!empty($yaLiquidados['TALLER'][$rep->id])) {
                    continue;
                }
                if (!empty($rep->nro_fact_ventas)) {
                    $facturasYaComisionadas[(int) $rep->nro_fact_ventas] = true;
                }

                $manoObraNeta = max(0, (float) $rep->costo_mano_obra - (float) $rep->descuento);
                $utilidadNeta = max(0, (float) $rep->total_general - (float) $rep->costo_repuestos);
                $totalOrden = max(0, (float) $rep->total_general);

                if ($cfg->base_taller === 'UTILIDAD_NETA') {
                    $base = $utilidadNeta;
                } elseif ($cfg->base_taller === 'TOTAL_ORDEN') {
                    $base = $totalOrden;
                } else {
                    $base = $manoObraNeta;
                }

                $comision = round($base * ((float) $cfg->porc_taller / 100));
                if ($comision <= 0 && $base <= 0) {
                    continue;
                }

                $clienteNom = $rep->cliente ? $rep->cliente->cliente_nombre : 'Cliente';
                $items[] = [
                    'incluido'            => true,
                    'origen_modulo'       => 'TALLER',
                    'referencia_id'       => $rep->id,
                    'codigo_comprobante'  => $rep->codigo_orden,
                    'fecha_operacion'     => $rep->fecha_entrega ?: ($rep->fecha_reparado ?: $rep->fecha_recepcion),
                    'descripcion'         => "Reparación {$rep->marca} {$rep->modelo} ({$clienteNom})",
                    'monto_operacion'     => (float) $rep->total_general,
                    'costo_operacion'     => (float) $rep->costo_repuestos,
                    'base_comisionable'   => $base,
                    'porcentaje_aplicado' => (float) $cfg->porc_taller,
                    'bono_fijo'           => 0,
                    'monto_comision'      => $comision,
                ];
            }

            // Verificar si hubo costos asumidos por garantías de órdenes de este técnico en el período
            if ($cfg->descontar_garantias_taller) {
                $descuentoGarantiasSugerido = (float) ReparacionGarantia::join('reparaciones as r', 'reparacion_garantias.reparacion_id', '=', 'r.id')
                    ->where('r.cod_usuario_tecnico', $codUsuario)
                    ->where('reparacion_garantias.cubre_garantia', 1)
                    ->whereBetween(DB::raw('DATE(reparacion_garantias.fecha_reclamo)'), [$desde, $hasta])
                    ->sum('reparacion_garantias.costo_asumido_taller');
            }
        }

        // =========================================================================
        // 2. VENTA DE EQUIPOS POR IMEI / PLAN CANJE (EQUIPO_IMEI)
        // =========================================================================
        if ($cfg->comisiona_imei) {
            $imeiQuery = CelularImei::with('clienteComprador:CLIENTES_cod,cliente_nombre')
                ->where('cod_usuario_venta', $codUsuario)
                ->where('estado', 'VENDIDO')
                ->whereBetween(DB::raw('DATE(fecha_venta)'), [$desde, $hasta]);

            if (!empty($sucCod) && $sucCod != '0') {
                $imeiQuery->where('suc_cod', $sucCod);
            }

            $equiposVendidos = $imeiQuery->orderBy('id', 'asc')->get();

            foreach ($equiposVendidos as $eq) {
                if (!empty($yaLiquidados['EQUIPO_IMEI'][$eq->id])) {
                    continue;
                }
                if (!empty($eq->nro_fact_ventas)) {
                    $facturasYaComisionadas[(int) $eq->nro_fact_ventas] = true;
                }

                $precioVentaReal = max(0, ((float) $eq->precio_vendido ?: (float) $eq->precio_venta_contado) - (float) $eq->descuento_venta);
                $utilidadEquipo = max(0, $precioVentaReal - (float) $eq->costo_total);

                $base = ($cfg->base_imei === 'PRECIO_VENTA') ? $precioVentaReal : $utilidadEquipo;
                $bonoFijo = (float) $cfg->bono_fijo_imei;
                $comision = round($base * ((float) $cfg->porc_imei / 100)) + $bonoFijo;

                if ($comision <= 0) {
                    continue;
                }

                $compradorNom = $eq->clienteComprador ? $eq->clienteComprador->cliente_nombre : 'Comprador';
                $items[] = [
                    'incluido'            => true,
                    'origen_modulo'       => 'EQUIPO_IMEI',
                    'referencia_id'       => $eq->id,
                    'codigo_comprobante'  => $eq->codigo_interno,
                    'fecha_operacion'     => $eq->fecha_venta,
                    'descripcion'         => "Equipo {$eq->marca} {$eq->modelo} {$eq->capacidad_gb} (IMEI {$eq->imei_1} - {$compradorNom})",
                    'monto_operacion'     => $precioVentaReal,
                    'costo_operacion'     => (float) $eq->costo_total,
                    'base_comisionable'   => $base,
                    'porcentaje_aplicado' => (float) $cfg->porc_imei,
                    'bono_fijo'           => $bonoFijo,
                    'monto_comision'      => $comision,
                ];
            }
        }

        // =========================================================================
        // 3. VENTAS GENERALES EN PUNTO DE VENTA (VENTA_POS)
        // =========================================================================
        if ($cfg->comisiona_ventas) {
            $ventasQuery = DB::table('ventas as v')
                ->leftJoin('clientes as c', 'v.clientes_cod', '=', 'c.CLIENTES_cod')
                ->where('v.cod_usuarios', $codUsuario)
                ->where('v.venta_estado', '!=', '0')
                ->whereBetween(DB::raw('DATE(v.venta_fecha)'), [$desde, $hasta])
                ->select(
                    'v.nro_fact_ventas',
                    'v.venta_fecha',
                    'v.venta_total',
                    'v.tipo_factura',
                    'v.documento',
                    'v.suc_cod',
                    'c.cliente_nombre'
                );

            if (!empty($sucCod) && $sucCod != '0') {
                $ventasQuery->where('v.suc_cod', $sucCod);
            }

            $ventasPos = $ventasQuery->orderBy('v.nro_fact_ventas', 'asc')->get();

            foreach ($ventasPos as $v) {
                $nroVenta = (int) $v->nro_fact_ventas;
                if (!empty($yaLiquidados['VENTA_POS'][$nroVenta]) || !empty($facturasYaComisionadas[$nroVenta])) {
                    continue;
                }

                $esCredito = ((string) $v->tipo_factura === '2');
                $porc = $esCredito ? (float) $cfg->porc_venta_credito : (float) $cfg->porc_venta_contado;
                if ($porc <= 0) {
                    continue;
                }

                $base = max(0, (float) $v->venta_total);
                $comision = round($base * ($porc / 100));
                if ($comision <= 0) {
                    continue;
                }

                $condTexto = $esCredito ? 'Crédito' : 'Contado';
                $items[] = [
                    'incluido'            => true,
                    'origen_modulo'       => 'VENTA_POS',
                    'referencia_id'       => $nroVenta,
                    'codigo_comprobante'  => "Venta #{$nroVenta}",
                    'fecha_operacion'     => $v->venta_fecha,
                    'descripcion'         => "Venta POS {$condTexto} ({$v->documento}) — " . ($v->cliente_nombre ?: 'Cliente Ocasional'),
                    'monto_operacion'     => $base,
                    'costo_operacion'     => 0,
                    'base_comisionable'   => $base,
                    'porcentaje_aplicado' => $porc,
                    'bono_fijo'           => 0,
                    'monto_comision'      => $comision,
                ];
            }
        }

        // =========================================================================
        // 4. COBRANZAS DE CUOTAS RECUPERADAS (COBRANZA)
        // =========================================================================
        if ($cfg->comisiona_cobranzas && (float) $cfg->porc_cobranzas > 0) {
            $cobQuery = DB::table('cobranzas as cb')
                ->join('cobranza_detalle as dc', 'cb.cc_numero', '=', 'dc.cc_numero')
                ->join('ventas as v', 'dc.nro_fact_ventas', '=', 'v.nro_fact_ventas')
                ->leftJoin('clientes as c', 'v.clientes_cod', '=', 'c.CLIENTES_cod')
                ->leftJoin('apert_cierres_caja as ap', 'cb.nro_operacion', '=', 'ap.nro_operacion')
                ->where('cb.estado', 'N')
                ->whereBetween(DB::raw('DATE(cb.cob_fecha)'), [$desde, $hasta])
                ->where(function ($w) use ($codUsuario) {
                    $w->where('ap.cod_usuarios', $codUsuario)
                      ->orWhere(function ($w2) use ($codUsuario) {
                          $w2->whereNull('ap.cod_usuarios')
                             ->where('v.cod_usuarios', $codUsuario);
                      });
                })
                ->select(
                    'cb.cc_numero',
                    'cb.cob_fecha',
                    'cb.cob_importe',
                    'cb.nro_recibo',
                    'cb.suc_cod',
                    'c.cliente_nombre'
                )
                ->groupBy('cb.cc_numero');

            if (!empty($sucCod) && $sucCod != '0') {
                $cobQuery->where('cb.suc_cod', $sucCod);
            }

            $cobros = $cobQuery->orderBy('cb.cc_numero', 'asc')->get();

            foreach ($cobros as $cb) {
                $ccNum = (int) $cb->cc_numero;
                if (!empty($yaLiquidados['COBRANZA'][$ccNum])) {
                    continue;
                }

                $base = max(0, (float) $cb->cob_importe);
                $comision = round($base * ((float) $cfg->porc_cobranzas / 100));
                if ($comision <= 0) {
                    continue;
                }

                $items[] = [
                    'incluido'            => true,
                    'origen_modulo'       => 'COBRANZA',
                    'referencia_id'       => $ccNum,
                    'codigo_comprobante'  => "Cobro #{$ccNum}",
                    'fecha_operacion'     => $cb->cob_fecha,
                    'descripcion'         => "Cobranza de Cuota Recibo #{$cb->nro_recibo} — " . ($cb->cliente_nombre ?: 'Cliente'),
                    'monto_operacion'     => $base,
                    'costo_operacion'     => 0,
                    'base_comisionable'   => $base,
                    'porcentaje_aplicado' => (float) $cfg->porc_cobranzas,
                    'bono_fijo'           => 0,
                    'monto_comision'      => $comision,
                ];
            }
        }

        return response()->json([
            'success'                      => true,
            'empleado'                     => $empleado,
            'config'                       => $cfg,
            'items'                        => $items,
            'descuento_garantias_sugerido' => $descuentoGarantiasSugerido,
        ]);
    }

    /**
     * Genera el código correlativo de liquidación (ej. LIQ-000001).
     */
    protected function generarCodigoLiquidacion($nextId = null)
    {
        $cfg = ComisionSettings::all();
        $prefijo = $cfg['prefijo_liquidacion'] ?: 'LIQ-';
        if (!$nextId) {
            $nextId = (int) (ComisionLiquidacion::max('id') ?: 0) + 1;
        }
        $cod = $prefijo . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        while (ComisionLiquidacion::where('codigo_liquidacion', $cod)->exists()) {
            $nextId++;
            $cod = $prefijo . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        }
        return $cod;
    }

    /**
     * Guarda, cierra y paga una Liquidación de Comisiones (impactando opcionalmente en Salida de Caja).
     */
    public function storeLiquidacion(Request $request)
    {
        if (!Auth::user()->tieneAccion('comision_generar_liquidacion')) {
            return response()->json([
                'success' => false,
                'message' => 'No cuenta con autorización para emitir y pagar liquidaciones de comisiones.',
            ], 403);
        }

        $request->validate([
            'cod_usuarios' => 'required|integer',
            'fecha_desde'  => 'required|date',
            'fecha_hasta'  => 'required|date',
            'items'        => 'required|array|min:1',
        ], [
            'items.required' => 'La liquidación debe incluir al menos una operación comisionable.',
        ]);

        $itemsInput = array_filter($request->input('items', []), function ($it) {
            return !empty($it['incluido']);
        });

        if (empty($itemsInput)) {
            return response()->json([
                'success' => false,
                'message' => 'Marcá al menos una operación para incluir en la liquidación.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            $codUsuario = (int) $request->input('cod_usuarios');
            $empleado = User::findOrFail($codUsuario);
            $sucCod = (int) ($request->input('suc_cod') ?: 1);

            $cantRep = 0; $baseTaller = 0; $comTaller = 0;
            $cantVentas = 0; $baseVentas = 0; $comVentas = 0;
            $cantImei = 0; $baseImei = 0; $comImei = 0;
            $cantCob = 0; $baseCob = 0; $comCob = 0;

            foreach ($itemsInput as $it) {
                $mod = $it['origen_modulo'] ?? 'VENTA_POS';
                $b = max(0, (float) ($it['base_comisionable'] ?? 0));
                $c = max(0, (float) ($it['monto_comision'] ?? 0));

                if ($mod === 'TALLER') {
                    $cantRep++;
                    $baseTaller += $b;
                    $comTaller += $c;
                } elseif ($mod === 'EQUIPO_IMEI') {
                    $cantImei++;
                    $baseImei += $b;
                    $comImei += $c;
                } elseif ($mod === 'COBRANZA') {
                    $cantCob++;
                    $baseCob += $b;
                    $comCob += $c;
                } else {
                    $cantVentas++;
                    $baseVentas += $b;
                    $comVentas += $c;
                }
            }

            $subtotalComisiones = $comTaller + $comVentas + $comImei + $comCob;
            $bono = max(0, (float) $request->input('bono_premio', 0));
            $descuento = max(0, (float) $request->input('descuento_adelanto', 0));
            $totalNeto = max(0, ($subtotalComisiones + $bono) - $descuento);

            $registrarCaja = (bool) $request->input('registrar_caja', false);
            $nroOpCaja = null;
            $codLiq = $this->generarCodigoLiquidacion();

            if ($registrarCaja && $totalNeto > 0) {
                $apertura = Apertura::where('suc_cod', $sucCod)
                    ->where('apert_estado', '1')
                    ->orderBy('nro_operacion', 'desc')
                    ->first();

                if ($apertura) {
                    $mov = new MovimientoCaja();
                    $mov->nro_operacion   = $apertura->nro_operacion;
                    $mov->mov_fecha       = date('Y-m-d H:i');
                    $mov->mov_concepto    = mb_substr("Pago Liquidación de Comisiones {$codLiq} - {$empleado->nom_usuarios}", 0, 250);
                    $mov->mov_tipo        = 'Salida';
                    $mov->mov_monto       = $totalNeto;
                    $mov->nro_fact_ventas = '-';
                    $mov->suc_cod         = $sucCod;
                    $mov->save();
                    $nroOpCaja = $apertura->nro_operacion;
                }
            }

            $liq = new ComisionLiquidacion();
            $liq->codigo_liquidacion     = $codLiq;
            $liq->cod_usuarios           = $codUsuario;
            $liq->suc_cod                = $sucCod;
            $liq->fecha_desde            = $request->input('fecha_desde');
            $liq->fecha_hasta            = $request->input('fecha_hasta');
            $liq->fecha_liquidacion      = date('Y-m-d H:i:s');
            $liq->cant_reparaciones      = $cantRep;
            $liq->total_base_taller      = $baseTaller;
            $liq->comision_taller        = $comTaller;
            $liq->cant_ventas_pos        = $cantVentas;
            $liq->total_base_ventas      = $baseVentas;
            $liq->comision_ventas        = $comVentas;
            $liq->cant_equipos_imei      = $cantImei;
            $liq->total_base_imei        = $baseImei;
            $liq->comision_imei          = $comImei;
            $liq->cant_cobranzas         = $cantCob;
            $liq->total_base_cobranzas   = $baseCob;
            $liq->comision_cobranzas     = $comCob;
            $liq->subtotal_comisiones    = $subtotalComisiones;
            $liq->bono_premio            = $bono;
            $liq->motivo_bono            = trim((string) $request->input('motivo_bono', ''));
            $liq->descuento_adelanto     = $descuento;
            $liq->motivo_descuento       = trim((string) $request->input('motivo_descuento', ''));
            $liq->total_neto_pagar       = $totalNeto;
            $liq->estado                 = 'PAGADO';
            $liq->forma_pago             = (int) $request->input('forma_pago', 1);
            $liq->registrar_caja         = $nroOpCaja !== null;
            $liq->nro_operacion_caja     = $nroOpCaja;
            $liq->observaciones          = trim((string) $request->input('observaciones', ''));
            $liq->cod_usuario_liquidador = Auth::user()->cod_usuarios;
            $liq->save();

            foreach ($itemsInput as $it) {
                ComisionLiquidacionDetalle::create([
                    'liquidacion_id'      => $liq->id,
                    'cod_usuarios'        => $codUsuario,
                    'origen_modulo'       => $it['origen_modulo'] ?? 'VENTA_POS',
                    'referencia_id'       => (int) ($it['referencia_id'] ?? 0),
                    'codigo_comprobante'  => $it['codigo_comprobante'] ?? '-',
                    'fecha_operacion'     => !empty($it['fecha_operacion']) ? $it['fecha_operacion'] : null,
                    'descripcion'         => mb_substr((string) ($it['descripcion'] ?? 'Operación'), 0, 250),
                    'monto_operacion'     => (float) ($it['monto_operacion'] ?? 0),
                    'costo_operacion'     => (float) ($it['costo_operacion'] ?? 0),
                    'base_comisionable'   => (float) ($it['base_comisionable'] ?? 0),
                    'porcentaje_aplicado' => (float) ($it['porcentaje_aplicado'] ?? 0),
                    'bono_fijo'           => (float) ($it['bono_fijo'] ?? 0),
                    'monto_comision'      => (float) ($it['monto_comision'] ?? 0),
                ]);
            }

            DB::commit();

            return response()->json([
                'success'            => true,
                'id'                 => $liq->id,
                'codigo_liquidacion' => $liq->codigo_liquidacion,
                'message'            => "Liquidación {$liq->codigo_liquidacion} emitida por Gs. " . number_format($totalNeto, 0, ',', '.'),
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al generar liquidación: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Muestra el detalle completo de una liquidación emitida.
     */
    public function showLiquidacion($id)
    {
        $liq = ComisionLiquidacion::with([
            'empleado',
            'liquidador',
            'sucursal',
            'detalles',
        ])->findOrFail($id);

        return response()->json([
            'success'     => true,
            'liquidacion' => $liq,
        ]);
    }

    /**
     * Anula una liquidación, liberando sus operaciones para poder volver a liquidarlas.
     */
    public function anularLiquidacion($id)
    {
        $liq = ComisionLiquidacion::findOrFail($id);
        $liq->estado = 'ANULADO';
        $liq->save();

        return response()->json([
            'success' => true,
            'message' => "Liquidación {$liq->codigo_liquidacion} anulada. Las operaciones incluidas quedaron liberadas.",
        ]);
    }

    /**
     * Imprime el Recibo Oficial de Liquidación de Comisiones (A4).
     */
    public function pdfLiquidacion($id)
    {
        $liquidacion = ComisionLiquidacion::with([
            'empleado.roles',
            'liquidador',
            'sucursal',
            'detalles',
        ])->findOrFail($id);

        $empresa = Empresa::first();

        return view('pdf.comision_liquidacion', compact('liquidacion', 'empresa'));
    }
}
