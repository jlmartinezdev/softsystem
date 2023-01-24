<table>
    <thead>
        <tr>
            <th colspan="8" height="18"> <center><h3>Informe Generado: {{ date('d-m-Y')}}</h3></center> </th>
        </tr>
        <tr>
            <th><strong>#</strong></th>
            <th width="60"><strong>Cliente</strong></th>
            <th width="15"><strong>Nro. Venta</strong></th>
            <th width="15"><strong>Fecha Venta</strong></th>
            <th width="24"><strong>Cobrado / Cant. Cuota</strong></th>
            <th width="17"><strong>Monto Cuota</strong></th>
            <th width="15"><strong>Mora dias</strong></th>
            <th width="25"></th> 
        </tr>
        
    </thead>
    <tbody>
        @foreach($ctas as $c)
        <tr>
            <td>{{($loop->index)+1}}</td>
            <td>{{ $c->cliente_nombre }}</td>
            <td>{{ $c->nro_fact_ventas}}</td>
            <td>{{ $c->venta_fecha}}</td>
            <td>{{ $c->pagada."/".$c->cuotas }}</td>
            <td>{{ $c->monto_cuota }}</td>
            <td>{{diferenciaFechaCompleta($moras->where('nro_fact_ventas',$c->nro_fact_ventas)->first()->fecha_v)['dias']}} </td>
            <td>{{diferenciaFechaCompleta($moras->where('nro_fact_ventas',$c->nro_fact_ventas)->first()->fecha_v)['detalle']}}</td>
        </tr>
        @endforeach
    
    </tbody>
</table>