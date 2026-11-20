<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReparacionHistorial extends Model
{
    protected $table = 'reparacion_historial';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'reparacion_id',
        'estado_anterior',
        'estado_nuevo',
        'titulo',
        'comentario',
        'cod_usuarios',
        'created_at',
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
