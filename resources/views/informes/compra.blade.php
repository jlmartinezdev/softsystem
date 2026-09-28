@extends('layouts.app')
@section('title', 'Informe de Compras')

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
    .kpi-icon-contado { background: #ecfdf5; color: #059669; }
    .kpi-icon-credito { background: #fffbeb; color: #d97706; }

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
    .badge-contado {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
        font-weight: 700;
        font-size: 0.78rem;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    body.dark-mode .badge-contado {
        background: #064e3b;
        color: #a7f3d0;
        border-color: #047857;
    }
    .badge-credito {
        background: #fffbeb;
        color: #92400e;
        border: 1px solid #fde68a;
        font-weight: 700;
        font-size: 0.78rem;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    body.dark-mode .badge-credito {
        background: #451a03;
        color: #fde68a;
        border-color: #78350f;
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
                <i class="fa fa-truck mr-1 text-success"></i> Informe de Compras
            </h1>
            <p class="dash-header-subtitle">
                Auditoría, control de gastos con proveedores y evolución del stock
            </p>
        </div>
        <div class="dash-header-badges">
            <span class="badge badge-light border p-2">
                <i class="fa fa-calendar-alt text-muted mr-1"></i> @{{ fecha.desde }} al @{{ fecha.hasta }}
            </span>
            <button class="btn btn-pos-secondary btn-sm" @click="getCompra" :disabled="requestSend" title="Actualizar datos">
                <i class="fa fa-sync-alt" :class="{ 'fa-spin': requestSend }"></i>
                <span class="d-none d-sm-inline ml-1">Actualizar</span>
            </button>
        </div>
    </div>

    <!-- KPI Cards Superiores -->
    <div class="row">
        <!-- KPI 1: Monto Total -->
        <div class="col-12 col-sm-6 col-xl-3 mb-3">
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-label">Total Compras</span>
                    <div class="kpi-icon kpi-icon-total">
                        <i class="fa fa-coins"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo text-success">
                    Gs. @{{ formatGs(totalGuaranies) }}
                </div>
                <div class="kpi-footer">
                    <i class="fa fa-receipt text-muted"></i>
                    <span>@{{ totalCompra }} factura(s) registrada(s)</span>
                </div>
            </div>
        </div>

        <!-- KPI 2: Operaciones / Facturas -->
        <div class="col-12 col-sm-6 col-xl-3 mb-3">
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-label">Facturas Cargadas</span>
                    <div class="kpi-icon kpi-icon-count">
                        <i class="fa fa-file-invoice"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo">
                    @{{ totalCompra }}
                </div>
                <div class="kpi-footer">
                    <i class="fa fa-calculator text-muted"></i>
                    <span>Promedio: Gs. @{{ formatGs(promedioCompra) }}</span>
                </div>
            </div>
        </div>

        <!-- KPI 3: Contado -->
        <div class="col-12 col-sm-6 col-xl-3 mb-3">
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-label">Compras Contado</span>
                    <div class="kpi-icon kpi-icon-contado">
                        <i class="fa fa-money-bill-wave"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo">
                    Gs. @{{ formatGs(totalContado) }}
                </div>
                <div class="kpi-footer">
                    <span class="badge badge-contado">@{{ porcentajeContado }}%</span>
                    <span>del importe total</span>
                </div>
            </div>
        </div>

        <!-- KPI 4: Crédito -->
        <div class="col-12 col-sm-6 col-xl-3 mb-3">
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-label">Compras a Crédito</span>
                    <div class="kpi-icon kpi-icon-credito">
                        <i class="fa fa-calendar-check"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo text-warning">
                    Gs. @{{ formatGs(totalCredito) }}
                </div>
                <div class="kpi-footer">
                    <span class="badge badge-credito">@{{ porcentajeCredito }}%</span>
                    <span>a pagar con proveedores</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de Filtros Moderno -->
    <div class="filter-card">
        <div class="filter-header">
            <span class="filter-header-title font-cairo">
                <i class="fa fa-filter text-success"></i> Filtros y Períodos
            </span>
            <span class="small text-muted font-weight-bold" v-if="compras.length">
                @{{ compras.length }} registro(s) encontrado(s)
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
                    <select class="form-control form-control-sm" v-model="idSucursal" @change="getCompra">
                        <option value="0">Todas las sucursales</option>
                        @foreach ($sucursales as $s)
                            <option value="{{ $s['suc_cod'] }}">{{ $s['suc_desc'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-3 mb-3 mb-md-0">
                    <label class="filter-label"><i class="fa fa-building mr-1"></i> Proveedor / RUC / Factura</label>
                    <div class="input-group input-group-sm">
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            v-model.trim="txtproveedor"
                            placeholder="Nombre, RUC o N° Factura..."
                            @keyup.enter="getCompra"
                        >
                        <div class="input-group-append" v-if="txtproveedor">
                            <button class="btn btn-outline-secondary" type="button" @click="txtproveedor = ''; getCompra();" title="Limpiar">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-3 d-flex align-items-center gap-2">
                    <button type="button" class="btn-pos-primary w-100 mr-2" @click="getCompra" :disabled="requestSend">
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
                    Detalle de Facturas de Compra
                </strong>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge badge-light border p-2">
                    Total período: <strong class="text-success font-cairo ml-1">Gs. @{{ formatGs(totalGuaranies) }}</strong>
                </span>
            </div>
        </div>
        <div class="table-card-body">
            <vue-good-table
                :columns="columns"
                :rows="rows"
                :search-options="{ enabled: true, placeholder: 'Filtrar en la tabla por proveedor, factura o sucursal...' }"
                :pagination-options="{ enabled: true, perPage: 15, perPageDropdown: [10, 15, 25, 50, 100], nextLabel: 'Siguiente', prevLabel: 'Anterior', rowsPerPageLabel: 'Filas por página', ofLabel: 'de', allLabel: 'Todos' }"
                style-class="vgt-table striped condensed"
            >
                <div slot="emptystate" class="text-center py-5">
                    <i class="fa fa-shopping-basket fa-3x text-muted mb-3" style="opacity: 0.35;"></i>
                    <h6 class="font-weight-bold font-cairo">No se encontraron compras</h6>
                    <p class="text-muted small mb-3">
                        Probá seleccionando otro rango de fechas o restablecé los filtros de búsqueda.
                    </p>
                    <button type="button" class="btn btn-outline-primary btn-sm" @click="limpiarFiltros">
                        <i class="fa fa-undo mr-1"></i> Ver compras de este mes
                    </button>
                </div>
            </vue-good-table>
        </div>
    </div>

    <!-- Modal Detalle de Compra Moderno -->
    <div class="modal fade" id="frmdetalle" tabindex="-1" role="dialog" aria-labelledby="frmdetalleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content modal-moderno shadow-lg border-0">
                <!-- Header -->
                <div class="modal-header d-flex align-items-center justify-content-between p-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="modal-header-icon mr-2.5">
                            <i class="fa fa-file-invoice text-success"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-weight-bold font-cairo mb-0" id="frmdetalleLabel">
                                Compra #@{{ compra.compra_cod }}
                            </h5>
                            <small class="text-muted">
                                Registrada el @{{ compra.compra_fecha }} · Sucursal @{{ compra.suc_desc }}
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
                                <div class="detail-card-label">Proveedor</div>
                                <div class="font-weight-bold font-cairo text-truncate" :title="compra.proveedor_nombre">
                                    @{{ compra.proveedor_nombre }}
                                </div>
                                <small class="text-muted d-block" v-if="compra.proveedor_ruc">
                                    <i class="fa fa-id-card mr-1"></i>RUC: @{{ compra.proveedor_ruc }}
                                </small>
                                <small class="text-muted d-block text-truncate" v-if="compra.proveedor_direc" :title="compra.proveedor_direc">
                                    <i class="fa fa-map-marker-alt mr-1"></i>@{{ compra.proveedor_direc }}
                                </small>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 mb-2 mb-md-0">
                            <div class="detail-card">
                                <div class="detail-card-label">Comprobante & Factura</div>
                                <div class="font-weight-bold font-cairo">
                                    <i class="fa fa-file-alt text-muted mr-1"></i>Factura: @{{ compra.compra_factura || 'Sin factura' }}
                                </div>
                                <div class="mt-1">
                                    <span class="badge" :class="compra.compra_tipo_factura == 1 || compra.compra_tipo_factura == '1' ? 'badge-contado' : 'badge-credito'">
                                        <i class="fa" :class="compra.compra_tipo_factura == 1 || compra.compra_tipo_factura == '1' ? 'fa-check-circle' : 'fa-clock'"></i>
                                        @{{ compra.compra_tipo_factura == 1 || compra.compra_tipo_factura == '1' ? 'Contado' : 'Crédito' }}
                                    </span>
                                    <small class="text-muted ml-2">Sucursal: @{{ compra.suc_desc }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="detail-card">
                                <div class="detail-card-label">Total Liquidado</div>
                                <div class="font-cairo font-weight-bold text-success" style="font-size: 1.35rem; line-height: 1.1;">
                                    Gs. @{{ formatGs(compra.total) }}
                                </div>
                                <small class="text-muted d-block" v-if="compra.compra_descuento > 0">
                                    Descuento: Gs. @{{ formatGs(compra.compra_descuento) }}
                                </small>
                                <small class="text-muted d-block" v-else>
                                    Sin descuento aplicado
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Ítems / Artículos Comprados -->
                    <div class="table-responsive border rounded-lg">
                        <table class="table table-sm table-striped table-hover mb-0">
                            <thead class="bg-light-panel">
                                <tr>
                                    <th style="width: 140px;">Código</th>
                                    <th>Descripción del Artículo</th>
                                    <th class="text-center" style="width: 80px;">IVA</th>
                                    <th class="text-center" style="width: 90px;">Cantidad</th>
                                    <th class="text-right" style="width: 130px;">Costo Unit.</th>
                                    <th class="text-right" style="width: 140px;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(d, i) in detalleCompra" :key="i">
                                    <td>
                                        <span class="badge-code">@{{ d.producto_c_barra }}</span>
                                    </td>
                                    <td class="font-weight-bold">
                                        @{{ d.producto_nombre }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-light border">@{{ d.iva || '10%' }}</span>
                                    </td>
                                    <td class="text-center font-weight-bold">
                                        @{{ parseInt(d.compra_cantidad) }}
                                    </td>
                                    <td class="text-right">
                                        Gs. @{{ formatGs(d.compra_precio) }}
                                    </td>
                                    <td class="text-right font-cairo font-weight-bold text-success">
                                        Gs. @{{ formatGs(d.compra_cantidad * d.compra_precio) }}
                                    </td>
                                </tr>
                                <tr v-if="!detalleCompra || !detalleCompra.length">
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <div class="spinner-border spinner-border-sm mr-1"></div>
                                        Cargando artículos de la compra...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer d-flex align-items-center justify-content-between p-3 border-top bg-light-panel">
                    <div class="d-flex align-items-center">
                        <span class="text-muted mr-2 font-weight-bold">Total Factura:</span>
                        <strong class="font-cairo text-success" style="font-size: 1.25rem;">
                            Gs. @{{ formatGs(compra.total) }}
                        </strong>
                    </div>
                    <div>
                        <a
                            v-if="compra.compra_cod"
                            :href="'{{ url('pdf/boletacompra') }}/' + compra.compra_cod"
                            class="btn btn-outline-danger btn-sm font-weight-bold mr-2"
                            target="_blank"
                            title="Descargar comprobante en PDF"
                        >
                            <i class="fa fa-file-pdf mr-1"></i> Imprimir Comprobante
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
    <div class="font-cairo font-weight-bold" style="font-size: 1rem;">Cargando Informe de Compras...</div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    var app = new Vue({
        el: '#app',
        data: {
            fecha: { desde: '', hasta: '' },
            txtproveedor: '',
            compra: {},
            detalleCompra: [],
            compras: [],
            error: '',
            requestSend: false,
            idSucursal: 0,
            presetActivo: 'mes',
            columns: [
                { label: 'N°', field: 'codigoHtml', html: true, width: '90px' },
                { label: 'Fecha y Hora', field: 'fechaHtml', html: true, width: '150px' },
                { label: 'Proveedor', field: 'proveedorHtml', html: true },
                { label: 'N° Factura', field: 'facturaHtml', html: true, width: '160px' },
                { label: 'Condición', field: 'tipoHtml', html: true, width: '110px' },
                { label: 'Sucursal', field: 'sucursal', width: '130px' },
                { label: 'Total (Gs.)', field: 'totalHtml', html: true, tdClass: 'text-right', width: '150px' },
                { label: 'Acciones', field: 'detalle', html: true, sortable: false, width: '130px', tdClass: 'text-center' }
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
                this.getCompra();
            },
            limpiarFiltros: function () {
                this.txtproveedor = '';
                this.idSucursal = 0;
                this.aplicarPreset('mes');
            },
            getCompra: function () {
                var self = this;
                self.requestSend = true;
                axios.get('{{ url('infcompra/fecha') }}', {
                    params: {
                        alld: self.fecha.desde,
                        allh: self.fecha.hasta,
                        alls: self.idSucursal,
                        proveedor: self.txtproveedor
                    }
                })
                .then(function (response) {
                    self.requestSend = false;
                    self.rows = [];
                    self.compras = response.data || [];
                    for (var i = 0; i < self.compras.length; i++) {
                        var c = self.compras[i];
                        var esContado = c.compra_tipo_factura == 1 || c.compra_tipo_factura == '1';
                        self.rows.push({
                            codigo: c.compra_cod,
                            codigoHtml: '<span class="badge-code">#' + c.compra_cod + '</span>',
                            fecha: c.compra_fecha,
                            fechaHtml: '<span class="small font-weight-bold text-nowrap"><i class="fa fa-calendar-alt text-muted mr-1"></i>' + (c.compra_fecha || '—') + '</span>',
                            proveedor: c.proveedor_nombre,
                            proveedorHtml: '<div><strong class="font-cairo d-block">' + c.proveedor_nombre + '</strong>' +
                                           '<small class="text-muted"><i class="fa fa-id-card mr-1"></i>' + (c.proveedor_ruc || 'S/ RUC') + '</small></div>',
                            factura: c.compra_factura || '—',
                            facturaHtml: '<span class="badge badge-light border text-muted font-weight-bold">' + (c.compra_factura || '—') + '</span>',
                            tipoHtml: esContado
                                ? '<span class="badge badge-contado"><i class="fa fa-check-circle mr-1"></i>Contado</span>'
                                : '<span class="badge badge-credito"><i class="fa fa-clock mr-1"></i>Crédito</span>',
                            sucursal: c.suc_desc,
                            total: Number(c.total) || 0,
                            totalHtml: '<strong class="font-cairo text-success">Gs. ' + self.formatGs(c.total) + '</strong>',
                            detalle: '<div class="btn-group btn-group-sm" role="group">' +
                                '<button type="button" class="btn btn-outline-primary btn-sm" onclick="app.showDetalle(' + c.compra_cod + ')" title="Ver detalle de artículos">' +
                                '<i class="fa fa-eye"></i></button>' +
                                '<a href="{{ url('pdf/boletacompra') }}/' + c.compra_cod + '" class="btn btn-outline-danger btn-sm" target="_blank" title="Imprimir PDF">' +
                                '<i class="fa fa-file-pdf"></i></a>' +
                                '</div>'
                        });
                    }
                })
                .catch(function (e) {
                    self.requestSend = false;
                    self.error = e.message;
                    console.error('Error al obtener compras:', e);
                });
            },
            showDetalle: function (id) {
                var idx = this.compras.findIndex(function (x) { return x.compra_cod == id; });
                if (idx < 0) return;
                this.compra = this.compras[idx];
                this.detalleCompra = [];
                $('#frmdetalle').modal('show');
                this.getDetalle();
            },
            getDetalle: function () {
                var self = this;
                axios.get('{{ url('infcompra/detalle') }}/' + this.compra.compra_cod)
                    .then(function (response) {
                        self.detalleCompra = response.data || [];
                    })
                    .catch(function (error) {
                        console.error('Error al obtener detalle:', error);
                    });
            }
        },
        computed: {
            totalCompra: function () {
                return this.compras.length;
            },
            totalGuaranies: function () {
                var total = 0;
                for (var i = 0; i < this.compras.length; i++) {
                    total += parseFloat(this.compras[i].total || 0);
                }
                return total;
            },
            totalContado: function () {
                var total = 0;
                for (var i = 0; i < this.compras.length; i++) {
                    var c = this.compras[i];
                    if (c.compra_tipo_factura == 1 || c.compra_tipo_factura == '1') {
                        total += parseFloat(c.total || 0);
                    }
                }
                return total;
            },
            totalCredito: function () {
                var total = 0;
                for (var i = 0; i < this.compras.length; i++) {
                    var c = this.compras[i];
                    if (c.compra_tipo_factura != 1 && c.compra_tipo_factura != '1') {
                        total += parseFloat(c.total || 0);
                    }
                }
                return total;
            },
            porcentajeContado: function () {
                if (!this.totalGuaranies) return 0;
                return Math.round((this.totalContado / this.totalGuaranies) * 100);
            },
            porcentajeCredito: function () {
                if (!this.totalGuaranies) return 0;
                return Math.round((this.totalCredito / this.totalGuaranies) * 100);
            },
            promedioCompra: function () {
                if (!this.totalCompra) return 0;
                return Math.round(this.totalGuaranies / this.totalCompra);
            }
        },
        mounted() {
            this.aplicarPreset('mes');
        }
    });

    activarMenu('m_informe', 'm_icompra');
</script>
@endsection
