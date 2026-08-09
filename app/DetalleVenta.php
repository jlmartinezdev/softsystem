<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalle_venta';
    protected $primaryKey = 'detalle_id';
    public $timestamps = false;
    protected $fillable = [
        'ARTICULOS_cod',
        'nro_fact_ventas',
        'venta_precio',
        'venta_cantidad',
        'precio_compra',
        'descripcion_libre',
    ];
}
