<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cobro extends Model
{
    protected $table = 'cobranzas';
    protected $primaryKey = 'cc_numero';
    public $timestamps = false;

    public function scopeClientes($query, $request){
        if(!empty($request->search)){
            return $query->where('c.cliente_nombre','LIKE',"%$request->search%");
        }else{
            return $query->whereBetween('cobranzas.cob_fecha', [$request->alld,$request->allh]);
        }
    }
}
