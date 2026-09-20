<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfertasTable extends Migration
{
    public function up()
    {
        Schema::create('ofertas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 150);
            $table->unsignedInteger('articulos_cod');
            // cantidad | fecha | ambos
            $table->string('tipo', 20)->default('cantidad');
            $table->decimal('cantidad_min', 12, 2)->nullable();
            $table->date('fecha_desde')->nullable();
            $table->date('fecha_hasta')->nullable();
            // porcentaje | monto | precio_fijo
            $table->string('descuento_tipo', 20)->default('porcentaje');
            $table->decimal('descuento_valor', 14, 2)->default(0);
            $table->tinyInteger('activo')->default(1);
            $table->text('observacion')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->index('articulos_cod');
            $table->index('activo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ofertas');
    }
}
