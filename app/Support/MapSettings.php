<?php

namespace App\Support;

use App\Ajuste;

class MapSettings
{
    /**
     * Obtiene la configuración actual de mapas y geolocalización.
     */
    public static function all()
    {
        $rows = Ajuste::where('categoria', 'mapas')->get()->pluck('value', 'name');

        $proveedor = (string) ($rows['mapas_proveedor'] ?? 'auto'); // 'auto', 'google', 'openstreet'
        $apiKey = trim((string) ($rows['google_maps_api_key'] ?? ''));
        $latDefault = (string) ($rows['mapas_lat_default'] ?? '-25.263740');
        $lngDefault = (string) ($rows['mapas_lng_default'] ?? '-57.575920');
        $zoomDefault = (int) ($rows['mapas_zoom_default'] ?? 16);

        // Regla: si no hay API key o si se seleccionó openstreet, usar openstreet
        $proveedorEfectivo = 'openstreet';
        if ($apiKey !== '') {
            if ($proveedor === 'google' || $proveedor === 'auto') {
                $proveedorEfectivo = 'google';
            }
        }

        return [
            'proveedor'           => $proveedor,
            'google_maps_api_key' => $apiKey,
            'mapas_lat_default'   => $latDefault,
            'mapas_lng_default'   => $lngDefault,
            'mapas_zoom_default'  => $zoomDefault,
            'proveedor_efectivo'  => $proveedorEfectivo,
            'tiene_google_api'    => $apiKey !== '',
        ];
    }

    /**
     * Guarda la configuración de mapas en la tabla configuraciones.
     */
    public static function save(array $data)
    {
        $map = [
            'mapas_proveedor'     => trim((string) ($data['proveedor'] ?? 'auto')),
            'google_maps_api_key' => trim((string) ($data['google_maps_api_key'] ?? '')),
            'mapas_lat_default'   => trim((string) ($data['mapas_lat_default'] ?? '-25.263740')),
            'mapas_lng_default'   => trim((string) ($data['mapas_lng_default'] ?? '-57.575920')),
            'mapas_zoom_default'  => (string) ($data['mapas_zoom_default'] ?? '16'),
        ];

        foreach ($map as $name => $value) {
            $row = Ajuste::firstOrNew(['name' => $name]);
            $row->categoria = 'mapas';
            $row->tipo_form = 'text';
            $row->value = $value;
            $row->save();
        }
    }

    /**
     * Retorna la configuración lista para consumo público en vistas Blade y Vue.
     */
    public static function publicConfig()
    {
        return self::all();
    }
}
