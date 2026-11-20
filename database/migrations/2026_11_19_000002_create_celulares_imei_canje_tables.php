<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use App\Support\CelularImeiSchema;

class CreateCelularesImeiCanjeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        CelularImeiSchema::createTables();
        CelularImeiSchema::seedPermissionsAndSettings();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('celular_imei_historial');
        Schema::dropIfExists('celular_imei_imagenes');
        Schema::dropIfExists('celular_canjes');
        Schema::dropIfExists('celulares_imei');
    }
}
