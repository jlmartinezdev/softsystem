<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GastoOperativo extends Model
{
    protected $table = 'gastos_operativos';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'codigo_gasto',
        'categoria_id',
        'suc_cod',
        'PROVEEDOR_cod',
        'fecha_gasto',
        'nro_comprobante',
        'concepto',
        'monto',
        'iva_incluido',
        'forma_pago',
        'registrar_caja',
        'nro_operacion_caja',
        'estado',
        'observaciones',
        'cod_usuarios',
    ];

    protected $casts = [
        'monto'          => 'float',
        'iva_incluido'   => 'integer',
        'forma_pago'     => 'integer',
        'registrar_caja' => 'boolean',
    ];

    public function categoria()
    {
        return $this->belongsTo(GastoCategoria::class, 'categoria_id', 'id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'suc_cod', 'suc_cod');
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
