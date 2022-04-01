<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table= "roles";
    protected $primaryKey = 'cod_rol';
    public $timestamps = false;
    protected $fillable = [
        'nom_rol'
    ];

    public function us(){
        return $this->hasMany(User::class,'cod_rol');
    }
}
