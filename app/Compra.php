<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Compra extends Model
{
    protected $primaryKey = 'compra_cod';
    protected $table= 'compra';
    public $timestamps = false;
    protected $fillable = [
		'compra_fecha', 'compra_tipo_factura', 'compra_cant_cuotas', 'compra_intervalo_venc', 'compra_factura', 'estado', 'compra_formacobro', 'compra_descuento'
	];
  public function scopeFiltrofecha($query,$desde,$hasta){
    	
    if(!empty($desde) && !empty($hasta)){
      return $query->whereBetween(DB::raw("date(compra.compra_fecha)"),[$desde,$hasta]);
    }else{
      return $query->paginate("100");
    }
  }
  public function scopeFiltrochart($query,$mes,$anho,$byYear){
    if($byYear){
      return $query->where(DB::raw("YEAR(compra.compra_fecha)"),"=",$anho);
    }else{
      return $query->where(DB::raw("YEAR(compra.compra_fecha)"),"=",$anho)
      ->where(DB::raw("MONTH(compra.compra_fecha)"),"=",$mes);
    }
  }
  public function scopeFiltrosuc($query,$suc){
      if($suc!='0'){
          return $query->where('s.suc_cod','=',$suc);
      }
  }
  public function scopeFiltroproveedor($query,$proveedor){
    if(!empty($proveedor)){
        return $query->where('p.proveedor_nombre','LIKE',"%$proveedor%");
    }
  }
  public function scopeWheresuc($query,$suc){
    if($suc!='0'){
        return $query->where('compra.suc_cod','=',$suc);
    }
  }
}
