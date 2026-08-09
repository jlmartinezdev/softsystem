<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddMt150FieldsToSifenConfig extends Migration
{
    public function up()
    {
        Schema::table('sifen_config', function (Blueprint $table) {
            $table->unsignedSmallInteger('version_formato')->default(150)->after('ambiente');
            $table->string('nota_tecnica', 20)->default('NT23')->after('version_formato');
            $table->unsignedTinyInteger('tipo_emision')->default(1)->after('id_software');
            $table->unsignedTinyInteger('tipo_transaccion')->default(1)->after('tipo_documento');
            $table->unsignedTinyInteger('tipo_impuesto')->default(1)->after('tipo_transaccion');
            $table->string('moneda', 3)->default('PYG')->after('tipo_impuesto');
            $table->unsignedTinyInteger('tipo_contribuyente')->default(2)->after('razon_social');
            $table->string('tipo_regimen', 5)->nullable()->after('tipo_contribuyente');
            $table->unsignedTinyInteger('indicador_presencia')->default(1)->after('moneda');
            $table->unsignedTinyInteger('condicion_operacion_defecto')->default(1)->after('indicador_presencia');
            $table->string('cod_departamento', 5)->nullable()->after('condicion_operacion_defecto');
            $table->string('cod_distrito', 5)->nullable()->after('cod_departamento');
            $table->string('cod_ciudad', 5)->nullable()->after('cod_distrito');
            $table->string('direccion_emisor', 255)->nullable()->after('cod_ciudad');
            $table->string('numero_casa', 6)->default('0')->after('direccion_emisor');
            $table->string('cod_actividad_economica', 10)->nullable()->after('numero_casa');
            $table->unsignedTinyInteger('natura_receptor_defecto')->default(2)->after('cod_actividad_economica');
            $table->unsignedTinyInteger('tipo_operacion_defecto')->default(2)->after('natura_receptor_defecto');
            $table->string('pais_receptor_defecto', 3)->default('PRY')->after('tipo_operacion_defecto');
        });

        DB::table('sifen_config')->where('id', 1)->update([
            'version_formato' => 150,
            'nota_tecnica' => 'NT23',
            'tipo_emision' => 1,
            'tipo_transaccion' => 1,
            'tipo_impuesto' => 1,
            'moneda' => 'PYG',
            'tipo_contribuyente' => 2,
            'indicador_presencia' => 1,
            'condicion_operacion_defecto' => 1,
            'numero_casa' => '0',
            'natura_receptor_defecto' => 2,
            'tipo_operacion_defecto' => 2,
            'pais_receptor_defecto' => 'PRY',
        ]);
    }

    public function down()
    {
        Schema::table('sifen_config', function (Blueprint $table) {
            $table->dropColumn([
                'version_formato',
                'nota_tecnica',
                'tipo_emision',
                'tipo_transaccion',
                'tipo_impuesto',
                'moneda',
                'tipo_contribuyente',
                'tipo_regimen',
                'indicador_presencia',
                'condicion_operacion_defecto',
                'cod_departamento',
                'cod_distrito',
                'cod_ciudad',
                'direccion_emisor',
                'numero_casa',
                'cod_actividad_economica',
                'natura_receptor_defecto',
                'tipo_operacion_defecto',
                'pais_receptor_defecto',
            ]);
        });
    }
}
