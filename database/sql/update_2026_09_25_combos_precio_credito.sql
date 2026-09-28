-- ============================================================
-- SoftSystem — precio crédito en combos
-- Migración: 2026_09_25_000001_add_precio_credito_to_combos_table
-- Archivo: database/sql/update_2026_09_25_combos_precio_credito.sql
-- ============================================================

SET NAMES utf8mb4;

-- Agregar columna precio_credito si no existe
ALTER TABLE `combos`
  ADD COLUMN `precio_credito` DECIMAL(14,0) NOT NULL DEFAULT 0 AFTER `precio`;

/*
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_09_25_000001_add_precio_credito_to_combos_table', IFNULL(MAX(batch), 0) + 1
FROM `migrations`
WHERE NOT EXISTS (
  SELECT 1 FROM `migrations`
  WHERE `migration` = '2026_09_25_000001_add_precio_credito_to_combos_table'
);
*/
