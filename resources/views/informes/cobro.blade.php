@extends('layouts.app')
@section('title', 'Informe de Cobros')

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
    .kpi-icon-total { background: var(--dash-primary-light); color: var(--dash-primary); }
    .kpi-icon-count { background: #eff6ff; color: #2563eb; }
    .kpi-icon-average { background: #fdf4ff; color: #a855f7; }
    .kpi-icon-max { background: #fffbeb; color: #d97706; }

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
    .badge-recibo {
        font-family: monospace;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        padding: 0.25rem 0.55rem;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.82rem;
    }
    body.dark-mode .badge-recibo {
        background: #064e3b;
        color: #a7f3d0;
        border-color: #047857;
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
                <i class="fa fa-hand-holding-usd text-success mr-1"></i> Informe de Cobros
            </h1>
            <p class="dash-header-subtitle">
                Auditoría y control de cuotas cobradas, emisión de recibos y recuperaciones de cuentas
            </p>
        </div>
        <div class="dash-header-badges">
            <span class="badge badge-light border p-2">
                <i class="fa fa-calendar-alt text-muted mr-1"></i> @{{ fecha.desde }} al @{{ fecha.hasta }}
            </span>
            <button class="btn btn-pos-secondary btn-sm" @click="getCobro" :disabled="requestSend" title="Actualizar datos">
                <i class="fa fa-sync-alt" :class="{ 'fa-spin': requestSend }"></i>
                <span class="d-none d-sm-inline ml-1">Actualizar</span>
            </button>
        </div>
    </div>

    <!-- KPI Cards Superiores -->
    <div class="row">
        <!-- KPI 1: Monto Total Cobrado -->
        <div class="col-12 col-sm-6 col-xl-3 mb-3">
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-label">Total Cobrado</span>
                    <div class="kpi-icon kpi-icon-total">
                        <i class="fa fa-money-bill-wave"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo text-success">
                    Gs. @{{ formatGs(totalGuaranies) }}
                </div>
                <div class="kpi-footer">
                    <i class="fa fa-receipt text-muted"></i>
                    <span>@{{ totalCobro }} cobranza(s) en el período</span>
                </div>
            </div>
        </div>

        <!-- KPI 2: Total Cobranzas -->
        <div class="col-12 col-sm-6 col-xl-3 mb-3">
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-label">Recibos Emitidos</span>
                    <div class="kpi-icon kpi-icon-count">
                        <i class="fa fa-file-invoice"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo">
                    @{{ totalCobro }}
                </div>
                <div class="kpi-footer">
                    <i class="fa fa-check-circle text-success"></i>
                    <span>Comprobantes de pago registrados</span>
                </div>
            </div>
        </div>

        <!-- KPI 3: Promedio por Cobro -->
        <div class="col-12 col-sm-6 col-xl-3 mb-3">
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-label">Cobro Promedio</span>
                    <div class="kpi-icon kpi-icon-average">
                        <i class="fa fa-calculator"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo">
                    Gs. @{{ formatGs(promedioCobro) }}
                </div>
                <div class="kpi-footer">
                    <i class="fa fa-chart-line text-muted"></i>
                    <span>Promedio por recibo emitido</span>
                </div>
            </div>
        </div>

        <!-- KPI 4: Mayor Cobro -->
        <div class="col-12 col-sm-6 col-xl-3 mb-3">
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-label">Mayor Cobro</span>
                    <div class="kpi-icon kpi-icon-max">
                        <i class="fa fa-trophy"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo text-warning">
                    Gs. @{{ formatGs(mayorCobro) }}
                </div>
                <div class="kpi-footer">
                    <i class="fa fa-arrow-up text-warning"></i>
                    <span>Máximo cobro en el rango</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de Filtros Moderno -->
    <div class="filter-card">
        <div class="filter-header">
            <span class="filter-header-title font-cairo">
                <i class="fa fa-filter text-success"></i> Filtros de Período y Cliente
            </span>
            <span class="small text-muted font-weight-bold" v-if="cobros.length">
                @{{ cobros.length }} recibo(s) encontrado(s)
            </span>
        </div>
        <div class="filter-body">
            <!-- Píldoras de Rango Rápido -->
            <div class="preset-pills">
                <button type="button" class="preset-pill" :class="{ active: presetActivo === 'hoy' }" @click="aplicarPreset('hoy')">
                    Hoy
                </button>
                <button type="button" class="preset-pill" :class="{ active: presetActivo === 'ayer' }" @click="aplicarPreset('ayer')">
                    Ayer
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
                    <label class="filter-label"><i class="fa fa-calendar-alt mr-1"></i> Desde</label>
                    <input type="date" class="form-control form-control-sm" v-model="fecha.desde" @change="presetActivo = 'custom'">
                </div>

                <div class="col-12 col-sm-6 col-md-2 mb-3 mb-md-0">
                    <label class="filter-label"><i class="fa fa-calendar-alt mr-1"></i> Hasta</label>
                    <input type="date" class="form-control form-control-sm" v-model="fecha.hasta" @change="presetActivo = 'custom'">
                </div>

                <div class="col-12 col-sm-6 col-md-2 mb-3 mb-md-0">
                    <label class="filter-label"><i class="fa fa-store mr-1"></i> Sucursal</label>
                    <select class="form-control form-control-sm" v-model="idSucursal" @change="getCobro">
                        <option value="0">Todas las sucursales</option>
                        @foreach ($sucursales as $s)
                            <option value="{{ $s['suc_cod'] }}">{{ $s['suc_desc'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-3 mb-3 mb-md-0">
                    <label class="filter-label"><i class="fa fa-user mr-1"></i> Cliente / C.I. / N° Recibo</label>
                    <div class="input-group input-group-sm">
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            v-model.trim="txtbuscar"
                            placeholder="Nombre, C.I., Recibo o Venta..."
                            @keyup.enter="getCobro"
                        >
                        <div class="input-group-append" v-if="txtbuscar">
                            <button class="btn btn-outline-secondary" type="button" @click="txtbuscar = ''; getCobro();" title="Limpiar">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3 d-flex align-items-center gap-2">
                    <button type="button" class="btn-pos-primary w-100 mr-2" @click="getCobro" :disabled="requestSend">
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
                    Detalle de Recibos y Cobranzas
                </strong>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge badge-light border p-2">
                    Total recaudado: <strong class="text-success font-cairo ml-1">Gs. @{{ formatGs(totalGuaranies) }}</strong>
                </span>
            </div>
        </div>
        <div class="table-card-body">
            <vue-good-table
                :columns="columns"
                :rows="rows"
                :search-options="{ enabled: true, placeholder: 'Filtrar en la tabla por cliente, recibo, venta o sucursal...' }"
                :pagination-options="{ enabled: true, perPage: 15, perPageDropdown: [10, 15, 25, 50, 100], nextLabel: 'Siguiente', prevLabel: 'Anterior', rowsPerPageLabel: 'Filas por página', ofLabel: 'de', allLabel: 'Todos' }"
                style-class="vgt-table striped condensed"
            >
                <div slot="emptystate" class="text-center py-5">
                    <i class="fa fa-receipt fa-3x text-muted mb-3" style="opacity: 0.35;"></i>
                    <h6 class="font-weight-bold font-cairo">No se encontraron cobranzas</h6>
                    <p class="text-muted small mb-3">
                        Probá seleccionando otro rango de fechas o restablecé los filtros de búsqueda.
                    </p>
                    <button type="button" class="btn btn-outline-primary btn-sm" @click="limpiarFiltros">
                        <i class="fa fa-undo mr-1"></i> Ver cobros de este mes
                    </button>
                </div>
            </vue-good-table>
        </div>
    </div>

    <!-- Modal Detalle de Cobro Moderno -->
    <div class="modal fade" id="frmdetalle" tabindex="-1" role="dialog" aria-labelledby="frmdetalleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content modal-moderno shadow-lg border-0">
                <!-- Header -->
                <div class="modal-header d-flex align-items-center justify-content-between p-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="modal-header-icon mr-2.5">
                            <i class="fa fa-hand-holding-usd text-success"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-weight-bold font-cairo mb-0" id="frmdetalleLabel">
                                Cobro #@{{ cobro.cc_numero }}
                            </h5>
                            <small class="text-muted">
                                Recibo: @{{ numeroRecibo(cobro.recibon1, cobro.recibon2, cobro.nro_recibo) }} · Registrado el @{{ formatFecha(cobro.cob_fecha) }}
                            </small>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Body -->
                <div class="modal-body p-3">
                    <!-- Tarjetas de Información -->
                    <div class="row mb-3">
                        <div class="col-12 col-md-4 mb-2 mb-md-0">
                            <div class="detail-card">
                                <div class="detail-card-label">Cliente</div>
                                <div class="font-weight-bold font-cairo text-truncate" :title="cobro.cliente_nombre">
                                    @{{ cobro.cliente_nombre }}
                                </div>
                                <small class="text-muted d-block" v-if="cobro.cliente_ci">
                                    <i class="fa fa-id-card mr-1"></i>C.I. / RUC: @{{ cobro.cliente_ci }}
                                </small>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 mb-2 mb-md-0">
                            <div class="detail-card">
                                <div class="detail-card-label">Comprobante Oficial</div>
                                <div class="font-weight-bold font-cairo text-truncate">
                                    <i class="fa fa-file-invoice text-muted mr-1"></i>Recibo: @{{ numeroRecibo(cobro.recibon1, cobro.recibon2, cobro.nro_recibo) }}
                                </div>
                                <small class="text-muted d-block" v-if="cobro.nro_fact_ventas">
                                    <i class="fa fa-shopping-cart mr-1"></i>Venta Asoc.: #@{{ cobro.nro_fact_ventas }}
                                </small>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="detail-card">
                                <div class="detail-card-label">Importe Cobrado</div>
                                <div class="font-cairo font-weight-bold text-success" style="font-size: 1.35rem; line-height: 1.1;">
                                    Gs. @{{ formatGs(cobro.cob_importe) }}
                                </div>
                                <small class="text-muted d-block">
                                    Fecha: @{{ formatFecha(cobro.cob_fecha) }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Sección 1: Cuotas cobradas en este recibo -->
                    <div class="d-flex align-items-center justify-content-between mb-2 mt-3">
                        <span class="font-weight-bold font-cairo text-uppercase small text-muted">
                            <i class="fa fa-check-double text-success mr-1"></i> Cuotas liquidadas en este recibo
                        </span>
                        <span class="badge badge-light border" v-if="detalleCobro && detalleCobro.length">
                            @{{ detalleCobro.length }} cuota(s)
                        </span>
                    </div>

                    <div class="table-responsive border rounded-lg mb-3">
                        <table class="table table-sm table-striped table-hover mb-0">
                            <thead class="bg-light-panel">
                                <tr>
                                    <th style="width: 140px;">N° Venta</th>
                                    <th class="text-center" style="width: 100px;">N° Cuota</th>
                                    <th class="text-right" style="width: 150px;">Monto Cuota</th>
                                    <th class="text-right" style="width: 150px;">Monto Cobrado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(d, i) in detalleCobro" :key="i">
                                    <td>
                                        <span class="badge-code">#@{{ d.nro_fact_ventas }}</span>
                                    </td>
                                    <td class="text-center font-weight-bold">
                                        Cuota @{{ d.nro_cuotas }}
                                    </td>
                                    <td class="text-right">
                                        Gs. @{{ formatGs(d.importe) }}
                                    </td>
                                    <td class="text-right font-cairo font-weight-bold text-success">
                                        Gs. @{{ formatGs(d.cobrado) }}
                                    </td>
                                </tr>
                                <tr v-if="!detalleCobro || !detalleCobro.length">
                                    <td colspan="4" class="text-center text-muted py-3">
                                        Sin detalle de cuotas
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Sección 2: Estado general de la cuenta / cuotas -->
                    <div v-if="cuotas && cuotas.length">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="font-weight-bold font-cairo text-uppercase small text-muted">
                                <i class="fa fa-clock text-warning mr-1"></i> Estado general de cuotas de la venta
                            </span>
                            <div class="small">
                                <span class="mr-3 text-muted">Cobrado acumulado: <strong class="text-success font-cairo">Gs. @{{ formatGs(Cuenta.montoCobrado) }}</strong></span>
                                <span class="text-muted">Saldo restante: <strong class="text-danger font-cairo">Gs. @{{ formatGs(Cuenta.saldo) }}</strong></span>
                            </div>
                        </div>

                        <div class="table-responsive border rounded-lg">
                            <table class="table table-sm table-striped table-hover mb-0">
                                <thead class="bg-light-panel">
                                    <tr>
                                        <th class="text-center" style="width: 90px;">N° Cuota</th>
                                        <th>Vencimiento</th>
                                        <th class="text-right">Monto Cuota</th>
                                        <th class="text-right">Cobrado</th>
                                        <th class="text-right">Saldo</th>
                                        <th class="text-center" style="width: 100px;">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(c, idx) in cuotas" :key="'cuota-' + idx">
                                        <td class="text-center font-weight-bold">
                                            Cuota @{{ c.nro_cuotas }}
                                        </td>
                                        <td>
                                            <span class="text-nowrap"><i class="fa fa-calendar-alt text-muted mr-1"></i>@{{ formatFecha(c.fecha_venc) }}</span>
                                        </td>
                                        <td class="text-right font-weight-bold">
                                            Gs. @{{ formatGs(c.monto_cuota) }}
                                        </td>
                                        <td class="text-right text-success font-weight-bold">
                                            Gs. @{{ formatGs(c.monto_cobrado) }}
                                        </td>
                                        <td class="text-right font-cairo font-weight-bold" :class="Number(c.monto_saldo) > 0 ? 'text-danger' : 'text-muted'">
                                            Gs. @{{ formatGs(c.monto_saldo) }}
                                        </td>
                                        <td class="text-center">
                                            <span v-if="Number(c.monto_saldo) <= 0" class="badge badge-success">Pagado</span>
                                            <span v-else class="badge badge-warning">Pendiente</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex align-items-center justify-content-between p-3 border-top bg-light-panel">
                    <div class="d-flex align-items-center">
                        <span class="text-muted mr-2 font-weight-bold">Total Recibo:</span>
                        <strong class="font-cairo text-success" style="font-size: 1.25rem;">
                            Gs. @{{ formatGs(cobro.cob_importe) }}
                        </strong>
                    </div>
                    <div>
                        <a
                            v-if="cobro.cc_numero"
                            :href="'{{ url('documento/recibocobro') }}/' + cobro.cc_numero"
                            class="btn btn-outline-primary btn-sm font-weight-bold mr-2"
                            target="_blank"
                            title="Descargar o imprimir recibo oficial"
                        >
                            <i class="fa fa-print mr-1"></i> Imprimir Recibo
                        </a>
                        <button type="button" class="btn btn-secondary btn-sm px-3" data-dismiss="modal">
                            <i class="fa fa-times mr-1"></i> Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Skeleton Loading Overlay -->
<div class="app-loading-skeleton">
    <div class="v-cloak-spinner"></div>
    <div class="font-cairo font-weight-bold" style="font-size: 1rem;">Cargando Informe de Cobros...</div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    var app = new Vue({
        el: '#app',
        data: {
            fecha: { desde: '', hasta: '' },
            txtbuscar: '',
            cobro: {},
            detalleCobro: [],
            cuotas: [],
            cobros: [],
            error: '',
            requestSend: false,
            idSucursal: 0,
            presetActivo: 'mes',
            Cuenta: { cantitad: 0, montoCuota: 0, saldo: 0, cobrado: 0, montoCobrado: 0 },
            columns: [
                { label: 'N° Cobro', field: 'codigoHtml', html: true, width: '100px' },
                { label: 'Fecha', field: 'fechaHtml', html: true, width: '120px' },
                { label: 'N° Recibo', field: 'reciboHtml', html: true, width: '160px' },
                { label: 'Cliente', field: 'clienteHtml', html: true },
                { label: 'N° Venta', field: 'ventaHtml', html: true, width: '110px' },
                { label: 'Sucursal', field: 'sucursal', width: '130px' },
                { label: 'Importe (Gs.)', field: 'importeHtml', html: true, tdClass: 'text-right', width: '150px' },
                { label: 'Acciones', field: 'acciones', html: true, sortable: false, width: '120px', tdClass: 'text-center' }
            ],
            rows: []
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
            formatFecha: function (fecha) {
                if (!fecha) return '—';
                var parts = String(fecha).split(' ')[0].split('-');
                if (parts.length === 3) {
                    return parts[2] + '/' + parts[1] + '/' + parts[0];
                }
                return fecha;
            },
            numeroRecibo: function (n1, n2, n3) {
                if (!n3) return 'S/ Recibo';
                var p1 = String(n1 || '1').padStart(3, '0');
                var p2 = String(n2 || '1').padStart(3, '0');
                var p3 = String(n3).padStart(7, '0');
                return p1 + '-' + p2 + '-' + p3;
            },
            aplicarPreset: function (preset) {
                this.presetActivo = preset;
                var hoy = new Date();
                var desde = new Date();
                var hasta = new Date();

                switch (preset) {
                    case 'hoy':
                        break;
                    case 'ayer':
                        desde.setDate(desde.getDate() - 1);
                        hasta.setDate(hasta.getDate() - 1);
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
                this.getCobro();
            },
            limpiarFiltros: function () {
                this.txtbuscar = '';
                this.idSucursal = 0;
                this.aplicarPreset('mes');
            },
            getCobro: function () {
                var self = this;
                self.requestSend = true;
                axios.get('{{ url('infcobro/fecha') }}', {
                    params: {
                        alld: self.fecha.desde,
                        allh: self.fecha.hasta,
                        alls: self.idSucursal,
                        search: self.txtbuscar
                    }
                })
                .then(function (response) {
                    self.requestSend = false;
                    self.rows = [];
                    self.cobros = response.data || [];
                    for (var i = 0; i < self.cobros.length; i++) {
                        var c = self.cobros[i];
                        var reciboStr = self.numeroRecibo(c.recibon1, c.recibon2, c.nro_recibo);
                        self.rows.push({
                            codigo: c.cc_numero,
                            codigoHtml: '<span class="badge-code">#' + c.cc_numero + '</span>',
                            fecha: c.cob_fecha,
                            fechaHtml: '<span class="small font-weight-bold text-nowrap"><i class="fa fa-calendar-alt text-muted mr-1"></i>' + self.formatFecha(c.cob_fecha) + '</span>',
                            recibo: reciboStr,
                            reciboHtml: '<span class="badge-recibo"><i class="fa fa-file-invoice mr-1 text-muted"></i>' + reciboStr + '</span>',
                            cliente: c.cliente_nombre,
                            clienteHtml: '<div><strong class="font-cairo d-block">' + (c.cliente_nombre || 'Sin cliente') + '</strong>' +
                                         '<small class="text-muted"><i class="fa fa-id-card mr-1"></i>' + (c.cliente_ci || 'S/ C.I.') + '</small></div>',
                            venta: c.nro_fact_ventas || '—',
                            ventaHtml: c.nro_fact_ventas ? '<span class="badge badge-light border">#' + c.nro_fact_ventas + '</span>' : '—',
                            sucursal: c.suc_desc || '—',
                            importe: Number(c.cob_importe) || 0,
                            importeHtml: '<strong class="font-cairo text-success">Gs. ' + self.formatGs(c.cob_importe) + '</strong>',
                            acciones: '<div class="btn-group btn-group-sm" role="group">' +
                                '<button type="button" class="btn btn-outline-primary btn-sm" onclick="app.showDetalleById(' + c.cc_numero + ')" title="Ver detalle">' +
                                '<i class="fa fa-eye"></i></button>' +
                                '<a href="{{ url('documento/recibocobro') }}/' + c.cc_numero + '" class="btn btn-outline-success btn-sm" target="_blank" title="Imprimir recibo">' +
                                '<i class="fa fa-print"></i></a>' +
                                '</div>'
                        });
                    }
                })
                .catch(function (e) {
                    self.requestSend = false;
                    self.error = e.message;
                    console.error('Error al obtener cobros:', e);
                });
            },
            showDetalleById: function (cc_numero) {
                var idx = this.cobros.findIndex(function (x) { return x.cc_numero == cc_numero; });
                if (idx < 0) return;
                this.showDetalle(this.cobros[idx]);
            },
            showDetalle: function (cobro) {
                this.cobro = cobro;
                this.detalleCobro = [];
                this.cuotas = [];
                this.Cuenta = { cantitad: 0, montoCuota: 0, saldo: 0, cobrado: 0, montoCobrado: 0 };
                $('#frmdetalle').modal('show');
                this.getDetalle();
            },
            getDetalle: function () {
                var self = this;
                axios.get('{{ url('infcobro/detalle') }}/' + self.cobro.cc_numero)
                    .then(function (response) {
                        self.detalleCobro = response.data || [];
                    })
                    .catch(function (error) {
                        console.error('Error al obtener detalle:', error);
                    });
                this.getCuotas();
            },
            getCuotas: function () {
                var self = this;
                if (!self.cobro.nro_fact_ventas) return;
                axios.get('{{ url('cuotas') }}/' + self.cobro.nro_fact_ventas)
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
                    })
                    .catch(function (error) {
                        console.error('Error al obtener cuotas:', error);
                    });
            }
        },
        computed: {
            totalCobro: function () {
                return this.cobros.length;
            },
            totalGuaranies: function () {
                var total = 0;
                for (var i = 0; i < this.cobros.length; i++) {
                    total += parseFloat(this.cobros[i].cob_importe || 0);
                }
                return total;
            },
            promedioCobro: function () {
                if (!this.totalCobro) return 0;
                return Math.round(this.totalGuaranies / this.totalCobro);
            },
            mayorCobro: function () {
                var max = 0;
                for (var i = 0; i < this.cobros.length; i++) {
                    var val = parseFloat(this.cobros[i].cob_importe || 0);
                    if (val > max) max = val;
                }
                return max;
            }
        },
        mounted: function () {
            this.aplicarPreset('mes');
        }
    });

    activarMenu('m_informe', 'm_ictacobro');
</script>
@endsection