@extends('layouts.app')
@section('title', 'Articulo')
@section('style')
<link href="{{ asset('css/icheck-bootstrap.min.css') }}" rel="stylesheet">
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

    /* Reglas Globales y de Modo Oscuro */
    .product-title {
        font-size: 0.93rem;
        color: var(--dash-text-main);
    }
    .price-display {
        font-size: 1rem;
        color: var(--dash-primary) !important;
        white-space: nowrap;
    }
    body.dark-mode .price-display {
        color: #34d399 !important;
    }

    /* Buscador & Inputs */
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
    body.dark-mode .toolbar-card {
        background-color: var(--dash-card-bg);
        border-color: var(--dash-border);
    }
    body.dark-mode .search-clear-btn {
        border-color: var(--dash-border) !important;
        background-color: var(--dash-card-bg) !important;
        color: var(--dash-text-muted) !important;
    }
    body.dark-mode .search-clear-btn:hover {
        color: #f87171 !important;
    }
    body.dark-mode .bg-white {
        background-color: var(--dash-card-bg) !important;
        color: var(--dash-text-main) !important;
    }
    body.dark-mode .bg-light {
        background-color: #111827 !important;
        color: var(--dash-text-main) !important;
    }
    body.dark-mode .text-dark {
        color: var(--dash-text-main) !important;
    }
    body.dark-mode .text-secondary {
        color: var(--dash-text-muted) !important;
    }
    body.dark-mode .card {
        background-color: var(--dash-card-bg) !important;
        border-color: var(--dash-border) !important;
    }
    body.dark-mode .modal-body {
        background-color: var(--dash-card-bg) !important;
        color: var(--dash-text-main);
    }
    body.dark-mode .modal-footer {
        background-color: #111827 !important;
        border-color: var(--dash-border) !important;
    }

    /* Botón Secundario en Modo Oscuro */
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

    /* Badges de Sección */
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
    body.dark-mode .badge-section {
        background: #111827;
        color: #9ca3af;
        border-color: #374151;
    }

    /* Badges de Código de Barra */
    body.dark-mode .badge-barcode {
        background: #111827;
        color: #cbd5e1;
        border-color: #374151;
    }

    /* Badges de Stock en Modo Oscuro */
    body.dark-mode .badge-stock-in {
        background-color: rgba(16, 185, 129, 0.2) !important;
        color: #6ee7b7 !important;
        border-color: #059669 !important;
    }
    body.dark-mode .badge-stock-low {
        background-color: rgba(245, 158, 11, 0.2) !important;
        color: #fde68a !important;
        border-color: #d97706 !important;
    }
    body.dark-mode .badge-stock-out {
        background-color: rgba(239, 68, 68, 0.2) !important;
        color: #fca5a5 !important;
        border-color: #dc2626 !important;
    }

    /* Tabla en Modo Oscuro */
    body.dark-mode .table-card {
        background: var(--dash-card-bg);
        border-color: var(--dash-border);
    }
    body.dark-mode .table-toolbar-head {
        background: var(--dash-card-bg);
        border-color: var(--dash-border);
        color: var(--dash-text-main);
    }
    body.dark-mode .custom-select-perpage {
        background-color: #111827 !important;
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

    /* Paginación en Modo Oscuro */
    body.dark-mode .table-pagination-footer {
        background: var(--dash-card-bg) !important;
        border-color: var(--dash-border) !important;
        color: var(--dash-text-main) !important;
    }
    body.dark-mode .page-nav-btn {
        background: #111827 !important;
        border-color: var(--dash-border) !important;
        color: var(--dash-text-main) !important;
    }
    body.dark-mode .page-nav-btn:hover:not(:disabled) {
        background: var(--dash-primary-light) !important;
        border-color: var(--dash-primary-border) !important;
        color: #34d399 !important;
    }
    body.dark-mode .page-number-btn {
        background: #111827 !important;
        border-color: var(--dash-border) !important;
        color: var(--dash-text-main) !important;
    }
    body.dark-mode .page-number-btn:hover:not(.active):not(.dots) {
        background: var(--dash-primary-light) !important;
        border-color: var(--dash-primary-border) !important;
        color: #34d399 !important;
    }

    /* Botones de Acción en Fila */
    body.dark-mode .btn-action-edit {
        background: rgba(16, 185, 129, 0.15) !important;
        color: #34d399 !important;
        border-color: rgba(16, 185, 129, 0.3) !important;
    }
    body.dark-mode .btn-action-edit:hover {
        background: #059669 !important;
        color: #ffffff !important;
    }
    body.dark-mode .btn-action-dropdown {
        background: rgba(16, 185, 129, 0.15) !important;
        color: #34d399 !important;
        border-color: rgba(16, 185, 129, 0.3) !important;
    }
    body.dark-mode .btn-action-dropdown:hover {
        background: #059669 !important;
        color: #ffffff !important;
    }

    /* Menús Desplegables en Modo Oscuro */
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

    /* Modales en Modo Oscuro */
    body.dark-mode .modal-content {
        background-color: #1f2937 !important;
        color: #e2e8f0 !important;
        border: 1px solid #374151 !important;
    }
    body.dark-mode .modal-header.bg-light,
    body.dark-mode .modal-footer.bg-light {
        background-color: #111827 !important;
        border-color: #374151 !important;
        color: #e2e8f0 !important;
    }
    body.dark-mode .list-group-item {
        background-color: #1f2937 !important;
        color: #e2e8f0 !important;
        border-color: #374151 !important;
    }
    body.dark-mode .list-group-item:hover {
        background-color: #111827 !important;
    }
    body.dark-mode .list-group-item.bg-light {
        background-color: #111827 !important;
        color: #34d399 !important;
    }
    body.dark-mode #modalfiltro .badge-light {
        background-color: #111827 !important;
        color: #9ca3af !important;
        border-color: #374151 !important;
    }
    body.dark-mode #modalexportar .list-group-item h6 {
        color: #f3f4f6 !important;
    }
    body.dark-mode #modalexportar .rounded-circle {
        opacity: 0.9;
    }

    #app {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    /* Cabecera idéntica a Home */
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

    /* Botones POS idénticos a Home */
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

    /* Tarjetas KPI de Home */
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

    /* Card Contenedora */
    .table-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }

    /* Vue Good Table adaptada al Dashboard */
    .vgt-table {
        border: none !important;
        font-family: inherit;
    }
    .vgt-table thead th {
        background: #f8fafc !important;
        color: var(--dash-text-muted) !important;
        font-size: 0.78rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
        border-bottom: 1px solid var(--dash-border) !important;
        border-top: none !important;
        padding: 0.85rem 1rem !important;
    }
    .vgt-table tbody td {
        color: var(--dash-text-main) !important;
        vertical-align: middle !important;
        border-bottom: 1px solid var(--dash-border) !important;
        padding: 0.75rem 1rem !important;
    }
    .vgt-table.striped tbody tr:nth-of-type(odd) {
        background-color: #fafbfc;
    }
    .vgt-table tbody tr:hover {
        background-color: var(--dash-primary-light) !important;
    }

    /* Estilos de la Tabla Personalizada Dashboard (Sin librerías de terceros) */
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
    body.dark-mode .table-responsive::-webkit-scrollbar-track {
        background: #1f2937;
    }
    body.dark-mode .table-responsive::-webkit-scrollbar-thumb {
        background: #374151;
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

    /* Estado vacío */
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

    /* Badges de Stock */
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
    .badge-stock-low {
        background-color: #fef3c7;
        color: #92400e;
        font-weight: 700;
        border: 1px solid #fde68a;
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

    /* Badge código de barra */
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

    /* Botón Edición Rápida en tabla */
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

    #modalPromo .modal-content {
        height: auto;
        border-radius: 12px;
        overflow: hidden;
    }
    #modalPromo .promo-item {
        border: 1px solid var(--dash-border);
        border-radius: 8px;
        padding: 0.65rem 0.75rem;
        margin-bottom: 0.5rem;
    }
    #modalPromo .promo-item-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }
    #modalPromo .promo-item-title {
        flex: 1 1 auto;
        min-width: 0;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.35rem;
    }
    #modalPromo .promo-item-actions {
        flex: 0 0 auto;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        white-space: nowrap;
    }
</style>
@endsection
@section('main')
    <div class="container-fluid px-3 py-3" id="app" v-cloak>
        <!-- Cabecera estilo Dashboard Inicio -->
        <div class="dash-header">
            <div>
                <h1 class="dash-header-title font-cairo">
                    <i class="fa-solid fa-boxes-stacked mr-2"></i>Artículos y Productos
                </h1>
                <p class="dash-header-subtitle">
                    Catálogo de productos, control de stock por sucursales y gestión de precios
                </p>
            </div>
            <div class="dash-header-badges">
                <button type="button" class="btn-pos-primary" @click="showMArticulo">
                    <i class="fa fa-plus-circle"></i> Nuevo Artículo
                </button>
                <a href="{{ route('articulo.cm') }}" class="btn-pos-secondary" title="Formulario extendido con cámara y fotos">
                    <i class="fa fa-camera"></i> ABM con Fotos
                </a>
            </div>
        </div>

        <!-- Tarjetas KPI de Resumen estilo Inicio -->
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="kpi-card" style="border-left: 4px solid #0284c7;">
                    <div class="kpi-header">
                        <span class="kpi-label">Total Productos</span>
                        <div class="kpi-icon-box kpi-icon-blue">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </div>
                    </div>
                    <div class="kpi-value font-cairo">@{{ articulos.length }}</div>
                    <div class="kpi-subtext">
                        <i class="fa-solid fa-layer-group text-muted"></i> En catálogo general
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="kpi-card" style="border-left: 4px solid #16a34a;">
                    <div class="kpi-header">
                        <span class="kpi-label">Con Stock</span>
                        <div class="kpi-icon-box kpi-icon-green">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                    <div class="kpi-value font-cairo" style="color: #166534;">@{{ totalConStock }}</div>
                    <div class="kpi-subtext" style="color: #166534;">
                        <i class="fa-solid fa-arrow-trend-up"></i> Disponibles para venta
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="kpi-card" style="border-left: 4px solid #dc2626;">
                    <div class="kpi-header">
                        <span class="kpi-label">Sin Stock / Agotados</span>
                        <div class="kpi-icon-box kpi-icon-amber">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                    </div>
                    <div class="kpi-value font-cairo" style="color: #b91c1c;">@{{ totalSinStock }}</div>
                    <div class="kpi-subtext" style="color: #b91c1c;">
                        <i class="fa-solid fa-circle-exclamation"></i> Requieren reposición
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="kpi-card" style="border-left: 4px solid #7e22ce;">
                    <div class="kpi-header">
                        <span class="kpi-label">Ofertas / Combos</span>
                        <div class="kpi-icon-box kpi-icon-purple">
                            <i class="fa-solid fa-tags"></i>
                        </div>
                    </div>
                    <div class="kpi-value font-cairo" style="color: #7e22ce;">@{{ totalConPromo }}</div>
                    <div class="kpi-subtext" style="color: #7e22ce;">
                        <i class="fa-solid fa-star"></i> Promociones activas
                    </div>
                </div>
            </div>
        </div>

        <!-- Barra de Búsqueda y Filtros Rápidos -->
        <div class="table-card toolbar-card mb-3">
            <div class="card-body py-2 px-3">
                <div class="row align-items-center">
                    <!-- Buscador predictivo: 100% en pantallas menores a 1200px (móviles/tablets) y 5 cols en pantallas anchas -->
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
                                placeholder="Buscar artículo por nombre o código de barra..."
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

                    <!-- Filtro por Sección / Categoría directo -->
                    <div class="col-12 col-sm-6 col-xl-3 mb-2 mb-xl-0">
                        <select class="form-control select-seccion" v-model="filtro.seccion" @change="buscar(false)" style="border-radius: 8px; font-size: 0.92rem;">
                            <option value="0">Todas las Secciones</option>
                            @foreach ($secciones as $seccion)
                                <option value="{{ $seccion['present_cod'] }}">{{ $seccion['present_descripcion'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Botones de Acción & Enlaces -->
                    <div class="col-12 col-sm-6 col-xl-4 text-left text-sm-right">
                        <div class="toolbar-actions-wrap">
                            <button type="button" class="btn-pos-secondary" data-toggle="modal" data-target="#modalfiltro" title="Más filtros de sección">
                                <i class="fa fa-filter"></i> Filtros
                            </button>
                            <button type="button" class="btn-pos-secondary" data-toggle="modal" data-target="#modalexportar" title="Exportar datos a Excel">
                                <i class="fa fa-file-excel text-success"></i> Exportar
                            </button>
                            <div class="dropdown d-inline-block">
                                <button type="button" class="btn-pos-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-layer-group text-info"></i> Promos
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow-sm border-0" style="border-radius: 10px;">
                                    <a class="dropdown-item py-2" href="{{ route('combo.index') }}">
                                        <i class="fa fa-layer-group text-info mr-2"></i> Gestión de Combos
                                    </a>
                                    <a class="dropdown-item py-2" href="{{ route('oferta.index') }}">
                                        <i class="fa fa-tags text-danger mr-2"></i> Gestión de Ofertas
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla Personalizada Dashboard (Sin librerías externas) -->
        <div class="table-card mb-4">
            <!-- Barra superior informativa de la tabla -->
            <div class="table-toolbar-head">
                <div class="d-flex align-items-center flex-wrap">
                    <span class="text-muted small">
                        Mostrando <strong>@{{ paginatedRows.length }}</strong> de <strong>@{{ filteredRows.length }}</strong> productos encontrados
                    </span>
                    <span v-if="txtbuscar" class="badge badge-light border text-secondary ml-2 py-1 px-2">
                        Búsqueda: "@{{ txtbuscar }}"
                        <i class="fa fa-times text-danger ml-1 cursor-pointer" @click="txtbuscar = ''" title="Quitar filtro de búsqueda"></i>
                    </span>
                    <span v-if="filtro.seccion != 0" class="badge badge-light border text-secondary ml-2 py-1 px-2">
                        Sección activa
                        <i class="fa fa-times text-danger ml-1 cursor-pointer" @click="filtro.seccion = 0; buscar(false)" title="Quitar filtro de sección"></i>
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
                            <th class="cursor-pointer sortable text-nowrap" @click="sortBy('codigo')" style="width: 130px; min-width: 120px;">
                                <span>Código</span>
                                <i class="fa-solid ml-1 text-muted" :class="getSortIcon('codigo')"></i>
                            </th>
                            <th class="cursor-pointer sortable text-nowrap" @click="sortBy('descripcion')" style="min-width: 220px;">
                                <span>Descripción / Producto</span>
                                <i class="fa-solid ml-1 text-muted" :class="getSortIcon('descripcion')"></i>
                            </th>
                            <th class="cursor-pointer sortable text-nowrap" @click="sortBy('seccion')" style="width: 140px; min-width: 120px;">
                                <span>Sección</span>
                                <i class="fa-solid ml-1 text-muted" :class="getSortIcon('seccion')"></i>
                            </th>
                            <th class="text-center text-nowrap" style="width: 95px; min-width: 85px;">
                                <span>Promo</span>
                            </th>
                            <th class="cursor-pointer sortable text-right text-nowrap" @click="sortBy('precio')" style="width: 140px; min-width: 125px;">
                                <span>Precio Venta</span>
                                <i class="fa-solid ml-1 text-muted" :class="getSortIcon('precio')"></i>
                            </th>
                            <th class="cursor-pointer sortable text-center text-nowrap" @click="sortBy('stock')" style="width: 125px; min-width: 110px;">
                                <span>Stock</span>
                                <i class="fa-solid ml-1 text-muted" :class="getSortIcon('stock')"></i>
                            </th>
                            <th class="text-center text-nowrap" style="width: 125px; min-width: 115px;">
                                <span>Acciones</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Estado de Carga -->
                        <tr v-if="requestSend">
                            <td colspan="7" class="text-center py-5">
                                <div class="spinner-border text-success" role="status" style="width: 2.5rem; height: 2.5rem; color: var(--dash-primary) !important;">
                                    <span class="sr-only">Cargando...</span>
                                </div>
                                <div class="mt-2 font-weight-bold text-muted small">Cargando catálogo de artículos...</div>
                            </td>
                        </tr>

                        <!-- Filas de Productos -->
                        <template v-else-if="paginatedRows.length > 0">
                            <tr v-for="row in paginatedRows" :key="row.ARTICULOS_cod">
                                <!-- Código de Barra -->
                                <td class="align-middle text-nowrap">
                                    <span v-if="row.codigo" class="badge-barcode">
                                        <i class="fa fa-barcode mr-1 text-muted"></i>@{{ row.codigo }}
                                    </span>
                                    <span v-else class="text-muted small">—</span>
                                </td>

                                <!-- Descripción y Ubicación -->
                                <td class="align-middle" style="min-width: 220px;">
                                    <div class="font-weight-bold product-title">@{{ row.descripcion }}</div>
                                    <div class="small text-muted mt-1" v-if="row.ubicacion">
                                        <i class="fa fa-location-dot mr-1" style="color: var(--dash-accent);"></i>@{{ row.ubicacion }}
                                    </div>
                                </td>

                                <!-- Sección -->
                                <td class="align-middle text-nowrap">
                                    <span class="badge-section">
                                        @{{ row.seccion }}
                                    </span>
                                </td>

                                <!-- Promo (Ofertas y Combos) -->
                                <td class="align-middle text-center text-nowrap">
                                    <span v-if="row.tiene_oferta" class="badge badge-danger mr-1 badge-promo cursor-pointer" @click="verPromo(row.ARTICULOS_cod, 'oferta')" title="Ver oferta activa">
                                        OFERTA
                                    </span>
                                    <span v-if="row.en_combo" class="badge badge-info badge-promo cursor-pointer" @click="verPromo(row.ARTICULOS_cod, 'combo')" title="Ver combo activo">
                                        COMBO
                                    </span>
                                    <span v-if="!row.tiene_oferta && !row.en_combo" class="text-muted small">—</span>
                                </td>

                                <!-- Precio -->
                                <td class="align-middle text-right text-nowrap">
                                    <span class="font-weight-bold font-cairo price-display">
                                        Gs. @{{ row.precio_formateado }}
                                    </span>
                                </td>

                                <!-- Stock con Semáforo -->
                                <td class="align-middle text-center text-nowrap">
                                    <span v-if="row.stock <= 0" class="badge-stock-out">
                                        <i class="fa-solid fa-circle-xmark mr-1"></i>0 Agotado
                                    </span>
                                    <span v-else-if="row.stock <= 5" class="badge-stock-low">
                                        <i class="fa-solid fa-triangle-exclamation mr-1"></i>@{{ row.stock }} Bajo
                                    </span>
                                    <span v-else class="badge-stock-in">
                                        <i class="fa-solid fa-circle-check mr-1"></i>@{{ row.stock }}
                                    </span>
                                </td>

                                <!-- Acciones en 1 Clic -->
                                <td class="align-middle text-center text-nowrap" style="width: 125px; min-width: 115px;">
                                    <div class="btn-group">
                                        <button type="button" class="btn-action-edit" @click="showEArticulo(row.ARTICULOS_cod)" title="Edición Rápida">
                                            <i class="fa fa-pen mr-1"></i> Editar
                                        </button>
                                        <button type="button" class="btn-action-dropdown dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="sr-only">Opciones</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right shadow-sm border-0" style="border-radius: 10px;">
                                            <button class="dropdown-item py-2" @click="showEArticulo(row.ARTICULOS_cod)">
                                                <i class="fa fa-pen text-primary mr-2"></i> Editar Artículo
                                            </button>
                                            <button class="dropdown-item py-2" @click="verPreciosCredito(row.ARTICULOS_cod, row.costo)">
                                                <i class="fa fa-credit-card text-info mr-2"></i> Precios a Crédito
                                            </button>
                                            <button class="dropdown-item py-2" @click="showDetalle(row.ARTICULOS_cod, row.descripcion)">
                                                <i class="fa fa-retweet text-success mr-2"></i> Stock / Transferir
                                            </button>
                                            <button class="dropdown-item py-2" @click="duplicar(row.ARTICULOS_cod)">
                                                <i class="fa fa-copy text-warning mr-2"></i> Duplicar Artículo
                                            </button>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item py-2" :href="'{{ url('articulo/cm') }}/' + row.ARTICULOS_cod">
                                                <i class="fa fa-camera text-secondary mr-2"></i> ABM Completo / Fotos
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <button class="dropdown-item py-2 text-danger" @click="modalDelete(row.ARTICULOS_cod, row.descripcion)">
                                                <i class="fa fa-trash text-danger mr-2"></i> Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <!-- Estado Vacío -->
                        <tr v-else>
                            <td colspan="7" class="text-center py-5">
                                <div class="empty-state-box">
                                    <div class="empty-state-icon mb-3">
                                        <i class="fa-solid fa-boxes-stacked fa-2x"></i>
                                    </div>
                                    <h5 class="font-weight-bold mb-1" style="color: var(--dash-text-main);">No se encontraron productos</h5>
                                    <p class="text-muted small mb-3">No hay artículos que coincidan con los criterios de búsqueda o filtro.</p>
                                    <div class="d-flex justify-content-center">
                                        <button v-if="txtbuscar || filtro.seccion != 0" type="button" class="btn-pos-secondary mr-2" @click="txtbuscar = ''; filtro.seccion = 0; buscar(false)">
                                            <i class="fa fa-undo mr-1"></i> Limpiar Filtros
                                        </button>
                                        <button type="button" class="btn-pos-primary" @click="showMArticulo">
                                            <i class="fa fa-plus-circle mr-1"></i> Crear Nuevo Artículo
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación Moderna del Dashboard -->
            <div class="table-pagination-footer" v-if="filteredRows.length > 0">
                <div class="text-muted small">
                    Mostrando <strong>@{{ paginationFrom }}</strong> a <strong>@{{ paginationTo }}</strong> de <strong>@{{ filteredRows.length }}</strong> artículos
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

                @include('articulo.modal')
        @include('articulo.delete')
        @include('articulo.detalle')
        @include('articulo.precio')
        <!-- Modal Filtros Avanzados / Por Sección -->
        <div class="modal fade" id="modalfiltro" tabindex="-1" role="dialog" aria-labelledby="modalFiltroLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-light border-bottom">
                        <h6 class="modal-title font-weight-bold mb-0" style="color: var(--dash-text-main);" id="modalFiltroLabel">
                            <i class="fa fa-filter text-primary mr-2"></i>Filtrar por Sección / Categoría
                        </h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="list-group list-group-flush">
                            <label class="list-group-item list-group-item-action d-flex align-items-center mb-0 cursor-pointer" :class="{'bg-light font-weight-bold text-primary': filtro.seccion == 0}">
                                <div class="icheck-primary d-inline mr-2">
                                    <input type="radio" id="sec_todas" name="r_seccion" v-model="filtro.seccion" value="0">
                                    <label for="sec_todas"></label>
                                </div>
                                <span class="flex-grow-1">TODAS LAS SECCIONES</span>
                                <span class="badge badge-light border">Todos</span>
                            </label>
                            @foreach ($secciones as $seccion)
                            <label class="list-group-item list-group-item-action d-flex align-items-center mb-0 cursor-pointer" :class="{'bg-light font-weight-bold text-primary': filtro.seccion == '{{ $seccion['present_cod'] }}'}">
                                <div class="icheck-primary d-inline mr-2">
                                    <input type="radio" id="sec_{{ $seccion['present_cod'] }}" name="r_seccion" v-model="filtro.seccion" value="{{ $seccion['present_cod'] }}">
                                    <label for="sec_{{ $seccion['present_cod'] }}"></label>
                                </div>
                                <span class="flex-grow-1">{{ $seccion['present_descripcion'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary btn-sm" @click="filtro.seccion = 0; buscar(false)" data-dismiss="modal">
                            <i class="fa fa-undo mr-1"></i> Restablecer
                        </button>
                        <button type="button" class="btn btn-primary btn-sm px-3" data-dismiss="modal" @click="buscar(false)">
                            <i class="fa fa-check mr-1"></i> Aplicar Filtro
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Exportar a Excel -->
        <div class="modal fade" id="modalexportar" tabindex="-1" role="dialog" aria-labelledby="modalExportarLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-light border-bottom">
                        <h6 class="modal-title font-weight-bold mb-0" style="color: var(--dash-text-main);" id="modalExportarLabel">
                            <i class="fa-solid fa-file-excel text-success mr-2"></i>Exportar Reporte a Excel
                        </h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-3">
                        <p class="text-muted small mb-3">
                            Seleccione el tipo de informe que desea generar con los filtros de búsqueda actuales:
                        </p>
                        <div class="list-group">
                            <button type="button" class="list-group-item list-group-item-action d-flex align-items-center p-3" @click="exportar('stock')" data-dismiss="modal">
                                <div class="rounded-circle p-3 mr-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:#e8f5e9;">
                                    <i class="fa-solid fa-boxes-stacked fa-lg text-success"></i>
                                </div>
                                <div class="flex-grow-1 text-left">
                                    <h6 class="mb-0 font-weight-bold" style="color: var(--dash-text-main);">Planilla con Stock</h6>
                                    <small class="text-muted">Exporta lista completa con cantidades en depósito y sucursales</small>
                                </div>
                                <i class="fa fa-chevron-right text-muted"></i>
                            </button>
                            <button type="button" class="list-group-item list-group-item-action d-flex align-items-center p-3 mt-2" @click="exportar('precios')" data-dismiss="modal">
                                <div class="rounded-circle p-3 mr-3 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:#e3f2fd;">
                                    <i class="fa-solid fa-tags fa-lg text-primary"></i>
                                </div>
                                <div class="flex-grow-1 text-left">
                                    <h6 class="mb-0 font-weight-bold" style="color: var(--dash-text-main);">Lista de Precios a Crédito</h6>
                                    <small class="text-muted">Exporta lista de precios de lista, cuotas y márgenes</small>
                                </div>
                                <i class="fa fa-chevron-right text-muted"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top py-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalPromo" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" style="height:auto;">
                    <div class="modal-header">
                        <h5 class="modal-title mb-0">
                            Promo — @{{ promoDetalle.articulo.nombre || '…' }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-if="promoDetalle.cargando" class="text-center py-4 text-muted">
                            <i class="fa fa-spinner fa-spin"></i> Cargando…
                        </div>
                        <template v-else>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="text-muted small mb-0" v-if="promoDetalle.articulo.precio">
                                    Precio lista: <strong>@{{ separador(promoDetalle.articulo.precio) }}</strong>
                                    <span v-if="promoDetalle.articulo.c_barra" class="ml-2">· Cod: @{{ promoDetalle.articulo.c_barra }}</span>
                                </p>
                                <button type="button" class="btn btn-sm btn-outline-dark"
                                    @click="imprimirEtiqueta('articulo')"
                                    title="Imprimir etiqueta del artículo">
                                    <i class="fa fa-print"></i> Etiqueta
                                </button>
                            </div>

                            <h6 id="promo-ofertas" class="text-danger"><i class="fa fa-tag"></i> Ofertas</h6>
                            <div v-if="!promoDetalle.ofertas.length" class="text-muted small mb-3">Sin ofertas.</div>
                            <div v-for="o in promoDetalle.ofertas" :key="'of-'+o.id" class="promo-item">
                                <div class="promo-item-head">
                                    <div class="promo-item-title">
                                        <strong>@{{ o.nombre }}</strong>
                                        <span class="badge" :class="o.activo ? 'badge-success' : 'badge-secondary'">
                                            @{{ o.activo ? 'Activa' : 'Inactiva' }}
                                        </span>
                                    </div>
                                    <div class="promo-item-actions">
                                        <span class="badge badge-danger">@{{ labelDescOferta(o) }}</span>
                                        <button type="button" class="btn btn-sm btn-outline-dark"
                                            @click="imprimirEtiqueta('oferta', o)" title="Imprimir etiqueta oferta">
                                            <i class="fa fa-print"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="promo-item-body">
                                    <div class="small text-muted">
                                        Tipo: @{{ labelTipoOferta(o.tipo) }}
                                        <span v-if="o.cantidad_min"> · Desde @{{ o.cantidad_min }} unid.</span>
                                        <span v-if="o.fecha_desde || o.fecha_hasta">
                                            · @{{ o.fecha_desde || '…' }} → @{{ o.fecha_hasta || '…' }}
                                        </span>
                                    </div>
                                    <div class="small mt-1">
                                        Precio con oferta: <strong>@{{ separador(o.precio_final) }}</strong>
                                    </div>
                                    <div class="small text-muted" v-if="o.observacion">@{{ o.observacion }}</div>
                                </div>
                            </div>

                            <h6 id="promo-combos" class="text-info mt-3"><i class="fa fa-layer-group"></i> Combos</h6>
                            <div v-if="!promoDetalle.combos.length" class="text-muted small">Sin combos.</div>
                            <div v-for="c in promoDetalle.combos" :key="'cb-'+c.id" class="promo-item">
                                <div class="promo-item-head">
                                    <div class="promo-item-title">
                                        <strong>@{{ c.nombre }}</strong>
                                        <span class="badge" :class="c.activo ? 'badge-success' : 'badge-secondary'">
                                            @{{ c.activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                        <span class="text-muted small" v-if="c.codigo">(@{{ c.codigo }})</span>
                                    </div>
                                    <div class="promo-item-actions">
                                        <span class="badge badge-info">@{{ separador(c.precio) }}</span>
                                        <button type="button" class="btn btn-sm btn-outline-dark"
                                            @click="imprimirEtiqueta('combo', c)" title="Imprimir etiqueta combo">
                                            <i class="fa fa-print"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="promo-item-body">
                                    <div class="small text-muted" v-if="c.precio_lista">
                                        Precio lista: @{{ separador(c.precio_lista) }}
                                    </div>
                                    <ul class="small mt-1">
                                        <li v-for="(it, idx) in c.items" :key="'it-'+c.id+'-'+idx">
                                            @{{ it.cantidad }} × @{{ it.nombre || ('#' + it.articulos_cod) }}
                                        </li>
                                    </ul>
                                    <div class="small text-muted mt-1" v-if="c.observacion">@{{ c.observacion }}</div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-dark"
                            :disabled="promoDetalle.cargando"
                            @click="imprimirEtiqueta('articulo')">
                            <i class="fa fa-print"></i> Imprimir etiqueta
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('js/separator.js') }}"></script>
    <script>
        const defaultArticulo = {
            'codigo': '',
            'c_barra': '',
            'descripcion': '',
            'indicaciones': '',
            'modouso': '',
            'seccion': 1,
            'unidad': 1,
            'factor': 1,
            'ubicacion': '',
            'costo': 0,
            'p1': 0,
            'p2': 0,
            'p3': 0,
            'p4': 0,
            'p5': 0,
            'm1': 0,
            'm2': 0,
            'm3': 0,
            'm4': 0,
            'm5': 0,
            'svenc': '0',
            existePrecios: false
        };

        const defaultStock = {
            'id': 1,
            'cantidad': 0,
            'loteold': 'S/N',
            'lotenew': 'S/N',
            'vencimiento': 'Sin vencimiento',
            'sucursal': 1
        };

        const defaultPrecio = [{
            p: 50,
            m: 5,
            c: 2
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }, {
            p: 0,
            m: 0,
            c: 0
        }];
        var app = new Vue({
            el: '#app',
            data: {
                requestSend: false,
                saving: false,
                toggleMasPrecios: false,
                precios: [...defaultPrecio],
                chcuota: false,
                chprecio: false,
                url: 'controller/ArticulosController.php',
                reservarC: false,
                bandstock: 0,
                isnew: true,
                viewPrecio: false,
                txtbuscar: '',
                datos: 'F',
                idstock: 1,
                articulos: [],
                secciones: [],
                sucursales: [],
                unidades: [],
                filtro: {
                    seccion: 0,
                    columna: 0,
                    orden: 'ASC'
                },
                articulo: { ...defaultArticulo },
                stock: { ...defaultStock },
                stocks: [],
                error: '',
                cantidadStock: 0,
                frmt: {
                    i: -1,
                    t: false,
                    suc: 0,
                    cant: 0
                },
                promoDetalle: {
                    cargando: false,
                    articulo: {},
                    ofertas: [],
                    combos: [],
                    foco: null
                },
                sortField: 'descripcion',
                sortOrder: 'asc',
                currentPage: 1,
                perPage: 25,
                rows: []
            },

            methods: {
                sortBy: function(field) {
                    if (this.sortField === field) {
                        this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sortField = field;
                        this.sortOrder = 'asc';
                    }
                    this.currentPage = 1;
                },
                getSortIcon: function(field) {
                    if (this.sortField !== field) return 'fa-sort text-muted opacity-50';
                    return this.sortOrder === 'asc' ? 'fa-sort-up text-success' : 'fa-sort-down text-success';
                },
                changePage: function(p) {
                    if (p < 1 || p > this.totalPages) return;
                    this.currentPage = p;
                },
                busqueda_tabla: function() { return true; },

                redondear: function(monto) {
                    var longitud = 0,
                        x = "",
                        b = "",
                        PFinal = 0;
                    if (monto > 1000) {
                        longitud = monto.toString().length;
                        x = monto.toString().substr(-3);
                        if (parseInt(x) > 500) {
                            x = "500";
                        } else {
                            x = "000";
                        }
                        b = monto.toString().substr(0, longitud - 3);
                        PFinal = parseInt(b + x);
                    } else {
                        if (monto >= 500) {
                            PFinal = 500;
                        }
                    }
                    return PFinal;
                },

                setMargen: function(index) {
                    if (this.viewPrecio) {
                        return false;
                    }
                    if (typeof(this.articulo.costo) === 'string') {
                        this.articulo.costo = this.articulo.costo * 1;
                    }
                    if (this.articulo.costo > 0 && this.precios[index].p > 0) {
                        if (this.precios[index].p > this.articulo.costo) {
                            var res = this.precios[index].p - this.articulo.costo;
                            this.precios[index].m = Math.round(res * 100 / this.articulo.costo);
                        } else {
                            this.precios[index].m = 0;
                        }
                        this.setCuota(index);
                    }
                },

                setPrecio: function(index) {
                    if (this.viewPrecio) {
                        return false;
                    }
                    if (typeof(this.articulo.costo) === 'string') {
                        this.articulo.costo = parseInt(this.articulo.costo);
                    }
                    if (this.articulo.costo < 1) {
                        this.precios[index].p = 0;
                        return;
                    }
                    if (parseInt(this.precios[index].m) < 1 || !this.precios[index].m) {
                        this.precios[index].p = 0;
                        return;
                    }

                    var retornar = parseInt((this.articulo.costo * parseInt(this.precios[index].m)) / 100 + this.articulo.costo);
                    if (this.chprecio) {
                        this.precios[index].p = this.redondear(retornar);
                    } else {
                        this.precios[index].p = retornar;
                    }
                },

                setCuota: function(index) {
                    if (this.viewPrecio) {
                        return false;
                    }
                    if (this.precios[index].p > 0) {
                        if (this.chcuota) {
                            this.precios[index].c = this.precios[index].p / (index + 2);
                            this.precios[index].c = this.redondear(parseInt(this.precios[index].c));
                        } else {
                            this.precios[index].c = parseInt(this.precios[index].p / (index + 2));
                        }
                    } else {
                        this.precios[index].c = 0;
                    }
                },

                generarCodigoBarra: function() {
                    const rnd = Math.floor(100000 + Math.random() * 900000);
                    this.articulo.c_barra = '784' + rnd;
                    this.validar_codigo_de_barra();
                },
                
                onChange: function() { //Al cambiar pagina
                    if (this.paginacion.ultima_pagina > 1) {
                        this.buscar(true);
                    }

                },
                verPreciosCredito: function(cod,costo){
                    this.viewPrecio= true;
                    this.articulo.costo= costo;
                    this.getPrecios(cod);
                    this.mostrarPrecios();

                },
                verPromo: function(cod, foco) {
                    this.promoDetalle = {
                        cargando: true,
                        articulo: {},
                        ofertas: [],
                        combos: [],
                        foco: foco || null
                    };
                    $('#modalPromo').modal('show');
                    axios.get('articulo/promo/' + cod)
                        .then(response => {
                            const d = response.data || {};
                            this.promoDetalle = {
                                cargando: false,
                                articulo: d.articulo || {},
                                ofertas: d.ofertas || [],
                                combos: d.combos || [],
                                foco: foco || null
                            };
                            this.$nextTick(function () {
                                if (foco === 'combo') {
                                    var el = document.getElementById('promo-combos');
                                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                } else if (foco === 'oferta') {
                                    var el2 = document.getElementById('promo-ofertas');
                                    if (el2) el2.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                }
                            });
                        })
                        .catch(e => {
                            this.promoDetalle.cargando = false;
                            $('#modalPromo').modal('hide');
                            Swal.fire('Error', e.response && e.response.data && e.response.data.message
                                ? e.response.data.message
                                : (e.message || 'No se pudo cargar'), 'error');
                        });
                },
                labelTipoOferta: function(tipo) {
                    if (tipo === 'cantidad') return 'Por cantidad';
                    if (tipo === 'fecha') return 'Por fecha';
                    if (tipo === 'ambos') return 'Cantidad + fecha';
                    return tipo || '—';
                },
                labelDescOferta: function(o) {
                    if (!o) return '';
                    const v = o.descuento_valor;
                    if (o.descuento_tipo === 'porcentaje') return v + '% off';
                    if (o.descuento_tipo === 'monto') return '-' + this.separador(v);
                    if (o.descuento_tipo === 'precio_fijo') return 'Precio ' + this.separador(v);
                    return String(v);
                },
                imprimirEtiqueta: function(tipo, item) {
                    var art = this.promoDetalle.articulo || {};
                    var titulo = art.nombre || '';
                    var codigo = art.c_barra || '';
                    var precioLista = art.precio || 0;
                    var precio = precioLista;
                    var badge = '';
                    var extra = '';

                    if (tipo === 'oferta' && item) {
                        precio = item.precio_final;
                        badge = 'OFERTA';
                        extra = item.nombre || '';
                        if (item.codigo) {
                            codigo = item.codigo;
                        }
                        if (item.cantidad_min) {
                            extra += (extra ? ' · ' : '') + 'Desde ' + item.cantidad_min + ' unid.';
                        }
                    } else if (tipo === 'combo' && item) {
                        titulo = item.nombre || titulo;
                        codigo = item.codigo || codigo || ('COMBO-' + item.id);
                        precio = item.precio;
                        precioLista = item.precio_lista || precioLista;
                        badge = 'COMBO';
                        if (item.items && item.items.length) {
                            extra = item.items.map(function (it) {
                                return (it.cantidad || 1) + '× ' + (it.nombre || ('#' + it.articulos_cod));
                            }).join(' · ');
                        }
                    } else {
                        var ofertaActiva = (this.promoDetalle.ofertas || []).find(function (o) { return parseInt(o.activo) === 1; });
                        if (ofertaActiva) {
                            precio = ofertaActiva.precio_final;
                            badge = 'OFERTA';
                            extra = ofertaActiva.nombre || '';
                            if (ofertaActiva.codigo) {
                                codigo = ofertaActiva.codigo;
                            }
                        }
                    }

                    var html = this._htmlEtiqueta({
                        titulo: titulo,
                        codigo: codigo,
                        precio: precio,
                        precioLista: precioLista,
                        badge: badge,
                        extra: extra,
                        barcodeSvg: this._barcodeSvg(codigo)
                    });

                    var w = window.open('', '_blank', 'width=480,height=580');
                    if (!w) {
                        Swal.fire('Atención', 'Permití ventanas emergentes para imprimir la etiqueta', 'info');
                        return;
                    }
                    w.document.open();
                    w.document.write(html);
                    w.document.close();
                },
                _barcodeSvg: function(text) {
                    text = String(text || '').trim();
                    if (!text) return '';

                    // CODE128B patterns (bar/space widths)
                    var patterns = [
                        '212222','222122','222221','121223','121322','131222','122213','122312','132212','221213',
                        '221312','231212','112232','122132','122231','113222','123122','123221','223211','221132',
                        '221231','213212','223112','312131','311222','321122','321221','312212','322112','322211',
                        '212123','212321','232121','111323','131123','131321','112313','132113','132311','211313',
                        '231113','231311','112133','112331','132131','113123','113321','133121','313121','211331',
                        '231131','213113','213311','213131','311123','311321','331121','312113','312311','332111',
                        '314111','221411','431111','111224','111422','121124','121421','141122','141221','112214',
                        '112412','122114','122411','142112','142211','241211','221114','413111','241112','134111',
                        '111242','121142','121241','114212','124112','124211','411212','421112','421211','212141',
                        '214121','412121','111143','111341','131141','114113','114311','411113','411311','113141',
                        '114131','311141','411131','211412','211214','211232','2331112'
                    ];

                    var start = 104; // Code B
                    var stop = 106;
                    var codes = [start];
                    var checksum = start;

                    for (var i = 0; i < text.length; i++) {
                        var v = text.charCodeAt(i) - 32;
                        if (v < 0 || v > 95) {
                            v = ('?').charCodeAt(0) - 32;
                        }
                        codes.push(v);
                        checksum += v * (i + 1);
                    }
                    codes.push(checksum % 103);
                    codes.push(stop);

                    var module = 1.6;
                    var height = 36;
                    var x = 0;
                    var bars = '';
                    for (var c = 0; c < codes.length; c++) {
                        var pat = patterns[codes[c]];
                        if (!pat) continue;
                        for (var p = 0; p < pat.length; p++) {
                            var w = parseInt(pat.charAt(p), 10) * module;
                            if (p % 2 === 0) {
                                bars += '<rect x="' + x.toFixed(2) + '" y="0" width="' + w.toFixed(2) + '" height="' + height + '" fill="#000"/>';
                            }
                            x += w;
                        }
                    }

                    var width = Math.ceil(x);
                    return '<svg xmlns="http://www.w3.org/2000/svg" width="' + width + '" height="' + height +
                        '" viewBox="0 0 ' + width + ' ' + height + '" role="img" aria-label="barcode">' +
                        bars + '</svg>';
                },
                _htmlEtiqueta: function(d) {
                    var formatMiles = function (n) {
                        var x = Math.round(Number(n) || 0).toString();
                        return x.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    };
                    var precioFmt = formatMiles(d.precio);
                    var listaFmt = formatMiles(d.precioLista);
                    var showTachado = d.badge && d.precioLista && Number(d.precioLista) > Number(d.precio);
                    var titulo = String(d.titulo || '').trim().replace(/</g, '&lt;');
                    var codigoRaw = String(d.codigo || '').trim();
                    var codigo = codigoRaw.replace(/</g, '&lt;');
                    var extra = String(d.extra || '').replace(/</g, '&lt;');
                    var badge = String(d.badge || '').toUpperCase();
                    var badgeClass = badge === 'COMBO' ? 'badge-combo' : (badge === 'OFERTA' ? 'badge-oferta' : 'badge-plain');
                    var badgeHtml = badge
                        ? '<span class="badge ' + badgeClass + '">' + badge + '</span>'
                        : '<span class="badge badge-plain">PRODUCTO</span>';
                    var listaHtml = showTachado
                        ? '<div class="precio-antes"><span class="lbl">Antes</span> <span class="val">Gs. ' + listaFmt + '</span></div>'
                        : '';
                    var extraHtml = extra
                        ? '<div class="extra">' + extra + '</div>'
                        : '';
                    var barcodeSvg = d.barcodeSvg || '';

                    return '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Etiqueta</title>' +
                        '<style>' +
                        '@@page{size:70mm 50mm;margin:0}' +
                        '*{box-sizing:border-box}' +
                        'html,body{margin:0;padding:0}' +
                        'body{font-family:"Segoe UI",Arial,Helvetica,sans-serif;color:#111;background:#f3f4f6;' +
                        'min-height:100vh;padding:12px;display:flex;flex-direction:column;align-items:center;gap:12px}' +
                        '.toolbar{display:flex;gap:8px;align-items:center}' +
                        '.toolbar button{border:0;border-radius:6px;padding:8px 14px;font-size:14px;font-weight:600;cursor:pointer}' +
                        '.btn-print{background:#111;color:#fff}' +
                        '.btn-close{background:#e5e7eb;color:#111}' +
                        '.etiqueta{width:64mm;min-height:42mm;background:#fff;border:1.5px solid #222;border-radius:2mm;' +
                        'padding:2.5mm 3mm;display:flex;flex-direction:column;justify-content:space-between;box-shadow:0 2px 8px rgba(0,0,0,.08)}' +
                        '.head{display:flex;align-items:center;justify-content:space-between;gap:2mm;margin-bottom:1.5mm}' +
                        '.badge{display:inline-block;font-size:8px;font-weight:800;letter-spacing:.6px;' +
                        'padding:1.5px 5px;border-radius:2px;line-height:1.2;text-transform:uppercase}' +
                        '.badge-oferta{background:#c62828;color:#fff}' +
                        '.badge-combo{background:#1565c0;color:#fff}' +
                        '.badge-plain{background:#333;color:#fff}' +
                        '.codigo{font-size:8px;color:#555;font-weight:600;letter-spacing:.3px}' +
                        '.titulo{font-size:12px;font-weight:700;line-height:1.25;text-transform:capitalize;' +
                        'max-height:2.6em;overflow:hidden;margin:0 0 1.5mm}' +
                        '.precios{margin:1mm 0}' +
                        '.precio-antes{font-size:9px;color:#777;margin-bottom:0.5mm;display:flex;align-items:center;gap:1.5mm}' +
                        '.precio-antes .lbl{font-weight:600;color:#888}' +
                        '.precio-antes .val{text-decoration:line-through}' +
                        '.precio-ahora{display:flex;align-items:baseline;gap:1.5mm;line-height:1}' +
                        '.precio-ahora .moneda{font-size:11px;font-weight:700;color:#333}' +
                        '.precio-ahora .monto{font-size:22px;font-weight:800;letter-spacing:-0.6px}' +
                        '.extra{font-size:8px;color:#555;line-height:1.25;margin-top:1.5mm;' +
                        'max-height:2.5em;overflow:hidden;border-top:1px dashed #bbb;padding-top:1.2mm}' +
                        '.bc-wrap{text-align:center;margin-top:1.5mm;padding-top:1.2mm;border-top:1px solid #ddd}' +
                        '.bc-wrap svg{max-width:100%;height:36px}' +
                        '.bc-text{font-size:9px;letter-spacing:1.2px;color:#222;margin-top:0.8mm;font-weight:600}' +
                        '.bc-missing{font-size:9px;color:#999;padding:2mm 0}' +
                        '@@media print{' +
                        'body{background:#fff;padding:0;min-height:auto;display:block}' +
                        '.toolbar{display:none!important}' +
                        '.etiqueta{width:100%;min-height:45mm;border-radius:0;box-shadow:none;margin:0}' +
                        '}' +
                        '</style></head><body>' +
                        '<div class="toolbar">' +
                        '<button type="button" class="btn-print" onclick="window.print()">Imprimir etiqueta</button>' +
                        '<button type="button" class="btn-close" onclick="window.close()">Cerrar</button>' +
                        '</div>' +
                        '<div class="etiqueta">' +
                        '<div>' +
                        '<div class="head">' + badgeHtml + (codigo ? '<span class="codigo">' + codigo + '</span>' : '') + '</div>' +
                        '<div class="titulo">' + titulo + '</div>' +
                        '<div class="precios">' + listaHtml +
                        '<div class="precio-ahora"><span class="moneda">Gs.</span><span class="monto">' + precioFmt + '</span></div>' +
                        '</div>' +
                        extraHtml +
                        '</div>' +
                        (codigo && barcodeSvg
                            ? '<div class="bc-wrap">' + barcodeSvg + '<div class="bc-text">' + codigo + '</div></div>'
                            : '<div class="bc-wrap"><div class="bc-missing">Sin código de barras</div></div>') +
                        '</div>' +
                        '</body></html>';
                },
                mostrarPrecios: function() {
                    if (this.articulo.costo > 0) {
                        if (!this.viewPrecio) {
                            $('#modalArticulo').modal('hide');
                        }
                        $('#precioArticulo').modal('show');
                    } else {
                        Swal.fire('Atención...', 'Agregue precio de compra', 'info');
                    }
                },
                cerrarPrecios: function() {
                    if (!this.viewPrecio) {
                        $('#modalArticulo').modal('show');
                    }
                    $('#precioArticulo').modal('hide');
                },
                color: function(id) {
                    return this.frmt.i == id ? true : false;
                },
                cancelTrans: function() {
                    $('#accordiontransferir').collapse('hide');
                    this.frmt = {
                        i: -1,
                        t: false,
                        suc: 0,
                        cant: 0
                    }
                },
                buscar: function(isPaginate) {
                    this.requestSend = true;
                    let pag = isPaginate ? this.currentPage : 1;
                    axios.get('articulo/buscar', {
                            params: {
                                page: pag,
                                buscar: this.txtbuscar,
                                criterio: 0,
                                seccion: this.filtro.seccion,
                                col: this.filtro.columna,
                                ord: this.filtro.orden,
                                suc: null
                            }
                        })
                        .then(response => {
                            this.requestSend = false;
                            if (response.data == 'NO') {
                                Swal.fire('Sin resultados', 'No se encontraron artículos para: ' + (this.txtbuscar || 'el filtro seleccionado'), 'info');
                                this.rows = [];
                                this.articulos = [];
                            } else {
                                this.rows = [];
                                this.articulos = response.data || [];
                                for (let i = 0; i < this.articulos.length; i++) {
                                    const art = this.articulos[i];
                                    const cantStock = parseInt(art.cantidad || 0);

                                    const item = {
                                        ARTICULOS_cod: art.ARTICULOS_cod,
                                        codigo: art.producto_c_barra || '',
                                        descripcion: art.producto_nombre || '',
                                        ubicacion: art.producto_ubicacion || '',
                                        seccion: art.present_descripcion || 'General',
                                        tiene_oferta: parseInt(art.tiene_oferta || 0) > 0,
                                        en_combo: parseInt(art.en_combo || 0) > 0,
                                        precio: parseFloat(art.pre_venta1) || 0,
                                        precio_formateado: this.separador(art.pre_venta1),
                                        stock: cantStock,
                                        costo: art.producto_costo_compra || 0
                                    };
                                    this.rows.push(item);
                                }
                                this.currentPage = 1;
                            }
                        })
                        .catch(e => {
                            this.requestSend = false;
                            this.error = e.message;
                        });
                },
                setUtilPrecio: function(tipo, i) {
                    if (tipo == 'M') {
                        this.articulo['p' + i] = ((this.articulo.costo * this.articulo['m' + i]) / 100) +
                            parseFloat(this.articulo.costo);
                    } else {
                        if (this.articulo.costo > 0 && this.articulo['p' + i] > 0) {
                            var res = this.articulo['p' + i] - this.articulo.costo;
                            this.articulo['m' + i] = Math.round(res * 100 / this.articulo.costo);
                        }
                    }
                },
                separador: function(number) {
                    var n = parseFloat(number);
                    return new Intl.NumberFormat().format(n);
                },
                showMArticulo: function() {
                    this.isnew = true;
                    this.viewPrecio = false;
                    this.cleanAll();
                    if (this.secciones.length > 0) {
                        this.articulo.seccion = this.secciones[0].present_cod;
                    }
                    if (this.unidades.length > 0) {
                        this.articulo.unidad = this.unidades[0].uni_codigo;
                    }
                    $('#tab-general-tab').tab('show');
                    $('#modalArticulo').modal('show');
                    this.$nextTick(function() {
                        $('#tab-general-tab').tab('show');
                        setTimeout(function() {
                            $('#txtArticuloDescripcion').focus().select();
                        }, 250);
                    });
                },
                showEArticulo: function(id) {
                    const a = this.articulos.find(e => e.ARTICULOS_cod == id);
                    if (!a) return;
                    this.cleanAll();
                    this.isnew = false;
                    this.viewPrecio = false;
                    this.setArticulo(a);
                    this.getStock(a.ARTICULOS_cod);
                    this.getPrecios(a.ARTICULOS_cod);
                    $('#tab-general-tab').tab('show');
                    $('#modalArticulo').modal('show');
                    this.$nextTick(function() {
                        $('#tab-general-tab').tab('show');
                        setTimeout(function() {
                            $('#txtArticuloDescripcion').focus().select();
                        }, 250);
                    });
                },
                setArticulo: function(a) {
                    this.articulo = {
                        'codigo': a.ARTICULOS_cod,
                        'c_barra': a.producto_c_barra || '',
                        'descripcion': a.producto_nombre || '',
                        'indicaciones': a.producto_indicaciones || '',
                        'modouso': a.producto_dosis || '',
                        'seccion': a.present_cod || 1,
                        'unidad': a.uni_codigo || 1,
                        'factor': a.producto_factor || 1,
                        'ubicacion': a.producto_ubicacion || '',
                        'costo': parseFloat(a.producto_costo_compra) || 0,
                        'p1': parseFloat(a.pre_venta1) || 0,
                        'p2': parseFloat(a.pre_venta2) || 0,
                        'p3': parseFloat(a.pre_venta3) || 0,
                        'p4': parseFloat(a.pre_venta4) || 0,
                        'p5': parseFloat(a.pre_venta5) || 0,
                        'm1': parseInt(a.pre_margen1, 10) || 0,
                        'm2': parseInt(a.pre_margen2, 10) || 0,
                        'm3': parseInt(a.pre_margen3, 10) || 0,
                        'm4': parseInt(a.pre_margen4, 10) || 0,
                        'm5': parseInt(a.pre_margen5, 10) || 0,
                        'svenc': '0',
                        existePrecios: false
                    };
                },
                duplicar: function(id) {
                    const a = this.articulos.find(e => e.ARTICULOS_cod == id);
                    if (!a) return;
                    this.cleanAll();
                    this.isnew = true;
                    this.viewPrecio = false;
                    this.setArticulo(a);
                    this.articulo.codigo = '';
                    this.articulo.c_barra = '';
                    this.articulo.descripcion = (a.producto_nombre || '') + ' (Copia)';
                    $('#tab-general-tab').tab('show');
                    $('#modalArticulo').modal('show');
                    this.$nextTick(function() {
                        $('#tab-general-tab').tab('show');
                        setTimeout(function() {
                            $('#txtArticuloDescripcion').focus().select();
                        }, 250);
                    });
                },
                setPrecioVenta: function() {
                    if (this.articulo.costo > 0) {
                        for (var i = 1; i < 6; i++) {
                            this.articulo['p' + i] = ((this.articulo.costo * this.articulo['m' + i]) / 100) +
                                parseFloat(this.articulo.costo);

                        }
                    }
                },
                getD: function() {
                    return {
                        'id': this.idstock,
                        'cantidad': this.stock.cantidad,
                        'loteold': this.reservarC ? this.stock.lotenew : this.stock.loteold,
                        'lotenew': this.stock.lotenew,
                        'vencimiento': this.validarVenc(this.stock.vencimiento),
                        'sucursal': this.stock.sucursal
                    };
                },
                validarVenc: function(fecha) {
                    if (fecha.length < 1) {
                        return "Sin vencimiento";
                    }
                    this.articulo.svenc = '1'
                    return fecha;
                },
                addStock: function() {
                    if (this.stock.cantidad > 0) {
                        var x = this.stocks.findIndex(x => (x.lotenew || 'S/N') == (this.stock.lotenew || 'S/N') && x.sucursal == this.stock.sucursal);
                        if (x == -1) {
                            this.idstock = this.stocks.length + 1;
                            this.stock.loteold = this.stock.lotenew;
                            this.stocks.push(this.getD());
                            this.limpiarCamposStock();
                        } else {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                            Toast.fire({
                                icon: 'success',
                                title: 'Se sumó la cantidad al lote existente en la sucursal'
                            });
                            this.stocks[x].cantidad = parseInt(this.stocks[x].cantidad) + parseInt(this.stock.cantidad);
                            if (this.stock.vencimiento && this.stock.vencimiento.length > 0) {
                                this.stocks[x].vencimiento = this.stock.vencimiento;
                            }
                            this.limpiarCamposStock();
                        }
                    } else {
                        Swal.fire('Atención', 'Ingresá una cantidad de stock mayor a 0', 'warning');
                    }
                },
                setStock: function(s, index) {

                    if (this.frmt.t) {
                        this.frmt.i = -1;
                        this.frmt.t = false;
                    } else {
                        this.frmt.t = true;
                        this.frmt.i = index;
                    }
                    this.stock = {
                        'id': s.id,
                        'cantidad': 0,
                        'loteold': s.loteold,
                        'lotenew': s.lotenew,
                        'vencimiento': s.vencimiento,
                        'sucursal': s.sucursal
                    };
                },
                transladarStock: function() {
                    const i = this.stocks.findIndex(stock => stock.id == this.stock.id);

                    if (this.frmt.cant > 0 && this.frmt.suc > 0) {
                        if (this.stocks[i].sucursal == this.frmt.suc) {
                            Swal.fire('Atencion!', 'Seleccione otra sucursal!', 'warning');
                            return false;
                        }
                        if (this.frmt.cant > this.stocks[i].cantidad) {
                            Swal.fire('Atencion!', 'Cantidad ingresada es Mayor!', 'warning');
                            return false;
                        }
                        this.stocks[i].cantidad = parseInt(this.stocks[i].cantidad) - this.frmt.cant;
                        this.stocks.push({
                            'id': this.stocks.length + 1,
                            'cantidad': this.frmt.cant,
                            'loteold': this.stock.loteold,
                            'lotenew': this.stock.lotenew,
                            'vencimiento': this.stock.vencimiento,
                            'sucursal': this.frmt.suc
                        });
                        this.updateStock();

                    } else {
                        Swal.fire('Atencion!', 'Seleccione Destino e ingrese cantidad!', 'error');
                    }
                },
                getByIdSucursal: function(id) {
                    const suc = this.sucursales.find(sucursal => sucursal.suc_cod == id);
                    return suc ? suc.suc_desc : 'Sucursal #' + id;
                },
                updateStockA: function() {
                    const idx = this.stocks.findIndex(s => s.id == this.stock.id);
                    if (idx !== -1) {
                        this.$set(this.stocks, idx, {
                            ...this.stocks[idx],
                            cantidad: parseInt(this.stock.cantidad || 0),
                            sucursal: this.stock.sucursal,
                            lotenew: this.stock.lotenew || 'S/N',
                            loteold: this.stock.loteold || this.stock.lotenew || 'S/N',
                            vencimiento: this.validarVenc(this.stock.vencimiento || '')
                        });
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'Stock actualizado correctamente'
                        });
                    }
                    this.limpiarCamposStock();
                },
                limpiarCamposStock: function() {
                    this.bandstock = 0;
                    this.stock = {...defaultStock};
                },
                cleanAll: function() {
                    this.stocks = [];
                    this.limpiarCamposStock();
                    for (var i = 0; i < 17; i++) {
                        this.precios[i].p = 0;
                        this.precios[i].m = 0;
                        this.precios[i].c = 0;
                    }
                    this.articulo = { ...defaultArticulo };
                    this.saving = false;
                    this.toggleMasPrecios = false;
                },
                saveArticulo: function() {
                    if (this.articulo.descripcion && this.articulo.costo !== undefined && this.articulo.p1) {
                        this.saving = true;
                        this.error = "";
                        if (this.stocks.length < 1) {
                            this.stocks.push({ ...defaultStock });
                        }
                        if (this.isnew) {
                            axios.post('articulo', {
                                articulo: this.articulo,
                                stock: this.stocks,
                                precios: this.precios
                            })
                            .then(r => {
                                this.saving = false;
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Artículo Guardado!',
                                    text: this.articulo.descripcion,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                this.cleanAll();
                                $('#modalArticulo').modal('hide');
                                this.buscar(false);
                            })
                            .catch(e => {
                                this.saving = false;
                                this.error = e.response && e.response.data && e.response.data.message
                                    ? e.response.data.message
                                    : (e.message || 'Error al guardar');
                                Swal.fire('Error', this.error, 'error');
                            });
                        } else {
                            axios.put('articulo/' + this.articulo.codigo, {
                                articulo: this.articulo,
                                stock: this.stocks,
                                precios: this.precios
                            })
                            .then(r => {
                                this.saving = false;
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Artículo Actualizado!',
                                    text: this.articulo.descripcion,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                this.cleanAll();
                                $('#modalArticulo').modal('hide');
                                this.buscar(false);
                            })
                            .catch(e => {
                                this.saving = false;
                                this.error = e.response && e.response.data && e.response.data.message
                                    ? e.response.data.message
                                    : (e.message || 'Error al actualizar');
                                Swal.fire('Error', this.error, 'error');
                            });
                        }
                    } else {
                        Swal.fire('Atención', 'Completá los campos obligatorios: Descripción, Precio de Compra y Precio de Venta 1.', 'warning');
                    }
                },
                getArticulo: function() {
                    axios.get('articulo/buscar')
                        .then(response => {
                            this.articulos = response.data.articulos.data;
                            this.datos = 'T';
                        })
                        .catch(e => {
                            this.error = e.message;
                        })
                },
                getStock: function(id) {
                    axios.get('stock/' + id).then(r => {
                        this.stocks = r.data;
                    }).catch(e => {
                        this.error = e.message;
                    })
                },
                getPrecios: function(id) {
                    axios.get('articulo/precios/' + id).then(response => {
                        if (response.data.length > 0)
                            for (i = 0; i < response.data.length; i++) {
                                this.articulo.existePrecios = true;
                                this.precios[i].p = parseInt(response.data[i].p);
                                this.precios[i].m = parseInt(response.data[i].m);
                                this.precios[i].c = response.data[i].c;
                            }
                        else
                            for (i = 0; i < 17; i++) {
                                this.articulo.existePrecios = false;
                                this.precios[i].p = 0;
                                this.precios[i].m = 0;
                                this.precios[i].c = 0;
                            }
                    }).catch(error => {
                        this.error = error.message;
                    })
                },
                reservarCodigo: function() {
                    axios.post('articulo/res', {
                            "codigo": this.articulo.codigo
                        })
                        .then(r => {
                            this.reservarC = true;
                        })
                        .catch(e => {
                            this.error = e.message;
                        })
                },
                getUltimo: function() {
                    axios.get('articulo/ultimo').then(r => {
                        this.articulo.codigo = (r.data) + 1;
                        this.reservarCodigo();
                    }).catch(e => {
                        Console.log(e.message)
                    })
                },
                getSeccion: function() {
                    var url = 'seccion/all';
                    axios.get(url)
                        .then(response => {
                            this.secciones = response.data;
                        })
                        .catch(e => {
                            this.error = e.message;
                        })
                },
                getUnidad: function() {
                    var url = 'unidad/all';
                    axios.get(url)
                        .then(response => {
                            this.unidades = response.data;
                        })
                        .catch(e => {
                            this.error = e.message;
                        })
                },
                getSucursal: function() {
                    var url = 'sucursal/all';
                    axios.get(url)
                        .then(response => {
                            this.sucursales = response.data;
                        })
                        .catch(e => {
                            this.error = e.message;
                        })
                },
                exportar: function(tipo){
                    if(this.articulos.length < 1){
                        return false;
                    }
                    if(tipo=="stock"){
                       let params= {
                                page: 0,
                                buscar: this.txtbuscar,
                                criterio: 0,
                                seccion: this.filtro.seccion,
                                col: this.filtro.columna,
                                ord: this.filtro.orden,
                                suc: ''
                            }
                        let u = new URLSearchParams(params).toString();
                        window.open('excel/articulos?'+u);
                    }
                    if(tipo=="precios"){
                        let params= {
                                page: 0,
                                buscar: this.txtbuscar,
                                criterio: 0,
                                seccion: this.filtro.seccion,
                                col: this.filtro.columna,
                                ord: this.filtro.orden,
                                suc: ''
                            }
                        let u = new URLSearchParams(params).toString();
                        window.open('excel/articulosprecios?'+u);
                    }
                },
                validar_codigo_de_barra: function(){
                    if(this.articulo.c_barra.length > 0){
                        axios.get('articulo/validar/cbarra/'+this.articulo.c_barra)
                        .then(response => {
                            if(response.data == '1'){
                                Swal.fire('Atención','El codigo de barra ya esta registrado en la base de datos','warning');
                                this.articulo.c_barra = '';
                            }
                        })
                        .catch(e => {
                            this.error = e.message;
                        })
                    }
                },
                showDetalle: function(id, desc) {
                    this.articulo.descripcion = desc;
                    this.articulo.codigo = id;
                    this.getStock(id);
                    $('#detalleArticulo').modal('show');
                },
                modalDelete: function(id, descripcion) {
                    this.articulo.codigo = id;
                    this.articulo.descripcion = descripcion;
                    $('#deleteArticulo').modal('show');
                },
                delArticulo: function() {
                    if (!this.articulo.codigo) return;
                    axios.delete('articulo/res/' + this.articulo.codigo)
                        .then(r => {
                            $('#deleteArticulo').modal('hide');
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Artículo eliminado correctamente',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            this.buscar(false);
                        }).catch(e => {
                            Swal.fire('Error', 'No se pudo eliminar: ' + (e.response && e.response.data && e.response.data.message ? e.response.data.message : e.message), 'error');
                        });
                },
                editStockA: function(stock) {
                    this.stock = { ...stock };
                    this.bandstock = 1;
                },
                delStockA: function(id) {
                    const s = this.stocks.find(stock => stock.id == id);
                    if (!s) return;
                    if (s.id > 20) {
                        const cant = parseInt(s.cantidad || 0);
                        var index = this.articulos.findIndex(x => x.ARTICULOS_cod == this.articulo.codigo);
                        if (index !== -1) {
                            this.articulos[index].cantidad = parseInt(this.articulos[index].cantidad) - cant;
                        }
                        if (!this.reservarC) {
                            axios.delete('stock/' + s.id)
                                .then(response => {
                                    console.log(response.data);
                                })
                                .catch(e => {
                                    console.log(e.message);
                                });
                        }
                    }
                    this.stocks = this.stocks.filter(st => st.id != id);
                    this.limpiarCamposStock();
                }
                
            },
            computed: {
                filteredRows: function() {
                    let list = [...this.rows];
                    if (this.txtbuscar) {
                        const q = this.txtbuscar.toLowerCase().trim();
                        list = list.filter(r => {
                            const cod = (r.codigo || '').toLowerCase();
                            const desc = (r.descripcion || '').toLowerCase();
                            const sec = (r.seccion || '').toLowerCase();
                            const ubi = (r.ubicacion || '').toLowerCase();
                            return cod.includes(q) || desc.includes(q) || sec.includes(q) || ubi.includes(q);
                        });
                    }
                    if (this.sortField) {
                        const field = this.sortField;
                        const order = this.sortOrder === 'asc' ? 1 : -1;
                        list.sort((a, b) => {
                            let valA = a[field];
                            let valB = b[field];
                            if (field === 'precio' || field === 'stock') {
                                valA = parseFloat(valA) || 0;
                                valB = parseFloat(valB) || 0;
                            } else {
                                valA = (valA || '').toString().toLowerCase();
                                valB = (valB || '').toString().toLowerCase();
                            }
                            if (valA < valB) return -1 * order;
                            if (valA > valB) return 1 * order;
                            return 0;
                        });
                    }
                    return list;
                },
                totalPages: function() {
                    return Math.ceil(this.filteredRows.length / this.perPage) || 1;
                },
                paginatedRows: function() {
                    const start = (this.currentPage - 1) * this.perPage;
                    return this.filteredRows.slice(start, start + this.perPage);
                },
                paginationFrom: function() {
                    if (this.filteredRows.length === 0) return 0;
                    return (this.currentPage - 1) * this.perPage + 1;
                },
                paginationTo: function() {
                    return Math.min(this.currentPage * this.perPage, this.filteredRows.length);
                },
                visiblePages: function() {
                    const total = this.totalPages;
                    const current = this.currentPage;
                    if (total <= 7) {
                        const pages = [];
                        for (let i = 1; i <= total; i++) pages.push(i);
                        return pages;
                    }
                    const pages = [];
                    pages.push(1);
                    if (current > 3) pages.push('...');
                    const start = Math.max(2, current - 1);
                    const end = Math.min(total - 1, current + 1);
                    for (let i = start; i <= end; i++) pages.push(i);
                    if (current < total - 2) pages.push('...');
                    pages.push(total);
                    return pages;
                },
                totalStock() {
                    this.cantidadStock = 0;
                    for (var i = 0; i < this.stocks.length; i++) {
                        this.cantidadStock += parseInt(this.stocks[i].cantidad || 0);
                    }
                    return this.cantidadStock;
                },
                totalConStock() {
                    return (this.articulos || []).filter(a => parseInt(a.cantidad || 0) > 0).length;
                },
                totalSinStock() {
                    return (this.articulos || []).filter(a => parseInt(a.cantidad || 0) <= 0).length;
                },
                totalConPromo() {
                    return (this.articulos || []).filter(a => parseInt(a.tiene_oferta || 0) > 0 || parseInt(a.en_combo || 0) > 0).length;
                }
            },
            mounted() {
                this.buscar();
                this.getSucursal();
                this.getSeccion();
                this.getUnidad();
                $('#modalArticulo').on('shown.bs.modal', function() {
                    $('#tab-general-tab').tab('show');
                    $('#txtArticuloDescripcion').focus().select();
                });
            }
        })
        /* $('#addArticulo').on('hidden.bs.modal',function(e){
        	app.delArticulo();
        }); */
        /*  $('#editArticulo').on('hidden.bs.modal', function(e) {
             app.cleanAll();
         }); */
        activarMenu('m_articulo', '');
    </script>

@endsection
