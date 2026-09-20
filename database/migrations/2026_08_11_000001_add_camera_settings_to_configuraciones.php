<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddCameraSettingsToConfiguraciones extends Migration
{
    public function up()
    {
        $defaults = [
            ['name' => 'camara_url', 'categoria' => 'camara', 'value' => '', 'tipo_form' => 'text'],
            ['name' => 'camara_user', 'categoria' => 'camara', 'value' => 'admin', 'tipo_form' => 'text'],
            ['name' => 'camara_password', 'categoria' => 'camara', 'value' => '', 'tipo_form' => 'text'],
            ['name' => 'camara_canal', 'categoria' => 'camara', 'value' => '102', 'tipo_form' => 'text'],
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
        DB::table('configuraciones')->where('categoria', 'camara')->delete();
    }
}
