<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permiso';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'for_codigo',
        'cod_rol',
        'permiso',
        'per_open',
        'per_add',
        'per_edit',
        'per_del',
        'per_export',
    ];

    public function formulario()
    {
        return $this->belongsTo(Formulario::class, 'for_codigo', 'for_codigo');
    }

    public function rol()
    {
        return $this->belongsTo(Roles::class, 'cod_rol', 'cod_rol');
    }
}
