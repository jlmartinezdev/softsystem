<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use App\Seccion;
use App\Unidad;
use DB;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function infstock(){
        $secciones= Seccion::All();
        $unidades = Unidad::All();
        return view('informes.stock',compact('secciones','unidades'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Stock::select("id_stock as id","cantidad","lote_nro as loteold","lote_nro as lotenew","stock_fech_venc as vencimiento","suc_cod as sucursal")
        ->where('ARTICULOS_cod','=',$id)
        ->get();
        return "OK";
    }
    private function setVencimiento($fecha){
        if(empty($fecha) || $fecha=="Sin vencimiento"){
            return "2030-01-01";
        }
        return $fecha;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
         for ($i=0; $i <count($request->stock) ; $i++) { 
            if($request->stock[$i]['id'] > 5){
                Stock::where('id_stock',$request->stock[$i]['id'])->update([
                    'cantidad' =>$request->stock[$i]['cantidad'], 
                    'stock_fech_venc' => $this->setVencimiento($request->stock[$i]['vencimiento']),
                    'lote_nro' => $request->stock[$i]['lotenew']
                ]);
            }else{
                $stock = new Stock();
                $stock->articulos_cod= $id;
                $stock->suc_cod=$request->stock[$i]['sucursal'];
                $stock->cantidad= $request->stock[$i]['cantidad'];
                $stock->stock_fech_venc= $this->setVencimiento($request->stock[$i]['vencimiento']);
                $stock->lote_nro= $request->stock[$i]['lotenew'];
                $stock->save();
            }
            //DB::select('call insert_stock(?,?,?,?,?,?)',[$id,$request->stock[$i]['sucursal'],$request->stock[$i]['cantidad'],$this->setVencimiento($request->stock[$i]['vencimiento']),$request->stock[$i]['loteold'],$request->stock[$i]['lotenew']]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $stock= Stock::where('id_stock',$id)->delete();
        if($stock){
            return "OK";
        }else{
            return "Error";
        }
    }
}
