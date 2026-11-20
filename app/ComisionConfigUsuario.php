<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComisionConfigUsuario extends Model
{
    protected $table = 'comision_config_usuarios';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'cod_usuarios',
        'activo_comision',
        'comisiona_taller',
        'porc_taller',
        'base_taller',
        'descontar_garantias_taller',
        'comisiona_ventas',
        'porc_venta_contado',
        'porc_venta_credito',
        'comisiona_imei',
        'porc_imei',
        'base_imei',
        'bono_fijo_imei',
        'comisiona_cobranzas',
        'porc_cobranzas',
        'sueldo_base_referencia',
        'observaciones',
    ];

    protected $casts = [
        'activo_comision'            => 'boolean',
        'comisiona_taller'           => 'boolean',
        'porc_taller'                => 'float',
        'descontar_garantias_taller' => 'boolean',
        'comisiona_ventas'           => 'boolean',
        'porc_venta_contado'         => 'float',
        'porc_venta_credito'         => 'float',
        'comisiona_imei'             => 'boolean',
        'porc_imei'                  => 'float',
        'bono_fijo_imei'             => 'float',
        'comisiona_cobranzas'        => 'boolean',
        'porc_cobranzas'             => 'float',
        'sueldo_base_referencia'     => 'float',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'cod_usuarios', 'cod_usuarios');
    }
}
