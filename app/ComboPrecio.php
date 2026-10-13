<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComboPrecio extends Model
{
    protected $table = 'combo_precios';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'combo_id',
        'id_precio',
        'precio',
        'margen',
        'cant_cuota',
        'monto_cuota',
    ];

    public function combo()
    {
        return $this->belongsTo(Combo::class, 'combo_id', 'id');
    }
}
