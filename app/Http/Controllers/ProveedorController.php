<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proveedor;
use App\Ciudad;
use Auth;

class ProveedorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $ciudades= Ciudad:: All();
        $proveedores = $this->getAll();
        return view('proveedor',compact('proveedores','ciudades'));
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
    public function store(Request $request){
        $ultimo= $this->ultimo();
        $ultimo = is_null($ultimo) ? 0 : $ultimo->PROVEEDOR_cod;
        $p= new Proveedor();
        $p->PROVEEDOR_cod= $ultimo + 1;
        $p->nacio_cod= $request->idnacionalidad; 
        $p->CIUDAD_cod= $request->idciudad; 
        $p->proveedor_nombre= $request->nombre;
        $p->proveedor_direc= $request->direccion;
        $p->proveedor_telef= $request->telefono;
        $p->proveedor_ruc= $request->ruc;
        $p->save();
    }
    private function ultimo(){
        return Proveedor::orderBy('PROVEEDOR_cod','DESC')->first();
    }
    public function update(Request $request, $id){
        Proveedor::where('PROVEEDOR_cod',$id)->update([
            'nacio_cod'=>$request->idnacionalidad,
            'CIUDAD_cod'=> $request->idciudad,
            'proveedor_nombre' => $request->nombre,
            'proveedor_direc' => $request->direccion,
            'proveedor_telef' => $request->telefono,
            'proveedor_ruc' => $request->ruc
        ]);

    }
    public function destroy($id){
        Proveedor::where('PROVEEDOR_cod',$id)->delete();
    }
}
