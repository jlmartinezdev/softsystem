<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('articulo_unidades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('articulo_id');
            $table->unsignedBigInteger('unidad_id');
            $table->decimal('factor_conversion', 10, 2);
            $table->decimal('precio_venta', 10, 2);
            $table->boolean('es_principal')->default(false);
            $table->timestamps();

            $table->foreign('articulo_id')->references('articulos_cod')->on('articulos');
            $table->foreign('unidad_id')->references('uni_codigo')->on('unidad');
        });
    }

    public function down()
    {
        Schema::dropIfExists('articulo_unidades');
    }
}; 