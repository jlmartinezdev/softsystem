<?php

namespace App\Services\Sifen;

use DB;

class SifenVentaData
{
    public static function cargar($nroFactVenta)
    {
        $cabecera = self::cabecera($nroFactVenta);
        if (!$cabecera) {
            throw new \RuntimeException('Venta no encontrada.');
        }

        $items = self::items($nroFactVenta);
        if (!count($items)) {
            throw new \RuntimeException('La venta no tiene ítems.');
        }

        $procesado = self::procesarItems($items, $cabecera);

        return [
            'cabecera' => $cabecera,
            'items' => $items,
            'lineas' => $procesado['lineas'],
            'totales' => $procesado['totales'],
            'receptor' => self::resolverReceptor($cabecera),
        ];
    }

    public static function cabecera($nroFactVenta)
    {
        $rows = DB::select(
            'SELECT v.*, c.cliente_nombre, c.cliente_direccion, c.cliente_ruc, c.cliente_ci
             FROM ventas v
             INNER JOIN clientes c ON v.clientes_cod = c.clientes_cod
             WHERE v.nro_fact_ventas = ?',
            [$nroFactVenta]
        );

        return count($rows) ? $rows[0] : null;
    }

    public static function items($nroFactVenta)
    {
        return DB::select(
            'SELECT dv.*,
                    COALESCE(NULLIF(TRIM(dv.descripcion_libre), \'\'), a.producto_nombre) AS producto_nombre,
                    a.producto_c_barra,
                    IFNULL(p.iva, 10) AS iva_tasa
             FROM detalle_venta dv
             INNER JOIN articulos a ON dv.ARTICULOS_cod = a.ARTICULOS_cod
             LEFT JOIN presentacion p ON a.present_cod = p.present_cod
             WHERE dv.nro_fact_ventas = ?
             ORDER BY dv.detalle_id',
            [$nroFactVenta]
        );
    }

    public static function procesarItems(array $items, $cabecera)
    {
        $lineas = [];
        $subExe = 0;
        $sub5 = 0;
        $sub10 = 0;
        $base5 = 0;
        $base10 = 0;
        $iva5 = 0;
        $iva10 = 0;
        $descuento = (float) ($cabecera->venta_descuento ?? 0);

        foreach ($items as $item) {
            $linea = self::calcularLineaItem($item);
            $lineas[] = $linea;

            if ($linea['tasa'] === 5) {
                $sub5 += $linea['tot_ope'];
                $base5 += $linea['base_grav'];
                $iva5 += $linea['liq_iva'];
            } elseif ($linea['tasa'] === 10) {
                $sub10 += $linea['tot_ope'];
                $base10 += $linea['base_grav'];
                $iva10 += $linea['liq_iva'];
            } else {
                $subExe += $linea['tot_ope'];
            }
        }

        $totOpe = $subExe + $sub5 + $sub10;
        $totalGral = max(0, round((float) $cabecera->venta_total, 0));
        if ($totalGral <= 0) {
            $totalGral = max(0, $totOpe - round($descuento, 0));
        }

        return [
            'lineas' => $lineas,
            'totales' => [
                'sub_exe' => round($subExe, 0),
                'sub_5' => round($sub5, 0),
                'sub_10' => round($sub10, 0),
                'base_grav_5' => round($base5, 0),
                'base_grav_10' => round($base10, 0),
                'iva_5' => round($iva5, 0),
                'iva_10' => round($iva10, 0),
                'total_iva' => round($iva5 + $iva10, 0),
                'tot_ope' => round($totOpe, 0),
                'total_gral' => $totalGral,
                'descuento' => round($descuento, 0),
                'items_count' => count($items),
            ],
        ];
    }

    public static function calcularLineaItem($item)
    {
        $cant = (float) $item->venta_cantidad;
        $precio = (float) $item->venta_precio;
        $bruto = round($precio * $cant, 0);
        $totOpe = $bruto;
        $tasa = (int) $item->iva_tasa;
        $propIva = 100;
        $afectacion = 3;
        $descAfectacion = 'Exento';
        $base = 0;
        $liq = 0;

        if ($tasa === 5) {
            $afectacion = 1;
            $descAfectacion = 'Gravado IVA';
            $base = round(($totOpe * ($propIva / 100)) / 1.05, 0);
            $liq = $totOpe - $base;
        } elseif ($tasa === 10) {
            $afectacion = 1;
            $descAfectacion = 'Gravado IVA';
            $base = round(($totOpe * ($propIva / 100)) / 1.1, 0);
            $liq = $totOpe - $base;
        }

        return [
            'item' => $item,
            'cant' => $cant,
            'precio' => $precio,
            'bruto' => $bruto,
            'tot_ope' => $totOpe,
            'tasa' => $tasa,
            'afectacion' => $afectacion,
            'desc_afectacion' => $descAfectacion,
            'base_grav' => $base,
            'liq_iva' => $liq,
        ];
    }

    public static function calcularTotales(array $items, $cabecera)
    {
        return self::procesarItems($items, $cabecera)['totales'];
    }

    public static function resolverReceptor($cabecera)
    {
        $ruc = preg_replace('/\D/', '', (string) ($cabecera->cliente_ruc ?? ''));
        $ci = trim((string) ($cabecera->cliente_ci ?? ''));

        if (strlen($ruc) >= 8) {
            $rucParsed = SifenRuc::parse($cabecera->cliente_ruc);

            return [
                'natura' => 1,
                'tipo_operacion' => 1,
                'pais' => 'PRY',
                'ruc' => $rucParsed['base'],
                'dv' => $rucParsed['dv'],
                'nombre' => $cabecera->cliente_nombre,
                'tipo_id' => null,
                'num_id' => null,
            ];
        }

        if ($ci === '') {
            return [
                'natura' => 2,
                'tipo_operacion' => 2,
                'pais' => 'PRY',
                'ruc' => null,
                'dv' => null,
                'nombre' => $cabecera->cliente_nombre,
                'tipo_id' => 5,
                'num_id' => '0',
            ];
        }

        return [
            'natura' => 2,
            'tipo_operacion' => 2,
            'pais' => 'PRY',
            'ruc' => null,
            'dv' => null,
            'nombre' => $cabecera->cliente_nombre,
            'tipo_id' => 1,
            'num_id' => $ci,
        ];
    }

    public static function digitoVerificadorRuc($rucBase)
    {
        $k = 2;
        $total = 0;
        for ($i = strlen($rucBase) - 1; $i >= 0; $i--) {
            $total += (int) $rucBase[$i] * $k;
            $k = $k >= 11 ? 2 : $k + 1;
        }
        $resto = $total % 11;
        $dv = $resto > 1 ? 11 - $resto : 0;

        return (string) $dv;
    }

    public static function fechaEmision($cabecera)
    {
        if (!empty($cabecera->venta_fecha)) {
            $ts = strtotime($cabecera->venta_fecha);
            if ($ts) {
                return date('Y-m-d\TH:i:s', $ts);
            }
        }

        return date('Y-m-d\TH:i:s');
    }
}
