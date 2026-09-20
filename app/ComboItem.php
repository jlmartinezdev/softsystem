<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComboItem extends Model
{
    protected $table = 'combo_items';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'combo_id',
        'articulos_cod',
        'cantidad',
        'precio_ref',
    ];

    public function combo()
    {
        return $this->belongsTo(Combo::class, 'combo_id', 'id');
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'articulos_cod', 'ARTICULOS_cod');
    }
}
