-- =============================================================================
-- SOFTSYSTEM - SCRIPT SQL: MODIFICACIONES DE PERMISOS, PRIVILEGIOS Y ACCIONES
-- Fecha: 28/09/2026
-- Descripción:
--   1. Agrega campos de categorización, rutas e iconos a la tabla `formularios`.
--   2. Agrega permiso de exportación/impresión y timestamps a la tabla `permiso`.
--   3. Agrega clave identificadora a la tabla `accion`.
--   4. Crea la tabla `accion_rol` para vincular acciones y privilegios especiales por rol.
--   5. Agrega campos de descripción y color a la tabla `roles`.
--   6. Inserta/actualiza módulos del sistema y acciones críticas por defecto.
-- =============================================================================

-- 1. MODIFICACIONES EN TABLA `formularios`
ALTER TABLE `formularios`
  ADD COLUMN IF NOT EXISTS `for_categoria` VARCHAR(60) NULL DEFAULT 'OPERACIONES' AFTER `for_submenu`,
  ADD COLUMN IF NOT EXISTS `for_icono` VARCHAR(60) NULL DEFAULT 'fas fa-window-maximize' AFTER `for_categoria`,
  ADD COLUMN IF NOT EXISTS `for_ruta` VARCHAR(100) NULL AFTER `for_icono`,
  ADD COLUMN IF NOT EXISTS `for_orden` INT(11) NOT NULL DEFAULT 0 AFTER `for_ruta`,
  ADD COLUMN IF NOT EXISTS `activo` TINYINT(1) NOT NULL DEFAULT 1 AFTER `for_orden`;

-- 2. MODIFICACIONES EN TABLA `permiso`
ALTER TABLE `permiso`
  ADD COLUMN IF NOT EXISTS `per_export` CHAR(1) NOT NULL DEFAULT '0' COMMENT '1: Permite exportar/imprimir reportes' AFTER `per_del`,
  ADD COLUMN IF NOT EXISTS `created_at` TIMESTAMP NULL DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP NULL DEFAULT NULL;

-- 3. MODIFICACIONES EN TABLA `accion`
ALTER TABLE `accion`
  ADD COLUMN IF NOT EXISTS `clave_accion` VARCHAR(60) NULL AFTER `for_codigo`,
  ADD INDEX IF NOT EXISTS `idx_accion_clave` (`clave_accion`);

-- 4. CREACIÓN DE TABLA `accion_rol` (SI NO EXISTE)
CREATE TABLE IF NOT EXISTS `accion_rol` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cod_per` INT(10) UNSIGNED NOT NULL,
  `cod_rol` INT(10) UNSIGNED NOT NULL,
  `permitido` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `accion_rol_unique` (`cod_per`, `cod_rol`),
  CONSTRAINT `fk_accion_rol_cod_per` FOREIGN KEY (`cod_per`) REFERENCES `accion` (`cod_per`) ON DELETE CASCADE,
  CONSTRAINT `fk_accion_rol_cod_rol` FOREIGN KEY (`cod_rol`) REFERENCES `roles` (`cod_rol`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. MODIFICACIONES EN TABLA `roles`
ALTER TABLE `roles`
  ADD COLUMN IF NOT EXISTS `descripcion_rol` VARCHAR(150) NULL AFTER `nom_rol`,
  ADD COLUMN IF NOT EXISTS `color_rol` VARCHAR(25) NULL DEFAULT '#0a4d36' AFTER `descripcion_rol`,
  ADD COLUMN IF NOT EXISTS `activo` TINYINT(1) NOT NULL DEFAULT 1 AFTER `color_rol`;

-- 6. ACTUALIZACIÓN DE MÓDULOS DEL SISTEMA EN `formularios`
-- Actualizar categorías e iconos principales
UPDATE `formularios` SET `for_categoria` = 'OPERACIONES', `for_icono` = 'fas fa-shopping-cart', `for_ruta` = 'venta', `for_orden` = 10, `activo` = 1 WHERE `for_nombre` = 'ventas';
UPDATE `formularios` SET `for_categoria` = 'OPERACIONES', `for_icono` = 'fas fa-file-invoice-dollar', `for_ruta` = 'presupuesto.index', `for_orden` = 20, `activo` = 1 WHERE `for_nombre` = 'presupuesto';
UPDATE `formularios` SET `for_categoria` = 'OPERACIONES', `for_icono` = 'fas fa-truck-loading', `for_ruta` = 'compra', `for_orden` = 30, `activo` = 1 WHERE `for_nombre` = 'compra';

UPDATE `formularios` SET `for_categoria` = 'CAJA Y COBROS', `for_icono` = 'fas fa-door-open', `for_ruta` = 'apertura', `for_orden` = 40, `activo` = 1 WHERE `for_nombre` = 'apert_cierres_caja';
UPDATE `formularios` SET `for_categoria` = 'CAJA Y COBROS', `for_icono` = 'fas fa-cash-register', `for_ruta` = 'movimiento', `for_orden` = 50, `activo` = 1 WHERE `for_nombre` = 'movimiento_caja';
UPDATE `formularios` SET `for_categoria` = 'CAJA Y COBROS', `for_icono` = 'fas fa-hand-holding-usd', `for_ruta` = 'cobro', `for_orden` = 60, `activo` = 1 WHERE `for_nombre` = 'cobranzas';
UPDATE `formularios` SET `for_categoria` = 'CAJA Y COBROS', `for_icono` = 'fas fa-file-invoice', `for_ruta` = 'infctacobrar', `for_orden` = 70, `activo` = 1 WHERE `for_nombre` = 'ctas_cobrar';

UPDATE `formularios` SET `for_categoria` = 'CATALOGO', `for_icono` = 'fas fa-boxes', `for_ruta` = 'articulo', `for_orden` = 80, `activo` = 1 WHERE `for_nombre` = 'articulos';
UPDATE `formularios` SET `for_categoria` = 'CATALOGO', `for_icono` = 'fas fa-users', `for_ruta` = 'cliente.index', `for_orden` = 110, `activo` = 1 WHERE `for_nombre` = 'clientes';
UPDATE `formularios` SET `for_categoria` = 'CATALOGO', `for_icono` = 'fas fa-industry', `for_ruta` = 'proveedor.index', `for_orden` = 120, `activo` = 1 WHERE `for_nombre` = 'proveedor';

UPDATE `formularios` SET `for_categoria` = 'INVENTARIO', `for_icono` = 'fas fa-warehouse', `for_ruta` = 'infstock', `for_orden` = 130, `activo` = 1 WHERE `for_nombre` = 'inventario';
UPDATE `formularios` SET `for_categoria` = 'INVENTARIO', `for_icono` = 'fas fa-sliders-h', `for_ruta` = 'articulo.cm', `for_orden` = 140, `activo` = 1 WHERE `for_nombre` = 'ajuste';

UPDATE `formularios` SET `for_categoria` = 'INFORMES', `for_icono` = 'fas fa-chart-line', `for_ruta` = 'infventa', `for_orden` = 150, `activo` = 1 WHERE `for_nombre` = 'inf_venta';
UPDATE `formularios` SET `for_categoria` = 'INFORMES', `for_icono` = 'fas fa-chart-bar', `for_ruta` = 'infcompra', `for_orden` = 160, `activo` = 1 WHERE `for_nombre` = 'inf_compra';
UPDATE `formularios` SET `for_categoria` = 'INFORMES', `for_icono` = 'fas fa-receipt', `for_ruta` = 'infcobro', `for_orden` = 170, `activo` = 1 WHERE `for_nombre` = 'inf_cobros';
UPDATE `formularios` SET `for_categoria` = 'INFORMES', `for_icono` = 'fas fa-clipboard-list', `for_ruta` = 'infstock', `for_orden` = 180, `activo` = 1 WHERE `for_nombre` = 'inf_stock';
UPDATE `formularios` SET `for_categoria` = 'INFORMES', `for_icono` = 'fas fa-chart-pie', `for_ruta` = 'resumen', `for_orden` = 190, `activo` = 1 WHERE `for_nombre` = 'inf_resumen';

UPDATE `formularios` SET `for_categoria` = 'AUDITORIA', `for_icono` = 'fas fa-ban', `for_ruta` = 'anularventa', `for_orden` = 200, `activo` = 1 WHERE `for_nombre` = 'anular_venta';
UPDATE `formularios` SET `for_categoria` = 'AUDITORIA', `for_icono` = 'fas fa-times-circle', `for_ruta` = 'anularcobro', `for_orden` = 220, `activo` = 1 WHERE `for_nombre` = 'anular_cobros';

UPDATE `formularios` SET `for_categoria` = 'CONFIGURACION', `for_icono` = 'fas fa-th-list', `for_ruta` = 'seccion.index', `for_orden` = 240, `activo` = 1 WHERE `for_nombre` = 'presentacion';
UPDATE `formularios` SET `for_categoria` = 'CONFIGURACION', `for_icono` = 'fas fa-balance-scale', `for_ruta` = 'unidades.index', `for_orden` = 250, `activo` = 1 WHERE `for_nombre` = 'unidad';
UPDATE `formularios` SET `for_categoria` = 'CONFIGURACION', `for_icono` = 'fas fa-file-invoice', `for_ruta` = 'reffactura.index', `for_orden` = 260, `activo` = 1 WHERE `for_nombre` = 'reffactura';
UPDATE `formularios` SET `for_categoria` = 'CONFIGURACION', `for_icono` = 'fas fa-building', `for_ruta` = 'empresa.index', `for_orden` = 270, `activo` = 1 WHERE `for_nombre` = 'empresa';
UPDATE `formularios` SET `for_categoria` = 'CONFIGURACION', `for_icono` = 'fas fa-map-marker-alt', `for_ruta` = 'ciudad.index', `for_orden` = 280, `activo` = 1 WHERE `for_nombre` = 'ciudad';
UPDATE `formularios` SET `for_categoria` = 'CONFIGURACION', `for_icono` = 'fas fa-user-cog', `for_ruta` = 'usuario', `for_orden` = 290, `activo` = 1 WHERE `for_nombre` = 'usuarios';

-- Insertar módulos modernos si no existen
INSERT IGNORE INTO `formularios` (`for_nombre`, `for_titulo`, `for_tipo`, `for_menu`, `for_categoria`, `for_icono`, `for_ruta`, `for_orden`, `activo`) VALUES
  ('combos', 'Combos y Paquetes', 'CATALOGO', 'CATALOGO', 'CATALOGO', 'fas fa-layer-group', 'combo.index', 90, 1),
  ('ofertas', 'Ofertas y Promociones', 'CATALOGO', 'CATALOGO', 'CATALOGO', 'fas fa-tags', 'oferta.index', 100, 1),
  ('anular_compra', 'Anular Compras', 'AUDITORIA', 'AUDITORIA', 'AUDITORIA', 'fas fa-undo-alt', 'anularcompra', 210, 1),
  ('sifen', 'Facturación Electrónica SIFEN', 'CONFIGURACION', 'CONFIGURACION', 'CONFIGURACION', 'fas fa-qrcode', 'sifen.index', 230, 1),
  ('permisos', 'Privilegios y Permisos', 'CONFIGURACION', 'CONFIGURACION', 'CONFIGURACION', 'fas fa-user-shield', 'permiso.index', 300, 1);

-- 7. INSERTAR ACCIONES OPERATIVAS Y PRIVILEGIOS ESPECIALES
INSERT INTO `accion` (`for_codigo`, `clave_accion`, `nombre_accion`, `descripcion_accion`, `orden`, `tipo`)
SELECT f.for_codigo, 'venta_modificar_precio', 'Modificar Precios en Venta', 'Permite cambiar el precio unitario del artículo en la pantalla de cobranza/ticket.', 1, 'S'
FROM `formularios` f WHERE f.for_nombre = 'ventas' AND NOT EXISTS (SELECT 1 FROM `accion` WHERE `clave_accion` = 'venta_modificar_precio') LIMIT 1;

INSERT INTO `accion` (`for_codigo`, `clave_accion`, `nombre_accion`, `descripcion_accion`, `orden`, `tipo`)
SELECT f.for_codigo, 'venta_descuento', 'Aplicar Descuentos', 'Permite otorgar descuentos generales o por ítem en el punto de venta.', 2, 'S'
FROM `formularios` f WHERE f.for_nombre = 'ventas' AND NOT EXISTS (SELECT 1 FROM `accion` WHERE `clave_accion` = 'venta_descuento') LIMIT 1;

INSERT INTO `accion` (`for_codigo`, `clave_accion`, `nombre_accion`, `descripcion_accion`, `orden`, `tipo`)
SELECT f.for_codigo, 'venta_sin_stock', 'Vender Sin Stock', 'Permite emitir comprobantes de artículos cuya existencia actual sea cero o negativa.', 3, 'S'
FROM `formularios` f WHERE f.for_nombre = 'ventas' AND NOT EXISTS (SELECT 1 FROM `accion` WHERE `clave_accion` = 'venta_sin_stock') LIMIT 1;

INSERT INTO `accion` (`for_codigo`, `clave_accion`, `nombre_accion`, `descripcion_accion`, `orden`, `tipo`)
SELECT f.for_codigo, 'venta_autorizar_credito', 'Autorizar Venta a Crédito', 'Permite seleccionar condición crédito y generar cuotas para el cliente.', 4, 'S'
FROM `formularios` f WHERE f.for_nombre = 'ventas' AND NOT EXISTS (SELECT 1 FROM `accion` WHERE `clave_accion` = 'venta_autorizar_credito') LIMIT 1;

INSERT INTO `accion` (`for_codigo`, `clave_accion`, `nombre_accion`, `descripcion_accion`, `orden`, `tipo`)
SELECT f.for_codigo, 'caja_reabrir_turno', 'Reabrir Turnos Cerrados', 'Permite revertir o reabrir una caja que ya cuenta con cierre asentado.', 5, 'S'
FROM `formularios` f WHERE f.for_nombre = 'apert_cierres_caja' AND NOT EXISTS (SELECT 1 FROM `accion` WHERE `clave_accion` = 'caja_reabrir_turno') LIMIT 1;

INSERT INTO `accion` (`for_codigo`, `clave_accion`, `nombre_accion`, `descripcion_accion`, `orden`, `tipo`)
SELECT f.for_codigo, 'caja_movimientos_manuales', 'Movimientos Manuales de Caja', 'Permite ingresar o retirar dinero de caja chica por conceptos varios.', 6, 'S'
FROM `formularios` f WHERE f.for_nombre = 'apert_cierres_caja' AND NOT EXISTS (SELECT 1 FROM `accion` WHERE `clave_accion` = 'caja_movimientos_manuales') LIMIT 1;

INSERT INTO `accion` (`for_codigo`, `clave_accion`, `nombre_accion`, `descripcion_accion`, `orden`, `tipo`)
SELECT f.for_codigo, 'articulo_ver_costo', 'Ver Costos de Compra', 'Muestra el precio y costo de compra en catálogo y listados.', 7, 'S'
FROM `formularios` f WHERE f.for_nombre = 'articulos' AND NOT EXISTS (SELECT 1 FROM `accion` WHERE `clave_accion` = 'articulo_ver_costo') LIMIT 1;

INSERT INTO `accion` (`for_codigo`, `clave_accion`, `nombre_accion`, `descripcion_accion`, `orden`, `tipo`)
SELECT f.for_codigo, 'compra_modificar_costo', 'Actualizar Precios desde Compra', 'Permite actualizar automáticamente el costo de referencia y venta al asentar factura de compra.', 8, 'S'
FROM `formularios` f WHERE f.for_nombre = 'compra' AND NOT EXISTS (SELECT 1 FROM `accion` WHERE `clave_accion` = 'compra_modificar_costo') LIMIT 1;

INSERT INTO `accion` (`for_codigo`, `clave_accion`, `nombre_accion`, `descripcion_accion`, `orden`, `tipo`)
SELECT f.for_codigo, 'cobro_descuento_cuota', 'Condonar Interés / Descuento Cuota', 'Permite aplicar reducciones sobre cuotas vencidas o intereses acumulados.', 9, 'S'
FROM `formularios` f WHERE f.for_nombre = 'cobranzas' AND NOT EXISTS (SELECT 1 FROM `accion` WHERE `clave_accion` = 'cobro_descuento_cuota') LIMIT 1;

-- Asignar todas las acciones al Administrador (cod_rol = 4)
INSERT IGNORE INTO `accion_rol` (`cod_per`, `cod_rol`, `permitido`, `created_at`, `updated_at`)
SELECT a.cod_per, 4, 1, NOW(), NOW() FROM `accion` a WHERE a.clave_accion IS NOT NULL;

-- Asignar permisos completos a Administrador (cod_rol = 4) en todos los formularios
INSERT INTO `permiso` (`for_codigo`, `cod_rol`, `per_open`, `per_add`, `per_edit`, `per_del`, `per_export`, `created_at`, `updated_at`)
SELECT f.for_codigo, 4, '1', '1', '1', '1', '1', NOW(), NOW()
FROM `formularios` f
ON DUPLICATE KEY UPDATE `per_open` = '1', `per_add` = '1', `per_edit` = '1', `per_del` = '1', `per_export` = '1', `updated_at` = NOW();

-- Fin del script SQL

