<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo de Dinero</title>
    <style>
        .cabecera{
            height: 115px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-3">
                <div class="m-2  cabecera">
                    <img src="{{asset("/img/".$empresa->emp_logo)}}" alt="{{$empresa->emp_nombre}}">
                </div>
            </div>
            <div class="col-5">
               <div class="m-2 border border-dark p-2 d-flex flex-column text-center cabecera">
                   <span><strong>{{$empresa->emp_nombre}}</strong></span>
                   <span>Cel: {{$empresa->emp_celular}}</span>
                   <span>Direc: {{$empresa->emp_direccion}}</span>
                   <span>RUC: {{$empresa->emp_ruc}}</span>
               </div>
            </div>
            <div class="col-4">
                <div class="m-2 border border-dark p-2 d-flex flex-column text-center cabecera">
                    <span>
                        <h4><strong>RECIBO DE DINERO</strong></h4>
                    </span>
                    <span><h5> N° {{str_pad($cobro->recibon1,3,"0",STR_PAD_LEFT)}}-{{str_pad($cobro->recibon2,3,"0",STR_PAD_LEFT)}}-{{str_pad($cobro->nro_recibo,7,"0",STR_PAD_LEFT)}}</h5></span>
                    <span class="border border-dark rounded-lg"><h5>GS.:{{number_format($cobro->cob_importe,2,",",".")}}</h5></span>
                </div>
            </div>
        </div>
        <div class="m-2 border border-dark p-2 d-flex flex-column">
           
           <span>Recibimos del/la Sr./a <strong>{{$cobro->cliente_nombre}}</strong>  con documento y/o R.U.C. número  <strong>{{$cobro->cliente_ruc}}</strong> en la fecha <strong>{{ date('d-m-Y')}}</strong> </span>
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
                </tr>
                @foreach($cuotas as $cuota)
                <tr>
                    <td>&nbsp;&nbsp;{{$cuota->nro_cuotas}} de {{$cobro->cantidad}}</td>
                    <td>&nbsp;&nbsp;{{number_format($cuota->cobrado,2,",",".")}}</td>
                    <td>&nbsp;&nbsp;{{number_format($cuota->interes,2,",",".")}}</td>
                </tr>
                @endforeach
            </table>
            <hr>
            <table class="table-bordered">
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
    function imprimir(){

    }
</script>
</html>