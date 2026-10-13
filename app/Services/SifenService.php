<?php
namespace App\Services;
use App\SifenConfig;
use App\SifenDocumento;
use App\Empresa;
use App\Support\SifenMt150;
use App\Services\Sifen\SifenApiClient;
use App\Services\Sifen\SifenApiEmisionService;
use App\Services\Sifen\SifenVentaData;
class SifenService
{
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_ENVIADO = 'enviado';
    const ESTADO_APROBADO = 'aprobado';
    const ESTADO_RECHAZADO = 'rechazado';
    const ESTADO_ANULADO = 'anulado';
    public function config()
    {
        $config = SifenConfig::firstOrCreate(['id' => 1], $this->defaults());
        if (strtolower((string) $config->modo_emision) !== 'api') {
            $config->modo_emision = 'api';
            $config->save();
        }
        return $config;
    }
    public function isActivo()
    {
        $config = $this->config();
        return (bool) $config->activo;
    }

    public function isModoApi()
    {
        return true;
    }

    public function validarConfig()
    {
        $config = $this->config();
        $faltantes = [];
        if (empty($config->api_url)) {
            $faltantes[] = 'URL base de la API SIFEN';
        }
        if (empty($config->api_token)) {
            $faltantes[] = 'Token Bearer de la API SIFEN';
        }
        return $faltantes;
    }
    public function puedeEmitir()
    {
        return $this->isActivo() && count($this->validarConfig()) === 0;
    }
    public function sincronizarDesdeEmpresa()
    {
        $empresa = Empresa::first();
        if (!$empresa) {
            return $this->config();
        }
        $config = $this->config();
        $config->update([
            'ruc' => $config->ruc ?: $empresa->emp_ruc,
            'razon_social' => $config->razon_social ?: $empresa->emp_nombre,
            'direccion_emisor' => $config->direccion_emisor ?: $empresa->emp_direccion,
            'telefono_emisor' => $config->telefono_emisor ?: ($empresa->emp_telefono ?: $empresa->emp_celular),
            'email_emisor' => $config->email_emisor ?: $empresa->emp_correo,
        ]);
        return $config->fresh();
    }
    public function reservarDocumento($nroFactVenta)
    {
        if (!$this->puedeEmitir()) {
            throw new \RuntimeException('La configuración SIFEN está incompleta o inactiva.');
        }
        $existente = SifenDocumento::where('nro_fact_ventas', $nroFactVenta)
            ->whereNotIn('estado', [self::ESTADO_RECHAZADO, self::ESTADO_ANULADO])
            ->first();
        if ($existente) {
            return $existente;
        }

        $config = $this->config();

        return SifenDocumento::create([
            'nro_fact_ventas' => $nroFactVenta,
            'timbrado' => $config->timbrado,
            'establecimiento' => str_pad((string) ($config->establecimiento ?: '001'), 3, '0', STR_PAD_LEFT),
            'punto_expedicion' => str_pad((string) ($config->punto_expedicion ?: '001'), 3, '0', STR_PAD_LEFT),
            'numero' => 0,
            'tipo_documento' => (int) ($config->tipo_documento ?: 1),
            'estado' => self::ESTADO_PENDIENTE,
            'fecha_emision' => date('Y-m-d H:i:s'),
        ]);
    }
    public function actualizarDocumento(SifenDocumento $documento, array $datos)
    {
        $documento->update($datos);
        return $documento->fresh();
    }
    public function marcarEnviado(SifenDocumento $documento, $xmlEnviado = null, $xmlRespuesta = null, $codigo = null, $mensaje = null)
    {
        return $this->actualizarDocumento($documento, [
            'estado' => self::ESTADO_ENVIADO,
            'xml_enviado' => $xmlEnviado,
            'xml_respuesta' => $xmlRespuesta,
            'codigo_respuesta' => $codigo,
            'mensaje_respuesta' => $mensaje,
            'fecha_envio' => date('Y-m-d H:i:s'),
        ]);
    }
    public function marcarAprobado(SifenDocumento $documento, $cdc, $qrUrl = null, $xmlRespuesta = null)
    {
        return $this->actualizarDocumento($documento, [
            'estado' => self::ESTADO_APROBADO,
            'cdc' => $cdc,
            'qr_url' => $qrUrl,
            'xml_respuesta' => $xmlRespuesta,
            'fecha_envio' => $documento->fecha_envio ?: date('Y-m-d H:i:s'),
        ]);
    }
    public function marcarRechazado(SifenDocumento $documento, $codigo, $mensaje, $xmlRespuesta = null)
    {
        return $this->actualizarDocumento($documento, [
            'estado' => self::ESTADO_RECHAZADO,
            'codigo_respuesta' => $codigo,
            'mensaje_respuesta' => $mensaje,
            'xml_respuesta' => $xmlRespuesta,
            'fecha_envio' => date('Y-m-d H:i:s'),
        ]);
    }
    public function documentoPorVenta($nroFactVenta)
    {
        return SifenDocumento::where('nro_fact_ventas', $nroFactVenta)->orderBy('id', 'desc')->first();
    }
    public function documentosRecientes($limit = 50)
    {
        return SifenDocumento::recientes($limit)->get();
    }
    public function formatoNumero(SifenDocumento $documento)
    {
        return str_pad($documento->establecimiento, 3, '0', STR_PAD_LEFT) . '-' .
            str_pad($documento->punto_expedicion, 3, '0', STR_PAD_LEFT) . '-' .
            str_pad($documento->numero, 7, '0', STR_PAD_LEFT);
    }
    public function emitirDocumento($nroFactVenta)
    {
        $doc = $this->documentoPorVenta($nroFactVenta);
        if (!$doc || in_array($doc->estado, [self::ESTADO_RECHAZADO, self::ESTADO_ANULADO])) {
            $doc = $this->reservarDocumento($nroFactVenta);
        }
        if ($doc->estado === self::ESTADO_APROBADO) {
            return $doc;
        }

        return $this->procesarEmision($doc);
    }

    public function procesarEmision(SifenDocumento $documento)
    {
        return (new SifenApiEmisionService())->emitir($documento, $this->config());
    }

    public function descargarKudeApi(SifenDocumento $documento)
    {
        if (empty($documento->api_documento_id)) {
            throw new \RuntimeException('El documento no tiene ID en la API SIFEN.');
        }
        $cliente = new SifenApiClient($this->config());
        return $cliente->descargarKude((int) $documento->api_documento_id);
    }

    public function probarApi()
    {
        $cliente = new SifenApiClient($this->config());
        return $cliente->status();
    }

    public function obtenerTokenApi($email, $password)
    {
        $cliente = new SifenApiClient($this->config());
        return $cliente->login($email, $password, 'softsystem');
    }
    public function anularDocumento($nroFactVenta)
    {
        $doc = $this->documentoPorVenta($nroFactVenta);
        if (!$doc) {
            throw new \RuntimeException('No existe documento SIFEN para esta venta.');
        }
        if ($doc->estado === self::ESTADO_ANULADO) {
            return $doc;
        }
        $doc->update(['estado' => self::ESTADO_ANULADO]);
        return $doc->fresh();
    }
    public function estaFacturada($nroFactVenta)
    {
        $doc = $this->documentoPorVenta($nroFactVenta);
        return $doc && $doc->estado === self::ESTADO_APROBADO;
    }
    public function datosKuDe($nroFactVenta)
    {
        $documento = $this->documentoPorVenta($nroFactVenta);
        if (!$documento || $documento->estado !== self::ESTADO_APROBADO) {
            throw new \RuntimeException('No hay KuDE disponible para esta venta.');
        }
        $venta = SifenVentaData::cargar($nroFactVenta);
        $config = $this->config();
        $empresa = Empresa::first();
        $qrUrl = $this->urlQrDocumento($documento, $venta);
        $qrImage = $qrUrl ? base64_encode($this->generarQrPng($qrUrl, 220)) : null;
        return compact('documento', 'venta', 'config', 'empresa', 'qrUrl', 'qrImage');
    }
    public function urlQrDocumento(SifenDocumento $documento, array $venta = null)
    {
        return !empty($documento->qr_url) ? $documento->qr_url : null;
    }
    public function generarQrPng($contenido, $size = 220)
    {
        if (empty($contenido)) {
            return null;
        }
        $renderer = new \BaconQrCode\Renderer\Image\Png();
        $renderer->setWidth((int) $size);
        $renderer->setHeight((int) $size);
        $renderer->setMargin(1);
        $writer = new \BaconQrCode\Writer($renderer);
        return $writer->writeString(
            $contenido,
            \BaconQrCode\Encoder\Encoder::DEFAULT_BYTE_MODE_ECODING,
            \BaconQrCode\Common\ErrorCorrectionLevel::M
        );
    }
    private function defaults()
    {
        return array_merge([
            'activo' => false,
            'modo_emision' => 'api',
            'ambiente' => 'test',
            'establecimiento' => '001',
            'punto_expedicion' => '001',
            'ultimo_numero' => 0,
            'url_test' => 'https://sifen-test.set.gov.py/de/ws/sync/recibe',
            'url_prod' => 'https://sifen.set.gov.py/de/ws/sync/recibe',
            'api_enviar_sifen' => true,
            'api_enviar_correo' => false,
        ], SifenMt150::defaultsConfig());
    }
}
