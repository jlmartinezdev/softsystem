<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table= 'proveedor';
    protected $primaryKey = 'proveedor_cod';
    public $timestamps = false;
    protected $fillable = [
        'proveedor_nombre','proveedor_direc','proveedor_telef','proveedor_ruc'
    ];

    public function scopeNombre($query, $nombre){
    	if($nombre)
    		return $query->whereRaw('upper(proveedor.proveedor_nombre) like ?',["%{$nombre}%"]);
    }
}
