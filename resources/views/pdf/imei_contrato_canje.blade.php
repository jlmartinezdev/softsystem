<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acta y Contrato de Plan Canje {{ $canje->codigo_canje }} - {{ $empresa->emp_nombre ?? 'SoftSystem' }}</title>
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
        .company-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            max-width: 60%;
        }
        .company-logo { max-height: 62px; max-width: 130px; object-fit: contain; }
        .company-info h2 { font-size: 17px; font-weight: 800; margin: 0 0 2px 0; color: #0a4d36; text-transform: uppercase; }
        .company-info p { margin: 1px 0; font-size: 10.5px; color: #475569; }
        .doc-badge-box {
            text-align: right;
            border: 2px solid #0a4d36;
            border-radius: 8px;
            padding: 8px 14px;
            background: #f8fafc;
            min-width: 210px;
        }
        .doc-title { font-size: 11px; font-weight: 800; color: #0a4d36; text-transform: uppercase; }
        .doc-number { font-size: 18px; font-weight: 900; color: #0f172a; font-family: 'Courier New', monospace; margin: 2px 0; }
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
        .section-body { padding: 10px 12px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .info-line { margin: 3px 0; font-size: 11px; }
        .declaration-box {
            background: #fffbeb;
            border: 1.5px solid #f59e0b;
            border-radius: 7px;
            padding: 10px 14px;
            font-size: 10.5px;
            color: #78350f;
            line-height: 1.45;
            white-space: pre-line;
            margin-bottom: 14px;
        }
        .photos-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 6px;
        }
        .photo-thumb {
            width: 130px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            overflow: hidden;
            text-align: center;
            background: #f8fafc;
        }
        .photo-thumb img {
            width: 100%;
            height: 85px;
            object-fit: cover;
            display: block;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 38px;
            padding: 0 20px;
        }
        .sig-block { text-align: center; width: 225px; }
        .sig-line { border-top: 1.5px solid #334155; margin-bottom: 5px; }
        .sig-label { font-size: 10px; color: #334155; text-transform: uppercase; font-weight: 700; }
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
            <i class="fas fa-print"></i> Imprimir Contrato de Canje / Recepción
        </button>
        <button type="button" class="btn-action" style="background: #334155;" onclick="window.close()">
            <i class="fas fa-times"></i> Cerrar
        </button>
    </div>

    @php
        $ent = $canje->celularEntregado;
        $llev = $canje->celularLlevado;
    @endphp

    <div class="page-sheet">
        <div class="header-top">
            <div class="company-brand">
                @if(!empty($empresa->emp_logo) && file_exists(public_path('img/'.$empresa->emp_logo)))
                    <img src="{{ asset('img/'.$empresa->emp_logo) }}" alt="{{ $empresa->emp_nombre }}" class="company-logo">
                @endif
                <div class="company-info">
                    <h2>{{ $empresa->emp_nombre ?? 'GESTIÓN DE EQUIPOS MÓVILES' }}</h2>
                    <p><strong>RUC:</strong> {{ $empresa->emp_ruc ?? '-' }} | <strong>Sucursal:</strong> {{ $canje->sucursal->suc_desc ?? 'Casa Central' }}</p>
                    <p><strong>Dirección:</strong> {{ $empresa->emp_direccion ?? '-' }} | <strong>Tel/Cel:</strong> {{ $empresa->emp_celular ?? ($empresa->emp_telefono ?? '-') }}</p>
                </div>
            </div>
            <div class="doc-badge-box">
                <div class="doc-title">
                    {{ $canje->tipo_operacion === 'PLAN_CANJE' ? 'Contrato de Plan Canje (Trade-In)' : 'Acta de Compra de Equipo Usado' }}
                </div>
                <div class="doc-number">{{ $canje->codigo_canje }}</div>
                <div style="font-size: 10.5px;">
                    <strong>Fecha:</strong> {{ date('d/m/Y H:i', strtotime($canje->fecha_operacion)) }}
                </div>
            </div>
        </div>

        <!-- 1. Datos del Cliente / Cedente -->
        <div class="section-box">
            <div class="section-header">
                <span><i class="fas fa-user-check mr-1"></i> 1. Datos del Titular que Entrega el Dispositivo Usado</span>
            </div>
            <div class="section-body">
                <div class="grid-2">
                    <div>
                        <div class="info-line"><strong>Nombre y Apellido:</strong> {{ $canje->cliente->cliente_nombre ?? 'Sin especificar' }}</div>
                        <div class="info-line"><strong>Nro. de C.I. / RUC:</strong> {{ $canje->cliente->cliente_ci ?: ($canje->cliente->cliente_ruc ?? '-') }}</div>
                    </div>
                    <div>
                        <div class="info-line"><strong>Teléfono / Celular:</strong> {{ $canje->cliente->cliente_cel ?? ($canje->cliente->cliente_telef ?? '-') }}</div>
                        <div class="info-line"><strong>Domicilio:</strong> {{ $canje->cliente->cliente_direccion ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Equipo Entregado y Equipo Adquirido -->
        <div class="grid-2" style="margin-bottom: 12px;">
            <div class="section-box" style="margin-bottom: 0;">
                <div class="section-header" style="background: #0284c7;">
                    <span><i class="fas fa-mobile-alt mr-1"></i> 2. Equipo Usado Recibido por el Local</span>
                    <span>{{ $ent->codigo_interno ?? '' }}</span>
                </div>
                <div class="section-body">
                    @if ($ent)
                        <div class="info-line"><strong>Marca y Modelo:</strong> {{ $ent->marca }} {{ $ent->modelo }} {{ $ent->capacidad_gb }}</div>
                        <div class="info-line"><strong>Color:</strong> {{ $ent->color ?: '-' }} | <strong>Estética:</strong> Grado {{ $ent->grado_estetico }}</div>
                        <div class="info-line"><strong>IMEI Principal (1):</strong> <span style="font-family: monospace; font-weight: 900; font-size: 12px;">{{ $ent->imei_1 }}</span></div>
                        @if(!empty($ent->imei_2))
                            <div class="info-line"><strong>IMEI Secundario (2):</strong> <span style="font-family: monospace;">{{ $ent->imei_2 }}</span></div>
                        @endif
                        <div class="info-line"><strong>Salud de Batería:</strong> {{ $ent->bateria_salud }}% | <strong>Cuentas Limpias:</strong> {{ $ent->icloud_google_limpio ? 'Sí (Sin iCloud/Google)' : 'Con cuenta' }}</div>
                        <div class="info-line"><strong>Accesorios Entregados:</strong> {{ $ent->accesorios_incluidos ?: 'Solo equipo' }}</div>
                        @if(!empty($ent->observaciones_equipo))
                            <div class="info-line"><strong>Observaciones:</strong> {{ $ent->observaciones_equipo }}</div>
                        @endif
                        <div style="margin-top: 8px; padding: 6px 10px; background: #e0f2fe; border-radius: 5px; font-weight: 800; color: #0369a1; display: flex; justify-content: space-between;">
                            <span>VALOR RECONOCIDO / TASACIÓN:</span>
                            <span>Gs. {{ number_format($canje->valor_tasacion_usado, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="section-box" style="margin-bottom: 0;">
                <div class="section-header">
                    <span><i class="fas fa-exchange-alt mr-1"></i> 3. Liquidación de la Operación</span>
                </div>
                <div class="section-body">
                    @if ($canje->tipo_operacion === 'PLAN_CANJE' && $llev)
                        <div class="info-line"><strong>Equipo Adquirido por el Cliente:</strong> {{ $llev->marca }} {{ $llev->modelo }} {{ $llev->capacidad_gb }} ({{ $llev->color ?: '-' }})</div>
                        <div class="info-line"><strong>Código Interno:</strong> {{ $llev->codigo_interno }} | <strong>Condición:</strong> {{ $llev->condicion_ingreso }}</div>
                        <div class="info-line"><strong>IMEI Equipo Adquirido:</strong> <span style="font-family: monospace; font-weight: 800;">{{ $llev->imei_1 }}</span></div>
                        <div class="info-line"><strong>Garantía Otorgada:</strong> {{ $llev->dias_garantia }} días</div>
                        <hr style="border: 0; border-top: 1px dashed #cbd5e1; margin: 6px 0;">
                        <div style="display: flex; justify-content: space-between; margin: 3px 0;">
                            <span>Precio Equipo Adquirido:</span>
                            <strong>Gs. {{ number_format($canje->precio_equipo_llevado, 0, ',', '.') }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin: 3px 0; color: #0284c7;">
                            <span>(-) Valor Equipo Entregado en Canje:</span>
                            <strong>- Gs. {{ number_format($canje->valor_tasacion_usado, 0, ',', '.') }}</strong>
                        </div>
                        @if ($canje->descuento_adicional > 0)
                            <div style="display: flex; justify-content: space-between; margin: 3px 0; color: #dc2626;">
                                <span>(-) Descuento Adicional:</span>
                                <strong>- Gs. {{ number_format($canje->descuento_adicional, 0, ',', '.') }}</strong>
                            </div>
                        @endif
                        <div style="margin-top: 6px; padding: 7px 10px; background: #0a4d36; color: #fff; border-radius: 5px; font-weight: 800; display: flex; justify-content: space-between; font-size: 12.5px;">
                            <span>DIFERENCIA A ABONAR:</span>
                            <span>Gs. {{ number_format($canje->diferencia_a_pagar, 0, ',', '.') }}</span>
                        </div>
                    @else
                        <div class="info-line"><strong>Modalidad:</strong> Compra Directa de Equipo Usado</div>
                        <div class="info-line"><strong>Atendido por:</strong> {{ $canje->usuario->nom_usuarios ?? 'Operador' }}</div>
                        @if(!empty($canje->observaciones))
                            <div class="info-line"><strong>Observaciones:</strong> {{ $canje->observaciones }}</div>
                        @endif
                        <div style="margin-top: 14px; padding: 8px 10px; background: #0a4d36; color: #fff; border-radius: 5px; font-weight: 800; display: flex; justify-content: space-between; font-size: 13px;">
                            <span>MONTO ABONADO AL TITULAR:</span>
                            <span>Gs. {{ number_format($canje->valor_tasacion_usado, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Fotografías de Respaldo (Equipo / C.I.) -->
        @if ($ent && $ent->imagenes && $ent->imagenes->count() > 0)
            <div class="section-box">
                <div class="section-header">
                    <span><i class="fas fa-camera mr-1"></i> Registro Fotográfico del Equipo y Documento de Identidad</span>
                </div>
                <div class="section-body">
                    <div class="photos-row">
                        @foreach ($ent->imagenes->take(5) as $img)
                            <div class="photo-thumb">
                                <img src="{{ $img->url }}" alt="{{ $img->tipo }}">
                                <div style="font-size: 9px; padding: 3px; font-weight: 700;">{{ $img->tipo }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Declaración Jurada Legal -->
        <div class="declaration-box">
            <strong style="display: block; margin-bottom: 4px; text-transform: uppercase; font-size: 11px;">
                <i class="fas fa-gavel"></i> Declaración Jurada de Propiedad, Titularidad y Origen Lícito del Dispositivo:
            </strong>
            {{ $config['clausula_canje'] ?? '' }}
        </div>

        <!-- Firmas -->
        <div class="signatures">
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">Firma y Sello del Comercio</div>
                <div style="font-size: 9.5px; color: #64748b;">Recibido por: {{ $canje->usuario->nom_usuarios ?? '' }}</div>
            </div>
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-label">Firma del Titular / Cedente</div>
                <div style="font-size: 9.5px; color: #0f172a; font-weight: 700;">
                    {{ $canje->cliente->cliente_nombre ?? '' }} — C.I.: {{ $canje->cliente->cliente_ci ?: ($canje->cliente->cliente_ruc ?? '') }}
                </div>
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
