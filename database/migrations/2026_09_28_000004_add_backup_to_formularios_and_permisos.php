<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddBackupToFormulariosAndPermisos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Insertar formulario si no existe
        $formulario = DB::table('formularios')->where('for_nombre', 'backup')->first();
        if (!$formulario) {
            $forId = DB::table('formularios')->insertGetId([
                'for_nombre' => 'backup',
                'for_titulo' => 'Copia de Seguridad (Backup)',
                'for_tipo' => 'CONFIGURACION',
                'for_menu' => 'CONFIGURACION',
                'for_submenu' => null,
                'for_categoria' => 'CONFIGURACION',
                'for_icono' => 'fas fa-database',
                'for_ruta' => 'backup.index',
                'for_orden' => 320,
                'activo' => 1
            ]);
        } else {
            $forId = $formulario->for_codigo;
        }

        // 2. Asignar permiso a rol Administrador (cod_rol = 4 o roles administradores)
        $adminRoles = DB::table('roles')->where('nom_rol', 'LIKE', '%Admin%')->pluck('cod_rol');
        foreach ($adminRoles as $rolId) {
            $existe = DB::table('permiso')->where('cod_rol', $rolId)->where('for_codigo', $forId)->first();
            if (!$existe) {
                DB::table('permiso')->insert([
                    'cod_rol' => $rolId,
                    'for_codigo' => $forId,
                    'permiso' => 1,
                    'per_open' => 1,
                    'per_add' => 1,
                    'per_edit' => 1,
                    'per_del' => 1,
                    'per_export' => 1
                ]);
            } else {
                DB::table('permiso')->where('cod_rol', $rolId)->where('for_codigo', $forId)->update([
                    'permiso' => 1,
                    'per_open' => 1,
                    'per_add' => 1,
                    'per_edit' => 1,
                    'per_del' => 1,
                    'per_export' => 1
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $formulario = DB::table('formularios')->where('for_nombre', 'backup')->first();
        if ($formulario) {
            DB::table('permiso')->where('for_codigo', $formulario->for_codigo)->delete();
            DB::table('formularios')->where('for_codigo', $formulario->for_codigo)->delete();
        }
    }
}
