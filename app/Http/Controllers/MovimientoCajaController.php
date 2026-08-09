<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Apertura;
use App\MovimientoCaja;
use DB;
use Validator;

class MovimientoCajaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('movimiento');
    }

    public function getAll($nro_operacion)
    {
        return MovimientoCaja::select(
                DB::raw('DATE_FORMAT(mov_fecha,"%d/%m/%Y %H:%i") AS mov_fecha'),
                'nro_operacion',
                'mov_concepto',
                'mov_tipo',
                'mov_monto'
            )
            ->where('nro_operacion', '=', $nro_operacion)
            ->orderBy('mov_cod')
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->input('data', []);

        $validator = Validator::make($data, [
            'nro_operacion' => 'required',
            'idSucursal' => 'required',
            'tipo' => 'required|in:Entrada,Salida',
            'descripcion' => 'required|string|min:2|max:255',
            'monto' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $apertura = Apertura::where('nro_operacion', $data['nro_operacion'])
            ->where('apert_estado', '1')
            ->first();

        if (!$apertura) {
            return response()->json([
                'ok' => false,
                'message' => 'La caja no está abierta. No se puede registrar el movimiento.',
            ], 422);
        }

        if ((string) $apertura->suc_cod !== (string) $data['idSucursal']) {
            return response()->json([
                'ok' => false,
                'message' => 'La sucursal no coincide con la operación de caja.',
            ], 422);
        }

        if ($data['tipo'] === 'Salida') {
            $entradas = (float) MovimientoCaja::where('nro_operacion', $data['nro_operacion'])
                ->where('mov_tipo', 'Entrada')
                ->sum('mov_monto');
            $salidas = (float) MovimientoCaja::where('nro_operacion', $data['nro_operacion'])
                ->where('mov_tipo', 'Salida')
                ->sum('mov_monto');
            $saldo = $entradas - $salidas;

            if ((float) $data['monto'] > $saldo) {
                return response()->json([
                    'ok' => false,
                    'message' => 'El monto de salida supera el saldo en caja (Gs. ' . number_format($saldo, 0, ',', '.') . ').',
                ], 422);
            }
        }

        $movimiento = new MovimientoCaja();
        $movimiento->nro_operacion = $data['nro_operacion'];
        $movimiento->mov_fecha = date('Y-m-d H:i');
        $movimiento->mov_concepto = trim($data['descripcion']);
        $movimiento->mov_tipo = $data['tipo'];
        $movimiento->mov_monto = $data['monto'];
        $movimiento->nro_fact_ventas = '-';
        $movimiento->suc_cod = $data['idSucursal'];
        $movimiento->save();

        return response()->json([
            'ok' => true,
            'message' => 'Movimiento registrado correctamente.',
            'movimientos' => $this->getAll($data['nro_operacion']),
        ]);
    }

    public function informe($nro_operacion)
    {
        $movimiento = $this->getAll($nro_operacion);
        return view('informes.apertura', compact('movimiento'));
    }
}
