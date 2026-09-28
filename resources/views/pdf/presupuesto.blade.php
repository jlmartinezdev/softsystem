<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Presupuesto #{{ str_pad($presupuesto->pre_numero, 6, '0', STR_PAD_LEFT) }} - {{ $empresa->emp_nombre ?? 'SoftSystem' }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm;
        }
        body {
            font-family: Arial, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 12px;
            color: #1e293b;
            background: #f1f5f9;
            margin: 0;
            padding: 20px 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .page-sheet {
            background: #ffffff;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 30px 35px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            box-sizing: border-box;
            position: relative;
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .page-sheet {
                width: 100%;
                min-height: auto;
                margin: 0;
                padding: 0;
                border-radius: 0;
                box-shadow: none;
            }
            .no-print {
                display: none !important;
            }
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #0a4d36;
            padding-bottom: 15px;
            margin-bottom: 18px;
        }
        .company-brand {
            display: flex;
            align-items: center;
            gap: 15px;
            max-width: 60%;
        }
        .company-logo {
            max-height: 70px;
            max-width: 140px;
            object-fit: contain;
        }
        .company-info h2 {
            font-size: 18px;
            font-weight: 800;
            margin: 0 0 3px 0;
            color: #0a4d36;
            text-transform: uppercase;
        }
        .company-info p {
            margin: 1px 0;
            font-size: 11px;
            color: #475569;
            line-height: 1.35;
        }

        .doc-badge-box {
            text-align: right;
            border: 1.5px solid #0a4d36;
            border-radius: 8px;
            padding: 10px 14px;
            background: #f8fafc;
            min-width: 190px;
        }
        .doc-title {
            font-size: 13px;
            font-weight: 800;
            color: #0a4d36;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .doc-number {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            font-family: 'Courier New', monospace;
            margin-bottom: 4px;
        }
        .doc-status {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 12px;
            text-transform: uppercase;
        }
        .status-PENDIENTE { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .status-APROBADO { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .status-FACTURADO { background: #e0f2fe; color: #075985; border: 1px solid #bae6fd; }
        .status-RECHAZADO { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .status-ANULADO { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 18px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
        }
        .meta-col p {
            margin: 3px 0;
            font-size: 11.5px;
            color: #334155;
        }
        .meta-col strong {
            color: #0f172a;
            font-weight: 700;
        }

        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 11px;
        }
        .table-items th {
            background: #0a4d36;
            color: #ffffff;
            font-weight: 700;
            text-align: left;
            padding: 7px 8px;
            border: 1px solid #0a4d36;
            text-transform: uppercase;
            font-size: 10.5px;
        }
        .table-items td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            color: #334155;
            vertical-align: middle;
        }
        .table-items tbody tr:nth-child(even) {
            background: #fbfcfd;
        }
        .table-items .text-right {
            text-align: right;
        }
        .table-items .text-center {
            text-align: center;
        }

        .totals-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .tax-breakdown {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 11px;
        }
        .tax-breakdown-title {
            font-weight: 700;
            color: #0a4d36;
            margin-bottom: 6px;
            text-transform: uppercase;
            font-size: 10px;
        }
        .tax-breakdown-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            color: #475569;
        }
        .tax-breakdown-row.total-iva {
            border-top: 1px dashed #cbd5e1;
            margin-top: 4px;
            padding-top: 4px;
            font-weight: 700;
            color: #0f172a;
        }

        .grand-total-box {
            width: 260px;
            border: 1.5px solid #0a4d36;
            border-radius: 6px;
            overflow: hidden;
        }
        .grand-total-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 12px;
            font-size: 11.5px;
            color: #334155;
            border-bottom: 1px solid #e2e8f0;
        }
        .grand-total-final {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #0a4d36;
            color: #ffffff;
            padding: 10px 12px;
            font-weight: 800;
            font-size: 15px;
        }

        .notes-box {
            border: 1px dashed #cbd5e1;
            background: #f8fafc;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 20px;
            font-size: 11px;
        }
        .notes-title {
            font-weight: 700;
            color: #334155;
            margin-bottom: 4px;
            text-transform: uppercase;
            font-size: 10px;
        }

        .validity-alert {
            background: #fefce8;
            border-left: 4px solid #eab308;
            padding: 8px 12px;
            font-size: 10.5px;
            color: #713f12;
            border-radius: 0 4px 4px 0;
            margin-bottom: 25px;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding: 0 30px;
        }
        .sig-block {
            text-align: center;
            width: 200px;
        }
        .sig-line {
            border-top: 1px solid #94a3b8;
            margin-bottom: 6px;
        }
        .sig-label {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
        }

        /* Floating action bar */
        .print-actions {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(8px);
            padding: 10px 16px;
            border-radius: 30px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            z-index: 9999;
        }
        .btn-print-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            border-radius: 20px;
            padding: 8px 16px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
        }
        .btn-print-primary {
            background: #10b981;
            color: #ffffff;
        }
        .btn-print-primary:hover {
            background: #059669;
            color: #ffffff;
        }
        .btn-print-secondary {
            background: #334155;
            color: #f8fafc;
        }
        .btn-print-secondary:hover {
            background: #475569;
            color: #ffffff;
        }
    </style>
</head>
<body>

    <!-- Floating Actions (Screen Only) -->
    <div class="print-actions no-print">
        <button type="button" class="btn-print-action btn-print-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Imprimir Presupuesto
        </button>
        <button type="button" class="btn-print-action btn-print-secondary" onclick="window.close()">
            <i class="fas fa-times"></i> Cerrar
        </button>
    </div>

    <div class="page-sheet">
        <!-- Header -->
        <div class="header-top">
            <div class="company-brand">
                @if(!empty($empresa->emp_logo) && file_exists(public_path('img/'.$empresa->emp_logo)))
                    <img src="{{ asset('img/'.$empresa->emp_logo) }}" alt="{{ $empresa->emp_nombre }}" class="company-logo">
                @endif
                <div class="company-info">
                    <h2>{{ $empresa->emp_nombre ?? 'SoftSystem' }}</h2>
                    @if(!empty($empresa->emp_descripcion))
                        <p>{{ $empresa->emp_descripcion }}</p>
                    @endif
                    <p><strong>RUC:</strong> {{ $empresa->emp_ruc ?? '-' }}</p>
                    <p><strong>Dirección:</strong> {{ $empresa->emp_direccion ?? '-' }}</p>
                    <p><strong>Tel/Cel:</strong> {{ $empresa->emp_celular ?? ($empresa->emp_telefono ?? '-') }}</p>
                </div>
            </div>

            <div class="doc-badge-box">
                <div class="doc-title">Presupuesto de Venta</div>
                <div class="doc-number">Nº {{ str_pad($presupuesto->pre_numero, 6, '0', STR_PAD_LEFT) }}</div>
                <div>
                    <span class="doc-status status-{{ $presupuesto->estado }}">
                        {{ $presupuesto->estado }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Meta Information -->
        <div class="meta-grid">
            <div class="meta-col">
                <p><strong>Cliente:</strong> {{ $presupuesto->cliente->cliente_nombre ?? 'Consumidor Final' }}</p>
                <p><strong>RUC / CI:</strong> {{ $presupuesto->cliente->cliente_ruc ?? ($presupuesto->cliente->cliente_ci ?? 'Sin documento') }}</p>
                <p><strong>Teléfono:</strong> {{ $presupuesto->cliente->cliente_cel ?? ($presupuesto->cliente->cliente_telef ?? '-') }}</p>
                <p><strong>Dirección:</strong> {{ $presupuesto->cliente->cliente_direccion ?? '-' }}</p>
            </div>
            <div class="meta-col">
                <p><strong>Fecha de Emisión:</strong> {{ date('d/m/Y H:i', strtotime($presupuesto->pre_fecha)) }}</p>
                <p><strong>Validez:</strong> {{ $presupuesto->pre_validez_dias }} días</p>
                <p><strong>Fecha de Vencimiento:</strong> {{ !empty($presupuesto->pre_vencimiento) ? date('d/m/Y', strtotime($presupuesto->pre_vencimiento)) : '-' }}</p>
                <p><strong>Condición:</strong> {{ $presupuesto->pre_tipo == 2 ? 'Crédito' : 'Contado' }}</p>
                <p><strong>Sucursal:</strong> {{ $presupuesto->sucursal->suc_desc ?? 'Casa Central' }}</p>
                <p><strong>Emitido por:</strong> {{ $presupuesto->usuario->nom_usuarios ?? 'Usuario' }}</p>
            </div>
        </div>

        <!-- Items Table -->
        <table class="table-items">
            <thead>
                <tr>
                    <th style="width: 35px;" class="text-center">#</th>
                    <th style="width: 100px;">Código</th>
                    <th>Descripción del Producto / Servicio</th>
                    <th style="width: 45px;" class="text-center">IVA</th>
                    <th style="width: 55px;" class="text-right">Cant.</th>
                    <th style="width: 90px;" class="text-right">Precio Unit.</th>
                    <th style="width: 80px;" class="text-right">Desc.</th>
                    <th style="width: 95px;" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($presupuesto->detalles as $idx => $det)
                    @php
                        $art = $det->articulo;
                        $desc = !empty($det->descripcion_libre) ? $det->descripcion_libre : ($art->producto_nombre ?? 'Artículo');
                        $ivaBadge = ($art && isset($art->iva)) ? ($art->iva == 0 ? 'Exenta' : $art->iva.'%') : '10%';
                    @endphp
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>{{ $art->producto_c_barra ?? $det->ARTICULOS_cod }}</td>
                        <td>
                            <strong>{{ $desc }}</strong>
                            @if(!empty($art->uni_desc))
                                <span style="font-size: 9.5px; color: #64748b;">({{ $art->uni_desc }})</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $ivaBadge }}</td>
                        <td class="text-right">{{ number_format($det->pre_det_cantidad, (fmod($det->pre_det_cantidad, 1) !== 0.0 ? 2 : 0), ',', '.') }}</td>
                        <td class="text-right">{{ number_format($det->pre_det_precio, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($det->pre_det_descuento, 0, ',', '.') }}</td>
                        <td class="text-right font-weight-bold">{{ number_format($det->pre_det_subtotal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-3">No hay renglones registrados en este presupuesto.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Totals & Tax Liquidations -->
        <div class="totals-section">
            <div class="tax-breakdown">
                <div class="tax-breakdown-title">Liquidación del IVA</div>
                <div class="tax-breakdown-row">
                    <span>Total Exentas:</span>
                    <span>Gs. {{ number_format($presupuesto->total_exenta, 0, ',', '.') }}</span>
                </div>
                <div class="tax-breakdown-row">
                    <span>IVA 5% (Liq.):</span>
                    <span>Gs. {{ number_format($presupuesto->total_iva5, 0, ',', '.') }}</span>
                </div>
                <div class="tax-breakdown-row">
                    <span>IVA 10% (Liq.):</span>
                    <span>Gs. {{ number_format($presupuesto->total_iva10, 0, ',', '.') }}</span>
                </div>
                <div class="tax-breakdown-row total-iva">
                    <span>Total Impuesto IVA:</span>
                    <span>Gs. {{ number_format($presupuesto->total_iva, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="grand-total-box">
                <div class="grand-total-row">
                    <span>Subtotal:</span>
                    <strong>Gs. {{ number_format($presupuesto->subtotal, 0, ',', '.') }}</strong>
                </div>
                @if($presupuesto->descuento > 0)
                    <div class="grand-total-row" style="color: #dc2626;">
                        <span>Descuento General:</span>
                        <strong>- Gs. {{ number_format($presupuesto->descuento, 0, ',', '.') }}</strong>
                    </div>
                @endif
                <div class="grand-total-final">
                    <span>TOTAL PRESUPUESTO:</span>
                    <span>Gs. {{ number_format($presupuesto->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Observations -->
        @if(!empty($presupuesto->observaciones))
            <div class="notes-box">
                <div class="notes-title">Observaciones / Condiciones Comerciales:</div>
                <p style="margin: 0; white-space: pre-line; color: #334155;">{{ $presupuesto->observaciones }}</p>
            </div>
        @endif

        <div class="validity-alert">
            <i class="fas fa-info-circle mr-1"></i>
            <strong>Cláusula de Validez:</strong> Los precios y condiciones detallados en este presupuesto son válidos por <strong>{{ $presupuesto->pre_validez_dias }} días</strong> a partir de su emisión (hasta el {{ !empty($presupuesto->pre_vencimiento) ? date('d/m/Y', strtotime($presupuesto->pre_vencimiento)) : '-' }}). Pasado dicho plazo, los costos quedan sujetos a revisión y confirmación de stock.
        </div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">Firma Responsable / Vendedor</div>
            </div>
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">Conformidad del Cliente</div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('autoprint') === '1') {
                setTimeout(function () {
                    window.print();
                }, 400);
            }
        });
    </script>
</body>
</html>
