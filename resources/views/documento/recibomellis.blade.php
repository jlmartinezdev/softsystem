<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo de Dinero</title>
    <style>
        body{
            font-family: 'Arial';
        }
        .cabecera{
            height: 125px;
        }
        /* Tamaño legal para impresión */
        @media print {
            @page {
                size: legal portrait;
                margin: 10mm;
            }
            body, .container {
                width: 21.59cm;
                min-height: 35.56cm;
                max-width: 100%;
            }
            
        }
        .upper{
            text-transform: uppercase;
            font-size: 1.5rem;
        }
        /* Para visualización en pantalla similar a legal */
        .container {
            width: 21.59cm;
            min-height: 35.56cm;
            max-width: 100%;
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        /* Bordes negros para todas las tablas y celdas */
        table, th, td {
            border: 1px solid #000 !important;
            border-collapse: collapse;
        }
        th, td {
            padding: 4px;
        }
        @media print {
            table, th, td {
                border: 1px solid #000 !important;
                color: #000 !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-8">
                <!--<img src="{{ asset('/img/membrete_eco_muebles.jpg') }}" width="100%" alt="{{$empresa->emp_nombre}}">-->
                <div class="m-2 border border-dark p-2 d-flex flex-column text-center cabecera">
                   <span class="upper"><strong>{{$empresa->emp_nombre}}</strong></span>
                   <span style="font-size: 0.8rem;">{{$empresa->emp_descripcion}}</span>
                   <span style="margin-top: -5px; font-size: 0.8rem;">Cel: {{$empresa->emp_celular}}</span>
                   <span style="margin-top: -5px; font-size: 0.8rem;">Direc: {{$empresa->emp_direccion}}</span>
                   
               </div>
            </div>
            
            <div class="col-4">
                <div class="m-2 d-flex flex-column text-center cabecera">
                    <span>
                        <h4><strong>RECIBO DE DINERO</strong></h4>
                    </span>
                    <span><h5> N° {{str_pad($cobro->recibon1,3,"0",STR_PAD_LEFT)}}-{{str_pad($cobro->recibon2,3,"0",STR_PAD_LEFT)}}-{{str_pad($cobro->nro_recibo,7,"0",STR_PAD_LEFT)}}</h5></span>
                    <span class="border border-dark p-2"><h5>GS.:{{number_format($cobro->cob_importe,2,",",".")}}</h5></span>
                </div>
            </div>
        </div>
        <div class="m-2 p-2 d-flex flex-column">
           
@php
    $ruc = $cobro->cliente_ruc;
    $ruc_parts = explode('-', $ruc);
    $ruc_num = isset($ruc_parts[0]) ? number_format($ruc_parts[0], 0, '', '.') : '';
    $ruc_dv = isset($ruc_parts[1]) ? $ruc_parts[1] : '';
@endphp
           <span>Recibimos del/la Sr./a <strong>{{$cobro->cliente_nombre}}</strong>  con documento y/o R.U.C. número  <strong>{{ $ruc_num }}{{ $ruc_dv !== '' ? '-' . $ruc_dv : '' }}</strong> <br> en la fecha <strong>{{ date('d-m-Y')}}</strong> </span>
           <span>La suma de: <strong>{{NumeroALetras::convertir($cobro->cob_importe,"Guaranies")}}</strong></span>
           <span>En concepto: <strong>se detalla acontinuación </strong></span>
        </div>

        
        <div class="m-2 border border-dark pl-2 pr-2 pt-1 pb-1 d-flex flex-column">
            Detalles de Cobro
        </div>
        <div class="p-2">
            <table class="table-bordered">
                <tr>
                    <th style="width: 100px" class="pl-2"> Nro. Cuota</th>
                    <th style="width: 150px" class="pl-2"> Importe</th>
                    <th style="width: 150px" class="pl-2"> Interes por Mora</th>
                    <th style="width: 150px" class="pl-2"> Dias de atraso</th>
                </tr>
                @foreach($cuotas as $cuota)
                <tr>
                    <td>&nbsp;&nbsp;{{$cuota->nro_cuotas}} de {{  $cantidad_cuotas[array_search($cuota->nro_fact_ventas, array_column($cantidad_cuotas, 'nro_fact_ventas'))]['cantidad']}}</td>
                    <td>&nbsp;&nbsp;{{number_format($cuota->cobrado,2,",",".")}}</td>
                    <td>&nbsp;&nbsp;{{number_format($cuota->interes,2,",",".")}}</td>
                    <td>&nbsp;&nbsp;{{ $cuota->interes> 0 ? diferenciaFecha($cuota->fecha_venc, $cobro->cob_fecha) : "-"}}</td>
                </tr>
                @endforeach
            </table>
            <hr>
            <table class="table-bordered border-dark">
                <tr>
                    <th  style="width: 100px">&nbsp;&nbsp;Nro. Venta</th>
                    <th  style="width: 170px">&nbsp;&nbsp;Fecha Venta</th>
                    <th  style="width: 350px">&nbsp;&nbsp;Articulo</th>
                </tr>
                @foreach($articulos as $articulo)
                <tr>
                    <td>&nbsp;&nbsp;{{$articulo->nro_fact_ventas}}</td>
                    <td>&nbsp;&nbsp;{{date_format(new DateTime($articulo->venta_fecha),"d/m/Y H:i")}}</td>
                    <td>&nbsp;&nbsp;{{$articulo->producto_nombre}}</td>
                </tr>
                @endforeach
            </table>

        </div>
        <div class="d-print-none">
            <button class="btn btn-primary"  onclick="javascript:window.print()" > Imprimir</button>
            <a href="{{route('cobro')}}" class="btn btn-secondary"> Atras</a>
        </div>
    </div>

</body>
<script>

    formatFecha= function(fecha) {
        const f = fecha.split("-");
        return f[2] + "/" + f[1] + "/" + f[0];
    } 
    subFecha= function(startFecha) {
        const fechaInicio = new Date(startFecha).getTime();
        const fechaFin = new Date(endFecha).getTime(); 
        if (fechaInicio > fechaFin) {
            return 0;
        }
        const diff = fechaFin - fechaInicio;
        return parseInt(diff / (1000 * 60 * 60 * 24));
    }
    diferenciaFecha= function(fecha_vent, pagada) {
        //2016-07-12
        const dia = subFecha(fecha_vent)
        
        //let diferenciaFecha = 0;
        if (pagada == 0) {
            /* if ((dia - 30) > 0) {
                return dia - 30;
            } else {
                return "-";
            } */
            return dia
        } else {
            return "-"
        }
    }
</script>
</html>