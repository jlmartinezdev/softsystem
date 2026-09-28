@extends('layouts.app')
@section('title', 'Cierre de Caja')
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

    /* Bloque Monto Esperado */
    .esperado-box {
        background: var(--dash-primary-light);
        border: 1px solid var(--dash-primary-border);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .esperado-box-label {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--dash-primary-dark);
    }
    .esperado-box-val {
        font-size: 1.85rem;
        font-weight: 800;
        color: var(--dash-primary);
        font-variant-numeric: tabular-nums;
    }

    /* Caja de Diferencia */
    .diff-box {
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        border: 1px solid transparent;
        transition: all 0.2s ease-in-out;
    }
    .diff-box.diff-zero {
        background-color: #dcfce7;
        border-color: #bbf7d0;
        color: #166534;
    }
    .diff-box.diff-missing {
        background-color: #fee2e2;
        border-color: #fecaca;
        color: #991b1b;
    }
    .diff-box.diff-surplus {
        background-color: #e0f2fe;
        border-color: #bae6fd;
        color: #0369a1;
    }
    .diff-box.diff-empty {
        background-color: #f1f5f9;
        border-color: #e2e8f0;
        color: #64748b;
    }
    .diff-title {
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .diff-val {
        font-size: 1.75rem;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
        line-height: 1.2;
    }
    .diff-hint {
        font-size: 0.82rem;
        font-weight: 600;
        margin-top: 0.25rem;
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
    .input-monto-cierre {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--dash-primary);
        letter-spacing: -0.5px;
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

    /* Info Grid */
    .turn-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    .turn-info-item {
        background: #f8fafc;
        border: 1px solid var(--dash-border);
        border-radius: 8px;
        padding: 0.6rem 0.85rem;
    }
    .turn-info-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--dash-text-muted);
        letter-spacing: 0.04em;
        margin-bottom: 0.2rem;
    }
    .turn-info-val {
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--dash-text-main);
    }

    /* Tabla Movimientos */
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

    /* Desglose de Billetes */
    .billetes-table {
        width: 100%;
        font-size: 0.85rem;
    }
    .billetes-table td {
        padding: 0.35rem 0.5rem;
        vertical-align: middle;
    }
    .billetes-table input {
        text-align: center;
        font-weight: 700;
        padding: 0.2rem 0.4rem;
        height: auto;
    }

    /* Dark Mode Adjustments */
    body.dark-mode .table-card {
        background-color: var(--dash-card-bg);
        border-color: var(--dash-border);
    }
    body.dark-mode .card-header-pos {
        background-color: var(--dash-card-bg) !important;
        border-color: var(--dash-border) !important;
    }
    body.dark-mode .turn-info-item {
        background-color: #111827 !important;
        border-color: var(--dash-border) !important;
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
    body.dark-mode .diff-box.diff-zero {
        background-color: rgba(16, 185, 129, 0.15);
        color: #34d399;
        border-color: rgba(16, 185, 129, 0.3);
    }
    body.dark-mode .diff-box.diff-missing {
        background-color: rgba(239, 68, 68, 0.15);
        color: #f87171;
        border-color: rgba(239, 68, 68, 0.3);
    }
    body.dark-mode .diff-box.diff-surplus {
        background-color: rgba(14, 165, 233, 0.15);
        color: #38bdf8;
        border-color: rgba(14, 165, 233, 0.3);
    }
    body.dark-mode .diff-box.diff-empty {
        background-color: #111827;
        color: #94a3b8;
        border-color: #374151;
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
<div class="container-fluid px-3 py-3 caja-wrapper">
    <!-- Header de Página -->
    <div class="dash-header">
        <div>
            <h1 class="dash-header-title font-cairo">
                <i class="fa-solid fa-lock mr-2 text-danger"></i>Arqueo y Cierre de Caja
            </h1>
            <p class="dash-header-subtitle">
                Operación #{{ $apertura->nro_operacion }} — Cuadre, conciliación y cierre de turno
            </p>
        </div>
        <div>
            <a href="{{ route('apertura') }}" class="btn-pos-secondary">
                <i class="fa fa-arrow-left mr-1"></i> Volver a Turnos
            </a>
        </div>
    </div>

    <!-- 4 Tarjetas KPI de Resumen del Turno -->
    <div class="row">
        <!-- 1: Apertura -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #0284c7;">
                <div class="kpi-header">
                    <span class="kpi-label">Fondo de Apertura</span>
                    <div class="kpi-icon-box kpi-icon-blue">
                        <i class="fa-solid fa-door-open"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #0284c7;">
                    Gs. {{ number_format($apertura->apert_monto, 0, ',', '.') }}
                </div>
                <div class="kpi-subtext">
                    <i class="fa-solid fa-clock text-muted"></i> {{ date('d/m/Y', strtotime($apertura->apert_fecha)) }} {{ $apertura->apert_hora }}
                </div>
            </div>
        </div>

        <!-- 2: Entradas -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #16a34a;">
                <div class="kpi-header">
                    <span class="kpi-label">Total Entradas</span>
                    <div class="kpi-icon-box kpi-icon-green">
                        <i class="fa-solid fa-arrow-down"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #166534;">
                    Gs. {{ number_format($entradas, 0, ',', '.') }}
                </div>
                <div class="kpi-subtext" style="color: #166534;">
                    <i class="fa-solid fa-plus-circle"></i> Ventas, cobros y fondo
                </div>
            </div>
        </div>

        <!-- 3: Salidas -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #dc2626;">
                <div class="kpi-header">
                    <span class="kpi-label">Total Salidas</span>
                    <div class="kpi-icon-box kpi-icon-red">
                        <i class="fa-solid fa-arrow-up"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: #b91c1c;">
                    Gs. {{ number_format($salidas, 0, ',', '.') }}
                </div>
                <div class="kpi-subtext" style="color: #b91c1c;">
                    <i class="fa-solid fa-minus-circle"></i> Gastos y retiros de caja
                </div>
            </div>
        </div>

        <!-- 4: Esperado -->
        <div class="col-xl-3 col-sm-6 mb-3">
            <div class="kpi-card" style="border-left: 4px solid #0a4d36;">
                <div class="kpi-header">
                    <span class="kpi-label">Saldo Teórico Esperado</span>
                    <div class="kpi-icon-box kpi-icon-green">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>
                </div>
                <div class="kpi-value font-cairo" style="color: var(--dash-primary);">
                    Gs. {{ number_format($esperado, 0, ',', '.') }}
                </div>
                <div class="kpi-subtext" style="color: var(--dash-primary);">
                    <i class="fa-solid fa-calculator"></i> Total Entradas - Salidas
                </div>
            </div>
        </div>
    </div>

    <!-- Layout Dividido: Arqueo a la Izquierda y Movimientos a la Derecha -->
    <div class="row">
        <!-- Columna Izquierda: Formulario de Cierre y Arqueo -->
        <div class="col-xl-5 col-12">
            <div class="table-card">
                <div class="card-header-pos">
                    <h5><i class="fa-solid fa-calculator mr-2 text-success"></i> Conciliación y Arqueo</h5>
                    <span class="badge badge-light border font-weight-bold">
                        #{{ $apertura->nro_operacion }}
                    </span>
                </div>
                <form method="POST" action="{{ route('apertura.close') }}" id="formCierreCaja">
                    @csrf
                    <input type="hidden" name="nro_operacion" value="{{ $apertura->nro_operacion }}">
                    <input type="hidden" id="esperado" value="{{ $esperado }}">

                    <div class="p-3 p-md-4">
                        <!-- Datos Informativos del Turno -->
                        <div class="turn-info-grid">
                            <div class="turn-info-item">
                                <div class="turn-info-label"><i class="fa fa-building mr-1"></i> Sucursal</div>
                                <div class="turn-info-val">{{ $apertura->suc_desc }}</div>
                            </div>
                            <div class="turn-info-item">
                                <div class="turn-info-label"><i class="fa fa-cash-register mr-1"></i> Caja</div>
                                <div class="turn-info-val">{{ $apertura->caja_descrip }}</div>
                            </div>
                            <div class="turn-info-item">
                                <div class="turn-info-label"><i class="fa fa-user mr-1"></i> Cajero</div>
                                <div class="turn-info-val">{{ $apertura->nom_usuarios }}</div>
                            </div>
                            <div class="turn-info-item">
                                <div class="turn-info-label"><i class="fa fa-clock mr-1"></i> Apertura</div>
                                <div class="turn-info-val">{{ date('d/m/Y', strtotime($apertura->apert_fecha)) }} {{ $apertura->apert_hora }}</div>
                            </div>
                        </div>

                        <!-- Monto Esperado en Sistema -->
                        <div class="esperado-box">
                            <div>
                                <div class="esperado-box-label">Saldo Teórico en Sistema</div>
                                <small class="text-muted">Efectivo total que debe haber en caja</small>
                            </div>
                            <div class="esperado-box-val font-cairo">
                                Gs. {{ number_format($esperado, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Input Monto Contado (Arqueo Físico) -->
                        <div class="form-group mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="monto" class="form-label-custom mb-0">
                                    Efectivo Contado (Arqueo Físico) <span class="text-danger">*</span>
                                </label>
                                <button type="button" class="btn btn-xs btn-outline-success font-weight-bold" onclick="copiarEsperado()">
                                    <i class="fa fa-clone mr-1"></i> Copiar Esperado
                                </button>
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light font-weight-bold text-success" style="font-size: 1.2rem;">Gs.</span>
                                </div>
                                <input
                                    type="number"
                                    class="form-control form-control-pos input-monto-cierre font-cairo"
                                    name="monto"
                                    id="monto"
                                    placeholder="0"
                                    min="0"
                                    step="1"
                                    required
                                    value="{{ old('monto') }}"
                                    oninput="calcDiff()"
                                    autofocus
                                    autocomplete="off"
                                />
                            </div>
                            <small class="text-muted d-block mt-1">Ingresá el total de billetes y monedas que contaste físicamente.</small>
                        </div>

                        <!-- Desglose de Billetes Opcional (Arqueador Inteligente) -->
                        <div class="mb-3">
                            <button class="btn btn-sm btn-outline-secondary w-100 font-weight-bold text-left d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#collapseBilletes" aria-expanded="false">
                                <span><i class="fa fa-calculator mr-1 text-primary"></i> Desglose de billetes (Calculadora)</span>
                                <i class="fa fa-chevron-down small"></i>
                            </button>
                            <div class="collapse mt-2" id="collapseBilletes">
                                <div class="p-2 border rounded bg-light">
                                    <table class="billetes-table">
                                        <thead>
                                            <tr class="text-muted small font-weight-bold">
                                                <th>Billete</th>
                                                <th style="width: 85px;">Cantidad</th>
                                                <th class="text-right">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="font-weight-bold">100.000 Gs.</td>
                                                <td><input type="number" min="0" class="form-control form-control-sm form-control-pos b-cant" data-valor="100000" oninput="calcularDesglose()"></td>
                                                <td class="text-right font-weight-bold text-muted b-sub" id="sub_100k">Gs. 0</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">50.000 Gs.</td>
                                                <td><input type="number" min="0" class="form-control form-control-sm form-control-pos b-cant" data-valor="50000" oninput="calcularDesglose()"></td>
                                                <td class="text-right font-weight-bold text-muted b-sub" id="sub_50k">Gs. 0</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">20.000 Gs.</td>
                                                <td><input type="number" min="0" class="form-control form-control-sm form-control-pos b-cant" data-valor="20000" oninput="calcularDesglose()"></td>
                                                <td class="text-right font-weight-bold text-muted b-sub" id="sub_20k">Gs. 0</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">10.000 Gs.</td>
                                                <td><input type="number" min="0" class="form-control form-control-sm form-control-pos b-cant" data-valor="10000" oninput="calcularDesglose()"></td>
                                                <td class="text-right font-weight-bold text-muted b-sub" id="sub_10k">Gs. 0</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">5.000 Gs.</td>
                                                <td><input type="number" min="0" class="form-control form-control-sm form-control-pos b-cant" data-valor="5000" oninput="calcularDesglose()"></td>
                                                <td class="text-right font-weight-bold text-muted b-sub" id="sub_5k">Gs. 0</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">2.000 Gs.</td>
                                                <td><input type="number" min="0" class="form-control form-control-sm form-control-pos b-cant" data-valor="2000" oninput="calcularDesglose()"></td>
                                                <td class="text-right font-weight-bold text-muted b-sub" id="sub_2k">Gs. 0</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Monedas</td>
                                                <td><input type="number" min="0" class="form-control form-control-sm form-control-pos b-cant" data-valor="1" placeholder="Monto" oninput="calcularDesglose()"></td>
                                                <td class="text-right font-weight-bold text-muted b-sub" id="sub_monedas">Gs. 0</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Caja de Diferencia Dinámica -->
                        <div class="diff-box diff-empty" id="diffBox">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <span class="diff-title" id="diffTitle">Balance / Diferencia</span>
                                <span class="diff-val font-cairo" id="diferencia">Gs. 0</span>
                            </div>
                            <div class="diff-hint" id="diffHint">
                                Ingresá el monto contado para ver el resultado del arqueo.
                            </div>
                        </div>

                        <!-- Botón de Envío -->
                        <button class="btn-pos-primary btn-block btn-lg mt-3" type="submit" id="btnCerrarCaja">
                            <i class="fa-solid fa-lock mr-2"></i> Confirmar Cierre de Caja
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Columna Derecha: Movimientos del Turno -->
        <div class="col-xl-7 col-12">
            <div class="table-card">
                <div class="card-header-pos">
                    <h5>
                        <i class="fa-solid fa-list-check mr-2 text-info"></i> Movimientos del Turno
                    </h5>
                    <span class="badge badge-light border font-weight-bold">
                        {{ count($movimientos) }} registros
                    </span>
                </div>
                <div class="table-responsive" style="max-height: 520px; overflow-y: auto;">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 110px;">Tipo</th>
                                <th style="width: 140px;">Fecha y Hora</th>
                                <th>Concepto</th>
                                <th class="text-right" style="width: 140px;">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($movimientos as $i => $m)
                                <tr>
                                    <td class="align-middle text-muted small">{{ $i + 1 }}</td>
                                    <td class="align-middle">
                                        @if ($m->mov_tipo == 'Entrada')
                                            <span class="badge-mov-in">
                                                <i class="fa fa-arrow-down"></i> Entrada
                                            </span>
                                        @else
                                            <span class="badge-mov-out">
                                                <i class="fa fa-arrow-up"></i> Salida
                                            </span>
                                        @endif
                                    </td>
                                    <td class="align-middle small">
                                        {{ $m->mov_fecha }}
                                    </td>
                                    <td class="align-middle font-weight-bold">
                                        {{ $m->mov_concepto }}
                                    </td>
                                    <td class="align-middle text-right font-weight-bold font-cairo {{ $m->mov_tipo == 'Entrada' ? 'text-success' : 'text-danger' }}">
                                        @if ($m->mov_tipo == 'Salida' && $m->mov_monto > 0)-@endif Gs. {{ number_format($m->mov_monto, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fa fa-receipt fa-2x mb-2 text-secondary opacity-50"></i>
                                        <p class="mb-0">No hay movimientos registrados en este turno.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if (count($movimientos) > 0)
                            <tfoot class="bg-light font-weight-bold">
                                <tr>
                                    <td colspan="4" class="text-right">Total Entradas:</td>
                                    <td class="text-right text-success font-cairo">Gs. {{ number_format($entradas, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right">Total Salidas:</td>
                                    <td class="text-right text-danger font-cairo">Gs. {{ number_format($salidas, 0, ',', '.') }}</td>
                                </tr>
                                <tr style="border-top: 2px solid var(--dash-border);">
                                    <td colspan="4" class="text-right font-weight-bold" style="color: var(--dash-primary);">Saldo Teórico Esperado:</td>
                                    <td class="text-right font-weight-bold font-cairo" style="color: var(--dash-primary); font-size: 1.05rem;">
                                        Gs. {{ number_format($esperado, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function formatGs(n) {
        var neg = n < 0;
        var abs = Math.abs(Math.round(n));
        var s = abs.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return (neg ? '-Gs. ' : 'Gs. ') + s;
    }

    function copiarEsperado() {
        var esperado = Number(document.getElementById('esperado').value || 0);
        var montoInput = document.getElementById('monto');
        if (montoInput) {
            montoInput.value = esperado >= 0 ? esperado : 0;
            calcDiff();
            montoInput.focus();
        }
    }

    function calcularDesglose() {
        var inputs = document.querySelectorAll('.b-cant');
        var total = 0;
        inputs.forEach(function (inp) {
            var cant = parseInt(inp.value) || 0;
            var val = parseInt(inp.dataset.valor) || 0;
            var sub = cant * val;
            total += sub;

            // Actualizar celda de subtotal
            var subCell = inp.closest('tr').querySelector('.b-sub');
            if (subCell) {
                subCell.textContent = formatGs(sub);
            }
        });

        var montoInput = document.getElementById('monto');
        if (montoInput) {
            montoInput.value = total;
            calcDiff();
        }
    }

    function calcDiff() {
        var esperado = Number(document.getElementById('esperado').value || 0);
        var montoVal = document.getElementById('monto').value;
        var contado = Number(montoVal || 0);
        var diff = contado - esperado;
        var box = document.getElementById('diffBox');
        var label = document.getElementById('diferencia');
        var title = document.getElementById('diffTitle');
        var hint = document.getElementById('diffHint');

        box.classList.remove('diff-zero', 'diff-missing', 'diff-surplus', 'diff-empty');

        if (montoVal === '') {
            box.classList.add('diff-empty');
            title.textContent = 'Balance / Diferencia';
            label.textContent = 'Gs. 0';
            hint.textContent = 'Ingresá el monto contado para ver el resultado del arqueo.';
            return;
        }

        label.textContent = (diff >= 0 ? '+ ' : '') + formatGs(diff);

        if (diff === 0) {
            box.classList.add('diff-zero');
            title.textContent = 'Arqueo Exacto / Cuadrado';
            label.textContent = 'Gs. 0';
            hint.textContent = '¡Excelente! El efectivo contado coincide exactamente con el sistema.';
        } else if (diff < 0) {
            box.classList.add('diff-missing');
            title.textContent = 'Faltante en Caja';
            hint.textContent = 'Atención: Hay un faltante de dinero respecto al saldo esperado.';
        } else {
            box.classList.add('diff-surplus');
            title.textContent = 'Sobrante en Caja';
            hint.textContent = 'Atención: Hay un sobrante de dinero respecto al saldo esperado.';
        }
    }

    document.getElementById('formCierreCaja').addEventListener('submit', function (e) {
        e.preventDefault();
        var form = this;
        var esperado = Number(document.getElementById('esperado').value || 0);
        var montoEl = document.getElementById('monto');
        var contado = Number(montoEl.value || 0);

        if (montoEl.value === '' || isNaN(contado) || contado < 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Monto de cierre inválido',
                text: 'Por favor ingresá el monto contado en efectivo para cerrar la caja.',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#0a4d36'
            });
            montoEl.focus();
            return;
        }

        var diff = contado - esperado;
        var diffLabel = formatGs(diff);
        var estadoArqueo = diff === 0
            ? '<span class="text-success font-weight-bold">Arqueo Cuadrado (Exacto)</span>'
            : (diff < 0
                ? '<span class="text-danger font-weight-bold">Faltante: ' + diffLabel + '</span>'
                : '<span class="text-primary font-weight-bold">Sobrante: +' + diffLabel + '</span>');

        Swal.fire({
            title: '¿Confirmar cierre de caja?',
            html:
                '<div class="text-left py-2 px-3 border rounded bg-light" style="font-size: 0.95rem;">' +
                '<p class="mb-1"><strong>Operación:</strong> #{{ $apertura->nro_operacion }}</p>' +
                '<p class="mb-1"><strong>Caja:</strong> {{ $apertura->caja_descrip }} ({{ $apertura->suc_desc }})</p>' +
                '<p class="mb-1"><strong>Esperado:</strong> ' + formatGs(esperado) + '</p>' +
                '<p class="mb-1"><strong>Contado:</strong> ' + formatGs(contado) + '</p>' +
                '<p class="mb-0"><strong>Resultado:</strong> ' + estadoArqueo + '</p>' +
                '</div>',
            icon: diff === 0 ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0a4d36',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa fa-lock mr-1"></i> Sí, cerrar caja',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then(function (result) {
            if (result.value || result.isConfirmed) {
                Swal.fire({
                    title: 'Cerrando caja...',
                    text: 'Procesando arqueo y enviando resumen...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: function () {
                        Swal.showLoading();
                    }
                });
                form.submit();
            }
        });
    });

    calcDiff();
    activarMenu('m_caja', 'm_apertura');
</script>
@endsection
