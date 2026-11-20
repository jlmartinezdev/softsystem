<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use App\Support\ReparacionSchema;

class CreateReparacionesModuleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ReparacionSchema::createTables();
        ReparacionSchema::seedPermissionsAndSettings();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reparacion_garantias');
        Schema::dropIfExists('reparacion_historial');
        Schema::dropIfExists('reparacion_pagos');
        Schema::dropIfExists('reparacion_imagenes');
        Schema::dropIfExists('reparacion_repuestos');
        Schema::dropIfExists('reparaciones');
    }
}
