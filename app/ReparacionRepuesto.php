<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReparacionRepuesto extends Model
{
    protected $table = 'reparacion_repuestos';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'reparacion_id',
        'ARTICULOS_cod',
        'id_stock',
        'suc_cod',
        'es_libre',
        'codigo_barra',
        'descripcion',
        'cantidad',
        'costo_unitario',
        'precio_unitario',
        'subtotal',
        'iva',
        'stock_descontado',
        'garantia_repuesto_dias',
        'observacion',
        'cod_usuarios',
    ];

    protected $casts = [
        'es_libre'               => 'boolean',
        'stock_descontado'       => 'boolean',
        'cantidad'               => 'float',
        'costo_unitario'         => 'float',
        'precio_unitario'        => 'float',
        'subtotal'               => 'float',
        'iva'                    => 'integer',
        'garantia_repuesto_dias' => 'integer',
    ];

    public function reparacion()
    {
        return $this->belongsTo(Reparacion::class, 'reparacion_id', 'id');
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'ARTICULOS_cod', 'ARTICULOS_cod');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'cod_usuarios', 'cod_usuarios');
    }
}
