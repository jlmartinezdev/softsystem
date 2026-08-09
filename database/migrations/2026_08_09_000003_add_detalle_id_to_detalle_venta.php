<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDetalleIdToDetalleVenta extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('detalle_venta')) {
            return;
        }

        if (Schema::hasColumn('detalle_venta', 'detalle_id')) {
            return;
        }

        // Permitir varias líneas con el mismo ARTICULOS_cod (ítems libres VARIOS).
        DB::statement('ALTER TABLE detalle_venta DROP FOREIGN KEY detalle_venta_ibfk_1');
        DB::statement('ALTER TABLE detalle_venta DROP FOREIGN KEY detalle_venta_ibfk_2');
        DB::statement('ALTER TABLE detalle_venta DROP PRIMARY KEY');
        DB::statement('ALTER TABLE detalle_venta MODIFY ARTICULOS_cod INT(10) UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE detalle_venta ADD COLUMN detalle_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST');
        DB::statement('ALTER TABLE detalle_venta ADD CONSTRAINT detalle_venta_ibfk_1 FOREIGN KEY (ARTICULOS_cod) REFERENCES articulos (ARTICULOS_cod) ON DELETE NO ACTION ON UPDATE NO ACTION');
        DB::statement('ALTER TABLE detalle_venta ADD CONSTRAINT detalle_venta_ibfk_2 FOREIGN KEY (nro_fact_ventas) REFERENCES ventas (nro_fact_ventas) ON DELETE NO ACTION ON UPDATE NO ACTION');
        DB::statement('ALTER TABLE detalle_venta ADD INDEX detalle_venta_nro_fact_idx (nro_fact_ventas)');
        DB::statement('ALTER TABLE detalle_venta ADD INDEX detalle_venta_articulo_idx (ARTICULOS_cod)');
    }

    public function down()
    {
        if (!Schema::hasTable('detalle_venta') || !Schema::hasColumn('detalle_venta', 'detalle_id')) {
            return;
        }

        // Revertir solo si no hay duplicados ARTICULOS_cod + nro_fact_ventas.
        $dupes = DB::select(
            'SELECT ARTICULOS_cod, nro_fact_ventas, COUNT(*) AS c
             FROM detalle_venta
             GROUP BY ARTICULOS_cod, nro_fact_ventas
             HAVING c > 1'
        );

        if (count($dupes)) {
            throw new \RuntimeException(
                'No se puede revertir: hay ventas con más de una línea del mismo artículo (ítems libres).'
            );
        }

        DB::statement('ALTER TABLE detalle_venta DROP FOREIGN KEY detalle_venta_ibfk_1');
        DB::statement('ALTER TABLE detalle_venta DROP FOREIGN KEY detalle_venta_ibfk_2');
        DB::statement('ALTER TABLE detalle_venta DROP PRIMARY KEY');
        DB::statement('ALTER TABLE detalle_venta DROP COLUMN detalle_id');
        DB::statement('ALTER TABLE detalle_venta MODIFY ARTICULOS_cod INT(10) UNSIGNED NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE detalle_venta ADD PRIMARY KEY (ARTICULOS_cod, nro_fact_ventas)');
        DB::statement('ALTER TABLE detalle_venta ADD CONSTRAINT detalle_venta_ibfk_1 FOREIGN KEY (ARTICULOS_cod) REFERENCES articulos (ARTICULOS_cod) ON DELETE NO ACTION ON UPDATE NO ACTION');
        DB::statement('ALTER TABLE detalle_venta ADD CONSTRAINT detalle_venta_ibfk_2 FOREIGN KEY (nro_fact_ventas) REFERENCES ventas (nro_fact_ventas) ON DELETE NO ACTION ON UPDATE NO ACTION');
    }
}
