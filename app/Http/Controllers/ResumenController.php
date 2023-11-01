<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Articulo;
use App\Stock;
use App\Compra;
use App\Venta;
use App\Sucursal;
use DB;

class ResumenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {   
        $sucursales= Sucursal::All();
        return view('informes.resumen',compact('sucursales'));
    }
    public function resumen(Request $request)
    {
        $sucursal = $request->idsucursal;
        $fecha_inicio = $request->desde;
        $fecha_fin = $request->hasta;
        $ventas = Venta::select('ventas.venta_total','ventas.venta_descuento','ventas.tipo_factura')->wheresuc($sucursal)->whereBetween(DB::raw("date(ventas.venta_fecha)"),[$fecha_inicio,$fecha_fin])->get();
        $d_ventas= ['contado'=>0,'credito'=>0,'total'=>0,'descuento'=>0,'ganancia'=>0];
        foreach($ventas as $venta){

            if($venta->tipo_factura == 1){
                $d_ventas['contado'] = $d_ventas['contado'] + $venta->venta_total;
            }else{
                $d_ventas['credito'] = $d_ventas['credito'] + $venta->venta_total;
            }
            $d_ventas['total'] = $d_ventas['total'] + $venta->venta_total;
            $d_ventas['descuento'] =$d_ventas['descuento'] + $venta->venta_descuento;
        }
        $d_ventas['cantidad']= count($ventas);
        
        //Ganancia
        
        $ganancia= DB::select("SELECT SUM(dv.venta_cantidad * (dv.venta_precio - dv.precio_compra) ) AS ganancia FROM ventas v INNER JOIN detalle_venta dv ON v.nro_fact_ventas=dv.nro_fact_ventas WHERE DATE(v.venta_fecha) BETWEEN ? and ?",[$fecha_inicio,$fecha_fin]);
        if(!empty($ganancia)){
            $d_ventas['ganancia']= (float)$ganancia[0]->ganancia;
        }
        //Compras
        $compras= Compra::select('compra.compra_cod','compra.compra_tipo_factura',DB::raw('SUM(dc.compra_cantidad * dc.compra_precio) total'),'compra.compra_descuento')
        ->join('detalle_compra as dc','compra.compra_cod','=','dc.compra_cod')
        ->wheresuc($sucursal)
        ->whereBetween('compra_fecha',[$fecha_inicio,$fecha_fin])
        ->groupBy('compra.compra_cod')
        ->get();
        $d_compras= ['contado'=>0,'credito'=>0,'total'=>0, 'descuento'=>0];
        foreach($compras as $compra){
            if($compra->compra_tipo_factura == 1){
                $d_compras['contado'] = $d_compras['contado'] + $compra->total;
            }else{
                $d_compras['credito'] = $d_compras['credito'] + $compra->total;
            }
            $d_compras['total'] = $d_compras['total'] + $compra->total;
            $d_compras['descuento'] = $d_compras['descuento'] + $compra->compra_descuento;
        }
        $d_compras['cantidad']= count($compras);

        //Articulos
        $d_articulos= ['cantidad'=>0,'stock'=>0,'costo'=>0,'venta'=>0,'sin_stock'=>0];
        $articulos= Articulo::select('articulos.ARTICULOS_cod','articulos.producto_costo_compra','articulos.pre_venta1',DB::raw('SUM(stock.cantidad) stock'))
        ->join('stock','articulos.ARTICULOS_cod','=','stock.ARTICULOS_cod')
        ->groupBy('articulos.ARTICULOS_cod')
        ->get();

        foreach($articulos as $articulo){
            $d_articulos['cantidad'] = $d_articulos['cantidad'] + 1;
            $d_articulos['stock'] = $d_articulos['stock'] + $articulo->stock;
            $d_articulos['costo'] = $d_articulos['costo'] + ($articulo->producto_costo_compra * $articulo->stock);
            $d_articulos['venta'] = $d_articulos['venta'] + ($articulo->pre_venta1 * $articulo->stock);
            if($articulo->stock == 0){
                $d_articulos['sin_stock'] = $d_articulos['sin_stock'] + 1;
            }
        }
        $d_articulos['costo'] = round($d_articulos['costo'],2);
        $d_articulos['venta'] = round($d_articulos['venta'],2);

        return ['venta' => $d_ventas, 'compra' => $d_compras, 'articulo' => $d_articulos];
    }
}
