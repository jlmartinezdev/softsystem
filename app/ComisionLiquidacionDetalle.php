<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComisionLiquidacionDetalle extends Model
{
    protected $table = 'comision_liquidacion_detalles';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'liquidacion_id',
        'cod_usuarios',
        'origen_modulo',
        'referencia_id',
        'codigo_comprobante',
        'fecha_operacion',
        'descripcion',
        'monto_operacion',
        'costo_operacion',
        'base_comisionable',
        'porcentaje_aplicado',
        'bono_fijo',
        'monto_comision',
    ];

    protected $casts = [
        'monto_operacion'     => 'float',
        'costo_operacion'     => 'float',
        'base_comisionable'   => 'float',
        'porcentaje_aplicado' => 'float',
        'bono_fijo'           => 'float',
        'monto_comision'      => 'float',
    ];

    public function liquidacion()
    {
        return $this->belongsTo(ComisionLiquidacion::class, 'liquidacion_id', 'id');
    }
}
