<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\reffactura;

class ReffacturaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permiso:reffactura,open')->only(['index']);
        $this->middleware('permiso:reffactura,edit')->only(['update', 'store']);
    }

    public function index(){
        return view('reffactura');
    }
    public function store(Request $request){

    }
    public function update(Request $request)
    {
        reffactura::first()->update(['nivel1'=>$request->nivel1,
        'nivel2' => $request->nivel2,
        'nrofactura'=>$request->nrofactura,
        'timbrado'=>$request->timbrado,
        'vigenciadesde'=>$request->vigenciadesde,
        'vencimiento'=>$request->vencimiento]);
        return 'OK';
    }
    public function delete($id){

    }
    public function getAll(){
        return reffactura::All();
    }
}
