<?php

namespace App\Services\Sifen;

use App\SifenConfig;

class CertificateService
{
    public function cargarDesdeConfig(SifenConfig $config)
    {
        $path = trim((string) $config->cert_path);
        $password = (string) $config->cert_password;

        if ($path === '') {
            throw new \RuntimeException('No se configuró la ruta del certificado digital.');
        }

        if (!file_exists($path)) {
            throw new \RuntimeException('No se encontró el certificado digital en: ' . $path);
        }

        if ($password === '') {
            throw new \RuntimeException('No se configuró la contraseña del certificado digital.');
        }

        $raw = file_get_contents($path);
        $certs = [];
        if (!openssl_pkcs12_read($raw, $certs, $password)) {
            throw new \RuntimeException('No se pudo leer el certificado PKCS#12. Verifique la contraseña.');
        }

        $this->verificarLlavePrivada($certs['pkey'], $password);

        return [
            'cert' => $certs['cert'],
            'pkey' => $certs['pkey'],
            'extracerts' => $certs['extracerts'] ?? [],
        ];
    }

    public function verificarLlavePrivada($pkeyPem, $password)
    {
        $resource = openssl_pkey_get_private($pkeyPem, $password);
        if ($resource === false) {
            $resource = openssl_pkey_get_private($pkeyPem);
        }
        if ($resource === false) {
            $detalle = $this->ultimoErrorOpenSsl();
            throw new \RuntimeException(
                'No se pudo cargar la llave privada del certificado.'
                . ($detalle ? ' ' . $detalle : ' Verifique la contraseña del certificado.')
            );
        }

        if (function_exists('openssl_pkey_free')) {
            openssl_pkey_free($resource);
        }

        return true;
    }

    protected function ultimoErrorOpenSsl()
    {
        $mensajes = [];
        while ($msg = openssl_error_string()) {
            $mensajes[] = $msg;
        }

        return count($mensajes) ? end($mensajes) : '';
    }

    public function escribirTemporales(array $material)
    {
        $dir = storage_path('app/sifen/certs');
        if (!is_dir($dir)) {
            mkdir($dir, 0700, true);
        }

        $certPem = $material['cert'];
        if (!empty($material['extracerts']) && is_array($material['extracerts'])) {
            foreach ($material['extracerts'] as $extra) {
                $certPem .= "\n" . $extra;
            }
        }

        $certFile = $dir . '/client.pem';
        $keyFile = $dir . '/client.key';
        $combinedFile = $dir . '/client-combined.pem';

        file_put_contents($certFile, $certPem);
        file_put_contents($keyFile, $material['pkey']);
        file_put_contents($combinedFile, $certPem . "\n" . $material['pkey']);
        chmod($keyFile, 0600);
        chmod($combinedFile, 0600);

        return [
            'cert' => $this->rutaCurl($certFile),
            'key' => $this->rutaCurl($keyFile),
            'combined' => $this->rutaCurl($combinedFile),
        ];
    }

    public function diagnostico(SifenConfig $config)
    {
        $material = $this->cargarDesdeConfig($config);
        $info = openssl_x509_parse($material['cert']);
        $subject = $info['subject'] ?? [];
        $serial = (string) ($subject['serialNumber'] ?? '');
        $tipo = strtoupper((string) ($subject['OU'] ?? ''));
        $rucConfig = preg_replace('/\D/', '', (string) $config->ruc);
        $rucCert = '';

        if (preg_match('/RUC(\d+)/i', $serial, $m)) {
            $rucCert = $m[1];
        } elseif (preg_match('/CI(\d+)/i', $serial, $m)) {
            $rucCert = $m[1];
        } elseif (preg_match('/^\d+$/', $serial)) {
            $rucCert = $serial;
        }

        $coincideRuc = $rucConfig !== '' && $rucCert !== ''
            && strpos($rucConfig, $rucCert) === 0;

        return [
            'titular' => (string) ($subject['CN'] ?? ''),
            'tipo_certificado' => $tipo ?: 'desconocido',
            'identificacion_cert' => $serial,
            'ruc_config' => (string) $config->ruc,
            'ruc_coincide' => $coincideRuc,
            'valido_hasta' => !empty($info['validTo_time_t'])
                ? date('Y-m-d', $info['validTo_time_t'])
                : null,
            'vigente' => !empty($info['validTo_time_t']) && $info['validTo_time_t'] >= time(),
            'es_persona_fisica' => $tipo === 'F1',
            'advertencias' => $this->advertenciasCertificado($tipo, $serial, $coincideRuc, $config->ambiente),
        ];
    }

    protected function advertenciasCertificado($tipo, $serial, $coincideRuc, $ambiente)
    {
        $avisos = [];

        if ($ambiente === 'test') {
            $avisos[] = 'El ambiente test exige certificado habilitado en e-Kuatia (Marangatu) para sifen-test.set.gov.py.';
        }

        if ($tipo === 'F1' && stripos($serial, 'CI') === 0) {
            $avisos[] = 'El certificado es F1 (persona física, CI). Si factura a nombre de empresa (RUC), puede requerir certificado F2 con RUC en el subject.';
        }

        if (!$coincideRuc) {
            $avisos[] = 'La identificación del certificado no coincide claramente con el RUC configurado en SIFEN.';
        }

        return $avisos;
    }

    protected function rutaCurl($path)
    {
        $real = realpath($path);
        if ($real) {
            $path = $real;
        }

        return str_replace('\\', '/', $path);
    }
}
