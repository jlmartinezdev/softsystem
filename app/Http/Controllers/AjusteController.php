<?php

namespace App\Http\Controllers;

use App\Ajuste;
use App\Support\MailSettings;
use Illuminate\Http\Request;
use Mail;

class AjusteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $ajuste = Ajuste::where('categoria', 'caja')->orderBy('id')->get();
        $mail = MailSettings::all();
        $mail['password'] = '';

        return view('configuracion', compact('ajuste', 'mail'));
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
}
