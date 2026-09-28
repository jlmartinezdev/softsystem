<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Presupuesto extends Model
{
    protected $table = 'presupuesto';
    protected $primaryKey = 'pre_numero';
    public $timestamps = true;

    protected $fillable = [
        'CLIENTES_cod',
        'cod_usuarios',
        'suc_cod',
        'pre_fecha',
        'pre_validez_dias',
        'pre_vencimiento',
        'pre_tipo',
        'estado',
        'descuento',
        'subtotal',
        'total',
        'total_exenta',
        'total_iva5',
        'total_iva10',
        'total_iva',
        'observaciones',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'CLIENTES_cod', 'CLIENTES_cod');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'cod_usuarios', 'cod_usuarios');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'suc_cod', 'suc_cod');
    }

    public function detalles()
    {
        return $this->hasMany(PresupuestoDetalle::class, 'pre_numero', 'pre_numero');
    }

    public function scopeFiltroFecha($query, $desde, $hasta)
    {
        if (!empty($desde) && !empty($hasta)) {
            return $query->whereBetween(DB::raw("date(presupuesto.pre_fecha)"), [$desde, $hasta]);
        }
        return $query;
    }

    public function scopeFiltroEstado($query, $estado)
    {
        if (!empty($estado) && $estado !== 'TODOS') {
            return $query->where('presupuesto.estado', $estado);
        }
        return $query;
    }

    public function scopeFiltroSucursal($query, $suc)
    {
        if (!empty($suc) && $suc != '0') {
            return $query->where('presupuesto.suc_cod', $suc);
        }
        return $query;
    }

    public function scopeFiltroBusqueda($query, $termino)
    {
        if (!empty($termino)) {
            $t = trim($termino);
            return $query->where(function ($q) use ($t) {
                $q->where('presupuesto.pre_numero', $t)
                  ->orWhere('presupuesto.observaciones', 'like', "%{$t}%")
                  ->orWhereHas('cliente', function ($qc) use ($t) {
                      $qc->where('cliente_nombre', 'like', "%{$t}%")
                         ->orWhere('cliente_ruc', 'like', "%{$t}%")
                         ->orWhere('cliente_ci', 'like', "%{$t}%");
                  });
            });
        }
        return $query;
    }
}
