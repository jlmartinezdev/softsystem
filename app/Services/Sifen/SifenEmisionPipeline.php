<?php

namespace App\Services\Sifen;

use App\SifenConfig;
use App\SifenDocumento;
use App\Services\SifenService;

class SifenEmisionPipeline
{
    protected $certificates;
    protected $signer;
    protected $qr;
    protected $soap;

    public function __construct(
        CertificateService $certificates,
        XmlSigner $signer,
        QrKuDeService $qr,
        SifenSoapClient $soap
    ) {
        $this->certificates = $certificates;
        $this->signer = $signer;
        $this->qr = $qr;
        $this->soap = $soap;
    }

    public function procesar(SifenDocumento $documento, SifenConfig $config)
    {
        $venta = SifenVentaData::cargar($documento->nro_fact_ventas);
        $fechaEmision = SifenVentaData::fechaEmision($venta['cabecera']);

        $codigoSeguridad = $documento->codigo_seguridad ?: CdcGenerator::generarCodigoSeguridad();
        if (!$documento->codigo_seguridad) {
            $documento->update(['codigo_seguridad' => $codigoSeguridad]);
            $documento = $documento->fresh();
        }

        $cdc = CdcGenerator::construir($config, $documento, $codigoSeguridad, $fechaEmision);

        $xml = (new DeXmlBuilder(
            $config,
            $documento,
            $venta,
            $cdc,
            $codigoSeguridad,
            $fechaEmision
        ))->build();

        $material = $this->certificates->cargarDesdeConfig($config);
        $firmado = $this->signer->firmar(
            $xml,
            $material['pkey'],
            $material['cert'],
            (string) $config->cert_password
        );

        $qrUrl = $this->qr->construirUrlDesdeXmlFirmado($config, $firmado['xml']);

        $xmlEnviar = $this->signer->agregarQr($firmado['xml'], $qrUrl);

        $documento->update([
            'cdc' => $cdc,
            'qr_url' => $qrUrl,
            'digest_value' => $firmado['digest_value'],
            'xml_enviado' => $xmlEnviar,
        ]);

        try {
            $respuesta = $this->soap->enviarDocumento($config, $xmlEnviar);
        } catch (\Throwable $e) {
            $documento->update([
                'estado' => SifenService::ESTADO_ENVIADO,
                'codigo_respuesta' => 'SOAP',
                'mensaje_respuesta' => $e->getMessage(),
                'fecha_envio' => date('Y-m-d H:i:s'),
            ]);

            throw new \RuntimeException('DE preparado y firmado, pero falló el envío a SIFEN: ' . $e->getMessage(), 0, $e);
        }

        if ($respuesta['aprobado']) {
            $documento->update([
                'estado' => SifenService::ESTADO_APROBADO,
                'cdc' => $respuesta['cdc'] ?: $cdc,
                'codigo_respuesta' => $respuesta['codigo'],
                'mensaje_respuesta' => $respuesta['mensaje'],
                'xml_respuesta' => $respuesta['raw'],
                'fecha_envio' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $documento->update([
                'estado' => SifenService::ESTADO_RECHAZADO,
                'codigo_respuesta' => $respuesta['codigo'],
                'mensaje_respuesta' => $respuesta['mensaje'],
                'xml_respuesta' => $respuesta['raw'],
                'fecha_envio' => date('Y-m-d H:i:s'),
            ]);

            throw new \RuntimeException('SIFEN rechazó el documento: [' . $respuesta['codigo'] . '] ' . $respuesta['mensaje']);
        }

        return $documento->fresh();
    }
}
