<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Apertura extends Model
{
    protected $table= "apert_cierres_caja";
    protected $primaryKey = 'nro_operacion';
    public $timestamps = false;
    protected $fillable = [
        'nro_operacion', 'cod_usuarios', 'caja_cod', 'apert_fecha', 'apert_hora', 'apert_monto', 'cierre_fecha', 'cierre_hora', 'cierre_monto', 'apert_estado', 'suc_cod'
    ];

    public function scopeUsuario($query, $user){
        if($user){
            if($user=="2"){
                return $query->where("apert_cierres_caja.cod_usuarios",Auth::user()->cod_usuarios);
            }
        }
    }
}
