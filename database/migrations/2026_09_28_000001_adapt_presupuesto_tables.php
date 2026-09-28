<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AdaptPresupuestoTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Deshabilitar temporalmente verificación de claves foráneas para reorganizar
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('presupuesto_detalle');
        Schema::dropIfExists('presupuesto');

        Schema::create('presupuesto', function (Blueprint $table) {
            $table->increments('pre_numero');
            $table->integer('CLIENTES_cod')->nullable()->index();
            $table->unsignedInteger('cod_usuarios')->index();
            $table->unsignedInteger('suc_cod')->nullable()->index();
            $table->dateTime('pre_fecha');
            $table->integer('pre_validez_dias')->default(15);
            $table->date('pre_vencimiento')->nullable();
            $table->tinyInteger('pre_tipo')->default(1)->comment('1: Contado, 2: Credito');
            $table->string('estado', 25)->default('PENDIENTE')->index()->comment('PENDIENTE, APROBADO, FACTURADO, RECHAZADO, ANULADO');
            $table->decimal('descuento', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('total_exenta', 15, 2)->default(0);
            $table->decimal('total_iva5', 15, 2)->default(0);
            $table->decimal('total_iva10', 15, 2)->default(0);
            $table->decimal('total_iva', 15, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('cod_usuarios')->references('cod_usuarios')->on('usuarios')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('CLIENTES_cod')->references('CLIENTES_cod')->on('clientes')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('suc_cod')->references('suc_cod')->on('sucursales')->onDelete('set null')->onUpdate('cascade');
        });

        Schema::create('presupuesto_detalle', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('pre_numero')->index();
            $table->unsignedInteger('ARTICULOS_cod')->index();
            $table->string('descripcion_libre', 255)->nullable();
            $table->decimal('pre_det_cantidad', 10, 3)->default(1);
            $table->decimal('pre_det_precio', 15, 2)->default(0);
            $table->decimal('pre_det_descuento', 15, 2)->default(0);
            $table->decimal('pre_det_exenta', 15, 2)->default(0);
            $table->decimal('pre_det_gravada5', 15, 2)->default(0);
            $table->decimal('pre_det_gravada', 15, 2)->default(0)->comment('IVA 10%');
            $table->decimal('pre_det_subtotal', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('pre_numero')->references('pre_numero')->on('presupuesto')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('ARTICULOS_cod')->references('ARTICULOS_cod')->on('articulos')->onDelete('restrict')->onUpdate('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('presupuesto_detalle');
        Schema::dropIfExists('presupuesto');
        Schema::enableForeignKeyConstraints();
    }
}
