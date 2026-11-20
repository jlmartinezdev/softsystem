<?php

namespace App\Support;

use App\Ajuste;
use Exception;

class CelularImeiSettings
{
    const DEFAULT_CLAUSULA_CANJE = "1. DECLARO BAJO FE DE JURAMENTO que el dispositivo móvil descrito en este documento (identificado por su marca, modelo y número de IMEI/Serie) es de mi exclusiva y legítima propiedad, habiendo sido adquirido de forma lícita.\n2. Garantizo que el equipo se encuentra libre de denuncias por robo, hurto o extravío, libre de deudas o bloqueos pendientes con operadoras telefónicas, y sin cuentas de bloqueo activas (iCloud / Find My iPhone / Google FRP / Xiaomi Cloud).\n3. Asumo plena responsabilidad civil y penal ante cualquier reclamo posterior de terceros o bloqueo por reporte de origen previo a la fecha y hora de esta entrega, eximiendo de toda responsabilidad al comercio receptor.";

    const DEFAULT_CLAUSULA_GARANTIA = "1. El presente certificado garantiza el correcto funcionamiento del equipo identificado únicamente por el número de IMEI/Serie detallado en este documento durante el plazo estipulado.\n2. En equipos SEMINUEVOS o REACONDICIONADOS, la batería cuenta con el porcentaje de salud verificado al momento de la entrega, no considerándose falla el desgaste natural por ciclos de carga.\n3. La garantía pierde total validez si el equipo presenta golpes, abolladuras, pantalla o tapa trizada, contacto con líquidos/humedad, sobrecarga eléctrica, modificaciones de software (root/jailbreak) o apertura por personal ajeno a nuestro servicio técnico.";

    /**
     * Obtiene toda la configuración del módulo de Compra-Venta por IMEI y Plan Canje.
     *
     * @return array
     */
    public static function all()
    {
        try {
            $rows = Ajuste::where('categoria', 'celulares_imei')->get()->pluck('value', 'name');
        } catch (Exception $e) {
            $rows = collect([]);
        }

        $activo = isset($rows['modulo_celulares_imei_activo'])
            ? ((string) $rows['modulo_celulares_imei_activo'] === '1')
            : true;

        $garantiaNuevoDias = isset($rows['imei_garantia_nuevo_dias'])
            ? max(0, (int) $rows['imei_garantia_nuevo_dias'])
            : 180;

        $garantiaUsadoDias = isset($rows['imei_garantia_usado_dias'])
            ? max(0, (int) $rows['imei_garantia_usado_dias'])
            : 60;

        $prefijoEquipo = trim((string) ($rows['imei_prefijo_equipo'] ?? 'EQ-')) ?: 'EQ-';
        $prefijoCanje = trim((string) ($rows['imei_prefijo_canje'] ?? 'CJ-')) ?: 'CJ-';

        $clausulaCanje = trim((string) ($rows['imei_clausula_canje'] ?? ''));
        if ($clausulaCanje === '') {
            $clausulaCanje = self::DEFAULT_CLAUSULA_CANJE;
        }

        $clausulaGarantia = trim((string) ($rows['imei_clausula_garantia'] ?? ''));
        if ($clausulaGarantia === '') {
            $clausulaGarantia = self::DEFAULT_CLAUSULA_GARANTIA;
        }

        return [
            'activo'              => $activo,
            'garantia_nuevo_dias' => $garantiaNuevoDias,
            'garantia_usado_dias' => $garantiaUsadoDias,
            'prefijo_equipo'      => $prefijoEquipo,
            'prefijo_canje'       => $prefijoCanje,
            'clausula_canje'      => $clausulaCanje,
            'clausula_garantia'   => $clausulaGarantia,
        ];
    }

    /**
     * Indica si el módulo de Equipos por IMEI y Plan Canje está habilitado por el Superadministrador.
     *
     * @return bool
     */
    public static function isModuloActivo()
    {
        try {
            $val = Ajuste::where('name', 'modulo_celulares_imei_activo')->value('value');
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
        $row = Ajuste::firstOrNew(['name' => 'modulo_celulares_imei_activo']);
        $row->categoria = 'celulares_imei';
        $row->tipo_form = 'checkbox';
        $row->value = $val;
        $row->save();

        return $val === '1';
    }

    /**
     * Guarda la configuración completa del módulo.
     *
     * @param array $data
     * @return void
     */
    public static function save(array $data)
    {
        $activo = !empty($data['activo']) ? '1' : '0';
        $garantiaNuevo = max(0, (int) ($data['garantia_nuevo_dias'] ?? 180));
        $garantiaUsado = max(0, (int) ($data['garantia_usado_dias'] ?? 60));
        $prefijoEquipo = trim((string) ($data['prefijo_equipo'] ?? 'EQ-')) ?: 'EQ-';
        $prefijoCanje = trim((string) ($data['prefijo_canje'] ?? 'CJ-')) ?: 'CJ-';
        $clausulaCanje = trim((string) ($data['clausula_canje'] ?? self::DEFAULT_CLAUSULA_CANJE));
        $clausulaGarantia = trim((string) ($data['clausula_garantia'] ?? self::DEFAULT_CLAUSULA_GARANTIA));

        $map = [
            'modulo_celulares_imei_activo' => $activo,
            'imei_garantia_nuevo_dias'     => (string) $garantiaNuevo,
            'imei_garantia_usado_dias'     => (string) $garantiaUsado,
            'imei_prefijo_equipo'          => $prefijoEquipo,
            'imei_prefijo_canje'           => $prefijoCanje,
            'imei_clausula_canje'          => $clausulaCanje,
            'imei_clausula_garantia'       => $clausulaGarantia,
        ];

        foreach ($map as $name => $value) {
            $row = Ajuste::firstOrNew(['name' => $name]);
            $row->categoria = 'celulares_imei';
            $row->tipo_form = 'text';
            $row->value = $value;
            $row->save();
        }
    }
}
