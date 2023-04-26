<!DOCTYPE html>

<head>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        .bg-gray {
            background-color: gray !important;
        }

        @media print {
            .table th {
                background-color: gray !important;
            }

            .oculto {
                display: none !important;
            }
        }

    </style>
</head>

<body>

    <div class="container">
        <div style="font-size: 16pt; font-style: italic; color: #3393FF; font-weight: bold;">
            <center>RESUMEN</center>
        </div>

        <hr class="bg-primary" style="height: 4px; margin-bottom: 0px">
        <div class="row">
            <div class="col-4">
                <img src="{{asset('img/'.$empresa->emp_logo)}}" alt="{{$empresa->emp_nombre}}">
            </div>
            <div class="col-4">
                <ul class="list-unstyled">
                    <li class="font-weight-bold text-uppercase" style="font-size: 1.3em"> {{ $empresa->emp_nombre }}</li>
                    <li>{{ $empresa->emp_descripcion }}</li>
                    <li>Direccion:{{ $empresa->emp_direccion }}</li>
                    <li>Cel:{{ $empresa->emp_celular }}</li>
                </ul>
            </div>
            <div class="col-4 p-2">
                <div class="border rounded p-2 text-center">
                    <ul class="list-unstyled">
                    <li>
                        <h5><strong>Nº DE VENTA</strong></h5>
                    </li>
                    <li>
                        {{ str_pad($venta->nro_fact_ventas, 7, '0', STR_PAD_LEFT) }}
                    </li>
                    <li><strong>RUC</strong></li>
                    <li>{{ $empresa->emp_ruc }}</li>
                </ul>
                </div>
                
            </div>
        </div>

        <hr class="bg-primary" style="height: 3px; margin-bottom: 0px">
        <table width="100%">
            <tr>
                <td> <strong>Razon social: </strong> {{ $venta->cliente_nombre }}</td>
                <td><strong>Fecha: </strong>{{ date_format(new DateTime($venta->venta_fecha), 'd/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td><strong>Documento:</strong> {{ $venta->cliente_ruc }}</td>
                <td><strong>Condicion:</strong> {{ $venta->tipo_factura == "1" ? "Contado" : "Credito"}} </td>
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
                @foreach ($detalle as $d)
                    <tr>
                        <td>{{ $d->ARTICULOS_cod }}</td>
                        <td>{{ $d->producto_nombre }}</td>
                        <td>{{ number_format($d->venta_precio, 2, ',', '.') }}</td>
                        <td>{{ number_format($d->venta_cantidad, 2, ',', '.') }}</td>
                        <td>{{ number_format($d->venta_precio * $d->venta_cantidad, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </table>
            <hr>
            <div>
                <strong>DESCUENTO:</strong> {{ number_format($venta->venta_descuento, 2, ',', '.') }}
                <h5 class="mt-2"><strong>TOTAL:</strong> {{ number_format($venta->venta_total, 2, ',', '.') }}</h5>
            </div>
            <div>
                {{ NumeroALetras::convertir($venta->venta_total, 'GUARANIES') }}.-
            </div>
            <hr>
            <div class="d-print-none">
                <button class="btn btn-primary"  onclick="javascript:window.print()" > Imprimir</button>
                <a href="{{route('infventa.imprimir')}}" class="btn btn-secondary"> Atras</a>
            </div>

        </div>
    </div>


</body>

</html>
