<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Support\ArticuloLibre;

class AddDescripcionLibreToDetalleVenta extends Migration
{
    public function up()
    {
        if (Schema::hasTable('detalle_venta') && !Schema::hasColumn('detalle_venta', 'descripcion_libre')) {
            Schema::table('detalle_venta', function (Blueprint $table) {
                $table->string('descripcion_libre', 255)->nullable()->after('precio_compra');
            });
        }

        ArticuloLibre::ensureExists();
    }

    public function down()
    {
        if (Schema::hasTable('detalle_venta') && Schema::hasColumn('detalle_venta', 'descripcion_libre')) {
            Schema::table('detalle_venta', function (Blueprint $table) {
                $table->dropColumn('descripcion_libre');
            });
        }
    }
}
