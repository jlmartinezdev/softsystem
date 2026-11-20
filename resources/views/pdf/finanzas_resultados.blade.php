<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de Resultados (Ganancia Neta Real) - {{ $resultados['desde'] }} al {{ $resultados['hasta'] }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }
        .no-print-bar {
            background: #0a4d36;
            color: #fff;
            padding: 10px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            border-radius: 6px;
        }
        .btn-print {
            background: #f59e0b;
            color: #111827;
            border: none;
            padding: 6px 14px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        @media print {
            .no-print-bar {
                display: none !important;
            }
        }
        .header-box {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #0a4d36;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .company-title {
            font-size: 18px;
            font-weight: 800;
            color: #0a4d36;
            margin: 0 0 3px 0;
            text-transform: uppercase;
        }
        .company-sub {
            font-size: 11px;
            color: #475569;
            margin: 1px 0;
        }
        .doc-badge {
            border: 2px solid #0a4d36;
            border-radius: 6px;
            padding: 8px 14px;
            text-align: right;
            background: #f0fdf4;
        }
        .doc-badge-title {
            font-size: 10px;
            font-weight: bold;
            color: #0a4d36;
            text-transform: uppercase;
        }
        .doc-badge-num {
            font-size: 14px;
            font-weight: 900;
            color: #111827;
        }
        .section-title {
            background: #0a4d36;
            color: #ffffff;
            font-weight: bold;
            font-size: 10.5px;
            padding: 5px 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 4px 4px 0 0;
            margin-top: 12px;
        }
        .info-panel {
            border: 1px solid #cbd5e1;
            border-top: none;
            padding: 10px 12px;
            border-radius: 0 0 4px 4px;
            background: #ffffff;
        }
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }
        table.items-table th {
            background: #f1f5f9;
            color: #334155;
            font-size: 9.5px;
            text-transform: uppercase;
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }
        table.items-table td {
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
            font-size: 10.5px;
        }
        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        .net-box {
            margin-top: 16px;
            border: 2px solid #0a4d36;
            border-radius: 8px;
            padding: 14px 18px;
            background: #f0fdf4;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .net-box.negative {
            border-color: #e11d48;
            background: #fff1f2;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 38px;
            padding: 0 25px;
        }
        .sig-block {
            width: 42%;
            text-align: center;
            border-top: 1px solid #334155;
            padding-top: 5px;
            font-size: 10px;
            color: #475569;
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <div>
            <strong>ESTADO DE RESULTADOS Y GANANCIA NETA REAL</strong> — Período: {{ date('d/m/Y', strtotime($resultados['desde'])) }} al {{ date('d/m/Y', strtotime($resultados['hasta'])) }}
        </div>
        <div>
            <button class="btn-print" onclick="window.print()">Imprimir / Guardar PDF</button>
        </div>
    </div>

    <div class="header-box">
        <div>
            <h1 class="company-title">{{ $empresa->emp_nombre ?? 'SERVICIO TÉCNICO Y VENTAS' }}</h1>
            @if(!empty($empresa->emp_ruc))
                <p class="company-sub"><strong>RUC:</strong> {{ $empresa->emp_ruc }}</p>
            @endif
            <p class="company-sub"><strong>Sucursal:</strong> {{ $sucursal ? $sucursal->suc_desc : 'Consolidado General (Todas las Sucursales)' }}</p>
            <p class="company-sub"><strong>Fecha de Emisión:</strong> {{ date('d/m/Y H:i') }}</p>
        </div>
        <div class="doc-badge">
            <div class="doc-badge-title">Estado de Resultados</div>
            <div class="doc-badge-num">{{ date('d/m/Y', strtotime($resultados['desde'])) }} — {{ date('d/m/Y', strtotime($resultados['hasta'])) }}</div>
            <div style="font-size: 10px; color: #475569; margin-top: 2px;">
                Margen Neto Real: <strong>{{ $resultados['margen_neto_porc'] }}%</strong>
            </div>
        </div>
    </div>

    <!-- 1. INGRESOS Y UTILIDAD BRUTA POR UNIDAD DE NEGOCIO -->
    <div class="section-title">1. Ingresos, Costos Directos y Utilidad Bruta por Unidad de Negocio</div>
    <div class="info-panel" style="padding: 0; border: none;">
        <table class="items-table">
            <thead>
                <tr>
                    <th>Unidad de Negocio</th>
                    <th class="text-center" style="width: 110px;">Operaciones</th>
                    <th class="text-right" style="width: 140px;">Ingresos Facturados</th>
                    <th class="text-right" style="width: 140px;">Costo Directo / Repuestos</th>
                    <th class="text-right" style="width: 145px;">Utilidad Bruta</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>1.1. Punto de Venta (POS - Mercadería General y Accesorios)</strong>
                    </td>
                    <td class="text-center">{{ $resultados['cant_ventas_pos'] }} venta(s)</td>
                    <td class="text-right">Gs. {{ number_format($resultados['ingresos_ventas_pos'], 0, ',', '.') }}</td>
                    <td class="text-right">Gs. {{ number_format($resultados['costo_ventas_pos'], 0, ',', '.') }}</td>
                    <td class="text-right" style="font-weight: bold; color: #0a4d36;">
                        Gs. {{ number_format($resultados['utilidad_ventas_pos'], 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>1.2. Servicio Técnico y Reparación de Celulares</strong>
                        @if($resultados['costo_garantias_taller'] > 0)
                            <div style="font-size: 9px; color: #e11d48;">
                                Incluye Gs. {{ number_format($resultados['costo_garantias_taller'], 0, ',', '.') }} en costos asumidos por garantías
                            </div>
                        @endif
                    </td>
                    <td class="text-center">{{ $resultados['cant_reparaciones'] }} orden(es)</td>
                    <td class="text-right">Gs. {{ number_format($resultados['ingresos_taller'], 0, ',', '.') }}</td>
                    <td class="text-right">Gs. {{ number_format($resultados['costo_repuestos_taller'] + $resultados['costo_garantias_taller'], 0, ',', '.') }}</td>
                    <td class="text-right" style="font-weight: bold; color: #0a4d36;">
                        Gs. {{ number_format($resultados['utilidad_taller'], 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>1.3. Venta de Equipos Celulares por IMEI y Plan Canje</strong>
                    </td>
                    <td class="text-center">{{ $resultados['cant_equipos_imei'] }} equipo(s)</td>
                    <td class="text-right">Gs. {{ number_format($resultados['ingresos_imei'], 0, ',', '.') }}</td>
                    <td class="text-right">Gs. {{ number_format($resultados['costo_imei'], 0, ',', '.') }}</td>
                    <td class="text-right" style="font-weight: bold; color: #0a4d36;">
                        Gs. {{ number_format($resultados['utilidad_imei'], 0, ',', '.') }}
                    </td>
                </tr>
                <tr style="background: #f8fafc; font-weight: bold;">
                    <td colspan="2" class="text-right">SUBTOTAL CONSOLIDADO OPERATIVO (=)</td>
                    <td class="text-right">Gs. {{ number_format($resultados['ingresos_totales'], 0, ',', '.') }}</td>
                    <td class="text-right">Gs. {{ number_format($resultados['costos_directos_totales'], 0, ',', '.') }}</td>
                    <td class="text-right" style="font-size: 12px; color: #0a4d36;">
                        Gs. {{ number_format($resultados['utilidad_bruta_total'], 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- 2. COMISIONES Y GASTOS OPERATIVOS DEL PERÍODO -->
    <div class="section-title">2. (-) Comisiones Liquidadas y Gastos Operativos del Período</div>
    <div class="info-panel" style="padding: 0; border: none;">
        <table class="items-table">
            <thead>
                <tr>
                    <th>Categoría de Egreso / Gasto Operativo</th>
                    <th class="text-center" style="width: 110px;">Clasificación</th>
                    <th class="text-center" style="width: 110px;">Comprobantes</th>
                    <th class="text-right" style="width: 150px;">Total Egreso (Gs.)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Comisiones Liquidadas a Técnicos y Vendedores</strong></td>
                    <td class="text-center">VARIABLE</td>
                    <td class="text-center">Liquidaciones</td>
                    <td class="text-right" style="color: #be123c; font-weight: bold;">
                        - Gs. {{ number_format($resultados['total_comisiones'], 0, ',', '.') }}
                    </td>
                </tr>
                @forelse($resultados['gastos_por_categoria'] as $gcat)
                <tr>
                    <td>{{ $gcat->nombre }}</td>
                    <td class="text-center">{{ $gcat->tipo }}</td>
                    <td class="text-center">{{ $gcat->cantidad }} reg.</td>
                    <td class="text-right" style="color: #be123c;">
                        - Gs. {{ number_format($gcat->total, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="color: #64748b;">Sin gastos operativos registrados en este rango de fechas</td>
                    <td class="text-right">Gs. 0</td>
                </tr>
                @endforelse
                <tr style="background: #fff1f2; font-weight: bold;">
                    <td colspan="3" class="text-right">TOTAL COMISIONES Y GASTOS OPERATIVOS (-)</td>
                    <td class="text-right" style="font-size: 11.5px; color: #be123c;">
                        - Gs. {{ number_format($resultados['total_comisiones'] + $resultados['total_gastos_operativos'], 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- 3. RESULTADO FINAL: GANANCIA NETA REAL -->
    <div class="net-box {{ $resultados['ganancia_neta_real'] < 0 ? 'negative' : '' }}">
        <div>
            <div style="font-size: 11px; font-weight: bold; text-transform: uppercase; color: {{ $resultados['ganancia_neta_real'] >= 0 ? '#0a4d36' : '#e11d48' }};">
                (=) RESULTADO FINAL DEL EJERCICIO / PERÍODO
            </div>
            <div style="font-size: 16px; font-weight: 900; color: #111827;">
                {{ $resultados['ganancia_neta_real'] >= 0 ? 'GANANCIA NETA REAL DEL NEGOCIO' : 'PÉRDIDA NETA DEL PERÍODO' }}
            </div>
            <div style="font-size: 10px; color: #475569; margin-top: 2px;">
                Utilidad Bruta (Gs. {{ number_format($resultados['utilidad_bruta_total'], 0, ',', '.') }})
                (-) Comisiones (Gs. {{ number_format($resultados['total_comisiones'], 0, ',', '.') }})
                (-) Gastos Operativos (Gs. {{ number_format($resultados['total_gastos_operativos'], 0, ',', '.') }})
            </div>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 22px; font-weight: 900; color: {{ $resultados['ganancia_neta_real'] >= 0 ? '#0a4d36' : '#e11d48' }};">
                Gs. {{ number_format($resultados['ganancia_neta_real'], 0, ',', '.') }}
            </div>
            <div style="font-size: 10.5px; font-weight: bold; color: #334155;">
                Rentabilidad Neta sobre Facturación: {{ $resultados['margen_neto_porc'] }}%
            </div>
        </div>
    </div>

    <!-- 4. ESTADO DE PASIVOS CON PROVEEDORES -->
    <div style="margin-top: 12px; border: 1px dashed #94a3b8; border-radius: 6px; padding: 8px 12px; display: flex; justify-content: space-between; background: #f8fafc;">
        <div>
            <strong>Pagos a Proveedores realizados en el período:</strong>
            Gs. {{ number_format($resultados['pagos_proveedores_periodo'], 0, ',', '.') }}
        </div>
        <div>
            <strong>Deuda Total Actual con Proveedores (Cuentas a Pagar):</strong>
            <span style="color: #be123c; font-weight: bold;">Gs. {{ number_format($resultados['deuda_proveedores_actual'], 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="signatures">
        <div class="sig-block">
            <strong>Elaborado / Revisado por Administración</strong><br>
            Firma y Sello
        </div>
        <div class="sig-block">
            <strong>Conformidad Dirección / Gerencia</strong><br>
            Firma y Sello
        </div>
    </div>

</body>
</html>
