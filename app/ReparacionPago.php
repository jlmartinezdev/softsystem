<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReparacionPago extends Model
{
    protected $table = 'reparacion_pagos';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'reparacion_id',
        'tipo_pago',
        'forma_cobro',
        'monto',
        'nro_recibo',
        'nro_fact_ventas',
        'registrar_caja',
        'nro_operacion_caja',
        'observacion',
        'cod_usuarios',
        'fecha_pago',
    ];

    protected $casts = [
        'monto'          => 'float',
        'forma_cobro'    => 'integer',
        'registrar_caja' => 'boolean',
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
