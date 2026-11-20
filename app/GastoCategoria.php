<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GastoCategoria extends Model
{
    protected $table = 'gasto_categorias';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'tipo',
        'icono',
        'color',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function gastos()
    {
        return $this->hasMany(GastoOperativo::class, 'categoria_id', 'id');
    }
}
