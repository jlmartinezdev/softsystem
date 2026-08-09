-- Ítem libre / VARIOS
-- Ejecutar si no usás migraciones artisan.

-- 1) Columna descripción libre
ALTER TABLE detalle_venta
  ADD COLUMN descripcion_libre VARCHAR(255) NULL AFTER precio_compra;

-- 2) Artículo genérico (omitir si ya existe producto_c_barra = 'VARIOS')
INSERT INTO articulos (
  ARTICULOS_cod, uni_codigo, present_cod, producto_c_barra, producto_nombre,
  producto_costo_compra, producto_costo_venta, foto, producto_fecHab, producto_vencimiento,
  pre_venta1, pre_venta2, pre_venta3, pre_venta4, pre_venta5,
  producto_ubicacion, producto_peso, producto_factor,
  pre_margen1, pre_margen2, pre_margen3, pre_margen4, pre_margen5,
  producto_indicaciones, producto_dosis, producto_formula, producto_dimagen
)
SELECT
  (SELECT IFNULL(MAX(a.ARTICULOS_cod), 0) + 1 FROM articulos a),
  1, 1, 'VARIOS', 'VARIOS / ITEM LIBRE',
  0, 0, '', '0', '2030-01-01',
  0, 0, 0, 0, 0,
  '', '0', 1,
  0, 0, 0, 0, 0,
  '', '', '', ''
FROM DUAL
WHERE NOT EXISTS (
  SELECT 1 FROM articulos WHERE producto_c_barra = 'VARIOS'
);

-- 3) Stock 0 en sucursales
INSERT INTO stock (ARTICULOS_cod, suc_cod, cantidad, stock_fech_venc, lote_nro)
SELECT a.ARTICULOS_cod, s.suc_cod, 0, '2030-01-01', ''
FROM articulos a
CROSS JOIN sucursales s
WHERE a.producto_c_barra = 'VARIOS'
  AND NOT EXISTS (
    SELECT 1 FROM stock st
    WHERE st.ARTICULOS_cod = a.ARTICULOS_cod AND st.suc_cod = s.suc_cod
  );
