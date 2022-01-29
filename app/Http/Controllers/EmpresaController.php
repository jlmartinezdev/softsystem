<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\Ciudad;
use App\Sucursal;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empresa = Empresa::first();
        $ciudades = Ciudad::All();
        $sucursales = Sucursal::All();
        return view('empresa',compact('empresa','ciudades','sucursales'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function show(Empresa $empresa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function edit(Empresa $empresa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        Empresa::where('emp_codigo',0)->update([
            'emp_nombre'=>$request->nombre,
            'suc_cod'=> $request->sucursal,
            'CIUDAD_cod' => $request->ciudad,
            'emp_direccion' => $request->direccion,
            'emp_ruc' => $request->ruc,
            'emp_celular' => $request->celular,
            'emp_telefono' => $request->telefono,
            'emp_correo' => $request->correo,
            'emp_web' => $request->web,
            'emp_descripcion' => $request->descripcion
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Empresa $empresa)
    {
        //
    }
}
