-- ============================================================
-- SoftSystem — update manual (3 migraciones 2026-08-11)
-- 1) Cámara IP en configuraciones
-- 2/3) Combos (esquema final)
-- Archivo: database/sql/update_2026_08_11_camara_y_combos.sql
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- 1) Cámara CCTV → tabla configuraciones
-- ------------------------------------------------------------
INSERT INTO `configuraciones` (`name`, `categoria`, `value`, `tipo_form`)
SELECT 'camara_url', 'camara', '', 'text'
WHERE NOT EXISTS (SELECT 1 FROM `configuraciones` WHERE `name` = 'camara_url');

INSERT INTO `configuraciones` (`name`, `categoria`, `value`, `tipo_form`)
SELECT 'camara_user', 'camara', 'admin', 'text'
WHERE NOT EXISTS (SELECT 1 FROM `configuraciones` WHERE `name` = 'camara_user');

INSERT INTO `configuraciones` (`name`, `categoria`, `value`, `tipo_form`)
SELECT 'camara_password', 'camara', '', 'text'
WHERE NOT EXISTS (SELECT 1 FROM `configuraciones` WHERE `name` = 'camara_password');

INSERT INTO `configuraciones` (`name`, `categoria`, `value`, `tipo_form`)
SELECT 'camara_canal', 'camara', '102', 'text'
WHERE NOT EXISTS (SELECT 1 FROM `configuraciones` WHERE `name` = 'camara_canal');

-- ------------------------------------------------------------
-- 2/3) Combos
--     - Elimina solo legacy `combo_detalle` (si existe)
--     - Crea `combos` y `combo_items` si no existen
--     ATENCIÓN: si tenés `combos` vieja (id_combo/desc_combo/sta_combo)
--     y querés migrar al esquema nuevo, usá el bloque ALTERNATIVA abajo.
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `combo_detalle`;

CREATE TABLE IF NOT EXISTS `combos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(150) NOT NULL,
  `codigo` VARCHAR(40) NULL,
  `precio` DECIMAL(14,0) NOT NULL DEFAULT 0,
  `precio_lista` DECIMAL(14,0) NOT NULL DEFAULT 0,
  `activo` TINYINT NOT NULL DEFAULT 1,
  `observacion` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `combo_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `combo_id` INT UNSIGNED NOT NULL,
  `articulos_cod` INT UNSIGNED NOT NULL,
  `cantidad` DECIMAL(12,2) NOT NULL DEFAULT 1.00,
  `precio_ref` DECIMAL(14,0) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `combo_items_combo_id_index` (`combo_id`),
  KEY `combo_items_articulos_cod_index` (`articulos_cod`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- FK (se ignora si ya existe)
-- Si falla por FK duplicada, no pasa nada: la tabla ya está bien.
ALTER TABLE `combo_items`
  ADD CONSTRAINT `combo_items_combo_id_foreign`
  FOREIGN KEY (`combo_id`) REFERENCES `combos` (`id`)
  ON DELETE CASCADE;

SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------------
-- ALTERNATIVA: si `combos` existe con esquema VIEJO
-- (columnas id_combo, desc_combo, sta_combo) y está vacía,
-- descomentá este bloque y comentá el CREATE IF NOT EXISTS de arriba.
-- ------------------------------------------------------------
/*
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `combo_detalle`;
DROP TABLE IF EXISTS `combo_items`;
DROP TABLE IF EXISTS `combos`;

CREATE TABLE `combos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(150) NOT NULL,
  `codigo` VARCHAR(40) NULL,
  `precio` DECIMAL(14,0) NOT NULL DEFAULT 0,
  `precio_lista` DECIMAL(14,0) NOT NULL DEFAULT 0,
  `activo` TINYINT NOT NULL DEFAULT 1,
  `observacion` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `combo_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `combo_id` INT UNSIGNED NOT NULL,
  `articulos_cod` INT UNSIGNED NOT NULL,
  `cantidad` DECIMAL(12,2) NOT NULL DEFAULT 1.00,
  `precio_ref` DECIMAL(14,0) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `combo_items_combo_id_index` (`combo_id`),
  KEY `combo_items_articulos_cod_index` (`articulos_cod`),
  CONSTRAINT `combo_items_combo_id_foreign`
    FOREIGN KEY (`combo_id`) REFERENCES `combos` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SET FOREIGN_KEY_CHECKS = 1;
*/

-- ------------------------------------------------------------
-- Opcional: registrar migraciones en Laravel
-- ------------------------------------------------------------
/*
SET @batch := (SELECT IFNULL(MAX(batch), 0) + 1 FROM `migrations`);

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_08_11_000001_add_camera_settings_to_configuraciones', @batch
WHERE NOT EXISTS (
  SELECT 1 FROM `migrations`
  WHERE `migration` = '2026_08_11_000001_add_camera_settings_to_configuraciones'
);

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_08_11_000002_create_combos_tables', @batch
WHERE NOT EXISTS (
  SELECT 1 FROM `migrations`
  WHERE `migration` = '2026_08_11_000002_create_combos_tables'
);

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_08_11_000003_adapt_existing_combos_tables', @batch
WHERE NOT EXISTS (
  SELECT 1 FROM `migrations`
  WHERE `migration` = '2026_08_11_000003_adapt_existing_combos_tables'
);
*/
