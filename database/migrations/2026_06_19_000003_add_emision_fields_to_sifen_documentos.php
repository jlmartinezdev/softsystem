<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmisionFieldsToSifenDocumentos extends Migration
{
    public function up()
    {
        Schema::table('sifen_documentos', function (Blueprint $table) {
            $table->string('codigo_seguridad', 9)->nullable()->after('tipo_documento');
            $table->string('digest_value', 255)->nullable()->after('qr_url');
        });
    }

    public function down()
    {
        Schema::table('sifen_documentos', function (Blueprint $table) {
            $table->dropColumn(['codigo_seguridad', 'digest_value']);
        });
    }
}
