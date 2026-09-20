<?php

namespace App\Support;

use App\Ajuste;

class CameraSettings
{
    public static function all()
    {
        $rows = Ajuste::where('categoria', 'camara')->get()->pluck('value', 'name');

        return [
            'url' => (string) ($rows['camara_url'] ?? ''),
            'user' => (string) ($rows['camara_user'] ?? 'admin'),
            'password' => (string) ($rows['camara_password'] ?? ''),
            'canal' => (string) ($rows['camara_canal'] ?? '102'),
        ];
    }

    public static function save(array $data)
    {
        $map = [
            'camara_url' => trim((string) ($data['url'] ?? '')),
            'camara_user' => trim((string) ($data['user'] ?? 'admin')),
            'camara_canal' => trim((string) ($data['canal'] ?? '102')),
        ];

        if (array_key_exists('password', $data) && $data['password'] !== null && $data['password'] !== '') {
            $map['camara_password'] = (string) $data['password'];
        }

        foreach ($map as $name => $value) {
            $row = Ajuste::firstOrNew(['name' => $name]);
            $row->categoria = 'camara';
            $row->tipo_form = 'text';
            $row->value = $value;
            $row->save();
        }
    }

    public static function isConfigured()
    {
        $cfg = self::all();
        return $cfg['url'] !== '';
    }

    /**
     * Build snapshot URL.
     * Accepts bare IP/host or a full snapshot URL.
     */
    public static function snapshotUrl(array $cfg = null)
    {
        $cfg = $cfg ?: self::all();
        $raw = trim($cfg['url']);
        if ($raw === '') {
            return '';
        }

        $canal = $cfg['canal'] !== '' ? $cfg['canal'] : '102';

        if (preg_match('#^https?://.+/.+#i', $raw)) {
            return $raw;
        }

        if (preg_match('#^https?://#i', $raw)) {
            return rtrim($raw, '/') . '/ISAPI/Streaming/channels/' . $canal . '/picture';
        }

        return 'http://' . $raw . '/ISAPI/Streaming/channels/' . $canal . '/picture';
    }

    public static function publicConfig()
    {
        $cfg = self::all();

        return [
            'configured' => self::isConfigured(),
            'url' => $cfg['url'],
            'user' => $cfg['user'],
            'canal' => $cfg['canal'],
            'has_password' => $cfg['password'] !== '',
        ];
    }
}
