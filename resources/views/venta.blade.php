@extends('layouts.app')
@section('title', 'Punto de Venta')

@section('style')
<style type="text/css">
    @font-face {
        font-family: "Cairo";
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url({{ asset("webfonts/Cairo-Bold.ttf") }}) format("truetype");
    }

    .font-cairo {
        font-family: 'Cairo', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
    }

    :root {
        --dash-primary: #0a4d36;
        --dash-primary-dark: #073827;
        --dash-primary-light: #eaf3ef;
        --dash-primary-border: #c8dfd5;
        --dash-accent: #b8860b;
        --dash-accent-light: #fef8eb;
        --dash-danger: #e11d48;
        --dash-danger-dark: #be123c;
        --dash-danger-light: #fff1f2;
        --dash-danger-border: #fecdd3;
        --dash-card-bg: #ffffff;
        --dash-border: #e2e8f0;
        --dash-text-main: #1e293b;
        --dash-text-muted: #64748b;
        --dash-panel-bg: #f8fafc;
    }

    body.dark-mode {
        --dash-primary: #10b981;
        --dash-primary-dark: #059669;
        --dash-primary-light: #064e3b;
        --dash-primary-border: #047857;
        --dash-accent: #f59e0b;
        --dash-accent-light: #451a03;
        --dash-danger: #fb7185;
        --dash-danger-dark: #f43f5e;
        --dash-danger-light: rgba(225, 29, 72, 0.15);
        --dash-danger-border: rgba(225, 29, 72, 0.35);
        --dash-card-bg: #1f2937;
        --dash-border: #374151;
        --dash-text-main: #f3f4f6;
        --dash-text-muted: #9ca3af;
        --dash-panel-bg: #111827;
    }

    #main {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: var(--dash-text-main);
    }

    .form-group {
        margin-bottom: 0.65rem;
    }
    .form-group label {
        margin-bottom: 0.25rem;
        font-weight: 700;
        font-size: 0.82rem;
    }

    .modal-dialog {
        overflow-y: initial !important;
    }
    .modal-body {
        height: auto;
        max-height: 75vh;
        overflow-y: auto;
    }

    /* Modal Moderno */
    .modal-moderno {
        background-color: var(--dash-card-bg);
        border: 1px solid var(--dash-border) !important;
        border-radius: 16px;
        color: var(--dash-text-main);
        overflow: hidden;
    }
    .modal-moderno .modal-header {
        border-bottom: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        padding: 1rem 1.25rem;
    }
    .modal-moderno .modal-footer {
        border-top: 1px solid var(--dash-border);
        padding: 0.85rem 1.25rem;
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
    .text-dark-mode {
        color: var(--dash-text-main) !important;
    }
    body.dark-mode .close {
        color: #f3f4f6;
        text-shadow: none;
        opacity: 0.8;
    }
    body.dark-mode .close:hover {
        opacity: 1;
    }

    /* Topbar */
    .venta-topbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.65rem;
        margin-bottom: 0.85rem;
        padding-bottom: 0.65rem;
        border-bottom: 1px solid var(--dash-border);
    }
    .venta-local {
        background: var(--dash-primary-light);
        border: 1px solid var(--dash-primary-border);
        color: var(--dash-primary-dark);
        border-radius: 999px;
        padding: 0.35rem 0.85rem;
        font-weight: 700;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
    }
    body.dark-mode .venta-local {
        color: #a7f3d0;
    }
    .venta-carros {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.35rem;
    }
    .venta-tab {
        border-radius: 8px 0 0 8px !important;
        border: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        color: var(--dash-text-main);
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.35rem 0.65rem;
        transition: all 0.15s ease;
    }
    .venta-tab:hover {
        background: var(--dash-primary-light);
        border-color: var(--dash-primary);
    }
    .venta-tab.is-on {
        background: var(--dash-primary) !important;
        border-color: var(--dash-primary) !important;
        color: #ffffff !important;
        font-weight: 700;
        box-shadow: 0 2px 6px rgba(10, 77, 54, 0.25);
    }
    .venta-tab-del {
        border-radius: 0 8px 8px 0 !important;
        border: 1px solid var(--dash-border);
        border-left: none;
        background: var(--dash-card-bg);
        color: var(--dash-text-muted);
        padding: 0.35rem 0.5rem;
    }
    .venta-tab-del:hover {
        color: var(--dash-danger);
        background: var(--dash-danger-light);
    }
    .venta-tab.is-on + .venta-tab-del {
        background: var(--dash-primary) !important;
        border-color: var(--dash-primary) !important;
        color: #ffffff !important;
        opacity: 0.85;
    }
    .venta-tab.is-on + .venta-tab-del:hover {
        opacity: 1;
        background: #be123c !important;
    }
    .venta-tab-add {
        border-radius: 8px !important;
        border: 1px dashed var(--dash-border);
        background: transparent;
        color: var(--dash-text-muted);
        font-weight: 700;
        padding: 0.35rem 0.65rem;
        transition: all 0.15s ease;
    }
    .venta-tab-add:hover {
        border-style: solid;
        border-color: var(--dash-primary);
        color: var(--dash-primary);
        background: var(--dash-primary-light);
    }
    .badge-count {
        background: rgba(255, 255, 255, 0.25);
        color: inherit;
        border-radius: 999px;
        font-size: 0.72rem;
        padding: 0.15rem 0.4rem;
    }
    .venta-tab:not(.is-on) .badge-count {
        background: var(--dash-panel-bg);
        color: var(--dash-text-muted);
        border: 1px solid var(--dash-border);
    }
    .badge-caja {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.85rem;
        border-radius: 999px;
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
        font-weight: 700;
        font-size: 0.82rem;
    }
    .badge-caja.is-cerrada {
        background: #fef3c7;
        color: #92400e;
        border-color: #fde68a;
    }
    body.dark-mode .badge-caja {
        background: #064e3b;
        color: #a7f3d0;
        border-color: #047857;
    }
    body.dark-mode .badge-caja.is-cerrada {
        background: #451a03;
        color: #fde68a;
        border-color: #78350f;
    }
    .status-dot-pulse {
        width: 8px;
        height: 8px;
        background-color: #16a34a;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7);
        animation: pulse-green 1.8s infinite;
        margin-right: 6px;
    }
    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(22, 163, 74, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
    }

    /* Visor & Scanner */
    .venta-visor {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        margin-bottom: 1.25rem;
        overflow: visible;
        position: relative;
    }
    .venta-scan {
        padding: 0.65rem 0.85rem;
        border-bottom: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        border-top-left-radius: 13px;
        border-top-right-radius: 13px;
        position: relative;
    }
    .venta-scan .buscador-catalogo .buscador-navbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.55rem !important;
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border) !important;
        border-radius: 12px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
    }
    body.dark-mode .venta-scan .buscador-catalogo .buscador-navbar {
        background: var(--dash-card-bg);
        border-color: var(--dash-border) !important;
    }

    /* Ocultar el ícono de catálogo por defecto del componente BuscadorCatalogo */
    .venta-scan .buscador-catalogo .nav-link[title*="Catálogo con im"],
    .venta-scan .buscador-catalogo .nav-link[title*="Catalogo con im"],
    .venta-scan .buscador-catalogo a[title*="Catálogo con im"],
    .venta-scan .buscador-catalogo a[title*="Catalogo con im"],
    .venta-scan .buscador-catalogo a[title*="imágenes"],
    .venta-scan .buscador-catalogo a[title*="imagenes"],
    .venta-scan .buscador-catalogo .navbar-nav.flex-row > li > a:not(.venta-atajo) {
        display: none !important;
    }
    .venta-scan .buscador-catalogo .navbar-nav.flex-row > li:has(> a:not(.venta-atajo)) {
        display: none !important;
    }

    /* Input del buscador flexible */
    .venta-scan .buscador-catalogo .navbar-nav.w-100 {
        flex: 1 1 300px;
        min-width: 180px;
        width: auto !important;
    }
    .venta-scan .autocomplete {
        position: relative;
        width: 100%;
    }
    .venta-scan .autocomplete-input {
        width: 100%;
        border-radius: 10px !important;
        border: 1px solid var(--dash-border) !important;
        background-color: var(--dash-panel-bg) !important;
        color: var(--dash-text-main) !important;
        font-size: 0.92rem;
        padding: 0.52rem 1rem 0.52rem 2.65rem !important;
        transition: all 0.15s ease;
    }
    .venta-scan .autocomplete-input:focus,
    .venta-scan .autocomplete-input[aria-expanded="true"] {
        border-color: var(--dash-primary) !important;
        background-color: var(--dash-card-bg) !important;
        box-shadow: 0 0 0 3px var(--dash-primary-light) !important;
    }
    body.dark-mode .venta-scan .autocomplete-input {
        background-color: var(--dash-panel-bg) !important;
        border-color: var(--dash-border) !important;
        color: var(--dash-text-main) !important;
    }

    /* Contenedor y alineación de los atajos del buscador */
    .venta-scan .buscador-catalogo .navbar-nav.flex-row {
        flex: 0 0 auto;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        margin-left: auto;
        padding: 0;
    }
    .venta-scan .buscador-catalogo .navbar-nav.flex-row > li {
        display: flex;
        align-items: center;
    }

    /* Botones de acción rápida (Libre, Catálogo, Combos) */
    .venta-atajo {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.75rem !important;
        border-radius: 8px;
        background: var(--dash-primary-light);
        border: 1px solid var(--dash-primary-border);
        color: var(--dash-primary-dark) !important;
        font-weight: 700;
        font-size: 0.82rem;
        line-height: 1;
        transition: all 0.15s ease;
        white-space: nowrap;
        text-decoration: none !important;
    }
    .venta-atajo:hover {
        background: var(--dash-primary);
        color: #ffffff !important;
        border-color: var(--dash-primary);
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(10, 77, 54, 0.2);
    }
    .venta-atajo-sec {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        color: var(--dash-text-main) !important;
        font-weight: 600;
    }
    .venta-atajo-sec:hover {
        background: var(--dash-primary-light);
        color: var(--dash-primary-dark) !important;
        border-color: var(--dash-primary);
    }
    body.dark-mode .venta-atajo-sec {
        background: var(--dash-panel-bg);
        border-color: var(--dash-border);
        color: var(--dash-text-main) !important;
    }
    body.dark-mode .venta-atajo-sec:hover {
        background: var(--dash-primary-light);
        color: #a7f3d0 !important;
        border-color: var(--dash-primary);
    }

    /* Desplegable Autocomplete Flotante y Amplio */
    .venta-scan .autocomplete-result-container {
        position: absolute;
        top: calc(100% + 4px) !important;
        left: 0;
        z-index: 1050 !important;
        width: auto !important;
        min-width: 100% !important;
    }
    .venta-scan .autocomplete-result-list {
        min-width: min(560px, calc(100vw - 2.5rem)) !important;
        max-width: min(720px, calc(100vw - 2.5rem)) !important;
        width: max-content;
        background: var(--dash-card-bg) !important;
        border: 1px solid var(--dash-border) !important;
        border-radius: 12px !important;
        box-shadow: 0 14px 35px rgba(0, 0, 0, 0.16) !important;
        padding: 0.35rem 0 !important;
        max-height: 380px !important;
        overflow-y: auto !important;
        scrollbar-width: thin;
    }
    body.dark-mode .venta-scan .autocomplete-result-list {
        background: var(--dash-card-bg) !important;
        border-color: var(--dash-border) !important;
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.6) !important;
        color: var(--dash-text-main) !important;
    }
    .venta-scan .autocomplete-result-list::-webkit-scrollbar {
        width: 6px;
    }
    .venta-scan .autocomplete-result-list::-webkit-scrollbar-thumb {
        background-color: var(--dash-border);
        border-radius: 4px;
    }

    /* Ítems del resultado de búsqueda */
    .venta-scan .autocomplete-result {
        padding: 0.6rem 0.85rem 0.6rem 2.65rem !important;
        min-height: 44px !important;
        border-bottom: 1px solid var(--dash-border);
        transition: background 0.12s ease;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 0.75rem !important;
        color: var(--dash-text-main) !important;
    }
    .venta-scan .autocomplete-result:last-child {
        border-bottom: none;
    }
    .venta-scan .autocomplete-result:hover,
    .venta-scan .autocomplete-result[aria-selected="true"] {
        background-color: var(--dash-primary-light) !important;
        color: var(--dash-primary-dark) !important;
    }
    body.dark-mode .venta-scan .autocomplete-result:hover,
    body.dark-mode .venta-scan .autocomplete-result[aria-selected="true"] {
        background-color: #064e3b !important;
        color: #a7f3d0 !important;
    }
    .venta-scan .autocomplete-result .left {
        flex: 1 1 auto;
        min-width: 0;
        font-weight: 600;
        font-size: 0.88rem;
        line-height: 1.3;
        color: var(--dash-text-main);
        white-space: normal !important;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    body.dark-mode .venta-scan .autocomplete-result .left {
        color: var(--dash-text-main) !important;
    }
    .venta-scan .autocomplete-result.text-maroon .left,
    .venta-scan .autocomplete-result.text-maroon .precio {
        color: var(--dash-danger) !important;
    }
    .venta-scan .autocomplete-result .right {
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
    }
    .venta-scan .autocomplete-result .catalogo-stock {
        padding: 0.2rem 0.55rem;
        border-radius: 6px;
        font-size: 0.73rem;
        font-weight: 700;
        white-space: nowrap;
        background: #dcfce7;
        color: #166534;
    }
    .venta-scan .autocomplete-result .catalogo-stock.agotado {
        background: #fee2e2;
        color: #991b1b;
    }
    body.dark-mode .venta-scan .autocomplete-result .catalogo-stock {
        background: #064e3b;
        color: #a7f3d0;
    }
    body.dark-mode .venta-scan .autocomplete-result .catalogo-stock.agotado {
        background: #450a0a;
        color: #fca5a5;
    }
    .venta-scan .autocomplete-result .precio {
        font-family: 'Cairo', sans-serif;
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--dash-primary-dark);
        white-space: nowrap;
    }
    body.dark-mode .venta-scan .autocomplete-result .precio {
        color: #10b981;
    }

    /* Adaptación para pantallas pequeñas / móviles */
    @media (max-width: 767.98px) {
        .venta-scan .buscador-catalogo .buscador-navbar {
            flex-direction: column;
            align-items: stretch;
            gap: 0.4rem;
        }
        .venta-scan .buscador-catalogo .navbar-nav.w-100 {
            width: 100% !important;
            flex: 1 1 100%;
        }
        .venta-scan .buscador-catalogo .navbar-nav.flex-row {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.35rem;
            margin-left: 0 !important;
        }
        .venta-scan .buscador-catalogo .navbar-nav.flex-row > li {
            width: 100%;
        }
        .venta-scan .venta-atajo {
            width: 100%;
            justify-content: center;
            padding: 0.42rem 0.25rem !important;
            font-size: 0.78rem;
        }
        .venta-scan .autocomplete-result-list {
            min-width: 100% !important;
            max-width: 100% !important;
            width: 100% !important;
        }
        .venta-scan .autocomplete-result {
            padding-left: 2.2rem !important;
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.35rem !important;
        }
        .venta-scan .autocomplete-result .right {
            width: 100%;
            justify-content: space-between;
        }
    }

    /* Ticket / Cart */
    .ticket {
        padding: 0.75rem 1rem;
    }
    .ticket-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        padding-bottom: 0.35rem;
        border-bottom: 1px dashed var(--dash-border);
    }
    .ticket-vaciar {
        background: transparent;
        border: none;
        color: var(--dash-danger);
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        padding: 0.2rem 0.5rem;
        border-radius: 6px;
        transition: background 0.15s ease;
    }
    .ticket-vaciar:hover {
        background: var(--dash-danger-light);
    }
    .ticket-head {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 140px 140px 36px;
        gap: 0.75rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--dash-text-muted);
        font-weight: 700;
        padding: 0.4rem 0.5rem;
        border-bottom: 1px solid var(--dash-border);
    }
    .ticket-lista {
        list-style: none;
        padding: 0;
        margin: 0;
        max-height: calc(100vh - 380px);
        min-height: 220px;
        overflow-y: auto;
    }
    .ticket-linea {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 140px 140px 36px;
        gap: 0.75rem;
        align-items: center;
        padding: 0.65rem 0.5rem;
        border-bottom: 1px solid var(--dash-border);
        transition: background-color 0.12s ease;
    }
    .ticket-linea:hover {
        background-color: rgba(0, 0, 0, 0.015);
    }
    body.dark-mode .ticket-linea:hover {
        background-color: rgba(255, 255, 255, 0.02);
    }
    .ticket-nombre {
        font-weight: 700;
        font-size: 0.92rem;
        display: block;
        color: var(--dash-text-main);
        line-height: 1.25;
    }
    .ticket-meta {
        font-size: 0.78rem;
        color: var(--dash-text-muted);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.25rem;
        margin-top: 0.2rem;
    }
    .venta-qty-wrapper {
        display: inline-flex;
        align-items: center;
        justify-self: center;
        width: 110px;
        max-width: 100%;
        border: 1px solid var(--dash-border);
        border-radius: 8px;
        background: var(--dash-card-bg);
        overflow: hidden;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .venta-qty-wrapper:focus-within {
        border-color: var(--dash-primary);
        box-shadow: 0 0 0 2px var(--dash-primary-light);
    }
    .btn-qty {
        flex: 0 0 32px;
        width: 32px;
        height: 32px;
        border: none;
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        font-weight: 700;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.15s ease, color 0.15s ease;
        padding: 0;
        user-select: none;
    }
    .btn-qty:hover {
        background: var(--dash-primary-light);
        color: var(--dash-primary);
    }
    .venta-qty {
        flex: 1 1 0;
        width: 100% !important;
        min-width: 0;
        height: 32px;
        border: none !important;
        box-shadow: none !important;
        outline: none !important;
        text-align: center;
        font-weight: 700;
        background: transparent;
        color: var(--dash-text-main);
        padding: 0 2px;
        -moz-appearance: textfield;
    }
    .venta-qty::-webkit-inner-spin-button,
    .venta-qty::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .venta-qty:focus {
        box-shadow: none !important;
        outline: none !important;
        background: transparent;
    }
    .ticket-gs {
        background: transparent;
        border: 1px solid transparent;
        border-radius: 8px;
        padding: 0.35rem 0.5rem;
        text-align: right;
        cursor: pointer;
        transition: all 0.15s ease;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }
    .ticket-gs:hover {
        background: var(--dash-panel-bg);
        border-color: var(--dash-border);
    }
    .ticket-gs strong {
        font-size: 1rem;
        color: var(--dash-primary-dark);
        line-height: 1.1;
    }
    body.dark-mode .ticket-gs strong {
        color: #10b981;
    }
    .ticket-gs span {
        font-size: 0.72rem;
        color: var(--dash-text-muted);
    }
    .ticket-quitar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: var(--dash-text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .ticket-quitar:hover {
        background: var(--dash-danger-light);
        color: var(--dash-danger);
    }
    .venta-vacio-box {
        padding: 3.5rem 1.5rem;
        text-align: center;
        color: var(--dash-text-muted);
    }
    .venta-vacio-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: var(--dash-text-muted);
        margin: 0 auto 1rem;
    }
    .venta-vacio-title {
        font-size: 1.1rem;
        color: var(--dash-text-main);
        margin-bottom: 0.35rem;
    }
    .ticket-foot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 1rem;
        margin-top: 0.5rem;
        border-top: 2px solid var(--dash-border);
    }
    .venta-total h2 {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--dash-primary-dark);
        margin: 0;
        line-height: 1;
    }
    body.dark-mode .venta-total h2 {
        color: #10b981;
    }

    /* Right Panel (Cobro) */
    .card.venta-cobro {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        overflow: visible;
        position: relative;
    }
    .card.venta-cobro > .card-footer {
        border-bottom-left-radius: 13px;
        border-bottom-right-radius: 13px;
    }
    .doc-rail-label {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--dash-text-muted);
        font-weight: 700;
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
    }
    .venta-switch {
        display: flex;
        gap: 0.35rem;
        background: var(--dash-panel-bg);
        padding: 0.3rem;
        border-radius: 10px;
        border: 1px solid var(--dash-border);
    }
    .venta-switch button {
        flex: 1;
        border: none;
        background: transparent;
        color: var(--dash-text-muted);
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.45rem 0.5rem;
        border-radius: 7px;
        cursor: pointer;
        transition: all 0.15s ease;
        text-align: center;
    }
    .venta-switch button:hover {
        color: var(--dash-text-main);
    }
    .venta-switch button.on {
        background: var(--dash-primary);
        color: #ffffff;
        font-weight: 700;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
    }
    .doc-ayuda {
        font-size: 0.78rem;
        color: var(--dash-text-muted);
    }
    .venta-sifen {
        padding: 0.45rem 0.75rem;
        border-radius: 8px;
        font-size: 0.82rem;
        line-height: 1.35;
    }
    .venta-sifen.is-ready {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .venta-sifen.is-blocked {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    body.dark-mode .venta-sifen.is-ready {
        background: #064e3b;
        color: #a7f3d0;
        border-color: #047857;
    }
    body.dark-mode .venta-sifen.is-blocked {
        background: #451a03;
        color: #fde68a;
        border-color: #78350f;
    }

    /* Cliente Picker */
    .cliente-picker {
        position: relative;
        z-index: 100;
    }
    .cliente-picker-trigger {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        border-radius: 10px;
        padding: 0.6rem 0.85rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--dash-text-main);
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .cliente-picker-trigger:hover,
    .cliente-picker-trigger.open {
        border-color: var(--dash-primary);
        background: var(--dash-card-bg);
    }
    .cliente-picker-trigger.needs-ruc {
        border-color: #f59e0b;
        background: #fffbeb;
    }
    body.dark-mode .cliente-picker-trigger.needs-ruc {
        background: #451a03;
        border-color: #b45309;
    }
    .cliente-picker-panel {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        z-index: 1050;
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.16);
        padding: 0.75rem;
    }
    body.dark-mode .cliente-picker-panel {
        box-shadow: 0 14px 36px rgba(0, 0, 0, 0.6);
    }
    .cliente-picker-search {
        position: relative;
        margin-bottom: 0.5rem;
    }
    .cliente-picker-search i.fa-search {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--dash-text-muted);
        font-size: 0.85rem;
    }
    .cliente-picker-search input {
        width: 100%;
        padding: 0.45rem 2rem 0.45rem 2rem;
        border-radius: 8px;
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        font-size: 0.88rem;
    }
    .cliente-picker-search input:focus {
        outline: none;
        border-color: var(--dash-primary);
        background: var(--dash-card-bg);
    }
    .cliente-picker-search .btn-clear {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        color: var(--dash-text-muted);
        cursor: pointer;
        padding: 0 4px;
    }
    .cliente-picker-list {
        max-height: 210px;
        overflow-y: auto;
        scrollbar-width: thin;
    }
    .cliente-picker-list::-webkit-scrollbar {
        width: 6px;
    }
    .cliente-picker-list::-webkit-scrollbar-thumb {
        background-color: var(--dash-border);
        border-radius: 4px;
    }
    .cliente-picker-item {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.65rem;
        border: none;
        background: transparent;
        color: var(--dash-text-main);
        border-radius: 8px;
        cursor: pointer;
        text-align: left;
        font-size: 0.85rem;
        transition: background 0.12s ease;
    }
    .cliente-picker-item:hover,
    .cliente-picker-item.active {
        background: var(--dash-primary-light);
        color: var(--dash-primary-dark);
    }
    .cliente-picker-create {
        width: 100%;
        margin-top: 0.5rem;
        padding: 0.45rem;
        border: 1px dashed var(--dash-primary);
        border-radius: 8px;
        background: var(--dash-primary-light);
        color: var(--dash-primary-dark);
        font-weight: 700;
        font-size: 0.82rem;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .cliente-picker-create:hover {
        background: var(--dash-primary);
        color: #ffffff;
    }

    /* Botón Cobrar */
    .btn-cobrar {
        width: 100%;
        padding: 0.95rem 1.25rem;
        background: linear-gradient(135deg, var(--dash-primary), var(--dash-primary-dark));
        color: #ffffff !important;
        font-weight: 700;
        font-size: 1.35rem;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }
    .btn-cobrar:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(10, 77, 54, 0.35);
        color: #ffffff !important;
    }
    .btn-cobrar:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
    .cobrar-motivo {
        display: block;
        font-size: 0.82rem;
        color: #e11d48;
        font-weight: 600;
    }
    .cobro-hint-rail {
        display: block;
        font-size: 0.82rem;
        color: var(--dash-text-muted);
    }
    .shortcut-key {
        display: inline-block;
        padding: 0.15rem 0.4rem;
        font-size: 0.75rem;
        font-weight: 700;
        line-height: 1;
        color: var(--dash-text-main);
        background-color: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        border-radius: 4px;
        box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    }
    .btn-volver-ticket {
        background: transparent;
        border: 1px solid var(--dash-border);
        color: var(--dash-text-muted);
        border-radius: 8px;
        padding: 0.4rem 0.75rem;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-volver-ticket:hover {
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
    }

    /* Modal Cobro Components */
    .pago-total {
        background: var(--dash-primary-light);
        border: 1px solid var(--dash-primary-border) !important;
        border-radius: 12px;
    }
    .pago-grupo-title {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--dash-text-muted);
        margin-bottom: 0.5rem;
    }
    .pago-metodos-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
    }
    .pago-metodo-tile {
        background: var(--dash-card-bg);
        border: 2px solid var(--dash-border);
        border-radius: 12px;
        padding: 0.75rem 0.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.15s ease;
        color: var(--dash-text-main);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
    }
    .pago-metodo-tile:hover {
        border-color: var(--dash-primary);
        transform: translateY(-1px);
    }
    .pago-metodo-tile.selected {
        border-color: var(--dash-primary);
        background: var(--dash-primary-light);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        font-weight: 700;
    }
    .tile-icon {
        font-size: 1.35rem;
        color: var(--dash-primary);
    }
    .tile-label {
        font-size: 0.82rem;
        font-weight: 600;
    }
    .pago-condicion-switch {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
        background: var(--dash-panel-bg);
        padding: 0.35rem;
        border-radius: 10px;
        border: 1px solid var(--dash-border);
    }
    .pago-chip {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        border: 1px solid transparent;
        background: transparent;
        color: var(--dash-text-muted);
        font-weight: 600;
        font-size: 0.88rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .pago-chip:hover {
        color: var(--dash-text-main);
    }
    .pago-chip.selected {
        background: var(--dash-primary);
        color: #ffffff;
        font-weight: 700;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .pago-efectivo-card {
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
    }
    .pago-billete {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        color: var(--dash-text-main);
        border-radius: 8px;
        padding: 0.35rem 0.75rem;
        font-size: 0.82rem;
        cursor: pointer;
        transition: all 0.15s ease;
        font-family: 'Cairo', sans-serif;
    }
    .pago-billete:hover {
        border-color: var(--dash-primary);
        background: var(--dash-primary-light);
        color: var(--dash-primary-dark);
    }
    .pago-vuelto-box {
        background: var(--dash-primary-light);
        border: 1px solid var(--dash-primary-border);
    }
    .vueltotext {
        color: var(--dash-primary-dark);
        font-size: 0.95rem;
    }
    body.dark-mode .vueltotext {
        color: #a7f3d0;
    }
    .search-input-efectivo {
        background: var(--dash-card-bg) !important;
        border: 2px solid var(--dash-border) !important;
        border-radius: 10px !important;
    }
    .search-input-efectivo:focus {
        border-color: var(--dash-primary) !important;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2) !important;
    }

    /* Modal Cobro 1280x720 and Compact Screen Optimization */
    #finalizarventa .modal-dialog {
        max-width: 840px;
        margin: 1rem auto;
    }
    #finalizarventa .modal-header {
        padding: 0.75rem 1.25rem 0.5rem;
    }
    #finalizarventa .modal-body {
        padding: 0.75rem 1.25rem;
        max-height: calc(100vh - 130px) !important;
        overflow-y: auto;
    }
    #finalizarventa .modal-footer {
        padding: 0.6rem 1.25rem;
        flex-shrink: 0;
    }
    #finalizarventa .pago-total {
        padding: 0.5rem 0.75rem !important;
        margin-bottom: 0.5rem !important;
    }
    #finalizarventa .pago-total-monto {
        font-size: 1.75rem !important;
        line-height: 1.15;
    }
    #finalizarventa .pago-grupo-title {
        font-size: 0.76rem;
        margin-bottom: 0.25rem;
    }
    #finalizarventa .pago-metodos-grid {
        gap: 0.35rem;
    }
    #finalizarventa .pago-metodo-tile {
        padding: 0.45rem 0.3rem;
        min-height: 56px;
        border-radius: 10px;
        gap: 0.2rem;
    }
    #finalizarventa .tile-icon {
        font-size: 1.15rem;
    }
    #finalizarventa .tile-label {
        font-size: 0.76rem;
    }
    #finalizarventa .pago-condicion-switch {
        padding: 0.25rem;
        gap: 0.35rem;
    }
    #finalizarventa .pago-chip {
        padding: 0.35rem 0.5rem;
        font-size: 0.82rem;
    }
    #finalizarventa .pago-efectivo-card {
        padding: 0.65rem 0.85rem !important;
    }
    #finalizarventa .search-input-efectivo {
        padding: 0.45rem 0.75rem;
        font-size: 1.25rem;
    }
    #finalizarventa .pago-billete {
        padding: 0.25rem 0.6rem;
        font-size: 0.78rem;
    }
    #finalizarventa .pago-vuelto-box {
        padding: 0.45rem 0.75rem !important;
        margin-top: 0.5rem !important;
    }
    #finalizarventa .cobro-footer-salir,
    #finalizarventa .cobro-footer-primario {
        display: flex;
        align-items: center;
    }
    #finalizarventa #btn-cobrar-imprimir {
        padding: 0.55rem 1.35rem;
        font-size: 1rem;
    }

    @media (max-width: 767.98px) {
        #finalizarventa .modal-dialog {
            max-width: calc(100% - 1rem);
            margin: 0.5rem auto;
        }
        #finalizarventa .modal-footer {
            flex-direction: column-reverse;
            gap: 0.5rem;
            align-items: stretch !important;
        }
        #finalizarventa .cobro-footer-salir {
            justify-content: space-between;
            width: 100%;
        }
        #finalizarventa .cobro-footer-primario {
            width: 100%;
            justify-content: stretch;
        }
        #finalizarventa #btn-cobrar-imprimir {
            width: 100%;
            justify-content: center;
        }
    }

    /* Modal Precios Cards */
    .precio-listas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 0.75rem;
    }
    .precio-card-btn {
        background: var(--dash-card-bg);
        border: 2px solid var(--dash-border);
        border-radius: 12px;
        padding: 0.85rem 1rem;
        text-align: left;
        cursor: pointer;
        transition: all 0.15s ease;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        color: var(--dash-text-main);
        position: relative;
    }
    .precio-card-btn:hover {
        border-color: var(--dash-primary);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .precio-card-btn.on {
        border-color: var(--dash-primary);
        background: var(--dash-primary-light);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.18);
    }
    .precio-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .precio-card-name {
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--dash-text-muted);
        font-weight: 700;
    }
    .precio-card-monto {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--dash-primary-dark);
    }
    body.dark-mode .precio-card-monto {
        color: #10b981;
    }
    .precio-card-note {
        font-size: 0.78rem;
        color: var(--dash-text-muted);
    }
    .check-active {
        color: var(--dash-primary);
        font-size: 1.1rem;
    }

    /* Combos Grid */
    .combos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 0.75rem;
    }
    .combo-card-btn {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        padding: 0.85rem 1rem;
        text-align: left;
        cursor: pointer;
        transition: all 0.15s ease;
        color: var(--dash-text-main);
    }
    .combo-card-btn:hover {
        border-color: var(--dash-primary);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        transform: translateY(-1px);
    }
    .combo-gs {
        font-size: 1.15rem;
        color: var(--dash-primary);
    }
    .badge-stock-ok {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    body.dark-mode .badge-stock-ok {
        background: #064e3b;
        color: #a7f3d0;
        border-color: #047857;
    }

    /* Catalogo Modal Components */
    #modalCatalogoArticulos {
        z-index: 1060 !important;
    }
    #modalCatalogoArticulos .modal-dialog {
        z-index: 1061 !important;
        max-width: 95vw;
    }
    @media (min-width: 1400px) {
        #modalCatalogoArticulos .modal-dialog {
            max-width: 1320px;
        }
    }
    .catalogo-zoom-overlay {
        z-index: 2050 !important;
    }
    .catalogo-card-wrap {
        position: relative;
        height: 100%;
    }
    .catalogo-card-img img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }

    /* Thumb Bar Mobile */
    .venta-thumb-bar {
        display: none;
    }
    @media (max-width: 768px) {
        .venta-thumb-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1040;
            background: var(--dash-card-bg);
            border-top: 1px solid var(--dash-border);
            padding: 0.65rem 1rem;
            box-shadow: 0 -4px 15px rgba(0,0,0,0.1);
        }
        .thumb-mid {
            text-align: center;
        }
        .thumb-mid .total {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--dash-primary);
        }
        .btn-vaciar {
            background: transparent;
            border: 1px solid var(--dash-border);
            border-radius: 8px;
            color: var(--dash-danger);
            padding: 0.4rem 0.75rem;
            font-size: 0.85rem;
        }
        .ticket-lista {
            max-height: 45vh;
        }
        .ticket-head,
        .ticket-linea {
            grid-template-columns: minmax(0, 1fr) 90px 95px 32px;
            gap: 0.35rem;
        }
        .venta-qty-wrapper {
            width: 86px;
        }
        .btn-qty {
            flex: 0 0 26px;
            width: 26px;
            height: 28px;
            font-size: 0.95rem;
        }
        .venta-qty {
            height: 28px;
            font-size: 0.85rem;
        }
    }
</style>
@endsection

@section('main')
    <div id="app" v-cloak>
        <div>
            <h1 class="sr-only">Cobrar</h1>
            <div class="row">
                <!-- PANEL IZQUIERDA -->
                <div class="col-md-8">
                    <!-- Top Bar con carros y estado de caja -->
                    <div class="venta-topbar">
                        @if(!empty($nombreLocal))
                            <span class="venta-local">
                                <i class="fa fa-store mr-1 text-success"></i> {{ $nombreLocal }}
                            </span>
                        @endif
                        <div class="venta-carros">
                            <template v-for="(cr, idx) in carritos">
                                <div class="btn-group mr-1 mb-1" role="group">
                                    <button type="button" class="btn btn-sm venta-tab" :class="{ 'is-on': indiceCarroActivo === idx }" @click="cambiarCarro(idx)" :title="'Carro ' + (idx + 1) + (cr.carro.length ? ' (' + cr.carro.length + ' ítem(s))' : '')">
                                        <i class="fa fa-shopping-basket mr-1"></i> Venta @{{ idx + 1 }}
                                        <span v-if="cr.carro.length" class="badge badge-count ml-1">@{{ cr.carro.length }}</span>
                                    </button>
                                    <button v-if="carritos.length > 1" type="button" class="btn btn-sm venta-tab venta-tab-del" :class="{ 'is-on': indiceCarroActivo === idx }" @click.stop="eliminarCarro(idx)" title="Eliminar carro">
                                        <span class="fa fa-times"></span>
                                    </button>
                                </div>
                            </template>
                            <button type="button" class="btn btn-sm venta-tab-add mb-1" @click="nuevoCarro()" title="Nuevo carro de venta" aria-label="Nuevo carro de venta">
                                <span class="fa fa-plus"></span>
                            </button>
                        </div>
                        <span class="badge badge-pill badge-caja" :class="{ 'is-cerrada': cajaConsultada && caja !== 'ABIERTA' }">
                            <span class="status-dot-pulse" v-if="cajaConsultada && caja === 'ABIERTA'"></span>
                            Caja @{{ cajaConsultada ? (caja === 'ABIERTA' ? 'abierta' : (caja === 'CERRADA' ? 'cerrada' : caja)) : '…' }}
                            <span v-if="nrooperacion && nrooperacion !== '...'"> · @{{ nrooperacion }}</span>
                        </span>
                    </div>

                    <!-- Visor y Scanner de Productos -->
                    <div class="venta-visor">
                        <div class="venta-scan">
                            <buscador-catalogo
                                ref="buscador"
                                url="{{ env('APP_APIDB') }}"
                                :idsucursal="ventaCabecera.idSucursal"
                                url-buscar="{{ url('articulo/buscar') }}"
                                url-foto-base="{{ asset('storage/articulos') }}"
                                img-fallback="{{ asset('img/sinimagen.png') }}"
                                route-articulo=""
                                validar-lote="false"
                                is-ready-balance="true"
                                precio-field="pre_venta1"
                                modal-id="modalCatalogoArticulos"
                                titulo="Catálogo"
                                scan-placeholder="Pasá el código de barras o escribí el nombre..."
                                @articulo="addCarrito"
                                @peso="setPeso"
                                @seleccion="agregarDesdeCatalogo"
                            >
                                <template slot="actions-after">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link venta-atajo venta-atajo-sec" @click.prevent="abrirItemLibre" title="Agregar ítem manual sin catálogo">
                                            <i class="fa fa-bolt" aria-hidden="true"></i> Libre
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link venta-atajo" @click.prevent="abrirCatalogo" title="Explorar catálogo visual con fotos">
                                            <i class="fa fa-th" aria-hidden="true"></i> Catálogo
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link venta-atajo venta-atajo-sec" @click.prevent="abrirModalCombos" title="Ver combos y promociones">
                                            <i class="fa fa-layer-group" aria-hidden="true"></i> Combos
                                        </a>
                                    </li>
                                </template>
                            </buscador-catalogo>
                        </div>

                        <!-- Ticket / Detalle de Carrito -->
                        <div class="ticket">
                            <div class="ticket-toolbar" v-if="carro.length">
                                <span class="small font-weight-bold text-muted">
                                    <i class="fa fa-shopping-basket mr-1 text-success"></i> @{{ carro.length }} ítem(s) en el ticket
                                </span>
                                <button type="button" class="ticket-vaciar" @click="cancelar">
                                    <i class="fa fa-trash-alt mr-1"></i> Vaciar ticket
                                </button>
                            </div>
                            <div class="ticket-head" v-if="carro.length">
                                <span>Ítem / Producto</span>
                                <span class="text-center">Cant.</span>
                                <span class="text-right">Total (Gs.)</span>
                                <span class="sr-only">Quitar</span>
                            </div>
                            <ul class="ticket-lista" v-if="carro.length">
                                <li class="ticket-linea" v-for="(item,index) in carroOrdenado" :key="item.linea_uid || (item.codigo + '-' + item.idstock + '-' + index)">
                                    <div class="ticket-que">
                                        <span class="ticket-nombre">@{{ item.descripcion }}</span>
                                        <span class="ticket-meta">
                                            <span v-if="item.es_libre" class="badge badge-warning text-dark font-weight-bold">Libre</span>
                                            <span v-else-if="item.es_combo" class="badge badge-primary font-weight-bold">Combo</span>
                                            <span v-else class="badge badge-light border text-muted">@{{ item.codigo }}</span>
                                            <span v-if="item.oferta_nombre" class="badge badge-info ml-1">@{{ item.oferta_nombre }}</span>
                                        </span>
                                    </div>
                                    <div class="venta-qty-wrapper">
                                        <button type="button" class="btn-qty" @click.stop="decrementarCantidad(item)" aria-label="Disminuir">-</button>
                                        <input type="number" class="venta-qty form-control form-control-sm font-cairo font-weight-bold"
                                            min="1" :max="(item.es_libre || item.es_combo) ? 999999 : item.stock" v-model.number="item.cantidad"
                                            @change="onCantidadCarritoChange(item)" :aria-label="'Cantidad de ' + item.descripcion">
                                        <button type="button" class="btn-qty" @click.stop="incrementarCantidad(item)" aria-label="Aumentar">+</button>
                                    </div>
                                    <button type="button" class="ticket-gs" @click="showModalPrecio(index, item)"
                                        :aria-label="'Elegí el precio de ' + item.descripcion + '. Unitario Gs. ' + format(item.precio) + '. Total Gs. ' + format(item.precio * item.cantidad)">
                                        <strong class="font-cairo">@{{ format(item.precio * item.cantidad) }}</strong>
                                        <span>Gs. @{{ format(item.precio) }} c/u</span>
                                    </button>
                                    <button type="button" class="ticket-quitar" title="Quitar del ticket" :aria-label="'Quitar ' + item.descripcion" @click="delArticulo(item)">
                                        <span class="fa fa-times" aria-hidden="true"></span>
                                    </button>
                                </li>
                            </ul>
                            <div class="venta-vacio-box" v-else>
                                <div class="venta-vacio-icon">
                                    <i class="fa fa-barcode"></i>
                                </div>
                                <div class="venta-vacio-title font-weight-bold font-cairo">Ticket sin ítems</div>
                                <p class="venta-vacio-text text-muted mb-0">
                                    Pasá el código de barras o escribí el nombre del producto para comenzar a cargar.
                                </p>
                            </div>
                            <div class="ticket-foot">
                                <span class="label font-weight-bold text-muted text-uppercase">Total General</span>
                                <div class="venta-total">
                                    <h2 class="font-cairo">Gs. @{{ totalVenta }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PANEL DERECHA (Cobro) -->
                <div class="col-md-4">
                    <div class="card venta-cobro shadow-sm">
                        <div class="card-body">
                            <!-- Tipo de Documento -->
                            <fieldset class="form-group venta-doc-rail">
                                <label class="doc-rail-label"><i class="fa fa-file-invoice mr-1 text-muted"></i> Qué sale</label>
                                <div class="venta-switch" role="group" aria-label="Documento">
                                    <button type="button" :class="{ on: ventaCabecera.documento === 'Ticket' }" :aria-pressed="ventaCabecera.documento === 'Ticket' ? 'true' : 'false'" @click="setDocumento('Ticket')">
                                        <i class="fa fa-receipt mr-1"></i> Ticket
                                    </button>
                                    <button type="button" :class="{ on: esFactura }" :aria-pressed="esFactura ? 'true' : 'false'" @click="setDocumento('Factura')">
                                        <i class="fa fa-file-invoice-dollar mr-1"></i> Factura
                                    </button>
                                    <button type="button" class="tertiary" :class="{ on: esComprobante }" :aria-pressed="esComprobante ? 'true' : 'false'" @click="setDocumento('Comprobante')">
                                        <i class="fa fa-file-alt mr-1"></i> Comprobante
                                    </button>
                                </div>
                                <p class="doc-ayuda mb-0 mt-1" v-if="esComprobante">
                                    <i class="fa fa-info-circle mr-1 text-secondary"></i> Comprobante de venta. No es factura electrónica.
                                </p>
                                <p class="doc-ayuda mb-0 mt-1" v-else-if="!esFactura">
                                    <i class="fa fa-info-circle mr-1 text-secondary"></i> Ticket interno. No es factura.
                                </p>
                                <div class="factura-quien mt-2" v-if="esFactura" role="status">
                                    <p class="venta-sifen mb-0" :class="motivoBloqueoFactura ? 'is-blocked' : 'is-ready'">
                                        <template v-if="!sifenActivo">
                                            <i class="fa fa-exclamation-circle mr-1"></i> Factura electrónica apagada.
                                            @if ($esAdministrador)
                                                <a href="{{ route('sifen.index') }}" class="font-weight-bold ml-1">Configurar SIFEN</a>
                                            @else
                                                Avisá al dueño: falta SIFEN.
                                            @endif
                                        </template>
                                        <template v-else-if="!sifenListo">
                                            <i class="fa fa-exclamation-triangle mr-1"></i> Falta @{{ sifenFalta || 'configuración' }} para facturar.
                                            @if ($esAdministrador)
                                                <a href="{{ route('sifen.index') }}" class="font-weight-bold ml-1">Completar</a>
                                            @else
                                                Avisá al dueño: falta SIFEN.
                                            @endif
                                        </template>
                                        <template v-else-if="esClienteOcasional || !clienteTieneRuc">
                                            <i class="fa fa-id-card mr-1"></i> Elegí un cliente con RUC para facturar.
                                        </template>
                                        <template v-else>
                                            <i class="fa fa-check-circle mr-1"></i> @{{ ventaCabecera.clienteNombre }} · RUC @{{ ventaCabecera.clienteRuc }}. Listo para factura electrónica@{{ sifenAmbiente === 'test' ? ' (prueba SIFEN)' : ' SIFEN' }}.
                                        </template>
                                    </p>
                                </div>
                            </fieldset>

                            <!-- Condición de Venta -->
                            <fieldset class="form-group venta-cond-rail">
                                <label class="doc-rail-label"><i class="fa fa-file-contract mr-1 text-muted"></i> Condición</label>
                                <div class="venta-switch" role="group" aria-label="Condición de venta">
                                    <button type="button" :class="{ on: !esCredito }" :aria-pressed="!esCredito ? 'true' : 'false'" @click="seleccionarCondicionVenta(1)">
                                        <i class="fa fa-coins mr-1"></i> Contado
                                    </button>
                                    <button type="button" :class="{ on: esCredito }" :aria-pressed="esCredito ? 'true' : 'false'" @click="seleccionarCondicionVenta(2)">
                                        <i class="fa fa-calendar-alt mr-1"></i> Crédito
                                    </button>
                                </div>
                                <div v-if="esCredito" class="mt-2">
                                    <div v-if="!cuotas || !cuotas.length" class="alert alert-warning py-1 px-2 mb-0 d-flex align-items-center justify-content-between rounded" style="font-size: 0.82rem;">
                                        <span><i class="fa fa-exclamation-triangle mr-1"></i> Faltan cuotas</span>
                                        <button type="button" class="btn btn-warning btn-sm py-0 px-2 font-weight-bold" @click="abrirCuotas" style="font-size: 0.75rem;">
                                            Armar
                                        </button>
                                    </div>
                                    <div v-else class="p-2 border rounded bg-light-panel d-flex align-items-center justify-content-between" style="font-size: 0.82rem;">
                                        <div class="text-truncate mr-1 text-dark-mode" :title="resumenCuotas">
                                            <span class="badge badge-success mr-1">@{{ cuotas.length }}c</span>
                                            <span class="font-weight-bold font-cairo">@{{ resumenCuotas }}</span>
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" @click="abrirCuotas" title="Editar cuotas" style="font-size: 0.75rem;">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    </div>
                                </div>
                            </fieldset>

                            <!-- Selector de Cliente Moderno -->
                            <fieldset class="form-group venta-cliente-rail">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <label class="doc-rail-label mb-0"><i class="fa fa-user mr-1 text-muted"></i> Cliente</label>
                                    <span class="badge badge-light border text-muted" v-if="esClienteOcasional">Ocasional</span>
                                    <span class="badge badge-success" v-else><i class="fa fa-check mr-1"></i>Registrado</span>
                                </div>
                                <div class="cliente-picker" ref="clientePicker">
                                    <button type="button" class="cliente-picker-trigger"
                                        :class="{ open: clientePickerOpen, 'needs-ruc': esFactura && sifenActivo && sifenListo && (esClienteOcasional || !clienteTieneRuc) }"
                                        :aria-expanded="clientePickerOpen ? 'true' : 'false'"
                                        aria-haspopup="true"
                                        aria-controls="cliente-picker-panel"
                                        @click="toggleClientePicker">
                                        <span class="cliente-trigger-nombre text-truncate" :class="{ placeholder: !ventaCabecera.clienteNombre }">
                                            <i class="fa fa-user-circle mr-1 text-muted"></i>
                                            @{{ ventaCabecera.clienteNombre || 'Seleccioná un cliente' }}
                                        </span>
                                        <span class="cliente-trigger-doc small text-muted ml-auto mr-1" v-if="clienteDocVisible">
                                            @{{ clienteDocVisible }}
                                        </span>
                                        <i class="fa ml-1" :class="clientePickerOpen ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                    </button>

                                    <div id="cliente-picker-panel" class="cliente-picker-panel" v-show="clientePickerOpen" @click.stop>
                                        <div class="cliente-picker-search">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                            <label for="txtclienteVenta" class="sr-only">Buscar cliente</label>
                                            <input type="text"
                                                id="txtclienteVenta"
                                                v-model="txtcliente"
                                                @input="onBuscarClienteInput"
                                                @keydown.enter.prevent="seleccionarPrimerCliente"
                                                @keydown.down.prevent="moverSeleccionCliente(1)"
                                                @keydown.up.prevent="moverSeleccionCliente(-1)"
                                                @keydown.esc.prevent="cerrarClientePicker"
                                                placeholder="Nombre o RUC..."
                                                aria-label="Buscar cliente"
                                                autocomplete="off">
                                            <button type="button" class="btn-clear" v-if="txtcliente"
                                                @click="limpiarBusquedaCliente" title="Limpiar">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>

                                        <div class="cliente-picker-empty" v-if="clienteBuscando">
                                            <i class="fa fa-spinner fa-spin mr-1"></i> Buscando cliente...
                                        </div>
                                        <div class="cliente-picker-empty" v-else-if="!clientes.length">
                                            No hay clientes para mostrar.
                                        </div>
                                        <div class="cliente-picker-list" v-else>
                                            <button type="button" class="cliente-picker-item"
                                                v-for="(cliente, index) in clientes"
                                                :key="cliente.clientes_cod"
                                                :class="{ active: index === clienteIndexActivo }"
                                                @click="selectCliente(cliente)"
                                                @mouseenter="clienteIndexActivo = index">
                                                <i class="fa fa-user mr-2 text-muted"></i>
                                                <span class="text-truncate">
                                                    <strong>@{{ cliente.cliente_nombre }}</strong>
                                                    <span class="meta d-block small text-muted" v-if="documentoCliente(cliente)">
                                                        Doc: @{{ documentoCliente(cliente) }}
                                                    </span>
                                                </span>
                                            </button>
                                        </div>

                                        <button type="button" class="cliente-picker-create"
                                            v-if="!mostrarFormClienteNuevo"
                                            @click="mostrarFormClienteNuevo = true">
                                            <i class="fa fa-plus-circle mr-1"></i> Crear cliente rápido
                                        </button>

                                        <div class="cliente-picker-form" v-else>
                                            <div class="form-group mb-2">
                                                <label for="cliente-nuevo-nombre" class="sr-only">Nombre del cliente</label>
                                                <input id="cliente-nuevo-nombre" type="text" class="form-control form-control-sm"
                                                    v-model.trim="clienteNuevo.nombre"
                                                    placeholder="Nombre del cliente *"
                                                    @keydown.enter.prevent="guardarClienteRapido">
                                            </div>
                                            <div class="form-group mb-2">
                                                <label for="cliente-nuevo-ruc" class="sr-only">CI o RUC</label>
                                                <input id="cliente-nuevo-ruc" type="text" class="form-control form-control-sm"
                                                    v-model.trim="clienteNuevo.doc"
                                                    :placeholder="esFactura ? 'RUC (necesario para SIFEN)' : 'CI / RUC (opcional)'">
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <button type="button" class="btn btn-link btn-sm px-0 text-muted"
                                                    @click="cancelarClienteNuevo">Cancelar</button>
                                                <button type="button" class="btn btn-success btn-sm font-weight-bold"
                                                    :disabled="clienteCreando"
                                                    @click="guardarClienteRapido">
                                                    <i class="fa mr-1" :class="clienteCreando ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                                                    Guardar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <!-- Fecha y Descuento -->
                            <details class="venta-mas">
                                <summary class="font-weight-bold text-muted small">
                                    <i class="fa fa-sliders-h mr-1"></i> Fecha y Descuento
                                </summary>
                                <div class="p-2 border rounded bg-light-panel mt-2">
                                    <fieldset class="form-group mb-2">
                                        <label for="venta-fecha" class="small font-weight-bold text-muted">Fecha de Venta</label>
                                        <input id="venta-fecha" type="date" class="form-control form-control-sm" v-model="ventaCabecera.fecha">
                                    </fieldset>
                                    <fieldset class="form-group mb-0">
                                        <label for="venta-descuento" class="small font-weight-bold text-muted">Descuento (Gs.)</label>
                                        <input id="venta-descuento" type="number" min="0" step="1" @input="clampDescuento" @keyup="saveDatos" class="form-control form-control-sm font-cairo"
                                            v-model="ventaCabecera.descuento" placeholder="0">
                                    </fieldset>
                                </div>
                            </details>
                        </div>

                        <!-- Card Footer Cobrar -->
                        <div class="card-footer bg-light-panel border-top py-3">
                            <div class="cobrar-row">
                                <button type="button" class="btn-cobrar font-cairo shadow-sm" @click="showFinalizar" :disabled="!puedeCobrar">
                                    <i class="fa fa-cash-register mr-2"></i> Cobrar
                                </button>
                            </div>
                            <div class="text-center mt-2">
                                <span class="cobrar-motivo" v-if="!puedeCobrar">@{{ motivoNoCobrar }}</span>
                                <span class="cobro-hint cobro-hint-rail" v-else-if="puedeCobrar">
                                    <kbd class="shortcut-key">Enter</kbd> o <kbd class="shortcut-key">F12</kbd> abre el cobro
                                </span>
                            </div>
                            <button type="button" class="btn-volver-ticket mt-2 w-100" v-if="motivoBloqueoFactura" @click="setDocumento('Ticket')">
                                <i class="fa fa-undo mr-1"></i> Volvé a Ticket
                            </button>
                        </div>
                    </div>
                </div>
            </div> <!-- end row -->
        </div>

        <!-- Thumb bar para móviles -->
        <div class="venta-thumb-bar">
            <button type="button" class="btn-vaciar" @click="cancelar">
                <i class="fa fa-trash-alt mr-1"></i> Vaciar
            </button>
            <div class="thumb-mid">
                <div class="total font-cairo">Gs. @{{ totalVenta }}</div>
                <span class="cobrar-motivo" v-if="!puedeCobrar">@{{ motivoNoCobrar }}</span>
                <span class="cobro-hint" v-else-if="puedeCobrar">Enter o F12 cobra</span>
            </div>
            <button type="button" class="btn-cobrar font-cairo" @click="showFinalizar" :disabled="!puedeCobrar">
                Cobrar
            </button>
        </div>

        @include('venta.finalizar')
        @include('venta.selprecio')

        <!-- Modal Ítem libre -->
        <div class="modal fade" id="modalItemLibre" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content shadow-lg border-0 modal-moderno">
                    <div class="modal-header border-0 pb-0">
                        <div class="d-flex align-items-center">
                            <div class="modal-header-icon mr-2">
                                <span class="fa fa-bolt text-warning" aria-hidden="true"></span>
                            </div>
                            <div>
                                <h5 class="modal-title font-weight-bold mb-0">Ítem Libre (Sin Catálogo)</h5>
                                <p class="small text-muted mb-0">Carga rápida para productos o servicios varios</p>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-3">
                        <div class="alert alert-light border small text-muted mb-3">
                            <i class="fa fa-info-circle mr-1 text-primary"></i> Para vender algo no registrado: cargá descripción y precio. No descuenta stock.
                        </div>
                        <div class="form-group mb-3">
                            <label for="fastItemDescripcion" class="font-weight-bold small text-muted text-uppercase">Descripción</label>
                            <input type="text" class="form-control" v-model.trim="fastItem.descripcion"
                                id="fastItemDescripcion" placeholder="Ej: Servicio técnico, accesorio varios..."
                                maxlength="255" @keyup.enter="focusFastPrecio">
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-0">
                                    <label for="fastItemCantidad" class="font-weight-bold small text-muted text-uppercase">Cantidad</label>
                                    <input type="number" class="form-control font-weight-bold font-cairo" v-model.number="fastItem.cantidad"
                                        id="fastItemCantidad" min="1" step="1">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-0">
                                    <label for="fastItemPrecio" class="font-weight-bold small text-muted text-uppercase">Precio Unitario</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text font-weight-bold font-cairo">Gs.</span>
                                        </div>
                                        <input type="number" class="form-control font-weight-bold font-cairo" v-model.number="fastItem.precio"
                                            id="fastItemPrecio" placeholder="0" min="1" step="1"
                                            @keyup.enter="addFastItem">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light-panel border-top py-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">
                            <i class="fa fa-times mr-1"></i> Cerrar
                        </button>
                        <button type="button" class="btn btn-success font-weight-bold font-cairo" @click="addFastItem">
                            <span class="fa fa-plus mr-1" aria-hidden="true"></span> Agregar al ticket
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal combos -->
        <div class="modal fade" id="modalCombos" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content shadow-lg border-0 modal-moderno">
                    <div class="modal-header border-0 pb-0">
                        <div class="d-flex align-items-center">
                            <div class="modal-header-icon mr-2">
                                <span class="fa fa-layer-group text-primary" aria-hidden="true"></span>
                            </div>
                            <div>
                                <h5 class="modal-title font-weight-bold mb-0 font-cairo">Combos y Paquetes de Artículos</h5>
                                <p class="small text-muted mb-0">Seleccioná un combo activo para cargarlo con todos sus componentes</p>
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-3">
                        <div v-if="comboModal.cargando" class="text-center text-muted py-4">
                            <span class="fa fa-spinner fa-spin fa-2x text-primary mb-2"></span>
                            <div class="font-weight-bold font-cairo">Cargando combos...</div>
                        </div>
                        <div v-else-if="!comboModal.items.length" class="text-center text-muted py-4">
                            <i class="fa fa-box-open fa-2x mb-2 text-muted"></i>
                            <div class="font-weight-bold">No hay combos activos registrados.</div>
                        </div>
                        <div v-else class="combos-grid">
                            <button type="button" class="combo-card-btn"
                                v-for="c in comboModal.items" :key="c.id"
                                @click="agregarComboAlCarrito(c)">
                                <div class="d-flex w-100 justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0 font-weight-bold text-dark-mode">@{{ c.nombre }}</h6>
                                    <div class="text-right">
                                        <strong class="combo-gs font-cairo">Gs. @{{ format(c.precio) }}</strong>
                                        <span v-if="Number(c.precio_credito) > 0" class="badge badge-info mt-1 d-block" style="font-size: 0.72rem; background: #0284c7;">
                                            <i class="fa fa-calendar-alt mr-1"></i>Crédito: Gs. @{{ format(c.precio_credito) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="small text-muted mb-2 text-left">
                                    <span v-for="(it, i) in c.items" :key="i">
                                        @{{ it.cantidad }}× @{{ it.nombre }}<span v-if="i < c.items.length - 1"> · </span>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between pt-1 border-top">
                                    <span class="badge" :class="c.stock_ok ? 'badge-stock-ok' : 'badge-warning'">
                                        <i class="fa" :class="c.stock_ok ? 'fa-check mr-1' : 'fa-exclamation-triangle mr-1'"></i>
                                        @{{ c.stock_ok ? 'Stock suficiente' : 'Falta stock' }}
                                    </span>
                                    <span class="badge badge-light border text-muted" v-if="c.precio_lista > c.precio">
                                        Lista Gs. @{{ format(c.precio_lista) }}
                                    </span>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer bg-light-panel border-top py-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">
                            <i class="fa fa-times mr-1"></i> Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- end app -->
    <div class="app-loading-skeleton">
        <div class="v-cloak-spinner"></div>
        <div class="font-cairo font-weight-bold" style="font-size: 1rem;">Cargando Punto de Venta...</div>
    </div>
@endsection
@section('script')
    <script src="{{ asset(mix('js/venta.js')) }}"></script>
    <script src="{{ asset('js/separator.js') }}"></script>
    <script type="text/javascript">

    $('#modalItemLibre').on('shown.bs.modal', function () {
        var el = document.getElementById('fastItemDescripcion');
        if (el) el.focus();
    });
        var app = new Vue({
            el: '#app',
            data: {
                articuloLibreId: {{ (int) ($articuloLibreId ?? 0) }},
                inputNumberClasses: {
                    input: "form-control form-control-lg text-success"
                },
                inputNumberClassesPrecio: {
                    input: "input-number-precio"
                },
                requestSend: false,
                requestFinalizar: false,
                currentPage: 1,
                opcionesEfectivo: [],
                carritos: [],
                indiceCarroActivo: 0,
                nextCarroId: 1,
                caja: '',
                cajaConsultada: false,
                nrooperacion: '',
                precioVista: 'contado',
                sifenActivo: {!! json_encode((bool) ($sifenActivo ?? false)) !!},
                sifenListo: {!! json_encode((bool) ($sifenListo ?? false)) !!},
                sifenAmbiente: {!! json_encode($sifenAmbiente ?? 'test') !!},
                sifenFalta: {!! json_encode($sifenFalta ?? null) !!},
                esAdministrador: {!! json_encode((bool) ($esAdministrador ?? false)) !!},
                urlFacturar: '{{ url('venta/facturar') }}',
                tmpIndexPrecio: {
                    iPrecio: 'CO1',
                    iArticulo: 0,
                    monto_cuota: 0,
                    is_multiple: false
                },
                txtbuscar: '',
                txtcliente: '',
                clienteBuscando: false,
                clienteBusquedaTimer: null,
                clienteBusquedaSeq: 0,
                clienteIndexActivo: 0,
                clientePickerOpen: false,
                mostrarFormClienteNuevo: false,
                clienteCreando: false,
                clienteNuevo: {
                    nombre: '',
                    doc: ''
                },
                filtro: {
                    seccion: 0,
                    columna: 0,
                    orden: 'ASC'
                },
                error: '',
                articulos: [],
                preciosContado: {
                    p1: 0,
                    m1: 10,
                    p2: 0,
                    m2: 20,
                    p3: 0,
                    m3: 30,
                    p4: 0,
                    m4: 40,
                    p5: 0,
                    m5: 0,
                    articulo: ''
                },
                peso: "",
                cantidad: 0,
                preciosCredito: [],
                articulo: null,
                clientes: [],
                requestLote: false,
                enfocar: false,
                defaultVentaCabecera: {
                    fecha: '',
                    clienteId: '1',
                    clienteNombre: 'Cliente Ocasional',
                    clienteRuc: '',
                    documento: 'Ticket',
                    idSucursal: 1,
                    formacobro: 1,
                    condicionventa: 1,
                    total: 0,
                    descuento: 0,
                    nro_operacion: 0,
                    generarcuota: true,
                    vender_sin_stock: 0,
                    descontar_stock: 1
                },
                fastItem: {
                    precio: 0,
                    descripcion: '',
                    cantidad: 1
                },
                comboModal: {
                    cargando: false,
                    items: []
                },
                ofertasActivas: []
            },
            methods: {
                search: function(input) {
                    console.log(input);
                },

                setCuotas: function(cuotas) {
                    this.cuotas = cuotas;
                    this.saveDatos && this.saveDatos();
                },
                setPeso: function(peso){
                    this.peso = peso;
                    const parteEntera = parseInt(peso.slice(0, 2), 10); 
                    const parteDecimal = parseInt(peso.slice(2, 4), 10);
                    const resultado = parteEntera + parteDecimal / 100;
                    this.cantidad = resultado;
                   
                },
                addCarrito: function(a) {

                    var Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                    });
                    //Buscar articulo si no esta en la lista
                    if (a.cantidad == 0 && this.ventaCabecera.vender_sin_stock == 0) {
                        console.log("No se puede agregar")
                        Toast.fire({
                            title: 'No se puede agregar un artículo sin stock.',
                            icon: 'error'
                        });
                        return;
                    }
                    let i = this.carro.findIndex(x => x.codigo == a.ARTICULOS_cod && x.idstock == a.id_stock);
                    if (i == -1) {
                        let art = {
                            codigo: a.ARTICULOS_cod,
                            idstock: a.id_stock,
                            descripcion: a.producto_nombre,
                            cantidad: this.peso.length > 0 ? this.cantidad : 1,
                            stock: a.cantidad,
                            precio: a.pre_venta1,
                            precio_lista: parseInt(a.pre_venta1, 10) || Number(a.pre_venta1) || 0,
                            p1: parseInt(a.pre_venta1),
                            p2: a.pre_venta2,
                            p3: a.pre_venta3,
                            p4: a.pre_venta4,
                            p5: a.pre_venta5,
                            m1: a.pre_margen1,
                            m2: a.pre_margen2,
                            m3: a.pre_margen3,
                            m4: a.pre_margen4,
                            m5: a.pre_margen5,
                            costo: a.producto_costo_compra,
                            iPrecio: 'CO1',
                            oferta_id: null,
                            oferta_nombre: null
                        }
                        

                        this.carro.push(art);
                        this.aplicarOfertaItem(art);
                        this.peso = '';
                        this.cantidad = 0;
                    } else {
                        //vender sin stock
                        if(this.ventaCabecera.vender_sin_stock==1){
                            this.carro[i].cantidad = this.peso.length > 0 ? +(this.carro[i].cantidad+ this.cantidad).toFixed(2) : parseInt(this.carro[i].cantidad) + 1;
                        }else{
                            if ((this.carro[i].cantidad + 1) <= a.cantidad) {
                                this.carro[i].cantidad = this.peso.length > 0 ? +(this.carro[i].cantidad+ this.cantidad).toFixed(2)  : parseInt(this.carro[i].cantidad) + 1;
                            } else {
                                Toast.fire({
                                    title: `Cantidad supera stock disponible: ${a.cantidad} ...`,
                                    icon: 'error'
                                });
                            }
                        }
                        this.aplicarOfertaItem(this.carro[i]);
                        //Actualizar cantidad
                    }
                    this.saveDatos();
                },
                cargarOfertasActivas: function () {
                    var self = this;
                    var fecha = (this.ventaCabecera && this.ventaCabecera.fecha) ? this.ventaCabecera.fecha : '';
                    axios.get('{{ url('oferta/activas') }}', { params: { fecha: fecha } })
                        .then(function (r) {
                            self.ofertasActivas = Array.isArray(r.data) ? r.data : [];
                            self.aplicarOfertasCarrito();
                        })
                        .catch(function () {
                            self.ofertasActivas = [];
                        });
                },
                ofertaAplica: function (o, cantidad, fecha) {
                    if (!o) return false;
                    cantidad = Number(cantidad) || 0;
                    var tipo = o.tipo;
                    var okCant = true;
                    var okFecha = true;

                    if (tipo === 'cantidad' || tipo === 'ambos') {
                        var min = Number(o.cantidad_min) || 0;
                        okCant = min > 0 && cantidad >= min;
                    }
                    if (tipo === 'fecha' || tipo === 'ambos') {
                        okFecha = true;
                        if (o.fecha_desde && fecha && fecha < o.fecha_desde) okFecha = false;
                        if (o.fecha_hasta && fecha && fecha > o.fecha_hasta) okFecha = false;
                        if (!o.fecha_desde && !o.fecha_hasta) okFecha = false;
                        if (!fecha && (o.fecha_desde || o.fecha_hasta)) {
                            // sin fecha de venta, usar hoy
                            var hoy = new Date();
                            fecha = hoy.getFullYear() + '-' + String(hoy.getMonth() + 1).padStart(2, '0') + '-' + String(hoy.getDate()).padStart(2, '0');
                            okFecha = true;
                            if (o.fecha_desde && fecha < o.fecha_desde) okFecha = false;
                            if (o.fecha_hasta && fecha > o.fecha_hasta) okFecha = false;
                        }
                    }
                    if (tipo === 'cantidad') return okCant;
                    if (tipo === 'fecha') return okFecha;
                    return okCant && okFecha;
                },
                calcularPrecioOferta: function (o, precioBase) {
                    var base = Number(precioBase) || 0;
                    var v = Number(o.descuento_valor) || 0;
                    if (o.descuento_tipo === 'monto') return Math.max(0, base - v);
                    if (o.descuento_tipo === 'precio_fijo') return Math.max(0, v);
                    return Math.max(0, Math.round(base * (1 - v / 100)));
                },
                aplicarOfertaItem: function (item) {
                    if (!item || item.es_libre || item.es_combo) return;
                    var base = Number(item.precio_lista);
                    if (!base) {
                        base = Number(item.p1) || Number(item.precio) || 0;
                        item.precio_lista = base;
                    }
                    var fecha = (this.ventaCabecera && this.ventaCabecera.fecha) ? this.ventaCabecera.fecha : '';
                    var best = null;
                    var bestPrecio = base;
                    var self = this;
                    (this.ofertasActivas || []).forEach(function (o) {
                        if (String(o.articulos_cod) !== String(item.codigo)) return;
                        if (!self.ofertaAplica(o, item.cantidad, fecha)) return;
                        var p = self.calcularPrecioOferta(o, base);
                        if (p < bestPrecio) {
                            bestPrecio = p;
                            best = o;
                        }
                    });
                    if (best) {
                        item.precio = bestPrecio;
                        item.oferta_id = best.id;
                        item.oferta_nombre = best.nombre;
                    } else {
                        if (!item.iPrecio || item.iPrecio === 'CO1') {
                            item.precio = base;
                        }
                        item.oferta_id = null;
                        item.oferta_nombre = null;
                    }
                },
                aplicarOfertasCarrito: function () {
                    var self = this;
                    (this.carro || []).forEach(function (item) {
                        self.aplicarOfertaItem(item);
                    });
                },
                onCantidadCarritoChange: function (item) {
                    this.aplicarOfertaItem(item);
                    this.saveDatos();
                },
                incrementarCantidad: function (item) {
                    if (!item) return;
                    var max = (item.es_libre || item.es_combo) ? 999999 : Number(item.stock || 999999);
                    var cant = Number(item.cantidad) || 0;
                    if (cant < max) {
                        item.cantidad = cant + 1;
                        this.onCantidadCarritoChange(item);
                    }
                },
                decrementarCantidad: function (item) {
                    if (!item) return;
                    var cant = Number(item.cantidad) || 0;
                    if (cant > 1) {
                        item.cantidad = cant - 1;
                        this.onCantidadCarritoChange(item);
                    }
                },
                getFecha: function() {

                    var f = new Date();
                    var dia = f.getDate();
                    var mes = (f.getMonth() + 1);
                    this.ventaCabecera.fecha = f.getFullYear() + "-" + mes.toString().padStart(2, "0") + "-" +
                        dia.toString().padStart(2, "0");
                    //this.filtrovalue= this.meses[mes];
                },
                setCantidad: async function(articulo) {
                    const swalBootstrap = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn-cobrar mr-2',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    })
                    const {
                        value: cant
                    } = await swalBootstrap.fire({
                        title: '¿Cuántas unidades?',
                        input: 'number',
                        inputValue: articulo.cantidad,
                        inputAttributes: {
                            min: 0,
                            max: articulo.stock
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Aceptar',
                        cancelButtonText: 'Cancelar'
                    })
                    if (cant) {
                        let realIndex = this.findCarroIndex(articulo);
                        if (realIndex !== -1) {
                            this.carro[realIndex].cantidad = cant;
                            this.aplicarOfertaItem(this.carro[realIndex]);
                        }
                        this.saveDatos();
                    }
                    this.$refs.buscador.focusSearchInput();
                },
                showModalPrecio: function(index, articulo) {
                    
                    this.articulo = articulo;
                    let realIndex = this.findCarroIndex(articulo);
                    this.tmpIndexPrecio.iArticulo = realIndex;
                    for (i = 1; i < 6; i++) {
                        this.preciosContado['m' + i] = parseInt(articulo['m' + i]);
                        this.preciosContado['p' + i] = parseInt(articulo['p' + i]);
                    }
                    this.preciosContado.articulo = articulo.descripcion;
                    this.precioVista = String(this.ventaCabecera.condicionventa) === '2' ? 'credito' : 'contado';
                    $('#selPrecio').modal('show');
                    this.preciosCredito = [];
                    if (articulo.es_combo) {
                        var pCred = Number(articulo.precio_credito) || 0;
                        if (pCred > 0) {
                            this.preciosCredito.push({
                                p: pCred,
                                c: pCred,
                                m: 0
                            });
                        }
                    } else {
                        axios.get('articulo/precios/' + articulo.codigo).then(response => {
                            if (response.data.length > 0)
                                this.preciosCredito = [];
                            for (i = 0; i < response.data.length; i++) {
                                let precios = {
                                    p: response.data[i].p,
                                    c: response.data[i].c,
                                    m: response.data[i].m
                                }
                                this.preciosCredito.push(precios);
                            }

                        }).catch(error => {
                            this.error = error.message;
                        });
                    }
                },
                setPrecio: function() {
                    $('#selPrecio').modal('hide');
                    let iPrecio = this.tmpIndexPrecio.iPrecio;
                    let x = iPrecio.substr(2);
                    let index = this.tmpIndexPrecio.iArticulo;
                    if (this.articulo && this.articulo.es_combo) {
                        if (iPrecio.includes('CO')) {
                            this.ventaCabecera.condicionventa = 1;
                            this.ventaCabecera.generarcuota = true;
                            let newPrecio = Number(this.articulo.precio_contado) || Number(this.articulo.p1) || Number(this.articulo.precio);
                            if (newPrecio > 0) {
                                this.carro[index].precio = newPrecio;
                                this.carro[index].iPrecio = 'CO1';
                            }
                        } else {
                            this.ventaCabecera.condicionventa = 2;
                            this.ventaCabecera.generarcuota = false;
                            let newPrecio = Number(this.articulo.precio_credito) || 0;
                            if (newPrecio > 0) {
                                this.carro[index].precio = newPrecio;
                                this.carro[index].iPrecio = 'CR0';
                                this.tmpIndexPrecio.monto_cuota = newPrecio;
                                this.tmpIndexPrecio.is_multiple = this.carro.length > 1;
                            }
                        }
                    } else if (iPrecio.includes('CO')) {
                        this.ventaCabecera.condicionventa = 1;
                        this.ventaCabecera.generarcuota = true;
                        let newPrecio = this.articulo['p' + x];
                        if (newPrecio > 0)
                            this.carro[index].precio = newPrecio;
                    } else {
                        this.ventaCabecera.condicionventa = 2
                        this.ventaCabecera.generarcuota = false;
                        let newPrecio = this.preciosCredito[x] ? this.preciosCredito[x].p : 0;
                        if (newPrecio > 0) {
                            
                            this.carro[index].precio = newPrecio;
                            this.tmpIndexPrecio.monto_cuota = this.preciosCredito[x].c;
                            this.tmpIndexPrecio.is_multiple = this.carro.length > 1;
                        }

                    }
                    this.$refs.buscador.focusSearchInput();
                    this.saveDatos();
                },
                findCarroIndex: function(a) {
                    if (a && a.linea_uid) {
                        return this.carro.findIndex(x => x.linea_uid === a.linea_uid);
                    }
                    if (a && a.es_libre) {
                        return this.carro.findIndex(x => x.es_libre
                            && x.descripcion === a.descripcion
                            && x.precio == a.precio
                            && x.cantidad == a.cantidad);
                    }
                    return this.carro.findIndex(x => x.codigo == a.codigo && x.idstock == a.idstock);
                },
                delArticulo: function(a) {
                    this.$refs.buscador.focusSearchInput();
                    let validar = this.findCarroIndex(a);
                    if (validar > -1) {
                        this.carro.splice(validar, 1);
                    }
                    this.saveDatos();
                    
                },
                format: function(numero) {
                    return new Intl.NumberFormat("de-DE").format(numero);
                },
                getApertura: function() {
                    let idSucursal = $('#sucursal').attr('data-id');
                    this.ventaCabecera.idSucursal = idSucursal;
                    var self = this;
                    if (idSucursal != null) {
                        axios.get('aperturacierre/' + idSucursal)
                            .then(response => {
                                if (response.data) {
                                    self.nrooperacion = response.data.nro_operacion;
                                    self.ventaCabecera.nro_operacion = response.data.nro_operacion;
                                    self.caja = 'ABIERTA';
                                } else {
                                    self.caja = 'CERRADA';
                                }
                                self.cajaConsultada = true;
                            })
                            .catch(error => {
                                console.log(error);
                                self.cajaConsultada = true;
                            });
                    } else {
                        this.cajaConsultada = true;
                        this.caja = 'CERRADA';
                    }
                },
                showFinalizar: function() {
                    if (!this.cajaConsultada) {
                        return;
                    }
                    if (this.caja != 'ABIERTA') {
                        Swal.fire('Caja cerrada', 'Abrí caja para cobrar.', 'warning');
                        return;
                    }
                    if (!(this.ventaCabecera.total > 0)) {
                        Swal.fire('Cargá un ítem', 'Pasá el código o el nombre para cobrar.', 'info');
                        return;
                    }
                    if (this.motivoBloqueoFactura) {
                        if (this.sifenActivo && this.sifenListo) {
                            this.abrirPickerRuc();
                        }
                        return;
                    }
                    this.sugerirEfectivoRecibido();
                    var self = this;
                    $('#finalizarventa').one('shown.bs.modal', function() {
                        self.$nextTick(function() {
                            self.focusCobroModal();
                        });
                    });
                    $('#finalizarventa').modal('show');
                },
                finalizar: function(print) {
                    if (this.requestFinalizar) {
                        return false;
                    }
                    var cabecera = this.ensureVentaCabecera();
                    if (!cabecera.idSucursal) {
                        Swal.fire('Sucursal requerida', 'Seleccioná una sucursal antes de finalizar la venta.', 'warning');
                        return false;
                    }
                    if (cabecera.condicionventa == 2 && this.cuotas.length < 1) {
                        Swal.fire('Faltan cuotas', 'Armá las cuotas del crédito antes de cobrar.', 'error');
                        return false;
                    }
                    if (this.motivoBloqueoFactura) {
                        Swal.fire('No se puede facturar', this.motivoBloqueoFactura, 'warning');
                        return false;
                    }
                    if (print && !this.esCredito && this.esEfectivo && !this.puedeEnterCobrar) {
                        return false;
                    }
                    this.calcularVuelto();
                    this.requestFinalizar = true;
                    axios.post('venta', {
                            ventaCabecera: cabecera,
                            detalle: this.carro,
                            cuotas: this.cuotas,
                            venta_recibido: Number(this.efectivoRecibido) || 0,
                            venta_vuelto: Number(this.vuelto) || 0
                        })
                        .then(response => {
                            this.requestFinalizar = false;
                            var docParaPrint = cabecera.documento;
                            this.carritos.splice(this.indiceCarroActivo, 1);
                            if (this.carritos.length === 0) {
                                this.carritos.push(this.getDefaultCarrito());
                            }
                            if (this.indiceCarroActivo >= this.carritos.length) {
                                this.indiceCarroActivo = this.carritos.length - 1;
                            }
                            this.saveDatos();
                            if (print) {
                                if (docParaPrint == 'Factura') {
                                    window.location.assign(this.urlFacturar + '/' + response.data);
                                } else if (docParaPrint == 'Ticket') {
                                    window.location.assign('{{ env('APP_URL') }}' + 'ticket/venta/' +
                                        response.data);
                                } else {
                                    window.location.assign('{{ env('APP_URL') }}' + 'pdf/boletaventa/' +
                                        response.data);
                                }
                            } else {
                                $('#finalizarventa').modal('hide');
                            }
                        })
                        .catch(error => {
                            this.requestFinalizar = false;
                            var msg = 'No se pudo guardar la venta. Intentá de nuevo.';
                            if (error.response && error.response.data && error.response.data.message) {
                                msg = error.response.data.message;
                            } else if (error.message) {
                                msg = error.message;
                            }
                            Swal.fire('Error', msg, 'error');
                        })
                },
                numeroaletra: function(n) {
                    return NumeroALetras.NumeroALetras(parseInt(n));
                },
                getDefaultCarrito: function() {
                    var id = this.nextCarroId++;
                    var def = this.defaultVentaCabecera;
                    var vc = (def && typeof def === 'object') ? JSON.parse(JSON.stringify(def)) : {
                        fecha: '', clienteId: '1', clienteNombre: 'Cliente Ocasional', documento: 'Ticket',
                        idSucursal: this.ventaCabecera.idSucursal, formacobro: 1, condicionventa: 1, total: 0, descuento: 0, nro_operacion: this.nrooperacion,
                        generarcuota: true, vender_sin_stock: 0, descontar_stock: 1
                    };
                    return { id: id, carro: [], ventaCabecera: vc, efectivoRecibido: 0, vuelto: 0, cuotas: [] };
                },
                saveDatos: function() {
                    localStorage.setItem('carritos_venta', JSON.stringify(this.carritos));
                    localStorage.setItem('indice_carro_activo', String(this.indiceCarroActivo));
                },
                recuperarDatos: function() {
                    var saved = localStorage.getItem('carritos_venta');
                    var idx = localStorage.getItem('indice_carro_activo');
                    if (saved != null && saved !== '' && saved !== 'undefined') {
                        try {
                            var arr = JSON.parse(saved);
                            if (Array.isArray(arr) && arr.length > 0) {
                arr.forEach(function(c) {
                                    if (!c.cuotas) c.cuotas = [];
                                    if (typeof c.efectivoRecibido === 'undefined') c.efectivoRecibido = 0;
                                    if (typeof c.vuelto === 'undefined') c.vuelto = 0;
                                    if (!c.ventaCabecera || typeof c.ventaCabecera !== 'object') {
                                        c.ventaCabecera = JSON.parse(JSON.stringify(this.defaultVentaCabecera));
                                    } else {
                                        var base = JSON.parse(JSON.stringify(this.defaultVentaCabecera));
                                        c.ventaCabecera = Object.assign(base, c.ventaCabecera);
                                    }
                                    if (Array.isArray(c.carro)) {
                                        c.carro.forEach(function(item, i) {
                                            if (item && item.es_libre && !item.linea_uid) {
                                                item.linea_uid = 'libre-rec-' + (c.id || 0) + '-' + i + '-' + Date.now();
                                            }
                                        });
                                    }
                                }.bind(this));
                                this.carritos = arr;
                                this.indiceCarroActivo = idx != null ? Math.min(parseInt(idx, 10) || 0, arr.length - 1) : 0;
                                if (this.nextCarroId <= Math.max.apply(null, this.carritos.map(function(c) { return c.id || 0; }))) {
                                    this.nextCarroId = Math.max.apply(null, this.carritos.map(function(c) { return c.id || 0; })) + 1;
                                }
                                return;
                            }
                        } catch (e) {}
                    }
                    var carroAntiguo = localStorage.getItem('carro_venta');
                    var cabAntigua = localStorage.getItem('ventaCabecera');
                    var carroValido = carroAntiguo != null && carroAntiguo !== '' && carroAntiguo !== 'undefined';
                    var cabValida = cabAntigua != null && cabAntigua !== '' && cabAntigua !== 'undefined';
                    if (carroValido || cabValida) {
                        var c = this.getDefaultCarrito();
                        if (carroValido) {
                            try { c.carro = JSON.parse(carroAntiguo); } catch (e) {}
                        }
                        if (cabValida) {
                            try {
                                var cab = JSON.parse(cabAntigua);
                                if (cab && typeof cab.total !== 'undefined') {
                                    var base = JSON.parse(JSON.stringify(this.defaultVentaCabecera));
                                    c.ventaCabecera = Object.assign(base, cab);
                                }
                            } catch (e) {}
                        }
                        c.ventaCabecera.condicionventa = 1;
                        c.ventaCabecera.generarcuota = true;
                        this.carritos = [c];
                        this.saveDatos();
                    } else {
                        this.carritos = [this.getDefaultCarrito()];
                    }
                    this.indiceCarroActivo = 0;
                },
                showBuscarCliente: function() {
                    this.toggleClientePicker(true);
                },
                toggleClientePicker: function (forceOpen) {
                    var open = typeof forceOpen === 'boolean' ? forceOpen : !this.clientePickerOpen;
                    if (!open) {
                        this.cerrarClientePicker();
                        return;
                    }
                    this.clientePickerOpen = true;
                    this.mostrarFormClienteNuevo = false;
                    this.clienteIndexActivo = 0;
                    if (this.clienteBusquedaTimer) {
                        clearTimeout(this.clienteBusquedaTimer);
                    }
                    this.buscarCliente();
                    var self = this;
                    this.$nextTick(function () {
                        self.focusClienteSearch();
                    });
                },
                cerrarClientePicker: function () {
                    this.clientePickerOpen = false;
                    this.mostrarFormClienteNuevo = false;
                    if (this.clienteBusquedaTimer) {
                        clearTimeout(this.clienteBusquedaTimer);
                    }
                },
                onClickOutsideClientePicker: function (e) {
                    if (!this.clientePickerOpen) return;
                    var root = this.$refs.clientePicker;
                    if (root && root.contains(e.target)) return;
                    this.cerrarClientePicker();
                },
                onBuscarClienteInput: function () {
                    var self = this;
                    if (this.clienteBusquedaTimer) {
                        clearTimeout(this.clienteBusquedaTimer);
                    }
                    this.clienteIndexActivo = 0;
                    var q = (this.txtcliente || '').trim();
                    var delay = q.length >= 2 ? 300 : 150;
                    this.clienteBusquedaTimer = setTimeout(function () {
                        self.buscarCliente();
                    }, delay);
                },
                limpiarBusquedaCliente: function () {
                    if (this.clienteBusquedaTimer) {
                        clearTimeout(this.clienteBusquedaTimer);
                    }
                    this.txtcliente = '';
                    this.clienteIndexActivo = 0;
                    this.buscarCliente();
                    var self = this;
                    this.$nextTick(function () {
                        self.focusClienteSearch();
                    });
                },
                moverSeleccionCliente: function (dir) {
                    if (!this.clientes.length) return;
                    var next = this.clienteIndexActivo + dir;
                    if (next < 0) next = this.clientes.length - 1;
                    if (next >= this.clientes.length) next = 0;
                    this.clienteIndexActivo = next;
                },
                seleccionarPrimerCliente: function () {
                    if (!this.clientes.length) {
                        this.buscarCliente();
                        return;
                    }
                    var idx = this.clienteIndexActivo >= 0 ? this.clienteIndexActivo : 0;
                    var c = this.clientes[idx];
                    if (c) {
                        this.selectCliente(c);
                    }
                },
                buscarCliente: function() {
                    var q = (this.txtcliente || '').trim();
                    var params = q.length >= 2
                        ? { q: q, limit: 50 }
                        : { limit: 10 };

                    var seq = ++this.clienteBusquedaSeq;
                    this.clienteBuscando = true;

                    axios.get('{{ url('cliente/buscar') }}', { params: params })
                        .then(response => {
                            if (seq !== this.clienteBusquedaSeq) return;
                            this.clientes = response.data || [];
                            this.clienteIndexActivo = 0;
                            this.clienteBuscando = false;
                        })
                        .catch(error => {
                            if (seq !== this.clienteBusquedaSeq) return;
                            this.clienteBuscando = false;
                            this.clientes = [];
                            console.log(error.message);
                        });
                },
                cancelarClienteNuevo: function () {
                    this.mostrarFormClienteNuevo = false;
                    this.clienteNuevo = { nombre: '', doc: '' };
                },
                guardarClienteRapido: function () {
                    var self = this;
                    var nombre = (this.clienteNuevo.nombre || '').trim();
                    if (!nombre) {
                        Swal.fire('Falta nombre', 'Indicá el nombre del cliente.', 'warning');
                        return;
                    }
                    this.clienteCreando = true;
                    axios.post('{{ url('cliente') }}', {
                        cliente: {
                            idciudad: 1,
                            doc: (this.clienteNuevo.doc || '').trim() || '0',
                            nombre: nombre,
                            direccion: '',
                            telefono: '',
                            celular: '',
                            correo: '',
                            celfamiliar: '',
                            ocupacion: '',
                            reflaboral: ''
                        }
                    }).then(function () {
                        return axios.get('{{ url('cliente/buscar') }}', {
                            params: { q: nombre, limit: 5 }
                        });
                    }).then(function (r) {
                        self.clienteCreando = false;
                        var list = r.data || [];
                        var found = list.find(function (c) {
                            return String(c.cliente_nombre).toUpperCase() === nombre.toUpperCase();
                        }) || list.find(function (c) {
                            return String(c.cliente_nombre || '').toUpperCase().indexOf(nombre.toUpperCase()) !== -1;
                        }) || list[0];
                        if (found) {
                            self.selectCliente(found);
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000,
                                icon: 'success',
                                title: 'Cliente creado'
                            });
                        } else {
                            self.cancelarClienteNuevo();
                            self.txtcliente = nombre;
                            self.buscarCliente();
                            Swal.fire('Creado', 'Cliente guardado. Buscalo para seleccionarlo.', 'success');
                        }
                    }).catch(function () {
                        self.clienteCreando = false;
                        Swal.fire('No se pudo crear', 'No se pudo crear el cliente. Intentá de nuevo.', 'error');
                    });
                },
                selectCliente: function(idOrCliente, nombre) {
                    var id = idOrCliente;
                    var clienteNombre = nombre;
                    var ruc = '';
                    if (idOrCliente && typeof idOrCliente === 'object') {
                        id = idOrCliente.clientes_cod;
                        clienteNombre = idOrCliente.cliente_nombre;
                        ruc = idOrCliente.cliente_ruc || idOrCliente.cliente_ci || '';
                    }
                    this.ventaCabecera.clienteId = id;
                    this.ventaCabecera.clienteNombre = clienteNombre;
                    this.ventaCabecera.clienteRuc = ruc;
                    this.txtcliente = '';
                    this.clientes = [];
                    this.clienteIndexActivo = 0;
                    this.cancelarClienteNuevo();
                    this.cerrarClientePicker();
                    this.saveDatos && this.saveDatos();
                },
                documentoCliente: function (c) {
                    if (!c) return '';
                    if (Number(c.clientes_cod) === 1 || (c.cliente_nombre && c.cliente_nombre.toLowerCase().indexOf('ocasional') !== -1)) {
                        return '';
                    }
                    var doc = String(c.cliente_ruc || c.cliente_ci || '').trim();
                    if (!doc || doc === '0' || doc === '1') return '';
                    return doc;
                },
                seleccionarFormaPago: function (valor) {
                    this.ventaCabecera.formacobro = valor;
                    this.saveDatos && this.saveDatos();
                    var self = this;
                    this.$nextTick(function() {
                        self.focusCobroModal();
                    });
                },
                seleccionarCondicionVenta: function (valor) {
                    this.ventaCabecera.condicionventa = valor;
                    if (String(valor) === '2') {
                        (this.carro || []).forEach(function (it) {
                            if (it.es_combo && Number(it.precio_credito) > 0) {
                                it.precio = Number(it.precio_credito);
                                it.iPrecio = 'CR0';
                            }
                        });
                        this.abrirCuotas();
                    } else if (String(valor) === '1') {
                        (this.carro || []).forEach(function (it) {
                            if (it.es_combo) {
                                it.precio = Number(it.precio_contado) || Number(it.p1) || Number(it.precio);
                                it.iPrecio = 'CO1';
                            }
                        });
                        this.ventaCabecera.generarcuota = true;
                        this.cuotas = [];
                    }
                    this.saveDatos && this.saveDatos();
                },
                abrirCuotas: function() {
                    if (this.$refs.generarcuota && (!this.cuotas || !this.cuotas.length)) {
                        this.$refs.generarcuota.generar();
                    }
                    $('#modalCuotas').modal('show');
                },
                confirmarCuotas: function() {
                    if (!(this.cuotas && this.cuotas.length)) {
                        Swal.fire('Faltan cuotas', 'Elegí un plan de cuotas.', 'warning');
                        return;
                    }
                    $('#modalCuotas').modal('hide');
                },
                setDocumento: function(tipo) {
                    if (!this.ventaCabecera) return;
                    this.ventaCabecera.documento = tipo;
                    this.saveDatos && this.saveDatos();
                    this.syncClienteSeccion();
                    if (tipo === 'Factura' && this.sifenActivo && this.sifenListo && (this.esClienteOcasional || !this.clienteTieneRuc)) {
                        this.abrirPickerRuc();
                    }
                },
                syncClienteSeccion: function() {
                    var det = this.$el && this.$el.querySelector('.venta-cliente');
                    if (!det) return;
                    var faltaRuc = this.esFactura && this.sifenActivo && this.sifenListo && (this.esClienteOcasional || !this.clienteTieneRuc);
                    if (faltaRuc) det.open = true;
                    else if (!this.esFactura) det.open = false;
                },
                abrirPickerRuc: function() {
                    var self = this;
                    this.buscarCliente();
                    this.clientePickerOpen = true;
                    this.$nextTick(function() {
                        self.focusClienteSearch();
                    });
                },
                focusClienteSearch: function() {
                    var el = document.getElementById('txtclienteVenta');
                    if (el) el.focus();
                },
                focusCobroModal: function() {
                    if (!$('#finalizarventa').hasClass('show')) return;
                    if (this.esEfectivo) {
                        var root = document.getElementById('efectivo-recibido-modal');
                        var el = root && (root.tagName === 'INPUT' ? root : root.querySelector('input'));
                        if (el) {
                            el.focus();
                            if (typeof el.select === 'function') el.select();
                            return;
                        }
                    }
                    var btn = document.getElementById('btn-cobrar-imprimir');
                    if (btn) btn.focus();
                },
                getSucursal: function() {
                    var obj = document.getElementById("sucursal");
                    var id = (obj && obj.getAttribute('data-id') != null) ? obj.getAttribute('data-id') : null;
                    if (!this.carritos.length) {
                        this.carritos.push(this.getDefaultCarrito());
                    }
                    if (!this.carritos[this.indiceCarroActivo].ventaCabecera) {
                        this.$set(this.carritos[this.indiceCarroActivo], 'ventaCabecera', JSON.parse(JSON.stringify(this.defaultVentaCabecera)));
                    }
                    if (id != null) {
                        this.$set(this.carritos[this.indiceCarroActivo].ventaCabecera, 'idSucursal', id);
                    } else if (!this.carritos[this.indiceCarroActivo].ventaCabecera.idSucursal) {
                        this.$set(this.carritos[this.indiceCarroActivo].ventaCabecera, 'idSucursal', this.defaultVentaCabecera.idSucursal);
                    }
                },
                ensureVentaCabecera: function() {
                    if (!this.carritos.length) {
                        this.carritos.push(this.getDefaultCarrito());
                    }
                    var act = this.carritos[this.indiceCarroActivo];
                    var defaults = this.defaultVentaCabecera;
                    if (!defaults || typeof defaults !== 'object') {
                        defaults = {
                            fecha: '',
                            clienteId: '1',
                            clienteNombre: 'Cliente Ocasional',
                            documento: 'Ticket',
                            idSucursal: 1,
                            formacobro: 1,
                            condicionventa: 1,
                            total: 0,
                            descuento: 0,
                            nro_operacion: 0,
                            generarcuota: true,
                            vender_sin_stock: 0,
                            descontar_stock: 1
                        };
                    }
                    if (!act.ventaCabecera || typeof act.ventaCabecera !== 'object') {
                        this.$set(act, 'ventaCabecera', JSON.parse(JSON.stringify(defaults)));
                    }
                    var vc = act.ventaCabecera;
                    var sid = $('#sucursal').attr('data-id');
                    Object.keys(defaults).forEach(function (k) {
                        if (typeof vc[k] === 'undefined' || vc[k] === null || vc[k] === '') {
                            if (k === 'idSucursal' && sid != null && sid !== '') {
                                vc[k] = sid;
                            } else if (k === 'nro_operacion' && this.nrooperacion && this.nrooperacion !== '...') {
                                vc[k] = this.nrooperacion;
                            } else {
                                vc[k] = defaults[k];
                            }
                        }
                    }.bind(this));
                    if (sid != null && sid !== '') {
                        vc.idSucursal = sid;
                    }
                    if (this.nrooperacion && this.nrooperacion !== '...') {
                        vc.nro_operacion = this.nrooperacion;
                    }
                    return vc;
                },
                validarLote: async function(articulo, lotes) {
                    var values = {};
                    for (var i = 0; i < lotes.length; i++) {
                        values[i] = lotes[i].lote_nro;
                    }
                    const {
                        value: lote
                    } = await Swal.fire({
                        title: 'Elegí el lote',
                        input: 'select',
                        inputOptions: values,
                        inputPlaceholder: 'Elegí un lote',
                        showCancelButton: true,
                        confirmButtonText: 'Listo',
                        cancelButtonText: 'Volver'
                    })
                    if (lote) {
                        this.addCarrito(articulo, lotes[lote].id_stock);
                    }
                },
                cancelar: function() {
                    var act = this.carritos[this.indiceCarroActivo];
                    if (!act || !act.carro.length) {
                        return;
                    }
                    var self = this;
                    Swal.fire({
                        title: '¿Vaciar este ticket?',
                        text: 'Se sacan todos los ítems de esta venta.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Vaciar',
                        cancelButtonText: 'Seguir cobrando',
                        confirmButtonColor: '#6b4f00'
                    }).then(function(result) {
                        if (!result.value) return;
                        act.carro = [];
                        act.ventaCabecera.total = 0;
                        act.ventaCabecera.descuento = 0;
                        act.ventaCabecera.condicionventa = 1;
                        act.ventaCabecera.generarcuota = true;
                        act.efectivoRecibido = 0;
                        act.vuelto = 0;
                        act.cuotas = [];
                        self.getFecha();
                        self.saveDatos();
                    });
                },
                abrirCatalogo: function() {
                    var root = document.getElementById('modalCatalogoArticulos');
                    if (root && root.parentNode !== document.body) {
                        document.body.appendChild(root);
                    }
                    this.tuneCatalogoVenta();
                    if (this.$refs.buscador && typeof this.$refs.buscador.abrirCatalogo === 'function') {
                        this.$refs.buscador.abrirCatalogo();
                    }
                    var self = this;
                    this.$nextTick(function () {
                        self.tuneCatalogoVenta();
                    });
                },
                tuneCatalogoVenta: function() {
                    this.tuneScanVenta();
                    var root = document.getElementById('modalCatalogoArticulos');
                    if (!root) return;
                    if (root.parentNode !== document.body) {
                        document.body.appendChild(root);
                    }
                },
                tuneScanVenta: function() {
                    var inp = document.querySelector('.venta-scan .autocomplete-input');
                    if (inp) {
                        inp.setAttribute('placeholder', 'Pasá el código o el nombre');
                        inp.setAttribute('aria-label', 'Pasá el código o el nombre');
                    }
                    document.querySelectorAll('.venta-scan .autocomplete-result .precio').forEach(function (el) {
                        el.textContent = el.textContent.replace(/^Gs\s/, 'Gs. ');
                    });
                    document.querySelectorAll('.venta-scan .buscador-catalogo a:not(.venta-atajo)').forEach(function (el) {
                        if (el.parentElement) {
                            el.parentElement.style.display = 'none';
                        }
                    });
                },
                abrirMasArbolCaja: function() {
                    if (document.body.classList.contains('sidebar-collapse')) return;
                    document.querySelectorAll('.main-sidebar .nav-item.nav-more').forEach(function (el) {
                        el.classList.add('menu-open');
                    });
                },
                onPushmenuCaja: function() {
                    var self = this;
                    setTimeout(function() {
                        self.abrirMasArbolCaja();
                    }, 0);
                },
                clampDescuento: function() {
                    if (!this.ventaCabecera) return;
                    var n = Number(this.ventaCabecera.descuento);
                    if (isNaN(n) || n < 0) this.ventaCabecera.descuento = 0;
                },
                onCajaKey: function(e) {
                    if (document.querySelector('.swal2-container')) return;
                    var modalOpen = $('#finalizarventa').hasClass('show');
                    var t = e.target;
                    var tag = ((t && t.tagName) || '').toLowerCase();
                    var inField = tag === 'input' || tag === 'textarea' || tag === 'select' || (t && t.isContentEditable);
                    var cobraKey = e.key === 'Enter' || e.key === 'F12';
                    if (!cobraKey) return;
                    if (e.key === 'F12') e.preventDefault();
                    if (modalOpen) {
                        if (this.requestFinalizar) return;
                        var inEfectivo = t && t.closest && t.closest('.pago-efectivo');
                        if (inField && !inEfectivo && e.key === 'Enter') return;
                        e.preventDefault();
                        this.intentarCobrarImprimir();
                        return;
                    }
                    var inScan = t && t.closest && t.closest('.venta-scan');
                    if (inScan) {
                        var q = ((t && t.value) || '').trim();
                        if (q && e.key === 'Enter') return;
                        if (!this.puedeCobrar) return;
                        e.preventDefault();
                        this.showFinalizar();
                        return;
                    }
                    if (inField && e.key === 'Enter') return;
                    if (t && t.closest && t.closest('.cliente-picker, .modal')) return;
                    if (!this.puedeCobrar) return;
                    e.preventDefault();
                    this.showFinalizar();
                },
                intentarCobrarImprimir: function() {
                    this.calcularVuelto();
                    if (!this.puedeImprimirCobro) return;
                    this.finalizar(true);
                },
                elegirListaContado: function(n) {
                    this.tmpIndexPrecio.iPrecio = 'CO' + n;
                    this.ventaCabecera.condicionventa = 1;
                    this.setPrecio();
                },
                elegirListaCredito: function(index) {
                    this.tmpIndexPrecio.iPrecio = 'CR' + index;
                    this.ventaCabecera.condicionventa = 2;
                    this.setPrecio();
                },
                irAVentaPrincipal: function() {
                    this.cambiarCarro(0);
                    var el = document.getElementById('main') || document.getElementById('app');
                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                },
                nuevoCarro: function() {
                    this.carritos.push(this.getDefaultCarrito());
                    this.indiceCarroActivo = this.carritos.length - 1;
                    this.getFecha();
                    this.getConfigVenta();
                    this.saveDatos();
                },
                cambiarCarro: function(index) {
                    if (index >= 0 && index < this.carritos.length) {
                        this.indiceCarroActivo = index;
                        this.saveDatos();
                    }
                },
                eliminarCarro: function(index) {
                    if (this.carritos.length <= 1) return;
                    var self = this;
                    Swal.fire({
                        title: '¿Sacar este carro?',
                        text: 'Se pierde el ticket de Venta ' + (index + 1) + '.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sacar',
                        cancelButtonText: 'Seguir cobrando',
                        confirmButtonColor: '#6b4f00'
                    }).then(function(result) {
                        if (!result.value) return;
                        self.carritos.splice(index, 1);
                        if (self.indiceCarroActivo >= self.carritos.length) {
                            self.indiceCarroActivo = self.carritos.length - 1;
                        } else if (index < self.indiceCarroActivo) {
                            self.indiceCarroActivo--;
                        }
                        self.saveDatos();
                    });
                },
                getConfigVenta() {
                    this.ensureVentaCabecera();
                    var config = localStorage.getItem('config_venta');
                    if (config != null && config !== '' && config !== 'undefined') {
                        try {
                            config = JSON.parse(config);
                            if (config && typeof config.tipo_comprobante !== 'undefined') {
                                var vc = this.carritos[this.indiceCarroActivo].ventaCabecera;
                                this.$set(vc, 'documento', config.tipo_comprobante);
                                this.$set(vc, 'vender_sin_stock', config.vender_sin_stock);
                                this.$set(vc, 'descontar_stock', config.descontar_stock);
                            }
                        } catch (e) {}
                    }
                },
                abrirItemLibre: function () {
                    this.fastItem = { precio: '', descripcion: '', cantidad: 1 };
                    $('#modalItemLibre').modal('show');
                },
                abrirModalCombos: function () {
                    var self = this;
                    this.comboModal.cargando = true;
                    this.comboModal.items = [];
                    $('#modalCombos').modal('show');
                    axios.get('{{ url('combo/activos') }}', {
                        params: { suc: this.ventaCabecera.idSucursal || null }
                    }).then(function (r) {
                        self.comboModal.cargando = false;
                        self.comboModal.items = Array.isArray(r.data) ? r.data : [];
                    }).catch(function () {
                        self.comboModal.cargando = false;
                        Swal.fire('No se cargaron', 'No se pudieron cargar los combos. Intentá de nuevo.', 'error');
                    });
                },
                agregarComboAlCarrito: function (combo) {
                    if (!combo) return;
                    if (!combo.stock_ok && Number(this.ventaCabecera.vender_sin_stock) == 0) {
                        Swal.fire('Stock insuficiente', 'Uno o más artículos del combo no tienen stock.', 'warning');
                        return;
                    }
                    this.ensureVentaCabecera();
                    var esVentaCredito = String(this.ventaCabecera.condicionventa) === '2';
                    var pContado = Number(combo.precio) || 0;
                    var pCredito = Number(combo.precio_credito) || 0;
                    var precioAplicar = (esVentaCredito && pCredito > 0) ? pCredito : pContado;

                    var art = {
                        codigo: 'COMBO-' + combo.id,
                        combo_id: combo.id,
                        idstock: 0,
                        descripcion: combo.nombre,
                        descripcion_libre: combo.nombre,
                        es_combo: true,
                        componentes: (combo.items || []).map(function (it) {
                            return {
                                articulos_cod: it.articulos_cod,
                                cantidad: it.cantidad,
                                id_stock: it.id_stock,
                                nombre: it.nombre
                            };
                        }),
                        linea_uid: 'combo-' + combo.id + '-' + Date.now(),
                        cantidad: 1,
                        stock: 999999,
                        precio: precioAplicar,
                        precio_contado: pContado,
                        precio_credito: pCredito,
                        p1: pContado,
                        p2: 0,
                        p3: 0,
                        p4: 0,
                        p5: 0,
                        m1: 0,
                        m2: 0,
                        m3: 0,
                        m4: 0,
                        m5: 0,
                        costo: 0,
                        iPrecio: (esVentaCredito && pCredito > 0) ? 'CR0' : 'CO1'
                    };
                    var lista = this.carro.slice();
                    lista.push(art);
                    this.carro = lista;
                    this.saveDatos();
                    $('#modalCombos').modal('hide');
                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    Toast.fire({ icon: 'success', title: combo.nombre + ' agregado' });
                    var self = this;
                    $('#modalCombos').one('hidden.bs.modal', function () {
                        if (self.$refs.buscador && self.$refs.buscador.focusSearchInput) {
                            self.$refs.buscador.focusSearchInput();
                        }
                    });
                },
                agregarDesdeCatalogo: function (arts) {
                    var agregados = 0;
                    var self = this;
                    (arts || []).forEach(function (art) {
                        if (!art) return;
                        if (!art.ARTICULOS_cod && art.articulos_cod) {
                            art.ARTICULOS_cod = art.articulos_cod;
                        }
                        if (typeof art.id_stock === 'undefined' && typeof art.idstock !== 'undefined') {
                            art.id_stock = art.idstock;
                        }
                        var antes = self.carro.length;
                        var cantAntes = 0;
                        var idx = self.carro.findIndex(function (x) {
                            return String(x.codigo) === String(art.ARTICULOS_cod) && x.idstock == art.id_stock;
                        });
                        if (idx !== -1) {
                            cantAntes = self.carro[idx].cantidad;
                        }
                        self.addCarrito(art);
                        idx = self.carro.findIndex(function (x) {
                            return String(x.codigo) === String(art.ARTICULOS_cod) && x.idstock == art.id_stock;
                        });
                        if (idx !== -1 && (self.carro.length > antes || self.carro[idx].cantidad > cantAntes)) {
                            agregados++;
                        }
                    });
                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    Toast.fire({
                        icon: agregados ? 'success' : 'warning',
                        title: agregados
                            ? (agregados + ' artículo(s) agregado(s) al carrito')
                            : 'No se pudo agregar (revisá stock)'
                    });
                },
                focusFastPrecio: function () {
                    var el = document.getElementById('fastItemPrecio');
                    if (el) el.focus();
                },
                addFastItem: function () {
                    var desc = (this.fastItem.descripcion || '').trim();
                    var precio = parseFloat(this.fastItem.precio);
                    var cantidad = parseFloat(this.fastItem.cantidad) || 1;

                    if (!desc) {
                        Swal.fire('Falta descripción', 'Ingresá la descripción del ítem.', 'warning');
                        return;
                    }
                    if (!(precio > 0)) {
                        Swal.fire('Falta precio', 'Ingresá un precio mayor a cero.', 'warning');
                        return;
                    }
                    if (!(cantidad > 0)) {
                        cantidad = 1;
                    }

                    this.ensureVentaCabecera();

                    var art = {
                        codigo: this.articuloLibreId || 'VARIOS',
                        idstock: 0,
                        descripcion: desc,
                        descripcion_libre: desc,
                        es_libre: true,
                        linea_uid: 'libre-' + Date.now() + '-' + Math.floor(Math.random() * 100000),
                        cantidad: cantidad,
                        stock: 999999,
                        precio: precio,
                        p1: parseInt(precio, 10),
                        p2: 0,
                        p3: 0,
                        p4: 0,
                        p5: 0,
                        m1: 0,
                        m2: 0,
                        m3: 0,
                        m4: 0,
                        m5: 0,
                        costo: 0,
                        iPrecio: 'CO1'
                    };

                    var lista = this.carro.slice();
                    lista.push(art);
                    this.carro = lista;
                    this.saveDatos();
                    this.fastItem = { precio: '', descripcion: '', cantidad: 1 };
                    $('#modalItemLibre').modal('hide');

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Ítem libre agregado',
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                calcularVuelto: function() {
                    if (this.efectivoRecibido > 0 && this.ventaCabecera.total > 0) {
                        this.vuelto = this.efectivoRecibido - this.ventaCabecera.total;
                    } else {
                        this.vuelto = 0;
                    }
                },
                sugerirEfectivoRecibido: function() {
                    var billetes = [5000, 10000, 20000, 50000, 100000];
                    var total = this.ventaCabecera.total;
                    var opciones = [];
                    var minCombinacion = this.minimoConBilletes(total, billetes);
                    if (minCombinacion.monto > 0) {
                        opciones.push({ monto: minCombinacion.monto, label: this.format(minCombinacion.monto) });
                    }
                    if (50000 > total && opciones.every(function(o) { return o.monto !== 50000; })) {
                        opciones.push({ monto: 50000, label: this.format(50000) });
                    }
                    if (100000 > total && opciones.every(function(o) { return o.monto !== 100000; })) {
                        opciones.push({ monto: 100000, label: this.format(100000) });
                    }
                    this.opcionesEfectivo = opciones.slice(0, 3);
                },
                minimoConBilletes: function(total, billetes) {
                    var maxBill = Math.max.apply(null, billetes);
                    var maxAmount = total + maxBill;
                    var canMake = { 0: true };
                    for (var a = 1; a <= maxAmount; a++) {
                        canMake[a] = false;
                        for (var i = 0; i < billetes.length; i++) {
                            if (a >= billetes[i] && canMake[a - billetes[i]]) {
                                canMake[a] = true;
                                break;
                            }
                        }
                    }
                    var monto = 0;
                    for (var j = total + 1; j <= maxAmount; j++) {
                        if (canMake[j]) {
                            monto = j;
                            break;
                        }
                    }
                    var label = monto ? this.formarLabelBilletes(monto, billetes) : '';
                    return { monto: monto, label: label };
                },
                formarLabelBilletes: function(monto, billetes) {
                    var ordenados = billetes.slice().sort(function(a, b) { return b - a; });
                    var usados = [];
                    var restante = monto;
                    for (var i = 0; i < ordenados.length && restante > 0; i++) {
                        while (restante >= ordenados[i]) {
                            usados.push(ordenados[i]);
                            restante -= ordenados[i];
                        }
                    }
                    return usados.map(function(u) { return u.toLocaleString('es-PY'); }).join(' + ');
                },
                aplicarOpcionEfectivo: function(monto) {
                    this.efectivoRecibido = monto;
                    this.calcularVuelto();
                }
            },
            computed: {
                carro: {
                    get: function() {
                        return this.carritos.length && this.carritos[this.indiceCarroActivo] ? this.carritos[this.indiceCarroActivo].carro : [];
                    },
                    set: function(v) {
                        if (this.carritos.length && this.carritos[this.indiceCarroActivo]) {
                            this.$set(this.carritos[this.indiceCarroActivo], 'carro', v);
                        }
                    }
                },
                ventaCabecera: {
                    get: function() {
                        return this.carritos.length && this.carritos[this.indiceCarroActivo] ? this.carritos[this.indiceCarroActivo].ventaCabecera : {};
                    }
                },
                efectivoRecibido: {
                    get: function() {
                        return this.carritos.length && this.carritos[this.indiceCarroActivo] ? this.carritos[this.indiceCarroActivo].efectivoRecibido : 0;
                    },
                    set: function(v) {
                        if (this.carritos.length && this.carritos[this.indiceCarroActivo]) {
                            this.$set(this.carritos[this.indiceCarroActivo], 'efectivoRecibido', v);
                        }
                    }
                },
                vuelto: {
                    get: function() {
                        return this.carritos.length && this.carritos[this.indiceCarroActivo] ? this.carritos[this.indiceCarroActivo].vuelto : 0;
                    },
                    set: function(v) {
                        if (this.carritos.length && this.carritos[this.indiceCarroActivo]) {
                            this.$set(this.carritos[this.indiceCarroActivo], 'vuelto', v);
                        }
                    }
                },
                cuotas: {
                    get: function() {
                        return this.carritos.length && this.carritos[this.indiceCarroActivo] ? this.carritos[this.indiceCarroActivo].cuotas : [];
                    },
                    set: function(v) {
                        if (this.carritos.length && this.carritos[this.indiceCarroActivo]) {
                            this.$set(this.carritos[this.indiceCarroActivo], 'cuotas', v);
                        }
                    }
                },
                carroOrdenado: function() {
                    var c = this.carro;
                    return c.slice().sort((a, b) => {
                        return c.indexOf(b) - c.indexOf(a);
                    });
                },
                totalVenta: function() {
                    var vc = this.ventaCabecera;
                    var c = this.carro;
                    if (!vc || typeof vc.total === 'undefined') return '0';
                    vc.total = 0;
                    for (var i = 0; i < c.length; i++) {
                        vc.total += (c[i].precio * c[i].cantidad);
                    }
                    if (vc.descuento > 0 && vc.total > 0) {
                        vc.total -= vc.descuento;
                    }
                    return this.format(vc.total);
                },
                esFactura: function() {
                    return this.ventaCabecera && this.ventaCabecera.documento === 'Factura';
                },
                esComprobante: function() {
                    return this.ventaCabecera && this.ventaCabecera.documento === 'Comprobante';
                },
                esEfectivo: function() {
                    return String(this.ventaCabecera && this.ventaCabecera.formacobro) === '1';
                },
                esCredito: function() {
                    return String(this.ventaCabecera && this.ventaCabecera.condicionventa) === '2';
                },
                esClienteOcasional: function() {
                    var id = Number(this.ventaCabecera && this.ventaCabecera.clienteId);
                    var nom = ((this.ventaCabecera && this.ventaCabecera.clienteNombre) || '').toLowerCase();
                    return id === 1 || nom.indexOf('ocasional') !== -1;
                },
                clienteTieneRuc: function() {
                    if (this.esClienteOcasional) return false;
                    var r = String((this.ventaCabecera && this.ventaCabecera.clienteRuc) || '').replace(/\s/g, '');
                    return r !== '' && r !== '0' && r !== '1';
                },
                clienteDocVisible: function() {
                    if (this.esClienteOcasional) return '';
                    var r = String((this.ventaCabecera && this.ventaCabecera.clienteRuc) || '').trim();
                    if (r && r !== '0' && r !== '1') return r;
                    return '';
                },
                motivoBloqueoFactura: function() {
                    if (!this.esFactura) return '';
                    if (!this.sifenActivo) {
                        return this.esAdministrador
                            ? 'Factura electrónica apagada.'
                            : 'Avisá al dueño: falta SIFEN.';
                    }
                    if (!this.sifenListo) {
                        return this.esAdministrador
                            ? ('Falta ' + (this.sifenFalta || 'configuración') + ' para facturar.')
                            : 'Avisá al dueño: falta SIFEN.';
                    }
                    if (this.esClienteOcasional || !this.clienteTieneRuc) {
                        return 'Elegí un cliente con RUC para factura.';
                    }
                    return '';
                },
                motivoNoCobrar: function() {
                    if (!this.cajaConsultada) return 'Revisando caja…';
                    if (this.caja !== 'ABIERTA') return 'Abrí caja para cobrar.';
                    if (!(this.ventaCabecera && this.ventaCabecera.total > 0)) {
                        return 'Cargá un ítem para cobrar.';
                    }
                    if (this.motivoBloqueoFactura) return this.motivoBloqueoFactura;
                    return '';
                },
                puedeCobrar: function() {
                    return this.cajaConsultada
                        && this.caja === 'ABIERTA'
                        && this.ventaCabecera
                        && this.ventaCabecera.total > 0
                        && !this.motivoBloqueoFactura;
                },
                puedeEnterCobrar: function() {
                    if (!this.esEfectivo) return true;
                    var rec = Number(this.efectivoRecibido) || 0;
                    var tot = Number(this.ventaCabecera && this.ventaCabecera.total) || 0;
                    return tot > 0 && rec >= tot;
                },
                puedeImprimirCobro: function() {
                    if (this.motivoBloqueoFactura) return false;
                    if (this.esCredito && !(this.cuotas && this.cuotas.length)) return false;
                    if (!this.esCredito && this.esEfectivo && !this.puedeEnterCobrar) return false;
                    return true;
                },
                hintCobro: function() {
                    if (this.motivoBloqueoFactura) return this.motivoBloqueoFactura;
                    if (this.esCredito && !(this.cuotas && this.cuotas.length)) return 'Armá las cuotas para cobrar';
                    if (!this.esCredito && this.esEfectivo && !this.puedeEnterCobrar) return 'Recibí el total para Enter o F12';
                    return 'Enter o F12 cobra e imprime';
                },
                resumenCuotas: function() {
                    var c = this.cuotas || [];
                    if (!c.length) return '';
                    var first = c[0] || {};
                    var n = c.length;
                    var line = n + (n === 1 ? ' cuota' : ' cuotas') + ' · primera Gs. ' + this.format(first.monto);
                    if (first.vencimiento) line += ' · vence ' + first.vencimiento;
                    return line;
                },
                listasContado: function() {
                    var out = [];
                    var p = this.preciosContado || {};
                    for (var n = 1; n <= 5; n++) {
                        if (Number(p['p' + n]) > 0) out.push(n);
                    }
                    if (out.length > 3) out = out.slice(0, 3);
                    return out.length ? out : [1];
                },
                preciosCreditoConMonto: function() {
                    return (this.preciosCredito || []).map(function(p, index) {
                        return { p: p.p, c: p.c, index: index };
                    }).filter(function(p) {
                        return Number(p.p) > 0;
                    });
                }
            },
            mounted() {
                // this.getFecha();
                this.recuperarDatos();
                this.getSucursal();
                this.ensureVentaCabecera();
                this.getApertura();
                this.getFecha();
                this.getConfigVenta();
                this.cargarOfertasActivas();
                this.tuneCatalogoVenta();
                this.tuneScanVenta();
                this.$el.querySelectorAll('.venta-mas').forEach(function (d) {
                    d.removeAttribute('open');
                });
                this.syncClienteSeccion();
                document.addEventListener('click', this.onClickOutsideClientePicker);
                document.addEventListener('keydown', this.onCajaKey);
                $(document).on('shown.lte.pushmenu', this.onPushmenuCaja);
                $(document).on('click', '[data-widget="pushmenu"]', this.onPushmenuCaja);

                // Cargar presupuesto transferido si existe
                var presRaw = sessionStorage.getItem('presupuesto_a_venta');
                if (presRaw) {
                    try {
                        var presData = JSON.parse(presRaw);
                        sessionStorage.removeItem('presupuesto_a_venta');
                        if (presData && presData.items && presData.items.length) {
                            var self = this;
                            setTimeout(function () {
                                if (presData.presupuesto && presData.presupuesto.cliente) {
                                    self.seleccionarCliente(presData.presupuesto.cliente);
                                }
                                var actCarro = self.carritos[self.indiceCarroActivo];
                                if (actCarro) {
                                    presData.items.forEach(function (it) {
                                        actCarro.carro.push({
                                            codigo: it.codigo,
                                            c_barra: it.c_barra || '',
                                            descripcion: it.descripcion,
                                            cantidad: it.cantidad,
                                            precio: it.precio,
                                            costo: it.costo || 0,
                                            iva: it.iva,
                                            idstock: 0,
                                            stock: 999
                                        });
                                    });
                                    if (presData.presupuesto && presData.presupuesto.descuento) {
                                        actCarro.ventaCabecera.descuento = presData.presupuesto.descuento;
                                    }
                                    if (presData.presupuesto && presData.presupuesto.condicion) {
                                        actCarro.ventaCabecera.condicionventa = presData.presupuesto.condicion;
                                    }
                                    self.saveDatos();
                                }
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Presupuesto cargado al ticket',
                                    text: 'Se importaron los artículos del Presupuesto #' + (presData.presupuesto ? presData.presupuesto.pre_numero : '') + '.',
                                    timer: 3000,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end'
                                });
                            }, 400);
                        }
                    } catch (e) {
                        console.error('Error importando presupuesto a venta:', e);
                    }
                }
            },
            beforeDestroy() {
                document.removeEventListener('click', this.onClickOutsideClientePicker);
                document.removeEventListener('keydown', this.onCajaKey);
                $(document).off('shown.lte.pushmenu', this.onPushmenuCaja);
                $(document).off('click', '[data-widget="pushmenu"]', this.onPushmenuCaja);
            }
        });
        window.ventaApp = app;
        activarMenu('m_venta', '');
    </script>
@endsection
