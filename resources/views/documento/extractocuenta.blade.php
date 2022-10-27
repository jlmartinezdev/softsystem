<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Extracto de Cuenta</title>
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
                <div class="m-2 p-2 d-flex text-center align-items-center cabecera">
                    <span>
                        <h4><strong>ESTADO DE CUENTA</strong></h4>
                    </span>
                </div>
            </div>
            <div class="col-4">
               <div class="m-2 border border-dark p-2 d-flex flex-column text-center  cabecera">
                   <span><strong>{{$empresa->emp_nombre}}</strong></span>
                   <span>Cel: {{$empresa->emp_celular}}</span>
                   <span>Direc: {{$empresa->emp_direccion}}</span>
                   <span>RUC: {{$empresa->emp_ruc}}</span>
               </div>
            </div>
            
        </div>


        <div class="row text-center">
            <div class="col-3 border-right">
                <strong> Fecha de Informe </strong></span>
                <span class="d-block"> {{ date("d/m/Y")}}</span>
                <strong> Nro Venta </strong></span>
                <span class="d-block"> {{ $articulos[0]->nro_fact_ventas}}</span>
            </div>
            <div class="col-3 border-right">
                <strong> Documento Nro. </strong></span>
                <span class="d-block">{{ $articulos[0]->cliente_ruc}}</span>
                <strong> Fecha Venta </strong></span>
                <span class="d-block">{{ date_format(new DateTime( $articulos[0]->venta_fecha),"d/m/Y H:i")}}</span>
            </div>
            <div class="col-4">
                <strong> Nombre Cliente </strong></span>
                <span class="d-block">{{ $articulos[0]->cliente_nombre}}</span>
            </div>
        </div>
       
        <span class="mb-2 mt-2"><strong> Detalle de venta</strong></span>
        <div class="border border-secondary">
            <table class="table table-sm table-striped">
            <tr>
                <th>Codigo</th>
                <th>Descripcion</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Importe</th>
            </tr>
            @foreach($articulos as $a)
                <tr>
                    <td>{{$a->articulos_cod}}</td>
                    <td>{{$a->producto_nombre}}</td>
                    <td>{{intval($a->venta_cantidad)}}</td>
                    <td>{{number_format($a->venta_precio,2,",",".")}}</td>
                    <td>{{number_format($a->venta_precio * $a->venta_cantidad,2,",",".")}}</td>
                </tr>
            @endforeach
        </table> 
        </div>
        <br>
        <span class="mt-2 mb-2"><strong> Detalle Cuotas</strong></span>
        <div class="border border-success">
            <table class="table table-sm table-striped">
                <tr>
                    <td>Nro Cuota</td>
                    <td>Vencimiento</td>
                    <td>Monto Cuota</td>
                    <td>Cobrado</td>
                    <td>Saldo</td>
                    <td>Mora</td>
                    <td>Estado</td>
                </tr>
                @foreach($cuotas as $cuota)
                    <tr>
                        <td>{{$cuota->nro_cuotas}}</td>
                        <td>{{date_format(new DateTime($cuota->fecha_venc),"d/m/Y")}}</td>
                        <td>{{number_format($cuota->monto_cuota,2,",",".")}}</td>
                        <td>{{number_format($cuota->monto_cobrado,2,",",".")}}</td>
                        <td>{{number_format($cuota->monto_saldo,2,",",".")}}</td>
                        <td>{{$cuota->monto_saldo > 0 && !isFechaMayor($cuota->fecha_venc) ? diferenciaFecha($cuota->fecha_venc) : "-"}}</td>
                        <td>{{ $cuota->monto_cobrado == $cuota->monto_cuota ? "Pagado" : "Pendiente"}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        
        <div class="row mt-2">
            <div class="col-4">
                Monto Cobrado: <strong>{{ number_format($cuotas->sum('monto_cobrado'),2,",",".")}}</strong> 
            </div>
            <div class="col-4">
                Saldo: <strong>{{ number_format($cuotas->sum('monto_saldo'),2,",",".")}}</strong> 
            </div>
        </div>
        <br>
        <br>
        <div class="d-print-none">
            <button class="btn btn-primary"  onclick="javascript:window.print()" > Imprimir</button>
            <a href="{{route('cobro')}}" class="btn btn-secondary"> Atras</a>
        </div>
    </div>
</body>
</html>