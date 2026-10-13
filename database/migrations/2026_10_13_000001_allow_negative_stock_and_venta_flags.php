<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AllowNegativeStockAndVentaFlags extends Migration
{
    public function up()
    {
        if (Schema::hasTable('stock')) {
            DB::statement('ALTER TABLE `stock` MODIFY `cantidad` INT(11) NULL');
        }

        if (!Schema::hasTable('configuraciones')) {
            return;
        }

        $flags = [
            'vender_sin_stock' => '1',
            'descontar_stock' => '1',
        ];

        foreach ($flags as $name => $value) {
            if (!DB::table('configuraciones')->where('name', $name)->exists()) {
                DB::table('configuraciones')->insert([
                    'name' => $name,
                    'categoria' => 'caja',
                    'value' => $value,
                    'tipo_form' => 'checkbox',
                ]);
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('configuraciones')) {
            DB::table('configuraciones')->whereIn('name', ['vender_sin_stock', 'descontar_stock'])->delete();
        }

        if (Schema::hasTable('stock')) {
            DB::statement('UPDATE `stock` SET `cantidad` = 0 WHERE `cantidad` < 0');
            DB::statement('ALTER TABLE `stock` MODIFY `cantidad` INT(10) UNSIGNED NULL');
        }
    }
}
