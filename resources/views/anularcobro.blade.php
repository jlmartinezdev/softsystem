@extends('layouts.app')
@section('title', 'Anulación de Cobro')

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

    /* Recent Payments List */
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
    .badge-recibo {
        font-family: 'SFMono-Regular', Consolas, Menlo, Courier, monospace;
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 0.82rem;
        font-weight: 700;
    }
    body.dark-mode .badge-recibo {
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
    .badge-quota-restored {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    body.dark-mode .badge-quota-restored {
        background: #451a03;
        color: #fde68a;
        border-color: #78350f;
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
                <i class="fa fa-hand-holding-usd"></i>
            </div>
            <div>
                <h4 class="dash-header-title font-cairo">Anulación de Cobro</h4>
                <p class="dash-header-subtitle">Búsqueda, auditoría e invalidación de cobros de cuotas y reversión a caja</p>
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
            <button class="btn-pos-secondary" @click="recargarCobrosRecientes" title="Actualizar lista de cobros recientes">
                <i class="fa fa-sync-alt" :class="{ 'fa-spin': cargandoRecientes }"></i>
            </button>
        </div>
    </div>

    <!-- AUDIT WARNING BOX -->
    <div class="audit-warning-box">
        <i class="fa fa-exclamation-triangle fa-lg text-warning mt-1"></i>
        <div>
            <strong>Atención de Control y Auditoría:</strong> La anulación de un cobro reversará de forma inmediata los montos cobrados a las cuentas a cobrar de las cuotas correspondientes, restableciendo su saldo pendiente como <b>deuda activa</b>, y registrará un movimiento de <b>Salida de Efectivo</b> en la caja abierta actual.
        </div>
    </div>

    <!-- SEARCH & RECENT PAYMENTS GRID -->
    <div class="row">
        <!-- Columna 1: Buscador -->
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="dash-card h-100">
                <div class="dash-card-header">
                    <h5 class="dash-card-title font-cairo">
                        <i class="fa fa-search text-danger"></i>
                        <span>Buscar Cobro por Número</span>
                    </h5>
                    <small class="text-muted">Cód. Cobro (cc_numero)</small>
                </div>
                <div class="dash-card-body d-flex flex-column justify-content-between">
                    <div>
                        <label class="font-weight-bold text-muted small mb-2 text-uppercase">
                            Número de Operación de Cobro:
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

        <!-- Columna 2: Cobros Recientes -->
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="dash-card h-100">
                <div class="dash-card-header">
                    <h5 class="dash-card-title font-cairo">
                        <i class="fa fa-history text-muted"></i>
                        <span>Cobros Recientes Registrados</span>
                    </h5>
                    <small class="text-muted">Clic para cargar</small>
                </div>
                <div class="dash-card-body p-2">
                    <div class="recent-list" v-if="cobrosRecientes.length > 0">
                        <div v-for="c in cobrosRecientes"
                             :key="c.cc_numero"
                             class="recent-row"
                             :class="{ 'selected': cobroCabecera.nro == c.cc_numero }"
                             @click="seleccionarCobro(c.cc_numero)">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge-id">#@{{ c.cc_numero }}</span>
                                <div>
                                    <div class="font-weight-bold" style="font-size: 0.88rem;">
                                        @{{ (c.cliente_nombre || 'Cliente').trim() }}
                                    </div>
                                    <small class="text-muted">
                                        <i class="fa fa-calendar-alt mr-1"></i>@{{ formatFecha(c.cob_fecha) }}
                                        <span class="mx-1">•</span>
                                        Recibo: <b>@{{ formatRecibo(c) }}</b>
                                    </small>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-weight-bold text-dark dark:text-white font-cairo" style="font-size: 0.95rem;">
                                    @{{ formatGs(c.cob_importe) }} Gs.
                                </div>
                                <span class="badge badge-light border text-muted" style="font-size: 0.72rem;">
                                    Cargar <i class="fa fa-arrow-right ml-1"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center py-4 text-muted small">
                        <i class="fa fa-inbox fa-2x mb-2 text-muted" style="opacity: 0.3;"></i>
                        <div>No hay cobros registrados recientemente</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- COBRO DETALLES & ACCIONES (VISIBLE SOLO CUANDO HAY UN COBRO CARGADO) -->
    <div v-if="cobroCabecera.nro" class="dash-card mt-2">
        <div class="dash-card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge badge-danger p-2 font-cairo" style="font-size: 0.9rem;">
                    <i class="fa fa-receipt mr-1"></i>Cobro #@{{ cobroCabecera.nro }}
                </span>
                <span class="badge-recibo">
                    Recibo: @{{ formatRecibo(cobroCabecera) }}
                </span>
                <span class="header-badge">
                    <i class="fa fa-calendar-day text-info"></i>
                    <span>Fecha: @{{ cobroCabecera.fecha }}</span>
                </span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a :href="'documento/recibocobro/' + cobroCabecera.nro"
                   target="_blank"
                   class="btn-pos-secondary font-cairo"
                   title="Ver comprobante original en PDF">
                    <i class="fa fa-print"></i>
                    <span>Ver Recibo Original</span>
                </a>
                <button class="btn-pos-secondary" @click="cancelar" title="Cerrar cobro actual">
                    <i class="fa fa-times"></i>
                    <span>Cerrar</span>
                </button>
            </div>
        </div>

        <div class="dash-card-body">
            <!-- Metadata Grid -->
            <div class="meta-grid">
                <!-- Cliente -->
                <div class="meta-tile">
                    <div class="meta-label">
                        <i class="fa fa-user text-primary"></i>
                        <span>Cliente / Titular</span>
                    </div>
                    <div class="meta-value">@{{ cobroCabecera.clienteNombre }}</div>
                </div>

                <!-- C.I. / R.U.C. -->
                <div class="meta-tile">
                    <div class="meta-label">
                        <i class="fa fa-id-card text-info"></i>
                        <span>C.I. / R.U.C.</span>
                    </div>
                    <div class="meta-value">@{{ cobroCabecera.clienteId || '—' }}</div>
                </div>

                <!-- Dirección -->
                <div class="meta-tile">
                    <div class="meta-label">
                        <i class="fa fa-map-marker-alt text-danger"></i>
                        <span>Dirección</span>
                    </div>
                    <div class="meta-value">@{{ cobroCabecera.direccion || 'No especificada' }}</div>
                </div>

                <!-- Sucursal / Operación -->
                <div class="meta-tile">
                    <div class="meta-label">
                        <i class="fa fa-store text-success"></i>
                        <span>Sucursal y Operación</span>
                    </div>
                    <div class="meta-value">
                        Sucursal #@{{ idSucursal }} / Op. #@{{ nrooperacion || cobroCabecera.nro_operacion || '—' }}
                    </div>
                </div>
            </div>

            <!-- TOTAL FINANCIAL HIGHLIGHT BOX -->
            <div class="total-box-highlight">
                <div>
                    <div class="total-box-label">
                        <i class="fa fa-money-bill-wave mr-1"></i>Importe Total del Cobro a Anular
                    </div>
                    <div class="total-box-amount font-cairo">
                        @{{ formatGs(cobroCabecera.total) }} Gs.
                    </div>
                    <div class="total-box-desc">
                        Este monto será debitado de la caja como movimiento de salida y restaurado a las cuotas.
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button class="btn-pos-danger font-cairo"
                            :disabled="anulando"
                            @click="anular">
                        <i class="fa fa-spinner fa-spin" v-if="anulando"></i>
                        <i class="fa fa-ban" v-else></i>
                        <span>@{{ anulando ? 'Anulando cobro...' : 'Anular Cobro Definitivamente' }}</span>
                    </button>
                    <button class="btn-pos-secondary"
                            :disabled="anulando"
                            @click="cancelar">
                        <i class="fa fa-times"></i>
                        <span>Cancelar</span>
                    </button>
                </div>
            </div>

            <!-- DETALLE DE CUOTAS AFECTADAS -->
            <div class="mb-2 d-flex align-items-center justify-content-between">
                <h6 class="font-cairo mb-0 text-dark dark:text-white" style="font-weight: 700;">
                    <i class="fa fa-list-ol text-primary mr-1"></i>
                    <span>Cuotas Afectadas por este Cobro (@{{ cuotas.length }})</span>
                </h6>
                <small class="text-muted">Desglose de valores que volverán al saldo de la cuenta por cobrar</small>
            </div>

            <div class="table-responsive border rounded-lg">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th style="width: 130px;">Venta / Comprobante</th>
                            <th style="width: 110px;" class="text-center">Cuota Nro.</th>
                            <th style="width: 140px;">Vencimiento</th>
                            <th class="text-right">Monto Cuota</th>
                            <th class="text-right text-danger">Cobrado en este Recibo</th>
                            <th class="text-right text-success">Nuevo Saldo tras Anular</th>
                            <th class="text-center" style="width: 150px;">Estado Posterior</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="cuotas.length === 0">
                            <td colspan="7" class="text-center py-4 text-muted">
                                No se encontraron detalles de cuotas para este cobro.
                            </td>
                        </tr>
                        <tr v-for="d in cuotas" :key="d.nro_fact_ventas + '-' + d.nro_cuotas">
                            <!-- Venta -->
                            <td>
                                <span class="badge-id font-weight-bold">Venta #@{{ d.nro_fact_ventas }}</span>
                                <div class="small text-muted mt-1" v-if="d.documento">
                                    @{{ d.documento }}
                                </div>
                            </td>

                            <!-- Nro Cuota -->
                            <td class="text-center">
                                <span class="badge badge-light border font-weight-bold" style="font-size: 0.82rem;">
                                    Cuota @{{ d.nro_cuotas }}
                                </span>
                            </td>

                            <!-- Vencimiento -->
                            <td>
                                <span class="small font-weight-bold">
                                    <i class="fa fa-calendar-alt text-muted mr-1"></i>@{{ formatFecha(d.fecha_venc) }}
                                </span>
                            </td>

                            <!-- Monto Cuota -->
                            <td class="text-right font-weight-bold">
                                @{{ formatGs(d.monto_cuota || d.importe) }} Gs.
                            </td>

                            <!-- Cobrado en este recibo -->
                            <td class="text-right">
                                <span class="badge-revert">
                                    <i class="fa fa-undo-alt"></i>
                                    <span>-@{{ formatGs(d.cobrado) }} Gs.</span>
                                </span>
                            </td>

                            <!-- Nuevo saldo calculado -->
                            <td class="text-right font-weight-bold text-dark dark:text-white">
                                @{{ formatGs(calcularNuevoSaldo(d)) }} Gs.
                            </td>

                            <!-- Estado Posterior -->
                            <td class="text-center">
                                <span class="badge-quota-restored">
                                    <i class="fa fa-clock"></i>
                                    <span>Restaurada (Pendiente)</span>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- EMPTY / IDLE STATE (CUANDO NO HAY COBRO SELECCIONADO) -->
    <div v-else class="dash-card">
        <div class="empty-search-state">
            <i class="fa fa-receipt fa-4x mb-3 text-muted" style="opacity: 0.25;"></i>
            <h5 class="font-cairo font-weight-bold text-dark dark:text-white">Ningún Cobro Seleccionado</h5>
            <p class="text-muted small mx-auto" style="max-width: 480px;">
                Ingresá el número de cobro en el buscador superior y presioná <b>Enter</b>, o hacé clic directamente en cualquiera de los <b>Cobros Recientes</b> para previsualizar los detalles de auditoría antes de anular.
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

            cobroCabecera: {
                nro: '',
                recibo: '',
                recibon1: '',
                recibon2: '',
                nro_recibo: '',
                fecha: '',
                direccion: '',
                clienteNombre: '',
                clienteId: '',
                total: 0,
                nro_operacion: 0
            },
            cuotas: [],
            cobrosRecientes: {!! json_encode($cobrosRecientes) !!},
            idSucursal: 1,
            nrooperacion: 0
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
            formatRecibo: function(item) {
                if (!item) return '001-001-0000001';
                var n1 = String(item.recibon1 || 1).padStart(3, '0');
                var n2 = String(item.recibon2 || 1).padStart(3, '0');
                var n3 = String(item.nro_recibo || item.nro || 1).padStart(7, '0');
                return n1 + '-' + n2 + '-' + n3;
            },
            calcularNuevoSaldo: function(cuota) {
                var saldoActual = parseFloat(cuota.monto_saldo) || 0;
                var cobrado = parseFloat(cuota.cobrado) || 0;
                return saldoActual + cobrado;
            },
            buscar: function() {
                var self = this;
                var query = (this.txtbuscar || '').trim();
                if (!query) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Ingrese un número',
                        text: 'Debe ingresar el número de cobro a consultar.',
                        confirmButtonColor: '#e11d48'
                    });
                    return;
                }

                if (this.requestSend) return;
                this.requestSend = true;

                axios.get('cobro/' + query)
                    .then(function(res) {
                        self.requestSend = false;
                        if (!res.data.cobro || res.data.cobro.length < 1) {
                            Swal.fire({
                                icon: 'info',
                                title: 'No encontrado',
                                text: 'No se encontró ningún cobro registrado con el número: ' + query,
                                confirmButtonColor: '#e11d48'
                            });
                            self.cancelar();
                            return;
                        }

                        var cobro = res.data.cobro[0];
                        self.cuotas = res.data.detalle || [];
                        self.cobroCabecera.nro = cobro.cc_numero || query;
                        self.cobroCabecera.recibon1 = cobro.recibon1;
                        self.cobroCabecera.recibon2 = cobro.recibon2;
                        self.cobroCabecera.nro_recibo = cobro.nro_recibo;
                        self.cobroCabecera.fecha = self.formatFecha(cobro.cob_fecha);
                        self.cobroCabecera.direccion = (cobro.cliente_direccion || '').trim();
                        self.cobroCabecera.clienteId = (cobro.cliente_ci || '').trim();
                        self.cobroCabecera.clienteNombre = (cobro.cliente_nombre || '').trim();
                        self.cobroCabecera.total = parseFloat(cobro.cob_importe) || 0;
                        self.cobroCabecera.nro_operacion = cobro.nro_operacion || 0;

                        var Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'Cobro #' + self.cobroCabecera.nro + ' cargado'
                        });
                    })
                    .catch(function(err) {
                        self.requestSend = false;
                        console.error('Error al buscar cobro:', err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de búsqueda',
                            text: 'No fue posible consultar el cobro. Verifique la conexión o el código ingresado.',
                            confirmButtonColor: '#e11d48'
                        });
                    });
            },
            seleccionarCobro: function(id) {
                this.txtbuscar = String(id);
                this.buscar();
            },
            anular: function() {
                var self = this;
                if (!this.cobroCabecera.nro || this.cobroCabecera.total <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sin cobro cargado',
                        text: 'Seleccione o busque un cobro válido antes de anular.',
                        confirmButtonColor: '#e11d48'
                    });
                    return;
                }

                Swal.fire({
                    title: '¿Confirmar Anulación de Cobro?',
                    html: '<div class="text-left" style="font-size: 0.95rem;">' +
                          '<p>Está a punto de <b>anular definitivamente</b> el siguiente cobro:</p>' +
                          '<ul class="list-unstyled p-2 rounded bg-light border">' +
                          '<li><b>Cobro Nro:</b> #' + self.cobroCabecera.nro + '</li>' +
                          '<li><b>Recibo:</b> ' + self.formatRecibo(self.cobroCabecera) + '</li>' +
                          '<li><b>Cliente:</b> ' + self.cobroCabecera.clienteNombre + '</li>' +
                          '<li><b>Monto a Reversar:</b> <span class="text-danger font-weight-bold">' + self.formatGs(self.cobroCabecera.total) + ' Gs.</span></li>' +
                          '<li><b>Cuotas afectadas:</b> ' + self.cuotas.length + ' cuota(s)</li>' +
                          '</ul>' +
                          '<small class="text-danger"><b>Consecuencias:</b> Se restaurará la deuda en las cuentas por cobrar y se generará una salida de efectivo en la caja activa.</small>' +
                          '</div>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="fa fa-ban"></i> Sí, anular cobro',
                    cancelButtonText: 'Cancelar'
                }).then(function(result) {
                    if (result.value) {
                        self.anulando = true;
                        axios.post('anular_cobro', {
                            id: self.cobroCabecera.nro,
                            monto: self.cobroCabecera.total,
                            idSucursal: self.idSucursal,
                            nrooperacion: self.nrooperacion,
                            cuotas: self.cuotas
                        })
                        .then(function(res) {
                            self.anulando = false;
                            Swal.fire({
                                icon: 'success',
                                title: '¡Cobro Anulado!',
                                html: 'El cobro <b>#' + self.cobroCabecera.nro + '</b> fue anulado con éxito.<br>Los saldos de las cuotas fueron restablecidos y se registró la salida en caja.',
                                confirmButtonColor: '#0a4d36'
                            });

                            self.cancelar();
                            self.recargarCobrosRecientes();
                        })
                        .catch(function(err) {
                            self.anulando = false;
                            var msg = err.response && err.response.data && err.response.data.message
                                ? err.response.data.message
                                : 'Ocurrió un error al procesar la anulación del cobro.';
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
                this.cobroCabecera = {
                    nro: '',
                    recibo: '',
                    recibon1: '',
                    recibon2: '',
                    nro_recibo: '',
                    fecha: '',
                    direccion: '',
                    clienteNombre: '',
                    clienteId: '',
                    total: 0,
                    nro_operacion: 0
                };
                this.cuotas = [];
                this.txtbuscar = '';
            },
            recargarCobrosRecientes: function() {
                var self = this;
                this.cargandoRecientes = true;
                axios.get('cobros_recientes')
                    .then(function(res) {
                        self.cobrosRecientes = res.data || [];
                        self.cargandoRecientes = false;
                    })
                    .catch(function(err) {
                        self.cargandoRecientes = false;
                        console.error('Error al recargar cobros recientes:', err);
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

    activarMenu('m_anular', 'm_acobro');
</script>
@endsection