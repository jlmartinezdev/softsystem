<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use App\Support\ComisionSchema;

class CreateComisionesModuleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ComisionSchema::createTables();
        ComisionSchema::seedPermissionsAndSettings();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comision_liquidacion_detalles');
        Schema::dropIfExists('comision_liquidaciones');
        Schema::dropIfExists('comision_config_usuarios');
    }
}
