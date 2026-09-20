<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSifenApiModeFields extends Migration
{
    public function up()
    {
        Schema::table('sifen_config', function (Blueprint $table) {
            $table->string('modo_emision', 20)->default('local')->after('activo');
            $table->string('api_url', 255)->nullable()->after('url_prod');
            $table->text('api_token')->nullable()->after('api_url');
            $table->boolean('api_enviar_sifen')->default(true)->after('api_token');
            $table->boolean('api_enviar_correo')->default(false)->after('api_enviar_sifen');
        });

        Schema::table('sifen_documentos', function (Blueprint $table) {
            $table->unsignedBigInteger('api_documento_id')->nullable()->after('nro_fact_ventas');
            $table->index('api_documento_id');
        });
    }

    public function down()
    {
        Schema::table('sifen_documentos', function (Blueprint $table) {
            $table->dropIndex(['api_documento_id']);
            $table->dropColumn('api_documento_id');
        });

        Schema::table('sifen_config', function (Blueprint $table) {
            $table->dropColumn([
                'modo_emision',
                'api_url',
                'api_token',
                'api_enviar_sifen',
                'api_enviar_correo',
            ]);
        });
    }
}
