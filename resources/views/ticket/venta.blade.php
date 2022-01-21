<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.7">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/ticket.css" rel="stylesheet">
    <title>COMPROBANTE SOFTSYSTEM</title>
</head>

<body>
    <div class="ticket">

        <p class="centrado">
            <strong>{{ $empresa->emp_nombre }}</strong>
            <br>
            {{ $empresa->emp_direccion }}
            <br>
            <strong>
                Celular:{{ $empresa->emp_celular }}
            </strong>
            <br>
            <strong>
                RUC: {{ $empresa->emp_ruc }}
            </strong>
        </p>
        <p class="centrado">
            <strong> COMPROBANTE DE VENTA</strong>
            <br>
            <strong>Nro. de Venta: {{$venta[0]->nro_fact_ventas}}</strong>
        </p>
        Fecha: {{ date('d/m/Y H:i')}}
        <br>
        Cliente: {{ $venta[0]->cliente_nombre}}
        <br>
        C.I. / RUC: {{$venta[0]->cliente_ruc}}
        <br>
        <br>
        <table>
            <thead>
                <tr>
                    <th class="cantidad">CANT</th>
                    <th class="producto"> PRODUCTO</th>
                    <th class="precio"> $</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta as $v)
				<tr>
                    <td class="producto" colspan="3">{{ $v->producto_nombre}}</td>
                </tr>
                <tr>
                    <td class="cantidad">{{ number_format($v->venta_cantidad,2,',','.')}}</td>
                    <td class="precio" colspan="2" align="right">{{number_format($v->venta_precio, 0, ',', '.')}}</td>
                </tr>
                @endforeach
               
            </tbody>
        </table>
        <br>
        <strong>Total a Pagar:</strong> {{ number_format($venta[0]->venta_total,0,',','.')}}
        <br>
        {{ NumeroALetras::convertir($venta[0]->venta_total,"GUARANIES")}}
        <br>
        <br>
        <br>
        <p class="centrado">¡GRACIAS POR SU PREFERENCIA!</p> 
    </div>
</body>

</html>
