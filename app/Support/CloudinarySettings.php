<?php

namespace App\Support;

use App\Ajuste;

class CloudinarySettings
{
    /**
     * Obtiene la configuración completa de Cloudinary y almacenamiento de imágenes.
     *
     * @return array
     */
    public static function all()
    {
        $rows = Ajuste::where('categoria', 'cloudinary')->get()->pluck('value', 'name');

        $driver = (string) ($rows['almacenamiento_driver'] ?? env('CLOUDINARY_DRIVER', 'local'));
        $cloudName = trim((string) ($rows['cloudinary_cloud_name'] ?? env('CLOUDINARY_CLOUD_NAME', '')));
        $apiKey = trim((string) ($rows['cloudinary_api_key'] ?? env('CLOUDINARY_API_KEY', '')));
        $apiSecret = trim((string) ($rows['cloudinary_api_secret'] ?? env('CLOUDINARY_API_SECRET', '')));
        $folder = trim((string) ($rows['cloudinary_folder'] ?? env('CLOUDINARY_FOLDER', 'articulos')));
        if ($folder === '') {
            $folder = 'articulos';
        }
        $autoOptimize = (string) ($rows['cloudinary_auto_optimize'] ?? '1') === '1';

        $hasCredentials = ($cloudName !== '' && $apiKey !== '' && $apiSecret !== '');
        $isEnabled = ($driver === 'cloudinary' && $hasCredentials);

        return [
            'driver'          => $driver,
            'cloud_name'      => $cloudName,
            'api_key'         => $apiKey,
            'api_secret'      => $apiSecret,
            'folder'          => $folder,
            'auto_optimize'   => $autoOptimize,
            'has_credentials' => $hasCredentials,
            'is_enabled'      => $isEnabled,
        ];
    }

    /**
     * Configuración pública y segura para enviar a vistas/frontend (ocultando el secret).
     *
     * @return array
     */
    public static function publicConfig()
    {
        $all = self::all();
        $hasSecret = !empty($all['api_secret']);

        return [
            'driver'          => $all['driver'],
            'cloud_name'      => $all['cloud_name'],
            'api_key'         => $all['api_key'],
            'api_secret'      => '',
            'has_secret'      => $hasSecret,
            'folder'          => $all['folder'],
            'auto_optimize'   => $all['auto_optimize'],
            'has_credentials' => $all['has_credentials'],
            'is_enabled'      => $all['is_enabled'],
        ];
    }

    /**
     * Comprueba si el almacenamiento Cloudinary está activo y listo para usar.
     *
     * @return bool
     */
    public static function isEnabled()
    {
        $all = self::all();
        return $all['is_enabled'];
    }

    /**
     * Retorna la carpeta donde se almacenan las fotos en Cloudinary.
     *
     * @return string
     */
    public static function getFolder()
    {
        $all = self::all();
        return $all['folder'] ?: 'articulos';
    }

    /**
     * Guarda la configuración de Cloudinary en la tabla configuraciones.
     *
     * @param array $data
     * @return void
     */
    public static function save(array $data)
    {
        $driver = trim((string) ($data['driver'] ?? 'local'));
        if (!in_array($driver, ['local', 'cloudinary'])) {
            $driver = 'local';
        }

        $cloudName = trim((string) ($data['cloud_name'] ?? ''));
        $apiKey = trim((string) ($data['api_key'] ?? ''));
        $newSecret = trim((string) ($data['api_secret'] ?? ''));
        $folder = trim((string) ($data['folder'] ?? 'articulos'));
        if ($folder === '') {
            $folder = 'articulos';
        }
        $autoOptimize = !empty($data['auto_optimize']) ? '1' : '0';

        $map = [
            'almacenamiento_driver'    => $driver,
            'cloudinary_cloud_name'     => $cloudName,
            'cloudinary_api_key'        => $apiKey,
            'cloudinary_folder'         => $folder,
            'cloudinary_auto_optimize'  => $autoOptimize,
        ];

        // Solo actualizar el api_secret si fue provisto y no es máscara
        if ($newSecret !== '' && strpos($newSecret, '***') === false) {
            $map['cloudinary_api_secret'] = $newSecret;
        }

        foreach ($map as $name => $value) {
            $row = Ajuste::firstOrNew(['name' => $name]);
            $row->categoria = 'cloudinary';
            $row->tipo_form = 'text';
            $row->value = $value;
            $row->save();
        }
    }
}
