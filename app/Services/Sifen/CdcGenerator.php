<?php

namespace App\Services\Sifen;

use App\SifenConfig;
use App\SifenDocumento;

class CdcGenerator
{
    public static function generarCodigoSeguridad()
    {
        return str_pad((string) random_int(1, 999999999), 9, '0', STR_PAD_LEFT);
    }

    public static function construir(SifenConfig $config, SifenDocumento $documento, $codigoSeguridad, $fechaEmision)
    {
        $rucParsed = SifenRuc::parse($config->ruc);
        $rucBase = $rucParsed['base_cdc'];
        $dvRuc = $rucParsed['dv'];
        $fecha = date('Ymd', strtotime($fechaEmision));

        $base = '0'
            . (int) $config->tipo_documento
            . $rucBase
            . $dvRuc
            . str_pad($documento->establecimiento, 3, '0', STR_PAD_LEFT)
            . str_pad($documento->punto_expedicion, 3, '0', STR_PAD_LEFT)
            . str_pad($documento->numero, 7, '0', STR_PAD_LEFT)
            . (int) $config->tipo_contribuyente
            . $fecha
            . (int) $config->tipo_emision
            . str_pad($codigoSeguridad, 9, '0', STR_PAD_LEFT);

        $base = substr($base, 0, 43);

        return $base . self::digitoVerificador($base);
    }

    public static function digitoVerificador($cadena43)
    {
        $k = 2;
        $total = 0;
        for ($i = strlen($cadena43) - 1; $i >= 0; $i--) {
            $total += (int) $cadena43[$i] * $k;
            $k = $k >= 11 ? 2 : $k + 1;
        }
        $resto = $total % 11;
        $dv = $resto > 1 ? 11 - $resto : 0;

        return (string) $dv;
    }
}
