<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cobro extends Model
{
    protected $table = 'cobranzas';
    protected $primaryKey = 'cc_numero';
    public $timestamps = false;

    public function scopeClientes($query, $request){
        if (!empty($request->alld) && !empty($request->allh)) {
            $query->whereBetween('cobranzas.cob_fecha', [$request->alld, $request->allh]);
        }
        if (!empty($request->search)) {
            $term = trim($request->search);
            $query->where(function($q) use ($term) {
                $q->where('c.cliente_nombre', 'LIKE', "%$term%")
                  ->orWhere('c.cliente_ci', 'LIKE', "%$term%")
                  ->orWhere('cobranzas.nro_recibo', 'LIKE', "%$term%")
                  ->orWhere('dc.nro_fact_ventas', 'LIKE', "%$term%");
            });
        }
        if (!empty($request->alls) && $request->alls != '0') {
            $query->where('v.suc_cod', $request->alls);
        }
        return $query;
    }
}
