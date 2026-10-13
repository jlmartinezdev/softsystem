<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ExpandArticulosFotoForCloudinary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Expandir tamaño de columna foto en articulos para soportar URLs completas de Cloudinary u otros servicios
        try {
            DB::statement("ALTER TABLE `articulos` MODIFY `foto` VARCHAR(500) NULL DEFAULT NULL");
        } catch (\Exception $e) {
            // Si ya tiene ese tamaño o falla por tipo, se omite
        }

        // Sembrar configuraciones iniciales de Cloudinary si no existen
        $defaults = [
            ['name' => 'almacenamiento_driver', 'categoria' => 'cloudinary', 'value' => 'local', 'tipo_form' => 'text'],
            ['name' => 'cloudinary_cloud_name',  'categoria' => 'cloudinary', 'value' => '',      'tipo_form' => 'text'],
            ['name' => 'cloudinary_api_key',     'categoria' => 'cloudinary', 'value' => '',      'tipo_form' => 'text'],
            ['name' => 'cloudinary_api_secret',  'categoria' => 'cloudinary', 'value' => '',      'tipo_form' => 'text'],
            ['name' => 'cloudinary_folder',      'categoria' => 'cloudinary', 'value' => 'articulos', 'tipo_form' => 'text'],
            ['name' => 'cloudinary_auto_optimize','categoria' => 'cloudinary', 'value' => '1',    'tipo_form' => 'text'],
        ];

        foreach ($defaults as $item) {
            $exists = DB::table('configuraciones')->where('name', $item['name'])->exists();
            if (!$exists) {
                DB::table('configuraciones')->insert($item);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            DB::statement("ALTER TABLE `articulos` MODIFY `foto` VARCHAR(200) NULL DEFAULT NULL");
        } catch (\Exception $e) {
        }

        DB::table('configuraciones')->where('categoria', 'cloudinary')->delete();
    }
}
