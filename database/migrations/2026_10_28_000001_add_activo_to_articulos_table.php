<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActivoToArticulosTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('articulos') || Schema::hasColumn('articulos', 'activo')) {
            return;
        }

        Schema::table('articulos', function (Blueprint $table) {
            $table->tinyInteger('activo')->default(1)->comment('1: visible en el buscador de ventas');
        });
    }

    public function down()
    {
        if (!Schema::hasTable('articulos') || !Schema::hasColumn('articulos', 'activo')) {
            return;
        }

        Schema::table('articulos', function (Blueprint $table) {
            $table->dropColumn('activo');
        });
    }
}
