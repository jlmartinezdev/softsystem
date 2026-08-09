<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Apertura;
use App\Sucursal;
use App\Caja;
use App\MovimientoCaja;
use App\Ajuste;
use App\Empresa;
use App\Mail\CierreCajaResumen;
use App\Support\MailSettings;
use Auth;
use DB;
use Mail;

class AperturaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $sucursales = Sucursal::all();
        $cajas = Caja::all();

        $query = Apertura::join('sucursales', 'apert_cierres_caja.suc_cod', '=', 'sucursales.suc_cod')
            ->join('caja', 'apert_cierres_caja.caja_cod', '=', 'caja.caja_cod')
            ->join('usuarios', 'apert_cierres_caja.cod_usuarios', '=', 'usuarios.cod_usuarios')
            ->select(
                'apert_cierres_caja.*',
                'sucursales.suc_desc',
                'caja.caja_descrip',
                'usuarios.nom_usuarios'
            )
            ->orderBy('nro_operacion', 'DESC');

        if ($request->filled('sucursal')) {
            $query->where('apert_cierres_caja.suc_cod', $request->sucursal);
        }

        if ($request->filled('estado') && $request->estado !== 'all') {
            $query->where('apert_cierres_caja.apert_estado', $request->estado);
        }

        if ($request->filled('desde')) {
            $query->whereDate('apert_cierres_caja.apert_fecha', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('apert_cierres_caja.apert_fecha', '<=', $request->hasta);
        }

        $aperturas = $query->paginate(15)->appends($request->query());

        $sucursalFiltro = $request->filled('sucursal')
            ? $request->sucursal
            : optional($sucursales->first())->suc_cod;
        $cajaAbierta = $sucursalFiltro ? $this->getStatu($sucursalFiltro) : null;

        if ($cajaAbierta && empty($cajaAbierta->suc_desc)) {
            $cajaAbierta = Apertura::join('sucursales', 'apert_cierres_caja.suc_cod', '=', 'sucursales.suc_cod')
                ->join('caja', 'apert_cierres_caja.caja_cod', '=', 'caja.caja_cod')
                ->join('usuarios', 'apert_cierres_caja.cod_usuarios', '=', 'usuarios.cod_usuarios')
                ->select(
                    'apert_cierres_caja.*',
                    'sucursales.suc_desc',
                    'caja.caja_descrip',
                    'usuarios.nom_usuarios'
                )
                ->where('apert_cierres_caja.nro_operacion', $cajaAbierta->nro_operacion)
                ->first();
        }

        return view('apertura', compact('sucursales', 'cajas', 'aperturas', 'cajaAbierta'));
    }

    public function indexCierre($operacion)
    {
        $apertura = Apertura::join('sucursales', 'apert_cierres_caja.suc_cod', '=', 'sucursales.suc_cod')
            ->join('caja', 'apert_cierres_caja.caja_cod', '=', 'caja.caja_cod')
            ->join('usuarios', 'apert_cierres_caja.cod_usuarios', '=', 'usuarios.cod_usuarios')
            ->select(
                'apert_cierres_caja.*',
                'sucursales.suc_desc',
                'caja.caja_descrip',
                'usuarios.nom_usuarios'
            )
            ->where('apert_cierres_caja.nro_operacion', $operacion)
            ->first();

        if (!$apertura) {
            return redirect()->route('apertura')->with('error', 'Operación de caja no encontrada.');
        }

        if ((string) $apertura->apert_estado !== '1') {
            return redirect()->route('apertura')->with('error', 'La caja de esta operación ya está cerrada.');
        }

        $entradas = (float) MovimientoCaja::where('nro_operacion', $operacion)
            ->where('mov_tipo', 'Entrada')
            ->sum('mov_monto');
        $salidas = (float) MovimientoCaja::where('nro_operacion', $operacion)
            ->where('mov_tipo', 'Salida')
            ->sum('mov_monto');
        $esperado = $entradas - $salidas;

        $movimientos = MovimientoCaja::select(
                DB::raw('DATE_FORMAT(mov_fecha,"%d/%m/%Y %H:%i") AS mov_fecha'),
                'nro_operacion',
                'mov_concepto',
                'mov_tipo',
                'mov_monto'
            )
            ->where('nro_operacion', $operacion)
            ->orderBy('mov_cod')
            ->get();

        return view('cierre', compact('apertura', 'entradas', 'salidas', 'esperado', 'movimientos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sucursal' => 'required',
            'caja' => 'required',
            'monto' => 'required|numeric|min:0',
            'usuario' => 'required',
        ]);

        if (!empty($this->getStatu($request->sucursal))) {
            return redirect()->back()->with('error', 'Ya existe una caja abierta en esa sucursal.');
        }

        $apertura = new Apertura();
        $apertura->cod_usuarios = $request->usuario;
        $apertura->caja_cod = $request->caja;
        $apertura->apert_fecha = date('Y-m-d');
        $apertura->apert_hora = date('H:i:s');
        $apertura->apert_monto = $request->monto;
        $apertura->apert_estado = 1;
        $apertura->suc_cod = $request->sucursal;
        $apertura->save();

        $this->storeMovimiento($request->sucursal, $apertura->nro_operacion, $request->monto);

        return redirect()->back()->with(
            'success',
            'Caja abierta correctamente. Operación #' . $apertura->nro_operacion
        );
    }

    private function storeMovimiento($idSucursal, $ope, $monto)
    {
        $movimiento = new MovimientoCaja();
        $movimiento->nro_operacion = $ope;
        $movimiento->mov_fecha = date('Y-m-d H:i');
        $movimiento->mov_concepto = 'Monto Inicial';
        $movimiento->mov_tipo = 'Entrada';
        $movimiento->mov_monto = $monto;
        $movimiento->nro_fact_ventas = '-';
        $movimiento->suc_cod = $idSucursal;
        $movimiento->save();
    }

    public function update(Request $request)
    {
        $request->validate([
            'nro_operacion' => 'required',
            'monto' => 'required|numeric|min:0',
        ]);

        $apertura = Apertura::find($request->nro_operacion);
        if (!$apertura) {
            return redirect()->route('apertura')->with('error', 'Operación de caja no encontrada.');
        }

        if ((string) $apertura->apert_estado !== '1') {
            return redirect()->route('apertura')->with('error', 'La caja ya está cerrada.');
        }

        $entradas = (float) MovimientoCaja::where('nro_operacion', $request->nro_operacion)
            ->where('mov_tipo', 'Entrada')
            ->sum('mov_monto');
        $salidas = (float) MovimientoCaja::where('nro_operacion', $request->nro_operacion)
            ->where('mov_tipo', 'Salida')
            ->sum('mov_monto');
        $esperado = $entradas - $salidas;
        $contado = (float) $request->monto;
        $diferencia = $contado - $esperado;

        $apertura->update([
            'cierre_fecha' => date('Y-m-d'),
            'cierre_hora' => date('H:i:s'),
            'cierre_monto' => $request->monto,
            'apert_estado' => '0',
        ]);

        $signo = $diferencia >= 0 ? '+' : '';
        $msgDiff = 'Diferencia: Gs. ' . $signo . number_format($diferencia, 0, ',', '.');
        $mailMsg = '';

        try {
            $mailMsg = $this->enviarResumenCierreMail($request->nro_operacion, $contado, $esperado, $diferencia, $entradas, $salidas);
        } catch (\Throwable $e) {
            $mailMsg = ' El correo de resumen no se pudo enviar: ' . $e->getMessage();
        }

        return redirect()->route('apertura')->with(
            'success',
            'Caja cerrada correctamente. Operación #' . $request->nro_operacion . '. ' . $msgDiff . $mailMsg
        );
    }

    private function enviarResumenCierreMail($nroOperacion, $contado, $esperado, $diferencia, $entradas, $salidas)
    {
        if (!MailSettings::canSendCierre()) {
            return '';
        }

        $info = Apertura::join('sucursales', 'apert_cierres_caja.suc_cod', '=', 'sucursales.suc_cod')
            ->join('caja', 'apert_cierres_caja.caja_cod', '=', 'caja.caja_cod')
            ->join('usuarios', 'apert_cierres_caja.cod_usuarios', '=', 'usuarios.cod_usuarios')
            ->select(
                'apert_cierres_caja.*',
                'sucursales.suc_desc',
                'caja.caja_descrip',
                'usuarios.nom_usuarios'
            )
            ->where('apert_cierres_caja.nro_operacion', $nroOperacion)
            ->first();

        if (!$info) {
            return '';
        }

        $movimientos = MovimientoCaja::select(
                DB::raw('DATE_FORMAT(mov_fecha,"%d/%m/%Y %H:%i") AS fecha'),
                'mov_concepto',
                'mov_tipo',
                'mov_monto'
            )
            ->where('nro_operacion', $nroOperacion)
            ->orderBy('mov_cod')
            ->get()
            ->map(function ($m) {
                return [
                    'fecha' => $m->fecha,
                    'concepto' => $m->mov_concepto,
                    'tipo' => $m->mov_tipo,
                    'monto' => (float) $m->mov_monto,
                ];
            })
            ->all();

        $empresa = Empresa::first();

        $resumen = [
            'empresa' => $empresa ? $empresa->emp_nombre : 'SoftSystem',
            'nro_operacion' => $info->nro_operacion,
            'sucursal' => $info->suc_desc,
            'caja' => $info->caja_descrip,
            'usuario' => $info->nom_usuarios,
            'apertura_fecha' => date('d/m/Y', strtotime($info->apert_fecha)),
            'apertura_hora' => $info->apert_hora,
            'cierre_fecha' => date('d/m/Y', strtotime($info->cierre_fecha)),
            'cierre_hora' => $info->cierre_hora,
            'monto_apertura' => (float) $info->apert_monto,
            'entradas' => $entradas,
            'salidas' => $salidas,
            'esperado' => $esperado,
            'contado' => $contado,
            'diferencia' => $diferencia,
            'movimientos' => $movimientos,
            'cerrado_por' => Auth::check() ? Auth::user()->nom_usuarios : '',
        ];

        MailSettings::apply();
        $recipients = MailSettings::recipients();

        Mail::to($recipients)->send(new CierreCajaResumen($resumen));

        return ' Resumen enviado por correo a ' . implode(', ', $recipients) . '.';
    }

    public function getStatu($id)
    {
        $date = date('Y-m-d');
        $config = Ajuste::where('categoria', 'caja')->get();

        switch ($config[0]->value) {
            case '1':
                return Apertura::join('usuarios', 'apert_cierres_caja.cod_usuarios', '=', 'usuarios.cod_usuarios')
                    ->where('apert_cierres_caja.suc_cod', '=', $id)
                    ->where('apert_cierres_caja.apert_fecha', '=', $date)
                    ->usuario($config[1]->value)
                    ->where('apert_cierres_caja.apert_estado', '=', '1')
                    ->first();
            case '2':
                return Apertura::where('apert_cierres_caja.suc_cod', '=', $id)
                    ->where(DB::raw('TIMESTAMPDIFF(HOUR,CONCAT(apert_cierres_caja.apert_fecha," ",apert_cierres_caja.apert_hora),NOW())'), '<', '24')
                    ->usuario($config[1]->value)
                    ->where('apert_cierres_caja.apert_estado', '=', '1')
                    ->get()
                    ->last();
            default:
                return Apertura::where('apert_cierres_caja.suc_cod', '=', $id)
                    ->usuario($config[1]->value)
                    ->where('apert_cierres_caja.apert_estado', '=', '1')
                    ->get()
                    ->last();
        }
    }

    public function comando()
    {
        $exitCode = Artisan::call('up');
        return 'DONE';
    }
}
