<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Certificado de Entrega y Garantía {{ $reparacion->codigo_orden }} - {{ $empresa->emp_nombre ?? 'SoftSystem' }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 11.5px;
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
            .page-sheet {
                width: 100%;
                min-height: auto;
                margin: 0;
                padding: 0;
                border-radius: 0;
                box-shadow: none;
            }
            .no-print { display: none !important; }
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2.5px solid #0a4d36;
            padding-bottom: 14px;
            margin-bottom: 16px;
        }
        .company-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            max-width: 60%;
        }
        .company-logo {
            max-height: 64px;
            max-width: 130px;
            object-fit: contain;
        }
        .company-info h2 {
            font-size: 17px;
            font-weight: 800;
            margin: 0 0 2px 0;
            color: #0a4d36;
            text-transform: uppercase;
        }
        .company-info p {
            margin: 1px 0;
            font-size: 10.5px;
            color: #475569;
        }
        .doc-badge-box {
            text-align: right;
            border: 2px solid #0a4d36;
            border-radius: 8px;
            padding: 10px 14px;
            background: #eaf3ef;
            min-width: 210px;
        }
        .doc-title {
            font-size: 11.5px;
            font-weight: 800;
            color: #0a4d36;
            text-transform: uppercase;
        }
        .doc-number {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            font-family: 'Courier New', monospace;
            margin: 2px 0;
        }
        .warranty-banner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 2px solid #0a4d36;
            background: linear-gradient(90deg, #eaf3ef 0%, #ffffff 100%);
            border-radius: 8px;
            padding: 10px 16px;
            margin-bottom: 14px;
        }
        .warranty-banner.no-warranty {
            border-color: #94a3b8;
            background: #f8fafc;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 12px;
        }
        .section-box {
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            margin-bottom: 12px;
            overflow: hidden;
        }
        .section-header {
            background: #0a4d36;
            color: #ffffff;
            padding: 5px 12px;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            display: flex;
            justify-content: space-between;
        }
        .section-body {
            padding: 10px 12px;
        }
        .info-line {
            margin: 3px 0;
            font-size: 11px;
        }
        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 11px;
        }
        .table-items th {
            background: #0a4d36;
            color: #ffffff;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        .table-items td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
        }
        .totals-grid {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 14px;
        }
        .total-card {
            width: 270px;
            border: 1.5px solid #0a4d36;
            border-radius: 6px;
            overflow: hidden;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .total-final {
            display: flex;
            justify-content: space-between;
            background: #0a4d36;
            color: #ffffff;
            padding: 8px 10px;
            font-weight: 800;
            font-size: 13.5px;
        }
        .terms-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 9px 12px;
            font-size: 9.5px;
            color: #334155;
            line-height: 1.42;
            white-space: pre-line;
            margin-bottom: 18px;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 32px;
            padding: 0 25px;
        }
        .sig-block {
            text-align: center;
            width: 215px;
        }
        .sig-line {
            border-top: 1px solid #475569;
            margin-bottom: 5px;
        }
        .sig-label {
            font-size: 10px;
            color: #475569;
            text-transform: uppercase;
            font-weight: 700;
        }
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
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
    </style>
</head>
<body>
    <div class="print-actions no-print">
        <button type="button" class="btn-action" style="background: #10b981;" onclick="window.print()">
            <i class="fas fa-print"></i> Imprimir Certificado de Garantía
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
                    <h2>{{ $empresa->emp_nombre ?? 'SERVICIO TÉCNICO ESPECIALIZADO' }}</h2>
                    @if(!empty($empresa->emp_descripcion))
                        <p>{{ $empresa->emp_descripcion }}</p>
                    @endif
                    <p><strong>RUC:</strong> {{ $empresa->emp_ruc ?? '-' }} | <strong>Sucursal:</strong> {{ $reparacion->sucursal->suc_desc ?? 'Casa Central' }}</p>
                    <p><strong>Dirección:</strong> {{ $empresa->emp_direccion ?? '-' }} | <strong>Tel/Cel:</strong> {{ $empresa->emp_celular ?? ($empresa->emp_telefono ?? '-') }}</p>
                </div>
            </div>
            <div class="doc-badge-box">
                <div class="doc-title"><i class="fas fa-shield-alt"></i> Comprobante de Entrega y Garantía</div>
                <div class="doc-number">{{ $reparacion->codigo_orden }}</div>
                <div style="font-size: 10.5px;">
                    <strong>Fecha Entrega:</strong> {{ !empty($reparacion->fecha_entrega) ? date('d/m/Y H:i', strtotime($reparacion->fecha_entrega)) : date('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        <!-- Banner Destacado de Cobertura de Garantía -->
        @if ($reparacion->tiene_garantia && $reparacion->dias_garantia > 0)
            <div class="warranty-banner">
                <div>
                    <div style="font-size: 13.5px; font-weight: 800; color: #0a4d36; text-transform: uppercase;">
                        <i class="fas fa-award"></i> CERTIFICADO DE GARANTÍA DE REPARACIÓN ACTIVA
                    </div>
                    <div style="font-size: 11px; color: #334155; margin-top: 2px;">
                        Cobertura de <strong>{{ $reparacion->dias_garantia }} días corridos</strong> sobre el trabajo técnico realizado y los repuestos detallados.
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 10px; color: #475569; text-transform: uppercase; font-weight: 700;">Válida hasta el:</div>
                    <div style="font-size: 16px; font-weight: 900; color: #0a4d36; font-family: 'Courier New', monospace;">
                        {{ !empty($reparacion->fecha_vencimiento_garantia) ? date('d/m/Y', strtotime($reparacion->fecha_vencimiento_garantia)) : date('d/m/Y', strtotime('+'.$reparacion->dias_garantia.' days')) }}
                    </div>
                </div>
            </div>
        @else
            <div class="warranty-banner no-warranty">
                <div>
                    <div style="font-size: 13px; font-weight: 800; color: #475569;">
                        ENTREGA DE EQUIPO SIN COBERTURA DE GARANTÍA
                    </div>
                    <div style="font-size: 10.5px; color: #64748b;">
                        Estado: {{ $reparacion->estado }} — El equipo se entrega bajo las condiciones informadas en recepción/diagnóstico.
                    </div>
                </div>
            </div>
        @endif

        <!-- Datos de Cliente y Equipo -->
        <div class="grid-2">
            <div class="section-box" style="margin-bottom: 0;">
                <div class="section-header">
                    <span>Datos del Cliente y Retiro</span>
                </div>
                <div class="section-body">
                    <div class="info-line"><strong>Titular:</strong> {{ $reparacion->cliente->cliente_nombre ?? 'Cliente' }} ({{ $reparacion->cliente->cliente_ruc ?? ($reparacion->cliente->cliente_ci ?? '-') }})</div>
                    <div class="info-line"><strong>Teléfono:</strong> {{ $reparacion->cliente->cliente_cel ?? ($reparacion->cliente->cliente_telef ?? '-') }}</div>
                    <div class="info-line"><strong>Retirado por:</strong> {{ $reparacion->retirado_por_nombre ?: ($reparacion->cliente->cliente_nombre ?? '-') }} (CI: {{ $reparacion->retirado_por_ci ?: ($reparacion->cliente->cliente_ci ?? '-') }})</div>
                    <div class="info-line"><strong>Entregado por:</strong> {{ $reparacion->usuarioEntrega->nom_usuarios ?? ($reparacion->usuarioRecepcion->nom_usuarios ?? 'Taller') }}</div>
                </div>
            </div>

            <div class="section-box" style="margin-bottom: 0;">
                <div class="section-header">
                    <span>Datos del Dispositivo</span>
                </div>
                <div class="section-body">
                    <div class="info-line"><strong>Equipo:</strong> {{ $reparacion->tipo_equipo }} <strong>{{ $reparacion->marca }} {{ $reparacion->modelo }}</strong> ({{ $reparacion->color ?: '-' }})</div>
                    <div class="info-line"><strong>IMEI / Serie:</strong> <span style="font-family: monospace; font-weight: 700;">{{ $reparacion->imei_serie ?: 'No especificado' }}</span></div>
                    <div class="info-line"><strong>Fecha de Ingreso:</strong> {{ date('d/m/Y H:i', strtotime($reparacion->fecha_recepcion)) }}</div>
                    <div class="info-line"><strong>Técnico Responsable:</strong> {{ $reparacion->tecnico->nom_usuarios ?? 'Servicio Técnico' }}</div>
                </div>
            </div>
        </div>

        <!-- Informe Técnico: Falla, Diagnóstico y Trabajo Realizado -->
        <div class="section-box">
            <div class="section-header">
                <span><i class="fas fa-microchip mr-1"></i> Informe Técnico de Diagnóstico y Reparación Efectuada</span>
                <span>Estado: {{ $reparacion->estado }}</span>
            </div>
            <div class="section-body">
                <div class="info-line"><strong>Falla Inicial Reportada:</strong> {{ $reparacion->falla_reportada }}</div>
                <div class="info-line" style="margin-top: 5px;"><strong>Diagnóstico Técnico:</strong> {{ $reparacion->diagnostico_tecnico ?: 'Revisión general de hardware y componentes.' }}</div>
                <div class="info-line" style="margin-top: 5px; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 6px 10px; border-radius: 5px;">
                    <strong>Solución / Trabajo Aplicado:</strong> {{ $reparacion->solucion_aplicada ?: 'Reparación completada y verificada en banco de pruebas.' }}
                </div>
            </div>
        </div>

        <!-- Tabla de Repuestos y Servicios -->
        <table class="table-items">
            <thead>
                <tr>
                    <th style="width: 35px;">#</th>
                    <th>Concepto / Repuesto Reemplazado</th>
                    <th style="width: 90px; text-align: center;">Garantía</th>
                    <th style="width: 65px; text-align: right;">Cant.</th>
                    <th style="width: 100px; text-align: right;">Precio Unit.</th>
                    <th style="width: 105px; text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $itemIndex = 1; @endphp
                @if ($reparacion->costo_mano_obra > 0)
                    <tr>
                        <td>{{ $itemIndex++ }}</td>
                        <td><strong>Mano de Obra — Servicio Técnico Especializado ({{ $reparacion->marca }} {{ $reparacion->modelo }})</strong></td>
                        <td style="text-align: center;">{{ $reparacion->tiene_garantia ? $reparacion->dias_garantia . ' días' : 'S/G' }}</td>
                        <td style="text-align: right;">1</td>
                        <td style="text-align: right;">Gs. {{ number_format($reparacion->costo_mano_obra, 0, ',', '.') }}</td>
                        <td style="text-align: right; font-weight: 700;">Gs. {{ number_format($reparacion->costo_mano_obra, 0, ',', '.') }}</td>
                    </tr>
                @endif
                @foreach ($reparacion->repuestos as $rep)
                    <tr>
                        <td>{{ $itemIndex++ }}</td>
                        <td>
                            <strong>{{ $rep->descripcion }}</strong>
                            @if(!empty($rep->observacion))
                                <span style="font-size: 9.5px; color: #64748b;">({{ $rep->observacion }})</span>
                            @endif
                        </td>
                        <td style="text-align: center;">{{ $rep->garantia_repuesto_dias > 0 ? $rep->garantia_repuesto_dias . ' días' : 'S/G' }}</td>
                        <td style="text-align: right;">{{ number_format($rep->cantidad, (fmod($rep->cantidad, 1) !== 0.0 ? 2 : 0), ',', '.') }}</td>
                        <td style="text-align: right;">Gs. {{ number_format($rep->precio_unitario, 0, ',', '.') }}</td>
                        <td style="text-align: right; font-weight: 700;">Gs. {{ number_format($rep->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                @if ($itemIndex === 1)
                    <tr>
                        <td colspan="6" style="text-align: center; color: #64748b;">Servicio de diagnóstico / revisión técnica ({{ $reparacion->es_reingreso_garantia ? 'Cubierto por Garantía sin cargo' : 'Sin cargos adicionales' }}).</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Totales y Pagos -->
        <div class="totals-grid">
            <div style="flex: 1; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px 12px; background: #f8fafc;">
                <div style="font-weight: 700; font-size: 10.5px; color: #0a4d36; text-transform: uppercase; margin-bottom: 4px;">
                    Historial de Pagos y Abonos de la Orden
                </div>
                @forelse ($reparacion->pagos as $p)
                    <div style="display: flex; justify-content: space-between; font-size: 10.5px; padding: 2px 0; border-bottom: 1px dashed #e2e8f0;">
                        <span>{{ date('d/m/Y H:i', strtotime($p->fecha_pago)) }} — {{ $p->tipo_pago }} ({{ $p->observacion ?: 'Pago' }})</span>
                        <strong>Gs. {{ number_format($p->monto, 0, ',', '.') }}</strong>
                    </div>
                @empty
                    <div style="font-size: 10.5px; color: #64748b;">No se registran cobros monetarios en esta orden.</div>
                @endforelse
                @if(!empty($reparacion->conformidad_cliente))
                    <div style="margin-top: 6px; font-size: 10.5px; color: #334155;">
                        <strong>Nota de Entrega:</strong> {{ $reparacion->conformidad_cliente }}
                    </div>
                @endif
            </div>

            <div class="total-card">
                <div class="total-row">
                    <span>Mano de Obra:</span>
                    <strong>Gs. {{ number_format($reparacion->costo_mano_obra, 0, ',', '.') }}</strong>
                </div>
                <div class="total-row">
                    <span>Total Repuestos:</span>
                    <strong>Gs. {{ number_format($reparacion->total_repuestos, 0, ',', '.') }}</strong>
                </div>
                @if ($reparacion->descuento > 0)
                    <div class="total-row" style="color: #dc2626;">
                        <span>Descuento:</span>
                        <strong>- Gs. {{ number_format($reparacion->descuento, 0, ',', '.') }}</strong>
                    </div>
                @endif
                <div class="total-final">
                    <span>TOTAL SERVICIO:</span>
                    <span>Gs. {{ number_format($reparacion->total_general, 0, ',', '.') }}</span>
                </div>
                <div class="total-row" style="background: #f0fdf4;">
                    <span>Total Abonado:</span>
                    <strong style="color: #15803d;">Gs. {{ number_format($reparacion->total_pagado, 0, ',', '.') }}</strong>
                </div>
                <div class="total-row" style="border-bottom: none;">
                    <span>Saldo Pendiente:</span>
                    <strong style="color: {{ $reparacion->saldo_pendiente > 0 ? '#b91c1c' : '#15803d' }};">
                        Gs. {{ number_format($reparacion->saldo_pendiente, 0, ',', '.') }}
                    </strong>
                </div>
            </div>
        </div>

        <!-- Términos de Garantía -->
        <div class="terms-box">
            <strong style="display: block; margin-bottom: 3px; color: #0a4d36; text-transform: uppercase;">
                Términos y Condiciones de Cobertura de Garantía:
            </strong>
            {{ $reparacion->condiciones_garantia ?: ($config['condiciones_garantia'] ?? '') }}
        </div>

        <!-- Firmas -->
        <div class="signatures">
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">Firma y Sello Servicio Técnico</div>
                <div style="font-size: 9.5px; color: #64748b;">{{ $empresa->emp_nombre ?? 'Taller' }}</div>
            </div>
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">Recibí Conforme (Equipo Funcionando)</div>
                <div style="font-size: 9.5px; color: #64748b;">{{ $reparacion->retirado_por_nombre ?: ($reparacion->cliente->cliente_nombre ?? '') }} — CI: {{ $reparacion->retirado_por_ci ?: ($reparacion->cliente->cliente_ci ?? '') }}</div>
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
