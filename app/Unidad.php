<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Articulo;

class Unidad extends Model
{
    protected $table= "unidad";
    protected $primaryKey = 'uni_codigo';
    public $timestamps = false;
    protected $fillable = [
        'uni_nombre','uni_abreviatura'
    ];

    public function articulos()
    {
        return $this->hasMany(Articulo::class, 'uni_codigo', 'uni_codigo');
    }
}

