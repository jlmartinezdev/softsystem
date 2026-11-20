<?php

namespace App\Support;

use App\Ajuste;
use Exception;

class ReparacionSettings
{
    const DEFAULT_CONDICIONES_RECEPCION = "1. El cliente declara ser propietario o estar autorizado para dejar el equipo descrito.\n2. El taller no se responsabiliza por pérdida de datos, agendas o archivos; se recomienda realizar copia de seguridad previa.\n3. En equipos ingresados apagados, mojados o con pantalla dañada, el diagnóstico final se determinará tras la apertura técnica.\n4. Todo equipo no retirado dentro de los 60 días posteriores a la notificación de presupuesto o finalización será considerado en abandono.";

    const DEFAULT_CONDICIONES_GARANTIA = "1. La garantía cubre única y exclusivamente la falla reparada y los repuestos detallados en este comprobante.\n2. La garantía queda anulada automáticamente si el equipo presenta golpes, trizaduras, rastros de humedad/líquidos, fajas de garantía rotas o intervención por terceros.\n3. Para hacer efectiva la garantía es indispensable presentar este certificado junto con el equipo.";

    /**
     * Obtiene toda la configuración del módulo de Reparación de Celulares.
     *
     * @return array
     */
    public static function all()
    {
        try {
            $rows = Ajuste::where('categoria', 'reparacion')->get()->pluck('value', 'name');
        } catch (Exception $e) {
            $rows = collect([]);
        }

        $activo = isset($rows['modulo_reparacion_activo'])
            ? ((string) $rows['modulo_reparacion_activo'] === '1')
            : true;

        $descontarStock = isset($rows['reparacion_descontar_stock'])
            ? ((string) $rows['reparacion_descontar_stock'] === '1')
            : true;

        $garantiaDias = isset($rows['reparacion_garantia_dias_default'])
            ? max(0, (int) $rows['reparacion_garantia_dias_default'])
            : 30;

        $prefijo = trim((string) ($rows['reparacion_prefijo_orden'] ?? 'ST-'));
        if ($prefijo === '') {
            $prefijo = 'ST-';
        }

        $condRecepcion = trim((string) ($rows['reparacion_condiciones_recepcion'] ?? ''));
        if ($condRecepcion === '') {
            $condRecepcion = self::DEFAULT_CONDICIONES_RECEPCION;
        }

        $condGarantia = trim((string) ($rows['reparacion_condiciones_garantia'] ?? ''));
        if ($condGarantia === '') {
            $condGarantia = self::DEFAULT_CONDICIONES_GARANTIA;
        }

        return [
            'activo'                => $activo,
            'descontar_stock'       => $descontarStock,
            'garantia_dias_default' => $garantiaDias,
            'prefijo_orden'         => $prefijo,
            'condiciones_recepcion' => $condRecepcion,
            'condiciones_garantia'  => $condGarantia,
        ];
    }

    /**
     * Indica si el módulo de reparación está habilitado por el Superusuario / Administrador.
     *
     * @return bool
     */
    public static function isModuloActivo()
    {
        try {
            $val = Ajuste::where('name', 'modulo_reparacion_activo')->value('value');
            if ($val === null || $val === '') {
                return true;
            }
            return (string) $val === '1';
        } catch (Exception $e) {
            return true;
        }
    }

    /**
     * Activa o desactiva el módulo de reparación (solo Superusuario / Administrador).
     *
     * @param bool|int $activo
     * @return bool
     */
    public static function setModuloActivo($activo)
    {
        $val = $activo ? '1' : '0';
        $row = Ajuste::firstOrNew(['name' => 'modulo_reparacion_activo']);
        $row->categoria = 'reparacion';
        $row->tipo_form = 'checkbox';
        $row->value = $val;
        $row->save();

        return $val === '1';
    }

    /**
     * Guarda la configuración completa del módulo de reparación.
     *
     * @param array $data
     * @return void
     */
    public static function save(array $data)
    {
        $activo = !empty($data['activo']) ? '1' : '0';
        $descontarStock = !empty($data['descontar_stock']) ? '1' : '0';
        $garantiaDias = max(0, (int) ($data['garantia_dias_default'] ?? 30));
        $prefijo = trim((string) ($data['prefijo_orden'] ?? 'ST-')) ?: 'ST-';
        $condRecepcion = trim((string) ($data['condiciones_recepcion'] ?? self::DEFAULT_CONDICIONES_RECEPCION));
        $condGarantia = trim((string) ($data['condiciones_garantia'] ?? self::DEFAULT_CONDICIONES_GARANTIA));

        $map = [
            'modulo_reparacion_activo'         => $activo,
            'reparacion_descontar_stock'       => $descontarStock,
            'reparacion_garantia_dias_default' => (string) $garantiaDias,
            'reparacion_prefijo_orden'         => $prefijo,
            'reparacion_condiciones_recepcion' => $condRecepcion,
            'reparacion_condiciones_garantia'  => $condGarantia,
        ];

        foreach ($map as $name => $value) {
            $row = Ajuste::firstOrNew(['name' => $name]);
            $row->categoria = 'reparacion';
            $row->tipo_form = 'text';
            $row->value = $value;
            $row->save();
        }
    }
}
