<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\reffactura;
use App\Facturar;
use App\Venta;
use App\Empresa;
use DB;

class FacturarController extends Controller
{
    public function index($id){
        $ultimo= reffactura::first();
        $venta= $this->getDetalle($id);
        $factura= $this->getNrofactura($id);
        return view('venta.facturar',compact('id','ultimo','venta','factura'));
    }
    private function getDetalle($nro_venta){
        return DB::select('SELECT v.*,c.cliente_nombre,c.cliente_direccion,c.cliente_ruc,dv.*,a.producto_nombre,a.producto_c_barra FROM ventas v INNER JOIN detalle_venta dv ON v.nro_fact_ventas= dv.nro_fact_ventas INNER JOIN articulos a ON dv.ARTICULOS_cod=a.ARTICULOS_cod INNER JOIN clientes c ON v.clientes_cod= c.clientes_cod where v.nro_fact_ventas=?',[$nro_venta]);
    }
    private function getIva($nro_venta){
        return DB::select('SELECT SUM(IF(p.iva=5, dv.venta_precio* dv.venta_cantidad,0)) AS iva5, SUM(IF(p.iva=10, dv.venta_precio* dv.venta_cantidad,0)) AS iva10 FROM detalle_venta dv INNER JOIN articulos a ON dv.ARTICULOS_cod= a.ARTICULOS_cod INNER JOIN presentacion p ON a.present_cod= p.present_cod WHERE dv.nro_fact_ventas=?',[$nro_venta]);
    }
    private function getNrofactura($nro_venta){
       return  Facturar::where('nro_fact_ventas',$nro_venta)->get();
    }
    public function store(Request $request){
        $nrofact= $this->facturaUp($request->nivel1,$request->nivel2,$request->factura);
        $facturar= new Facturar();
        $facturar->nro_factura= $nrofact['n3'];
        $facturar->nro_fact_ventas=  $request->nro_venta;
        $facturar->nivel1= $this->rellenar($nrofact['n1'],3);
        $facturar->nivel2=$this->rellenar($nrofact['n2'],3);
        $facturar->fecha_venc= $request->vencimiento;
        $facturar->timbrado= $request->timbrado;
        $facturar->fecha_factura= date('Y-m-d');
        $facturar->save();
        $this->updateRef($nrofact);
        $this->updateStatusVen($request->nro_venta,'1');
       return ['mensaje'=> 'OK'];
    }
    private function facturaUp($pn1,$pn2,$nro){
        $n1 = $pn1 * 1;
        $n2 = $pn2 * 1;
        $n3 = $nro+1;
        if($n3>9999999){
            $n3= 1;
            if($n2<999){
                $n2++;
            }else{
                $n2= 1;
                $n1++;
            }
        }
        return ['n1'=>$n1,'n2'=>$n2,'n3'=>$n3];
    }
    private function facturaDown($pn1,$pn2,$nro){
        $n1 = $pn1 * 1;
        $n2 = $pn2 * 1;
        $n3 = $nro-1;
        if($n3==0){
            $n3= 9999999;
            $n2--;
            if($n2==0){
               $n2=999;
            }else{
                $n1--;
            }
        }
        $this->updateRef(['n1'=>$n1,'n2'=>$n2,'n3'=>$n3]);
    }
    private function updateRef($nrofact)
    {
        reffactura::first()->update(['nivel1'=>str_pad( $nrofact['n1'], 3, "0", STR_PAD_LEFT),
        'nivel2' =>  str_pad( $nrofact['n2'], 3, "0", STR_PAD_LEFT),
        'nrofactura'=> str_pad( $nrofact['n3'], 7, "0", STR_PAD_LEFT)]);
        return 'OK';
    }
    private function updateStatusVen($nro_venta,$status){
        Venta::where('nro_fact_ventas',$nro_venta)->update(['factura_status'=>$status]);
    }
    private function rellenar($str, $len){
        return str_pad( $str, $len, "0", STR_PAD_LEFT);
    }
    public function destroy($id){

        $ultimo= Facturar::where('nro_fact_ventas',$id)->get();
        Facturar::where('nro_fact_ventas',$id)->delete();
        $this->updateStatusVen($id,'0');
        $this->facturaDown($ultimo[0]->nivel1,$ultimo[0]->nivel2,$ultimo[0]->nro_factura);
        
    }
    public function ticket($id){
        $venta= $this->getDetalle($id);
        $factura= $this->getNrofactura($id);
        $iva= $this->getIva($id);
        $empresa= Empresa::first();
       return view('ticket.factura',compact('empresa','venta','factura','iva')); 
    }
}
