<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CtaPagar extends Model
{
    protected $table = 'ctas_pagar';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'codigo_cta',
        'compra_cod',
        'PROVEEDOR_cod',
        'suc_cod',
        'nro_factura_proveedor',
        'concepto',
        'fecha_emision',
        'fecha_vencimiento',
        'nro_cuota',
        'total_cuotas',
        'monto_original',
        'monto_pagado',
        'monto_saldo',
        'estado',
        'observaciones',
        'cod_usuarios',
    ];

    protected $casts = [
        'monto_original' => 'float',
        'monto_pagado'   => 'float',
        'monto_saldo'    => 'float',
        'nro_cuota'      => 'integer',
        'total_cuotas'   => 'integer',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'PROVEEDOR_cod', 'PROVEEDOR_cod');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'suc_cod', 'suc_cod');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'cod_usuarios', 'cod_usuarios');
    }

    public function pagos()
    {
        return $this->hasMany(CtaPagarPago::class, 'cta_pagar_id', 'id')->orderBy('fecha_pago', 'desc');
    }

    public function recalcularSaldo()
    {
        $pagado = (float) CtaPagarPago::where('cta_pagar_id', $this->id)->sum('monto');
        $saldo = max(0, (float) $this->monto_original - $pagado);

        $this->monto_pagado = $pagado;
        $this->monto_saldo = $saldo;
        if ($this->estado !== 'ANULADO') {
            if ($saldo <= 0) {
                $this->estado = 'PAGADO';
            } elseif ($pagado > 0) {
                $this->estado = 'PARCIAL';
            } else {
                $this->estado = 'PENDIENTE';
            }
        }
        $this->save();
        return $this;
    }
}
