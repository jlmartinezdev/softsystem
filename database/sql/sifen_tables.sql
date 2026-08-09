-- Facturación electrónica SIFEN (ejecutar si no usa migraciones Laravel)

CREATE TABLE IF NOT EXISTS `sifen_config` (
  `id` tinyint(3) unsigned NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 0,
  `ambiente` varchar(10) NOT NULL DEFAULT 'test',
  `id_software` varchar(50) DEFAULT NULL,
  `ruc` varchar(20) DEFAULT NULL,
  `razon_social` varchar(255) DEFAULT NULL,
  `timbrado` varchar(20) DEFAULT NULL,
  `vigencia_desde` date DEFAULT NULL,
  `vigencia_hasta` date DEFAULT NULL,
  `establecimiento` varchar(3) NOT NULL DEFAULT '001',
  `punto_expedicion` varchar(3) NOT NULL DEFAULT '001',
  `ultimo_numero` int(10) unsigned NOT NULL DEFAULT 0,
  `tipo_documento` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `csc_id` varchar(10) DEFAULT NULL,
  `csc_token` varchar(255) DEFAULT NULL,
  `cert_path` varchar(255) DEFAULT NULL,
  `cert_password` varchar(255) DEFAULT NULL,
  `url_test` varchar(255) DEFAULT NULL,
  `url_prod` varchar(255) DEFAULT NULL,
  `observaciones` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `sifen_config` (`id`, `activo`, `ambiente`, `establecimiento`, `punto_expedicion`, `ultimo_numero`, `tipo_documento`, `url_test`, `url_prod`)
VALUES (1, 0, 'test', '001', '001', 0, 1,
  'https://sifen-test.set.gov.py/de/ws/sync/recibe.wsdl',
  'https://sifen.set.gov.py/de/ws/sync/recibe.wsdl');

CREATE TABLE IF NOT EXISTS `sifen_documentos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nro_fact_ventas` int(10) unsigned NOT NULL,
  `cdc` varchar(44) DEFAULT NULL,
  `timbrado` varchar(20) DEFAULT NULL,
  `establecimiento` varchar(3) DEFAULT NULL,
  `punto_expedicion` varchar(3) DEFAULT NULL,
  `numero` int(10) unsigned DEFAULT NULL,
  `tipo_documento` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `estado` varchar(20) NOT NULL DEFAULT 'pendiente',
  `codigo_respuesta` varchar(20) DEFAULT NULL,
  `mensaje_respuesta` text,
  `xml_enviado` longtext,
  `xml_respuesta` longtext,
  `qr_url` varchar(500) DEFAULT NULL,
  `fecha_emision` datetime DEFAULT NULL,
  `fecha_envio` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sifen_documentos_cdc_unique` (`cdc`),
  KEY `sifen_documentos_nro_fact_ventas_index` (`nro_fact_ventas`),
  KEY `sifen_documentos_estado_index` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
