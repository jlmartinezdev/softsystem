<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>KuDE {{ $documento->cdc }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #111; }
        .header { text-align: center; margin-bottom: 12px; }
        .header h2 { margin: 0 0 4px; font-size: 16px; }
        .meta { width: 100%; margin-bottom: 10px; }
        .meta td { vertical-align: top; padding: 2px 4px; }
        .box { border: 1px solid #333; padding: 8px; margin-bottom: 10px; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table.items th, table.items td { border: 1px solid #ccc; padding: 4px; }
        table.items th { background: #f5f5f5; }
        .right { text-align: right; }
        .center { text-align: center; }
        .qr { text-align: center; margin-top: 12px; }
        .qr img { width: 130px; height: 130px; }
        .small { font-size: 9px; color: #444; }
        .cdc { font-size: 10px; word-break: break-all; }
    </style>
</head>
<body>
    @php
        $cabecera = $venta['cabecera'];
        $items = $venta['items'];
        $totales = $venta['totales'];
        $numero = str_pad($documento->establecimiento, 3, '0', STR_PAD_LEFT) . '-' .
            str_pad($documento->punto_expedicion, 3, '0', STR_PAD_LEFT) . '-' .
            str_pad($documento->numero, 7, '0', STR_PAD_LEFT);
    @endphp

    <div class="header">
        <h2>{{ $empresa->emp_nombre }}</h2>
        <div>{{ $empresa->emp_direccion }}</div>
        <div>RUC: {{ $empresa->emp_ruc }} · Tel: {{ $empresa->emp_celular }}</div>
        <div><strong>KuDE — Factura electrónica</strong></div>
    </div>

    <table class="meta">
        <tr>
            <td width="50%">
                <strong>Timbrado:</strong> {{ $documento->timbrado }}<br>
                <strong>Válido desde:</strong> {{ $config->vigencia_desde }}<br>
                @if(!empty($config->vigencia_hasta))
                    <strong>Válido hasta:</strong> {{ $config->vigencia_hasta }}<br>
                @endif
                <strong>Nº comprobante:</strong> {{ $numero }}
            </td>
            <td width="50%">
                <strong>Fecha emisión:</strong> {{ $cabecera->venta_fecha }}<br>
                <strong>Cliente:</strong> {{ $cabecera->cliente_nombre }}<br>
                <strong>CI / RUC:</strong> {{ $cabecera->cliente_ruc ?: $cabecera->cliente_ci }}<br>
                <strong>Ambiente:</strong> {{ $config->ambiente === 'prod' ? 'Producción' : 'Prueba' }}
            </td>
        </tr>
    </table>

    <div class="box">
        <strong>CDC:</strong><br>
        <span class="cdc">{{ $documento->cdc }}</span>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th class="right">Cant.</th>
                <th class="right">Precio</th>
                <th class="right">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->producto_c_barra }}</td>
                    <td>{{ $item->producto_nombre }}</td>
                    <td class="right">{{ number_format($item->venta_cantidad, 2, ',', '.') }}</td>
                    <td class="right">{{ number_format($item->venta_precio, 0, ',', '.') }}</td>
                    <td class="right">{{ number_format($item->venta_precio * $item->venta_cantidad, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="meta" style="margin-top: 10px;">
        <tr>
            <td width="60%"></td>
            <td width="40%">
                <table width="100%">
                    <tr><td>Subtotal exento</td><td class="right">{{ number_format($totales['sub_exe'], 0, ',', '.') }}</td></tr>
                    <tr><td>Gravada 5%</td><td class="right">{{ number_format($totales['sub_5'], 0, ',', '.') }}</td></tr>
                    <tr><td>Gravada 10%</td><td class="right">{{ number_format($totales['sub_10'], 0, ',', '.') }}</td></tr>
                    <tr><td>IVA 5%</td><td class="right">{{ number_format($totales['iva_5'], 0, ',', '.') }}</td></tr>
                    <tr><td>IVA 10%</td><td class="right">{{ number_format($totales['iva_10'], 0, ',', '.') }}</td></tr>
                    <tr><td><strong>Total IVA</strong></td><td class="right"><strong>{{ number_format($totales['total_iva'], 0, ',', '.') }}</strong></td></tr>
                    <tr><td><strong>Total general</strong></td><td class="right"><strong>{{ number_format($totales['total_gral'], 0, ',', '.') }}</strong></td></tr>
                </table>
            </td>
        </tr>
    </table>

    @if(!empty($qrImage))
        <div class="qr">
            <img src="data:image/png;base64,{{ $qrImage }}" alt="QR e-Kuatia">
            <div class="small">Consulte su comprobante en e-Kuatia</div>
        </div>
    @endif

    <p class="small center">Documento electrónico generado conforme Manual Técnico v150 / SIFEN</p>
</body>
</html>
