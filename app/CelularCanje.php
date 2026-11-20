<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CelularCanje extends Model
{
    protected $table = 'celular_canjes';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'codigo_canje',
        'tipo_operacion',
        'suc_cod',
        'CLIENTES_cod',
        'celular_entregado_id',
        'celular_llevado_id',
        'valor_tasacion_usado',
        'precio_equipo_llevado',
        'descuento_adicional',
        'diferencia_a_pagar',
        'forma_pago',
        'registrar_caja',
        'nro_operacion_caja',
        'nro_fact_ventas',
        'enviar_a_taller',
        'reparacion_id',
        'estado',
        'observaciones',
        'cod_usuarios',
        'fecha_operacion',
    ];

    protected $casts = [
        'valor_tasacion_usado'  => 'float',
        'precio_equipo_llevado' => 'float',
        'descuento_adicional'   => 'float',
        'diferencia_a_pagar'    => 'float',
        'registrar_caja'        => 'boolean',
        'enviar_a_taller'       => 'boolean',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'CLIENTES_cod', 'CLIENTES_cod');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'suc_cod', 'suc_cod');
    }

    public function celularEntregado()
    {
        return $this->belongsTo(CelularImei::class, 'celular_entregado_id', 'id');
    }

    public function celularLlevado()
    {
        return $this->belongsTo(CelularImei::class, 'celular_llevado_id', 'id');
    }

    public function reparacion()
    {
        return $this->belongsTo(Reparacion::class, 'reparacion_id', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'cod_usuarios', 'cod_usuarios');
    }
}
