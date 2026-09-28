@extends('layouts.app')
@section('title', 'Datos de la Empresa')

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
        --dash-info: #0284c7;
        --dash-info-light: #f0f9ff;
        --dash-warning: #f59e0b;
        --dash-danger: #ef4444;
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
        --dash-info: #38bdf8;
        --dash-info-light: #082f49;
        --dash-warning: #fbbf24;
        --dash-danger: #f87171;
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

    /* Primary POS Button */
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
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.06);
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
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .kpi-sub {
        font-size: 0.78rem;
        color: var(--dash-text-muted);
        margin-top: 0.2rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Content Cards */
    .settings-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 16px;
        padding: 1.35rem 1.5rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }
    .card-section-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.2rem;
        padding-bottom: 0.65rem;
        border-bottom: 1px solid var(--dash-border);
    }
    .card-section-title h5 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--dash-text-main);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .card-section-desc {
        font-size: 0.82rem;
        color: var(--dash-text-muted);
        margin: 0.2rem 0 0;
    }

    /* Form Fields */
    .pos-label {
        font-size: 0.84rem;
        font-weight: 700;
        color: var(--dash-text-main);
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .pos-label .req {
        color: var(--dash-danger);
    }
    .pos-input, .pos-select, .pos-textarea {
        width: 100%;
        padding: 0.55rem 0.85rem;
        border-radius: 10px;
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        font-size: 0.9rem;
        transition: all 0.15s ease;
    }
    .pos-input:focus, .pos-select:focus, .pos-textarea:focus {
        outline: none;
        border-color: var(--dash-primary);
        background: var(--dash-card-bg);
        box-shadow: 0 0 0 3px var(--dash-primary-light);
    }
    body.dark-mode .pos-input:focus, body.dark-mode .pos-select:focus, body.dark-mode .pos-textarea:focus {
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }
    .pos-help-text {
        font-size: 0.77rem;
        color: var(--dash-text-muted);
        margin-top: 0.25rem;
        display: block;
    }

    /* Logo Upload Box */
    .logo-uploader-box {
        border: 2px dashed var(--dash-border);
        border-radius: 14px;
        background: var(--dash-panel-bg);
        padding: 1.25rem;
        text-align: center;
        transition: all 0.2s ease;
        position: relative;
    }
    .logo-uploader-box:hover {
        border-color: var(--dash-primary);
    }
    .logo-current-img {
        max-height: 100px;
        max-width: 100%;
        object-fit: contain;
        border-radius: 8px;
        background: #ffffff;
        padding: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--dash-border);
    }
    .btn-file-select {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 1rem;
        border-radius: 8px;
        border: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        color: var(--dash-text-main);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        margin-top: 0.75rem;
    }
    .btn-file-select:hover {
        border-color: var(--dash-primary);
        color: var(--dash-primary);
        background: var(--dash-primary-light);
    }

    /* Thermal Ticket Preview Simulation */
    .ticket-container {
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        padding: 1.25rem;
    }
    .ticket-preview-box {
        background: #fffdf5;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        padding: 1.5rem 1.25rem;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
        font-family: 'Courier New', Courier, monospace;
        color: #1e293b;
        max-width: 320px;
        margin: 0 auto;
        position: relative;
    }
    body.dark-mode .ticket-preview-box {
        background: #f8fafc;
        color: #0f172a;
    }
    .ticket-logo {
        max-height: 52px;
        max-width: 170px;
        object-fit: contain;
        margin: 0 auto 0.65rem auto;
        display: block;
        filter: grayscale(100%) contrast(140%);
    }
    .ticket-title {
        font-weight: 700;
        font-size: 1.05rem;
        text-align: center;
        line-height: 1.2;
        margin-bottom: 0.35rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .ticket-sub {
        font-size: 0.78rem;
        text-align: center;
        line-height: 1.35;
        margin-bottom: 0.2rem;
        color: #334155;
    }
    .ticket-divider {
        border-top: 1px dashed #94a3b8;
        margin: 0.65rem 0;
    }
    .ticket-tag {
        display: inline-block;
        font-size: 0.72rem;
        padding: 0.15rem 0.45rem;
        border-radius: 4px;
        background: #e2e8f0;
        color: #334155;
        text-align: center;
        margin-bottom: 0.5rem;
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
                <i class="fa fa-building"></i>
            </div>
            <div>
                <h4 class="dash-header-title font-cairo">Datos de la Empresa</h4>
                <p class="dash-header-subtitle">Configuración tributaria, sucursal principal, canales de contacto y membrete de comprobantes</p>
            </div>
        </div>
        <div class="dash-header-actions">
            <button class="btn-pos-primary font-cairo" @click="update" :disabled="guardando" title="Presioná Ctrl + S para guardar">
                <i class="fa" :class="guardando ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                <span>@{{ guardando ? 'Guardando...' : 'Guardar Cambios' }}</span>
                <span class="badge badge-light ml-1 d-none d-md-inline" style="opacity: 0.8; font-size: 0.7rem;">Ctrl+S</span>
            </button>
        </div>
    </div>

    <!-- QUICK STATUS KPI ROW -->
    <div class="kpi-row">
        <!-- KPI 1: Empresa -->
        <div class="kpi-card">
            <div class="kpi-icon-box kpi-icon-green">
                <i class="fa fa-store-alt"></i>
            </div>
            <div style="min-width: 0;">
                <div class="kpi-label">Razón Social</div>
                <div class="kpi-val font-cairo" :title="empresa.nombre">@{{ empresa.nombre || 'Sin definir' }}</div>
                <div class="kpi-sub">Empresa Emisora Principal</div>
            </div>
        </div>

        <!-- KPI 2: RUC -->
        <div class="kpi-card">
            <div class="kpi-icon-box kpi-icon-blue">
                <i class="fa fa-id-card"></i>
            </div>
            <div style="min-width: 0;">
                <div class="kpi-label">Identificador Tributario</div>
                <div class="kpi-val font-cairo">RUC: @{{ empresa.ruc || 'No especificado' }}</div>
                <div class="kpi-sub">Registro Único de Contribuyente</div>
            </div>
        </div>

        <!-- KPI 3: Sucursal & Ciudad -->
        <div class="kpi-card">
            <div class="kpi-icon-box kpi-icon-amber">
                <i class="fa fa-map-marker-alt"></i>
            </div>
            <div style="min-width: 0;">
                <div class="kpi-label">Ubicación y Sucursal</div>
                <div class="kpi-val font-cairo">@{{ currentCiudadName }}</div>
                <div class="kpi-sub">@{{ currentSucursalName }}</div>
            </div>
        </div>

        <!-- KPI 4: Logotipo -->
        <div class="kpi-card">
            <div class="kpi-icon-box kpi-icon-purple">
                <i class="fa fa-image"></i>
            </div>
            <div style="min-width: 0;">
                <div class="kpi-label">Logotipo de Comprobantes</div>
                <div class="kpi-val font-cairo">
                    <span v-if="logoPreview || empresa.logo" class="text-success"><i class="fa fa-check-circle mr-1"></i>Cargado</span>
                    <span v-else class="text-muted"><i class="fa fa-times-circle mr-1"></i>Sin logo</span>
                </div>
                <div class="kpi-sub">Tickets, facturas y reportes</div>
            </div>
        </div>
    </div>

    <!-- MAIN FORM GRID -->
    <div class="row">
        <!-- LEFT COLUMN: Identidad Legal y Canales de Contacto -->
        <div class="col-lg-7">
            
            <!-- CARD 1: Identidad Legal y Sucursal -->
            <div class="settings-card">
                <div class="card-section-title">
                    <div>
                        <h5 class="font-cairo"><i class="fa fa-id-card text-success"></i> Identidad Legal y Establecimiento</h5>
                        <p class="card-section-desc">Datos fiscales y domicilio comercial utilizados en comprobantes y tributación.</p>
                    </div>
                </div>

                <div class="row">
                    <!-- Nombre de la empresa -->
                    <div class="col-md-8 mb-3">
                        <label class="pos-label">
                            <i class="fa fa-store"></i> Nombre comercial / Razón social <span class="req">*</span>
                        </label>
                        <input type="text" class="pos-input" v-model.trim="empresa.nombre" placeholder="Ej: COMERCIAL DAIANITA">
                        <span class="pos-help-text">Nombre principal visible en facturas, tickets y membretes.</span>
                    </div>

                    <!-- RUC -->
                    <div class="col-md-4 mb-3">
                        <label class="pos-label">
                            <i class="fa fa-id-badge"></i> R.U.C. <span class="req">*</span>
                        </label>
                        <input type="text" class="pos-input" v-model.trim="empresa.ruc" placeholder="Ej: 5263934-1">
                        <span class="pos-help-text">Con o sin dígito verificador.</span>
                    </div>
                </div>

                <div class="row">
                    <!-- Sucursal -->
                    <div class="col-md-6 mb-3">
                        <label class="pos-label">
                            <i class="fa fa-store-alt"></i> Sucursal asignada
                        </label>
                        <select class="pos-select" v-model="empresa.sucursal">
                            @foreach ($sucursales as $s)
                            <option value="{{ $s->suc_cod }}">{{ trim($s->suc_desc) }}</option>
                            @endforeach
                        </select>
                        <span class="pos-help-text">Sucursal base para emisión de documentos.</span>
                    </div>

                    <!-- Ciudad -->
                    <div class="col-md-6 mb-3">
                        <label class="pos-label">
                            <i class="fa fa-city"></i> Ciudad / Distrito
                        </label>
                        <select class="pos-select" v-model="empresa.ciudad">
                            @foreach ($ciudades as $c)
                            <option value="{{ $c->CIUDAD_cod }}">{{ $c->ciudad_nombre }}</option>
                            @endforeach
                        </select>
                        <span class="pos-help-text">Ubicación municipal de la sede principal.</span>
                    </div>
                </div>

                <!-- Dirección -->
                <div class="mb-3">
                    <label class="pos-label">
                        <i class="fa fa-map-marked-alt"></i> Dirección comercial
                    </label>
                    <input type="text" class="pos-input" v-model.trim="empresa.direccion" placeholder="Calle, número, esquina o barrio">
                    <span class="pos-help-text">Dirección exacta que se imprimirá en los comprobantes de venta.</span>
                </div>
            </div>

            <!-- CARD 2: Canales de Comunicación -->
            <div class="settings-card">
                <div class="card-section-title">
                    <div>
                        <h5 class="font-cairo"><i class="fa fa-phone-alt text-primary"></i> Canales de Contacto y Atención</h5>
                        <p class="card-section-desc">Teléfonos, correos y redes para contacto de clientes y proveedores.</p>
                    </div>
                </div>

                <div class="row">
                    <!-- Celular -->
                    <div class="col-md-6 mb-3">
                        <label class="pos-label">
                            <i class="fa fa-mobile-alt"></i> Celular / WhatsApp
                        </label>
                        <input type="text" class="pos-input" v-model.trim="empresa.celular" placeholder="Ej: 0982 446 445">
                        <span class="pos-help-text">Número móvil o de mensajería instantánea.</span>
                    </div>

                    <!-- Teléfono Fijo -->
                    <div class="col-md-6 mb-3">
                        <label class="pos-label">
                            <i class="fa fa-phone"></i> Teléfono fijo
                        </label>
                        <input type="text" class="pos-input" v-model.trim="empresa.telefono" placeholder="Ej: 021 123 456">
                        <span class="pos-help-text">Línea fija o conmutador de oficina.</span>
                    </div>
                </div>

                <div class="row">
                    <!-- Correo -->
                    <div class="col-md-6 mb-3">
                        <label class="pos-label">
                            <i class="fa fa-envelope"></i> Correo institucional
                        </label>
                        <input type="email" class="pos-input" v-model.trim="empresa.correo" placeholder="contacto@empresa.com">
                        <span class="pos-help-text">Email de atención al cliente o facturación.</span>
                    </div>

                    <!-- Página Web -->
                    <div class="col-md-6 mb-3">
                        <label class="pos-label">
                            <i class="fa fa-globe"></i> Página Web o Red Social
                        </label>
                        <input type="text" class="pos-input" v-model.trim="empresa.web" placeholder="www.empresa.com.py">
                        <span class="pos-help-text">Sitio web, Instagram o página de Facebook.</span>
                    </div>
                </div>

                <!-- Descripción / Rubro -->
                <div class="mb-0">
                    <label class="pos-label">
                        <i class="fa fa-tag"></i> Rubro y Actividad comercial
                    </label>
                    <textarea class="pos-textarea" rows="3" v-model="empresa.descripcion" placeholder="Ej: Tienda - Mercería - Bazar. Venta de Muebles y Electrodomésticos."></textarea>
                    <span class="pos-help-text">Texto secundario de actividad comercial que se visualiza en los tickets térmicos.</span>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: Logotipo y Simulación en Vivo de Ticket -->
        <div class="col-lg-5">

            <!-- CARD 3: Logotipo Institucional -->
            <div class="settings-card">
                <div class="card-section-title">
                    <div>
                        <h5 class="font-cairo"><i class="fa fa-image text-purple"></i> Logotipo Institucional</h5>
                        <p class="card-section-desc">Imagen impresa en tickets de caja, facturas y recibos.</p>
                    </div>
                </div>

                <div class="logo-uploader-box">
                    <div v-if="logoPreview" class="mb-3">
                        <span class="badge badge-info mb-2"><i class="fa fa-eye mr-1"></i>Nuevo logo listo para guardar</span>
                        <div>
                            <img :src="logoPreview" alt="Nuevo logo" class="logo-current-img">
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger mt-2" @click="quitarLogoSeleccionado">
                            <i class="fa fa-times mr-1"></i> Descartar nuevo logo
                        </button>
                    </div>
                    <div v-else-if="empresa.logo" class="mb-3">
                        <span class="badge badge-success mb-2"><i class="fa fa-check mr-1"></i>Logo actual configurado</span>
                        <div>
                            <img :src="'{{ asset('img') }}/' + empresa.logo" alt="Logo actual" class="logo-current-img"
                                onerror="this.src='{{ asset('img/sinimagen.png') }}'">
                        </div>
                        <small class="text-muted d-block mt-1 font-weight-bold">@{{ empresa.logo }}</small>
                    </div>
                    <div v-else class="text-muted py-3">
                        <i class="fa fa-image fa-3x mb-2 text-secondary" style="opacity: 0.4;"></i>
                        <div class="font-weight-bold">Sin logotipo cargado</div>
                        <small>Se utilizará solo el nombre comercial en texto plano.</small>
                    </div>

                    <input type="file" id="logoFileInput" ref="logoFileInput" class="d-none" accept="image/png, image/jpeg, image/jpg" @change="onLogoChange">
                    
                    <button type="button" class="btn-file-select" @click="$refs.logoFileInput.click()">
                        <i class="fa fa-cloud-upload-alt"></i>
                        <span>@{{ (logoPreview || empresa.logo) ? 'Cambiar Logotipo' : 'Subir Logotipo' }}</span>
                    </button>
                    <span class="pos-help-text mt-2">Recomendado: Formato PNG transparente o JPG claro (máx. 2MB).</span>
                </div>
            </div>

            <!-- CARD 4: Vista Previa de Ticket Térmico -->
            <div class="settings-card">
                <div class="card-section-title">
                    <div>
                        <h5 class="font-cairo"><i class="fa fa-receipt text-warning"></i> Simulación de Membrete Térmico</h5>
                        <p class="card-section-desc">Vista previa en tiempo real del encabezado que saldrá en tu impresora POS.</p>
                    </div>
                </div>

                <div class="ticket-container">
                    <div class="text-center mb-2">
                        <span class="ticket-tag"><i class="fa fa-print mr-1"></i>Impresora Térmica 80mm / 56mm</span>
                    </div>

                    <div class="ticket-preview-box">
                        <!-- Ticket Logo -->
                        <div v-if="logoPreview">
                            <img :src="logoPreview" alt="Logo Ticket" class="ticket-logo">
                        </div>
                        <div v-else-if="empresa.logo">
                            <img :src="'{{ asset('img') }}/' + empresa.logo" alt="Logo Ticket" class="ticket-logo"
                                onerror="this.style.display='none'">
                        </div>

                        <!-- Ticket Business Name -->
                        <div class="ticket-title">@{{ empresa.nombre || 'NOMBRE DE EMPRESA' }}</div>
                        
                        <!-- Description / Rubro -->
                        <div class="ticket-sub font-italic" v-if="empresa.descripcion" style="font-size: 0.72rem; white-space: pre-line;">
                            @{{ empresa.descripcion }}
                        </div>

                        <div class="ticket-divider"></div>

                        <!-- Tax ID / RUC -->
                        <div class="ticket-sub font-weight-bold">
                            RUC: @{{ empresa.ruc || 'XXX-XXXX' }}
                        </div>

                        <!-- Address & City -->
                        <div class="ticket-sub" v-if="empresa.direccion">
                            @{{ empresa.direccion }}
                        </div>
                        <div class="ticket-sub" v-if="currentCiudadName">
                            @{{ currentCiudadName }} - @{{ currentSucursalName }}
                        </div>

                        <!-- Phone / Contact -->
                        <div class="ticket-sub" v-if="empresa.celular || empresa.telefono">
                            <span v-if="empresa.telefono">Tel: @{{ empresa.telefono }}</span>
                            <span v-if="empresa.telefono && empresa.celular"> — </span>
                            <span v-if="empresa.celular">Cel: @{{ empresa.celular }}</span>
                        </div>

                        <!-- Email / Web -->
                        <div class="ticket-sub" v-if="empresa.correo && empresa.correo !== '-'">
                            @{{ empresa.correo }}
                        </div>
                        <div class="ticket-sub" v-if="empresa.web && empresa.web !== '-'">
                            @{{ empresa.web }}
                        </div>

                        <div class="ticket-divider"></div>
                        <div class="text-center" style="font-size: 0.72rem; color: #64748b;">
                            *** TICKET DE VENTA DE PRUEBA ***
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- FLOATING BOTTOM SAVE BAR -->
    <div class="floating-save-bar">
        <div class="d-flex align-items-center gap-2">
            <span class="badge badge-success px-2 py-1"><i class="fa fa-shield-alt mr-1"></i>Datos oficiales</span>
            <span class="text-muted small d-none d-md-inline">Los cambios se reflejarán inmediatamente en las ventas, facturas y reportes.</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-outline-secondary btn-sm" @click="cancelar" type="button">
                <i class="fa fa-undo mr-1"></i> Descartar cambios
            </button>
            <button class="btn-pos-primary font-cairo" @click="update" :disabled="guardando">
                <i class="fa" :class="guardando ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                <span>@{{ guardando ? 'Guardando cambios...' : 'Guardar Datos de la Empresa' }}</span>
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

    var app = new Vue({
        el: '#app',
        data: {
            guardando: false,
            logoFile: null,
            logoPreview: null,
            ciudades: @json($ciudades),
            sucursales: @json($sucursales),
            empresa: {
                nombre: @json(trim($empresa->emp_nombre ?? '')),
                sucursal: @json($empresa->suc_cod ?? 1),
                ciudad: @json($empresa->CIUDAD_cod ?? 1),
                direccion: @json(trim($empresa->emp_direccion ?? '')),
                ruc: @json(trim($empresa->emp_ruc ?? '')),
                celular: @json(trim($empresa->emp_celular ?? '')),
                telefono: @json(trim($empresa->emp_telefono ?? '')),
                correo: @json(trim($empresa->emp_correo ?? '')),
                web: @json(trim($empresa->emp_web ?? '')),
                logo: @json(trim($empresa->emp_logo ?? '')),
                descripcion: @json(trim($empresa->emp_descripcion ?? ''))
            }
        },
        computed: {
            currentCiudadName: function () {
                var self = this;
                var found = this.ciudades.find(function (c) {
                    return c.CIUDAD_cod == self.empresa.ciudad;
                });
                return found ? found.ciudad_nombre : 'Ciudad';
            },
            currentSucursalName: function () {
                var self = this;
                var found = this.sucursales.find(function (s) {
                    return s.suc_cod == self.empresa.sucursal;
                });
                return found ? (found.suc_desc || '').trim() : 'Casa Central';
            }
        },
        methods: {
            onLogoChange: function (event) {
                var file = event.target.files[0];
                if (file) {
                    if (!file.type.match('image.*')) {
                        Swal.fire({
                            title: 'Formato no soportado',
                            text: 'Por favor seleccioná un archivo de imagen válido (PNG, JPG, JPEG).',
                            icon: 'warning',
                            confirmButtonColor: '#0a4d36'
                        });
                        return;
                    }
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire({
                            title: 'Archivo muy grande',
                            text: 'La imagen no debe superar los 2MB de tamaño.',
                            icon: 'warning',
                            confirmButtonColor: '#0a4d36'
                        });
                        return;
                    }
                    this.logoFile = file;
                    var reader = new FileReader();
                    var self = this;
                    reader.onload = function (e) {
                        self.logoPreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },
            quitarLogoSeleccionado: function () {
                this.logoFile = null;
                this.logoPreview = null;
                if (this.$refs.logoFileInput) {
                    this.$refs.logoFileInput.value = '';
                }
            },
            update: function () {
                if (!this.empresa.nombre || this.empresa.nombre.trim().length === 0) {
                    Swal.fire({
                        title: 'Nombre Requerido',
                        text: 'Completá el nombre o razón social de la empresa.',
                        icon: 'warning',
                        confirmButtonColor: '#0a4d36'
                    });
                    return;
                }

                var self = this;
                this.guardando = true;

                var formData = new FormData();
                formData.append('nombre', this.empresa.nombre);
                formData.append('sucursal', this.empresa.sucursal);
                formData.append('ciudad', this.empresa.ciudad);
                formData.append('direccion', this.empresa.direccion);
                formData.append('ruc', this.empresa.ruc);
                formData.append('celular', this.empresa.celular);
                formData.append('telefono', this.empresa.telefono);
                formData.append('correo', this.empresa.correo);
                formData.append('web', this.empresa.web);
                formData.append('descripcion', this.empresa.descripcion);

                if (this.logoFile) {
                    formData.append('logo', this.logoFile);
                }

                axios.post('{{ url('empresa') }}', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function (response) {
                    self.guardando = false;
                    if (response.data && response.data.emp_logo) {
                        self.empresa.logo = response.data.emp_logo;
                        self.logoFile = null;
                        self.logoPreview = null;
                    }
                    Toast.fire({
                        title: (response.data && response.data.message) ? response.data.message : 'Datos de empresa actualizados',
                        icon: 'success'
                    });
                })
                .catch(function (error) {
                    self.guardando = false;
                    var msg = (error.response && error.response.data && error.response.data.message)
                        ? error.response.data.message
                        : 'No se pudo guardar la información de la empresa.';
                    Swal.fire({
                        title: 'Error al Guardar',
                        text: msg,
                        icon: 'error',
                        confirmButtonColor: '#ef4444'
                    });
                });
            },
            cancelar: function () {
                Swal.fire({
                    title: '¿Descartar cambios?',
                    text: 'Se recargarán los datos guardados en el sistema.',
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
            }
        },
        mounted: function () {
            activarMenu('m_mantenimiento', 'm_empresa');

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
