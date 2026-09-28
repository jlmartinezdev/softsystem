<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Resumen Gerencial</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; color: #1c2430; background: #f1f5f9; margin: 0; padding: 24px;">
    <div style="max-width: 680px; margin: 0 auto; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        
        <!-- Header -->
        <div style="background: #0a4d36; color: #ffffff; padding: 20px 24px;">
            <div style="font-size: 20px; font-weight: bold; letter-spacing: -0.5px;">{{ $datos['empresa'] ?? 'SoftSystem' }}</div>
            <div style="font-size: 13px; opacity: 0.9; margin-top: 2px;">Resumen Gerencial y Financiero</div>
        </div>

        <div style="padding: 24px;">
            <!-- Parámetros del Informe -->
            <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 20px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
                <tr>
                    <td style="padding: 10px 14px; color: #64748b; font-weight: bold; width: 30%;">Período Consultado:</td>
                    <td style="padding: 10px 14px; text-align: right; font-weight: bold; color: #0a4d36;">
                        {{ $datos['desde'] }} al {{ $datos['hasta'] }}
                    </td>
                </tr>
                <tr style="border-top: 1px solid #e2e8f0;">
                    <td style="padding: 10px 14px; color: #64748b; font-weight: bold;">Sucursal:</td>
                    <td style="padding: 10px 14px; text-align: right; font-weight: bold;">
                        {{ $datos['sucursal_nombre'] }}
                    </td>
                </tr>
                <tr style="border-top: 1px solid #e2e8f0;">
                    <td style="padding: 10px 14px; color: #64748b; font-weight: bold;">Generado por:</td>
                    <td style="padding: 10px 14px; text-align: right; color: #475569;">
                        {{ $datos['generado_por'] }} ({{ $datos['fecha_hora'] }})
                    </td>
                </tr>
            </table>

            <!-- 4 Tarjetas de Resumen Ejecutivo -->
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 24px;">
                <tr>
                    <!-- Facturación Ventas -->
                    <td style="width: 50%; padding: 12px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px;">
                        <div style="font-size: 11px; font-weight: bold; text-transform: uppercase; color: #1e40af;">Facturación Ventas</div>
                        <div style="font-size: 18px; font-weight: bold; color: #1e3a8a; margin-top: 4px;">
                            Gs. {{ number_format($datos['balance']['total_ventas'] ?? 0, 0, ',', '.') }}
                        </div>
                        <div style="font-size: 11px; color: #3b82f6; margin-top: 2px;">
                            {{ $datos['venta']['cantidad'] ?? 0 }} ventas realizadas
                        </div>
                    </td>
                    <td style="width: 12px;"></td>
                    <!-- Ganancia Bruta -->
                    <td style="width: 50%; padding: 12px; background: #ecfdf5; border: 1px solid #bbf7d0; border-radius: 8px;">
                        <div style="font-size: 11px; font-weight: bold; text-transform: uppercase; color: #065f46;">Ganancia Bruta</div>
                        <div style="font-size: 18px; font-weight: bold; color: #047857; margin-top: 4px;">
                            Gs. {{ number_format($datos['balance']['ganancia_bruta'] ?? 0, 0, ',', '.') }}
                        </div>
                        <div style="font-size: 11px; color: #10b981; margin-top: 2px;">
                            Margen: {{ $datos['balance']['margen_pct'] ?? 0 }}% sobre ventas
                        </div>
                    </td>
                </tr>
                <tr><td colspan="3" style="height: 12px;"></td></tr>
                <tr>
                    <!-- Compras Realizadas -->
                    <td style="padding: 12px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px;">
                        <div style="font-size: 11px; font-weight: bold; text-transform: uppercase; color: #991b1b;">Compras Proveedor</div>
                        <div style="font-size: 18px; font-weight: bold; color: #b91c1c; margin-top: 4px;">
                            Gs. {{ number_format($datos['balance']['total_compras'] ?? 0, 0, ',', '.') }}
                        </div>
                        <div style="font-size: 11px; color: #ef4444; margin-top: 2px;">
                            {{ $datos['compra']['cantidad'] ?? 0 }} facturas de compra
                        </div>
                    </td>
                    <td></td>
                    <!-- Valor en Stock -->
                    <td style="padding: 12px; background: #faf5ff; border: 1px solid #e9d5ff; border-radius: 8px;">
                        <div style="font-size: 11px; font-weight: bold; text-transform: uppercase; color: #6b21a8;">Valor en Stock</div>
                        <div style="font-size: 18px; font-weight: bold; color: #7e22ce; margin-top: 4px;">
                            Gs. {{ number_format($datos['articulo']['venta'] ?? 0, 0, ',', '.') }}
                        </div>
                        <div style="font-size: 11px; color: #a855f7; margin-top: 2px;">
                            {{ number_format($datos['articulo']['stock'] ?? 0, 0, ',', '.') }} unidades físicas
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Desglose Comparativo de Ventas y Compras -->
            <div style="font-size: 14px; font-weight: bold; color: #0a4d36; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                Desglose Financiero
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 24px; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
                <tr style="background: #f8fafc; font-weight: bold; color: #475569; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 8px 12px; text-align: left;">Concepto</th>
                    <th style="padding: 8px 12px; text-align: right;">Ventas</th>
                    <th style="padding: 8px 12px; text-align: right;">Compras</th>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 8px 12px; color: #64748b;">Operaciones al Contado</td>
                    <td style="padding: 8px 12px; text-align: right; color: #059669; font-weight: bold;">
                        Gs. {{ number_format($datos['venta']['contado'] ?? 0, 0, ',', '.') }}
                    </td>
                    <td style="padding: 8px 12px; text-align: right; font-weight: bold;">
                        Gs. {{ number_format($datos['compra']['contado'] ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 8px 12px; color: #64748b;">Operaciones a Crédito</td>
                    <td style="padding: 8px 12px; text-align: right; color: #2563eb; font-weight: bold;">
                        Gs. {{ number_format($datos['venta']['credito'] ?? 0, 0, ',', '.') }}
                    </td>
                    <td style="padding: 8px 12px; text-align: right; font-weight: bold;">
                        Gs. {{ number_format($datos['compra']['credito'] ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 8px 12px; color: #64748b;">Descuentos</td>
                    <td style="padding: 8px 12px; text-align: right; color: #d97706;">
                        Gs. {{ number_format($datos['venta']['descuento'] ?? 0, 0, ',', '.') }}
                    </td>
                    <td style="padding: 8px 12px; text-align: right; color: #059669;">
                        Gs. {{ number_format($datos['compra']['descuento'] ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
                <tr style="background: #f8fafc; font-weight: bold; border-top: 2px solid #cbd5e1;">
                    <td style="padding: 10px 12px; color: #1e293b;">Total General</td>
                    <td style="padding: 10px 12px; text-align: right; color: #0a4d36; font-size: 14px;">
                        Gs. {{ number_format($datos['balance']['total_ventas'] ?? 0, 0, ',', '.') }}
                    </td>
                    <td style="padding: 10px 12px; text-align: right; color: #dc2626; font-size: 14px;">
                        Gs. {{ number_format($datos['balance']['total_compras'] ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
            </table>

            <!-- Resumen de Stock -->
            <div style="font-size: 14px; font-weight: bold; color: #0a4d36; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                Inventario y Mercaderías
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 24px; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 8px 12px; color: #64748b;">Valuación a Precio de Costo</td>
                    <td style="padding: 8px 12px; text-align: right; font-weight: bold;">
                        Gs. {{ number_format($datos['articulo']['costo'] ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 8px 12px; color: #64748b;">Valuación a Precio de Venta</td>
                    <td style="padding: 8px 12px; text-align: right; font-weight: bold; color: #059669;">
                        Gs. {{ number_format($datos['articulo']['venta'] ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 8px 12px; color: #64748b;">Margen Potencial en Inventario</td>
                    <td style="padding: 8px 12px; text-align: right; font-weight: bold; color: #2563eb;">
                        Gs. {{ number_format($datos['articulo']['potencial'] ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 12px; color: #64748b;">Artículos con Stock 0 (Agotados)</td>
                    <td style="padding: 8px 12px; text-align: right; font-weight: bold; color: {{ ($datos['articulo']['sin_stock'] ?? 0) > 0 ? '#dc2626' : '#64748b' }};">
                        {{ $datos['articulo']['sin_stock'] ?? 0 }} productos
                    </td>
                </tr>
            </table>

            <!-- Top Productos si existen -->
            @if (!empty($datos['top_productos']) && count($datos['top_productos']) > 0)
                <div style="font-size: 14px; font-weight: bold; color: #0a4d36; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Top 5 Productos Más Vendidos
                </div>
                <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 20px; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
                    <thead>
                        <tr style="background: #111827; color: #ffffff;">
                            <th style="padding: 8px 10px; text-align: left; width: 30px;">#</th>
                            <th style="padding: 8px 10px; text-align: left;">Producto</th>
                            <th style="padding: 8px 10px; text-align: center; width: 80px;">Unidades</th>
                            <th style="padding: 8px 10px; text-align: right; width: 130px;">Total Facturado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datos['top_productos'] as $idx => $prod)
                            <tr style="border-bottom: 1px solid #e2e8f0; {{ $idx % 2 == 1 ? 'background: #f8fafc;' : '' }}">
                                <td style="padding: 8px 10px; font-weight: bold; color: #64748b;">{{ $idx + 1 }}</td>
                                <td style="padding: 8px 10px; font-weight: bold;">{{ $prod->producto_nombre ?? '' }}</td>
                                <td style="padding: 8px 10px; text-align: center;">{{ number_format($prod->cant_total ?? 0, 0, ',', '.') }}</td>
                                <td style="padding: 8px 10px; text-align: right; font-weight: bold; color: #059669;">
                                    Gs. {{ number_format($prod->monto_total ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <!-- Pie de mensaje -->
            <div style="padding-top: 14px; border-top: 1px solid #e2e8f0; color: #94a3b8; font-size: 11px; text-align: center;">
                Este correo fue generado automáticamente por el sistema {{ $datos['empresa'] ?? 'SoftSystem' }}.
            </div>
        </div>
    </div>
</body>
</html>
