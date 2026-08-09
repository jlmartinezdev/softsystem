<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmisorContactoGeoToSifenConfig extends Migration
{
    public function up()
    {
        Schema::table('sifen_config', function (Blueprint $table) {
            $table->string('telefono_emisor', 20)->nullable()->after('numero_casa');
            $table->string('email_emisor', 80)->nullable()->after('telefono_emisor');
            $table->string('desc_departamento', 30)->nullable()->after('cod_departamento');
            $table->string('desc_distrito', 30)->nullable()->after('cod_distrito');
            $table->string('desc_ciudad', 30)->nullable()->after('cod_ciudad');
            $table->string('desc_actividad_economica', 300)->nullable()->after('cod_actividad_economica');
        });
    }

    public function down()
    {
        Schema::table('sifen_config', function (Blueprint $table) {
            $table->dropColumn([
                'telefono_emisor',
                'email_emisor',
                'desc_departamento',
                'desc_distrito',
                'desc_ciudad',
                'desc_actividad_economica',
            ]);
        });
    }
}
