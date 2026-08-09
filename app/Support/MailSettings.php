<?php

namespace App\Support;

use App\Ajuste;
use Config;

class MailSettings
{
    public static function all()
    {
        $rows = Ajuste::where('categoria', 'mail')->get()->pluck('value', 'name');

        return [
            'activo' => (string) ($rows['mail_activo'] ?? '0') === '1',
            'host' => (string) ($rows['mail_host'] ?? ''),
            'port' => (string) ($rows['mail_port'] ?? '587'),
            'username' => (string) ($rows['mail_username'] ?? ''),
            'password' => (string) ($rows['mail_password'] ?? ''),
            'encryption' => (string) ($rows['mail_encryption'] ?? 'tls'),
            'from_address' => (string) ($rows['mail_from_address'] ?? ''),
            'from_name' => (string) ($rows['mail_from_name'] ?? 'SoftSystem'),
            'to' => (string) ($rows['mail_to'] ?? ''),
            'cierre_caja' => (string) ($rows['mail_cierre_caja'] ?? '1') === '1',
        ];
    }

    public static function save(array $data)
    {
        $map = [
            'mail_activo' => !empty($data['activo']) ? '1' : '0',
            'mail_host' => trim((string) ($data['host'] ?? '')),
            'mail_port' => trim((string) ($data['port'] ?? '587')),
            'mail_username' => trim((string) ($data['username'] ?? '')),
            'mail_encryption' => trim((string) ($data['encryption'] ?? 'tls')),
            'mail_from_address' => trim((string) ($data['from_address'] ?? '')),
            'mail_from_name' => trim((string) ($data['from_name'] ?? 'SoftSystem')),
            'mail_to' => trim((string) ($data['to'] ?? '')),
            'mail_cierre_caja' => !empty($data['cierre_caja']) ? '1' : '0',
        ];

        if (array_key_exists('password', $data) && $data['password'] !== null && $data['password'] !== '') {
            $map['mail_password'] = (string) $data['password'];
        }

        foreach ($map as $name => $value) {
            $row = Ajuste::firstOrNew(['name' => $name]);
            $row->categoria = 'mail';
            $row->tipo_form = in_array($name, ['mail_activo', 'mail_cierre_caja'], true) ? 'bool' : 'text';
            $row->value = $value;
            $row->save();
        }
    }

    public static function apply()
    {
        $cfg = self::all();
        $encryption = strtolower($cfg['encryption']);
        if ($encryption === '' || $encryption === 'null' || $encryption === 'none') {
            $encryption = null;
        }

        $from = $cfg['from_address'] !== '' ? $cfg['from_address'] : $cfg['username'];

        Config::set('mail.driver', 'smtp');
        Config::set('mail.host', $cfg['host']);
        Config::set('mail.port', (int) $cfg['port']);
        Config::set('mail.username', $cfg['username']);
        Config::set('mail.password', $cfg['password']);
        Config::set('mail.encryption', $encryption);
        Config::set('mail.from.address', $from);
        Config::set('mail.from.name', $cfg['from_name'] ?: 'SoftSystem');

        if (app()->bound('swift.transport')) {
            app()->forgetInstance('swift.transport');
        }
        if (app()->bound('swift.mailer')) {
            app()->forgetInstance('swift.mailer');
        }
    }

    public static function recipients()
    {
        $to = self::all()['to'];
        if ($to === '') {
            return [];
        }

        $parts = preg_split('/[;,]+/', $to);
        $emails = [];
        foreach ($parts as $part) {
            $email = trim($part);
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emails[] = $email;
            }
        }

        return array_values(array_unique($emails));
    }

    public static function canSendCierre()
    {
        $cfg = self::all();
        return $cfg['activo']
            && $cfg['cierre_caja']
            && $cfg['host'] !== ''
            && count(self::recipients()) > 0;
    }
}
