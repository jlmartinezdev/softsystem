<?php

namespace App\Http\Controllers;
use App\Unidad;
use Illuminate\Http\Request;
use Auth;

class UnidadController extends Controller
{
    
    public function all(){
        return Unidad::All();
    }
}
