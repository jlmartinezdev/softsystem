@extends('layouts.app')
@section('title', 'Gestionar Compra')
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
    }

    [v-cloak] {
        display: none !important;
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
    .badge-caja {
        font-size: 0.82rem;
        font-weight: 700;
        padding: 0.4rem 0.75rem;
        border-radius: 999px;
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
    }
    body.dark-mode .badge-caja {
        background: #064e3b;
        color: #a7f3d0;
        border-color: #047857;
    }
    .badge-caja.is-cerrada {
        background: #fef2f2;
        color: #991b1b;
        border-color: #fecaca;
    }
    body.dark-mode .badge-caja.is-cerrada {
        background: #450a0a;
        color: #fca5a5;
        border-color: #7f1d1d;
    }
    .status-dot-pulse {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulseDot 1.8s infinite;
    }
    @keyframes pulseDot {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    /* Scanner & Atajos */
    .venta-scan {
        margin-bottom: 0.75rem;
    }
    .venta-scan .buscador-catalogo .buscador-navbar {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        align-items: center !important;
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

    /* Input del buscador flexible: Misma línea que los botones */
    .venta-scan .buscador-catalogo .navbar-nav.w-100 {
        flex: 1 1 auto !important;
        min-width: 180px;
        width: auto !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .venta-scan .buscador-catalogo .navbar-nav.w-100 > li {
        width: 100% !important;
    }
    .venta-scan .autocomplete {
        position: relative;
        width: 100% !important;
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

    /* Contenedor y alineación de los atajos del buscador en la misma línea */
    .venta-scan .buscador-catalogo .navbar-nav.flex-row {
        flex: 0 0 auto !important;
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 0.4rem;
        margin-left: auto !important;
        padding: 0 !important;
        margin-bottom: 0 !important;
        list-style: none !important;
    }
    .venta-scan .buscador-catalogo .navbar-nav.flex-row > li {
        display: flex !important;
        align-items: center !important;
        margin: 0 !important;
    }

    /* Atajos en Buscador (Catálogo y Nuevo Artículo) */
    .venta-atajo {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        padding: 0.45rem 0.8rem !important;
        height: 38px;
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

    /* Ticket POS Container */
    .ticket {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        padding: 0.85rem 1rem;
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

    /* Ticket Line Item Grid */
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

    /* Quantity Wrapper */
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

    /* Ticket Cost / Total Button */
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

    /* Empty Ticket State */
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

    /* Ticket Footer with Total */
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

    /* Right Panel (Liquidación / Registro Compra) */
    .card.venta-cobro {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
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

    /* Big Action Button (btn-cobrar) */
    .btn-cobrar {
        width: 100%;
        padding: 0.95rem 1.25rem;
        background: linear-gradient(135deg, var(--dash-primary), var(--dash-primary-dark));
        color: #ffffff !important;
        font-weight: 700;
        font-size: 1.25rem;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        box-shadow: 0 4px 12px rgba(10, 77, 54, 0.2);
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

    /* Modales Modernos */
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

    @media (max-width: 768px) {
        /* En pantallas pequeñas: buscador arriba y botones en la fila de abajo */
        .venta-scan .buscador-catalogo .buscador-navbar {
            flex-direction: column !important;
            flex-wrap: wrap !important;
            align-items: stretch !important;
            gap: 0.45rem;
            padding: 0.45rem !important;
        }
        .venta-scan .buscador-catalogo .navbar-nav.w-100 {
            width: 100% !important;
            flex: 1 1 100% !important;
        }
        .venta-scan .buscador-catalogo .navbar-nav.flex-row {
            width: 100% !important;
            display: grid !important;
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 0.4rem !important;
            margin-left: 0 !important;
        }
        .venta-scan .buscador-catalogo .navbar-nav.flex-row > li {
            width: 100% !important;
        }
        .venta-scan .venta-atajo {
            width: 100% !important;
            justify-content: center !important;
            text-align: center;
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
    <div class="container-fluid">
        <div class="row">
            <!-- PANEL IZQUIERDO: Scanner, Catálogo y Ticket POS (Col 8) -->
            <div class="col-md-8">
                <!-- Topbar con estado de caja y tienda -->
                <div class="venta-topbar">
                    <span class="venta-local">
                        <i class="fa fa-truck mr-1 text-success"></i> Gestión de Compras
                    </span>
                    <span class="badge badge-pill badge-caja" :class="{ 'is-cerrada': caja !== 'ABIERTA' }">
                        <span class="status-dot-pulse" v-if="caja === 'ABIERTA'"></span>
                        Caja @{{ caja }}
                        <span v-if="nrooperacion && nrooperacion !== '...'"> · Op #@{{ nrooperacion }}</span>
                    </span>
                </div>

                <!-- Visor y Scanner de Productos -->
                <div class="venta-visor">
                    <div class="venta-scan">
                        <buscador-catalogo
                            ref="buscador"
                            url="{{ env('APP_APIDB') }}"
                            :idsucursal="compraCabecera.idSucursal"
                            url-buscar="{{ url('articulo/buscar') }}"
                            url-foto-base="{{ asset('storage/articulos') }}"
                            img-fallback="{{ asset('img/sinimagen.png') }}"
                            route-articulo="{{ route('articulo.cm') }}"
                            validar-lote="false"
                            precio-field="producto_costo_compra"
                            titulo="Catálogo para Compra"
                            modal-id="modalCatalogoCompra"
                            scan-placeholder="Pasá el código de barras o escribí el nombre del artículo..."
                            @articulo="addCarrito"
                            @seleccion="agregarDesdeCatalogo"
                        >
                            <template slot="actions-after">
                                <li class="nav-item">
                                    <a href="#" class="nav-link venta-atajo" @click.prevent="abrirCatalogo" title="Explorar catálogo visual con fotos">
                                        <i class="fa fa-th" aria-hidden="true"></i> Catálogo
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('articulo.cm') }}" class="nav-link venta-atajo venta-atajo-sec" title="Crear nuevo artículo">
                                        <i class="fa fa-plus" aria-hidden="true"></i> Nuevo Artículo
                                    </a>
                                </li>
                            </template>
                        </buscador-catalogo>
                    </div>

                    <!-- Ticket / Detalle de la Compra Estilo Venta -->
                    <div class="ticket">
                        <div class="ticket-toolbar" v-if="carro.length">
                            <span class="small font-weight-bold text-muted">
                                <i class="fa fa-shopping-basket mr-1 text-success"></i> @{{ carro.length }} ítem(s) en la compra
                            </span>
                            <button type="button" class="ticket-vaciar" @click="vaciarCarro">
                                <i class="fa fa-trash-alt mr-1"></i> Vaciar compra
                            </button>
                        </div>

                        <div class="ticket-head" v-if="carro.length">
                            <span>Ítem / Producto</span>
                            <span class="text-center">Cant.</span>
                            <span class="text-right">Costo / Total (Gs.)</span>
                            <span class="sr-only">Quitar</span>
                        </div>

                        <ul class="ticket-lista" v-if="carro.length">
                            <li class="ticket-linea" v-for="(item, index) in carro" :key="item.codigo + '-' + item.idstock + '-' + index">
                                <div class="ticket-que">
                                    <span class="ticket-nombre">@{{ item.descripcion }}</span>
                                    <span class="ticket-meta">
                                        <span class="badge badge-light border text-muted">@{{ item.codigo }}</span>
                                        <span v-if="item.stock" class="badge badge-light border text-muted">Stock actual: @{{ item.stock }}</span>
                                    </span>
                                </div>
                                <div class="venta-qty-wrapper">
                                    <button type="button" class="btn-qty" @click.stop="decrementarCantidad(item)" aria-label="Disminuir">-</button>
                                    <input type="number" class="venta-qty form-control form-control-sm font-cairo font-weight-bold"
                                        min="1" v-model.number="item.cantidad"
                                        @change="saveDatos" :aria-label="'Cantidad de ' + item.descripcion">
                                    <button type="button" class="btn-qty" @click.stop="incrementarCantidad(item)" aria-label="Aumentar">+</button>
                                </div>
                                <button type="button" class="ticket-gs" @click="showSetPrecio(index, item)"
                                    :title="'Ajustar costo y precios de venta de ' + item.descripcion + '. Unitario: Gs. ' + format(item.costo) + '. Total: Gs. ' + format(item.costo * item.cantidad)">
                                    <strong class="font-cairo">@{{ format(item.costo * item.cantidad) }}</strong>
                                    <span>Gs. @{{ format(item.costo) }} c/u</span>
                                </button>
                                <button type="button" class="ticket-quitar" title="Quitar de la compra" :aria-label="'Quitar ' + item.descripcion" @click="delArticulo(item)">
                                    <span class="fa fa-times" aria-hidden="true"></span>
                                </button>
                            </li>
                        </ul>

                        <div class="venta-vacio-box" v-else>
                            <div class="venta-vacio-icon">
                                <i class="fa fa-barcode"></i>
                            </div>
                            <div class="venta-vacio-title font-weight-bold font-cairo">Compra sin ítems</div>
                            <p class="venta-vacio-text text-muted mb-0">
                                Pasá el código de barras o escribí el nombre del producto para comenzar a cargar la factura.
                            </p>
                        </div>

                        <div class="ticket-foot">
                            <span class="label font-weight-bold text-muted text-uppercase">Total de Compra</span>
                            <div class="venta-total">
                                <h2 class="font-cairo">Gs. @{{ totalCompra }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PANEL DERECHO: Proveedor y Liquidación Factura Estilo Venta (Col 4) -->
            <div class="col-md-4">
                <div class="card venta-cobro shadow-sm">
                    <div class="card-body">
                        <!-- Proveedor Rail -->
                        <fieldset class="form-group mb-3">
                            <label class="doc-rail-label"><i class="fa fa-building mr-1 text-muted"></i> Proveedor *</label>
                            <div v-if="compraCabecera.proveedor" class="d-flex align-items-center justify-content-between p-2 border rounded bg-light-panel">
                                <div>
                                    <strong class="font-cairo d-block">@{{ compraCabecera.proveedor }}</strong>
                                    <small class="text-muted">ID Proveedor: #@{{ compraCabecera.idproveedor }}</small>
                                </div>
                                <button type="button" class="btn btn-outline-secondary btn-sm" @click="showBuscarProveedor">
                                    Cambiar
                                </button>
                            </div>
                            <div v-else>
                                <button type="button" class="btn btn-outline-primary btn-block py-2 font-weight-bold" @click="showBuscarProveedor">
                                    <i class="fa fa-search mr-1"></i> Seleccionar Proveedor
                                </button>
                            </div>
                        </fieldset>

                        <!-- Nro. Factura Proveedor -->
                        <fieldset class="form-group mb-3">
                            <label class="doc-rail-label"><i class="fa fa-file-invoice mr-1 text-muted"></i> Nro. Factura Proveedor</label>
                            <div class="input-group input-group-sm">
                                <input type="text" placeholder="001" v-model="compraCabecera.factura_n1" v-on:blur="rellenarCero('factura_n1',3)" class="form-control text-center font-weight-bold" maxlength="3" title="Establecimiento">
                                <div class="input-group-prepend input-group-append"><span class="input-group-text bg-transparent border-0 font-weight-bold">-</span></div>
                                <input type="text" placeholder="001" v-model="compraCabecera.factura_n2" v-on:blur="rellenarCero('factura_n2',3)" class="form-control text-center font-weight-bold" maxlength="3" title="Punto de expedición">
                                <div class="input-group-prepend input-group-append"><span class="input-group-text bg-transparent border-0 font-weight-bold">-</span></div>
                                <input type="text" placeholder="0000001" v-model="compraCabecera.factura_n3" v-on:blur="rellenarCero('factura_n3',7)" class="form-control text-center font-weight-bold" maxlength="7" title="Número de factura">
                            </div>
                        </fieldset>

                        <!-- Fecha de Factura -->
                        <fieldset class="form-group mb-3">
                            <label class="doc-rail-label"><i class="fa fa-calendar-alt mr-1 text-muted"></i> Fecha Factura</label>
                            <input type="date" v-model="compraCabecera.fecha" class="form-control form-control-sm">
                        </fieldset>

                        <!-- Condición de Compra -->
                        <fieldset class="form-group mb-3">
                            <label class="doc-rail-label"><i class="fa fa-handshake mr-1 text-muted"></i> Condición</label>
                            <div class="venta-switch" role="group">
                                <button type="button" :class="{ on: compraCabecera.condicioncompra == 1 }" @click="compraCabecera.condicioncompra = 1; saveDatos();">
                                    <i class="fa fa-money-bill-wave mr-1"></i> Contado
                                </button>
                                <button type="button" :class="{ on: compraCabecera.condicioncompra == 2 }" @click="compraCabecera.condicioncompra = 2; saveDatos();">
                                    <i class="fa fa-calendar-check mr-1"></i> Crédito
                                </button>
                            </div>
                        </fieldset>

                        <!-- Forma de Pago -->
                        <fieldset class="form-group mb-3">
                            <label class="doc-rail-label"><i class="fa fa-wallet mr-1 text-muted"></i> Forma de Pago</label>
                            <div class="venta-switch" role="group">
                                <button type="button" :class="{ on: compraCabecera.formacobro == 1 }" @click="compraCabecera.formacobro = 1; saveDatos();">
                                    Efectivo
                                </button>
                                <button type="button" :class="{ on: compraCabecera.formacobro == 2 }" @click="compraCabecera.formacobro = 2; saveDatos();">
                                    Tarjeta
                                </button>
                                <button type="button" :class="{ on: compraCabecera.formacobro == 3 }" @click="compraCabecera.formacobro = 3; saveDatos();">
                                    Transferencia
                                </button>
                            </div>
                        </fieldset>

                        <!-- Descuento Global -->
                        <fieldset class="form-group mb-4">
                            <label class="doc-rail-label"><i class="fa fa-tag mr-1 text-muted"></i> Descuento Global (Gs.)</label>
                            <input type="number" v-model.number="compraCabecera.descuento" class="form-control form-control-sm" placeholder="0" min="0" @change="saveDatos">
                        </fieldset>

                        <!-- Botón Registrar Compra (btn-cobrar) -->
                        <button type="button" class="btn-cobrar" @click="showFinalizar" :disabled="!carro.length || caja !== 'ABIERTA'">
                            <span class="mr-2">REGISTRAR COMPRA</span>
                            <i class="fa fa-arrow-right"></i>
                        </button>

                        <div v-if="caja !== 'ABIERTA'" class="cobrar-motivo text-center mt-2">
                            <i class="fa fa-exclamation-triangle mr-1"></i> Abrí la caja para registrar compras.
                        </div>
                        <div v-else-if="!carro.length" class="cobrar-motivo text-center mt-2">
                            <i class="fa fa-info-circle mr-1"></i> Cargá un ítem para registrar la compra.
                        </div>
                        <div v-else-if="!compraCabecera.proveedor" class="cobrar-motivo text-center mt-2">
                            <i class="fa fa-building mr-1"></i> Seleccioná un proveedor para continuar.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modales Auxiliares -->
    <busquedaproveedor @set_proveedor="setProveedor"></busquedaproveedor>
    @include('compra.finalizar')
    @include('compra.precio')
    @include('articulo.precio')
</div>

<!-- Skeleton Loading Overlay -->
<div class="app-loading-skeleton">
    <div class="v-cloak-spinner"></div>
    <div class="font-cairo font-weight-bold" style="font-size: 1rem;">Cargando Módulo de Compras...</div>
</div>
@endsection

@section('script')
<script src="{{ asset(mix('js/component/proveedor.js')) }}"></script>
<script type="text/javascript">
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000
    });

    const defaulPrecio = [
        {p: 50, m: 5, c: 2}, {p: 0, m: 0, c: 0}, {p: 0, m: 0, c: 0},
        {p: 0, m: 0, c: 0}, {p: 0, m: 0, c: 0}, {p: 0, m: 0, c: 0},
        {p: 0, m: 0, c: 0}, {p: 0, m: 0, c: 0}, {p: 0, m: 0, c: 0},
        {p: 0, m: 0, c: 0}, {p: 0, m: 0, c: 0}, {p: 0, m: 0, c: 0},
        {p: 0, m: 0, c: 0}, {p: 0, m: 0, c: 0}, {p: 0, m: 0, c: 0},
        {p: 0, m: 0, c: 0}, {p: 0, m: 0, c: 0}, {p: 0, m: 0, c: 0}
    ];

    var app = new Vue({
        el: '#app',
        data: {
            txtbuscar: '',
            inNumberClass: {
                input: 'form-control form-control-sm'
            },
            requestSend: false,
            requestLote: false,
            carro: [],
            articulos: [],
            preciosCreditos: [],
            precioCredito: [],
            chcuota: false,
            chprecio: false,
            precios: defaulPrecio,
            articulo: {},
            pos_edit: 0,
            stocks: [],
            compraCabecera: {
                fecha: '2021-01-01',
                idproveedor: 1,
                proveedor: '',
                pro: 'aa',
                idSucursal: 1,
                factura_n1: '',
                factura_n2: '',
                factura_n3: '',
                total: 0,
                descuento: 0,
                nro_operacion: 0,
                condicioncompra: 1,
                formacobro: 1
            },
            caja: '...',
            nrooperacion: '...',
            articulo_selecionado: {}
        },
        watch: {
            chprecio: function(newVal, oldVal) {
                for (var i = 0; i < this.precios.length; i++) {
                    this.setPrecio(i);
                }
            },
            chcuota: function(newVal, oldVal) {
                for (var i = 0; i < this.precios.length; i++) {
                    this.setCuota(i);
                }
            }
        },
        methods: {
            abrirCatalogo: function() {
                if (this.$refs.buscador && typeof this.$refs.buscador.abrirCatalogo === 'function') {
                    this.$refs.buscador.abrirCatalogo();
                }
            },
            setMargen: function(index) {
                if (typeof this.articulo.costo === 'string') {
                    this.articulo.costo = parseInt(this.articulo.costo);
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
                if (typeof this.articulo.costo === 'string') {
                    this.articulo.costo = parseInt(this.articulo.costo);
                }
                if (this.articulo.costo < 1) {
                    this.precios[index].p = 0;
                    return;
                }
                if (parseInt(this.precios[index].m) < 1 || this.precios[index].m.length == 0) {
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
            mostrarPrecios: function() {
                if (this.articulo.costo > 0) {
                    $('#preciocompra').modal('hide');
                    $('#precioArticulo').modal('show');
                } else {
                    Swal.fire('Atención', 'Agregá el precio de costo de compra.', 'info');
                }
            },
            cerrarPrecios: function() {
                $('#preciocompra').modal('show');
                $('#precioArticulo').modal('hide');
            },
            format: function(numero) {
                return new Intl.NumberFormat("de-DE").format(Number(numero) || 0);
            },
            showBuscarProveedor: function() {
                $('#busquedaProveedor').modal('show');
            },
            setProveedor: function(p) {
                this.compraCabecera.idproveedor = p.PROVEEDOR_cod;
                this.compraCabecera.proveedor = p.proveedor_nombre;
                $('#busquedaProveedor').modal('hide');
                this.saveDatos();
                Toast.fire({ icon: 'success', title: 'Proveedor seleccionado' });
            },
            agregarDesdeCatalogo: function(arts) {
                var self = this;
                var n = 0;
                (arts || []).forEach(function (a) {
                    self.addCarrito(a);
                    n++;
                });
                if (n) {
                    Toast.fire({
                        icon: 'success',
                        title: n + ' artículo(s) agregado(s)'
                    });
                }
            },
            addCarrito: function(a, idstock) {
                if (idstock !== undefined && idstock !== null) {
                    a.id_stock = idstock;
                }
                if (!a.id_stock && a.idstock) {
                    a.id_stock = a.idstock;
                }
                var cod = a.ARTICULOS_cod || a.codigo;
                var i = this.carro.findIndex(function(x) {
                    return x.codigo == cod && x.idstock == a.id_stock;
                });
                if (i === -1) {
                    var art = {
                        codigo: cod,
                        idstock: a.id_stock,
                        descripcion: a.producto_nombre || a.descripcion,
                        cantidad: 1,
                        stock: a.cantidad || a.stock || 0,
                        costo: Number(a.producto_costo_compra || a.costo) || 0,
                        precio: a.pre_venta1 || a.precio,
                        p1: parseInt(a.pre_venta1, 10) || 0,
                        p2: parseInt(a.pre_venta2, 10) || 0,
                        p3: parseInt(a.pre_venta3, 10) || 0,
                        p4: parseInt(a.pre_venta4, 10) || 0,
                        p5: parseInt(a.pre_venta5, 10) || 0,
                        m1: parseInt(a.pre_margen1, 10) || 0,
                        m2: parseInt(a.pre_margen2, 10) || 0,
                        m3: parseInt(a.pre_margen3, 10) || 0,
                        m4: parseInt(a.pre_margen4, 10) || 0,
                        m5: parseInt(a.pre_margen5, 10) || 0
                    };
                    this.carro.push(art);
                    this.getPreciosCredito(cod);
                } else {
                    this.carro[i].cantidad = parseInt(this.carro[i].cantidad) + 1;
                    this.saveDatos();
                }
                if (this.$refs.buscador && this.$refs.buscador.focusSearchInput) {
                    this.$refs.buscador.focusSearchInput();
                }
            },
            incrementarCantidad: function(item) {
                item.cantidad = (Number(item.cantidad) || 0) + 1;
                this.saveDatos();
            },
            decrementarCantidad: function(item) {
                if (Number(item.cantidad) > 1) {
                    item.cantidad = Number(item.cantidad) - 1;
                    this.saveDatos();
                } else {
                    this.delArticulo(item);
                }
            },
            vaciarCarro: function() {
                var self = this;
                Swal.fire({
                    title: '¿Vaciar compra?',
                    text: 'Se quitarán todos los artículos de la lista.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, vaciar',
                    cancelButtonText: 'Cancelar'
                }).then(function(res) {
                    if (res.value) {
                        self.carro = [];
                        self.preciosCreditos = [];
                        self.saveDatos();
                        Toast.fire({ icon: 'info', title: 'Compra vaciada' });
                    }
                });
            },
            setCantidad: async function(index, cantidad, stock) {
                const swalBootstrap = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-primary mr-2',
                        cancelButton: 'btn btn-secondary'
                    },
                    buttonsStyling: false
                });
                const { value: cant } = await swalBootstrap.fire({
                    title: 'Cantidad a Comprar',
                    text: this.carro[index].descripcion,
                    input: 'number',
                    inputValue: cantidad,
                    inputAttributes: { min: 1, max: 999999, step: 1 },
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar'
                });
                if (cant && Number(cant) > 0) {
                    this.carro[index].cantidad = Number(cant);
                    this.saveDatos();
                }
            },
            showSetPrecio: function(index, a) {
                this.pos_edit = index;
                this.articulo = this.carro[index];
                var indexPrecio = this.preciosCreditos.findIndex(function(x) {
                    return x.id == a.codigo;
                });
                if (indexPrecio !== -1) {
                    this.precios = this.preciosCreditos[indexPrecio].precios;
                } else {
                    this.precios = defaulPrecio;
                }

                $('#preciocompra').modal('show');
                this.get_historial();
            },
            update_precio: function() {
                this.carro[this.pos_edit] = this.articulo;
                var idx = this.preciosCreditos.findIndex(function(x) {
                    return x.id == app.articulo.codigo;
                });
                if (idx !== -1) {
                    this.preciosCreditos[idx].precios = this.precios;
                }
                $('#preciocompra').modal('hide');
                this.saveDatos();
                Toast.fire({ icon: 'success', title: 'Precios actualizados' });
            },
            getPreciosCredito: function(id) {
                var self = this;
                axios.get('articulo/precios/' + id).then(function(response) {
                    if (response.data && response.data.length > 0) {
                        var tmpPrecios = [];
                        for (var i = 0; i < response.data.length; i++) {
                            tmpPrecios.push({
                                p: response.data[i].p,
                                c: response.data[i].c,
                                m: response.data[i].m
                            });
                        }
                        self.preciosCreditos.push({'id': id, 'precios': tmpPrecios});
                        self.saveDatos();
                    } else {
                        self.preciosCreditos.push({'id': id, 'precios': defaulPrecio});
                        self.saveDatos();
                    }
                }).catch(function(error) {
                    self.error = error.message;
                });
            },
            setUtilPrecio: function(tipo, i) {
                if (tipo == 'M') {
                    if (this.articulo.costo > 0 && this.articulo['m' + i] > 0) {
                        this.articulo['p' + i] = ((this.articulo.costo * this.articulo['m' + i]) / 100) + parseFloat(this.articulo.costo);
                    }
                } else {
                    if (this.articulo.costo > 0 && this.articulo['p' + i] > 0) {
                        var res = this.articulo['p' + i] - this.articulo.costo;
                        this.articulo['m' + i] = Math.round(res * 100 / this.articulo.costo);
                    }
                }
            },
            delArticulo: function(a) {
                var validar = this.carro.findIndex(function(x) {
                    return x.codigo == a.codigo;
                });
                if (validar > -1) {
                    this.carro.splice(validar, 1);
                    var precIdx = this.preciosCreditos.findIndex(function(x) {
                        return x.id == a.codigo;
                    });
                    if (precIdx !== -1) {
                        this.preciosCreditos.splice(precIdx, 1);
                    }
                }
                this.saveDatos();
            },
            showFinalizar: function() {
                if (this.caja !== 'ABIERTA') {
                    Swal.fire('Atención', 'La caja no está abierta para registrar compras.', 'warning');
                    return;
                }
                if (this.compraCabecera.total <= 0 || !this.carro.length) {
                    Swal.fire('Compra vacía', 'Cargá al menos un artículo para finalizar.', 'warning');
                    return;
                }
                if (!this.compraCabecera.proveedor) {
                    Swal.fire('Falta proveedor', 'Seleccioná el proveedor emisor de la factura.', 'warning');
                    return;
                }
                $('#finalizarcompra').modal('show');
            },
            finalizar: function() {
                var self = this;
                axios.post('compra', {
                    compraCabecera: this.compraCabecera,
                    detalle: this.carro,
                    precios: this.preciosCreditos
                }).then(function(response) {
                    self.carro = [];
                    self.precios = defaulPrecio;
                    self.preciosCreditos = [];
                    localStorage.removeItem('carro_compra');
                    localStorage.removeItem('compraCabecera');
                    localStorage.removeItem('compraPreciosCredito');
                    $('#finalizarcompra').modal('hide');
                    self.compraCabecera.factura_n1 = "";
                    self.compraCabecera.factura_n2 = "";
                    self.compraCabecera.factura_n3 = "";
                    self.compraCabecera.proveedor = "";
                    self.compraCabecera.idproveedor = 1;
                    Swal.fire('¡Compra Registrada!', 'La compra y el stock fueron ingresados con éxito.', 'success');
                }).catch(function(error) {
                    var msg = (error.response && error.response.data && error.response.data.message)
                        ? error.response.data.message
                        : error.message;
                    Swal.fire('Error', msg, 'error');
                });
            },
            get_historial: function() {
                var self = this;
                axios.get('compra/historial', {
                    params: { ARTICULOS_cod: this.articulo.codigo }
                }).then(function(response) {
                    self.articulos = response.data || [];
                });
            },
            saveDatos: function() {
                localStorage.setItem('carro_compra', JSON.stringify(this.carro));
                localStorage.setItem('compraCabecera', JSON.stringify(this.compraCabecera));
                localStorage.setItem('compraPreciosCredito', JSON.stringify(this.preciosCreditos));
            },
            recuperarDatos: function() {
                var carro = localStorage.getItem('carro_compra');
                if (carro != null) {
                    try { this.carro = JSON.parse(carro) || []; } catch(e) {}
                }
                var cab = localStorage.getItem('compraCabecera');
                if (cab != null) {
                    try { this.compraCabecera = Object.assign(this.compraCabecera, JSON.parse(cab)); } catch(e) {}
                }
                var prec = localStorage.getItem('compraPreciosCredito');
                if (prec != null) {
                    try { this.preciosCreditos = JSON.parse(prec) || []; } catch(e) {}
                }
            },
            getSucursal: function() {
                var obj = document.getElementById("sucursal");
                if (obj && obj.getAttribute('data-id') != null) {
                    this.compraCabecera.idSucursal = obj.getAttribute('data-id');
                }
            },
            getApertura: function() {
                var self = this;
                var idSucursal = $('#sucursal').attr('data-id');
                this.compraCabecera.idSucursal = idSucursal;
                if (idSucursal != null) {
                    axios.get('aperturacierre/' + idSucursal).then(function(response) {
                        if (response.data) {
                            self.nrooperacion = response.data.nro_operacion;
                            self.compraCabecera.nro_operacion = response.data.nro_operacion;
                            self.caja = 'ABIERTA';
                        } else {
                            self.caja = 'CERRADA';
                        }
                    }).catch(function(error) {
                        console.error(error);
                    });
                }
            },
            validarNroFactura: function(n, flag) {
                if (flag != 3 && n.length < 3) {
                    n.toString().padStart(2, "0");
                }
            },
            numeroaletra: function(n) {
                return NumeroALetras.NumeroALetras(n);
            },
            getFecha: function() {
                var hoy = new Date();
                var yyyy = hoy.getFullYear();
                var mm = String(hoy.getMonth() + 1).padStart(2, '0');
                var dd = String(hoy.getDate()).padStart(2, '0');
                this.compraCabecera.fecha = yyyy + '-' + mm + '-' + dd;
            },
            rellenarCero: function(obj, cantidad) {
                if (!this.compraCabecera[obj]) return;
                var v = String(this.compraCabecera[obj]).trim();
                this.compraCabecera[obj] = v.padStart(cantidad, "0");
                this.saveDatos();
            }
        },
        computed: {
            totalCompra: function() {
                var total = 0;
                for (var i = 0; i < this.carro.length; i++) {
                    total += (Number(this.carro[i].costo) * Number(this.carro[i].cantidad));
                }
                if (this.compraCabecera.descuento > 0 && total > 0) {
                    total -= Number(this.compraCabecera.descuento);
                }
                this.compraCabecera.total = Math.max(0, total);
                return this.format(this.compraCabecera.total);
            }
        },
        mounted() {
            this.getApertura();
            this.recuperarDatos();
            this.getSucursal();
            if (!this.compraCabecera.fecha || this.compraCabecera.fecha === '2021-01-01') {
                this.getFecha();
            }
            this.$nextTick(function () {
                document.querySelectorAll('.venta-scan .buscador-catalogo a:not(.venta-atajo)').forEach(function (el) {
                    if (el.parentElement) {
                        el.parentElement.style.display = 'none';
                    }
                });
            });
        }
    });

    activarMenu('m_compra', '');
</script>
@endsection
