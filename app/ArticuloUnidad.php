<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticuloUnidad extends Model
{
    protected $table = 'articulo_unidades';
    public $timestamps = true;
    protected $fillable = [
        'articulo_id',
        'unidad_id',
        'factor_conversion',
        'precio_venta',
        'es_principal'
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'articulo_id', 'articulos_cod');
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'unidad_id', 'uni_codigo');
    }
} 