<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Cierre de caja</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; color: #222; background: #f6f7f9; margin: 0; padding: 24px;">
    <div style="max-width: 680px; margin: 0 auto; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;">
        <div style="background: #1f2937; color: #fff; padding: 16px 20px;">
            <div style="font-size: 18px; font-weight: bold;">{{ $resumen['empresa'] ?? 'SoftSystem' }}</div>
            <div style="opacity: .85; font-size: 13px;">Resumen de cierre de caja</div>
        </div>

        <div style="padding: 20px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px; margin-bottom: 16px;">
                <tr>
                    <td style="padding: 6px 0; color: #6b7280;">Operación</td>
                    <td style="padding: 6px 0; text-align: right;"><strong>#{{ $resumen['nro_operacion'] }}</strong></td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #6b7280;">Sucursal</td>
                    <td style="padding: 6px 0; text-align: right;">{{ $resumen['sucursal'] }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #6b7280;">Caja</td>
                    <td style="padding: 6px 0; text-align: right;">{{ $resumen['caja'] }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #6b7280;">Usuario</td>
                    <td style="padding: 6px 0; text-align: right;">{{ $resumen['usuario'] }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #6b7280;">Apertura</td>
                    <td style="padding: 6px 0; text-align: right;">{{ $resumen['apertura_fecha'] }} {{ $resumen['apertura_hora'] }}</td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; color: #6b7280;">Cierre</td>
                    <td style="padding: 6px 0; text-align: right;">{{ $resumen['cierre_fecha'] }} {{ $resumen['cierre_hora'] }}</td>
                </tr>
            </table>

            <div style="display: block; margin: 12px 0 18px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; padding: 10px; background: #f3f4f6; border-radius: 6px;">
                            <div style="font-size: 12px; color: #6b7280;">Monto apertura</div>
                            <div style="font-size: 16px; font-weight: bold;">Gs. {{ number_format($resumen['monto_apertura'], 0, ',', '.') }}</div>
                        </td>
                        <td style="width: 8px;"></td>
                        <td style="width: 50%; padding: 10px; background: #ecfdf5; border-radius: 6px;">
                            <div style="font-size: 12px; color: #6b7280;">Entradas</div>
                            <div style="font-size: 16px; font-weight: bold; color: #059669;">Gs. {{ number_format($resumen['entradas'], 0, ',', '.') }}</div>
                        </td>
                    </tr>
                    <tr><td colspan="3" style="height: 8px;"></td></tr>
                    <tr>
                        <td style="padding: 10px; background: #fef2f2; border-radius: 6px;">
                            <div style="font-size: 12px; color: #6b7280;">Salidas</div>
                            <div style="font-size: 16px; font-weight: bold; color: #dc2626;">Gs. {{ number_format($resumen['salidas'], 0, ',', '.') }}</div>
                        </td>
                        <td></td>
                        <td style="padding: 10px; background: #eff6ff; border-radius: 6px;">
                            <div style="font-size: 12px; color: #6b7280;">Esperado</div>
                            <div style="font-size: 16px; font-weight: bold; color: #2563eb;">Gs. {{ number_format($resumen['esperado'], 0, ',', '.') }}</div>
                        </td>
                    </tr>
                    <tr><td colspan="3" style="height: 8px;"></td></tr>
                    <tr>
                        <td style="padding: 10px; background: #f9fafb; border-radius: 6px;">
                            <div style="font-size: 12px; color: #6b7280;">Contado</div>
                            <div style="font-size: 16px; font-weight: bold;">Gs. {{ number_format($resumen['contado'], 0, ',', '.') }}</div>
                        </td>
                        <td></td>
                        <td style="padding: 10px; background: {{ $resumen['diferencia'] == 0 ? '#ecfdf5' : ($resumen['diferencia'] < 0 ? '#fef2f2' : '#eff6ff') }}; border-radius: 6px;">
                            <div style="font-size: 12px; color: #6b7280;">Diferencia</div>
                            <div style="font-size: 16px; font-weight: bold;">
                                Gs. {{ ($resumen['diferencia'] >= 0 ? '+' : '') . number_format($resumen['diferencia'], 0, ',', '.') }}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="font-size: 14px; font-weight: bold; margin: 8px 0;">Movimientos del turno ({{ count($resumen['movimientos']) }})</div>
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <thead>
                    <tr style="background: #111827; color: #fff;">
                        <th style="text-align: left; padding: 8px;">Fecha</th>
                        <th style="text-align: left; padding: 8px;">Tipo</th>
                        <th style="text-align: left; padding: 8px;">Concepto</th>
                        <th style="text-align: right; padding: 8px;">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($resumen['movimientos'] as $m)
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 7px 8px;">{{ $m['fecha'] }}</td>
                            <td style="padding: 7px 8px; color: {{ $m['tipo'] === 'Entrada' ? '#059669' : '#dc2626' }};">{{ $m['tipo'] }}</td>
                            <td style="padding: 7px 8px;">{{ $m['concepto'] }}</td>
                            <td style="padding: 7px 8px; text-align: right;">
                                Gs. {{ ($m['tipo'] === 'Salida' ? '-' : '') . number_format($m['monto'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 12px; text-align: center; color: #6b7280;">Sin movimientos</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding: 12px 20px; background: #f9fafb; color: #6b7280; font-size: 12px;">
            Enviado automáticamente por SoftSystem al cerrar la caja.
        </div>
    </div>
</body>
</html>
