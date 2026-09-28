<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Estado de Cuenta #{{ $articulos[0]->nro_fact_ventas }} - {{ $articulos[0]->cliente_nombre }}</title>
    <link href="{{ asset('css/adminlte.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.min.css') }}" rel="stylesheet">
    <style>
        @font-face {
            font-family: "Cairo";
            font-style: normal;
            font-weight: 700;
            font-display: swap;
            src: url({{ asset("webfonts/Cairo-Bold.ttf") }}) format("truetype");
        }
        .font-cairo {
            font-family: 'Cairo', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background-color: #f1f5f9;
            color: #1e293b;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 13px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* Barra de Acciones Superior en Pantalla */
        .print-actions-bar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 0.6rem 1rem;
        }

        /* Hoja de Extracto */
        .extracto-sheet {
            background: #ffffff;
            max-width: 920px;
            margin: 1.5rem auto 3rem;
            padding: 2.2rem 2.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }

        /* Encabezado */
        .extracto-header-box {
            padding-bottom: 1.25rem;
            border-bottom: 2px solid #0a4d36;
        }
        .extracto-badge-title {
            display: inline-block;
            background: #0a4d36;
            color: #ffffff;
            font-family: 'Cairo', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 4px 16px;
            border-radius: 6px;
            text-transform: uppercase;
        }
        .company-fiscal-box {
            font-size: 0.85rem;
            line-height: 1.35;
        }

        /* Tarjeta de Información */
        .info-card-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.85rem 1.15rem;
        }

        /* Títulos de Sección */
        .section-title {
            font-family: 'Cairo', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            color: #0a4d36;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 0.45rem;
            display: flex;
            align-items: center;
        }

        /* Tablas */
        .table-extracto {
            width: 100%;
            margin-bottom: 1rem;
            border-collapse: collapse;
            font-size: 0.88rem;
        }
        .table-extracto th {
            background: #f1f5f9;
            color: #475569;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 7px 10px;
            border-top: 1px solid #cbd5e1;
            border-bottom: 1px solid #cbd5e1;
            vertical-align: middle;
        }
        .table-extracto td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .table-extracto tbody tr:hover {
            background-color: #f8fafc;
        }
        .table-extracto .tfoot-total td {
            background: #f8fafc;
            border-top: 2px solid #cbd5e1;
            border-bottom: 2px solid #cbd5e1;
            padding: 8px 10px;
        }

        /* Badges de Estado para Impresión */
        .badge-print {
            display: inline-block;
            padding: 2px 7px;
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 4px;
            text-align: center;
            letter-spacing: 0.3px;
        }
        .badge-print-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .badge-print-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }
        .badge-print-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* KPI Cards Resumen */
        .kpi-print-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem 0.9rem;
            text-align: center;
            height: 100%;
        }
        .kpi-print-box.highlight-debt {
            border-color: #fca5a5;
            background: #fef2f2;
        }
        .kpi-print-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 2px;
        }
        .kpi-print-val {
            font-family: 'Cairo', sans-serif;
            font-size: 1.12rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
        }
        .kpi-print-sub {
            font-size: 0.72rem;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* Líneas de Firma */
        .signature-line {
            width: 220px;
            border-top: 1px solid #475569;
            margin-top: 50px;
        }

        /* Reglas Específicas de Impresión */
        @media print {
            body {
                background: #ffffff !important;
                color: #000000 !important;
                font-size: 11px !important;
            }
            .d-print-none {
                display: none !important;
            }
            .extracto-sheet {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
            .page-break-avoid {
                page-break-inside: avoid !important;
            }
            .table-extracto {
                font-size: 10px !important;
            }
            .table-extracto th {
                background: #f1f5f9 !important;
                color: #000000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .badge-print {
                border-width: 1px !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .kpi-print-box {
                border: 1px solid #cbd5e1 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .extracto-badge-title {
                background: #0a4d36 !important;
                color: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        @page {
            size: A4 portrait;
            margin: 10mm 12mm;
        }
    </style>
</head>
<body>

    <!-- Barra de Acciones Superior (Solo visible en pantalla) -->
    <div class="print-actions-bar d-print-none">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <a href="{{ route('cobro') }}" class="btn btn-outline-secondary btn-sm mr-3 font-weight-bold" style="border-radius: 6px;">
                    <i class="fa fa-arrow-left mr-1"></i> Volver a Cobros
                </a>
                <span class="font-weight-bold font-cairo" style="font-size: 1rem; color: #1e293b;">
                    <i class="fa-solid fa-file-invoice-dollar mr-1.5 text-success"></i>
                    Estado de Cuenta #{{ $articulos[0]->nro_fact_ventas }} &bull; {{ $articulos[0]->cliente_nombre }}
                </span>
            </div>
            <div class="d-flex align-items-center">
                <button class="btn btn-success btn-sm font-weight-bold px-3 shadow-sm" onclick="window.print()" style="border-radius: 6px;">
                    <i class="fa fa-print mr-1.5"></i> Imprimir / Guardar PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Hoja Principal de Extracto -->
    <div class="extracto-sheet">
        <!-- Encabezado de la Empresa y Título del Documento -->
        <div class="extracto-header-box mb-3">
            <div class="row align-items-center">
                <!-- Logo / Nombre Empresa -->
                <div class="col-4">
                    @if(!empty($empresa->emp_logo) && file_exists(public_path('img/'.$empresa->emp_logo)))
                        <img src="{{ asset('/img/'.$empresa->emp_logo) }}" alt="{{ $empresa->emp_nombre }}" class="img-fluid" style="max-height: 80px; object-fit: contain;">
                    @else
                        <h4 class="font-cairo font-weight-bold mb-0 text-uppercase" style="color: #0a4d36;">
                            {{ $empresa->emp_nombre }}
                        </h4>
                    @endif
                </div>

                <!-- Título Central -->
                <div class="col-4 text-center">
                    <div class="extracto-badge-title">ESTADO DE CUENTA</div>
                    <div class="small text-muted font-weight-bold mt-1">Crédito Comercial</div>
                    <div class="font-weight-bold font-cairo" style="font-size: 1.15rem; color: #0f172a;">
                        Venta N° {{ $articulos[0]->nro_fact_ventas }}
                    </div>
                </div>

                <!-- Datos Fiscales Empresa -->
                <div class="col-4 text-right">
                    <div class="company-fiscal-box">
                        <div class="font-weight-bold text-uppercase" style="font-size: 0.95rem; color: #0a4d36;">
                            {{ $empresa->emp_nombre }}
                        </div>
                        <div><strong>RUC:</strong> {{ $empresa->emp_ruc }}</div>
                        <div><strong>Tel/Cel:</strong> {{ $empresa->emp_celular }}</div>
                        <div class="text-muted" style="font-size: 0.8rem; line-height: 1.2;">
                            {{ $empresa->emp_direccion }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ficha de Datos del Cliente y la Operación -->
        <div class="info-card-box mb-3">
            <div class="row">
                <div class="col-7 border-right">
                    <div class="small text-muted font-weight-bold text-uppercase">Titular de la Cuenta</div>
                    <div class="font-weight-bold font-cairo" style="font-size: 1.15rem; color: #0f172a;">
                        {{ $articulos[0]->cliente_nombre }}
                    </div>
                    <div class="mt-1 small">
                        <strong class="text-muted">Documento / C.I.:</strong>
                        <span class="font-weight-bold">{{ $articulos[0]->cliente_ruc }}</span>
                    </div>
                </div>
                <div class="col-5 pl-3">
                    <div class="small text-muted font-weight-bold text-uppercase">Datos de la Venta</div>
                    <div class="small">
                        <strong>N° Venta / Factura:</strong> #{{ $articulos[0]->nro_fact_ventas }}
                    </div>
                    <div class="small">
                        <strong>Fecha de la Venta:</strong> {{ date_format(new DateTime($articulos[0]->venta_fecha), "d/m/Y H:i") }}
                    </div>
                    <div class="small">
                        <strong>Fecha de Emisión:</strong> {{ date("d/m/Y H:i") }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla 1: Detalle de Artículos de la Venta -->
        <div class="section-title">
            <i class="fa fa-shopping-cart mr-1.5"></i> Artículos de la Venta
        </div>
        <table class="table-extracto mb-3">
            <thead>
                <tr>
                    <th style="width: 100px;">Código</th>
                    <th>Descripción del Producto</th>
                    <th class="text-center" style="width: 70px;">Cant.</th>
                    <th class="text-right" style="width: 125px;">Precio Unit.</th>
                    <th class="text-right" style="width: 135px;">Importe</th>
                </tr>
            </thead>
            <tbody>
                @php $totalVenta = 0; @endphp
                @foreach($articulos as $a)
                    @php 
                        $subtotalItem = $a->venta_precio * $a->venta_cantidad;
                        $totalVenta += $subtotalItem;
                    @endphp
                    <tr>
                        <td class="font-monospace small text-muted">{{ $a->articulos_cod }}</td>
                        <td class="font-weight-bold">{{ $a->producto_nombre }}</td>
                        <td class="text-center font-weight-bold">{{ intval($a->venta_cantidad) }}</td>
                        <td class="text-right text-muted">Gs. {{ number_format($a->venta_precio, 0, ',', '.') }}</td>
                        <td class="text-right font-weight-bold font-cairo">Gs. {{ number_format($subtotalItem, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="tfoot-total">
                    <td colspan="4" class="text-right font-weight-bold text-uppercase">Total Artículos:</td>
                    <td class="text-right font-weight-bold font-cairo" style="font-size: 0.95rem; color: #0a4d36;">
                        Gs. {{ number_format($totalVenta, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Tabla 2: Cronograma de Cuotas y Amortizaciones -->
        <div class="section-title">
            <i class="fa fa-calendar-check mr-1.5"></i> Cronograma de Cuotas y Amortizaciones
        </div>
        <table class="table-extracto mb-3">
            <thead>
                <tr>
                    <th class="text-center" style="width: 55px;">Cuota</th>
                    <th style="width: 95px;">Vencimiento</th>
                    <th class="text-right" style="width: 110px;">Monto Cuota</th>
                    <th class="text-right" style="width: 110px;">Cobrado</th>
                    <th class="text-right" style="width: 110px;">Saldo</th>
                    <th class="text-center" style="width: 65px;">Mora</th>
                    <th class="text-right" style="width: 100px;">Interés</th>
                    <th class="text-center" style="width: 90px;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $sumCuotas = 0;
                    $sumCobrado = 0;
                    $sumSaldo = 0;
                    $sumInteres = 0;
                @endphp
                @foreach($cuotas as $cuota)
                    @php
                        $diasAtraso = ($cuota->monto_saldo > 0 && !isFechaMayor($cuota->fecha_venc)) ? diferenciaFecha($cuota->fecha_venc) : "-";
                        $montoInt = setMontointeres($cuota->fecha_venc, $cuota->monto_cuota, $cuota->monto_saldo);
                        $sumCuotas += $cuota->monto_cuota;
                        $sumCobrado += $cuota->monto_cobrado;
                        $sumSaldo += $cuota->monto_saldo;
                        $sumInteres += $montoInt;
                        $isPagado = ($cuota->monto_cobrado >= $cuota->monto_cuota);
                    @endphp
                    <tr>
                        <td class="text-center font-weight-bold">#{{ $cuota->nro_cuotas }}</td>
                        <td>{{ date_format(new DateTime($cuota->fecha_venc), "d/m/Y") }}</td>
                        <td class="text-right font-weight-bold font-cairo">Gs. {{ number_format($cuota->monto_cuota, 0, ',', '.') }}</td>
                        <td class="text-right text-success font-weight-bold font-cairo">Gs. {{ number_format($cuota->monto_cobrado, 0, ',', '.') }}</td>
                        <td class="text-right font-weight-bold font-cairo {{ $cuota->monto_saldo > 0 ? 'text-danger' : 'text-muted' }}">
                            Gs. {{ number_format($cuota->monto_saldo, 0, ',', '.') }}
                        </td>
                        <td class="text-center font-weight-bold {{ $diasAtraso != '-' && intval($diasAtraso) > 0 ? 'text-danger' : 'text-muted' }}">
                            {{ $diasAtraso }}
                        </td>
                        <td class="text-right {{ $montoInt > 0 ? 'text-warning font-weight-bold font-cairo' : 'text-muted' }}">
                            Gs. {{ number_format($montoInt, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @if($isPagado)
                                <span class="badge-print badge-print-success">PAGADO</span>
                            @elseif($diasAtraso != '-' && intval($diasAtraso) > 0)
                                <span class="badge-print badge-print-danger">VENCIDA</span>
                            @else
                                <span class="badge-print badge-print-warning">PENDIENTE</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="tfoot-total">
                    <td colspan="2" class="text-right font-weight-bold text-uppercase">Totales:</td>
                    <td class="text-right font-weight-bold font-cairo">Gs. {{ number_format($sumCuotas, 0, ',', '.') }}</td>
                    <td class="text-right font-weight-bold font-cairo text-success">Gs. {{ number_format($sumCobrado, 0, ',', '.') }}</td>
                    <td class="text-right font-weight-bold font-cairo text-danger">Gs. {{ number_format($sumSaldo, 0, ',', '.') }}</td>
                    <td></td>
                    <td class="text-right font-weight-bold font-cairo text-warning">Gs. {{ number_format($sumInteres, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <!-- Resumen Financiero Ejecutivo (Cajas de Balance) -->
        <div class="row no-gutters mb-4 page-break-avoid">
            <div class="col-3 pr-1">
                <div class="kpi-print-box">
                    <div class="kpi-print-label">TOTAL CRÉDITO</div>
                    <div class="kpi-print-val">Gs. {{ number_format($sumCuotas, 0, ',', '.') }}</div>
                    <div class="kpi-print-sub">Monto financiado</div>
                </div>
            </div>
            <div class="col-3 px-1">
                <div class="kpi-print-box">
                    <div class="kpi-print-label text-success">TOTAL COBRADO</div>
                    <div class="kpi-print-val text-success">Gs. {{ number_format($sumCobrado, 0, ',', '.') }}</div>
                    <div class="kpi-print-sub">Amortizado a la fecha</div>
                </div>
            </div>
            <div class="col-3 px-1">
                <div class="kpi-print-box">
                    <div class="kpi-print-label text-warning">INTERÉS MORA</div>
                    <div class="kpi-print-val text-warning">Gs. {{ number_format($sumInteres, 0, ',', '.') }}</div>
                    <div class="kpi-print-sub">Recargo por atraso</div>
                </div>
            </div>
            <div class="col-3 pl-1">
                <div class="kpi-print-box highlight-debt">
                    <div class="kpi-print-label text-danger">SALDO PENDIENTE</div>
                    <div class="kpi-print-val text-danger" style="font-size: 1.15rem;">
                        Gs. {{ number_format($sumSaldo, 0, ',', '.') }}
                    </div>
                    <div class="kpi-print-sub">Deuda neta actual</div>
                </div>
            </div>
        </div>

        <!-- Sección de Firmas y Nota Legal -->
        <div class="page-break-avoid mt-4">
            <div class="row pt-4">
                <div class="col-6 text-center">
                    <div class="signature-line mx-auto"></div>
                    <div class="font-weight-bold mt-1" style="font-size: 0.88rem; color: #0f172a;">
                        {{ $articulos[0]->cliente_nombre }}
                    </div>
                    <div class="small text-muted">Firma y Aclaración del Cliente / Titular</div>
                    <div class="small text-muted">C.I. / RUC: {{ $articulos[0]->cliente_ruc }}</div>
                </div>
                <div class="col-6 text-center">
                    <div class="signature-line mx-auto"></div>
                    <div class="font-weight-bold mt-1" style="font-size: 0.88rem; color: #0a4d36;">
                        {{ $empresa->emp_nombre }}
                    </div>
                    <div class="small text-muted">Firma y Sello de Cobranzas / Caja</div>
                    <div class="small text-muted">Responsable Autorizado</div>
                </div>
            </div>

            <div class="text-center text-muted small mt-4 pt-2 border-top" style="font-size: 0.78rem;">
                Documento emitido electrónicamente como extracto de cuenta fidedigno. Válido como constancia del estado contable y plan de pagos.
            </div>
        </div>
    </div>

</body>
</html>