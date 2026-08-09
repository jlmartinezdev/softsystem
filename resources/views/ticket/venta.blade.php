<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/css/ticket.css" rel="stylesheet">
    <title>COMPROBANTE SOFTSYSTEM</title>
</head>

<body>
    @php
        $descuento = $venta['venta'][0]->venta_descuento ?? 0;
        $recibido = $venta['venta'][0]->venta_recibido ?? 0;
        $vuelto = $venta['venta'][0]->venta_vuelto ?? 0;
        $total = $venta['venta'][0]->venta_total;
    @endphp

    <div class="ticket" id="ticket-container">
        <header class="centrado ticket-company">
            <img src="{{ asset('img/logo_negro.png') }}" alt="" class="ticket-logo" />
            <div class="ticket-title"><strong>{{ $empresa->emp_nombre }}</strong></div>
            <div>{{ $empresa->emp_direccion }}</div>
            <div><strong>Cel. {{ $empresa->emp_celular }}</strong></div>
            <div><strong>RUC {{ $empresa->emp_ruc }}</strong></div>
        </header>

        <div class="centrado">
            <div class="ticket-title">Comprobante de venta</div>
            <div class="ticket-sale-no">Nº {{ $venta['venta'][0]->nro_fact_ventas }}</div>
        </div>

        <hr class="raya" />

        <div class="ticket-meta">
            <div class="ticket-meta-row">
                <span class="ticket-meta-label">Fecha</span>
                <span class="ticket-meta-value">{{ date('d/m/Y H:i') }}</span>
            </div>
            <div class="ticket-meta-block">
                <span class="label">Cliente</span>
                {{ $venta['venta'][0]->cliente_nombre }}
            </div>
            <div class="ticket-meta-row">
                <span class="ticket-meta-label">C.I. / RUC</span>
                <span class="ticket-meta-value">{{ $venta['venta'][0]->cliente_ci }}</span>
            </div>
        </div>

        <table class="ticket-items">
            <thead>
                <tr>
                    <th class="col-qty">Cant.</th>
                    <th class="col-name">Producto</th>
                    <th class="col-amt">Importe</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($venta['detalle'] as $v)
                    <tr class="item-name-row">
                        <td colspan="3" class="col-name">{{ $v->producto_nombre }}</td>
                    </tr>
                    <tr class="item-detail-row">
                        <td class="col-qty">× {{ number_format($v->venta_cantidad, 2, ',', '.') }}</td>
                        <td colspan="2" class="col-amt">{{ number_format($v->venta_precio * $v->venta_cantidad, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="ticket-totals">
            <tr>
                <td>Descuento</td>
                <td class="num">Gs. {{ number_format($descuento, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-main">
                <td>Total a pagar</td>
                <td class="num">Gs. {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-words">
                <td colspan="2">{{ NumeroALetras::convertir($total, 'GUARANIES') }}</td>
            </tr>
            <tr>
                <td>Recibido</td>
                <td class="num">Gs. {{ number_format($recibido, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Vuelto</td>
                <td class="num">Gs. {{ number_format($vuelto, 0, ',', '.') }}</td>
            </tr>
        </table>

        <p class="centrado ticket-footer">¡Gracias por su preferencia!</p>

        <div class="ticket-actions d-print-none">
            <button type="button" class="btn btn-primary" onclick="window.print()">Imprimir</button>
            <a href="{{ route('venta') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</body>
<script>
    window.onload = function() {
        try {
            var cfgRaw = localStorage.getItem('config_venta');
            var ticketEl = document.getElementById('ticket-container');
            if (!ticketEl) {
                window.print();
                return;
            }
            ticketEl.classList.remove('ticket--80', 'ticket--56');
            if (cfgRaw != null && cfgRaw !== '') {
                var cfg = JSON.parse(cfgRaw);
                var tam = cfg.tamano_ticket;
                if (tam === 80 || tam === '80') tam = '80mm';
                if (tam === 56 || tam === '56') tam = '56mm';
                if (tam === '80mm' || tam === '56mm') {
                    ticketEl.style.width = tam;
                    ticketEl.style.maxWidth = tam;
                    ticketEl.classList.add(tam === '56mm' ? 'ticket--56' : 'ticket--80');
                }
            } else {
                ticketEl.classList.add('ticket--80');
            }
        } catch (e) {}
        window.print();
    };
</script>

</html>
