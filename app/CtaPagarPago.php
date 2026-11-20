<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CtaPagarPago extends Model
{
    protected $table = 'ctas_pagar_pagos';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'cta_pagar_id',
        'PROVEEDOR_cod',
        'suc_cod',
        'fecha_pago',
        'monto',
        'forma_pago',
        'nro_recibo_comprobante',
        'registrar_caja',
        'nro_operacion_caja',
        'observacion',
        'cod_usuarios',
    ];

    protected $casts = [
        'monto'          => 'float',
        'forma_pago'     => 'integer',
        'registrar_caja' => 'boolean',
    ];

    public function cuenta()
    {
        return $this->belongsTo(CtaPagar::class, 'cta_pagar_id', 'id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'PROVEEDOR_cod', 'PROVEEDOR_cod');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'cod_usuarios', 'cod_usuarios');
    }
}
