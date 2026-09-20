<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCombosTables extends Migration
{
    public function up()
    {
        Schema::create('combos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 150);
            $table->string('codigo', 40)->nullable();
            $table->decimal('precio', 14, 0)->default(0);
            $table->decimal('precio_lista', 14, 0)->default(0);
            $table->tinyInteger('activo')->default(1);
            $table->text('observacion')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('combo_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('combo_id');
            $table->unsignedInteger('articulos_cod');
            $table->decimal('cantidad', 12, 2)->default(1);
            $table->decimal('precio_ref', 14, 0)->default(0);

            $table->foreign('combo_id')->references('id')->on('combos')->onDelete('cascade');
            $table->index('articulos_cod');
        });
    }

    public function down()
    {
        Schema::dropIfExists('combo_items');
        Schema::dropIfExists('combos');
    }
}
