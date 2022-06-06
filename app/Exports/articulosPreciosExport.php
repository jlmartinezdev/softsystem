<?php
namespace App\Exports;
use App\Articulo;
use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;


class articulosPreciosExport implements FromView
{
    use Exportable;

    public function filtro($request)
    {
        $this->request = $request;
        
        return $this;
    }
  
    public function stock(){
        $criterios = ["producto_nombre","producto_c_barra"];
        $columnas= ["articulos.producto_nombre","articulos.articulos_cod", "articulos.pre_venta1"];
        $columna=$columnas[$this->request->col];
        $criterio=$criterios[$this->request->criterio];
        $seccion= $this->request->seccion;
        $buscar = $this->request->buscar;
        $idsucursal= isset($this->request->suc) ? $this->request->suc : null;
        
            $articulos = Articulo::query()->join('stock', 'articulos.articulos_cod', '=', 'stock.articulos_cod')
            ->join('presentacion','articulos.present_cod','=','presentacion.present_cod')
            ->select('articulos.ARTICULOS_cod', 'articulos.producto_c_barra',DB::raw('TRIM(articulos.producto_nombre) AS producto_nombre'),DB::raw('TRIM(presentacion.present_descripcion) AS present_descripcion'),'articulos.pre_venta1',DB::raw('SUM(stock.cantidad) AS cantidad'))
            ->descripcion($buscar)
            ->seccion($seccion)
            ->bysucursal($idsucursal)
            ->orderBy($columna, $this->request->ord)
            ->groupBy('articulos.articulos_cod')
            ->get();
                
        return $articulos;
    }
    
    public function precios()
    {
        $criterios = ["producto_nombre","producto_c_barra"];
        $columnas= ["articulos.producto_nombre","articulos.articulos_cod", "articulos.pre_venta1"];
        $columna=$columnas[$this->request->col];
        $criterio=$criterios[$this->request->criterio];
        $seccion= $this->request->seccion;
        $buscar = $this->request->buscar;
        $idsucursal= isset($this->request->suc) ? $this->request->suc : null;
        
            $articulos = Articulo::query()->join('precios','articulos.ARTICULOS_cod','=', 'precios.ARTICULOS_cod')
            ->join('presentacion','articulos.present_cod','=','presentacion.present_cod')
            ->select('articulos.ARTICULOS_cod','precios.*')
            ->descripcion($buscar)
            ->seccion($seccion)
            ->bysucursal($idsucursal)
            ->orderBy($columna, $this->request->ord)
            ->get();
                
        return $articulos;
    }
    public function view(): View
    {
        return view('export.preciocredito', [
            'articulos' => $this->stock(),
            'precios' => $this->precios()
        ]);
    }
}
