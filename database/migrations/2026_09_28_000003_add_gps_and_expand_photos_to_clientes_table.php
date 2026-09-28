<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddGpsAndExpandPhotosToClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            if (!Schema::hasColumn('clientes', 'cliente_latitud')) {
                $table->string('cliente_latitud', 35)->nullable()->after('cliente_direccion');
            }
            if (!Schema::hasColumn('clientes', 'cliente_longitud')) {
                $table->string('cliente_longitud', 35)->nullable()->after('cliente_latitud');
            }
            if (!Schema::hasColumn('clientes', 'cliente_ubicacion_url')) {
                $table->string('cliente_ubicacion_url', 255)->nullable()->after('cliente_longitud');
            }
        });

        // Expandir tamaño de campos de fotos de CI si existen
        try {
            DB::statement("ALTER TABLE `clientes` MODIFY `cliente_foto_ci_dorso` VARCHAR(255) NULL DEFAULT NULL");
            DB::statement("ALTER TABLE `clientes` MODIFY `cliente_foto_ci_reverso` VARCHAR(255) NULL DEFAULT NULL");
        } catch (\Throwable $e) {
            // Ignorar si falla la alteración directa
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            if (Schema::hasColumn('clientes', 'cliente_ubicacion_url')) {
                $table->dropColumn('cliente_ubicacion_url');
            }
            if (Schema::hasColumn('clientes', 'cliente_longitud')) {
                $table->dropColumn('cliente_longitud');
            }
            if (Schema::hasColumn('clientes', 'cliente_latitud')) {
                $table->dropColumn('cliente_latitud');
            }
        });
    }
}
