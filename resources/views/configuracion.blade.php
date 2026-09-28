@extends('layouts.app')
@section('title', 'Ajustes del Sistema')

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
        --dash-card-bg: #ffffff;
        --dash-panel-bg: #f8fafc;
        --dash-border: #e2e8f0;
        --dash-text-main: #1c2430;
        --dash-text-muted: #64748b;
        --dash-success: #10b981;
        --dash-success-light: #ecfdf5;
        --dash-info: #0284c7;
        --dash-info-light: #f0f9ff;
        --dash-warning: #f59e0b;
        --dash-warning-light: #fffbeb;
        --dash-danger: #ef4444;
        --dash-danger-light: #fee2e2;
    }

    body.dark-mode {
        --dash-primary: #10b981;
        --dash-primary-dark: #059669;
        --dash-primary-light: #064e3b;
        --dash-primary-border: #047857;
        --dash-accent: #f59e0b;
        --dash-card-bg: #1e293b;
        --dash-panel-bg: #0f172a;
        --dash-border: #334155;
        --dash-text-main: #f1f5f9;
        --dash-text-muted: #94a3b8;
        --dash-success: #34d399;
        --dash-success-light: #064e3b;
        --dash-info: #38bdf8;
        --dash-info-light: #082f49;
        --dash-warning: #fbbf24;
        --dash-warning-light: #451a03;
        --dash-danger: #f87171;
        --dash-danger-light: #450a0a;
    }

    #app {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
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
        background: var(--dash-primary-light);
        color: var(--dash-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        box-shadow: 0 2px 8px rgba(10, 77, 54, 0.12);
        flex-shrink: 0;
    }
    .dash-header-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dash-primary);
        margin: 0;
        line-height: 1.2;
    }
    body.dark-mode .dash-header-title {
        color: #10b981;
    }
    .dash-header-subtitle {
        font-size: 0.88rem;
        color: var(--dash-text-muted);
        margin: 0.2rem 0 0;
    }
    .dash-header-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* Primary Save Button */
    .btn-pos-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.55rem 1.35rem;
        background: var(--dash-primary);
        border: 1px solid var(--dash-primary);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.92rem;
        border-radius: 10px;
        transition: all 0.15s ease-in-out;
        text-decoration: none !important;
        box-shadow: 0 2px 6px rgba(10, 77, 54, 0.2);
        cursor: pointer;
    }
    .btn-pos-primary:hover:not(:disabled) {
        background: var(--dash-primary-dark);
        border-color: var(--dash-primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(10, 77, 54, 0.3);
    }
    .btn-pos-primary:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* KPI Summary Cards */
    .kpi-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
        margin-bottom: 1.25rem;
    }
    .kpi-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        padding: 1rem 1.15rem;
        display: flex;
        align-items: center;
        gap: 0.9rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        cursor: pointer;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.06);
    }
    .kpi-card.active {
        border-color: var(--dash-primary);
        background: var(--dash-primary-light);
    }
    body.dark-mode .kpi-card.active {
        background: rgba(16, 185, 129, 0.12);
        border-color: var(--dash-primary);
    }
    .kpi-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .kpi-icon-green {
        background: #dcfce7;
        color: #166534;
    }
    .kpi-icon-blue {
        background: #e0f2fe;
        color: #0369a1;
    }
    .kpi-icon-amber {
        background: #fef3c7;
        color: #92400e;
    }
    .kpi-icon-purple {
        background: #f3e8ff;
        color: #6b21a8;
    }
    body.dark-mode .kpi-icon-green {
        background: #064e3b;
        color: #a7f3d0;
    }
    body.dark-mode .kpi-icon-blue {
        background: #0c4a6e;
        color: #bae6fd;
    }
    body.dark-mode .kpi-icon-amber {
        background: #451a03;
        color: #fde68a;
    }
    body.dark-mode .kpi-icon-purple {
        background: #3b0764;
        color: #e9d5ff;
    }
    .kpi-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--dash-text-muted);
        font-weight: 700;
        margin-bottom: 0.2rem;
    }
    .kpi-val {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--dash-text-main);
        line-height: 1.2;
        margin: 0;
    }
    .kpi-sub {
        font-size: 0.78rem;
        color: var(--dash-text-muted);
        margin-top: 0.2rem;
    }

    /* Tab Navigation */
    .settings-nav {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        padding: 0.45rem;
        margin-bottom: 1.25rem;
        overflow-x: auto;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }
    .settings-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.15rem;
        border-radius: 10px;
        border: none;
        background: transparent;
        color: var(--dash-text-muted);
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.15s ease;
        white-space: nowrap;
        cursor: pointer;
    }
    .settings-tab-btn:hover {
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
    }
    .settings-tab-btn.active {
        background: var(--dash-primary);
        color: #ffffff !important;
        font-weight: 700;
        box-shadow: 0 2px 6px rgba(10, 77, 54, 0.25);
    }
    body.dark-mode .settings-tab-btn.active {
        background: #10b981;
        color: #042f2e !important;
    }
    .tab-badge {
        padding: 0.15rem 0.5rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
    }
    .settings-tab-btn.active .tab-badge {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }
    body.dark-mode .settings-tab-btn.active .tab-badge {
        background: rgba(0, 0, 0, 0.25);
        color: #042f2e;
    }

    /* Content Cards */
    .settings-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }
    .card-section-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--dash-border);
    }
    .card-section-title h5 {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--dash-text-main);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .card-section-desc {
        font-size: 0.84rem;
        color: var(--dash-text-muted);
        margin: 0.25rem 0 0;
    }

    /* Option Grid Cards (for Radio-style selectors) */
    .options-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 0.85rem;
        margin-bottom: 1.25rem;
    }
    .option-box {
        border: 2px solid var(--dash-border);
        background: var(--dash-panel-bg);
        border-radius: 12px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.15s ease;
        position: relative;
    }
    .option-box:hover {
        border-color: var(--dash-primary-border);
        transform: translateY(-2px);
    }
    .option-box.selected {
        border-color: var(--dash-primary);
        background: var(--dash-primary-light);
    }
    body.dark-mode .option-box.selected {
        background: rgba(16, 185, 129, 0.1);
        border-color: #10b981;
    }
    .option-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.35rem;
    }
    .option-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--dash-text-main);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .option-box.selected .option-title {
        color: var(--dash-primary);
    }
    body.dark-mode .option-box.selected .option-title {
        color: #34d399;
    }
    .option-desc {
        font-size: 0.82rem;
        color: var(--dash-text-muted);
        line-height: 1.35;
        margin: 0;
    }
    .option-check {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid var(--dash-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        color: transparent;
        background: var(--dash-card-bg);
        transition: all 0.15s ease;
    }
    .option-box.selected .option-check {
        border-color: var(--dash-primary);
        background: var(--dash-primary);
        color: #ffffff;
    }
    body.dark-mode .option-box.selected .option-check {
        border-color: #10b981;
        background: #10b981;
        color: #042f2e;
    }

    /* Form Fields */
    .pos-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--dash-text-main);
        margin-bottom: 0.4rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .pos-input, .pos-select {
        width: 100%;
        padding: 0.55rem 0.85rem;
        border-radius: 10px;
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        font-size: 0.9rem;
        transition: all 0.15s ease;
    }
    .pos-input:focus, .pos-select:focus {
        outline: none;
        border-color: var(--dash-primary);
        background: var(--dash-card-bg);
        box-shadow: 0 0 0 3px var(--dash-primary-light);
    }
    body.dark-mode .pos-input:focus, body.dark-mode .pos-select:focus {
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }
    .pos-help-text {
        font-size: 0.78rem;
        color: var(--dash-text-muted);
        margin-top: 0.3rem;
        display: block;
    }

    /* Input with action/toggle */
    .input-action-group {
        position: relative;
        display: flex;
        align-items: center;
    }
    .input-action-group .pos-input {
        padding-right: 2.5rem;
    }
    .input-action-btn {
        position: absolute;
        right: 8px;
        background: transparent;
        border: none;
        color: var(--dash-text-muted);
        cursor: pointer;
        padding: 4px 6px;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: color 0.15s;
    }
    .input-action-btn:hover {
        color: var(--dash-primary);
    }

    /* Modern Switch Toggle */
    .modern-switch {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.85rem 1.15rem;
        border-radius: 12px;
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        margin-bottom: 1rem;
        transition: all 0.15s ease;
    }
    .modern-switch:hover {
        border-color: var(--dash-primary-border);
    }
    .switch-label-wrap {
        display: flex;
        flex-direction: column;
    }
    .switch-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--dash-text-main);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .switch-desc {
        font-size: 0.8rem;
        color: var(--dash-text-muted);
        margin-top: 0.15rem;
    }
    .switch-ui {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 26px;
        flex-shrink: 0;
    }
    .switch-ui input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .switch-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #cbd5e1;
        transition: .3s;
        border-radius: 34px;
    }
    body.dark-mode .switch-slider {
        background-color: #475569;
    }
    .switch-slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    .switch-ui input:checked + .switch-slider {
        background-color: var(--dash-primary);
    }
    body.dark-mode .switch-ui input:checked + .switch-slider {
        background-color: #10b981;
    }
    .switch-ui input:checked + .switch-slider:before {
        transform: translateX(22px);
    }

    /* Preset Buttons for SMTP */
    .preset-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    .preset-pill-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .preset-pill-btn:hover {
        background: var(--dash-card-bg);
        border-color: var(--dash-primary);
        color: var(--dash-primary);
    }

    /* Callout notice boxes */
    .callout-box {
        border-radius: 12px;
        padding: 0.85rem 1rem;
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
        margin-bottom: 1.25rem;
        font-size: 0.84rem;
        line-height: 1.4;
    }
    .callout-info {
        background: var(--dash-info-light);
        border: 1px solid rgba(2, 132, 199, 0.2);
        color: var(--dash-info);
    }
    .callout-warning {
        background: var(--dash-warning-light);
        border: 1px solid rgba(245, 158, 11, 0.25);
        color: #92400e;
    }
    body.dark-mode .callout-warning {
        color: #fde68a;
    }
    .callout-box i {
        font-size: 1.1rem;
        margin-top: 0.1rem;
        flex-shrink: 0;
    }

    /* Camera Preview Area */
    .cam-preview-container {
        border-radius: 14px;
        border: 2px dashed var(--dash-border);
        background: var(--dash-panel-bg);
        padding: 1.5rem;
        text-align: center;
        min-height: 220px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    .cam-preview-img {
        max-width: 100%;
        max-height: 280px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border: 1px solid var(--dash-border);
    }
    .cam-timestamp-badge {
        position: absolute;
        bottom: 12px;
        right: 12px;
        background: rgba(0, 0, 0, 0.75);
        color: #ffffff;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-family: monospace;
    }

    /* Secondary Action Buttons (Tests) */
    .btn-pos-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1.15rem;
        border-radius: 10px;
        border: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        color: var(--dash-text-main);
        font-weight: 600;
        font-size: 0.88rem;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-pos-secondary:hover:not(:disabled) {
        border-color: var(--dash-primary);
        color: var(--dash-primary);
        background: var(--dash-panel-bg);
    }
    .btn-pos-secondary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    /* Floating Save Bar */
    .floating-save-bar {
        position: sticky;
        bottom: 1rem;
        z-index: 100;
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        padding: 0.85rem 1.25rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 1.5rem;
        backdrop-filter: blur(8px);
    }
    body.dark-mode .floating-save-bar {
        background: rgba(30, 41, 59, 0.95);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .dash-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .dash-header-actions {
            width: 100%;
        }
        .dash-header-actions .btn-pos-primary {
            width: 100%;
        }
        .floating-save-bar {
            flex-direction: column;
            align-items: stretch;
            text-align: center;
        }
    }
</style>
@endsection

@section('main')
<div class="container-fluid" id="app" v-cloak>
    
    <!-- DASHBOARD HEADER -->
    <div class="dash-header">
        <div class="dash-header-left">
            <div class="dash-header-icon">
                <i class="fa fa-sliders-h"></i>
            </div>
            <div>
                <h4 class="dash-header-title font-cairo">Ajustes Generales del Sistema</h4>
                <p class="dash-header-subtitle">Configuración global de cajas, ventas, alertas por correo y cámara de seguridad</p>
            </div>
        </div>
        <div class="dash-header-actions">
            <button class="btn-pos-primary font-cairo" @click="update" :disabled="guardando" title="Presioná Ctrl + S para guardar">
                <i class="fa" :class="guardando ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                <span>@{{ guardando ? 'Guardando ajustes...' : 'Guardar Ajustes' }}</span>
                <span class="badge badge-light ml-1 d-none d-md-inline" style="opacity: 0.8; font-size: 0.7rem;">Ctrl+S</span>
            </button>
        </div>
    </div>

    <!-- QUICK STATUS KPI ROW -->
    <div class="kpi-row">
        <!-- KPI 1: Caja -->
        <div class="kpi-card" :class="{ 'active': activeTab === 'caja' }" @click="activeTab = 'caja'">
            <div class="kpi-icon-box kpi-icon-green">
                <i class="fa fa-cash-register"></i>
            </div>
            <div>
                <div class="kpi-label">Apertura de Caja</div>
                <div class="kpi-val font-cairo">@{{ labelValidez }}</div>
                <div class="kpi-sub">@{{ labelUsuario }}</div>
            </div>
        </div>

        <!-- KPI 2: POS / Ventas -->
        <div class="kpi-card" :class="{ 'active': activeTab === 'venta' }" @click="activeTab = 'venta'">
            <div class="kpi-icon-box kpi-icon-blue">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div>
                <div class="kpi-label">Punto de Venta</div>
                <div class="kpi-val font-cairo">@{{ venta.tipo_comprobante }} (@{{ venta.tamano_ticket }})</div>
                <div class="kpi-sub">@{{ venta.descontar_stock == 1 ? 'Descuenta stock' : 'Sin descontar stock' }}</div>
            </div>
        </div>

        <!-- KPI 3: Servidor Correo -->
        <div class="kpi-card" :class="{ 'active': activeTab === 'mail' }" @click="activeTab = 'mail'">
            <div class="kpi-icon-box kpi-icon-amber">
                <i class="fa fa-envelope-open-text"></i>
            </div>
            <div>
                <div class="kpi-label">Servidor SMTP</div>
                <div class="kpi-val font-cairo">
                    <span v-if="mail.activo" class="text-success"><i class="fa fa-check-circle mr-1"></i>Activo</span>
                    <span v-else class="text-muted"><i class="fa fa-pause-circle mr-1"></i>Inactivo</span>
                </div>
                <div class="kpi-sub">@{{ mail.host ? mail.host : 'Sin servidor configurado' }}</div>
            </div>
        </div>

        <!-- KPI 4: Cámara IP -->
        <div class="kpi-card" :class="{ 'active': activeTab === 'camara' }" @click="activeTab = 'camara'">
            <div class="kpi-icon-box kpi-icon-purple">
                <i class="fa fa-video"></i>
            </div>
            <div>
                <div class="kpi-label">Cámara IP (CCTV)</div>
                <div class="kpi-val font-cairo">
                    <span v-if="camara.url" class="text-info"><i class="fa fa-video mr-1"></i>Configurada</span>
                    <span v-else class="text-muted"><i class="fa fa-video-slash mr-1"></i>Sin configurar</span>
                </div>
                <div class="kpi-sub">@{{ camara.url ? ('Canal ' + camara.canal) : 'Monitoreo desactivado' }}</div>
            </div>
        </div>

        <!-- KPI 5: Mapas & GPS -->
        <div class="kpi-card" :class="{ 'active': activeTab === 'mapas' }" @click="activeTab = 'mapas'">
            <div class="kpi-icon-box" :class="proveedorEfectivo === 'google' ? 'kpi-icon-blue' : 'kpi-icon-green'">
                <i class="fa fa-map-marked-alt"></i>
            </div>
            <div>
                <div class="kpi-label">Geolocalización GPS</div>
                <div class="kpi-val font-cairo">
                    <span v-if="proveedorEfectivo === 'google'" class="text-primary font-weight-bold">
                        <i class="fa-brands fa-google mr-1"></i> Google Maps
                    </span>
                    <span v-else class="text-success font-weight-bold">
                        <i class="fa fa-globe mr-1"></i> OpenStreetMap
                    </span>
                </div>
                <div class="kpi-sub">
                    <span v-if="mapas.google_maps_api_key">API Key configurada</span>
                    <span v-else>Modo libre (Sin API Key)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MODERN NAVIGATION TABS -->
    <div class="settings-nav">
        <button class="settings-tab-btn" :class="{ 'active': activeTab === 'caja' }" @click="activeTab = 'caja'">
            <i class="fa fa-cash-register"></i>
            <span>Caja y Turnos</span>
        </button>
        <button class="settings-tab-btn" :class="{ 'active': activeTab === 'venta' }" @click="activeTab = 'venta'">
            <i class="fa fa-receipt"></i>
            <span>Punto de Venta (POS)</span>
        </button>
        <button class="settings-tab-btn" :class="{ 'active': activeTab === 'mail' }" @click="activeTab = 'mail'">
            <i class="fa fa-envelope"></i>
            <span>Servidor de Correo (SMTP)</span>
            <span class="tab-badge" :class="mail.activo ? 'badge-success' : 'badge-secondary'">
                @{{ mail.activo ? 'Activo' : 'Off' }}
            </span>
        </button>
        <button class="settings-tab-btn" :class="{ 'active': activeTab === 'camara' }" @click="activeTab = 'camara'">
            <i class="fa fa-video"></i>
            <span>Cámara IP & Seguridad</span>
            <span class="tab-badge" :class="camara.url ? 'badge-info' : 'badge-secondary'">
                @{{ camara.url ? 'Conectada' : 'Off' }}
            </span>
        </button>
        <button class="settings-tab-btn" :class="{ 'active': activeTab === 'mapas' }" @click="activeTab = 'mapas'">
            <i class="fa fa-map-marked-alt"></i>
            <span>Geolocalización & Mapas</span>
            <span class="tab-badge" :class="proveedorEfectivo === 'google' ? 'badge-primary' : 'badge-success'">
                @{{ proveedorEfectivo === 'google' ? 'Google Maps' : 'OpenStreetMap' }}
            </span>
        </button>
    </div>

    <!-- ========================================== -->
    <!-- TAB 1: CAJA Y TURNOS                      -->
    <!-- ========================================== -->
    <div v-show="activeTab === 'caja'">
        <div class="settings-card">
            <div class="card-section-title">
                <div>
                    <h5 class="font-cairo"><i class="fa fa-clock text-success"></i> Validez de la Apertura de Caja</h5>
                    <p class="card-section-desc">Determina cuándo debe vencer una apertura de caja o solicitar arqueo de cierre.</p>
                </div>
            </div>

            <div class="options-grid">
                <!-- Option 1: Día a día -->
                <div class="option-box" :class="{ 'selected': caja[0].value == '1' }" @click="caja[0].value = '1'">
                    <div class="option-header">
                        <div class="option-title">
                            <i class="fa fa-calendar-day"></i> Día a día por fecha
                        </div>
                        <div class="option-check"><i class="fa fa-check"></i></div>
                    </div>
                    <p class="option-desc">La apertura caduca al finalizar el día calendario (medianoche). Exige abrir una nueva caja cada jornada.</p>
                </div>

                <!-- Option 2: Cada 24 Horas -->
                <div class="option-box" :class="{ 'selected': caja[0].value == '2' }" @click="caja[0].value = '2'">
                    <div class="option-header">
                        <div class="option-title">
                            <i class="fa fa-history"></i> Cada 24 horas
                        </div>
                        <div class="option-check"><i class="fa fa-check"></i></div>
                    </div>
                    <p class="option-desc">Permite turnos continuos durante exactamente 24 horas a partir del momento exacto en que fue abierta.</p>
                </div>

                <!-- Option 3: Indefinido -->
                <div class="option-box" :class="{ 'selected': caja[0].value == '3' }" @click="caja[0].value = '3'">
                    <div class="option-header">
                        <div class="option-title">
                            <i class="fa fa-infinity"></i> Indefinido
                        </div>
                        <div class="option-check"><i class="fa fa-check"></i></div>
                    </div>
                    <p class="option-desc">La caja permanece abierta indefinidamente hasta que un usuario realice el arqueo y cierre manual.</p>
                </div>
            </div>

            <div class="card-section-title mt-4">
                <div>
                    <h5 class="font-cairo"><i class="fa fa-users text-primary"></i> Modalidad de Usuario en Caja</h5>
                    <p class="card-section-desc">Configura si los usuarios comparten una sola caja activa o cada cajero abre su propia caja.</p>
                </div>
            </div>

            <div class="options-grid">
                <!-- Option 1: Una apertura multiusuario -->
                <div class="option-box" :class="{ 'selected': caja[1].value == '1' }" @click="caja[1].value = '1'">
                    <div class="option-header">
                        <div class="option-title">
                            <i class="fa fa-user-friends"></i> Multiusuario compartido
                        </div>
                        <div class="option-check"><i class="fa fa-check"></i></div>
                    </div>
                    <p class="option-desc">Una sola apertura global en la sucursal. Todos los cajeros facturan y operan sobre el mismo arqueo.</p>
                </div>

                <!-- Option 2: Una por usuario -->
                <div class="option-box" :class="{ 'selected': caja[1].value == '2' }" @click="caja[1].value = '2'">
                    <div class="option-header">
                        <div class="option-title">
                            <i class="fa fa-user-tag"></i> Apertura por cada usuario
                        </div>
                        <div class="option-check"><i class="fa fa-check"></i></div>
                    </div>
                    <p class="option-desc">Cada cajero debe abrir su propia caja individual con su propio saldo inicial y realiza su propio cierre.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 2: PUNTO DE VENTA (POS)               -->
    <!-- ========================================== -->
    <div v-show="activeTab === 'venta'">
        <div class="settings-card">
            <div class="card-section-title">
                <div>
                    <h5 class="font-cairo"><i class="fa fa-receipt text-info"></i> Facturación y Formato de Impresión</h5>
                    <p class="card-section-desc">Establece el comprobante predeterminado y las dimensiones del papel para tickets.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="pos-label">
                        <i class="fa fa-file-invoice"></i> Comprobante predeterminado
                    </label>
                    <div class="options-grid" style="grid-template-columns: repeat(3, 1fr);">
                        <div class="option-box text-center" :class="{ 'selected': venta.tipo_comprobante === 'Ticket' }" @click="venta.tipo_comprobante = 'Ticket'">
                            <i class="fa fa-receipt fa-lg mb-2 text-primary"></i>
                            <div class="font-weight-bold">Ticket</div>
                            <small class="text-muted d-block">Térmico POS</small>
                        </div>
                        <div class="option-box text-center" :class="{ 'selected': venta.tipo_comprobante === 'Factura' }" @click="venta.tipo_comprobante = 'Factura'">
                            <i class="fa fa-file-invoice-dollar fa-lg mb-2 text-success"></i>
                            <div class="font-weight-bold">Factura</div>
                            <small class="text-muted d-block">Fiscal / Timbrado</small>
                        </div>
                        <div class="option-box text-center" :class="{ 'selected': venta.tipo_comprobante === 'Comprobante' }" @click="venta.tipo_comprobante = 'Comprobante'">
                            <i class="fa fa-file-alt fa-lg mb-2 text-warning"></i>
                            <div class="font-weight-bold">Comprobante</div>
                            <small class="text-muted d-block">Recibo interno</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="pos-label">
                        <i class="fa fa-print"></i> Ancho del Ticket Térmico
                    </label>
                    <div class="options-grid" style="grid-template-columns: repeat(2, 1fr);">
                        <div class="option-box text-center" :class="{ 'selected': venta.tamano_ticket === '80mm' }" @click="venta.tamano_ticket = '80mm'">
                            <i class="fa fa-newspaper fa-lg mb-2 text-info"></i>
                            <div class="font-weight-bold">80 mm</div>
                            <small class="text-muted d-block">Estándar (Epson / Hasar)</small>
                        </div>
                        <div class="option-box text-center" :class="{ 'selected': venta.tamano_ticket === '56mm' }" @click="venta.tamano_ticket = '56mm'">
                            <i class="fa fa-scroll fa-lg mb-2 text-secondary"></i>
                            <div class="font-weight-bold">56 mm</div>
                            <small class="text-muted d-block">Angosto / Portátil (POS-58)</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-section-title mt-4">
                <div>
                    <h5 class="font-cairo"><i class="fa fa-boxes text-warning"></i> Control de Stock e Inventario en Ventas</h5>
                    <p class="card-section-desc">Reglas para descuento automático y ventas con inventario en cero o negativo.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="modern-switch">
                        <div class="switch-label-wrap">
                            <span class="switch-title"><i class="fa fa-box-open text-primary"></i> Descontar stock al confirmar venta</span>
                            <span class="switch-desc">Resta de manera automática la cantidad vendida del depósito asignado.</span>
                        </div>
                        <label class="switch-ui">
                            <input type="checkbox" v-model="venta.descontar_stock" :true-value="1" :false-value="0">
                            <span class="switch-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="modern-switch">
                        <div class="switch-label-wrap">
                            <span class="switch-title"><i class="fa fa-exclamation-triangle text-warning"></i> Permitir vender sin stock</span>
                            <span class="switch-desc">Permite completar la venta aun cuando el artículo tenga existencia cero o negativa.</span>
                        </div>
                        <label class="switch-ui">
                            <input type="checkbox" v-model="venta.vender_sin_stock" :true-value="1" :false-value="0">
                            <span class="switch-slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 3: SERVIDOR DE CORREO (SMTP)          -->
    <!-- ========================================== -->
    <div v-show="activeTab === 'mail'">
        <div class="settings-card">
            <div class="card-section-title">
                <div>
                    <h5 class="font-cairo"><i class="fa fa-envelope text-primary"></i> Configuración del Servidor SMTP</h5>
                    <p class="card-section-desc">Envío de resúmenes gerenciales de caja, cierres y notificaciones automáticas.</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn-pos-secondary" @click="testMail" :disabled="mailTesting || !mail.host || !mail.username">
                        <i class="fa" :class="mailTesting ? 'fa-spinner fa-spin' : 'fa-paper-plane'"></i>
                        <span>@{{ mailTesting ? 'Enviando prueba...' : 'Enviar Correo de Prueba' }}</span>
                    </button>
                </div>
            </div>

            <!-- Master Switch -->
            <div class="modern-switch mb-3">
                <div class="switch-label-wrap">
                    <span class="switch-title"><i class="fa fa-paper-plane text-success"></i> Habilitar envío de correos desde el sistema</span>
                    <span class="switch-desc">Activa el motor de correo saliente para reportes y notificaciones automáticas.</span>
                </div>
                <label class="switch-ui">
                    <input type="checkbox" v-model="mail.activo">
                    <span class="switch-slider"></span>
                </label>
            </div>

            <!-- Presets Rápidos -->
            <div class="preset-pills">
                <span class="text-muted small align-self-center mr-2"><i class="fa fa-magic"></i> Ajuste rápido:</span>
                <button type="button" class="preset-pill-btn" @click="applySmtpPreset('gmail')">
                    <i class="fab fa-google text-danger"></i> Gmail (587 TLS)
                </button>
                <button type="button" class="preset-pill-btn" @click="applySmtpPreset('outlook')">
                    <i class="fab fa-microsoft text-primary"></i> Outlook / Office 365 (587 STARTTLS)
                </button>
                <button type="button" class="preset-pill-btn" @click="applySmtpPreset('ssl')">
                    <i class="fa fa-shield-alt text-success"></i> cPanel / SSL Directo (465 SSL)
                </button>
            </div>

            <div class="row">
                <!-- Host SMTP -->
                <div class="col-md-6 mb-3">
                    <label class="pos-label"><i class="fa fa-server"></i> Servidor Host SMTP</label>
                    <input type="text" class="pos-input" v-model.trim="mail.host" placeholder="smtp.gmail.com">
                    <span class="pos-help-text">Ejemplo: smtp.gmail.com, smtp.office365.com, mail.tudominio.com</span>
                </div>

                <!-- Puerto -->
                <div class="col-md-3 mb-3">
                    <label class="pos-label"><i class="fa fa-network-wired"></i> Puerto</label>
                    <input type="number" class="pos-input" v-model="mail.port" placeholder="587">
                    <span class="pos-help-text">Comunes: 587 (TLS) ó 465 (SSL)</span>
                </div>

                <!-- Cifrado -->
                <div class="col-md-3 mb-3">
                    <label class="pos-label"><i class="fa fa-lock"></i> Cifrado</label>
                    <select class="pos-select" v-model="mail.encryption">
                        <option value="tls">TLS (Recomendado)</option>
                        <option value="ssl">SSL</option>
                        <option value="null">Ninguno / Sin cifrado</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <!-- Usuario SMTP -->
                <div class="col-md-6 mb-3">
                    <label class="pos-label"><i class="fa fa-user"></i> Usuario SMTP / Correo de autenticación</label>
                    <input type="text" class="pos-input" v-model.trim="mail.username" placeholder="usuario@dominio.com" autocomplete="off">
                    <span class="pos-help-text">Tu dirección completa de correo electrónico</span>
                </div>

                <!-- Contraseña SMTP -->
                <div class="col-md-6 mb-3">
                    <label class="pos-label"><i class="fa fa-key"></i> Contraseña o App Password</label>
                    <div class="input-action-group">
                        <input :type="showMailPassword ? 'text' : 'password'" class="pos-input" v-model="mail.password"
                            placeholder="Dejar vacío para no modificar la actual" autocomplete="new-password">
                        <button type="button" class="input-action-btn" @click="showMailPassword = !showMailPassword" :title="showMailPassword ? 'Ocultar' : 'Mostrar'">
                            <i class="fa" :class="showMailPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    <span class="pos-help-text">Para Gmail se requiere "Contraseña de Aplicación" de 16 caracteres</span>
                </div>
            </div>

            <div class="row">
                <!-- Remitente (From Address) -->
                <div class="col-md-6 mb-3">
                    <label class="pos-label"><i class="fa fa-at"></i> Correo Remitente (From Address)</label>
                    <input type="email" class="pos-input" v-model.trim="mail.from_address" placeholder="noreply@tudominio.com">
                    <span class="pos-help-text">Opcional. Si se deja en blanco se utilizará el usuario SMTP</span>
                </div>

                <!-- Nombre Remitente (From Name) -->
                <div class="col-md-6 mb-3">
                    <label class="pos-label"><i class="fa fa-id-badge"></i> Nombre visible del Remitente</label>
                    <input type="text" class="pos-input" v-model.trim="mail.from_name" placeholder="SoftSystem Ventas">
                    <span class="pos-help-text">Nombre que aparecerá en la bandeja de entrada del receptor</span>
                </div>
            </div>

            <!-- Destinatarios de Reportes -->
            <div class="mb-3">
                <label class="pos-label"><i class="fa fa-users"></i> Destinatarios de Reportes (Emails de Gerencia / Administración)</label>
                <input type="text" class="pos-input" v-model.trim="mail.to" placeholder="admin@empresa.com, gerencia@empresa.com">
                <span class="pos-help-text"><i class="fa fa-info-circle"></i> Podés colocar múltiples correos electrónicos separados por comas (,) o punto y coma (;).</span>
            </div>

            <!-- Switch Cierre Caja -->
            <div class="modern-switch mt-2">
                <div class="switch-label-wrap">
                    <span class="switch-title"><i class="fa fa-file-invoice-dollar text-success"></i> Enviar Resumen Gerencial automático al Cerrar Caja</span>
                    <span class="switch-desc">Envía por correo el balance completo de ventas, cobros, gastos y arqueo en cuanto el cajero cierra su turno.</span>
                </div>
                <label class="switch-ui">
                    <input type="checkbox" v-model="mail.cierre_caja">
                    <span class="switch-slider"></span>
                </label>
            </div>

            <div class="callout-box callout-info mt-3">
                <i class="fa fa-lightbulb"></i>
                <div>
                    <strong>Recomendación para cuentas Google / Gmail:</strong><br>
                    Si utilizás Gmail con verificación en 2 pasos, debés generar una <em>Contraseña de Aplicaciones</em> desde tu cuenta Google (Seguridad &rarr; Verificación en dos pasos &rarr; Contraseñas de aplicaciones) y pegarla en el campo contraseña.
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 4: CÁMARA IP & SEGURIDAD (CCTV)       -->
    <!-- ========================================== -->
    <div v-show="activeTab === 'camara'">
        <div class="settings-card">
            <div class="card-section-title">
                <div>
                    <h5 class="font-cairo"><i class="fa fa-video text-purple"></i> Integración con Cámara IP (CCTV)</h5>
                    <p class="card-section-desc">Captura instantánea de fotografías durante aperturas de caja, ventas o auditoría de seguridad.</p>
                </div>
                <div>
                    <button type="button" class="btn-pos-secondary" @click="testCamara" :disabled="camaraTesting || !camara.url">
                        <i class="fa" :class="camaraTesting ? 'fa-spinner fa-spin' : 'fa-camera'"></i>
                        <span>@{{ camaraTesting ? 'Probando captura...' : 'Capturar Foto de Prueba' }}</span>
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-7">
                    <!-- URL / IP -->
                    <div class="mb-3">
                        <label class="pos-label"><i class="fa fa-globe"></i> Dirección IP o URL Snapshot de la cámara</label>
                        <input type="text" class="pos-input" v-model.trim="camara.url" placeholder="192.168.1.101 o http://192.168.1.101/ISAPI/Streaming/channels/102/picture">
                        <span class="pos-help-text">Si ingresás solo la IP local, el sistema generará automáticamente la URL ISAPI Hikvision / Dahua.</span>
                    </div>

                    <div class="row">
                        <!-- Usuario Cámara -->
                        <div class="col-md-6 mb-3">
                            <label class="pos-label"><i class="fa fa-user"></i> Usuario de la Cámara</label>
                            <input type="text" class="pos-input" v-model.trim="camara.user" placeholder="admin" autocomplete="off">
                        </div>

                        <!-- Contraseña Cámara -->
                        <div class="col-md-6 mb-3">
                            <label class="pos-label"><i class="fa fa-key"></i> Contraseña de la Cámara</label>
                            <div class="input-action-group">
                                <input :type="showCamPassword ? 'text' : 'password'" class="pos-input" v-model="camara.password"
                                    placeholder="Dejar vacío para mantener la actual" autocomplete="new-password">
                                <button type="button" class="input-action-btn" @click="showCamPassword = !showCamPassword" :title="showCamPassword ? 'Ocultar' : 'Mostrar'">
                                    <i class="fa" :class="showCamPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Canal -->
                    <div class="mb-3">
                        <label class="pos-label"><i class="fa fa-broadcast-tower"></i> Canal de Stream (Hikvision / NVR)</label>
                        <select class="pos-select" v-model="camara.canal">
                            <option value="102">Canal 102 — Sub stream (Recomendado para captura rápida y bajo peso)</option>
                            <option value="101">Canal 101 — Main stream (Alta resolución)</option>
                            <option value="202">Canal 202 — Cámara 2 Sub stream</option>
                            <option value="201">Canal 201 — Cámara 2 Main stream</option>
                        </select>
                        <span class="pos-help-text">El Sub-stream (102) es ideal para guardar fotos instantáneas en base de datos sin retardar el ticket.</span>
                    </div>

                    <div class="callout-box callout-info mt-3">
                        <i class="fa fa-info-circle"></i>
                        <div>
                            <strong>Compatibilidad:</strong><br>
                            Soporta cámaras Hikvision, Hilook, Dahua y compatibles con protocolo ISAPI / HTTP Digest Snapshot. Asegurate de que la cámara esté en la misma red local que el servidor o accesible vía IP estática.
                        </div>
                    </div>
                </div>

                <!-- Visor / Preview -->
                <div class="col-lg-5">
                    <label class="pos-label"><i class="fa fa-image"></i> Previsualización en Vivo de la Cámara</label>
                    <div class="cam-preview-container">
                        <div v-if="camaraTesting" class="text-center py-4">
                            <i class="fa fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                            <div class="font-weight-bold">Conectando con la cámara IP...</div>
                            <small class="text-muted">Obteniendo frame ISAPI</small>
                        </div>
                        <div v-else-if="camaraPreview" class="w-100 text-center position-relative">
                            <img :src="camaraPreview" alt="Captura de cámara" class="cam-preview-img">
                            <div class="cam-timestamp-badge" v-if="camaraTimestamp">
                                <i class="fa fa-clock mr-1"></i>@{{ camaraTimestamp }}
                            </div>
                        </div>
                        <div v-else class="text-muted text-center py-4">
                            <i class="fa fa-video fa-3x mb-3 text-secondary" style="opacity: 0.4;"></i>
                            <div class="font-weight-bold text-dark dark:text-white">Sin captura reciente</div>
                            <small class="text-muted d-block mb-3">Hacé clic en "Capturar Foto de Prueba" para verificar la transmisión.</small>
                            <button type="button" class="btn btn-sm btn-outline-primary" @click="testCamara" :disabled="camaraTesting || !camara.url">
                                <i class="fa fa-camera mr-1"></i> Probar ahora
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 5: GEOLOCALIZACIÓN Y MAPAS (GPS)       -->
    <!-- ========================================== -->
    <div v-show="activeTab === 'mapas'">
        <div class="settings-card">
            <div class="card-section-title">
                <div>
                    <h5 class="font-cairo"><i class="fa fa-map-marked-alt text-success"></i> Proveedor de Mapas para Clientes y Entregas</h5>
                    <p class="card-section-desc">Selecciona qué servicio de mapas se utilizará para geolocalizar clientes, fijar coordenadas y trazar rutas.</p>
                </div>
            </div>

            <!-- Selector de Proveedor -->
            <div class="options-grid">
                <!-- Option 1: Auto -->
                <div class="option-box" :class="{ 'selected': mapas.proveedor === 'auto' }" @click="mapas.proveedor = 'auto'">
                    <div class="option-header">
                        <div class="option-title">
                            <i class="fa fa-wand-magic-sparkles text-primary"></i> Automático (Recomendado)
                        </div>
                        <div class="option-check"><i class="fa fa-check"></i></div>
                    </div>
                    <p class="option-desc">Usa <strong>Google Maps</strong> si la API Key está configurada; si no hay clave, conmuta automáticamente a <strong>OpenStreetMap</strong> sin errores.</p>
                </div>

                <!-- Option 2: Google Maps -->
                <div class="option-box" :class="{ 'selected': mapas.proveedor === 'google' }" @click="mapas.proveedor = 'google'">
                    <div class="option-header">
                        <div class="option-title">
                            <i class="fa-brands fa-google text-danger"></i> Google Maps Platform
                        </div>
                        <div class="option-check"><i class="fa fa-check"></i></div>
                    </div>
                    <p class="option-desc">Usa Google Maps con soporte para vista satelital y búsqueda de lugares. Si la clave falta o falla, usa OpenStreetMap como respaldo.</p>
                </div>

                <!-- Option 3: OpenStreetMap -->
                <div class="option-box" :class="{ 'selected': mapas.proveedor === 'openstreet' }" @click="mapas.proveedor = 'openstreet'">
                    <div class="option-header">
                        <div class="option-title">
                            <i class="fa fa-globe text-success"></i> OpenStreetMap (Leaflet)
                        </div>
                        <div class="option-check"><i class="fa fa-check"></i></div>
                    </div>
                    <p class="option-desc">Totalmente libre y gratuito. Funciona sin ninguna API Key, sin tarjeta de crédito y sin límites de peticiones.</p>
                </div>
            </div>

            <!-- Callout Informativo -->
            <div class="callout-box" :class="mapas.google_maps_api_key ? 'callout-info' : 'callout-warning'">
                <i class="fa" :class="mapas.google_maps_api_key ? 'fa-circle-info' : 'fa-triangle-exclamation'"></i>
                <div>
                    <strong v-if="mapas.google_maps_api_key">Google Maps activo con API Key registrada:</strong>
                    <strong v-else>Operando en modo libre (OpenStreetMap):</strong>
                    <span v-if="mapas.google_maps_api_key">
                        El sistema usará la API de Google Maps. Si la clave expira o excede cuota, el sistema continuará funcionando sin interrupción usando OpenStreetMap.
                    </span>
                    <span v-else>
                        No has ingresado una API Key de Google Maps. El sistema utiliza OpenStreetMap de forma automática, sin costo y sin necesidad de configuración adicional.
                    </span>
                </div>
            </div>

            <!-- Clave de Google Maps -->
            <div class="card-section-title mt-4">
                <div>
                    <h5 class="font-cairo"><i class="fa-brands fa-google text-danger"></i> Credenciales de Google Maps API</h5>
                    <p class="card-section-desc">API Key de Google Cloud Platform con la librería "Maps JavaScript API" habilitada.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label-custom font-weight-bold">Google Maps API Key</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-key text-muted"></i></span>
                        </div>
                        <input :type="showMapApiKey ? 'text' : 'password'"
                               class="form-control form-control-pos font-cairo"
                               v-model="mapas.google_maps_api_key"
                               placeholder="Ej: AIzaSyD-..." />
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" @click="showMapApiKey = !showMapApiKey" :title="showMapApiKey ? 'Ocultar clave' : 'Mostrar clave'">
                                <i class="fa" :class="showMapApiKey ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                            <button v-if="mapas.google_maps_api_key" class="btn btn-outline-danger" type="button" @click="mapas.google_maps_api_key = ''" title="Borrar clave">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <small class="text-muted mt-1 d-block">
                        <i class="fa fa-info-circle mr-1 text-info"></i> Puedes generar una clave gratuita en
                        <a href="https://console.cloud.google.com/google/maps-apis/credentials" target="_blank" class="text-primary font-weight-bold">
                            Google Cloud Console <i class="fa fa-external-link-alt small"></i>
                        </a>. Habilita <em>Maps JavaScript API</em>.
                    </small>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label-custom font-weight-bold">Estado del Proveedor</label>
                    <div class="p-2 rounded border" :style="{ background: 'var(--dash-panel-bg)' }">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="small text-muted">Proveedor activo:</span>
                            <span class="badge" :class="proveedorEfectivo === 'google' ? 'badge-primary' : 'badge-success'">
                                @{{ proveedorEfectivo === 'google' ? 'Google Maps' : 'OpenStreetMap' }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="small text-muted">API Key presente:</span>
                            <span class="badge" :class="mapas.google_maps_api_key ? 'badge-success' : 'badge-secondary'">
                                @{{ mapas.google_maps_api_key ? 'Sí' : 'No (Libre)' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coordenadas por defecto -->
            <div class="card-section-title mt-3">
                <div>
                    <h5 class="font-cairo"><i class="fa fa-crosshairs text-info"></i> Centro y Zoom Predeterminado del Mapa</h5>
                    <p class="card-section-desc">Ubicación geográfica inicial cuando se abre el mapa de un cliente nuevo sin coordenadas.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label-custom">Latitud Inicial</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-arrows-up-down text-muted"></i></span>
                        </div>
                        <input type="text" class="form-control form-control-pos font-cairo" v-model="mapas.mapas_lat_default" placeholder="-25.263740" />
                    </div>
                    <small class="text-muted">Por defecto: Asunción (-25.263740)</small>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label-custom">Longitud Inicial</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-arrows-left-right text-muted"></i></span>
                        </div>
                        <input type="text" class="form-control form-control-pos font-cairo" v-model="mapas.mapas_lng_default" placeholder="-57.575920" />
                    </div>
                    <small class="text-muted">Por defecto: Asunción (-57.575920)</small>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label-custom">Nivel de Zoom (10 a 19)</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-magnifying-glass text-muted"></i></span>
                        </div>
                        <select class="form-control form-control-pos" v-model.number="mapas.mapas_zoom_default">
                            <option :value="12">12 - Ciudad / Región</option>
                            <option :value="14">14 - Barrio</option>
                            <option :value="16">16 - Calles y Manzanas (Recomendado)</option>
                            <option :value="18">18 - Detalle de Edificio</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FLOATING BOTTOM SAVE BAR -->
    <div class="floating-save-bar">
        <div class="d-flex align-items-center gap-2">
            <span class="badge badge-success px-2 py-1"><i class="fa fa-check-circle mr-1"></i>Configuración lista</span>
            <span class="text-muted small d-none d-md-inline">Los cambios se aplican de inmediato en caja, POS y servidores.</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary btn-sm" @click="resetToDefaults" type="button">
                <i class="fa fa-undo mr-1"></i> Revertir cambios
            </button>
            <button class="btn-pos-primary font-cairo" @click="update" :disabled="guardando">
                <i class="fa" :class="guardando ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                <span>@{{ guardando ? 'Guardando ajustes...' : 'Guardar Ajustes del Sistema' }}</span>
            </button>
        </div>
    </div>

</div>
@endsection

@section('script')
<script>
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    @php
        $validezVal = '1';
        $usuarioVal = '1';
        foreach ($ajuste as $a) {
            if ($a->name === 'validez') $validezVal = (string)$a->value;
            if ($a->name === 'usuario') $usuarioVal = (string)$a->value;
        }
    @endphp

    var app = new Vue({
        el: '#app',
        data: {
            activeTab: 'caja',
            guardando: false,
            mailTesting: false,
            camaraTesting: false,
            camaraPreview: null,
            camaraTimestamp: null,
            showMailPassword: false,
            showCamPassword: false,
            showMapApiKey: false,
            mapas: {
                proveedor: @json($mapas['proveedor'] ?? 'auto'),
                google_maps_api_key: @json($mapas['google_maps_api_key'] ?? ''),
                mapas_lat_default: @json($mapas['mapas_lat_default'] ?? '-25.263740'),
                mapas_lng_default: @json($mapas['mapas_lng_default'] ?? '-57.575920'),
                mapas_zoom_default: @json($mapas['mapas_zoom_default'] ?? 16)
            },
            caja: [
                {
                    name: 'validez',
                    value: @json($validezVal)
                },
                {
                    name: 'usuario',
                    value: @json($usuarioVal)
                }
            ],
            venta: {
                tipo_comprobante: 'Ticket',
                descontar_stock: 1,
                vender_sin_stock: 1,
                tamano_ticket: '80mm',
            },
            camara: {
                url: @json($camara['url'] ?? ''),
                user: @json($camara['user'] ?? 'admin'),
                password: '',
                canal: @json($camara['canal'] ?? '102')
            },
            mail: {
                activo: {{ !empty($mail['activo']) ? 'true' : 'false' }},
                host: @json($mail['host'] ?? ''),
                port: @json($mail['port'] ?? '587'),
                username: @json($mail['username'] ?? ''),
                password: '',
                encryption: @json($mail['encryption'] ?? 'tls'),
                from_address: @json($mail['from_address'] ?? ''),
                from_name: @json($mail['from_name'] ?? 'SoftSystem'),
                to: @json($mail['to'] ?? ''),
                cierre_caja: {{ !empty($mail['cierre_caja']) ? 'true' : 'false' }}
            }
        },
        computed: {
            proveedorEfectivo: function () {
                var p = (this.mapas && this.mapas.proveedor) || 'auto';
                var key = ((this.mapas && this.mapas.google_maps_api_key) || '').trim();
                if (key === '' || p === 'openstreet') {
                    return 'openstreet';
                }
                return 'google';
            },
            labelValidez: function () {
                var v = this.caja[0] ? this.caja[0].value : '1';
                if (v == '1') return 'Día a día (Fecha)';
                if (v == '2') return 'Cada 24 Horas';
                if (v == '3') return 'Indefinido';
                return 'Día a día';
            },
            labelUsuario: function () {
                var u = this.caja[1] ? this.caja[1].value : '1';
                if (u == '1') return 'Multiusuario compartido';
                if (u == '2') return 'Por cada usuario';
                return 'Multiusuario';
            }
        },
        methods: {
            mapasPayload: function () {
                return {
                    proveedor: this.mapas.proveedor,
                    google_maps_api_key: (this.mapas.google_maps_api_key || '').trim(),
                    mapas_lat_default: this.mapas.mapas_lat_default,
                    mapas_lng_default: this.mapas.mapas_lng_default,
                    mapas_zoom_default: this.mapas.mapas_zoom_default
                };
            },
            mailPayload: function () {
                var payload = Object.assign({}, this.mail);
                if (!payload.password) {
                    delete payload.password;
                }
                return payload;
            },
            camaraPayload: function () {
                var payload = Object.assign({}, this.camara);
                if (!payload.password) {
                    delete payload.password;
                }
                return payload;
            },
            applySmtpPreset: function (type) {
                if (type === 'gmail') {
                    this.mail.host = 'smtp.gmail.com';
                    this.mail.port = 587;
                    this.mail.encryption = 'tls';
                    Toast.fire({ title: 'Valores predeterminados de Gmail aplicados', icon: 'info' });
                } else if (type === 'outlook') {
                    this.mail.host = 'smtp.office365.com';
                    this.mail.port = 587;
                    this.mail.encryption = 'tls';
                    Toast.fire({ title: 'Valores de Outlook / Office 365 aplicados', icon: 'info' });
                } else if (type === 'ssl') {
                    this.mail.port = 465;
                    this.mail.encryption = 'ssl';
                    Toast.fire({ title: 'Configuración SSL (Puerto 465) aplicada', icon: 'info' });
                }
            },
            updateCaja: function () {
                var cajaPayload = [
                    { name: 'validez', value: this.caja[0].value },
                    { name: 'usuario', value: this.caja[1].value },
                    { name: 'tipo_comprobante', value: this.venta.tipo_comprobante }
                ];
                return axios.post('{{ url('ajustes') }}', {
                    caja: cajaPayload,
                    mail: this.mailPayload(),
                    camara: this.camaraPayload(),
                    mapas: this.mapasPayload()
                });
            },
            updateVenta: function () {
                localStorage.setItem('config_venta', JSON.stringify(this.venta));
            },
            getConfigVenta: function () {
                var venta = localStorage.getItem('config_venta');
                if (venta != null) {
                    try {
                        var parsed = JSON.parse(venta);
                        if (parsed) {
                            if (parsed.tipo_comprobante) this.venta.tipo_comprobante = parsed.tipo_comprobante;
                            if (typeof parsed.descontar_stock !== 'undefined') this.venta.descontar_stock = parsed.descontar_stock;
                            if (typeof parsed.vender_sin_stock !== 'undefined') this.venta.vender_sin_stock = parsed.vender_sin_stock;
                            if (parsed.tamano_ticket) this.venta.tamano_ticket = parsed.tamano_ticket;
                        }
                    } catch (e) {}
                }
            },
            resetToDefaults: function () {
                var self = this;
                Swal.fire({
                    title: '¿Revertir cambios sin guardar?',
                    text: 'Se recargarán los ajustes almacenados en el servidor.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, recargar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#0a4d36'
                }).then(function (result) {
                    if (result.value) {
                        window.location.reload();
                    }
                });
            },
            update: function () {
                var self = this;
                this.guardando = true;
                this.updateVenta();
                this.updateCaja()
                    .then(function () {
                        self.guardando = false;
                        self.mail.password = '';
                        self.camara.password = '';
                        Toast.fire({ title: 'Ajustes del sistema actualizados con éxito', icon: 'success' });
                    })
                    .catch(function (error) {
                        self.guardando = false;
                        var msg = (error.response && error.response.data && error.response.data.message)
                            ? error.response.data.message
                            : 'No se pudieron guardar los ajustes';
                        Swal.fire({
                            title: 'Error al Guardar',
                            text: msg,
                            icon: 'error',
                            confirmButtonColor: '#ef4444'
                        });
                    });
            },
            testMail: function () {
                var self = this;
                this.mailTesting = true;
                axios.post('{{ route('ajuste.mail.test') }}', { mail: this.mailPayload() })
                    .then(function (response) {
                        self.mailTesting = false;
                        self.mail.password = '';
                        Swal.fire({
                            title: '¡Correo Enviado!',
                            text: response.data.message || 'El mensaje de prueba se envió correctamente.',
                            icon: 'success',
                            confirmButtonColor: '#0a4d36'
                        });
                    })
                    .catch(function (error) {
                        self.mailTesting = false;
                        var msg = (error.response && error.response.data && error.response.data.message)
                            ? error.response.data.message
                            : 'No se pudo enviar el correo de prueba';
                        Swal.fire({
                            title: 'Error de Envío SMTP',
                            text: msg,
                            icon: 'error',
                            confirmButtonColor: '#ef4444'
                        });
                    });
            },
            testCamara: function () {
                var self = this;
                this.camaraTesting = true;
                this.camaraPreview = null;
                axios.post('{{ route('ajuste.camara.test') }}', { camara: this.camaraPayload() })
                    .then(function (response) {
                        self.camaraTesting = false;
                        self.camara.password = '';
                        self.camaraPreview = response.data.preview || null;
                        var now = new Date();
                        self.camaraTimestamp = now.toLocaleDateString() + ' ' + now.toLocaleTimeString();
                        Toast.fire({ title: response.data.message || 'Captura de cámara exitosa', icon: 'success' });
                    })
                    .catch(function (error) {
                        self.camaraTesting = false;
                        var msg = (error.response && error.response.data && error.response.data.message)
                            ? error.response.data.message
                            : 'No se pudo capturar imagen desde la cámara';
                        Swal.fire({
                            title: 'Error de Cámara IP',
                            text: msg,
                            icon: 'error',
                            confirmButtonColor: '#ef4444'
                        });
                    });
            }
        },
        mounted: function () {
            this.getConfigVenta();
            activarMenu('m_ajuste');

            var self = this;
            // Hotkey Ctrl + S to save
            window.addEventListener('keydown', function (e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    if (!self.guardando) {
                        self.update();
                    }
                }
            });
        }
    });
</script>
@endsection
