<?php

namespace App\Services\Sifen;

use DOMDocument;
use DOMXPath;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;

class XmlSigner
{
    public function firmar($xml, $privateKeyPem, $certificatePem, $passphrase = '')
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = false;
        if (!$doc->loadXML($xml)) {
            throw new \RuntimeException('XML DE inválido.');
        }

        $deNode = $doc->getElementsByTagName('DE')->item(0);
        if (!$deNode) {
            throw new \RuntimeException('No se encontró el nodo DE para firmar.');
        }

        $objKey = $this->crearLlaveFirma($privateKeyPem, $passphrase);

        $objDSig = new XMLSecurityDSig('');
        $objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);
        $objDSig->addReference(
            $deNode,
            XMLSecurityDSig::SHA256,
            [
                'http://www.w3.org/2000/09/xmldsig#enveloped-signature',
                'http://www.w3.org/2001/10/xml-exc-c14n#',
            ],
            [
                'overwrite' => false,
                'id_name' => 'Id',
                'force_uri' => true,
            ]
        );

        $objDSig->sign($objKey, $doc->documentElement);
        $objDSig->add509Cert($certificatePem, true, false, ['issuerSerial' => false]);

        $digestValue = $this->extraerDigestValue($doc);

        return [
            'xml' => $doc->saveXML(),
            'digest_value' => $digestValue,
        ];
    }

    protected function crearLlaveFirma($privateKeyPem, $passphrase)
    {
        $resource = openssl_pkey_get_private($privateKeyPem, $passphrase);
        if ($resource === false) {
            $resource = openssl_pkey_get_private($privateKeyPem);
        }
        if ($resource === false) {
            throw new \RuntimeException('No se pudo cargar la llave privada del certificado para firmar el XML.');
        }

        if (function_exists('openssl_pkey_free')) {
            openssl_pkey_free($resource);
        }

        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, ['type' => 'private']);
        if ($passphrase !== '') {
            $objKey->passphrase = $passphrase;
        }

        $intentos = [
            function () use ($objKey, $privateKeyPem) {
                $objKey->loadKey($privateKeyPem, false);
            },
            function () use ($objKey, $privateKeyPem, $passphrase) {
                $archivo = $this->escribirLlaveTemporal($privateKeyPem);
                try {
                    $objKey->loadKey($archivo, true);
                } finally {
                    @unlink($archivo);
                }
            },
        ];

        foreach ($intentos as $intentar) {
            try {
                $intentar();
                if (!empty($objKey->key)) {
                    return $objKey;
                }
            } catch (\Throwable $e) {
                // Probar siguiente estrategia de carga.
            }
        }

        throw new \RuntimeException('No se pudo preparar la llave privada para la firma XML.');
    }

    protected function escribirLlaveTemporal($privateKeyPem)
    {
        $dir = storage_path('app/sifen/certs');
        if (!is_dir($dir)) {
            mkdir($dir, 0700, true);
        }

        $archivo = $dir . '/sign-' . uniqid('', true) . '.key';
        file_put_contents($archivo, $privateKeyPem);
        chmod($archivo, 0600);

        return $archivo;
    }

    public function agregarQr($xml, $qrUrl)
    {
        if (strpos($xml, '<gCamFuFD') !== false) {
            throw new \RuntimeException('El XML ya contiene gCamFuFD.');
        }

        $qrXml = str_replace('&', '&amp;', $qrUrl);
        $bloque = '<gCamFuFD><dCarQR>' . $qrXml . '</dCarQR></gCamFuFD>';
        $cierre = strrpos($xml, '</rDE>');

        if ($cierre === false) {
            throw new \RuntimeException('No se encontró el cierre de rDE para insertar el QR.');
        }

        return substr($xml, 0, $cierre) . $bloque . substr($xml, $cierre);
    }

    protected function extraerDigestValue(DOMDocument $doc)
    {
        $xpath = new DOMXPath($doc);
        $xpath->registerNamespace('ds', XMLSecurityDSig::XMLDSIGNS);
        $digest = $xpath->evaluate('string(//ds:Reference/ds:DigestValue)');

        if ($digest === '') {
            throw new \RuntimeException('No se pudo obtener DigestValue de la firma.');
        }

        return $digest;
    }
}
