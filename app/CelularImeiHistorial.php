<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CelularImeiHistorial extends Model
{
    protected $table = 'celular_imei_historial';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'celular_imei_id',
        'evento',
        'titulo',
        'detalle',
        'costo_anterior',
        'costo_nuevo',
        'referencia_id',
        'cod_usuarios',
        'created_at',
    ];

    protected $casts = [
        'costo_anterior' => 'float',
        'costo_nuevo'    => 'float',
    ];

    public function celular()
    {
        return $this->belongsTo(CelularImei::class, 'celular_imei_id', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'cod_usuarios', 'cod_usuarios');
    }
}
