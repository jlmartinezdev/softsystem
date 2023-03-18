<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CtaCobrar;
use App\Cobro;
use App\Venta;
use App\MovimientoCaja;
use App\Empresa;
use App\Sucursal;

use DB;
use PDF;
use Auth;

use App\Exports\CtasCobrarExport;
use Maatwebsite\Excel\Facades\Excel;

class CtaCobrarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('cobros');
    }
    public function indexanular()
    {
        return view('anularcobro');
    }
    public function indexInf()
    {
        $empresa = Empresa::first();
        return view('informes.ctacobrar', compact('empresa'));
    }
    public function indexCobrado()
    {
        $sucursales= Sucursal::all();
        return view('informes.cobro', compact('sucursales'));
    }
    public function infToPdf(Request $request)
    {
        if (!empty($request->buscar)) {
            $ci= is_numeric($request->buscar);
            $ctas= $this->getCtas($request, $ci);
            $articulos= $this->getArticuloFromCta($request, $ci);
            $pdf= PDF::loadView('pdf.ctacobrar', compact('ctas', 'articulos'));
            return $pdf->stream();
            return view('pdf.ctacobrar', compact('ctas', 'articulos'));
        } else {
            return back();
        }
        
        // return $request;
    }

    public function getCtaCobrar(Request $request)
    {
        if($request->from=="cobro"){
            return [
                'ctas'=>$this->getCtas($request),
                'articulos' => $this->getArticuloFromCta($request),
                'cuotas' => $this->getCuotasById($request->buscar)
            ]; 
        }else{
            return [
                'ctas'=>$this->getCtas($request),
                'articulos' => $this->getArticuloFromCta($request)
            ]; 
        }
        
    }


    public function getCobroById($id)
    {
        $cobro=Cobro::join('cobranza_detalle as dc', 'cobranzas.cc_numero', 'dc.cc_numero')
                ->join('ventas as v', 'dc.nro_fact_ventas', 'v.nro_fact_ventas')
                ->join('clientes as c', 'v.clientes_cod', 'c.clientes_cod')
                ->select('cobranzas.*', 'c.cliente_ci', 'c.cliente_nombre', 'c.cliente_direccion')
                ->where('cobranzas.cc_numero', $id)
                ->get();
        $detalle= $this->getDetalleCobro($id);
        return ["cobro" => $cobro,"detalle" =>$detalle];
    }
    public function getCobroFecha(Request $request)
    {
        $cobro = Cobro::join('cobranza_detalle as dc', 'cobranzas.cc_numero', 'dc.cc_numero')
        ->join('ventas as v', 'dc.nro_fact_ventas', 'v.nro_fact_ventas')
        ->join('clientes as c', 'v.clientes_cod', 'c.clientes_cod')
        ->select('cobranzas.*','c.cliente_nombre','dc.nro_fact_ventas')
        ->clientes($request)
        ->orderBy('cobranzas.cc_numero','DESC')
        ->groupBy('cobranzas.cc_numero')
        ->get();
        return $cobro;
    }
     
    public function getDetalleCobro($id)
    {
        $detalle = Cobro::join('cobranza_detalle as cd', 'cobranzas.cc_numero', 'cd.cc_numero')
                    ->where('cobranzas.cc_numero', $id)->get();
        return $detalle;
    }

    private function getCtas($request)
    {
        if ($request->tipo=='fecha') {
            return $ctas= CtaCobrar::join('ventas', 'ctas_cobrar.nro_fact_ventas', 'ventas.nro_fact_ventas')
            ->join('clientes as c', 'ventas.clientes_cod', 'c.CLIENTES_cod')
            ->select('ctas_cobrar.nro_fact_ventas','ventas.venta_total', DB::raw('ctas_cobrar.nro_cuotas as "cuotas"'), DB::raw('ctas_cobrar.monto_cobrado as "cobrado"'), DB::raw('ctas_cobrar.monto_cuota as "total"'), DB::raw('ctas_cobrar.monto_saldo as "saldo"'), DB::raw('DATE_FORMAT(ventas.venta_fecha,"%d/%m/%Y") as venta_fecha'), DB::raw('DATE_FORMAT(ctas_cobrar.fecha_venc,"%Y-%m-%d") as fecha_v'), 'ventas.venta_descuento', 'c.cliente_ruc', 'c.cliente_nombre', 'c.cliente_direccion', 'c.cliente_cel')
            ->whereBetween('ctas_cobrar.fecha_venc', [$request->desde,$request->hasta])
            ->having('saldo', '>', 0)
            ->ordenar($request->ordenarpor, $request->ord, $request->buscar)
            ->get();
        } elseif ($request->tipo=='cliente') {
            return $ctas= CtaCobrar::join('ventas', 'ctas_cobrar.nro_fact_ventas', 'ventas.nro_fact_ventas')
            ->join('clientes as c', 'ventas.clientes_cod', 'c.CLIENTES_cod')
            ->select('ctas_cobrar.nro_fact_ventas', DB::raw('COUNT(ctas_cobrar.nro_cuotas) as "cuotas"'), DB::raw('SUM(ctas_cobrar.monto_cobrado) as "cobrado"'), DB::raw('SUM(ctas_cobrar.monto_cuota) as "total"'), DB::raw('SUM(ctas_cobrar.monto_saldo) as "saldo"'), DB::raw('COUNT(IF(ctas_cobrar.estado=1,1,NULL)) AS "nopagada"'), DB::raw('COUNT(IF(ctas_cobrar.estado=0,1,NULL)) AS "pagada"'), DB::raw('DATE_FORMAT(ventas.venta_fecha,"%d/%m/%Y") as venta_fecha'), DB::raw('DATE_FORMAT(ctas_cobrar.fecha_venc,"%Y-%m-%d") as fecha_v'), 'ventas.venta_descuento', 'c.cliente_ruc', 'c.cliente_nombre', 'c.cliente_direccion', 'c.cliente_cel')
            ->cliente($request->buscar, $request->buscarpor)
            ->groupBy('ctas_cobrar.nro_fact_ventas')
            ->having('saldo', '>', 0)
            ->ordenar($request->ordenarpor, $request->ord, $request->buscar)
            ->get();
        } else {
            return $ctas= CtaCobrar::join('ventas', 'ctas_cobrar.nro_fact_ventas', 'ventas.nro_fact_ventas')
            ->join('clientes as c', 'ventas.clientes_cod', 'c.CLIENTES_cod')
            ->select('ctas_cobrar.nro_fact_ventas', DB::raw('COUNT(ctas_cobrar.nro_cuotas) as "cuotas"'), DB::raw('SUM(ctas_cobrar.monto_cobrado) as "cobrado"'), DB::raw('SUM(ctas_cobrar.monto_cuota) as "total"'), DB::raw('SUM(ctas_cobrar.monto_saldo) as "saldo"'), DB::raw('COUNT(IF(ctas_cobrar.estado=1,1,NULL)) AS "nopagada"'), DB::raw('COUNT(IF(ctas_cobrar.estado=0,1,NULL)) AS "pagada"'), DB::raw('DATE_FORMAT(ventas.venta_fecha,"%d/%m/%Y") as venta_fecha'), DB::raw('DATE_FORMAT(ctas_cobrar.fecha_venc,"%Y-%m-%d") as fecha_v'), 'ventas.venta_descuento', 'c.cliente_ruc', 'c.cliente_nombre', 'c.cliente_direccion', 'c.cliente_cel')
            ->direccion($request->buscar)
            ->groupBy('ctas_cobrar.nro_fact_ventas')
            ->having('saldo', '>', 0)
            ->ordenar($request->ordenarpor, $request->ord, $request->buscar)
            ->get();
        }

    }
 

    private function getArticuloFromCta($filtro)
    {
         /*  tipo : tipo,
         buscar: this.txtbuscar,
         desde: this.filtro.desde,
         hasta: this.filtro.hasta,
         buscarpor: this.filtro.busquedapor,
         ordenarpor: this.filtro.ordenarpor,
         ord: this.filtro.orden */
        if($filtro->tipo=='fecha'){
            return CtaCobrar::join('ventas as v', 'ctas_cobrar.nro_fact_ventas', 'v.nro_fact_ventas')
            ->join('detalle_venta as dv', 'v.nro_fact_ventas', 'dv.nro_fact_ventas')
            ->join('articulos as a', 'dv.articulos_cod', 'a.articulos_cod')
            ->join('clientes as c', 'v.clientes_cod', 'c.clientes_cod')
            ->select('ctas_cobrar.nro_fact_ventas', 'a.producto_c_barra', 'a.producto_nombre', 'dv.venta_cantidad', 'dv.venta_precio')
            ->whereBetween('ctas_cobrar.fecha_venc', [$filtro->desde,$filtro->hasta])
            ->groupBy('a.articulos_cod', 'v.nro_fact_ventas')
            ->orderBy('v.nro_fact_ventas', 'ASC')
            ->get();
        }else if($filtro->tipo =='cliente'){
            return CtaCobrar::join('ventas as v', 'ctas_cobrar.nro_fact_ventas', 'v.nro_fact_ventas')
            ->join('detalle_venta as dv', 'v.nro_fact_ventas', 'dv.nro_fact_ventas')
            ->join('articulos as a', 'dv.articulos_cod', 'a.articulos_cod')
            ->join('clientes as c', 'v.clientes_cod', 'c.clientes_cod')
            ->select('ctas_cobrar.nro_fact_ventas', 'a.producto_c_barra', 'a.producto_nombre', 'dv.venta_cantidad', 'dv.venta_precio')
            ->cliente($filtro->buscar, $filtro->buscarpor)
            ->groupBy('a.articulos_cod', 'v.nro_fact_ventas')
            ->orderBy('v.nro_fact_ventas', 'ASC')
            ->get();
        }else{
            return CtaCobrar::join('ventas as v', 'ctas_cobrar.nro_fact_ventas', 'v.nro_fact_ventas')
            ->join('detalle_venta as dv', 'v.nro_fact_ventas', 'dv.nro_fact_ventas')
            ->join('articulos as a', 'dv.articulos_cod', 'a.articulos_cod')
            ->join('clientes as c', 'v.clientes_cod', 'c.clientes_cod')
            ->select('ctas_cobrar.nro_fact_ventas', 'a.producto_c_barra', 'a.producto_nombre', 'dv.venta_cantidad', 'dv.venta_precio')
            ->direccion($filtro->buscar)
            ->groupBy('a.articulos_cod', 'v.nro_fact_ventas')
            ->orderBy('v.nro_fact_ventas', 'ASC')
            ->get();
        }

            
        }
    public function getCuotas($id)
    {
        return CtaCobrar::where('nro_fact_ventas', $id)->get();
    }
    private function getCuotasById($ci){
        return CtaCobrar::join('ventas as v','ctas_cobrar.nro_fact_ventas','v.nro_fact_ventas')
                ->join("clientes as c",'v.clientes_cod','c.clientes_cod')
                ->select("ctas_cobrar.*")
                ->where("c.cliente_ci",$ci)
                ->get();
    }
    public function store(Request $request)
    {
        if($request->onlyInteres){
            $ultimo= Cobro::orderBy('cc_numero', 'DESC')->first();
            $recibo= $this->reciboUp([$ultimo->recibon1,$ultimo->recibon2,$ultimo->nro_recibo]);
            $cobro = new Cobro();
            $cobro->nro_operacion = $request->cobro['nro_operacion'];
            $cobro->suc_cod = $request->cobro['idSucursal'];
            $cobro->cob_fecha = $request->cobro['fecha'];
            $cobro->recibon1 = $recibo[0];
            $cobro->recibon2 = $recibo[1];
            $cobro->nro_recibo = $recibo[2];
            $cobro->cob_importe= $request->cobro['totalInteres'];
            $cobro->estado = "N";
            $cobro->save();

            foreach ($request->cuotas as $cuota) {
                $detalle = DB::insert("INSERT INTO cobranza_detalle (cc_numero, nro_fact_ventas, nro_cuotas, importe, cobrado, tipo) VALUES (?,?,?,?,?,?)", [$cobro->cc_numero,$cuota['nro_fact_ventas'],$cuota['nro_cuotas'],$cuota['interes'],$cuota['interes'],'2']);

                Ctacobrar::where('nro_cuotas', $cuota['nro_cuotas'])
                ->where('nro_fact_ventas', $cuota['nro_fact_ventas'])
                ->update([
                    'interes' => $cuota['interes'],
                    'estado_interes' => '1'
                ]);
            }
            if(Auth::user()->cod_usuarios!= 1){
                $this->storeMovimiento($cobro);
            }
            return $cobro->cc_numero;
        }
        $ultimo= Cobro::orderBy('cc_numero', 'DESC')->first();
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
            $detalle = DB::insert("INSERT INTO cobranza_detalle (cc_numero, nro_fact_ventas, nro_cuotas, importe, cobrado, tipo) VALUES (?,?,?,?,?,?)", [$cobro->cc_numero,$cuota['nro_fact_ventas'],$cuota['nro_cuotas'],$cuota['monto_saldo'],$cuota['acobrar'],'1']);

            Ctacobrar::where('nro_cuotas', $cuota['nro_cuotas'])
            ->where('nro_fact_ventas', $cuota['nro_fact_ventas'])
            ->update([
                'monto_cobrado' => $cuota['monto_cobrado'] + $cuota['acobrar'],
                'monto_saldo'=> $cuota['monto_saldo'] - $cuota['acobrar'],
                'estado' => $cuota['monto_saldo']== $cuota['acobrar'] ? '0' : '1',
                'interes'=> $cuota['interes']
            ]);
        }
        if(Auth::user()->cod_usuarios!= 1){
            $this->storeMovimiento($cobro);
        }
        
        return $cobro->cc_numero;
    }
    private function storeMovimiento($cobro)
    {
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
    
    public function destroy(Request $request)
    {
        DB::table('cobranza_detalle')->where('cc_numero', $request->id)->delete();
        Cobro::where('cc_numero',$request->id)->delete();
        foreach ($request->cuotas as $cuota) {
            $cuenta = Ctacobrar::where('nro_cuotas', $cuota['nro_cuotas'])
            ->where('nro_fact_ventas', $cuota['nro_fact_ventas']);
            $cuentafila= $cuenta->first();

            $cuenta->decrement('monto_cobrado',$cuota['cobrado']);
            $newVal= intval($cuentafila->monto_saldo) + $cuota['cobrado'];
            $cuenta->update([
                'monto_saldo' => $newVal,
                'interes' => '0',
                'estado' => '1'
            ]);
           /*  ->update([
                'monto_cobrado' => intval('monto_cobrado') -$cuota['cobrado'],
                'monto_saldo'=> intval('monto_saldo') + $cuota['cobrado'],
                'estado' => '1'
            ]); */
        }
        if(Auth::user()->cod_usuarios!= 1){
            $movimiento= new MovimientoCaja();
            $movimiento->nro_operacion= $request->nrooperacion;
            $movimiento->mov_fecha= date('Y-m-d H:i');
            $movimiento->mov_concepto= 'Anular Cobro Nº: '.$request->id;
            $movimiento->mov_tipo= 'Salida';
            $movimiento->mov_monto= $request->monto;
            $movimiento->nro_fact_ventas= '-';
            $movimiento->suc_cod= $request->idSucursal;
            $movimiento->save();
            //Cobro::where('cc_numero',$request->id)->delete();
        }
        return "OK";

    }
    private function reciboUp($numeros)
    {
        $n1 = $numeros[0];
        $n2= str_pad($numeros[1], 3, "0", STR_PAD_LEFT);
        $recibo = str_pad($numeros[2], 7, "0", STR_PAD_LEFT);
        $nrofinal = ($n1.$n2.$recibo) + 1;
        $strfinal = strval($nrofinal);
        $l= strlen($nrofinal);
        if ($l < 7) {
            return ["001","001", str_pad($strfinal, 7, "0", STR_PAD_LEFT)];
        } else {
            $recibo =substr($strfinal, -7, 7);
            if (($l-7)>3) {
                $n2= substr($strfinal, -10, 3);
                $n1= str_pad(substr($strfinal, 0, $l-10), 3, "0", STR_PAD_LEFT);
            } else {
                $n2= str_pad(substr($strfinal, 0, $l-7), 3, "0", STR_PAD_LEFT);
                $n1="000";
            }
        }
        return [$n1,$n2,$recibo];
    }
    public function printRecibo($id)
    {
        $empresa= Empresa::first();
        $cobro= Cobro::join('cobranza_detalle as cd', 'cobranzas.cc_numero', 'cd.cc_numero')
                ->join('ctas_cobrar as cc', 'cd.nro_fact_ventas', 'cc.nro_fact_ventas')
                ->join('ventas as v', 'cd.nro_fact_ventas', 'v.nro_fact_ventas')
                ->join('clientes as c', 'v.clientes_cod', 'c.clientes_cod')
                ->select('cobranzas.*', 'c.cliente_nombre', 'c.cliente_ruc')
                ->where('cobranzas.cc_numero', $id)
                ->first();
        $cuotas = Cobro::join('cobranza_detalle as cd', 'cobranzas.cc_numero', 'cd.cc_numero')
                ->join('ctas_cobrar as cc', function ($join) {
                    $join->on('cd.nro_cuotas', '=', 'cc.nro_cuotas');
                    $join->on('cd.nro_fact_ventas', '=', 'cc.nro_fact_ventas');
                })
                ->select('cd.nro_cuotas', 'cd.cobrado', 'cc.interes','cc.nro_fact_ventas','cc.fecha_venc')
                ->where('cobranzas.cc_numero', $id)
                ->get();
        $nro_ventas= DB::table('cobranza_detalle as cd')
                ->join('ctas_cobrar as cc', 'cd.nro_fact_ventas', '=', 'cc.nro_fact_ventas')
                ->select('cc.nro_fact_ventas')
                ->where('cd.cc_numero', $id)
                ->groupBy('cc.nro_fact_ventas')
                ->get();
        $cantidad_cuotas =  array();    
        for ($i=0; $i < count($nro_ventas); $i++) { 
            $tmpFila= DB::table('ctas_cobrar as cc')
                ->select('cc.nro_fact_ventas',DB::raw('COUNT(cc.nro_fact_ventas) AS cantidad'))
                ->where('cc.nro_fact_ventas', $nro_ventas[$i]->nro_fact_ventas)
                ->first();
            array_push($cantidad_cuotas,array('nro_fact_ventas' => $tmpFila->nro_fact_ventas,'cantidad' => $tmpFila->cantidad ));
        }
        
        
        $articulos = Cobro::join('cobranza_detalle as cd', 'cobranzas.cc_numero', 'cd.cc_numero')
                    ->join('ventas as v', 'cd.nro_fact_ventas', 'v.nro_fact_ventas')
                    ->join('detalle_venta as dv', 'v.nro_fact_ventas', 'dv.nro_fact_ventas')
                    ->join('articulos as a', 'dv.articulos_cod', 'a.articulos_cod')
                    ->select('a.producto_nombre', 'v.nro_fact_ventas', 'v.venta_fecha')
                    ->where('cobranzas.cc_numero', $id)
                    ->groupBy('a.articulos_cod')
                    ->get();
                    
        return view('documento.recibocobro', compact('empresa', 'cobro', 'cuotas', 'articulos','cantidad_cuotas'));
    }
    
    public function printExtracto($id){
        $empresa= Empresa::first();
        $articulos = Venta::join('detalle_venta as dv', 'ventas.nro_fact_ventas', 'dv.nro_fact_ventas')
                    ->join('articulos as a', 'dv.articulos_cod', 'a.articulos_cod')
                    ->join('clientes as c','ventas.clientes_cod','c.clientes_cod')
                    ->select('a.articulos_cod','dv.venta_cantidad','dv.venta_precio','a.producto_nombre', 'ventas.nro_fact_ventas', 'ventas.venta_fecha','c.cliente_ruc','c.cliente_nombre')
                    ->where('ventas.nro_fact_ventas', $id)
                    ->get();
        $cuotas = Venta::join('ctas_cobrar as cc', 'ventas.nro_fact_ventas', 'cc.nro_fact_ventas')
        ->select('cc.nro_cuotas', 'cc.monto_saldo','cc.monto_cuota','cc.monto_cobrado', 'cc.interes','cc.nro_fact_ventas','cc.fecha_venc')
        ->where('ventas.nro_fact_ventas', $id)
        ->get();
        if(count($articulos)<1){
            return back();
        }
        return view('documento.extractocuenta',compact('empresa','articulos','cuotas'));
    }
    public function exportCtasAll(Request $request){
        //return Excel::download(new ArticulosExport, 'articulos.xlsx');
        //return (new InvoicesExport(2018))->download('invoices.xlsx');
        $date=date('d_m_Y');
        return (new CtasCobrarExport)->download('Ctas_a_Cobrar_'.$date.'.xlsx');
    }
}