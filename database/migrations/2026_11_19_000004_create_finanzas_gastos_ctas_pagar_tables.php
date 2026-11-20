<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use App\Support\FinanzasSchema;

class CreateFinanzasGastosCtasPagarTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        FinanzasSchema::createTables();
        FinanzasSchema::seedDefaultCategories();
        FinanzasSchema::seedPermissionsAndSettings();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ctas_pagar_pagos');
        Schema::dropIfExists('ctas_pagar');
        Schema::dropIfExists('gastos_operativos');
        Schema::dropIfExists('gasto_categorias');
    }
}
