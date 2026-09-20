-- ============================================================
-- SoftSystem — update manual ofertas
-- Migración: 2026_08_11_000004_create_ofertas_table
-- ============================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `ofertas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(150) NOT NULL,
  `articulos_cod` INT UNSIGNED NOT NULL,
  `tipo` VARCHAR(20) NOT NULL DEFAULT 'cantidad',
  `cantidad_min` DECIMAL(12,2) NULL,
  `fecha_desde` DATE NULL,
  `fecha_hasta` DATE NULL,
  `descuento_tipo` VARCHAR(20) NOT NULL DEFAULT 'porcentaje',
  `descuento_valor` DECIMAL(14,2) NOT NULL DEFAULT 0,
  `activo` TINYINT NOT NULL DEFAULT 1,
  `observacion` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ofertas_articulos_cod_index` (`articulos_cod`),
  KEY `ofertas_activo_index` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Opcional: registrar migración Laravel
/*
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_08_11_000004_create_ofertas_table', IFNULL(MAX(batch), 0) + 1
FROM `migrations`
WHERE NOT EXISTS (
  SELECT 1 FROM `migrations`
  WHERE `migration` = '2026_08_11_000004_create_ofertas_table'
);
*/
