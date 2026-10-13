<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddBotonPrincipalToConfiguraciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('configuraciones')) {
            try {
                DB::statement("ALTER TABLE `configuraciones` MODIFY `name` VARCHAR(50) NULL");
                DB::statement("ALTER TABLE `configuraciones` MODIFY `value` VARCHAR(255) NULL");
            } catch (\Throwable $e) {
                // Si la columna ya tiene ese tamaño o no permite modify, ignorar
            }

            if (!DB::table('configuraciones')->where('name', 'boton_principal')->exists()) {
                DB::table('configuraciones')->insert([
                    'name' => 'boton_principal',
                    'categoria' => 'caja',
                    'value' => 'cobrar_imprimir',
                    'tipo_form' => 'text'
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('configuraciones')) {
            DB::table('configuraciones')->where('name', 'boton_principal')->delete();
        }
    }
}
