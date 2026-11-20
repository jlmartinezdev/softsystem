<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CelularImeiImagen extends Model
{
    protected $table = 'celular_imei_imagenes';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'celular_imei_id',
        'tipo',
        'url',
        'filename',
        'public_id',
        'driver',
        'descripcion',
        'cod_usuarios',
    ];

    public function celular()
    {
        return $this->belongsTo(CelularImei::class, 'celular_imei_id', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'cod_usuarios', 'cod_usuarios');
    }
}
