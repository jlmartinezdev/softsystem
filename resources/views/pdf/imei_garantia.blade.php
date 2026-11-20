<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Certificado de Venta y Garantía por IMEI {{ $equipo->codigo_interno }}</title>
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
            min-height: 285mm;
            margin: 0 auto;
            padding: 28px 34px;
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
            padding-bottom: 14px;
            margin-bottom: 16px;
        }
        .company-brand { display: flex; align-items: center; gap: 14px; max-width: 60%; }
        .company-logo { max-height: 64px; max-width: 130px; object-fit: contain; }
        .company-info h2 { font-size: 17px; font-weight: 800; margin: 0 0 2px 0; color: #0a4d36; text-transform: uppercase; }
        .company-info p { margin: 1px 0; font-size: 10.5px; color: #475569; }
        .doc-badge-box {
            text-align: right;
            border: 2px solid #0a4d36;
            border-radius: 8px;
            padding: 10px 14px;
            background: #eaf3ef;
            min-width: 215px;
        }
        .doc-title { font-size: 11px; font-weight: 800; color: #0a4d36; text-transform: uppercase; }
        .doc-number { font-size: 18px; font-weight: 900; color: #0f172a; font-family: 'Courier New', monospace; margin: 2px 0; }
        .warranty-hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 2px solid #0a4d36;
            background: linear-gradient(90deg, #eaf3ef 0%, #ffffff 100%);
            border-radius: 8px;
            padding: 12px 18px;
            margin-bottom: 16px;
        }
        .section-box {
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            margin-bottom: 14px;
            overflow: hidden;
        }
        .section-header {
            background: #0a4d36;
            color: #ffffff;
            padding: 6px 12px;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            display: flex;
            justify-content: space-between;
        }
        .section-body { padding: 12px 14px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .info-line { margin: 4px 0; font-size: 11.5px; }
        .specs-banner {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            padding: 10px;
            margin-top: 10px;
            text-align: center;
        }
        .spec-val { font-size: 14px; font-weight: 800; color: #0a4d36; }
        .spec-lbl { font-size: 9.5px; color: #64748b; text-transform: uppercase; font-weight: 700; }
        .terms-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 10px;
            color: #334155;
            line-height: 1.45;
            white-space: pre-line;
            margin-bottom: 20px;
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
            <i class="fas fa-print"></i> Imprimir Certificado de Garantía por IMEI
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
                    <h2>{{ $empresa->emp_nombre ?? 'VENTA DE EQUIPOS MÓVILES' }}</h2>
                    <p><strong>RUC:</strong> {{ $empresa->emp_ruc ?? '-' }} | <strong>Sucursal:</strong> {{ $equipo->sucursal->suc_desc ?? 'Casa Central' }}</p>
                    <p><strong>Dirección:</strong> {{ $empresa->emp_direccion ?? '-' }} | <strong>Tel/Cel:</strong> {{ $empresa->emp_celular ?? ($empresa->emp_telefono ?? '-') }}</p>
                </div>
            </div>
            <div class="doc-badge-box">
                <div class="doc-title"><i class="fas fa-award"></i> Certificado de Garantía por IMEI</div>
                <div class="doc-number">{{ $equipo->codigo_interno }}</div>
                <div style="font-size: 10.5px;">
                    <strong>Fecha Venta:</strong> {{ !empty($equipo->fecha_venta) ? date('d/m/Y H:i', strtotime($equipo->fecha_venta)) : date('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        <div class="warranty-hero">
            <div>
                <div style="font-size: 14px; font-weight: 900; color: #0a4d36; text-transform: uppercase;">
                    <i class="fas fa-shield-alt"></i> GARANTÍA OFICIAL DE EQUIPO MÓVIL POR IMEI
                </div>
                <div style="font-size: 11px; color: #334155; margin-top: 3px;">
                    Plazo de cobertura: <strong>{{ $equipo->dias_garantia }} días corridos</strong> a partir de la fecha de entrega.
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 10px; color: #475569; text-transform: uppercase; font-weight: 700;">Garantía Válida Hasta:</div>
                <div style="font-size: 17px; font-weight: 900; color: #0a4d36; font-family: 'Courier New', monospace;">
                    {{ !empty($equipo->fecha_vencimiento_garantia) ? date('d/m/Y', strtotime($equipo->fecha_vencimiento_garantia)) : date('d/m/Y', strtotime('+'.$equipo->dias_garantia.' days')) }}
                </div>
            </div>
        </div>

        <!-- Datos del Comprador -->
        <div class="section-box">
            <div class="section-header">
                <span><i class="fas fa-user mr-1"></i> Datos del Cliente Comprador</span>
                @if(!empty($equipo->nro_fact_ventas))
                    <span>Comprobante Venta #{{ $equipo->nro_fact_ventas }}</span>
                @endif
            </div>
            <div class="section-body">
                <div class="grid-2">
                    <div>
                        <div class="info-line"><strong>Cliente:</strong> {{ $equipo->clienteComprador->cliente_nombre ?? 'Consumidor Final' }}</div>
                        <div class="info-line"><strong>C.I. / RUC:</strong> {{ $equipo->clienteComprador->cliente_ruc ?? ($equipo->clienteComprador->cliente_ci ?? '-') }}</div>
                    </div>
                    <div>
                        <div class="info-line"><strong>Teléfono:</strong> {{ $equipo->clienteComprador->cliente_cel ?? ($equipo->clienteComprador->cliente_telef ?? '-') }}</div>
                        <div class="info-line"><strong>Vendedor:</strong> {{ $equipo->usuarioVenta->nom_usuarios ?? 'Mostrador' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Especificaciones Técnicas del Equipo Vendido -->
        <div class="section-box">
            <div class="section-header">
                <span><i class="fas fa-mobile-alt mr-1"></i> Identificación y Especificaciones Técnicas del Dispositivo</span>
                <span>Condición: {{ $equipo->condicion_ingreso }}</span>
            </div>
            <div class="section-body">
                <div class="grid-2">
                    <div>
                        <div class="info-line"><strong>Marca y Modelo:</strong> <span style="font-size: 13px; font-weight: 800;">{{ $equipo->marca }} {{ $equipo->modelo }}</span></div>
                        <div class="info-line"><strong>Color:</strong> {{ $equipo->color ?: '-' }} | <strong>Grado Estético:</strong> {{ $equipo->grado_estetico }}</div>
                        <div class="info-line"><strong>Accesorios Incluidos:</strong> {{ $equipo->accesorios_incluidos ?: 'Sin accesorios adicionales' }}</div>
                    </div>
                    <div>
                        <div class="info-line"><strong>IMEI Principal (1):</strong> <span style="font-family: 'Courier New', monospace; font-size: 13px; font-weight: 900; background: #eaf3ef; padding: 2px 6px; border-radius: 4px;">{{ $equipo->imei_1 }}</span></div>
                        @if(!empty($equipo->imei_2))
                            <div class="info-line"><strong>IMEI Secundario (2):</strong> <span style="font-family: 'Courier New', monospace; font-weight: 700;">{{ $equipo->imei_2 }}</span></div>
                        @endif
                        <div class="info-line"><strong>Estado de Red:</strong> {{ $equipo->libre_operadora ? 'Liberado para todas las operadoras' : 'Operadora específica' }}</div>
                    </div>
                </div>

                <div class="specs-banner">
                    <div>
                        <div class="spec-val">{{ $equipo->capacidad_gb ?: 'Estándar' }}</div>
                        <div class="spec-lbl">Almacenamiento</div>
                    </div>
                    <div>
                        <div class="spec-val">{{ $equipo->bateria_salud }}%</div>
                        <div class="spec-lbl">Salud de Batería</div>
                    </div>
                    <div>
                        <div class="spec-val">{{ $equipo->condicion_ingreso }}</div>
                        <div class="spec-lbl">Condición</div>
                    </div>
                    <div>
                        <div class="spec-val">Gs. {{ number_format($equipo->precio_vendido ?: $equipo->precio_venta_contado, 0, ',', '.') }}</div>
                        <div class="spec-lbl">Valor del Equipo</div>
                    </div>
                </div>

                @if ($equipo->monto_canje_descontado > 0 || $equipo->descuento_venta > 0)
                    <div style="margin-top: 10px; padding: 8px 12px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 6px; display: flex; justify-content: space-between; font-size: 11px;">
                        <span>Precio Equipo: <strong>Gs. {{ number_format($equipo->precio_vendido, 0, ',', '.') }}</strong></span>
                        @if($equipo->monto_canje_descontado > 0)
                            <span style="color: #0284c7;">(-) Equipo Recibido en Plan Canje: <strong>Gs. {{ number_format($equipo->monto_canje_descontado, 0, ',', '.') }}</strong></span>
                        @endif
                        @if($equipo->descuento_venta > 0)
                            <span style="color: #dc2626;">(-) Descuento: <strong>Gs. {{ number_format($equipo->descuento_venta, 0, ',', '.') }}</strong></span>
                        @endif
                        <span style="color: #0a4d36;">Diferencia Abonada: <strong>Gs. {{ number_format($equipo->saldo_cobrado, 0, ',', '.') }}</strong></span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Condiciones de Garantía -->
        <div class="terms-box">
            <strong style="display: block; margin-bottom: 4px; color: #0a4d36; text-transform: uppercase;">
                Condiciones y Alcance de la Garantía por IMEI:
            </strong>
            {{ $equipo->condiciones_garantia ?: ($config['clausula_garantia'] ?? '') }}
        </div>

        <!-- Firmas -->
        <div class="signatures">
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">Firma y Sello del Local</div>
                <div style="font-size: 9.5px; color: #64748b;">{{ $empresa->emp_nombre ?? '' }}</div>
            </div>
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">Conformidad del Comprador</div>
                <div style="font-size: 9.5px; color: #64748b;">Recibí el equipo verificado y conforme</div>
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
