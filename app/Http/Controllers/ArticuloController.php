<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Articulo;
use App\Seccion;
use App\Unidad;
use App\Stock;
use DB;
use App\Exports\ArticulosExport;
use App\Exports\articulosPreciosExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;

class ArticuloController extends Controller
{
    public $request= '';
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        
        $secciones= Seccion::All();
        $unidades = Unidad::All();
        return view('articulo',compact('secciones','unidades'));
    }
    public function cm(){
        $secciones= Seccion::All();
        $unidades = Unidad::All();
        return view('articulo.cm',compact('secciones','unidades'));
    }
    public function cmupdate($id){
        $secciones= Seccion::All();
        $unidades = Unidad::All();
        return view('articulo.cm',compact('secciones','unidades'))->with('id',$id);
    }
    public function getArticulo(Request $request){
        $criterios = ["producto_nombre","producto_c_barra"];
        $columnas= ["articulos.producto_nombre","articulos.articulos_cod", "articulos.pre_venta1"];
        $columna=$columnas[$request->col];
        $criterio=$criterios[$request->criterio];
        $seccion= $request->seccion;
        $buscar = $request->buscar;
        $idsucursal= isset($request->suc) ? $request->suc : null;
        
            $articulos = Articulo::join('stock', 'articulos.articulos_cod', '=', 'stock.articulos_cod')
            ->join('presentacion','articulos.present_cod','=','presentacion.present_cod')
            ->join('unidad','articulos.uni_codigo','=','unidad.uni_codigo')
            ->select(  'articulos.*','presentacion.present_descripcion',DB::raw('SUM(stock.cantidad) AS cantidad'),'unidad.uni_nombre','unidad.uni_abreviatura')
            ->descripcion($buscar)
            ->seccion($seccion)
            ->bysucursal($idsucursal)
            ->groupBy('articulos.articulos_cod')
            ->orderBy($columna, $request->ord)
            ->get();
        
        return $articulos;
    }
    public function getInventario(Request $request){
        $articulos= DB::select('SELECT a.producto_c_barra,a.producto_nombre,a.pre_venta1,p.present_descripcion, SUM(s.cantidad) AS cantidad, SUM(dv.venta_cantidad) AS salida, SUM(dc.compra_cantidad) AS entrada FROM articulos a INNER JOIN presentacion p ON a.present_cod= p.present_cod INNER JOIN stock s ON a.ARTICULOS_cod=s.ARTICULOS_cod LEFT JOIN detalle_venta dv ON a.ARTICULOS_cod= dv.ARTICULOS_cod LEFT JOIN detalle_compra dc ON a.ARTICULOS_cod= dc.ARTICULOS_cod LEFT JOIN ventas v ON dv.nro_fact_ventas= v.nro_fact_ventas WHERE DATE(v.venta_fecha) BETWEEN ? AND ? GROUP BY a.ARTICULOS_cod',[$request->desde,$request->hasta]);
            
        return $articulos;
    }
    public function getPrecios($id){
        return DB::select("SELECT truncate(precio,0) as p, truncate(margen,0) as m, truncate(monto_cuota,0) as c FROM precios where ARTICULOS_cod=".$id);
    }
    public function getByCodigo(Request $request){
        return Articulo::join('stock', 'articulos.articulos_cod', '=', 'stock.articulos_cod')
            ->join('presentacion','articulos.present_cod','=','presentacion.present_cod')
            ->join('unidad','articulos.uni_codigo','=','unidad.uni_codigo')
            ->select(  'articulos.*','presentacion.present_descripcion',DB::raw('SUM(stock.cantidad) AS cantidad'),'unidad.uni_nombre','unidad.uni_abreviatura')
            ->where('articulos.producto_c_barra','=',$request->codigo)
            ->groupBy('articulos.articulos_cod')
            ->first();
    }
    public function getById(Request $request){
        return Articulo::join('stock', 'articulos.articulos_cod', '=', 'stock.articulos_cod')
            ->join('presentacion','articulos.present_cod','=','presentacion.present_cod')
            ->join('unidad','articulos.uni_codigo','=','unidad.uni_codigo')
            ->select(  'articulos.*','presentacion.present_descripcion',DB::raw('SUM(stock.cantidad) AS cantidad'),'unidad.uni_nombre','unidad.uni_abreviatura')
            ->where('articulos.articulos_cod','=',$request->codigo)
            ->groupBy('articulos.articulos_cod')
            ->first();
    }
    public function validarCbarra(Request $request){
        return Articulo::where('producto_c_barra',$request->cbarra)->count();
    }
    public function getUltimo(){
        return Articulo::max('articulos_cod');
    }
    public function reservarCodigo(Request $request){
       
        $articulo= new Articulo();
        $articulo->ARTICULOS_cod= $request->codigo;
        $articulo->uni_codigo=1;
        $articulo->present_cod=1;
        $articulo->save();
        return ["success"=>"OK"];
    }

  

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $articulo= new Articulo();
        $articulo->uni_codigo=$request->articulo['unidad']; 
        $articulo->present_cod =  $request->articulo['seccion'];
        $articulo->producto_nombre = $request->articulo['descripcion'];
        $articulo->producto_costo_compra = $request->articulo['costo'];
        $articulo->producto_costo_venta = $request->articulo['p1'];
        $articulo->foto= '';
        $articulo->producto_fecHab = '0';
        $articulo->producto_vencimiento = '2030-01-01';
        $articulo->pre_venta1 = $request->articulo['p1'];
        $articulo->pre_venta2 = $request->articulo['p2'];
        $articulo->pre_venta3 = $request->articulo['p3'];
        $articulo->pre_venta4 = $request->articulo['p4'];
        $articulo->pre_venta5= $request->articulo['p5'];
        $articulo->producto_ubicacion = is_null($request->articulo['ubicacion'])? '' : $request->articulo['ubicacion'];
        $articulo->producto_peso = '0';
        $articulo->producto_factor =$request->articulo['factor'];
        $articulo->pre_margen1 = $request->articulo['m1'];
        $articulo->pre_margen2 = $request->articulo['m2'];
        $articulo->pre_margen3 = $request->articulo['m3'];
        $articulo->pre_margen4 = $request->articulo['m4'];
        $articulo->pre_margen5 = $request->articulo['m5'];
        $articulo->producto_indicaciones= is_null($request->articulo['indicaciones']) ? '' : $request->articulo['indicaciones'];
        $articulo->producto_dosis= is_null($request->articulo['modouso']) ? '' :$request->articulo['modouso'] ;
        $articulo->producto_formula= '';
        $articulo->producto_dimagen= '';
        $articulo->save();
        $articulo= Articulo::latest('ARTICULOS_cod', 'desc')->first();
        $cbarra= null;
        if (!is_null($request->articulo['c_barra'])){
            $cbarra=$request->articulo['c_barra'];
        }else{
            $cbarra= $this->rellenar($articulo->ARTICULOS_cod);
        }
        Articulo::where('articulos_cod',$articulo->ARTICULOS_cod)->update([
            'producto_c_barra'=>$cbarra
        ]);
        $stock= array();
        if( Auth::user()->roles()->first()->nom_rol!='Administrador'){
            for ($i=0; $i <count($request->stock); $i++){
                $temp= array('sucursal' => $request->stock[$i]['sucursal'], 
                    'cantidad' => 0,
                    'vencimiento' => $request->stock[$i]['vencimiento'],
                    'loteold' => $request->stock[$i]['loteold'],
                    'lotenew' => $request->stock[$i]['lotenew']);
                array_push($stock,$temp);
            }
        }else{
            for ($i=0; $i <count($request->stock); $i++){
                $temp= array('sucursal' => $request->stock[$i]['sucursal'], 
                    'cantidad' => $request->stock[$i]['cantidad'],
                    'vencimiento' => $request->stock[$i]['vencimiento'],
                    'loteold' => $request->stock[$i]['loteold'],
                    'lotenew' => $request->stock[$i]['lotenew']);
                array_push($stock,$temp);
            }
        }
        for ($i=0; $i <count($stock) ; $i++) { 
            $s = new Stock();
            $s->articulos_cod= $articulo->ARTICULOS_cod;
            $s->suc_cod=$request->stock[$i]['sucursal'];
            $s->cantidad= $request->stock[$i]['cantidad'];
            $s->stock_fech_venc= $this->setVencimiento($request->stock[$i]['vencimiento']);
            $s->lote_nro= $request->stock[$i]['lotenew'];
            $s->save();
            
        }
       
        $suma= 0;
        for($i=0;$i<=10;$i++){
            $suma += intval($request->precios[$i]["p"]);
        }
        if($suma > 0 ){
            for($i=2;$i<=18;$i++){
                DB::insert('INSERT INTO precios VALUES (?,?,?,?,?,?)',[$i,$articulo->ARTICULOS_cod,$request->precios[$i-2]["p"],$request->precios[$i-2]["m"],$i,$request->precios[$i-2]["c"]]);
            }
        }
        return $articulo->ARTICULOS_cod;
        
    }

    private function rellenar($codigo){
       return str_pad($codigo,7,"0",STR_PAD_LEFT);
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
       // return $request;
        
        $ok= Articulo::where('articulos_cod',$id)->update([
            'uni_codigo'=>$request->articulo['unidad'], 
            'producto_c_barra'=>$request->articulo['c_barra'],
            'present_cod' =>  $request->articulo['seccion'],
            'producto_nombre' => $request->articulo['descripcion'],
            'producto_costo_compra' => $request->articulo['costo'],
            'producto_costo_venta' => $request->articulo['p1'],
            'foto'=> '',
            'producto_fecHab' => '0',
            'producto_vencimiento' => '2030-01-01',
            'pre_venta1' => $request->articulo['p1'],
            'pre_venta2' => $request->articulo['p2'],
            'pre_venta3' => $request->articulo['p3'],
            'pre_venta4' => $request->articulo['p4'],
            'pre_venta5' => $request->articulo['p5'],
            'producto_ubicacion' => is_null($request->articulo['ubicacion']) ? '' : $request->articulo['ubicacion'] ,
            'producto_peso' => '0',
            'producto_factor' =>$request->articulo['factor'],
            'pre_margen1' => $request->articulo['m1'],
            'pre_margen2' => $request->articulo['m2'],
            'pre_margen3' => $request->articulo['m3'],
            'pre_margen4' => $request->articulo['m4'],
            'pre_margen5' => $request->articulo['m5'],
            'producto_indicaciones'=> is_null($request->articulo['indicaciones']) ? '' : $request->articulo['indicaciones'],
            'producto_dosis'=> is_null($request->articulo['modouso']) ? '' :$request->articulo['modouso'] ,
            'producto_formula'=> '',
            'producto_dimagen'=> ''
        ]);
        if( Auth::user()->roles()->first()->nom_rol=='Administrador'){
            for ($i=0; $i < count($request->stock) ; $i++) { 
                if($request->stock[$i]['id'] > 0){
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
            }
        }
        $suma = 0;
        for($i=0;$i<=10;$i++){
            $suma += intval($request->precios[$i]["p"]);
        }
        for($i=0;$i< count($request->precios);$i++){
            if($request->articulo['existePrecios']){
                DB::update('UPDATE precios SET precio= ?,margen=?, cant_cuota=?, monto_cuota=? WHERE id_precio=? AND articulos_cod=?',[$request->precios[$i]["p"],$request->precios[$i]["m"],$i+2,$request->precios[$i]["c"],$i+2,$id]);
            }else{
                if($suma > 0){
                    DB::insert('INSERT INTO precios VALUES (?,?,?,?,?,?)',[$i+2,$id,$request->precios[$i]["p"],$request->precios[$i]["m"],$i+2,$request->precios[$i]["c"]]);
                }
            }
            
        }
        //$ok= $ok ? "OK": "ERROR";
        return $ok;
    }
    private function setVencimiento($fecha){
        if(empty($fecha) || $fecha=="Sin vencimiento"){
            return "2030-01-01";
        }
        return $fecha;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if( Auth::user()->roles()->first()->nom_rol=='Administrador'){
            DB::select("delete from stock where articulos_cod=?",[$id]);
            DB::select("delete from precios where articulos_cod=?",[$id]);
            $articulo= Articulo::where('ARTICULOS_cod',$id)->delete();
            if($articulo){
                return "OK";
            }else{
                return "Error";
            }
        }
        return "Sin Privilegio...";
    }
    public function informe(){
        return view('informes.articulo');
    }
    public function export(Request $request){
        //return Excel::download(new ArticulosExport, 'articulos.xlsx');
        //return (new InvoicesExport(2018))->download('invoices.xlsx');
        return (new ArticulosExport)->filtro($request)->download('articulos.xlsx');
    }
    public function export_costo(Request $request){
        //return Excel::download(new ArticulosExport, 'articulos.xlsx');
        //return (new InvoicesExport(2018))->download('invoices.xlsx');
        return (new ArticulosExportCosto)->filtro($request)->download('articulos.xlsx');
    }
    public function exportPrecio(Request $request){
      
        $date = date('d_m_Y');
       return (new articulosPreciosExport)->filtro($request)->download($date.'_precioscreditos.xlsx');
    }
   
}
