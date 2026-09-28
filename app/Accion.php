<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accion extends Model
{
    protected $table = 'accion';
    protected $primaryKey = 'cod_per';
    public $timestamps = false;

    protected $fillable = [
        'for_codigo',
        'clave_accion',
        'nombre_accion',
        'descripcion_accion',
        'orden',
        'tipo',
    ];

    public function formulario()
    {
        return $this->belongsTo(Formulario::class, 'for_codigo', 'for_codigo');
    }

    public function roles()
    {
        return $this->hasMany(AccionRol::class, 'cod_per', 'cod_per');
    }
}
