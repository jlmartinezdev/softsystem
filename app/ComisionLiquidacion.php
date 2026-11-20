<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComisionLiquidacion extends Model
{
    protected $table = 'comision_liquidaciones';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'codigo_liquidacion',
        'cod_usuarios',
        'suc_cod',
        'fecha_desde',
        'fecha_hasta',
        'fecha_liquidacion',
        'cant_reparaciones',
        'total_base_taller',
        'comision_taller',
        'cant_ventas_pos',
        'total_base_ventas',
        'comision_ventas',
        'cant_equipos_imei',
        'total_base_imei',
        'comision_imei',
        'cant_cobranzas',
        'total_base_cobranzas',
        'comision_cobranzas',
        'subtotal_comisiones',
        'bono_premio',
        'motivo_bono',
        'descuento_adelanto',
        'motivo_descuento',
        'total_neto_pagar',
        'estado',
        'forma_pago',
        'registrar_caja',
        'nro_operacion_caja',
        'observaciones',
        'cod_usuario_liquidador',
    ];

    protected $casts = [
        'total_base_taller'    => 'float',
        'comision_taller'      => 'float',
        'total_base_ventas'    => 'float',
        'comision_ventas'      => 'float',
        'total_base_imei'      => 'float',
        'comision_imei'        => 'float',
        'total_base_cobranzas' => 'float',
        'comision_cobranzas'   => 'float',
        'subtotal_comisiones'  => 'float',
        'bono_premio'          => 'float',
        'descuento_adelanto'   => 'float',
        'total_neto_pagar'     => 'float',
        'registrar_caja'       => 'boolean',
    ];

    public function empleado()
    {
        return $this->belongsTo(User::class, 'cod_usuarios', 'cod_usuarios');
    }

    public function liquidador()
    {
        return $this->belongsTo(User::class, 'cod_usuario_liquidador', 'cod_usuarios');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'suc_cod', 'suc_cod');
    }

    public function detalles()
    {
        return $this->hasMany(ComisionLiquidacionDetalle::class, 'liquidacion_id', 'id')->orderBy('id', 'asc');
    }
}
