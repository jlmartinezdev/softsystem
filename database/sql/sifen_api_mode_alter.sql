-- Modo de emisión vía API SIFEN (api_sifen)
ALTER TABLE `sifen_config`
  ADD COLUMN `modo_emision` VARCHAR(20) NOT NULL DEFAULT 'local' AFTER `activo`,
  ADD COLUMN `api_url` VARCHAR(255) NULL AFTER `url_prod`,
  ADD COLUMN `api_token` TEXT NULL AFTER `api_url`,
  ADD COLUMN `api_enviar_sifen` TINYINT(1) NOT NULL DEFAULT 1 AFTER `api_token`,
  ADD COLUMN `api_enviar_correo` TINYINT(1) NOT NULL DEFAULT 0 AFTER `api_enviar_sifen`;

ALTER TABLE `sifen_documentos`
  ADD COLUMN `api_documento_id` BIGINT UNSIGNED NULL AFTER `nro_fact_ventas`,
  ADD INDEX `sifen_documentos_api_documento_id_index` (`api_documento_id`);
