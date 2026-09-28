<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Roles;
use App\Formulario;
use App\Permiso;
use App\Accion;
use App\AccionRol;
use App\User;
use DB;
use Auth;
use Exception;

class PermisoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('administrador');
    }

    /**
     * Display the permissions & privileges management view.
     */
    public function index(Request $request)
    {
        $roles = Roles::withCount('us')->orderBy('cod_rol')->get();
        $formularios = Formulario::where('activo', 1)
            ->orderBy('for_categoria')
            ->orderBy('for_orden')
            ->get();

        $acciones = Accion::with('formulario')
            ->orderBy('orden')
            ->get();

        $rolSeleccionadoId = (int) $request->get('rol', ($roles->first()->cod_rol ?? 4));

        // Permisos del rol seleccionado
        $permisosRol = Permiso::where('cod_rol', $rolSeleccionadoId)->get()->keyBy('for_codigo');

        // Acciones del rol seleccionado
        $accionesRol = AccionRol::where('cod_rol', $rolSeleccionadoId)->get()->keyBy('cod_per');

        return view('permiso', compact('roles', 'formularios', 'acciones', 'rolSeleccionadoId', 'permisosRol', 'accionesRol'));
    }

    /**
     * Return permissions and actions for a specific role as JSON.
     */
    public function getPermisosRol($cod_rol)
    {
        $rol = Roles::withCount('us')->findOrFail($cod_rol);

        $permisosRaw = DB::table('permiso')
            ->where('cod_rol', $cod_rol)
            ->get()
            ->keyBy('for_codigo');

        $accionesRaw = DB::table('accion_rol')
            ->where('cod_rol', $cod_rol)
            ->get()
            ->keyBy('cod_per');

        return response()->json([
            'success' => true,
            'rol' => $rol,
            'permisos' => $permisosRaw,
            'acciones' => $accionesRaw,
        ]);
    }

    /**
     * Save matrix of permissions and special privileges for a role.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cod_rol' => 'required|integer|exists:roles,cod_rol',
            'permisos' => 'nullable|array',
            'acciones' => 'nullable|array',
        ]);

        $codRol = (int) $request->cod_rol;
        $permisos = $request->input('permisos', []);
        $acciones = $request->input('acciones', []);

        DB::beginTransaction();
        try {
            $now = now();

            // 1. Guardar permisos por formulario
            foreach ($permisos as $forCodigo => $pData) {
                $open = !empty($pData['per_open']) ? '1' : '0';
                $add = !empty($pData['per_add']) ? '1' : '0';
                $edit = !empty($pData['per_edit']) ? '1' : '0';
                $del = !empty($pData['per_del']) ? '1' : '0';
                $export = !empty($pData['per_export']) ? '1' : '0';

                $permisoGeneral = ($open === '1' || $add === '1' || $edit === '1' || $del === '1' || $export === '1') ? '1' : '0';

                $exists = DB::table('permiso')
                    ->where('for_codigo', $forCodigo)
                    ->where('cod_rol', $codRol)
                    ->exists();

                if ($exists) {
                    DB::table('permiso')
                        ->where('for_codigo', $forCodigo)
                        ->where('cod_rol', $codRol)
                        ->update([
                            'permiso' => $permisoGeneral,
                            'per_open' => $open,
                            'per_add' => $add,
                            'per_edit' => $edit,
                            'per_del' => $del,
                            'per_export' => $export,
                            'updated_at' => $now,
                        ]);
                } else {
                    DB::table('permiso')->insert([
                        'for_codigo' => $forCodigo,
                        'cod_rol' => $codRol,
                        'permiso' => $permisoGeneral,
                        'per_open' => $open,
                        'per_add' => $add,
                        'per_edit' => $edit,
                        'per_del' => $del,
                        'per_export' => $export,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            // 2. Guardar acciones especiales
            foreach ($acciones as $codPer => $permitido) {
                $val = !empty($permitido) ? 1 : 0;

                $existAcc = DB::table('accion_rol')
                    ->where('cod_per', $codPer)
                    ->where('cod_rol', $codRol)
                    ->exists();

                if ($existAcc) {
                    DB::table('accion_rol')
                        ->where('cod_per', $codPer)
                        ->where('cod_rol', $codRol)
                        ->update([
                            'permitido' => $val,
                            'updated_at' => $now,
                        ]);
                } else {
                    DB::table('accion_rol')->insert([
                        'cod_per' => $codPer,
                        'cod_rol' => $codRol,
                        'permitido' => $val,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Privilegios y permisos guardados exitosamente.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar permisos: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create or update a role.
     */
    public function guardarRol(Request $request)
    {
        $request->validate([
            'nom_rol' => 'required|string|max:50',
            'descripcion_rol' => 'nullable|string|max:150',
            'color_rol' => 'nullable|string|max:25',
        ]);

        $codRol = $request->input('cod_rol');

        if ($codRol) {
            $rol = Roles::findOrFail($codRol);
            $rol->nom_rol = trim($request->nom_rol);
            $rol->descripcion_rol = $request->descripcion_rol;
            $rol->color_rol = $request->color_rol ?? '#0a4d36';
            $rol->save();

            $msg = 'Rol actualizado correctamente.';
        } else {
            $rol = new Roles();
            $rol->nom_rol = trim($request->nom_rol);
            $rol->descripcion_rol = $request->descripcion_rol;
            $rol->color_rol = $request->color_rol ?? '#0a4d36';
            $rol->activo = 1;
            $rol->save();

            // Asignar permisos básicos de apertura
            $formularios = Formulario::where('activo', 1)->get();
            $now = now();
            foreach ($formularios as $f) {
                DB::table('permiso')->insertOrIgnore([
                    'for_codigo' => $f->for_codigo,
                    'cod_rol' => $rol->cod_rol,
                    'permiso' => '0',
                    'per_open' => '0',
                    'per_add' => '0',
                    'per_edit' => '0',
                    'per_del' => '0',
                    'per_export' => '0',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $msg = 'Nuevo rol creado exitosamente.';
        }

        $rol->loadCount('us');

        return response()->json([
            'success' => true,
            'message' => $msg,
            'rol' => $rol,
        ]);
    }

    /**
     * Delete a custom role.
     */
    public function eliminarRol($cod_rol)
    {
        if ((int) $cod_rol === 4) {
            return response()->json([
                'success' => false,
                'message' => 'No podés eliminar el rol de Administrador principal del sistema.',
            ], 422);
        }

        $rol = Roles::withCount('us')->findOrFail($cod_rol);

        if ($rol->us_count > 0) {
            return response()->json([
                'success' => false,
                'message' => "No se puede eliminar el rol '{$rol->nom_rol}' porque tiene {$rol->us_count} usuario(s) asignado(s).",
            ], 422);
        }

        DB::beginTransaction();
        try {
            DB::table('permiso')->where('cod_rol', $cod_rol)->delete();
            DB::table('accion_rol')->where('cod_rol', $cod_rol)->delete();
            $rol->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rol eliminado correctamente.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar rol: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Copy permissions and actions from one role to another.
     */
    public function copiarPermisos(Request $request)
    {
        $request->validate([
            'rol_origen' => 'required|integer|exists:roles,cod_rol',
            'rol_destino' => 'required|integer|exists:roles,cod_rol',
        ]);

        $origen = (int) $request->rol_origen;
        $destino = (int) $request->rol_destino;

        if ($origen === $destino) {
            return response()->json([
                'success' => false,
                'message' => 'El rol de origen y destino deben ser diferentes.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            $permisosOrigen = DB::table('permiso')->where('cod_rol', $origen)->get();
            $accionesOrigen = DB::table('accion_rol')->where('cod_rol', $origen)->get();
            $now = now();

            foreach ($permisosOrigen as $po) {
                $exists = DB::table('permiso')
                    ->where('for_codigo', $po->for_codigo)
                    ->where('cod_rol', $destino)
                    ->exists();

                if ($exists) {
                    DB::table('permiso')
                        ->where('for_codigo', $po->for_codigo)
                        ->where('cod_rol', $destino)
                        ->update([
                            'permiso' => $po->permiso,
                            'per_open' => $po->per_open,
                            'per_add' => $po->per_add,
                            'per_edit' => $po->per_edit,
                            'per_del' => $po->per_del,
                            'per_export' => $po->per_export,
                            'updated_at' => $now,
                        ]);
                } else {
                    DB::table('permiso')->insert([
                        'for_codigo' => $po->for_codigo,
                        'cod_rol' => $destino,
                        'permiso' => $po->permiso,
                        'per_open' => $po->per_open,
                        'per_add' => $po->per_add,
                        'per_edit' => $po->per_edit,
                        'per_del' => $po->per_del,
                        'per_export' => $po->per_export,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            foreach ($accionesOrigen as $ao) {
                DB::table('accion_rol')->updateOrInsert(
                    ['cod_per' => $ao->cod_per, 'cod_rol' => $destino],
                    ['permitido' => $ao->permitido, 'updated_at' => $now]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permisos copiados exitosamente al rol de destino.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al copiar permisos: ' . $e->getMessage(),
            ], 500);
        }
    }
}
