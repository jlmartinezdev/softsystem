<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venta;
use App\Empresa;
use App\Services\SifenService;
use DB;

class FacturarController extends Controller
{
    protected $sifen;

    public function __construct(SifenService $sifen)
    {
        $this->middleware('auth');
        $this->sifen = $sifen;
    }

    public function index($id)
    {
        if (!$this->sifen->isActivo()) {
            return redirect()
                ->route('infventa.imprimir')
                ->with('error', 'La facturación electrónica SIFEN está desactivada.');
        }

        $cabecera = $this->getCabecera($id);
        if (!$cabecera) {
            abort(404, 'Venta no encontrada');
        }

        $detalle = $this->getDetalle($id);
        $documento = $this->sifen->documentoPorVenta($id);
        $config = $this->sifen->config();
        $faltantes = $this->sifen->validarConfig();
        $iva = $this->getIva($id);

        return view('venta.facturar', compact('id', 'cabecera', 'detalle', 'documento', 'config', 'faltantes', 'iva'));
    }

    public function store(Request $request)
    {
        if (!$this->sifen->isActivo()) {
            return response()->json([
                'mensaje' => 'La facturación electrónica SIFEN está desactivada.',
                'facturado' => false,
                'enviado' => false,
            ], 422);
        }

        $request->validate([
            'nro_venta' => 'required|integer',
        ]);

        try {
            $documento = $this->sifen->emitirDocumento($request->nro_venta);

            if ($documento->estado === SifenService::ESTADO_APROBADO) {
                $this->updateStatusVen($request->nro_venta, '1');
            }

            return [
                'mensaje' => 'OK',
                'documento' => $documento,
                'numero' => $this->sifen->formatoNumero($documento),
                'facturado' => $documento->estado === SifenService::ESTADO_APROBADO,
                'enviado' => $documento->estado === SifenService::ESTADO_ENVIADO,
            ];
        } catch (\Throwable $e) {
            $documento = $this->sifen->documentoPorVenta($request->nro_venta);
            return response()->json([
                'mensaje' => $e->getMessage(),
                'documento' => $documento,
                'facturado' => false,
                'enviado' => $documento && $documento->estado === SifenService::ESTADO_ENVIADO,
            ], 422);
        }
    }

    public function destroy($id)
    {
        try {
            $this->sifen->anularDocumento($id);
            $this->updateStatusVen($id, '0');

            return ['mensaje' => 'OK'];
        } catch (\Throwable $e) {
            return response()->json(['mensaje' => $e->getMessage()], 422);
        }
    }

    public function ticket($id)
    {
        try {
            $kude = $this->sifen->datosKuDe($id);
        } catch (\Throwable $e) {
            abort(404, $e->getMessage());
        }

        $documento = $kude['documento'];
        $config = $kude['config'];
        $empresa = $kude['empresa'];
        $cabecera = $kude['venta']['cabecera'];
        $venta = $kude['venta']['items'];
        $qrImage = $kude['qrImage'];
        $iva = $this->getIva($id);

        $factura = collect([(object) [
            'timbrado' => $documento->timbrado,
            'fecha_factura' => $config->vigencia_desde,
            'fecha_venc' => $config->vigencia_hasta,
            'nivel1' => $documento->establecimiento,
            'nivel2' => $documento->punto_expedicion,
            'nro_factura' => $documento->numero,
        ]]);

        return view('ticket.factura', compact('empresa', 'venta', 'cabecera', 'factura', 'iva', 'documento', 'qrImage'));
    }

    public function kudePdf($id)
    {
        try {
            $kude = $this->sifen->datosKuDe($id);
        } catch (\Throwable $e) {
            abort(404, $e->getMessage());
        }

        $pdf = \PDF::loadView('pdf.kude', $kude);
        $nombre = 'KuDE-' . $kude['documento']->nro_fact_ventas . '.pdf';

        return $pdf->stream($nombre);
    }

    private function getCabecera($nroVenta)
    {
        $rows = DB::select(
            'SELECT v.*, c.cliente_nombre, c.cliente_direccion, c.cliente_ruc, c.cliente_ci
             FROM ventas v
             INNER JOIN clientes c ON v.clientes_cod = c.clientes_cod
             WHERE v.nro_fact_ventas = ?',
            [$nroVenta]
        );

        return count($rows) ? $rows[0] : null;
    }

    private function getDetalle($nroVenta)
    {
        return DB::select(
            'SELECT dv.*,
                    COALESCE(NULLIF(TRIM(dv.descripcion_libre), \'\'), a.producto_nombre) AS producto_nombre,
                    a.producto_c_barra
             FROM detalle_venta dv
             INNER JOIN articulos a ON dv.ARTICULOS_cod = a.ARTICULOS_cod
             WHERE dv.nro_fact_ventas = ?',
            [$nroVenta]
        );
    }

    private function getIva($nroVenta)
    {
        return DB::select(
            'SELECT SUM(IF(p.iva=5, dv.venta_precio * dv.venta_cantidad, 0)) AS iva5,
                    SUM(IF(p.iva=10, dv.venta_precio * dv.venta_cantidad, 0)) AS iva10
             FROM detalle_venta dv
             INNER JOIN articulos a ON dv.ARTICULOS_cod = a.ARTICULOS_cod
             INNER JOIN presentacion p ON a.present_cod = p.present_cod
             WHERE dv.nro_fact_ventas = ?',
            [$nroVenta]
        );
    }

    private function updateStatusVen($nroVenta, $status)
    {
        Venta::where('nro_fact_ventas', $nroVenta)->update(['factura_status' => $status]);
    }
}
