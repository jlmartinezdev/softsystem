@extends('layouts.app')
@section('title', 'Presupuestos de Venta')

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

    [v-cloak] {
        display: none !important;
    }

    #app {
        color: var(--dash-text-main);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    /* Header & Navigation Pills */
    .view-topbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        padding-bottom: 0.85rem;
        border-bottom: 1px solid var(--dash-border);
    }
    .view-title-box h3 {
        font-size: 1.45rem;
        font-weight: 800;
        margin: 0;
        color: var(--dash-text-main);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .view-title-box p {
        font-size: 0.85rem;
        color: var(--dash-text-muted);
        margin: 0;
    }

    .nav-pills-custom {
        display: inline-flex;
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        border-radius: 999px;
        padding: 4px;
        gap: 4px;
    }
    .nav-pill-btn {
        border: none;
        background: transparent;
        color: var(--dash-text-muted);
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.45rem 1.15rem;
        border-radius: 999px;
        cursor: pointer;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
    }
    .nav-pill-btn.active {
        background: var(--dash-primary);
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(10, 77, 54, 0.25);
    }

    .badge-branch {
        background: var(--dash-primary-light);
        border: 1px solid var(--dash-primary-border);
        color: var(--dash-primary);
        font-size: 0.82rem;
        font-weight: 700;
        padding: 0.35rem 0.8rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    /* Cards */
    .card-modern {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        padding: 1.15rem;
        margin-bottom: 1.15rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        transition: all 0.2s ease;
    }
    .card-modern:hover {
        border-color: rgba(10, 77, 54, 0.35);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    .card-modern-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--dash-text-main);
        margin-bottom: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Quick Chips for Validity */
    .chip-btn {
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        font-size: 0.78rem;
        font-weight: 700;
        padding: 0.25rem 0.65rem;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .chip-btn:hover,
    .chip-btn.active {
        background: var(--dash-primary-light);
        border-color: var(--dash-primary);
        color: var(--dash-primary);
    }

    /* Cliente Picker */
    .cliente-picker {
        position: relative;
    }
    .cliente-picker-trigger {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        border-radius: 10px;
        padding: 0.65rem 0.9rem;
        font-size: 0.9rem;
        color: var(--dash-text-main);
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .cliente-picker-trigger:hover,
    .cliente-picker-trigger.open {
        border-color: var(--dash-primary);
        background: var(--dash-card-bg);
    }
    .cliente-card-selected {
        background: var(--dash-primary-light);
        border: 1px solid var(--dash-primary-border);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }
    .cliente-card-info h6 {
        margin: 0;
        font-weight: 800;
        font-size: 0.95rem;
        color: var(--dash-primary);
    }
    .cliente-card-info p {
        margin: 0.15rem 0 0 0;
        font-size: 0.8rem;
        color: var(--dash-text-muted);
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
    }
    .cliente-picker-list {
        max-height: 220px;
        overflow-y: auto;
    }
    .cliente-picker-item {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem 0.75rem;
        border: none;
        background: transparent;
        color: var(--dash-text-main);
        border-radius: 8px;
        cursor: pointer;
        text-align: left;
        font-size: 0.85rem;
        transition: background 0.12s ease;
    }
    .cliente-picker-item:hover {
        background: var(--dash-panel-bg);
    }

    /* Buscador Catalogo override styling */
    .pres-scan .buscador-catalogo .buscador-navbar {
        background: var(--dash-card-bg) !important;
        border: 1px solid var(--dash-border) !important;
        border-radius: 12px !important;
        padding: 0.4rem 0.65rem !important;
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 0.5rem;
    }
    .pres-scan .buscador-catalogo .navbar-nav.w-100 {
        flex: 1 1 auto !important;
        margin: 0 !important;
    }
    .pres-scan .buscador-catalogo .navbar-nav.w-100 > li {
        width: 100% !important;
    }
    .pres-scan .buscador-catalogo .autocomplete-input {
        border-radius: 10px !important;
        border: 1px solid var(--dash-border) !important;
        background-color: var(--dash-panel-bg) !important;
        color: var(--dash-text-main) !important;
        font-size: 0.92rem;
        padding: 0.5rem 1rem 0.5rem 2.6rem !important;
    }
    .pres-scan .buscador-catalogo .autocomplete-input:focus {
        border-color: var(--dash-primary) !important;
        background-color: var(--dash-card-bg) !important;
    }
    .pres-scan .buscador-catalogo .navbar-nav.flex-row {
        flex: 0 0 auto !important;
        display: flex !important;
        gap: 0.4rem;
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
    }
    .pres-scan .buscador-catalogo .nav-link[title*="Catálogo con im"],
    .pres-scan .buscador-catalogo .nav-link[title*="Catalogo con im"],
    .pres-scan .buscador-catalogo a[title*="Catálogo con im"],
    .pres-scan .buscador-catalogo a[title*="Catalogo con im"],
    .pres-scan .buscador-catalogo a[title*="imágenes"],
    .pres-scan .buscador-catalogo a[title*="imagenes"],
    .pres-scan .buscador-catalogo .navbar-nav.flex-row > li > a:not(.pres-atajo) {
        display: none !important;
    }
    .pres-scan .buscador-catalogo .navbar-nav.flex-row > li:has(> a:not(.pres-atajo)) {
        display: none !important;
    }
    .pres-atajo {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.45rem 0.8rem !important;
        height: 38px;
        border-radius: 8px;
        background: var(--dash-primary-light);
        border: 1px solid var(--dash-primary-border);
        color: var(--dash-primary) !important;
        font-weight: 700;
        font-size: 0.82rem;
        text-decoration: none !important;
        transition: all 0.15s ease;
    }
    .pres-atajo:hover {
        background: var(--dash-primary);
        color: #ffffff !important;
        transform: translateY(-1px);
    }
    .pres-atajo-sec {
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        color: var(--dash-text-main) !important;
    }
    .pres-atajo-sec:hover {
        background: var(--dash-primary-light);
        color: var(--dash-primary) !important;
    }

    /* Ticket / Items Table */
    .table-ticket {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }
    .table-ticket th {
        background: var(--dash-panel-bg);
        color: var(--dash-text-muted);
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 0.65rem 0.75rem;
        border-top: 1px solid var(--dash-border);
        border-bottom: 1px solid var(--dash-border);
    }
    .table-ticket td {
        padding: 0.65rem 0.75rem;
        border-bottom: 1px solid var(--dash-border);
        vertical-align: middle;
        font-size: 0.87rem;
    }
    .table-ticket tr:hover td {
        background: var(--dash-panel-bg);
    }

    .qty-stepper {
        display: inline-flex;
        align-items: center;
        border: 1px solid var(--dash-border);
        border-radius: 8px;
        overflow: hidden;
        background: var(--dash-card-bg);
    }
    .qty-stepper button {
        border: none;
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        padding: 0.25rem 0.5rem;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.12s;
    }
    .qty-stepper button:hover {
        background: var(--dash-border);
    }
    .qty-stepper input {
        width: 55px;
        border: none;
        text-align: center;
        font-weight: 700;
        font-size: 0.88rem;
        background: transparent;
        color: var(--dash-text-main);
    }
    .qty-stepper input:focus {
        outline: none;
    }

    .input-table-money {
        width: 105px;
        border: 1px solid var(--dash-border);
        border-radius: 6px;
        padding: 0.25rem 0.45rem;
        font-size: 0.88rem;
        font-weight: 600;
        text-align: right;
        background: var(--dash-card-bg);
        color: var(--dash-text-main);
    }
    .input-table-money:focus {
        border-color: var(--dash-primary);
        outline: none;
    }

    .badge-iva {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 6px;
        display: inline-block;
    }
    .badge-iva-10 { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .badge-iva-5 { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .badge-iva-0 { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

    /* Sticky Summary Box */
    .summary-card {
        background: var(--dash-card-bg);
        border: 2px solid var(--dash-border);
        border-radius: 16px;
        padding: 1.25rem;
        position: sticky;
        top: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    .summary-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--dash-text-main);
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--dash-border);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.35rem 0;
        font-size: 0.9rem;
        color: var(--dash-text-muted);
    }
    .summary-row strong {
        color: var(--dash-text-main);
    }
    .summary-tax-box {
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        border-radius: 10px;
        padding: 0.65rem 0.85rem;
        margin: 0.85rem 0;
        font-size: 0.8rem;
    }
    .summary-grand-total {
        background: var(--dash-primary-light);
        border: 1.5px solid var(--dash-primary-border);
        border-radius: 12px;
        padding: 0.85rem 1rem;
        margin-top: 1rem;
        margin-bottom: 1.2rem;
        text-align: right;
    }
    .summary-grand-total .label {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--dash-primary);
        margin-bottom: 2px;
    }
    .summary-grand-total .amount {
        font-size: 1.85rem;
        font-weight: 900;
        color: var(--dash-primary);
        line-height: 1.1;
    }

    .btn-action-primary {
        background: #10b981;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        padding: 0.75rem 1.25rem;
        font-weight: 800;
        font-size: 0.95rem;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.15s ease;
        margin-bottom: 0.5rem;
    }
    .btn-action-primary:hover {
        background: #059669;
        transform: translateY(-1px);
        color: #ffffff;
    }
    .btn-action-secondary {
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        border: 1px solid var(--dash-border);
        border-radius: 10px;
        padding: 0.65rem 1.25rem;
        font-weight: 700;
        font-size: 0.9rem;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.15s ease;
        margin-bottom: 0.5rem;
    }
    .btn-action-secondary:hover {
        background: var(--dash-card-bg);
        border-color: var(--dash-primary);
        color: var(--dash-primary);
    }

    /* KPI Summary Cards in History Tab */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.25rem;
    }
    .kpi-tile {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 0.9rem;
    }
    .kpi-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .kpi-info .kpi-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--dash-text-muted);
        text-transform: uppercase;
        margin-bottom: 2px;
    }
    .kpi-info .kpi-value {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--dash-text-main);
        line-height: 1.2;
    }

    /* Status Pills */
    .status-badge {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-transform: uppercase;
    }
    .status-badge.status-PENDIENTE { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .status-badge.status-APROBADO { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .status-badge.status-FACTURADO { background: #e0f2fe; color: #075985; border: 1px solid #bae6fd; }
    .status-badge.status-RECHAZADO { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    .status-badge.status-ANULADO { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

    /* Modals */
    .modal-moderno {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 16px;
        color: var(--dash-text-main);
        overflow: hidden;
    }
    .modal-moderno .modal-header {
        border-bottom: 1px solid var(--dash-border);
        padding: 1rem 1.25rem;
    }
    .modal-moderno .modal-footer {
        border-top: 1px solid var(--dash-border);
        padding: 0.85rem 1.25rem;
    }
    .modal-moderno-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: var(--dash-primary-light);
        color: var(--dash-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-right: 0.75rem;
    }
</style>
@endsection

@section('main')
<div id="app" v-cloak class="container-fluid py-3">

    <!-- Topbar Navigation -->
    <div class="view-topbar">
        <div class="view-title-box">
            <h3 class="font-cairo">
                <i class="fas fa-file-invoice-dollar text-success"></i> Presupuestos de Venta
            </h3>
            <p>Generá cotizaciones detalladas con validez, cálculo automático de IVA y conversión a venta.</p>
        </div>

        <div class="d-flex align-items-center flex-wrap gap-2">
            <!-- Tabs -->
            <div class="nav-pills-custom mr-3">
                <button type="button" class="nav-pill-btn" :class="{ active: vistaActiva === 'crear' }" @click="vistaActiva = 'crear'">
                    <i class="fas fa-plus-circle"></i> Nuevo Presupuesto
                </button>
                <button type="button" class="nav-pill-btn" :class="{ active: vistaActiva === 'historial' }" @click="activarHistorial">
                    <i class="fas fa-history"></i> Historial Emitidos
                    <span class="badge badge-light ml-1" v-if="historialTotal > 0">@{{ historialTotal }}</span>
                </button>
            </div>

            <!-- Branch info -->
            <span class="badge-branch">
                <i class="fas fa-warehouse text-warning"></i>
                <span>{{ $empresa->emp_nombre ?? 'Sucursal' }}</span>
            </span>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 1: CREAR NUEVO PRESUPUESTO            -->
    <!-- ========================================== -->
    <div v-show="vistaActiva === 'crear'" class="row">
        <!-- Main Form Column (Left) -->
        <div class="col-lg-8">
            <!-- Card 1: Datos Generales y Cliente -->
            <div class="card-modern">
                <div class="card-modern-title">
                    <span><i class="fas fa-info-circle text-primary mr-1"></i> Configuración de Cotización y Cliente</span>
                    <span class="text-muted small">Nº asignado automáticamente</span>
                </div>

                <div class="row">
                    <!-- Fecha de Emisión -->
                    <div class="col-md-3 col-6 mb-2">
                        <label class="small font-weight-bold text-muted mb-1">Fecha de Emisión</label>
                        <input type="date" class="form-control form-control-sm" v-model="cabecera.fecha" @change="recalcularVencimiento">
                    </div>

                    <!-- Validez en Días -->
                    <div class="col-md-5 col-6 mb-2">
                        <label class="small font-weight-bold text-muted mb-1">
                            Validez: <strong class="text-primary">@{{ cabecera.validez_dias }} días</strong>
                            <small class="text-muted">(Vence: @{{ fechaVencimientoFormato }})</small>
                        </label>
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="chip-btn" :class="{ active: cabecera.validez_dias === 7 }" @click="setValidez(7)">7d</button>
                            <button type="button" class="chip-btn" :class="{ active: cabecera.validez_dias === 15 }" @click="setValidez(15)">15d</button>
                            <button type="button" class="chip-btn" :class="{ active: cabecera.validez_dias === 30 }" @click="setValidez(30)">30d</button>
                            <button type="button" class="chip-btn" :class="{ active: cabecera.validez_dias === 60 }" @click="setValidez(60)">60d</button>
                            <input type="number" min="1" max="365" class="form-control form-control-sm ml-1" style="width: 70px;" v-model.number="cabecera.validez_dias" @input="recalcularVencimiento">
                        </div>
                    </div>

                    <!-- Condición Comercial -->
                    <div class="col-md-4 col-12 mb-2">
                        <label class="small font-weight-bold text-muted mb-1">Condición Comercial</label>
                        <div class="btn-group btn-group-sm w-100" role="group">
                            <button type="button" class="btn" :class="cabecera.condicion === 1 ? 'btn-success font-weight-bold' : 'btn-outline-secondary'" @click="cabecera.condicion = 1">
                                <i class="fas fa-money-bill-wave mr-1"></i> Contado
                            </button>
                            <button type="button" class="btn" :class="cabecera.condicion === 2 ? 'btn-warning font-weight-bold' : 'btn-outline-secondary'" @click="cabecera.condicion = 2">
                                <i class="fas fa-clock mr-1"></i> Crédito
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Selector de Cliente -->
                <div class="mt-2 pt-2 border-top">
                    <label class="small font-weight-bold text-muted mb-1 d-flex justify-content-between">
                        <span><i class="fas fa-user mr-1 text-primary"></i> Cliente Destinatario</span>
                        <a href="#" class="text-primary font-weight-bold" @click.prevent="abrirModalNuevoCliente">
                            <i class="fas fa-user-plus mr-1"></i> + Registrar Cliente Nuevo
                        </a>
                    </label>

                    <!-- Estado: Cliente Seleccionado -->
                    <div v-if="clienteSeleccionado" class="cliente-card-selected">
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar-circle" style="width: 38px; height: 38px; border-radius: 50%; background: #0a4d36; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="cliente-card-info">
                                <h6>@{{ clienteSeleccionado.cliente_nombre }}</h6>
                                <p>
                                    <span class="mr-2"><strong>Doc/RUC:</strong> @{{ clienteSeleccionado.cliente_ruc || clienteSeleccionado.cliente_ci || 'Sin documento' }}</span>
                                    <span class="mr-2" v-if="clienteSeleccionado.cliente_cel || clienteSeleccionado.cliente_telef">
                                        <i class="fas fa-phone-alt mr-1"></i> @{{ clienteSeleccionado.cliente_cel || clienteSeleccionado.cliente_telef }}
                                    </span>
                                    <span v-if="clienteSeleccionado.cliente_direccion">
                                        <i class="fas fa-map-marker-alt mr-1"></i> @{{ clienteSeleccionado.cliente_direccion }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" @click="quitarCliente" title="Cambiar cliente">
                            <i class="fas fa-times"></i> Quitar
                        </button>
                    </div>

                    <!-- Estado: Buscador Desplegable de Clientes -->
                    <div v-else class="cliente-picker">
                        <div class="cliente-picker-trigger" :class="{ open: clientePicker.abierto }" @click="toggleClientePicker">
                            <span class="text-muted">
                                <i class="fas fa-search mr-1"></i> Escribí el nombre, RUC o documento del cliente...
                            </span>
                            <i class="fas fa-chevron-down text-muted"></i>
                        </div>

                        <!-- Dropdown Panel -->
                        <div v-if="clientePicker.abierto" class="cliente-picker-panel">
                            <div class="cliente-picker-search">
                                <i class="fa fa-search"></i>
                                <input
                                    ref="inputBuscarCliente"
                                    type="text"
                                    v-model="clientePicker.busqueda"
                                    placeholder="Buscar por nombre, RUC, cédula o teléfono..."
                                    @input="onBuscarClienteInput"
                                >
                                <button type="button" class="btn-clear" v-if="clientePicker.busqueda" @click="clientePicker.busqueda = ''; buscarClientes()">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>

                            <div class="cliente-picker-list">
                                <div v-if="clientePicker.cargando" class="text-center py-3 text-muted small">
                                    <i class="fas fa-spinner fa-spin mr-1"></i> Buscando clientes...
                                </div>
                                <div v-else-if="clientePicker.resultados.length === 0" class="text-center py-3 text-muted small">
                                    No se encontraron clientes para "<strong>@{{ clientePicker.busqueda }}</strong>".
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-xs btn-outline-primary" @click="abrirModalNuevoCliente">
                                            + Crear este cliente ahora
                                        </button>
                                    </div>
                                </div>
                                <button
                                    v-else
                                    type="button"
                                    class="cliente-picker-item"
                                    v-for="cli in clientePicker.resultados"
                                    :key="cli.clientes_cod"
                                    @click="seleccionarCliente(cli)"
                                >
                                    <div>
                                        <strong>@{{ cli.cliente_nombre }}</strong>
                                        <div class="small text-muted">RUC/CI: @{{ cli.cliente_ruc || cli.cliente_ci || 'S/D' }} · Tel: @{{ cli.cliente_cel || cli.cliente_telef || 'S/T' }}</div>
                                    </div>
                                    <i class="fas fa-check text-success ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Buscador y Visor de Artículos -->
            <div class="card-modern">
                <div class="card-modern-title mb-2">
                    <span><i class="fas fa-barcode text-success mr-1"></i> Búsqueda y Selección de Productos</span>
                    <span class="badge badge-light font-weight-bold">
                        @{{ carro.length }} @{{ carro.length === 1 ? 'artículo en cotización' : 'artículos en cotización' }}
                    </span>
                </div>

                <div class="pres-scan mb-3">
                    <buscador-catalogo
                        ref="buscador"
                        url="{{ env('APP_APIDB') }}"
                        :idsucursal="cabecera.idSucursal"
                        url-buscar="{{ url('articulo/buscar') }}"
                        url-foto-base="{{ asset('storage/articulos') }}"
                        img-fallback="{{ asset('img/sinimagen.png') }}"
                        route-articulo="{{ route('articulo.cm') }}"
                        validar-lote="false"
                        is-ready-balance="true"
                        precio-field="pre_venta1"
                        modal-id="modalCatalogoPresupuesto"
                        titulo="Catálogo de Productos"
                        scan-placeholder="Escaneá código de barras o buscá producto..."
                        @articulo="addCarrito"
                        @seleccion="agregarDesdeCatalogo"
                    >
                        <template slot="actions-after">
                            <li class="nav-item">
                                <a href="#" class="nav-link pres-atajo pres-atajo-sec" @click.prevent="abrirModalItemLibre" title="Agregar concepto manual o servicio sin código">
                                    <i class="fa fa-bolt" aria-hidden="true"></i> + Servicio / Ítem Libre
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link pres-atajo" @click.prevent="abrirCatalogo" title="Explorar catálogo visual con fotos y stock">
                                    <i class="fa fa-th" aria-hidden="true"></i> Catálogo
                                </a>
                            </li>
                        </template>
                    </buscador-catalogo>
                </div>

                <!-- Tabla de Ítems Cotizados -->
                <div class="table-responsive">
                    <table class="table table-ticket">
                        <thead>
                            <tr>
                                <th style="width: 30px;" class="text-center">#</th>
                                <th>Artículo / Servicio</th>
                                <th style="width: 55px;" class="text-center">IVA</th>
                                <th style="width: 130px;" class="text-center">Cantidad</th>
                                <th style="width: 120px;" class="text-right">Precio Unit. (Gs.)</th>
                                <th style="width: 100px;" class="text-right">Desc. (Gs.)</th>
                                <th style="width: 130px;" class="text-right">Subtotal (Gs.)</th>
                                <th style="width: 35px;" class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="carro.length === 0">
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="fas fa-shopping-basket fa-2x mb-2 text-muted" style="opacity: 0.4;"></i>
                                    <p class="mb-1 font-weight-bold">Aún no hay artículos agregados al presupuesto.</p>
                                    <small>Escaneá un código de barras, buscá por nombre o agregá un servicio manual.</small>
                                </td>
                            </tr>
                            <tr v-for="(item, idx) in carro" :key="item.codigo + '_' + idx">
                                <td class="text-center text-muted font-weight-bold">@{{ idx + 1 }}</td>
                                <td>
                                    <div class="font-weight-bold text-truncate" style="max-width: 260px;">
                                        @{{ item.descripcion }}
                                    </div>
                                    <div class="small text-muted" v-if="item.c_barra && item.c_barra !== 'VARIOS'">
                                        <i class="fas fa-barcode mr-1"></i> @{{ item.c_barra }}
                                    </div>
                                    <div class="small text-info" v-if="item.es_libre">
                                        <i class="fas fa-tag mr-1"></i> Ítem manual / servicio
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge-iva" :class="'badge-iva-' + item.iva">
                                        @{{ item.iva == 0 ? 'Exenta' : item.iva + '%' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="qty-stepper">
                                        <button type="button" @click="cambiarCantidad(item, -1)">-</button>
                                        <input type="number" min="0.1" step="1" v-model.number="item.cantidad" @input="recalcularItem(item)">
                                        <button type="button" @click="cambiarCantidad(item, 1)">+</button>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <input type="number" min="0" step="100" class="input-table-money" v-model.number="item.precio" @input="recalcularItem(item)">
                                </td>
                                <td class="text-right">
                                    <input type="number" min="0" step="100" class="input-table-money" v-model.number="item.descuento" @input="recalcularItem(item)">
                                </td>
                                <td class="text-right font-weight-bold text-success font-cairo" style="font-size: 0.95rem;">
                                    @{{ formatGs(item.subtotal) }}
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-outline-danger border-0" @click="eliminarItem(idx)" title="Eliminar renglón">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top" v-if="carro.length > 0">
                    <span class="small text-muted">
                        <i class="fas fa-calculator mr-1"></i> Precios en Guaraníes con impuestos correspondientes.
                    </span>
                    <button type="button" class="btn btn-sm btn-link text-danger p-0" @click="vaciarTicket">
                        <i class="fas fa-trash mr-1"></i> Vaciar lista de artículos
                    </button>
                </div>
            </div>

            <!-- Card 3: Observaciones y Condiciones Comerciales -->
            <div class="card-modern">
                <div class="card-modern-title mb-2">
                    <span><i class="fas fa-comment-alt text-muted mr-1"></i> Observaciones y Términos Comerciales</span>
                </div>
                <textarea
                    class="form-control"
                    rows="3"
                    v-model="cabecera.observaciones"
                    placeholder="Escribí aquí condiciones de pago, tiempo de entrega, cuentas bancarias para depósito, garantía o notas para el cliente..."
                ></textarea>
            </div>
        </div>

        <!-- Sticky Summary Column (Right) -->
        <div class="col-lg-4">
            <div class="summary-card">
                <div class="summary-title font-cairo">
                    <i class="fas fa-receipt text-success"></i> Resumen de Cotización
                </div>

                <!-- Totales calculados -->
                <div class="summary-row">
                    <span>Cantidad de Ítems:</span>
                    <strong>@{{ totalUnidades }} unidad(es)</strong>
                </div>

                <div class="summary-row">
                    <span>Subtotal Bruto:</span>
                    <strong>Gs. @{{ formatGs(totales.subtotal) }}</strong>
                </div>

                <div class="summary-row">
                    <span>Descuento General:</span>
                    <div style="width: 110px;">
                        <input type="number" min="0" step="500" class="input-table-money w-100" v-model.number="cabecera.descuento" @input="recalcularTotales">
                    </div>
                </div>

                <!-- Liquidación de Impuesto IVA -->
                <div class="summary-tax-box">
                    <div class="font-weight-bold text-muted mb-1 text-uppercase small" style="letter-spacing: 0.5px;">
                        Liquidación Estimada del IVA
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <span>Exenta:</span>
                        <span>Gs. @{{ formatGs(totales.exenta) }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <span>IVA 5% (Liq.):</span>
                        <span>Gs. @{{ formatGs(totales.iva5) }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <span>IVA 10% (Liq.):</span>
                        <span>Gs. @{{ formatGs(totales.iva10) }}</span>
                    </div>
                    <div class="d-flex justify-content-between pt-1 border-top font-weight-bold text-dark">
                        <span>Total Impuesto IVA:</span>
                        <span>Gs. @{{ formatGs(totales.totalIva) }}</span>
                    </div>
                </div>

                <!-- Gran Total -->
                <div class="summary-grand-total">
                    <div class="label font-cairo">Total Presupuestado</div>
                    <div class="amount font-cairo">Gs. @{{ formatGs(totales.total) }}</div>
                </div>

                <!-- Botones de Acción -->
                <button
                    type="button"
                    class="btn-action-primary"
                    :disabled="carro.length === 0 || guardando"
                    @click="guardarPresupuesto(false)"
                >
                    <i class="fas fa-spinner fa-spin" v-if="guardando"></i>
                    <i class="fas fa-save" v-else></i>
                    <span>@{{ guardando ? 'Guardando cotización...' : 'Guardar Presupuesto' }}</span>
                </button>

                <button
                    type="button"
                    class="btn-action-secondary"
                    :disabled="carro.length === 0 || guardando"
                    @click="guardarPresupuesto(true)"
                >
                    <i class="fas fa-print text-primary"></i> Guardar e Imprimir PDF
                </button>

                <button
                    type="button"
                    class="btn btn-sm btn-link text-muted w-100 text-center mt-2"
                    @click="resetearFormulario"
                    :disabled="guardando"
                >
                    <i class="fas fa-undo mr-1"></i> Limpiar formulario
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 2: HISTORIAL DE PRESUPUESTOS          -->
    <!-- ========================================== -->
    <div v-show="vistaActiva === 'historial'">
        <!-- KPI Cards -->
        <div class="kpi-grid">
            <div class="kpi-tile">
                <div class="kpi-icon-box" style="background: #eaf3ef; color: #0a4d36;">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div class="kpi-info">
                    <div class="kpi-label">Total Cotizaciones</div>
                    <div class="kpi-value font-cairo">@{{ historialPaginacion.total || 0 }}</div>
                </div>
            </div>

            <div class="kpi-tile">
                <div class="kpi-icon-box" style="background: #fef3c7; color: #b45309;">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="kpi-info">
                    <div class="kpi-label">Pendientes</div>
                    <div class="kpi-value font-cairo text-warning">@{{ kpiPendientes }}</div>
                </div>
            </div>

            <div class="kpi-tile">
                <div class="kpi-icon-box" style="background: #dcfce7; color: #15803d;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="kpi-info">
                    <div class="kpi-label">Aprobados / Facturados</div>
                    <div class="kpi-value font-cairo text-success">@{{ kpiAprobados }}</div>
                </div>
            </div>

            <div class="kpi-tile">
                <div class="kpi-icon-box" style="background: #e0f2fe; color: #0369a1;">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="kpi-info">
                    <div class="kpi-label">Monto Cotizado (Pág.)</div>
                    <div class="kpi-value font-cairo text-primary" style="font-size: 1.15rem;">
                        Gs. @{{ formatGs(kpiMontoPagina) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros del Historial -->
        <div class="card-modern">
            <div class="row align-items-center">
                <div class="col-md-3 col-6 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Desde</label>
                    <input type="date" class="form-control form-control-sm" v-model="filtrosHistorial.desde" @change="cargarHistorial(1)">
                </div>

                <div class="col-md-3 col-6 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Hasta</label>
                    <input type="date" class="form-control form-control-sm" v-model="filtrosHistorial.hasta" @change="cargarHistorial(1)">
                </div>

                <div class="col-md-3 col-6 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Estado</label>
                    <select class="form-control form-control-sm" v-model="filtrosHistorial.estado" @change="cargarHistorial(1)">
                        <option value="TODOS">Todos los estados</option>
                        <option value="PENDIENTE">PENDIENTE</option>
                        <option value="APROBADO">APROBADO</option>
                        <option value="FACTURADO">FACTURADO</option>
                        <option value="RECHAZADO">RECHAZADO</option>
                        <option value="ANULADO">ANULADO</option>
                    </select>
                </div>

                <div class="col-md-3 col-6 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Buscar (Cliente, RUC, Nº)</label>
                    <div class="input-group input-group-sm">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Buscar cotización..."
                            v-model="filtrosHistorial.q"
                            @keyup.enter="cargarHistorial(1)"
                        >
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" @click="cargarHistorial(1)">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla del Historial -->
        <div class="card-modern p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: var(--dash-panel-bg);">
                        <tr>
                            <th style="width: 80px;">Nº Pres.</th>
                            <th style="width: 140px;">Emisión</th>
                            <th style="width: 130px;">Vencimiento</th>
                            <th>Cliente</th>
                            <th style="width: 120px;">Vendedor</th>
                            <th style="width: 90px;" class="text-center">Condición</th>
                            <th style="width: 110px;" class="text-center">Estado</th>
                            <th style="width: 130px;" class="text-right">Total Gs.</th>
                            <th style="width: 140px;" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="cargandoHistorial">
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fas fa-spinner fa-spin fa-2x mb-2 text-primary"></i>
                                <p class="mb-0">Cargando cotizaciones...</p>
                            </td>
                        </tr>
                        <tr v-else-if="historial.length === 0">
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-2x mb-2" style="opacity: 0.3;"></i>
                                <p class="mb-1 font-weight-bold">No se encontraron presupuestos registrados con estos filtros.</p>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-1" @click="vistaActiva = 'crear'">
                                    + Emitir nueva cotización
                                </button>
                            </td>
                        </tr>
                        <tr v-for="item in historial" :key="item.pre_numero">
                            <td>
                                <strong class="font-cairo text-primary">#@{{ String(item.pre_numero).padStart(6, '0') }}</strong>
                            </td>
                            <td>
                                <div class="font-weight-bold">@{{ formatearFecha(item.pre_fecha) }}</div>
                                <small class="text-muted">@{{ formatearHora(item.pre_fecha) }}</small>
                            </td>
                            <td>
                                <div v-if="item.pre_vencimiento">
                                    <span class="small font-weight-bold" :class="esVencido(item.pre_vencimiento) ? 'text-danger' : 'text-muted'">
                                        @{{ formatearSoloFecha(item.pre_vencimiento) }}
                                    </span>
                                    <div class="small" :class="esVencido(item.pre_vencimiento) ? 'text-danger font-weight-bold' : 'text-muted'">
                                        @{{ diasRestantesTexto(item.pre_vencimiento) }}
                                    </div>
                                </div>
                                <span v-else class="text-muted small">-</span>
                            </td>
                            <td>
                                <div class="font-weight-bold text-truncate" style="max-width: 220px;">
                                    @{{ item.cliente ? item.cliente.cliente_nombre : 'Consumidor Final' }}
                                </div>
                                <div class="small text-muted" v-if="item.cliente">
                                    RUC/CI: @{{ item.cliente.cliente_ruc || item.cliente.cliente_ci || 'S/D' }}
                                </div>
                            </td>
                            <td>
                                <span class="small text-muted">@{{ item.usuario ? item.usuario.nom_usuarios : 'Sistema' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge" :class="item.pre_tipo == 2 ? 'badge-warning' : 'badge-light'">
                                    @{{ item.pre_tipo == 2 ? 'Crédito' : 'Contado' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="status-badge" :class="'status-' + item.estado">
                                    @{{ item.estado }}
                                </span>
                            </td>
                            <td class="text-right font-weight-bold font-cairo text-success">
                                Gs. @{{ formatGs(item.total) }}
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <!-- Ver Detalle -->
                                    <button type="button" class="btn btn-outline-secondary" @click="verDetallePresupuesto(item)" title="Ver detalle de cotización">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- Imprimir PDF -->
                                    <button type="button" class="btn btn-outline-primary" @click="imprimirPresupuesto(item.pre_numero)" title="Imprimir o exportar PDF">
                                        <i class="fas fa-print"></i>
                                    </button>

                                    <!-- Cambiar Estado Dropdown -->
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Cambiar estado comercial">
                                            <i class="fas fa-exchange-alt"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <h6 class="dropdown-header">Cambiar Estado a:</h6>
                                            <a class="dropdown-item text-warning" href="#" @click.prevent="cambiarEstado(item, 'PENDIENTE')">
                                                <i class="fas fa-clock mr-1"></i> PENDIENTE
                                            </a>
                                            <a class="dropdown-item text-success" href="#" @click.prevent="cambiarEstado(item, 'APROBADO')">
                                                <i class="fas fa-check mr-1"></i> APROBADO
                                            </a>
                                            <a class="dropdown-item text-primary" href="#" @click.prevent="cambiarEstado(item, 'FACTURADO')">
                                                <i class="fas fa-receipt mr-1"></i> FACTURADO
                                            </a>
                                            <a class="dropdown-item text-danger" href="#" @click.prevent="cambiarEstado(item, 'RECHAZADO')">
                                                <i class="fas fa-times-circle mr-1"></i> RECHAZADO
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-muted" href="#" @click.prevent="anularPresupuesto(item)">
                                                <i class="fas fa-ban mr-1"></i> ANULAR PRESUPUESTO
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Cargar en Punto de Venta -->
                                    <button type="button" class="btn btn-outline-success" @click="cargarEnVenta(item)" title="Convertir a Venta (Cargar en POS)">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-between align-items-center p-3 border-top" v-if="historialPaginacion.last_page > 1">
                <div class="small text-muted">
                    Mostrando página <strong>@{{ historialPaginacion.current_page }}</strong> de <strong>@{{ historialPaginacion.last_page }}</strong> (@{{ historialPaginacion.total }} cotizaciones)
                </div>
                <div class="btn-group btn-group-sm">
                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        :disabled="historialPaginacion.current_page <= 1"
                        @click="cargarHistorial(historialPaginacion.current_page - 1)"
                    >
                        &laquo; Anterior
                    </button>
                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        :disabled="historialPaginacion.current_page >= historialPaginacion.last_page"
                        @click="cargarHistorial(historialPaginacion.current_page + 1)"
                    >
                        Siguiente &raquo;
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL 1: VER DETALLE DE PRESUPUESTO        -->
    <!-- ========================================== -->
    <div class="modal fade" id="modalDetallePresupuesto" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content modal-moderno" v-if="detalleModal.presupuesto">
                <div class="modal-header d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="modal-moderno-icon">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-weight-bold font-cairo mb-0">
                                Presupuesto #@{{ String(detalleModal.presupuesto.pre_numero).padStart(6, '0') }}
                            </h5>
                            <span class="status-badge" :class="'status-' + detalleModal.presupuesto.estado">
                                @{{ detalleModal.presupuesto.estado }}
                            </span>
                        </div>
                    </div>
                    <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-3">
                    <!-- Resumen Cabecera -->
                    <div class="row bg-light rounded p-2 mb-3 mx-0">
                        <div class="col-md-6 mb-1">
                            <div class="small text-muted font-weight-bold">CLIENTE:</div>
                            <div class="font-weight-bold">@{{ detalleModal.presupuesto.cliente ? detalleModal.presupuesto.cliente.cliente_nombre : 'Consumidor Final' }}</div>
                            <div class="small text-muted" v-if="detalleModal.presupuesto.cliente">
                                RUC/CI: @{{ detalleModal.presupuesto.cliente.cliente_ruc || detalleModal.presupuesto.cliente.cliente_ci || 'S/D' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-1 text-md-right">
                            <div class="small text-muted font-weight-bold">EMISIÓN / VALIDEZ:</div>
                            <div>@{{ formatearFecha(detalleModal.presupuesto.pre_fecha) }} (@{{ detalleModal.presupuesto.pre_validez_dias }} días)</div>
                            <div class="small text-muted" v-if="detalleModal.presupuesto.pre_vencimiento">
                                Vence: @{{ formatearSoloFecha(detalleModal.presupuesto.pre_vencimiento) }}
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Renglones -->
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 30px;" class="text-center">#</th>
                                    <th>Descripción</th>
                                    <th style="width: 50px;" class="text-center">IVA</th>
                                    <th style="width: 60px;" class="text-right">Cant.</th>
                                    <th style="width: 100px;" class="text-right">Precio</th>
                                    <th style="width: 90px;" class="text-right">Desc.</th>
                                    <th style="width: 110px;" class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(det, dIdx) in detalleModal.presupuesto.detalles" :key="dIdx">
                                    <td class="text-center text-muted">@{{ dIdx + 1 }}</td>
                                    <td>
                                        <strong>@{{ det.descripcion_libre || (det.articulo ? det.articulo.producto_nombre : 'Artículo') }}</strong>
                                        <div class="small text-muted" v-if="det.articulo && det.articulo.producto_c_barra">
                                            @{{ det.articulo.producto_c_barra }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-light">
                                            @{{ det.pre_det_exenta > 0 ? 'Exenta' : (det.pre_det_gravada5 > 0 ? '5%' : '10%') }}
                                        </span>
                                    </td>
                                    <td class="text-right font-weight-bold">@{{ Number(det.pre_det_cantidad) }}</td>
                                    <td class="text-right">Gs. @{{ formatGs(det.pre_det_precio) }}</td>
                                    <td class="text-right">Gs. @{{ formatGs(det.pre_det_descuento) }}</td>
                                    <td class="text-right font-weight-bold text-success">Gs. @{{ formatGs(det.pre_det_subtotal) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Totales & IVA Modal -->
                    <div class="d-flex justify-content-between align-items-center mt-2 p-2 bg-light rounded">
                        <div class="small text-muted">
                            <span>Exenta: Gs. @{{ formatGs(detalleModal.presupuesto.total_exenta) }}</span> ·
                            <span>IVA 5%: Gs. @{{ formatGs(detalleModal.presupuesto.total_iva5) }}</span> ·
                            <span>IVA 10%: Gs. @{{ formatGs(detalleModal.presupuesto.total_iva10) }}</span>
                        </div>
                        <div class="text-right">
                            <span class="small font-weight-bold text-muted mr-2">TOTAL PRESUPUESTO:</span>
                            <span class="font-cairo font-weight-bold text-success" style="font-size: 1.25rem;">
                                Gs. @{{ formatGs(detalleModal.presupuesto.total) }}
                            </span>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div v-if="detalleModal.presupuesto.observaciones" class="mt-3 p-2 border rounded bg-white small">
                        <strong>Observaciones:</strong>
                        <p class="mb-0 text-muted" style="white-space: pre-line;">@{{ detalleModal.presupuesto.observaciones }}</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">
                        Cerrar
                    </button>
                    <button type="button" class="btn btn-sm btn-primary" @click="imprimirPresupuesto(detalleModal.presupuesto.pre_numero)">
                        <i class="fas fa-print mr-1"></i> Imprimir Cotización
                    </button>
                    <button type="button" class="btn btn-sm btn-success" @click="cargarEnVenta(detalleModal.presupuesto)">
                        <i class="fas fa-cart-plus mr-1"></i> Convertir a Venta
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL 2: NUEVO CLIENTE RÁPIDO              -->
    <!-- ========================================== -->
    <div class="modal fade" id="modalNuevoClienteRapido" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-moderno">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="modal-moderno-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h5 class="modal-title font-weight-bold font-cairo mb-0">Registrar Nuevo Cliente</h5>
                    </div>
                    <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label class="small font-weight-bold">Nombre Completo o Razón Social <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" v-model="nuevoCliente.nombre" placeholder="Ej: Juan Pérez / Empresa S.A.">
                    </div>

                    <div class="row">
                        <div class="col-6 form-group mb-2">
                            <label class="small font-weight-bold">Cédula o RUC <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" v-model="nuevoCliente.doc" placeholder="Ej: 4567890 o 80012345-6">
                        </div>
                        <div class="col-6 form-group mb-2">
                            <label class="small font-weight-bold">Teléfono / Celular</label>
                            <input type="text" class="form-control form-control-sm" v-model="nuevoCliente.celular" placeholder="Ej: 0981 123456">
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label class="small font-weight-bold">Dirección</label>
                        <input type="text" class="form-control form-control-sm" v-model="nuevoCliente.direccion" placeholder="Ej: Avda. Principal 123">
                    </div>

                    <div class="form-group mb-0">
                        <label class="small font-weight-bold">Correo Electrónico</label>
                        <input type="email" class="form-control form-control-sm" v-model="nuevoCliente.correo" placeholder="Ej: cliente@correo.com">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-sm btn-success" :disabled="guardandoCliente" @click="guardarNuevoCliente">
                        <i class="fas fa-spinner fa-spin" v-if="guardandoCliente"></i>
                        <i class="fas fa-check" v-else></i> Guardar y Seleccionar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL 3: AGREGAR ÍTEM LIBRE / SERVICIO     -->
    <!-- ========================================== -->
    <div class="modal fade" id="modalItemLibre" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-moderno">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="modal-moderno-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h5 class="modal-title font-weight-bold font-cairo mb-0">Agregar Concepto Manual / Servicio</h5>
                    </div>
                    <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label class="small font-weight-bold">Descripción del Concepto / Servicio <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" v-model="itemLibre.descripcion" placeholder="Ej: Servicio de Instalación / Mantenimiento / Flete...">
                    </div>

                    <div class="row">
                        <div class="col-6 form-group mb-2">
                            <label class="small font-weight-bold">Cantidad</label>
                            <input type="number" min="0.1" step="1" class="form-control form-control-sm" v-model.number="itemLibre.cantidad">
                        </div>
                        <div class="col-6 form-group mb-2">
                            <label class="small font-weight-bold">Precio Unitario (Gs.) <span class="text-danger">*</span></label>
                            <input type="number" min="0" step="1000" class="form-control form-control-sm" v-model.number="itemLibre.precio">
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="small font-weight-bold">Tasa de Impuesto IVA</label>
                        <div class="btn-group btn-group-sm w-100" role="group">
                            <button type="button" class="btn" :class="itemLibre.iva === 10 ? 'btn-primary font-weight-bold' : 'btn-outline-secondary'" @click="itemLibre.iva = 10">
                                IVA 10%
                            </button>
                            <button type="button" class="btn" :class="itemLibre.iva === 5 ? 'btn-warning font-weight-bold' : 'btn-outline-secondary'" @click="itemLibre.iva = 5">
                                IVA 5%
                            </button>
                            <button type="button" class="btn" :class="itemLibre.iva === 0 ? 'btn-secondary font-weight-bold' : 'btn-outline-secondary'" @click="itemLibre.iva = 0">
                                Exenta
                            </button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-sm btn-success" @click="agregarItemLibreAlTicket">
                        <i class="fas fa-plus mr-1"></i> Agregar al Presupuesto
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
<script>
    var app = new Vue({
        el: '#app',
        data: {
            vistaActiva: 'crear',
            guardando: false,
            articuloLibreId: {{ $articuloLibreId ?? 1 }},

            // Cabecera de la cotización
            cabecera: {
                idSucursal: {{ $sucursalActual ?? 1 }},
                fecha: '{{ date("Y-m-d") }}',
                validez_dias: 15,
                vencimiento: '',
                condicion: 1, // 1: Contado, 2: Credito
                descuento: 0,
                observaciones: '',
                clienteId: null,
            },

            // Cliente
            clienteSeleccionado: null,
            clientePicker: {
                abierto: false,
                busqueda: '',
                resultados: [],
                cargando: false,
                timer: null,
            },

            // Carrito de items
            carro: [],

            // Modal Nuevo Cliente
            guardandoCliente: false,
            nuevoCliente: {
                nombre: '',
                doc: '',
                celular: '',
                telefono: '',
                direccion: '',
                correo: '',
                idciudad: 1
            },

            // Modal Item Libre
            itemLibre: {
                descripcion: '',
                cantidad: 1,
                precio: 0,
                iva: 10
            },

            // Tab Historial
            cargandoHistorial: false,
            historial: [],
            historialTotal: 0,
            historialPaginacion: {
                current_page: 1,
                last_page: 1,
                total: 0
            },
            filtrosHistorial: {
                desde: '{{ date("Y-m-01") }}',
                hasta: '{{ date("Y-m-d") }}',
                estado: 'TODOS',
                sucursal: '{{ $sucursalActual ?? 1 }}',
                q: ''
            },

            // Modal Detalle
            detalleModal: {
                presupuesto: null
            }
        },
        computed: {
            fechaVencimientoFormato: function () {
                if (!this.cabecera.vencimiento) return '';
                var partes = this.cabecera.vencimiento.split('-');
                if (partes.length === 3) {
                    return partes[2] + '/' + partes[1] + '/' + partes[0];
                }
                return this.cabecera.vencimiento;
            },
            totalUnidades: function () {
                var total = 0;
                this.carro.forEach(function (it) {
                    total += Number(it.cantidad) || 0;
                });
                return total;
            },
            totales: function () {
                var subtotal = 0;
                var exenta = 0;
                var grav5 = 0;
                var grav10 = 0;

                this.carro.forEach(function (item) {
                    var cant = Math.max(0.001, Number(item.cantidad) || 1);
                    var prec = Math.max(0, Number(item.precio) || 0);
                    var desc = Math.max(0, Number(item.descuento) || 0);
                    var sub = Math.max(0, (cant * prec) - desc);

                    subtotal += sub;
                    var iva = Number(item.iva);
                    if (iva === 0) {
                        exenta += sub;
                    } else if (iva === 5) {
                        grav5 += sub;
                    } else {
                        grav10 += sub;
                    }
                });

                var descGral = Math.max(0, Number(this.cabecera.descuento) || 0);
                var total = Math.max(0, subtotal - descGral);

                var iva5Liq = Math.round(grav5 / 21);
                var iva10Liq = Math.round(grav10 / 11);
                var totalIva = iva5Liq + iva10Liq;

                return {
                    subtotal: subtotal,
                    exenta: exenta,
                    grav5: grav5,
                    grav10: grav10,
                    iva5: iva5Liq,
                    iva10: iva10Liq,
                    totalIva: totalIva,
                    total: total
                };
            },
            kpiPendientes: function () {
                return this.historial.filter(function (h) { return h.estado === 'PENDIENTE'; }).length;
            },
            kpiAprobados: function () {
                return this.historial.filter(function (h) { return h.estado === 'APROBADO' || h.estado === 'FACTURADO'; }).length;
            },
            kpiMontoPagina: function () {
                var sum = 0;
                this.historial.forEach(function (h) {
                    if (h.estado !== 'ANULADO') {
                        sum += Number(h.total) || 0;
                    }
                });
                return sum;
            }
        },
        methods: {
            formatGs: function (val) {
                if (isNaN(val) || val === null || val === undefined) return '0';
                return Number(val).toLocaleString('es-PY', { maximumFractionDigits: 0 });
            },
            setValidez: function (dias) {
                this.cabecera.validez_dias = dias;
                this.recalcularVencimiento();
            },
            recalcularVencimiento: function () {
                if (!this.cabecera.fecha) return;
                var f = new Date(this.cabecera.fecha + 'T00:00:00');
                var dias = parseInt(this.cabecera.validez_dias, 10) || 15;
                f.setDate(f.getDate() + dias);

                var yyyy = f.getFullYear();
                var mm = String(f.getMonth() + 1).padStart(2, '0');
                var dd = String(f.getDate()).padStart(2, '0');
                this.cabecera.vencimiento = yyyy + '-' + mm + '-' + dd;
            },

            /* Cliente Picker */
            toggleClientePicker: function () {
                this.clientePicker.abierto = !this.clientePicker.abierto;
                if (this.clientePicker.abierto) {
                    var self = this;
                    this.$nextTick(function () {
                        if (self.$refs.inputBuscarCliente) {
                            self.$refs.inputBuscarCliente.focus();
                        }
                    });
                    this.buscarClientes();
                }
            },
            onBuscarClienteInput: function () {
                var self = this;
                clearTimeout(this.clientePicker.timer);
                this.clientePicker.timer = setTimeout(function () {
                    self.buscarClientes();
                }, 300);
            },
            buscarClientes: function () {
                var self = this;
                this.clientePicker.cargando = true;
                axios.get('{{ url("cliente/buscar") }}', {
                    params: {
                        q: this.clientePicker.busqueda,
                        limit: 30
                    }
                }).then(function (response) {
                    self.clientePicker.resultados = Array.isArray(response.data) ? response.data : [];
                }).catch(function (error) {
                    console.error('Error buscando clientes:', error);
                }).finally(function () {
                    self.clientePicker.cargando = false;
                });
            },
            seleccionarCliente: function (cli) {
                this.clienteSeleccionado = cli;
                this.cabecera.clienteId = cli.clientes_cod || cli.CLIENTES_cod;
                this.clientePicker.abierto = false;
            },
            quitarCliente: function () {
                this.clienteSeleccionado = null;
                this.cabecera.clienteId = null;
            },
            abrirModalNuevoCliente: function () {
                this.nuevoCliente = {
                    nombre: this.clientePicker.busqueda || '',
                    doc: '',
                    celular: '',
                    telefono: '',
                    direccion: '',
                    correo: '',
                    idciudad: 1
                };
                $('#modalNuevoClienteRapido').modal('show');
            },
            guardarNuevoCliente: function () {
                if (!this.nuevoCliente.nombre.trim()) {
                    Swal.fire('Atención', 'Ingresá el nombre del cliente.', 'warning');
                    return;
                }
                if (!this.nuevoCliente.doc.trim()) {
                    Swal.fire('Atención', 'Ingresá el número de documento o RUC.', 'warning');
                    return;
                }

                var self = this;
                this.guardandoCliente = true;

                axios.post('{{ url("cliente") }}', {
                    cliente: {
                        nombre: this.nuevoCliente.nombre,
                        doc: this.nuevoCliente.doc,
                        celular: this.nuevoCliente.celular,
                        telefono: this.nuevoCliente.telefono,
                        direccion: this.nuevoCliente.direccion,
                        correo: this.nuevoCliente.correo,
                        idciudad: this.nuevoCliente.idciudad,
                        celfamiliar: '',
                        ocupacion: '',
                        reflaboral: ''
                    }
                }).then(function (response) {
                    $('#modalNuevoClienteRapido').modal('hide');
                    // Buscar el cliente recién creado para seleccionarlo
                    return axios.get('{{ url("cliente/buscar") }}', {
                        params: { q: self.nuevoCliente.doc }
                    });
                }).then(function (res) {
                    if (res && res.data && res.data.length > 0) {
                        self.seleccionarCliente(res.data[0]);
                    } else {
                        self.seleccionarCliente({
                            cliente_nombre: self.nuevoCliente.nombre,
                            cliente_ruc: self.nuevoCliente.doc,
                            cliente_cel: self.nuevoCliente.celular,
                            cliente_direccion: self.nuevoCliente.direccion
                        });
                    }
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Cliente registrado y seleccionado',
                        showConfirmButton: false,
                        timer: 2500
                    });
                }).catch(function (error) {
                    console.error(error);
                    Swal.fire('Error', 'No se pudo registrar el cliente.', 'error');
                }).finally(function () {
                    self.guardandoCliente = false;
                });
            },

            /* Buscador de Artículos & Catálogo */
            addCarrito: function (articulo) {
                if (!articulo) return;

                var idCod = articulo.ARTICULOS_cod || articulo.articulos_cod;
                var existe = this.carro.find(function (x) {
                    return x.codigo === idCod && !x.es_libre;
                });

                if (existe) {
                    existe.cantidad += 1;
                    this.recalcularItem(existe);
                } else {
                    var precioRef = Number(articulo.pre_venta1 || articulo.precio || 0);
                    var ivaRef = 10;
                    if (articulo.iva !== undefined && articulo.iva !== null) {
                        ivaRef = Number(articulo.iva);
                    }

                    var item = {
                        codigo: idCod,
                        c_barra: articulo.producto_c_barra || '',
                        descripcion: articulo.producto_nombre || 'Artículo',
                        cantidad: 1,
                        precio: precioRef,
                        descuento: 0,
                        iva: ivaRef,
                        es_libre: false,
                        descripcion_libre: null,
                        subtotal: precioRef
                    };
                    this.carro.push(item);
                }

                Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500
                }).fire({
                    icon: 'success',
                    title: 'Artículo agregado a la cotización'
                });
            },
            agregarDesdeCatalogo: function (articulo) {
                this.addCarrito(articulo);
            },
            abrirCatalogo: function () {
                $('#modalCatalogoPresupuesto').modal('show');
            },

            /* Ítem Libre / Servicio */
            abrirModalItemLibre: function () {
                this.itemLibre = {
                    descripcion: '',
                    cantidad: 1,
                    precio: 0,
                    iva: 10
                };
                $('#modalItemLibre').modal('show');
            },
            agregarItemLibreAlTicket: function () {
                if (!this.itemLibre.descripcion.trim()) {
                    Swal.fire('Atención', 'Ingresá la descripción del concepto o servicio.', 'warning');
                    return;
                }

                var cant = Math.max(0.1, Number(this.itemLibre.cantidad) || 1);
                var prec = Math.max(0, Number(this.itemLibre.precio) || 0);

                var item = {
                    codigo: this.articuloLibreId,
                    c_barra: 'VARIOS',
                    descripcion: this.itemLibre.descripcion.trim(),
                    cantidad: cant,
                    precio: prec,
                    descuento: 0,
                    iva: Number(this.itemLibre.iva),
                    es_libre: true,
                    descripcion_libre: this.itemLibre.descripcion.trim(),
                    subtotal: cant * prec
                };

                this.carro.push(item);
                $('#modalItemLibre').modal('hide');

                Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                }).fire({
                    icon: 'success',
                    title: 'Concepto libre agregado'
                });
            },

            /* Manipulación del Ticket */
            cambiarCantidad: function (item, delta) {
                var nueva = (Number(item.cantidad) || 1) + delta;
                if (nueva <= 0) {
                    var idx = this.carro.indexOf(item);
                    if (idx !== -1) {
                        this.eliminarItem(idx);
                    }
                    return;
                }
                item.cantidad = nueva;
                this.recalcularItem(item);
            },
            recalcularItem: function (item) {
                var cant = Math.max(0.001, Number(item.cantidad) || 1);
                var prec = Math.max(0, Number(item.precio) || 0);
                var desc = Math.max(0, Number(item.descuento) || 0);
                item.subtotal = Math.max(0, (cant * prec) - desc);
            },
            eliminarItem: function (index) {
                this.carro.splice(index, 1);
            },
            vaciarTicket: function () {
                var self = this;
                Swal.fire({
                    title: '¿Vaciar cotización?',
                    text: 'Se eliminarán todos los artículos agregados.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, vaciar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#e11d48'
                }).then(function (result) {
                    if (result.value) {
                        self.carro = [];
                        self.cabecera.descuento = 0;
                    }
                });
            },
            recalcularTotales: function () {
                // Computed totales handles reactivity
            },
            resetearFormulario: function () {
                var self = this;
                Swal.fire({
                    title: '¿Limpiar formulario?',
                    text: 'Se restablecerán los datos del presupuesto.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Limpiar',
                    cancelButtonText: 'Continuar editando'
                }).then(function (result) {
                    if (result.value) {
                        self.carro = [];
                        self.cabecera.descuento = 0;
                        self.cabecera.observaciones = '';
                        self.cabecera.condicion = 1;
                        self.cabecera.validez_dias = 15;
                        self.clienteSeleccionado = null;
                        self.cabecera.clienteId = null;
                        self.recalcularVencimiento();
                    }
                });
            },

            /* Guardar Presupuesto */
            guardarPresupuesto: function (imprimir) {
                if (this.carro.length === 0) {
                    Swal.fire('Atención', 'Agregá al menos un artículo o servicio para presupuestar.', 'warning');
                    return;
                }

                var self = this;
                this.guardando = true;

                var payload = {
                    cabecera: {
                        idSucursal: this.cabecera.idSucursal,
                        fecha: this.cabecera.fecha,
                        validez_dias: this.cabecera.validez_dias,
                        vencimiento: this.cabecera.vencimiento,
                        condicion: this.cabecera.condicion,
                        descuento: this.cabecera.descuento,
                        observaciones: this.cabecera.observaciones,
                        clienteId: this.cabecera.clienteId
                    },
                    detalle: this.carro.map(function (it) {
                        return {
                            codigo: it.codigo,
                            cantidad: it.cantidad,
                            precio: it.precio,
                            descuento: it.descuento,
                            iva: it.iva,
                            es_libre: it.es_libre,
                            descripcion_libre: it.descripcion_libre
                        };
                    })
                };

                axios.post('{{ route("presupuesto.store") }}', payload)
                    .then(function (response) {
                        if (response.data && response.data.success) {
                            var nro = response.data.pre_numero;

                            Swal.fire({
                                icon: 'success',
                                title: '¡Presupuesto Nº ' + nro + ' generado!',
                                text: 'La cotización comercial ha sido guardada exitosamente.',
                                confirmButtonText: 'Aceptar',
                                confirmButtonColor: '#0a4d36'
                            }).then(function () {
                                if (imprimir) {
                                    window.open('{{ url("presupuesto") }}/' + nro + '/pdf?autoprint=1', '_blank');
                                }
                                // Reset
                                self.carro = [];
                                self.cabecera.descuento = 0;
                                self.cabecera.observaciones = '';
                                self.clienteSeleccionado = null;
                                self.cabecera.clienteId = null;
                                self.recalcularVencimiento();
                                // Recargar historial
                                self.cargarHistorial(1);
                            });
                        } else {
                            Swal.fire('Error', response.data.message || 'No se pudo guardar el presupuesto.', 'error');
                        }
                    })
                    .catch(function (error) {
                        console.error('Error al guardar presupuesto:', error);
                        var msg = (error.response && error.response.data && error.response.data.message)
                            ? error.response.data.message
                            : 'Ocurrió un error al procesar el presupuesto.';
                        Swal.fire('Error', msg, 'error');
                    })
                    .finally(function () {
                        self.guardando = false;
                    });
            },

            /* Tab Historial */
            activarHistorial: function () {
                this.vistaActiva = 'historial';
                this.cargarHistorial(1);
            },
            cargarHistorial: function (page) {
                var self = this;
                this.cargandoHistorial = true;
                var p = page || 1;

                axios.get('{{ route("presupuesto.index") }}', {
                    params: {
                        page: p,
                        desde: this.filtrosHistorial.desde,
                        hasta: this.filtrosHistorial.hasta,
                        estado: this.filtrosHistorial.estado,
                        sucursal: this.filtrosHistorial.sucursal,
                        q: this.filtrosHistorial.q
                    }
                }).then(function (response) {
                    if (response.data) {
                        self.historial = response.data.data || [];
                        self.historialTotal = response.data.total || 0;
                        self.historialPaginacion = {
                            current_page: response.data.current_page || 1,
                            last_page: response.data.last_page || 1,
                            total: response.data.total || 0
                        };
                    }
                }).catch(function (error) {
                    console.error('Error al cargar historial de presupuestos:', error);
                }).finally(function () {
                    self.cargandoHistorial = false;
                });
            },
            formatearFecha: function (dt) {
                if (!dt) return '-';
                var d = new Date(dt.replace(/-/g, '/'));
                if (isNaN(d.getTime())) return dt;
                var dd = String(d.getDate()).padStart(2, '0');
                var mm = String(d.getMonth() + 1).padStart(2, '0');
                var yyyy = d.getFullYear();
                return dd + '/' + mm + '/' + yyyy;
            },
            formatearSoloFecha: function (f) {
                if (!f) return '-';
                var partes = f.split('-');
                if (partes.length === 3) {
                    return partes[2] + '/' + partes[1] + '/' + partes[0];
                }
                return f;
            },
            formatearHora: function (dt) {
                if (!dt) return '';
                var partes = dt.split(' ');
                if (partes.length >= 2) {
                    return partes[1].substring(0, 5) + ' hs';
                }
                return '';
            },
            esVencido: function (venc) {
                if (!venc) return false;
                var hoy = new Date();
                hoy.setHours(0, 0, 0, 0);
                var fVenc = new Date(venc + 'T00:00:00');
                return fVenc < hoy;
            },
            diasRestantesTexto: function (venc) {
                if (!venc) return '';
                var hoy = new Date();
                hoy.setHours(0, 0, 0, 0);
                var fVenc = new Date(venc + 'T00:00:00');
                var diff = Math.ceil((fVenc - hoy) / (1000 * 60 * 60 * 24));
                if (diff < 0) {
                    return 'Vencido hace ' + Math.abs(diff) + 'd';
                } else if (diff === 0) {
                    return 'Vence hoy';
                } else {
                    return 'Quedan ' + diff + ' días';
                }
            },

            verDetallePresupuesto: function (item) {
                var self = this;
                axios.get('{{ url("presupuesto") }}/' + item.pre_numero)
                    .then(function (response) {
                        self.detalleModal.presupuesto = response.data;
                        $('#modalDetallePresupuesto').modal('show');
                    })
                    .catch(function (error) {
                        console.error(error);
                        Swal.fire('Error', 'No se pudo cargar el detalle del presupuesto.', 'error');
                    });
            },
            imprimirPresupuesto: function (id) {
                window.open('{{ url("presupuesto") }}/' + id + '/pdf?autoprint=1', '_blank');
            },
            cambiarEstado: function (item, nuevoEstado) {
                var self = this;
                axios.put('{{ url("presupuesto") }}/' + item.pre_numero + '/estado', {
                    estado: nuevoEstado
                }).then(function (response) {
                    item.estado = nuevoEstado;
                    Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000
                    }).fire({
                        icon: 'success',
                        title: 'Estado actualizado a ' + nuevoEstado
                    });
                }).catch(function (error) {
                    console.error(error);
                    Swal.fire('Error', 'No se pudo actualizar el estado.', 'error');
                });
            },
            anularPresupuesto: function (item) {
                var self = this;
                Swal.fire({
                    title: '¿Anular Presupuesto Nº ' + item.pre_numero + '?',
                    text: 'El estado pasará a ANULADO.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, anular',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#e11d48'
                }).then(function (result) {
                    if (result.value) {
                        axios.delete('{{ url("presupuesto") }}/' + item.pre_numero)
                            .then(function (response) {
                                item.estado = 'ANULADO';
                                Swal.fire('Anulado', 'El presupuesto ha sido anulado.', 'success');
                            })
                            .catch(function (error) {
                                console.error(error);
                                Swal.fire('Error', 'No se pudo anular el presupuesto.', 'error');
                            });
                    }
                });
            },
            cargarEnVenta: function (item) {
                var self = this;
                axios.get('{{ url("presupuesto") }}/' + item.pre_numero + '/para-venta')
                    .then(function (response) {
                        if (response.data && response.data.success) {
                            // Guardar en sessionStorage para que la vista Venta lo cargue
                            sessionStorage.setItem('presupuesto_a_venta', JSON.stringify(response.data));

                            Swal.fire({
                                icon: 'info',
                                title: 'Cotización lista para facturar',
                                text: '¿Deseás ir a la pantalla de Punto de Venta para completar el cobro y emisión?',
                                showCancelButton: true,
                                confirmButtonText: 'Ir a Punto de Venta',
                                cancelButtonText: 'Permanecer aquí',
                                confirmButtonColor: '#0a4d36'
                            }).then(function (res) {
                                if (res.value) {
                                    window.location.href = '{{ route("venta") }}';
                                }
                            });
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                        Swal.fire('Error', 'No se pudieron transferir los ítems a la venta.', 'error');
                    });
            }
        },
        mounted: function () {
            this.recalcularVencimiento();
            this.cargarHistorial(1);

            // Ajustar navbar de BuscadorCatalogo
            this.$nextTick(function () {
                document.querySelectorAll('.pres-scan .buscador-catalogo a:not(.pres-atajo)').forEach(function (el) {
                    if (el.parentElement) {
                        el.parentElement.style.display = 'none';
                    }
                });
            });

            // Cerrar cliente-picker al hacer clic afuera
            var self = this;
            document.addEventListener('click', function (e) {
                var el = document.querySelector('.cliente-picker');
                if (el && !el.contains(e.target)) {
                    self.clientePicker.abierto = false;
                }
            });
        }
    });

    activarMenu('m_presupuesto', '');
</script>
@endsection
