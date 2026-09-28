@extends('layouts.app')
@section('title', 'Apertura y Cierre de Caja')
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
        --dash-accent-light: #fef8eb;
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
        --dash-accent-light: #451a03;
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
    .dash-header-badges {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
    }

    /* Status Pills */
    .badge-status-open {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 0.9rem;
        border-radius: 999px;
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 0.02em;
    }
    .badge-status-closed {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 0.9rem;
        border-radius: 999px;
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 0.02em;
    }
    .status-dot-pulse {
        width: 9px;
        height: 9px;
        background-color: #16a34a;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7);
        animation: pulse-green 1.8s infinite;
    }
    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 7px rgba(22, 163, 74, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
    }

    /* KPI Cards */
    .kpi-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        padding: 1.15rem 1.25rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: calc(100% - 1.25rem);
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0,0,0,0.06);
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
    .kpi-icon-green  { background: #dcfce7; color: #166534; }
    .kpi-icon-blue   { background: #e0f2fe; color: #0284c7; }
    .kpi-icon-amber  { background: #fef3c7; color: #b45309; }
    .kpi-icon-purple { background: #f3e8ff; color: #7e22ce; }

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

    /* Tarjetas de Turno Principal */
    .turn-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        margin-bottom: 1.5rem;
    }
    .turn-card.active-turn {
        border-top: 5px solid var(--dash-primary);
    }
    .turn-card.closed-turn {
        border-top: 5px solid #f59e0b;
    }
    .turn-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.1rem 1.4rem;
        border-bottom: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
    }
    .turn-body {
        padding: 1.4rem;
    }
    .turn-footer {
        padding: 1.1rem 1.4rem;
        border-top: 1px solid var(--dash-border);
        background: #f8fafc;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }

    /* Bloque Monto Grande */
    .monto-display-box {
        background: var(--dash-primary-light);
        border: 1px solid var(--dash-primary-border);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .monto-display-label {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--dash-primary-dark);
    }
    .monto-display-value {
        font-size: 2.1rem;
        font-weight: 800;
        color: var(--dash-primary);
        line-height: 1;
        font-variant-numeric: tabular-nums;
    }

    /* Detalle Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .info-item {
        background: #f8fafc;
        border: 1px solid var(--dash-border);
        border-radius: 8px;
        padding: 0.75rem 1rem;
    }
    .info-item-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--dash-text-muted);
        letter-spacing: 0.04em;
        margin-bottom: 0.2rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .info-item-val {
        font-size: 1rem;
        font-weight: 700;
        color: var(--dash-text-main);
        word-break: break-word;
    }

    /* Inputs y Formularios de Apertura */
    .form-label-custom {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--dash-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0.35rem;
        display: block;
    }
    .form-control-pos {
        border-radius: 8px;
        border: 1px solid var(--dash-border);
        font-size: 0.95rem;
        padding: 0.55rem 0.85rem;
        color: var(--dash-text-main);
        background-color: var(--dash-card-bg);
        transition: all 0.15s;
        height: auto;
    }
    .form-control-pos:focus {
        border-color: var(--dash-primary);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        background-color: var(--dash-card-bg);
        color: var(--dash-text-main);
    }
    .input-monto-apertura {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--dash-primary);
        letter-spacing: -0.5px;
    }

    /* Botones de sugerencia rápida de monto */
    .quick-amount-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-top: 0.6rem;
    }
    .btn-quick-amount {
        background: #f1f5f9;
        border: 1px solid var(--dash-border);
        color: var(--dash-text-main);
        border-radius: 6px;
        padding: 0.25rem 0.65rem;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-quick-amount:hover {
        background: var(--dash-primary-light);
        border-color: var(--dash-primary-border);
        color: var(--dash-primary);
    }

    /* Botones POS */
    .btn-pos-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.65rem 1.4rem;
        background: var(--dash-primary);
        border: 1px solid var(--dash-primary);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.95rem;
        border-radius: 8px;
        transition: all 0.15s ease-in-out;
        text-decoration: none !important;
        box-shadow: 0 2px 5px rgba(10, 77, 54, 0.15);
        cursor: pointer;
    }
    .btn-pos-primary:hover, .btn-pos-primary:focus {
        background: var(--dash-primary-dark);
        border-color: var(--dash-primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(10, 77, 54, 0.25);
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
        transition: all 0.15s;
        cursor: pointer;
    }
    .btn-pos-secondary:hover {
        background: var(--dash-primary-light);
        border-color: var(--dash-primary-border);
        color: var(--dash-primary) !important;
    }
    .btn-pos-danger {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.6rem 1.25rem;
        background: #dc2626;
        border: 1px solid #dc2626;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.92rem;
        border-radius: 8px;
        text-decoration: none !important;
        transition: all 0.15s;
        cursor: pointer;
    }
    .btn-pos-danger:hover {
        background: #b91c1c;
        border-color: #b91c1c;
    }

    /* Tabla Contenedora e Historial */
    .table-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }
    .table-toolbar-head {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
    }
    .table-custom {
        min-width: 880px;
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
    .table-custom tbody tr {
        transition: background-color 0.15s;
    }
    .table-custom tbody tr:nth-of-type(odd) {
        background-color: #fafbfc;
    }
    .table-custom tbody tr:hover {
        background-color: var(--dash-primary-light) !important;
    }
    .table-custom tbody td {
        padding: 0.8rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--dash-border);
        color: var(--dash-text-main);
        font-size: 0.9rem;
    }
    .table-custom tbody tr.active-row td {
        background-color: rgba(16, 185, 129, 0.08) !important;
    }

    /* Badges */
    .badge-op-number {
        font-family: 'SFMono-Regular', Consolas, Menlo, monospace;
        font-weight: 700;
        color: var(--dash-primary);
        background: var(--dash-primary-light);
        border: 1px solid var(--dash-primary-border);
        border-radius: 6px;
        padding: 0.2rem 0.5rem;
        font-size: 0.85rem;
    }
    .badge-status-chip {
        font-weight: 700;
        border-radius: 6px;
        padding: 0.25rem 0.6rem;
        font-size: 0.78rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .badge-status-chip.is-open {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .badge-status-chip.is-closed {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    /* Paginación */
    .table-pagination-footer {
        padding: 0.85rem 1.25rem;
        border-top: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    /* Dark Mode Adjustments */
    body.dark-mode .turn-card,
    body.dark-mode .table-card {
        background-color: var(--dash-card-bg);
        border-color: var(--dash-border);
    }
    body.dark-mode .turn-header,
    body.dark-mode .table-toolbar-head,
    body.dark-mode .table-pagination-footer {
        background-color: var(--dash-card-bg) !important;
        border-color: var(--dash-border) !important;
        color: var(--dash-text-main) !important;
    }
    body.dark-mode .turn-footer {
        background-color: #111827 !important;
        border-color: var(--dash-border) !important;
    }
    body.dark-mode .info-item {
        background-color: #111827 !important;
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
    body.dark-mode .btn-quick-amount {
        background: #111827;
        color: #cbd5e1;
        border-color: var(--dash-border);
    }
    body.dark-mode .badge-status-chip.is-closed {
        background: #111827;
        color: #94a3b8;
        border-color: #374151;
    }
    body.dark-mode .btn-pos-secondary {
        background: #111827 !important;
        border-color: var(--dash-border) !important;
        color: var(--dash-text-main) !important;
    }
    body.dark-mode .btn-pos-secondary:hover {
        background: var(--dash-primary-light) !important;
        border-color: var(--dash-primary-border) !important;
        color: #34d399 !important;
    }
</style>
@endsection

@section('main')
@php
    $nombreLocal = trim((string) optional($empresa)->emp_nombre);
    $tituloHoja = $nombreLocal !== '' ? $nombreLocal : 'Apertura y Cierre de Caja';
    $sucursalSeleccionada = optional($cajaAbierta)->suc_cod
        ?? (request()->filled('sucursal') ? request('sucursal') : optional($sucursales->first())['suc_cod']);
@endphp

<div class="container-fluid px-3 py-3 caja-wrapper">
    <!-- Feedback de Sesión con SweetAlert2 -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: '¡Operación Exitosa!',
                    text: @json(session('success')),
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#0a4d36'
                });
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Atención',
                    text: @json(session('error')),
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#0a4d36'
                });
            });
        </script>
    @endif
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Revisá los datos ingresados',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#0a4d36'
                });
            });
        </script>
    @endif

    <!-- Cabecera estilo Dashboard -->
    <div class="dash-header">
        <div>
            <h1 class="dash-header-title font-cairo">
                <i class="fa-solid fa-cash-register mr-2"></i>Apertura y Cierre de Caja
            </h1>
            <p class="dash-header-subtitle">
                {{ $tituloHoja }} — Control de turnos, arqueo de fondos iniciales y cierre diario
            </p>
        </div>
        <div class="dash-header-badges">
            @if ($cajaAbierta)
                <span class="badge-status-open">
                    <span class="status-dot-pulse"></span>
                    Turno Activo #{{ $cajaAbierta->nro_operacion }}
                </span>
                <a href="{{ route('cierre', $cajaAbierta->nro_operacion) }}" class="btn-pos-primary" title="Cerrar turno y realizar arqueo">
                    <i class="fa fa-lock mr-1"></i> Cerrar Caja
                </a>
            @else
                <span class="badge-status-closed">
                    <i class="fa fa-lock text-warning mr-1"></i>
                    Caja Cerrada (Sin turno activo)
                </span>
            @endif
        </div>
    </div>

    <!-- Indicadores / KPIs de Caja -->
    <div class="row">
        <!-- KPI 1: Estado del Turno -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid {{ $cajaAbierta ? '#16a34a' : '#d97706' }};">
                <div class="kpi-header">
                    <span class="kpi-label">Estado de Caja</span>
                    <div class="kpi-icon-box {{ $cajaAbierta ? 'kpi-icon-green' : 'kpi-icon-amber' }}">
                        <i class="fa-solid {{ $cajaAbierta ? 'fa-door-open' : 'fa-lock' }}"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: {{ $cajaAbierta ? '#166534' : '#b45309' }};">
                    {{ $cajaAbierta ? 'Abierta' : 'Cerrada' }}
                </div>
                <div class="kpi-subtext">
                    @if ($cajaAbierta)
                        <i class="fa-solid fa-circle-check text-success"></i> Operación #{{ $cajaAbierta->nro_operacion }} en curso
                    @else
                        <i class="fa-solid fa-clock text-muted"></i> Esperando apertura de turno
                    @endif
                </div>
            </div>
        </div>

        <!-- KPI 2: Fondo de Apertura -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #0284c7;">
                <div class="kpi-header">
                    <span class="kpi-label">Fondo Inicial Turno</span>
                    <div class="kpi-icon-box kpi-icon-blue">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #0284c7;">
                    Gs. {{ number_format(optional($cajaAbierta)->apert_monto ?? 0, 0, ',', '.') }}
                </div>
                <div class="kpi-subtext">
                    <i class="fa-solid fa-wallet text-muted"></i> Fondo para cambio y vuelto
                </div>
            </div>
        </div>

        <!-- KPI 3: Sucursal & Caja Activa -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #7e22ce;">
                <div class="kpi-header">
                    <span class="kpi-label">Caja Asignada</span>
                    <div class="kpi-icon-box kpi-icon-purple">
                        <i class="fa-solid fa-building-user"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="font-size: 1.25rem;">
                    {{ optional($cajaAbierta)->caja_descrip ?? 'Caja Principal' }}
                </div>
                <div class="kpi-subtext">
                    <i class="fa-solid fa-shop text-muted"></i> {{ optional($cajaAbierta)->suc_desc ?? 'Sucursal activa' }}
                </div>
            </div>
        </div>

        <!-- KPI 4: Historial de Turnos -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #0a4d36;">
                <div class="kpi-header">
                    <span class="kpi-label">Total Turnos</span>
                    <div class="kpi-icon-box kpi-icon-green">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo">
                    {{ $aperturas->total() }}
                </div>
                <div class="kpi-subtext">
                    <i class="fa-solid fa-database text-muted"></i> Operaciones en historial
                </div>
            </div>
        </div>
    </div>

    <!-- Sección Principal: Turno Activo vs Formulario de Apertura -->
    @if ($cajaAbierta)
        <!-- Caso 1: CAJA ABIERTA (Panel de Control del Turno) -->
        <div class="turn-card active-turn">
            <div class="turn-header">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <span class="badge-status-chip is-open">
                            <span class="status-dot-pulse mr-1"></span> EN CURSO
                        </span>
                    </div>
                    <div>
                        <h4 class="font-weight-bold font-cairo mb-0 text-success">
                            Turno de Caja Activo · Operación #{{ $cajaAbierta->nro_operacion }}
                        </h4>
                        <span class="text-muted small">
                            Iniciado el {{ date('d/m/Y', strtotime($cajaAbierta->apert_fecha)) }} a las {{ $cajaAbierta->apert_hora }} por <strong>{{ trim($cajaAbierta->nom_usuarios) }}</strong>
                        </span>
                    </div>
                </div>
                <div class="d-none d-md-block">
                    <a href="{{ route('cierre', $cajaAbierta->nro_operacion) }}" class="btn-pos-danger">
                        <i class="fa fa-lock mr-1"></i> Cerrar Caja Ahora
                    </a>
                </div>
            </div>
            <div class="turn-body">
                <!-- Monto destacado -->
                <div class="monto-display-box">
                    <div>
                        <div class="monto-display-label">Monto de Apertura en Efectivo</div>
                        <small class="text-muted">Fondo asignado para inicio de operaciones</small>
                    </div>
                    <div class="monto-display-value font-cairo">
                        Gs. {{ number_format($cajaAbierta->apert_monto, 0, ',', '.') }}
                    </div>
                </div>

                <!-- Datos del Turno -->
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-item-label">
                            <i class="fa fa-building text-primary"></i> Sucursal
                        </div>
                        <div class="info-item-val">{{ $cajaAbierta->suc_desc }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-label">
                            <i class="fa fa-cash-register text-info"></i> Caja
                        </div>
                        <div class="info-item-val">{{ $cajaAbierta->caja_descrip }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-label">
                            <i class="fa fa-user-check text-success"></i> Cajero Responsable
                        </div>
                        <div class="info-item-val">{{ trim($cajaAbierta->nom_usuarios) }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-item-label">
                            <i class="fa fa-clock text-warning"></i> Fecha y Hora Apertura
                        </div>
                        <div class="info-item-val">
                            {{ date('d/m/Y', strtotime($cajaAbierta->apert_fecha)) }} {{ $cajaAbierta->apert_hora }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="turn-footer">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <a href="{{ route('caja.informe', $cajaAbierta->nro_operacion) }}" class="btn-pos-secondary mr-2">
                        <i class="fa fa-list-check text-info mr-1"></i> Ver Movimientos de Turno
                    </a>
                    <a href="{{ route('venta') }}" class="btn-pos-secondary">
                        <i class="fa fa-shopping-cart text-success mr-1"></i> Ir al Punto de Venta (POS)
                    </a>
                </div>
                <div>
                    <a href="{{ route('cierre', $cajaAbierta->nro_operacion) }}" class="btn-pos-primary">
                        <i class="fa fa-calculator mr-1"></i> Proceder al Arqueo y Cierre
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Caso 2: CAJA CERRADA (Formulario de Nueva Apertura) -->
        <div class="turn-card closed-turn">
            <div class="turn-header">
                <div>
                    <h4 class="font-weight-bold font-cairo mb-0" style="color: var(--dash-primary);">
                        <i class="fa-solid fa-door-open mr-2 text-success"></i> Iniciar Nuevo Turno de Caja
                    </h4>
                    <span class="text-muted small">
                        Ingresá el monto de fondo inicial en efectivo y seleccioná sucursal y caja para comenzar.
                    </span>
                </div>
                <div>
                    <span class="badge badge-light border py-1 px-2 text-muted">
                        <i class="fa fa-user mr-1"></i> {{ Auth::user()->nom_usuarios }}
                    </span>
                </div>
            </div>
            <form method="POST" action="{{ route('apertura.add') }}" id="formAperturaCaja">
                @csrf
                <input type="hidden" value="{{ Auth::user()->cod_usuarios }}" name="usuario">
                <div class="turn-body">
                    <div class="row">
                        <!-- Columna Izquierda: Monto -->
                        <div class="col-lg-6 mb-3 mb-lg-0">
                            <label class="form-label-custom" for="monto">
                                Monto Inicial de Apertura (Gs.) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light font-weight-bold text-success" style="font-size: 1.2rem;">Gs.</span>
                                </div>
                                <input
                                    type="number"
                                    name="monto"
                                    id="monto"
                                    class="form-control form-control-pos input-monto-apertura font-cairo"
                                    placeholder="0"
                                    min="0"
                                    step="1"
                                    required
                                    value="{{ old('monto') }}"
                                    inputmode="numeric"
                                    autocomplete="off"
                                    autofocus
                                />
                            </div>
                            <small class="text-muted d-block mt-1">Fondo para dar cambio o vuelto a los clientes.</small>

                            <!-- Atajos Rápidos de Monto -->
                            <div class="quick-amount-wrap">
                                <span class="text-muted small align-self-center mr-1">Sugerencias:</span>
                                <button type="button" class="btn-quick-amount" onclick="setMontoApertura(0)">Gs. 0</button>
                                <button type="button" class="btn-quick-amount" onclick="setMontoApertura(100000)">100.000</button>
                                <button type="button" class="btn-quick-amount" onclick="setMontoApertura(200000)">200.000</button>
                                <button type="button" class="btn-quick-amount" onclick="setMontoApertura(300000)">300.000</button>
                                <button type="button" class="btn-quick-amount" onclick="setMontoApertura(500000)">500.000</button>
                                <button type="button" class="btn-quick-amount" onclick="setMontoApertura(1000000)">1.000.000</button>
                            </div>
                        </div>

                        <!-- Columna Derecha: Sucursal y Caja -->
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-custom" for="selsucursal">Sucursal</label>
                                    <select name="sucursal" id="selsucursal" class="form-control form-control-pos" required>
                                        @foreach ($sucursales as $sucursal)
                                            <option value="{{ $sucursal['suc_cod'] }}"
                                                {{ (string) $sucursalSeleccionada === (string) $sucursal['suc_cod'] ? 'selected' : '' }}>
                                                {{ $sucursal['suc_desc'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label-custom" for="selcaja">Caja</label>
                                    <select name="caja" id="selcaja" class="form-control form-control-pos" required>
                                        @foreach ($cajas as $caja)
                                            <option value="{{ $caja['caja_cod'] }}">{{ $caja['caja_descrip'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="info-item mt-1">
                                <div class="info-item-label">
                                    <i class="fa fa-info-circle text-primary"></i> Información del Turno
                                </div>
                                <span class="small text-muted">
                                    El turno se registrará a nombre de <strong>{{ Auth::user()->nom_usuarios }}</strong> con fecha de hoy ({{ date('d/m/Y') }}).
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="turn-footer justify-content-end">
                    <button type="submit" class="btn-pos-primary btn-lg" id="btn-abrir">
                        <i class="fa-solid fa-key mr-2"></i> Abrir Turno de Caja
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Historial de Turnos de Caja (Tabla Estilo POS) -->
    <div class="table-card mb-4">
        <!-- Toolbar con Filtros de Búsqueda -->
        <div class="table-toolbar-head">
            <div>
                <h5 class="font-weight-bold font-cairo mb-0 text-success">
                    <i class="fa-solid fa-clock-rotate-left mr-2"></i> Historial de Aperturas y Cierres
                </h5>
                <span class="text-muted small">
                    Mostrando <strong>{{ $aperturas->count() }}</strong> de <strong>{{ $aperturas->total() }}</strong> turnos registrados
                </span>
            </div>
            <div>
                <button class="btn btn-sm btn-outline-secondary" type="button" data-toggle="collapse" data-target="#collapseFiltros" aria-expanded="false" aria-controls="collapseFiltros">
                    <i class="fa fa-filter mr-1"></i> Filtros de Historial
                </button>
            </div>
        </div>

        <!-- Formulario Colapsable de Filtros -->
        <div class="collapse {{ request()->hasAny(['sucursal', 'estado', 'desde', 'hasta']) ? 'show' : '' }}" id="collapseFiltros">
            <div class="p-3 bg-light border-bottom">
                <form method="GET" action="{{ route('apertura') }}" class="row align-items-end">
                    <div class="col-md-3 mb-2">
                        <label class="form-label-custom">Sucursal</label>
                        <select name="sucursal" class="form-control form-control-sm form-control-pos">
                            <option value="">Todas las Sucursales</option>
                            @foreach ($sucursales as $sucursal)
                                <option value="{{ $sucursal['suc_cod'] }}"
                                    {{ (string) request('sucursal') === (string) $sucursal['suc_cod'] ? 'selected' : '' }}>
                                    {{ $sucursal['suc_desc'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label-custom">Estado</label>
                        <select name="estado" class="form-control form-control-sm form-control-pos">
                            <option value="all" {{ request('estado', 'all') === 'all' ? 'selected' : '' }}>Todos</option>
                            <option value="1" {{ request('estado') === '1' ? 'selected' : '' }}>Abiertas</option>
                            <option value="0" {{ request('estado') === '0' ? 'selected' : '' }}>Cerradas</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label-custom">Desde</label>
                        <input type="date" name="desde" value="{{ request('desde') }}" class="form-control form-control-sm form-control-pos">
                    </div>
                    <div class="col-md-2 mb-2">
                        <label class="form-label-custom">Hasta</label>
                        <input type="date" name="hasta" value="{{ request('hasta') }}" class="form-control form-control-sm form-control-pos">
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn-pos-primary py-1 px-3 font-weight-bold" style="font-size: 0.85rem;">
                            <i class="fa fa-filter mr-1"></i> Aplicar Filtros
                        </button>
                        <a href="{{ route('apertura') }}" class="btn-pos-secondary py-1 px-3 ml-1" style="font-size: 0.85rem;">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla Responsiva de Turnos -->
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th style="width: 100px;">Nº Turno</th>
                        <th>Sucursal / Caja</th>
                        <th>Responsable / Usuario</th>
                        <th class="text-right">Fondo Apertura</th>
                        <th>Fecha y Hora</th>
                        <th>Estado</th>
                        <th class="text-center" style="width: 130px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($aperturas as $apertura)
                        <tr class="{{ $apertura['apert_estado'] == '1' ? 'active-row' : '' }}">
                            <!-- Nº Operación -->
                            <td class="align-middle">
                                <span class="badge-op-number">
                                    #{{ $apertura['nro_operacion'] }}
                                </span>
                            </td>

                            <!-- Sucursal & Caja -->
                            <td class="align-middle">
                                <div class="font-weight-bold">{{ $apertura['suc_desc'] }}</div>
                                <small class="text-muted"><i class="fa fa-cash-register mr-1"></i>{{ $apertura['caja_descrip'] }}</small>
                            </td>

                            <!-- Responsable -->
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm mr-2 text-primary font-weight-bold">
                                        <i class="fa fa-user-circle fa-lg"></i>
                                    </div>
                                    <span>{{ $apertura['nom_usuarios'] }}</span>
                                </div>
                            </td>

                            <!-- Monto Apertura -->
                            <td class="align-middle text-right">
                                <span class="font-weight-bold font-cairo text-success" style="font-size: 1rem;">
                                    Gs. {{ number_format($apertura['apert_monto'], 0, ',', '.') }}
                                </span>
                            </td>

                            <!-- Fecha y Hora -->
                            <td class="align-middle">
                                <div><i class="fa fa-calendar-alt text-muted mr-1"></i> {{ date('d/m/Y', strtotime($apertura['apert_fecha'])) }}</div>
                                <small class="text-muted"><i class="fa fa-clock mr-1"></i> {{ $apertura['apert_hora'] }}</small>
                            </td>

                            <!-- Estado -->
                            <td class="align-middle">
                                @if ($apertura['apert_estado'] == '1')
                                    <span class="badge-status-chip is-open">
                                        <span class="status-dot-pulse mr-1"></span> Abierta
                                    </span>
                                @else
                                    <span class="badge-status-chip is-closed">
                                        <i class="fa fa-check mr-1"></i> Cerrada
                                    </span>
                                @endif
                            </td>

                            <!-- Acciones -->
                            <td class="align-middle text-center">
                                @if ($apertura['apert_estado'] == '1')
                                    <a class="btn btn-sm btn-success font-weight-bold px-2 py-1" href="{{ route('cierre', $apertura['nro_operacion']) }}" title="Proceder al arqueo y cierre">
                                        <i class="fa fa-lock mr-1"></i> Cerrar
                                    </a>
                                @else
                                    <a class="btn btn-sm btn-outline-secondary font-weight-bold px-2 py-1" href="{{ route('caja.informe', $apertura['nro_operacion']) }}" title="Ver movimientos e informe">
                                        <i class="fa fa-eye mr-1"></i> Detalle
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fa fa-cash-register fa-3x mb-3 text-secondary opacity-50"></i>
                                    <h5 class="font-weight-bold">No hay turnos registrados</h5>
                                    <p class="small mb-0">No se encontraron aperturas con los filtros seleccionados.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if ($aperturas->hasPages())
            <div class="table-pagination-footer">
                <div class="small text-muted">
                    Página <strong>{{ $aperturas->currentPage() }}</strong> de <strong>{{ $aperturas->lastPage() }}</strong>
                </div>
                <div>
                    {{ $aperturas->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('script')
<script>
    function setMontoApertura(val) {
        var montoInput = document.getElementById('monto');
        if (montoInput) {
            montoInput.value = val;
            montoInput.focus();
        }
    }

    (function () {
        var form = document.getElementById('formAperturaCaja');
        var monto = document.getElementById('monto');
        var boton = document.getElementById('btn-abrir');

        if (form) {
            form.addEventListener('submit', function (e) {
                if (form.dataset.abriendo) {
                    e.preventDefault();
                    return;
                }
                if (!monto || monto.value === '' || Number(monto.value) < 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Monto inválido',
                        text: 'Ingresá el monto de apertura.',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#0a4d36'
                    });
                    return;
                }
                form.dataset.abriendo = '1';
                if (boton) {
                    boton.disabled = true;
                    boton.innerHTML = '<span class="spinner-border spinner-border-sm mr-2" role="status"></span> Abriendo turno...';
                }
            });
        }

        activarMenu('m_caja', 'm_apertura');
    })();
</script>
@endsection
