<?php

namespace App\Services\Sifen;

use App\SifenConfig;
use App\SifenDocumento;
use App\Services\SifenService;
use DB;

class SifenLaboratorioService
{
    protected $certificates;
    protected $signer;
    protected $qr;
    protected $soap;
    protected $sifen;

    public function __construct(
        CertificateService $certificates,
        XmlSigner $signer,
        QrKuDeService $qr,
        SifenSoapClient $soap,
        SifenService $sifen
    ) {
        $this->certificates = $certificates;
        $this->signer = $signer;
        $this->qr = $qr;
        $this->soap = $soap;
        $this->sifen = $sifen;
    }

    public function ventasRecientes($limit = 30)
    {
        return DB::select(
            'SELECT v.nro_fact_ventas, v.venta_total, v.venta_fecha, c.cliente_nombre,
                    IFNULL(sd.estado, "sin_documento") AS estado_sifen
             FROM ventas v
             INNER JOIN clientes c ON v.clientes_cod = c.clientes_cod
             LEFT JOIN sifen_documentos sd ON sd.nro_fact_ventas = v.nro_fact_ventas
                AND sd.id = (SELECT MAX(id) FROM sifen_documentos WHERE nro_fact_ventas = v.nro_fact_ventas)
             ORDER BY v.nro_fact_ventas DESC
             LIMIT ?',
            [$limit]
        );
    }

    public function ejecutar($nroVenta, $paso, $enviarSet = false)
    {
        $config = $this->sifen->config();
        $resultado = [
            'paso' => $paso,
            'ok' => true,
            'mensaje' => '',
            'faltantes' => $this->sifen->validarConfig(),
            'config' => [
                'ambiente' => $config->ambiente,
                'url' => $config->ambiente === 'prod' ? $config->url_prod : $config->url_test,
            ],
        ];

        if ($paso === 'validar') {
            if (!count($resultado['faltantes'])) {
                try {
                    $this->certificates->cargarDesdeConfig($config);
                    $resultado['certificado'] = 'Certificado PKCS#12 leído correctamente';
                    $resultado['cert_diag'] = $this->certificates->diagnostico($config);
                    $resultado['set'] = $this->soap->probarConexion($config);
                    if (!$resultado['set']['ok']) {
                        $resultado['ok'] = false;
                    }
                } catch (\Throwable $e) {
                    $resultado['ok'] = false;
                    $resultado['certificado'] = $e->getMessage();
                }
            }
            $resultado['mensaje'] = count($resultado['faltantes'])
                ? 'Configuración incompleta'
                : ($resultado['ok']
                    ? 'Configuración, certificado y conexión SET listos'
                    : 'Configuración completa, pero falló certificado o conexión SET');
            return $resultado;
        }

        if (count($resultado['faltantes'])) {
            throw new \RuntimeException('Complete la configuración SIFEN: ' . implode(', ', $resultado['faltantes']));
        }

        $venta = SifenVentaData::cargar($nroVenta);
        $fechaEmision = SifenVentaData::fechaEmision($venta['cabecera']);
        $preview = $this->documentoPreview($config);
        $cdc = CdcGenerator::construir($config, $preview, $preview->codigo_seguridad, $fechaEmision);

        $resultado['venta'] = [
            'nro' => (int) $nroVenta,
            'cliente' => $venta['cabecera']->cliente_nombre,
            'total' => (float) $venta['cabecera']->venta_total,
            'items' => count($venta['items']),
        ];
        $resultado['numeracion'] = $this->formatoNumero($preview);
        $resultado['cdc'] = $cdc;
        $resultado['codigo_seguridad'] = $preview->codigo_seguridad;
        $resultado['fecha_emision'] = $fechaEmision;

        $xml = (new DeXmlBuilder(
            $config,
            $preview,
            $venta,
            $cdc,
            $preview->codigo_seguridad,
            $fechaEmision
        ))->build();

        if ($paso === 'xml') {
            $resultado['mensaje'] = 'XML DE generado (sin enviar a SET)';
            $resultado['xml'] = $xml;
            return $resultado;
        }

        $material = $this->certificates->cargarDesdeConfig($config);
        $firmado = $this->signer->firmar(
            $xml,
            $material['pkey'],
            $material['cert'],
            (string) $config->cert_password
        );
        $resultado['digest_value'] = $firmado['digest_value'];

        if ($paso === 'firmar') {
            $resultado['mensaje'] = 'XML firmado correctamente';
            $resultado['xml'] = $firmado['xml'];
            return $resultado;
        }

        $qrUrl = $this->qr->construirUrlDesdeXmlFirmado($config, $firmado['xml']);
        $xmlFinal = $this->signer->agregarQr($firmado['xml'], $qrUrl);
        $resultado['qr_url'] = $qrUrl;
        $resultado['qr_image'] = base64_encode($this->sifen->generarQrPng($qrUrl, 180));

        if ($paso === 'qr') {
            $resultado['mensaje'] = 'QR KuDE generado';
            $resultado['xml'] = $xmlFinal;
            return $resultado;
        }

        $resultado['xml'] = $xmlFinal;

        if ($paso === 'enviar' || ($paso === 'completo' && $enviarSet)) {
            $respuesta = $this->soap->enviarDocumento($config, $xmlFinal, false);
            $resultado['ok'] = $respuesta['aprobado'];
            $resultado['set'] = [
                'aprobado' => $respuesta['aprobado'],
                'codigo' => $respuesta['codigo'],
                'mensaje' => $respuesta['mensaje'],
                'cdc' => $respuesta['cdc'],
            ];
            $resultado['respuesta_set'] = $respuesta['raw'];
            $resultado['mensaje'] = $respuesta['aprobado']
                ? 'SET aprobó el documento de prueba'
                : 'SET respondió sin aprobar';
            return $resultado;
        }

        $resultado['mensaje'] = 'Flujo completo generado localmente (sin envío a SET)';
        return $resultado;
    }

    protected function documentoPreview(SifenConfig $config)
    {
        $documento = new SifenDocumento();
        $documento->timbrado = $config->timbrado;
        $documento->establecimiento = str_pad($config->establecimiento, 3, '0', STR_PAD_LEFT);
        $documento->punto_expedicion = str_pad($config->punto_expedicion, 3, '0', STR_PAD_LEFT);
        $documento->numero = (int) $config->ultimo_numero + 1;
        $documento->tipo_documento = (int) $config->tipo_documento;
        $documento->codigo_seguridad = CdcGenerator::generarCodigoSeguridad();

        return $documento;
    }

    protected function formatoNumero($documento)
    {
        return str_pad($documento->establecimiento, 3, '0', STR_PAD_LEFT) . '-'
            . str_pad($documento->punto_expedicion, 3, '0', STR_PAD_LEFT) . '-'
            . str_pad($documento->numero, 7, '0', STR_PAD_LEFT);
    }
}
