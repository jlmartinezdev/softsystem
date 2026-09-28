@extends('layouts.app')
@section('title', 'Movimiento de Caja')
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

    .caja-wrapper {
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

    /* Status Pills */
    .badge-status-open {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 0.9rem;
        border-radius: 999px;
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
        font-weight: 700;
        font-size: 0.85rem;
    }
    .badge-status-closed {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.45rem 0.9rem;
        border-radius: 999px;
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
        font-weight: 700;
        font-size: 0.85rem;
    }
    .status-dot-pulse {
        width: 9px;
        height: 9px;
        background-color: #16a34a;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7);
        animation: pulse-green 1.8s infinite;
    }
    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 7px rgba(22, 163, 74, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
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
    .kpi-icon-red    { background: #fee2e2; color: #991b1b; }
    .kpi-icon-blue   { background: #e0f2fe; color: #0284c7; }
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
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0;
        color: var(--dash-primary);
    }

    /* Segmented Switch para Entrada / Salida */
    .type-switcher {
        display: flex;
        background: #f1f5f9;
        border: 1px solid var(--dash-border);
        border-radius: 10px;
        padding: 4px;
        margin-bottom: 1.25rem;
        gap: 4px;
    }
    .type-btn {
        flex: 1;
        padding: 0.65rem 1rem;
        border-radius: 8px;
        border: none;
        background: transparent;
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--dash-text-muted);
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
    }
    .type-btn:focus {
        outline: none;
    }
    .type-btn.active-entrada {
        background: #16a34a;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(22, 163, 74, 0.3);
    }
    .type-btn.active-salida {
        background: #dc2626;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
    }

    /* Inputs */
    .form-label-custom {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--dash-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0.35rem;
        display: block;
    }
    .form-control-pos {
        border-radius: 8px;
        border: 1px solid var(--dash-border);
        font-size: 0.95rem;
        padding: 0.55rem 0.85rem;
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
    .input-monto-mov {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--dash-primary);
        letter-spacing: -0.5px;
    }
    .input-monto-mov.is-salida {
        color: #dc2626;
    }

    /* Quick Amount Presets */
    .quick-chips-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        margin-top: 0.5rem;
    }
    .btn-quick-chip {
        background: #f1f5f9;
        border: 1px solid var(--dash-border);
        color: var(--dash-text-main);
        border-radius: 6px;
        padding: 0.25rem 0.65rem;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-quick-chip:hover {
        background: var(--dash-primary-light);
        border-color: var(--dash-primary-border);
        color: var(--dash-primary);
    }

    /* Concept Suggestion Chips */
    .concept-chip {
        background: #f8fafc;
        border: 1px solid var(--dash-border);
        color: var(--dash-text-muted);
        border-radius: 999px;
        padding: 0.2rem 0.6rem;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s;
    }
    .concept-chip:hover {
        background: var(--dash-primary-light);
        color: var(--dash-primary);
        border-color: var(--dash-primary-border);
    }

    /* Botones POS */
    .btn-pos-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.65rem 1.4rem;
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

    /* Tabla de Movimientos */
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
        padding: 0.65rem 0.9rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--dash-border);
        color: var(--dash-text-main);
        font-size: 0.88rem;
    }

    /* Badges */
    .badge-mov-in {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
        font-weight: 700;
        border-radius: 6px;
        padding: 0.2rem 0.5rem;
        font-size: 0.78rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    .badge-mov-out {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
        font-weight: 700;
        border-radius: 6px;
        padding: 0.2rem 0.5rem;
        font-size: 0.78rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Modo Oscuro */
    body.dark-mode .table-card {
        background-color: var(--dash-card-bg);
        border-color: var(--dash-border);
    }
    body.dark-mode .card-header-pos {
        background-color: var(--dash-card-bg) !important;
        border-color: var(--dash-border) !important;
    }
    body.dark-mode .type-switcher {
        background: #111827;
        border-color: var(--dash-border);
    }
    body.dark-mode .btn-quick-chip {
        background: #111827;
        color: #cbd5e1;
        border-color: var(--dash-border);
    }
    body.dark-mode .concept-chip {
        background: #111827;
        color: #94a3b8;
        border-color: #374151;
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
</style>
@endsection

@section('main')
@php
    $nombreLocal = trim((string) optional($empresa)->emp_nombre);
    $tituloHoja = $nombreLocal !== '' ? $nombreLocal : 'Movimientos de Caja';
@endphp

<div class="container-fluid px-3 py-3 caja-wrapper" id="app" v-cloak>
    <!-- Header de Página -->
    <div class="dash-header">
        <div>
            <h1 class="dash-header-title font-cairo">
                <i class="fa-solid fa-money-bill-transfer mr-2"></i>Entradas y Salidas de Caja
            </h1>
            <p class="dash-header-subtitle">
                {{ $tituloHoja }} — Registro directo de ingresos adicionales, gastos y retiros del turno
            </p>
        </div>
        <div class="dash-header-badges">
            <span v-if="cajaAbierta" class="badge-status-open">
                <span class="status-dot-pulse"></span>
                Turno Activo #@{{ movimiento.nro_operacion }}
            </span>
            <span v-else class="badge-status-closed">
                <i class="fa fa-lock text-warning mr-1"></i>
                Caja Cerrada
            </span>
            <a href="{{ route('apertura') }}" class="btn-pos-secondary">
                <i class="fa fa-cash-register mr-1"></i> Apertura / Cierre
            </a>
            <a v-if="cajaAbierta" :href="'{{ url('caja/movimiento') }}/' + movimiento.nro_operacion" class="btn-pos-secondary" title="Ver reporte imprimible">
                <i class="fa fa-print mr-1"></i> Ver Informe
            </a>
        </div>
    </div>

    <!-- 4 Indicadores / KPIs del Turno -->
    <div class="row">
        <!-- 1: Saldo Disponible en Caja -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #0a4d36;">
                <div class="kpi-header">
                    <span class="kpi-label">Saldo en Caja</span>
                    <div class="kpi-icon-box kpi-icon-green">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: var(--dash-primary);">
                    Gs. @{{ formatGs(totales.saldo) }}
                </div>
                <div class="kpi-subtext">
                    <i class="fa-solid fa-coins text-muted"></i> Efectivo disponible actualmente
                </div>
            </div>
        </div>

        <!-- 2: Total Entradas -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #16a34a;">
                <div class="kpi-header">
                    <span class="kpi-label">Total Entradas</span>
                    <div class="kpi-icon-box kpi-icon-green">
                        <i class="fa-solid fa-arrow-down"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #166534;">
                    Gs. @{{ formatGs(totales.entradas) }}
                </div>
                <div class="kpi-subtext" style="color: #166534;">
                    <i class="fa-solid fa-plus-circle"></i> Fondo inicial + ingresos
                </div>
            </div>
        </div>

        <!-- 3: Total Salidas -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #dc2626;">
                <div class="kpi-header">
                    <span class="kpi-label">Total Salidas</span>
                    <div class="kpi-icon-box kpi-icon-red">
                        <i class="fa-solid fa-arrow-up"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #b91c1c;">
                    Gs. @{{ formatGs(totales.salidas) }}
                </div>
                <div class="kpi-subtext" style="color: #b91c1c;">
                    <i class="fa-solid fa-minus-circle"></i> Gastos y retiros
                </div>
            </div>
        </div>

        <!-- 4: Movimientos Registrados -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #0284c7;">
                <div class="kpi-header">
                    <span class="kpi-label">Registros</span>
                    <div class="kpi-icon-box kpi-icon-blue">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #0284c7;">
                    @{{ movimientos.length }}
                </div>
                <div class="kpi-subtext">
                    <i class="fa-solid fa-list-check text-muted"></i> Movimientos en este turno
                </div>
            </div>
        </div>
    </div>

    <!-- Layout Dividido: Formulario a la Izquierda y Movimientos a la Derecha -->
    <div class="row">
        <!-- Columna Izquierda: Formulario de Registro -->
        <div class="col-xl-5 col-12">
            <div class="table-card">
                <div class="card-header-pos">
                    <h5>
                        <i class="fa-solid fa-pen-to-square mr-2 text-success"></i> Registrar Movimiento
                    </h5>
                    <span v-if="cajaAbierta" class="badge badge-light border">
                        Op. #@{{ movimiento.nro_operacion }}
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <!-- Advertencia si la caja está cerrada -->
                    <div v-if="!cajaAbierta" class="alert alert-warning border text-center p-3 mb-3">
                        <i class="fa fa-lock fa-2x mb-2 text-warning d-block"></i>
                        <h6 class="font-weight-bold mb-1">No hay una caja abierta</h6>
                        <p class="small mb-3">Para registrar ingresos o salidas es necesario abrir un turno de caja en esta sucursal.</p>
                        <a href="{{ route('apertura') }}" class="btn-pos-primary btn-sm">
                            <i class="fa fa-key mr-1"></i> Ir a Abrir Turno de Caja
                        </a>
                    </div>

                    <form id="formMovimiento" @submit.prevent="store" v-else>
                        <!-- Selector de Tipo: Entrada vs Salida -->
                        <div class="type-switcher" role="group" aria-label="Tipo de movimiento">
                            <button
                                type="button"
                                class="type-btn"
                                :class="{ 'active-entrada': movimiento.tipo === 'Entrada' }"
                                :disabled="guardando"
                                @click="movimiento.tipo = 'Entrada'">
                                <i class="fa fa-arrow-down mr-1"></i> Entrada (+)
                            </button>
                            <button
                                type="button"
                                class="type-btn"
                                :class="{ 'active-salida': movimiento.tipo === 'Salida' }"
                                :disabled="guardando"
                                @click="movimiento.tipo = 'Salida'">
                                <i class="fa fa-arrow-up mr-1"></i> Salida / Gasto (-)
                            </button>
                        </div>

                        <!-- Campo Monto -->
                        <div class="form-group mb-3">
                            <label class="form-label-custom" for="monto">
                                Monto del Movimiento (Gs.) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light font-weight-bold"
                                          :class="movimiento.tipo === 'Entrada' ? 'text-success' : 'text-danger'"
                                          style="font-size: 1.2rem;">Gs.</span>
                                </div>
                                <input
                                    type="number"
                                    id="monto"
                                    class="form-control form-control-pos input-monto-mov font-cairo"
                                    :class="{ 'is-salida': movimiento.tipo === 'Salida' }"
                                    placeholder="0"
                                    min="1"
                                    step="1"
                                    inputmode="numeric"
                                    v-model.number="movimiento.monto"
                                    :disabled="guardando"
                                    @keyup.enter="alEnterMonto"
                                    ref="monto"
                                    autocomplete="off"
                                    required
                                />
                            </div>

                            <!-- Atajos Rápidos de Monto -->
                            <div class="quick-chips-wrap">
                                <span class="text-muted small align-self-center mr-1">Rápido:</span>
                                <button type="button" class="btn-quick-chip" @click="setMonto(20000)">20.000</button>
                                <button type="button" class="btn-quick-chip" @click="setMonto(50000)">50.000</button>
                                <button type="button" class="btn-quick-chip" @click="setMonto(100000)">100.000</button>
                                <button type="button" class="btn-quick-chip" @click="setMonto(200000)">200.000</button>
                                <button type="button" class="btn-quick-chip" @click="setMonto(500000)">500.000</button>
                                <button v-if="movimiento.tipo === 'Salida' && totales.saldo > 0" type="button" class="btn-quick-chip text-danger font-weight-bold" @click="setMonto(totales.saldo)">
                                    Todo el saldo
                                </button>
                            </div>
                        </div>

                        <!-- Campo Descripción / Motivo -->
                        <div class="form-group mb-3">
                            <label class="form-label-custom" for="descripcion">
                                Descripción o Motivo <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="descripcion"
                                class="form-control form-control-pos font-weight-bold"
                                :placeholder="pistaDescripcion"
                                v-model.trim="movimiento.descripcion"
                                :disabled="guardando"
                                @keyup.enter="store"
                                ref="descripcion"
                                maxlength="255"
                                required
                            />

                            <!-- Sugerencias de Conceptos Comunes -->
                            <div class="quick-chips-wrap mt-2">
                                <span class="text-muted small align-self-center mr-1">Comunes:</span>
                                <template v-if="movimiento.tipo === 'Salida'">
                                    <span class="concept-chip" @click="setConcepto('Pago de flete')">Pago de flete</span>
                                    <span class="concept-chip" @click="setConcepto('Insumos de limpieza')">Insumos</span>
                                    <span class="concept-chip" @click="setConcepto('Retiro de efectivo')">Retiro de efectivo</span>
                                    <span class="concept-chip" @click="setConcepto('Adelanto de sueldo')">Adelanto</span>
                                    <span class="concept-chip" @click="setConcepto('Pago de servicios')">Servicios</span>
                                </template>
                                <template v-else>
                                    <span class="concept-chip" @click="setConcepto('Cambio para caja')">Cambio para caja</span>
                                    <span class="concept-chip" @click="setConcepto('Inyección de efectivo')">Inyección</span>
                                    <span class="concept-chip" @click="setConcepto('Cobro vario')">Cobro vario</span>
                                </template>
                            </div>
                        </div>

                        <!-- Advertencia si supera saldo -->
                        <div class="alert alert-danger border p-2 mb-3" v-if="superaSaldo">
                            <small class="font-weight-bold">
                                <i class="fa fa-triangle-exclamation mr-1"></i>
                                El monto supera el saldo disponible en caja (Gs. @{{ formatGs(totales.saldo) }}).
                            </small>
                        </div>

                        <!-- Botón de Envío -->
                        <button
                            type="submit"
                            class="btn-pos-primary btn-block btn-lg mt-3"
                            :disabled="guardando || superaSaldo || !movimiento.descripcion || !(movimiento.monto > 0)">
                            <span v-if="guardando" class="spinner-border spinner-border-sm mr-2" role="status"></span>
                            <i v-else :class="movimiento.tipo === 'Entrada' ? 'fa fa-plus-circle mr-1' : 'fa fa-minus-circle mr-1'"></i>
                            @{{ guardando ? 'Registrando movimiento...' : (movimiento.tipo === 'Entrada' ? 'Registrar Entrada' : 'Registrar Salida') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Tabla de Movimientos del Turno -->
        <div class="col-xl-7 col-12">
            <div class="table-card">
                <div class="card-header-pos">
                    <h5>
                        <i class="fa-solid fa-list-check mr-2 text-info"></i> Movimientos del Turno
                    </h5>
                    <span class="badge badge-light border font-weight-bold">
                        @{{ movimientos.length }} registros
                    </span>
                </div>
                <div class="table-responsive" style="max-height: 540px; overflow-y: auto;">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 110px;">Tipo</th>
                                <th style="width: 140px;">Fecha y Hora</th>
                                <th>Concepto / Motivo</th>
                                <th class="text-right" style="width: 140px;">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="movimientos.length">
                                <tr v-for="(m, i) in movimientos" :key="i">
                                    <td class="align-middle text-muted small">@{{ i + 1 }}</td>
                                    <td class="align-middle">
                                        <span v-if="m.mov_tipo === 'Entrada'" class="badge-mov-in">
                                            <i class="fa fa-arrow-down"></i> Entrada
                                        </span>
                                        <span v-else class="badge-mov-out">
                                            <i class="fa fa-arrow-up"></i> Salida
                                        </span>
                                    </td>
                                    <td class="align-middle small">
                                        @{{ m.mov_fecha }}
                                    </td>
                                    <td class="align-middle font-weight-bold">
                                        @{{ m.mov_concepto }}
                                    </td>
                                    <td class="align-middle text-right font-weight-bold font-cairo"
                                        :class="m.mov_tipo === 'Entrada' ? 'text-success' : 'text-danger'">
                                        <template v-if="m.mov_tipo === 'Salida' && m.mov_monto > 0">−</template>Gs. @{{ formatGs(m.mov_monto) }}
                                    </td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa fa-receipt fa-3x mb-3 text-secondary opacity-50"></i>
                                    <h5 class="font-weight-bold">No hay movimientos registrados</h5>
                                    <p class="small mb-0" v-if="cajaAbierta">Los ingresos y salidas que registres aparecerán en esta lista.</p>
                                    <p class="small mb-0" v-else>Abrí una caja para ver y registrar movimientos de turno.</p>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot v-if="movimientos.length > 0" class="bg-light font-weight-bold">
                            <tr>
                                <td colspan="4" class="text-right">Total Entradas:</td>
                                <td class="text-right text-success font-cairo">Gs. @{{ formatGs(totales.entradas) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right">Total Salidas:</td>
                                <td class="text-right text-danger font-cairo">Gs. @{{ formatGs(totales.salidas) }}</td>
                            </tr>
                            <tr style="border-top: 2px solid var(--dash-border);">
                                <td colspan="4" class="text-right" style="color: var(--dash-primary);">Saldo Disponible en Caja:</td>
                                <td class="text-right font-cairo font-weight-bold" style="color: var(--dash-primary); font-size: 1.05rem;">
                                    Gs. @{{ formatGs(totales.saldo) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
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
            movimientos: [],
            guardando: false,
            movimiento: {
                idSucursal: 0,
                nro_operacion: '-',
                caja: '',
                tipo: 'Entrada',
                descripcion: '',
                monto: ''
            }
        },
        computed: {
            cajaAbierta: function () {
                return this.movimiento.caja === 'ABIERTA' && this.movimiento.nro_operacion !== '-';
            },
            pistaDescripcion: function () {
                return this.movimiento.tipo === 'Salida'
                    ? 'Ej: Pago de flete, compra de insumos, retiro...'
                    : 'Ej: Cambio para caja, inyección de efectivo...';
            },
            superaSaldo: function () {
                return this.movimiento.tipo === 'Salida' && Number(this.movimiento.monto) > this.totales.saldo;
            },
            totales: function () {
                var entradas = 0;
                var salidas = 0;
                for (var i = 0; i < this.movimientos.length; i++) {
                    var m = this.movimientos[i];
                    var monto = parseFloat(m.mov_monto || 0);
                    if (m.mov_tipo === 'Entrada') {
                        entradas += monto;
                    } else {
                        salidas += monto;
                    }
                }
                return {
                    entradas: entradas,
                    salidas: salidas,
                    saldo: entradas - salidas
                };
            }
        },
        methods: {
            formatGs: function (n) {
                return new Intl.NumberFormat('de-DE').format(Math.round(Number(n) || 0));
            },
            setMonto: function (val) {
                this.movimiento.monto = val;
                if (this.$refs.descripcion) {
                    this.$refs.descripcion.focus();
                }
            },
            setConcepto: function (texto) {
                this.movimiento.descripcion = texto;
                if (!this.movimiento.monto && this.$refs.monto) {
                    this.$refs.monto.focus();
                }
            },
            alEnterMonto: function () {
                if (!this.movimiento.descripcion && this.$refs.descripcion) {
                    this.$refs.descripcion.focus();
                    return;
                }
                this.store();
            },
            getApertura: function () {
                var idSucursal = localStorage.getItem("suc_cod") || $('#sucursal').attr('data-id');
                this.movimiento.idSucursal = idSucursal;
                if (idSucursal != null) {
                    axios.get('aperturacierre/' + idSucursal)
                        .then(response => {
                            if (response.data) {
                                this.movimiento.nro_operacion = response.data.nro_operacion;
                                this.movimiento.caja = 'ABIERTA';
                                this.getMovimiento();
                                this.$nextTick(() => {
                                    if (this.$refs.monto) {
                                        this.$refs.monto.focus();
                                    }
                                });
                            } else {
                                this.movimiento.caja = 'CERRADA';
                                this.movimiento.nro_operacion = '-';
                                this.movimientos = [];
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            Swal.fire({
                                title: 'Error',
                                text: 'No se pudo consultar el estado de caja.',
                                icon: 'error',
                                confirmButtonColor: '#0a4d36'
                            });
                        });
                }
            },
            getMovimiento: function () {
                if (this.movimiento.nro_operacion !== '-') {
                    axios.get('movimiento/' + this.movimiento.nro_operacion)
                        .then(response => {
                            this.movimientos = response.data || [];
                        })
                        .catch(error => {
                            console.error(error.message);
                        });
                }
            },
            store: function () {
                if (this.guardando) return;
                if (!this.cajaAbierta) {
                    Swal.fire({
                        title: 'Caja cerrada',
                        text: 'No se puede registrar el movimiento porque la caja no está abierta.',
                        icon: 'warning',
                        confirmButtonColor: '#0a4d36'
                    });
                    return;
                }
                if (!this.movimiento.descripcion || !(Number(this.movimiento.monto) > 0)) {
                    Swal.fire({
                        title: 'Campos incompletos',
                        text: 'Ingresá una descripción y un monto mayor a cero.',
                        icon: 'warning',
                        confirmButtonColor: '#0a4d36'
                    });
                    return;
                }
                if (this.movimiento.tipo === 'Salida' && Number(this.movimiento.monto) > this.totales.saldo) {
                    Swal.fire({
                        title: 'Saldo insuficiente',
                        text: 'El monto de salida supera el saldo actual en caja (Gs. ' + this.formatGs(this.totales.saldo) + ').',
                        icon: 'warning',
                        confirmButtonColor: '#0a4d36'
                    });
                    return;
                }
                this.guardando = true;
                this.enviar();
            },
            enviar: function () {
                axios.post('movimiento', { data: this.movimiento })
                    .then(response => {
                        if (response.data && response.data.ok === false) {
                            Swal.fire({
                                title: 'No se pudo registrar',
                                text: response.data.message || 'Error desconocido.',
                                icon: 'warning',
                                confirmButtonColor: '#0a4d36'
                            });
                            return;
                        }
                        var tipoRegistrado = this.movimiento.tipo;
                        this.movimiento.descripcion = '';
                        this.movimiento.monto = '';
                        this.movimientos = (response.data && response.data.movimientos)
                            ? response.data.movimientos
                            : response.data;
                        
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: tipoRegistrado === 'Entrada' ? 'Entrada registrada con éxito' : 'Salida registrada con éxito',
                            showConfirmButton: false,
                            timer: 2000
                        });

                        this.$nextTick(() => {
                            if (this.$refs.monto) {
                                this.$refs.monto.focus();
                            }
                        });
                    })
                    .catch(error => {
                        var msg = 'No se pudo registrar el movimiento.';
                        if (error.response && error.response.data && error.response.data.message) {
                            msg = error.response.data.message;
                        }
                        Swal.fire({
                            title: 'Error',
                            text: msg,
                            icon: 'error',
                            confirmButtonColor: '#0a4d36'
                        });
                    })
                    .finally(() => {
                        this.guardando = false;
                    });
            }
        },
        mounted() {
            this.getApertura();
        }
    });

    activarMenu('m_caja', 'm_movimiento');
</script>
@endsection
