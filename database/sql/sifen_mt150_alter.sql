-- Campos Manual Técnico v150 / NT23 para sifen_config

ALTER TABLE `sifen_config`
  ADD COLUMN `version_formato` smallint(5) unsigned NOT NULL DEFAULT 150 AFTER `ambiente`,
  ADD COLUMN `nota_tecnica` varchar(20) NOT NULL DEFAULT 'NT23' AFTER `version_formato`,
  ADD COLUMN `tipo_emision` tinyint(3) unsigned NOT NULL DEFAULT 1 AFTER `id_software`,
  ADD COLUMN `tipo_transaccion` tinyint(3) unsigned NOT NULL DEFAULT 1 AFTER `tipo_documento`,
  ADD COLUMN `tipo_impuesto` tinyint(3) unsigned NOT NULL DEFAULT 1 AFTER `tipo_transaccion`,
  ADD COLUMN `moneda` varchar(3) NOT NULL DEFAULT 'PYG' AFTER `tipo_impuesto`,
  ADD COLUMN `tipo_contribuyente` tinyint(3) unsigned NOT NULL DEFAULT 2 AFTER `razon_social`,
  ADD COLUMN `tipo_regimen` varchar(5) DEFAULT NULL AFTER `tipo_contribuyente`,
  ADD COLUMN `indicador_presencia` tinyint(3) unsigned NOT NULL DEFAULT 1 AFTER `moneda`,
  ADD COLUMN `condicion_operacion_defecto` tinyint(3) unsigned NOT NULL DEFAULT 1 AFTER `indicador_presencia`,
  ADD COLUMN `cod_departamento` varchar(5) DEFAULT NULL AFTER `condicion_operacion_defecto`,
  ADD COLUMN `cod_distrito` varchar(5) DEFAULT NULL AFTER `cod_departamento`,
  ADD COLUMN `cod_ciudad` varchar(5) DEFAULT NULL AFTER `cod_distrito`,
  ADD COLUMN `direccion_emisor` varchar(255) DEFAULT NULL AFTER `cod_ciudad`,
  ADD COLUMN `numero_casa` varchar(6) NOT NULL DEFAULT '0' AFTER `direccion_emisor`,
  ADD COLUMN `cod_actividad_economica` varchar(10) DEFAULT NULL AFTER `numero_casa`,
  ADD COLUMN `natura_receptor_defecto` tinyint(3) unsigned NOT NULL DEFAULT 2 AFTER `cod_actividad_economica`,
  ADD COLUMN `tipo_operacion_defecto` tinyint(3) unsigned NOT NULL DEFAULT 2 AFTER `natura_receptor_defecto`,
  ADD COLUMN `pais_receptor_defecto` varchar(3) NOT NULL DEFAULT 'PRY' AFTER `tipo_operacion_defecto`;

UPDATE `sifen_config` SET
  `version_formato` = 150,
  `nota_tecnica` = 'NT23',
  `tipo_emision` = 1,
  `tipo_transaccion` = 1,
  `tipo_impuesto` = 1,
  `moneda` = 'PYG',
  `tipo_contribuyente` = 2,
  `indicador_presencia` = 1,
  `condicion_operacion_defecto` = 1,
  `numero_casa` = '0',
  `natura_receptor_defecto` = 2,
  `tipo_operacion_defecto` = 2,
  `pais_receptor_defecto` = 'PRY'
WHERE `id` = 1;
