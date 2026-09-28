@extends('layouts.app')
@section('title', 'Ofertas y Promos')
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
    }

    [v-cloak] {
        display: none !important;
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
        font-size: 1.55rem;
        font-weight: 800;
        color: var(--dash-primary);
        margin: 0;
        line-height: 1.2;
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

    /* Action Buttons */
    .btn-pos-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        padding: 0.5rem 1.15rem;
        background: var(--dash-primary);
        border: 1px solid var(--dash-primary);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.88rem;
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
        padding: 0.5rem 0.95rem;
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        color: var(--dash-text-main) !important;
        font-weight: 600;
        font-size: 0.88rem;
        border-radius: 8px;
        transition: all 0.15s ease;
        text-decoration: none !important;
        cursor: pointer;
    }
    .btn-pos-secondary:hover {
        background: var(--dash-panel-bg);
        border-color: var(--dash-primary);
        color: var(--dash-primary) !important;
    }

    /* Cards */
    .card-modern {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .card-modern-header {
        padding: 0.9rem 1.25rem;
        background: var(--dash-card-bg);
        border-bottom: 1px solid var(--dash-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }
    .card-modern-title {
        font-size: 1.05rem;
        font-weight: 700;
        margin: 0;
        color: var(--dash-text-main);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .card-modern-body {
        padding: 1.25rem;
    }
    .card-modern-footer {
        padding: 0.85rem 1.25rem;
        background: var(--dash-panel-bg);
        border-top: 1px solid var(--dash-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Header Icons */
    .card-header-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        background: var(--dash-primary-light);
        color: var(--dash-primary);
        border: 1px solid var(--dash-primary-border);
    }

    /* Search & Filter in list */
    .oferta-filter-box {
        padding: 0.85rem 1rem;
        background: var(--dash-panel-bg);
        border-bottom: 1px solid var(--dash-border);
    }
    .oferta-pills {
        display: flex;
        gap: 0.35rem;
        flex-wrap: wrap;
        margin-top: 0.5rem;
    }
    .btn-pill {
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 600;
        padding: 0.22rem 0.65rem;
        border: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        color: var(--dash-text-muted);
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-pill:hover {
        border-color: var(--dash-primary);
        color: var(--dash-primary);
    }
    .btn-pill.active {
        background: var(--dash-primary);
        color: #ffffff;
        border-color: var(--dash-primary);
    }

    /* Ofertas List */
    .oferta-list {
        max-height: 640px;
        overflow-y: auto;
        padding: 0.5rem;
    }
    .oferta-item {
        border: 1px solid var(--dash-border);
        border-radius: 10px;
        padding: 0.85rem;
        margin-bottom: 0.5rem;
        background: var(--dash-card-bg);
        cursor: pointer;
        transition: all 0.15s ease;
        position: relative;
    }
    .oferta-item:hover {
        border-color: var(--dash-primary);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transform: translateY(-1px);
    }
    .oferta-item.is-selected {
        border-color: var(--dash-primary);
        background: var(--dash-primary-light);
        box-shadow: 0 0 0 2px var(--dash-primary-border);
    }
    .oferta-item-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--dash-text-main);
        line-height: 1.25;
        margin-bottom: 0.2rem;
    }
    .oferta-item-art {
        font-size: 0.82rem;
        color: var(--dash-text-muted);
        margin-bottom: 0.4rem;
    }
    .oferta-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        align-items: center;
    }
    .tag-pill {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.15rem 0.5rem;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    .tag-descuento {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    body.dark-mode .tag-descuento {
        background: #064e3b;
        color: #a7f3d0;
        border-color: #047857;
    }
    .tag-condicion {
        background: var(--dash-panel-bg);
        color: var(--dash-text-muted);
        border: 1px solid var(--dash-border);
    }

    /* Status Dot */
    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 4px;
    }
    .status-dot-active {
        background-color: #10b981;
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.25);
    }
    .status-dot-inactive {
        background-color: #94a3b8;
    }

    /* Selected Product Card in Form */
    .articulo-selected-box {
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        border-radius: 10px;
        padding: 0.85rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }
    .articulo-selected-info strong {
        font-size: 0.95rem;
        color: var(--dash-text-main);
        display: block;
    }
    .articulo-selected-info span {
        font-size: 0.8rem;
        color: var(--dash-text-muted);
    }

    /* Search Results for Articles */
    .oferta-search-results {
        max-height: 230px;
        overflow-y: auto;
        border: 1px solid var(--dash-border);
        border-radius: 8px;
        background: var(--dash-card-bg);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
    }
    .oferta-search-results .list-group-item {
        cursor: pointer;
        padding: 0.55rem 0.85rem;
        background: var(--dash-card-bg);
        border-color: var(--dash-border);
        color: var(--dash-text-main);
        transition: background 0.12s ease;
    }
    .oferta-search-results .list-group-item:hover {
        background: var(--dash-primary-light);
        color: var(--dash-primary-dark);
    }

    /* Live Preview Simulator Box */
    .oferta-simulator {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(10, 77, 54, 0.04) 100%);
        border: 1px solid var(--dash-primary-border);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-top: 1rem;
        margin-bottom: 1.25rem;
    }
    body.dark-mode .oferta-simulator {
        background: rgba(16, 185, 129, 0.06);
    }
    .sim-header {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--dash-primary);
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 0.5rem;
    }
    .sim-price-main {
        font-size: 1.45rem;
        font-weight: 800;
        color: var(--dash-primary);
        line-height: 1.1;
    }
    .sim-old-price {
        font-size: 0.88rem;
        text-decoration: line-through;
        color: var(--dash-text-muted);
    }
    .sim-savings {
        font-size: 0.82rem;
        font-weight: 700;
        color: #10b981;
    }

    /* Segmented Control / Type Selectors */
    .segmented-control {
        display: flex;
        background: var(--dash-panel-bg);
        padding: 3px;
        border-radius: 10px;
        border: 1px solid var(--dash-border);
        gap: 3px;
    }
    .segmented-btn {
        flex: 1 1 0;
        text-align: center;
        padding: 0.4rem 0.5rem;
        font-size: 0.82rem;
        font-weight: 700;
        border: none;
        background: transparent;
        color: var(--dash-text-muted);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .segmented-btn:hover {
        color: var(--dash-text-main);
    }
    .segmented-btn.active {
        background: var(--dash-card-bg);
        color: var(--dash-primary);
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
    }
    body.dark-mode .segmented-btn.active {
        background: #374151;
        color: #10b981;
    }

    /* Form Controls */
    .form-control-modern {
        border: 1px solid var(--dash-border);
        border-radius: 8px;
        background-color: var(--dash-card-bg);
        color: var(--dash-text-main);
        padding: 0.48rem 0.75rem;
        font-size: 0.9rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .form-control-modern:focus {
        border-color: var(--dash-primary);
        box-shadow: 0 0 0 3px var(--dash-primary-light);
        outline: none;
        background-color: var(--dash-card-bg);
        color: var(--dash-text-main);
    }
    body.dark-mode .form-control-modern {
        background-color: #111827;
        color: #f3f4f6;
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
<div class="container-fluid" id="app" v-cloak>
    <!-- Header de la Página -->
    <div class="dash-header">
        <div>
            <h1 class="dash-header-title font-cairo">
                <i class="fa fa-tag mr-1 text-primary"></i> Ofertas y Promos
            </h1>
            <p class="dash-header-subtitle">
                Descuentos por volumen, promociones por fechas de vigencia y precios especiales en caja
            </p>
        </div>
        <div class="dash-header-badges">
            <span class="badge badge-light border px-2 py-1 text-muted">
                <strong>@{{ ofertas.length }}</strong> ofertas totales
            </span>
            <span class="badge badge-success px-2 py-1">
                <span class="status-dot status-dot-active"></span>
                <strong>@{{ activasCount }}</strong> activas
            </span>
            <span class="badge badge-secondary px-2 py-1" v-if="inactivasCount > 0">
                <strong>@{{ inactivasCount }}</strong> pausadas
            </span>
            <a href="{{ route('articulo') }}" class="btn-pos-secondary ml-1" title="Ir al catálogo de artículos">
                <i class="fa fa-boxes"></i> Artículos
            </a>
            <button type="button" class="btn-pos-primary ml-1" @click="nuevo">
                <i class="fa fa-plus"></i> Nueva Oferta
            </button>
        </div>
    </div>

    <!-- Contenido Principal en 2 Columnas -->
    <div class="row">
        
        <!-- Columna Izquierda: Listado y Filtros -->
        <div class="col-lg-5 mb-4">
            <div class="card-modern">
                <div class="card-modern-header">
                    <div class="card-modern-title font-cairo">
                        <span class="card-header-icon"><i class="fa fa-list"></i></span>
                        Listado de Ofertas
                    </div>
                    <span class="badge badge-pill badge-primary font-weight-bold">
                        @{{ ofertasFiltradas.length }}
                    </span>
                </div>

                <!-- Caja de Búsqueda y Filtros Rápidos -->
                <div class="oferta-filter-box">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-right-0 text-muted">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                        <input
                            type="text"
                            class="form-control form-control-modern border-left-0"
                            v-model="filtroTexto"
                            placeholder="Filtrar por nombre, artículo o código..."
                        >
                        <div class="input-group-append" v-if="filtroTexto">
                            <button class="btn btn-outline-secondary" type="button" @click="filtroTexto = ''">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <div class="oferta-pills">
                        <button
                            type="button"
                            class="btn-pill"
                            :class="{ active: filtroEstado === 'todas' }"
                            @click="filtroEstado = 'todas'"
                        >
                            Todas (@{{ ofertas.length }})
                        </button>
                        <button
                            type="button"
                            class="btn-pill"
                            :class="{ active: filtroEstado === 'activas' }"
                            @click="filtroEstado = 'activas'"
                        >
                            <i class="fa fa-check text-success mr-1"></i> Activas (@{{ activasCount }})
                        </button>
                        <button
                            type="button"
                            class="btn-pill"
                            :class="{ active: filtroEstado === 'pausadas' }"
                            @click="filtroEstado = 'pausadas'"
                        >
                            <i class="fa fa-pause text-muted mr-1"></i> Pausadas (@{{ inactivasCount }})
                        </button>
                    </div>
                </div>

                <!-- Lista de Ofertas -->
                <div class="oferta-list">
                    <div
                        v-for="o in ofertasFiltradas"
                        :key="o.id"
                        class="oferta-item"
                        :class="{ 'is-selected': form.id === o.id }"
                        @click="editar(o)"
                    >
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="pr-2">
                                <div class="oferta-item-title">
                                    <span
                                        class="status-dot"
                                        :class="Number(o.activo) ? 'status-dot-active' : 'status-dot-inactive'"
                                        :title="Number(o.activo) ? 'Oferta Activa' : 'Oferta Pausada'"
                                    ></span>
                                    @{{ o.nombre }}
                                </div>
                                <div class="oferta-item-art">
                                    <i class="fa fa-cube text-muted mr-1"></i>
                                    <strong>@{{ (o.articulo && o.articulo.producto_nombre) || ('#' + o.articulos_cod) }}</strong>
                                    <span v-if="o.codigo" class="badge badge-light border ml-1 font-family-monospace">
                                        @{{ o.codigo }}
                                    </span>
                                </div>
                            </div>

                            <!-- Acciones rápidas en ítem -->
                            <div class="d-flex align-items-center gap-1" @click.stop>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-link p-1 text-muted"
                                    @click="toggleActivo(o)"
                                    :title="Number(o.activo) ? 'Pausar oferta' : 'Activar oferta'"
                                >
                                    <i class="fa" :class="Number(o.activo) ? 'fa-toggle-on text-success fa-lg' : 'fa-toggle-off text-muted fa-lg'"></i>
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger border-0 p-1"
                                    @click="eliminar(o)"
                                    title="Eliminar oferta"
                                >
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Etiquetas de Condición y Descuento -->
                        <div class="oferta-tags mt-2">
                            <span class="tag-pill tag-descuento">
                                <i class="fa fa-percentage"></i> @{{ labelDescuento(o) }}
                            </span>
                            <span class="tag-pill tag-condicion">
                                <i class="fa" :class="o.tipo === 'fecha' ? 'fa-calendar-alt' : 'fa-shopping-cart'"></i>
                                @{{ labelTipo(o) }}
                            </span>
                            <span v-if="o.articulo && o.articulo.pre_venta1" class="tag-pill tag-condicion ml-auto font-weight-bold">
                                Gs. @{{ format(o.articulo.pre_venta1) }}
                            </span>
                        </div>
                    </div>

                    <!-- Estado Vacío -->
                    <div v-if="!ofertasFiltradas.length" class="text-center text-muted py-5">
                        <i class="fa fa-tags fa-3x mb-3 text-muted" style="opacity: 0.35;"></i>
                        <h6 class="font-weight-bold">No se encontraron ofertas</h6>
                        <p class="small text-muted mb-2">
                            @{{ filtroTexto ? 'No hay coincidencias para "' + filtroTexto + '"' : 'Aún no registraste ninguna oferta.' }}
                        </p>
                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" @click="nuevo">
                            <i class="fa fa-plus mr-1"></i> Crear primera oferta
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Formulario Editor -->
        <div class="col-lg-7">
            <div class="card-modern">
                <div class="card-modern-header">
                    <div class="card-modern-title font-cairo">
                        <span class="card-header-icon">
                            <i class="fa" :class="form.id ? 'fa-edit text-warning' : 'fa-magic text-primary'"></i>
                        </span>
                        <span>@{{ form.id ? 'Editar Oferta #' + form.id : 'Configurar Nueva Oferta' }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span v-if="form.id" class="badge badge-warning mr-2">Modo Edición</span>
                        <button v-if="form.id" type="button" class="btn btn-outline-secondary btn-sm" @click="nuevo">
                            <i class="fa fa-times mr-1"></i> Cancelar
                        </button>
                    </div>
                </div>

                <div class="card-modern-body">
                    <!-- Fila 1: Nombre y Código de Barras -->
                    <div class="row">
                        <div class="col-md-7 form-group">
                            <label class="font-weight-bold small text-muted text-uppercase">Nombre de la Oferta *</label>
                            <input
                                type="text"
                                class="form-control form-control-modern"
                                v-model.trim="form.nombre"
                                placeholder="Ej: Promo 3x Gaseosas, Viernes de Pizza, -20% Limpieza"
                            >
                        </div>
                        <div class="col-md-5 form-group">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="font-weight-bold small text-muted text-uppercase mb-0">Código de Barras *</label>
                                <button
                                    type="button"
                                    class="btn btn-link btn-xs p-0 text-primary font-weight-bold"
                                    @click="generarCodigoAutomatico"
                                    title="Generar un código OFT aleatorio"
                                >
                                    <i class="fa fa-random"></i> Auto
                                </button>
                            </div>
                            <div class="input-group">
                                <input
                                    type="text"
                                    class="form-control form-control-modern"
                                    :class="{'is-invalid': codigoEstado === 'error', 'is-valid': codigoEstado === 'ok'}"
                                    v-model.trim="form.codigo"
                                    placeholder="Ej: OFT001"
                                    @blur="validarCodigo"
                                    @input="codigoEstado = ''"
                                >
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" @click="validarCodigo" title="Verificar código">
                                        <i class="fa fa-check"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="form-text" :class="codigoEstado === 'error' ? 'text-danger' : (codigoEstado === 'ok' ? 'text-success' : 'text-muted')">
                                @{{ codigoMensaje || 'Código único para identificar la promoción en caja' }}
                            </small>
                        </div>
                    </div>

                    <!-- Fila 2: Selector de Artículo -->
                    <div class="form-group">
                        <label class="font-weight-bold small text-muted text-uppercase">Artículo en Promoción *</label>
                        
                        <!-- Si el artículo ya está seleccionado -->
                        <div v-if="form.articulo_nombre" class="articulo-selected-box">
                            <div class="d-flex align-items-center">
                                <div class="card-header-icon mr-3">
                                    <i class="fa fa-cube text-primary"></i>
                                </div>
                                <div class="articulo-selected-info">
                                    <strong>@{{ form.articulo_nombre }}</strong>
                                    <span>
                                        Código: <code>@{{ form.articulo_codigo || ('#' + form.articulos_cod) }}</code> ·
                                        Precio Lista: <strong class="text-success">Gs. @{{ format(form.articulo_precio) }}</strong>
                                    </span>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm" @click="limpiarArticulo">
                                <i class="fa fa-exchange-alt mr-1"></i> Cambiar
                            </button>
                        </div>

                        <!-- Si no hay artículo seleccionado, mostrar buscador -->
                        <div v-else>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-transparent border-right-0 text-muted">
                                        <i class="fa" :class="buscando ? 'fa-spinner fa-spin' : 'fa-search'"></i>
                                    </span>
                                </div>
                                <input
                                    type="text"
                                    class="form-control form-control-modern border-left-0"
                                    v-model.trim="buscar"
                                    placeholder="Buscar artículo por nombre o código de barras..."
                                    @input="onBuscarInput"
                                    @keyup.enter="buscarArticulos"
                                >
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" @click="buscarArticulos">
                                        Buscar
                                    </button>
                                </div>
                            </div>

                            <!-- Resultados emergentes del buscador -->
                            <div class="oferta-search-results mt-1" v-if="resultados.length">
                                <div
                                    class="list-group-item d-flex justify-content-between align-items-center"
                                    v-for="a in resultados"
                                    :key="a.ARTICULOS_cod"
                                    @click="elegirArticulo(a)"
                                >
                                    <div>
                                        <div class="font-weight-bold">@{{ a.producto_nombre }}</div>
                                        <small class="text-muted">
                                            Cod: @{{ a.producto_c_barra || a.ARTICULOS_cod }}
                                        </small>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-weight-bold text-success">Gs. @{{ format(a.pre_venta1) }}</span>
                                        <span class="btn btn-sm btn-outline-primary py-0 px-2 ml-2">Elegir</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fila 3: Tipo de Oferta (Segmented Control) -->
                    <div class="form-group">
                        <label class="font-weight-bold small text-muted text-uppercase">Condición de Aplicación *</label>
                        <div class="segmented-control">
                            <button
                                type="button"
                                class="segmented-btn"
                                :class="{ active: form.tipo === 'cantidad' }"
                                @click="form.tipo = 'cantidad'"
                            >
                                <i class="fa fa-cubes mr-1"></i> Por Cantidad (Volumen)
                            </button>
                            <button
                                type="button"
                                class="segmented-btn"
                                :class="{ active: form.tipo === 'fecha' }"
                                @click="form.tipo = 'fecha'"
                            >
                                <i class="fa fa-calendar mr-1"></i> Por Fechas (Temporada)
                            </button>
                            <button
                                type="button"
                                class="segmented-btn"
                                :class="{ active: form.tipo === 'ambos' }"
                                @click="form.tipo = 'ambos'"
                            >
                                <i class="fa fa-layer-group mr-1"></i> Cantidad + Fechas
                            </button>
                        </div>
                    </div>

                    <!-- Campos condicionales de Cantidad -->
                    <div class="row" v-if="form.tipo === 'cantidad' || form.tipo === 'ambos'">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold small text-muted text-uppercase">Cantidad mínima requerida *</label>
                            <div class="input-group">
                                <input
                                    type="number"
                                    class="form-control form-control-modern"
                                    v-model.number="form.cantidad_min"
                                    min="1"
                                    step="1"
                                    placeholder="Ej: 3"
                                >
                                <div class="input-group-append">
                                    <span class="input-group-text bg-light-panel text-muted small">unidades</span>
                                </div>
                            </div>
                            <small class="text-muted">A partir de esta cantidad se aplica el descuento.</small>
                        </div>
                    </div>

                    <!-- Campos condicionales de Fechas -->
                    <div class="row" v-if="form.tipo === 'fecha' || form.tipo === 'ambos'">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold small text-muted text-uppercase">Fecha Desde</label>
                            <input type="date" class="form-control form-control-modern" v-model="form.fecha_desde">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold small text-muted text-uppercase">Fecha Hasta</label>
                            <input type="date" class="form-control form-control-modern" v-model="form.fecha_hasta">
                        </div>
                        <div class="col-12 mb-3">
                            <span class="small text-muted mr-2">Accesos rápidos de fechas:</span>
                            <button type="button" class="btn btn-xs btn-outline-secondary mr-1" @click="setFechaPreset('hoy')">Solo hoy</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary mr-1" @click="setFechaPreset('semana')">Próximos 7 días</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary" @click="setFechaPreset('mes')">Mes completo</button>
                        </div>
                    </div>

                    <!-- Fila 4: Tipo y Valor de Descuento -->
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold small text-muted text-uppercase">Forma de Descuento *</label>
                            <select class="form-control form-control-modern" v-model="form.descuento_tipo">
                                <option value="porcentaje">Porcentaje de Descuento (%)</option>
                                <option value="monto">Monto Fijo de Descuento (Gs.)</option>
                                <option value="precio_fijo">Precio Final Fijo de Oferta (Gs.)</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold small text-muted text-uppercase">
                                Valor del Descuento *
                                <span v-if="form.descuento_tipo === 'porcentaje'">(%)</span>
                                <span v-else>(Gs.)</span>
                            </label>
                            <input
                                type="number"
                                class="form-control form-control-modern"
                                v-model.number="form.descuento_valor"
                                min="0"
                                step="1"
                                :placeholder="form.descuento_tipo === 'porcentaje' ? 'Ej: 15' : 'Ej: 5000'"
                            >
                        </div>
                    </div>

                    <!-- Simulador en Vivo de la Oferta -->
                    <div class="oferta-simulator" v-if="form.articulo_precio && form.descuento_valor >= 0">
                        <div class="sim-header font-cairo">
                            <i class="fa fa-calculator"></i> Simulación en Tiempo Real
                        </div>
                        <div class="row align-items-center">
                            <div class="col-sm-6 mb-2 mb-sm-0">
                                <span class="sim-old-price d-block">
                                    Precio lista: Gs. @{{ format(form.articulo_precio) }}
                                </span>
                                <div class="sim-price-main font-cairo">
                                    Gs. @{{ format(precioPreview) }}
                                    <small class="badge badge-success ml-1">@{{ resumenDescuentoTexto }}</small>
                                </div>
                            </div>
                            <div class="col-sm-6 text-sm-right border-sm-left" v-if="form.tipo === 'cantidad' || form.tipo === 'ambos'">
                                <small class="text-muted d-block">Llevando @{{ form.cantidad_min || 1 }} unidades:</small>
                                <strong class="text-dark-mode">Gs. @{{ format(precioPreview * (form.cantidad_min || 1)) }}</strong>
                                <span class="sim-savings d-block">
                                    Ahorro cliente: Gs. @{{ format((form.articulo_precio - precioPreview) * (form.cantidad_min || 1)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Fila 5: Estado y Observación -->
                    <div class="row align-items-center">
                        <div class="col-md-5 form-group">
                            <div class="custom-control custom-switch mt-2">
                                <input type="checkbox" class="custom-control-input" id="ofertaActiva" v-model="form.activo">
                                <label class="custom-control-label font-weight-bold" for="ofertaActiva">
                                    @{{ form.activo ? 'Oferta Activa en Caja' : 'Oferta Pausada / Inactiva' }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-7 form-group">
                            <label class="font-weight-bold small text-muted text-uppercase">Observación / Nota interna</label>
                            <input
                                type="text"
                                class="form-control form-control-modern"
                                v-model.trim="form.observacion"
                                placeholder="Ej: Hasta agotar stock, solo para clientes finales..."
                            >
                        </div>
                    </div>
                </div>

                <!-- Footer con Botones de Acción -->
                <div class="card-modern-footer">
                    <button type="button" class="btn btn-outline-secondary" @click="nuevo">
                        <i class="fa fa-eraser mr-1"></i> Limpiar
                    </button>

                    <div class="d-flex align-items-center gap-2">
                        <button
                            type="button"
                            class="btn-pos-primary"
                            @click="guardar"
                            :disabled="guardando"
                        >
                            <span class="fa" :class="guardando ? 'fa-spinner fa-spin' : 'fa-save'"></span>
                            @{{ form.id ? 'Actualizar Oferta' : 'Guardar Oferta' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="app-loading-skeleton">
    <div class="v-cloak-spinner"></div>
    <div class="font-cairo font-weight-bold" style="font-size: 1rem;">Cargando Ofertas y Promos...</div>
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
            ofertas: @json($ofertas),
            filtroTexto: '',
            filtroEstado: 'todas',
            buscar: '',
            buscando: false,
            resultados: [],
            buscarTimer: null,
            guardando: false,
            codigoEstado: '',
            codigoMensaje: '',
            form: {
                id: null,
                nombre: '',
                codigo: '',
                articulos_cod: 0,
                articulo_nombre: '',
                articulo_codigo: '',
                articulo_precio: 0,
                tipo: 'cantidad',
                cantidad_min: 2,
                fecha_desde: '',
                fecha_hasta: '',
                descuento_tipo: 'porcentaje',
                descuento_valor: 10,
                activo: true,
                observacion: ''
            }
        },
        computed: {
            activasCount: function () {
                return (this.ofertas || []).filter(function (o) {
                    return Number(o.activo) === 1;
                }).length;
            },
            inactivasCount: function () {
                return (this.ofertas || []).filter(function (o) {
                    return Number(o.activo) === 0;
                }).length;
            },
            ofertasFiltradas: function () {
                var txt = (this.filtroTexto || '').toLowerCase().trim();
                var estado = this.filtroEstado;

                return (this.ofertas || []).filter(function (o) {
                    if (estado === 'activas' && Number(o.activo) !== 1) return false;
                    if (estado === 'pausadas' && Number(o.activo) !== 0) return false;

                    if (!txt) return true;

                    var nom = (o.nombre || '').toLowerCase();
                    var cod = (o.codigo || '').toLowerCase();
                    var artNom = ((o.articulo && o.articulo.producto_nombre) || '').toLowerCase();
                    var artBarra = ((o.articulo && o.articulo.producto_c_barra) || '').toLowerCase();

                    return nom.indexOf(txt) !== -1 ||
                           cod.indexOf(txt) !== -1 ||
                           artNom.indexOf(txt) !== -1 ||
                           artBarra.indexOf(txt) !== -1;
                });
            },
            precioPreview: function () {
                var base = Number(this.form.articulo_precio) || 0;
                var v = Number(this.form.descuento_valor) || 0;
                if (this.form.descuento_tipo === 'monto') return Math.max(0, base - v);
                if (this.form.descuento_tipo === 'precio_fijo') return Math.max(0, v);
                return Math.max(0, Math.round(base * (1 - v / 100)));
            },
            resumenDescuentoTexto: function () {
                if (this.form.descuento_tipo === 'porcentaje') {
                    return '-' + this.form.descuento_valor + '% OFF';
                }
                if (this.form.descuento_tipo === 'monto') {
                    return '- Gs. ' + this.format(this.form.descuento_valor);
                }
                return 'Precio final';
            }
        },
        methods: {
            format: function (n) {
                return new Intl.NumberFormat('de-DE').format(Number(n) || 0);
            },
            labelTipo: function (o) {
                if (o.tipo === 'cantidad') return 'Desde ' + o.cantidad_min + ' u.';
                if (o.tipo === 'fecha') {
                    return (o.fecha_desde || '…') + ' → ' + (o.fecha_hasta || '…');
                }
                return '≥' + o.cantidad_min + ' u. · ' + (o.fecha_desde || '…') + ' → ' + (o.fecha_hasta || '…');
            },
            labelDescuento: function (o) {
                if (o.descuento_tipo === 'monto') return '- Gs. ' + this.format(o.descuento_valor);
                if (o.descuento_tipo === 'precio_fijo') return 'Gs. ' + this.format(o.descuento_valor);
                return o.descuento_valor + '% OFF';
            },
            blankForm: function () {
                return {
                    id: null,
                    nombre: '',
                    codigo: '',
                    articulos_cod: 0,
                    articulo_nombre: '',
                    articulo_codigo: '',
                    articulo_precio: 0,
                    tipo: 'cantidad',
                    cantidad_min: 2,
                    fecha_desde: '',
                    fecha_hasta: '',
                    descuento_tipo: 'porcentaje',
                    descuento_valor: 10,
                    activo: true,
                    observacion: ''
                };
            },
            nuevo: function () {
                this.form = this.blankForm();
                this.resultados = [];
                this.buscar = '';
                this.codigoEstado = '';
                this.codigoMensaje = '';
            },
            editar: function (o) {
                var art = o.articulo || {};
                this.form = {
                    id: o.id,
                    nombre: o.nombre,
                    codigo: o.codigo || '',
                    articulos_cod: o.articulos_cod,
                    articulo_nombre: art.producto_nombre || '',
                    articulo_codigo: art.producto_c_barra || '',
                    articulo_precio: Number(art.pre_venta1) || 0,
                    tipo: o.tipo,
                    cantidad_min: o.cantidad_min != null ? Number(o.cantidad_min) : 2,
                    fecha_desde: o.fecha_desde || '',
                    fecha_hasta: o.fecha_hasta || '',
                    descuento_tipo: o.descuento_tipo,
                    descuento_valor: Number(o.descuento_valor) || 0,
                    activo: Number(o.activo) === 1,
                    observacion: o.observacion || ''
                };
                this.resultados = [];
                this.codigoEstado = '';
                this.codigoMensaje = '';
            },
            generarCodigoAutomatico: function () {
                var rand = Math.floor(1000 + Math.random() * 9000);
                this.form.codigo = 'OFT' + rand;
                this.validarCodigo();
            },
            setFechaPreset: function (preset) {
                var hoy = new Date();
                var yyyy = hoy.getFullYear();
                var mm = String(hoy.getMonth() + 1).padStart(2, '0');
                var dd = String(hoy.getDate()).padStart(2, '0');
                var hoyStr = yyyy + '-' + mm + '-' + dd;

                if (preset === 'hoy') {
                    this.form.fecha_desde = hoyStr;
                    this.form.fecha_hasta = hoyStr;
                } else if (preset === 'semana') {
                    var en7 = new Date();
                    en7.setDate(en7.getDate() + 7);
                    var mm7 = String(en7.getMonth() + 1).padStart(2, '0');
                    var dd7 = String(en7.getDate()).padStart(2, '0');
                    this.form.fecha_desde = hoyStr;
                    this.form.fecha_hasta = en7.getFullYear() + '-' + mm7 + '-' + dd7;
                } else if (preset === 'mes') {
                    var finMes = new Date(yyyy, hoy.getMonth() + 1, 0);
                    var ddFin = String(finMes.getDate()).padStart(2, '0');
                    this.form.fecha_desde = yyyy + '-' + mm + '-01';
                    this.form.fecha_hasta = yyyy + '-' + mm + '-' + ddFin;
                }
            },
            validarCodigo: function () {
                var self = this;
                var codigo = (this.form.codigo || '').trim();
                if (!codigo) {
                    this.codigoEstado = 'error';
                    this.codigoMensaje = 'El código de barras es obligatorio.';
                    return Promise.resolve(false);
                }
                return axios.get('{{ url('oferta/validar-codigo') }}', {
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
            limpiarArticulo: function () {
                this.form.articulos_cod = 0;
                this.form.articulo_nombre = '';
                this.form.articulo_codigo = '';
                this.form.articulo_precio = 0;
            },
            onBuscarInput: function () {
                var self = this;
                if (this.buscarTimer) clearTimeout(this.buscarTimer);
                this.buscarTimer = setTimeout(function () {
                    if ((self.buscar || '').trim().length >= 2) self.buscarArticulos();
                    else self.resultados = [];
                }, 300);
            },
            buscarArticulos: function () {
                var self = this;
                this.buscando = true;
                axios.get('{{ url('oferta/articulos') }}', { params: { buscar: this.buscar } })
                    .then(function (r) {
                        self.buscando = false;
                        self.resultados = r.data || [];
                    })
                    .catch(function () {
                        self.buscando = false;
                        self.resultados = [];
                    });
            },
            elegirArticulo: function (a) {
                this.form.articulos_cod = a.ARTICULOS_cod || a.articulos_cod;
                this.form.articulo_nombre = a.producto_nombre;
                this.form.articulo_codigo = a.producto_c_barra || '';
                this.form.articulo_precio = Number(a.pre_venta1) || 0;
                this.resultados = [];
                this.buscar = '';
            },
            payload: function () {
                return {
                    nombre: this.form.nombre,
                    codigo: this.form.codigo,
                    articulos_cod: this.form.articulos_cod,
                    tipo: this.form.tipo,
                    cantidad_min: this.form.cantidad_min,
                    fecha_desde: this.form.fecha_desde || null,
                    fecha_hasta: this.form.fecha_hasta || null,
                    descuento_tipo: this.form.descuento_tipo,
                    descuento_valor: this.form.descuento_valor,
                    activo: this.form.activo ? 1 : 0,
                    observacion: this.form.observacion
                };
            },
            toggleActivo: function (o) {
                var nuevoEstado = Number(o.activo) ? 0 : 1;
                var self = this;
                axios.put('{{ url('oferta') }}/' + o.id, {
                    nombre: o.nombre,
                    codigo: o.codigo,
                    articulos_cod: o.articulos_cod,
                    tipo: o.tipo,
                    cantidad_min: o.cantidad_min,
                    fecha_desde: o.fecha_desde,
                    fecha_hasta: o.fecha_hasta,
                    descuento_tipo: o.descuento_tipo,
                    descuento_valor: o.descuento_valor,
                    activo: nuevoEstado,
                    observacion: o.observacion
                }).then(function () {
                    o.activo = nuevoEstado;
                    if (self.form.id === o.id) {
                        self.form.activo = nuevoEstado === 1;
                    }
                    Toast.fire({
                        icon: 'success',
                        title: nuevoEstado ? 'Oferta activada' : 'Oferta pausada'
                    });
                }).catch(function () {
                    Toast.fire({ icon: 'error', title: 'No se pudo cambiar el estado' });
                });
            },
            guardar: function () {
                var self = this;
                if (!this.form.nombre) {
                    Swal.fire('Falta nombre', 'Indicá el nombre de la oferta.', 'warning');
                    return;
                }
                if (!(this.form.codigo || '').trim()) {
                    Swal.fire('Falta código', 'Indicá el código de barras de la oferta.', 'warning');
                    return;
                }
                if (!this.form.articulos_cod) {
                    Swal.fire('Falta artículo', 'Seleccioná un artículo.', 'warning');
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
                        ? axios.put('{{ url('oferta') }}/' + self.form.id, self.payload())
                        : axios.post('{{ url('oferta') }}', self.payload());
                    req.then(function (r) {
                        self.guardando = false;
                        Toast.fire({ icon: 'success', title: r.data.message || 'Guardado' });
                        window.location.reload();
                    }).catch(function (err) {
                        self.guardando = false;
                        var msg = (err.response && err.response.data && err.response.data.message)
                            ? err.response.data.message : 'No se pudo guardar';
                        Swal.fire('Error', msg, 'error');
                    });
                });
            },
            eliminar: function (o) {
                Swal.fire({
                    title: '¿Eliminar oferta?',
                    text: 'Se eliminará "' + o.nombre + '"',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then(function (res) {
                    if (!res.value) return;
                    axios.delete('{{ url('oferta') }}/' + o.id).then(function () {
                        Toast.fire({ icon: 'success', title: 'Oferta eliminada' });
                        window.location.reload();
                    });
                });
            }
        },
        mounted: function () {
            activarMenu('m_oferta', '');
        }
    });
</script>
@endsection
