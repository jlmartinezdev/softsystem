<?php

namespace App\Support;

use App\Ajuste;
use Exception;

class FinanzasSettings
{
    /**
     * Obtiene la configuración del módulo de Gastos, Cuentas a Pagar y Estado de Resultados.
     *
     * @return array
     */
    public static function all()
    {
        try {
            $rows = Ajuste::where('categoria', 'finanzas')->get()->pluck('value', 'name');
        } catch (Exception $e) {
            $rows = collect([]);
        }

        $activo = isset($rows['modulo_finanzas_activo'])
            ? ((string) $rows['modulo_finanzas_activo'] === '1')
            : true;

        $diasAlerta = isset($rows['finanzas_dias_alerta_vencimiento'])
            ? max(1, (int) $rows['finanzas_dias_alerta_vencimiento'])
            : 7;

        return [
            'activo'                  => $activo,
            'dias_alerta_vencimiento' => $diasAlerta,
        ];
    }

    /**
     * Indica si el módulo está habilitado por el Superadministrador.
     *
     * @return bool
     */
    public static function isModuloActivo()
    {
        try {
            $val = Ajuste::where('name', 'modulo_finanzas_activo')->value('value');
            if ($val === null || $val === '') {
                return true;
            }
            return (string) $val === '1';
        } catch (Exception $e) {
            return true;
        }
    }

    /**
     * Activa o desactiva el módulo (Exclusivo Superadministrador).
     *
     * @param bool|int $activo
     * @return bool
     */
    public static function setModuloActivo($activo)
    {
        $val = $activo ? '1' : '0';
        $row = Ajuste::firstOrNew(['name' => 'modulo_finanzas_activo']);
        $row->categoria = 'finanzas';
        $row->tipo_form = 'checkbox';
        $row->value = $val;
        $row->save();

        return $val === '1';
    }

    /**
     * Guarda la configuración del módulo.
     *
     * @param array $data
     * @return void
     */
    public static function save(array $data)
    {
        $activo = !empty($data['activo']) ? '1' : '0';
        $diasAlerta = max(1, (int) ($data['dias_alerta_vencimiento'] ?? 7));

        $map = [
            'modulo_finanzas_activo'           => $activo,
            'finanzas_dias_alerta_vencimiento' => (string) $diasAlerta,
        ];

        foreach ($map as $name => $value) {
            $row = Ajuste::firstOrNew(['name' => $name]);
            $row->categoria = 'finanzas';
            $row->tipo_form = 'text';
            $row->value = $value;
            $row->save();
        }
    }
}
