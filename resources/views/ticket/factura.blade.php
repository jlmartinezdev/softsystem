<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.7">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/ticket.css" rel="stylesheet">
    <title>FACTURA SOFTSYSTEM</title>
</head>

<body>
    <div class="ticket" id="ticket-container">

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
            <br>
            Timbrado: {{ $factura[0]->timbrado}}
            <br>
            Valido desde: {{ date('d/m/Y', strtotime($factura[0]->fecha_factura)) }}
            @if(!empty($factura[0]->fecha_venc))
            <br>
            Valido hasta: {{ date('d/m/Y', strtotime($factura[0]->fecha_venc)) }}
            @endif
        </p>
        <p class="centrado">
            <strong> FACTURA ELECTRÓNICA</strong>
            <br>
            <strong>{{$factura[0]->nivel1.'-'.$factura[0]->nivel2.'-'.str_pad($factura[0]->nro_factura,7,'0',STR_PAD_LEFT)}}</strong>
        </p>
        @if(!empty($documento) && !empty($documento->cdc))
            <p class="centrado small">
                CDC: {{ $documento->cdc }}
            </p>
        @endif
        Fecha: {{ date('d/m/Y H:i', strtotime($cabecera->venta_fecha ?? 'now')) }}
        <br>
        Cliente: {{ $cabecera->cliente_nombre }}
        <br>
        C.I. / RUC: {{ $cabecera->cliente_ruc ?: $cabecera->cliente_ci }}
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
        <strong>Total a Pagar:</strong> {{ number_format($cabecera->venta_total,0,',','.')}}
        <br>
        {{ NumeroALetras::convertir($cabecera->venta_total,"GUARANIES")}}
        <br>
        <br>
        <strong>Liq. IVA. (5%):</strong> {{ number_format($iva[0]->iva5 * 0.05 ,0,',','.')}}
        <br>
        <strong>Liq. IVA (10%):</strong> {{ number_format($iva[0]->iva10 * 0.090909 ,0,',','.')}}
        <br>
        <strong>Total IVA:</strong> {{ number_format(($iva[0]->iva10 * 0.090909) + ($iva[0]->iva5 * 0.05),0,',','.') }}
        <br>
        @if(!empty($qrImage))
            <div class="ticket-qr centrado">
                <img src="data:image/png;base64,{{ $qrImage }}" alt="Código QR e-Kuatia" class="ticket-qr-img">
                <p class="ticket-qr-hint">Consulte su comprobante en e-Kuatia</p>
            </div>
        @endif
        <p class="centrado">¡GRACIAS POR SU PREFERENCIA!</p> 
    </div>
    <script>
        window.onload = function() {
            try {
                var cfgRaw = localStorage.getItem('config_venta');
                if (cfgRaw != null && cfgRaw !== '') {
                    var cfg = JSON.parse(cfgRaw);
                    var tam = cfg.tamano_ticket;
                    var ticketEl = document.getElementById('ticket-container');
                    if (ticketEl && (tam === '80mm' || tam === '56mm' || tam === 80 || tam === 56 || tam === '80' || tam === '56')) {
                        if (tam === 80 || tam === '80') tam = '80mm';
                        if (tam === 56 || tam === '56') tam = '56mm';
                        ticketEl.classList.remove('ticket--80', 'ticket--56');
                        ticketEl.style.width = tam;
                        ticketEl.style.maxWidth = tam;
                        ticketEl.classList.add(tam === '56mm' ? 'ticket--56' : 'ticket--80');
                    }
                }
            } catch (e) {}
        };
    </script>
</body>

</html>
