<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodigoToOfertasTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('ofertas')) {
            return;
        }

        Schema::table('ofertas', function (Blueprint $table) {
            if (!Schema::hasColumn('ofertas', 'codigo')) {
                $table->string('codigo', 40)->nullable()->after('nombre');
                $table->index('codigo');
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('ofertas') || !Schema::hasColumn('ofertas', 'codigo')) {
            return;
        }

        Schema::table('ofertas', function (Blueprint $table) {
            $table->dropIndex(['codigo']);
            $table->dropColumn('codigo');
        });
    }
}
