@extends('layouts.app')
@section('title', 'Extracto de Cuentas a Cobrar - ' . ($empresa->emp_nombre ?? 'SoftSystem'))
@section('style')
<style type="text/css" media="all">
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

    .ctacobrar-wrapper {
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
        width: 38px;
        height: 38px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
    }
    .kpi-icon-saldo { background: #fef2f2; color: #dc2626; }
    .kpi-icon-cobrado { background: #ecfdf5; color: #059669; }
    .kpi-icon-total { background: #eff6ff; color: #2563eb; }
    .kpi-icon-mora { background: #fffbeb; color: #d97706; }

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

    /* Container Card */
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

    /* View Switcher */
    .view-mode-btn {
        padding: 0.4rem 0.75rem;
        font-size: 0.82rem;
        font-weight: 600;
        border: 1px solid var(--dash-border);
        background: #ffffff;
        color: var(--dash-text-muted);
        cursor: pointer;
        transition: all 0.15s;
    }
    .view-mode-btn:first-child {
        border-top-left-radius: 6px;
        border-bottom-left-radius: 6px;
    }
    .view-mode-btn:last-child {
        border-top-right-radius: 6px;
        border-bottom-right-radius: 6px;
        border-left: none;
    }
    .view-mode-btn.active {
        background: var(--dash-primary);
        border-color: var(--dash-primary);
        color: #ffffff;
    }

    /* Cards Component (cta-card) */
    .cta-card {
        border: 1px solid var(--dash-border);
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 1.15rem;
        background: var(--dash-card-bg);
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        transition: all 0.15s ease;
    }
    .cta-card:hover {
        box-shadow: 0 6px 14px rgba(0,0,0,0.06);
        border-color: var(--dash-primary-border);
    }
    .cta-card-header {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem 1.25rem;
        align-items: center;
        padding: 0.85rem 1.2rem;
        background: #f8fafc;
        border-bottom: 1px solid var(--dash-border);
    }
    .cta-card-header .cta-nombre {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--dash-primary);
        flex: 1 1 240px;
    }
    .cta-card-header .cta-meta {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.84rem;
        color: var(--dash-text-muted);
    }
    .cta-card-header .cta-meta i {
        color: var(--dash-primary);
        font-size: 0.85rem;
    }

    /* Progress bar of payment */
    .cta-progress-wrap {
        padding: 0.4rem 1.2rem;
        background: #fafbfc;
        border-bottom: 1px solid var(--dash-border);
    }
    .cta-progress-bar {
        height: 7px;
        border-radius: 999px;
        background: #fee2e2;
        overflow: hidden;
        display: flex;
    }
    .cta-progress-fill {
        background: #10b981;
        height: 100%;
        transition: width 0.3s ease;
    }

    /* Summary Stat Grid in Card */
    .cta-card-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 0.75rem 1rem;
        padding: 1rem 1.2rem;
    }
    .cta-stat-label {
        display: block;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--dash-text-muted);
        margin-bottom: 0.25rem;
    }
    .cta-stat-value {
        display: block;
        font-size: 1.05rem;
        font-weight: 700;
        line-height: 1.2;
        font-variant-numeric: tabular-nums;
    }
    .cta-stat-saldo .cta-stat-value {
        color: #dc2626;
        font-size: 1.15rem;
    }
    .cta-stat-cobrado .cta-stat-value {
        color: #059669;
    }

    /* Collapsible Articles Section */
    .cta-card-detalle {
        padding: 0 1.2rem 1rem;
    }
    .cta-detalle-btn {
        background: transparent;
        border: none;
        color: var(--dash-primary);
        font-weight: 600;
        font-size: 0.84rem;
        padding: 0.4rem 0;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .cta-detalle-btn:hover {
        text-decoration: underline;
    }
    .cta-card-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
        gap: 0.5rem;
        padding: 0.65rem 1.2rem;
        background: #fdfdfd;
        border-top: 1px solid var(--dash-border);
    }

    /* Modern Table (Resumido) */
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

    .badge-code {
        font-family: monospace;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        padding: 0.2rem 0.45rem;
        border-radius: 6px;
        font-size: 0.85rem;
    }

    /* Dark Mode */
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
    body.dark-mode .filter-section,
    body.dark-mode .cta-card-actions {
        background: #192231;
    }
    body.dark-mode .cta-card-header,
    body.dark-mode .cta-progress-wrap {
        background: #111827;
        border-bottom-color: var(--dash-border);
    }
    body.dark-mode .preset-pill,
    body.dark-mode .view-mode-btn {
        background: #111827;
        color: #cbd5e1;
        border-color: var(--dash-border);
    }
    body.dark-mode .preset-pill:hover,
    body.dark-mode .preset-pill.active,
    body.dark-mode .view-mode-btn.active {
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

    /* Print Styles */
    @media print {
        .dash-header button,
        .dash-header a,
        .main-sidebar,
        .main-header,
        .filter-section,
        .nav-tabs-modern,
        .cta-card-actions,
        .no-print {
            display: none !important;
        }
        body {
            background: #fff !important;
            color: #000 !important;
        }
        .cta-card, .table-card {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
            page-break-inside: avoid;
        }
    }
</style>
@endsection

@section('main')
<div class="container-fluid px-3 py-3 ctacobrar-wrapper" id="app" v-cloak>
    <!-- Header de Página -->
    <div class="dash-header">
        <div>
            <h1 class="dash-header-title font-cairo">
                <i class="fa-solid fa-file-invoice-dollar mr-2"></i>Extracto de Cuentas a Cobrar
            </h1>
            <p class="dash-header-subtitle">
                Control de créditos otorgados, vencimientos de cuotas, cartera de clientes y cobranzas
            </p>
        </div>
        <div class="dash-header-badges">
            <button type="button" class="btn-pos-secondary mr-2" @click="exportarCSV" title="Exportar listado actual a Excel / CSV">
                <i class="fa fa-file-excel mr-1 text-success"></i> Exportar CSV
            </button>
            <button type="button" class="btn-pos-secondary mr-2" onclick="window.print()">
                <i class="fa fa-print mr-1"></i> Imprimir Listado
            </button>
            <a href="{{ route('cobro') }}" class="btn-pos-primary mr-2" title="Ir al módulo de cobranzas">
                <i class="fa fa-hand-holding-dollar mr-1"></i> Registrar Cobro
            </a>
            <a href="{{ route('venta') }}" class="btn-pos-secondary">
                <i class="fa fa-cash-register mr-1"></i> Punto de Venta
            </a>
        </div>
    </div>

    <!-- Fila de Tarjetas KPI -->
    <div class="row">
        <!-- Saldo Total Pendiente -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card">
                <div>
                    <div class="kpi-header">
                        <span class="kpi-label">Saldo a Cobrar</span>
                        <div class="kpi-icon kpi-icon-saldo">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                        </div>
                    </div>
                    <div class="kpi-value font-cairo text-danger">
                        Gs. @{{ format(totalSaldo) }}
                    </div>
                </div>
                <div class="kpi-footer">
                    <span class="badge badge-danger">@{{ ctasFiltradas.length }} cuentas</span>
                    <span>pendientes de cancelación</span>
                </div>
            </div>
        </div>

        <!-- Total Cobrado Acumulado -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card">
                <div>
                    <div class="kpi-header">
                        <span class="kpi-label">Total Recaudado</span>
                        <div class="kpi-icon kpi-icon-cobrado">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                    <div class="kpi-value font-cairo text-success">
                        Gs. @{{ format(totalCobrado) }}
                    </div>
                </div>
                <div class="kpi-footer">
                    <span class="font-weight-bold text-success">@{{ getCobradoPct() }}%</span>
                    <span>cobrado de la cartera consultada</span>
                </div>
            </div>
        </div>

        <!-- Total Financiado / Cartera -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card">
                <div>
                    <div class="kpi-header">
                        <span class="kpi-label">Cartera Total</span>
                        <div class="kpi-icon kpi-icon-total">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                    </div>
                    <div class="kpi-value font-cairo text-primary">
                        Gs. @{{ format(totalImporte) }}
                    </div>
                </div>
                <div class="kpi-footer">
                    <i class="fa fa-file-invoice text-muted"></i>
                    <span>Monto total otorgado a crédito</span>
                </div>
            </div>
        </div>

        <!-- Cuentas en Mora / Vencidas -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card">
                <div>
                    <div class="kpi-header">
                        <span class="kpi-label">Cuotas en Mora</span>
                        <div class="kpi-icon kpi-icon-mora">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                    </div>
                    <div class="kpi-value font-cairo text-warning">
                        @{{ totalVencidas }}
                    </div>
                </div>
                <div class="kpi-footer">
                    <span class="font-weight-bold text-danger">Gs. @{{ format(saldoVencido) }}</span>
                    <span>vencido pendiente</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenedor Principal con Pestañas -->
    <div class="table-card mb-4">
        <!-- Navegación de Pestañas -->
        <ul class="nav-tabs-modern" role="tablist">
            <li class="nav-item">
                <a class="nav-link" :class="{ active: filtro.tipo === 'fecha' }" href="#tab-fecha" @click.prevent="cambiarTab('fecha')">
                    <i class="fa-solid fa-calendar-days"></i> Por Vencimiento / Fecha
                    <span class="tab-badge" v-if="filtro.tipo === 'fecha' && ctas.length">@{{ ctas.length }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="{ active: filtro.tipo === 'cliente' }" href="#tab-cliente" @click.prevent="cambiarTab('cliente')">
                    <i class="fa-solid fa-users"></i> Por Cliente
                    <span class="tab-badge" v-if="filtro.tipo === 'cliente' && ctas.length">@{{ ctas.length }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="{ active: filtro.tipo === 'direccion' }" href="#tab-zona" @click.prevent="cambiarTab('direccion')">
                    <i class="fa-solid fa-map-location-dot"></i> Por Zona / Dirección
                    <span class="tab-badge" v-if="filtro.tipo === 'direccion' && ctas.length">@{{ ctas.length }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" :class="{ active: filtro.tipo === 'exportar' }" href="#tab-exportar" @click.prevent="cambiarTab('exportar')">
                    <i class="fa-solid fa-file-excel"></i> Exportación Oficial
                </a>
            </li>
        </ul>

        <!-- ========================================== -->
        <!-- TAB 1: POR FECHA / VENCIMIENTO -->
        <!-- ========================================== -->
        <div v-show="filtro.tipo === 'fecha'">
            <div class="filter-section">
                <!-- Atajos rápidos -->
                <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                    <span class="filter-label mb-0 mr-2"><i class="fa fa-bolt text-warning mr-1"></i> Rango de Vencimiento:</span>
                    <button type="button" class="preset-pill" @click="setPreset('hoy')">Vence Hoy</button>
                    <button type="button" class="preset-pill" @click="setPreset('semana')">Esta Semana</button>
                    <button type="button" class="preset-pill active" @click="setPreset('mes')">Este Mes</button>
                    <button type="button" class="preset-pill" @click="setPreset('mes_anterior')">Mes Anterior</button>
                    <button type="button" class="preset-pill" @click="setPreset('prox15')">Próximos 15 Días</button>
                    <button type="button" class="preset-pill" @click="setPreset('prox30')">Próximos 30 Días</button>
                </div>

                <div class="row align-items-end">
                    <div class="col-lg-2 col-md-3 col-sm-6 mb-2">
                        <label class="filter-label">Vencimiento Desde</label>
                        <input type="date" class="form-control form-control-sm" v-model="filtro.desde">
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-6 mb-2">
                        <label class="filter-label">Vencimiento Hasta</label>
                        <input type="date" class="form-control form-control-sm" v-model="filtro.hasta">
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-6 mb-2">
                        <label class="filter-label">Sucursal</label>
                        <select class="form-control form-control-sm" v-model="idSucursal">
                            <option value="0">Todas las Sucursales</option>
                            @if(isset($sucursales))
                                @foreach($sucursales as $s)
                                    <option value="{{ $s->suc_cod }}">{{ $s->suc_desc }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-6 mb-2">
                        <label class="filter-label">Ordenar Por</label>
                        <select class="form-control form-control-sm" v-model="filtro.ordenarpor">
                            <option value="1">N° de Venta</option>
                            <option value="4">Fecha Venta</option>
                            <option value="3">Nombre Cliente</option>
                            <option value="6">Total Venta</option>
                            <option value="7">Saldo Pendiente</option>
                            <option value="8">Dirección</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-2">
                        <label class="filter-label">Filtro Estado</label>
                        <select class="form-control form-control-sm" v-model="filtroEstado">
                            <option value="solo_saldo">Solo con Saldo Pendiente</option>
                            <option value="vencidas">Solo Cuotas en Mora</option>
                            <option value="todas">Todas las Cuotas</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-8 col-sm-12 mb-2 d-flex gap-2">
                        <button type="button" class="btn-pos-primary flex-grow-1" @click="buscar('fecha')" :disabled="requestSend">
                            <span v-if="requestSend"><i class="fa fa-spinner fa-spin mr-1"></i> Buscando...</span>
                            <span v-else><i class="fa fa-search mr-1"></i> Consultar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: POR CLIENTE -->
        <!-- ========================================== -->
        <div v-show="filtro.tipo === 'cliente'">
            <div class="filter-section">
                <div class="row align-items-end">
                    <div class="col-lg-5 col-md-6 mb-2">
                        <label class="filter-label">Buscar Cliente</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" v-model.trim="txtbuscar"
                                placeholder="Nombre, RUC, CI o N° de Venta..."
                                @keyup.enter="buscar('cliente')">
                            <div class="input-group-append" v-if="txtbuscar">
                                <button class="btn btn-outline-secondary" type="button" @click="txtbuscar = ''; ctas = []">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-6 mb-2">
                        <label class="filter-label">Sucursal</label>
                        <select class="form-control form-control-sm" v-model="idSucursal">
                            <option value="0">Todas las Sucursales</option>
                            @if(isset($sucursales))
                                @foreach($sucursales as $s)
                                    <option value="{{ $s->suc_cod }}">{{ $s->suc_desc }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-6 mb-2">
                        <label class="filter-label">Ordenar Por</label>
                        <select class="form-control form-control-sm" v-model="filtro.ordenarpor">
                            <option value="1">N° Venta</option>
                            <option value="3">Cliente</option>
                            <option value="6">Total</option>
                            <option value="7">Saldo</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-12 mb-2">
                        <button type="button" class="btn-pos-primary btn-block" @click="buscar('cliente')" :disabled="requestSend">
                            <span v-if="requestSend"><i class="fa fa-spinner fa-spin mr-1"></i> Buscando...</span>
                            <span v-else><i class="fa fa-search mr-1"></i> Buscar Créditos</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: POR ZONA / DIRECCIÓN -->
        <!-- ========================================== -->
        <div v-show="filtro.tipo === 'direccion'">
            <div class="filter-section">
                <div class="row align-items-end">
                    <div class="col-lg-5 col-md-6 mb-2">
                        <label class="filter-label">Barrio, Ciudad o Dirección</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" v-model.trim="txtbuscar"
                                placeholder="Escriba barrio, calle, ciudad o 'TODOS'..."
                                @keyup.enter="buscar('direccion')">
                            <div class="input-group-append" v-if="txtbuscar">
                                <button class="btn btn-outline-secondary" type="button" @click="txtbuscar = ''; ctas = []">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-6 mb-2">
                        <label class="filter-label">Sucursal</label>
                        <select class="form-control form-control-sm" v-model="idSucursal">
                            <option value="0">Todas las Sucursales</option>
                            @if(isset($sucursales))
                                @foreach($sucursales as $s)
                                    <option value="{{ $s->suc_cod }}">{{ $s->suc_desc }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-6 mb-2">
                        <label class="filter-label">Ordenar Por</label>
                        <select class="form-control form-control-sm" v-model="filtro.ordenarpor">
                            <option value="8">Dirección / Zona</option>
                            <option value="3">Cliente</option>
                            <option value="7">Saldo</option>
                            <option value="1">N° Venta</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-12 mb-2">
                        <button type="button" class="btn-pos-primary btn-block" @click="buscar('direccion')" :disabled="requestSend">
                            <span v-if="requestSend"><i class="fa fa-spinner fa-spin mr-1"></i> Buscando...</span>
                            <span v-else><i class="fa fa-search mr-1"></i> Filtrar por Zona</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 4: EXPORTACIÓN OFICIAL -->
        <!-- ========================================== -->
        <div v-show="filtro.tipo === 'exportar'">
            <div class="p-5 text-center">
                <i class="fa-solid fa-file-excel fa-4x text-success mb-3"></i>
                <h4 class="font-cairo font-weight-bold">Exportación Completa de Cuentas a Cobrar</h4>
                <p class="text-muted" style="max-width: 500px; margin: 0 auto 1.5rem;">
                    Genera una planilla completa en formato Excel (.xlsx) con todas las cuentas, cuotas pendientes, datos de clientes y saldos para auditoría o gestión externa de cobradores.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn-pos-primary" @click="exportarOficial">
                        <i class="fa fa-download mr-1"></i> Descargar Excel Oficial (.xlsx)
                    </button>
                    <button type="button" class="btn-pos-secondary" @click="exportarCSV">
                        <i class="fa fa-file-csv mr-1"></i> Descargar CSV Inmediato
                    </button>
                </div>
            </div>
        </div>

        <!-- Barra de Control de Visualización y Búsqueda en Vivo (para tabs 1, 2 y 3) -->
        <div class="px-3 py-2 bg-light border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2" v-if="filtro.tipo !== 'exportar'">
            <div class="d-flex align-items-center gap-2 flex-grow-1" style="max-width: 400px;">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-filter text-muted"></i></span>
                    </div>
                    <input type="text" class="form-control" v-model="filtroEnVivo" placeholder="Filtrar en pantalla por cliente, RUC, zona...">
                    <div class="input-group-append" v-if="filtroEnVivo">
                        <button class="btn btn-outline-secondary" type="button" @click="filtroEnVivo = ''">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="small font-weight-bold text-muted">
                    Mostrando: <strong>@{{ ctasFiltradas.length }}</strong> cuentas
                </span>
                <!-- Switch Fichas vs Tabla -->
                <div class="d-inline-flex">
                    <button type="button" class="view-mode-btn" :class="{ active: filtro.presentacion == 1 }" @click="filtro.presentacion = 1" title="Vista Fichas Detalladas">
                        <i class="fa fa-id-card mr-1"></i> Fichas
                    </button>
                    <button type="button" class="view-mode-btn" :class="{ active: filtro.presentacion == 2 }" @click="filtro.presentacion = 2" title="Vista Tabla Resumida">
                        <i class="fa fa-table-list mr-1"></i> Tabla
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- CONTENIDO: FICHAS DETALLADAS (presentacion == 1) -->
        <!-- ========================================== -->
        <div class="p-3" v-if="filtro.tipo !== 'exportar' && filtro.presentacion == 1">
            <template v-if="ctasFiltradas.length > 0">
                <cta-cuenta-card
                    v-for="(c, index) in ctasFiltradas"
                    :key="'cta-' + index + '-' + c.nro_fact_ventas"
                    :c="c"
                    :articulos="articulos"
                    :variante="filtro.tipo"
                    :tipo="filtro.tipo"
                    :empresa="'{{ $empresa->emp_nombre ?? 'SoftSystem' }}'"
                ></cta-cuenta-card>
            </template>
            <div class="text-center py-5 text-muted" v-else>
                <i class="fa fa-inbox fa-3x mb-2 text-muted" style="opacity: 0.4;"></i>
                <p class="mb-0">No se encontraron cuentas a cobrar para los filtros aplicados.</p>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- CONTENIDO: TABLA RESUMIDA (presentacion == 2) -->
        <!-- ========================================== -->
        <div class="p-3" v-if="filtro.tipo !== 'exportar' && filtro.presentacion == 2">
            <div class="table-responsive" v-if="ctasFiltradas.length > 0">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th style="width: 80px;">N° Venta</th>
                            <th>Cliente</th>
                            <th>RUC / CI</th>
                            <th>Contacto</th>
                            <th>Dirección / Zona</th>
                            <th>Vencimiento</th>
                            <th class="text-right">Total Venta</th>
                            <th class="text-right">Cobrado</th>
                            <th class="text-right">Saldo Pendiente</th>
                            <th class="text-center" style="width: 140px;">Estado / Mora</th>
                            <th class="text-right" style="width: 130px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(c, index) in ctasFiltradas" :key="'tr-' + index + '-' + c.nro_fact_ventas">
                            <td>
                                <span class="badge-code font-weight-bold">#@{{ c.nro_fact_ventas }}</span>
                            </td>
                            <td>
                                <strong>@{{ c.cliente_nombre }}</strong>
                            </td>
                            <td>@{{ c.cliente_ruc || '—' }}</td>
                            <td>
                                <span v-if="c.cliente_cel">
                                    <i class="fa fa-phone mr-1 text-muted small"></i>@{{ c.cliente_cel }}
                                </span>
                                <span class="text-muted" v-else>—</span>
                            </td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 160px;" :title="c.cliente_direccion">
                                    @{{ c.cliente_direccion || '—' }}
                                </span>
                            </td>
                            <td class="text-nowrap">@{{ formatFecha(c.fecha_v) }}</td>
                            <td class="text-right font-weight-bold">
                                Gs. @{{ format(c.total || c.venta_total) }}
                            </td>
                            <td class="text-right text-success">
                                Gs. @{{ format(c.cobrado || 0) }}
                            </td>
                            <td class="text-right font-weight-bold font-cairo text-danger" style="font-size: 0.95rem;">
                                Gs. @{{ format(c.saldo) }}
                            </td>
                            <td class="text-center">
                                <span class="badge" :class="calcularAtraso(c.fecha_v).vencido ? 'badge-danger' : 'badge-success'">
                                    <i class="fa mr-1" :class="calcularAtraso(c.fecha_v).vencido ? 'fa-triangle-exclamation' : 'fa-check'"></i>
                                    @{{ calcularAtraso(c.fecha_v).texto }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="d-flex align-items-center justify-content-end gap-1">
                                    <button type="button" class="btn btn-xs btn-outline-success" @click="enviarWhatsApp(c)" title="Recordatorio WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </button>
                                    <a :href="'{{ url('documento/extractocuenta') }}/' + c.nro_fact_ventas" class="btn btn-xs btn-outline-primary" target="_blank" title="Extracto de Cuenta">
                                        <i class="fa fa-file-invoice"></i>
                                    </a>
                                    <a :href="'{{ url('pdf/boletaventa') }}/' + c.nro_fact_ventas" class="btn btn-xs btn-outline-secondary" target="_blank" title="Comprobante Venta">
                                        <i class="fa fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="text-center py-5 text-muted" v-else>
                <i class="fa fa-inbox fa-3x mb-2 text-muted" style="opacity: 0.4;"></i>
                <p class="mb-0">No se encontraron cuentas a cobrar para los filtros aplicados.</p>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- PLANTILLA DEL COMPONENTE CTA-CUENTA-CARD -->
<!-- ========================================== -->
<script type="text/x-template" id="cta-cuenta-card-tpl">
    <div class="cta-card">
        <!-- Cabecera de la Ficha -->
        <div class="cta-card-header">
            <span class="cta-nombre font-cairo">
                <i class="fa fa-user-circle mr-1"></i> @{{ c.cliente_nombre }}
            </span>
            <span class="cta-meta">
                <i class="fa fa-id-card"></i> @{{ c.cliente_ruc || 'Sin RUC' }}
            </span>
            <span class="cta-meta" v-if="c.cliente_direccion">
                <i class="fa fa-map-marker-alt"></i> @{{ c.cliente_direccion }}
            </span>
            <span class="cta-meta" v-if="c.cliente_cel">
                <i class="fa fa-phone-alt"></i> @{{ c.cliente_cel }}
            </span>
            <span class="cta-meta" v-if="c.suc_desc">
                <span class="badge badge-light border">@{{ c.suc_desc }}</span>
            </span>
        </div>

        <!-- Barra de Progreso de Cobro -->
        <div class="cta-progress-wrap" v-if="c.total || c.venta_total">
            <div class="d-flex justify-content-between small text-muted mb-1">
                <span>Cobrado: <strong>@{{ getCobradoPct() }}%</strong></span>
                <span>Saldo Pendiente: <strong>@{{ 100 - getCobradoPct() }}%</strong></span>
            </div>
            <div class="cta-progress-bar">
                <div class="cta-progress-fill" :style="{ width: getCobradoPct() + '%' }"></div>
            </div>
        </div>

        <!-- Estadísticas Clave de la Venta a Crédito -->
        <div class="cta-card-summary">
            <div class="cta-stat">
                <span class="cta-stat-label">Nro. Venta</span>
                <span class="cta-stat-value badge-code font-weight-bold">#@{{ c.nro_fact_ventas }}</span>
            </div>
            <div class="cta-stat">
                <span class="cta-stat-label">Fecha Venta</span>
                <span class="cta-stat-value">@{{ c.venta_fecha }}</span>
            </div>
            <div class="cta-stat">
                <span class="cta-stat-label">Total Financiado</span>
                <span class="cta-stat-value font-cairo">Gs. @{{ format(c.total || c.venta_total) }}</span>
            </div>
            <div class="cta-stat cta-stat-cobrado">
                <span class="cta-stat-label">Total Cobrado</span>
                <span class="cta-stat-value font-cairo text-success">Gs. @{{ format(c.cobrado || 0) }}</span>
            </div>
            <div class="cta-stat cta-stat-saldo">
                <span class="cta-stat-label">Saldo Pendiente</span>
                <span class="cta-stat-value font-cairo text-danger font-weight-bold">Gs. @{{ format(c.saldo) }}</span>
            </div>
            <div class="cta-stat">
                <span class="cta-stat-label">Vencimiento & Estado</span>
                <div class="cta-stat-value">
                    <span class="badge" :class="infoAtraso.vencido ? 'badge-danger' : 'badge-success'">
                        <i class="fa mr-1" :class="infoAtraso.vencido ? 'fa-triangle-exclamation' : 'fa-check'"></i>
                        @{{ infoAtraso.texto }}
                    </span>
                    <small class="text-muted d-block mt-1" v-if="c.fecha_v">(@{{ formatFecha(c.fecha_v) }})</small>
                </div>
            </div>
        </div>

        <!-- Desglose de Artículos (Desplegable) -->
        <div class="cta-card-detalle">
            <button type="button" class="cta-detalle-btn" @click="mostrarDetalle = !mostrarDetalle">
                <i class="fa" :class="mostrarDetalle ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                <span v-if="mostrarDetalle">Ocultar Artículos de la Venta</span>
                <span v-else>Ver Artículos de la Venta (@{{ detalles.length }} ítems)</span>
            </button>

            <div class="table-responsive mt-2" v-show="mostrarDetalle">
                <table class="table table-sm table-striped table-bordered mb-0 bg-white" style="font-size: 0.85rem;">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 120px;">Código</th>
                            <th>Descripción</th>
                            <th class="text-center" style="width: 80px;">Cant.</th>
                            <th class="text-right" style="width: 120px;">Precio Unit.</th>
                            <th class="text-right" style="width: 130px;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(dv, i) in detalles" :key="'det-' + i">
                            <td><span class="badge-code">@{{ dv.producto_c_barra }}</span></td>
                            <td><strong>@{{ dv.producto_nombre }}</strong></td>
                            <td class="text-center font-weight-bold">@{{ parseInt(dv.venta_cantidad) }}</td>
                            <td class="text-right">Gs. @{{ format(dv.venta_precio) }}</td>
                            <td class="text-right font-weight-bold font-cairo" style="color: var(--dash-primary);">
                                Gs. @{{ format(dv.venta_cantidad * dv.venta_precio) }}
                            </td>
                        </tr>
                        <tr v-if="!detalles.length">
                            <td colspan="5" class="text-center text-muted py-2">Sin artículos específicos en caché.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Barra de Acciones de la Ficha -->
        <div class="cta-card-actions">
            <button type="button" class="btn btn-sm btn-outline-success" @click="enviarWhatsApp" title="Enviar recordatorio amistoso de pago por WhatsApp">
                <i class="fab fa-whatsapp mr-1"></i> WhatsApp
            </button>
            <a :href="'{{ url('documento/extractocuenta') }}/' + c.nro_fact_ventas" class="btn btn-sm btn-outline-primary" target="_blank">
                <i class="fa fa-file-invoice mr-1"></i> Extracto PDF
            </a>
            <a :href="'{{ url('pdf/boletaventa') }}/' + c.nro_fact_ventas" class="btn btn-sm btn-outline-secondary" target="_blank">
                <i class="fa fa-file-pdf mr-1"></i> Comprobante Venta
            </a>
            <a :href="'{{ route('cobro') }}'" class="btn btn-sm btn-pos-primary">
                <i class="fa fa-cash-register mr-1"></i> Cobrar Cuota
            </a>
        </div>
    </div>
</script>
@endsection

@section('script')
<script>
    Vue.component('cta-cuenta-card', {
        props: {
            c: { type: Object, required: true },
            articulos: { type: Array, default: function () { return []; } },
            variante: { type: String, default: 'fecha' },
            tipo: { type: String, default: 'fecha' },
            empresa: { type: String, default: 'SoftSystem' }
        },
        template: '#cta-cuenta-card-tpl',
        data: function () {
            return {
                mostrarDetalle: false
            };
        },
        computed: {
            detalles: function () {
                var nro = this.c.nro_fact_ventas;
                return (this.articulos || []).filter(function (venta) {
                    return venta.nro_fact_ventas == nro;
                });
            },
            infoAtraso: function () {
                return this.$root.calcularAtraso(this.c.fecha_v);
            }
        },
        methods: {
            format: function (numero) {
                return new Intl.NumberFormat("de-DE").format(Math.round(Number(numero) || 0));
            },
            formatFecha: function (fecha) {
                if (!fecha) return '—';
                var f = String(fecha).split("-");
                if (f.length < 3) return fecha;
                return f[2] + "/" + f[1] + "/" + f[0];
            },
            getCobradoPct: function () {
                var tot = parseFloat(this.c.total || this.c.venta_total) || 0;
                var cob = parseFloat(this.c.cobrado) || 0;
                if (tot <= 0) return 0;
                return Math.min(100, Math.max(0, Math.round((cob / tot) * 100)));
            },
            enviarWhatsApp: function () {
                this.$root.enviarWhatsApp(this.c);
            }
        }
    });

    var app = new Vue({
        el: '#app',
        data: {
            requestSend: false,
            idSucursal: 0,
            filtroEstado: 'solo_saldo',
            filtroEnVivo: '',
            filtro: {
                desde: '',
                hasta: '',
                orden: 'ASC',
                busquedapor: '',
                ordenarpor: 1,
                presentacion: 1,
                tipo: 'fecha'
            },
            txtbuscar: '',
            ctas: [],
            articulos: [],
            error: ''
        },
        computed: {
            ctasFiltradas: function () {
                var self = this;
                var list = self.ctas;

                if (self.filtroEstado === 'solo_saldo') {
                    list = list.filter(function (c) { return parseFloat(c.saldo || 0) > 0; });
                } else if (self.filtroEstado === 'vencidas') {
                    list = list.filter(function (c) {
                        var info = self.calcularAtraso(c.fecha_v);
                        return parseFloat(c.saldo || 0) > 0 && info.vencido;
                    });
                }

                if (self.filtroEnVivo.trim()) {
                    var q = self.filtroEnVivo.toLowerCase().trim();
                    list = list.filter(function (c) {
                        return (c.cliente_nombre && c.cliente_nombre.toLowerCase().indexOf(q) !== -1) ||
                               (c.cliente_ruc && c.cliente_ruc.toLowerCase().indexOf(q) !== -1) ||
                               (c.cliente_direccion && c.cliente_direccion.toLowerCase().indexOf(q) !== -1) ||
                               (c.cliente_cel && c.cliente_cel.indexOf(q) !== -1) ||
                               (String(c.nro_fact_ventas).indexOf(q) !== -1);
                    });
                }

                return list;
            },
            totalSaldo: function () {
                var total = 0;
                for (var i = 0; i < this.ctasFiltradas.length; i++) {
                    total += parseFloat(this.ctasFiltradas[i].saldo || 0);
                }
                return total;
            },
            totalCobrado: function () {
                var total = 0;
                for (var i = 0; i < this.ctasFiltradas.length; i++) {
                    total += parseFloat(this.ctasFiltradas[i].cobrado || 0);
                }
                return total;
            },
            totalImporte: function () {
                var total = 0;
                for (var i = 0; i < this.ctasFiltradas.length; i++) {
                    total += parseFloat(this.ctasFiltradas[i].total || this.ctasFiltradas[i].venta_total || 0);
                }
                return total;
            },
            totalVencidas: function () {
                var c = 0;
                for (var i = 0; i < this.ctasFiltradas.length; i++) {
                    var item = this.ctasFiltradas[i];
                    if (parseFloat(item.saldo || 0) > 0 && this.calcularAtraso(item.fecha_v).vencido) {
                        c++;
                    }
                }
                return c;
            },
            saldoVencido: function () {
                var s = 0;
                for (var i = 0; i < this.ctasFiltradas.length; i++) {
                    var item = this.ctasFiltradas[i];
                    if (parseFloat(item.saldo || 0) > 0 && this.calcularAtraso(item.fecha_v).vencido) {
                        s += parseFloat(item.saldo || 0);
                    }
                }
                return s;
            }
        },
        methods: {
            format: function (numero) {
                return new Intl.NumberFormat("de-DE").format(Math.round(Number(numero) || 0));
            },
            formatFecha: function (fecha) {
                if (!fecha) return '—';
                const f = String(fecha).split("-");
                if (f.length < 3) return fecha;
                return f[2] + "/" + f[1] + "/" + f[0];
            },
            cambiarTab: function (tab) {
                this.filtro.tipo = tab;
                if (tab === 'fecha' || tab === 'exportar') {
                    if (tab === 'fecha') this.buscar('fecha');
                }
            },
            getCobradoPct: function () {
                if (this.totalImporte <= 0) return 0;
                return Math.round((this.totalCobrado / this.totalImporte) * 100);
            },
            calcularAtraso: function (fechaVenc) {
                if (!fechaVenc) return { dias: 0, texto: 'Al día', vencido: false };
                var fVenc = new Date(fechaVenc + 'T00:00:00');
                var hoy = new Date();
                hoy.setHours(0, 0, 0, 0);
                var diffMs = hoy.getTime() - fVenc.getTime();
                var dias = Math.floor(diffMs / (1000 * 60 * 60 * 24));
                if (dias > 0) {
                    return { dias: dias, texto: dias + ' días de mora', vencido: true };
                } else if (dias === 0) {
                    return { dias: 0, texto: 'Vence hoy', vencido: true };
                } else {
                    return { dias: Math.abs(dias), texto: 'Vence en ' + Math.abs(dias) + ' días', vencido: false };
                }
            },
            setPreset: function (tipo) {
                var hoy = new Date();
                var d1 = new Date();
                var d2 = new Date();

                if (tipo === 'hoy') {
                    // Mismo día
                } else if (tipo === 'semana') {
                    var diaSem = hoy.getDay();
                    var diff = hoy.getDate() - diaSem + (diaSem === 0 ? -6 : 1);
                    d1 = new Date(hoy.setDate(diff));
                    d2 = new Date();
                } else if (tipo === 'mes') {
                    d1 = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
                    d2 = new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0);
                } else if (tipo === 'mes_anterior') {
                    d1 = new Date(hoy.getFullYear(), hoy.getMonth() - 1, 1);
                    d2 = new Date(hoy.getFullYear(), hoy.getMonth(), 0);
                } else if (tipo === 'prox15') {
                    d1 = new Date();
                    d2 = new Date();
                    d2.setDate(d2.getDate() + 15);
                } else if (tipo === 'prox30') {
                    d1 = new Date();
                    d2 = new Date();
                    d2.setDate(d2.getDate() + 30);
                }

                this.filtro.desde = this.formatFechaISO(d1);
                this.filtro.hasta = this.formatFechaISO(d2);
                this.buscar('fecha');
            },
            formatFechaISO: function (d) {
                var mes = (d.getMonth() + 1).toString().padStart(2, '0');
                var dia = d.getDate().toString().padStart(2, '0');
                return d.getFullYear() + '-' + mes + '-' + dia;
            },
            buscar: function (tipo) {
                if (this.requestSend) return false;
                this.filtro.tipo = tipo;
                this.requestSend = true;

                if (tipo === 'cliente') {
                    var t = parseFloat(this.txtbuscar);
                    this.filtro.busquedapor = isNaN(t) ? 'nombre' : 'ci';
                }

                var self = this;
                axios.get('ctas_cobrar/buscar', {
                    params: {
                        tipo: tipo,
                        buscar: self.txtbuscar,
                        desde: self.filtro.desde,
                        hasta: self.filtro.hasta,
                        buscarpor: self.filtro.busquedapor,
                        ordenarpor: self.filtro.ordenarpor,
                        ord: self.filtro.orden,
                        sucursal: self.idSucursal,
                        from: 'inf'
                    }
                }).then(function (response) {
                    self.requestSend = false;
                    if (response.data === 'NO' || !response.data) {
                        self.ctas = [];
                        self.articulos = [];
                        Swal.fire('Sin resultados', 'No se encontraron cuentas a cobrar para el filtro especificado.', 'info');
                    } else {
                        self.ctas = response.data.ctas || [];
                        self.articulos = response.data.articulos || [];
                    }
                }).catch(function (e) {
                    self.requestSend = false;
                    console.error(e);
                    Swal.fire('Error', 'No se pudo consultar las cuentas a cobrar.', 'error');
                });
            },
            enviarWhatsApp: function (c) {
                if (!c.cliente_cel || !c.cliente_cel.trim()) {
                    Swal.fire('Sin teléfono', 'El cliente no posee un número de celular registrado.', 'warning');
                    return;
                }
                var cel = c.cliente_cel.replace(/[^0-9]/g, '');
                if (cel.startsWith('0')) {
                    cel = '595' + cel.substring(1);
                } else if (!cel.startsWith('595')) {
                    cel = '595' + cel;
                }
                var nombre = c.cliente_nombre || 'Estimado/a cliente';
                var saldo = this.format(c.saldo);
                var empresa = '{{ $empresa->emp_nombre ?? "SoftSystem" }}';
                var texto = encodeURIComponent(
                    'Hola ' + nombre + ', le saludamos de *' + empresa + '*. ' +
                    'Le recordamos que registra un saldo pendiente de *Gs. ' + saldo + '* ' +
                    'correspondiente a su crédito #' + c.nro_fact_ventas + '. ' +
                    'Quedamos a su disposición para coordinar su cobro. ¡Muchas gracias!'
                );
                window.open('https://api.whatsapp.com/send?phone=' + cel + '&text=' + texto, '_blank');
            },
            exportarOficial: function () {
                window.open('excel/ctascobrar');
            },
            exportarCSV: function () {
                if (!this.ctasFiltradas.length) {
                    Swal.fire('Atención', 'No hay cuentas para exportar en este momento.', 'warning');
                    return;
                }
                var csv = "\uFEFFNro_Venta;Cliente;RUC_CI;Telefono;Direccion;Fecha_Venta;Vencimiento;Total_Venta;Cobrado;Saldo;Atraso_Mora;Sucursal\n";
                var self = this;
                this.ctasFiltradas.forEach(function (c) {
                    var info = self.calcularAtraso(c.fecha_v);
                    csv += [
                        c.nro_fact_ventas,
                        '"' + (c.cliente_nombre || '').replace(/"/g, '""') + '"',
                        '"' + (c.cliente_ruc || '').replace(/"/g, '""') + '"',
                        '"' + (c.cliente_cel || '').replace(/"/g, '""') + '"',
                        '"' + (c.cliente_direccion || '').replace(/"/g, '""') + '"',
                        '"' + (c.venta_fecha || '').replace(/"/g, '""') + '"',
                        '"' + (c.fecha_v || '').replace(/"/g, '""') + '"',
                        c.total || c.venta_total || 0,
                        c.cobrado || 0,
                        c.saldo || 0,
                        '"' + info.texto.replace(/"/g, '""') + '"',
                        '"' + (c.suc_desc || '').replace(/"/g, '""') + '"'
                    ].join(';') + "\n";
                });
                var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                var link = document.createElement("a");
                link.href = URL.createObjectURL(blob);
                link.download = 'cuentas_a_cobrar_' + (new Date().toISOString().slice(0, 10)) + '.csv';
                link.click();
            }
        },
        mounted: function () {
            var hoy = new Date();
            var primerDia = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
            var ultimoDia = new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0);
            this.filtro.desde = this.formatFechaISO(primerDia);
            this.filtro.hasta = this.formatFechaISO(ultimoDia);
            this.buscar('fecha');
        }
    });

    activarMenu('m_informe', 'm_ictacobrar');
</script>
@endsection
