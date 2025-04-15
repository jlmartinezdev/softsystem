<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venta;
use App\Apertura;
use App\Sucursal;
use App\MovimientoCaja;
use App\Empresa;
use App\CtaCobrar;
use App\Cobro;
use App\Stock;
use DB;
use Auth;
use PDF;
class VentaController extends Controller
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
       // return view('venta.generar');
        $apertura=Apertura::join('sucursales','apert_cierres_caja.suc_cod','=','sucursales.suc_cod')->join('caja','apert_cierres_caja.caja_cod','=','caja.caja_cod')
        ->where('apert_cierres_caja.apert_fecha','=',date('Y-m-d'))->get();
        return view('venta',compact('apertura'));
    }
    public function indexanular(){
        return view('anularventa');
    }
    public function indexInf(){
        $sucursales= Sucursal::all();
        return view('informes.venta',compact('sucursales'));
    }
    public function getVentaByFecha(Request $request){
        return Venta::select('ventas.nro_fact_ventas','ventas.venta_descuento','ventas.documento','ventas.venta_total',DB::raw('DATE_FORMAT(ventas.venta_fecha,"%d/%m/%Y %H:%i") AS fecha'),'c.cliente_nombre', 'c.cliente_direccion', 'c.cliente_ruc','s.suc_desc','ventas.tipo_factura')
        ->join('clientes as c','ventas.clientes_cod','=','c.clientes_cod')
        ->join('sucursales as s','ventas.suc_cod','=','s.suc_cod')
        ->filtrofecha($request->alld,$request->allh)
        ->filtrosuc($request->alls)
        ->orderBy('ventas.nro_fact_ventas','desc')
        ->get();

    }
    public function getVentaByCliente(Request $request){
        return Venta::select('ventas.nro_fact_ventas','ventas.documento','ventas.venta_descuento','ventas.venta_total',DB::raw('DATE_FORMAT(ventas.venta_fecha,"%d/%m/%Y %H:%i") AS fecha'),'c.cliente_nombre', 'c.cliente_direccion','c.cliente_cel', 'c.cliente_ruc','s.suc_desc','ventas.tipo_factura')
        ->join('clientes as c','ventas.clientes_cod','=','c.clientes_cod')
        ->join('sucursales as s','ventas.suc_cod','=','s.suc_cod')
        ->filtrocliente($request->cliente,$request->isNumber)
        ->filtrosuc($request->alls)
        ->orderBy('ventas.nro_fact_ventas','desc')
        ->limit(100)
        ->get();
    }
    public function getVentaArticulo(Request $request){
        $suc= $request->arts!='0' ? 'v.suc_cod='.$request->arts.' AND ' :'';
         return DB::select("SELECT SUM(dv.venta_cantidad) AS vendida, dv.ARTICULOS_cod, a.producto_c_barra, a.producto_nombre, s.cantidad, p.present_descripcion FROM detalle_venta dv INNER JOIN ventas v ON dv.nro_fact_ventas=v.nro_fact_ventas INNER JOIN articulos a ON dv.ARTICULOS_cod=a.ARTICULOS_cod INNER JOIN stock s ON a.ARTICULOS_cod= s.ARTICULOS_cod INNER JOIN presentacion p ON a.present_cod=p.present_cod WHERE ".$suc." DATE(v.venta_fecha) BETWEEN '".$request->artd."' AND '".$request->arth."' GROUP BY dv.ARTICULOS_cod ORDER BY vendida DESC");
    }
    public function getDetalle($nro_venta){
        return DB::select('SELECT dv.*,a.producto_nombre,a.producto_c_barra,p.iva FROM detalle_venta dv INNER JOIN articulos a ON dv.ARTICULOS_cod=a.ARTICULOS_cod inner join presentacion p on a.present_cod=p.present_cod where dv.nro_fact_ventas=?',[$nro_venta]);
    }
    public function getCabecera($nro_venta){
        $cabecera= DB::select('SELECT v.*, c.cliente_ci,c.cliente_nombre FROM ventas v INNER JOIN clientes c ON v.CLIENTES_cod= c.CLIENTES_cod WHERE v.nro_fact_ventas= ?',[$nro_venta]);
        return ["venta"=> $cabecera, "detalle" => $this->getDetalle($nro_venta)];
    }
    public function getVentaChart(Request $request){
        
       return Venta::select(DB::raw("SUM(ventas.venta_total) AS total"),DB::raw("DATE_FORMAT(ventas.venta_fecha,'%Y-%m-%d') AS fecha"))
        ->filtrochart($request->chart['mes'],$request->chart['anho'])
        ->groupBy(DB::raw("DATE(ventas.venta_fecha)"))
        ->get();

    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $venta = new Venta();
        $venta->clientes_cod= $request->ventaCabecera['clienteId'];
        $venta->cod_usuarios= Auth::user()->cod_usuarios;
        $venta->suc_cod= $request->ventaCabecera['idSucursal'];
        $venta->venta_total= $request->ventaCabecera['total'];
        $venta->venta_fecha = $request->ventaCabecera['fecha'].date(' H:i');
        $venta->tipo_factura = $request->ventaCabecera['condicionventa'];
        $venta->cant_cuotas =0;
        $venta->intervalo_venc='2030-01-01'; 
        $venta->venta_estado='2'; 
        $venta->venta_descuento=$request->ventaCabecera['descuento'] ; 
        $venta->forma_cobro= $request->ventaCabecera['formacobro']; 
        $venta->documento= $request->ventaCabecera['documento']; 
        $venta->save();
        if($request->ventaCabecera['condicionventa']=='1'){
            if(Auth::user()->cod_usuarios!= 1){
                $this->storeMovimiento($request->ventaCabecera['idSucursal'],$request->ventaCabecera['nro_operacion'],[$venta->nro_fact_ventas, $venta->venta_total]);
            }
        }else{
            foreach($request->cuotas as $cuota){
                
                $this->storeCtaCobrar($venta->nro_fact_ventas,$cuota);
                if($cuota['tipo']=='Entrega'){
                    $this->storeCobro($request->ventaCabecera,$venta->nro_fact_ventas, $cuota);
                    if(Auth::user()->cod_usuarios!= 1){
                        $this->storeMovimiento($request->ventaCabecera['idSucursal'],$request->ventaCabecera['nro_operacion'],[$venta->nro_fact_ventas,$cuota['monto']]);
                    }
                }
            }
        }
        
        foreach ($request->detalle as $detalle) {
           // $lote = empty($detalle['aux_lote']) ? " AND lote_nro is null": " AND lote_nro='".trim($detalle['aux_lote'])."'");
            DB::insert('INSERT INTO detalle_venta (ARTICULOS_cod, nro_fact_ventas, venta_precio, venta_cantidad, precio_compra) VALUES (?, ?, ?, ?,?)',[$detalle['codigo'],$venta->nro_fact_ventas,$detalle['precio'],$detalle['cantidad'],$detalle['costo']]);
            if($request->ventaCabecera['descontar_stock']==1){
                DB::update('update stock set cantidad = (cantidad - ?) where id_stock=?',[$detalle['cantidad'],$detalle['idstock']]);
            }
        }
        return $venta->nro_fact_ventas;
        
    }
    public function destroy(Request $request){
        foreach ($request->articulos as $articulo) {
            Stock::where('ARTICULOS_cod',$articulo['id'])
            ->first()
            ->increment('cantidad',$articulo['cantidad']);
        }
        DB::table('cobranza_detalle')->where('nro_fact_ventas',$request->id)->delete();
        DB::table('cobranzas as c')->join('cobranza_detalle as cd','c.cc_numero','=','cd.cc_numero')
        ->where('cd.nro_fact_ventas',$request->id)->delete();
        DB::table('ctas_cobrar')->where('nro_fact_ventas',$request->id)->delete();
        DB::table('detalle_venta')->where('nro_fact_ventas',$request->id)->delete();
        DB::table('ventas')->where('nro_fact_ventas',$request->id)->delete();
        return "ok";
    }

    private function storeCtaCobrar($idventa, $cuota){
        $CtaCobrar= new CtaCobrar();
        $CtaCobrar->nro_cuotas = $cuota['nro'];
        $CtaCobrar->nro_fact_ventas= $idventa;
        $CtaCobrar->monto_cuota= $cuota['monto'];
        $CtaCobrar->monto_cobrado= $cuota['tipo']=="Entrega" ? $cuota['monto'] : 0;
        $CtaCobrar->monto_saldo = $cuota['tipo']== "Entrega" ? 0 : $cuota['monto'];
        $CtaCobrar->fecha_venc= $this->formatFecha($cuota['vencimiento']);
        $CtaCobrar->estado= $cuota['tipo']=="Entrega" ? '0' : '1';
        $CtaCobrar->interes = $cuota['interes'] ;
        $CtaCobrar->save();
        
    }
    private function storeCobro($ventaCabecera,$idventa,$cuota){
        $ultimo= Cobro::orderBy('cc_numero','DESC')->first();
        $recibo= $this->reciboUp([$ultimo->recibon1,$ultimo->recibon2,$ultimo->nro_recibo]);
        $cobro = new Cobro();
        $cobro->nro_operacion = $ventaCabecera['nro_operacion'];
        $cobro->suc_cod = $ventaCabecera['idSucursal'];
        $cobro->cob_fecha = $ventaCabecera['fecha'];
        $cobro->recibon1 = $recibo[0];
        $cobro->recibon2 = $recibo[1];
        $cobro->nro_recibo = $recibo[2];
        $cobro->cob_importe= $cuota['monto'];
        $cobro->estado = "N";
        $cobro->save();

        DB::insert("INSERT INTO cobranza_detalle (cc_numero, nro_fact_ventas, nro_cuotas, importe, cobrado, tipo) VALUES (?,?,?,?,?,?)",[$cobro->cc_numero,$idventa,$cuota['nro'],$cuota['monto'],$cuota['monto'],'1']);

    }
    private function reciboUp($numeros){
        
        $n1 = $numeros[0];
        $n2= str_pad($numeros[1],3,"0",STR_PAD_LEFT);
        $recibo = str_pad($numeros[2],7,"0",STR_PAD_LEFT);
        $nrofinal = ($n1.$n2.$recibo) + 1;
        $strfinal = strval($nrofinal);
        $l= strlen($nrofinal);
        if($l < 7){
            return ["001","001", str_pad($strfinal,7,"0",STR_PAD_LEFT)];
        }else{
            $recibo =substr($strfinal, -7,7);
            if (($l-7)>3){
                $n2= substr($strfinal,-10,3);
                $n1= str_pad(substr($strfinal,0,$l-10),3,"0",STR_PAD_LEFT);
            }else{
                $n2= str_pad(substr($strfinal,0,$l-7),3,"0",STR_PAD_LEFT);
                $n1="000";
            }

        }
        return [$n1,$n2,$recibo];
    }
    private function formatFecha($fecha){
        $array_fecha= explode("-",$fecha);
        return $array_fecha[2]."-".$array_fecha[1]."-".$array_fecha[0];
    }
    private function storeMovimiento($idSucursal,$ope, $datos ){
        $movimiento= new MovimientoCaja();
        $movimiento->nro_operacion= $ope;
        $movimiento->mov_fecha= date('Y-m-d H:i');
        $movimiento->mov_concepto= 'Venta Nº: '.$datos[0];
        $movimiento->mov_tipo= 'Entrada';
        $movimiento->mov_monto= $datos[1];
        $movimiento->nro_fact_ventas= $datos[0];
        $movimiento->suc_cod= $idSucursal;
        $movimiento->save();
        
    }
    public function pdfboleta($id){
       
        $venta =  Venta::join('clientes','clientes.CLIENTES_cod','=','ventas.CLIENTES_cod')->where('ventas.nro_fact_ventas',$id)->first();
        $detalle= $this->getDetalle($id);
        $empresa= Empresa::first();
        //$pdf= PDF::loadView('pdf.venta',compact('venta','detalle','empresa'));
        // return $pdf->stream();
        return view('pdf.venta',compact('venta','detalle','empresa'));
    }
   public function ticketfactura(){
       $empresa= Empresa::first();
       return view('ticket.factura',compact('empresa'));
   }
   public function ticket($id){
    $venta= $this->getCabecera($id);
    $empresa= Empresa::first();
    //return $venta;
    return view('ticket.venta',compact('venta','empresa'));
   }
   public function imprimir(){
       return view('venta.imprimir');
   }
   
}
