<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table= "roles";
    protected $primaryKey = 'cod_rol';
    public $timestamps = false;
    protected $fillable = [
        'nom_rol',
        'descripcion_rol',
        'color_rol',
        'activo'
    ];

    public function us(){
        return $this->hasMany(User::class,'cod_rol');
    }

    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'cod_rol', 'cod_rol');
    }

    public function acciones()
    {
        return $this->hasMany(AccionRol::class, 'cod_rol', 'cod_rol');
    }
}
