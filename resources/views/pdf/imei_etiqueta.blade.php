<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Etiqueta de Vitrina {{ $equipo->codigo_interno }}</title>
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
            width: 75mm;
            border: 2.5px solid #0a4d36;
            border-radius: 8px;
            padding: 10px 12px;
            box-sizing: border-box;
            box-shadow: 0 4px 14px rgba(0,0,0,0.12);
        }
        @media print {
            body { background: #ffffff; padding: 0; }
            .sticker-card { box-shadow: none; margin: 0; }
            .no-print { display: none !important; }
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1.5px solid #0a4d36;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }
        .code-pill {
            background: #0a4d36;
            color: #fff;
            font-weight: 900;
            font-size: 13px;
            padding: 2px 7px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
        }
        .cond-pill {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #f59e0b;
            font-weight: 800;
            font-size: 9.5px;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .device-title {
            font-size: 15px;
            font-weight: 900;
            color: #0f172a;
            line-height: 1.2;
            margin-bottom: 4px;
        }
        .specs-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            padding: 5px 7px;
            margin-bottom: 6px;
            font-size: 10px;
        }
        .imei-box {
            font-family: 'Courier New', monospace;
            font-size: 10.5px;
            font-weight: 700;
            text-align: center;
            background: #f1f5f9;
            padding: 3px;
            border-radius: 4px;
            margin-bottom: 6px;
            border: 1px dashed #94a3b8;
        }
        .price-box {
            background: #0a4d36;
            color: #ffffff;
            text-align: center;
            padding: 6px 8px;
            border-radius: 6px;
        }
        .price-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
        }
        .price-val {
            font-size: 17px;
            font-weight: 900;
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
            <i class="fas fa-print"></i> Imprimir Etiqueta de Vitrina
        </button>
        <button type="button" class="btn-act" style="background:#334155;color:#fff;" onclick="window.close()">
            <i class="fas fa-times"></i> Cerrar
        </button>
    </div>

    <div class="sticker-card">
        <div class="top-bar">
            <span class="code-pill">{{ $equipo->codigo_interno }}</span>
            <span class="cond-pill">{{ $equipo->condicion_ingreso }} ({{ $equipo->grado_estetico }})</span>
        </div>

        <div class="device-title">
            {{ $equipo->marca }} {{ $equipo->modelo }}
        </div>

        <div class="specs-grid">
            <div><strong>Capacidad:</strong> {{ $equipo->capacidad_gb ?: '-' }}</div>
            <div><strong>Batería:</strong> {{ $equipo->bateria_salud }}%</div>
            <div><strong>Color:</strong> {{ $equipo->color ?: '-' }}</div>
            <div><strong>Garantía:</strong> {{ $equipo->dias_garantia }} días</div>
        </div>

        <div class="imei-box">
            IMEI: {{ $equipo->imei_1 }}
        </div>

        <div class="price-box">
            <div class="price-label">Precio Contado</div>
            <div class="price-val">Gs. {{ number_format($equipo->precio_venta_contado, 0, ',', '.') }}</div>
            @if ($equipo->precio_venta_credito > 0)
                <div style="font-size: 9.5px; margin-top: 2px; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 2px;">
                    Lista / Crédito: Gs. {{ number_format($equipo->precio_venta_credito, 0, ',', '.') }}
                </div>
            @endif
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
