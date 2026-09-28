@extends('layouts.app')
@section('title', 'Informe de Inventario')

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
        --dash-panel-bg: #f8fafc;
        --dash-border: #e2e8f0;
        --dash-danger: #dc2626;
        --dash-danger-light: #fee2e2;
        --dash-success: #10b981;
        --dash-success-light: #ecfdf5;
        --dash-warning: #d97706;
        --dash-warning-light: #fffbeb;
        --dash-info: #0284c7;
        --dash-info-light: #e0f2fe;
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
        --dash-panel-bg: #111827;
        --dash-border: #374151;
        --dash-danger: #f87171;
        --dash-danger-light: #450a0a;
        --dash-success: #34d399;
        --dash-success-light: #064e3b;
        --dash-warning: #fbbf24;
        --dash-warning-light: #451a03;
        --dash-info: #38bdf8;
        --dash-info-light: #082f49;
    }

    [v-cloak] {
        display: none !important;
    }

    .informe-wrapper {
        color: var(--dash-text-main);
    }

    /* Header */
    .dash-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.25rem;
        padding-bottom: 0.85rem;
        border-bottom: 1px solid var(--dash-border);
    }
    .dash-header-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dash-primary);
        margin: 0;
        line-height: 1.2;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .dash-header-subtitle {
        font-size: 0.9rem;
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
        border-radius: 14px;
        padding: 1.15rem 1.25rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: calc(100% - 1.25rem);
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
    }
    .kpi-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }
    .kpi-label {
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--dash-text-muted);
        margin: 0;
    }
    .kpi-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
    }
    .kpi-icon-art { background: #eff6ff; color: #2563eb; }
    .kpi-icon-stock { background: var(--dash-primary-light); color: var(--dash-primary); }
    .kpi-icon-costo { background: #fdf4ff; color: #a855f7; }
    .kpi-icon-venta { background: #ecfdf5; color: #059669; }

    .kpi-value {
        font-size: 1.55rem;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 0.25rem;
        font-variant-numeric: tabular-nums;
        color: var(--dash-text-main);
    }
    .kpi-footer {
        font-size: 0.8rem;
        color: var(--dash-text-muted);
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    /* Filter Card */
    .filter-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        margin-bottom: 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }
    .filter-header {
        padding: 0.75rem 1.25rem;
        background: var(--dash-panel-bg);
        border-bottom: 1px solid var(--dash-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .filter-header-title {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--dash-text-main);
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }
    .filter-body {
        padding: 1.15rem 1.25rem;
    }
    .filter-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--dash-text-muted);
        margin-bottom: 0.35rem;
        display: block;
    }
    .preset-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-bottom: 1rem;
    }
    .preset-pill {
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-muted);
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.35rem 0.8rem;
        border-radius: 999px;
        transition: all 0.15s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        user-select: none;
    }
    .preset-pill:hover {
        background: var(--dash-primary-light);
        border-color: var(--dash-primary-border);
        color: var(--dash-primary);
    }
    .preset-pill.active {
        background: var(--dash-primary);
        border-color: var(--dash-primary);
        color: #ffffff !important;
        font-weight: 700;
        box-shadow: 0 2px 6px rgba(10, 77, 54, 0.25);
    }
    .stock-filter-pill {
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-muted);
        font-size: 0.76rem;
        font-weight: 600;
        padding: 0.25rem 0.65rem;
        border-radius: 8px;
        transition: all 0.15s;
        cursor: pointer;
    }
    .stock-filter-pill.active {
        background: var(--dash-primary-light);
        border-color: var(--dash-primary-border);
        color: var(--dash-primary);
        font-weight: 700;
    }

    /* Buttons */
    .btn-pos-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        padding: 0.5rem 1.15rem;
        background: linear-gradient(135deg, var(--dash-primary), var(--dash-primary-dark));
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.88rem;
        border: none;
        border-radius: 9px;
        transition: all 0.15s ease;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(10, 77, 54, 0.25);
    }
    .btn-pos-primary:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(10, 77, 54, 0.35);
    }
    .btn-pos-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 0.5rem 0.95rem;
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        color: var(--dash-text-main) !important;
        font-weight: 600;
        font-size: 0.88rem;
        border-radius: 9px;
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .btn-pos-secondary:hover {
        background: var(--dash-primary-light);
        border-color: var(--dash-primary-border);
        color: var(--dash-primary) !important;
    }

    /* Badges */
    .badge-code {
        font-family: monospace;
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--dash-text-main);
    }
    .badge-stock-ok {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.82rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    body.dark-mode .badge-stock-ok {
        background: #064e3b;
        color: #a7f3d0;
        border-color: #047857;
    }
    .badge-stock-low {
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #92400e;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.82rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    body.dark-mode .badge-stock-low {
        background: #451a03;
        color: #fde68a;
        border-color: #78350f;
    }
    .badge-stock-empty {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.82rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    body.dark-mode .badge-stock-empty {
        background: #450a0a;
        color: #fca5a5;
        border-color: #7f1d1d;
    }
    .badge-in {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
        padding: 0.2rem 0.45rem;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 700;
    }
    .badge-out {
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
        padding: 0.2rem 0.45rem;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 700;
    }

    /* Table Container */
    .table-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }
    .table-card-header {
        padding: 0.9rem 1.25rem;
        background: var(--dash-panel-bg);
        border-bottom: 1px solid var(--dash-border);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    /* Vue Good Table custom styles */
    .vgt-table {
        font-size: 0.88rem !important;
        border: none !important;
        background: var(--dash-card-bg) !important;
        color: var(--dash-text-main) !important;
    }
    .vgt-table thead th {
        background: var(--dash-panel-bg) !important;
        color: var(--dash-text-muted) !important;
        font-weight: 700 !important;
        font-size: 0.76rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
        border-bottom: 1px solid var(--dash-border) !important;
        border-top: none !important;
        padding: 0.75rem 0.9rem !important;
    }
    .vgt-table td {
        padding: 0.65rem 0.9rem !important;
        vertical-align: middle !important;
        border-bottom: 1px solid var(--dash-border) !important;
        color: var(--dash-text-main) !important;
        background: var(--dash-card-bg) !important;
    }
    .vgt-table tr:hover td {
        background: var(--dash-primary-light) !important;
    }
    body.dark-mode .vgt-table tr:hover td {
        background: rgba(16, 185, 129, 0.12) !important;
    }
    .vgt-wrap__footer {
        background: var(--dash-panel-bg) !important;
        border-top: 1px solid var(--dash-border) !important;
        padding: 0.75rem 1.25rem !important;
        font-size: 0.85rem !important;
        color: var(--dash-text-muted) !important;
    }
    .vgt-global-search {
        background: transparent !important;
        border: none !important;
        padding: 0.85rem 1.25rem !important;
        border-bottom: 1px solid var(--dash-border) !important;
    }
    .vgt-global-search input {
        border-radius: 8px !important;
        border: 1px solid var(--dash-border) !important;
        background: var(--dash-panel-bg) !important;
        color: var(--dash-text-main) !important;
        padding: 0.45rem 0.85rem !important;
        font-size: 0.88rem !important;
    }
    .vgt-global-search input:focus {
        border-color: var(--dash-primary) !important;
        outline: none;
    }

    /* Modal Moderno */
    .modal-moderno {
        border-radius: 16px;
        overflow: hidden;
        background: var(--dash-card-bg);
        color: var(--dash-text-main);
        border: 1px solid var(--dash-border) !important;
    }
    .modal-header-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--dash-primary-light);
        border: 1px solid var(--dash-primary-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
    }
    .bg-light-panel {
        background-color: var(--dash-panel-bg) !important;
    }
    .detail-card {
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        height: 100%;
    }
    .detail-card-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--dash-text-muted);
        margin-bottom: 0.2rem;
    }

    /* Skeleton Spinner */
    .app-loading-skeleton {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.85);
        z-index: 9999;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    body.dark-mode .app-loading-skeleton {
        background: rgba(17, 24, 39, 0.85);
        color: #f3f4f6;
    }
    [v-cloak] ~ .app-loading-skeleton {
        display: flex;
    }
    .v-cloak-spinner {
        width: 44px;
        height: 44px;
        border: 4px solid var(--dash-primary-light);
        border-top-color: var(--dash-primary);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin-bottom: 0.75rem;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
@endsection

@section('main')
<div class="container-fluid informe-wrapper" id="app" v-cloak>
    <!-- Topbar & Page Header -->
    <div class="dash-header">
        <div>
            <h1 class="dash-header-title font-cairo">
                <i class="fa fa-boxes mr-1 text-success"></i> Informe de Inventario y Stock
            </h1>
            <p class="dash-header-subtitle">
                Control de existencias físicas, valorización a costo/venta y balance de entradas y salidas
            </p>
        </div>
        <div class="dash-header-badges">
            <!-- Dropdown Exportar Excel -->
            <div class="btn-group mr-2">
                <button type="button" class="btn btn-outline-success btn-sm dropdown-toggle font-weight-bold" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-file-excel mr-1"></i> Exportar a Excel
                </button>
                <div class="dropdown-menu dropdown-menu-right shadow">
                    <button class="dropdown-item" @click="exportar('stock')">
                        <i class="fa fa-boxes mr-1 text-success"></i> Artículos con Stock
                    </button>
                    <button class="dropdown-item" @click="exportar('costo')">
                        <i class="fa fa-tags mr-1 text-primary"></i> Inventario con Costos
                    </button>
                    <div class="dropdown-divider"></div>
                    <button class="dropdown-item" @click="exportar('precios')">
                        <i class="fa fa-list-alt mr-1 text-warning"></i> Precios de Crédito
                    </button>
                </div>
            </div>

            <button class="btn btn-pos-secondary btn-sm" @click="buscar" :disabled="requestSend" title="Actualizar datos">
                <i class="fa fa-sync-alt" :class="{ 'fa-spin': requestSend }"></i>
                <span class="d-none d-sm-inline ml-1">Actualizar</span>
            </button>
        </div>
    </div>

    <!-- KPI Cards Superiores -->
    <div class="row">
        <!-- KPI 1: Artículos Totales -->
        <div class="col-12 col-sm-6 col-xl-3 mb-3">
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-label">Artículos Registrados</span>
                    <div class="kpi-icon kpi-icon-art">
                        <i class="fa fa-box-open"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo">
                    @{{ totalArticulos }}
                </div>
                <div class="kpi-footer">
                    <span class="badge badge-stock-empty" v-if="articulosSinStock > 0">
                        @{{ articulosSinStock }} sin stock
                    </span>
                    <span class="text-muted ml-1" v-if="articulosCriticos > 0">
                        · @{{ articulosCriticos }} crítico(s)
                    </span>
                    <span class="text-success" v-else-if="articulosSinStock === 0">
                        <i class="fa fa-check-circle"></i> Todo con stock
                    </span>
                </div>
            </div>
        </div>

        <!-- KPI 2: Total Unidades Físicas -->
        <div class="col-12 col-sm-6 col-xl-3 mb-3">
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-label">Stock Físico Total</span>
                    <div class="kpi-icon kpi-icon-stock">
                        <i class="fa fa-cubes"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo text-success">
                    @{{ formatGs(totalStockFisico) }}
                </div>
                <div class="kpi-footer">
                    <i class="fa fa-warehouse text-muted"></i>
                    <span>Unidades disponibles en almacén</span>
                </div>
            </div>
        </div>

        <!-- KPI 3: Valor a Costo -->
        <div class="col-12 col-sm-6 col-xl-3 mb-3">
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-label">Capital en Stock (Costo)</span>
                    <div class="kpi-icon kpi-icon-costo">
                        <i class="fa fa-wallet"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo">
                    Gs. @{{ formatGs(valorTotalCosto) }}
                </div>
                <div class="kpi-footer">
                    <i class="fa fa-calculator text-muted"></i>
                    <span>Valorización calculada al costo</span>
                </div>
            </div>
        </div>

        <!-- KPI 4: Valor a Venta -->
        <div class="col-12 col-sm-6 col-xl-3 mb-3">
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-label">Potencial de Venta (P1)</span>
                    <div class="kpi-icon kpi-icon-venta">
                        <i class="fa fa-hand-holding-usd"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo text-success">
                    Gs. @{{ formatGs(valorTotalVenta) }}
                </div>
                <div class="kpi-footer">
                    <span class="text-success font-weight-bold">
                        Margen: Gs. @{{ formatGs(valorTotalVenta - valorTotalCosto) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de Filtros Moderno -->
    <div class="filter-card">
        <div class="filter-header">
            <span class="filter-header-title font-cairo">
                <i class="fa fa-filter text-success"></i> Filtros de Inventario y Movimientos
            </span>
            <div class="d-flex align-items-center gap-1">
                <button
                    type="button"
                    class="stock-filter-pill"
                    :class="{ active: filtroStock === 'todos' }"
                    @click="filtroStock = 'todos'; filtrarFilas();"
                >
                    Todos (@{{ rows.length }})
                </button>
                <button
                    type="button"
                    class="stock-filter-pill"
                    :class="{ active: filtroStock === 'con_stock' }"
                    @click="filtroStock = 'con_stock'; filtrarFilas();"
                >
                    Con Stock
                </button>
                <button
                    type="button"
                    class="stock-filter-pill"
                    :class="{ active: filtroStock === 'critico' }"
                    @click="filtroStock = 'critico'; filtrarFilas();"
                >
                    Crítico (&le; 5)
                </button>
                <button
                    type="button"
                    class="stock-filter-pill"
                    :class="{ active: filtroStock === 'sin_stock' }"
                    @click="filtroStock = 'sin_stock'; filtrarFilas();"
                >
                    Agotados (0)
                </button>
            </div>
        </div>
        <div class="filter-body">
            <!-- Píldoras de Rango Rápido para Movimientos -->
            <div class="preset-pills">
                <span class="small font-weight-bold text-muted mr-1 align-self-center">
                    Período movimientos:
                </span>
                <button type="button" class="preset-pill" :class="{ active: presetActivo === 'hoy' }" @click="aplicarPreset('hoy')">
                    Hoy
                </button>
                <button type="button" class="preset-pill" :class="{ active: presetActivo === 'semana' }" @click="aplicarPreset('semana')">
                    Esta Semana
                </button>
                <button type="button" class="preset-pill" :class="{ active: presetActivo === 'mes' }" @click="aplicarPreset('mes')">
                    Este Mes
                </button>
                <button type="button" class="preset-pill" :class="{ active: presetActivo === 'mes_ant' }" @click="aplicarPreset('mes_ant')">
                    Mes Anterior
                </button>
                <button type="button" class="preset-pill" :class="{ active: presetActivo === 'anho' }" @click="aplicarPreset('anho')">
                    Todo el Año
                </button>
            </div>

            <!-- Formulario de Filtros -->
            <div class="row align-items-end">
                <div class="col-12 col-sm-6 col-md-2 mb-3 mb-md-0">
                    <label class="filter-label"><i class="fa fa-calendar-alt mr-1"></i> Mov. Desde</label>
                    <input type="date" class="form-control form-control-sm" v-model="fecha.desde" @change="presetActivo = 'custom'">
                </div>

                <div class="col-12 col-sm-6 col-md-2 mb-3 mb-md-0">
                    <label class="filter-label"><i class="fa fa-calendar-alt mr-1"></i> Mov. Hasta</label>
                    <input type="date" class="form-control form-control-sm" v-model="fecha.hasta" @change="presetActivo = 'custom'">
                </div>

                <div class="col-12 col-sm-6 col-md-2 mb-3 mb-md-0">
                    <label class="filter-label"><i class="fa fa-store mr-1"></i> Sucursal</label>
                    <select class="form-control form-control-sm" v-model="idSucursal" @change="buscar">
                        <option value="0">Todas las sucursales</option>
                        @foreach ($sucursales as $suc)
                            <option value="{{ $suc->suc_cod }}">{{ $suc->suc_desc }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-2 mb-3 mb-md-0">
                    <label class="filter-label"><i class="fa fa-layer-group mr-1"></i> Sección / Categoría</label>
                    <select class="form-control form-control-sm" v-model="seccion" @change="buscar">
                        <option value="0">Todas las secciones</option>
                        @foreach ($secciones as $sec)
                            <option value="{{ $sec['present_cod'] }}">{{ $sec['present_descripcion'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-2 mb-3 mb-md-0">
                    <label class="filter-label"><i class="fa fa-search mr-1"></i> Nombre o Código</label>
                    <div class="input-group input-group-sm">
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            v-model.trim="txtbuscar"
                            placeholder="Buscar producto..."
                            @keyup.enter="buscar"
                        >
                        <div class="input-group-append" v-if="txtbuscar">
                            <button class="btn btn-outline-secondary" type="button" @click="txtbuscar = ''; buscar();" title="Limpiar">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-2 d-flex align-items-center gap-2">
                    <button type="button" class="btn-pos-primary w-100 mr-2" @click="buscar" :disabled="requestSend">
                        <template v-if="requestSend">
                            <span class="spinner-border spinner-border-sm" role="status"></span>
                            <span>Buscando...</span>
                        </template>
                        <template v-else>
                            <i class="fa fa-search"></i>
                            <span>Consultar</span>
                        </template>
                    </button>
                    <button type="button" class="btn-pos-secondary" @click="limpiarFiltros" title="Restablecer filtros">
                        <i class="fa fa-undo"></i>
                        <span class="d-none d-sm-inline">Limpiar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Resultados -->
    <div class="table-card">
        <div class="table-card-header">
            <div class="d-flex align-items-center">
                <i class="fa fa-list mr-2 text-success"></i>
                <strong class="font-cairo text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.04em;">
                    Planilla de Existencias y Movimientos
                </strong>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge badge-light border p-2">
                    Mostrando: <strong class="text-primary font-cairo ml-1">@{{ filasFiltradas.length }}</strong> de @{{ rows.length }} artículos
                </span>
            </div>
        </div>
        <div class="table-card-body">
            <vue-good-table
                :columns="columns"
                :rows="filasFiltradas"
                :search-options="{ enabled: true, placeholder: 'Filtrar en la tabla por código, nombre o sección...' }"
                :pagination-options="{ enabled: true, perPage: 15, perPageDropdown: [10, 15, 25, 50, 100], nextLabel: 'Siguiente', prevLabel: 'Anterior', rowsPerPageLabel: 'Filas por página', ofLabel: 'de', allLabel: 'Todos' }"
                style-class="vgt-table striped condensed"
            >
                <div slot="emptystate" class="text-center py-5">
                    <i class="fa fa-boxes fa-3x text-muted mb-3" style="opacity: 0.35;"></i>
                    <h6 class="font-weight-bold font-cairo">No se encontraron artículos</h6>
                    <p class="text-muted small mb-3">
                        Probá cambiando los filtros de sección, stock o búsqueda de texto.
                    </p>
                    <button type="button" class="btn btn-outline-primary btn-sm" @click="limpiarFiltros">
                        <i class="fa fa-undo mr-1"></i> Restablecer filtros
                    </button>
                </div>
            </vue-good-table>
        </div>
    </div>

    <!-- Modal Detalle Rápido de Artículo -->
    <div class="modal fade" id="modalArticuloDetalle" tabindex="-1" role="dialog" aria-labelledby="modalArticuloDetalleLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-moderno shadow-lg border-0">
                <div class="modal-header d-flex align-items-center justify-content-between p-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="modal-header-icon mr-2.5">
                            <i class="fa fa-box text-success"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-weight-bold font-cairo mb-0" id="modalArticuloDetalleLabel">
                                @{{ articuloSeleccionado.producto_nombre }}
                            </h5>
                            <small class="text-muted">
                                Código: @{{ articuloSeleccionado.producto_c_barra }} · Sección: @{{ articuloSeleccionado.present_descripcion }}
                            </small>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3">
                    <div class="row">
                        <div class="col-6 mb-2">
                            <div class="detail-card text-center">
                                <div class="detail-card-label">Stock Actual</div>
                                <div class="font-cairo font-weight-bold text-success" style="font-size: 1.5rem;">
                                    @{{ articuloSeleccionado.cantidad }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="detail-card text-center">
                                <div class="detail-card-label">Costo de Compra</div>
                                <div class="font-cairo font-weight-bold" style="font-size: 1.25rem;">
                                    Gs. @{{ formatGs(articuloSeleccionado.costo) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-card text-center">
                                <div class="detail-card-label">Precio Venta (P1)</div>
                                <div class="font-cairo font-weight-bold text-primary" style="font-size: 1.25rem;">
                                    Gs. @{{ formatGs(articuloSeleccionado.pre_venta1) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-card text-center">
                                <div class="detail-card-label">Valor en Stock</div>
                                <div class="font-cairo font-weight-bold text-success" style="font-size: 1.25rem;">
                                    Gs. @{{ formatGs(articuloSeleccionado.cantidad * articuloSeleccionado.costo) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 p-2 border rounded bg-light-panel text-center">
                        <div class="small text-muted font-weight-bold mb-1">Movimientos del período:</div>
                        <span class="badge badge-in mr-2"><i class="fa fa-arrow-down mr-1"></i> Entradas: @{{ articuloSeleccionado.entrada || 0 }}</span>
                        <span class="badge badge-out"><i class="fa fa-arrow-up mr-1"></i> Salidas: @{{ articuloSeleccionado.salida || 0 }}</span>
                    </div>
                </div>
                <div class="modal-footer p-2.5 bg-light-panel border-top">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-dismiss="modal">
                        <i class="fa fa-times mr-1"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Skeleton Loading Overlay -->
<div class="app-loading-skeleton">
    <div class="v-cloak-spinner"></div>
    <div class="font-cairo font-weight-bold" style="font-size: 1rem;">Cargando Informe de Inventario...</div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    var app = new Vue({
        el: '#app',
        data: {
            requestSend: false,
            fecha: {
                desde: '',
                hasta: ''
            },
            seccion: 0,
            idSucursal: 0,
            txtbuscar: '',
            presetActivo: 'mes',
            filtroStock: 'todos',
            articulos: [],
            articuloSeleccionado: {},
            columns: [
                { label: 'Código', field: 'codigoHtml', html: true, width: '130px' },
                { label: 'Descripción / Producto', field: 'descripcionHtml', html: true },
                { label: 'Sección', field: 'seccion', width: '130px' },
                { label: 'Costo Unit.', field: 'costoHtml', html: true, tdClass: 'text-right', width: '120px' },
                { label: 'Precio (P1)', field: 'precioHtml', html: true, tdClass: 'text-right', width: '120px' },
                { label: 'Stock Actual', field: 'stockHtml', html: true, tdClass: 'text-center', width: '120px' },
                { label: 'Entradas (+)', field: 'entradaHtml', html: true, tdClass: 'text-center', width: '100px' },
                { label: 'Salidas (-)', field: 'salidaHtml', html: true, tdClass: 'text-center', width: '100px' },
                { label: 'Valor Stock', field: 'valorStockHtml', html: true, tdClass: 'text-right', width: '130px' },
                { label: 'Info', field: 'acciones', html: true, sortable: false, width: '70px', tdClass: 'text-center' }
            ],
            rows: [],
            filasFiltradas: []
        },
        methods: {
            formatGs: function (n) {
                return new Intl.NumberFormat('de-DE').format(Math.round(Number(n) || 0));
            },
            formatDateYMD: function (date) {
                var y = date.getFullYear();
                var m = String(date.getMonth() + 1).padStart(2, '0');
                var d = String(date.getDate()).padStart(2, '0');
                return y + '-' + m + '-' + d;
            },
            aplicarPreset: function (preset) {
                this.presetActivo = preset;
                var hoy = new Date();
                var desde = new Date();
                var hasta = new Date();

                switch (preset) {
                    case 'hoy':
                        break;
                    case 'semana':
                        var day = desde.getDay();
                        var diff = desde.getDate() - day + (day === 0 ? -6 : 1);
                        desde.setDate(diff);
                        break;
                    case 'mes':
                        desde.setDate(1);
                        break;
                    case 'mes_ant':
                        desde.setMonth(desde.getMonth() - 1);
                        desde.setDate(1);
                        hasta = new Date(desde.getFullYear(), desde.getMonth() + 1, 0);
                        break;
                    case 'anho':
                        desde = new Date(hoy.getFullYear(), 0, 1);
                        break;
                }

                this.fecha.desde = this.formatDateYMD(desde);
                this.fecha.hasta = this.formatDateYMD(hasta);
                this.buscar();
            },
            limpiarFiltros: function () {
                this.seccion = 0;
                this.idSucursal = 0;
                this.txtbuscar = '';
                this.filtroStock = 'todos';
                this.aplicarPreset('mes');
            },
            buscar: function () {
                var self = this;
                self.requestSend = true;
                axios.get('{{ url('inventario/fecha') }}', {
                    params: {
                        desde: self.fecha.desde,
                        hasta: self.fecha.hasta,
                        seccion: self.seccion,
                        sucursal: self.idSucursal,
                        buscar: self.txtbuscar
                    }
                })
                .then(function (response) {
                    self.requestSend = false;
                    self.rows = [];
                    self.articulos = response.data || [];

                    for (var i = 0; i < self.articulos.length; i++) {
                        var a = self.articulos[i];
                        var stock = Number(a.cantidad) || 0;
                        var costo = Number(a.costo) || 0;
                        var precio = Number(a.pre_venta1) || 0;
                        var entrada = Number(a.entrada) || 0;
                        var salida = Number(a.salida) || 0;

                        var stockBadge = '';
                        if (stock <= 0) {
                            stockBadge = '<span class="badge-stock-empty"><i class="fa fa-times-circle mr-1"></i>0 Agotado</span>';
                        } else if (stock <= 5) {
                            stockBadge = '<span class="badge-stock-low"><i class="fa fa-exclamation-triangle mr-1"></i>' + stock + ' Crítico</span>';
                        } else {
                            stockBadge = '<span class="badge-stock-ok"><i class="fa fa-check mr-1"></i>' + stock + '</span>';
                        }

                        self.rows.push({
                            id: a.ARTICULOS_cod,
                            codigo: a.producto_c_barra || '—',
                            codigoHtml: '<span class="badge-code">' + (a.producto_c_barra || '—') + '</span>',
                            descripcion: a.producto_nombre,
                            descripcionHtml: '<strong class="font-cairo">' + a.producto_nombre + '</strong>',
                            seccion: a.present_descripcion || 'General',
                            costo: costo,
                            costoHtml: '<span>Gs. ' + self.formatGs(costo) + '</span>',
                            precio: precio,
                            precioHtml: '<strong class="text-primary">Gs. ' + self.formatGs(precio) + '</strong>',
                            stock: stock,
                            stockHtml: stockBadge,
                            entrada: entrada,
                            entradaHtml: entrada > 0 ? '<span class="badge-in">+' + entrada + '</span>' : '<span class="text-muted small">0</span>',
                            salida: salida,
                            salidaHtml: salida > 0 ? '<span class="badge-out">-' + salida + '</span>' : '<span class="text-muted small">0</span>',
                            valorStock: stock * costo,
                            valorStockHtml: '<strong class="font-cairo text-success">Gs. ' + self.formatGs(stock * costo) + '</strong>',
                            acciones: '<button type="button" class="btn btn-outline-info btn-sm py-0 px-2" onclick="app.verFichaArticulo(' + a.ARTICULOS_cod + ')" title="Ver ficha">' +
                                      '<i class="fa fa-info-circle"></i></button>',
                            raw: a
                        });
                    }

                    self.filtrarFilas();
                })
                .catch(function (error) {
                    self.requestSend = false;
                    console.error('Error al cargar inventario:', error);
                });
            },
            filtrarFilas: function () {
                var self = this;
                if (self.filtroStock === 'con_stock') {
                    self.filasFiltradas = self.rows.filter(function (r) { return r.stock > 0; });
                } else if (self.filtroStock === 'critico') {
                    self.filasFiltradas = self.rows.filter(function (r) { return r.stock > 0 && r.stock <= 5; });
                } else if (self.filtroStock === 'sin_stock') {
                    self.filasFiltradas = self.rows.filter(function (r) { return r.stock <= 0; });
                } else {
                    self.filasFiltradas = self.rows;
                }
            },
            verFichaArticulo: function (id) {
                var art = this.articulos.find(function (x) { return x.ARTICULOS_cod == id; });
                if (!art) return;
                this.articuloSeleccionado = art;
                $('#modalArticuloDetalle').modal('show');
            },
            exportar: function (tipo) {
                if (this.articulos.length < 1) {
                    Swal.fire('Atención', 'No hay artículos en la lista para exportar.', 'info');
                    return false;
                }
                var params = {
                    page: 0,
                    buscar: this.txtbuscar || '',
                    criterio: 0,
                    seccion: this.seccion || 0,
                    col: 'producto_nombre',
                    ord: 'ASC',
                    suc: this.idSucursal || ''
                };
                var u = new URLSearchParams(params).toString();

                if (tipo === "stock") {
                    window.open('{{ url('excel/articulos') }}?' + u);
                } else if (tipo === "costo") {
                    window.open('{{ url('excel/articulos_costo') }}?' + u);
                } else if (tipo === "precios") {
                    window.open('{{ url('excel/articulosprecios') }}?' + u);
                }
            }
        },
        computed: {
            totalArticulos: function () {
                return this.articulos.length;
            },
            totalStockFisico: function () {
                var total = 0;
                for (var i = 0; i < this.articulos.length; i++) {
                    total += Number(this.articulos[i].cantidad) || 0;
                }
                return total;
            },
            valorTotalCosto: function () {
                var total = 0;
                for (var i = 0; i < this.articulos.length; i++) {
                    var cant = Number(this.articulos[i].cantidad) || 0;
                    var costo = Number(this.articulos[i].costo) || 0;
                    if (cant > 0) {
                        total += (cant * costo);
                    }
                }
                return total;
            },
            valorTotalVenta: function () {
                var total = 0;
                for (var i = 0; i < this.articulos.length; i++) {
                    var cant = Number(this.articulos[i].cantidad) || 0;
                    var p1 = Number(this.articulos[i].pre_venta1) || 0;
                    if (cant > 0) {
                        total += (cant * p1);
                    }
                }
                return total;
            },
            articulosSinStock: function () {
                var count = 0;
                for (var i = 0; i < this.articulos.length; i++) {
                    if ((Number(this.articulos[i].cantidad) || 0) <= 0) {
                        count++;
                    }
                }
                return count;
            },
            articulosCriticos: function () {
                var count = 0;
                for (var i = 0; i < this.articulos.length; i++) {
                    var cant = Number(this.articulos[i].cantidad) || 0;
                    if (cant > 0 && cant <= 5) {
                        count++;
                    }
                }
                return count;
            }
        },
        mounted: function () {
            this.aplicarPreset('mes');
        }
    });

    activarMenu('m_informe', 'm_istock');
</script>
@endsection
