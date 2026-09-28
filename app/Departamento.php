<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table= 'departamento';
    protected $primaryKey = 'depart_codigo';
    public $timestamps = false;

    public function ciudades()
    {
        return $this->hasMany(Ciudad::class, 'depart_codigo', 'depart_codigo');
    }
}
