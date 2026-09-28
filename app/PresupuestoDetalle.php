<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresupuestoDetalle extends Model
{
    protected $table = 'presupuesto_detalle';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'pre_numero',
        'ARTICULOS_cod',
        'descripcion_libre',
        'pre_det_cantidad',
        'pre_det_precio',
        'pre_det_descuento',
        'pre_det_exenta',
        'pre_det_gravada5',
        'pre_det_gravada',
        'pre_det_subtotal',
    ];

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class, 'pre_numero', 'pre_numero');
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'ARTICULOS_cod', 'ARTICULOS_cod');
    }
}
