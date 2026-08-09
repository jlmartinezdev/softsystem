<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddMailSettingsToConfiguraciones extends Migration
{
    public function up()
    {
        $defaults = [
            ['name' => 'mail_activo', 'categoria' => 'mail', 'value' => '0', 'tipo_form' => 'bool'],
            ['name' => 'mail_host', 'categoria' => 'mail', 'value' => 'smtp.gmail.com', 'tipo_form' => 'text'],
            ['name' => 'mail_port', 'categoria' => 'mail', 'value' => '587', 'tipo_form' => 'text'],
            ['name' => 'mail_username', 'categoria' => 'mail', 'value' => '', 'tipo_form' => 'text'],
            ['name' => 'mail_password', 'categoria' => 'mail', 'value' => '', 'tipo_form' => 'text'],
            ['name' => 'mail_encryption', 'categoria' => 'mail', 'value' => 'tls', 'tipo_form' => 'text'],
            ['name' => 'mail_from_address', 'categoria' => 'mail', 'value' => '', 'tipo_form' => 'text'],
            ['name' => 'mail_from_name', 'categoria' => 'mail', 'value' => 'SoftSystem', 'tipo_form' => 'text'],
            ['name' => 'mail_to', 'categoria' => 'mail', 'value' => '', 'tipo_form' => 'text'],
            ['name' => 'mail_cierre_caja', 'categoria' => 'mail', 'value' => '1', 'tipo_form' => 'bool'],
        ];

        foreach ($defaults as $row) {
            $exists = DB::table('configuraciones')->where('name', $row['name'])->exists();
            if (!$exists) {
                DB::table('configuraciones')->insert($row);
            }
        }
    }

    public function down()
    {
        DB::table('configuraciones')->where('categoria', 'mail')->delete();
    }
}
