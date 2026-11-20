<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReparacionImagen extends Model
{
    protected $table = 'reparacion_imagenes';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'reparacion_id',
        'etapa',
        'url',
        'filename',
        'public_id',
        'driver',
        'descripcion',
        'cod_usuarios',
    ];

    public function reparacion()
    {
        return $this->belongsTo(Reparacion::class, 'reparacion_id', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'cod_usuarios', 'cod_usuarios');
    }
}
