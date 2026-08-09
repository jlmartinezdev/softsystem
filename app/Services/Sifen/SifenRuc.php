<?php

namespace App\Services\Sifen;

class SifenRuc
{
    public static function parse($rucRaw)
    {
        $digits = preg_replace('/\D/', '', (string) $rucRaw);
        if (strlen($digits) < 2) {
            throw new \RuntimeException('RUC inválido: ' . $rucRaw);
        }

        $dv = substr($digits, -1);
        $base = substr($digits, 0, -1);
        $base = ltrim($base, '0');
        if ($base === '') {
            $base = '0';
        }

        if (strlen($base) > 8) {
            throw new \RuntimeException('RUC inválido (base mayor a 8 dígitos): ' . $rucRaw);
        }

        return [
            'base' => $base,
            'dv' => $dv,
            'base_cdc' => str_pad($base, 8, '0', STR_PAD_LEFT),
        ];
    }
}
