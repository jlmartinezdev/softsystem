-- Campos adicionales para emisión SIFEN (CDC, QR, firma)

ALTER TABLE `sifen_documentos`
  ADD COLUMN `codigo_seguridad` varchar(9) DEFAULT NULL AFTER `tipo_documento`,
  ADD COLUMN `digest_value` varchar(255) DEFAULT NULL AFTER `qr_url`;
