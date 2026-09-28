@extends('layouts.app')
@section('title', 'Gestión de Proveedores')

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
        --dash-panel-bg: #f8fafc;
        --dash-border: #e2e8f0;
        --dash-success: #10b981;
        --dash-info: #0284c7;
        --dash-warning: #f59e0b;
        --dash-danger: #ef4444;
    }

    body.dark-mode {
        --dash-primary: #10b981;
        --dash-primary-dark: #059669;
        --dash-primary-light: #064e3b;
        --dash-primary-border: #047857;
        --dash-accent: #f59e0b;
        --dash-text-main: #f1f5f9;
        --dash-text-muted: #94a3b8;
        --dash-card-bg: #1e293b;
        --dash-panel-bg: #0f172a;
        --dash-border: #334155;
        --dash-success: #34d399;
        --dash-info: #38bdf8;
        --dash-warning: #fbbf24;
        --dash-danger: #f87171;
    }

    #app {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
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
    .dash-header-left {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }
    .dash-header-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: var(--dash-primary-light);
        color: var(--dash-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        box-shadow: 0 2px 8px rgba(10, 77, 54, 0.12);
        flex-shrink: 0;
    }
    .dash-header-title {
        font-size: 1.55rem;
        font-weight: 700;
        color: var(--dash-primary);
        margin: 0;
        line-height: 1.2;
    }
    body.dark-mode .dash-header-title {
        color: #10b981;
    }
    .dash-header-subtitle {
        font-size: 0.88rem;
        color: var(--dash-text-muted);
        margin: 0.2rem 0 0;
    }

    /* Buttons */
    .btn-pos-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.55rem 1.35rem;
        background: var(--dash-primary);
        border: 1px solid var(--dash-primary);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.92rem;
        border-radius: 10px;
        transition: all 0.15s ease-in-out;
        text-decoration: none !important;
        box-shadow: 0 2px 6px rgba(10, 77, 54, 0.2);
        cursor: pointer;
    }
    .btn-pos-primary:hover:not(:disabled) {
        background: var(--dash-primary-dark);
        border-color: var(--dash-primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(10, 77, 54, 0.3);
    }
    .btn-pos-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.9rem;
        border-radius: 8px;
        border: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        color: var(--dash-text-main);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-pos-secondary:hover {
        border-color: var(--dash-primary);
        color: var(--dash-primary);
        background: var(--dash-primary-light);
    }

    /* KPI Summary Cards */
    .kpi-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 1rem;
        margin-bottom: 1.25rem;
    }
    .kpi-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        padding: 1rem 1.15rem;
        display: flex;
        align-items: center;
        gap: 0.9rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.06);
    }
    .kpi-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .kpi-icon-green  { background: #dcfce7; color: #166534; }
    .kpi-icon-blue   { background: #e0f2fe; color: #0284c7; }
    .kpi-icon-amber  { background: #fef3c7; color: #b45309; }
    .kpi-icon-purple { background: #f3e8ff; color: #7e22ce; }
    body.dark-mode .kpi-icon-green  { background: #064e3b; color: #a7f3d0; }
    body.dark-mode .kpi-icon-blue   { background: #0c4a6e; color: #bae6fd; }
    body.dark-mode .kpi-icon-amber  { background: #451a03; color: #fde68a; }
    body.dark-mode .kpi-icon-purple { background: #3b0764; color: #e9d5ff; }

    .kpi-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--dash-text-muted);
        font-weight: 700;
        margin-bottom: 0.15rem;
    }
    .kpi-val {
        font-size: 1.45rem;
        font-weight: 700;
        color: var(--dash-text-main);
        line-height: 1.1;
        margin: 0;
    }
    .kpi-sub {
        font-size: 0.77rem;
        color: var(--dash-text-muted);
        margin-top: 0.2rem;
    }

    /* Toolbar / Filters Card */
    .toolbar-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        padding: 0.85rem 1.15rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }
    .search-input-wrap {
        position: relative;
        flex: 1;
        min-width: 240px;
    }
    .search-input-wrap i.fa-search {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--dash-text-muted);
        font-size: 0.9rem;
    }
    .search-input-wrap input {
        width: 100%;
        padding: 0.5rem 2.2rem 0.5rem 2.4rem;
        border-radius: 10px;
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        font-size: 0.9rem;
        transition: all 0.15s ease;
    }
    .search-input-wrap input:focus {
        outline: none;
        border-color: var(--dash-primary);
        background: var(--dash-card-bg);
        box-shadow: 0 0 0 3px var(--dash-primary-light);
    }
    body.dark-mode .search-input-wrap input:focus {
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }
    .search-clear-btn {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: var(--dash-text-muted);
        cursor: pointer;
        padding: 2px 5px;
    }
    .search-clear-btn:hover {
        color: var(--dash-danger);
    }
    .filter-select {
        border-radius: 10px;
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        padding: 0.5rem 0.85rem;
        font-size: 0.88rem;
        font-weight: 600;
        min-width: 180px;
    }
    .filter-select:focus {
        outline: none;
        border-color: var(--dash-primary);
        background: var(--dash-card-bg);
    }

    /* Table Card */
    .card-table {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }
    .table-custom {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    .table-custom thead th {
        background: var(--dash-panel-bg);
        color: var(--dash-text-muted);
        font-size: 0.77rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--dash-border);
        border-top: none;
        padding: 0.85rem 1rem;
        white-space: nowrap;
    }
    .table-custom tbody tr {
        transition: background-color 0.15s ease;
    }
    .table-custom tbody tr:hover {
        background-color: var(--dash-primary-light) !important;
    }
    body.dark-mode .table-custom tbody tr:hover {
        background-color: rgba(16, 185, 129, 0.08) !important;
    }
    .table-custom tbody td {
        padding: 0.8rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--dash-border);
        color: var(--dash-text-main);
        font-size: 0.89rem;
    }

    /* Supplier Avatar */
    .supplier-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--dash-primary-light);
        color: var(--dash-primary);
        font-weight: 700;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 5px rgba(10, 77, 54, 0.1);
    }
    body.dark-mode .supplier-avatar {
        background: rgba(16, 185, 129, 0.2);
        color: #34d399;
    }

    /* Badges */
    .badge-ruc {
        font-family: 'SFMono-Regular', Consolas, Menlo, Courier, monospace;
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        border: 1px solid var(--dash-border);
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 0.4px;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }
    .badge-city {
        background: var(--dash-panel-bg);
        color: var(--dash-text-muted);
        border: 1px solid var(--dash-border);
        font-weight: 600;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.78rem;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    /* Action Buttons in Table */
    .btn-action-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        color: var(--dash-text-muted);
        cursor: pointer;
        transition: all 0.15s ease;
        margin: 0 2px;
    }
    .btn-action-icon:hover {
        border-color: var(--dash-primary);
        color: var(--dash-primary);
        background: var(--dash-primary-light);
    }
    .btn-action-icon.btn-action-delete:hover {
        border-color: var(--dash-danger);
        color: var(--dash-danger);
        background: #fee2e2;
    }
    body.dark-mode .btn-action-icon.btn-action-delete:hover {
        background: #450a0a;
    }

    /* Pagination Footer */
    .table-pagination-footer {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.85rem 1.25rem;
        border-top: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
    }
    .page-nav-btn {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        border: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        color: var(--dash-text-main);
        border-radius: 6px;
        font-size: 0.82rem;
        font-weight: 600;
        transition: all 0.15s;
        cursor: pointer;
    }
    .page-nav-btn:hover:not(:disabled) {
        background: var(--dash-primary-light);
        border-color: var(--dash-primary-border);
        color: var(--dash-primary);
    }
    .page-nav-btn:disabled {
        opacity: 0.45;
        cursor: not-allowed;
    }
    .page-numbers {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    .page-number-btn {
        min-width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 0.35rem;
        border: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        color: var(--dash-text-main);
        border-radius: 6px;
        font-size: 0.82rem;
        font-weight: 600;
        transition: all 0.15s;
        cursor: pointer;
    }
    .page-number-btn:hover:not(.active):not(.dots) {
        background: var(--dash-primary-light);
        border-color: var(--dash-primary-border);
        color: var(--dash-primary);
    }
    .page-number-btn.active {
        background: var(--dash-primary) !important;
        border-color: var(--dash-primary) !important;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(10, 77, 54, 0.25);
    }
    .page-number-btn.dots {
        border: none;
        background: transparent;
        cursor: default;
    }

    /* Modal Form Styles */
    .modal-content-custom {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }
    .modal-header-custom {
        padding: 1.15rem 1.5rem;
        background: var(--dash-card-bg);
        border-bottom: 1px solid var(--dash-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .modal-title-custom {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--dash-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    body.dark-mode .modal-title-custom {
        color: #10b981;
    }
    .pos-label {
        font-size: 0.84rem;
        font-weight: 700;
        color: var(--dash-text-main);
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .pos-label .req {
        color: var(--dash-danger);
    }
    .pos-input, .pos-select {
        width: 100%;
        padding: 0.55rem 0.85rem;
        border-radius: 10px;
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        font-size: 0.9rem;
        transition: all 0.15s ease;
    }
    .pos-input:focus, .pos-select:focus {
        outline: none;
        border-color: var(--dash-primary);
        background: var(--dash-card-bg);
        box-shadow: 0 0 0 3px var(--dash-primary-light);
    }
    body.dark-mode .pos-input:focus, body.dark-mode .pos-select:focus {
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }
</style>
@endsection

@section('main')
<div class="container-fluid" id="app" v-cloak>

    <!-- DASHBOARD HEADER -->
    <div class="dash-header">
        <div class="dash-header-left">
            <div class="dash-header-icon">
                <i class="fa fa-truck-loading"></i>
            </div>
            <div>
                <h4 class="dash-header-title font-cairo">Gestión de Proveedores</h4>
                <p class="dash-header-subtitle">Directorio comercial de suministradores, datos de contacto, RUC y ubicación</p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn-pos-primary font-cairo" @click="abrirModalCrear">
                <i class="fa fa-plus-circle"></i>
                <span>Nuevo Proveedor</span>
            </button>
        </div>
    </div>

    <!-- QUICK STATS KPI ROW -->
    <div class="kpi-row">
        <!-- KPI 1: Total Proveedores -->
        <div class="kpi-card">
            <div class="kpi-icon-box kpi-icon-green">
                <i class="fa fa-building"></i>
            </div>
            <div>
                <div class="kpi-label">Total Proveedores</div>
                <div class="kpi-val font-cairo">@{{ proveedores.length }}</div>
                <div class="kpi-sub">Registrados en el sistema</div>
            </div>
        </div>

        <!-- KPI 2: Con RUC -->
        <div class="kpi-card">
            <div class="kpi-icon-box kpi-icon-blue">
                <i class="fa fa-id-card"></i>
            </div>
            <div>
                <div class="kpi-label">Con R.U.C.</div>
                <div class="kpi-val font-cairo">@{{ countConRuc }}</div>
                <div class="kpi-sub">Proveedores formales</div>
            </div>
        </div>

        <!-- KPI 3: Con Teléfono -->
        <div class="kpi-card">
            <div class="kpi-icon-box kpi-icon-amber">
                <i class="fa fa-phone-alt"></i>
            </div>
            <div>
                <div class="kpi-label">Con Teléfono</div>
                <div class="kpi-val font-cairo">@{{ countConTelefono }}</div>
                <div class="kpi-sub">Contacto directo disponible</div>
            </div>
        </div>

        <!-- KPI 4: Ciudades -->
        <div class="kpi-card">
            <div class="kpi-icon-box kpi-icon-purple">
                <i class="fa fa-map-marked-alt"></i>
            </div>
            <div>
                <div class="kpi-label">Ciudades</div>
                <div class="kpi-val font-cairo">@{{ countCiudades }}</div>
                <div class="kpi-sub">Zonas de suministro</div>
            </div>
        </div>
    </div>

    <!-- TOOLBAR & FILTERS CARD -->
    <div class="toolbar-card">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex flex-wrap align-items-center gap-2 flex-grow-1">
                <!-- Search input -->
                <div class="search-input-wrap">
                    <i class="fa fa-search"></i>
                    <input type="text" v-model="filtroTexto" placeholder="Buscar por nombre, RUC, teléfono o dirección..." autofocus>
                    <button v-if="filtroTexto" class="search-clear-btn" @click="filtroTexto = ''" title="Limpiar búsqueda">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                <!-- City filter -->
                <select class="filter-select" v-model="filtroCiudad">
                    <option value="">Todas las ciudades</option>
                    @foreach ($ciudades as $c)
                    <option value="{{ $c->CIUDAD_cod }}">{{ $c->ciudad_nombre }}</option>
                    @endforeach
                </select>

                <!-- Reset button if filters active -->
                <button v-if="filtroTexto || filtroCiudad" class="btn-pos-secondary" @click="resetFiltros" title="Restablecer filtros">
                    <i class="fa fa-undo"></i>
                    <span>Limpiar</span>
                </button>
            </div>

            <!-- Items per page & info -->
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small">Mostrar:</span>
                <select class="filter-select py-1 px-2" style="min-width: 75px; width: 75px;" v-model.number="perPage">
                    <option :value="10">10</option>
                    <option :value="25">25</option>
                    <option :value="50">50</option>
                    <option :value="100">100</option>
                </select>
                <span class="text-muted small ml-1">
                    (@{{ filteredProveedores.length }} proveedores)
                </span>
            </div>
        </div>
    </div>

    <!-- MAIN SUPPLIERS TABLE CARD -->
    <div class="card-table">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 60px;" class="text-center">#</th>
                        <th>Proveedor / Razón Social</th>
                        <th>R.U.C.</th>
                        <th>Ciudad</th>
                        <th>Contacto</th>
                        <th>Dirección</th>
                        <th style="width: 100px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="pagedProveedores.length === 0">
                        <td colspan="7" class="text-center py-5">
                            <i class="fa fa-truck-loading fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                            <div class="font-weight-bold text-muted">No se encontraron proveedores</div>
                            <small class="text-muted" v-if="filtroTexto || filtroCiudad">
                                Intentá cambiar el término de búsqueda o limpiar los filtros aplicados.
                            </small>
                            <small class="text-muted" v-else>
                                Aún no hay proveedores registrados. Hacé clic en "Nuevo Proveedor" para registrar el primero.
                            </small>
                        </td>
                    </tr>
                    <tr v-for="(p, index) in pagedProveedores" :key="p.PROVEEDOR_cod">
                        <td class="text-center text-muted font-weight-bold">
                            @{{ (currentPage - 1) * perPage + index + 1 }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="supplier-avatar font-cairo">
                                    @{{ getInitials(p.proveedor_nombre) }}
                                </div>
                                <div>
                                    <div class="font-weight-bold text-dark dark:text-white">
                                        @{{ (p.proveedor_nombre || '').trim() }}
                                    </div>
                                    <small class="text-muted">Cód: #@{{ p.PROVEEDOR_cod }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span v-if="p.proveedor_ruc && p.proveedor_ruc.trim() !== ''" class="badge-ruc">
                                <i class="fa fa-id-badge text-muted mr-1"></i>@{{ (p.proveedor_ruc || '').trim() }}
                            </span>
                            <span v-else class="text-muted small">Sin RUC</span>
                        </td>
                        <td>
                            <span class="badge-city">
                                <i class="fa fa-map-marker-alt text-info"></i>
                                @{{ getCiudadName(p.CIUDAD_cod) }}
                            </span>
                        </td>
                        <td>
                            <div v-if="p.proveedor_telef && p.proveedor_telef.trim() !== ''" class="d-flex align-items-center gap-1">
                                <a :href="'tel:' + p.proveedor_telef.trim()" class="text-success font-weight-bold text-decoration-none">
                                    <i class="fa fa-phone-alt mr-1"></i>@{{ p.proveedor_telef.trim() }}
                                </a>
                            </div>
                            <span v-else class="text-muted small">Sin teléfono</span>
                        </td>
                        <td>
                            <span v-if="p.proveedor_direc && p.proveedor_direc.trim() !== ''" class="text-muted small" :title="p.proveedor_direc.trim()">
                                @{{ (p.proveedor_direc || '').trim() }}
                            </span>
                            <span v-else class="text-muted small font-italic">—</span>
                        </td>
                        <td class="text-center">
                            <button class="btn-action-icon" @click="showEditar(p)" title="Editar proveedor">
                                <i class="fa fa-edit text-primary"></i>
                            </button>
                            <button class="btn-action-icon btn-action-delete" @click="eliminarProveedor(p)" title="Eliminar proveedor">
                                <i class="fa fa-trash-alt text-danger"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- TABLE PAGINATION FOOTER -->
        <div class="table-pagination-footer" v-if="filteredProveedores.length > perPage">
            <div class="text-muted small">
                Mostrando @{{ ((currentPage - 1) * perPage) + 1 }} a @{{ Math.min(currentPage * perPage, filteredProveedores.length) }} de @{{ filteredProveedores.length }} proveedores
            </div>
            <div class="d-flex align-items-center gap-1">
                <button class="page-nav-btn" :disabled="currentPage === 1" @click="currentPage = 1" title="Primera página">
                    <i class="fa fa-angle-double-left"></i>
                </button>
                <button class="page-nav-btn" :disabled="currentPage === 1" @click="currentPage--" title="Anterior">
                    <i class="fa fa-chevron-left"></i>
                </button>
                <div class="page-numbers">
                    <button v-for="page in totalPages" :key="page" class="page-number-btn"
                        :class="{ 'active': page === currentPage }"
                        @click="currentPage = page">
                        @{{ page }}
                    </button>
                </div>
                <button class="page-nav-btn" :disabled="currentPage === totalPages" @click="currentPage++" title="Siguiente">
                    <i class="fa fa-chevron-right"></i>
                </button>
                <button class="page-nav-btn" :disabled="currentPage === totalPages" @click="currentPage = totalPages" title="Última página">
                    <i class="fa fa-angle-double-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL REGISTRO / EDICIÓN PROVEEDOR -->
    <div class="modal fade" id="modalProveedor" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content modal-content-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title-custom font-cairo">
                        <i class="fa" :class="editando ? 'fa-edit text-primary' : 'fa-truck-loading text-success'"></i>
                        <span>@{{ editando ? 'Editar Proveedor' : 'Registrar Nuevo Proveedor' }}</span>
                    </h5>
                    <button type="button" class="close" @click="cerrarModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <form @submit.prevent="guardar">
                        <div class="row">
                            <!-- Nombre / Razón Social -->
                            <div class="col-md-8 mb-3">
                                <label class="pos-label">
                                    <i class="fa fa-store"></i> Nombre comercial / Razón social <span class="req">*</span>
                                </label>
                                <input type="text" class="pos-input" v-model.trim="form.nombre" ref="nombreInput"
                                    placeholder="Ej: DISTRIBUIDORA DEL ESTE S.A." required>
                            </div>

                            <!-- RUC -->
                            <div class="col-md-4 mb-3">
                                <label class="pos-label">
                                    <i class="fa fa-id-badge"></i> R.U.C.
                                </label>
                                <input type="text" class="pos-input" v-model.trim="form.ruc" placeholder="Ej: 80012345-6">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Ciudad -->
                            <div class="col-md-6 mb-3">
                                <label class="pos-label">
                                    <i class="fa fa-city"></i> Ciudad
                                </label>
                                <select class="pos-select" v-model="form.idciudad">
                                    @foreach ($ciudades as $c)
                                    <option value="{{ $c->CIUDAD_cod }}">{{ $c->ciudad_nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nacionalidad -->
                            <div class="col-md-6 mb-3">
                                <label class="pos-label">
                                    <i class="fa fa-globe-americas"></i> Nacionalidad / Origen
                                </label>
                                <select class="pos-select" v-model="form.idnacionalidad">
                                    <option value="1">Paraguaya</option>
                                    <option value="2">Extranjera / Importación</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Teléfono -->
                            <div class="col-md-6 mb-3">
                                <label class="pos-label">
                                    <i class="fa fa-phone-alt"></i> Teléfono o Celular
                                </label>
                                <input type="text" class="pos-input" v-model.trim="form.telefono" placeholder="Ej: 0981 123 456">
                            </div>

                            <!-- Dirección -->
                            <div class="col-md-6 mb-3">
                                <label class="pos-label">
                                    <i class="fa fa-map-marked-alt"></i> Dirección comercial
                                </label>
                                <input type="text" class="pos-input" v-model.trim="form.direccion" placeholder="Calle, número o referencia">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-outline-secondary" @click="cerrarModal" :disabled="guardando">
                        <i class="fa fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="button" class="btn-pos-primary font-cairo" @click="guardar" :disabled="guardando">
                        <i class="fa" :class="guardando ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                        <span>@{{ guardando ? 'Guardando...' : (editando ? 'Actualizar Proveedor' : 'Guardar Proveedor') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
<script>
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    var app = new Vue({
        el: '#app',
        data: {
            guardando: false,
            editando: false,
            filtroTexto: '',
            filtroCiudad: '',
            currentPage: 1,
            perPage: 10,
            ciudades: @json($ciudades),
            proveedores: @json($proveedores),
            form: {
                id: 0,
                nombre: '',
                ruc: '',
                idciudad: 1,
                idnacionalidad: 1,
                telefono: '',
                direccion: ''
            }
        },
        computed: {
            countConRuc: function () {
                return this.proveedores.filter(function (p) {
                    return p.proveedor_ruc && p.proveedor_ruc.trim() !== '';
                }).length;
            },
            countConTelefono: function () {
                return this.proveedores.filter(function (p) {
                    return p.proveedor_telef && p.proveedor_telef.trim() !== '';
                }).length;
            },
            countCiudades: function () {
                var unique = {};
                this.proveedores.forEach(function (p) {
                    if (p.CIUDAD_cod) unique[p.CIUDAD_cod] = true;
                });
                return Object.keys(unique).length;
            },
            filteredProveedores: function () {
                var q = (this.filtroTexto || '').toLowerCase().trim();
                var c = this.filtroCiudad;

                return this.proveedores.filter(function (p) {
                    // Filter city
                    if (c !== '' && p.CIUDAD_cod != c) {
                        return false;
                    }
                    // Filter text
                    if (q !== '') {
                        var nombre = (p.proveedor_nombre || '').toLowerCase();
                        var ruc = (p.proveedor_ruc || '').toLowerCase();
                        var telef = (p.proveedor_telef || '').toLowerCase();
                        var direc = (p.proveedor_direc || '').toLowerCase();
                        return nombre.indexOf(q) !== -1 || ruc.indexOf(q) !== -1 || telef.indexOf(q) !== -1 || direc.indexOf(q) !== -1;
                    }
                    return true;
                });
            },
            totalPages: function () {
                return Math.max(1, Math.ceil(this.filteredProveedores.length / this.perPage));
            },
            pagedProveedores: function () {
                if (this.currentPage > this.totalPages) {
                    this.currentPage = this.totalPages;
                }
                var start = (this.currentPage - 1) * this.perPage;
                return this.filteredProveedores.slice(start, start + this.perPage);
            }
        },
        watch: {
            filtroTexto: function () {
                this.currentPage = 1;
            },
            filtroCiudad: function () {
                this.currentPage = 1;
            },
            perPage: function () {
                this.currentPage = 1;
            }
        },
        methods: {
            getInitials: function (name) {
                if (!name) return 'PR';
                var clean = name.trim().split(/\s+/);
                if (clean.length === 1) {
                    return clean[0].substring(0, 2).toUpperCase();
                }
                return (clean[0][0] + clean[1][0]).toUpperCase();
            },
            getCiudadName: function (cod) {
                var found = this.ciudades.find(function (c) {
                    return c.CIUDAD_cod == cod;
                });
                return found ? found.ciudad_nombre : 'Ciudad #' + cod;
            },
            resetFiltros: function () {
                this.filtroTexto = '';
                this.filtroCiudad = '';
                this.currentPage = 1;
            },
            abrirModalCrear: function () {
                this.editando = false;
                this.form = {
                    id: 0,
                    nombre: '',
                    ruc: '',
                    idciudad: this.ciudades.length ? this.ciudades[0].CIUDAD_cod : 1,
                    idnacionalidad: 1,
                    telefono: '',
                    direccion: ''
                };
                $('#modalProveedor').modal('show');
                var self = this;
                this.$nextTick(function () {
                    if (self.$refs.nombreInput) self.$refs.nombreInput.focus();
                });
            },
            showEditar: function (p) {
                this.editando = true;
                this.form = {
                    id: p.PROVEEDOR_cod,
                    nombre: (p.proveedor_nombre || '').trim(),
                    ruc: (p.proveedor_ruc || '').trim(),
                    idciudad: p.CIUDAD_cod || 1,
                    idnacionalidad: p.nacio_cod || 1,
                    telefono: (p.proveedor_telef || '').trim(),
                    direccion: (p.proveedor_direc || '').trim()
                };
                $('#modalProveedor').modal('show');
                var self = this;
                this.$nextTick(function () {
                    if (self.$refs.nombreInput) self.$refs.nombreInput.focus();
                });
            },
            cerrarModal: function () {
                $('#modalProveedor').modal('hide');
            },
            guardar: function () {
                if (!this.form.nombre || this.form.nombre.trim().length === 0) {
                    Swal.fire({
                        title: 'Nombre Requerido',
                        text: 'Ingresá el nombre o razón social del proveedor.',
                        icon: 'warning',
                        confirmButtonColor: '#0a4d36'
                    });
                    return;
                }

                var self = this;
                this.guardando = true;

                if (this.editando) {
                    // Actualizar proveedor existente
                    axios.post('{{ url('proveedor') }}/' + this.form.id, this.form)
                        .then(function (response) {
                            self.guardando = false;
                            // Actualizar en array local
                            var found = self.proveedores.find(function (item) {
                                return item.PROVEEDOR_cod == self.form.id;
                            });
                            if (found) {
                                found.proveedor_nombre = self.form.nombre;
                                found.proveedor_ruc = self.form.ruc;
                                found.CIUDAD_cod = self.form.idciudad;
                                found.nacio_cod = self.form.idnacionalidad;
                                found.proveedor_telef = self.form.telefono;
                                found.proveedor_direc = self.form.direccion;
                            }
                            $('#modalProveedor').modal('hide');
                            Toast.fire({
                                title: (response.data && response.data.message) ? response.data.message : 'Proveedor actualizado con éxito',
                                icon: 'success'
                            });
                        })
                        .catch(function (error) {
                            self.guardando = false;
                            var msg = (error.response && error.response.data && error.response.data.message)
                                ? error.response.data.message
                                : 'No se pudo actualizar el proveedor.';
                            Swal.fire({
                                title: 'Error al actualizar',
                                text: msg,
                                icon: 'error',
                                confirmButtonColor: '#ef4444'
                            });
                        });
                } else {
                    // Crear nuevo proveedor
                    axios.post('{{ url('proveedor') }}', this.form)
                        .then(function (response) {
                            self.guardando = false;
                            var newProv = (response.data && response.data.proveedor) ? response.data.proveedor : {
                                PROVEEDOR_cod: Date.now(),
                                proveedor_nombre: self.form.nombre,
                                proveedor_ruc: self.form.ruc,
                                CIUDAD_cod: self.form.idciudad,
                                nacio_cod: self.form.idnacionalidad,
                                proveedor_telef: self.form.telefono,
                                proveedor_direc: self.form.direccion
                            };
                            self.proveedores.unshift(newProv);
                            $('#modalProveedor').modal('hide');
                            Toast.fire({
                                title: (response.data && response.data.message) ? response.data.message : 'Proveedor registrado con éxito',
                                icon: 'success'
                            });
                        })
                        .catch(function (error) {
                            self.guardando = false;
                            var msg = (error.response && error.response.data && error.response.data.message)
                                ? error.response.data.message
                                : 'No se pudo registrar el proveedor.';
                            Swal.fire({
                                title: 'Error al registrar',
                                text: msg,
                                icon: 'error',
                                confirmButtonColor: '#ef4444'
                            });
                        });
                }
            },
            eliminarProveedor: function (p) {
                var self = this;
                var nombre = (p.proveedor_nombre || '').trim();
                Swal.fire({
                    title: '¿Eliminar proveedor?',
                    text: 'Estás a punto de eliminar a "' + nombre + '". Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b'
                }).then(function (result) {
                    if (result.value) {
                        axios.delete('{{ url('proveedor') }}/' + p.PROVEEDOR_cod)
                            .then(function (response) {
                                self.proveedores = self.proveedores.filter(function (item) {
                                    return item.PROVEEDOR_cod != p.PROVEEDOR_cod;
                                });
                                Toast.fire({
                                    title: (response.data && response.data.message) ? response.data.message : 'Proveedor eliminado con éxito',
                                    icon: 'success'
                                });
                            })
                            .catch(function (error) {
                                var msg = (error.response && error.response.data && error.response.data.message)
                                    ? error.response.data.message
                                    : 'No se pudo eliminar el proveedor.';
                                Swal.fire({
                                    title: 'No se pudo eliminar',
                                    text: msg,
                                    icon: 'error',
                                    confirmButtonColor: '#ef4444'
                                });
                            });
                    }
                });
            }
        },
        mounted: function () {
            activarMenu('m_mantenimiento', 'm_proveedor');
        }
    });
</script>
@endsection
