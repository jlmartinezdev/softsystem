<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AdaptExistingCombosTables extends Migration
{
    public function up()
    {
        // Tablas legacy vacías: las rearmamos al esquema actual
        Schema::dropIfExists('combo_detalle');
        Schema::dropIfExists('combo_items');
        Schema::dropIfExists('combos');

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
            $table->index('combo_id');
            $table->index('articulos_cod');
        });
    }

    public function down()
    {
        Schema::dropIfExists('combo_items');
        Schema::dropIfExists('combos');

        Schema::create('combos', function (Blueprint $table) {
            $table->increments('id_combo');
            $table->string('desc_combo', 100)->nullable();
            $table->string('sta_combo', 10)->nullable();
        });

        Schema::create('combo_detalle', function (Blueprint $table) {
            $table->unsignedInteger('id_combo');
            $table->unsignedInteger('ARTICULOS_cod');
            $table->decimal('cantidad', 12, 2)->nullable();
        });
    }
}
