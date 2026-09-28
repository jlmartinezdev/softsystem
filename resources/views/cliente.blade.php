@extends('layouts.app')
@section('title', 'Directorio de Clientes')
@section('style')
<link rel="stylesheet" href="{{ asset('js/leaflet/leaflet.css') }}" />
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

    /* Estilos de Mapa OpenStreetMap y Leaflet */
    .map-leaflet-wrapper {
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid var(--dash-border);
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        position: relative;
    }
    .map-container-pos {
        height: 280px;
        width: 100%;
        background-color: #e5e7eb;
        z-index: 1;
    }
    .map-preview-container {
        height: 380px;
        width: 100%;
        background-color: #e5e7eb;
        z-index: 1;
    }
    .map-toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        background: var(--dash-card-bg);
        border-bottom: 1px solid var(--dash-border);
    }
    .coord-display-box {
        font-family: 'SFMono-Regular', Consolas, monospace;
        font-size: 0.82rem;
        background: var(--dash-primary-light);
        color: var(--dash-primary);
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        border: 1px solid var(--dash-primary-border);
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    /* Tarjetas de Carga de Cédula / Documento */
    .doc-card-upload {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 10px;
        padding: 0.85rem;
        transition: all 0.2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .doc-card-upload:hover {
        border-color: var(--dash-primary-border);
        box-shadow: 0 4px 12px rgba(10, 77, 54, 0.08);
    }
    .doc-preview-wrapper {
        position: relative;
        width: 100%;
        height: 180px;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px dashed var(--dash-border);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-bottom: 0.65rem;
        transition: border-color 0.15s;
    }
    .doc-preview-img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    .doc-preview-img:hover {
        transform: scale(1.02);
    }
    .doc-upload-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1.25rem;
        text-align: center;
        cursor: pointer;
        width: 100%;
        height: 100%;
        color: var(--dash-text-muted);
    }
    .doc-upload-placeholder:hover {
        background: var(--dash-primary-light);
        color: var(--dash-primary);
    }
    .doc-upload-icon {
        font-size: 2.2rem;
        margin-bottom: 0.4rem;
        color: var(--dash-primary);
        opacity: 0.85;
    }

    /* Badges de Tabla para GPS y Cédula */
    .badge-gps {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
        font-weight: 600;
        padding: 0.25rem 0.55rem;
        border-radius: 6px;
        font-size: 0.78rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        cursor: pointer;
        text-decoration: none !important;
        transition: all 0.15s;
    }
    .badge-gps:hover {
        background: #047857;
        color: #ffffff !important;
        border-color: #047857;
    }
    .badge-doc-foto {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        font-weight: 600;
        padding: 0.25rem 0.55rem;
        border-radius: 6px;
        font-size: 0.78rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        cursor: pointer;
        text-decoration: none !important;
        transition: all 0.15s;
    }
    .badge-doc-foto:hover {
        background: #1d4ed8;
        color: #ffffff !important;
        border-color: #1d4ed8;
    }

    /* Modo Oscuro Adicional */
    body.dark-mode .map-leaflet-wrapper {
        border-color: #374151;
    }
    body.dark-mode .map-toolbar {
        background: #1f2937;
        border-color: #374151;
    }
    body.dark-mode .coord-display-box {
        background: #064e3b;
        color: #34d399;
        border-color: #047857;
    }
    body.dark-mode .doc-card-upload {
        background: #1f2937;
        border-color: #374151;
    }
    body.dark-mode .doc-preview-wrapper {
        background: #111827;
        border-color: #374151;
    }
    body.dark-mode .doc-upload-placeholder:hover {
        background: rgba(16, 185, 129, 0.1);
    }
    body.dark-mode .badge-gps {
        background: #064e3b;
        color: #6ee7b7;
        border-color: #047857;
    }
    body.dark-mode .badge-gps:hover {
        background: #10b981;
        color: #ffffff !important;
    }
    body.dark-mode .badge-doc-foto {
        background: #1e3a8a;
        color: #93c5fd;
        border-color: #1d4ed8;
    }
    body.dark-mode .badge-doc-foto:hover {
        background: #2563eb;
        color: #ffffff !important;
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
            <span class="badge badge-light border py-2 px-3 text-secondary font-weight-bold" style="border-radius: 8px;">
                <i class="fa-solid fa-location-dot text-success mr-1"></i> @{{ totalConGps }} con GPS
            </span>
            <span class="badge badge-light border py-2 px-3 text-secondary font-weight-bold" style="border-radius: 8px;">
                <i class="fa-solid fa-id-card text-primary mr-1"></i> @{{ totalConDocFoto }} con Doc C.I.
            </span>
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

                <!-- Botones de Acción & Filtros Rápidos -->
                <div class="col-12 col-sm-6 col-xl-4 text-left text-sm-right">
                    <div class="toolbar-actions-wrap">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-sm" :class="filtroGps === 'con_gps' ? 'btn-success text-white' : 'btn-outline-secondary'" @click="toggleFiltroGps" title="Filtrar clientes con GPS">
                                <i class="fa-solid fa-location-dot mr-1"></i> GPS
                            </button>
                            <button type="button" class="btn btn-sm" :class="filtroFotos === 'con_fotos' ? 'btn-primary text-white' : 'btn-outline-secondary'" @click="toggleFiltroFotos" title="Filtrar clientes con fotos de cédula">
                                <i class="fa-solid fa-id-card mr-1"></i> Cédula
                            </button>
                        </div>
                        <button v-if="txtbuscar || filtroCiudad != 0 || filtroGps !== 'todos' || filtroFotos !== 'todos'" type="button" class="btn-pos-secondary" @click="limpiarFiltros" title="Restablecer filtros">
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
                <span v-if="filtroGps === 'con_gps'" class="badge badge-success ml-2 py-1 px-2 cursor-pointer" @click="filtroGps = 'todos'" title="Quitar filtro de GPS">
                    Con GPS <i class="fa fa-times ml-1"></i>
                </span>
                <span v-if="filtroFotos === 'con_fotos'" class="badge badge-primary ml-2 py-1 px-2 cursor-pointer" @click="filtroFotos = 'todos'" title="Quitar filtro de cédula">
                    Con Cédula <i class="fa fa-times ml-1"></i>
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
                                <!-- Badge de Fotos Cédula si existen -->
                                <div v-if="row.foto_frente_url || row.foto_dorso_url" class="mt-1">
                                    <button type="button" class="badge-doc-foto border-0" @click="verFotosDoc(row)" title="Ver fotos de documento de identidad">
                                        <i class="fa-solid fa-id-card"></i> Doc C.I.
                                        <span v-if="row.foto_frente_url && row.foto_dorso_url" class="ml-1 small font-weight-normal">(2)</span>
                                        <span v-else class="ml-1 small font-weight-normal">(1)</span>
                                    </button>
                                </div>
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

                            <!-- Dirección y GPS -->
                            <td class="align-middle" style="min-width: 180px;">
                                <span class="small text-secondary" v-if="row.direccion">@{{ row.direccion }}</span>
                                <span class="text-muted small" v-else>—</span>
                                <!-- Botón/Badge de GPS si tiene coordenadas -->
                                <div v-if="row.latitud && row.longitud" class="mt-1 d-flex align-items-center flex-wrap">
                                    <button type="button" class="badge-gps mr-1 border-0" @click="verGpsModal(row)" title="Ver mapa interactivo">
                                        <i class="fa-solid fa-location-dot"></i> GPS
                                    </button>
                                    <a :href="'https://www.openstreetmap.org/?mlat=' + row.latitud + '&mlon=' + row.longitud + '#map=18/' + row.latitud + '/' + row.longitud"
                                       target="_blank"
                                       class="text-muted small"
                                       title="Abrir en OpenStreetMap externo">
                                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    </a>
                                </div>
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
                                        <button v-if="row.latitud && row.longitud" class="dropdown-item py-2" @click="verGpsModal(row)">
                                            <i class="fa-solid fa-location-dot text-danger mr-2"></i> Ver Mapa GPS
                                        </button>
                                        <button v-if="row.foto_frente_url || row.foto_dorso_url" class="dropdown-item py-2" @click="verFotosDoc(row)">
                                            <i class="fa-solid fa-id-card text-info mr-2"></i> Ver Fotos Documento
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
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
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

                        <!-- Sección 4: Geolocalización y Ubicación GPS -->
                        <div class="form-section-title mt-3 d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa-solid fa-map-location-dot"></i> 4. Geolocalización y Ubicación GPS
                            </span>
                            <span class="badge" :class="usarGoogleMaps ? 'badge-primary' : 'badge-success'" style="font-size: 0.72rem; font-weight: 700; text-transform: none;">
                                <i :class="usarGoogleMaps ? 'fa-brands fa-google' : 'fa-solid fa-globe'" class="mr-1"></i>
                                @{{ usarGoogleMaps ? 'Google Maps' : 'OpenStreetMap' }}
                            </span>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <label class="form-label-custom mb-1">Latitud</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa-solid fa-arrows-up-down text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control form-control-pos font-cairo" v-model="form.latitud" @input="actualizarMarcadorDesdeInputs" placeholder="Ej: -25.263740">
                                </div>
                            </div>
                            <div class="col-md-4 mb-2 mb-md-0">
                                <label class="form-label-custom mb-1">Longitud</label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa-solid fa-arrows-left-right text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control form-control-pos font-cairo" v-model="form.longitud" @input="actualizarMarcadorDesdeInputs" placeholder="Ej: -57.575920">
                                </div>
                            </div>
                            <div class="col-md-4 text-md-right pt-md-3">
                                <button type="button" class="btn btn-sm btn-outline-success font-weight-bold mr-1" @click="obtenerUbicacionActual" title="Detectar coordenadas GPS usando el navegador">
                                    <i class="fa-solid fa-location-crosshairs mr-1"></i> Mi Ubicación
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger mr-1" @click="limpiarGps" v-if="form.latitud && form.longitud" title="Quitar coordenadas GPS">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                                <a v-if="form.latitud && form.longitud" :href="'https://www.google.com/maps?q=' + form.latitud + ',' + form.longitud" target="_blank" class="btn btn-sm btn-outline-success mr-1" title="Ver en Google Maps">
                                    <i class="fa-brands fa-google"></i>
                                </a>
                                <a v-if="form.latitud && form.longitud" :href="'https://www.openstreetmap.org/?mlat=' + form.latitud + '&mlon=' + form.longitud + '#map=17/' + form.latitud + '/' + form.longitud" target="_blank" class="btn btn-sm btn-outline-info" title="Ver en OpenStreetMap">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                            </div>
                        </div>

                        <div class="map-leaflet-wrapper mb-2">
                            <div class="map-toolbar">
                                <div class="d-flex align-items-center">
                                    <i :class="usarGoogleMaps ? 'fa-brands fa-google text-danger' : 'fa-solid fa-map-pin text-success'" class="mr-2"></i>
                                    <span class="small font-weight-bold" style="color: var(--dash-text-main);">
                                        Mapa Interactivo (@{{ usarGoogleMaps ? 'Google Maps' : 'OpenStreetMap' }})
                                    </span>
                                </div>
                                <div>
                                    <span v-if="form.latitud && form.longitud" class="coord-display-box">
                                        <i class="fa-solid fa-satellite"></i> @{{ form.latitud }}, @{{ form.longitud }}
                                    </span>
                                    <span v-else class="text-muted small">
                                        <i class="fa-solid fa-hand-pointer mr-1"></i> Haz clic en el mapa para marcar ubicación
                                    </span>
                                </div>
                            </div>
                            <div id="mapCliente" class="map-container-pos"></div>
                        </div>
                        <small class="text-muted d-block mb-3">
                            <i class="fa-solid fa-circle-info text-info mr-1"></i> Haz clic en el mapa o arrastra el marcador para fijar la ubicación exacta del cliente.
                            <span v-if="usarGoogleMaps" class="text-primary font-weight-bold ml-1">(Motor Google Maps activo)</span>
                            <span v-else class="text-success font-weight-bold ml-1">(Motor OpenStreetMap activo)</span>
                        </small>

                        <!-- Sección 5: Fotos de Documento de Identidad (Cara y Reverso) -->
                        <div class="form-section-title mt-3">
                            <i class="fa-solid fa-id-card"></i> 5. Fotos de Documento de Identidad (C.I. / RUC)
                        </div>

                        <div class="row">
                            <!-- Foto Frente / Cara -->
                            <div class="col-md-6 mb-3">
                                <div class="doc-card-upload">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <label class="form-label-custom mb-0 font-weight-bold">
                                            <i class="fa-solid fa-image text-primary mr-1"></i> Cara Frontal (Anverso)
                                        </label>
                                        <span v-if="form.foto_frente_url" class="badge badge-success">
                                            <i class="fa-solid fa-check mr-1"></i> Cargada
                                        </span>
                                        <span v-else class="badge badge-secondary">Sin foto</span>
                                    </div>

                                    <div class="doc-preview-wrapper">
                                        <template v-if="form.foto_frente_url">
                                            <img :src="form.foto_frente_url" alt="Documento Frente" class="doc-preview-img" @click="ampliarFoto(form.foto_frente_url, 'Documento Frontal - ' + (form.nombre || 'Cliente'))" title="Clic para ampliar foto" />
                                        </template>
                                        <div v-else class="doc-upload-placeholder" @click="seleccionarFoto('frente')">
                                            <div v-if="subiendoFotoFrente" class="text-center">
                                                <div class="spinner-border text-success" role="status"></div>
                                                <div class="small mt-2 font-weight-bold text-muted">Subiendo imagen...</div>
                                            </div>
                                            <div v-else>
                                                <i class="fa-solid fa-id-card doc-upload-icon"></i>
                                                <div class="font-weight-bold small">Cargar foto frontal</div>
                                                <div class="text-muted" style="font-size: 0.75rem;">JPG, PNG, WebP (Máx. 10MB)</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between gap-1 mt-auto">
                                        <input type="file" ref="inputFotoFrente" accept="image/jpeg,image/png,image/jpg,image/webp" class="d-none" @change="subirFoto($event, 'frente')">
                                        <button type="button" class="btn btn-sm btn-outline-primary" @click="seleccionarFoto('frente')" :disabled="subiendoFotoFrente">
                                            <i class="fa-solid fa-upload mr-1"></i> @{{ form.foto_frente_url ? 'Cambiar Foto' : 'Subir Foto' }}
                                        </button>
                                        <div v-if="form.foto_frente_url" class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-info" @click="ampliarFoto(form.foto_frente_url, 'Documento Frontal - ' + (form.nombre || 'Cliente'))" title="Ver foto ampliada">
                                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" @click="eliminarFoto('frente')" title="Eliminar foto">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Foto Dorso / Reverso -->
                            <div class="col-md-6 mb-3">
                                <div class="doc-card-upload">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <label class="form-label-custom mb-0 font-weight-bold">
                                            <i class="fa-solid fa-image text-primary mr-1"></i> Reverso / Dorso
                                        </label>
                                        <span v-if="form.foto_dorso_url" class="badge badge-success">
                                            <i class="fa-solid fa-check mr-1"></i> Cargada
                                        </span>
                                        <span v-else class="badge badge-secondary">Sin foto</span>
                                    </div>

                                    <div class="doc-preview-wrapper">
                                        <template v-if="form.foto_dorso_url">
                                            <img :src="form.foto_dorso_url" alt="Documento Dorso" class="doc-preview-img" @click="ampliarFoto(form.foto_dorso_url, 'Documento Posterior - ' + (form.nombre || 'Cliente'))" title="Clic para ampliar foto" />
                                        </template>
                                        <div v-else class="doc-upload-placeholder" @click="seleccionarFoto('dorso')">
                                            <div v-if="subiendoFotoDorso" class="text-center">
                                                <div class="spinner-border text-success" role="status"></div>
                                                <div class="small mt-2 font-weight-bold text-muted">Subiendo imagen...</div>
                                            </div>
                                            <div v-else>
                                                <i class="fa-regular fa-id-card doc-upload-icon"></i>
                                                <div class="font-weight-bold small">Cargar foto posterior</div>
                                                <div class="text-muted" style="font-size: 0.75rem;">JPG, PNG, WebP (Máx. 10MB)</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between gap-1 mt-auto">
                                        <input type="file" ref="inputFotoDorso" accept="image/jpeg,image/png,image/jpg,image/webp" class="d-none" @change="subirFoto($event, 'dorso')">
                                        <button type="button" class="btn btn-sm btn-outline-primary" @click="seleccionarFoto('dorso')" :disabled="subiendoFotoDorso">
                                            <i class="fa-solid fa-upload mr-1"></i> @{{ form.foto_dorso_url ? 'Cambiar Foto' : 'Subir Foto' }}
                                        </button>
                                        <div v-if="form.foto_dorso_url" class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-info" @click="ampliarFoto(form.foto_dorso_url, 'Documento Posterior - ' + (form.nombre || 'Cliente'))" title="Ver foto ampliada">
                                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" @click="eliminarFoto('dorso')" title="Eliminar foto">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
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

    <!-- Modal Visor Rápido de Ubicación GPS (OpenStreetMap) -->
    <div class="modal fade" id="modalVerGps" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" v-if="clienteGpsSeleccionado">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title font-weight-bold font-cairo mb-0">
                        <i class="fa-solid fa-location-dot text-danger mr-2"></i>
                        Ubicación GPS: @{{ clienteGpsSeleccionado.nombre }}
                    </h5>
                    <div class="d-flex align-items-center">
                        <span class="badge mr-2" :class="usarGoogleMaps ? 'badge-primary' : 'badge-success'" style="font-size: 0.72rem; font-weight: 700; text-transform: none;">
                            <i :class="usarGoogleMaps ? 'fa-brands fa-google' : 'fa-solid fa-globe'" class="mr-1"></i>
                            @{{ usarGoogleMaps ? 'Google Maps' : 'OpenStreetMap' }}
                        </span>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body p-3">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                        <div>
                            <div class="font-weight-bold" style="color: var(--dash-text-main);">
                                <i class="fa-solid fa-house-chimney text-muted mr-1"></i> @{{ clienteGpsSeleccionado.direccion || 'Sin dirección descriptiva' }}
                            </div>
                            <div class="small text-muted">
                                <i class="fa-solid fa-city mr-1"></i> @{{ clienteGpsSeleccionado.ciudad_nombre }} &bull; CI/RUC: @{{ clienteGpsSeleccionado.doc || '—' }}
                            </div>
                        </div>
                        <div class="mt-2 mt-sm-0">
                            <span class="coord-display-box">
                                <i class="fa-solid fa-satellite"></i> @{{ clienteGpsSeleccionado.latitud }}, @{{ clienteGpsSeleccionado.longitud }}
                            </span>
                        </div>
                    </div>

                    <div class="map-leaflet-wrapper">
                        <div id="mapPreviewViewer" class="map-preview-container"></div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div>
                        <a :href="'https://www.openstreetmap.org/?mlat=' + clienteGpsSeleccionado.latitud + '&mlon=' + clienteGpsSeleccionado.longitud + '#map=18/' + clienteGpsSeleccionado.latitud + '/' + clienteGpsSeleccionado.longitud"
                           target="_blank" class="btn btn-sm btn-outline-primary mr-1">
                            <i class="fa-solid fa-map mr-1"></i> Abrir OpenStreetMap
                        </a>
                        <a :href="'https://www.google.com/maps?q=' + clienteGpsSeleccionado.latitud + ',' + clienteGpsSeleccionado.longitud"
                           target="_blank" class="btn btn-sm btn-outline-success">
                            <i class="fa-brands fa-google mr-1"></i> Google Maps
                        </a>
                    </div>
                    <button type="button" class="btn-pos-secondary" data-dismiss="modal">
                        <i class="fa fa-times mr-1"></i> Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Visor de Documentos de Identidad (Cara y Reverso) -->
    <div class="modal fade" id="modalFotoDocViewer" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" v-if="docViewerCliente">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title font-weight-bold font-cairo mb-0">
                            <i class="fa-solid fa-id-card text-primary mr-2"></i>
                            Cédula / Documento: @{{ docViewerCliente.nombre }}
                        </h5>
                        <div class="small text-muted mt-1">
                            Doc: @{{ docViewerCliente.doc || 'Sin Doc' }} &bull; Ciudad: @{{ docViewerCliente.ciudad_nombre }}
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3 text-center">
                    <!-- Pestañas para alternar Frente y Dorso -->
                    <ul class="nav nav-pills justify-content-center mb-3">
                        <li class="nav-item">
                            <a class="nav-link cursor-pointer font-weight-bold"
                               :class="{ 'active': docViewerTipo === 'frente', 'disabled': !docViewerCliente.foto_frente_url }"
                               @click="docViewerCliente.foto_frente_url && (docViewerTipo = 'frente')">
                                <i class="fa-solid fa-id-card mr-1"></i> Cara Frontal
                                <span v-if="!docViewerCliente.foto_frente_url" class="badge badge-light ml-1">(Sin foto)</span>
                            </a>
                        </li>
                        <li class="nav-item ml-2">
                            <a class="nav-link cursor-pointer font-weight-bold"
                               :class="{ 'active': docViewerTipo === 'dorso', 'disabled': !docViewerCliente.foto_dorso_url }"
                               @click="docViewerCliente.foto_dorso_url && (docViewerTipo = 'dorso')">
                                <i class="fa-regular fa-id-card mr-1"></i> Reverso / Dorso
                                <span v-if="!docViewerCliente.foto_dorso_url" class="badge badge-light ml-1">(Sin foto)</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Contenedor de Imagen -->
                    <div class="p-2 border rounded bg-light d-flex align-items-center justify-content-center" style="min-height: 380px; max-height: 520px; overflow: hidden;">
                        <img v-if="docViewerTipo === 'frente' && docViewerCliente.foto_frente_url"
                             :src="docViewerCliente.foto_frente_url"
                             alt="C.I. Frente"
                             class="img-fluid rounded"
                             style="max-height: 480px; object-fit: contain; box-shadow: 0 4px 15px rgba(0,0,0,0.1);" />

                        <img v-else-if="docViewerTipo === 'dorso' && docViewerCliente.foto_dorso_url"
                             :src="docViewerCliente.foto_dorso_url"
                             alt="C.I. Dorso"
                             class="img-fluid rounded"
                             style="max-height: 480px; object-fit: contain; box-shadow: 0 4px 15px rgba(0,0,0,0.1);" />

                        <div v-else class="text-muted py-5">
                            <i class="fa-solid fa-image-slash fa-3x mb-3 text-muted"></i>
                            <p class="mb-0">No se ha cargado la fotografía para este lado del documento.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div>
                        <a v-if="docViewerTipo === 'frente' && docViewerCliente.foto_frente_url"
                           :href="docViewerCliente.foto_frente_url"
                           target="_blank"
                           download
                           class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-download mr-1"></i> Descargar Foto
                        </a>
                        <a v-else-if="docViewerTipo === 'dorso' && docViewerCliente.foto_dorso_url"
                           :href="docViewerCliente.foto_dorso_url"
                           target="_blank"
                           download
                           class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-download mr-1"></i> Descargar Foto
                        </a>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-pos-primary mr-1" @click="$('#modalFotoDocViewer').modal('hide'); editar(docViewerCliente)">
                            <i class="fa fa-pen mr-1"></i> Editar Documentos en Ficha
                        </button>
                        <button type="button" class="btn-pos-secondary" data-dismiss="modal">
                            <i class="fa fa-times mr-1"></i> Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Zoom Foto Individual -->
    <div class="modal fade" id="modalFotoIndividual" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title font-weight-bold font-cairo mb-0">
                        <i class="fa-solid fa-image mr-1 text-primary"></i> @{{ fotoAmpliadaTitulo }}
                    </h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-2 text-center bg-dark">
                    <img :src="fotoAmpliadaUrl" alt="Foto Ampliada" class="img-fluid rounded" style="max-height: 75vh; object-fit: contain;" />
                </div>
                <div class="modal-footer justify-content-between py-2">
                    <a :href="fotoAmpliadaUrl" target="_blank" class="btn btn-sm btn-outline-secondary" download>
                        <i class="fa-solid fa-download mr-1"></i> Descargar Original
                    </a>
                    <button type="button" class="btn btn-sm btn-pos-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/leaflet/leaflet.js') }}"></script>
@if(!empty($mapConfig['google_maps_api_key']) && ($mapConfig['proveedor_efectivo'] ?? 'openstreet') === 'google')
<script>
    // Callback si Google Maps falla por autenticación (API key errónea o inválida)
    window.gm_authFailure = function () {
        console.warn('Google Maps: Fallo de autenticación en la clave API. Activando fallback a OpenStreetMap.');
        if (window.app) {
            window.app.onGoogleMapsError();
        }
    };
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ $mapConfig['google_maps_api_key'] }}"></script>
@endif
<script>
    // Configuración de rutas de iconos de Leaflet para compatibilidad total
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
        iconRetinaUrl: '{{ asset("js/leaflet/images/marker-icon-2x.png") }}',
        iconUrl: '{{ asset("js/leaflet/images/marker-icon.png") }}',
        shadowUrl: '{{ asset("js/leaflet/images/marker-shadow.png") }}',
    });

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
            filtroGps: 'todos',
            filtroFotos: 'todos',
            sortField: 'nombre',
            sortOrder: 'asc',
            currentPage: 1,
            perPage: 25,
            rows: [],
            ciudadesList: @json($ciudades),
            mapConfig: {!! json_encode($mapConfig ?? [
                'proveedor' => 'openstreet',
                'proveedor_efectivo' => 'openstreet',
                'google_maps_api_key' => '',
                'lat_default' => -25.263740,
                'lng_default' => -57.575920,
                'zoom_default' => 14
            ]) !!},
            modoGoogleMapsFallo: false,
            subiendoFotoFrente: false,
            subiendoFotoDorso: false,
            mapCliente: null,
            markerCliente: null,
            gMapCliente: null,
            gMarkerCliente: null,
            gpsViewerMap: null,
            gpsViewerMarker: null,
            gMapViewer: null,
            gMarkerViewer: null,
            clienteGpsSeleccionado: null,
            docViewerCliente: null,
            docViewerTipo: 'frente',
            fotoAmpliadaUrl: '',
            fotoAmpliadaTitulo: '',
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
                reflaboral: '',
                latitud: null,
                longitud: null,
                ubicacion_url: null,
                foto_ci_dorso: null,
                foto_ci_reverso: null,
                foto_frente_url: null,
                foto_dorso_url: null
            }
        },
        computed: {
            usarGoogleMaps: function () {
                return !this.modoGoogleMapsFallo
                    && ((this.mapConfig.proveedor_efectivo || '') === 'google')
                    && typeof google !== 'undefined'
                    && typeof google.maps !== 'undefined';
            },
            proveedorActivoLabel: function () {
                return this.usarGoogleMaps ? 'Google Maps' : 'OpenStreetMap';
            },
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
            totalConGps: function () {
                return this.rows.filter(function (c) {
                    return c.latitud && c.longitud;
                }).length;
            },
            totalConDocFoto: function () {
                return this.rows.filter(function (c) {
                    return c.foto_frente_url || c.foto_dorso_url || c.foto_ci_dorso || c.foto_ci_reverso;
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

                // Filtro rápido por GPS
                if (this.filtroGps === 'con_gps') {
                    list = list.filter(function (r) {
                        return r.latitud && r.longitud;
                    });
                }

                // Filtro rápido por Fotos C.I.
                if (this.filtroFotos === 'con_fotos') {
                    list = list.filter(function (r) {
                        return r.foto_frente_url || r.foto_dorso_url;
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
            toggleFiltroGps: function () {
                this.filtroGps = this.filtroGps === 'con_gps' ? 'todos' : 'con_gps';
                this.currentPage = 1;
            },
            toggleFiltroFotos: function () {
                this.filtroFotos = this.filtroFotos === 'con_fotos' ? 'todos' : 'con_fotos';
                this.currentPage = 1;
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
                    reflaboral: '',
                    latitud: null,
                    longitud: null,
                    ubicacion_url: null,
                    foto_ci_dorso: null,
                    foto_ci_reverso: null,
                    foto_frente_url: null,
                    foto_dorso_url: null
                };
            },
            nuevoCliente: function () {
                var self = this;
                this.form = this.blankForm();
                $('#modalCliente').modal('show');
                this.$nextTick(function () {
                    setTimeout(function () {
                        $('#txtClienteDoc').focus();
                        self.initMapCliente(null, null);
                    }, 300);
                });
            },
            editar: function (c) {
                var self = this;
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
                    reflaboral: c.reflaboral || '',
                    latitud: c.latitud || null,
                    longitud: c.longitud || null,
                    ubicacion_url: c.ubicacion_url || null,
                    foto_ci_dorso: c.foto_ci_dorso || null,
                    foto_ci_reverso: c.foto_ci_reverso || null,
                    foto_frente_url: c.foto_frente_url || null,
                    foto_dorso_url: c.foto_dorso_url || null
                };
                $('#modalCliente').modal('show');
                this.$nextTick(function () {
                    setTimeout(function () {
                        $('#txtClienteDoc').focus().select();
                        self.initMapCliente(c.latitud, c.longitud);
                    }, 300);
                });
            },
            limpiarFiltros: function () {
                this.txtbuscar = '';
                this.filtroCiudad = 0;
                this.filtroGps = 'todos';
                this.filtroFotos = 'todos';
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
                            reflaboral: c.cliente_referencia_laboral || '',
                            latitud: c.cliente_latitud || null,
                            longitud: c.cliente_longitud || null,
                            ubicacion_url: c.cliente_ubicacion_url || null,
                            foto_ci_dorso: c.cliente_foto_ci_dorso || null,
                            foto_ci_reverso: c.cliente_foto_ci_reverso || null,
                            foto_frente_url: c.foto_frente_url || (c.cliente_foto_ci_dorso ? ('{{ asset("storage/clientes") }}/' + c.cliente_foto_ci_dorso) : null),
                            foto_dorso_url: c.foto_dorso_url || (c.cliente_foto_ci_reverso ? ('{{ asset("storage/clientes") }}/' + c.cliente_foto_ci_reverso) : null),
                            tiene_gps: !!(c.cliente_latitud && c.cliente_longitud),
                            gps_url: c.gps_url || (c.cliente_latitud && c.cliente_longitud ? ('https://www.openstreetmap.org/?mlat=' + c.cliente_latitud + '&mlon=' + c.cliente_longitud + '#map=17/' + c.cliente_latitud + '/' + c.cliente_longitud) : null)
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
                        reflaboral: this.form.reflaboral || '',
                        latitud: this.form.latitud || null,
                        longitud: this.form.longitud || null,
                        ubicacion_url: this.form.ubicacion_url || null,
                        foto_ci_dorso: this.form.foto_ci_dorso || null,
                        foto_ci_reverso: this.form.foto_ci_reverso || null
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
            },

            /* ============================================================
             * FUNCIONES DE MAPA (GOOGLE MAPS & OPENSTREETMAP LEAFLET)
             * ============================================================ */
            onGoogleMapsError: function () {
                var self = this;
                this.modoGoogleMapsFallo = true;
                Toast.fire({
                    icon: 'warning',
                    title: 'Fallo al autenticar Google Maps. Usando OpenStreetMap automáticamente.'
                });
                if ($('#modalCliente').is(':visible')) {
                    setTimeout(function () {
                        self.initLeafletMapCliente(self.form.latitud, self.form.longitud);
                    }, 200);
                }
                if ($('#modalVerGps').is(':visible') && this.clienteGpsSeleccionado) {
                    setTimeout(function () {
                        self.initLeafletMapViewer(
                            self.clienteGpsSeleccionado.latitud,
                            self.clienteGpsSeleccionado.longitud,
                            self.clienteGpsSeleccionado.nombre,
                            self.clienteGpsSeleccionado.direccion
                        );
                    }, 200);
                }
            },

            clearMapContainer: function (elementId) {
                var el = document.getElementById(elementId);
                if (el) {
                    if (el._leaflet_id) {
                        delete el._leaflet_id;
                    }
                    el.innerHTML = '';
                }
            },

            initMapCliente: function (lat, lng) {
                if (this.usarGoogleMaps) {
                    this.initGoogleMapCliente(lat, lng);
                } else {
                    this.initLeafletMapCliente(lat, lng);
                }
            },

            initGoogleMapCliente: function (lat, lng) {
                var self = this;
                var defaultLat = parseFloat(this.mapConfig.lat_default) || -25.263740;
                var defaultLng = parseFloat(this.mapConfig.lng_default) || -57.575920;
                var defaultZoom = parseInt(this.mapConfig.zoom_default) || 14;

                var hasCoord = lat && lng && !isNaN(parseFloat(lat)) && !isNaN(parseFloat(lng));
                var curLat = hasCoord ? parseFloat(lat) : defaultLat;
                var curLng = hasCoord ? parseFloat(lng) : defaultLng;
                var zoom = hasCoord ? 16 : defaultZoom;

                var mapEl = document.getElementById('mapCliente');
                if (!mapEl) return;

                // Si existía Leaflet previo, limpiarlo
                if (self.mapCliente) {
                    try { self.mapCliente.remove(); } catch(e){}
                    self.mapCliente = null;
                    self.markerCliente = null;
                }

                var center = { lat: curLat, lng: curLng };

                if (!self.gMapCliente || !mapEl.hasChildNodes()) {
                    self.clearMapContainer('mapCliente');
                    self.gMapCliente = new google.maps.Map(mapEl, {
                        center: center,
                        zoom: zoom,
                        mapTypeControl: true,
                        streetViewControl: true,
                        fullscreenControl: true
                    });

                    self.gMapCliente.addListener('click', function (e) {
                        self.setUbicacionGps(e.latLng.lat(), e.latLng.lng());
                    });
                } else {
                    self.gMapCliente.setCenter(center);
                    self.gMapCliente.setZoom(zoom);
                }

                if (hasCoord) {
                    if (!self.gMarkerCliente) {
                        self.gMarkerCliente = new google.maps.Marker({
                            position: center,
                            map: self.gMapCliente,
                            draggable: true,
                            title: 'Ubicación del Cliente'
                        });
                        self.gMarkerCliente.addListener('dragend', function (e) {
                            self.setUbicacionGps(e.latLng.lat(), e.latLng.lng());
                        });
                    } else {
                        self.gMarkerCliente.setPosition(center);
                        self.gMarkerCliente.setMap(self.gMapCliente);
                    }
                } else {
                    if (self.gMarkerCliente) {
                        self.gMarkerCliente.setMap(null);
                    }
                }

                setTimeout(function () {
                    if (self.gMapCliente) {
                        google.maps.event.trigger(self.gMapCliente, 'resize');
                        self.gMapCliente.setCenter(center);
                    }
                }, 250);
            },

            initLeafletMapCliente: function (lat, lng) {
                var self = this;
                var defaultLat = parseFloat(this.mapConfig.lat_default) || -25.263740;
                var defaultLng = parseFloat(this.mapConfig.lng_default) || -57.575920;
                var defaultZoom = parseInt(this.mapConfig.zoom_default) || 14;

                var hasCoord = lat && lng && !isNaN(parseFloat(lat)) && !isNaN(parseFloat(lng));
                var curLat = hasCoord ? parseFloat(lat) : defaultLat;
                var curLng = hasCoord ? parseFloat(lng) : defaultLng;
                var zoom = hasCoord ? 16 : defaultZoom;

                var mapEl = document.getElementById('mapCliente');
                if (!mapEl) return;

                // Si existía Google Maps previo, limpiarlo
                if (self.gMapCliente) {
                    self.clearMapContainer('mapCliente');
                    self.gMapCliente = null;
                    self.gMarkerCliente = null;
                }

                if (!self.mapCliente || !mapEl.classList.contains('leaflet-container')) {
                    self.clearMapContainer('mapCliente');
                    self.mapCliente = L.map('mapCliente').setView([curLat, curLng], zoom);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>'
                    }).addTo(self.mapCliente);

                    self.mapCliente.on('click', function (e) {
                        self.setUbicacionGps(e.latlng.lat, e.latlng.lng);
                    });
                } else {
                    self.mapCliente.setView([curLat, curLng], zoom);
                }

                if (hasCoord) {
                    if (!self.markerCliente) {
                        self.markerCliente = L.marker([curLat, curLng], { draggable: true }).addTo(self.mapCliente);
                        self.markerCliente.on('dragend', function (e) {
                            var pos = e.target.getLatLng();
                            self.setUbicacionGps(pos.lat, pos.lng);
                        });
                    } else {
                        self.markerCliente.setLatLng([curLat, curLng]);
                        if (!self.mapCliente.hasLayer(self.markerCliente)) {
                            self.markerCliente.addTo(self.mapCliente);
                        }
                    }
                } else {
                    if (self.markerCliente && self.mapCliente.hasLayer(self.markerCliente)) {
                        self.mapCliente.removeLayer(self.markerCliente);
                    }
                }

                setTimeout(function () {
                    if (self.mapCliente) self.mapCliente.invalidateSize();
                }, 250);
            },

            setUbicacionGps: function (lat, lng) {
                var latFixed = parseFloat(lat).toFixed(6);
                var lngFixed = parseFloat(lng).toFixed(6);
                this.form.latitud = latFixed;
                this.form.longitud = lngFixed;
                this.form.ubicacion_url = 'https://www.google.com/maps?q=' + latFixed + ',' + lngFixed;

                var fLat = parseFloat(latFixed);
                var fLng = parseFloat(lngFixed);

                if (this.usarGoogleMaps) {
                    var center = { lat: fLat, lng: fLng };
                    if (!this.gMarkerCliente) {
                        var self = this;
                        this.gMarkerCliente = new google.maps.Marker({
                            position: center,
                            map: this.gMapCliente,
                            draggable: true,
                            title: 'Ubicación del Cliente'
                        });
                        this.gMarkerCliente.addListener('dragend', function (e) {
                            self.setUbicacionGps(e.latLng.lat(), e.latLng.lng());
                        });
                    } else {
                        this.gMarkerCliente.setPosition(center);
                        if (this.gMapCliente) this.gMarkerCliente.setMap(this.gMapCliente);
                    }
                } else {
                    if (!this.markerCliente) {
                        var self = this;
                        this.markerCliente = L.marker([fLat, fLng], { draggable: true }).addTo(this.mapCliente);
                        this.markerCliente.on('dragend', function (e) {
                            var pos = e.target.getLatLng();
                            self.setUbicacionGps(pos.lat, pos.lng);
                        });
                    } else {
                        this.markerCliente.setLatLng([fLat, fLng]);
                        if (this.mapCliente && !this.mapCliente.hasLayer(this.markerCliente)) {
                            this.markerCliente.addTo(this.mapCliente);
                        }
                    }
                }
            },

            limpiarGps: function () {
                this.form.latitud = null;
                this.form.longitud = null;
                this.form.ubicacion_url = null;
                if (this.markerCliente && this.mapCliente && this.mapCliente.hasLayer(this.markerCliente)) {
                    this.mapCliente.removeLayer(this.markerCliente);
                }
                if (this.gMarkerCliente) {
                    this.gMarkerCliente.setMap(null);
                }
                Toast.fire({ icon: 'info', title: 'Coordenadas GPS eliminadas' });
            },

            obtenerUbicacionActual: function () {
                var self = this;
                if (!navigator.geolocation) {
                    Swal.fire('No compatible', 'El navegador no soporta geolocalización GPS.', 'warning');
                    return;
                }
                Toast.fire({ icon: 'info', title: 'Obteniendo GPS del dispositivo...' });
                navigator.geolocation.getCurrentPosition(
                    function (pos) {
                        var lat = pos.coords.latitude;
                        var lng = pos.coords.longitude;
                        self.setUbicacionGps(lat, lng);
                        if (self.usarGoogleMaps && self.gMapCliente) {
                            self.gMapCliente.setCenter({ lat: lat, lng: lng });
                            self.gMapCliente.setZoom(17);
                        } else if (self.mapCliente) {
                            self.mapCliente.setView([lat, lng], 17);
                            self.mapCliente.invalidateSize();
                        }
                        Toast.fire({ icon: 'success', title: 'Ubicación GPS fijada correctamente' });
                    },
                    function (err) {
                        var msg = 'No se pudo obtener la posición GPS actual.';
                        if (err.code === 1) msg = 'Permiso de geolocalización denegado en el navegador.';
                        else if (err.code === 2) msg = 'Posición GPS no disponible en este dispositivo.';
                        else if (err.code === 3) msg = 'Tiempo de espera agotado al consultar GPS.';
                        Swal.fire('GPS', msg, 'info');
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            },

            actualizarMarcadorDesdeInputs: function () {
                var lat = parseFloat(this.form.latitud);
                var lng = parseFloat(this.form.longitud);
                if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                    this.setUbicacionGps(lat, lng);
                    if (this.usarGoogleMaps && this.gMapCliente) {
                        this.gMapCliente.setCenter({ lat: lat, lng: lng });
                        this.gMapCliente.setZoom(16);
                    } else if (this.mapCliente) {
                        this.mapCliente.setView([lat, lng], 16);
                    }
                }
            },

            verGpsModal: function (row) {
                var self = this;
                this.clienteGpsSeleccionado = row;
                $('#modalVerGps').modal('show');
                this.$nextTick(function () {
                    setTimeout(function () {
                        self.initMapViewer(row.latitud, row.longitud, row.nombre, row.direccion);
                    }, 300);
                });
            },

            initMapViewer: function (lat, lng, nombre, direccion) {
                if (this.usarGoogleMaps) {
                    this.initGoogleMapViewer(lat, lng, nombre, direccion);
                } else {
                    this.initLeafletMapViewer(lat, lng, nombre, direccion);
                }
            },

            initGoogleMapViewer: function (lat, lng, nombre, direccion) {
                var self = this;
                var curLat = parseFloat(lat);
                var curLng = parseFloat(lng);
                if (isNaN(curLat) || isNaN(curLng)) return;

                var mapEl = document.getElementById('mapPreviewViewer');
                if (!mapEl) return;

                if (self.gpsViewerMap) {
                    try { self.gpsViewerMap.remove(); } catch(e){}
                    self.gpsViewerMap = null;
                    self.gpsViewerMarker = null;
                }

                var center = { lat: curLat, lng: curLng };

                if (!self.gMapViewer || !mapEl.hasChildNodes()) {
                    self.clearMapContainer('mapPreviewViewer');
                    self.gMapViewer = new google.maps.Map(mapEl, {
                        center: center,
                        zoom: 16,
                        mapTypeControl: true,
                        streetViewControl: true,
                        fullscreenControl: true
                    });

                    self.gMarkerViewer = new google.maps.Marker({
                        position: center,
                        map: self.gMapViewer,
                        title: nombre || 'Cliente'
                    });
                } else {
                    self.gMapViewer.setCenter(center);
                    self.gMapViewer.setZoom(16);
                    if (self.gMarkerViewer) {
                        self.gMarkerViewer.setPosition(center);
                        self.gMarkerViewer.setMap(self.gMapViewer);
                    }
                }

                var infoWindow = new google.maps.InfoWindow({
                    content: '<div style="font-family: Cairo, sans-serif; font-size: 0.9rem;"><strong style="color: #059669;">' + (nombre || 'Cliente') + '</strong><br><small class="text-muted">' + (direccion || 'Sin dirección') + '</small></div>'
                });
                infoWindow.open(self.gMapViewer, self.gMarkerViewer);

                setTimeout(function () {
                    if (self.gMapViewer) {
                        google.maps.event.trigger(self.gMapViewer, 'resize');
                        self.gMapViewer.setCenter(center);
                    }
                }, 250);
            },

            initLeafletMapViewer: function (lat, lng, nombre, direccion) {
                var self = this;
                var curLat = parseFloat(lat);
                var curLng = parseFloat(lng);
                if (isNaN(curLat) || isNaN(curLng)) return;

                var mapEl = document.getElementById('mapPreviewViewer');
                if (!mapEl) return;

                if (self.gMapViewer) {
                    self.clearMapContainer('mapPreviewViewer');
                    self.gMapViewer = null;
                    self.gMarkerViewer = null;
                }

                if (!self.gpsViewerMap || !mapEl.classList.contains('leaflet-container')) {
                    self.clearMapContainer('mapPreviewViewer');
                    self.gpsViewerMap = L.map('mapPreviewViewer').setView([curLat, curLng], 16);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap'
                    }).addTo(self.gpsViewerMap);

                    self.gpsViewerMarker = L.marker([curLat, curLng]).addTo(self.gpsViewerMap);
                } else {
                    self.gpsViewerMap.setView([curLat, curLng], 16);
                    self.gpsViewerMarker.setLatLng([curLat, curLng]);
                }

                var popup = '<div style="font-family: Cairo, sans-serif; font-size: 0.9rem;"><strong style="color: #059669;">' + (nombre || 'Cliente') + '</strong><br><small class="text-muted">' + (direccion || 'Sin dirección') + '</small></div>';
                self.gpsViewerMarker.bindPopup(popup).openPopup();

                setTimeout(function () {
                    if (self.gpsViewerMap) self.gpsViewerMap.invalidateSize();
                }, 250);
            },

            /* ============================================================
             * FUNCIONES DE DOCUMENTOS DE IDENTIDAD (CARA Y REVERSO)
             * ============================================================ */
            seleccionarFoto: function (tipo) {
                if (tipo === 'frente') {
                    if (this.$refs.inputFotoFrente) this.$refs.inputFotoFrente.click();
                } else {
                    if (this.$refs.inputFotoDorso) this.$refs.inputFotoDorso.click();
                }
            },

            subirFoto: function (event, tipo) {
                var self = this;
                var file = event.target.files[0];
                if (!file) return;

                if (file.size > 10 * 1024 * 1024) {
                    Swal.fire('Archivo muy grande', 'La imagen no debe superar 10 MB.', 'warning');
                    event.target.value = '';
                    return;
                }

                var formData = new FormData();
                formData.append('foto', file);
                formData.append('tipo', tipo);
                if (self.form.id) {
                    formData.append('id_cliente', self.form.id);
                }

                if (tipo === 'frente') self.subiendoFotoFrente = true;
                else self.subiendoFotoDorso = true;

                axios.post('{{ url("cliente/foto") }}', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                }).then(function (res) {
                    if (tipo === 'frente') {
                        self.subiendoFotoFrente = false;
                        self.form.foto_ci_dorso = res.data.filename;
                        self.form.foto_frente_url = res.data.url;
                    } else {
                        self.subiendoFotoDorso = false;
                        self.form.foto_ci_reverso = res.data.filename;
                        self.form.foto_dorso_url = res.data.url;
                    }
                    event.target.value = '';
                    Toast.fire({ icon: 'success', title: res.data.message || 'Foto cargada correctamente' });
                    if (self.form.id) {
                        self.buscar(true);
                    }
                }).catch(function (err) {
                    if (tipo === 'frente') self.subiendoFotoFrente = false;
                    else self.subiendoFotoDorso = false;
                    event.target.value = '';
                    var msg = (err.response && err.response.data && err.response.data.message) || 'Error al subir la fotografía.';
                    Swal.fire('Error', msg, 'error');
                });
            },

            eliminarFoto: function (tipo) {
                var self = this;
                var titulo = tipo === 'frente' ? '¿Eliminar foto frontal de la cédula?' : '¿Eliminar foto posterior/dorso de la cédula?';
                Swal.fire({
                    title: titulo,
                    text: 'Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#ef4444'
                }).then(function (res) {
                    if (!res.value) return;

                    var filename = tipo === 'frente' ? self.form.foto_ci_dorso : self.form.foto_ci_reverso;
                    axios.post('{{ url("cliente/foto/eliminar") }}', {
                        tipo: tipo,
                        id_cliente: self.form.id || null,
                        filename: filename
                    }).then(function () {
                        if (tipo === 'frente') {
                            self.form.foto_ci_dorso = null;
                            self.form.foto_frente_url = null;
                        } else {
                            self.form.foto_ci_reverso = null;
                            self.form.foto_dorso_url = null;
                        }
                        Toast.fire({ icon: 'success', title: 'Foto eliminada correctamente' });
                        if (self.form.id) {
                            self.buscar(true);
                        }
                    }).catch(function () {
                        Swal.fire('Error', 'No se pudo eliminar la imagen.', 'error');
                    });
                });
            },

            verFotosDoc: function (row) {
                this.docViewerCliente = row;
                this.docViewerTipo = row.foto_frente_url ? 'frente' : 'dorso';
                $('#modalFotoDocViewer').modal('show');
            },

            ampliarFoto: function (url, titulo) {
                this.fotoAmpliadaUrl = url;
                this.fotoAmpliadaTitulo = titulo || 'Visualización de Documento';
                $('#modalFotoIndividual').modal('show');
            }
        },
        mounted: function () {
            this.buscar(false);

            // Ajustar mapas al abrir modales de Bootstrap (Google Maps y Leaflet)
            $('#modalCliente').on('shown.bs.modal', function () {
                if (app.usarGoogleMaps) {
                    if (app.gMapCliente) {
                        google.maps.event.trigger(app.gMapCliente, 'resize');
                        var lat = parseFloat(app.form.latitud) || (parseFloat(app.mapConfig.lat_default) || -25.263740);
                        var lng = parseFloat(app.form.longitud) || (parseFloat(app.mapConfig.lng_default) || -57.575920);
                        app.gMapCliente.setCenter({ lat: lat, lng: lng });
                    } else {
                        app.initMapCliente(app.form.latitud, app.form.longitud);
                    }
                } else {
                    if (app.mapCliente) {
                        app.mapCliente.invalidateSize();
                    } else {
                        app.initMapCliente(app.form.latitud, app.form.longitud);
                    }
                }
            });

            $('#modalVerGps').on('shown.bs.modal', function () {
                if (app.usarGoogleMaps) {
                    if (app.gMapViewer && app.clienteGpsSeleccionado) {
                        google.maps.event.trigger(app.gMapViewer, 'resize');
                        var cLat = parseFloat(app.clienteGpsSeleccionado.latitud);
                        var cLng = parseFloat(app.clienteGpsSeleccionado.longitud);
                        if (!isNaN(cLat) && !isNaN(cLng)) {
                            app.gMapViewer.setCenter({ lat: cLat, lng: cLng });
                        }
                    }
                } else {
                    if (app.gpsViewerMap) {
                        app.gpsViewerMap.invalidateSize();
                    }
                }
            });
        }
    });

    activarMenu('m_cliente', '');
</script>
@endsection
