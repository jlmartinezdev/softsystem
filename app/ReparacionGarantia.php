<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReparacionGarantia extends Model
{
    protected $table = 'reparacion_garantias';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'reparacion_id',
        'reparacion_reingreso_id',
        'fecha_reclamo',
        'motivo_reclamo',
        'evaluacion_tecnica',
        'cubre_garantia',
        'resolucion',
        'estado',
        'costo_asumido_taller',
        'fecha_resolucion',
        'cod_usuarios',
    ];

    protected $casts = [
        'cubre_garantia'       => 'boolean',
        'costo_asumido_taller' => 'float',
    ];

    public function reparacion()
    {
        return $this->belongsTo(Reparacion::class, 'reparacion_id', 'id');
    }

    public function ordenReingreso()
    {
        return $this->belongsTo(Reparacion::class, 'reparacion_reingreso_id', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'cod_usuarios', 'cod_usuarios');
    }
}
