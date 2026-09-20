-- ============================================================
-- SoftSystem — código de barras en ofertas
-- Migración: 2026_08_14_000001_add_codigo_to_ofertas_table
-- ============================================================

SET NAMES utf8mb4;

-- Si la columna ya existe, este ALTER fallará: se puede ignorar.
ALTER TABLE `ofertas`
  ADD COLUMN `codigo` VARCHAR(40) NULL AFTER `nombre`;

ALTER TABLE `ofertas`
  ADD INDEX `ofertas_codigo_index` (`codigo`);

/*
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_08_14_000001_add_codigo_to_ofertas_table', IFNULL(MAX(batch), 0) + 1
FROM `migrations`
WHERE NOT EXISTS (
  SELECT 1 FROM `migrations`
  WHERE `migration` = '2026_08_14_000001_add_codigo_to_ofertas_table'
);
*/
