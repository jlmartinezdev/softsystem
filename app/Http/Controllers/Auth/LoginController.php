<?php

namespace App\Http\Controllers\Auth;

use App\Empresa;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'user_usuarios';
    }

    public function showLoginForm()
    {
        $empresa = Empresa::first();
        $meses = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'setiembre', 'octubre', 'noviembre', 'diciembre'];
        $fecha = date('d') . ' de ' . $meses[(int) date('n') - 1] . ' de ' . date('Y');

        return view('auth.login', [
            'empresa' => $empresa,
            'fecha_turno' => $fecha,
        ]);
    }

    public function login(Request $request)
    {
        $usuario = trim((string) $request->input($this->username()));
        if ($usuario === '' || strcasecmp($usuario, 'Seleccionar') === 0) {
            return response()->json([
                'success' => 'no',
                'field' => 'usuario',
                'message' => 'Elegí tu usuario para abrir la caja.',
            ], 422);
        }

        if (trim((string) $request->input('password')) === '') {
            return response()->json([
                'success' => 'no',
                'field' => 'password',
                'message' => 'Escribí tu clave.',
            ], 422);
        }

        $request->merge([$this->username() => $usuario]);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return response()->json(['success' => 'si'], 200);
        }

        $this->incrementLoginAttempts($request);

        $remaining = $this->intentosRestantes($request);

        return response()->json([
            'success' => 'no',
            'field' => 'password',
            'remaining' => $remaining,
            'message' => $this->mensajeClaveIncorrecta($remaining),
        ], 200);
    }

    protected function intentosRestantes(Request $request)
    {
        if (!method_exists($this, 'limiter') || !method_exists($this, 'maxAttempts')) {
            return null;
        }

        $intentos = (int) $this->limiter()->attempts($this->throttleKey($request));

        return max(0, $this->maxAttempts() - $intentos);
    }

    protected function mensajeClaveIncorrecta($remaining)
    {
        if ($remaining === 1) {
            return 'La clave no coincide. Te queda 1 intento. Si no la recordás, pedí al administrador.';
        }
        if ($remaining === 0) {
            return 'La clave no coincide. El próximo intento incorrecto bloquea la caja.';
        }
        if (is_int($remaining) && $remaining > 1) {
            return 'La clave no coincide. Te quedan ' . $remaining . ' intentos.';
        }

        return 'La clave no coincide. Probá de nuevo.';
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = 60;
        if (method_exists($this, 'limiter') && method_exists($this, 'throttleKey')) {
            $seconds = (int) $this->limiter()->availableIn($this->throttleKey($request));
        }
        if ($seconds < 1) {
            $seconds = 60;
        }

        return response()->json([
            'success' => 'lock',
            'retry_after' => $seconds,
            'message' => 'Caja bloqueada. Esperá ' . $seconds . ' segundos.',
        ], 429)->header('Retry-After', (string) $seconds);
    }
}
