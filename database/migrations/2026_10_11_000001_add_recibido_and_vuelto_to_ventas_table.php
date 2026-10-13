<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecibidoAndVueltoToVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('ventas')) {
            Schema::table('ventas', function (Blueprint $table) {
                if (!Schema::hasColumn('ventas', 'venta_recibido')) {
                    $table->decimal('venta_recibido', 15, 3)->default(0)->after('documento');
                }
                if (!Schema::hasColumn('ventas', 'venta_vuelto')) {
                    $table->decimal('venta_vuelto', 15, 3)->default(0)->after('venta_recibido');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('ventas')) {
            Schema::table('ventas', function (Blueprint $table) {
                $columns = [];
                if (Schema::hasColumn('ventas', 'venta_vuelto')) {
                    $columns[] = 'venta_vuelto';
                }
                if (Schema::hasColumn('ventas', 'venta_recibido')) {
                    $columns[] = 'venta_recibido';
                }
                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
}
