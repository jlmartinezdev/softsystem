<?php

namespace App\Support;

use App\Ajuste;
use Exception;

class ComisionSettings
{
    /**
     * Obtiene toda la configuración del módulo de Comisiones de Técnicos y Vendedores.
     *
     * @return array
     */
    public static function all()
    {
        try {
            $rows = Ajuste::where('categoria', 'comisiones')->get()->pluck('value', 'name');
        } catch (Exception $e) {
            $rows = collect([]);
        }

        $activo = isset($rows['modulo_comisiones_activo'])
            ? ((string) $rows['modulo_comisiones_activo'] === '1')
            : true;

        $prefijo = trim((string) ($rows['comision_prefijo_liquidacion'] ?? 'LIQ-')) ?: 'LIQ-';
        $porcTaller = isset($rows['comision_porc_taller_default']) ? (float) $rows['comision_porc_taller_default'] : 40.0;
        $baseTaller = trim((string) ($rows['comision_base_taller_default'] ?? 'MANO_OBRA')) ?: 'MANO_OBRA';
        $porcVentaContado = isset($rows['comision_porc_venta_contado_default']) ? (float) $rows['comision_porc_venta_contado_default'] : 3.0;
        $porcVentaCredito = isset($rows['comision_porc_venta_credito_default']) ? (float) $rows['comision_porc_venta_credito_default'] : 2.0;
        $porcImei = isset($rows['comision_porc_imei_default']) ? (float) $rows['comision_porc_imei_default'] : 5.0;
        $baseImei = trim((string) ($rows['comision_base_imei_default'] ?? 'UTILIDAD_NETA')) ?: 'UTILIDAD_NETA';
        $porcCobranza = isset($rows['comision_porc_cobranza_default']) ? (float) $rows['comision_porc_cobranza_default'] : 2.0;

        return [
            'activo'                     => $activo,
            'prefijo_liquidacion'        => $prefijo,
            'porc_taller_default'        => $porcTaller,
            'base_taller_default'        => $baseTaller,
            'porc_venta_contado_default' => $porcVentaContado,
            'porc_venta_credito_default' => $porcVentaCredito,
            'porc_imei_default'          => $porcImei,
            'base_imei_default'          => $baseImei,
            'porc_cobranza_default'      => $porcCobranza,
        ];
    }

    /**
     * Indica si el módulo de Comisiones está habilitado por el Superadministrador.
     *
     * @return bool
     */
    public static function isModuloActivo()
    {
        try {
            $val = Ajuste::where('name', 'modulo_comisiones_activo')->value('value');
            if ($val === null || $val === '') {
                return true;
            }
            return (string) $val === '1';
        } catch (Exception $e) {
            return true;
        }
    }

    /**
     * Activa o desactiva el módulo (exclusivo Superadministrador).
     *
     * @param bool|int $activo
     * @return bool
     */
    public static function setModuloActivo($activo)
    {
        $val = $activo ? '1' : '0';
        $row = Ajuste::firstOrNew(['name' => 'modulo_comisiones_activo']);
        $row->categoria = 'comisiones';
        $row->tipo_form = 'checkbox';
        $row->value = $val;
        $row->save();

        return $val === '1';
    }

    /**
     * Guarda la configuración completa del módulo de Comisiones.
     *
     * @param array $data
     * @return void
     */
    public static function save(array $data)
    {
        $activo = !empty($data['activo']) ? '1' : '0';
        $prefijo = trim((string) ($data['prefijo_liquidacion'] ?? 'LIQ-')) ?: 'LIQ-';
        $porcTaller = max(0, (float) ($data['porc_taller_default'] ?? 40));
        $baseTaller = in_array($data['base_taller_default'] ?? '', ['MANO_OBRA', 'UTILIDAD_NETA', 'TOTAL_ORDEN'])
            ? $data['base_taller_default']
            : 'MANO_OBRA';
        $porcVentaContado = max(0, (float) ($data['porc_venta_contado_default'] ?? 3));
        $porcVentaCredito = max(0, (float) ($data['porc_venta_credito_default'] ?? 2));
        $porcImei = max(0, (float) ($data['porc_imei_default'] ?? 5));
        $baseImei = in_array($data['base_imei_default'] ?? '', ['UTILIDAD_NETA', 'PRECIO_VENTA'])
            ? $data['base_imei_default']
            : 'UTILIDAD_NETA';
        $porcCobranza = max(0, (float) ($data['porc_cobranza_default'] ?? 2));

        $map = [
            'modulo_comisiones_activo'            => $activo,
            'comision_prefijo_liquidacion'        => $prefijo,
            'comision_porc_taller_default'        => (string) $porcTaller,
            'comision_base_taller_default'        => $baseTaller,
            'comision_porc_venta_contado_default' => (string) $porcVentaContado,
            'comision_porc_venta_credito_default' => (string) $porcVentaCredito,
            'comision_porc_imei_default'          => (string) $porcImei,
            'comision_base_imei_default'          => $baseImei,
            'comision_porc_cobranza_default'      => (string) $porcCobranza,
        ];

        foreach ($map as $name => $value) {
            $row = Ajuste::firstOrNew(['name' => $name]);
            $row->categoria = 'comisiones';
            $row->tipo_form = 'text';
            $row->value = $value;
            $row->save();
        }
    }
}
