<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orden de Recepción {{ $reparacion->codigo_orden }} - {{ $empresa->emp_nombre ?? 'SoftSystem' }}</title>
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
            padding: 24px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            box-sizing: border-box;
        }
        .ticket-sheet {
            background: #ffffff;
            width: 80mm;
            margin: 0 auto;
            padding: 10px 12px;
            border-radius: 6px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            box-sizing: border-box;
            font-size: 11px;
        }
        @media print {
            body { background: #ffffff; padding: 0; }
            .page-sheet, .ticket-sheet {
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
            border-bottom: 2px solid #0a4d36;
            padding-bottom: 12px;
            margin-bottom: 14px;
        }
        .company-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            max-width: 62%;
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
            border: 1.5px solid #0a4d36;
            border-radius: 8px;
            padding: 8px 14px;
            background: #f8fafc;
            min-width: 190px;
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
            margin: 3px 0;
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
            letter-spacing: 0.4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .section-body {
            padding: 10px 12px;
            background: #ffffff;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 8px;
        }
        .info-line {
            margin: 3px 0;
            font-size: 11px;
        }
        .info-line strong {
            color: #0f172a;
        }
        .checklist-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
        }
        .chk-pill {
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 4px 7px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
        }
        .chk-ok { color: #15803d; font-weight: 700; }
        .chk-fail { color: #b91c1c; font-weight: 700; }
        .chk-na { color: #64748b; }
        .pattern-box {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 1px dashed #94a3b8;
            border-radius: 6px;
            padding: 6px 10px;
            background: #f8fafc;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px dashed #e2e8f0;
        }
        .terms-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 9.5px;
            color: #475569;
            line-height: 1.4;
            white-space: pre-line;
            margin-bottom: 16px;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 28px;
            padding: 0 25px;
        }
        .sig-block {
            text-align: center;
            width: 210px;
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
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-green { background: #10b981; color: #fff; }
        .btn-blue { background: #0284c7; color: #fff; }
        .btn-dark { background: #334155; color: #fff; }
    </style>
</head>
<body>
    @php
        $chkLabels = [
            'enciende'          => 'Enciende',
            'pantalla_sana'     => 'Pantalla sana',
            'tactil_funciona'   => 'Táctil OK',
            'botones_ok'        => 'Botones físicos',
            'camara_delantera'  => 'Cámara frontal',
            'camara_trasera'    => 'Cámara trasera',
            'carga_ok'          => 'Pin de carga',
            'altavoz_mic'       => 'Audio / Mic.',
            'wifi_senal'        => 'WiFi / Señal',
            'lector_huella'     => 'Huella / FaceID',
            'bandeja_sim'       => 'Bandeja SIM',
            'tapa_trasera_sana' => 'Tapa trasera',
            'rastros_humedad'   => 'Sin humedad',
            'bateria_hinchada'  => 'Batería normal',
        ];
        $chkData = is_array($reparacion->checklist_recepcion) ? $reparacion->checklist_recepcion : [];
    @endphp

    <div class="print-actions no-print">
        <a href="{{ route('reparacion.pdf.orden', ['id' => $reparacion->id, 'formato' => $formato === 'ticket' ? 'a4' : 'ticket']) }}" class="btn-action btn-blue">
            <i class="fas fa-exchange-alt"></i> Cambiar a {{ $formato === 'ticket' ? 'Hoja A4' : 'Ticket 80mm' }}
        </a>
        <a href="{{ route('reparacion.pdf.etiqueta', $reparacion->id) }}" target="_blank" class="btn-action btn-dark">
            <i class="fas fa-tag"></i> Etiqueta Equipo
        </a>
        <button type="button" class="btn-action btn-green" onclick="window.print()">
            <i class="fas fa-print"></i> Imprimir Orden
        </button>
        <button type="button" class="btn-action btn-dark" onclick="window.close()">
            <i class="fas fa-times"></i> Cerrar
        </button>
    </div>

    @if ($formato === 'ticket')
        <div class="ticket-sheet">
            <div style="text-align: center; border-bottom: 1px dashed #334155; padding-bottom: 8px; margin-bottom: 8px;">
                <div style="font-size: 14px; font-weight: 800;">{{ $empresa->emp_nombre ?? 'SERVICIO TÉCNICO' }}</div>
                <div style="font-size: 10px;">RUC: {{ $empresa->emp_ruc ?? '-' }} | Tel: {{ $empresa->emp_celular ?? ($empresa->emp_telefono ?? '-') }}</div>
                <div style="font-size: 10px;">{{ $empresa->emp_direccion ?? '' }}</div>
                <div style="margin-top: 6px; font-size: 13px; font-weight: 800; background: #0a4d36; color: #fff; padding: 3px; border-radius: 4px;">
                    ORDEN {{ $reparacion->codigo_orden }}
                </div>
                <div style="font-size: 10px; margin-top: 3px;">
                    Fecha: {{ date('d/m/Y H:i', strtotime($reparacion->fecha_recepcion)) }}
                </div>
            </div>

            <div style="margin-bottom: 8px; border-bottom: 1px dashed #cbd5e1; padding-bottom: 6px;">
                <div><strong>Cliente:</strong> {{ $reparacion->cliente->cliente_nombre ?? 'Cliente' }}</div>
                <div><strong>CI/RUC:</strong> {{ $reparacion->cliente->cliente_ruc ?? ($reparacion->cliente->cliente_ci ?? '-') }}</div>
                <div><strong>Celular:</strong> {{ $reparacion->cliente->cliente_cel ?? ($reparacion->cliente->cliente_telef ?? '-') }}</div>
            </div>

            <div style="margin-bottom: 8px; border-bottom: 1px dashed #cbd5e1; padding-bottom: 6px;">
                <div><strong>Equipo:</strong> {{ $reparacion->tipo_equipo }} {{ $reparacion->marca }} {{ $reparacion->modelo }}</div>
                <div><strong>Color:</strong> {{ $reparacion->color ?: '-' }} | <strong>IMEI:</strong> {{ $reparacion->imei_serie ?: 'S/D' }}</div>
                <div><strong>Bloqueo:</strong> {{ $reparacion->tipo_bloqueo }} {{ $reparacion->clave_bloqueo ?: $reparacion->patron_bloqueo }}</div>
                <div><strong>Accesorios:</strong> {{ $reparacion->accesorios_dejados ?: 'Ninguno' }}</div>
                @if (!empty($reparacion->estado_fisico_obs))
                    <div><strong>Est. Físico:</strong> {{ $reparacion->estado_fisico_obs }}</div>
                @endif
            </div>

            <div style="margin-bottom: 8px; border-bottom: 1px dashed #cbd5e1; padding-bottom: 6px;">
                <div><strong>Falla Reportada:</strong></div>
                <div style="font-weight: 700;">{{ $reparacion->falla_reportada }}</div>
            </div>

            <div style="margin-bottom: 10px;">
                <div class="summary-row"><span>Presupuesto Est.:</span> <strong>Gs. {{ number_format($reparacion->total_general, 0, ',', '.') }}</strong></div>
                <div class="summary-row"><span>Seña / Adelanto:</span> <strong>Gs. {{ number_format($reparacion->total_pagado, 0, ',', '.') }}</strong></div>
                <div class="summary-row"><span>Saldo Pendiente:</span> <strong>Gs. {{ number_format($reparacion->saldo_pendiente, 0, ',', '.') }}</strong></div>
            </div>

            <div style="font-size: 9px; color: #475569; margin-bottom: 22px; white-space: pre-line;">{{ $config['condiciones_recepcion'] ?? '' }}</div>

            <div style="text-align: center; margin-top: 26px;">
                <div style="border-top: 1px solid #334155; width: 80%; margin: 0 auto 4px;"></div>
                <div style="font-size: 10px; font-weight: 700;">FIRMA Y CONFORMIDAD CLIENTE</div>
            </div>
        </div>
    @else
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
                    <div class="doc-title">Orden de Recepción</div>
                    <div class="doc-number">{{ $reparacion->codigo_orden }}</div>
                    <div style="font-size: 10.5px; color: #475569;">
                        <strong>Ingreso:</strong> {{ date('d/m/Y H:i', strtotime($reparacion->fecha_recepcion)) }}
                    </div>
                    @if($reparacion->es_reingreso_garantia)
                        <div style="margin-top: 4px; background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 10px; font-size: 9.5px; font-weight: 800; display: inline-block;">
                            REINGRESO POR GARANTÍA
                        </div>
                    @endif
                </div>
            </div>

            <!-- Datos del Cliente y Dispositivo -->
            <div class="grid-2">
                <div class="section-box">
                    <div class="section-header">
                        <span><i class="fas fa-user mr-1"></i> Datos del Cliente</span>
                    </div>
                    <div class="section-body">
                        <div class="info-line"><strong>Nombre y Apellido:</strong> {{ $reparacion->cliente->cliente_nombre ?? 'Sin especificar' }}</div>
                        <div class="info-line"><strong>C.I. / RUC:</strong> {{ $reparacion->cliente->cliente_ruc ?? ($reparacion->cliente->cliente_ci ?? '-') }}</div>
                        <div class="info-line"><strong>Celular / WhatsApp:</strong> {{ $reparacion->cliente->cliente_cel ?? ($reparacion->cliente->cliente_telef ?? '-') }}</div>
                        <div class="info-line"><strong>Dirección:</strong> {{ $reparacion->cliente->cliente_direccion ?? '-' }}</div>
                        <div class="info-line"><strong>Recibido por:</strong> {{ $reparacion->usuarioRecepcion->nom_usuarios ?? 'Recepción' }}</div>
                    </div>
                </div>

                <div class="section-box">
                    <div class="section-header">
                        <span><i class="fas fa-mobile-alt mr-1"></i> Identificación del Equipo</span>
                        <span>Prioridad: {{ $reparacion->prioridad }}</span>
                    </div>
                    <div class="section-body">
                        <div class="info-line"><strong>Tipo / Marca / Modelo:</strong> {{ $reparacion->tipo_equipo }} — <strong>{{ $reparacion->marca }} {{ $reparacion->modelo }}</strong></div>
                        <div class="info-line"><strong>Color:</strong> {{ $reparacion->color ?: '-' }} | <strong>IMEI / Serie:</strong> <span style="font-family: monospace; font-weight: 700;">{{ $reparacion->imei_serie ?: 'No legible / Sin verificar' }}</span></div>
                        <div class="info-line"><strong>Accesorios Dejados:</strong> {{ $reparacion->accesorios_dejados ?: 'Ninguno (Sin chip, sin memoria, sin funda)' }}</div>
                        <div class="info-line">
                            <strong>Clave / Desbloqueo ({{ $reparacion->tipo_bloqueo }}):</strong>
                            @if ($reparacion->tipo_bloqueo === 'PATRON' && !empty($reparacion->patron_bloqueo))
                                <span style="font-family: monospace; font-weight: 800; color: #0a4d36;">Secuencia: {{ $reparacion->patron_bloqueo }}</span>
                            @elseif (!empty($reparacion->clave_bloqueo))
                                <span style="font-family: monospace; font-weight: 800;">{{ $reparacion->clave_bloqueo }}</span>
                            @else
                                <span>Sin bloqueo provisto</span>
                            @endif
                        </div>
                        @if(!empty($reparacion->fecha_prometida))
                            <div class="info-line"><strong>Entrega Estimada:</strong> {{ date('d/m/Y H:i', strtotime($reparacion->fecha_prometida)) }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Checklist de Recepción y Patrón Gráfico -->
            <div class="section-box">
                <div class="section-header">
                    <span><i class="fas fa-clipboard-check mr-1"></i> Inspección Física y Funcional de Recepción</span>
                    <span>Estado de Ingreso</span>
                </div>
                <div class="section-body">
                    <div style="display: flex; gap: 14px; align-items: center;">
                        <div style="flex: 1;">
                            <div class="checklist-grid">
                                @foreach ($chkLabels as $key => $label)
                                    @php
                                        $val = $chkData[$key] ?? 'NA';
                                    @endphp
                                    <div class="chk-pill">
                                        <span>{{ $label }}</span>
                                        @if ($val === 'SI' || $val === true || $val === 1 || $val === '1')
                                            <span class="chk-ok">✔ SÍ</span>
                                        @elseif ($val === 'NO' || $val === false || $val === 0 || $val === '0')
                                            <span class="chk-fail">✖ NO</span>
                                        @else
                                            <span class="chk-na">S/V</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @if (!empty($reparacion->estado_fisico_obs))
                                <div style="margin-top: 8px; font-size: 11px; background: #fffbeb; border: 1px solid #fde68a; padding: 6px 10px; border-radius: 5px;">
                                    <strong>Observaciones estéticas / físicas al ingresar:</strong> {{ $reparacion->estado_fisico_obs }}
                                </div>
                            @endif
                        </div>

                        <!-- Gráfico de Patrón 3x3 -->
                        <div class="pattern-box" style="flex-direction: column; min-width: 115px; text-align: center;">
                            <div style="font-size: 9.5px; font-weight: 700; color: #334155; text-transform: uppercase;">Patrón de Desbloqueo</div>
                            @php
                                $coords = [
                                    1 => [20, 20], 2 => [50, 20], 3 => [80, 20],
                                    4 => [20, 50], 5 => [50, 50], 6 => [80, 50],
                                    7 => [20, 80], 8 => [50, 80], 9 => [80, 80],
                                ];
                                $nodes = array_values(array_filter(array_map('intval', explode('-', (string) $reparacion->patron_bloqueo)), function ($n) {
                                    return $n >= 1 && $n <= 9;
                                }));
                            @endphp
                            <svg width="96" height="96" viewBox="0 0 100 100" style="background: #fff; border: 1px solid #cbd5e1; border-radius: 6px;">
                                @if (count($nodes) > 1)
                                    @for ($i = 0; $i < count($nodes) - 1; $i++)
                                        @php
                                            $p1 = $coords[$nodes[$i]];
                                            $p2 = $coords[$nodes[$i + 1]];
                                        @endphp
                                        <line x1="{{ $p1[0] }}" y1="{{ $p1[1] }}" x2="{{ $p2[0] }}" y2="{{ $p2[1] }}" stroke="#0a4d36" stroke-width="3.5" stroke-linecap="round" />
                                    @endfor
                                @endif
                                @foreach ($coords as $num => $pt)
                                    @php
                                        $activeIdx = array_search($num, $nodes);
                                        $isStart = ($activeIdx === 0);
                                        $isActive = ($activeIdx !== false);
                                    @endphp
                                    <circle cx="{{ $pt[0] }}" cy="{{ $pt[1] }}" r="{{ $isStart ? 8 : 6 }}"
                                            fill="{{ $isStart ? '#10b981' : ($isActive ? '#0a4d36' : '#cbd5e1') }}" />
                                    <text x="{{ $pt[0] }}" y="{{ $pt[1] + 14 }}" font-size="8" text-anchor="middle" fill="#64748b">{{ $num }}</text>
                                @endforeach
                            </svg>
                            <div style="font-size: 9px; font-family: monospace; color: #0f172a;">
                                {{ !empty($nodes) ? implode(' → ', $nodes) : 'Manual / Sin patrón' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Falla Reportada y Presupuesto Inicial -->
            <div class="grid-2">
                <div class="section-box">
                    <div class="section-header">
                        <span><i class="fas fa-tools mr-1"></i> Motivo de Ingreso / Falla Reportada</span>
                    </div>
                    <div class="section-body">
                        <div style="font-size: 12px; font-weight: 700; color: #0f172a; min-height: 45px; white-space: pre-line;">{{ $reparacion->falla_reportada }}</div>
                        @if (!empty($reparacion->diagnostico_tecnico))
                            <div style="margin-top: 8px; padding-top: 6px; border-top: 1px dashed #cbd5e1; font-size: 11px;">
                                <strong>Pre-diagnóstico / Nota Técnica:</strong> {{ $reparacion->diagnostico_tecnico }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="section-box">
                    <div class="section-header">
                        <span><i class="fas fa-coins mr-1"></i> Resumen de Presupuesto y Señas</span>
                        <span>{{ $reparacion->estado_pago }}</span>
                    </div>
                    <div class="section-body">
                        <div class="summary-row">
                            <span>Mano de Obra / Servicio Técnico:</span>
                            <strong>Gs. {{ number_format($reparacion->costo_mano_obra, 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Repuestos e Insumos:</span>
                            <strong>Gs. {{ number_format($reparacion->total_repuestos, 0, ',', '.') }}</strong>
                        </div>
                        @if ($reparacion->descuento > 0)
                            <div class="summary-row" style="color: #dc2626;">
                                <span>Descuento:</span>
                                <strong>- Gs. {{ number_format($reparacion->descuento, 0, ',', '.') }}</strong>
                            </div>
                        @endif
                        <div class="summary-row" style="font-size: 12.5px; color: #0a4d36;">
                            <strong>TOTAL ESTIMADO:</strong>
                            <strong>Gs. {{ number_format($reparacion->total_general, 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Seña / Adelanto Abonado:</span>
                            <strong style="color: #15803d;">Gs. {{ number_format($reparacion->total_pagado, 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-row" style="border-bottom: none; font-size: 12px;">
                            <strong>SALDO A ABONAR AL RETIRAR:</strong>
                            <strong style="color: #b91c1c;">Gs. {{ number_format($reparacion->saldo_pendiente, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Condiciones Legales de Recepción -->
            <div class="terms-box">
                <strong style="display: block; margin-bottom: 3px; color: #0f172a; text-transform: uppercase;">Condiciones de Recepción y Servicio Técnico:</strong>
                {{ $config['condiciones_recepcion'] ?? '' }}
            </div>

            <!-- Firmas -->
            <div class="signatures">
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-label">Firma por Servicio Técnico</div>
                    <div style="font-size: 9.5px; color: #64748b;">{{ $reparacion->usuarioRecepcion->nom_usuarios ?? '' }}</div>
                </div>
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-label">Conformidad del Cliente</div>
                    <div style="font-size: 9.5px; color: #64748b;">{{ $reparacion->cliente->cliente_nombre ?? '' }}</div>
                </div>
            </div>
        </div>
    @endif

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
