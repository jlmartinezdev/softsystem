<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddActivoToUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('usuarios') && !Schema::hasColumn('usuarios', 'activo')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->tinyInteger('activo')->default(1)->after('direcc_usuarios');
            });
            DB::table('usuarios')->whereNull('activo')->update(['activo' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('usuarios') && Schema::hasColumn('usuarios', 'activo')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->dropColumn('activo');
            });
        }
    }
}
