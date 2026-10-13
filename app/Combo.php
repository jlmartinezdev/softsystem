<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    protected $table = 'combos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'codigo',
        'precio',
        'precio_credito',
        'precio_lista',
        'activo',
        'observacion',
    ];

    public function items()
    {
        return $this->hasMany(ComboItem::class, 'combo_id', 'id');
    }

    public function precios()
    {
        return $this->hasMany(ComboPrecio::class, 'combo_id', 'id')->orderBy('cant_cuota', 'ASC');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', 1);
    }
}
