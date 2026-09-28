@extends('layouts.app')
@section('title', 'Detalle de Movimientos de Caja')
@section('style')
<style>
    @font-face {
        font-family: "Cairo";
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url({{ asset("webfonts/Cairo-Bold.ttf") }}) format("truetype");
    }
    .font-cairo {
        font-family: 'Cairo', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    :root {
        --dash-primary: #0a4d36;
        --dash-primary-dark: #073827;
        --dash-primary-light: #eaf3ef;
        --dash-primary-border: #c8dfd5;
        --dash-accent: #b8860b;
        --dash-text-main: #1c2430;
        --dash-text-muted: #64748b;
        --dash-card-bg: #ffffff;
        --dash-border: #e2e8f0;
    }

    body.dark-mode {
        --dash-primary: #10b981;
        --dash-primary-dark: #059669;
        --dash-primary-light: #064e3b;
        --dash-primary-border: #047857;
        --dash-accent: #f59e0b;
        --dash-text-main: #f3f4f6;
        --dash-text-muted: #9ca3af;
        --dash-card-bg: #1f2937;
        --dash-border: #374151;
    }

    .caja-wrapper {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: var(--dash-text-main);
    }

    /* Page Header */
    .dash-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.25rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--dash-border);
    }
    .dash-header-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--dash-primary);
        margin: 0;
        line-height: 1.2;
    }
    .dash-header-subtitle {
        font-size: 0.925rem;
        color: var(--dash-text-muted);
        margin: 0.25rem 0 0;
    }

    /* KPI Cards */
    .kpi-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        padding: 1.15rem 1.25rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: calc(100% - 1.25rem);
    }
    .kpi-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.6rem;
    }
    .kpi-label {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--dash-text-muted);
    }
    .kpi-icon-box {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    .kpi-icon-green { background: #dcfce7; color: #166534; }
    .kpi-icon-red   { background: #fee2e2; color: #991b1b; }
    .kpi-icon-blue  { background: #e0f2fe; color: #0284c7; }

    .kpi-value {
        font-size: 1.55rem;
        font-weight: 800;
        color: var(--dash-text-main);
        line-height: 1.2;
        font-variant-numeric: tabular-nums;
    }
    .kpi-subtext {
        margin-top: 0.4rem;
        font-size: 0.8rem;
        color: var(--dash-text-muted);
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    /* Botones POS */
    .btn-pos-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 1.1rem;
        background: var(--dash-primary);
        border: 1px solid var(--dash-primary);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.88rem;
        border-radius: 8px;
        text-decoration: none !important;
        cursor: pointer;
    }
    .btn-pos-primary:hover {
        background: var(--dash-primary-dark);
        border-color: var(--dash-primary-dark);
    }
    .btn-pos-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 1rem;
        background: #ffffff;
        border: 1px solid var(--dash-border);
        color: var(--dash-text-main) !important;
        font-weight: 600;
        font-size: 0.88rem;
        border-radius: 8px;
        text-decoration: none !important;
        cursor: pointer;
    }
    .btn-pos-secondary:hover {
        background: var(--dash-primary-light);
        border-color: var(--dash-primary-border);
        color: var(--dash-primary) !important;
    }

    /* Tabla Contenedora */
    .table-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }
    .card-header-pos {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .card-header-pos h5 {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0;
        color: var(--dash-primary);
    }
    .table-custom {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    .table-custom thead th {
        background: #f8fafc;
        color: var(--dash-text-muted);
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1px solid var(--dash-border);
        border-top: none;
        padding: 0.85rem 1rem;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table-custom tbody tr:nth-of-type(odd) {
        background-color: #fafbfc;
    }
    .table-custom tbody tr:hover {
        background-color: var(--dash-primary-light) !important;
    }
    .table-custom tbody td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--dash-border);
        color: var(--dash-text-main);
        font-size: 0.9rem;
    }

    /* Badges */
    .badge-mov-in {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
        font-weight: 700;
        border-radius: 6px;
        padding: 0.2rem 0.5rem;
        font-size: 0.78rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    .badge-mov-out {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
        font-weight: 700;
        border-radius: 6px;
        padding: 0.2rem 0.5rem;
        font-size: 0.78rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Modo Oscuro */
    body.dark-mode .table-card {
        background-color: var(--dash-card-bg);
        border-color: var(--dash-border);
    }
    body.dark-mode .card-header-pos {
        background-color: var(--dash-card-bg) !important;
        border-color: var(--dash-border) !important;
    }
    body.dark-mode .table-custom thead th {
        background: #111827 !important;
        color: var(--dash-text-muted) !important;
        border-color: var(--dash-border) !important;
    }
    body.dark-mode .table-custom tbody tr {
        background-color: var(--dash-card-bg) !important;
    }
    body.dark-mode .table-custom tbody tr:nth-of-type(odd) {
        background-color: rgba(255, 255, 255, 0.02) !important;
    }
    body.dark-mode .table-custom tbody tr:hover {
        background-color: var(--dash-primary-light) !important;
    }
    body.dark-mode .table-custom tbody td {
        border-color: var(--dash-border) !important;
        color: var(--dash-text-main) !important;
    }
    body.dark-mode .btn-pos-secondary {
        background: #111827 !important;
        border-color: var(--dash-border) !important;
        color: var(--dash-text-main) !important;
    }

    @media print {
        .dash-header button,
        .dash-header a,
        .main-sidebar,
        .main-header {
            display: none !important;
        }
        body {
            background: #fff !important;
        }
        .table-card {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
        }
    }
</style>
@endsection

@section('main')
@php
    $nroOp = count($movimiento) > 0 ? $movimiento[0]->nro_operacion : '-';
    $entradas = collect($movimiento)->where('mov_tipo', 'Entrada')->sum('mov_monto');
    $salidas = collect($movimiento)->where('mov_tipo', 'Salida')->sum('mov_monto');
    $saldo = $entradas - $salidas;
@endphp

<div class="container-fluid px-3 py-3 caja-wrapper">
    <!-- Header -->
    <div class="dash-header">
        <div>
            <h1 class="dash-header-title font-cairo">
                <i class="fa-solid fa-receipt mr-2"></i>Informe de Movimientos de Caja
            </h1>
            <p class="dash-header-subtitle">
                @if (count($movimiento) > 0)
                    Operación Nº <strong>#{{ $nroOp }}</strong> — Resumen detallado de ingresos y egresos
                @else
                    Sin registros de movimientos en esta operación
                @endif
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn-pos-primary mr-2" onclick="window.print()">
                <i class="fa fa-print mr-1"></i> Imprimir Informe
            </button>
            <a href="{{ route('apertura') }}" class="btn-pos-secondary">
                <i class="fa fa-arrow-left mr-1"></i> Volver a Caja
            </a>
        </div>
    </div>

    <!-- 3 Tarjetas KPI -->
    <div class="row">
        <!-- 1: Total Entradas -->
        <div class="col-md-4 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #16a34a;">
                <div class="kpi-header">
                    <span class="kpi-label">Total Entradas</span>
                    <div class="kpi-icon-box kpi-icon-green">
                        <i class="fa-solid fa-arrow-down"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #166534;">
                    Gs. {{ number_format($entradas, 0, ',', '.') }}
                </div>
                <div class="kpi-subtext" style="color: #166534;">
                    <i class="fa-solid fa-circle-check"></i> Ingresos de turno
                </div>
            </div>
        </div>

        <!-- 2: Total Salidas -->
        <div class="col-md-4 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #dc2626;">
                <div class="kpi-header">
                    <span class="kpi-label">Total Salidas</span>
                    <div class="kpi-icon-box kpi-icon-red">
                        <i class="fa-solid fa-arrow-up"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #b91c1c;">
                    Gs. {{ number_format($salidas, 0, ',', '.') }}
                </div>
                <div class="kpi-subtext" style="color: #b91c1c;">
                    <i class="fa-solid fa-minus-circle"></i> Egresos de turno
                </div>
            </div>
        </div>

        <!-- 3: Saldo Neto -->
        <div class="col-md-4 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #0a4d36;">
                <div class="kpi-header">
                    <span class="kpi-label">Saldo Teórico en Turno</span>
                    <div class="kpi-icon-box kpi-icon-blue">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: var(--dash-primary);">
                    Gs. {{ number_format($saldo, 0, ',', '.') }}
                </div>
                <div class="kpi-subtext" style="color: var(--dash-primary);">
                    <i class="fa-solid fa-wallet"></i> Entradas menos salidas
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Detalle de Movimientos -->
    <div class="table-card mb-4">
        <div class="card-header-pos">
            <h5>
                <i class="fa-solid fa-list-check mr-2 text-primary"></i> Detalle Cronológico de Movimientos
            </h5>
            <span class="badge badge-light border font-weight-bold">
                {{ count($movimiento) }} movimientos
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th style="width: 130px;">Tipo</th>
                        <th style="width: 170px;">Fecha y Hora</th>
                        <th>Concepto / Descripción</th>
                        <th class="text-right" style="width: 170px;">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($movimiento as $i => $m)
                        <tr>
                            <td class="align-middle text-muted small">{{ $i + 1 }}</td>
                            <td class="align-middle">
                                @if ($m->mov_tipo == 'Entrada')
                                    <span class="badge-mov-in">
                                        <i class="fa fa-arrow-down"></i> Entrada
                                    </span>
                                @else
                                    <span class="badge-mov-out">
                                        <i class="fa fa-arrow-up"></i> Salida
                                    </span>
                                @endif
                            </td>
                            <td class="align-middle small">
                                <i class="fa fa-clock text-muted mr-1"></i> {{ $m->mov_fecha }}
                            </td>
                            <td class="align-middle font-weight-bold">
                                {{ $m->mov_concepto }}
                            </td>
                            <td class="align-middle text-right font-weight-bold font-cairo {{ $m->mov_tipo == 'Entrada' ? 'text-success' : 'text-danger' }}">
                                @if ($m->mov_tipo == 'Salida' && $m->mov_monto > 0)-@endif Gs. {{ number_format($m->mov_monto, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fa fa-receipt fa-3x mb-3 text-secondary opacity-50"></i>
                                <h5 class="font-weight-bold">Sin movimientos registrados</h5>
                                <p class="small mb-0">No se encontraron movimientos para esta operación de caja.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if (count($movimiento) > 0)
                    <tfoot class="bg-light font-weight-bold">
                        <tr>
                            <td colspan="4" class="text-right">Total Entradas:</td>
                            <td class="text-right text-success font-cairo">Gs. {{ number_format($entradas, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right">Total Salidas:</td>
                            <td class="text-right text-danger font-cairo">Gs. {{ number_format($salidas, 0, ',', '.') }}</td>
                        </tr>
                        <tr style="border-top: 2px solid var(--dash-border);">
                            <td colspan="4" class="text-right" style="color: var(--dash-primary);">Saldo Teórico Final:</td>
                            <td class="text-right font-cairo" style="color: var(--dash-primary); font-size: 1.1rem;">
                                Gs. {{ number_format($saldo, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    activarMenu('m_caja', 'm_apertura');
</script>
@endsection
