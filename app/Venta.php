<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Venta extends Model
{
    protected $primaryKey= "nro_fact_ventas";
    public $timestamps = false;
    public function scopeFiltrofecha($query,$desde,$hasta){
    	
    	if(!empty($desde) && !empty($hasta)){
			return $query->whereBetween(DB::raw("date(ventas.venta_fecha)"),[$desde,$hasta]);
		}else{
			return $query->paginate("100");
		}
    }
    public function scopeFiltrochart($query,$mes,$anho){
    	if($mes== 13){
    		return $query->where(DB::raw("YEAR(ventas.venta_fecha)"),"=",$anho);
    	}else{
    		return $query->where(DB::raw("YEAR(ventas.venta_fecha)"),"=",$anho)
    		->where(DB::raw("MONTH(ventas.venta_fecha)"),"=",$mes);
    	}
    }
    public function scopeFiltrosuc($query,$suc){
        if($suc!='0'){
            return $query->where('s.suc_cod','=',$suc);
        }
    }
    public function scopeWheresuc($query,$suc){
        if($suc!='0'){
            return $query->where('ventas.suc_cod','=',$suc);
        }
    }
    public function scopeFiltrocliente($query, $cliente, $isNumber){
        if (!empty($cliente)) {
            $clienteTrim = trim($cliente);
            return $query->where(function ($q) use ($clienteTrim) {
                $q->where('c.cliente_nombre', 'like', "%{$clienteTrim}%")
                  ->orWhere('c.cliente_ruc', 'like', "%{$clienteTrim}%")
                  ->orWhere('c.cliente_ci', 'like', "%{$clienteTrim}%")
                  ->orWhere('ventas.nro_fact_ventas', $clienteTrim)
                  ->orWhere('ventas.documento', 'like', "%{$clienteTrim}%");
            });
        }
    }
}
