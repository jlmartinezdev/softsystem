<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComboPreciosTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('combo_precios')) {
            Schema::create('combo_precios', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('combo_id');
                $table->integer('id_precio')->nullable();
                $table->decimal('precio', 15, 3)->default(0);
                $table->decimal('margen', 7, 3)->default(0);
                $table->tinyInteger('cant_cuota')->nullable();
                $table->decimal('monto_cuota', 15, 3)->default(0);

                $table->foreign('combo_id')->references('id')->on('combos')->onDelete('cascade');
                $table->index(['combo_id', 'cant_cuota']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('combo_precios');
    }
}
