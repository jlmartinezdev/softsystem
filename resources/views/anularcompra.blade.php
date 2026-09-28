@extends('layouts.app')
@section('title', 'Anulación de Compra')

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
        font-family: 'Cairo', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    :root {
        --dash-primary: #0a4d36;
        --dash-primary-dark: #073827;
        --dash-primary-light: #eaf3ef;
        --dash-primary-border: #c8dfd5;
        --dash-danger: #e11d48;
        --dash-danger-dark: #be123c;
        --dash-danger-light: #fff1f2;
        --dash-danger-border: #fecdd3;
        --dash-accent: #b8860b;
        --dash-text-main: #1e293b;
        --dash-text-muted: #64748b;
        --dash-card-bg: #ffffff;
        --dash-panel-bg: #f8fafc;
        --dash-border: #e2e8f0;
        --dash-warning: #f59e0b;
        --dash-info: #0284c7;
    }

    body.dark-mode {
        --dash-primary: #10b981;
        --dash-primary-dark: #059669;
        --dash-primary-light: #064e3b;
        --dash-primary-border: #047857;
        --dash-danger: #fb7185;
        --dash-danger-dark: #f43f5e;
        --dash-danger-light: rgba(225, 29, 72, 0.15);
        --dash-danger-border: rgba(225, 29, 72, 0.35);
        --dash-accent: #f59e0b;
        --dash-text-main: #f1f5f9;
        --dash-text-muted: #94a3b8;
        --dash-card-bg: #1e293b;
        --dash-panel-bg: #0f172a;
        --dash-border: #334155;
        --dash-warning: #fbbf24;
        --dash-info: #38bdf8;
    }

    [v-cloak] {
        display: none;
    }

    #app {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: var(--dash-text-main);
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
        background: var(--dash-danger-light);
        border: 1px solid var(--dash-danger-border);
        color: var(--dash-danger);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        box-shadow: 0 2px 6px rgba(225, 29, 72, 0.08);
        flex-shrink: 0;
    }
    .dash-header-title {
        font-size: 1.55rem;
        font-weight: 700;
        color: var(--dash-danger);
        margin: 0;
        line-height: 1.2;
    }
    body.dark-mode .dash-header-title {
        color: #fb7185;
    }
    .dash-header-subtitle {
        font-size: 0.88rem;
        color: var(--dash-text-muted);
        margin: 0.2rem 0 0;
    }

    /* Badges & Pills in Header */
    .header-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        border: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        color: var(--dash-text-muted);
    }
    .header-badge.active-caja {
        background: #ecfdf5;
        color: #065f46;
        border-color: #a7f3d0;
    }
    body.dark-mode .header-badge.active-caja {
        background: #064e3b;
        color: #6ee7b7;
        border-color: #047857;
    }

    /* Audit Warning Banner */
    .audit-warning-box {
        background: #fff7ed;
        border: 1px solid #ffedd5;
        border-left: 4px solid var(--dash-warning);
        border-radius: 10px;
        padding: 0.85rem 1.15rem;
        margin-bottom: 1.25rem;
        color: #9a3412;
        font-size: 0.86rem;
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
    }
    body.dark-mode .audit-warning-box {
        background: rgba(245, 158, 11, 0.12);
        border-color: rgba(245, 158, 11, 0.3);
        color: #fde68a;
    }

    /* Cards */
    .dash-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        margin-bottom: 1.25rem;
        overflow: hidden;
    }
    .dash-card-header {
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .dash-card-title {
        font-size: 0.95rem;
        font-weight: 700;
        margin: 0;
        color: var(--dash-text-main);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .dash-card-body {
        padding: 1.25rem;
    }

    /* Search Controls */
    .search-input-lg {
        height: calc(2.75rem + 2px);
        font-size: 1.1rem;
        font-weight: 700;
        border-radius: 10px 0 0 10px;
        border: 2px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .search-input-lg:focus {
        border-color: var(--dash-danger);
        box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.15);
        background: var(--dash-card-bg);
        color: var(--dash-text-main);
        outline: none;
    }
    .btn-search-lg {
        padding: 0 1.35rem;
        font-weight: 700;
        font-size: 0.92rem;
        border-radius: 0 10px 10px 0;
        background: var(--dash-danger);
        border: 2px solid var(--dash-danger);
        color: #ffffff;
        transition: all 0.15s;
        cursor: pointer;
    }
    .btn-search-lg:hover:not(:disabled) {
        background: var(--dash-danger-dark);
        border-color: var(--dash-danger-dark);
    }
    .btn-search-lg:disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }

    /* Recent Purchases List */
    .recent-list {
        max-height: 250px;
        overflow-y: auto;
        border-radius: 10px;
        border: 1px solid var(--dash-border);
    }
    .recent-row {
        cursor: pointer;
        padding: 0.6rem 0.85rem;
        border-bottom: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        color: var(--dash-text-main);
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .recent-row:last-child {
        border-bottom: none;
    }
    .recent-row:hover {
        background: var(--dash-danger-light);
    }
    body.dark-mode .recent-row:hover {
        background: rgba(225, 29, 72, 0.18);
    }
    .recent-row.selected {
        background: var(--dash-danger-light);
        border-left: 4px solid var(--dash-danger);
    }
    .recent-id {
        font-family: 'Cairo', monospace;
        font-weight: 700;
        font-size: 0.92rem;
        color: var(--dash-danger);
    }

    /* Metadata Grid */
    .meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    .meta-tile {
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        border-radius: 10px;
        padding: 0.75rem 1rem;
    }
    .meta-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--dash-text-muted);
        font-weight: 700;
        margin-bottom: 0.2rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .meta-value {
        font-size: 0.96rem;
        font-weight: 700;
        color: var(--dash-text-main);
        word-break: break-word;
    }

    /* Total Financial Box */
    .total-box-highlight {
        background: var(--dash-danger-light);
        border: 1px solid var(--dash-danger-border);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }
    .total-box-label {
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--dash-danger-dark);
        margin-bottom: 0.2rem;
    }
    body.dark-mode .total-box-label {
        color: #fca5a5;
    }
    .total-box-amount {
        font-size: 1.85rem;
        font-weight: 700;
        line-height: 1;
        color: var(--dash-danger-dark);
    }
    body.dark-mode .total-box-amount {
        color: #fb7185;
    }
    .total-box-desc {
        font-size: 0.8rem;
        color: var(--dash-text-muted);
        margin-top: 0.25rem;
    }

    /* Action Buttons */
    .btn-pos-danger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.6rem 1.4rem;
        background: var(--dash-danger);
        border: 1px solid var(--dash-danger);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.92rem;
        border-radius: 10px;
        transition: all 0.15s ease-in-out;
        text-decoration: none !important;
        box-shadow: 0 2px 6px rgba(225, 29, 72, 0.25);
        cursor: pointer;
    }
    .btn-pos-danger:hover:not(:disabled) {
        background: var(--dash-danger-dark);
        border-color: var(--dash-danger-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(225, 29, 72, 0.35);
    }
    .btn-pos-danger:disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }

    .btn-pos-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 1.1rem;
        border-radius: 10px;
        border: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        color: var(--dash-text-main);
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none !important;
    }
    .btn-pos-secondary:hover {
        border-color: var(--dash-primary);
        color: var(--dash-primary);
        background: var(--dash-primary-light);
    }

    /* Table */
    .table-modern {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    .table-modern thead th {
        background: var(--dash-panel-bg);
        border-bottom: 1px solid var(--dash-border);
        border-top: none;
        color: var(--dash-text-muted);
        font-size: 0.77rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        padding: 0.75rem 1rem;
        white-space: nowrap;
    }
    .table-modern tbody td {
        border-top: 1px solid var(--dash-border);
        padding: 0.75rem 1rem;
        vertical-align: middle;
        font-size: 0.89rem;
        color: var(--dash-text-main);
    }
    .table-modern tbody tr:first-child td {
        border-top: none;
    }
    .table-modern tbody tr:hover {
        background: var(--dash-panel-bg);
    }

    /* Badges */
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
    .badge-factura {
        font-family: 'SFMono-Regular', Consolas, Menlo, Courier, monospace;
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 0.82rem;
        font-weight: 700;
    }
    body.dark-mode .badge-factura {
        background: #064e3b;
        color: #6ee7b7;
        border-color: #047857;
    }
    .badge-revert {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 0.82rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    body.dark-mode .badge-revert {
        background: #450a0a;
        color: #fca5a5;
        border-color: #7f1d1d;
    }
    .badge-condicion {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 700;
    }
    .badge-condicion-contado {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    body.dark-mode .badge-condicion-contado {
        background: #064e3b;
        color: #a7f3d0;
        border-color: #047857;
    }
    .badge-condicion-credito {
        background: #fffbeb;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    body.dark-mode .badge-condicion-credito {
        background: #451a03;
        color: #fde68a;
        border-color: #78350f;
    }

    /* Warning for negative stock */
    .badge-stock-warning {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        border-radius: 6px;
        padding: 2px 6px;
        font-size: 0.74rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    body.dark-mode .badge-stock-warning {
        background: #450a0a;
        color: #fca5a5;
        border-color: #7f1d1d;
    }

    /* Empty state */
    .empty-search-state {
        text-align: center;
        padding: 3.5rem 1rem;
        color: var(--dash-text-muted);
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
                <h4 class="dash-header-title font-cairo">Anulación de Compra</h4>
                <p class="dash-header-subtitle">Búsqueda, auditoría e invalidación de compras de proveedores y reversión física de stock</p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="header-badge" :class="{ 'active-caja': nrooperacion > 0 }">
                <i class="fa fa-cash-register"></i>
                <span v-if="nrooperacion > 0">Caja Op. #@{{ nrooperacion }}</span>
                <span v-else class="text-danger">Sin Caja Abierta</span>
            </span>
            <span class="header-badge">
                <i class="fa fa-store"></i>
                <span>Sucursal #@{{ idSucursal || '1' }}</span>
            </span>
            <button class="btn-pos-secondary" @click="recargarComprasRecientes" title="Actualizar lista de compras recientes">
                <i class="fa fa-sync-alt" :class="{ 'fa-spin': cargandoRecientes }"></i>
            </button>
        </div>
    </div>

    <!-- AUDIT WARNING BOX -->
    <div class="audit-warning-box">
        <i class="fa fa-exclamation-triangle fa-lg text-warning mt-1"></i>
        <div>
            <strong>Atención de Control y Auditoría:</strong> La anulación de una compra <b>descontará físicamente</b> las cantidades compradas del stock de la sucursal de origen. Si la compra fue realizada al <b>Contado</b>, se generará automáticamente una <b>Entrada de Efectivo</b> en la caja abierta para reflejar la devolución del pago.
        </div>
    </div>

    <!-- SEARCH & RECENT PURCHASES GRID -->
    <div class="row">
        <!-- Columna 1: Buscador -->
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="dash-card h-100">
                <div class="dash-card-header">
                    <h5 class="dash-card-title font-cairo">
                        <i class="fa fa-search text-danger"></i>
                        <span>Buscar Compra por Código</span>
                    </h5>
                    <small class="text-muted">Cód. Compra (compra_cod)</small>
                </div>
                <div class="dash-card-body d-flex flex-column justify-content-between">
                    <div>
                        <label class="font-weight-bold text-muted small mb-2 text-uppercase">
                            Número de Registro de Compra:
                        </label>
                        <div class="input-group mb-3">
                            <input type="text"
                                   v-model="txtbuscar"
                                   @keyup.enter="buscar"
                                   class="form-control search-input-lg"
                                   placeholder="Ej: 1, 2, 3..."
                                   autofocus>
                            <div class="input-group-append">
                                <button class="btn btn-search-lg font-cairo"
                                        @click="buscar"
                                        :disabled="requestSend || !txtbuscar.trim()">
                                    <span v-if="requestSend" class="spinner-border spinner-border-sm mr-1"></span>
                                    <i v-else class="fa fa-search mr-1"></i>
                                    <span>Buscar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2">
                        <small class="text-muted">
                            <i class="fa fa-keyboard text-muted mr-1"></i>Presioná <b>Enter</b> para buscar rápidamente.
                        </small>
                        <button v-if="txtbuscar" class="btn btn-sm btn-link text-danger p-0" @click="cancelar">
                            <i class="fa fa-times mr-1"></i>Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna 2: Compras Recientes -->
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="dash-card h-100">
                <div class="dash-card-header">
                    <h5 class="dash-card-title font-cairo">
                        <i class="fa fa-history text-muted"></i>
                        <span>Compras Recientes Registradas</span>
                    </h5>
                    <small class="text-muted">Clic para cargar</small>
                </div>
                <div class="dash-card-body p-2">
                    <div class="recent-list" v-if="comprasRecientes.length > 0">
                        <div v-for="c in comprasRecientes"
                             :key="c.compra_cod"
                             class="recent-row"
                             :class="{ 'selected': compraCabecera.nro == c.compra_cod }"
                             @click="seleccionarCompra(c.compra_cod)">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge-id">#@{{ c.compra_cod }}</span>
                                <div>
                                    <div class="font-weight-bold" style="font-size: 0.88rem;">
                                        @{{ (c.proveedor_nombre || 'Proveedor').trim() }}
                                    </div>
                                    <small class="text-muted">
                                        <i class="fa fa-calendar-alt mr-1"></i>@{{ formatFecha(c.compra_fecha) }}
                                        <span class="mx-1">•</span>
                                        <span v-if="c.compra_factura && c.compra_factura !== '--'">Fact: @{{ c.compra_factura }}</span>
                                        <span v-else class="text-muted">Sin Factura</span>
                                    </small>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-weight-bold text-dark dark:text-white font-cairo" style="font-size: 0.95rem;">
                                    @{{ formatGs(c.total) }} Gs.
                                </div>
                                <span class="badge" :class="c.compra_tipo_factura == '1' ? 'badge-success' : 'badge-warning'" style="font-size: 0.72rem;">
                                    @{{ c.compra_tipo_factura == '1' ? 'Contado' : 'Crédito' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center py-4 text-muted small">
                        <i class="fa fa-inbox fa-2x mb-2 text-muted" style="opacity: 0.3;"></i>
                        <div>No hay compras registradas recientemente</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- COMPRA DETALLES & ACCIONES (VISIBLE SOLO CUANDO HAY UNA COMPRA CARGADA) -->
    <div v-if="compraCabecera.nro" class="dash-card mt-2">
        <div class="dash-card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge badge-danger p-2 font-cairo" style="font-size: 0.9rem;">
                    <i class="fa fa-truck mr-1"></i>Compra #@{{ compraCabecera.nro }}
                </span>
                <span class="badge-factura" v-if="compraCabecera.factura && compraCabecera.factura !== '--'">
                    Factura: @{{ compraCabecera.factura }}
                </span>
                <span class="badge-condicion" :class="compraCabecera.tipo_factura == '1' ? 'badge-condicion-contado' : 'badge-condicion-credito'">
                    @{{ compraCabecera.condicion }}
                </span>
                <span class="header-badge">
                    <i class="fa fa-calendar-day text-info"></i>
                    <span>Fecha: @{{ compraCabecera.fecha }}</span>
                </span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a :href="'pdf/compra/' + compraCabecera.nro"
                   target="_blank"
                   class="btn-pos-secondary font-cairo"
                   title="Ver comprobante de compra en PDF">
                    <i class="fa fa-print"></i>
                    <span>Ver Boleta</span>
                </a>
                <button class="btn-pos-secondary" @click="cancelar" title="Cerrar compra actual">
                    <i class="fa fa-times"></i>
                    <span>Cerrar</span>
                </button>
            </div>
        </div>

        <div class="dash-card-body">
            <!-- Metadata Grid -->
            <div class="meta-grid">
                <!-- Proveedor -->
                <div class="meta-tile">
                    <div class="meta-label">
                        <i class="fa fa-building text-primary"></i>
                        <span>Proveedor / Razón Social</span>
                    </div>
                    <div class="meta-value">@{{ compraCabecera.proveedorNombre }}</div>
                </div>

                <!-- RUC -->
                <div class="meta-tile">
                    <div class="meta-label">
                        <i class="fa fa-id-card text-info"></i>
                        <span>R.U.C. Proveedor</span>
                    </div>
                    <div class="meta-value">@{{ compraCabecera.proveedorId || '—' }}</div>
                </div>

                <!-- Condición de Pago -->
                <div class="meta-tile">
                    <div class="meta-label">
                        <i class="fa fa-hand-holding-usd text-warning"></i>
                        <span>Condición de Pago</span>
                    </div>
                    <div class="meta-value">
                        @{{ compraCabecera.condicion }}
                        <small class="text-muted" v-if="parseFloat(compraCabecera.descuento) > 0">
                            (Desc: @{{ formatGs(compraCabecera.descuento) }} Gs.)
                        </small>
                    </div>
                </div>

                <!-- Sucursal -->
                <div class="meta-tile">
                    <div class="meta-label">
                        <i class="fa fa-store text-success"></i>
                        <span>Sucursal de Origen</span>
                    </div>
                    <div class="meta-value">Sucursal #@{{ compraCabecera.suc_cod || idSucursal }}</div>
                </div>
            </div>

            <!-- TOTAL FINANCIAL HIGHLIGHT BOX -->
            <div class="total-box-highlight">
                <div>
                    <div class="total-box-label">
                        <i class="fa fa-money-bill-wave mr-1"></i>Importe Total de la Compra a Anular
                    </div>
                    <div class="total-box-amount font-cairo">
                        @{{ formatGs(totalCompra) }} Gs.
                    </div>
                    <div class="total-box-desc">
                        Se descontarán físicamente del stock <b>@{{ totalCantidadArticulos }} unidades</b> en total de @{{ articulos.length }} producto(s).
                        <span v-if="compraCabecera.tipo_factura == '1'" class="text-success font-weight-bold ml-1">
                            (Se acreditará este importe a la caja activa como devolución en efectivo).
                        </span>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button class="btn-pos-danger font-cairo"
                            :disabled="anulando"
                            @click="anular">
                        <i class="fa fa-spinner fa-spin" v-if="anulando"></i>
                        <i class="fa fa-ban" v-else></i>
                        <span>@{{ anulando ? 'Anulando compra...' : 'Anular Compra Definitivamente' }}</span>
                    </button>
                    <button class="btn-pos-secondary"
                            :disabled="anulando"
                            @click="cancelar">
                        <i class="fa fa-times"></i>
                        <span>Cancelar</span>
                    </button>
                </div>
            </div>

            <!-- DETALLE DE ARTÍCULOS Y REVERSIÓN DE STOCK -->
            <div class="mb-2 d-flex align-items-center justify-content-between">
                <h6 class="font-cairo mb-0 text-dark dark:text-white" style="font-weight: 700;">
                    <i class="fa fa-boxes text-primary mr-1"></i>
                    <span>Artículos de la Compra y Reversión de Stock (@{{ articulos.length }})</span>
                </h6>
                <small class="text-muted">Las cantidades serán descontadas del stock físico</small>
            </div>

            <div class="table-responsive border rounded-lg">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th style="width: 120px;">Cód. / Barras</th>
                            <th>Descripción del Producto</th>
                            <th style="width: 90px;" class="text-center">IVA</th>
                            <th class="text-right" style="width: 130px;">Costo Unitario</th>
                            <th class="text-center" style="width: 140px;">Cantidad a Descontar</th>
                            <th class="text-center" style="width: 140px;">Stock Actual</th>
                            <th class="text-center" style="width: 150px;">Stock Resultante</th>
                            <th class="text-right" style="width: 140px;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="articulos.length === 0">
                            <td colspan="8" class="text-center py-4 text-muted">
                                No se encontraron detalles de artículos para esta compra.
                            </td>
                        </tr>
                        <tr v-for="d in articulos" :key="d.ARTICULOS_cod">
                            <!-- Código / Barras -->
                            <td>
                                <span class="badge-id font-weight-bold">@{{ d.producto_c_barra || ('#' + d.ARTICULOS_cod) }}</span>
                            </td>

                            <!-- Descripción -->
                            <td>
                                <div class="font-weight-bold text-dark dark:text-white">
                                    @{{ d.producto_nombre }}
                                </div>
                                <small class="text-muted">Cód. interno: #@{{ d.ARTICULOS_cod }}</small>
                            </td>

                            <!-- IVA -->
                            <td class="text-center">
                                <span class="badge badge-light border">
                                    @{{ d.iva }}%
                                </span>
                            </td>

                            <!-- Costo Unitario -->
                            <td class="text-right">
                                @{{ formatGs(d.compra_precio) }} Gs.
                            </td>

                            <!-- Cantidad Comprada (A descontar) -->
                            <td class="text-center">
                                <span class="badge-revert">
                                    <i class="fa fa-minus"></i>
                                    <span>@{{ formatGs(d.compra_cantidad) }}</span>
                                </span>
                            </td>

                            <!-- Stock Actual -->
                            <td class="text-center font-weight-bold">
                                @{{ formatGs(d.stock_actual) }}
                            </td>

                            <!-- Stock Resultante -->
                            <td class="text-center">
                                <span class="font-weight-bold" :class="(parseFloat(d.stock_actual) - parseFloat(d.compra_cantidad)) < 0 ? 'text-danger' : 'text-success'">
                                    @{{ formatGs(parseFloat(d.stock_actual) - parseFloat(d.compra_cantidad)) }}
                                </span>
                                <div v-if="(parseFloat(d.stock_actual) - parseFloat(d.compra_cantidad)) < 0" class="mt-1">
                                    <span class="badge-stock-warning" title="El stock físico quedará negativo porque parte de los artículos ya fue vendida">
                                        <i class="fa fa-exclamation-circle"></i> Stock Insuficiente
                                    </span>
                                </div>
                            </td>

                            <!-- Subtotal -->
                            <td class="text-right font-weight-bold text-dark dark:text-white">
                                @{{ formatGs(d.compra_cantidad * d.compra_precio) }} Gs.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- EMPTY / IDLE STATE (CUANDO NO HAY COMPRA SELECCIONADA) -->
    <div v-else class="dash-card">
        <div class="empty-search-state">
            <i class="fa fa-truck fa-4x mb-3 text-muted" style="opacity: 0.25;"></i>
            <h5 class="font-cairo font-weight-bold text-dark dark:text-white">Ninguna Compra Seleccionada</h5>
            <p class="text-muted small mx-auto" style="max-width: 480px;">
                Ingresá el código de compra en el buscador superior y presioná <b>Enter</b>, o hacé clic directamente en cualquiera de las <b>Compras Recientes</b> para auditar los productos y el impacto en el stock antes de anular.
            </p>
        </div>
    </div>

</div>
@endsection

@section('script')
<script type="text/javascript">
    var app = new Vue({
        el: "#app",
        data: {
            txtbuscar: '',
            requestSend: false,
            anulando: false,
            cargandoRecientes: false,

            compraCabecera: {
                nro: '',
                fecha: '',
                factura: '',
                condicion: '',
                tipo_factura: '1',
                proveedorNombre: '',
                proveedorId: '',
                descuento: 0,
                suc_cod: 1,
                total: 0
            },
            articulos: [],
            comprasRecientes: {!! json_encode($comprasRecientes) !!},
            idSucursal: 1,
            nrooperacion: 0
        },
        computed: {
            totalCompra: function() {
                var sum = 0;
                for (var i = 0; i < this.articulos.length; i++) {
                    sum += parseFloat(this.articulos[i].compra_precio || 0) * parseFloat(this.articulos[i].compra_cantidad || 0);
                }
                var desc = parseFloat(this.compraCabecera.descuento) || 0;
                return Math.max(0, sum - desc);
            },
            totalCantidadArticulos: function() {
                var total = 0;
                for (var i = 0; i < this.articulos.length; i++) {
                    total += parseFloat(this.articulos[i].compra_cantidad || 0);
                }
                return Math.round(total);
            }
        },
        methods: {
            formatGs: function(valor) {
                var num = parseFloat(valor) || 0;
                return new Intl.NumberFormat("de-DE").format(Math.round(num));
            },
            formatFecha: function(fecha) {
                if (!fecha) return '—';
                var clean = fecha.split(" ")[0];
                var f = clean.split("-");
                if (f.length === 3) {
                    return f[2] + "/" + f[1] + "/" + f[0];
                }
                return fecha;
            },
            buscar: function() {
                var self = this;
                var query = (this.txtbuscar || '').trim();
                if (!query) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Ingrese un número',
                        text: 'Debe ingresar el código de compra a consultar.',
                        confirmButtonColor: '#e11d48'
                    });
                    return;
                }

                if (this.requestSend) return;
                this.requestSend = true;

                axios.get('compra/cabecera/' + query)
                    .then(function(res) {
                        self.requestSend = false;
                        if (!res.data.compra || res.data.compra.length < 1) {
                            Swal.fire({
                                icon: 'info',
                                title: 'No encontrada',
                                text: 'No se encontró ninguna compra registrada con el código: ' + query,
                                confirmButtonColor: '#e11d48'
                            });
                            self.cancelar();
                            return;
                        }

                        var compra = res.data.compra[0];
                        self.articulos = res.data.detalle || [];
                        self.compraCabecera.nro = compra.compra_cod || query;
                        self.compraCabecera.fecha = self.formatFecha(compra.compra_fecha);
                        self.compraCabecera.factura = (compra.compra_factura || '').trim();
                        self.compraCabecera.tipo_factura = compra.compra_tipo_factura;
                        self.compraCabecera.condicion = compra.compra_tipo_factura == "2" ? "Crédito" : "Contado";
                        self.compraCabecera.proveedorId = (compra.proveedor_ruc || '').trim();
                        self.compraCabecera.proveedorNombre = (compra.proveedor_nombre || '').trim();
                        self.compraCabecera.descuento = parseFloat(compra.compra_descuento) || 0;
                        self.compraCabecera.suc_cod = compra.suc_cod || self.idSucursal;

                        var Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'Compra #' + self.compraCabecera.nro + ' cargada'
                        });
                    })
                    .catch(function(err) {
                        self.requestSend = false;
                        console.error('Error al buscar compra:', err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de búsqueda',
                            text: 'No fue posible consultar la compra. Verifique el código ingresado.',
                            confirmButtonColor: '#e11d48'
                        });
                    });
            },
            seleccionarCompra: function(id) {
                this.txtbuscar = String(id);
                this.buscar();
            },
            anular: function() {
                var self = this;
                if (!this.compraCabecera.nro || this.articulos.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sin compra cargada',
                        text: 'Seleccione o busque una compra válida antes de anular.',
                        confirmButtonColor: '#e11d48'
                    });
                    return;
                }

                // Check if any product will result in negative stock
                var negativeItems = [];
                for (var i = 0; i < this.articulos.length; i++) {
                    var diff = parseFloat(this.articulos[i].stock_actual) - parseFloat(this.articulos[i].compra_cantidad);
                    if (diff < 0) {
                        negativeItems.push(this.articulos[i].producto_nombre + ' (quedará en ' + Math.round(diff) + ')');
                    }
                }

                var warningHtml = '';
                if (negativeItems.length > 0) {
                    warningHtml = '<div class="alert alert-warning text-left p-2 mt-2 mb-2" style="font-size:0.83rem;">' +
                        '<b><i class="fa fa-exclamation-triangle mr-1"></i>Advertencia de Stock Insuficiente:</b><br>' +
                        'Los siguientes artículos ya han sido vendidos parcialmente y su stock quedará en negativo:<br>' +
                        '• ' + negativeItems.join('<br>• ') +
                        '</div>';
                }

                var cashWarning = '';
                if (this.compraCabecera.tipo_factura == '1') {
                    cashWarning = '<li><b>Caja:</b> Se registrará una <span class="text-success font-weight-bold">Entrada de Efectivo por ' + self.formatGs(self.totalCompra) + ' Gs.</span> en la caja activa.</li>';
                }

                Swal.fire({
                    title: '¿Confirmar Anulación de Compra?',
                    html: '<div class="text-left" style="font-size: 0.95rem;">' +
                          '<p>Está a punto de <b>anular definitivamente</b> la siguiente compra:</p>' +
                          '<ul class="list-unstyled p-2 rounded bg-light border">' +
                          '<li><b>Compra Nro:</b> #' + self.compraCabecera.nro + '</li>' +
                          '<li><b>Proveedor:</b> ' + self.compraCabecera.proveedorNombre + '</li>' +
                          '<li><b>Condición:</b> ' + self.compraCabecera.condicion + '</li>' +
                          '<li><b>Total:</b> <span class="text-danger font-weight-bold">' + self.formatGs(self.totalCompra) + ' Gs.</span></li>' +
                          '<li><b>Artículos a Descontar:</b> ' + self.totalCantidadArticulos + ' unidades en ' + self.articulos.length + ' producto(s)</li>' +
                          cashWarning +
                          '</ul>' +
                          warningHtml +
                          '<small class="text-danger"><b>Esta acción no se puede deshacer.</b> El stock físico se actualizará de forma inmediata.</small>' +
                          '</div>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="fa fa-ban"></i> Sí, anular compra',
                    cancelButtonText: 'Cancelar'
                }).then(function(result) {
                    if (result.value) {
                        self.anulando = true;

                        var arts = [];
                        for (var i = 0; i < self.articulos.length; i++) {
                            arts.push({
                                id: self.articulos[i].ARTICULOS_cod,
                                cantidad: parseFloat(self.articulos[i].compra_cantidad)
                            });
                        }

                        axios.post('anular_compra', {
                            id: self.compraCabecera.nro,
                            idSucursal: self.compraCabecera.suc_cod || self.idSucursal,
                            nrooperacion: self.nrooperacion,
                            articulos: arts
                        })
                        .then(function(res) {
                            self.anulando = false;
                            Swal.fire({
                                icon: 'success',
                                title: '¡Compra Anulada!',
                                html: 'La compra <b>#' + self.compraCabecera.nro + '</b> fue anulada con éxito.<br>El stock de los artículos fue revertido correctamente.',
                                confirmButtonColor: '#0a4d36'
                            });

                            self.cancelar();
                            self.recargarComprasRecientes();
                        })
                        .catch(function(err) {
                            self.anulando = false;
                            var msg = err.response && err.response.data && err.response.data.message
                                ? err.response.data.message
                                : 'Ocurrió un error al procesar la anulación de la compra.';
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al anular',
                                text: msg,
                                confirmButtonColor: '#e11d48'
                            });
                        });
                    }
                });
            },
            cancelar: function() {
                this.compraCabecera = {
                    nro: '',
                    fecha: '',
                    factura: '',
                    condicion: '',
                    tipo_factura: '1',
                    proveedorNombre: '',
                    proveedorId: '',
                    descuento: 0,
                    suc_cod: 1,
                    total: 0
                };
                this.articulos = [];
                this.txtbuscar = '';
            },
            recargarComprasRecientes: function() {
                var self = this;
                this.cargandoRecientes = true;
                axios.get('compras_recientes')
                    .then(function(res) {
                        self.comprasRecientes = res.data || [];
                        self.cargandoRecientes = false;
                    })
                    .catch(function(err) {
                        self.cargandoRecientes = false;
                        console.error('Error al recargar compras recientes:', err);
                    });
            },
            getApertura: function() {
                var self = this;
                var sid = localStorage.getItem("suc_cod") || $('#sucursal').attr('data-id') || 1;
                this.idSucursal = sid;

                if (sid) {
                    axios.get('aperturacierre/' + sid)
                        .then(function(res) {
                            if (res.data) {
                                self.nrooperacion = res.data.nro_operacion || 0;
                            }
                        })
                        .catch(function(err) {
                            console.error('Error al obtener estado de caja:', err);
                        });
                }
            }
        },
        mounted: function() {
            this.getApertura();
        }
    });

    activarMenu('m_anular', 'm_acompra');
</script>
@endsection