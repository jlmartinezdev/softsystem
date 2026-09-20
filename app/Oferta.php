<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Oferta extends Model
{
    protected $table = 'ofertas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre',
        'codigo',
        'articulos_cod',
        'tipo',
        'cantidad_min',
        'fecha_desde',
        'fecha_hasta',
        'descuento_tipo',
        'descuento_valor',
        'activo',
        'observacion',
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'articulos_cod', 'ARTICULOS_cod');
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', 1);
    }

    /**
     * ¿La oferta aplica para cantidad + fecha de venta?
     */
    public function aplica($cantidad, $fecha = null)
    {
        $cantidad = (float) $cantidad;
        $fecha = $fecha ? Carbon::parse($fecha)->startOfDay() : Carbon::today();

        $tipo = $this->tipo;

        $okCantidad = true;
        $okFecha = true;

        if ($tipo === 'cantidad' || $tipo === 'ambos') {
            $min = (float) ($this->cantidad_min ?: 0);
            $okCantidad = $min > 0 && $cantidad >= $min;
        }

        if ($tipo === 'fecha' || $tipo === 'ambos') {
            $okFecha = true;
            if ($this->fecha_desde) {
                $okFecha = $okFecha && $fecha->gte(Carbon::parse($this->fecha_desde)->startOfDay());
            }
            if ($this->fecha_hasta) {
                $okFecha = $okFecha && $fecha->lte(Carbon::parse($this->fecha_hasta)->startOfDay());
            }
            if (!$this->fecha_desde && !$this->fecha_hasta) {
                $okFecha = false;
            }
        }

        if ($tipo === 'cantidad') {
            return $okCantidad;
        }
        if ($tipo === 'fecha') {
            return $okFecha;
        }
        // ambos: debe cumplir cantidad Y fecha
        return $okCantidad && $okFecha;
    }

    /**
     * Calcula precio unitario con descuento.
     */
    public function precioFinal($precioBase)
    {
        $base = (float) $precioBase;
        $valor = (float) $this->descuento_valor;

        switch ($this->descuento_tipo) {
            case 'monto':
                return max(0, $base - $valor);
            case 'precio_fijo':
                return max(0, $valor);
            case 'porcentaje':
            default:
                return max(0, round($base * (1 - ($valor / 100))));
        }
    }
}
