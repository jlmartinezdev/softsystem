<?php

namespace App\Http\Controllers;
use App\Seccion;
use Illuminate\Http\Request;
use Auth;

class SeccionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $secciones = $this->ALL();
        return view('seccion',compact('secciones'));
    }
    public function All(){
        return Seccion::All();
    }

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illumin·te\Http\Response
     */
    public function store(Request $request)
    {
        $seccion = new Seccion();
        $seccion->present_descripcion= $request->descripcion;
        $seccion->iva= $request->iva;
        $seccion->save();
        return 'OK';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Seccion::where('present_cod',$id)->update(['present_descripcion'=>$request->descripcion ,'iva' => $request->iva]);
        return 'OK';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Seccion::where('present_cod',$id)->delete();
    }
}
