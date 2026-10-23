@php
    $logoEmpresa = null;
    if (!empty($empresa) && !empty($empresa->emp_logo)) {
        $logoName = trim($empresa->emp_logo);
        if (file_exists(public_path('img/' . $logoName))) {
            $logoEmpresa = asset('img/' . $logoName);
        } elseif (file_exists(public_path($logoName))) {
            $logoEmpresa = asset($logoName);
        }
    }

    $ruc = trim($venta->cliente_ruc ?? '');
    $ruc_parts = explode('-', $ruc);
    $ruc_clean = isset($ruc_parts[0]) ? str_replace('.', '', trim($ruc_parts[0])) : '';
    $ruc_num = ($ruc_clean !== '' && is_numeric($ruc_clean)) ? number_format((float)$ruc_clean, 0, '', '.') : ($ruc_parts[0] ?? '');
    $ruc_dv = isset($ruc_parts[1]) ? trim($ruc_parts[1]) : '';
    $ruc_formateado = $ruc_num . ($ruc_dv !== '' ? '-' . $ruc_dv : '');

    if (!empty($cobro)) {
        $nroReciboTexto = str_pad($cobro->recibon1, 3, '0', STR_PAD_LEFT) . '-' . str_pad($cobro->recibon2, 3, '0', STR_PAD_LEFT) . '-' . str_pad($cobro->nro_recibo, 7, '0', STR_PAD_LEFT);
    } else {
        $nroReciboTexto = '001-001-' . str_pad($venta->nro_fact_ventas, 7, '0', STR_PAD_LEFT);
    }

    $importeRecibo = $venta->venta_total ?? 0;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Recibo de Dinero Nº {{ $nroReciboTexto }} - {{ trim($empresa->emp_nombre ?? 'SoftSystem') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        body {
            font-family: 'Arial', sans-serif;
        }
        .cabecera {
            min-height: 125px;
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
                box-shadow: none !important;
            }
            .oculto {
                display: none !important;
            }
        }
        .upper {
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
                <div class="m-2 border border-dark p-2 d-flex align-items-center cabecera">
                    @if($logoEmpresa)
                        <div class="pr-3 pl-2 d-flex align-items-center justify-content-center" style="max-width: 175px; flex-shrink: 0;">
                            <img src="{{ $logoEmpresa }}" alt="{{ trim($empresa->emp_nombre ?? '') }}" style="max-height: 105px; max-width: 160px; object-fit: contain;">
                        </div>
                    @endif
                    <div class="d-flex flex-column text-center justify-content-center flex-grow-1">
                        <span class="upper"><strong>{{ trim($empresa->emp_nombre ?? '') }}</strong></span>
                        @if(!empty($empresa->emp_descripcion) && trim($empresa->emp_descripcion) !== '-')
                            <span style="font-size: 0.8rem;">{{ trim($empresa->emp_descripcion) }}</span>
                        @endif
                        <span style="margin-top: -2px; font-size: 0.8rem;">Cel: {{ trim($empresa->emp_celular ?? '') }}</span>
                        <span style="margin-top: -2px; font-size: 0.8rem;">Direc: {{ trim($empresa->emp_direccion ?? '') }}</span>
                        @if(!empty($empresa->emp_ruc) && trim($empresa->emp_ruc) !== '-')
                            <span style="margin-top: -2px; font-size: 0.8rem;">RUC: {{ trim($empresa->emp_ruc) }}</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-4">
                <div class="m-2 d-flex flex-column justify-content-center text-center cabecera">
                    <span>
                        <h4><strong>RECIBO DE DINERO</strong></h4>
                    </span>
                    <span>
                        <h5>N° {{ $nroReciboTexto }}</h5>
                    </span>
                    <span class="border border-dark p-2">
                        <h5>GS.: {{ number_format($importeRecibo, 2, ",", ".") }}</h5>
                    </span>
                </div>
            </div>
        </div>

        <div class="m-2 p-2 d-flex flex-column">
            <span>Recibimos del/la Sr./a <strong>{{ $venta->cliente_nombre }}</strong> con documento y/o R.U.C. número <strong>{{ $ruc_formateado }}</strong> <br> en la fecha <strong>{{ date_format(new DateTime($venta->venta_fecha), 'd-m-Y') }}</strong></span>
            <span>La suma de: <strong>{{ NumeroALetras::convertir($importeRecibo, "Guaranies") }}</strong></span>
            <span>En concepto: <strong>se detalla a continuación ({{ $venta->tipo_factura == "1" ? "Venta Contado" : "Venta Crédito" }})</strong></span>
        </div>

        <div class="m-2 border border-dark pl-2 pr-2 pt-1 pb-1 d-flex flex-column font-weight-bold">
            Detalles de Venta
        </div>
        <div class="p-2">
            <table class="table-bordered border-dark" style="width: 100%;">
                <tr>
                    <th style="width: 90px" class="pl-2">Nro. Venta</th>
                    <th style="width: 150px" class="pl-2">Fecha Venta</th>
                    <th class="pl-2">Artículo</th>
                    <th style="width: 95px" class="text-center">Cantidad</th>
                    <th style="width: 140px" class="text-right pr-2">Precio</th>
                    <th style="width: 140px" class="text-right pr-2">Importe</th>
                </tr>
                @foreach ($detalle as $d)
                    <tr>
                        <td>&nbsp;&nbsp;{{ $venta->nro_fact_ventas }}</td>
                        <td>&nbsp;&nbsp;{{ date_format(new DateTime($venta->venta_fecha), 'd/m/Y H:i') }}</td>
                        <td>&nbsp;&nbsp;{{ $d->producto_nombre }}</td>
                        <td class="text-center">{{ number_format($d->venta_cantidad, 2, ',', '.') }}</td>
                        <td class="text-right pr-2">{{ number_format($d->venta_precio, 2, ',', '.') }}</td>
                        <td class="text-right pr-2">{{ number_format($d->venta_precio * $d->venta_cantidad, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
                @if(($venta->venta_descuento ?? 0) > 0)
                    <tr>
                        <td colspan="5" class="text-right pr-2"><strong>Descuento:</strong></td>
                        <td class="text-right pr-2">{{ number_format($venta->venta_descuento, 2, ',', '.') }}</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="5" class="text-right pr-2"><strong>Total Gs.:</strong></td>
                    <td class="text-right pr-2"><strong>{{ number_format($venta->venta_total, 2, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

        <div class="d-print-none mt-3 pl-2">
            <button class="btn btn-primary" onclick="javascript:window.print()"><i class="fa fa-print mr-1"></i> Imprimir</button>
            <a href="{{ route('venta') }}" class="btn btn-success"><i class="fa fa-shopping-cart mr-1"></i> Volver a Ventas</a>
            <a href="{{ route('infventa.imprimir') }}" class="btn btn-secondary">Ir a Informes</a>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
