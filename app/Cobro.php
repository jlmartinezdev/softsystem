<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cobro extends Model
{
    protected $table = 'cobranzas';
    protected $primaryKey = 'cc_numero';
    public $timestamps = false;
}
