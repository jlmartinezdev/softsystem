<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\Venta;
use App\Cobro;
use App\Apertura;
use App\CtaCobrar;
use App\Articulo;
use App\Stock;
use App\Services\SifenService;
use Auth;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $mes = $meses[(int) date('m') - 1];
        $hoy = date('Y-m-d');
        $inicioMes = date('Y-m-01');

        $empresa = Empresa::first();
        $usuario = Auth::user();
        $nombreLocal = trim((string) ($empresa->emp_nombre ?? ''));
        $localIncompleto = $nombreLocal === '' || mb_strtoupper($nombreLocal, 'UTF-8') === 'EMPRESA';

        $esAdministrador = $usuario->esAdministrador();

        $n_ventas = 0;
        $total_ventas_mes = 0;
        $n_ventas_hoy = 0;
        $total_ventas_hoy = 0;
        $n_cobros = 0;
        $total_cobros_mes = 0;
        $saldoCobrar = 0;
        $cuotasVencidas = 0;

        if ($esAdministrador) {
            $ventasMes = Venta::whereBetween(DB::raw('DATE(ventas.venta_fecha)'), [$inicioMes, $hoy])
                ->selectRaw('COUNT(*) as cantidad, COALESCE(SUM(venta_total), 0) as total')
                ->first();

            $ventasHoy = Venta::whereDate('ventas.venta_fecha', $hoy)
                ->selectRaw('COUNT(*) as cantidad, COALESCE(SUM(venta_total), 0) as total')
                ->first();

            $cobrosMes = Cobro::whereBetween('cobranzas.cob_fecha', [$inicioMes, $hoy])
                ->selectRaw('COUNT(*) as cantidad, COALESCE(SUM(cob_importe), 0) as total')
                ->first();

            $saldoCobrar = (float) CtaCobrar::where('monto_saldo', '>', 0)->sum('monto_saldo');
            $cuotasVencidas = CtaCobrar::where('monto_saldo', '>', 0)
                ->whereDate('fecha_venc', '<', $hoy)
                ->count();

            $n_ventas = (int) ($ventasMes->cantidad ?? 0);
            $total_ventas_mes = (float) ($ventasMes->total ?? 0);
            $n_ventas_hoy = (int) ($ventasHoy->cantidad ?? 0);
            $total_ventas_hoy = (float) ($ventasHoy->total ?? 0);
            $n_cobros = (int) ($cobrosMes->cantidad ?? 0);
            $total_cobros_mes = (float) ($cobrosMes->total ?? 0);
        }

        $configCaja = DB::table('configuraciones')->where('categoria', 'caja')->pluck('value', 'name');
        $validezCaja = (string) ($configCaja['validez'] ?? '1');
        $usuarioModalidad = (string) ($configCaja['usuario'] ?? '1');

        $cajasQuery = Apertura::join('sucursales', 'apert_cierres_caja.suc_cod', '=', 'sucursales.suc_cod')
            ->join('caja', 'apert_cierres_caja.caja_cod', '=', 'caja.caja_cod')
            ->join('usuarios', 'apert_cierres_caja.cod_usuarios', '=', 'usuarios.cod_usuarios')
            ->select(
                'apert_cierres_caja.*',
                'sucursales.suc_desc',
                'caja.caja_descrip',
                'usuarios.nom_usuarios'
            )
            ->where('apert_cierres_caja.apert_estado', '1');

        // Modalidad de usuario:
        // '1' = Multiusuario compartido: todos los usuarios del local comparten la misma caja abierta
        // '2' = Por cada usuario: sólo cuenta la caja abierta por el usuario en sesión
        if ($usuarioModalidad === '2') {
            $cajasQuery->where('apert_cierres_caja.cod_usuarios', $usuario->cod_usuarios);
        }

        // Validez de turno:
        // '1' = Día a día por fecha (sólo la jornada de hoy)
        // '2' = Cada 24 horas continuas
        // '3' = Indefinido (abierta hasta cierre explícito)
        if ($validezCaja === '1') {
            $cajasQuery->whereDate('apert_cierres_caja.apert_fecha', $hoy);
        } elseif ($validezCaja === '2') {
            $cajasQuery->where(DB::raw('TIMESTAMPDIFF(HOUR, CONCAT(apert_cierres_caja.apert_fecha, " ", apert_cierres_caja.apert_hora), NOW())'), '<', '24');
        }

        $cajasAbiertas = $cajasQuery
            ->orderByRaw('CASE WHEN apert_cierres_caja.cod_usuarios = ? THEN 0 ELSE 1 END', [$usuario->cod_usuarios])
            ->orderBy('apert_cierres_caja.nro_operacion', 'DESC')
            ->get();

        $cajaAbierta = $cajasAbiertas->first();
        $cajaHref = $cajaAbierta
            ? route('caja.informe', $cajaAbierta->nro_operacion)
            : route('apertura');

        $sifenService = app(SifenService::class);
        $sifenConfig = $sifenService->config();
        $sifenFaltantes = $sifenService->validarConfig();
        $sifenActivo = (bool) $sifenConfig->activo;
        $sifenListo = $sifenActivo && count($sifenFaltantes) === 0;
        $sifenAmbiente = $sifenConfig->ambiente === 'prod' ? 'prod' : 'test';
        $sifenFalta = $sifenFaltantes[0] ?? null;

        $ventasRecientes = Venta::join('clientes as c', 'ventas.CLIENTES_cod', '=', 'c.CLIENTES_cod')
            ->leftJoin('sucursales as s', 'ventas.suc_cod', '=', 's.suc_cod')
            ->select(
                'ventas.nro_fact_ventas',
                'ventas.venta_total',
                'ventas.tipo_factura',
                'ventas.documento',
                'c.cliente_nombre',
                's.suc_desc',
                DB::raw('DATE_FORMAT(ventas.venta_fecha, "%d/%m/%Y %H:%i") as fecha')
            )
            ->orderBy('ventas.nro_fact_ventas', 'DESC')
            ->limit(8)
            ->get();

        // Si es admin, obtener todas las cajas abiertas en el comercio
        $todasCajasAbiertas = collect();
        $totalArticulos = 0;
        $articulosSinStock = 0;
        if ($esAdministrador) {
            $todasCajasQuery = Apertura::join('sucursales', 'apert_cierres_caja.suc_cod', '=', 'sucursales.suc_cod')
                ->join('caja', 'apert_cierres_caja.caja_cod', '=', 'caja.caja_cod')
                ->join('usuarios', 'apert_cierres_caja.cod_usuarios', '=', 'usuarios.cod_usuarios')
                ->select(
                    'apert_cierres_caja.*',
                    'sucursales.suc_desc',
                    'caja.caja_descrip',
                    'usuarios.nom_usuarios'
                )
                ->where('apert_cierres_caja.apert_estado', '1');

            if ($validezCaja === '1') {
                $todasCajasQuery->whereDate('apert_cierres_caja.apert_fecha', $hoy);
            } elseif ($validezCaja === '2') {
                $todasCajasQuery->where(DB::raw('TIMESTAMPDIFF(HOUR, CONCAT(apert_cierres_caja.apert_fecha, " ", apert_cierres_caja.apert_hora), NOW())'), '<', '24');
            }

            $todasCajasAbiertas = $todasCajasQuery->orderBy('nro_operacion', 'DESC')->get();

            $totalArticulos = Articulo::count();
            $articulosSinStock = Stock::where('cantidad', '<=', 0)->count();
        }

        $ventasChart = [];
        $chartTieneVentas = false;
        $totalVentasSemana = 0;
        $maxVentaChart = 0;
        $promedioVentaDiaria = 0;

        if ($esAdministrador) {
            $chartData = Venta::select(
                DB::raw('DATE(venta_fecha) as fecha'),
                DB::raw('COALESCE(SUM(venta_total), 0) as total'),
                DB::raw('COUNT(*) as cantidad')
            )
                ->whereBetween(DB::raw('DATE(venta_fecha)'), [date('Y-m-d', strtotime('-6 days')), $hoy])
                ->groupBy(DB::raw('DATE(venta_fecha)'))
                ->orderBy('fecha')
                ->get()
                ->keyBy('fecha');

            $diasSemana = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];

            for ($i = 6; $i >= 0; $i--) {
                $dia = date('Y-m-d', strtotime("-{$i} days"));
                $row = $chartData->get($dia);
                $totalDia = $row ? (float) $row->total : 0;
                $cantDia = $row ? (int) $row->cantidad : 0;

                if ($totalDia > 0) {
                    $chartTieneVentas = true;
                }
                if ($totalDia > $maxVentaChart) {
                    $maxVentaChart = $totalDia;
                }
                $totalVentasSemana += $totalDia;

                $diaSemanaNum = (int) date('w', strtotime($dia));
                $ventasChart[] = [
                    'fecha_corta' => date('d/m', strtotime($dia)),
                    'fecha_full' => date('d/m/Y', strtotime($dia)),
                    'dia_nombre' => $diasSemana[$diaSemanaNum],
                    'es_hoy' => ($dia === $hoy),
                    'total' => $totalDia,
                    'cantidad' => $cantDia,
                ];
            }

            $promedioVentaDiaria = round($totalVentasSemana / 7);
        }

        return view('home', compact(
            'empresa',
            'usuario',
            'nombreLocal',
            'localIncompleto',
            'mes',
            'esAdministrador',
            'n_ventas',
            'total_ventas_mes',
            'n_ventas_hoy',
            'total_ventas_hoy',
            'n_cobros',
            'total_cobros_mes',
            'saldoCobrar',
            'cuotasVencidas',
            'cajasAbiertas',
            'todasCajasAbiertas',
            'cajaHref',
            'ventasRecientes',
            'ventasChart',
            'chartTieneVentas',
            'totalVentasSemana',
            'promedioVentaDiaria',
            'maxVentaChart',
            'totalArticulos',
            'articulosSinStock',
            'sifenActivo',
            'sifenListo',
            'sifenAmbiente',
            'sifenFalta',
            'usuarioModalidad',
            'validezCaja'
        ));
    }
}
