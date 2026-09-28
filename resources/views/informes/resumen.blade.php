@extends('layouts.app')
@section('title', 'Resumen Gerencial')
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

    .resumen-wrapper {
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

    /* KPI Cards */
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
    .kpi-icon-red    { background: #fee2e2; color: #991b1b; }
    .kpi-icon-purple { background: #f3e8ff; color: #7e22ce; }

    .kpi-value {
        font-size: 1.55rem;
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

    /* Cards */
    .table-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        margin-bottom: 1.5rem;
        height: calc(100% - 1.5rem);
        display: flex;
        flex-direction: column;
    }
    .card-header-pos {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .card-header-pos h5 {
        font-size: 1.05rem;
        font-weight: 700;
        margin: 0;
        color: var(--dash-primary);
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }
    .card-body-pos {
        padding: 1.25rem;
        flex: 1 1 auto;
    }
    .card-footer-pos {
        padding: 0.85rem 1.25rem;
        border-top: 1px solid var(--dash-border);
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Filtros Rápidos */
    .preset-pill {
        border: 1px solid var(--dash-border);
        background: #ffffff;
        color: var(--dash-text-main);
        font-size: 0.8rem;
        font-weight: 700;
        border-radius: 6px;
        padding: 0.3rem 0.75rem;
        cursor: pointer;
        transition: all 0.15s;
    }
    .preset-pill:hover,
    .preset-pill.active {
        background: var(--dash-primary);
        border-color: var(--dash-primary);
        color: #ffffff;
    }

    /* Form Controls */
    .form-control-pos {
        border-radius: 8px;
        border: 1px solid var(--dash-border);
        font-size: 0.9rem;
        padding: 0.45rem 0.75rem;
        color: var(--dash-text-main);
        background-color: var(--dash-card-bg);
        transition: all 0.15s;
        height: auto;
    }
    .form-control-pos:focus {
        border-color: var(--dash-primary);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
        background-color: var(--dash-card-bg);
        color: var(--dash-text-main);
    }
    .form-label-custom {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--dash-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0.25rem;
        display: block;
    }

    /* Botones POS */
    .btn-pos-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.55rem 1.2rem;
        background: var(--dash-primary);
        border: 1px solid var(--dash-primary);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.92rem;
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

    /* Filas de Desglose de Resumen */
    .summary-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.65rem 0;
        border-bottom: 1px dashed var(--dash-border);
        font-size: 0.92rem;
    }
    .summary-row:last-child {
        border-bottom: none;
    }
    .summary-label {
        color: var(--dash-text-muted);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .summary-val {
        font-weight: 700;
        color: var(--dash-text-main);
        font-variant-numeric: tabular-nums;
    }

    /* Tabla de Rankings / Top */
    .table-custom {
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
        padding: 0.75rem 0.9rem;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table-custom tbody tr:nth-of-type(odd) {
        background-color: #fafbfc;
    }
    .table-custom tbody tr:hover {
        background-color: var(--dash-primary-light) !important;
    }
    .table-custom tbody td {
        padding: 0.7rem 0.9rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--dash-border);
        color: var(--dash-text-main);
        font-size: 0.88rem;
    }

    .badge-rank {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.78rem;
    }
    .rank-1 { background: #fef08a; color: #854d0e; }
    .rank-2 { background: #e2e8f0; color: #475569; }
    .rank-3 { background: #fed7aa; color: #9a3412; }
    .rank-other { background: #f1f5f9; color: #64748b; }

    /* Barra de Progreso Financiero */
    .ratio-bar-wrap {
        height: 10px;
        border-radius: 999px;
        background: #e2e8f0;
        overflow: hidden;
        display: flex;
        margin: 0.6rem 0;
    }
    .ratio-bar-in  { background: #16a34a; }
    .ratio-bar-out { background: #dc2626; }

    /* Dark Mode */
    body.dark-mode .table-card {
        background-color: var(--dash-card-bg);
        border-color: var(--dash-border);
    }
    body.dark-mode .card-header-pos {
        background-color: var(--dash-card-bg) !important;
        border-color: var(--dash-border) !important;
    }
    body.dark-mode .card-footer-pos {
        background-color: #111827 !important;
        border-color: var(--dash-border) !important;
    }
    body.dark-mode .preset-pill {
        background: #111827;
        color: #cbd5e1;
        border-color: var(--dash-border);
    }
    body.dark-mode .preset-pill:hover,
    body.dark-mode .preset-pill.active {
        background: var(--dash-primary);
        border-color: var(--dash-primary);
        color: #ffffff;
    }
    body.dark-mode .summary-row {
        border-bottom-color: #374151;
    }
    body.dark-mode .table-custom thead th {
        background: #111827 !important;
        color: var(--dash-text-muted) !important;
        border-color: var(--dash-border) !important;
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

    @media print {
        .dash-header button,
        .dash-header a,
        .main-sidebar,
        .main-header,
        .filter-card {
            display: none !important;
        }
        body {
            background: #fff !important;
            color: #000 !important;
        }
        .table-card, .kpi-card {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
            page-break-inside: avoid;
        }
    }
</style>
@endsection

@section('main')
<div class="container-fluid px-3 py-3 resumen-wrapper" id="app" v-cloak>
    <!-- Header de Página -->
    <div class="dash-header">
        <div>
            <h1 class="dash-header-title font-cairo">
                <i class="fa-solid fa-chart-pie mr-2"></i>Resumen Gerencial y Financiero
            </h1>
            <p class="dash-header-subtitle">
                Análisis consolidado de ventas, rentabilidad, compras a proveedores e inventario
            </p>
        </div>
        <div class="dash-header-badges">
            <button type="button" class="btn-pos-secondary mr-2" @click="enviarPorCorreo" :disabled="requestSend || sendingMail" title="Enviar informe por correo electrónico">
                <i class="fa fa-envelope mr-1 text-primary"></i>
                <span v-if="sendingMail"><i class="fa fa-spinner fa-spin mr-1"></i> Enviando...</span>
                <span v-else>Enviar por Correo</span>
            </button>
            <button type="button" class="btn-pos-secondary mr-2" onclick="window.print()">
                <i class="fa fa-print mr-1"></i> Imprimir Informe
            </button>
            <a href="{{ route('venta') }}" class="btn-pos-primary">
                <i class="fa fa-cash-register mr-1"></i> Punto de Venta
            </a>
        </div>
    </div>

    <!-- Toolbar de Filtros y Atajos Rápidos -->
    <div class="table-card filter-card mb-4" style="height: auto;">
        <div class="p-3">
            <div class="row align-items-end">
                <!-- Rango de Fechas -->
                <div class="col-md-3 col-sm-6 mb-2">
                    <label class="form-label-custom">Desde Fecha</label>
                    <input type="date" class="form-control form-control-pos" v-model="fecha.desde">
                </div>
                <div class="col-md-3 col-sm-6 mb-2">
                    <label class="form-label-custom">Hasta Fecha</label>
                    <input type="date" class="form-control form-control-pos" v-model="fecha.hasta">
                </div>

                <!-- Sucursal -->
                <div class="col-md-3 col-sm-6 mb-2">
                    <label class="form-label-custom">Sucursal</label>
                    <select class="form-control form-control-pos" v-model="idSucursal">
                        <option value="0">Todas las Sucursales</option>
                        @foreach ($sucursales as $s)
                            <option value="{{ $s['suc_cod'] }}">{{ $s['suc_desc'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Botón de Búsqueda -->
                <div class="col-md-3 col-sm-6 mb-2">
                    <button type="button" @click="getData" class="btn-pos-primary btn-block" :disabled="requestSend">
                        <span v-if="requestSend" class="spinner-border spinner-border-sm mr-2" role="status"></span>
                        <i v-else class="fa fa-search mr-1"></i>
                        @{{ requestSend ? 'Consultando...' : 'Consultar Período' }}
                    </button>
                </div>
            </div>

            <!-- Atajos Rápidos de Período -->
            <div class="d-flex align-items-center flex-wrap gap-2 pt-2 border-top mt-2">
                <span class="text-muted small font-weight-bold mr-2"><i class="fa fa-clock mr-1"></i> Atajos de fecha:</span>
                <button type="button" class="preset-pill mr-1 mb-1" @click="setPreset('hoy')">Hoy</button>
                <button type="button" class="preset-pill mr-1 mb-1" @click="setPreset('semana')">Esta Semana</button>
                <button type="button" class="preset-pill mr-1 mb-1" @click="setPreset('mes')">Este Mes</button>
                <button type="button" class="preset-pill mr-1 mb-1" @click="setPreset('mes_anterior')">Mes Anterior</button>
                <button type="button" class="preset-pill mr-1 mb-1" @click="setPreset('anho')">Año Actual</button>
            </div>
        </div>
    </div>

    <!-- 4 Indicadores / KPIs Ejecutivos Principales -->
    <div class="row">
        <!-- KPI 1: Facturación Total (Ventas) -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #0284c7;">
                <div class="kpi-header">
                    <span class="kpi-label">Facturación Total</span>
                    <div class="kpi-icon-box kpi-icon-blue">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #0284c7;">
                    Gs. @{{ millares(balance.total_ventas) }}
                </div>
                <div class="kpi-subtext">
                    <i class="fa-solid fa-receipt text-muted"></i> @{{ ventas.cantidad }} ventas realizadas
                </div>
            </div>
        </div>

        <!-- KPI 2: Ganancia Bruta Real -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #16a34a;">
                <div class="kpi-header">
                    <span class="kpi-label">Ganancia Bruta</span>
                    <div class="kpi-icon-box kpi-icon-green">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #166534;">
                    Gs. @{{ millares(balance.ganancia_bruta) }}
                </div>
                <div class="kpi-subtext" style="color: #166534;">
                    <i class="fa-solid fa-percent"></i> Margen: @{{ balance.margen_pct }}% s/ ventas
                </div>
            </div>
        </div>

        <!-- KPI 3: Compras a Proveedores -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #dc2626;">
                <div class="kpi-header">
                    <span class="kpi-label">Compras Realizadas</span>
                    <div class="kpi-icon-box kpi-icon-red">
                        <i class="fa-solid fa-truck-ramp-box"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #b91c1c;">
                    Gs. @{{ millares(balance.total_compras) }}
                </div>
                <div class="kpi-subtext" style="color: #b91c1c;">
                    <i class="fa-solid fa-file-invoice"></i> @{{ compras.cantidad }} facturas de compra
                </div>
            </div>
        </div>

        <!-- KPI 4: Valor de Inventario -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #7e22ce;">
                <div class="kpi-header">
                    <span class="kpi-label">Valor en Stock</span>
                    <div class="kpi-icon-box kpi-icon-purple">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #7e22ce;">
                    Gs. @{{ millares(articulo.venta) }}
                </div>
                <div class="kpi-subtext">
                    <i class="fa-solid fa-cubes text-muted"></i> @{{ millares(articulo.stock) }} unidades físicas
                </div>
            </div>
        </div>
    </div>

    <!-- 3 Columnas Principales: Ventas, Compras, Stock -->
    <div class="row">
        <!-- Tarjeta 1: Desglose de Ventas -->
        <div class="col-lg-4 col-12 mb-3">
            <div class="table-card">
                <div class="card-header-pos">
                    <h5>
                        <i class="fa-solid fa-cash-register text-success"></i> Rendimiento de Ventas
                    </h5>
                    <span class="badge badge-success px-2 py-1 font-weight-bold">
                        @{{ ventas.cantidad }} ventas
                    </span>
                </div>
                <div class="card-body-pos">
                    <div class="summary-row">
                        <span class="summary-label"><i class="fa fa-money-bill-wave text-success"></i> Venta al Contado</span>
                        <span class="summary-val text-success">Gs. @{{ millares(ventas.contado) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label"><i class="fa fa-credit-card text-info"></i> Venta a Crédito</span>
                        <span class="summary-val text-info">Gs. @{{ millares(ventas.credito) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label"><i class="fa fa-tag text-warning"></i> Descuentos Otorgados</span>
                        <span class="summary-val text-warning">Gs. @{{ millares(ventas.descuento) }}</span>
                    </div>
                    <div class="summary-row pt-3" style="border-top: 2px solid var(--dash-border); border-bottom: none;">
                        <span class="summary-label font-weight-bold" style="color: var(--dash-text-main); font-size: 0.95rem;">Total Facturado</span>
                        <span class="summary-val font-cairo" style="color: var(--dash-primary); font-size: 1.15rem;">
                            Gs. @{{ millares(ventas.total) }}
                        </span>
                    </div>
                </div>
                <div class="card-footer-pos">
                    <span class="font-weight-bold small text-muted">Ganancia bruta calculada:</span>
                    <span class="font-weight-bold text-success font-cairo" style="font-size: 1.05rem;">
                        Gs. @{{ millares(ventas.ganancia) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Tarjeta 2: Desglose de Compras -->
        <div class="col-lg-4 col-12 mb-3">
            <div class="table-card">
                <div class="card-header-pos">
                    <h5>
                        <i class="fa-solid fa-truck-moving text-danger"></i> Egresos por Compras
                    </h5>
                    <span class="badge badge-danger px-2 py-1 font-weight-bold">
                        @{{ compras.cantidad }} compras
                    </span>
                </div>
                <div class="card-body-pos">
                    <div class="summary-row">
                        <span class="summary-label"><i class="fa fa-money-bill text-secondary"></i> Compra al Contado</span>
                        <span class="summary-val">Gs. @{{ millares(compras.contado) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label"><i class="fa fa-clock text-secondary"></i> Compra a Crédito</span>
                        <span class="summary-val">Gs. @{{ millares(compras.credito) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label"><i class="fa fa-tag text-success"></i> Descuentos Proveedor</span>
                        <span class="summary-val text-success">Gs. @{{ millares(compras.descuento) }}</span>
                    </div>
                    <div class="summary-row pt-3" style="border-top: 2px solid var(--dash-border); border-bottom: none;">
                        <span class="summary-label font-weight-bold" style="color: var(--dash-text-main); font-size: 0.95rem;">Total en Compras</span>
                        <span class="summary-val font-cairo text-danger" style="font-size: 1.15rem;">
                            Gs. @{{ millares(compras.total) }}
                        </span>
                    </div>
                </div>
                <div class="card-footer-pos">
                    <span class="font-weight-bold small text-muted">Flujo operativo neto:</span>
                    <span class="font-weight-bold font-cairo" :class="balance.flujo_neto >= 0 ? 'text-success' : 'text-danger'" style="font-size: 1.05rem;">
                        @{{ balance.flujo_neto >= 0 ? '+ ' : '' }}Gs. @{{ millares(balance.flujo_neto) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Tarjeta 3: Auditoría de Inventario -->
        <div class="col-lg-4 col-12 mb-3">
            <div class="table-card">
                <div class="card-header-pos">
                    <h5>
                        <i class="fa-solid fa-warehouse text-info"></i> Auditoría de Stock
                    </h5>
                    <span class="badge badge-info px-2 py-1 font-weight-bold">
                        @{{ articulo.cantidad }} artículos
                    </span>
                </div>
                <div class="card-body-pos">
                    <div class="summary-row">
                        <span class="summary-label"><i class="fa fa-boxes text-secondary"></i> Unidades Totales</span>
                        <span class="summary-val">@{{ millares(articulo.stock) }} unid.</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label"><i class="fa fa-file-invoice-dollar text-secondary"></i> Valuación a Costo</span>
                        <span class="summary-val">Gs. @{{ millares(articulo.costo) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label"><i class="fa fa-tags text-success"></i> Valuación a Venta</span>
                        <span class="summary-val text-success">Gs. @{{ millares(articulo.venta) }}</span>
                    </div>
                    <div class="summary-row pt-3" style="border-top: 2px solid var(--dash-border); border-bottom: none;">
                        <span class="summary-label font-weight-bold" style="color: var(--dash-text-main); font-size: 0.95rem;">Margen Potencial</span>
                        <span class="summary-val font-cairo text-info" style="font-size: 1.15rem;">
                            Gs. @{{ millares(articulo.potencial) }}
                        </span>
                    </div>
                </div>
                <div class="card-footer-pos">
                    <span class="font-weight-bold small text-muted">Artículos con Stock 0:</span>
                    <span class="badge badge-pill font-weight-bold" :class="articulo.stock0 > 0 ? 'badge-danger' : 'badge-light border'">
                        @{{ articulo.stock0 }} agotados
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Fila Inferior: Top Productos y Balance Financiero -->
    <div class="row">
        <!-- Top 5 Productos Más Vendidos -->
        <div class="col-lg-7 col-12 mb-3">
            <div class="table-card">
                <div class="card-header-pos">
                    <h5>
                        <i class="fa-solid fa-trophy text-warning"></i> Top 5 Productos con Mayor Facturación
                    </h5>
                    <small class="text-muted">En el período consultado</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Producto / Artículo</th>
                                <th class="text-center" style="width: 100px;">Unidades</th>
                                <th class="text-right" style="width: 150px;">Monto Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="topProductos.length">
                                <tr v-for="(p, idx) in topProductos" :key="p.ARTICULOS_cod">
                                    <td class="align-middle">
                                        <span class="badge-rank" :class="getRankClass(idx)">
                                            @{{ idx + 1 }}
                                        </span>
                                    </td>
                                    <td class="align-middle font-weight-bold">
                                        @{{ p.producto_nombre }}
                                    </td>
                                    <td class="align-middle text-center font-weight-bold font-cairo">
                                        @{{ millares(p.cant_total) }}
                                    </td>
                                    <td class="align-middle text-right font-weight-bold font-cairo text-success">
                                        Gs. @{{ millares(p.monto_total) }}
                                    </td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fa fa-chart-line fa-2x mb-2 text-secondary opacity-50"></i>
                                    <p class="mb-0 small">No se registraron ventas en el período seleccionado.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Resumen de Proporciones y Ratios Financieros -->
        <div class="col-lg-5 col-12 mb-3">
            <div class="table-card">
                <div class="card-header-pos">
                    <h5>
                        <i class="fa-solid fa-scale-balanced text-primary"></i> Conciliación y Ratios
                    </h5>
                    <span class="badge badge-light border font-weight-bold">Financiero</span>
                </div>
                <div class="card-body-pos">
                    <!-- Ratio Ventas vs Compras -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center small font-weight-bold">
                            <span class="text-success"><i class="fa fa-arrow-up mr-1"></i> Ventas: @{{ millares(balance.total_ventas) }}</span>
                            <span class="text-danger"><i class="fa fa-arrow-down mr-1"></i> Compras: @{{ millares(balance.total_compras) }}</span>
                        </div>
                        <div class="ratio-bar-wrap">
                            <div class="ratio-bar-in" :style="{ width: getVentasPct() + '%' }" title="Ventas"></div>
                            <div class="ratio-bar-out" :style="{ width: (100 - getVentasPct()) + '%' }" title="Compras"></div>
                        </div>
                        <small class="text-muted d-block">Proporción de ingresos versus adquisiciones del período.</small>
                    </div>

                    <!-- Indicador Margen de Rentabilidad -->
                    <div class="p-3 rounded border mb-2" style="background: var(--dash-primary-light); border-color: var(--dash-primary-border) !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="font-weight-bold" style="color: var(--dash-primary-dark); font-size: 0.95rem;">
                                    Rentabilidad Operativa
                                </span>
                                <small class="text-muted d-block">Margen bruto obtenido sobre precio de venta</small>
                            </div>
                            <div class="text-right">
                                <span class="font-weight-bold font-cairo" style="font-size: 1.6rem; color: var(--dash-primary);">
                                    @{{ balance.margen_pct }}%
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen Contado vs Crédito -->
                    <div class="p-3 rounded border bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small font-weight-bold text-muted">Cobrado al Contado:</span>
                            <span class="font-weight-bold text-success font-cairo">@{{ getContadoPct() }}%</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small font-weight-bold text-muted">A Cobrar a Crédito:</span>
                            <span class="font-weight-bold text-info font-cairo">@{{ 100 - getContadoPct() }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    var app = new Vue({
        el: '#app',
        data: {
            requestSend: false,
            sendingMail: false,
            defaultMail: '{{ $defaultMail ?? '' }}',
            idSucursal: 0,
            fecha: {
                desde: '',
                hasta: ''
            },
            ventas: {
                cantidad: 0,
                contado: 0,
                credito: 0,
                total: 0,
                descuento: 0,
                ganancia: 0
            },
            compras: {
                cantidad: 0,
                contado: 0,
                credito: 0,
                total: 0,
                descuento: 0
            },
            articulo: {
                cantidad: 0,
                stock: 0,
                costo: 0,
                venta: 0,
                stock0: 0,
                potencial: 0
            },
            balance: {
                total_ventas: 0,
                total_compras: 0,
                flujo_neto: 0,
                ganancia_bruta: 0,
                margen_pct: 0
            },
            topProductos: []
        },
        methods: {
            millares: function (value) {
                var n = parseFloat(value || 0);
                return new Intl.NumberFormat('de-DE').format(Math.round(n));
            },
            formatFecha: function (d) {
                var mes = (d.getMonth() + 1).toString().padStart(2, '0');
                var dia = d.getDate().toString().padStart(2, '0');
                return d.getFullYear() + '-' + mes + '-' + dia;
            },
            setPreset: function (tipo) {
                var hoy = new Date();
                var d1 = new Date();
                var d2 = new Date();

                if (tipo === 'hoy') {
                    // mismo dia
                } else if (tipo === 'semana') {
                    var diaSem = hoy.getDay();
                    var diff = hoy.getDate() - diaSem + (diaSem === 0 ? -6 : 1); // lunes
                    d1 = new Date(hoy.setDate(diff));
                    d2 = new Date();
                } else if (tipo === 'mes') {
                    d1 = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
                    d2 = new Date();
                } else if (tipo === 'mes_anterior') {
                    d1 = new Date(hoy.getFullYear(), hoy.getMonth() - 1, 1);
                    d2 = new Date(hoy.getFullYear(), hoy.getMonth(), 0);
                } else if (tipo === 'anho') {
                    d1 = new Date(hoy.getFullYear(), 0, 1);
                    d2 = new Date();
                }

                this.fecha.desde = this.formatFecha(d1);
                this.fecha.hasta = this.formatFecha(d2);
                this.getData();
            },
            getVentasPct: function () {
                var v = parseFloat(this.balance.total_ventas) || 0;
                var c = parseFloat(this.balance.total_compras) || 0;
                var total = v + c;
                if (total <= 0) return 50;
                return Math.min(100, Math.max(0, Math.round((v / total) * 100)));
            },
            getContadoPct: function () {
                var total = parseFloat(this.ventas.total) || 0;
                var contado = parseFloat(this.ventas.contado) || 0;
                if (total <= 0) return 100;
                return Math.round((contado / total) * 100);
            },
            getRankClass: function (idx) {
                if (idx === 0) return 'rank-1';
                if (idx === 1) return 'rank-2';
                if (idx === 2) return 'rank-3';
                return 'rank-other';
            },
            getData: function () {
                if (this.requestSend) return;
                this.requestSend = true;

                axios.get('{{ url('resumen/datos') }}', {
                    params: {
                        desde: this.fecha.desde,
                        hasta: this.fecha.hasta,
                        idsucursal: this.idSucursal
                    }
                }).then(response => {
                    this.requestSend = false;
                    var d = response.data || {};
                    if (d.venta) this.ventas = d.venta;
                    if (d.compra) this.compras = d.compra;
                    if (d.articulo) {
                        this.articulo = d.articulo;
                        this.articulo.stock0 = d.articulo.sin_stock || 0;
                    }
                    if (d.balance) {
                        this.balance = d.balance;
                    } else {
                        var vTot = parseFloat(this.ventas.total || 0);
                        var cTot = parseFloat(this.compras.total || 0);
                        var gTot = parseFloat(this.ventas.ganancia || 0);
                        this.balance = {
                            total_ventas: vTot,
                            total_compras: cTot,
                            flujo_neto: vTot - cTot,
                            ganancia_bruta: gTot,
                            margen_pct: vTot > 0 ? Math.round((gTot / vTot) * 100) : 0
                        };
                    }
                    this.topProductos = d.top_productos || [];
                }).catch(err => {
                    this.requestSend = false;
                    console.error(err);
                    Swal.fire('Error', 'No se pudo cargar los datos del resumen gerencial.', 'error');
                });
            },
            enviarPorCorreo: function () {
                var self = this;
                Swal.fire({
                    title: 'Enviar Resumen por Correo',
                    text: 'Ingrese la dirección de correo a la que desea remitir este informe:',
                    input: 'text',
                    inputValue: self.defaultMail || '',
                    inputPlaceholder: 'ejemplo@empresa.com (o varios separados por coma)',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa fa-paper-plane mr-1"></i> Enviar Ahora',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#0a4d36',
                    cancelButtonColor: '#6c757d',
                    showLoaderOnConfirm: true,
                    inputValidator: function (value) {
                        if (!value || !value.trim()) {
                            return 'Debe ingresar al menos un correo electrónico.';
                        }
                    },
                    preConfirm: function (email) {
                        self.sendingMail = true;
                        return axios.post('{{ route('resumen.email') }}', {
                            email: email,
                            desde: self.fecha.desde,
                            hasta: self.fecha.hasta,
                            idsucursal: self.idSucursal
                        }).then(function (response) {
                            return response.data;
                        }).catch(function (error) {
                            var msg = (error.response && error.response.data && error.response.data.message)
                                ? error.response.data.message
                                : 'Error de comunicación al intentar enviar el correo.';
                            Swal.showValidationMessage(msg);
                        }).finally(function () {
                            self.sendingMail = false;
                        });
                    },
                    allowOutsideClick: function () {
                        return !Swal.isLoading();
                    }
                }).then(function (result) {
                    if (result.value && result.value.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Enviado!',
                            text: result.value.message || 'El informe ha sido enviado correctamente.',
                            confirmButtonColor: '#0a4d36'
                        });
                    }
                });
            }
        },
        mounted: function () {
            // Inicializar por defecto en el mes actual
            var hoy = new Date();
            var primerDia = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
            this.fecha.desde = this.formatFecha(primerDia);
            this.fecha.hasta = this.formatFecha(hoy);
            this.getData();
        }
    });

    activarMenu('m_informe', 'm_iresumen');
</script>
@endsection
