<?php

use App\Articulo;
use DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\Exportable;

class articulosPreciosExport implements FromQuery,WithColumnFormatting, WithHeadings, WithStyles,WithProperties,WithColumnWidths
{
    use Exportable;

    public function filtro($request)
    {
        $this->request = $request;
        
        return $this;
    }
    
    public function query()
    {
        $criterios = ["producto_nombre","producto_c_barra"];
        $columnas= ["articulos.producto_nombre","articulos.articulos_cod", "articulos.pre_venta1"];
        $columna=$columnas[$this->request->col];
        $criterio=$criterios[$this->request->criterio];
        $seccion= $this->request->seccion;
        $buscar = $this->request->buscar;
        $idsucursal= isset($this->request->suc) ? $this->request->suc : null;
        
            $articulos = Articulo::query()->join('stock', 'articulos.articulos_cod', '=', 'stock.articulos_cod')
            ->join('precios','articulos.ARTICULOS_cod','=', 'precios.ARTICULOS_cod')
            ->join('presentacion','articulos.present_cod','=','presentacion.present_cod')
            ->select( 'articulos.producto_c_barra',DB::raw('TRIM(articulos.producto_nombre)'),DB::raw('TRIM(presentacion.present_descripcion)'),'articulos.pre_venta1',DB::raw('SUM(stock.cantidad) AS cantidad'),'precios.*')
            ->descripcion($buscar)
            ->seccion($seccion)
            ->bysucursal($idsucursal)
            ->groupBy('articulos.articulos_cod')
            ->orderBy($columna, $this->request->ord); 
                
        return $articulos;
    }
    public function headings(): array
    {
        return [
            'Cod. Barra',
            'Articulo',
            'Seccion',
            'Precio Contado',
            '2 Cuotas',
            '3 Cuotas',
            '4 Cuotas',
            '5 Cuotas',
            '6 Cuotas',
            '7 Cuotas',
            '8 Cuotas',
            '9 Cuotas',
            '10 Cuotas',
            '11 Cuotas',
            '12 Cuotas',
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 18,
            'B' => 55, 
            'C' => 30,
            'D' => 14,
            'E' => 14,
            'F' => 14, 
            'G' => 14,
            'H' => 14, 
            'I' => 14,
            'J' => 14, 
            'K' => 14,  
            'L' => 14, 
            'M' => 14, 
            'N' => 14,
            'O' => 14                       
        ];
    }
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true],'borders'=>['allBorders'=>['borderStyle'=>true]]],

        ];
    }
    public function properties(): array
    {
        return [
            'creator'        => 'Softsystem v2.0',
            'lastModifiedBy' => 'By @jlmartinezpy',
            'title'          => 'Articulos',
            'description'    => 'Lista de articulos con stcok',
            'subject'        => 'Articulos',
            'keywords'       => 'articulos,export,spreadsheet',
            'category'       => 'Sistema Informatico',
            'manager'        => 'JL Martinez',
            'company'        => 'controlhig.com',
        ];
    }
}
