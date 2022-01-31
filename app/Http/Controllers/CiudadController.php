<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ciudad;
use App\Departamento;
use Auth;
class CiudadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $departamentos= Departamento::All();
        $ciudades= Ciudad::join('departamento','ciudad.depart_codigo','departamento.depart_codigo')->get();
        return view('ciudad',compact('departamentos','ciudades'));
    }
    public function store(Request $request)
    {
        $ciudad = new Ciudad();
        $ciudad->ciudad_nombre= $request->ciudad;
        $ciudad->depart_codigo= $request->departamento;
        $ciudad->save();
        return 'OK';
    }

    public function update(Request $request, $id)
    {
        Ciudad::where('CIUDAD_cod',$id)->update(['ciudad_nombre'=>$request->ciudad ,'depart_codigo' => $request->departamento]);
        return 'OK';
    }

    public function destroy($id)
    {
        Ciudad::where('CIUDAD_cod',$id)->delete();
    }
}
