<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SifenService;
use App\Services\Sifen\SifenLaboratorioService;

class SifenLaboratorioController extends Controller
{
    protected $sifen;
    protected $laboratorio;

    public function __construct(SifenService $sifen, SifenLaboratorioService $laboratorio)
    {
        $this->middleware('auth');
        $this->sifen = $sifen;
        $this->laboratorio = $laboratorio;
    }

    public function index()
    {
        $config = $this->sifen->config();
        $faltantes = $this->sifen->validarConfig();
        $ventas = $this->laboratorio->ventasRecientes(30);

        return view('sifen.laboratorio', compact('config', 'faltantes', 'ventas'));
    }

    public function ejecutar(Request $request)
    {
        $request->validate([
            'paso' => 'required|in:validar,xml,firmar,qr,enviar,completo',
            'nro_venta' => 'nullable|integer',
            'enviar_set' => 'nullable|boolean',
        ]);

        $paso = $request->input('paso');
        $nroVenta = (int) $request->input('nro_venta');
        $enviarSet = (bool) $request->input('enviar_set', false);

        if ($paso !== 'validar' && !$nroVenta) {
            return response()->json(['mensaje' => 'Seleccione una venta para la prueba.'], 422);
        }

        try {
            $resultado = $this->laboratorio->ejecutar(
                $nroVenta,
                $paso,
                $enviarSet || $paso === 'enviar'
            );

            return ['mensaje' => 'OK', 'resultado' => $resultado];
        } catch (\Throwable $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
            ], 422);
        }
    }
}
