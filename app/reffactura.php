<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class reffactura extends Model
{
    protected $table= "reffactura";
    protected $primaryKey = 'nivel1';
    public $timestamps = false;
    protected $fillable = [
        'nivel1', 'nivel2','nrofactura','timbrado','vencimiento','vigenciadesde'
    ];
}
