<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateSifenTables extends Migration
{
    public function up()
    {
        Schema::create('sifen_config', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->boolean('activo')->default(false);
            $table->string('ambiente', 10)->default('test');
            $table->string('id_software', 50)->nullable();
            $table->string('ruc', 20)->nullable();
            $table->string('razon_social', 255)->nullable();
            $table->string('timbrado', 20)->nullable();
            $table->date('vigencia_desde')->nullable();
            $table->date('vigencia_hasta')->nullable();
            $table->string('establecimiento', 3)->default('001');
            $table->string('punto_expedicion', 3)->default('001');
            $table->unsignedInteger('ultimo_numero')->default(0);
            $table->unsignedTinyInteger('tipo_documento')->default(1);
            $table->string('csc_id', 10)->nullable();
            $table->string('csc_token', 255)->nullable();
            $table->string('cert_path', 255)->nullable();
            $table->string('cert_password', 255)->nullable();
            $table->string('url_test', 255)->nullable();
            $table->string('url_prod', 255)->nullable();
            $table->text('observaciones')->nullable();
        });

        Schema::create('sifen_documentos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('nro_fact_ventas');
            $table->string('cdc', 44)->nullable()->unique();
            $table->string('timbrado', 20)->nullable();
            $table->string('establecimiento', 3)->nullable();
            $table->string('punto_expedicion', 3)->nullable();
            $table->unsignedInteger('numero')->nullable();
            $table->unsignedTinyInteger('tipo_documento')->default(1);
            $table->string('estado', 20)->default('pendiente');
            $table->string('codigo_respuesta', 20)->nullable();
            $table->text('mensaje_respuesta')->nullable();
            $table->longText('xml_enviado')->nullable();
            $table->longText('xml_respuesta')->nullable();
            $table->string('qr_url', 500)->nullable();
            $table->dateTime('fecha_emision')->nullable();
            $table->dateTime('fecha_envio')->nullable();
            $table->timestamps();

            $table->index('nro_fact_ventas');
            $table->index('estado');
        });

        DB::table('sifen_config')->insert([
            'id' => 1,
            'activo' => 0,
            'ambiente' => 'test',
            'establecimiento' => '001',
            'punto_expedicion' => '001',
            'ultimo_numero' => 0,
            'tipo_documento' => 1,
            'url_test' => 'https://sifen-test.set.gov.py/de/ws/sync/recibe.wsdl',
            'url_prod' => 'https://sifen.set.gov.py/de/ws/sync/recibe.wsdl',
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('sifen_documentos');
        Schema::dropIfExists('sifen_config');
    }
}
