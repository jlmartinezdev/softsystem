<?php

namespace App\Exports;

use App\Articulo;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithProperties;

class ArticulosExport implements FromCollection, WithColumnFormatting,ShouldAutoSize, WithHeadings, WithStyles,WithProperties
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Articulo::join('presentacion as p','articulos.present_cod','p.present_cod')
        ->join('stock as s','articulos.ARTICULOS_cod','s.ARTICULOS_cod')
        ->select('articulos.producto_c_barra','articulos.producto_nombre','p.present_descripcion','articulos.pre_venta1','s.cantidad')->get();
    }
    public function headings(): array
    {
        return [
            'Cod. Barra',
            'Articulo',
            'Seccion',
            'Precio Venta',
            'Stock'
        ];
    }
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
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
