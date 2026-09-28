@extends('layouts.app')
@section('title', 'Gestión de Combos')
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

    /* Botones POS */
    .btn-pos-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.55rem 1.25rem;
        background: var(--dash-primary);
        border: 1px solid var(--dash-primary);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.92rem;
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
        background: var(--dash-card-bg);
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
    .kpi-icon-blue { background: #e0f2fe; color: #0284c7; }
    .kpi-icon-amber { background: #fef3c7; color: #b45309; }
    .kpi-icon-purple { background: #f3e8ff; color: #7e22ce; }
    .kpi-value {
        font-size: 1.65rem;
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

    /* Contenedor Tarjeta */
    .table-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }
    .table-card-head {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
    }

    /* Tabla */
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
        user-select: none;
        white-space: nowrap;
    }
    .table-custom tbody td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--dash-border);
        color: var(--dash-text-main);
        font-size: 0.9rem;
    }
    .table-custom tbody tr {
        transition: background-color 0.15s;
    }
    .table-custom tbody tr:hover {
        background-color: var(--dash-primary-light);
    }
    .table-custom tbody tr.combo-selected {
        background-color: var(--dash-primary-light);
        border-left: 4px solid var(--dash-primary);
    }

    /* Badges */
    .badge-stock-in {
        background-color: #dcfce7;
        color: #166534;
        font-weight: 700;
        border: 1px solid #bbf7d0;
        border-radius: 20px;
        padding: 4px 10px;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }
    .badge-stock-out {
        background-color: #fee2e2;
        color: #991b1b;
        font-weight: 700;
        border: 1px solid #fecaca;
        border-radius: 20px;
        padding: 4px 10px;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }
    .badge-barcode {
        font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, Courier, monospace;
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }
    .badge-section {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        font-weight: 600;
        padding: 0.25rem 0.55rem;
        border-radius: 6px;
        font-size: 0.78rem;
        white-space: nowrap;
    }
    .price-display {
        font-size: 1rem;
        color: var(--dash-primary) !important;
        white-space: nowrap;
    }

    /* Buscador & Dropdown */
    .search-prepend {
        background-color: var(--dash-card-bg) !important;
        border-color: var(--dash-border) !important;
        color: var(--dash-text-muted) !important;
        border-radius: 8px 0 0 8px !important;
    }
    .search-input {
        background-color: var(--dash-card-bg) !important;
        border-color: var(--dash-border) !important;
        color: var(--dash-text-main) !important;
        border-radius: 0 8px 8px 0;
        font-size: 0.92rem;
    }
    .search-input:focus {
        background-color: var(--dash-card-bg) !important;
        border-color: var(--dash-primary) !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2) !important;
    }
    .combo-search-results {
        max-height: 250px;
        overflow-y: auto;
        border: 1px solid var(--dash-border);
        border-radius: 10px;
        background: var(--dash-card-bg);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        position: relative;
        z-index: 25;
    }
    .combo-search-item {
        cursor: pointer;
        padding: 0.6rem 0.9rem;
        border-bottom: 1px solid var(--dash-border);
        transition: background 0.15s;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        text-align: left;
        width: 100%;
        background: transparent;
        border-left: none;
        border-right: none;
        border-top: none;
    }
    .combo-search-item:last-child {
        border-bottom: none;
    }
    .combo-search-item:hover {
        background: var(--dash-primary-light);
    }

    /* Botón Eliminar Suave */
    .btn-action-stock-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #dc2626;
        transition: all 0.15s;
        cursor: pointer;
    }
    .btn-action-stock-delete:hover {
        background: #dc2626;
        color: #ffffff;
        border-color: #dc2626;
    }

    /* Cajas Resumen de Precios */
    .combo-prices-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 0.75rem;
    }
    @media (max-width: 576px) {
        .combo-prices-grid {
            grid-template-columns: 1fr;
        }
    }
    .price-box-card {
        background: #f8fafc;
        border: 1px solid var(--dash-border);
        border-radius: 10px;
        padding: 0.85rem 1rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-width: 0;
        height: 100%;
    }
    .price-box-credito {
        border-color: #38bdf8 !important;
        background: #f0f9ff !important;
    }
    .credit-chip {
        display: inline-flex;
        align-items: center;
        padding: 2px 7px;
        font-size: 0.72rem;
        font-weight: 700;
        border-radius: 4px;
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
        cursor: pointer;
        transition: all 0.12s ease;
    }
    .credit-chip:hover {
        background: #0284c7;
        color: #ffffff;
        border-color: #0284c7;
    }

    /* Banner Ahorro */
    .savings-banner {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.12) 0%, rgba(5, 150, 105, 0.08) 100%);
        border: 1px solid rgba(16, 185, 129, 0.3);
        border-radius: 10px;
        padding: 0.75rem 1rem;
    }

    /* Switch Card */
    .combo-switch-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        transition: border-color 0.15s;
    }
    .combo-switch-card .custom-switch {
        padding-left: 2.75rem;
        margin-bottom: 0;
    }
    .combo-switch-card .custom-control-label {
        font-weight: 600;
        color: var(--dash-text-main);
        cursor: pointer;
        user-select: none;
        padding-top: 1px;
    }
    .combo-switch-card .custom-control-input:checked ~ .custom-control-label::before {
        background-color: var(--dash-primary) !important;
        border-color: var(--dash-primary) !important;
    }
    .combo-switch-card .custom-control-input:focus ~ .custom-control-label::before {
        box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25) !important;
    }

    /* Modo Oscuro Global */
    body.dark-mode .table-card { background: var(--dash-card-bg); border-color: var(--dash-border); }
    body.dark-mode .combo-switch-card { background: var(--dash-card-bg); border-color: var(--dash-border); }
    body.dark-mode .table-card-head { background: var(--dash-card-bg); border-color: var(--dash-border); color: var(--dash-text-main); }
    body.dark-mode .table-custom thead th { background: #111827 !important; color: var(--dash-text-muted) !important; border-color: var(--dash-border) !important; }
    body.dark-mode .table-custom tbody tr { background-color: var(--dash-card-bg) !important; }
    body.dark-mode .table-custom tbody tr:hover { background-color: var(--dash-primary-light) !important; }
    body.dark-mode .table-custom tbody tr.combo-selected { background-color: var(--dash-primary-light) !important; border-left: 4px solid var(--dash-primary); }
    body.dark-mode .table-custom tbody td { border-color: var(--dash-border) !important; color: var(--dash-text-main) !important; }
    body.dark-mode .badge-barcode { background: #111827; color: #cbd5e1; border-color: #374151; }
    body.dark-mode .badge-section { background: #111827; color: #9ca3af; border-color: #374151; }
    body.dark-mode .badge-stock-in { background-color: rgba(16, 185, 129, 0.2) !important; color: #6ee7b7 !important; border-color: #059669 !important; }
    body.dark-mode .badge-stock-out { background-color: rgba(239, 68, 68, 0.2) !important; color: #fca5a5 !important; border-color: #dc2626 !important; }
    body.dark-mode .btn-pos-secondary { background: #111827 !important; border-color: var(--dash-border) !important; color: var(--dash-text-main) !important; }
    body.dark-mode .btn-pos-secondary:hover { background: var(--dash-primary-light) !important; color: #34d399 !important; }
    body.dark-mode .btn-action-stock-delete { background: rgba(239, 68, 68, 0.15) !important; border-color: rgba(239, 68, 68, 0.3) !important; color: #fca5a5 !important; }
    body.dark-mode .btn-action-stock-delete:hover { background: #dc2626 !important; color: #ffffff !important; }
    body.dark-mode .combo-search-results { background: #1f2937; border-color: #374151; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
    body.dark-mode .combo-search-item { border-color: #374151; color: var(--dash-text-main); }
    body.dark-mode .combo-search-item:hover { background: #111827; }
    body.dark-mode .price-box-card { background: #111827 !important; border-color: #374151 !important; }
    body.dark-mode .price-box-credito { background: rgba(2, 132, 199, 0.15) !important; border-color: #0284c7 !important; }
    body.dark-mode .credit-chip { background: #075985; color: #e0f2fe; border-color: #0369a1; }
    body.dark-mode .credit-chip:hover { background: #0284c7; color: #ffffff; }
    body.dark-mode .form-control { background-color: #111827 !important; border-color: var(--dash-border) !important; color: var(--dash-text-main) !important; }
    body.dark-mode .input-group-text { background-color: #111827 !important; border-color: var(--dash-border) !important; color: var(--dash-text-muted) !important; }
    body.dark-mode .price-display { color: #34d399 !important; }
</style>
@endsection

@section('main')
<div class="container-fluid px-3 py-3" id="app" v-cloak>
    <!-- Cabecera estilo Dashboard Inicio -->
    <div class="dash-header">
        <div>
            <h1 class="dash-header-title font-cairo">
                <i class="fa-solid fa-layer-group mr-2"></i>Gestión de Combos y Packs
            </h1>
            <p class="dash-header-subtitle">
                Armá promociones, combos con múltiples artículos y precios especiales redondeados para venta directa.
            </p>
        </div>
        <div class="dash-header-badges">
            <a href="{{ route('articulo') }}" class="btn-pos-secondary" title="Volver al catálogo general de artículos">
                <i class="fa fa-arrow-left"></i> Catálogo Artículos
            </a>
            <button type="button" class="btn-pos-primary" @click="nuevo">
                <i class="fa fa-plus-circle"></i> Nuevo Combo
            </button>
        </div>
    </div>

    <!-- Tarjetas KPI Resumen -->
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #7e22ce;">
                <div class="kpi-header">
                    <span class="kpi-label">Total Combos</span>
                    <div class="kpi-icon-box kpi-icon-purple">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #7e22ce;">@{{ combos.length }}</div>
                <div class="kpi-subtext" style="color: #7e22ce;">
                    <i class="fa-solid fa-boxes-packing"></i> Packs registrados
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #16a34a;">
                <div class="kpi-header">
                    <span class="kpi-label">Combos Activos</span>
                    <div class="kpi-icon-box kpi-icon-green">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #166534;">@{{ totalCombosActivos }}</div>
                <div class="kpi-subtext" style="color: #166534;">
                    <i class="fa-solid fa-cash-register"></i> Visibles en punto de venta
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #b45309;">
                <div class="kpi-header">
                    <span class="kpi-label">Pausados / Inactivos</span>
                    <div class="kpi-icon-box kpi-icon-amber">
                        <i class="fa-solid fa-circle-pause"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #b45309;">@{{ totalCombosInactivos }}</div>
                <div class="kpi-subtext" style="color: #b45309;">
                    <i class="fa-solid fa-clock-rotate-left"></i> Fuera de temporada
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #0284c7;">
                <div class="kpi-header">
                    <span class="kpi-label">Artículos Incluidos</span>
                    <div class="kpi-icon-box kpi-icon-blue">
                        <i class="fa-solid fa-cubes"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #0284c7;">@{{ totalItemsEnCombos }}</div>
                <div class="kpi-subtext" style="color: #0284c7;">
                    <i class="fa-solid fa-tags"></i> Enlazados en total
                </div>
            </div>
        </div>
    </div>

    <!-- Grilla Principal: Listado a la Izquierda y Formulario a la Derecha -->
    <div class="row">
        <!-- Columna Izquierda: Listado de Combos -->
        <div class="col-xl-5 col-12 mb-4 mb-xl-0">
            <div class="table-card">
                <!-- Cabecera de la lista con buscador -->
                <div class="table-card-head">
                    <div>
                        <h6 class="font-weight-bold mb-0" style="color: var(--dash-text-main);">
                            <i class="fa-solid fa-list-check mr-1.5 text-muted"></i> Catálogo de Combos
                        </h6>
                        <small class="text-muted">Hacé clic en una fila para editar</small>
                    </div>
                    <button type="button" class="btn-pos-primary btn-sm py-1 px-2" @click="nuevo" title="Crear un nuevo combo">
                        <i class="fa fa-plus"></i> Nuevo
                    </button>
                </div>

                <!-- Buscador de Combos en Lista -->
                <div class="p-2 border-bottom" style="background: var(--dash-card-bg);">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text search-prepend border-right-0">
                                <i class="fa-solid fa-magnifying-glass text-muted"></i>
                            </span>
                        </div>
                        <input type="text"
                               v-model="filtroCombos"
                               placeholder="Filtrar por nombre o código de barra..."
                               class="form-control search-input border-left-0" />
                        <div class="input-group-append" v-if="filtroCombos">
                            <button class="btn btn-outline-secondary border-left-0" type="button" @click="filtroCombos = ''" title="Limpiar filtro">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Combos -->
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Combo / Código</th>
                                <th class="text-right" style="width: 140px;">Precio Venta</th>
                                <th class="text-center" style="width: 45px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="c in combosFiltrados" :key="c.id"
                                :class="{ 'combo-selected': form.id === c.id }"
                                style="cursor: pointer;"
                                @click="editar(c)">
                                <td>
                                    <div class="font-weight-bold font-cairo" style="font-size: 0.95rem; color: var(--dash-text-main); word-break: break-word;">
                                        @{{ c.nombre }}
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap gap-1 mt-1">
                                        <span v-if="c.codigo" class="badge-barcode mr-1 mb-1">
                                            <i class="fa fa-barcode mr-1 text-muted"></i>@{{ c.codigo }}
                                        </span>
                                        <span class="badge-section mr-1 mb-1">
                                            <i class="fa fa-cubes mr-1 text-muted"></i>@{{ (c.items || []).length }} ítems
                                        </span>
                                        <span v-if="Number(c.activo)" class="badge-stock-in mr-1 mb-1" style="font-size: 0.72rem; padding: 2px 7px;">
                                            <i class="fa-solid fa-circle-check mr-1"></i>Activo
                                        </span>
                                        <span v-else class="badge-stock-out mr-1 mb-1" style="font-size: 0.72rem; padding: 2px 7px;">
                                            <i class="fa-solid fa-circle-pause mr-1"></i>Inactivo
                                        </span>
                                    </div>
                                </td>
                                <td class="text-right" style="white-space: nowrap; width: 140px;">
                                    <div class="font-weight-bold font-cairo price-display" style="font-size: 1rem;">
                                        <span class="badge badge-light text-muted mr-1" style="font-size: 0.68rem; font-weight: 600;">CONT.</span>Gs. @{{ format(c.precio) }}
                                    </div>
                                    <div v-if="Number(c.precio_credito) > 0" class="font-weight-bold font-cairo" style="font-size: 0.88rem; color: #0284c7;">
                                        <span class="badge badge-info mr-1" style="font-size: 0.65rem; font-weight: 600; background: #0284c7;">CRÉD.</span>Gs. @{{ format(c.precio_credito) }}
                                    </div>
                                    <div class="small text-muted" v-if="c.precio_lista > c.precio" style="text-decoration: line-through; font-size: 0.75rem;">
                                        Lista: Gs. @{{ format(c.precio_lista) }}
                                    </div>
                                </td>
                                <td class="text-center" style="width: 45px;" @click.stop>
                                    <button type="button" class="btn-action-stock-delete" @click="eliminar(c)" title="Eliminar este combo">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="!combosFiltrados.length">
                                <td colspan="3" class="text-center py-5">
                                    <div class="mb-2 text-muted" style="font-size: 2rem;">
                                        <i class="fa-solid fa-boxes-packing"></i>
                                    </div>
                                    <div class="font-weight-bold" style="color: var(--dash-text-main);">No se encontraron combos</div>
                                    <p class="text-muted small mb-0">Hacé clic en "+ Nuevo Combo" para crear tu primer pack.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pie Informativo de Lista -->
                <div class="d-flex align-items-center justify-content-between p-2 px-3 border-top" style="background: var(--dash-card-bg);">
                    <small class="text-muted">
                        Mostrando <strong>@{{ combosFiltrados.length }}</strong> de <strong>@{{ combos.length }}</strong> combos
                    </small>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Formulario Editor -->
        <div class="col-xl-7 col-12">
            <div class="table-card">
                <!-- Cabecera del Editor -->
                <div class="table-card-head" style="border-top: 4px solid var(--dash-primary);">
                    <div class="d-flex align-items-center">
                        <i :class="form.id ? 'fa-solid fa-pen-to-square text-warning mr-2 fa-lg' : 'fa-solid fa-circle-plus text-success mr-2 fa-lg'"></i>
                        <div>
                            <h6 class="font-weight-bold mb-0 font-cairo" style="color: var(--dash-text-main); font-size: 1.05rem;">
                                @{{ form.id ? 'Modificar Combo #' + form.id : 'Nuevo Combo Promocional' }}
                            </h6>
                            <small class="text-muted">Completá los datos y seleccioná los artículos que componen el pack</small>
                        </div>
                    </div>
                    <div v-if="ahorro > 0">
                        <span class="badge-stock-in font-weight-bold" style="font-size: 0.88rem; padding: 5px 12px;">
                            <i class="fa-solid fa-piggy-bank mr-1.5"></i>
                            Ahorro: Gs. @{{ format(ahorro) }} (@{{ porcentajeAhorro }}%)
                        </span>
                    </div>
                </div>

                <div class="p-3">
                    <!-- Fila 1: Nombre y Código de Barra -->
                    <div class="row">
                        <div class="col-12 col-md-7 mb-3">
                            <label class="font-weight-bold small text-muted mb-1">
                                <i class="fa-solid fa-tag mr-1 text-muted"></i> NOMBRE DEL COMBO <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control font-weight-bold"
                                   v-model.trim="form.nombre"
                                   placeholder="Ej: Pack Fin de Semana (Cerveza + Snack)"
                                   style="border-radius: 8px; font-size: 0.95rem;">
                        </div>
                        <div class="col-12 col-md-5 mb-3">
                            <label class="font-weight-bold small text-muted mb-1">
                                <i class="fa-solid fa-barcode mr-1 text-muted"></i> CÓDIGO DE BARRAS <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control"
                                   :class="{'is-invalid': codigoEstado === 'error', 'is-valid': codigoEstado === 'ok'}"
                                   v-model.trim="form.codigo"
                                   placeholder="Ej: CMB001 o código EAN"
                                   @blur="validarCodigo"
                                   @input="codigoEstado = ''"
                                   style="border-radius: 8px; font-size: 0.95rem;">
                            <small class="form-text" :class="codigoEstado === 'error' ? 'text-danger' : 'text-muted'" v-if="codigoMensaje">
                                @{{ codigoMensaje }}
                            </small>
                        </div>
                    </div>

                    <!-- Buscador de Artículos para Agregar al Combo -->
                    <div class="mb-3">
                        <label class="font-weight-bold small text-muted mb-1">
                            <i class="fa-solid fa-magnifying-glass mr-1 text-muted"></i> BUSCAR ARTÍCULOS PARA AGREGAR
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text search-prepend border-right-0">
                                    <i class="fa-solid fa-magnifying-glass text-muted"></i>
                                </span>
                            </div>
                            <input type="text"
                                   class="form-control search-input border-left-0"
                                   v-model.trim="buscar"
                                   placeholder="Escribí el nombre o código de barras del producto..."
                                   @keyup.enter="buscarArticulos"
                                   @input="onBuscarInput"
                                   style="border-radius: 0 8px 8px 0; font-size: 0.92rem;">
                            <div class="input-group-append" v-if="buscando">
                                <span class="input-group-text bg-white border-left-0">
                                    <i class="fa fa-spinner fa-spin text-success"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Dropdown de Resultados de Búsqueda -->
                        <div class="combo-search-results mt-1" v-if="resultados.length">
                            <button type="button"
                                    class="combo-search-item"
                                    v-for="a in resultados"
                                    :key="a.ARTICULOS_cod"
                                    @click="agregarItem(a)">
                                <div>
                                    <div class="font-weight-bold" style="color: var(--dash-text-main);">
                                        <i class="fa-solid fa-box text-muted mr-1.5"></i>@{{ a.producto_nombre }}
                                    </div>
                                    <small class="text-muted">
                                        <i class="fa fa-barcode mr-1"></i>@{{ a.producto_c_barra || 'Sin código' }}
                                    </small>
                                </div>
                                <div class="text-right">
                                    <span class="price-display font-weight-bold font-cairo">
                                        Gs. @{{ format(a.pre_venta1) }}
                                    </span>
                                    <div class="small text-success">
                                        <i class="fa fa-plus-circle"></i> Agregar
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Tabla de Artículos incluidos en el Combo -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="font-weight-bold small text-muted mb-0">
                                <i class="fa-solid fa-boxes-stacked mr-1 text-muted"></i> ARTÍCULOS INCLUIDOS EN EL PACK (@{{ form.items.length }})
                            </label>
                            <span v-if="form.items.length < 2" class="badge badge-warning">
                                <i class="fa fa-triangle-exclamation mr-1"></i> Se requieren al menos 2 artículos
                            </span>
                        </div>

                        <div class="table-responsive border rounded-lg" style="border-radius: 10px; overflow-x: auto;">
                            <table class="table table-custom mb-0">
                                <thead>
                                    <tr>
                                        <th>Artículo</th>
                                        <th class="text-center" style="width: 85px; min-width: 75px;">Cantidad</th>
                                        <th class="text-right" style="width: 110px; min-width: 95px;">P. Referencia</th>
                                        <th class="text-right" style="width: 110px; min-width: 95px;">Subtotal</th>
                                        <th class="text-center" style="width: 40px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(it, idx) in form.items" :key="it.articulos_cod">
                                        <td>
                                            <div class="font-weight-bold" style="color: var(--dash-text-main); word-break: break-word;">
                                                @{{ it.nombre }}
                                            </div>
                                            <small class="text-muted" v-if="it.codigo">
                                                <i class="fa fa-barcode mr-1"></i>@{{ it.codigo }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <input type="number"
                                                   class="form-control form-control-sm text-center font-weight-bold mx-auto"
                                                   min="0.01"
                                                   step="1"
                                                   v-model.number="it.cantidad"
                                                   @change="recalcular"
                                                   style="border-radius: 6px; max-width: 75px;">
                                        </td>
                                        <td class="text-right text-muted small" style="white-space: nowrap;">
                                            Gs. @{{ format(it.precio_ref) }}
                                        </td>
                                        <td class="text-right font-weight-bold font-cairo" style="white-space: nowrap;">
                                            Gs. @{{ format(it.cantidad * it.precio_ref) }}
                                        </td>
                                        <td class="text-center">
                                            <button type="button"
                                                    class="btn-action-stock-delete"
                                                    style="width: 28px; height: 28px;"
                                                    @click="quitarItem(idx)"
                                                    title="Quitar este artículo del combo">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="!form.items.length">
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fa-solid fa-boxes-packing fa-2x mb-2 text-muted"></i>
                                            <div class="font-weight-bold" style="color: var(--dash-text-main);">Sin artículos en el combo</div>
                                            <small>Buscá arriba los artículos que compondrán este pack promocional.</small>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot v-if="form.items.length" style="background: var(--dash-card-bg);">
                                    <tr>
                                        <th colspan="3" class="text-right font-weight-bold text-muted small text-uppercase">
                                            Suma Precios de Lista:
                                        </th>
                                        <th class="text-right font-weight-bold font-cairo" style="font-size: 1rem; white-space: nowrap;">
                                            Gs. @{{ format(precioLista) }}
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Fila de Precios (Contado, Crédito, Suma) -->
                    <div class="combo-prices-grid mb-3">
                        <!-- Suma Artículos -->
                        <div class="price-box-card">
                            <label class="small font-weight-bold text-muted mb-1">
                                <i class="fa-solid fa-calculator mr-1"></i> SUMA INDIVIDUAL
                            </label>
                            <div class="font-weight-bold font-cairo" style="font-size: 1.15rem; color: var(--dash-text-muted);">
                                Gs. @{{ format(precioLista) }}
                            </div>
                            <small class="text-muted">Si se compraran por separado</small>
                        </div>

                        <!-- Precio Combo Contado -->
                        <div class="price-box-card" style="border-color: var(--dash-primary); background: var(--dash-primary-light);">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="small font-weight-bold mb-0" style="color: var(--dash-primary);">
                                    <i class="fa-solid fa-money-bill-wave mr-1"></i> PRECIO CONTADO *
                                </label>
                                <span class="badge badge-success" style="font-size: 0.65rem;">Caja / Contado</span>
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold border-right-0" style="border-radius: 8px 0 0 8px; background: var(--dash-card-bg); color: var(--dash-primary);">
                                        Gs.
                                    </span>
                                </div>
                                <input type="number"
                                       class="form-control font-weight-bold font-cairo text-right border-left-0"
                                       v-model.number="form.precio"
                                       min="1"
                                       step="1"
                                       @input="precioEditado = true"
                                       style="border-radius: 0 8px 8px 0; font-size: 1.15rem; color: var(--dash-primary);">
                            </div>
                            <div class="mt-1 d-flex align-items-center justify-content-between" v-if="form.precio !== precioLista">
                                <button type="button" class="btn btn-link btn-xs p-0 text-muted" @click="usarSuma">
                                    <i class="fa fa-undo mr-1"></i> Usar suma original
                                </button>
                            </div>
                        </div>

                        <!-- Precio Combo Crédito -->
                        <div class="price-box-card price-box-credito">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="small font-weight-bold mb-0" style="color: #0284c7;">
                                    <i class="fa-solid fa-calendar-check mr-1"></i> PRECIO CRÉDITO
                                </label>
                                <span class="badge badge-info" style="font-size: 0.65rem; background: #0284c7;">Venta a Cuotas</span>
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text font-weight-bold border-right-0" style="border-radius: 8px 0 0 8px; background: var(--dash-card-bg); color: #0284c7;">
                                        Gs.
                                    </span>
                                </div>
                                <input type="number"
                                       class="form-control font-weight-bold font-cairo text-right border-left-0"
                                       v-model.number="form.precio_credito"
                                       min="0"
                                       step="1"
                                       placeholder="0"
                                       style="border-radius: 0 8px 8px 0; font-size: 1.15rem; color: #0284c7;">
                            </div>
                            <!-- Chips de cálculo rápido de crédito -->
                            <div class="d-flex flex-wrap gap-1 mt-1">
                                <button type="button" class="credit-chip mr-1 mb-1" @click="aplicarMargenCredito(0)" title="Mismo precio que contado">
                                    = Contado
                                </button>
                                <button type="button" class="credit-chip mr-1 mb-1" @click="aplicarMargenCredito(10)" title="+10% sobre contado">
                                    +10%
                                </button>
                                <button type="button" class="credit-chip mr-1 mb-1" @click="aplicarMargenCredito(15)" title="+15% sobre contado">
                                    +15%
                                </button>
                                <button type="button" class="credit-chip mr-1 mb-1" @click="aplicarMargenCredito(20)" title="+20% sobre contado">
                                    +20%
                                </button>
                                <button type="button" class="credit-chip mb-1" @click="aplicarMargenCredito(30)" title="+30% sobre contado">
                                    +30%
                                </button>
                            </div>
                            <small class="text-muted d-block mt-0.5" v-if="form.precio_credito > form.precio">
                                Recargo: +Gs. @{{ format(form.precio_credito - form.precio) }} (+@{{ porcentajeRecargoCredito }}%)
                            </small>
                        </div>
                    </div>

                    <!-- Fila de Herramientas de Redondeo -->
                    <div class="p-2 px-3 rounded-lg border mb-3 d-flex flex-wrap align-items-center justify-content-between gap-2" style="background: var(--dash-card-bg); border-radius: 8px;">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-wand-magic-sparkles text-info mr-2"></i>
                            <span class="small font-weight-bold" style="color: var(--dash-text-main);">Redondear a múltiplos de:</span>
                            <div class="ml-2" style="width: 120px;">
                                <select class="form-control form-control-sm font-weight-bold" v-model.number="form.multiplo" style="border-radius: 6px;">
                                    <option :value="100">100 Gs.</option>
                                    <option :value="500">500 Gs.</option>
                                    <option :value="1000">1.000 Gs.</option>
                                    <option :value="5000">5.000 Gs.</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-outline-success btn-sm font-weight-bold mr-1" @click="aplicarRedondeo('contado')" title="Redondear precio contado al múltiplo">
                                <i class="fa fa-magic mr-1"></i> Redondear Contado
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm font-weight-bold" @click="aplicarRedondeo('credito')" :disabled="!form.precio_credito" title="Redondear precio crédito al múltiplo">
                                <i class="fa fa-magic mr-1"></i> Redondear Crédito
                            </button>
                        </div>
                    </div>

                    <!-- Banner de Ahorro Destacado -->
                    <div class="savings-banner mb-3" v-if="ahorro > 0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="mr-2" style="width: 32px; height: 32px; border-radius: 50%; background: #10b981; color: #fff; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-arrow-down"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold" style="color: #065f46;">Descuento para el cliente</div>
                                    <small class="text-muted">El comprador ahorra Gs. @{{ format(ahorro) }} respecto al precio individual</small>
                                </div>
                            </div>
                            <span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 0.95rem;">
                                -@{{ porcentajeAhorro }}% OFF
                            </span>
                        </div>
                    </div>

                    <!-- Switch de Estado Activo / Visible -->
                    <div class="combo-switch-card mb-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="comboActivo" v-model="form.activo">
                            <label class="custom-control-label font-weight-bold" for="comboActivo">
                                Combo activo y disponible para la venta en caja / POS
                            </label>
                        </div>
                        <span class="badge" :class="form.activo ? 'badge-stock-in' : 'badge-stock-out'" style="font-size: 0.78rem;">
                            <i :class="form.activo ? 'fa-solid fa-circle-check mr-1' : 'fa-solid fa-circle-pause mr-1'"></i>
                            @{{ form.activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>

                    <!-- Observaciones -->
                    <div class="mb-0">
                        <label class="small font-weight-bold text-muted mb-1">
                            <i class="fa-solid fa-comment-dots mr-1"></i> OBSERVACIONES / NOTAS
                        </label>
                        <input type="text"
                               class="form-control"
                               v-model.trim="form.observacion"
                               placeholder="Opcional: aclaraciones internas o condiciones..."
                               style="border-radius: 8px;">
                    </div>
                </div>

                <!-- Footer del Editor -->
                <div class="d-flex align-items-center justify-content-between p-3 border-top" style="background: var(--dash-card-bg);">
                    <button type="button" class="btn-pos-secondary" @click="nuevo">
                        <i class="fa fa-broom mr-1"></i> Limpiar Formulario
                    </button>
                    <button type="button" class="btn-pos-primary" @click="guardar" :disabled="guardando">
                        <span class="fa" :class="guardando ? 'fa-spinner fa-spin' : 'fa-floppy-disk'"></span>
                        @{{ form.id ? 'Actualizar Combo #' + form.id : 'Guardar Nuevo Combo' }}
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
        timer: 2500
    });

    var app = new Vue({
        el: '#app',
        data: {
            combos: @json($combos),
            filtroCombos: '',
            buscar: '',
            buscando: false,
            resultados: [],
            buscarTimer: null,
            guardando: false,
            precioEditado: false,
            codigoEstado: '',
            codigoMensaje: '',
            form: {
                id: null,
                nombre: '',
                codigo: '',
                precio: 0,
                precio_credito: 0,
                multiplo: 500,
                activo: true,
                observacion: '',
                items: []
            }
        },
        computed: {
            precioLista: function () {
                var total = 0;
                this.form.items.forEach(function (it) {
                    total += (Number(it.cantidad) || 0) * (Number(it.precio_ref) || 0);
                });
                return total;
            },
            ahorro: function () {
                return Math.max(0, this.precioLista - (Number(this.form.precio) || 0));
            },
            porcentajeAhorro: function () {
                if (this.precioLista > 0 && this.ahorro > 0) {
                    return Math.round((this.ahorro / this.precioLista) * 100);
                }
                return 0;
            },
            porcentajeRecargoCredito: function () {
                var cont = Number(this.form.precio) || 0;
                var cred = Number(this.form.precio_credito) || 0;
                if (cont > 0 && cred > cont) {
                    return Math.round(((cred - cont) / cont) * 100);
                }
                return 0;
            },
            combosFiltrados: function () {
                if (!this.filtroCombos) return this.combos;
                var q = this.filtroCombos.toLowerCase().trim();
                return this.combos.filter(function (c) {
                    var nom = (c.nombre || '').toLowerCase();
                    var cod = (c.codigo || '').toLowerCase();
                    return nom.includes(q) || cod.includes(q);
                });
            },
            totalCombosActivos: function () {
                return this.combos.filter(function (c) { return Number(c.activo) === 1; }).length;
            },
            totalCombosInactivos: function () {
                return this.combos.filter(function (c) { return Number(c.activo) !== 1; }).length;
            },
            totalItemsEnCombos: function () {
                var total = 0;
                this.combos.forEach(function (c) {
                    total += (c.items || []).length;
                });
                return total;
            }
        },
        methods: {
            format: function (n) {
                return new Intl.NumberFormat('de-DE').format(Number(n) || 0);
            },
            blankForm: function () {
                return {
                    id: null,
                    nombre: '',
                    codigo: '',
                    precio: 0,
                    precio_credito: 0,
                    multiplo: 500,
                    activo: true,
                    observacion: '',
                    items: []
                };
            },
            nuevo: function () {
                this.form = this.blankForm();
                this.precioEditado = false;
                this.resultados = [];
                this.buscar = '';
                this.codigoEstado = '';
                this.codigoMensaje = '';
            },
            editar: function (c) {
                this.form = {
                    id: c.id,
                    nombre: c.nombre,
                    codigo: c.codigo || '',
                    precio: Number(c.precio) || 0,
                    precio_credito: Number(c.precio_credito) || 0,
                    multiplo: 500,
                    activo: Number(c.activo) === 1,
                    observacion: c.observacion || '',
                    items: (c.items || []).map(function (it) {
                        var art = it.articulo || {};
                        return {
                            articulos_cod: it.articulos_cod,
                            nombre: art.producto_nombre || ('#' + it.articulos_cod),
                            codigo: art.producto_c_barra || '',
                            cantidad: Number(it.cantidad) || 1,
                            precio_ref: Number(it.precio_ref) || Number(art.pre_venta1) || 0
                        };
                    })
                };
                this.precioEditado = Number(c.precio) !== Number(c.precio_lista);
                this.resultados = [];
                this.codigoEstado = '';
                this.codigoMensaje = '';
            },
            validarCodigo: function () {
                var self = this;
                var codigo = (this.form.codigo || '').trim();
                if (!codigo) {
                    this.codigoEstado = 'error';
                    this.codigoMensaje = 'El código de barras es obligatorio.';
                    return Promise.resolve(false);
                }
                return axios.get('{{ url('combo/validar-codigo') }}', {
                    params: { codigo: codigo, id: this.form.id || null }
                }).then(function (r) {
                    var d = r.data || {};
                    self.codigoEstado = d.ok ? 'ok' : 'error';
                    self.codigoMensaje = d.message || '';
                    return !!d.ok;
                }).catch(function () {
                    self.codigoEstado = 'error';
                    self.codigoMensaje = 'No se pudo validar el código.';
                    return false;
                });
            },
            onBuscarInput: function () {
                var self = this;
                if (this.buscarTimer) clearTimeout(this.buscarTimer);
                this.buscarTimer = setTimeout(function () {
                    if ((self.buscar || '').trim().length >= 2) {
                        self.buscarArticulos();
                    } else {
                        self.resultados = [];
                    }
                }, 300);
            },
            buscarArticulos: function () {
                var self = this;
                this.buscando = true;
                axios.get('{{ url('combo/articulos') }}', { params: { buscar: this.buscar } })
                    .then(function (r) {
                        self.buscando = false;
                        self.resultados = r.data || [];
                    })
                    .catch(function () {
                        self.buscando = false;
                        self.resultados = [];
                    });
            },
            agregarItem: function (a) {
                var cod = a.ARTICULOS_cod || a.articulos_cod;
                var exists = this.form.items.findIndex(function (x) { return String(x.articulos_cod) === String(cod); });
                if (exists !== -1) {
                    this.form.items[exists].cantidad = Number(this.form.items[exists].cantidad) + 1;
                } else {
                    this.form.items.push({
                        articulos_cod: cod,
                        nombre: a.producto_nombre,
                        codigo: a.producto_c_barra || '',
                        cantidad: 1,
                        precio_ref: Number(a.pre_venta1) || 0
                    });
                }
                this.resultados = [];
                this.buscar = '';
                this.recalcular();
            },
            quitarItem: function (idx) {
                this.form.items.splice(idx, 1);
                this.recalcular();
            },
            usarSuma: function () {
                this.precioEditado = false;
                this.form.precio = this.precioLista;
            },
            recalcular: function () {
                if (!this.precioEditado) {
                    this.form.precio = this.precioLista;
                }
            },
            aplicarMargenCredito: function (pct) {
                var base = Number(this.form.precio) || this.precioLista || 0;
                if (base <= 0) return;
                if (pct === 0) {
                    this.form.precio_credito = base;
                    return;
                }
                var mult = Number(this.form.multiplo) || 1000;
                var calculado = base * (1 + pct / 100);
                this.form.precio_credito = Math.round(calculado / mult) * mult;
            },
            aplicarRedondeo: function (tipo) {
                var multiplo = Number(this.form.multiplo) || 500;
                if (tipo === 'credito') {
                    var cred = Number(this.form.precio_credito) || 0;
                    if (cred > 0) {
                        this.form.precio_credito = Math.round(cred / multiplo) * multiplo;
                    }
                } else {
                    var base = Number(this.form.precio) || this.precioLista;
                    this.form.precio = Math.round(base / multiplo) * multiplo;
                    if (this.form.precio <= 0 && base > 0) {
                        this.form.precio = multiplo;
                    }
                    this.precioEditado = true;
                }
            },
            payload: function () {
                return {
                    nombre: this.form.nombre,
                    codigo: this.form.codigo,
                    precio: this.form.precio,
                    precio_credito: Number(this.form.precio_credito) || 0,
                    multiplo: this.form.multiplo,
                    activo: this.form.activo ? 1 : 0,
                    observacion: this.form.observacion,
                    items: this.form.items.map(function (it) {
                        return {
                            articulos_cod: it.articulos_cod,
                            cantidad: it.cantidad,
                            precio_ref: it.precio_ref
                        };
                    })
                };
            },
            guardar: function () {
                var self = this;
                if (!this.form.nombre) {
                    Swal.fire('Falta nombre', 'Indicá el nombre del combo.', 'warning');
                    return;
                }
                if (!(this.form.codigo || '').trim()) {
                    Swal.fire('Falta código', 'Indicá el código de barras del combo.', 'warning');
                    return;
                }
                if (this.form.items.length < 2) {
                    Swal.fire('Faltan artículos', 'Seleccioná al menos 2 artículos para armar el pack.', 'warning');
                    return;
                }
                if (!(this.form.precio > 0)) {
                    Swal.fire('Falta precio', 'Definí el precio del combo.', 'warning');
                    return;
                }
                this.guardando = true;
                this.validarCodigo().then(function (ok) {
                    if (!ok) {
                        self.guardando = false;
                        Swal.fire('Código inválido', self.codigoMensaje || 'Revisá el código de barras.', 'warning');
                        return;
                    }
                    var req = self.form.id
                        ? axios.put('{{ url('combo') }}/' + self.form.id, self.payload())
                        : axios.post('{{ url('combo') }}', self.payload());

                    req.then(function (r) {
                        self.guardando = false;
                        Toast.fire({ icon: 'success', title: r.data.message || 'Guardado exitosamente' });
                        window.location.reload();
                    }).catch(function (err) {
                        self.guardando = false;
                        var msg = (err.response && err.response.data && err.response.data.message)
                            ? err.response.data.message
                            : 'No se pudo guardar el combo';
                        Swal.fire('Error', msg, 'error');
                    });
                });
            },
            eliminar: function (c) {
                var self = this;
                Swal.fire({
                    title: '¿Eliminar este combo?',
                    text: c.nombre,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#dc2626'
                }).then(function (res) {
                    if (!res.value) return;
                    axios.delete('{{ url('combo') }}/' + c.id)
                        .then(function () {
                            Toast.fire({ icon: 'success', title: 'Combo eliminado' });
                            window.location.reload();
                        })
                        .catch(function () {
                            Swal.fire('Error', 'No se pudo eliminar el combo', 'error');
                        });
                });
            }
        },
        mounted: function () {
            activarMenu('m_combo', '');
        }
    });
</script>
@endsection
