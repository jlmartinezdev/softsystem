<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AjusteController extends Controller
{
    public function index(){
        return view('configuracion');
    }
}