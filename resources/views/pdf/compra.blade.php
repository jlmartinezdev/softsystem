<!DOCTYPE html>
<head>  
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        body{
            font-family: Arial,Helvetica, sans-serif;
        }
        .bg-gray{
            background-color: gray !important;
        }
        @media print{
            .table th{
                background-color: gray !important;       
            }
            .oculto{
                display: none !important;
            }
        }
    </style>
</head>
<body>

        
    <div style="font-size: 16pt; font-style: italic; color: #3393FF; font-weight: bold;">
        <center>COMPROBANTE DE COMPRA</center>
    </div>
    
    <hr class="bg-primary" style="height: 4px; margin-bottom: 0px">
    <table width="100%">
        <tr>
            <td style="font-size: 12pt; text-transform: uppercase; font-weight: bold; font-style: italic;">{{$empresa->emp_nombre}}</td>
            <td>
                <strong>Nº de Compra:</strong>{{ str_pad($compra->compra_cod,7,"0",STR_PAD_LEFT)}}
            </td>   
        </tr>
        <tr>
        <td>
            <ul style="list-style: none; line-height: 1.2em; margin-left: 0px; padding-left: 0px">
                <li style="font-size: 10pt">{{$empresa->emp_descripcion}}</li>
                <li style="font-size: 10pt; font-style: italic;">Direccion:{{$empresa->emp_direccion}}</li>
                <li style="font-size: 10pt; font-style: italic;">Cel:{{$empresa->emp_celular}}</li>
            </ul> 
        </td>
        <td></td>
        </tr>
    </table>

    <hr class="bg-primary" style="height: 3px; margin-bottom: 0px">
    <table width="100%">
        <tr>
            <td> <strong>Proveedor: </strong> {{$compra->proveedor_nombre}}</td>
            <td><strong>Fecha: </strong>{{date_format(new DateTime($compra->compra_fecha),"d/m/Y H:i")}}</td>
        </tr>
        <tr>
            <td><strong>Documento:</strong> {{$compra->proveedor_ruc}}</td>
            <td></td>
        </tr>
    </table>
   
   

    <div class="bg-primary text-white p-1">
        <center><strong><i>Detalle</i></strong></center> 
    </div>

    <div class="mt-1">
        <table class="table table-sm" style="line-height: 1.2em;">
            <tr class="bg-gray text-white">
                <th>Codigo</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Importe</th>
            </tr>
            @foreach($detalle as $d)
            <tr>
                <td>{{$d->ARTICULOS_cod}}</td>
                <td>{{$d->producto_nombre}}</td>
                <td>{{number_format($d->compra_precio,2,",",".")}}</td>
                <td>{{number_format($d->compra_cantidad,2,",",".")}}</td>
                <td>{{number_format($d->compra_precio * $d->compra_cantidad,2,",",".")}}</td>
            </tr>
            @endforeach
        </table> 
        <div>
            <strong>TOTAL:</strong> {{ number_format($compra->total,2,",",".")}}
        </div>
        <div>
            {{NumeroALetras::convertir($compra->total,"GUARANIES")}}.-
        </div>
        <hr>
        
        
    </div>

</body>
</html>