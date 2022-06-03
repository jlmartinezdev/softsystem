<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Compra;
use App\MovimientoCaja;
use App\Sucursal;
use App\Empresa;
use DB;
use Auth;

class CompraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        return view('compra');
    }
    public function store(Request $request)
    {
        
        $compra = new Compra();
        $compra->PROVEEDOR_cod = $request->compraCabecera['idproveedor'];
        $compra->cod_usuarios = Auth::user()->cod_usuarios;
        $compra->ord_comp_nro = '0';
        $compra->suc_cod =  $request->compraCabecera['idSucursal'];
        $compra->compra_fecha = $request->compraCabecera['fecha']." ".date("H:i");
        $compra->compra_tipo_factura =  $request->compraCabecera['condicioncompra'];
        $compra->compra_cant_cuotas = 0;
        $compra->compra_intervalo_venc= '2030-01-01'; 
        $compra->compra_factura = $request->compraCabecera['factura_n1'].'-'.$request->compraCabecera['factura_n2'].'-'.$request->compraCabecera['factura_n3'];
        $compra->estado = '1';
        $compra->compra_formacobro = $request->compraCabecera['formacobro'];  
        $compra->compra_descuento = $request->compraCabecera['descuento']; 
        $compra->save();
        
        if($request->compraCabecera['condicioncompra']=='1'){
            $this->storeMovimiento($compra,$request->compraCabecera['idSucursal'],$request->compraCabecera['nro_operacion']);
        }
        
        foreach ($request->detalle as $detalle) {
           // $lote = empty($detalle['aux_lote']) ? " AND lote_nro is null": " AND lote_nro='".trim($detalle['aux_lote'])."'");
            DB::insert('INSERT INTO detalle_compra (ARTICULOS_cod, compra_cod, compra_precio, compra_cantidad, exentas, grabadas5, grabadas10)
VALUES (?, ?, ?, ?, ?, ?, ?);',[$detalle['codigo'],$compra->compra_cod,$detalle['costo'],$detalle['cantidad'],0,0,0]);
           DB::update('update stock set cantidad = (cantidad + ?) where id_stock=?',[$detalle['cantidad'],$detalle['idstock']]);
        } 
        for($i=0;$i< count($request->precios);$i++){
            $suma = 0;
            for($y=0;$y<=10;$y++){
                $suma += intval($request->precios[$i]['precios'][$y]["p"]);
            }
            if($suma > 0) {
                DB::table('precios')->where('ARTICULOS_cod',$request->precios[$i]['id'])->delete();
            }
           
            for($x=0;$x< count($request->precios[$i]['precios']);$x++){
                if($suma > 0){
                    DB::insert('INSERT INTO precios VALUES (?,?,?,?,?,?)',[$x+2,$request->precios[$i]['id'],$request->precios[$i]['precios'][$x]["p"],$request->precios[$i]['precios'][$x]["m"],$x+2,$request->precios[$i]['precios'][$x]["c"]]);
                }
                
            }
        }
        return 1;
        //return $compra->compra_cod;
        
    }

    private function storeMovimiento(Compra $venta,$idSucursal,$ope ){
        $movimiento= new MovimientoCaja();
        $movimiento->nro_operacion= $ope;
        $movimiento->mov_fecha= date('Y-m-d H:i');
        $movimiento->mov_concepto= 'Compra Nº: '.$venta->compra_cod;
        $movimiento->mov_tipo= 'Salida';
        $movimiento->mov_monto= $venta->venta_total;
        $movimiento->nro_fact_ventas= $venta->nro_fact_ventas;
        $movimiento->suc_cod= $idSucursal;
        $movimiento->save();
        
    }
    public function indexInf(){
        $sucursales= Sucursal::all();
        return view('informes.compra',compact('sucursales'));
    }
    public function getHistorialPrecio(Request $request){
        return DB::select("SELECT dc.compra_precio,p.proveedor_nombre,DATE_FORMAT(c.compra_fecha,'%d/%m/%Y') AS compra_fecha FROM detalle_compra dc INNER JOIN compra c ON dc.compra_cod= c.compra_cod INNER JOIN proveedor p ON c.PROVEEDOR_cod=p.PROVEEDOR_cod WHERE dc.ARTICULOS_cod= ? ORDER BY c.compra_fecha DESC limit 10",[$request->ARTICULOS_cod]);

    }
    public function getCompraByFecha(Request $request){
        return Compra::select('compra.compra_cod',DB::raw('SUM(dc.compra_precio*dc.compra_cantidad) AS total'),DB::raw('DATE_FORMAT(compra.compra_fecha,"%d/%m/%Y %H:%i") AS compra_fecha'),'p.proveedor_nombre', 'p.proveedor_direc', 'p.proveedor_ruc','s.suc_desc','compra.compra_tipo_factura')
        ->join('proveedor as p','compra.PROVEEDOR_cod','=','p.PROVEEDOR_cod')
        ->join('detalle_compra as dc','dc.compra_cod','=','compra.compra_cod')
        ->join('sucursales as s','compra.suc_cod','=','s.suc_cod')
        ->filtrofecha($request->alld,$request->allh)
        ->filtrosuc($request->alls)
        ->filtroproveedor($request->proveedor)
        ->orderBy('compra.compra_cod','desc')
        ->groupBy('compra.compra_cod')
        ->get();

    }
    public function pdfboleta($id){
        $compra =  Compra::select('compra.compra_cod','compra_fecha',DB::raw('SUM(dc.compra_precio*dc.compra_cantidad) AS total'),'proveedor.proveedor_nombre','proveedor_ruc')
        ->join('detalle_compra as dc','compra.compra_cod','=','dc.compra_cod')
        ->join('proveedor','proveedor.PROVEEDOR_cod','=','compra.PROVEEDOR_cod')
        ->where('compra.compra_cod',$id)
        ->groupBy('compra.compra_cod')
        ->first();
        $detalle= $this->getDetalle($id);
        $empresa= Empresa::first();
        //$pdf= PDF::loadView('pdf.compra',compact('compra','detalle','empresa'));
        // return $pdf->stream();
        return view('pdf.compra',compact('compra','detalle','empresa'));
    }
    public function getDetalle($nro_compra){
        return DB::select('SELECT dc.*,a.producto_nombre,a.producto_c_barra,p.iva FROM detalle_compra dc INNER JOIN articulos a ON dc.ARTICULOS_cod=a.ARTICULOS_cod inner join presentacion p on a.present_cod=p.present_cod where dc.compra_cod=?',[$nro_compra]);
    }

}
