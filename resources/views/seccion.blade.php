@extends('layouts.app')
@section('title', 'Gestión de Secciones')

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
        padding: 0.55rem 1.25rem;
        background: var(--dash-primary);
        border: 1px solid var(--dash-primary);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.9rem;
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
    .btn-pos-primary:disabled {
        opacity: 0.65;
        cursor: not-allowed;
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
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 0.9rem;
        margin-bottom: 1.25rem;
    }
    .kpi-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        padding: 0.9rem 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.85rem;
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
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .kpi-icon-green  { background: #dcfce7; color: #166534; }
    .kpi-icon-blue   { background: #e0f2fe; color: #0284c7; }
    .kpi-icon-purple { background: #f3e8ff; color: #7e22ce; }
    .kpi-icon-amber  { background: #fef3c7; color: #b45309; }
    .kpi-icon-teal   { background: #ccfbf1; color: #0f766e; }

    body.dark-mode .kpi-icon-green  { background: #064e3b; color: #a7f3d0; }
    body.dark-mode .kpi-icon-blue   { background: #0c4a6e; color: #bae6fd; }
    body.dark-mode .kpi-icon-purple { background: #3b0764; color: #e9d5ff; }
    body.dark-mode .kpi-icon-amber  { background: #451a03; color: #fde68a; }
    body.dark-mode .kpi-icon-teal   { background: #134e4a; color: #99f6e4; }

    .kpi-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--dash-text-muted);
        font-weight: 700;
        margin-bottom: 0.1rem;
    }
    .kpi-val {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--dash-text-main);
        line-height: 1.1;
        margin: 0;
    }
    .kpi-sub {
        font-size: 0.74rem;
        color: var(--dash-text-muted);
        margin-top: 0.15rem;
    }

    /* Quick Add Section Card */
    .quick-add-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        padding: 1.15rem 1.35rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
    }
    .quick-add-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    .quick-add-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--dash-text-main);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0;
    }

    /* IVA Selection Pills */
    .iva-pill-group {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .iva-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 0.85rem;
        border-radius: 10px;
        border: 1.5px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-muted);
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
        margin-bottom: 0;
        font-size: 0.86rem;
        font-weight: 600;
    }
    .iva-pill:hover {
        border-color: var(--dash-primary);
        color: var(--dash-text-main);
    }
    .iva-pill.active {
        border-color: var(--dash-primary);
        background: var(--dash-primary-light);
        color: var(--dash-primary);
        font-weight: 700;
        box-shadow: 0 2px 6px rgba(10, 77, 54, 0.12);
    }
    body.dark-mode .iva-pill.active {
        background: rgba(16, 185, 129, 0.18);
        border-color: #10b981;
        color: #34d399;
    }
    .iva-pill.active.pill-iva-10 {
        border-color: #10b981;
        color: #065f46;
        background: #ecfdf5;
    }
    body.dark-mode .iva-pill.active.pill-iva-10 {
        background: #064e3b;
        color: #6ee7b7;
        border-color: #10b981;
    }
    .iva-pill.active.pill-iva-5 {
        border-color: #0284c7;
        color: #075985;
        background: #f0f9ff;
    }
    body.dark-mode .iva-pill.active.pill-iva-5 {
        background: #0c4a6e;
        color: #7dd3fc;
        border-color: #38bdf8;
    }
    .iva-pill.active.pill-iva-0 {
        border-color: #f59e0b;
        color: #92400e;
        background: #fffbeb;
    }
    body.dark-mode .iva-pill.active.pill-iva-0 {
        background: #451a03;
        color: #fcd34d;
        border-color: #fbbf24;
    }

    .iva-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        display: inline-block;
    }
    .iva-dot-10 { background: #10b981; }
    .iva-dot-5  { background: #0284c7; }
    .iva-dot-0  { background: #f59e0b; }

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
        min-width: 220px;
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
        min-width: 160px;
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

    /* Avatar & Badges */
    .section-avatar {
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
    body.dark-mode .section-avatar {
        background: rgba(16, 185, 129, 0.2);
        color: #34d399;
    }

    .badge-id {
        font-family: 'SFMono-Regular', Consolas, Menlo, Courier, monospace;
        background: var(--dash-panel-bg);
        color: var(--dash-text-muted);
        border: 1px solid var(--dash-border);
        border-radius: 6px;
        padding: 2px 7px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .badge-iva {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.28rem 0.65rem;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .badge-iva-10 {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    body.dark-mode .badge-iva-10 {
        background: #064e3b;
        color: #a7f3d0;
        border-color: #047857;
    }
    .badge-iva-5 {
        background: #f0f9ff;
        color: #075985;
        border: 1px solid #bae6fd;
    }
    body.dark-mode .badge-iva-5 {
        background: #0c4a6e;
        color: #bae6fd;
        border-color: #0369a1;
    }
    .badge-iva-0 {
        background: #fffbeb;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    body.dark-mode .badge-iva-0 {
        background: #451a03;
        color: #fde68a;
        border-color: #78350f;
    }

    .badge-articles {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        color: var(--dash-text-main);
        padding: 0.28rem 0.65rem;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 700;
    }
    .badge-articles.has-articles {
        background: var(--dash-primary-light);
        color: var(--dash-primary);
        border-color: var(--dash-primary-border);
    }
    body.dark-mode .badge-articles.has-articles {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
        border-color: #047857;
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
    .pos-input {
        width: 100%;
        padding: 0.55rem 0.85rem;
        border-radius: 10px;
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        font-size: 0.9rem;
        transition: all 0.15s ease;
    }
    .pos-input:focus {
        outline: none;
        border-color: var(--dash-primary);
        background: var(--dash-card-bg);
        box-shadow: 0 0 0 3px var(--dash-primary-light);
    }
    body.dark-mode .pos-input:focus {
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }

    .info-box-notice {
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        border-left: 4px solid var(--dash-info);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.83rem;
        color: var(--dash-text-muted);
    }
    body.dark-mode .info-box-notice {
        background: #0f172a;
    }
</style>
@endsection

@section('main')
<div class="container-fluid" id="app" v-cloak>

    <!-- DASHBOARD HEADER -->
    <div class="dash-header">
        <div class="dash-header-left">
            <div class="dash-header-icon">
                <i class="fa fa-layer-group"></i>
            </div>
            <div>
                <h4 class="dash-header-title font-cairo">Gestión de Secciones</h4>
                <p class="dash-header-subtitle">Clasificación de productos, tasas impositivas (IVA) y conteo de artículos vinculados</p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn-pos-secondary" @click="recargarSecciones" :disabled="cargando" title="Recargar lista">
                <i class="fa fa-sync-alt" :class="{ 'fa-spin': cargando }"></i>
                <span>Actualizar</span>
            </button>
            <button class="btn-pos-primary font-cairo" @click="enfocarNuevaSeccion">
                <i class="fa fa-plus-circle"></i>
                <span>Nueva Sección</span>
            </button>
        </div>
    </div>

    <!-- KPI SUMMARY CARDS -->
    <div class="kpi-row">
        <!-- KPI 1: Total Secciones -->
        <div class="kpi-card">
            <div class="kpi-icon-box kpi-icon-green">
                <i class="fa fa-tags"></i>
            </div>
            <div>
                <div class="kpi-label">Total Secciones</div>
                <div class="kpi-val font-cairo">@{{ secciones.length }}</div>
                <div class="kpi-sub">Categorías registradas</div>
            </div>
        </div>

        <!-- KPI 2: IVA 10% -->
        <div class="kpi-card">
            <div class="kpi-icon-box kpi-icon-teal">
                <i class="fa fa-percentage"></i>
            </div>
            <div>
                <div class="kpi-label">Con IVA 10%</div>
                <div class="kpi-val font-cairo">@{{ countIva10 }}</div>
                <div class="kpi-sub">Tasa estándar general</div>
            </div>
        </div>

        <!-- KPI 3: IVA 5% -->
        <div class="kpi-card">
            <div class="kpi-icon-box kpi-icon-blue">
                <i class="fa fa-percent"></i>
            </div>
            <div>
                <div class="kpi-label">Con IVA 5%</div>
                <div class="kpi-val font-cairo">@{{ countIva5 }}</div>
                <div class="kpi-sub">Tasa reducida / canasta</div>
            </div>
        </div>

        <!-- KPI 4: Exentas -->
        <div class="kpi-card">
            <div class="kpi-icon-box kpi-icon-amber">
                <i class="fa fa-shield-alt"></i>
            </div>
            <div>
                <div class="kpi-label">Exentas (0%)</div>
                <div class="kpi-val font-cairo">@{{ countExentas }}</div>
                <div class="kpi-sub">Sin impacto impositivo</div>
            </div>
        </div>

        <!-- KPI 5: Total Artículos -->
        <div class="kpi-card">
            <div class="kpi-icon-box kpi-icon-purple">
                <i class="fa fa-boxes"></i>
            </div>
            <div>
                <div class="kpi-label">Artículos Asignados</div>
                <div class="kpi-val font-cairo">@{{ totalArticulos }}</div>
                <div class="kpi-sub">En catálogo general</div>
            </div>
        </div>
    </div>

    <!-- QUICK ADD CARD -->
    <div class="quick-add-card" ref="cardNuevaSeccion">
        <div class="quick-add-header">
            <h5 class="quick-add-title font-cairo">
                <i class="fa fa-plus-circle text-success"></i>
                <span>Crear Nueva Sección</span>
            </h5>
            <small class="text-muted">Presioná <b>Enter</b> para guardar rápidamente</small>
        </div>
        <div class="row align-items-end">
            <!-- Nombre / Descripción -->
            <div class="col-md-5 col-sm-12 mb-3 mb-md-0">
                <label class="pos-label">
                    <span>Nombre de la Sección / Categoría</span>
                    <span class="req">*</span>
                </label>
                <div class="position-relative">
                    <input type="text"
                           class="pos-input"
                           ref="inputDescripcion"
                           v-model="form.descripcion"
                           placeholder="Ej: BEBIDAS, LACTEOS, LIMPIEZA, GOLOSINAS..."
                           maxlength="100"
                           @keyup.enter="guardarSeccion">
                </div>
            </div>

            <!-- Selector de Impuesto (IVA) -->
            <div class="col-md-4 col-sm-12 mb-3 mb-md-0">
                <label class="pos-label">
                    <span>Impuesto Asignado (IVA)</span>
                    <span class="req">*</span>
                </label>
                <div class="iva-pill-group">
                    <label class="iva-pill pill-iva-10" :class="{ 'active': form.iva == 10 }" title="IVA 10% General">
                        <input type="radio" value="10" v-model="form.iva" class="d-none">
                        <span class="iva-dot iva-dot-10"></span>
                        <span>IVA 10%</span>
                    </label>
                    <label class="iva-pill pill-iva-5" :class="{ 'active': form.iva == 5 }" title="IVA 5% Reducido">
                        <input type="radio" value="5" v-model="form.iva" class="d-none">
                        <span class="iva-dot iva-dot-5"></span>
                        <span>IVA 5%</span>
                    </label>
                    <label class="iva-pill pill-iva-0" :class="{ 'active': form.iva == 0 }" title="Exenta de IVA">
                        <input type="radio" value="0" v-model="form.iva" class="d-none">
                        <span class="iva-dot iva-dot-0"></span>
                        <span>Exenta (0%)</span>
                    </label>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="col-md-3 col-sm-12 d-flex gap-2">
                <button class="btn-pos-primary flex-grow-1 font-cairo"
                        :disabled="guardando || !form.descripcion.trim()"
                        @click="guardarSeccion">
                    <i class="fa fa-spinner fa-spin" v-if="guardando"></i>
                    <i class="fa fa-save" v-else></i>
                    <span>@{{ guardando ? 'Guardando...' : 'Guardar Sección' }}</span>
                </button>
                <button class="btn-pos-secondary"
                        v-if="form.descripcion.trim()"
                        @click="limpiarFormulario"
                        title="Limpiar formulario">
                    <i class="fa fa-times"></i>
                </button>
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
                    <input type="text"
                           v-model="filtroTexto"
                           placeholder="Buscar sección por nombre o código #..."
                           autocomplete="off">
                    <button v-if="filtroTexto" class="search-clear-btn" @click="filtroTexto = ''" title="Limpiar búsqueda">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                <!-- IVA filter -->
                <select class="filter-select" v-model="filtroIva">
                    <option value="">Todos los impuestos</option>
                    <option value="10">Sólo IVA 10%</option>
                    <option value="5">Sólo IVA 5%</option>
                    <option value="0">Sólo Exentas</option>
                </select>

                <!-- Articles count filter -->
                <select class="filter-select" v-model="filtroArticulos">
                    <option value="">Todos los artículos</option>
                    <option value="con">Con artículos vinculados</option>
                    <option value="sin">Sin artículos (vacías)</option>
                </select>

                <!-- Order by -->
                <select class="filter-select" v-model="ordenarPor">
                    <option value="nombre_asc">Nombre (A - Z)</option>
                    <option value="nombre_desc">Nombre (Z - A)</option>
                    <option value="articulos_desc">Más artículos primero</option>
                    <option value="id_asc">Código (Ascendente)</option>
                    <option value="id_desc">Código (Descendente)</option>
                </select>

                <!-- Reset button if filters active -->
                <button v-if="filtroTexto || filtroIva !== '' || filtroArticulos !== '' || ordenarPor !== 'nombre_asc'"
                        class="btn-pos-secondary"
                        @click="resetFiltros"
                        title="Restablecer filtros">
                    <i class="fa fa-undo"></i>
                    <span>Limpiar</span>
                </button>
            </div>

            <!-- Items per page & info -->
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted small">Mostrar:</span>
                <select class="filter-select py-1 px-2" style="min-width: 80px; width: 80px;" v-model.number="perPage">
                    <option :value="10">10</option>
                    <option :value="25">25</option>
                    <option :value="50">50</option>
                    <option :value="0">Todas</option>
                </select>
                <span class="text-muted small ml-1">
                    (@{{ filteredSecciones.length }} resultado@{{ filteredSecciones.length === 1 ? '' : 's' }})
                </span>
            </div>
        </div>
    </div>

    <!-- MAIN SECTIONS TABLE CARD -->
    <div class="card-table">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 70px;" class="text-center">Código</th>
                        <th>Sección / Categoría</th>
                        <th style="width: 170px;" class="text-center">Tasa Impuesto (IVA)</th>
                        <th style="width: 180px;" class="text-center">Artículos Vinculados</th>
                        <th style="width: 110px;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="pagedSecciones.length === 0">
                        <td colspan="5" class="text-center py-5">
                            <i class="fa fa-layer-group fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                            <div class="font-weight-bold text-muted">No se encontraron secciones</div>
                            <small class="text-muted" v-if="filtroTexto || filtroIva !== '' || filtroArticulos !== ''">
                                Intentá cambiar el término de búsqueda o restablecer los filtros aplicados.
                            </small>
                            <small class="text-muted" v-else>
                                Aún no hay secciones registradas. Usá el formulario superior para registrar la primera.
                            </small>
                        </td>
                    </tr>
                    <tr v-for="s in pagedSecciones" :key="s.present_cod">
                        <!-- Código ID -->
                        <td class="text-center">
                            <span class="badge-id">#@{{ s.present_cod }}</span>
                        </td>

                        <!-- Nombre / Avatar -->
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="section-avatar font-cairo">
                                    @{{ getInitials(s.present_descripcion) }}
                                </div>
                                <div>
                                    <div class="font-weight-bold text-dark dark:text-white" style="font-size: 0.95rem;">
                                        @{{ (s.present_descripcion || '').trim() }}
                                    </div>
                                    <small class="text-muted">Presentación de artículos</small>
                                </div>
                            </div>
                        </td>

                        <!-- Tasa de IVA -->
                        <td class="text-center">
                            <span v-if="parseInt(s.iva) === 10" class="badge-iva badge-iva-10">
                                <span class="iva-dot iva-dot-10"></span>
                                <span>IVA 10% (General)</span>
                            </span>
                            <span v-else-if="parseInt(s.iva) === 5" class="badge-iva badge-iva-5">
                                <span class="iva-dot iva-dot-5"></span>
                                <span>IVA 5% (Reducido)</span>
                            </span>
                            <span v-else class="badge-iva badge-iva-0">
                                <span class="iva-dot iva-dot-0"></span>
                                <span>Exenta (0%)</span>
                            </span>
                        </td>

                        <!-- Artículos Asignados -->
                        <td class="text-center">
                            <span class="badge-articles" :class="{ 'has-articles': (parseInt(s.articulos_count) || 0) > 0 }">
                                <i class="fa fa-box-open" :class="(parseInt(s.articulos_count) || 0) > 0 ? 'text-success' : 'text-muted'"></i>
                                <span>@{{ parseInt(s.articulos_count) || 0 }} artículo@{{ (parseInt(s.articulos_count) || 0) === 1 ? '' : 's' }}</span>
                            </span>
                        </td>

                        <!-- Acciones -->
                        <td class="text-center">
                            <button class="btn-action-icon"
                                    @click="showEditar(s)"
                                    title="Editar sección">
                                <i class="fa fa-edit text-primary"></i>
                            </button>
                            <button class="btn-action-icon btn-action-delete"
                                    @click="eliminarSeccion(s)"
                                    :title="(parseInt(s.articulos_count) || 0) > 0 ? 'Sección con artículos vinculados' : 'Eliminar sección'">
                                <i class="fa fa-trash-alt text-danger"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- TABLE PAGINATION FOOTER -->
        <div class="table-pagination-footer" v-if="filteredSecciones.length > perPage && perPage > 0">
            <div class="text-muted small">
                Mostrando @{{ ((currentPage - 1) * perPage) + 1 }} a @{{ Math.min(currentPage * perPage, filteredSecciones.length) }} de @{{ filteredSecciones.length }} secciones
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

    <!-- MODAL EDITAR SECCIÓN -->
    <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-custom">
                <!-- Modal Header -->
                <div class="modal-header-custom">
                    <h5 class="modal-title-custom font-cairo">
                        <i class="fa fa-edit"></i>
                        <span>Editar Sección</span>
                        <span class="badge-id ml-2">#@{{ editForm.codigo }}</span>
                    </h5>
                    <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body p-4">
                    <!-- Descripción -->
                    <div class="form-group mb-3">
                        <label class="pos-label">
                            <span>Descripción / Nombre</span>
                            <span class="req">*</span>
                        </label>
                        <input type="text"
                               class="pos-input"
                               ref="inputEditDescripcion"
                               v-model="editForm.descripcion"
                               placeholder="Nombre de la sección"
                               maxlength="100"
                               @keyup.enter="guardarEdicion">
                    </div>

                    <!-- Impuesto (IVA) -->
                    <div class="form-group mb-3">
                        <label class="pos-label">
                            <span>Impuesto Asignado (IVA)</span>
                            <span class="req">*</span>
                        </label>
                        <div class="iva-pill-group">
                            <label class="iva-pill pill-iva-10" :class="{ 'active': editForm.iva == 10 }">
                                <input type="radio" value="10" v-model="editForm.iva" class="d-none">
                                <span class="iva-dot iva-dot-10"></span>
                                <span>IVA 10% (General)</span>
                            </label>
                            <label class="iva-pill pill-iva-5" :class="{ 'active': editForm.iva == 5 }">
                                <input type="radio" value="5" v-model="editForm.iva" class="d-none">
                                <span class="iva-dot iva-dot-5"></span>
                                <span>IVA 5% (Reducido)</span>
                            </label>
                            <label class="iva-pill pill-iva-0" :class="{ 'active': editForm.iva == 0 }">
                                <input type="radio" value="0" v-model="editForm.iva" class="d-none">
                                <span class="iva-dot iva-dot-0"></span>
                                <span>Exenta (0%)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Notice / Linked Articles info -->
                    <div class="info-box-notice mt-3">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="fa fa-info-circle text-info"></i>
                            <strong class="text-dark dark:text-white">Artículos vinculados:</strong>
                            <span class="badge-articles ml-1" :class="{ 'has-articles': editForm.articulos_count > 0 }">
                                @{{ editForm.articulos_count }} artículo@{{ editForm.articulos_count === 1 ? '' : 's' }}
                            </span>
                        </div>
                        <div>
                            Los artículos actualmente asignados a esta sección reflejarán la actualización de nombre de manera inmediata en el catálogo y facturación.
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn-pos-secondary" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button"
                            class="btn-pos-primary font-cairo"
                            :disabled="actualizando || !editForm.descripcion.trim()"
                            @click="guardarEdicion">
                        <i class="fa fa-spinner fa-spin" v-if="actualizando"></i>
                        <i class="fa fa-check" v-else></i>
                        <span>@{{ actualizando ? 'Guardando...' : 'Guardar Cambios' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
<script type="text/javascript">
    var app = new Vue({
        el: '#app',
        data: {
            secciones: {!! json_encode($secciones) !!},
            cargando: false,
            guardando: false,
            actualizando: false,

            // New Section Form
            form: {
                descripcion: '',
                iva: 10
            },

            // Edit Section Form
            editForm: {
                codigo: null,
                descripcion: '',
                iva: 10,
                articulos_count: 0
            },

            // Filters & Pagination
            filtroTexto: '',
            filtroIva: '',
            filtroArticulos: '',
            ordenarPor: 'nombre_asc',
            currentPage: 1,
            perPage: 10
        },
        computed: {
            countIva10: function() {
                return this.secciones.filter(function(s) {
                    return parseInt(s.iva) === 10;
                }).length;
            },
            countIva5: function() {
                return this.secciones.filter(function(s) {
                    return parseInt(s.iva) === 5;
                }).length;
            },
            countExentas: function() {
                return this.secciones.filter(function(s) {
                    return parseInt(s.iva) === 0;
                }).length;
            },
            totalArticulos: function() {
                return this.secciones.reduce(function(acc, s) {
                    return acc + (parseInt(s.articulos_count) || 0);
                }, 0);
            },
            filteredSecciones: function() {
                var self = this;
                var list = this.secciones.slice();

                // Search query
                if (this.filtroTexto && this.filtroTexto.trim() !== '') {
                    var q = this.filtroTexto.trim().toLowerCase();
                    list = list.filter(function(s) {
                        var desc = (s.present_descripcion || '').toLowerCase();
                        var cod = (s.present_cod || '').toString();
                        return desc.indexOf(q) !== -1 || cod.indexOf(q) !== -1;
                    });
                }

                // IVA filter
                if (this.filtroIva !== '') {
                    var ivaVal = parseInt(this.filtroIva);
                    list = list.filter(function(s) {
                        return parseInt(s.iva) === ivaVal;
                    });
                }

                // Articles filter
                if (this.filtroArticulos === 'con') {
                    list = list.filter(function(s) {
                        return (parseInt(s.articulos_count) || 0) > 0;
                    });
                } else if (this.filtroArticulos === 'sin') {
                    list = list.filter(function(s) {
                        return (parseInt(s.articulos_count) || 0) === 0;
                    });
                }

                // Sorting
                list.sort(function(a, b) {
                    if (self.ordenarPor === 'nombre_asc') {
                        return (a.present_descripcion || '').localeCompare(b.present_descripcion || '');
                    } else if (self.ordenarPor === 'nombre_desc') {
                        return (b.present_descripcion || '').localeCompare(a.present_descripcion || '');
                    } else if (self.ordenarPor === 'articulos_desc') {
                        return (parseInt(b.articulos_count) || 0) - (parseInt(a.articulos_count) || 0);
                    } else if (self.ordenarPor === 'id_desc') {
                        return b.present_cod - a.present_cod;
                    } else {
                        return a.present_cod - b.present_cod;
                    }
                });

                return list;
            },
            totalPages: function() {
                if (this.perPage === 0) return 1;
                return Math.ceil(this.filteredSecciones.length / this.perPage) || 1;
            },
            pagedSecciones: function() {
                if (this.perPage === 0) return this.filteredSecciones;
                var start = (this.currentPage - 1) * this.perPage;
                return this.filteredSecciones.slice(start, start + this.perPage);
            }
        },
        watch: {
            filtroTexto: function() { this.currentPage = 1; },
            filtroIva: function() { this.currentPage = 1; },
            filtroArticulos: function() { this.currentPage = 1; },
            perPage: function() { this.currentPage = 1; }
        },
        methods: {
            getInitials: function(nombre) {
                if (!nombre) return 'SC';
                var clean = nombre.trim();
                var parts = clean.split(/\s+/);
                if (parts.length >= 2) {
                    return (parts[0][0] + parts[1][0]).toUpperCase();
                }
                return clean.substring(0, 2).toUpperCase();
            },
            enfocarNuevaSeccion: function() {
                var el = this.$refs.inputDescripcion;
                if (el) {
                    el.focus();
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            },
            limpiarFormulario: function() {
                this.form.descripcion = '';
                this.form.iva = 10;
            },
            resetFiltros: function() {
                this.filtroTexto = '';
                this.filtroIva = '';
                this.filtroArticulos = '';
                this.ordenarPor = 'nombre_asc';
                this.currentPage = 1;
            },
            recargarSecciones: function() {
                var self = this;
                this.cargando = true;
                axios.get('seccion/all')
                    .then(function(res) {
                        self.secciones = res.data;
                        self.cargando = false;
                        var Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'Secciones actualizadas'
                        });
                    })
                    .catch(function(err) {
                        self.cargando = false;
                        console.error('Error al recargar:', err);
                    });
            },
            guardarSeccion: function() {
                var self = this;
                var desc = (this.form.descripcion || '').trim();
                if (!desc) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campo obligatorio',
                        text: 'Debe ingresar una descripción para la sección.',
                        confirmButtonColor: '#0a4d36'
                    });
                    if (this.$refs.inputDescripcion) this.$refs.inputDescripcion.focus();
                    return;
                }

                this.guardando = true;
                axios.post('seccion', {
                    descripcion: desc,
                    iva: this.form.iva
                })
                .then(function(res) {
                    self.guardando = false;
                    if (res.data && res.data.seccion) {
                        self.secciones.unshift(res.data.seccion);
                    } else {
                        self.recargarSecciones();
                    }

                    self.limpiarFormulario();
                    self.filtroTexto = '';

                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true
                    });
                    Toast.fire({
                        icon: 'success',
                        title: 'Sección creada correctamente'
                    });

                    self.$nextTick(function() {
                        if (self.$refs.inputDescripcion) self.$refs.inputDescripcion.focus();
                    });
                })
                .catch(function(err) {
                    self.guardando = false;
                    var msg = err.response && err.response.data && err.response.data.message
                        ? err.response.data.message
                        : 'Ocurrió un error al registrar la sección.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: msg,
                        confirmButtonColor: '#0a4d36'
                    });
                });
            },
            showEditar: function(seccion) {
                var self = this;
                this.editForm = {
                    codigo: seccion.present_cod,
                    descripcion: (seccion.present_descripcion || '').trim(),
                    iva: parseInt(seccion.iva) || 10,
                    articulos_count: parseInt(seccion.articulos_count) || 0
                };
                $('#modalEditar').modal('show');
                this.$nextTick(function() {
                    if (self.$refs.inputEditDescripcion) {
                        self.$refs.inputEditDescripcion.focus();
                    }
                });
            },
            guardarEdicion: function() {
                var self = this;
                var desc = (this.editForm.descripcion || '').trim();
                if (!desc) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campo obligatorio',
                        text: 'Debe ingresar una descripción para la sección.',
                        confirmButtonColor: '#0a4d36'
                    });
                    return;
                }

                this.actualizando = true;
                axios.post('seccion/' + this.editForm.codigo, {
                    descripcion: desc,
                    iva: this.editForm.iva
                })
                .then(function(res) {
                    self.actualizando = false;
                    $('#modalEditar').modal('hide');

                    // Actualizar reactivamente en array local sin reload
                    var idx = self.secciones.findIndex(function(s) {
                        return s.present_cod === self.editForm.codigo;
                    });
                    if (idx !== -1) {
                        var updated = Object.assign({}, self.secciones[idx], {
                            present_descripcion: desc.toUpperCase(),
                            iva: self.editForm.iva.toString()
                        });
                        self.$set(self.secciones, idx, updated);
                    }

                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true
                    });
                    Toast.fire({
                        icon: 'success',
                        title: 'Sección actualizada correctamente'
                    });
                })
                .catch(function(err) {
                    self.actualizando = false;
                    var msg = err.response && err.response.data && err.response.data.message
                        ? err.response.data.message
                        : 'Ocurrió un error al actualizar la sección.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: msg,
                        confirmButtonColor: '#0a4d36'
                    });
                });
            },
            eliminarSeccion: function(seccion) {
                var self = this;
                var artCount = parseInt(seccion.articulos_count) || 0;

                if (artCount > 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No se puede eliminar',
                        html: 'La sección <b>' + (seccion.present_descripcion || '').trim() + '</b> tiene <b>' + artCount + ' artículo(s)</b> asignado(s).<br><br><small class="text-muted">Por seguridad e integridad de datos, reasigne o desvincule los artículos antes de eliminarla.</small>',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#0a4d36'
                    });
                    return;
                }

                Swal.fire({
                    title: '¿Eliminar sección?',
                    html: '¿Está seguro de eliminar la sección <b>' + (seccion.present_descripcion || '').trim() + '</b>?<br><small class="text-danger">Esta acción no se puede deshacer.</small>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa fa-trash"></i> Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b'
                }).then(function(result) {
                    if (result.value) {
                        axios.delete('seccion/' + seccion.present_cod)
                            .then(function(res) {
                                self.secciones = self.secciones.filter(function(s) {
                                    return s.present_cod !== seccion.present_cod;
                                });

                                var Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 2500,
                                    timerProgressBar: true
                                });
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Sección eliminada con éxito'
                                });
                            })
                            .catch(function(err) {
                                var msg = err.response && err.response.data && err.response.data.message
                                    ? err.response.data.message
                                    : 'Error al eliminar la sección.';
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: msg,
                                    confirmButtonColor: '#0a4d36'
                                });
                            });
                    }
                });
            }
        }
    });

    activarMenu('m_mantenimiento', 'm_seccion');
</script>
@endsection