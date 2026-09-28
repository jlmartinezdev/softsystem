<?php

namespace App\Http\Controllers;

use App\Ajuste;
use App\Support\CameraSettings;
use App\Support\MailSettings;
use App\Support\MapSettings;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Mail;

class AjusteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permiso:ajuste_sistema,open')->only(['index']);
        $this->middleware('permiso:ajuste_sistema,edit')->only(['update', 'testMail', 'testCamara']);
    }

    public function index()
    {
        $ajuste = Ajuste::where('categoria', 'caja')->orderBy('id')->get();
        $mail = MailSettings::all();
        $mail['password'] = '';
        $camara = CameraSettings::publicConfig();
        $mapas = MapSettings::publicConfig();

        return view('configuracion', compact('ajuste', 'mail', 'camara', 'mapas'));
    }

    public function update(Request $request)
    {
        if ($request->has('caja')) {
            foreach ($request->caja as $caja) {
                Ajuste::where('name', $caja['name'])->update(['value' => $caja['value']]);
            }
        }

        if ($request->has('mail')) {
            MailSettings::save($request->mail);
        }

        if ($request->has('camara')) {
            CameraSettings::save($request->camara);
        }

        if ($request->has('mapas')) {
            MapSettings::save($request->mapas);
        }

        return response()->json(['ok' => true, 'message' => 'Ajustes actualizados']);
    }

    public function testMail(Request $request)
    {
        if ($request->has('mail')) {
            MailSettings::save($request->mail);
        }

        $cfg = MailSettings::all();
        $recipients = MailSettings::recipients();

        if ($cfg['host'] === '' || $cfg['username'] === '') {
            return response()->json([
                'ok' => false,
                'message' => 'Completá host y usuario SMTP.',
            ], 422);
        }

        if (!count($recipients)) {
            return response()->json([
                'ok' => false,
                'message' => 'Indicá al menos un destinatario válido en “Enviar a”.',
            ], 422);
        }

        try {
            MailSettings::apply();

            Mail::raw(
                "Prueba de correo SoftSystem\n\nSi recibís este mensaje, la configuración SMTP es correcta.\nFecha: " . date('d/m/Y H:i:s'),
                function ($message) use ($recipients, $cfg) {
                    $message->to($recipients)
                        ->subject('Prueba de correo — SoftSystem');
                }
            );

            return response()->json([
                'ok' => true,
                'message' => 'Correo de prueba enviado a: ' . implode(', ', $recipients),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'No se pudo enviar: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function testCamara(Request $request)
    {
        if ($request->has('camara')) {
            CameraSettings::save($request->camara);
        }

        $cfg = CameraSettings::all();
        $url = CameraSettings::snapshotUrl($cfg);

        if ($url === '') {
            return response()->json([
                'ok' => false,
                'message' => 'Indicá la URL o IP de la cámara.',
            ], 422);
        }

        try {
            $options = [
                'verify' => false,
                'timeout' => 10,
            ];

            if ($cfg['user'] !== '') {
                $options['auth'] = [$cfg['user'], $cfg['password'], 'digest'];
            }

            $client = new Client($options);
            $response = $client->get($url);
            $body = (string) $response->getBody();
            $ctype = $response->getHeaderLine('Content-Type');

            if ($response->getStatusCode() !== 200 || strlen($body) < 100) {
                return response()->json([
                    'ok' => false,
                    'message' => 'La cámara respondió pero no se recibió una imagen válida.',
                ], 422);
            }

            $mime = $ctype !== '' ? explode(';', $ctype)[0] : 'image/jpeg';
            if (stripos($mime, 'image/') !== 0) {
                $mime = 'image/jpeg';
            }

            return response()->json([
                'ok' => true,
                'message' => 'Captura OK desde ' . $url,
                'preview' => 'data:' . $mime . ';base64,' . base64_encode($body),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'No se pudo conectar: ' . $e->getMessage(),
            ], 422);
        }
    }
}
