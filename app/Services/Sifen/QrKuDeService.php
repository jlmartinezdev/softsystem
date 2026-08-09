<?php



namespace App\Services\Sifen;



use App\SifenConfig;

use DOMDocument;

use DOMXPath;



class QrKuDeService

{

    const NS_SIFEN = 'http://ekuatia.set.gov.py/sifen/xsd';

    const NS_DSIG = 'http://www.w3.org/2000/09/xmldsig#';



    /**

     * Genera la URL del QR leyendo los valores exactos del XML firmado (MT v150 §13.8).

     */

    public function construirUrlDesdeXmlFirmado(SifenConfig $config, $xmlFirmado)

    {

        $doc = new DOMDocument('1.0', 'UTF-8');

        $doc->preserveWhiteSpace = false;

        if (!$doc->loadXML($xmlFirmado)) {

            throw new \RuntimeException('XML firmado inválido para generar QR.');

        }



        $xpath = new DOMXPath($doc);

        $xpath->registerNamespace('s', self::NS_SIFEN);

        $xpath->registerNamespace('ds', self::NS_DSIG);



        $de = $doc->getElementsByTagNameNS(self::NS_SIFEN, 'DE')->item(0);

        if (!$de) {

            $de = $doc->getElementsByTagName('DE')->item(0);

        }

        if (!$de) {

            throw new \RuntimeException('No se encontró el nodo DE en el XML firmado.');

        }



        $cdc = $de->getAttribute('Id');

        if ($cdc === '') {

            throw new \RuntimeException('El DE no tiene atributo Id (CDC).');

        }



        $fecha = $this->textoNodo($xpath, 'dFeEmiDE');

        $rucRec = $this->textoNodo($xpath, 'dRucRec');

        if ($rucRec !== '') {

            $idRec = $rucRec . $this->textoNodo($xpath, 'dDVRec');

        } else {

            $idRec = $this->textoNodo($xpath, 'dNumIDRec');

            if ($idRec === '') {

                $idRec = '0';

            }

        }



        $totGral = $this->textoNodo($xpath, 'dTotGralOpe');

        if ($totGral === '') {

            $totGral = '0';

        }



        $totIva = $this->textoNodo($xpath, 'dTotIVA');

        if ($totIva === '') {

            $totIva = '0';

        }



        $cItems = $xpath->evaluate('count(//*[local-name()="gCamItem"])');

        $digest = $xpath->evaluate('string(//ds:DigestValue)');

        if ($digest === '') {

            throw new \RuntimeException('No se encontró DigestValue en la firma del DE.');

        }



        return $this->construirUrlConValores(

            $config,

            $cdc,

            $fecha,

            $idRec,

            $totGral,

            $totIva,

            (int) $cItems,

            $digest

        );

    }



    /**

     * @deprecated Preferir construirUrlDesdeXmlFirmado tras firmar el DE.

     */

    public function construirUrl(SifenConfig $config, $cdc, $fechaEmision, array $receptor, array $totales, $digestValue)

    {

        if (!empty($receptor['ruc'])) {

            $idRec = $receptor['ruc'] . $receptor['dv'];

        } else {

            $idRec = (string) ($receptor['num_id'] ?? '0');

            if ($idRec === '') {

                $idRec = '0';

            }

        }



        $iva5 = (float) ($totales['iva_5'] ?? 0);

        $iva10 = (float) ($totales['iva_10'] ?? 0);



        return $this->construirUrlConValores(

            $config,

            $cdc,

            $fechaEmision,

            $idRec,

            $this->num($totales['total_gral'], 4),

            $this->num($iva5 + $iva10, 4),

            (int) $totales['items_count'],

            $digestValue

        );

    }



    protected function construirUrlConValores(

        SifenConfig $config,

        $cdc,

        $fechaEmision,

        $idRec,

        $totGral,

        $totIva,

        $cItems,

        $digestValue

    ) {

        $host = $config->ambiente === 'prod'

            ? 'https://ekuatia.set.gov.py/consultas/qr'

            : 'https://ekuatia.set.gov.py/consultas-test/qr';



        $cscToken = (string) $config->csc_token;

        if ($cscToken === '') {

            throw new \RuntimeException('Configure el CSC Token en Configuración SIFEN.');

        }



        $idCsc = str_pad((string) ($config->csc_id ?: '1'), 4, '0', STR_PAD_LEFT);

        $fechaHex = bin2hex($fechaEmision);

        $digestHex = bin2hex((string) $digestValue);



        $query = 'nVersion=' . (int) $config->version_formato

            . '&Id=' . $cdc

            . '&dFeEmiDE=' . $fechaHex

            . '&dRucRec=' . $idRec

            . '&dTotGralOpe=' . $totGral

            . '&dTotIVA=' . $totIva

            . '&cItems=' . (int) $cItems

            . '&DigestValue=' . $digestHex

            . '&IdCSC=' . $idCsc;



        $cHashQr = hash('sha256', $query . $cscToken);



        return $host . '?' . $query . '&cHashQR=' . $cHashQr;

    }



    protected function textoNodo(DOMXPath $xpath, $nombreLocal)

    {

        return trim($xpath->evaluate('string(//*[local-name()="' . $nombreLocal . '"])'));

    }



    protected function num($value, $decimales = 0)

    {

        return number_format((float) $value, $decimales, '.', '');

    }

}


