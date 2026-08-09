<?php



namespace App\Services;



use App\SifenConfig;

use App\SifenDocumento;

use App\Empresa;

use App\Support\SifenMt150;

use App\Services\Sifen\CertificateService;

use App\Services\Sifen\QrKuDeService;

use App\Services\Sifen\SifenEmisionPipeline;

use App\Services\Sifen\SifenSoapClient;

use App\Services\Sifen\SifenVentaData;

use DB;



class SifenService

{

    const ESTADO_PENDIENTE = 'pendiente';

    const ESTADO_ENVIADO = 'enviado';

    const ESTADO_APROBADO = 'aprobado';

    const ESTADO_RECHAZADO = 'rechazado';

    const ESTADO_ANULADO = 'anulado';



    protected $emision;

    protected $soap;

    protected $qr;



    public function __construct(SifenEmisionPipeline $emision, SifenSoapClient $soap, QrKuDeService $qr)

    {

        $this->emision = $emision;

        $this->soap = $soap;

        $this->qr = $qr;

    }



    public function config()

    {

        return SifenConfig::firstOrCreate(['id' => 1], $this->defaults());

    }



    public function isActivo()

    {

        $config = $this->config();

        return (bool) $config->activo;

    }



    public function urlServicio()

    {

        $config = $this->config();

        return $config->ambiente === 'prod' ? $config->url_prod : $config->url_test;

    }



    public function validarConfig()

    {

        $config = $this->config();

        $faltantes = [];



        if (empty($config->ruc)) {

            $faltantes[] = 'RUC del contribuyente';

        }

        if (empty($config->razon_social)) {

            $faltantes[] = 'Razón social';

        }

        if (empty($config->timbrado)) {

            $faltantes[] = 'Timbrado';

        }

        if (empty($config->vigencia_desde)) {

            $faltantes[] = 'Vigencia desde del timbrado';

        }

        if (empty($config->establecimiento) || empty($config->punto_expedicion)) {

            $faltantes[] = 'Establecimiento y punto de expedición';

        }

        if (empty($config->cert_path)) {

            $faltantes[] = 'Certificado digital';

        }

        if (empty($config->cert_password)) {

            $faltantes[] = 'Contraseña del certificado';

        }

        if (empty($config->csc_id)) {

            $faltantes[] = 'CSC ID';

        }

        if (empty($config->csc_token)) {

            $faltantes[] = 'CSC Token';

        }

        if ((int) $config->version_formato !== SifenMt150::VERSION) {

            $faltantes[] = 'Versión de formato distinta a MT v150 (dVerFor)';

        }

        if (empty($config->cod_departamento) || empty($config->cod_distrito) || empty($config->cod_ciudad)) {

            $faltantes[] = 'Ubicación geográfica del emisor (D149-D152)';

        }

        if (empty($config->desc_departamento) || empty($config->desc_distrito) || empty($config->desc_ciudad)) {

            $faltantes[] = 'Descripciones geográficas del emisor (Tabla 2.1 MT v150)';

        }

        if (empty($config->telefono_emisor)) {

            $faltantes[] = 'Teléfono del emisor (D117 dTelEmi)';

        }

        if (empty($config->email_emisor)) {

            $faltantes[] = 'Correo del emisor (D118 dEmailE)';

        }

        if (empty($config->direccion_emisor)) {

            $faltantes[] = 'Dirección del emisor (D107 dDirEmi)';

        }

        if (empty($config->moneda)) {

            $faltantes[] = 'Moneda de la operación (D015 cMoneOpe)';

        }

        if (empty($config->cod_actividad_economica)) {

            $faltantes[] = 'Actividad económica del emisor (Tabla 3 MT v150)';

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



    public function siguienteNumero()

    {

        return DB::transaction(function () {

            $config = SifenConfig::lockForUpdate()->findOrFail(1);

            $numero = (int) $config->ultimo_numero + 1;

            $config->update(['ultimo_numero' => $numero]);



            return [

                'numero' => $numero,

                'establecimiento' => str_pad($config->establecimiento, 3, '0', STR_PAD_LEFT),

                'punto_expedicion' => str_pad($config->punto_expedicion, 3, '0', STR_PAD_LEFT),

                'timbrado' => $config->timbrado,

                'tipo_documento' => $config->tipo_documento,

            ];

        });

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



        $numeracion = $this->siguienteNumero();



        return SifenDocumento::create([

            'nro_fact_ventas' => $nroFactVenta,

            'timbrado' => $numeracion['timbrado'],

            'establecimiento' => $numeracion['establecimiento'],

            'punto_expedicion' => $numeracion['punto_expedicion'],

            'numero' => $numeracion['numero'],

            'tipo_documento' => $numeracion['tipo_documento'],

            'codigo_seguridad' => \App\Services\Sifen\CdcGenerator::generarCodigoSeguridad(),

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



        if ($doc->estado === self::ESTADO_ENVIADO && !empty($doc->xml_enviado)) {

            return $this->reenviarDocumento($doc);

        }



        if ($doc->estado === self::ESTADO_PENDIENTE || $doc->estado === self::ESTADO_ENVIADO) {

            return $this->procesarEmision($doc);

        }



        return $doc;

    }



    public function procesarEmision(SifenDocumento $documento)

    {

        $config = $this->config();

        return $this->emision->procesar($documento, $config);

    }



    public function reenviarDocumento(SifenDocumento $documento)

    {

        $config = $this->config();



        if (empty($documento->xml_enviado)) {

            return $this->procesarEmision($documento);

        }



        try {

            $respuesta = $this->soap->enviarDocumento($config, $documento->xml_enviado);

        } catch (\Throwable $e) {

            $documento->update([

                'estado' => self::ESTADO_ENVIADO,

                'codigo_respuesta' => 'SOAP',

                'mensaje_respuesta' => $e->getMessage(),

                'fecha_envio' => date('Y-m-d H:i:s'),

            ]);



            throw new \RuntimeException('Reenvío SIFEN fallido: ' . $e->getMessage(), 0, $e);

        }



        if ($respuesta['aprobado']) {

            $documento->update([

                'estado' => self::ESTADO_APROBADO,

                'cdc' => $respuesta['cdc'] ?: $documento->cdc,

                'codigo_respuesta' => $respuesta['codigo'],

                'mensaje_respuesta' => $respuesta['mensaje'],

                'xml_respuesta' => $respuesta['raw'],

                'fecha_envio' => date('Y-m-d H:i:s'),

            ]);

        } else {

            $documento->update([

                'estado' => self::ESTADO_RECHAZADO,

                'codigo_respuesta' => $respuesta['codigo'],

                'mensaje_respuesta' => $respuesta['mensaje'],

                'xml_respuesta' => $respuesta['raw'],

                'fecha_envio' => date('Y-m-d H:i:s'),

            ]);



            throw new \RuntimeException('SIFEN rechazó el documento: [' . $respuesta['codigo'] . '] ' . $respuesta['mensaje']);

        }



        return $documento->fresh();

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



        DB::transaction(function () use ($doc) {

            $doc->update(['estado' => self::ESTADO_ANULADO]);

            $config = SifenConfig::lockForUpdate()->find(1);

            if ($config && (int) $config->ultimo_numero === (int) $doc->numero) {

                $config->update(['ultimo_numero' => max(0, (int) $config->ultimo_numero - 1)]);

            }

        });



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

        if (!empty($documento->qr_url)) {

            return $documento->qr_url;

        }



        if (empty($documento->cdc) || empty($documento->digest_value)) {

            return null;

        }



        $config = $this->config();

        if (!empty($documento->xml_enviado)) {
            return $this->qr->construirUrlDesdeXmlFirmado($config, $documento->xml_enviado);
        }

        $venta = $venta ?: SifenVentaData::cargar($documento->nro_fact_ventas);
        $fechaEmision = SifenVentaData::fechaEmision($venta['cabecera']);

        return $this->qr->construirUrl(
            $config,
            $documento->cdc,
            $fechaEmision,
            $venta['receptor'],
            $venta['totales'],
            $documento->digest_value
        );

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

            'ambiente' => 'test',

            'establecimiento' => '001',

            'punto_expedicion' => '001',

            'ultimo_numero' => 0,

            'url_test' => 'https://sifen-test.set.gov.py/de/ws/sync/recibe',

            'url_prod' => 'https://sifen.set.gov.py/de/ws/sync/recibe',

        ], SifenMt150::defaultsConfig());

    }

}


