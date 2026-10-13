<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\SifenConfig;
use App\Empresa;
use App\Services\SifenService;
use App\Services\Sifen\SifenApiClient;
class SifenConfigController extends Controller
{
    protected $sifen;
    public function __construct(SifenService $sifen)
    {
        $this->middleware('auth');
        $this->middleware('permiso:sifen,open')->only(['index', 'getAll', 'getDocumentos']);
        $this->middleware('permiso:sifen,edit')->only(['update', 'sincronizarEmpresa', 'probarApi', 'obtenerTokenApi']);
        $this->sifen = $sifen;
    }
    public function index()
    {
        $config = $this->configParaVista($this->sifen->config());
        $empresa = Empresa::first();
        $documentos = $this->sifen->documentosRecientes(30);
        $faltantes = $this->sifen->validarConfig();
        return view('sifen.config', compact(
            'config',
            'empresa',
            'documentos',
            'faltantes'
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
            'modo_emision' => 'api',
            'ambiente' => $request->input('ambiente', 'test') === 'prod' ? 'prod' : 'test',
            'moneda' => strtoupper($request->input('moneda', 'PYG') ?: 'PYG'),
            'api_url' => SifenApiClient::normalizarUrlBase($request->input('api_url')),
            'api_token' => $request->input('api_token'),
            'api_enviar_sifen' => $request->input('api_enviar_sifen', 1) ? 1 : 0,
            'api_enviar_correo' => $request->input('api_enviar_correo', 0) ? 1 : 0,
            'observaciones' => $request->input('observaciones'),
        ]);
        return 'OK';
    }

    public function probarApi()
    {
        try {
            $status = $this->sifen->probarApi();
            return [
                'mensaje' => 'Conexión OK con api_sifen',
                'status' => $status,
            ];
        } catch (\Throwable $e) {
            return response()->json(['mensaje' => $e->getMessage()], 422);
        }
    }

    public function obtenerTokenApi(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $resp = $this->sifen->obtenerTokenApi($request->email, $request->password);
            $token = $resp['token'] ?? null;
            if (!$token) {
                return response()->json(['mensaje' => 'La API no devolvió token.'], 422);
            }

            $config = $this->sifen->config();
            $config->update(['api_token' => $token]);

            return [
                'mensaje' => 'Token obtenido y guardado',
                'token' => $token,
                'token_type' => $resp['token_type'] ?? 'Bearer',
            ];
        } catch (\Throwable $e) {
            return response()->json(['mensaje' => $e->getMessage()], 422);
        }
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
        $datos['modo_emision'] = 'api';
        $datos['api_enviar_sifen'] = (bool) ($datos['api_enviar_sifen'] ?? true);
        $datos['api_enviar_correo'] = (bool) ($datos['api_enviar_correo'] ?? false);
        return $datos;
    }
}
