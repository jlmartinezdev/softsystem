@extends('layouts.app')
@section('title', 'Directorio de Clientes')
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

    #app {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    /* Page Header idéntica a Artículos */
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

    /* Botones POS idénticos a Artículos */
    .btn-pos-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.6rem 1.35rem;
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

    /* KPI Cards idénticas a Artículos */
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

    /* Buscador & Inputs Toolbar idénticos a Artículos */
    .table-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }
    .toolbar-card {
        overflow: visible !important;
    }
    .search-input-group {
        width: 100%;
        min-width: 0;
    }
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
        min-width: 0;
    }
    .search-input:focus {
        background-color: var(--dash-card-bg) !important;
        border-color: var(--dash-primary) !important;
        color: var(--dash-text-main) !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2) !important;
    }
    .search-clear-btn {
        border: 1px solid var(--dash-border);
        border-left: none;
        background-color: var(--dash-card-bg);
        color: var(--dash-text-muted);
        border-radius: 0 8px 8px 0;
        padding: 0.375rem 0.75rem;
        transition: all 0.15s;
        cursor: pointer;
    }
    .search-clear-btn:hover {
        color: #ef4444;
        background-color: var(--dash-card-bg);
    }
    .select-seccion {
        background-color: var(--dash-card-bg) !important;
        border-color: var(--dash-border) !important;
        color: var(--dash-text-main) !important;
        width: 100%;
        min-width: 0;
    }
    .select-seccion:focus {
        border-color: var(--dash-primary) !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2) !important;
    }
    .toolbar-actions-wrap {
        display: inline-flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-start;
        gap: 0.4rem;
    }
    @media (min-width: 576px) {
        .toolbar-actions-wrap {
            justify-content: flex-end;
        }
    }
    .toolbar-actions-wrap .btn-pos-secondary {
        white-space: nowrap;
        margin: 0;
    }

    /* Tabla Nativa Personalizada */
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
    .custom-select-perpage {
        width: auto !important;
        display: inline-block;
        border: 1px solid var(--dash-border);
        border-radius: 6px;
        font-size: 0.82rem;
        padding: 0.25rem 0.6rem;
        height: auto;
        color: var(--dash-text-main);
        background-color: #fff;
    }
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        min-height: 220px;
    }
    .table-responsive::-webkit-scrollbar {
        height: 7px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: var(--dash-card-bg);
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: var(--dash-border);
        border-radius: 4px;
    }
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: var(--dash-text-muted);
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
        user-select: none;
        white-space: nowrap;
        transition: background-color 0.15s, color 0.15s;
    }
    .table-custom thead th.sortable:hover {
        background: #f1f5f9;
        color: var(--dash-primary);
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

    /* Paginación Dashboard */
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
    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .page-nav-btn {
        display: inline-flex;
        align-items: center;
        padding: 0.38rem 0.75rem;
        border: 1px solid var(--dash-border);
        background: #ffffff;
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
        background: #ffffff;
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

    /* Badges */
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
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        font-size: 0.8rem;
        white-space: nowrap;
        display: inline-block;
    }

    /* Avatar Iniciales Cliente */
    .client-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--dash-primary-light);
        color: var(--dash-primary);
        font-weight: 700;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1px solid var(--dash-primary-border);
    }

    .product-title {
        font-size: 0.93rem;
        color: var(--dash-text-main);
    }

    /* Botón WhatsApp */
    .btn-wa {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 6px;
        background-color: #25d366;
        color: #ffffff !important;
        font-size: 0.8rem;
        text-decoration: none !important;
        transition: transform 0.15s;
    }
    .btn-wa:hover {
        transform: scale(1.1);
        color: #ffffff;
    }

    /* Botón de Acción en Fila */
    .btn-action-edit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--dash-primary-light);
        color: var(--dash-primary);
        border: 1px solid var(--dash-primary-border);
        border-radius: 6px 0 0 6px;
        padding: 0.35rem 0.65rem;
        font-size: 0.82rem;
        font-weight: 600;
        transition: all 0.15s;
    }
    .btn-action-edit:hover {
        background: var(--dash-primary);
        color: #ffffff;
    }
    .btn-action-dropdown {
        background: var(--dash-primary-light);
        color: var(--dash-primary);
        border: 1px solid var(--dash-primary-border);
        border-left: none;
        border-radius: 0 6px 6px 0;
        padding: 0.35rem 0.5rem;
        transition: all 0.15s;
    }
    .btn-action-dropdown:hover {
        background: var(--dash-primary);
        color: #ffffff;
    }

    /* Empty state */
    .empty-state-box {
        padding: 3rem 1rem;
    }
    .empty-state-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--dash-primary-light);
        color: var(--dash-primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 12px;
        border: 1px solid var(--dash-border);
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .form-section-title {
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--dash-primary);
        padding-bottom: 0.35rem;
        margin-bottom: 0.85rem;
        border-bottom: 2px solid var(--dash-primary-light);
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }
    .form-label-custom {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--dash-text-muted);
        margin-bottom: 0.3rem;
    }
    .form-control-pos {
        border-radius: 8px;
        border: 1px solid var(--dash-border);
        font-size: 0.9rem;
        padding: 0.45rem 0.75rem;
        color: var(--dash-text-main);
        background-color: var(--dash-card-bg);
        transition: all 0.15s;
    }
    .form-control-pos:focus {
        border-color: var(--dash-primary);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        background-color: var(--dash-card-bg);
        color: var(--dash-text-main);
    }

    /* Soporte Modo Oscuro */
    body.dark-mode .toolbar-card,
    body.dark-mode .table-card {
        background-color: var(--dash-card-bg);
        border-color: var(--dash-border);
    }
    body.dark-mode .table-toolbar-head,
    body.dark-mode .table-pagination-footer {
        background-color: var(--dash-card-bg) !important;
        border-color: var(--dash-border) !important;
        color: var(--dash-text-main) !important;
    }
    body.dark-mode .table-custom thead th {
        background: #111827 !important;
        color: var(--dash-text-muted) !important;
        border-color: var(--dash-border) !important;
    }
    body.dark-mode .table-custom thead th.sortable:hover {
        background: #1e293b !important;
        color: #34d399 !important;
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
    body.dark-mode .badge-barcode,
    body.dark-mode .badge-section {
        background: #111827;
        color: #cbd5e1;
        border-color: #374151;
    }
    body.dark-mode .custom-select-perpage,
    body.dark-mode .page-nav-btn,
    body.dark-mode .page-number-btn {
        background: #111827 !important;
        border-color: var(--dash-border) !important;
        color: var(--dash-text-main) !important;
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
    body.dark-mode .btn-action-edit,
    body.dark-mode .btn-action-dropdown {
        background: rgba(16, 185, 129, 0.15) !important;
        color: #34d399 !important;
        border-color: rgba(16, 185, 129, 0.3) !important;
    }
    body.dark-mode .btn-action-edit:hover,
    body.dark-mode .btn-action-dropdown:hover {
        background: #059669 !important;
        color: #ffffff !important;
    }
    body.dark-mode .dropdown-menu {
        background-color: #1f2937 !important;
        border: 1px solid #374151 !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6) !important;
    }
    body.dark-mode .dropdown-item {
        color: #e2e8f0 !important;
    }
    body.dark-mode .dropdown-item:hover,
    body.dark-mode .dropdown-item:focus {
        background-color: #111827 !important;
        color: #34d399 !important;
    }
    body.dark-mode .dropdown-divider {
        border-color: #374151 !important;
    }
    body.dark-mode .modal-content {
        background-color: #1f2937 !important;
        color: #e2e8f0 !important;
        border: 1px solid #374151 !important;
    }
    body.dark-mode .modal-header,
    body.dark-mode .modal-footer {
        background-color: #111827 !important;
        border-color: #374151 !important;
        color: #e2e8f0 !important;
    }
    body.dark-mode .form-section-title {
        border-bottom-color: #374151;
        color: #34d399;
    }
</style>
@endsection

@section('main')
<div class="container-fluid px-3 py-3" id="app" v-cloak>
    <!-- Header estilo Dashboard / Artículos -->
    <div class="dash-header">
        <div>
            <h1 class="dash-header-title font-cairo">
                <i class="fa-solid fa-users mr-2"></i>Directorio de Clientes
            </h1>
            <p class="dash-header-subtitle">
                Gestión de cartera de clientes, contactos y datos de facturación
            </p>
        </div>
        <div class="dash-header-badges">
            <button type="button" class="btn-pos-primary" @click="nuevoCliente">
                <i class="fa fa-user-plus mr-1"></i> Nuevo Cliente
            </button>
        </div>
    </div>

    <!-- Tarjetas KPI de Resumen estilo Artículos -->
    <div class="row">
        <!-- 1: Total Clientes -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #0284c7;">
                <div class="kpi-header">
                    <span class="kpi-label">Total Clientes</span>
                    <div class="kpi-icon-box kpi-icon-blue">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo">@{{ rows.length }}</div>
                <div class="kpi-subtext">
                    <i class="fa-solid fa-address-book text-muted"></i> En cartera de clientes
                </div>
            </div>
        </div>

        <!-- 2: Con Celular / Contacto -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #16a34a;">
                <div class="kpi-header">
                    <span class="kpi-label">Con Celular</span>
                    <div class="kpi-icon-box kpi-icon-green">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #166534;">@{{ totalConCelular }}</div>
                <div class="kpi-subtext" style="color: #166534;">
                    <i class="fa-brands fa-whatsapp"></i> Contacto directo activo
                </div>
            </div>
        </div>

        <!-- 3: Con Documento / RUC -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #7e22ce;">
                <div class="kpi-header">
                    <span class="kpi-label">Con Doc / RUC</span>
                    <div class="kpi-icon-box kpi-icon-purple">
                        <i class="fa-solid fa-id-card"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #7e22ce;">@{{ totalConDoc }}</div>
                <div class="kpi-subtext" style="color: #7e22ce;">
                    <i class="fa-solid fa-circle-check"></i> Facturación lista
                </div>
            </div>
        </div>

        <!-- 4: Con Correo Electrónico -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #d97706;">
                <div class="kpi-header">
                    <span class="kpi-label">Con Correo</span>
                    <div class="kpi-icon-box kpi-icon-amber">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #b45309;">@{{ totalConCorreo }}</div>
                <div class="kpi-subtext" style="color: #b45309;">
                    <i class="fa-solid fa-at"></i> Envío digital / electrónico
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Búsqueda y Filtros Rápidos -->
    <div class="table-card toolbar-card mb-3">
        <div class="card-body py-2 px-3">
            <div class="row align-items-center">
                <!-- Buscador predictivo -->
                <div class="col-12 col-xl-5 mb-2 mb-xl-0">
                    <div class="input-group search-input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text search-prepend border-right-0">
                                <i class="fa-solid fa-magnifying-glass text-muted"></i>
                            </span>
                        </div>
                        <input
                            type="text"
                            v-model="txtbuscar"
                            placeholder="Buscar cliente por nombre, CI / RUC o celular..."
                            @input="onBuscarInput"
                            @keyup.enter="buscar(false)"
                            class="form-control search-input border-left-0"
                            :style="{ borderRadius: txtbuscar ? '0' : '0 8px 8px 0', fontSize: '0.92rem' }"
                        />
                        <div class="input-group-append" v-if="txtbuscar">
                            <button class="btn search-clear-btn" type="button" @click="txtbuscar = ''; buscar(false)" title="Limpiar búsqueda">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filtro por Ciudad directo -->
                <div class="col-12 col-sm-6 col-xl-3 mb-2 mb-xl-0">
                    <select class="form-control select-seccion" v-model="filtroCiudad" @change="currentPage = 1" style="border-radius: 8px; font-size: 0.92rem;">
                        <option value="0">Todas las Ciudades</option>
                        @foreach ($ciudades as $c)
                            <option value="{{ $c->CIUDAD_cod }}">{{ $c->ciudad_nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Botones de Acción & Enlaces -->
                <div class="col-12 col-sm-6 col-xl-4 text-left text-sm-right">
                    <div class="toolbar-actions-wrap">
                        <button v-if="txtbuscar || filtroCiudad != 0" type="button" class="btn-pos-secondary" @click="limpiarFiltros" title="Restablecer filtros">
                            <i class="fa fa-undo"></i> Limpiar
                        </button>
                        <button type="button" class="btn-pos-primary" @click="nuevoCliente">
                            <i class="fa fa-user-plus mr-1"></i> Nuevo Cliente
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Personalizada Dashboard (Estilo Artículos) -->
    <div class="table-card mb-4">
        <!-- Barra superior informativa de la tabla -->
        <div class="table-toolbar-head">
            <div class="d-flex align-items-center flex-wrap">
                <span class="text-muted small">
                    Mostrando <strong>@{{ paginatedRows.length }}</strong> de <strong>@{{ filteredRows.length }}</strong> clientes encontrados
                </span>
                <span v-if="txtbuscar" class="badge badge-light border text-secondary ml-2 py-1 px-2">
                    Búsqueda: "@{{ txtbuscar }}"
                    <i class="fa fa-times text-danger ml-1 cursor-pointer" @click="txtbuscar = ''; buscar(false)" title="Quitar filtro de búsqueda"></i>
                </span>
                <span v-if="filtroCiudad != 0" class="badge badge-light border text-secondary ml-2 py-1 px-2">
                    Ciudad activa
                    <i class="fa fa-times text-danger ml-1 cursor-pointer" @click="filtroCiudad = 0; currentPage = 1" title="Quitar filtro de ciudad"></i>
                </span>
            </div>
            <div class="d-flex align-items-center">
                <label class="text-muted small mb-0 mr-2 font-weight-bold">Filas por página:</label>
                <select class="form-control form-control-sm custom-select-perpage" v-model.number="perPage" @change="currentPage = 1">
                    <option :value="10">10</option>
                    <option :value="25">25</option>
                    <option :value="50">50</option>
                    <option :value="100">100</option>
                </select>
            </div>
        </div>

        <!-- Tabla Nativa Responsiva -->
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th class="cursor-pointer sortable text-nowrap" @click="sortBy('doc')" style="width: 135px; min-width: 120px;">
                            <span>CI / RUC</span>
                            <i class="fa-solid ml-1 text-muted" :class="getSortIcon('doc')"></i>
                        </th>
                        <th class="cursor-pointer sortable text-nowrap" @click="sortBy('nombre')" style="min-width: 230px;">
                            <span>Cliente / Razón Social</span>
                            <i class="fa-solid ml-1 text-muted" :class="getSortIcon('nombre')"></i>
                        </th>
                        <th class="cursor-pointer sortable text-nowrap" @click="sortBy('celular')" style="width: 170px; min-width: 150px;">
                            <span>Celular / Teléfono</span>
                            <i class="fa-solid ml-1 text-muted" :class="getSortIcon('celular')"></i>
                        </th>
                        <th class="cursor-pointer sortable text-nowrap" @click="sortBy('ciudad_nombre')" style="width: 140px; min-width: 120px;">
                            <span>Ciudad</span>
                            <i class="fa-solid ml-1 text-muted" :class="getSortIcon('ciudad_nombre')"></i>
                        </th>
                        <th class="cursor-pointer sortable text-nowrap" @click="sortBy('direccion')" style="min-width: 180px;">
                            <span>Dirección / Ref.</span>
                            <i class="fa-solid ml-1 text-muted" :class="getSortIcon('direccion')"></i>
                        </th>
                        <th class="text-center text-nowrap" style="width: 130px; min-width: 120px;">
                            <span>Acciones</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Estado de Carga -->
                    <tr v-if="requestSend">
                        <td colspan="6" class="text-center py-5">
                            <div class="spinner-border text-success" role="status" style="width: 2.5rem; height: 2.5rem; color: var(--dash-primary) !important;">
                                <span class="sr-only">Cargando...</span>
                            </div>
                            <div class="mt-2 font-weight-bold text-muted small">Cargando directorio de clientes...</div>
                        </td>
                    </tr>

                    <!-- Filas de Clientes -->
                    <template v-else-if="paginatedRows.length > 0">
                        <tr v-for="row in paginatedRows" :key="row.id">
                            <!-- Documento / RUC -->
                            <td class="align-middle text-nowrap">
                                <span v-if="row.doc" class="badge-barcode">
                                    <i class="fa fa-id-card mr-1 text-muted"></i>@{{ row.doc }}
                                </span>
                                <span v-else class="text-muted small">—</span>
                            </td>

                            <!-- Cliente y Correo -->
                            <td class="align-middle" style="min-width: 230px;">
                                <div class="d-flex align-items-center">
                                    <div class="client-avatar mr-2">
                                        @{{ getInitials(row.nombre) }}
                                    </div>
                                    <div>
                                        <div class="font-weight-bold product-title">@{{ row.nombre }}</div>
                                        <div class="small text-muted" v-if="row.correo">
                                            <i class="fa-solid fa-envelope mr-1 text-secondary"></i>@{{ row.correo }}
                                        </div>
                                        <div class="small text-muted" v-else-if="row.ocupacion">
                                            <i class="fa-solid fa-briefcase mr-1 text-secondary"></i>@{{ row.ocupacion }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Celular y Teléfono -->
                            <td class="align-middle text-nowrap">
                                <div v-if="row.celular" class="d-flex align-items-center">
                                    <a :href="'https://wa.me/' + cleanPhone(row.celular)"
                                       target="_blank"
                                       class="btn-wa mr-1"
                                       title="Chatear en WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                    <span class="font-weight-bold font-cairo">@{{ row.celular }}</span>
                                </div>
                                <div v-if="row.telefono" class="small text-muted mt-1">
                                    <i class="fa fa-phone mr-1"></i>@{{ row.telefono }}
                                </div>
                                <span v-if="!row.celular && !row.telefono" class="text-muted small">—</span>
                            </td>

                            <!-- Ciudad -->
                            <td class="align-middle text-nowrap">
                                <span class="badge-section">
                                    @{{ row.ciudad_nombre || getCiudadNombre(row.idciudad) }}
                                </span>
                            </td>

                            <!-- Dirección -->
                            <td class="align-middle" style="min-width: 180px;">
                                <span class="small text-secondary" v-if="row.direccion">@{{ row.direccion }}</span>
                                <span class="text-muted small" v-else>—</span>
                            </td>

                            <!-- Acciones en 1 Clic -->
                            <td class="align-middle text-center text-nowrap" style="width: 130px; min-width: 120px;">
                                <div class="btn-group">
                                    <button type="button" class="btn-action-edit" @click="editar(row)" title="Editar Ficha de Cliente">
                                        <i class="fa fa-pen mr-1"></i> Editar
                                    </button>
                                    <button type="button" class="btn-action-dropdown dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="sr-only">Opciones</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right shadow-sm border-0" style="border-radius: 10px;">
                                        <button class="dropdown-item py-2" @click="editar(row)">
                                            <i class="fa fa-user-pen text-primary mr-2"></i> Editar Ficha
                                        </button>
                                        <a class="dropdown-item py-2" :href="'{{ url('documento/extractocuenta') }}/' + row.id" target="_blank">
                                            <i class="fa-solid fa-file-invoice text-info mr-2"></i> Extracto de Cuenta
                                        </a>
                                        <a v-if="row.celular" class="dropdown-item py-2" :href="'https://wa.me/' + cleanPhone(row.celular)" target="_blank">
                                            <i class="fa-brands fa-whatsapp text-success mr-2"></i> Enviar WhatsApp
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <button class="dropdown-item py-2 text-danger" @click="eliminar(row)">
                                            <i class="fa fa-trash text-danger mr-2"></i> Eliminar Cliente
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <!-- Estado Vacío -->
                    <tr v-else>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state-box">
                                <div class="empty-state-icon mb-3">
                                    <i class="fa-solid fa-users fa-2x"></i>
                                </div>
                                <h5 class="font-weight-bold mb-1" style="color: var(--dash-text-main);">No se encontraron clientes</h5>
                                <p class="text-muted small mb-3">No hay clientes que coincidan con los criterios de búsqueda o filtro.</p>
                                <div class="d-flex justify-content-center">
                                    <button v-if="txtbuscar || filtroCiudad != 0" type="button" class="btn-pos-secondary mr-2" @click="limpiarFiltros">
                                        <i class="fa fa-undo mr-1"></i> Limpiar Filtros
                                    </button>
                                    <button type="button" class="btn-pos-primary" @click="nuevoCliente">
                                        <i class="fa fa-user-plus mr-1"></i> Registrar Nuevo Cliente
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Paginación Dashboard -->
        <div class="table-pagination-footer" v-if="filteredRows.length > 0">
            <div class="text-muted small">
                Mostrando <strong>@{{ paginationFrom }}</strong> a <strong>@{{ paginationTo }}</strong> de <strong>@{{ filteredRows.length }}</strong> clientes
            </div>
            <div class="pagination-controls" v-if="totalPages > 1">
                <button type="button" class="page-nav-btn" :disabled="currentPage === 1" @click="changePage(currentPage - 1)">
                    <i class="fa fa-chevron-left mr-1"></i> Anterior
                </button>
                
                <div class="page-numbers">
                    <button v-for="(p, idx) in visiblePages"
                            :key="'page-' + idx + '-' + p"
                            type="button"
                            class="page-number-btn"
                            :class="{'active': p === currentPage, 'dots': p === '...'}"
                            :disabled="p === '...'"
                            @click="p !== '...' && changePage(p)">
                        @{{ p }}
                    </button>
                </div>

                <button type="button" class="page-nav-btn" :disabled="currentPage === totalPages" @click="changePage(currentPage + 1)">
                    Siguiente <i class="fa fa-chevron-right ml-1"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Formulario de Cliente (Nuevo / Edición) -->
    <div class="modal fade" id="modalCliente" tabindex="-1" role="dialog" aria-labelledby="modalClienteLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold font-cairo" id="modalClienteLabel">
                        <i class="fa fa-user-pen mr-2 text-success"></i>
                        @{{ esEdicion ? 'Editar Cliente #' + form.id : 'Nuevo Cliente' }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-3 px-4">
                    <form @submit.prevent="guardar">
                        <!-- Sección 1: Datos Principales -->
                        <div class="form-section-title">
                            <i class="fa fa-id-card"></i> 1. Datos Principales y Fiscales
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label class="form-label-custom">Doc. Identidad / C.I. / RUC <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text search-prepend"><i class="fa fa-id-badge text-muted"></i></span>
                                    </div>
                                    <input
                                        type="text"
                                        id="txtClienteDoc"
                                        class="form-control form-control-pos"
                                        v-model="form.doc"
                                        placeholder="Ej: 4589210 o 80012345-6"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="col-md-7 mb-3">
                                <label class="form-label-custom">Nombre Completo o Razón Social <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text search-prepend"><i class="fa fa-user text-muted"></i></span>
                                    </div>
                                    <input
                                        type="text"
                                        class="form-control form-control-pos font-weight-bold"
                                        v-model="form.nombre"
                                        placeholder="Nombre y apellido o empresa"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label-custom">Ciudad</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text search-prepend"><i class="fa fa-city text-muted"></i></span>
                                    </div>
                                    <select class="form-control form-control-pos" v-model="form.idciudad">
                                        @foreach ($ciudades as $c)
                                            <option value="{{ $c->CIUDAD_cod }}">{{ $c->ciudad_nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label-custom">Dirección Principal</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text search-prepend"><i class="fa fa-map-marker-alt text-muted"></i></span>
                                    </div>
                                    <input
                                        type="text"
                                        class="form-control form-control-pos"
                                        v-model="form.direccion"
                                        placeholder="Calle, nro de casa, barrio o referencia"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Sección 2: Canales de Contacto -->
                        <div class="form-section-title mt-2">
                            <i class="fa fa-phone"></i> 2. Canales de Contacto Directo
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label-custom">Celular (WhatsApp)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text search-prepend"><i class="fa-brands fa-whatsapp text-success"></i></span>
                                    </div>
                                    <input
                                        type="text"
                                        class="form-control form-control-pos"
                                        v-model="form.celular"
                                        placeholder="Ej: 0981 123456"
                                    />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label-custom">Teléfono Alternativo / Línea</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text search-prepend"><i class="fa fa-phone text-muted"></i></span>
                                    </div>
                                    <input
                                        type="text"
                                        class="form-control form-control-pos"
                                        v-model="form.telefono"
                                        placeholder="Ej: 021 555666"
                                    />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label-custom">Correo Electrónico</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text search-prepend"><i class="fa fa-envelope text-muted"></i></span>
                                    </div>
                                    <input
                                        type="email"
                                        class="form-control form-control-pos"
                                        v-model="form.correo"
                                        placeholder="cliente@ejemplo.com"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Sección 3: Referencias y Comercial Opcional -->
                        <div class="form-section-title mt-2">
                            <i class="fa fa-briefcase"></i> 3. Referencias y Perfil Comercial (Opcional)
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label-custom">Contacto / Ref. Familiar</label>
                                <input
                                    type="text"
                                    class="form-control form-control-pos"
                                    v-model="form.celfamiliar"
                                    placeholder="Nombre o teléfono de contacto"
                                />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label-custom">Ocupación / Profesión</label>
                                <input
                                    type="text"
                                    class="form-control form-control-pos"
                                    v-model="form.ocupacion"
                                    placeholder="Profesión, oficio o cargo"
                                />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label-custom">Referencia Laboral</label>
                                <input
                                    type="text"
                                    class="form-control form-control-pos"
                                    v-model="form.reflaboral"
                                    placeholder="Lugar de trabajo o empresa"
                                />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-pos-secondary" data-dismiss="modal">
                        <i class="fa fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="button" class="btn-pos-primary" @click="guardar" :disabled="guardando">
                        <span v-if="guardando" class="spinner-border spinner-border-sm mr-1" role="status"></span>
                        <i v-else class="fa fa-save mr-1"></i>
                        @{{ esEdicion ? 'Actualizar Cliente' : 'Guardar Cliente' }}
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
        timer: 2600,
        timerProgressBar: true
    });

    var app = new Vue({
        el: '#app',
        data: {
            requestSend: false,
            guardando: false,
            txtbuscar: '',
            buscarTimer: null,
            filtroCiudad: 0,
            sortField: 'nombre',
            sortOrder: 'asc',
            currentPage: 1,
            perPage: 25,
            rows: [],
            ciudadesList: @json($ciudades),
            form: {
                id: null,
                doc: '',
                nombre: '',
                direccion: '',
                celular: '',
                telefono: '',
                correo: '',
                idciudad: '{{ optional($ciudades->first())->CIUDAD_cod ?? 1 }}',
                celfamiliar: '',
                ocupacion: '',
                reflaboral: ''
            }
        },
        computed: {
            esEdicion: function () {
                return this.form.id !== null && this.form.id !== undefined && this.form.id !== '';
            },
            totalConCelular: function () {
                return this.rows.filter(function (c) {
                    return (c.celular || '').trim() !== '';
                }).length;
            },
            totalConDoc: function () {
                return this.rows.filter(function (c) {
                    return (c.doc || '').trim() !== '';
                }).length;
            },
            totalConCorreo: function () {
                return this.rows.filter(function (c) {
                    return (c.correo || '').trim() !== '';
                }).length;
            },
            filteredRows: function () {
                var list = [...this.rows];

                // Filtro por ciudad
                if (this.filtroCiudad && parseInt(this.filtroCiudad) > 0) {
                    var cid = parseInt(this.filtroCiudad);
                    list = list.filter(function (r) {
                        return parseInt(r.idciudad) === cid;
                    });
                }

                // Filtro local si txtbuscar está activo
                if (this.txtbuscar && this.txtbuscar.trim() !== '') {
                    var q = this.txtbuscar.toLowerCase().trim();
                    list = list.filter(function (r) {
                        var nom = (r.nombre || '').toLowerCase();
                        var doc = (r.doc || '').toLowerCase();
                        var cel = (r.celular || '').toLowerCase();
                        var dir = (r.direccion || '').toLowerCase();
                        var ciu = (r.ciudad_nombre || '').toLowerCase();
                        return nom.includes(q) || doc.includes(q) || cel.includes(q) || dir.includes(q) || ciu.includes(q);
                    });
                }

                // Ordenamiento
                if (this.sortField) {
                    var field = this.sortField;
                    var order = this.sortOrder === 'asc' ? 1 : -1;
                    list.sort(function (a, b) {
                        var valA = (a[field] || '').toString().toLowerCase();
                        var valB = (b[field] || '').toString().toLowerCase();
                        if (valA < valB) return -1 * order;
                        if (valA > valB) return 1 * order;
                        return 0;
                    });
                }

                return list;
            },
            totalPages: function () {
                return Math.ceil(this.filteredRows.length / this.perPage) || 1;
            },
            paginatedRows: function () {
                var start = (this.currentPage - 1) * this.perPage;
                return this.filteredRows.slice(start, start + this.perPage);
            },
            paginationFrom: function () {
                if (this.filteredRows.length === 0) return 0;
                return (this.currentPage - 1) * this.perPage + 1;
            },
            paginationTo: function () {
                return Math.min(this.currentPage * this.perPage, this.filteredRows.length);
            },
            visiblePages: function () {
                var total = this.totalPages;
                var current = this.currentPage;
                if (total <= 7) {
                    var pages = [];
                    for (var i = 1; i <= total; i++) pages.push(i);
                    return pages;
                }
                var pages = [];
                pages.push(1);
                if (current > 3) pages.push('...');
                var start = Math.max(2, current - 1);
                var end = Math.min(total - 1, current + 1);
                for (var i = start; i <= end; i++) pages.push(i);
                if (current < total - 2) pages.push('...');
                pages.push(total);
                return pages;
            }
        },
        methods: {
            sortBy: function (field) {
                if (this.sortField === field) {
                    this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortField = field;
                    this.sortOrder = 'asc';
                }
                this.currentPage = 1;
            },
            getSortIcon: function (field) {
                if (this.sortField !== field) return 'fa-sort text-muted opacity-50';
                return this.sortOrder === 'asc' ? 'fa-sort-up text-success' : 'fa-sort-down text-success';
            },
            changePage: function (p) {
                if (p < 1 || p > this.totalPages) return;
                this.currentPage = p;
            },
            cleanPhone: function (phone) {
                if (!phone) return '';
                var p = phone.toString().replace(/\D/g, '');
                if (p.startsWith('09')) {
                    p = '595' + p.substring(1);
                }
                return p;
            },
            getInitials: function (nombre) {
                if (!nombre) return 'CL';
                var parts = nombre.trim().split(/\s+/);
                if (parts.length >= 2) {
                    return (parts[0].charAt(0) + parts[1].charAt(0)).toUpperCase();
                }
                return nombre.substring(0, 2).toUpperCase();
            },
            getCiudadNombre: function (idciudad) {
                if (!idciudad) return 'General';
                var found = this.ciudadesList.find(function (c) {
                    return c.CIUDAD_cod == idciudad;
                });
                return found ? found.ciudad_nombre : 'General';
            },
            blankForm: function () {
                return {
                    id: null,
                    doc: '',
                    nombre: '',
                    direccion: '',
                    celular: '',
                    telefono: '',
                    correo: '',
                    idciudad: '{{ optional($ciudades->first())->CIUDAD_cod ?? 1 }}',
                    celfamiliar: '',
                    ocupacion: '',
                    reflaboral: ''
                };
            },
            nuevoCliente: function () {
                this.form = this.blankForm();
                $('#modalCliente').modal('show');
                this.$nextTick(function () {
                    setTimeout(function () {
                        $('#txtClienteDoc').focus();
                    }, 300);
                });
            },
            editar: function (c) {
                this.form = {
                    id: c.id,
                    doc: c.doc || '',
                    nombre: c.nombre || '',
                    direccion: c.direccion || '',
                    celular: c.celular || '',
                    telefono: c.telefono || '',
                    correo: c.correo || '',
                    idciudad: c.idciudad || this.blankForm().idciudad,
                    celfamiliar: c.celfamiliar || '',
                    ocupacion: c.ocupacion || '',
                    reflaboral: c.reflaboral || ''
                };
                $('#modalCliente').modal('show');
                this.$nextTick(function () {
                    setTimeout(function () {
                        $('#txtClienteDoc').focus().select();
                    }, 300);
                });
            },
            limpiarFiltros: function () {
                this.txtbuscar = '';
                this.filtroCiudad = 0;
                this.currentPage = 1;
                this.buscar(false);
            },
            onBuscarInput: function () {
                var self = this;
                if (this.buscarTimer) clearTimeout(this.buscarTimer);
                this.buscarTimer = setTimeout(function () {
                    self.buscar(false);
                }, 350);
            },
            buscar: function (keepPage) {
                var self = this;
                this.requestSend = true;
                axios.get('{{ url('cliente/buscar') }}', {
                    params: {
                        q: (this.txtbuscar || '').trim(),
                        limit: 500
                    }
                }).then(function (response) {
                    self.requestSend = false;
                    var data = response.data || [];
                    self.rows = data.map(function (c) {
                        var id = c.clientes_cod !== undefined && c.clientes_cod !== null ? c.clientes_cod : c.CLIENTES_cod;
                        return {
                            id: id,
                            doc: c.cliente_ci || c.cliente_ruc || '',
                            nombre: c.cliente_nombre || '',
                            direccion: c.cliente_direccion || '',
                            celular: c.cliente_cel || '',
                            telefono: c.cliente_telef || '',
                            correo: c.cliente_correo || '',
                            idciudad: c.CIUDAD_cod || c.ciudad_cod || 1,
                            ciudad_nombre: c.ciudad_nombre || self.getCiudadNombre(c.CIUDAD_cod || c.ciudad_cod),
                            celfamiliar: c.cliente_referente_nombre || '',
                            ocupacion: c.cliente_profesion || '',
                            reflaboral: c.cliente_referencia_laboral || ''
                        };
                    });
                    if (!keepPage) {
                        self.currentPage = 1;
                    }
                }).catch(function () {
                    self.requestSend = false;
                    self.rows = [];
                    Swal.fire('Error', 'No se pudo cargar el listado de clientes', 'error');
                });
            },
            payload: function () {
                return {
                    cliente: {
                        id: this.form.id,
                        doc: this.form.doc,
                        nombre: this.form.nombre,
                        direccion: this.form.direccion || '',
                        celular: this.form.celular || '',
                        telefono: this.form.telefono || '',
                        correo: this.form.correo || '',
                        idciudad: this.form.idciudad || 1,
                        celfamiliar: this.form.celfamiliar || '',
                        ocupacion: this.form.ocupacion || '',
                        reflaboral: this.form.reflaboral || ''
                    }
                };
            },
            guardar: function () {
                var self = this;
                if (!(this.form.nombre || '').trim() || !(this.form.doc || '').trim()) {
                    Swal.fire('Campos vacíos', 'Por favor completá documento y nombre del cliente.', 'warning');
                    return;
                }
                this.guardando = true;
                var esEdicion = this.esEdicion;
                var req = esEdicion
                    ? axios.post('{{ url('cliente/update') }}', this.payload())
                    : axios.post('{{ url('cliente') }}', this.payload());

                req.then(function () {
                    self.guardando = false;
                    $('#modalCliente').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: esEdicion ? 'Cliente actualizado con éxito' : 'Cliente registrado con éxito'
                    });
                    self.buscar(true);
                }).catch(function (err) {
                    self.guardando = false;
                    var msg = (err.response && err.response.data && (err.response.data.message || err.response.data.msg))
                        || 'No se pudo guardar los datos del cliente';
                    Swal.fire('Error', msg, 'error');
                });
            },
            eliminar: function (c) {
                var self = this;
                Swal.fire({
                    title: '¿Eliminar cliente?',
                    text: c.nombre + ' (' + (c.doc || 'Sin doc') + ')',
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Sí, eliminar',
                    confirmButtonClass: 'bg-danger'
                }).then(function (result) {
                    if (!result.value) return;
                    axios.delete('{{ url('cliente') }}/' + c.id)
                        .then(function () {
                            Toast.fire({ icon: 'success', title: 'Cliente eliminado correctamente' });
                            self.buscar(true);
                        })
                        .catch(function () {
                            Swal.fire(
                                'No se puede eliminar',
                                'Este cliente está registrado en ventas, créditos u otros movimientos contables.',
                                'error'
                            );
                        });
                });
            }
        },
        mounted: function () {
            this.buscar(false);
        }
    });

    activarMenu('m_cliente', '');
</script>
@endsection
