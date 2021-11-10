<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proveedor;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = $this->ALL();
        return view('proveedor',compact('proveedores'));
    }

    public function getAll(){
        return Proveedor::All();
    }
    public function buscar(Request $request)
    {
        $proveedor= Proveedor::select('PROVEEDOR_cod','proveedor_ruc','proveedor_nombre','proveedor_direc','proveedor_telef')
        ->nombre(strtoupper($request->nombre))
        ->orderBy('proveedor_nombre','ASC')
        ->limit(100)
        ->get();
        return $proveedor;
    }
}
