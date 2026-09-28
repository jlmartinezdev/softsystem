@extends('layouts.app')
@section('title', 'Informe de Ventas')
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

    .informe-wrapper {
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

    /* KPI Cards */
    .kpi-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        padding: 1.1rem 1.25rem;
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
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--dash-text-muted);
        margin: 0;
    }
    .kpi-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    .kpi-icon-ventas { background: #eff6ff; color: #2563eb; }
    .kpi-icon-contado { background: #ecfdf5; color: #059669; }
    .kpi-icon-credito { background: #fffbeb; color: #d97706; }
    .kpi-icon-ticket { background: #f5f3ff; color: #7c3aed; }
    .kpi-icon-art { background: #e0f2fe; color: #0284c7; }

    .kpi-value {
        font-size: 1.65rem;
        font-weight: 700;
        line-height: 1.15;
        margin-bottom: 0.35rem;
        font-variant-numeric: tabular-nums;
    }
    .kpi-footer {
        font-size: 0.82rem;
        color: var(--dash-text-muted);
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    /* Container Cards */
    .table-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    /* Modern Tabs */
    .nav-tabs-modern {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        padding: 0.75rem 1rem 0;
        background: #f8fafc;
        border-bottom: 1px solid var(--dash-border);
        list-style: none;
        margin-bottom: 0;
    }
    .nav-tabs-modern .nav-item {
        margin-bottom: -1px;
    }
    .nav-tabs-modern .nav-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 1.15rem;
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--dash-text-muted);
        background: transparent;
        border: 1px solid transparent;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        transition: all 0.15s ease;
        text-decoration: none;
        cursor: pointer;
    }
    .nav-tabs-modern .nav-link:hover {
        color: var(--dash-primary);
        background: rgba(10, 77, 54, 0.04);
    }
    .nav-tabs-modern .nav-link.active {
        color: var(--dash-primary);
        background: var(--dash-card-bg);
        border-color: var(--dash-border);
        border-bottom-color: var(--dash-card-bg);
        font-weight: 700;
        box-shadow: 0 -2px 5px rgba(0,0,0,0.02);
    }
    .tab-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.15rem 0.5rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        background: #e2e8f0;
        color: #475569;
    }
    .nav-tabs-modern .nav-link.active .tab-badge {
        background: var(--dash-primary-light);
        color: var(--dash-primary);
    }

    /* Filter Toolbar */
    .filter-section {
        background: #fdfdfd;
        border-bottom: 1px solid var(--dash-border);
        padding: 1.1rem 1.25rem;
    }
    .filter-label {
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--dash-text-muted);
        margin-bottom: 0.35rem;
        display: block;
    }
    .preset-pill {
        border: 1px solid var(--dash-border);
        background: #ffffff;
        color: var(--dash-text-muted);
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.3rem 0.75rem;
        border-radius: 999px;
        transition: all 0.15s;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        user-select: none;
    }
    .preset-pill:hover, .preset-pill.active {
        background: var(--dash-primary-light);
        border-color: var(--dash-primary-border);
        color: var(--dash-primary);
    }

    /* Buttons */
    .btn-pos-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 1.15rem;
        background: var(--dash-primary);
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.88rem;
        border: none;
        border-radius: 8px;
        text-decoration: none !important;
        transition: all 0.15s;
        cursor: pointer;
    }
    .btn-pos-primary:hover {
        background: var(--dash-primary-dark);
        box-shadow: 0 2px 6px rgba(10, 77, 54, 0.3);
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

    /* Badges & Tables */
    .badge-code {
        font-family: monospace;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        padding: 0.2rem 0.45rem;
        border-radius: 6px;
        font-size: 0.85rem;
    }
    .badge-contado {
        background: #dcfce7;
        color: #166534;
        font-weight: 600;
        font-size: 0.78rem;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
    }
    .badge-credito {
        background: #fef3c7;
        color: #92400e;
        font-weight: 600;
        font-size: 0.78rem;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
    }

    /* Rankings */
    .badge-rank {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.78rem;
    }
    .rank-1 { background: #fef08a; color: #854d0e; }
    .rank-2 { background: #e2e8f0; color: #475569; }
    .rank-3 { background: #fed7aa; color: #9a3412; }
    .rank-other { background: #f1f5f9; color: #64748b; }

    /* Custom Tables */
    .table-modern {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    .table-modern thead th {
        background: #f8fafc;
        color: var(--dash-text-muted);
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1px solid var(--dash-border);
        border-top: none;
        padding: 0.75rem 0.9rem;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table-modern tbody tr:nth-of-type(odd) {
        background-color: #fafbfc;
    }
    .table-modern tbody tr:hover {
        background-color: var(--dash-primary-light) !important;
    }
    .table-modern tbody td {
        padding: 0.7rem 0.9rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--dash-border);
        color: var(--dash-text-main);
        font-size: 0.88rem;
    }

    /* Charts */
    .chart-container {
        min-height: 280px;
        padding: 1rem;
        background: #ffffff;
        border-radius: 10px;
        border: 1px solid var(--dash-border);
    }
    .chart-metric-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        background: #f8fafc;
        padding: 0.85rem 1.25rem;
        border-radius: 8px;
        border: 1px solid var(--dash-border);
        margin-bottom: 1.25rem;
    }

    /* Vue Good Table custom styles */
    .vgt-table {
        font-size: 0.88rem !important;
        border: none !important;
    }
    .vgt-table thead th {
        background: #f8fafc !important;
        color: var(--dash-text-muted) !important;
        font-weight: 700 !important;
        font-size: 0.78rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
        border-bottom: 1px solid var(--dash-border) !important;
        padding: 0.8rem 0.9rem !important;
    }
    .vgt-table td {
        padding: 0.7rem 0.9rem !important;
        vertical-align: middle !important;
        border-bottom: 1px solid var(--dash-border) !important;
    }
    .vgt-wrap__footer {
        background: #f8fafc !important;
        border-top: 1px solid var(--dash-border) !important;
        padding: 0.75rem 1rem !important;
        font-size: 0.85rem !important;
        color: var(--dash-text-muted) !important;
    }
    .vgt-global-search {
        background: transparent !important;
        border: none !important;
        padding: 0 0 1rem 0 !important;
    }
    .vgt-global-search input {
        border-radius: 8px !important;
        border: 1px solid var(--dash-border) !important;
        padding: 0.5rem 0.85rem !important;
        font-size: 0.88rem !important;
    }

    /* Dark Mode Adjustments */
    body.dark-mode .nav-tabs-modern {
        background: #111827;
    }
    body.dark-mode .nav-tabs-modern .nav-link {
        color: #9ca3af;
    }
    body.dark-mode .nav-tabs-modern .nav-link.active {
        background: var(--dash-card-bg);
        border-color: var(--dash-border);
        border-bottom-color: var(--dash-card-bg);
        color: var(--dash-primary);
    }
    body.dark-mode .filter-section {
        background: #192231;
    }
    body.dark-mode .preset-pill {
        background: #111827;
        color: #cbd5e1;
        border-color: var(--dash-border);
    }
    body.dark-mode .preset-pill:hover,
    body.dark-mode .preset-pill.active {
        background: var(--dash-primary);
        border-color: var(--dash-primary);
        color: #ffffff;
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
    body.dark-mode .badge-code {
        background: #111827;
        border-color: #374151;
        color: #e2e8f0;
    }
    body.dark-mode .table-modern thead th {
        background: #111827 !important;
        color: var(--dash-text-muted) !important;
        border-color: var(--dash-border) !important;
    }
    body.dark-mode .table-modern tbody tr {
        background-color: var(--dash-card-bg) !important;
    }
    body.dark-mode .table-modern tbody tr:nth-of-type(odd) {
        background-color: rgba(255, 255, 255, 0.02) !important;
    }
    body.dark-mode .table-modern tbody tr:hover {
        background-color: var(--dash-primary-light) !important;
    }
    body.dark-mode .table-modern tbody td {
        border-color: var(--dash-border) !important;
        color: var(--dash-text-main) !important;
    }
    body.dark-mode .chart-container,
    body.dark-mode .chart-metric-bar {
        background: var(--dash-card-bg);
        border-color: var(--dash-border);
    }
    body.dark-mode .vgt-table {
        background-color: var(--dash-card-bg) !important;
        color: var(--dash-text-main) !important;
    }
    body.dark-mode .vgt-table thead th {
        background: #111827 !important;
        color: var(--dash-text-muted) !important;
        border-color: var(--dash-border) !important;
    }
    body.dark-mode .vgt-table tbody tr {
        background-color: var(--dash-card-bg) !important;
    }
    body.dark-mode .vgt-table tbody tr:nth-of-type(odd) {
        background-color: rgba(255, 255, 255, 0.02) !important;
    }
    body.dark-mode .vgt-table tbody tr:hover {
        background-color: var(--dash-primary-light) !important;
    }
    body.dark-mode .vgt-table tbody td {
        border-color: var(--dash-border) !important;
        color: var(--dash-text-main) !important;
    }
    body.dark-mode .vgt-wrap__footer {
        background: #111827 !important;
        border-color: var(--dash-border) !important;
        color: var(--dash-text-muted) !important;
    }
    body.dark-mode .vgt-global-search input {
        background: #111827 !important;
        border-color: var(--dash-border) !important;
        color: var(--dash-text-main) !important;
    }

    /* Print Styles */
    @media print {
        .dash-header button,
        .dash-header a,
        .main-sidebar,
        .main-header,
        .filter-section,
        .nav-tabs-modern,
        .vgt-wrap__footer,
        .vgt-global-search,
        .no-print {
            display: none !important;
        }
        body {
            background: #fff !important;
            color: #000 !important;
        }
        .table-card, .kpi-card {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
            page-break-inside: avoid;
        }
    }
</style>
@endsection

@section('main')
<div class="container-fluid px-3 py-3 informe-wrapper" id="app" v-cloak>
    <!-- Header de Página -->
    <div class="dash-header">
        <div>
            <h1 class="dash-header-title font-cairo">
                <i class="fa-solid fa-chart-line mr-2"></i>Informe de Ventas
            </h1>
            <p class="dash-header-subtitle">
                Historial de transacciones, análisis por cliente, evolución gráfica y artículos vendidos
            </p>
        </div>
        <div class="dash-header-badges">
            <button type="button" class="btn-pos-secondary mr-2" @click="exportarCSV(activeTab === 'articulo' ? 'articulos' : 'ventas')">
                <i class="fa fa-file-excel mr-1 text-success"></i> Exportar CSV
            </button>
            <button type="button" class="btn-pos-secondary mr-2" onclick="window.print()">
                <i class="fa fa-print mr-1"></i> Imprimir Vista
            </button>
            <a href="{{ route('infventa.imprimir') }}" class="btn-pos-secondary mr-2" title="Centro de Impresión de Comprobantes">
                <i class="fa fa-receipt mr-1 text-primary"></i> Centro de Impresión
            </a>
            <a href="{{ route('venta') }}" class="btn-pos-primary">
                <i class="fa fa-cash-register mr-1"></i> Punto de Venta
            </a>
        </div>
    </div>

    <!-- Fila de Tarjetas KPI -->
    <div class="row">
        <!-- Facturación Total -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card">
                <div>
                    <div class="kpi-header">
                        <span class="kpi-label">Facturación Total</span>
                        <div class="kpi-icon kpi-icon-ventas">
                            <i class="fa-solid fa-sack-dollar"></i>
                        </div>
                    </div>
                    <div class="kpi-value font-cairo" style="color: var(--dash-primary);">
                        Gs. @{{ formatGs(totalGuaranies) }}
                    </div>
                </div>
                <div class="kpi-footer">
                    <span class="badge badge-light border">@{{ totalVenta }} ventas</span>
                    <span>registradas en el período</span>
                </div>
            </div>
        </div>

        <!-- Ventas al Contado -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card">
                <div>
                    <div class="kpi-header">
                        <span class="kpi-label">Operaciones Contado</span>
                        <div class="kpi-icon kpi-icon-contado">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                    </div>
                    <div class="kpi-value font-cairo text-success">
                        Gs. @{{ formatGs(totalContado) }}
                    </div>
                </div>
                <div class="kpi-footer">
                    <span class="font-weight-bold text-success">@{{ getContadoPct() }}%</span>
                    <span>del total (@{{ cantidadContado }} transacciones)</span>
                </div>
            </div>
        </div>

        <!-- Ventas a Crédito -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card">
                <div>
                    <div class="kpi-header">
                        <span class="kpi-label">Ventas a Crédito</span>
                        <div class="kpi-icon kpi-icon-credito">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </div>
                    </div>
                    <div class="kpi-value font-cairo text-warning">
                        Gs. @{{ formatGs(totalCredito) }}
                    </div>
                </div>
                <div class="kpi-footer">
                    <span class="font-weight-bold text-warning">@{{ 100 - getContadoPct() }}%</span>
                    <span>del total (@{{ cantidadCredito }} créditos)</span>
                </div>
            </div>
        </div>

        <!-- Ticket Promedio -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card">
                <div>
                    <div class="kpi-header">
                        <span class="kpi-label">Ticket Promedio</span>
                        <div class="kpi-icon kpi-icon-ticket">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                    </div>
                    <div class="kpi-value font-cairo text-primary">
                        Gs. @{{ formatGs(ticketPromedio) }}
                    </div>
                </div>
                <div class="kpi-footer">
                    <i class="fa fa-calculator text-muted"></i>
                    <span>Promedio por venta emitida</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjeta Principal con Pestañas -->
    <div class="table-card mb-4">
        <!-- Navegación de Pestañas -->
        <ul class="nav-tabs-modern" role="tablist">
            <li class="nav-item">
                <a class="nav-link" :class="{ active: activeTab === 'fecha' }" href="#tab-fecha" @click.prevent="cambiarTab('fecha')">
                    <i class="fa-solid fa-calendar-days"></i> Por Período / Fecha
                    <span class="tab-badge" v-if="ventas.length">@{{ ventas.length }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="{ active: activeTab === 'cliente' }" href="#tab-cliente" @click.prevent="cambiarTab('cliente')">
                    <i class="fa-solid fa-users"></i> Por Cliente
                    <span class="tab-badge" v-if="clientes.length">@{{ clientes.length }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="{ active: activeTab === 'chart' }" href="#tab-chart" @click.prevent="cambiarTab('chart')">
                    <i class="fa-solid fa-chart-area"></i> Gráficas y Tendencias
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="{ active: activeTab === 'articulo' }" href="#tab-articulo" @click.prevent="cambiarTab('articulo')">
                    <i class="fa-solid fa-boxes-stacked"></i> Artículos Vendidos
                    <span class="tab-badge" v-if="articulos.length">@{{ articulos.length }}</span>
                </a>
            </li>
        </ul>

        <!-- ========================================== -->
        <!-- TAB 1: POR FECHA / PERÍODO -->
        <!-- ========================================== -->
        <div v-show="activeTab === 'fecha'">
            <!-- Barra de Filtros -->
            <div class="filter-section">
                <!-- Atajos de Fecha -->
                <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                    <span class="filter-label mb-0 mr-2"><i class="fa fa-bolt text-warning mr-1"></i> Período:</span>
                    <button type="button" class="preset-pill" @click="setPreset('hoy', 'fecha')">Hoy</button>
                    <button type="button" class="preset-pill" @click="setPreset('ayer', 'fecha')">Ayer</button>
                    <button type="button" class="preset-pill" @click="setPreset('semana', 'fecha')">Esta Semana</button>
                    <button type="button" class="preset-pill active" @click="setPreset('mes', 'fecha')">Este Mes</button>
                    <button type="button" class="preset-pill" @click="setPreset('mes_anterior', 'fecha')">Mes Anterior</button>
                    <button type="button" class="preset-pill" @click="setPreset('anho', 'fecha')">Este Año</button>
                </div>

                <div class="row align-items-end">
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                        <label class="filter-label">Fecha Desde</label>
                        <input type="date" class="form-control form-control-sm" v-model="fecha.desde">
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                        <label class="filter-label">Fecha Hasta</label>
                        <input type="date" class="form-control form-control-sm" v-model="fecha.hasta">
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                        <label class="filter-label">Sucursal</label>
                        <select class="form-control form-control-sm" v-model="idSucursal">
                            <option value="0">Todas las Sucursales (Consolidado)</option>
                            @foreach ($sucursales as $s)
                                <option value="{{ $s['suc_cod'] }}">{{ $s['suc_desc'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                        <label class="filter-label">Condición</label>
                        <select class="form-control form-control-sm" v-model="filtroTipo">
                            <option value="todos">Todas las ventas</option>
                            <option value="contado">Solo Contado</option>
                            <option value="credito">Solo Crédito</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-8 col-sm-12 mb-2 d-flex gap-2">
                        <button type="button" class="btn-pos-primary flex-grow-1" @click="getVenta" :disabled="requestSend">
                            <span v-if="requestSend"><i class="fa fa-spinner fa-spin mr-1"></i> Consultando...</span>
                            <span v-else><i class="fa fa-search mr-1"></i> Consultar</span>
                        </button>
                        <button type="button" class="btn-pos-secondary" @click="limpiarFecha" title="Restablecer fechas y sucursal">
                            <i class="fa fa-rotate-left"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla de Ventas por Fecha (Vue Good Table) -->
            <div class="p-3">
                <vue-good-table
                    :columns="columns"
                    :rows="rowsFiltradas"
                    :search-options="{ enabled: true, placeholder: 'Buscar rápidamente por cliente, N° venta, documento...' }"
                    :pagination-options="{ enabled: true, perPage: 15, perPageDropdown: [10, 15, 25, 50, 100], nextLabel: 'Sig', prevLabel: 'Ant', rowsPerPageLabel: 'Filas por pág', ofLabel: 'de', allLabel: 'Todos' }"
                    style-class="vgt-table striped condensed">
                    <template slot="table-row" slot-scope="props">
                        <span v-if="props.column.field === 'codigo'">
                            <span class="font-weight-bold badge-code">#@{{ props.row.codigo }}</span>
                        </span>
                        <span v-else-if="props.column.field === 'tipoHtml'">
                            <span class="badge" :class="props.row.tipo == 1 ? 'badge-contado' : 'badge-credito'">
                                <i class="fa mr-1" :class="props.row.tipo == 1 ? 'fa-check-circle' : 'fa-clock'"></i>
                                @{{ props.row.tipo == 1 ? 'Contado' : 'Crédito' }}
                            </span>
                        </span>
                        <span v-else-if="props.column.field === 'cliente'">
                            <div class="d-flex flex-column">
                                <span class="font-weight-bold text-truncate" style="max-width: 200px;" :title="props.row.cliente">
                                    @{{ props.row.cliente }}
                                </span>
                                <small class="text-muted" v-if="props.row.cliente_ruc">RUC/CI: @{{ props.row.cliente_ruc }}</small>
                            </div>
                        </span>
                        <span v-else-if="props.column.field === 'vendedor'">
                            <span class="text-muted small"><i class="fa fa-user mr-1 text-secondary"></i>@{{ props.row.vendedor || '—' }}</span>
                        </span>
                        <span v-else-if="props.column.field === 'totalFormateado'">
                            <span class="font-cairo font-weight-bold text-nowrap" style="color: var(--dash-primary); font-size: 0.95rem;">
                                @{{ props.row.totalFormateado }}
                            </span>
                        </span>
                        <span v-else-if="props.column.field === 'detalle'">
                            <div class="d-flex align-items-center justify-content-end gap-1">
                                <button type="button" class="btn btn-xs btn-outline-info" @click="showDetalle(props.row.codigo, 'ventas')" title="Ver Detalle Completo">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <a :href="'{{ url('pdf/boletaventa') }}/' + props.row.codigo" class="btn btn-xs btn-outline-danger" target="_blank" title="Imprimir PDF / Factura">
                                    <i class="fa fa-file-pdf"></i>
                                </a>
                                <a :href="'{{ url('ticket/venta') }}/' + props.row.codigo" class="btn btn-xs btn-outline-secondary" target="_blank" title="Ticket Térmico">
                                    <i class="fa fa-receipt"></i>
                                </a>
                            </div>
                        </span>
                        <span v-else>
                            @{{ props.formattedRow[props.column.field] }}
                        </span>
                    </template>
                    <div slot="emptystate" class="text-center py-4 text-muted">
                        <i class="fa fa-inbox fa-3x mb-2 text-muted" style="opacity: 0.4;"></i>
                        <p class="mb-0">No se encontraron ventas para los filtros seleccionados.</p>
                    </div>
                </vue-good-table>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: POR CLIENTE -->
        <!-- ========================================== -->
        <div v-show="activeTab === 'cliente'">
            <!-- Barra de Búsqueda de Cliente -->
            <div class="filter-section">
                <div class="row align-items-end">
                    <div class="col-md-6 mb-2">
                        <label class="filter-label">Buscar Cliente</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" v-model.trim="txtbuscar"
                                placeholder="Escriba nombre, RUC, CI o N° de comprobante..."
                                @keyup.enter="buscar('cliente')">
                            <div class="input-group-append" v-if="txtbuscar">
                                <button class="btn btn-outline-secondary" type="button" @click="txtbuscar = ''; clientes = []">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="filter-label">Sucursal</label>
                        <select class="form-control form-control-sm" v-model="idSucursal">
                            <option value="0">Todas las Sucursales</option>
                            @foreach ($sucursales as $s)
                                <option value="{{ $s['suc_cod'] }}">{{ $s['suc_desc'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="button" class="btn-pos-primary btn-block" @click="buscar('cliente')" :disabled="requestSend">
                            <span v-if="requestSend"><i class="fa fa-spinner fa-spin mr-1"></i> Buscando...</span>
                            <span v-else><i class="fa fa-search mr-1"></i> Buscar Ventas</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Resumen del Cliente Encontrado -->
            <div class="p-3" v-if="clientes.length">
                <div class="chart-metric-bar align-items-center justify-content-between mb-3">
                    <div>
                        <span class="small font-weight-bold text-uppercase text-muted d-block">Resultados para:</span>
                        <strong class="font-cairo" style="font-size: 1.15rem; color: var(--dash-primary);">
                            @{{ clientes[0].cliente_nombre }}
                        </strong>
                        <span class="text-muted small ml-2" v-if="clientes[0].cliente_ruc">(RUC: @{{ clientes[0].cliente_ruc }})</span>
                    </div>
                    <div class="d-flex gap-4">
                        <div>
                            <span class="small text-muted d-block">Comprobantes:</span>
                            <strong class="font-cairo" style="font-size: 1.1rem;">@{{ clientes.length }}</strong>
                        </div>
                        <div>
                            <span class="small text-muted d-block">Total Facturado:</span>
                            <strong class="font-cairo text-success" style="font-size: 1.15rem;">
                                Gs. @{{ formatGs(totalClienteAcumulado) }}
                            </strong>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Ventas del Cliente -->
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th style="width: 90px;">N° Venta</th>
                                <th>Fecha y Hora</th>
                                <th>Cliente</th>
                                <th>Vendedor</th>
                                <th style="width: 100px;">Condición</th>
                                <th>Documento</th>
                                <th class="text-right">Total Factura</th>
                                <th>Sucursal</th>
                                <th class="text-right" style="width: 120px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="venta in clientes" :key="'cli-' + venta.nro_fact_ventas">
                                <td>
                                    <span class="badge-code font-weight-bold">#@{{ venta.nro_fact_ventas }}</span>
                                </td>
                                <td class="text-nowrap">@{{ venta.fecha }}</td>
                                <td>
                                    <strong>@{{ venta.cliente_nombre }}</strong>
                                    <small class="d-block text-muted" v-if="venta.cliente_cel">
                                        <i class="fa fa-phone mr-1"></i>@{{ venta.cliente_cel }}
                                    </small>
                                </td>
                                <td>
                                    <span class="text-muted small">@{{ venta.vendedor || '—' }}</span>
                                </td>
                                <td>
                                    <span class="badge" :class="venta.tipo_factura == 1 || venta.tipo_factura == '1' ? 'badge-contado' : 'badge-credito'">
                                        <i class="fa mr-1" :class="venta.tipo_factura == 1 || venta.tipo_factura == '1' ? 'fa-check-circle' : 'fa-clock'"></i>
                                        @{{ venta.tipo_factura == 1 || venta.tipo_factura == '1' ? 'Contado' : 'Crédito' }}
                                    </span>
                                </td>
                                <td>@{{ venta.documento || 'Ticket' }}</td>
                                <td class="text-right font-weight-bold font-cairo" style="color: var(--dash-primary); font-size: 0.95rem;">
                                    Gs. @{{ formatGs(venta.venta_total) }}
                                </td>
                                <td>@{{ venta.suc_desc }}</td>
                                <td class="text-right">
                                    <div class="d-flex align-items-center justify-content-end gap-1">
                                        <button type="button" class="btn btn-xs btn-outline-info" @click="showDetalle(venta.nro_fact_ventas, 'clientes')" title="Ver Detalle">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <a :href="'{{ url('pdf/boletaventa') }}/' + venta.nro_fact_ventas" class="btn btn-xs btn-outline-danger" target="_blank" title="Imprimir PDF">
                                            <i class="fa fa-file-pdf"></i>
                                        </a>
                                        <a :href="'{{ url('ticket/venta') }}/' + venta.nro_fact_ventas" class="btn btn-xs btn-outline-secondary" target="_blank" title="Ticket">
                                            <i class="fa fa-receipt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="p-5 text-center text-muted" v-else>
                <i class="fa fa-user-tag fa-3x mb-2" style="opacity: 0.35;"></i>
                <p class="mb-0">Ingrese un nombre, RUC o número de venta para ver el historial del cliente.</p>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: GRÁFICAS Y TENDENCIAS -->
        <!-- ========================================== -->
        <div v-show="activeTab === 'chart'">
            <div class="filter-section">
                <div class="row align-items-end">
                    <div class="col-md-3 col-sm-6 mb-2">
                        <label class="filter-label">Año</label>
                        <select class="form-control form-control-sm" v-model="chart.anho">
                            @for ($i = 2018; $i <= date('Y'); $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-2">
                        <label class="filter-label">Mes</label>
                        <select class="form-control form-control-sm" v-model="chart.mes">
                            <option v-for="(m, index) in meses" :key="index" :value="index + 1">@{{ m }}</option>
                            <option value="13">Todos los meses (Evolución Anual)</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-2">
                        <label class="filter-label">Sucursal</label>
                        <select class="form-control form-control-sm" v-model="chart.sucursal">
                            <option value="0">Todas las Sucursales</option>
                            @foreach ($sucursales as $s)
                                <option value="{{ $s['suc_cod'] }}">{{ $s['suc_desc'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-2">
                        <button type="button" class="btn-pos-primary btn-block" @click="getVentaAgrupado(true)" :disabled="requestSend">
                            <span v-if="requestSend"><i class="fa fa-spinner fa-spin mr-1"></i> Graficando...</span>
                            <span v-else><i class="fa fa-chart-line mr-1"></i> Actualizar Gráfica</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-3">
                <!-- Estadísticas de la gráfica -->
                <div class="chart-metric-bar" v-if="datos.length">
                    <div>
                        <span class="small font-weight-bold text-muted text-uppercase d-block">Total Período</span>
                        <strong class="font-cairo text-success" style="font-size: 1.25rem;">
                            Gs. @{{ formatGs(chartTotalMonto) }}
                        </strong>
                    </div>
                    <div>
                        <span class="small font-weight-bold text-muted text-uppercase d-block">Pico Máximo de Venta</span>
                        <strong class="font-cairo text-primary" style="font-size: 1.25rem;">
                            Gs. @{{ formatGs(chartMaxVenta.total) }}
                        </strong>
                        <small class="text-muted ml-1" v-if="chartMaxVenta.fecha">(@{{ chartMaxVenta.fecha }})</small>
                    </div>
                    <div>
                        <span class="small font-weight-bold text-muted text-uppercase d-block">Promedio Diario</span>
                        <strong class="font-cairo text-info" style="font-size: 1.25rem;">
                            Gs. @{{ formatGs(chartPromedioDiario) }}
                        </strong>
                    </div>
                </div>

                <div v-if="!datos.length" class="alert alert-light border text-center py-5 text-muted">
                    <i class="fa fa-chart-pie fa-3x mb-2 text-muted" style="opacity: 0.35;"></i>
                    <p class="mb-0">No se encontraron ventas para graficar en el año/mes seleccionado.</p>
                </div>

                <div class="row" v-show="datos.length">
                    <div class="col-lg-6 mb-3">
                        <div class="chart-container">
                            <h6 class="font-weight-bold text-uppercase small text-muted mb-3">
                                <i class="fa fa-wave-square mr-1 text-primary"></i> Tendencia de Facturación
                            </h6>
                            <div id="line_chart_1" style="min-height: 250px;"></div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="chart-container">
                            <h6 class="font-weight-bold text-uppercase small text-muted mb-3">
                                <i class="fa fa-chart-bar mr-1 text-success"></i> Comparativo Diario
                            </h6>
                            <div id="column_chart_1" style="min-height: 250px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 4: ARTÍCULOS VENDIDOS -->
        <!-- ========================================== -->
        <div v-show="activeTab === 'articulo'">
            <div class="filter-section">
                <!-- Atajos rápidos -->
                <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                    <span class="filter-label mb-0 mr-2"><i class="fa fa-bolt text-warning mr-1"></i> Período:</span>
                    <button type="button" class="preset-pill" @click="setPreset('hoy', 'articulo')">Hoy</button>
                    <button type="button" class="preset-pill" @click="setPreset('semana', 'articulo')">Esta Semana</button>
                    <button type="button" class="preset-pill active" @click="setPreset('mes', 'articulo')">Este Mes</button>
                    <button type="button" class="preset-pill" @click="setPreset('mes_anterior', 'articulo')">Mes Anterior</button>
                </div>

                <div class="row align-items-end">
                    <div class="col-lg-2 col-md-3 col-sm-6 mb-2">
                        <label class="filter-label">Fecha Desde</label>
                        <input type="date" class="form-control form-control-sm" v-model="articulo.desde">
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-6 mb-2">
                        <label class="filter-label">Fecha Hasta</label>
                        <input type="date" class="form-control form-control-sm" v-model="articulo.hasta">
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 mb-2">
                        <label class="filter-label">Sucursal</label>
                        <select class="form-control form-control-sm" v-model="idSucursalArt">
                            <option value="0">Todas las Sucursales</option>
                            @foreach ($sucursales as $s)
                                <option value="{{ $s['suc_cod'] }}">{{ $s['suc_desc'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 mb-2">
                        <label class="filter-label">Filtrar Producto</label>
                        <input type="text" class="form-control form-control-sm" v-model="filtroArticulo" placeholder="Buscar por nombre o código...">
                    </div>
                    <div class="col-lg-2 col-md-12 col-sm-12 mb-2 d-flex gap-2">
                        <button type="button" class="btn-pos-primary flex-grow-1" @click="getArticulo" :disabled="requestSend">
                            <span v-if="requestSend"><i class="fa fa-spinner fa-spin mr-1"></i> Buscando...</span>
                            <span v-else><i class="fa fa-search mr-1"></i> Consultar</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-3">
                <!-- Barra de Estadísticas de Artículos -->
                <div class="chart-metric-bar" v-if="articulos.length">
                    <div>
                        <span class="small font-weight-bold text-muted text-uppercase d-block">Referencias Vendidas</span>
                        <strong class="font-cairo" style="font-size: 1.25rem;">@{{ articulos.length }} productos</strong>
                    </div>
                    <div>
                        <span class="small font-weight-bold text-muted text-uppercase d-block">Unidades Despachadas</span>
                        <strong class="font-cairo text-primary" style="font-size: 1.25rem;">@{{ formatGs(totalUnidadesVendidas) }} unids.</strong>
                    </div>
                    <div>
                        <span class="small font-weight-bold text-muted text-uppercase d-block">Total Facturado Artículos</span>
                        <strong class="font-cairo text-success" style="font-size: 1.25rem;">Gs. @{{ formatGs(totalMontoArticulos) }}</strong>
                    </div>
                    <div v-if="topArticulo">
                        <span class="small font-weight-bold text-muted text-uppercase d-block">Top Producto</span>
                        <strong class="text-truncate d-inline-block text-warning" style="max-width: 250px;">
                            <i class="fa fa-trophy mr-1"></i>@{{ topArticulo }}
                        </strong>
                    </div>
                </div>

                <!-- Tabla de Artículos Vendidos -->
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th style="width: 130px;">Código de Barra</th>
                                <th>Descripción del Artículo</th>
                                <th>Presentación / Categoría</th>
                                <th class="text-right" style="width: 130px;">Cant. Vendida</th>
                                <th class="text-right" style="width: 140px;">Total Facturado</th>
                                <th class="text-right" style="width: 120px;">Stock Actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="!articulosFiltrados.length">
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa fa-box-open fa-3x mb-2" style="opacity: 0.35;"></i>
                                    <p class="mb-0">No se encontraron artículos vendidos para el rango seleccionado.</p>
                                </td>
                            </tr>
                            <tr v-for="(a, i) in articulosFiltrados" :key="'art-' + i">
                                <td>
                                    <span class="badge-rank" :class="getRankClass(i)">@{{ i + 1 }}</span>
                                </td>
                                <td>
                                    <span class="badge-code">@{{ a.producto_c_barra || '—' }}</span>
                                </td>
                                <td>
                                    <strong>@{{ a.producto_nombre }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-light border">@{{ a.present_descripcion || 'Unidad' }}</span>
                                </td>
                                <td class="text-right font-weight-bold font-cairo" style="font-size: 1rem; color: var(--dash-primary);">
                                    @{{ formatGs(a.vendida) }}
                                </td>
                                <td class="text-right font-weight-bold font-cairo text-success">
                                    Gs. @{{ formatGs(a.monto_total || 0) }}
                                </td>
                                <td class="text-right font-weight-bold">
                                    <span class="badge" :class="Number(a.cantidad) <= 0 ? 'badge-danger' : (Number(a.cantidad) <= 5 ? 'badge-warning text-dark' : 'badge-success')">
                                        @{{ a.cantidad }} unids.
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL DETALLE DE VENTA -->
    <!-- ========================================== -->
    <div class="modal fade" id="frmdetalle" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <!-- Header del Modal -->
                <div class="modal-header py-3" style="background: var(--dash-primary); color: #ffffff;">
                    <div>
                        <h5 class="modal-title font-cairo mb-0">
                            <i class="fa fa-receipt mr-2"></i>Detalle de Venta #@{{ venta.nro_fact_ventas }}
                        </h5>
                        <small style="opacity: 0.9;">Comprobante emitido en @{{ venta.suc_desc || 'Sucursal' }}</small>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-4" style="background: #fafbfc;">
                    <!-- Metadata de la Venta -->
                    <div class="row mb-3 bg-white p-3 rounded border">
                        <div class="col-md-6 mb-2 mb-md-0 border-right">
                            <span class="small font-weight-bold text-uppercase text-muted d-block">Cliente</span>
                            <div class="font-weight-bold" style="font-size: 1rem; color: var(--dash-primary);">
                                @{{ venta.cliente_nombre }}
                            </div>
                            <small class="text-muted d-block" v-if="venta.cliente_ruc">RUC/CI: @{{ venta.cliente_ruc }}</small>
                            <small class="text-muted d-block" v-if="venta.cliente_direccion">Dirección: @{{ venta.cliente_direccion }}</small>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-6 mb-2">
                                    <span class="small text-muted d-block">Fecha y Hora</span>
                                    <strong>@{{ venta.fecha }}</strong>
                                </div>
                                <div class="col-6 mb-2">
                                    <span class="small text-muted d-block">Condición</span>
                                    <span class="badge" :class="venta.tipo_factura == 1 || venta.tipo_factura == '1' ? 'badge-contado' : 'badge-credito'">
                                        @{{ venta.tipo_factura == 1 || venta.tipo_factura == '1' ? 'Contado' : 'Crédito' }}
                                    </span>
                                </div>
                                <div class="col-6">
                                    <span class="small text-muted d-block">Documento</span>
                                    <strong>@{{ venta.documento || 'Ticket' }}</strong>
                                </div>
                                <div class="col-6">
                                    <span class="small text-muted d-block">Vendedor</span>
                                    <strong>@{{ venta.vendedor || '—' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen Financiero Rápido -->
                    <div class="row mb-3 p-3 rounded" style="background: #eaf3ef; border: 1px solid #c8dfd5;">
                        <div class="col-4 text-center border-right">
                            <span class="small text-muted d-block">Subtotal</span>
                            <strong class="font-cairo">Gs. @{{ formatGs(Number(venta.venta_total) + Number(venta.venta_descuento || 0)) }}</strong>
                        </div>
                        <div class="col-4 text-center border-right">
                            <span class="small text-muted d-block">Descuento</span>
                            <strong class="font-cairo text-danger">Gs. @{{ formatGs(venta.venta_descuento) }}</strong>
                        </div>
                        <div class="col-4 text-center">
                            <span class="small text-muted d-block">Total Facturado</span>
                            <strong class="font-cairo text-success" style="font-size: 1.15rem;">
                                Gs. @{{ formatGs(venta.venta_total) }}
                            </strong>
                        </div>
                    </div>

                    <!-- Artículos del Detalle -->
                    <h6 class="font-weight-bold text-uppercase small text-muted mb-2">
                        <i class="fa fa-boxes mr-1 text-primary"></i> Ítems Facturados
                    </h6>
                    <div class="table-responsive bg-white rounded border mb-3">
                        <table class="table table-sm table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 120px;">Código</th>
                                    <th>Descripción</th>
                                    <th class="text-right" style="width: 80px;">Cant.</th>
                                    <th class="text-right" style="width: 120px;">Precio Unit.</th>
                                    <th class="text-right" style="width: 130px;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(d, i) in detalleVenta" :key="'det-' + i">
                                    <td><span class="badge-code">@{{ d.producto_c_barra }}</span></td>
                                    <td><strong>@{{ d.producto_nombre }}</strong></td>
                                    <td class="text-right font-weight-bold">@{{ parseInt(d.venta_cantidad) }}</td>
                                    <td class="text-right">Gs. @{{ formatGs(d.venta_precio) }}</td>
                                    <td class="text-right font-weight-bold font-cairo" style="color: var(--dash-primary);">
                                        Gs. @{{ formatGs(d.venta_cantidad * d.venta_precio) }}
                                    </td>
                                </tr>
                                <tr v-if="!detalleVenta || !detalleVenta.length">
                                    <td colspan="5" class="text-center text-muted py-3">Sin artículos registrados.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Cuotas si la venta es a Crédito -->
                    <template v-if="venta.tipo_factura == 2 || venta.tipo_factura == '2'">
                        <h6 class="font-weight-bold text-uppercase small text-muted mb-2">
                            <i class="fa fa-calendar-check mr-1 text-warning"></i> Cronograma de Cuotas a Crédito
                        </h6>
                        <div class="table-responsive bg-white rounded border mb-3">
                            <table class="table table-sm table-striped mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>N°</th>
                                        <th>Vencimiento</th>
                                        <th class="text-right">Monto Cuota</th>
                                        <th class="text-right">Cobrado</th>
                                        <th class="text-right">Saldo</th>
                                        <th>Mora</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(c, i) in cuotas" :key="'cuo-' + i">
                                        <td><strong>@{{ c.nro_cuotas }}</strong></td>
                                        <td>@{{ formatFechaVenc(c.fecha_venc) }}</td>
                                        <td class="text-right font-weight-bold">Gs. @{{ formatGs(c.monto_cuota) }}</td>
                                        <td class="text-right text-success">Gs. @{{ formatGs(c.monto_cobrado) }}</td>
                                        <td class="text-right text-danger font-weight-bold">Gs. @{{ formatGs(c.monto_saldo) }}</td>
                                        <td>
                                            <span class="small text-muted" v-if="Funciones && Funciones.diferenciaFecha">
                                                @{{ Funciones.diferenciaFecha(c.fecha_venc, c.monto_saldo) }}
                                            </span>
                                            <span v-else>—</span>
                                        </td>
                                        <td>
                                            <span class="badge" :class="Number(c.monto_cobrado) >= Number(c.monto_cuota) ? 'badge-success' : 'badge-danger'">
                                                @{{ Number(c.monto_cobrado) >= Number(c.monto_cuota) ? 'Cobrado' : 'Pendiente' }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between p-2 rounded bg-white border font-cairo">
                            <div>Total Cobrado: <strong class="text-success">Gs. @{{ formatGs(Cuenta.montoCobrado) }}</strong></div>
                            <div>Saldo Pendiente: <strong class="text-danger">Gs. @{{ formatGs(Cuenta.saldo) }}</strong></div>
                        </div>
                    </template>
                </div>

                <!-- Footer del Modal -->
                <div class="modal-footer bg-white py-3">
                    <a v-if="venta.nro_fact_ventas" :href="'{{ url('pdf/boletaventa') }}/' + venta.nro_fact_ventas"
                        class="btn btn-outline-danger btn-sm" target="_blank">
                        <i class="fa fa-file-pdf mr-1"></i> Comprobante PDF
                    </a>
                    <a v-if="venta.nro_fact_ventas" :href="'{{ url('ticket/venta') }}/' + venta.nro_fact_ventas"
                        class="btn btn-outline-secondary btn-sm" target="_blank">
                        <i class="fa fa-receipt mr-1"></i> Ticket Térmico
                    </a>
                    <a v-if="venta.tipo_factura == 2 || venta.tipo_factura == '2'"
                        :href="'{{ url('documento/extractocuenta') }}/' + venta.nro_fact_ventas"
                        class="btn btn-outline-success btn-sm" target="_blank">
                        <i class="fa fa-file-invoice mr-1"></i> Extracto de Cuenta
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        <i class="fa fa-times mr-1"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('chart/raphael.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('chart/morris.min.js') }}"></script>
<script type="text/javascript">
    Vue.prototype.Funciones = window.Funciones;
    var app = new Vue({
        el: '#app',
        data: {
            activeTab: 'fecha',
            meses: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            fecha: { desde: '', hasta: '' },
            articulo: { desde: '', hasta: '' },
            chart: {
                mes: '1',
                anho: {{ date('Y') }},
                sucursal: 0,
                byYear: false
            },
            txtbuscar: '',
            filtroTipo: 'todos',
            filtroArticulo: '',
            venta: {},
            detalleVenta: [],
            ventas: [],
            clientes: [],
            articulos: [],
            cuotas: [],
            Cuenta: { cantitad: 0, montoCuota: 0, saldo: 0, cobrado: 0, montoCobrado: 0 },
            error: '',
            datos: [],
            isVisibleChart: false,
            requestSend: false,
            idSucursal: 0,
            idSucursalArt: 0,
            columns: [
                { label: 'N°', field: 'codigo', type: 'number', width: '85px' },
                { label: 'Fecha y Hora', field: 'fecha', width: '140px' },
                { label: 'Cliente', field: 'cliente' },
                { label: 'Vendedor', field: 'vendedor', width: '120px' },
                { label: 'Condición', field: 'tipoHtml', html: true, width: '100px' },
                { label: 'Documento', field: 'documento', width: '120px' },
                { label: 'Total Factura', field: 'totalFormateado', tdClass: 'font-weight-bold text-right', width: '140px' },
                { label: 'Sucursal', field: 'sucursal', width: '130px' },
                { label: 'Acciones', field: 'detalle', html: true, sortable: false, width: '110px' }
            ],
            rows: []
        },
        methods: {
            formatGs: function (n) {
                return new Intl.NumberFormat('de-DE').format(Math.round(Number(n) || 0));
            },
            formatFecha: function (d) {
                var mes = (d.getMonth() + 1).toString().padStart(2, '0');
                var dia = d.getDate().toString().padStart(2, '0');
                return d.getFullYear() + '-' + mes + '-' + dia;
            },
            formatFechaVenc: function (f) {
                if (!f) return '—';
                var partes = f.split('-');
                if (partes.length === 3) {
                    return partes[2] + '/' + partes[1] + '/' + partes[0];
                }
                return f;
            },
            cambiarTab: function (tab) {
                this.activeTab = tab;
                if (tab === 'chart') {
                    var self = this;
                    this.$nextTick(function () {
                        self.showChart();
                    });
                }
            },
            setPreset: function (tipo, target) {
                var hoy = new Date();
                var d1 = new Date();
                var d2 = new Date();

                if (tipo === 'hoy') {
                    // Mismo día
                } else if (tipo === 'ayer') {
                    d1.setDate(d1.getDate() - 1);
                    d2.setDate(d2.getDate() - 1);
                } else if (tipo === 'semana') {
                    var diaSem = hoy.getDay();
                    var diff = hoy.getDate() - diaSem + (diaSem === 0 ? -6 : 1);
                    d1 = new Date(hoy.setDate(diff));
                    d2 = new Date();
                } else if (tipo === 'mes') {
                    d1 = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
                    d2 = new Date();
                } else if (tipo === 'mes_anterior') {
                    d1 = new Date(hoy.getFullYear(), hoy.getMonth() - 1, 1);
                    d2 = new Date(hoy.getFullYear(), hoy.getMonth(), 0);
                } else if (tipo === 'anho') {
                    d1 = new Date(hoy.getFullYear(), 0, 1);
                    d2 = new Date();
                }

                if (target === 'articulo') {
                    this.articulo.desde = this.formatFecha(d1);
                    this.articulo.hasta = this.formatFecha(d2);
                    this.getArticulo();
                } else {
                    this.fecha.desde = this.formatFecha(d1);
                    this.fecha.hasta = this.formatFecha(d2);
                    this.getVenta();
                }
            },
            limpiarFecha: function () {
                var hoy = new Date();
                var primerDia = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
                this.fecha.desde = this.formatFecha(primerDia);
                this.fecha.hasta = this.formatFecha(hoy);
                this.idSucursal = 0;
                this.filtroTipo = 'todos';
                this.getVenta();
            },
            getRankClass: function (idx) {
                if (idx === 0) return 'rank-1';
                if (idx === 1) return 'rank-2';
                if (idx === 2) return 'rank-3';
                return 'rank-other';
            },
            buscar: function (modo) {
                var self = this;
                if (!self.txtbuscar.trim()) {
                    Swal.fire('Atención', 'Ingrese un término de búsqueda para consultar.', 'info');
                    return;
                }
                self.requestSend = true;
                var isNumber = isNaN(parseFloat(self.txtbuscar)) ? 0 : 1;
                axios.get('{{ route('infventa.cliente') }}', {
                    params: {
                        cliente: self.txtbuscar,
                        alls: self.idSucursal,
                        isNumber: isNumber
                    }
                }).then(function (response) {
                    self.requestSend = false;
                    self.clientes = response.data || [];
                    if (!self.clientes.length) {
                        Swal.fire('Sin resultados', 'No se encontraron ventas para: ' + self.txtbuscar, 'info');
                    }
                }).catch(function (e) {
                    self.requestSend = false;
                    console.error(e);
                    Swal.fire('Error', 'No se pudo completar la búsqueda del cliente.', 'error');
                });
            },
            getVenta: function () {
                var self = this;
                self.requestSend = true;
                axios.get('{{ url('infventa/fecha') }}', {
                    params: {
                        alld: self.fecha.desde,
                        allh: self.fecha.hasta,
                        alls: self.idSucursal
                    }
                }).then(function (response) {
                    self.requestSend = false;
                    self.ventas = response.data || [];
                    self.rebuildRows();
                }).catch(function (e) {
                    self.requestSend = false;
                    console.error(e);
                    Swal.fire('Error', 'No se pudo cargar el listado de ventas.', 'error');
                });
            },
            rebuildRows: function () {
                var self = this;
                self.rows = self.ventas.map(function (v) {
                    var esContado = v.tipo_factura == 1 || v.tipo_factura == '1';
                    return {
                        codigo: v.nro_fact_ventas,
                        fecha: v.fecha,
                        cliente: v.cliente_nombre || 'Cliente Ocasional',
                        cliente_ruc: v.cliente_ruc || '',
                        vendedor: v.vendedor || '—',
                        tipo: esContado ? 1 : 2,
                        tipoHtml: esContado ? 'Contado' : 'Crédito',
                        documento: v.documento || 'Ticket',
                        total: parseFloat(v.venta_total || 0),
                        totalFormateado: 'Gs. ' + self.formatGs(v.venta_total),
                        sucursal: v.suc_desc || '—',
                        detalle: ''
                    };
                });
            },
            getVentaAgrupado: function (show) {
                var self = this;
                self.requestSend = true;
                var lineEl = document.getElementById('line_chart_1');
                var colEl = document.getElementById('column_chart_1');
                if (lineEl) lineEl.innerHTML = '';
                if (colEl) colEl.innerHTML = '';

                axios.post('{{ url('infventa/chart') }}', { chart: self.chart })
                    .then(function (response) {
                        self.requestSend = false;
                        self.datos = response.data || [];
                        if (show) {
                            self.isVisibleChart = false;
                            self.$nextTick(function () {
                                self.showChart();
                            });
                        }
                    }).catch(function (e) {
                        self.requestSend = false;
                        console.error(e);
                        Swal.fire('Error', 'No se pudieron generar los datos para la gráfica.', 'error');
                    });
            },
            showChart: function () {
                if (this.isVisibleChart || !this.datos.length) {
                    return;
                }
                var lineEl = document.getElementById('line_chart_1');
                var colEl = document.getElementById('column_chart_1');
                if (!lineEl || !colEl) return;
                lineEl.innerHTML = '';
                colEl.innerHTML = '';

                var chartData = this.datos.map(function (d) {
                    return {
                        fecha: d.fecha,
                        total: parseFloat(d.total || 0),
                        cantidad: parseInt(d.cantidad || 0)
                    };
                });

                Morris.Area({
                    element: 'line_chart_1',
                    resize: true,
                    data: chartData,
                    xkey: 'fecha',
                    ykeys: ['total'],
                    labels: ['Facturación (Gs.)'],
                    fillOpacity: 0.35,
                    lineColors: ['#0a4d36'],
                    pointStrokeColors: ['#0a4d36'],
                    gridTextColor: '#64748b',
                    gridTextFamily: 'Cairo, sans-serif',
                    hideHover: 'auto',
                    dateFormat: function (x) {
                        var d = new Date(x);
                        var dia = d.getDate().toString().padStart(2, '0');
                        var mes = (d.getMonth() + 1).toString().padStart(2, '0');
                        return dia + '/' + mes + '/' + d.getFullYear();
                    },
                    yLabelFormat: function (y) {
                        return new Intl.NumberFormat('de-DE').format(Math.round(y));
                    }
                });

                Morris.Bar({
                    element: 'column_chart_1',
                    resize: true,
                    data: chartData,
                    xkey: 'fecha',
                    ykeys: ['total'],
                    labels: ['Facturación (Gs.)'],
                    barColors: ['#10b981'],
                    gridTextColor: '#64748b',
                    gridTextFamily: 'Cairo, sans-serif',
                    hideHover: 'auto',
                    yLabelFormat: function (y) {
                        return new Intl.NumberFormat('de-DE').format(Math.round(y));
                    }
                });

                this.isVisibleChart = true;
            },
            showDetalle: function (id, tab) {
                var list = tab === 'ventas' ? this.ventas : this.clientes;
                var idx = list.findIndex(function (x) { return x.nro_fact_ventas == id; });
                if (idx < 0) return;
                this.venta = list[idx];
                this.detalleVenta = [];
                this.cuotas = [];
                $('#frmdetalle').modal('show');
                this.getDetalle();
            },
            getDetalle: function () {
                var self = this;
                axios.get('{{ url('infventa/detalle') }}/' + self.venta.nro_fact_ventas)
                    .then(function (response) {
                        self.detalleVenta = response.data || [];
                    }).catch(function (error) {
                        console.error(error);
                    });

                if (self.venta.tipo_factura == 2 || self.venta.tipo_factura == '2') {
                    self.getCta();
                }
            },
            getCta: function () {
                var self = this;
                axios.get('{{ url('cuotas') }}/' + self.venta.nro_fact_ventas)
                    .then(function (response) {
                        var c = response.data || [];
                        self.cuotas = c;
                        var saldo = 0;
                        var cobrado = 0;
                        for (var i = 0; i < c.length; i++) {
                            saldo += parseInt(c[i].monto_saldo || 0);
                            cobrado += parseInt(c[i].monto_cobrado || 0);
                        }
                        self.Cuenta.saldo = saldo;
                        self.Cuenta.montoCobrado = cobrado;
                    }).catch(function (error) {
                        console.error(error);
                    });
            },
            getArticulo: function () {
                var self = this;
                self.requestSend = true;
                axios.get('{{ url('infventa/articulo') }}', {
                    params: {
                        artd: self.articulo.desde,
                        arth: self.articulo.hasta,
                        arts: self.idSucursalArt
                    }
                }).then(function (response) {
                    self.requestSend = false;
                    self.articulos = response.data || [];
                }).catch(function (e) {
                    self.requestSend = false;
                    console.error(e);
                    Swal.fire('Error', 'No se pudieron consultar los artículos vendidos.', 'error');
                });
            },
            getContadoPct: function () {
                var total = parseFloat(this.totalGuaranies) || 0;
                var contado = parseFloat(this.totalContado) || 0;
                if (total <= 0) return 0;
                return Math.round((contado / total) * 100);
            },
            exportarCSV: function (tipo) {
                if (tipo === 'articulos') {
                    if (!this.articulos.length) {
                        Swal.fire('Atención', 'No hay artículos para exportar.', 'warning');
                        return;
                    }
                    var csv = "\uFEFFRanking;Codigo_Barra;Descripcion;Presentacion;Cant_Vendida;Total_Facturado;Stock_Actual\n";
                    this.articulosFiltrados.forEach(function (a, idx) {
                        csv += [
                            idx + 1,
                            '"' + (a.producto_c_barra || '').replace(/"/g, '""') + '"',
                            '"' + (a.producto_nombre || '').replace(/"/g, '""') + '"',
                            '"' + (a.present_descripcion || '').replace(/"/g, '""') + '"',
                            a.vendida,
                            a.monto_total || 0,
                            a.cantidad
                        ].join(';') + "\n";
                    });
                    var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                    var link = document.createElement("a");
                    link.href = URL.createObjectURL(blob);
                    link.download = 'articulos_vendidos_' + this.articulo.desde + '_al_' + this.articulo.hasta + '.csv';
                    link.click();
                    return;
                }

                if (!this.rowsFiltradas.length) {
                    Swal.fire('Atención', 'No hay ventas para exportar con el filtro actual.', 'warning');
                    return;
                }
                var csv = "\uFEFFNro_Venta;Fecha_Hora;Cliente;RUC_CI;Vendedor;Condicion;Documento;Total;Sucursal\n";
                this.rowsFiltradas.forEach(function (r) {
                    csv += [
                        r.codigo,
                        '"' + (r.fecha || '').replace(/"/g, '""') + '"',
                        '"' + (r.cliente || '').replace(/"/g, '""') + '"',
                        '"' + (r.cliente_ruc || '').replace(/"/g, '""') + '"',
                        '"' + (r.vendedor || '').replace(/"/g, '""') + '"',
                        r.tipo == 1 ? 'Contado' : 'Crédito',
                        '"' + (r.documento || '').replace(/"/g, '""') + '"',
                        r.total,
                        '"' + (r.sucursal || '').replace(/"/g, '""') + '"'
                    ].join(';') + "\n";
                });
                var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                var link = document.createElement("a");
                link.href = URL.createObjectURL(blob);
                link.download = 'informe_ventas_' + this.fecha.desde + '_al_' + this.fecha.hasta + '.csv';
                link.click();
            }
        },
        computed: {
            rowsFiltradas: function () {
                var self = this;
                if (self.filtroTipo === 'contado') {
                    return self.rows.filter(function (r) { return r.tipo == 1; });
                }
                if (self.filtroTipo === 'credito') {
                    return self.rows.filter(function (r) { return r.tipo != 1; });
                }
                return self.rows;
            },
            articulosFiltrados: function () {
                if (!this.filtroArticulo.trim()) {
                    return this.articulos;
                }
                var q = this.filtroArticulo.toLowerCase().trim();
                return this.articulos.filter(function (a) {
                    return (a.producto_nombre && a.producto_nombre.toLowerCase().indexOf(q) !== -1) ||
                           (a.producto_c_barra && a.producto_c_barra.toLowerCase().indexOf(q) !== -1) ||
                           (a.present_descripcion && a.present_descripcion.toLowerCase().indexOf(q) !== -1);
                });
            },
            totalVenta: function () {
                return this.ventas.length;
            },
            totalGuaranies: function () {
                var total = 0;
                for (var i = 0; i < this.ventas.length; i++) {
                    total += parseFloat(this.ventas[i].venta_total || 0);
                }
                return total;
            },
            totalContado: function () {
                var total = 0;
                for (var i = 0; i < this.ventas.length; i++) {
                    var v = this.ventas[i];
                    if (v.tipo_factura == 1 || v.tipo_factura == '1') {
                        total += parseFloat(v.venta_total || 0);
                    }
                }
                return total;
            },
            totalCredito: function () {
                var total = 0;
                for (var i = 0; i < this.ventas.length; i++) {
                    var v = this.ventas[i];
                    if (v.tipo_factura != 1 && v.tipo_factura != '1') {
                        total += parseFloat(v.venta_total || 0);
                    }
                }
                return total;
            },
            cantidadContado: function () {
                var c = 0;
                for (var i = 0; i < this.ventas.length; i++) {
                    var v = this.ventas[i];
                    if (v.tipo_factura == 1 || v.tipo_factura == '1') c++;
                }
                return c;
            },
            cantidadCredito: function () {
                var c = 0;
                for (var i = 0; i < this.ventas.length; i++) {
                    var v = this.ventas[i];
                    if (v.tipo_factura != 1 && v.tipo_factura != '1') c++;
                }
                return c;
            },
            ticketPromedio: function () {
                if (this.totalVenta <= 0) return 0;
                return Math.round(this.totalGuaranies / this.totalVenta);
            },
            totalClienteAcumulado: function () {
                var total = 0;
                for (var i = 0; i < this.clientes.length; i++) {
                    total += parseFloat(this.clientes[i].venta_total || 0);
                }
                return total;
            },
            totalUnidadesVendidas: function () {
                var total = 0;
                for (var i = 0; i < this.articulos.length; i++) {
                    total += parseFloat(this.articulos[i].vendida || 0);
                }
                return total;
            },
            totalMontoArticulos: function () {
                var total = 0;
                for (var i = 0; i < this.articulos.length; i++) {
                    total += parseFloat(this.articulos[i].monto_total || 0);
                }
                return total;
            },
            topArticulo: function () {
                if (!this.articulos.length) return '';
                return this.articulos[0].producto_nombre || '';
            },
            chartTotalMonto: function () {
                var total = 0;
                for (var i = 0; i < this.datos.length; i++) {
                    total += parseFloat(this.datos[i].total || 0);
                }
                return total;
            },
            chartMaxVenta: function () {
                if (!this.datos.length) return { total: 0, fecha: '' };
                var max = { total: 0, fecha: '' };
                for (var i = 0; i < this.datos.length; i++) {
                    var t = parseFloat(this.datos[i].total || 0);
                    if (t > max.total) {
                        max.total = t;
                        max.fecha = this.datos[i].fecha;
                    }
                }
                return max;
            },
            chartPromedioDiario: function () {
                if (!this.datos.length) return 0;
                return Math.round(this.chartTotalMonto / this.datos.length);
            }
        },
        mounted: function () {
            var hoy = new Date();
            var primerDia = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
            this.fecha.desde = this.formatFecha(primerDia);
            this.fecha.hasta = this.formatFecha(hoy);
            this.articulo.desde = this.formatFecha(primerDia);
            this.articulo.hasta = this.formatFecha(hoy);
            this.chart.mes = (hoy.getMonth() + 1).toString();

            this.getVenta();
            this.getVentaAgrupado(false);
            this.getArticulo();
        }
    });

    activarMenu('m_informe', 'm_iventa');
</script>
@endsection
