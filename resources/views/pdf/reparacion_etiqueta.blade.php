<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Etiqueta {{ $reparacion->codigo_orden }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #e2e8f0;
            margin: 0;
            padding: 25px;
            display: flex;
            flex-direction: column;
            align-items: center;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .sticker-card {
            background: #ffffff;
            width: 72mm;
            border: 2px solid #0f172a;
            border-radius: 6px;
            padding: 8px 10px;
            box-sizing: border-box;
            box-shadow: 0 4px 14px rgba(0,0,0,0.12);
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .sticker-card {
                box-shadow: none;
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
        }
        .sticker-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1.5px solid #0f172a;
            padding-bottom: 4px;
            margin-bottom: 5px;
        }
        .sticker-order {
            font-size: 16px;
            font-weight: 900;
            font-family: 'Courier New', monospace;
            background: #0a4d36;
            color: #ffffff;
            padding: 2px 7px;
            border-radius: 4px;
        }
        .sticker-date {
            font-size: 9.5px;
            font-weight: 700;
            color: #334155;
            text-align: right;
        }
        .sticker-line {
            font-size: 10.5px;
            margin: 2.5px 0;
            color: #0f172a;
            line-height: 1.25;
            word-break: break-word;
        }
        .sticker-device {
            font-size: 12px;
            font-weight: 800;
            background: #f1f5f9;
            padding: 3px 6px;
            border-radius: 4px;
            border-left: 3px solid #0a4d36;
            margin: 4px 0;
        }
        .sticker-lock {
            font-size: 10.5px;
            font-weight: 800;
            font-family: monospace;
            background: #fef3c7;
            border: 1px solid #f59e0b;
            padding: 2px 6px;
            border-radius: 4px;
            margin: 4px 0;
        }
        .print-actions {
            margin-bottom: 16px;
            display: flex;
            gap: 8px;
        }
        .btn-act {
            border: none;
            border-radius: 20px;
            padding: 7px 14px;
            font-weight: 700;
            font-size: 12.5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="print-actions no-print">
        <button type="button" class="btn-act" style="background:#10b981;color:#fff;" onclick="window.print()">
            <i class="fas fa-print"></i> Imprimir Etiqueta / Sticker
        </button>
        <button type="button" class="btn-act" style="background:#334155;color:#fff;" onclick="window.close()">
            <i class="fas fa-times"></i> Cerrar
        </button>
    </div>

    <div class="sticker-card">
        <div class="sticker-top">
            <span class="sticker-order">{{ $reparacion->codigo_orden }}</span>
            <div class="sticker-date">
                {{ date('d/m/Y H:i', strtotime($reparacion->fecha_recepcion)) }}<br>
                <span>{{ $empresa->emp_nombre ?? 'TALLER' }}</span>
            </div>
        </div>

        <div class="sticker-device">
            {{ $reparacion->marca }} {{ $reparacion->modelo }}
            @if(!empty($reparacion->color))
                <span style="font-weight: 600; font-size: 10px;">({{ $reparacion->color }})</span>
            @endif
        </div>

        <div class="sticker-line">
            <strong>Cliente:</strong> {{ $reparacion->cliente->cliente_nombre ?? 'Cliente' }}
        </div>
        <div class="sticker-line">
            <strong>Tel/Cel:</strong> {{ $reparacion->cliente->cliente_cel ?? ($reparacion->cliente->cliente_telef ?? '-') }}
        </div>
        @if(!empty($reparacion->imei_serie))
            <div class="sticker-line">
                <strong>IMEI:</strong> <span style="font-family: monospace;">{{ $reparacion->imei_serie }}</span>
            </div>
        @endif

        <div class="sticker-lock">
            <strong>{{ $reparacion->tipo_bloqueo }}:</strong>
            @if($reparacion->tipo_bloqueo === 'PATRON')
                {{ $reparacion->patron_bloqueo ?: 'Sin patrón' }}
            @elseif($reparacion->tipo_bloqueo === 'NINGUNO')
                Sin bloqueo
            @else
                {{ $reparacion->clave_bloqueo ?: '-' }}
            @endif
        </div>

        <div class="sticker-line" style="border-top: 1px dashed #94a3b8; padding-top: 3px; margin-top: 4px;">
            <strong>Falla:</strong> {{ \Illuminate\Support\Str::limit($reparacion->falla_reportada, 85) }}
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('autoprint') === '1') {
                setTimeout(function () { window.print(); }, 350);
            }
        });
    </script>
</body>
</html>
