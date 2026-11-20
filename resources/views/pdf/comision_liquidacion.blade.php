<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liquidación de Comisiones {{ $liquidacion->codigo_liquidacion }} - {{ $empresa->emp_nombre ?? 'SoftSystem' }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 11px;
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
            min-height: 290mm;
            margin: 0 auto;
            padding: 26px 32px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            box-sizing: border-box;
        }
        @media print {
            body { background: #ffffff; padding: 0; }
            .page-sheet { width: 100%; min-height: auto; margin: 0; padding: 0; border-radius: 0; box-shadow: none; }
            .no-print { display: none !important; }
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2.5px solid #0a4d36;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }
        .company-brand { display: flex; align-items: center; gap: 14px; max-width: 60%; }
        .company-logo { max-height: 62px; max-width: 130px; object-fit: contain; }
        .company-info h2 { font-size: 17px; font-weight: 800; margin: 0 0 2px 0; color: #0a4d36; text-transform: uppercase; }
        .company-info p { margin: 1px 0; font-size: 10.5px; color: #475569; }
        .doc-badge-box {
            text-align: right;
            border: 2px solid #0a4d36;
            border-radius: 8px;
            padding: 8px 14px;
            background: #eaf3ef;
            min-width: 210px;
        }
        .doc-title { font-size: 11px; font-weight: 800; color: #0a4d36; text-transform: uppercase; }
        .doc-number { font-size: 18px; font-weight: 900; color: #0f172a; font-family: 'Courier New', monospace; margin: 2px 0; }
        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            padding: 10px 14px;
            margin-bottom: 14px;
        }
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-bottom: 14px;
        }
        .sum-card {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px 10px;
            background: #ffffff;
        }
        .sum-title { font-size: 9.5px; font-weight: 800; color: #64748b; text-transform: uppercase; }
        .sum-val { font-size: 13.5px; font-weight: 900; color: #0a4d36; margin-top: 2px; }
        .sum-sub { font-size: 9.5px; color: #475569; }
        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 10px;
        }
        .table-items th {
            background: #0a4d36;
            color: #ffffff;
            padding: 6px 7px;
            text-align: left;
            text-transform: uppercase;
            font-size: 9.5px;
        }
        .table-items td {
            padding: 5px 7px;
            border: 1px solid #e2e8f0;
        }
        .totals-wrap {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
        }
        .total-box {
            width: 280px;
            border: 2px solid #0a4d36;
            border-radius: 7px;
            overflow: hidden;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .total-final {
            display: flex;
            justify-content: space-between;
            background: #0a4d36;
            color: #ffffff;
            padding: 9px 12px;
            font-weight: 900;
            font-size: 14px;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding: 0 25px;
        }
        .sig-block { text-align: center; width: 220px; }
        .sig-line { border-top: 1.5px solid #475569; margin-bottom: 5px; }
        .sig-label { font-size: 10px; color: #475569; text-transform: uppercase; font-weight: 700; }
        .print-actions {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 8px;
            background: rgba(15, 23, 42, 0.92);
            padding: 10px 16px;
            border-radius: 30px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            z-index: 9999;
        }
        .btn-action {
            border: none;
            border-radius: 20px;
            padding: 7px 14px;
            font-weight: 700;
            font-size: 12.5px;
            cursor: pointer;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="print-actions no-print">
        <button type="button" class="btn-action" style="background: #10b981;" onclick="window.print()">
            <i class="fas fa-print"></i> Imprimir Recibo de Liquidación
        </button>
        <button type="button" class="btn-action" style="background: #334155;" onclick="window.close()">
            <i class="fas fa-times"></i> Cerrar
        </button>
    </div>

    <div class="page-sheet">
        <div class="header-top">
            <div class="company-brand">
                @if(!empty($empresa->emp_logo) && file_exists(public_path('img/'.$empresa->emp_logo)))
                    <img src="{{ asset('img/'.$empresa->emp_logo) }}" alt="{{ $empresa->emp_nombre }}" class="company-logo">
                @endif
                <div class="company-info">
                    <h2>{{ $empresa->emp_nombre ?? 'LIQUIDACIÓN DE COMISIONES' }}</h2>
                    <p><strong>RUC:</strong> {{ $empresa->emp_ruc ?? '-' }} | <strong>Sucursal:</strong> {{ $liquidacion->sucursal->suc_desc ?? 'Casa Central' }}</p>
                    <p><strong>Dirección:</strong> {{ $empresa->emp_direccion ?? '-' }} | <strong>Tel/Cel:</strong> {{ $empresa->emp_celular ?? ($empresa->emp_telefono ?? '-') }}</p>
                </div>
            </div>
            <div class="doc-badge-box">
                <div class="doc-title"><i class="fas fa-file-invoice-dollar"></i> Recibo de Liquidación de Comisiones</div>
                <div class="doc-number">{{ $liquidacion->codigo_liquidacion }}</div>
                <div style="font-size: 10.5px;">
                    <strong>Fecha Emisión:</strong> {{ date('d/m/Y H:i', strtotime($liquidacion->fecha_liquidacion)) }}
                </div>
            </div>
        </div>

        <div class="meta-grid">
            <div>
                <div style="margin: 2px 0;"><strong>Técnico / Vendedor:</strong> <span style="font-size: 12.5px; font-weight: 800; color: #0a4d36;">{{ $liquidacion->empleado->nom_usuarios ?? 'Empleado' }}</span></div>
                <div style="margin: 2px 0;"><strong>Usuario:</strong> {{ $liquidacion->empleado->user_usuarios ?? '-' }} | <strong>Rol:</strong> {{ $liquidacion->empleado->roles->nom_rol ?? 'Operador' }}</div>
            </div>
            <div>
                <div style="margin: 2px 0;"><strong>Período Liquidado:</strong> Del {{ date('d/m/Y', strtotime($liquidacion->fecha_desde)) }} al {{ date('d/m/Y', strtotime($liquidacion->fecha_hasta)) }}</div>
                <div style="margin: 2px 0;"><strong>Liquidado por:</strong> {{ $liquidacion->liquidador->nom_usuarios ?? 'Administración' }} | <strong>Estado:</strong> {{ $liquidacion->estado }}</div>
            </div>
        </div>

        <!-- Resumen por Concepto -->
        <div class="summary-cards">
            <div class="sum-card">
                <div class="sum-title">1. Servicio Técnico (Taller)</div>
                <div class="sum-val">Gs. {{ number_format($liquidacion->comision_taller, 0, ',', '.') }}</div>
                <div class="sum-sub">{{ $liquidacion->cant_reparaciones }} órdenes | Base: Gs. {{ number_format($liquidacion->total_base_taller, 0, ',', '.') }}</div>
            </div>
            <div class="sum-card">
                <div class="sum-title">2. Equipos IMEI / Canje</div>
                <div class="sum-val">Gs. {{ number_format($liquidacion->comision_imei, 0, ',', '.') }}</div>
                <div class="sum-sub">{{ $liquidacion->cant_equipos_imei }} equipos | Base: Gs. {{ number_format($liquidacion->total_base_imei, 0, ',', '.') }}</div>
            </div>
            <div class="sum-card">
                <div class="sum-title">3. Ventas POS</div>
                <div class="sum-val">Gs. {{ number_format($liquidacion->comision_ventas, 0, ',', '.') }}</div>
                <div class="sum-sub">{{ $liquidacion->cant_ventas_pos }} ventas | Base: Gs. {{ number_format($liquidacion->total_base_ventas, 0, ',', '.') }}</div>
            </div>
            <div class="sum-card">
                <div class="sum-title">4. Cobranzas Cuotas</div>
                <div class="sum-val">Gs. {{ number_format($liquidacion->comision_cobranzas, 0, ',', '.') }}</div>
                <div class="sum-sub">{{ $liquidacion->cant_cobranzas }} cobros | Base: Gs. {{ number_format($liquidacion->total_base_cobranzas, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Tabla Detallada de Operaciones -->
        <table class="table-items">
            <thead>
                <tr>
                    <th style="width: 30px;">#</th>
                    <th style="width: 75px;">Módulo</th>
                    <th style="width: 85px;">Comprobante</th>
                    <th style="width: 75px;">Fecha</th>
                    <th>Descripción de la Operación</th>
                    <th style="width: 85px; text-align: right;">Base Comis.</th>
                    <th style="width: 50px; text-align: right;">%</th>
                    <th style="width: 85px; text-align: right;">Comisión</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($liquidacion->detalles as $idx => $det)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td><strong>{{ $det->origen_modulo }}</strong></td>
                        <td style="font-family: monospace; font-weight: 700;">{{ $det->codigo_comprobante }}</td>
                        <td>{{ !empty($det->fecha_operacion) ? date('d/m/Y', strtotime($det->fecha_operacion)) : '-' }}</td>
                        <td>{{ $det->descripcion }}</td>
                        <td style="text-align: right;">Gs. {{ number_format($det->base_comisionable, 0, ',', '.') }}</td>
                        <td style="text-align: right;">{{ number_format($det->porcentaje_aplicado, 1, ',', '.') }}%</td>
                        <td style="text-align: right; font-weight: 700;">Gs. {{ number_format($det->monto_comision, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totales Finales -->
        <div class="totals-wrap">
            <div style="flex: 1; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px 12px; background: #f8fafc;">
                <div style="font-weight: 700; font-size: 10px; color: #0a4d36; text-transform: uppercase; margin-bottom: 4px;">
                    Constancia de Conformidad y Pago
                </div>
                <p style="margin: 3px 0; font-size: 10px; color: #334155;">
                    Recibí conforme de <strong>{{ $empresa->emp_nombre ?? 'la Empresa' }}</strong> la suma neta de <strong>Gs. {{ number_format($liquidacion->total_neto_pagar, 0, ',', '.') }}</strong> en concepto de liquidación total de comisiones por las operaciones detalladas en el período indicado.
                </p>
                @if(!empty($liquidacion->observaciones))
                    <p style="margin: 4px 0 0; font-size: 10px;"><strong>Observaciones:</strong> {{ $liquidacion->observaciones }}</p>
                @endif
            </div>

            <div class="total-box">
                <div class="total-row">
                    <span>Subtotal Comisiones:</span>
                    <strong>Gs. {{ number_format($liquidacion->subtotal_comisiones, 0, ',', '.') }}</strong>
                </div>
                @if ($liquidacion->bono_premio > 0)
                    <div class="total-row" style="color: #15803d;">
                        <span>(+) Bono / Premio {{ $liquidacion->motivo_bono ? '('.$liquidacion->motivo_bono.')' : '' }}:</span>
                        <strong>+ Gs. {{ number_format($liquidacion->bono_premio, 0, ',', '.') }}</strong>
                    </div>
                @endif
                @if ($liquidacion->descuento_adelanto > 0)
                    <div class="total-row" style="color: #dc2626;">
                        <span>(-) Adelantos / Descuentos {{ $liquidacion->motivo_descuento ? '('.$liquidacion->motivo_descuento.')' : '' }}:</span>
                        <strong>- Gs. {{ number_format($liquidacion->descuento_adelanto, 0, ',', '.') }}</strong>
                    </div>
                @endif
                <div class="total-final">
                    <span>NETO A COBRAR:</span>
                    <span>Gs. {{ number_format($liquidacion->total_neto_pagar, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="signatures">
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">Firma Administración / Caja</div>
                <div style="font-size: 9.5px; color: #64748b;">{{ $liquidacion->liquidador->nom_usuarios ?? '' }}</div>
            </div>
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">Recibí Conforme (Empleado)</div>
                <div style="font-size: 9.5px; color: #0f172a; font-weight: 700;">{{ $liquidacion->empleado->nom_usuarios ?? '' }}</div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('autoprint') === '1') {
                setTimeout(function () { window.print(); }, 400);
            }
        });
    </script>
</body>
</html>
