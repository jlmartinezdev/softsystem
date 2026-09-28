@extends('layouts.app')
@section('title', 'Anulación de Venta')

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
        --dash-border: #e2e8f0;
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
        --dash-text-main: #f3f4f6;
        --dash-text-muted: #9ca3af;
        --dash-card-bg: #1f2937;
        --dash-border: #374151;
    }

    [v-cloak] {
        display: none;
    }

    .anular-wrapper {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: var(--dash-text-main);
    }

    /* Header */
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
    }
    .dash-header-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dash-danger);
        margin: 0;
        line-height: 1.2;
    }
    .dash-header-subtitle {
        font-size: 0.88rem;
        color: var(--dash-text-muted);
        margin: 0.25rem 0 0;
    }
    .dash-header-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
    }

    /* Cards */
    .dash-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        margin-bottom: 1.25rem;
        overflow: hidden;
        transition: box-shadow 0.2s ease;
    }
    .dash-card:hover {
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    }
    .dash-card-header {
        padding: 0.9rem 1.25rem;
        background: transparent;
        border-bottom: 1px solid var(--dash-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .dash-card-title {
        font-size: 1rem;
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

    /* Input & Search Controls */
    .search-input-lg {
        height: calc(2.8rem + 2px);
        font-size: 1.15rem;
        font-weight: 600;
        border-radius: 10px 0 0 10px;
        border: 2px solid var(--dash-border);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        background: var(--dash-card-bg);
        color: var(--dash-text-main);
    }
    .search-input-lg:focus {
        border-color: var(--dash-danger);
        box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.15);
        background: var(--dash-card-bg);
        color: var(--dash-text-main);
    }
    .btn-search-lg {
        padding: 0 1.4rem;
        font-weight: 700;
        font-size: 0.95rem;
        border-radius: 0 10px 10px 0;
    }

    /* Recent Sales Scrollable */
    .recent-sales-list {
        max-height: 290px;
        overflow-y: auto;
        border-radius: 10px;
        border: 1px solid var(--dash-border);
    }
    .recent-sale-row {
        cursor: pointer;
        padding: 0.65rem 0.9rem;
        border-bottom: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        color: var(--dash-text-main);
        transition: background-color 0.15s ease, transform 0.1s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .recent-sale-row:last-child {
        border-bottom: none;
    }
    .recent-sale-row:hover {
        background-color: var(--dash-danger-light);
    }
    .recent-sale-id {
        font-family: 'Cairo', monospace;
        font-weight: 700;
        font-size: 0.95rem;
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
        background: rgba(0, 0, 0, 0.02);
        border: 1px solid var(--dash-border);
        border-radius: 10px;
        padding: 0.75rem 1rem;
    }
    body.dark-mode .meta-tile {
        background: rgba(255, 255, 255, 0.03);
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
        font-size: 0.98rem;
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
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--dash-danger-dark);
        margin-bottom: 0.15rem;
    }
    .total-box-amount {
        font-size: 1.9rem;
        font-weight: 700;
        line-height: 1;
        color: var(--dash-danger-dark);
    }
    body.dark-mode .total-box-amount {
        color: #fb7185;
    }

    /* Table */
    .table-modern {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    .table-modern thead th {
        background: rgba(0, 0, 0, 0.03);
        border-bottom: 1px solid var(--dash-border);
        border-top: none;
        color: var(--dash-text-muted);
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        padding: 0.75rem 1rem;
    }
    .table-modern tbody td {
        border-top: 1px solid var(--dash-border);
        padding: 0.75rem 1rem;
        vertical-align: middle;
        font-size: 0.9rem;
        color: var(--dash-text-main);
    }
    .table-modern tbody tr:first-child td {
        border-top: none;
    }
    .table-modern tbody tr:hover {
        background: rgba(0, 0, 0, 0.015);
    }
    body.dark-mode .table-modern thead th {
        background: rgba(255, 255, 255, 0.04);
        color: var(--dash-text-muted);
        border-color: var(--dash-border);
    }
    body.dark-mode .table-modern tbody td {
        border-color: var(--dash-border);
    }
    body.dark-mode .table-modern tbody tr:hover {
        background: rgba(255, 255, 255, 0.03);
    }

    /* Reposición stock badges */
    .badge-stock-return {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.65rem;
        border-radius: 999px;
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
        font-weight: 700;
        font-size: 0.78rem;
    }
    body.dark-mode .badge-stock-return {
        background: #064e3b;
        color: #a7f3d0;
        border-color: #047857;
    }
    .badge-free-item {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.65rem;
        border-radius: 999px;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
        font-weight: 600;
        font-size: 0.78rem;
    }
    body.dark-mode .badge-free-item {
        background: #334155;
        color: #cbd5e1;
        border-color: #475569;
    }

    /* Warning banner */
    .audit-warning-box {
        background: #fff7ed;
        border: 1px solid #ffedd5;
        border-left: 4px solid #f97316;
        border-radius: 10px;
        padding: 0.9rem 1.15rem;
        margin-bottom: 1.25rem;
        color: #9a3412;
    }
    body.dark-mode .audit-warning-box {
        background: rgba(249, 115, 22, 0.12);
        border-color: rgba(249, 115, 22, 0.3);
        border-left: 4px solid #ea580c;
        color: #fdba74;
    }

    /* Action bar */
    .action-bar-container {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding-top: 1rem;
        border-top: 1px solid var(--dash-border);
    }

    /* Empty state */
    .empty-state-box {
        padding: 3rem 1.5rem;
        text-align: center;
        color: var(--dash-text-muted);
    }
    .empty-state-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto 1.25rem;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.03);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--dash-text-muted);
    }
    body.dark-mode .empty-state-icon {
        background: rgba(255, 255, 255, 0.05);
    }
</style>
@endsection

@section('main')
<div id="app" class="anular-wrapper" v-cloak>
    <!-- Page Header -->
    <header class="dash-header">
        <div class="dash-header-left">
            <div class="dash-header-icon">
                <i class="fa fa-ban"></i>
            </div>
            <div>
                <h1 class="dash-header-title">Anulación de Venta</h1>
                <p class="dash-header-subtitle">
                    Búsqueda, auditoría previa y reversión segura de ventas con reposición automática al inventario
                </p>
            </div>
        </div>
        <div class="dash-header-actions">
            <a href="{{ route('venta') }}" class="btn btn-outline-success font-weight-bold">
                <i class="fa fa-shopping-cart mr-1"></i> Punto de Venta
            </a>
            <a href="{{ route('infventa') }}" class="btn btn-outline-secondary font-weight-bold">
                <i class="fa fa-chart-line mr-1"></i> Informe de Ventas
            </a>
        </div>
    </header>

    <!-- Row: Search & Quick Selection -->
    <div class="row">
        <!-- Search Box Column -->
        <div class="col-lg-5 col-md-12 mb-3">
            <div class="dash-card h-100">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">
                        <i class="fa fa-search text-danger"></i> Buscar Comprobante a Anular
                    </h2>
                </div>
                <div class="dash-card-body d-flex flex-column justify-content-between">
                    <div>
                        <label class="small font-weight-bold text-muted mb-2">NÚMERO DE VENTA / FACTURA</label>
                        <div class="input-group mb-3">
                            <input
                                type="text"
                                class="form-control search-input-lg"
                                v-model="txtbuscar"
                                @keyup.enter="buscar(txtbuscar)"
                                placeholder="Ej: 1045..."
                                autofocus
                            />
                            <div class="input-group-append">
                                <button
                                    class="btn btn-danger btn-search-lg"
                                    :disabled="requestSend"
                                    @click="buscar(txtbuscar)"
                                >
                                    <template v-if="requestSend">
                                        <span class="spinner-border spinner-border-sm mr-1"></span> Buscando...
                                    </template>
                                    <template v-else>
                                        <i class="fa fa-search mr-1"></i> Buscar
                                    </template>
                                </button>
                            </div>
                        </div>

                        <div class="p-3 rounded" style="background: rgba(0, 0, 0, 0.02); border: 1px solid var(--dash-border);">
                            <div class="d-flex align-items-start gap-2">
                                <i class="fa fa-info-circle text-primary mt-1 mr-2"></i>
                                <div class="small text-muted">
                                    Ingresá el número de venta impreso en el ticket o factura. La consulta cargará el cliente, montos, sucursal y la lista de artículos a reponer en stock.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3 text-right" v-if="ventaLoaded">
                        <button class="btn btn-sm btn-outline-secondary" @click="cancelar">
                            <i class="fa fa-eraser mr-1"></i> Limpiar Búsqueda
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Select Column (Últimas Ventas) -->
        <div class="col-lg-7 col-md-12 mb-3">
            <div class="dash-card h-100">
                <div class="dash-card-header">
                    <h2 class="dash-card-title">
                        <i class="fa fa-history text-secondary"></i> Últimas Ventas Emitidas
                    </h2>
                    <span class="badge badge-light border text-muted">1 Clic para Cargar</span>
                </div>
                <div class="dash-card-body p-2">
                    @if(isset($ultimasVentas) && count($ultimasVentas) > 0)
                        <div class="recent-sales-list">
                            @foreach($ultimasVentas as $uv)
                                <div
                                    class="recent-sale-row"
                                    @click="cargarVenta('{{ $uv->nro_fact_ventas }}')"
                                    title="Hacé clic para cargar la venta #{{ $uv->nro_fact_ventas }}"
                                >
                                    <div class="d-flex flex-column text-truncate mr-2">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="recent-sale-id mr-2">#{{ $uv->nro_fact_ventas }}</span>
                                            <span class="badge badge-light border mr-1 font-weight-bold">{{ $uv->documento ?? 'Ticket' }}</span>
                                            <span class="badge {{ $uv->tipo_factura == '2' ? 'badge-warning text-dark' : 'badge-success' }}">
                                                {{ $uv->tipo_factura == '2' ? 'Crédito' : 'Contado' }}
                                            </span>
                                        </div>
                                        <div class="font-weight-bold text-truncate" style="font-size: 0.9rem;">
                                            {{ $uv->cliente_nombre }}
                                        </div>
                                        <div class="small text-muted">
                                            <i class="fa fa-calendar-alt mr-1"></i>{{ $uv->fecha }}
                                            @if($uv->suc_desc)
                                                <span class="mx-1">•</span><i class="fa fa-store mr-1"></i>{{ $uv->suc_desc }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right text-nowrap pl-2">
                                        <div class="font-cairo font-weight-bold text-success" style="font-size: 1rem;">
                                            Gs. {{ number_format($uv->venta_total, 0, ',', '.') }}
                                        </div>
                                        <span class="btn btn-xs btn-outline-danger mt-1">
                                            <i class="fa fa-arrow-right mr-1"></i> Cargar
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            <i class="fa fa-receipt fa-2x mb-2 text-secondary opacity-50"></i>
                            <p class="mb-0 small">No hay ventas registradas recientemente.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Loaded Sale Details Section -->
    <div v-if="ventaLoaded">
        <!-- Warning / Audit Alert -->
        <div class="audit-warning-box">
            <div class="d-flex align-items-center">
                <i class="fa fa-exclamation-triangle fa-2x mr-3 text-warning"></i>
                <div>
                    <h5 class="font-weight-bold mb-1" style="font-size: 1rem;">
                        Atención: Proceso de Anulación y Auditoría
                    </h5>
                    <p class="mb-0 small">
                        La anulación cancela la venta en el sistema, revierte los movimientos de caja/cuentas a cobrar y
                        <strong>repondrá las unidades correspondientes al stock de la sucursal</strong>. Esta operación es definitiva.
                    </p>
                </div>
            </div>
        </div>

        <!-- Sale Main Card -->
        <div class="dash-card">
            <div class="dash-card-header">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <span class="badge badge-danger p-2 font-cairo" style="font-size: 1rem;">
                        VENTA Nº @{{ ventaCabecera.nro }}
                    </span>
                    <span class="badge badge-light border text-dark font-weight-bold ml-2">
                        <i class="fa fa-calendar-alt mr-1 text-muted"></i> @{{ formatFecha(ventaCabecera.fecha) }}
                    </span>
                    <span class="badge badge-secondary ml-1">
                        <i class="fa fa-store mr-1"></i> @{{ ventaCabecera.sucursal }}
                    </span>
                    <span class="badge badge-info ml-1" v-if="ventaCabecera.vendedor">
                        <i class="fa fa-user mr-1"></i> @{{ ventaCabecera.vendedor }}
                    </span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge" :class="ventaCabecera.condicion === 'Crédito' ? 'badge-warning text-dark' : 'badge-success'">
                        @{{ ventaCabecera.condicion }}
                    </span>
                </div>
            </div>

            <div class="dash-card-body">
                <!-- Metadata Grid -->
                <div class="meta-grid">
                    <div class="meta-tile">
                        <div class="meta-label">
                            <i class="fa fa-user"></i> Cliente
                        </div>
                        <div class="meta-value">@{{ ventaCabecera.clienteNombre }}</div>
                    </div>
                    <div class="meta-tile">
                        <div class="meta-label">
                            <i class="fa fa-id-card"></i> Documento / RUC
                        </div>
                        <div class="meta-value">@{{ ventaCabecera.clienteRuc || ventaCabecera.clienteId || 'Sin RUC' }}</div>
                    </div>
                    <div class="meta-tile">
                        <div class="meta-label">
                            <i class="fa fa-phone"></i> Teléfono / Contacto
                        </div>
                        <div class="meta-value">@{{ ventaCabecera.clienteCel || 'No especificado' }}</div>
                    </div>
                    <div class="meta-tile">
                        <div class="meta-label">
                            <i class="fa fa-file-invoice"></i> Comprobante
                        </div>
                        <div class="meta-value">@{{ ventaCabecera.documento }}</div>
                    </div>
                </div>

                <!-- Financial Highlight Box -->
                <div class="total-box-highlight">
                    <div>
                        <div class="total-box-label">Importe Total de la Venta a Revertir</div>
                        <div class="total-box-amount font-cairo">
                            Gs. @{{ formatMoney(ventaCabecera.total) }}
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="text-right" v-if="ventaCabecera.descuento > 0">
                            <div class="small text-muted font-weight-bold">DESCUENTO</div>
                            <div class="font-cairo font-weight-bold text-dark">Gs. @{{ formatMoney(ventaCabecera.descuento) }}</div>
                        </div>
                        <div class="text-right" v-if="ventaCabecera.recibido > 0">
                            <div class="small text-muted font-weight-bold">RECIBIDO / VUELTO</div>
                            <div class="font-cairo font-weight-bold text-muted">
                                Gs. @{{ formatMoney(ventaCabecera.recibido) }} / Gs. @{{ formatMoney(ventaCabecera.vuelto) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Itemized Table Header -->
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h3 class="dash-card-title" style="font-size: 0.95rem;">
                        <i class="fa fa-boxes text-success mr-1"></i> Artículos y Reposición al Inventario
                    </h3>
                    <span class="badge badge-light border">@{{ articulos.length }} ítems registrados</span>
                </div>

                <!-- Itemized Table -->
                <div class="table-responsive border rounded mb-3">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th style="width: 130px;">Cód. Barra / ID</th>
                                <th>Descripción del Producto</th>
                                <th class="text-center" style="width: 140px;">Cant. Vendida</th>
                                <th class="text-right" style="width: 140px;">Precio Unit.</th>
                                <th class="text-right" style="width: 150px;">Subtotal</th>
                                <th class="text-center" style="width: 180px;">Efecto en Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(d, index) in articulos" :key="index">
                                <td class="font-weight-bold text-muted">
                                    @{{ d.producto_c_barra || d.ARTICULOS_cod }}
                                </td>
                                <td>
                                    <div class="font-weight-bold">@{{ d.producto_nombre }}</div>
                                    <small class="text-muted" v-if="d.descripcion_libre">
                                        <i class="fa fa-pen mr-1"></i>Ítem personalizado / libre
                                    </small>
                                </td>
                                <td class="text-center font-weight-bold">
                                    @{{ formatCant(d.venta_cantidad) }}
                                </td>
                                <td class="text-right font-cairo">
                                    Gs. @{{ formatMoney(d.venta_precio) }}
                                </td>
                                <td class="text-right font-cairo font-weight-bold">
                                    Gs. @{{ formatMoney(d.venta_cantidad * d.venta_precio) }}
                                </td>
                                <td class="text-center">
                                    <span v-if="d.descripcion_libre" class="badge-free-item">
                                        <i class="fa fa-info-circle"></i> Sin stock
                                    </span>
                                    <span v-else class="badge-stock-return">
                                        <i class="fa fa-arrow-down"></i> Repone +@{{ formatCant(d.venta_cantidad) }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Action Bar -->
                <div class="action-bar-container">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <button
                            type="button"
                            class="btn btn-danger btn-lg font-weight-bold shadow-sm"
                            :disabled="anulando"
                            @click="confirmarAnulacion"
                        >
                            <template v-if="anulando">
                                <span class="spinner-border spinner-border-sm mr-1"></span> Anulando Venta...
                            </template>
                            <template v-else>
                                <i class="fa fa-ban mr-1"></i> ANULAR VENTA DEFINITIVAMENTE
                            </template>
                        </button>

                        <button type="button" class="btn btn-outline-secondary" @click="cancelar">
                            <i class="fa fa-times mr-1"></i> Cancelar / Nueva Búsqueda
                        </button>
                    </div>

                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <a
                            :href="'{{ url('pdf/boletaventa') }}/' + ventaCabecera.nro"
                            target="_blank"
                            class="btn btn-outline-info font-weight-bold"
                            title="Ver Comprobante Oficial PDF"
                        >
                            <i class="fa fa-file-pdf mr-1"></i> Comprobante PDF
                        </a>
                        <a
                            :href="'{{ url('ticket/venta') }}/' + ventaCabecera.nro"
                            target="_blank"
                            class="btn btn-outline-dark font-weight-bold"
                            title="Ver Formato Ticket Térmico"
                        >
                            <i class="fa fa-print mr-1"></i> Ticket Térmico
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty State (When no sale loaded) -->
    <div v-else class="dash-card">
        <div class="dash-card-body empty-state-box">
            <div class="empty-state-icon">
                <i class="fa fa-file-invoice"></i>
            </div>
            <h3 class="h5 font-weight-bold mb-2">Ninguna venta seleccionada para anulación</h3>
            <p class="text-muted mb-3" style="max-width: 500px; margin: 0 auto;">
                Buscá el número de comprobante en el buscador superior o seleccioná directamente una de las últimas ventas emitidas de la derecha para auditar sus datos antes de anular.
            </p>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var app = new Vue({
        el: "#app",
        data: {
            txtbuscar: '',
            requestSend: false,
            anulando: false,
            ventaLoaded: false,
            ventaCabecera: {
                nro: '',
                fecha: '',
                condicion: '',
                documento: '',
                clienteId: '',
                clienteRuc: '',
                clienteNombre: '',
                clienteCel: '',
                clienteDireccion: '',
                sucursal: '',
                vendedor: '',
                total: 0,
                descuento: 0,
                recibido: 0,
                vuelto: 0
            },
            articulos: []
        },
        mounted: function() {
            // Soporte para cargar venta mediante parámetro URL (?nro=... o ?id=...)
            let urlParams = new URLSearchParams(window.location.search);
            let nroParam = urlParams.get('nro') || urlParams.get('id');
            if (nroParam) {
                this.txtbuscar = nroParam;
                this.buscar(nroParam);
            }
        },
        methods: {
            buscar: function(nro) {
                let idABuscar = nro || this.txtbuscar;
                if (!idABuscar || idABuscar.toString().trim() === '') {
                    Swal.fire({
                        title: 'Atención',
                        text: 'Por favor ingresá un número de venta válido.',
                        icon: 'info',
                        confirmButtonColor: '#059669'
                    });
                    return;
                }

                idABuscar = idABuscar.toString().trim();
                this.txtbuscar = idABuscar;

                if (this.requestSend) {
                    return;
                }
                this.requestSend = true;

                axios.get('{{ url("venta/cabecera") }}/' + idABuscar)
                    .then(response => {
                        this.requestSend = false;
                        if (!response.data || !response.data.venta || response.data.venta.length < 1) {
                            this.ventaLoaded = false;
                            Swal.fire({
                                title: 'No encontrado',
                                text: 'No se encontró ninguna venta registrada con el número ' + idABuscar,
                                icon: 'info',
                                confirmButtonColor: '#059669'
                            });
                            return;
                        }

                        let venta = response.data.venta[0];
                        this.articulos = response.data.detalle || [];
                        this.ventaCabecera = {
                            nro: venta.nro_fact_ventas,
                            fecha: venta.venta_fecha,
                            condicion: venta.tipo_factura == "2" ? "Crédito" : "Contado",
                            documento: venta.documento || 'Ticket',
                            clienteId: venta.cliente_ci || '',
                            clienteRuc: venta.cliente_ruc || venta.cliente_ci || '',
                            clienteNombre: venta.cliente_nombre || 'Cliente Ocasional',
                            clienteCel: venta.cliente_cel || '',
                            clienteDireccion: venta.cliente_direccion || '',
                            sucursal: venta.suc_desc || 'Principal',
                            vendedor: venta.vendedor || 'Usuario',
                            total: parseFloat(venta.venta_total || 0),
                            descuento: parseFloat(venta.venta_descuento || 0),
                            recibido: parseFloat(venta.venta_recibido || 0),
                            vuelto: parseFloat(venta.venta_vuelto || 0)
                        };
                        this.ventaLoaded = true;
                    })
                    .catch(error => {
                        this.requestSend = false;
                        console.error(error);
                        Swal.fire({
                            title: 'Error de Conexión',
                            text: 'No se pudo consultar la venta solicitada. Verifique la conexión con el servidor.',
                            icon: 'error',
                            confirmButtonColor: '#e11d48'
                        });
                    });
            },
            cargarVenta: function(nro) {
                this.txtbuscar = nro;
                this.buscar(nro);
            },
            confirmarAnulacion: function() {
                if (!this.ventaCabecera.nro) {
                    Swal.fire('Atención', 'No hay ninguna venta cargada para anular.', 'warning');
                    return;
                }

                let cantArticulos = this.articulos.length;
                let totalGs = this.formatMoney(this.ventaCabecera.total);

                Swal.fire({
                    title: '¿Confirmar Anulación de Venta?',
                    html: `
                        <div class="text-left py-2" style="font-size: 0.95rem;">
                            <p class="mb-2">
                                Estás a punto de anular la <strong>Venta Nº ${this.ventaCabecera.nro}</strong> por un importe total de <strong>Gs. ${totalGs}</strong>.
                            </p>
                            <div class="p-2 rounded bg-light border mb-2 small text-muted">
                                <ul class="pl-3 mb-0">
                                    <li>Se repondrán los <strong>${cantArticulos} productos</strong> al stock de la sucursal.</li>
                                    <li>Se cancelarán los cobros, asientos de caja y cuentas a cobrar vinculadas.</li>
                                    <li class="text-danger font-weight-bold">Esta operación es irreversible.</li>
                                </ul>
                            </div>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="fa fa-ban mr-1"></i> Sí, anular definitivamente',
                    cancelButtonText: '<i class="fa fa-times mr-1"></i> Cancelar',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.value || result.isConfirmed) {
                        this.ejecutarAnulacion();
                    }
                });
            },
            ejecutarAnulacion: function() {
                this.anulando = true;

                Swal.fire({
                    title: 'Procesando Anulación...',
                    text: 'Reponiendo stock y cancelando registros de la venta.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                let arts = [];
                for (let i = 0; i < this.articulos.length; i++) {
                    if (this.articulos[i].descripcion_libre) {
                        continue;
                    }
                    arts.push({
                        id: this.articulos[i].ARTICULOS_cod,
                        cantidad: parseFloat(this.articulos[i].venta_cantidad || 0)
                    });
                }

                axios.post('{{ url("anular_venta") }}', {
                    id: this.ventaCabecera.nro,
                    articulos: arts
                }).then(response => {
                    this.anulando = false;
                    let msg = (response.data && response.data.message)
                        ? response.data.message
                        : 'La venta ha sido anulada correctamente.';

                    Swal.fire({
                        title: '¡Venta Anulada!',
                        text: msg,
                        icon: 'success',
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#059669'
                    }).then(() => {
                        window.location.reload();
                    });
                }).catch(error => {
                    this.anulando = false;
                    let errMsg = 'Ocurrió un error al intentar anular la venta.';
                    if (error.response && error.response.data && error.response.data.message) {
                        errMsg = error.response.data.message;
                    }
                    Swal.fire({
                        title: 'Error al anular',
                        text: errMsg,
                        icon: 'error',
                        confirmButtonColor: '#e11d48'
                    });
                });
            },
            cancelar: function() {
                this.ventaCabecera = {
                    nro: '',
                    fecha: '',
                    condicion: '',
                    documento: '',
                    clienteId: '',
                    clienteRuc: '',
                    clienteNombre: '',
                    clienteCel: '',
                    clienteDireccion: '',
                    sucursal: '',
                    vendedor: '',
                    total: 0,
                    descuento: 0,
                    recibido: 0,
                    vuelto: 0
                };
                this.articulos = [];
                this.txtbuscar = '';
                this.ventaLoaded = false;
            },
            formatMoney: function(val) {
                if (val === null || val === undefined || isNaN(val)) return '0';
                return new Intl.NumberFormat('de-DE').format(Math.round(val));
            },
            formatCant: function(val) {
                if (val === null || val === undefined || isNaN(val)) return '0';
                let n = parseFloat(val);
                return Number.isInteger(n) ? n.toString() : n.toFixed(2);
            },
            formatFecha: function(fechaStr) {
                if (!fechaStr) return '-';
                try {
                    let parts = fechaStr.split(' ');
                    let d = parts[0].split('-');
                    if (d.length === 3) {
                        let hora = parts[1] ? parts[1].substring(0, 5) : '';
                        return d[2] + '/' + d[1] + '/' + d[0] + (hora ? ' ' + hora : '');
                    }
                } catch(e) {}
                return fechaStr;
            }
        }
    });

    activarMenu('m_anular', 'm_aventa');
</script>
@endsection