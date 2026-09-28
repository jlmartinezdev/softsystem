<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $primaryKey = 'CIUDAD_cod';
    protected $table = 'ciudad';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'CIUDAD_cod', 'depart_codigo', 'ciudad_nombre'
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'depart_codigo', 'depart_codigo');
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'CIUDAD_cod', 'CIUDAD_cod');
    }

    public function proveedores()
    {
        return $this->hasMany(Proveedor::class, 'CIUDAD_cod', 'CIUDAD_cod');
    }
}
