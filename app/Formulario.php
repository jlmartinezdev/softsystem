<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Formulario extends Model
{
    protected $table = 'formularios';
    protected $primaryKey = 'for_codigo';
    public $timestamps = false;

    protected $fillable = [
        'for_nombre',
        'for_titulo',
        'for_tipo',
        'for_menu',
        'for_submenu',
        'for_categoria',
        'for_icono',
        'for_ruta',
        'for_orden',
        'activo',
    ];

    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'for_codigo', 'for_codigo');
    }

    public function acciones()
    {
        return $this->hasMany(Accion::class, 'for_codigo', 'for_codigo');
    }
}
