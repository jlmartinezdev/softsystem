-- Permitir varios ítems libres (mismo ARTICULOS_cod) en una venta.
-- Ejecutar si no usás migraciones artisan.

ALTER TABLE detalle_venta DROP FOREIGN KEY detalle_venta_ibfk_1;
ALTER TABLE detalle_venta DROP FOREIGN KEY detalle_venta_ibfk_2;
ALTER TABLE detalle_venta DROP PRIMARY KEY;
ALTER TABLE detalle_venta MODIFY ARTICULOS_cod INT(10) UNSIGNED NOT NULL;
ALTER TABLE detalle_venta ADD COLUMN detalle_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
ALTER TABLE detalle_venta
  ADD CONSTRAINT detalle_venta_ibfk_1 FOREIGN KEY (ARTICULOS_cod) REFERENCES articulos (ARTICULOS_cod) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT detalle_venta_ibfk_2 FOREIGN KEY (nro_fact_ventas) REFERENCES ventas (nro_fact_ventas) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE detalle_venta ADD INDEX detalle_venta_nro_fact_idx (nro_fact_ventas);
ALTER TABLE detalle_venta ADD INDEX detalle_venta_articulo_idx (ARTICULOS_cod);
