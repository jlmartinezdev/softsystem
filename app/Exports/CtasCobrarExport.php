<?php
namespace App\Exports;

//use App\Ctacobrar;
use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
class CtasCobrarExport implements FromView
{
    use Exportable;
   /*  public function headings(): array
    {
        return [
            '#',
            'Cliente',
            'Cuota',
            'Monto',
            'Mora'
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 55, 
            'C' => 30,
            'D' => 14,
            'E' => 15           
        ];
    } */
    private function getDiaMora(){
        return \App\CtaCobrar::query()
        ->select('ctas_cobrar.nro_cuotas', DB::raw('DATE_FORMAT(ctas_cobrar.fecha_venc,"%Y-%m-%d") as fecha_v'),'ctas_cobrar.nro_fact_ventas')
        ->where('ctas_cobrar.estado', '1')
        ->groupBy('ctas_cobrar.nro_fact_ventas')
        ->orderBy('ctas_cobrar.fecha_venc','ASC')
        ->get();
    }
    public function view(): View
    {
        $ctas= \App\CtaCobrar::query()->join('ventas', 'ctas_cobrar.nro_fact_ventas', 'ventas.nro_fact_ventas')
            ->join('clientes as c', 'ventas.clientes_cod', 'c.CLIENTES_cod')
            ->select( 'ctas_cobrar.nro_fact_ventas',DB::raw('SUM(ctas_cobrar.monto_saldo) as "saldo"'),DB::raw('COUNT(ctas_cobrar.nro_cuotas) as "cuotas"'), DB::raw('SUM(ctas_cobrar.monto_cobrado) as "cobrado"'), DB::raw('COUNT(IF(ctas_cobrar.estado=1,1,NULL)) AS "nopagada"'), DB::raw('COUNT(IF(ctas_cobrar.estado=0,1,NULL)) AS "pagada"'), DB::raw('DATE_FORMAT(ventas.venta_fecha,"%d/%m/%Y") as venta_fecha'), DB::raw('DATE_FORMAT(ctas_cobrar.fecha_venc,"%Y-%m-%d") as fecha_v'), 'ventas.venta_descuento', 'c.cliente_ruc', 'c.cliente_nombre', 'c.cliente_direccion', 'c.cliente_cel','ctas_cobrar.monto_cuota')
            ->groupBy('ctas_cobrar.nro_fact_ventas')
            ->having('saldo', '>', 0)
            ->get();

        
        return view('export.ctascobrar',[
            'ctas' => $ctas,
            'moras' => $this->getDiaMora()
        ]);
    }
}
?>