<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrecioCreditoToCombosTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('combos')) {
            return;
        }

        Schema::table('combos', function (Blueprint $table) {
            if (!Schema::hasColumn('combos', 'precio_credito')) {
                $table->decimal('precio_credito', 14, 0)->default(0)->after('precio');
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('combos') || !Schema::hasColumn('combos', 'precio_credito')) {
            return;
        }

        Schema::table('combos', function (Blueprint $table) {
            $table->dropColumn('precio_credito');
        });
    }
}
