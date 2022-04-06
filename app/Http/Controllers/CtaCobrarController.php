<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CtaCobrar;
use App\Cobro;
use App\MovimientoCaja;
use App\Empresa;
use App\Sucursal;

use DB;
use PDF;
class CtaCobrarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        return view('cobros');
    }
    public function indexanular(){
        return view('anularcobro');
    }
    public function indexInf(){
        $empresa = Empresa::first();
        return view('informes.ctacobrar',compact('empresa'));
    }
    public function indexCobrado(){
        $sucursales= Sucursal::all();
        return view('informes.cobro',compact('sucursales'));
    }
    public function infToPdf(Request $request){
        if(!empty($request->buscar)){
            $ci= is_numeric($request->buscar);
            $ctas= $this->getCtas($request,$ci);
            $articulos= $this->getArticuloFromCta($request,$ci);
            $pdf= PDF::loadView('pdf.ctacobrar',compact('ctas','articulos'));
            return $pdf->stream();
            return view('pdf.ctacobrar',compact('ctas','articulos'));
        }else{
           return back(); 
        }
        
       // return $request;
    }

    public function getCtaCobrar(Request $request){
     return [
        'ctas'=>$this->getCtas($request,$request->ci),
        'articulos' => $this->getArticuloFromCta($request,$request->ci)
    ];
        
    }
    public function getCtaCobrarFecha(Request $request){
        return [
            'ctas' =>$this->getCtasFecha($request),
            'articulos' => $this->getArticuloFromCtaFecha($request)
        ];
    }

    public function getCobroById($id){
        $cobro=Cobro::join('cobranza_detalle as dc','cobranzas.cc_numero','dc.cc_numero')
                ->join('ventas as v','dc.nro_fact_ventas','v.nro_fact_ventas')
                ->join('clientes as c','v.clientes_cod','c.clientes_cod')
                ->select('cobranzas.*','c.cliente_ci','c.cliente_nombre','c.cliente_direccion')
                ->where('cobranzas.cc_numero',$id)
                ->get();
        $detalle= $this->getDetalleCobro($id);
        return ["cobro" => $cobro,"detalle" =>$detalle];
    }
    public function getCobroFecha(Request $request){
        $cobro = Cobro::whereBetween('cobranzas.cob_fecha',[$request->alld,$request->allh])->get();
        return $cobro;
    }
    public function getDetalleCobro($id){
        $detalle = Cobro::join('cobranza_detalle as cd','cobranzas.cc_numero','cd.cc_numero')
                    ->where('cobranzas.cc_numero',$id)->get();
        return $detalle;
    }

    private function getCtas($request, $ci){
        return $ctas= CtaCobrar::join('ventas','ctas_cobrar.nro_fact_ventas','ventas.nro_fact_ventas')
        ->join('clientes as c','ventas.clientes_cod','c.CLIENTES_cod')
        ->select('ctas_cobrar.nro_fact_ventas',DB::raw('COUNT(ctas_cobrar.nro_cuotas) as "cuotas"'),DB::raw('SUM(ctas_cobrar.monto_cobrado) as "cobrado"'),DB::raw('SUM(ctas_cobrar.monto_cuota) as "total"'),DB::raw('SUM(ctas_cobrar.monto_saldo) as "saldo"'),DB::raw('COUNT(IF(ctas_cobrar.estado=1,1,NULL)) AS "nopagada"'), DB::raw('COUNT(IF(ctas_cobrar.estado=0,1,NULL)) AS "pagada"'),DB::raw('DATE_FORMAT(ventas.venta_fecha,"%d/%m/%Y") as venta_fecha'),DB::raw('DATE_FORMAT(ctas_cobrar.fecha_venc,"%Y-%m-%d") as fecha_v'),'ventas.venta_descuento','c.cliente_ruc','c.cliente_nombre','c.cliente_direccion', 'c.cliente_cel')
        ->cliente($request->buscar,$request->buscarpor,$ci)
        ->direccion($request->buscar,$request->buscarpor)
        ->groupBy('ctas_cobrar.nro_fact_ventas')
        ->having('saldo','>',0)
        ->ordenar($request->ordenarpor,$request->ord,$request->buscar)
        ->get();
    }
    private function getCtasFecha($request){
        return $ctas= CtaCobrar::join('ventas','ctas_cobrar.nro_fact_ventas','ventas.nro_fact_ventas')
        ->join('clientes as c','ventas.clientes_cod','c.CLIENTES_cod')
        ->select('ctas_cobrar.nro_fact_ventas',DB::raw('COUNT(ctas_cobrar.nro_cuotas) as "cuotas"'),DB::raw('SUM(ctas_cobrar.monto_cobrado) as "cobrado"'),DB::raw('SUM(ctas_cobrar.monto_cuota) as "total"'),DB::raw('SUM(ctas_cobrar.monto_saldo) as "saldo"'),DB::raw('COUNT(IF(ctas_cobrar.estado=1,1,NULL)) AS "nopagada"'), DB::raw('COUNT(IF(ctas_cobrar.estado=0,1,NULL)) AS "pagada"'),DB::raw('DATE_FORMAT(ventas.venta_fecha,"%d/%m/%Y") as venta_fecha'),DB::raw('DATE_FORMAT(ctas_cobrar.fecha_venc,"%Y-%m-%d") as fecha_v'),'ventas.venta_descuento','c.cliente_ruc','c.cliente_nombre','c.cliente_direccion', 'c.cliente_cel')
        ->whereBetween('ctas_cobrar.fecha_venc',[$request->desde,$request->hasta])
        ->groupBy('ctas_cobrar.nro_fact_ventas')
        ->having('saldo','>',0)
        ->ordenar($request->ordenarpor,$request->ord,$request->buscar)
        ->get();
    }
    private function getArticuloFromCtaFecha($request){
        return CtaCobrar::join('ventas as v','ctas_cobrar.nro_fact_ventas','v.nro_fact_ventas')
        ->join('detalle_venta as dv','v.nro_fact_ventas','dv.nro_fact_ventas')
        ->join('articulos as a','dv.articulos_cod','a.articulos_cod')
        ->join('clientes as c','v.clientes_cod','c.clientes_cod')
        ->select('ctas_cobrar.nro_fact_ventas','a.producto_c_barra','a.producto_nombre','dv.venta_cantidad','dv.venta_precio')
        ->whereBetween('ctas_cobrar.fecha_venc',[$request->desde,$request->hasta])
        ->groupBy('a.articulos_cod','v.nro_fact_ventas')
        ->orderBy('v.nro_fact_ventas','ASC')
        ->get();
    }
    private function getArticuloFromCta($filtro,$ci){
        return CtaCobrar::join('ventas as v','ctas_cobrar.nro_fact_ventas','v.nro_fact_ventas')
        ->join('detalle_venta as dv','v.nro_fact_ventas','dv.nro_fact_ventas')
        ->join('articulos as a','dv.articulos_cod','a.articulos_cod')
        ->join('clientes as c','v.clientes_cod','c.clientes_cod')
        ->select('ctas_cobrar.nro_fact_ventas','a.producto_c_barra','a.producto_nombre','dv.venta_cantidad','dv.venta_precio')
        ->cliente($filtro->buscar,$filtro->buscarpor,$ci)
        ->direccion($filtro->buscar,$filtro->buscarpor)
        ->groupBy('a.articulos_cod','v.nro_fact_ventas')
        ->orderBy('v.nro_fact_ventas','ASC')
        ->get();
    }
    public function getCuotas($id){
        return CtaCobrar::where('nro_fact_ventas',$id)->get();
    }
    public function store(Request $request){
        $ultimo= Cobro::orderBy('cc_numero','DESC')->first();
        $recibo= $this->reciboUp([$ultimo->recibon1,$ultimo->recibon2,$ultimo->nro_recibo]);
        $cobro = new Cobro();
        $cobro->nro_operacion = $request->cobro['nro_operacion'];
        $cobro->suc_cod = $request->cobro['idSucursal'];
        $cobro->cob_fecha = $request->cobro['fecha'];
        $cobro->recibon1 = $recibo[0];
        $cobro->recibon2 = $recibo[1];
        $cobro->nro_recibo = $recibo[2];
        $cobro->cob_importe= $request->cobro['total'];
        $cobro->estado = "N";
        $cobro->save();

        foreach ($request->cuotas as $cuota) {
            $detalle = DB::insert("INSERT INTO cobranza_detalle (cc_numero, nro_fact_ventas, nro_cuotas, importe, cobrado, tipo) VALUES (?,?,?,?,?,?)",[$cobro->cc_numero,$cuota['nro_fact_ventas'],$cuota['nro_cuotas'],$cuota['monto_cuota'],$cuota['monto_cuota'],'1']);

            Ctacobrar::where('nro_cuotas',$cuota['nro_cuotas'])
            ->where('nro_fact_ventas',$cuota['nro_fact_ventas'])
            ->update([
                'monto_cobrado' => $cuota['acobrar'], 
                'monto_saldo'=> $cuota['monto_saldo'] - $cuota['acobrar'],
                'estado' => $cuota['acobrar']== $cuota['acobrar'] ? '0' : '1', 
                'interes'=> $cuota['interes']
            ]);
        }
        $this->storeMovimiento($cobro);
        return $cobro->cc_numero;
        
    }
    private function storeMovimiento( $cobro ){
        $movimiento= new MovimientoCaja();
        $movimiento->nro_operacion= $cobro->nro_operacion;
        $movimiento->mov_fecha= date('Y-m-d H:i');
        $movimiento->mov_concepto= 'Cobro Nº: '.$cobro->cc_numero;
        $movimiento->mov_tipo= 'Entrada';
        $movimiento->mov_monto= $cobro->cob_importe;
        $movimiento->nro_fact_ventas= '-';
        $movimiento->suc_cod= $cobro->suc_cod;
        $movimiento->save();
        
    }
    public function destroy(Request $request){
        
        DB::table('cobranza_detalle')->where('cc_numero',$request->id)->delete();
        Cobro::find($request->id)->delete();
        foreach ($request->cuotas as $cuota) {

            Ctacobrar::where('nro_cuotas',$cuota['nro_cuotas'])
            ->where('nro_fact_ventas',$cuota['nro_fact_ventas'])
            ->update([
                'monto_cobrado' => $cuota['importe']-$cuota['cobrado'], 
                'monto_saldo'=> intval('monto_saldo') + $cuota['cobrado'],
                'estado' => '1'
            ]);
        }
        $movimiento= new MovimientoCaja();
        $movimiento->nro_operacion= $request->nrooperacion;
        $movimiento->mov_fecha= date('Y-m-d H:i');
        $movimiento->mov_concepto= 'Anular Cobro Nº: '.$request->id;
        $movimiento->mov_tipo= 'Salida';
        $movimiento->mov_monto= $request->monto;
        $movimiento->nro_fact_ventas= '-';
        $movimiento->suc_cod= $request->idSucursal;
        $movimiento->save();
        return "OK";
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
    public function printRecibo($id){
        $empresa= Empresa::first();
        $cobro= Cobro::join('cobranza_detalle as cd','cobranzas.cc_numero','cd.cc_numero')
                ->join('ctas_cobrar as cc','cd.nro_fact_ventas','cc.nro_fact_ventas')
                ->join('ventas as v','cd.nro_fact_ventas','v.nro_fact_ventas')
                ->join('clientes as c','v.clientes_cod','c.clientes_cod')
                ->select('cobranzas.*','c.cliente_nombre','c.cliente_ruc',DB::raw('COUNT(cd.cc_numero) AS cantidad'))
                ->where('cobranzas.cc_numero',$id)
                ->first();
        $cuotas = Cobro::join('cobranza_detalle as cd','cobranzas.cc_numero','cd.cc_numero')
                ->join('ctas_cobrar as cc', function($join)
                {
                    $join->on('cd.nro_cuotas','=','cc.nro_cuotas');
                    $join->on('cd.nro_fact_ventas','=', 'cc.nro_fact_ventas');
                })
                ->select('cd.nro_cuotas','cd.cobrado','cc.interes')
                ->where('cobranzas.cc_numero',$id)
                ->get();
        $articulos = Cobro::join('cobranza_detalle as cd','cobranzas.cc_numero','cd.cc_numero')
                    ->join('ventas as v','cd.nro_fact_ventas','v.nro_fact_ventas')
                    ->join('detalle_venta as dv','v.nro_fact_ventas','dv.nro_fact_ventas')
                    ->join('articulos as a','dv.articulos_cod','a.articulos_cod')
                    ->select('a.producto_nombre','v.nro_fact_ventas','v.venta_fecha')
                    ->where('cobranzas.cc_numero',$id)
                    ->get();
        return view('documento.recibocobro',compact('empresa','cobro','cuotas','articulos'));
    }
}
