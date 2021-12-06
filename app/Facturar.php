<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facturar extends Model
{
    protected $table= 'factura';
    protected $primaryKey = 'nro_factura';
    public $timestamps = false;
    public $incrementing= false;
}
