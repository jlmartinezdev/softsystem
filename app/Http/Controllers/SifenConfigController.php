<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\SifenConfig;

use App\Empresa;

use App\Services\SifenService;

use App\Support\SifenMt150;
use App\Services\Sifen\SifenSoapClient;



class SifenConfigController extends Controller

{

    protected $sifen;



    public function __construct(SifenService $sifen)

    {

        $this->middleware('auth');

        $this->sifen = $sifen;

    }



    public function index()

    {

        $config = $this->configParaVista($this->sifen->config());

        $empresa = Empresa::first();

        $documentos = $this->sifen->documentosRecientes(30);

        $faltantes = $this->sifen->validarConfig();

        $catalogos = SifenMt150::catalogos();

        $notasTecnicas = SifenMt150::notasTecnicas();

        return view('sifen.config', compact(
            'config',
            'empresa',
            'documentos',
            'faltantes',
            'catalogos',
            'notasTecnicas'
        ));

    }



    public function getAll()

    {

        return $this->configParaVista($this->sifen->config());

    }



    public function getDocumentos()

    {

        return $this->sifen->documentosRecientes(100);

    }



    public function sincronizarEmpresa()

    {

        $config = $this->sifen->sincronizarDesdeEmpresa();

        return [

            'mensaje' => 'OK',

            'config' => $this->configParaVista($config),

        ];

    }



    public function update(Request $request)

    {

        $config = $this->sifen->config();



        $config->update([

            'activo' => $request->input('activo', 0) ? 1 : 0,

            'ambiente' => $request->input('ambiente', 'test'),

            'version_formato' => (int) $request->input('version_formato', SifenMt150::VERSION),

            'nota_tecnica' => $request->input('nota_tecnica', SifenMt150::NOTA_TECNICA_VIGENTE),

            'tipo_emision' => (int) $request->input('tipo_emision', 1),

            'ruc' => $request->input('ruc'),

            'razon_social' => $request->input('razon_social'),

            'tipo_contribuyente' => (int) $request->input('tipo_contribuyente', 2),

            'tipo_regimen' => $request->input('tipo_regimen'),

            'timbrado' => $request->input('timbrado'),

            'vigencia_desde' => $this->formatearFecha($request->input('vigencia_desde')),

            'vigencia_hasta' => $this->formatearFecha($request->input('vigencia_hasta')),

            'establecimiento' => $this->rellenar($request->input('establecimiento', '001'), 3),

            'punto_expedicion' => $this->rellenar($request->input('punto_expedicion', '001'), 3),

            'ultimo_numero' => (int) $request->input('ultimo_numero', 0),

            'tipo_documento' => (int) $request->input('tipo_documento', 1),

            'tipo_transaccion' => (int) $request->input('tipo_transaccion', 1),

            'tipo_impuesto' => (int) $request->input('tipo_impuesto', 1),

            'moneda' => strtoupper($request->input('moneda', 'PYG')),

            'indicador_presencia' => (int) $request->input('indicador_presencia', 1),

            'condicion_operacion_defecto' => (int) $request->input('condicion_operacion_defecto', 1),

            'cod_departamento' => $request->input('cod_departamento'),

            'cod_distrito' => $request->input('cod_distrito'),

            'cod_ciudad' => $request->input('cod_ciudad'),

            'direccion_emisor' => $request->input('direccion_emisor'),

            'numero_casa' => $request->input('numero_casa', '0'),

            'telefono_emisor' => $request->input('telefono_emisor'),

            'email_emisor' => $request->input('email_emisor'),

            'cod_actividad_economica' => $request->input('cod_actividad_economica'),

            'desc_actividad_economica' => $request->input('desc_actividad_economica'),

            'desc_departamento' => $request->input('desc_departamento'),

            'desc_distrito' => $request->input('desc_distrito'),

            'desc_ciudad' => $request->input('desc_ciudad'),

            'natura_receptor_defecto' => (int) $request->input('natura_receptor_defecto', 2),

            'tipo_operacion_defecto' => (int) $request->input('tipo_operacion_defecto', 2),

            'pais_receptor_defecto' => strtoupper($request->input('pais_receptor_defecto', 'PRY')),

            'csc_id' => $request->input('csc_id'),

            'csc_token' => $request->input('csc_token'),

            'cert_path' => $request->input('cert_path'),

            'cert_password' => $request->input('cert_password'),

            'url_test' => SifenSoapClient::normalizarUrlServicio($request->input('url_test'), 'test'),

            'url_prod' => SifenSoapClient::normalizarUrlServicio($request->input('url_prod'), 'prod'),

            'observaciones' => $request->input('observaciones'),

        ]);



        return 'OK';

    }



    private function rellenar($valor, $len)

    {

        $texto = is_numeric($valor) ? (string) $valor : trim((string) $valor);

        if ($texto === '') {

            return str_repeat('0', $len);

        }

        return str_pad($texto, $len, '0', STR_PAD_LEFT);

    }



    private function formatearFecha($valor)

    {

        if ($valor === null || $valor === '') {

            return null;

        }



        $texto = trim((string) $valor);

        if ($texto === '') {

            return null;

        }



        if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $texto, $coincidencia)) {

            return $coincidencia[1];

        }



        $timestamp = strtotime($texto);

        if ($timestamp === false) {

            return null;

        }



        return date('Y-m-d', $timestamp);

    }



    private function configParaVista(SifenConfig $config)

    {

        $datos = $config->toArray();

        $datos['vigencia_desde'] = $this->formatearFecha($config->vigencia_desde);

        $datos['vigencia_hasta'] = $this->formatearFecha($config->vigencia_hasta);

        $datos['version_formato'] = (int) ($datos['version_formato'] ?? SifenMt150::VERSION);

        $datos['nota_tecnica'] = $datos['nota_tecnica'] ?? SifenMt150::NOTA_TECNICA_VIGENTE;



        return $datos;

    }

}


