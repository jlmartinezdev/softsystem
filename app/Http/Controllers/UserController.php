<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuario = User::leftJoin('roles', 'usuarios.cod_rol', '=', 'roles.cod_rol')
            ->leftJoin('cargo', 'usuarios.cod_cargo', '=', 'cargo.cod_cargo')
            ->select(
                'usuarios.cod_usuarios',
                'usuarios.cod_rol',
                'usuarios.cod_cargo',
                'usuarios.nom_usuarios',
                'usuarios.user_usuarios',
                'usuarios.tel_usuarios',
                'usuarios.direcc_usuarios',
                'roles.nom_rol',
                'cargo.nom_cargo'
            )
            ->orderBy('usuarios.cod_usuarios', 'asc')
            ->get();

        $cargo = DB::select('SELECT * FROM cargo ORDER BY nom_cargo ASC');
        $roles = DB::select('SELECT * FROM roles ORDER BY nom_rol ASC');
        $currentUserId = auth()->check() ? auth()->user()->cod_usuarios : null;

        return view('usuario', compact('usuario', 'cargo', 'roles', 'currentUserId'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:70',
            'usuario' => 'required|string|max:50',
            'rol' => 'required',
            'cargo' => 'required',
        ], [
            'nombre.required' => 'El nombre del usuario es obligatorio.',
            'usuario.required' => 'El nombre de acceso (usuario) es obligatorio.',
            'rol.required' => 'Debe asignar un rol.',
            'cargo.required' => 'Debe asignar un cargo.',
        ]);

        $usuarioTrim = trim($request->usuario);
        $nombreTrim = trim($request->nombre);

        if ((int)$request->band === 0) {
            if (empty($request->password)) {
                return response()->json(['error' => 'Debe ingresar una contraseña para el nuevo usuario.'], 422);
            }

            $existe = User::whereRaw('LOWER(TRIM(user_usuarios)) = ?', [strtolower($usuarioTrim)])->exists();
            if ($existe) {
                return response()->json(['error' => 'El nombre de usuario "' . $usuarioTrim . '" ya existe. Elija otro.'], 422);
            }

            $ultimo = (int) User::max('cod_usuarios');
            $user = new User();
            $user->cod_usuarios = $ultimo + 1;
            $user->cod_rol = $request->rol;
            $user->cod_cargo = $request->cargo;
            $user->nom_usuarios = $nombreTrim;
            $user->user_usuarios = $usuarioTrim;
            $user->clave_usuarios = md5($request->password);
            $user->tel_usuarios = trim((string)$request->celular);
            $user->direcc_usuarios = trim((string)$request->direccion);
            $user->save();
        } else {
            $user = User::where('cod_usuarios', $request->codigo)->first();
            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado.'], 404);
            }

            $existe = User::whereRaw('LOWER(TRIM(user_usuarios)) = ?', [strtolower($usuarioTrim)])
                ->where('cod_usuarios', '!=', $request->codigo)
                ->exists();
            if ($existe) {
                return response()->json(['error' => 'El nombre de usuario "' . $usuarioTrim . '" ya está en uso por otro operador.'], 422);
            }

            $password = !empty($request->password) ? md5($request->password) : $user->clave_usuarios;

            $user->update([
                'cod_rol' => $request->rol,
                'cod_cargo' => $request->cargo,
                'nom_usuarios' => $nombreTrim,
                'user_usuarios' => $usuarioTrim,
                'clave_usuarios' => $password,
                'tel_usuarios' => trim((string)$request->celular),
                'direcc_usuarios' => trim((string)$request->direccion)
            ]);
        }

        return response()->json(['status' => 'OK']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    public function showAll()
    {
        return User::select(
            DB::raw('TRIM(user_usuarios) as user_usuarios'),
            DB::raw('TRIM(nom_usuarios) as nom_usuarios')
        )->get()->sortBy(function ($user) {
            $nombre = strtoupper(trim($user->nom_usuarios . ' ' . $user->user_usuarios));
            $sistema = (strpos($nombre, 'ADMIN') !== false || strpos($nombre, 'SISTEMA') !== false || strpos($nombre, 'ROOT') !== false) ? '1' : '0';

            return $sistema . '-' . $user->nom_usuarios;
        })->values();
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $currentUserId = auth()->check() ? auth()->user()->cod_usuarios : null;
        if ($currentUserId && (int)$id === (int)$currentUserId) {
            return response()->json(['error' => 'No podés eliminar tu propia cuenta mientras estés en sesión.'], 422);
        }

        try {
            $user = User::where('cod_usuarios', $id)->first();
            if (!$user) {
                return response()->json(['error' => 'El usuario no existe.'], 404);
            }
            $user->delete();
            return response()->json(['status' => 'OK']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se puede eliminar el usuario porque posee registros o ventas asociadas en el sistema.'], 422);
        }
    }
}
