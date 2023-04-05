<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ajuste extends Model
{
    protected $table= "configuraciones";
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'name', 'categoria', 'value', 'tipo_form'];
}
