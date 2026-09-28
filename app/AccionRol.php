<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccionRol extends Model
{
    protected $table = 'accion_rol';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'cod_per',
        'cod_rol',
        'permitido',
    ];

    public function accion()
    {
        return $this->belongsTo(Accion::class, 'cod_per', 'cod_per');
    }

    public function rol()
    {
        return $this->belongsTo(Roles::class, 'cod_rol', 'cod_rol');
    }
}
