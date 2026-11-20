@extends('layouts.app')
@section('title', 'Servicio Técnico y Reparación de Celulares')

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

    /* Topbar & Navigation Pills */
    .view-topbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 1.15rem;
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
        font-size: 0.84rem;
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
        font-size: 0.86rem;
        padding: 0.45rem 1.05rem;
        border-radius: 999px;
        cursor: pointer;
        transition: all 0.18s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
    }
    .nav-pill-btn:hover {
        color: var(--dash-text-main);
    }
    .nav-pill-btn.active {
        background: var(--dash-primary);
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(10, 77, 54, 0.25);
    }

    /* KPI Row */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(175px, 1fr));
        gap: 0.8rem;
        margin-bottom: 1.15rem;
    }
    .kpi-box {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        padding: 0.85rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .kpi-box:hover {
        transform: translateY(-2px);
        border-color: var(--dash-primary);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    .kpi-box.active {
        border: 2px solid var(--dash-primary);
        background: var(--dash-primary-light);
    }
    .kpi-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .kpi-num {
        font-size: 1.25rem;
        font-weight: 800;
        line-height: 1.1;
        color: var(--dash-text-main);
    }
    .kpi-lbl {
        font-size: 0.76rem;
        font-weight: 700;
        color: var(--dash-text-muted);
        text-transform: uppercase;
    }

    /* Cards & Panels */
    .card-pos {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        margin-bottom: 1rem;
        overflow: hidden;
    }
    .card-pos-header {
        padding: 0.8rem 1.15rem;
        border-bottom: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .card-pos-title {
        font-size: 0.96rem;
        font-weight: 800;
        margin: 0;
        color: var(--dash-text-main);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .card-pos-body {
        padding: 1.1rem 1.15rem;
    }

    /* Status Badges */
    .badge-estado {
        font-size: 0.72rem;
        font-weight: 800;
        padding: 0.28rem 0.65rem;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .est-RECIBIDO { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .est-EN_DIAGNOSTICO { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .est-PRESUPUESTADO { background: #f3e8ff; color: #7e22ce; border: 1px solid #e9d5ff; }
    .est-APROBADO { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .est-ESPERANDO_REPUESTO { background: #ffedd5; color: #c2410c; border: 1px solid #fed7aa; }
    .est-EN_REPARACION { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .est-REPARADO { background: #d1fae5; color: #047857; border: 1px solid #6ee7b7; }
    .est-NO_REPARABLE { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
    .est-ENTREGADO { background: #0a4d36; color: #ffffff; border: 1px solid #073827; }
    .est-EN_GARANTIA { background: #ffe4e6; color: #be123c; border: 1px solid #fda4af; }
    .est-ANULADO { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }

    /* Interactive 3x3 Pattern Pad */
    .pattern-pad-wrap {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        padding: 0.85rem;
    }
    .pattern-svg-box {
        width: 150px;
        height: 150px;
        background: var(--dash-card-bg);
        border: 2px solid var(--dash-border);
        border-radius: 12px;
        user-select: none;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.03);
    }
    .pattern-node {
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .pattern-node:hover {
        r: 11px;
    }

    /* Reception Checklist Grid */
    .checklist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(195px, 1fr));
        gap: 0.55rem;
    }
    .chk-item-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.45rem 0.7rem;
        border-radius: 8px;
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        font-size: 0.82rem;
        font-weight: 600;
    }
    .chk-btn-group {
        display: inline-flex;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid var(--dash-border);
    }
    .chk-btn {
        border: none;
        background: var(--dash-card-bg);
        color: var(--dash-text-muted);
        font-size: 0.7rem;
        font-weight: 800;
        padding: 2px 7px;
        cursor: pointer;
    }
    .chk-btn.active-si { background: #10b981; color: #fff; }
    .chk-btn.active-no { background: #ef4444; color: #fff; }
    .chk-btn.active-na { background: #64748b; color: #fff; }

    /* Brand & Accessory Quick Chips */
    .chip-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        margin-top: 0.35rem;
    }
    .quick-chip {
        font-size: 0.74rem;
        font-weight: 700;
        padding: 0.2rem 0.6rem;
        border-radius: 999px;
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        cursor: pointer;
        transition: all 0.12s;
    }
    .quick-chip:hover, .quick-chip.active {
        background: var(--dash-primary-light);
        border-color: var(--dash-primary);
        color: var(--dash-primary);
    }

    /* Photo Gallery Grid */
    .photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(145px, 1fr));
        gap: 0.75rem;
    }
    .photo-card {
        border: 1px solid var(--dash-border);
        border-radius: 10px;
        overflow: hidden;
        background: var(--dash-panel-bg);
        position: relative;
    }
    .photo-card img {
        width: 100%;
        height: 115px;
        object-fit: cover;
        cursor: pointer;
        display: block;
    }
    .photo-meta {
        padding: 0.4rem 0.55rem;
        font-size: 0.74rem;
    }
    .photo-del-btn {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: none;
        background: rgba(225, 29, 72, 0.9);
        color: #fff;
        font-size: 0.72rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Client Picker Dropdown */
    .cliente-picker {
        position: relative;
    }
    .cliente-picker-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1050;
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        margin-top: 4px;
        max-height: 280px;
        overflow-y: auto;
    }
    .cliente-picker-item {
        padding: 0.55rem 0.85rem;
        border-bottom: 1px solid var(--dash-border);
        cursor: pointer;
        transition: background 0.12s;
    }
    .cliente-picker-item:hover {
        background: var(--dash-primary-light);
    }

    /* Timeline */
    .timeline-custom {
        position: relative;
        padding-left: 1.5rem;
        border-left: 2px solid var(--dash-primary-border);
    }
    .timeline-item-custom {
        position: relative;
        margin-bottom: 0.9rem;
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        border-radius: 8px;
        padding: 0.6rem 0.85rem;
    }
    .timeline-item-custom::before {
        content: '';
        position: absolute;
        left: -1.88rem;
        top: 0.75rem;
        width: 11px;
        height: 11px;
        border-radius: 50%;
        background: var(--dash-primary);
        border: 2px solid #fff;
    }
</style>
@endsection

@section('main')
<div id="app" v-cloak class="pt-2 pb-4">

    <!-- BANNER SI EL MÓDULO ESTÁ DESHABILITADO (VISIBLE AL SUPERUSUARIO) -->
    <div v-if="!moduloActivo" class="alert alert-danger d-flex align-items-center justify-content-between shadow-sm mb-3" style="border-radius: 12px;">
        <div>
            <i class="fas fa-power-off fa-lg mr-2"></i>
            <strong>Módulo Deshabilitado para Usuarios:</strong> Actualmente estás visualizando esta pantalla como <strong>Superusuario / Administrador</strong>. Los demás roles no tienen acceso hasta que lo habilites.
        </div>
        <button type="button" class="btn btn-sm btn-light font-weight-bold text-danger" @click="toggleModuloSuperusuario">
            <i class="fas fa-check-circle mr-1"></i> Habilitar Módulo Ahora
        </button>
    </div>

    <!-- TOPBAR -->
    <div class="view-topbar">
        <div class="view-title-box">
            <h3 class="font-cairo">
                <i class="fas fa-mobile-alt text-success"></i>
                Servicio Técnico y Reparación de Celulares
                @if (!empty($esSuperAdministrador))
                    <span class="badge ml-2" :class="moduloActivo ? 'badge-success' : 'badge-danger'"
                          style="font-size: 0.7rem; cursor: pointer;"
                          title="Control Superadministrador: Clic para habilitar/deshabilitar el módulo"
                          @click="toggleModuloSuperusuario">
                        <i class="fas fa-crown mr-1"></i>@{{ moduloActivo ? 'Módulo Activo' : 'Módulo Inactivo' }}
                    </span>
                @endif
            </h3>
            <p>Recepción con checklist y patrón, diagnóstico técnico, evidencia fotográfica, repuestos con stock, entrega y garantías.</p>
        </div>

        <div class="d-flex align-items-center flex-wrap" style="gap: 0.5rem;">
            <div class="nav-pills-custom">
                <button type="button" class="nav-pill-btn" :class="{ active: vistaActiva === 'ordenes' }" @click="cambiarVista('ordenes')">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Tablero de Taller</span>
                </button>
                <button type="button" class="nav-pill-btn" :class="{ active: vistaActiva === 'recepcion' }" @click="cambiarVista('recepcion')" v-if="permisos.add">
                    <i class="fas fa-plus-circle"></i>
                    <span>Nueva Recepción</span>
                </button>
                <button type="button" class="nav-pill-btn" :class="{ active: vistaActiva === 'garantias' }" @click="cambiarVista('garantias')">
                    <i class="fas fa-shield-alt"></i>
                    <span>Garantías</span>
                    <span v-if="kpis.en_garantia > 0" class="badge badge-danger ml-1">@{{ kpis.en_garantia }}</span>
                </button>
            </div>

            @if (!empty($esSuperAdministrador))
                <a href="{{ route('ajuste.index') }}" class="btn btn-sm btn-outline-secondary font-weight-bold" title="Ajustes de Servicio Técnico (Superadministrador)">
                    <i class="fas fa-cog"></i>
                </a>
            @endif
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- VISTA 1 Y 3: TABLERO DE TALLER Y SEGUIMIENTO DE GARANTÍAS     -->
    <!-- ============================================================ -->
    <div v-show="vistaActiva === 'ordenes' || vistaActiva === 'garantias'">

        <!-- KPIs Interactivos -->
        <div class="kpi-grid">
            <div class="kpi-box" :class="{ active: filtros.estado === 'TODOS' && vistaActiva === 'ordenes' }" @click="filtrarPorKpi('TODOS')">
                <div class="kpi-icon" style="background: #e0f2fe; color: #0284c7;">
                    <i class="fas fa-inbox"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo">@{{ kpis.recibidos }}</div>
                    <div class="kpi-lbl">Recibidos / Diag.</div>
                </div>
            </div>

            <div class="kpi-box" :class="{ active: filtros.estado === 'EN_TALLER' }" @click="filtrarPorKpi('EN_TALLER')">
                <div class="kpi-icon" style="background: #fef3c7; color: #d97706;">
                    <i class="fas fa-tools"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo">@{{ kpis.en_taller }}</div>
                    <div class="kpi-lbl">En Reparación</div>
                </div>
            </div>

            <div class="kpi-box" :class="{ active: filtros.estado === 'LISTOS_ENTREGA' }" @click="filtrarPorKpi('LISTOS_ENTREGA')">
                <div class="kpi-icon" style="background: #d1fae5; color: #059669;">
                    <i class="fas fa-check-double"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo">@{{ kpis.listos_entrega }}</div>
                    <div class="kpi-lbl">Listos p/ Entrega</div>
                </div>
            </div>

            <div class="kpi-box" :class="{ active: filtros.estado === 'ENTREGADO' }" @click="filtrarPorKpi('ENTREGADO')">
                <div class="kpi-icon" style="background: #eaf3ef; color: #0a4d36;">
                    <i class="fas fa-handshake"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo">@{{ kpis.entregados }}</div>
                    <div class="kpi-lbl">Entregados</div>
                </div>
            </div>

            <div class="kpi-box" :class="{ active: vistaActiva === 'garantias' || filtros.estado === 'EN_GARANTIA' }" @click="cambiarVista('garantias')">
                <div class="kpi-icon" style="background: #ffe4e6; color: #e11d48;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo">@{{ kpis.en_garantia }}</div>
                    <div class="kpi-lbl">En Garantía</div>
                </div>
            </div>

            <div class="kpi-box" @click="filtrarPorKpi('LISTOS_ENTREGA')">
                <div class="kpi-icon" style="background: #f3e8ff; color: #7e22ce;">
                    <i class="fas fa-coins"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo" style="font-size: 1.02rem;">Gs. @{{ formatNumber(kpis.saldo_por_cobrar) }}</div>
                    <div class="kpi-lbl">Saldo por Cobrar</div>
                </div>
            </div>
        </div>

        <!-- Barra de Filtros -->
        <div class="card-pos">
            <div class="card-pos-body py-3">
                <div class="row align-items-end">
                    <div class="col-md-3 mb-2">
                        <label class="small font-weight-bold text-muted mb-1">Buscar Orden / Cliente / Equipo / IMEI</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" class="form-control" v-model="filtros.q"
                                   @keyup.enter="cargarOrdenes(1)"
                                   placeholder="Ej: ST-000001, Samsung, A54, IMEI, Juan...">
                        </div>
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="small font-weight-bold text-muted mb-1">Estado de Taller</label>
                        <select class="form-control form-control-sm" v-model="filtros.estado" @change="cargarOrdenes(1)">
                            <option value="TODOS">Todos los estados</option>
                            <option value="EN_TALLER">En Taller (Activos)</option>
                            <option value="LISTOS_ENTREGA">Listos para Entregar</option>
                            <option value="RECIBIDO">Recibido</option>
                            <option value="EN_DIAGNOSTICO">En Diagnóstico</option>
                            <option value="PRESUPUESTADO">Presupuestado</option>
                            <option value="APROBADO">Aprobado</option>
                            <option value="ESPERANDO_REPUESTO">Esperando Repuesto</option>
                            <option value="EN_REPARACION">En Reparación</option>
                            <option value="REPARADO">Reparado (Listo)</option>
                            <option value="NO_REPARABLE">No Reparable</option>
                            <option value="ENTREGADO">Entregado</option>
                            <option value="EN_GARANTIA">En Garantía</option>
                            <option value="ANULADO">Anulado</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="small font-weight-bold text-muted mb-1">Técnico Asignado</label>
                        <select class="form-control form-control-sm" v-model="filtros.tecnico" @change="cargarOrdenes(1)">
                            <option value="0">Todos los técnicos</option>
                            <option v-for="t in tecnicos" :key="t.cod_usuarios" :value="t.cod_usuarios">@{{ t.nom_usuarios }}</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="small font-weight-bold text-muted mb-1">Desde</label>
                        <input type="date" class="form-control form-control-sm" v-model="filtros.desde" @change="cargarOrdenes(1)">
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="small font-weight-bold text-muted mb-1">Hasta</label>
                        <input type="date" class="form-control form-control-sm" v-model="filtros.hasta" @change="cargarOrdenes(1)">
                    </div>

                    <div class="col-md-1 mb-2 d-flex" style="gap: 4px;">
                        <button type="button" class="btn btn-sm btn-success btn-block font-weight-bold" @click="cargarOrdenes(1)" title="Filtrar">
                            <i class="fas fa-sync-alt" :class="{ 'fa-spin': cargandoOrdenes }"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" @click="limpiarFiltros" title="Limpiar filtros">
                            <i class="fas fa-eraser"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Órdenes -->
        <div class="card-pos">
            <div class="card-pos-header">
                <h5 class="card-pos-title font-cairo">
                    <i class="fas" :class="vistaActiva === 'garantias' ? 'fa-shield-alt text-danger' : 'fa-list-ul text-success'"></i>
                    <span v-if="vistaActiva === 'garantias'">Órdenes con Garantía / Reclamos / Reingresos (@{{ paginacion.total }})</span>
                    <span v-else>Órdenes de Servicio Técnico (@{{ paginacion.total }})</span>
                </h5>
                <button v-if="permisos.add" type="button" class="btn btn-sm btn-success font-weight-bold font-cairo" @click="cambiarVista('recepcion')">
                    <i class="fas fa-plus mr-1"></i> Recibir Celular
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                    <thead class="bg-light">
                        <tr>
                            <th>Orden</th>
                            <th>Ingreso</th>
                            <th>Cliente / Contacto</th>
                            <th>Equipo / IMEI</th>
                            <th>Falla / Diagnóstico</th>
                            <th>Técnico</th>
                            <th class="text-center">Estado</th>
                            <th class="text-right">Total / Saldo</th>
                            <th class="text-center" style="width: 155px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="cargandoOrdenes">
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-spinner fa-spin fa-lg mr-2 text-success"></i> Cargando órdenes de servicio técnico...
                            </td>
                        </tr>
                        <tr v-else-if="!ordenes.length">
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-mobile-alt fa-2x mb-2 d-block text-muted"></i>
                                No se encontraron órdenes de reparación con los filtros seleccionados.
                            </td>
                        </tr>
                        <tr v-else v-for="o in ordenes" :key="o.id" style="cursor: pointer;" @click="abrirGestionOrden(o.id)">
                            <td class="font-weight-bold font-cairo">
                                <span class="text-primary">@{{ o.codigo_orden }}</span>
                                <span v-if="o.es_reingreso_garantia" class="badge badge-danger d-block mt-1" style="font-size: 0.65rem;">
                                    <i class="fas fa-shield-alt mr-1"></i>GARANTÍA
                                </span>
                                <span v-if="o.prioridad === 'URGENTE' || o.prioridad === 'ALTA'" class="badge badge-warning d-block mt-1" style="font-size: 0.65rem;">
                                    @{{ o.prioridad }}
                                </span>
                            </td>
                            <td>
                                <div>@{{ formatearFecha(o.fecha_recepcion) }}</div>
                                <small class="text-muted">@{{ formatearHora(o.fecha_recepcion) }}</small>
                            </td>
                            <td>
                                <div class="font-weight-bold">@{{ o.cliente ? o.cliente.cliente_nombre : 'Sin cliente' }}</div>
                                <small class="text-muted">
                                    <i class="fas fa-phone-alt mr-1"></i>@{{ o.cliente ? (o.cliente.cliente_cel || o.cliente.cliente_telef || o.cliente.cliente_ruc) : '-' }}
                                </small>
                            </td>
                            <td>
                                <div class="font-weight-bold">@{{ o.marca }} @{{ o.modelo }}</div>
                                <small class="text-muted font-monospace" v-if="o.imei_serie">IMEI: @{{ o.imei_serie }}</small>
                                <div class="mt-1">
                                    <span v-if="o.imagenes_count > 0" class="badge badge-light border mr-1" title="Imágenes adjuntas">
                                        <i class="fas fa-camera text-info mr-1"></i>@{{ o.imagenes_count }}
                                    </span>
                                    <span v-if="o.repuestos_count > 0" class="badge badge-light border mr-1" title="Repuestos asignados">
                                        <i class="fas fa-microchip text-primary mr-1"></i>@{{ o.repuestos_count }}
                                    </span>
                                    <span v-if="o.garantias_count > 0" class="badge badge-danger" title="Reclamos de garantía">
                                        <i class="fas fa-shield-alt mr-1"></i>@{{ o.garantias_count }}
                                    </span>
                                </div>
                            </td>
                            <td style="max-width: 230px;">
                                <div class="text-truncate font-weight-bold" :title="o.falla_reportada">@{{ o.falla_reportada }}</div>
                                <small class="text-muted text-truncate d-block" v-if="o.diagnostico_tecnico" :title="o.diagnostico_tecnico">
                                    Diag: @{{ o.diagnostico_tecnico }}
                                </small>
                            </td>
                            <td>
                                <span v-if="o.tecnico" class="badge badge-light border">
                                    <i class="fas fa-user-cog mr-1 text-success"></i>@{{ o.tecnico.nom_usuarios }}
                                </span>
                                <span v-else class="text-muted small">Sin asignar</span>
                            </td>
                            <td class="text-center">
                                <span class="badge-estado" :class="'est-' + o.estado">
                                    @{{ labelEstado(o.estado) }}
                                </span>
                                <div v-if="o.estado === 'ENTREGADO' && o.tiene_garantia && o.fecha_vencimiento_garantia" class="small mt-1">
                                    <span class="badge" :class="esGarantiaVigenteLista(o.fecha_vencimiento_garantia) ? 'badge-success' : 'badge-secondary'" style="font-size: 0.65rem;">
                                        Garantía: @{{ formatearSoloFecha(o.fecha_vencimiento_garantia) }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-right font-cairo">
                                <div class="font-weight-bold">Gs. @{{ formatNumber(o.total_general) }}</div>
                                <small v-if="o.saldo_pendiente > 0" class="text-danger font-weight-bold">
                                    Saldo: Gs. @{{ formatNumber(o.saldo_pendiente) }}
                                </small>
                                <small v-else-if="o.total_general > 0" class="text-success font-weight-bold">
                                    <i class="fas fa-check-circle"></i> Pagado
                                </small>
                                <small v-else class="text-muted">Por cotizar</small>
                            </td>
                            <td class="text-center" @click.stop>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary" @click="abrirGestionOrden(o.id)" title="Gestionar Diagnóstico, Repuestos, Fotos y Entrega">
                                        <i class="fas fa-tools"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" @click="imprimirOrden(o.id, 'a4')" title="Imprimir Orden de Recepción">
                                        <i class="fas fa-print"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-dark" @click="imprimirEtiqueta(o.id)" title="Imprimir Sticker para el Celular">
                                        <i class="fas fa-tag"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-success" @click="imprimirGarantia(o.id)" title="Certificado de Entrega y Garantía">
                                        <i class="fas fa-award"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="card-pos-header border-top-0" v-if="paginacion.last_page > 1">
                <span class="small text-muted">Página @{{ paginacion.current_page }} de @{{ paginacion.last_page }}</span>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-secondary" :disabled="paginacion.current_page <= 1" @click="cargarOrdenes(paginacion.current_page - 1)">
                        <i class="fas fa-chevron-left"></i> Anterior
                    </button>
                    <button class="btn btn-outline-secondary" :disabled="paginacion.current_page >= paginacion.last_page" @click="cargarOrdenes(paginacion.current_page + 1)">
                        Siguiente <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- VISTA 2: NUEVA RECEPCIÓN DE CELULAR / DISPOSITIVO            -->
    <!-- ============================================================ -->
    <div v-show="vistaActiva === 'recepcion'">
        <div class="row">
            <!-- Columna Izquierda: Cliente, Equipo y Checklist -->
            <div class="col-lg-8">

                <!-- 1. Datos del Cliente y Asignación -->
                <div class="card-pos">
                    <div class="card-pos-header">
                        <h5 class="card-pos-title font-cairo">
                            <i class="fas fa-user-check text-success"></i> 1. Cliente Propietario y Sucursal
                        </h5>
                        <button type="button" class="btn btn-xs btn-outline-success font-weight-bold" @click="abrirModalNuevoCliente">
                            <i class="fas fa-user-plus mr-1"></i> Nuevo Cliente Rápido
                        </button>
                    </div>
                    <div class="card-pos-body">
                        <div class="row">
                            <div class="col-md-7 mb-2">
                                <label class="small font-weight-bold text-muted mb-1">Cliente <span class="text-danger">*</span></label>
                                <div class="cliente-picker">
                                    <div class="input-group">
                                        <input type="text" class="form-control"
                                               v-model="clientePicker.busqueda"
                                               @focus="abrirBuscadorCliente"
                                               @input="onBuscarClienteInput"
                                               placeholder="Buscar cliente por Nombre, CI, RUC o Celular...">
                                        <div class="input-group-append" v-if="clienteSeleccionado">
                                            <button class="btn btn-outline-danger" type="button" @click="quitarCliente" title="Cambiar cliente">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div v-if="clienteSeleccionado" class="mt-2 p-2 rounded border d-flex justify-content-between align-items-center" style="background: var(--dash-primary-light);">
                                        <div>
                                            <strong class="text-success"><i class="fas fa-check-circle mr-1"></i>@{{ clienteSeleccionado.cliente_nombre }}</strong>
                                            <span class="small text-muted ml-2">Doc: @{{ clienteSeleccionado.cliente_ruc || clienteSeleccionado.cliente_ci || '-' }}</span>
                                            <span class="small text-muted ml-2" v-if="clienteSeleccionado.cliente_cel"><i class="fas fa-phone-alt mr-1"></i>@{{ clienteSeleccionado.cliente_cel }}</span>
                                        </div>
                                    </div>

                                    <div class="cliente-picker-dropdown" v-show="clientePicker.abierto">
                                        <div v-if="clientePicker.cargando" class="p-3 text-center text-muted small">
                                            <i class="fas fa-spinner fa-spin mr-1"></i> Buscando clientes...
                                        </div>
                                        <div v-else-if="!clientePicker.resultados.length" class="p-3 text-center text-muted small">
                                            No se encontraron clientes.
                                            <button type="button" class="btn btn-xs btn-success ml-2" @click="abrirModalNuevoCliente">Crear Cliente</button>
                                        </div>
                                        <div v-else>
                                            <div class="cliente-picker-item" v-for="c in clientePicker.resultados" :key="c.CLIENTES_cod || c.clientes_cod" @click="seleccionarCliente(c)">
                                                <div class="font-weight-bold">@{{ c.cliente_nombre }}</div>
                                                <small class="text-muted">
                                                    CI/RUC: @{{ c.cliente_ruc || c.cliente_ci || '-' }} · Cel: @{{ c.cliente_cel || c.cliente_telef || '-' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5 mb-2">
                                <div class="row">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-muted mb-1">Sucursal</label>
                                        <select class="form-control" v-model="formRecepcion.suc_cod">
                                            <option v-for="s in sucursales" :key="s.suc_cod" :value="s.suc_cod">@{{ s.suc_desc }}</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-muted mb-1">Técnico Asignado</label>
                                        <select class="form-control" v-model="formRecepcion.cod_usuario_tecnico">
                                            <option :value="null">-- Sin asignar --</option>
                                            <option v-for="t in tecnicos" :key="t.cod_usuarios" :value="t.cod_usuarios">@{{ t.nom_usuarios }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Identificación del Dispositivo y Desbloqueo -->
                <div class="card-pos">
                    <div class="card-pos-header">
                        <h5 class="card-pos-title font-cairo">
                            <i class="fas fa-mobile-alt text-primary"></i> 2. Datos del Celular / Dispositivo y Patrón de Desbloqueo
                        </h5>
                    </div>
                    <div class="card-pos-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="small font-weight-bold text-muted mb-1">Tipo de Equipo</label>
                                <select class="form-control" v-model="formRecepcion.tipo_equipo">
                                    <option value="Celular">Celular / Smartphone</option>
                                    <option value="Tablet">Tablet / iPad</option>
                                    <option value="Smartwatch">Smartwatch</option>
                                    <option value="Notebook">Notebook / Otro</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="small font-weight-bold text-muted mb-1">Marca <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" v-model="formRecepcion.marca" placeholder="Ej: Samsung, Apple iPhone, Xiaomi...">
                                <div class="chip-list">
                                    <span class="quick-chip" v-for="m in marcasRapidas" :key="m"
                                          :class="{ active: formRecepcion.marca === m }"
                                          @click="formRecepcion.marca = m">@{{ m }}</span>
                                </div>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="small font-weight-bold text-muted mb-1">Modelo Exacto <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" v-model="formRecepcion.modelo" placeholder="Ej: Galaxy A54 5G, iPhone 13 Pro, Redmi Note 12...">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="small font-weight-bold text-muted mb-1">Color</label>
                                <input type="text" class="form-control" v-model="formRecepcion.color" placeholder="Ej: Negro, Azul, Blanco">
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="small font-weight-bold text-muted mb-1">IMEI / Número de Serie</label>
                                <input type="text" class="form-control font-monospace" v-model="formRecepcion.imei_serie" placeholder="Marcá *#06# en el equipo para ver IMEI">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="small font-weight-bold text-muted mb-1">Prioridad de Taller</label>
                                <select class="form-control" v-model="formRecepcion.prioridad">
                                    <option value="BAJA">Baja</option>
                                    <option value="NORMAL">Normal</option>
                                    <option value="ALTA">Alta</option>
                                    <option value="URGENTE">Urgente (Express)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Bloqueo y Patrón Gráfico -->
                        <div class="border-top pt-3 mt-1">
                            <div class="row align-items-center">
                                <div class="col-md-5 mb-2">
                                    <label class="small font-weight-bold text-muted mb-1"><i class="fas fa-lock mr-1"></i> Tipo de Bloqueo de Pantalla</label>
                                    <select class="form-control mb-2" v-model="formRecepcion.tipo_bloqueo">
                                        <option value="NINGUNO">Sin bloqueo / Deslizar</option>
                                        <option value="PATRON">Patrón Gráfico (Puntos 1 al 9)</option>
                                        <option value="PIN">PIN Numérico</option>
                                        <option value="PASSWORD">Contraseña Alfanumérica</option>
                                    </select>

                                    <div v-if="formRecepcion.tipo_bloqueo === 'PIN' || formRecepcion.tipo_bloqueo === 'PASSWORD'">
                                        <label class="small font-weight-bold text-muted mb-1">Código PIN / Contraseña</label>
                                        <input type="text" class="form-control font-monospace font-weight-bold" v-model="formRecepcion.clave_bloqueo" placeholder="Ej: 123456">
                                    </div>

                                    <div v-if="formRecepcion.tipo_bloqueo === 'PATRON'">
                                        <label class="small font-weight-bold text-muted mb-1">Secuencia del Patrón (Clic en los puntos)</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control font-monospace font-weight-bold" v-model="formRecepcion.patron_bloqueo" placeholder="Ej: 1-2-5-8-9">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-danger" @click="limpiarPatronRecepcion" title="Borrar patrón">
                                                    <i class="fas fa-undo"></i> Limpiar
                                                </button>
                                            </div>
                                        </div>
                                        <small class="text-muted">Tocá los círculos en el orden en que se dibuja el patrón.</small>
                                    </div>
                                </div>

                                <div class="col-md-7 mb-2" v-if="formRecepcion.tipo_bloqueo === 'PATRON'">
                                    <div class="pattern-pad-wrap">
                                        <svg class="pattern-svg-box" viewBox="0 0 120 120">
                                            <line v-for="(seg, idx) in segmentosPatronRecepcion" :key="'line-'+idx"
                                                  :x1="seg.x1" :y1="seg.y1" :x2="seg.x2" :y2="seg.y2"
                                                  stroke="#0a4d36" stroke-width="4" stroke-linecap="round" />
                                            <g v-for="n in nodosPatron" :key="n.id" @click="toggleNodoPatron(n.id)" style="cursor: pointer;">
                                                <circle :cx="n.x" :cy="n.y" :r="esNodoActivo(n.id) ? 10 : 7"
                                                        class="pattern-node"
                                                        :fill="esPrimerNodo(n.id) ? '#10b981' : (esNodoActivo(n.id) ? '#0a4d36' : '#cbd5e1')" />
                                                <text :x="n.x" :y="n.y + 3" font-size="8" font-weight="bold" text-anchor="middle"
                                                      :fill="esNodoActivo(n.id) ? '#ffffff' : '#334155'">@{{ n.id }}</text>
                                            </g>
                                        </svg>
                                        <div style="flex: 1;">
                                            <div class="font-weight-bold small text-uppercase text-muted mb-1">Patrón de Desbloqueo</div>
                                            <div class="p-2 rounded border bg-white font-monospace font-weight-bold text-success mb-2">
                                                @{{ formRecepcion.patron_bloqueo || 'Sin trazar' }}
                                            </div>
                                            <small class="text-muted d-block">El punto verde indica el inicio del trazo. Quedará impreso en la Orden y en el Sticker del equipo.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Checklist Físico y Accesorios -->
                <div class="card-pos">
                    <div class="card-pos-header">
                        <h5 class="card-pos-title font-cairo">
                            <i class="fas fa-clipboard-check text-info"></i> 3. Checklist de Estado Físico y Accesorios en Recepción
                        </h5>
                        <div>
                            <button type="button" class="btn btn-xs btn-outline-success mr-1" @click="setChecklistMasivo('SI')">
                                <i class="fas fa-check"></i> Todo OK
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-secondary" @click="setChecklistMasivo('NA')">
                                <i class="fas fa-power-off"></i> Apagado / Sin Verificar
                            </button>
                        </div>
                    </div>
                    <div class="card-pos-body">
                        <div class="checklist-grid mb-3">
                            <div class="chk-item-card" v-for="item in checklistDefinicion" :key="item.key">
                                <span><i :class="item.icon" class="mr-1 text-muted"></i> @{{ item.label }}</span>
                                <div class="chk-btn-group">
                                    <button type="button" class="chk-btn" :class="{ 'active-si': formRecepcion.checklist_recepcion[item.key] === 'SI' }"
                                            @click="$set(formRecepcion.checklist_recepcion, item.key, 'SI')">SÍ</button>
                                    <button type="button" class="chk-btn" :class="{ 'active-no': formRecepcion.checklist_recepcion[item.key] === 'NO' }"
                                            @click="$set(formRecepcion.checklist_recepcion, item.key, 'NO')">NO</button>
                                    <button type="button" class="chk-btn" :class="{ 'active-na': formRecepcion.checklist_recepcion[item.key] === 'NA' }"
                                            @click="$set(formRecepcion.checklist_recepcion, item.key, 'NA')">S/V</button>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="small font-weight-bold text-muted mb-1">Accesorios que deja el cliente</label>
                                <input type="text" class="form-control" v-model="formRecepcion.accesorios_dejados" placeholder="Ej: Funda silicona, Chip Tigo, Bandeja SIM...">
                                <div class="chip-list">
                                    <span class="quick-chip" v-for="acc in accesoriosRapidos" :key="acc" @click="toggleAccesorioChip(acc)">
                                        + @{{ acc }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="small font-weight-bold text-muted mb-1">Observaciones físicas / estéticas (Rayones, golpes, mojado)</label>
                                <input type="text" class="form-control" v-model="formRecepcion.estado_fisico_obs" placeholder="Ej: Tapa trasera trizada en esquina inferior, marco raspado...">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Evidencia Fotográfica en Recepción -->
                <div class="card-pos">
                    <div class="card-pos-header">
                        <h5 class="card-pos-title font-cairo">
                            <i class="fas fa-camera text-warning"></i> 4. Fotografías del Equipo al Ingresar
                        </h5>
                        <div class="d-flex" style="gap: 6px;">
                            <label class="btn btn-xs btn-primary mb-0 font-weight-bold" style="cursor: pointer;">
                                <i class="fas fa-upload mr-1"></i> Subir Foto / Archivo
                                <input type="file" accept="image/*" class="d-none" @change="onFileRecepcionChange">
                            </label>
                            <button type="button" class="btn btn-xs btn-info font-weight-bold" @click="abrirModalWebcam('RECEPCION', null)">
                                <i class="fas fa-camera-retro mr-1"></i> Cámara Web
                            </button>
                            <button v-if="camaraConfig.configured" type="button" class="btn btn-xs btn-dark font-weight-bold" @click="capturarCamaraIp('RECEPCION', null)" :disabled="subiendoImagen">
                                <i class="fas fa-video mr-1"></i> Cámara IP
                            </button>
                        </div>
                    </div>
                    <div class="card-pos-body">
                        <div v-if="!formRecepcion.imagenes_iniciales.length" class="text-center py-3 text-muted small border rounded" style="border-style: dashed !important;">
                            <i class="fas fa-images fa-lg mb-1 d-block"></i>
                            Podés adjuntar fotos del estado en que ingresa el celular (pantalla, golpes, etiqueta IMEI, humedad).
                        </div>
                        <div v-else class="photo-grid">
                            <div class="photo-card" v-for="(img, idx) in formRecepcion.imagenes_iniciales" :key="idx">
                                <img :src="img.url" @click="verFotoAmpliada(img.url, img.descripcion)">
                                <button type="button" class="photo-del-btn" @click="formRecepcion.imagenes_iniciales.splice(idx, 1)" title="Quitar">
                                    <i class="fas fa-times"></i>
                                </button>
                                <div class="photo-meta">
                                    <input type="text" class="form-control form-control-sm" v-model="img.descripcion" placeholder="Descripción foto...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Falla, Presupuesto Inicial, Seña y Guardado -->
            <div class="col-lg-4">
                <div class="card-pos">
                    <div class="card-pos-header">
                        <h5 class="card-pos-title font-cairo">
                            <i class="fas fa-wrench text-danger"></i> 5. Falla, Presupuesto y Seña
                        </h5>
                    </div>
                    <div class="card-pos-body">
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted mb-1">Falla Reportada / Trabajo Solicitado <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="3" v-model="formRecepcion.falla_reportada"
                                      placeholder="Ej: Cambio de módulo/pantalla completa, no carga pin USB-C, reinicio constante..."></textarea>
                            <div class="chip-list">
                                <span class="quick-chip" v-for="f in fallasFrecuentes" :key="f" @click="agregarFallaChip(f)">+ @{{ f }}</span>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted mb-1">Pre-diagnóstico o Notas Técnicas Iniciales</label>
                            <textarea class="form-control" rows="2" v-model="formRecepcion.diagnostico_tecnico"
                                      placeholder="Opcional: observaciones técnicas al recibir..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="small font-weight-bold text-muted mb-1">Entrega Prometida</label>
                                <input type="datetime-local" class="form-control form-control-sm" v-model="formRecepcion.fecha_prometida">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="small font-weight-bold text-muted mb-1">Garantía Est. (Días)</label>
                                <input type="number" class="form-control form-control-sm font-cairo" v-model.number="formRecepcion.dias_garantia" min="0" max="365">
                            </div>
                        </div>

                        <div class="p-3 rounded border mb-3" style="background: var(--dash-panel-bg);">
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-muted mb-1">Presupuesto Mano de Obra / Estimado (Gs.)</label>
                                <input type="number" class="form-control font-cairo font-weight-bold" v-model.number="formRecepcion.costo_mano_obra" min="0" step="1000">
                            </div>

                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-success mb-1"><i class="fas fa-hand-holding-usd mr-1"></i> Seña / Adelanto Recibido Hoy (Gs.)</label>
                                <input type="number" class="form-control font-cairo font-weight-bold border-success" v-model.number="formRecepcion.monto_adelanto" min="0" step="1000">
                            </div>

                            <div v-if="formRecepcion.monto_adelanto > 0" class="mt-2 pt-2 border-top">
                                <div class="row">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-muted mb-1">Forma de Cobro</label>
                                        <select class="form-control form-control-sm" v-model.number="formRecepcion.forma_cobro_adelanto">
                                            <option :value="1">Efectivo</option>
                                            <option :value="2">Tarjeta</option>
                                            <option :value="3">Transferencia / QR</option>
                                        </select>
                                    </div>
                                    <div class="col-6 d-flex align-items-end">
                                        <div class="custom-control custom-checkbox mb-1">
                                            <input type="checkbox" class="custom-control-input" id="chkCajaAdelanto" v-model="formRecepcion.registrar_caja_adelanto">
                                            <label class="custom-control-label small font-weight-bold" for="chkCajaAdelanto">Ingresar en Caja</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top font-cairo">
                                <span class="font-weight-bold">Saldo Estimado:</span>
                                <span class="h5 mb-0 font-weight-bold text-primary">
                                    Gs. @{{ formatNumber(Math.max(0, (Number(formRecepcion.costo_mano_obra) || 0) - (Number(formRecepcion.monto_adelanto) || 0))) }}
                                </span>
                            </div>
                        </div>

                        <button type="button" class="btn btn-success btn-block font-cairo font-weight-bold py-2 mb-2"
                                :disabled="guardandoRecepcion"
                                @click="guardarRecepcion(true)">
                            <i class="fas mr-1" :class="guardandoRecepcion ? 'fa-spinner fa-spin' : 'fa-print'"></i>
                            Registrar e Imprimir Orden
                        </button>

                        <button type="button" class="btn btn-outline-primary btn-block font-cairo font-weight-bold"
                                :disabled="guardandoRecepcion"
                                @click="guardarRecepcion(false)">
                            <i class="fas fa-save mr-1"></i> Solo Guardar Recepción
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- MODAL INTEGRAL DE GESTIÓN DE ORDEN (TALLER / REPUESTOS / ETC) -->
    <!-- ============================================================ -->
    <div class="modal fade" id="modalGestionOrden" tabindex="-1" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document" style="max-width: 1180px;">
            <div class="modal-content" v-if="ordenActiva">
                <div class="modal-header py-2 px-3 d-flex align-items-center justify-content-between" style="background: var(--dash-primary); color: #fff;">
                    <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                        <h5 class="modal-title font-cairo font-weight-bold mb-0">
                            <i class="fas fa-mobile-alt mr-1"></i> Orden @{{ ordenActiva.codigo_orden }}
                        </h5>
                        <span class="badge-estado" :class="'est-' + ordenActiva.estado">@{{ labelEstado(ordenActiva.estado) }}</span>
                        <span v-if="ordenActiva.es_reingreso_garantia" class="badge badge-warning font-weight-bold">
                            <i class="fas fa-shield-alt mr-1"></i> REINGRESO POR GARANTÍA
                        </span>
                        <span class="small" style="opacity: 0.9;">
                            @{{ ordenActiva.marca }} @{{ ordenActiva.modelo }} · Cliente: <strong>@{{ ordenActiva.cliente ? ordenActiva.cliente.cliente_nombre : '-' }}</strong>
                        </span>
                    </div>
                    <div class="d-flex align-items-center" style="gap: 6px;">
                        <button type="button" class="btn btn-xs btn-light font-weight-bold" @click="imprimirOrden(ordenActiva.id, 'a4')" title="Imprimir Orden A4">
                            <i class="fas fa-print mr-1"></i> Orden
                        </button>
                        <button type="button" class="btn btn-xs btn-light font-weight-bold" @click="imprimirEtiqueta(ordenActiva.id)" title="Imprimir Sticker">
                            <i class="fas fa-tag mr-1"></i> Sticker
                        </button>
                        <button type="button" class="btn btn-xs btn-warning font-weight-bold" @click="imprimirGarantia(ordenActiva.id)" title="Imprimir Certificado de Garantía">
                            <i class="fas fa-award mr-1"></i> Garantía
                        </button>
                        <button type="button" class="close text-white ml-2" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                </div>

                <!-- Sub-navegación del Modal -->
                <div class="bg-light border-bottom px-3 py-2 d-flex flex-wrap justify-content-between align-items-center">
                    <div class="nav-pills-custom">
                        <button type="button" class="nav-pill-btn" :class="{ active: tabOrden === 'diagnostico' }" @click="tabOrden = 'diagnostico'">
                            <i class="fas fa-stethoscope"></i> Diagnóstico y Taller
                        </button>
                        <button type="button" class="nav-pill-btn" :class="{ active: tabOrden === 'repuestos' }" @click="tabOrden = 'repuestos'">
                            <i class="fas fa-microchip"></i> Repuestos (@{{ ordenActiva.repuestos ? ordenActiva.repuestos.length : 0 }})
                        </button>
                        <button type="button" class="nav-pill-btn" :class="{ active: tabOrden === 'imagenes' }" @click="tabOrden = 'imagenes'">
                            <i class="fas fa-camera"></i> Imágenes (@{{ ordenActiva.imagenes ? ordenActiva.imagenes.length : 0 }})
                        </button>
                        <button type="button" class="nav-pill-btn" :class="{ active: tabOrden === 'entrega' }" @click="tabOrden = 'entrega'">
                            <i class="fas fa-handshake"></i> Pagos, POS y Entrega
                        </button>
                        <button type="button" class="nav-pill-btn" :class="{ active: tabOrden === 'garantia' }" @click="tabOrden = 'garantia'">
                            <i class="fas fa-shield-alt"></i> Garantías e Historial
                        </button>
                    </div>

                    <div class="font-cairo text-right">
                        <span class="small text-muted mr-2">Total: <strong class="text-dark">Gs. @{{ formatNumber(ordenActiva.total_general) }}</strong></span>
                        <span class="small text-success mr-2">Abonado: <strong>Gs. @{{ formatNumber(ordenActiva.total_pagado) }}</strong></span>
                        <span class="badge" :class="ordenActiva.saldo_pendiente > 0 ? 'badge-danger' : 'badge-success'" style="font-size: 0.82rem;">
                            Saldo: Gs. @{{ formatNumber(ordenActiva.saldo_pendiente) }}
                        </span>
                    </div>
                </div>

                <div class="modal-body p-3">

                    <!-- ========================================== -->
                    <!-- PESTAÑA 1: DIAGNÓSTICO Y TALLER            -->
                    <!-- ========================================== -->
                    <div v-show="tabOrden === 'diagnostico'">
                        <!-- Barra rápida de estados -->
                        <div class="mb-3 p-2 rounded border bg-light d-flex flex-wrap align-items-center justify-content-between" style="gap: 6px;">
                            <span class="small font-weight-bold text-muted text-uppercase"><i class="fas fa-random mr-1"></i> Etapa del Equipo:</span>
                            <div class="d-flex flex-wrap" style="gap: 4px;">
                                <button v-for="est in estadosFlujo" :key="est.codigo"
                                        type="button"
                                        class="btn btn-xs font-weight-bold"
                                        :class="ordenActiva.estado === est.codigo ? 'btn-success' : 'btn-outline-secondary'"
                                        @click="formDiagnostico.estado = est.codigo">
                                    @{{ est.nombre }}
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="p-3 rounded border mb-3" style="background: var(--dash-panel-bg);">
                                    <div class="d-flex justify-content-between mb-1">
                                        <strong class="small text-uppercase text-muted">Resumen del Equipo Recibido</strong>
                                        <span class="small font-monospace">IMEI: @{{ ordenActiva.imei_serie || 'S/D' }} · Bloqueo: @{{ ordenActiva.tipo_bloqueo }} @{{ ordenActiva.clave_bloqueo || ordenActiva.patron_bloqueo }}</span>
                                    </div>
                                    <div class="small mb-1"><strong>Falla reportada por el cliente:</strong> @{{ ordenActiva.falla_reportada }}</div>
                                    <div class="small text-muted" v-if="ordenActiva.accesorios_dejados"><strong>Accesorios:</strong> @{{ ordenActiva.accesorios_dejados }}</div>
                                    <div class="small text-muted" v-if="ordenActiva.estado_fisico_obs"><strong>Obs. Físicas:</strong> @{{ ordenActiva.estado_fisico_obs }}</div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-muted mb-1"><i class="fas fa-search-plus mr-1 text-primary"></i> Diagnóstico Técnico Detallado</label>
                                    <textarea class="form-control" rows="3" v-model="formDiagnostico.diagnostico_tecnico"
                                              placeholder="Describí el resultado de la revisión técnica, fallas detectadas en placa/componentes..."></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-muted mb-1"><i class="fas fa-check-circle mr-1 text-success"></i> Trabajo Realizado / Solución Aplicada</label>
                                    <textarea class="form-control" rows="3" v-model="formDiagnostico.solucion_aplicada"
                                              placeholder="Describí la reparación efectuada (se imprimirá en el Certificado de Entrega y Garantía)..."></textarea>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold text-muted mb-1"><i class="fas fa-user-secret mr-1"></i> Observaciones Internas de Taller (No visibles al cliente)</label>
                                    <textarea class="form-control" rows="2" v-model="formDiagnostico.observaciones_internas"
                                              placeholder="Notas privadas para técnicos..."></textarea>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="card-pos mb-0">
                                    <div class="card-pos-header">
                                        <h6 class="card-pos-title font-cairo"><i class="fas fa-calculator text-success"></i> Liquidación Técnica y Mano de Obra</h6>
                                    </div>
                                    <div class="card-pos-body">
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold text-muted mb-1">Estado Actual de la Orden</label>
                                            <select class="form-control font-weight-bold" v-model="formDiagnostico.estado">
                                                <option v-for="est in estadosFlujo" :key="est.codigo" :value="est.codigo">@{{ est.nombre }}</option>
                                                <option value="EN_GARANTIA">En Garantía</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold text-muted mb-1">Técnico Responsable</label>
                                            <select class="form-control" v-model="formDiagnostico.cod_usuario_tecnico">
                                                <option :value="null">-- Seleccionar Técnico --</option>
                                                <option v-for="t in tecnicos" :key="t.cod_usuarios" :value="t.cod_usuarios">@{{ t.nom_usuarios }}</option>
                                            </select>
                                        </div>

                                        <div class="row">
                                            <div class="col-6 mb-2">
                                                <label class="small font-weight-bold text-muted mb-1">Mano de Obra (Gs.)</label>
                                                <input type="number" class="form-control font-cairo font-weight-bold" v-model.number="formDiagnostico.costo_mano_obra" min="0" step="1000">
                                            </div>
                                            <div class="col-6 mb-2">
                                                <label class="small font-weight-bold text-muted mb-1">Descuento (Gs.)</label>
                                                <input type="number" class="form-control font-cairo" v-model.number="formDiagnostico.descuento" min="0" step="1000">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6 mb-2">
                                                <label class="small font-weight-bold text-muted mb-1">Días de Garantía</label>
                                                <input type="number" class="form-control font-cairo" v-model.number="formDiagnostico.dias_garantia" min="0" max="365">
                                            </div>
                                            <div class="col-6 mb-2 d-flex align-items-end">
                                                <div class="custom-control custom-switch mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="swGarantiaDiag" v-model="formDiagnostico.tiene_garantia">
                                                    <label class="custom-control-label small font-weight-bold" for="swGarantiaDiag">Con Garantía</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-2 rounded border mt-2 mb-3 bg-light font-cairo" style="font-size: 0.85rem;">
                                            <div class="d-flex justify-content-between py-1">
                                                <span>Mano de Obra:</span>
                                                <strong>Gs. @{{ formatNumber(formDiagnostico.costo_mano_obra) }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between py-1">
                                                <span>Repuestos Cargados (@{{ ordenActiva.repuestos ? ordenActiva.repuestos.length : 0 }}):</span>
                                                <strong>Gs. @{{ formatNumber(ordenActiva.total_repuestos) }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between py-1 text-danger" v-if="formDiagnostico.descuento > 0">
                                                <span>Descuento:</span>
                                                <strong>- Gs. @{{ formatNumber(formDiagnostico.descuento) }}</strong>
                                            </div>
                                            <div class="d-flex justify-content-between py-1 border-top font-weight-bold text-success" style="font-size: 1rem;">
                                                <span>TOTAL ORDEN:</span>
                                                <span>Gs. @{{ formatNumber(Math.max(0, (Number(formDiagnostico.costo_mano_obra) || 0) + (Number(ordenActiva.total_repuestos) || 0) - (Number(formDiagnostico.descuento) || 0))) }}</span>
                                            </div>
                                            <div v-if="permisos.ver_costo" class="d-flex justify-content-between pt-1 border-top text-muted small">
                                                <span>Costo Repuestos: Gs. @{{ formatNumber(ordenActiva.costo_repuestos) }}</span>
                                                <strong class="text-primary">Utilidad Neta: Gs. @{{ formatNumber(Math.max(0, (Number(formDiagnostico.costo_mano_obra) || 0) + (Number(ordenActiva.total_repuestos) || 0) - (Number(formDiagnostico.descuento) || 0) - (Number(ordenActiva.costo_repuestos) || 0))) }}</strong>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-success btn-block font-cairo font-weight-bold"
                                                :disabled="guardandoDiagnostico"
                                                @click="guardarDiagnosticoOrden">
                                            <i class="fas mr-1" :class="guardandoDiagnostico ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                                            Guardar Diagnóstico y Estado
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- PESTAÑA 2: INVENTARIO DE REPUESTOS         -->
                    <!-- ========================================== -->
                    <div v-show="tabOrden === 'repuestos'">
                        <div class="row">
                            <!-- Buscador de Inventario de Repuestos -->
                            <div class="col-md-6">
                                <div class="card-pos">
                                    <div class="card-pos-header">
                                        <h6 class="card-pos-title font-cairo">
                                            <i class="fas fa-boxes text-primary"></i> Catálogo e Inventario de Repuestos
                                        </h6>
                                        <button type="button" class="btn btn-xs btn-outline-primary font-weight-bold" @click="mostrarFormRepuestoLibre = !mostrarFormRepuestoLibre">
                                            <i class="fas fa-bolt mr-1"></i> Repuesto Libre / Externo
                                        </button>
                                    </div>
                                    <div class="card-pos-body">
                                        <!-- Formulario de Repuesto Libre / Externo -->
                                        <div v-if="mostrarFormRepuestoLibre" class="p-3 mb-3 rounded border bg-light">
                                            <h6 class="font-weight-bold small text-uppercase text-primary mb-2">
                                                <i class="fas fa-plus-circle mr-1"></i> Cargar Repuesto Externo (Fuera de Stock)
                                            </h6>
                                            <div class="form-group mb-2">
                                                <input type="text" class="form-control form-control-sm" v-model="repuestoLibre.descripcion" placeholder="Descripción del repuesto (Ej: Módulo OLED iPhone 12)...">
                                            </div>
                                            <div class="row">
                                                <div class="col-3">
                                                    <label class="small text-muted mb-0">Cant.</label>
                                                    <input type="number" class="form-control form-control-sm font-cairo" v-model.number="repuestoLibre.cantidad" min="1">
                                                </div>
                                                <div class="col-4" v-if="permisos.ver_costo">
                                                    <label class="small text-muted mb-0">Costo Compra</label>
                                                    <input type="number" class="form-control form-control-sm font-cairo" v-model.number="repuestoLibre.costo_unitario" min="0">
                                                </div>
                                                <div class="col-5">
                                                    <label class="small text-muted mb-0">Precio al Cliente</label>
                                                    <input type="number" class="form-control form-control-sm font-cairo font-weight-bold" v-model.number="repuestoLibre.precio_unitario" min="0">
                                                </div>
                                            </div>
                                            <div class="mt-2 text-right">
                                                <button type="button" class="btn btn-xs btn-secondary mr-1" @click="mostrarFormRepuestoLibre = false">Cancelar</button>
                                                <button type="button" class="btn btn-xs btn-success font-weight-bold" @click="agregarRepuestoLibreOrden">
                                                    <i class="fas fa-plus mr-1"></i> Asignar a la Orden
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Buscador de Artículos / Repuestos -->
                                        <div class="row mb-2">
                                            <div class="col-8">
                                                <div class="input-group input-group-sm">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" v-model="buscadorRepuesto.q"
                                                           @input="onBuscarRepuestosInput"
                                                           placeholder="Buscar repuesto por nombre o código...">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <select class="form-control form-control-sm" v-model="buscadorRepuesto.seccion" @change="buscarRepuestosCatalogo">
                                                    <option value="0">Todas las secciones</option>
                                                    <option v-for="sec in secciones" :key="sec.present_cod" :value="sec.present_cod">@{{ sec.present_descripcion }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div style="max-height: 320px; overflow-y: auto;" class="border rounded">
                                            <table class="table table-sm table-hover mb-0" style="font-size: 0.8rem;">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Repuesto / Artículo</th>
                                                        <th class="text-center">Stock</th>
                                                        <th class="text-right">Precio</th>
                                                        <th class="text-center" style="width: 45px;">+</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-if="buscadorRepuesto.cargando">
                                                        <td colspan="4" class="text-center py-3 text-muted">
                                                            <i class="fas fa-spinner fa-spin mr-1"></i> Buscando en inventario...
                                                        </td>
                                                    </tr>
                                                    <tr v-else-if="!buscadorRepuesto.resultados.length">
                                                        <td colspan="4" class="text-center py-3 text-muted">
                                                            No se encontraron artículos con ese criterio.
                                                        </td>
                                                    </tr>
                                                    <tr v-else v-for="art in buscadorRepuesto.resultados" :key="art.ARTICULOS_cod">
                                                        <td>
                                                            <div class="font-weight-bold">@{{ art.producto_nombre }}</div>
                                                            <small class="text-muted">
                                                                Cód: @{{ art.producto_c_barra || art.ARTICULOS_cod }} · @{{ art.present_descripcion }}
                                                                <span v-if="permisos.ver_costo"> · Costo: Gs. @{{ formatNumber(art.producto_costo_compra) }}</span>
                                                            </small>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <span class="badge" :class="Number(art.stock_disponible) > 0 ? 'badge-success' : 'badge-danger'">
                                                                @{{ Number(art.stock_disponible) }}
                                                            </span>
                                                        </td>
                                                        <td class="text-right align-middle font-cairo font-weight-bold">
                                                            Gs. @{{ formatNumber(art.pre_venta1) }}
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <button type="button" class="btn btn-xs btn-success" @click="agregarRepuestoDeCatalogo(art)" title="Usar este repuesto">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lista de Repuestos Asignados a la Orden -->
                            <div class="col-md-6">
                                <div class="card-pos">
                                    <div class="card-pos-header">
                                        <h6 class="card-pos-title font-cairo">
                                            <i class="fas fa-microchip text-success"></i> Repuestos Instalados / Asignados a la Orden
                                        </h6>
                                        <span class="font-cairo font-weight-bold text-success">Total: Gs. @{{ formatNumber(ordenActiva.total_repuestos) }}</span>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0" style="font-size: 0.82rem;">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Descripción del Repuesto</th>
                                                    <th class="text-center">Cant.</th>
                                                    <th class="text-right" v-if="permisos.ver_costo">Costo</th>
                                                    <th class="text-right">Precio</th>
                                                    <th class="text-right">Subtotal</th>
                                                    <th class="text-center" style="width: 40px;"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="!ordenActiva.repuestos || !ordenActiva.repuestos.length">
                                                    <td :colspan="permisos.ver_costo ? 6 : 5" class="text-center py-4 text-muted">
                                                        No se han asignado repuestos a esta reparación todavía.
                                                    </td>
                                                </tr>
                                                <tr v-else v-for="rep in ordenActiva.repuestos" :key="rep.id">
                                                    <td>
                                                        <div class="font-weight-bold">@{{ rep.descripcion }}</div>
                                                        <span v-if="rep.stock_descontado" class="badge badge-info" style="font-size: 0.65rem;">
                                                            <i class="fas fa-check mr-1"></i>Stock descontado
                                                        </span>
                                                        <span v-else-if="rep.es_libre" class="badge badge-secondary" style="font-size: 0.65rem;">
                                                            Externo / Libre
                                                        </span>
                                                    </td>
                                                    <td class="text-center align-middle font-cairo">@{{ rep.cantidad }}</td>
                                                    <td class="text-right align-middle font-cairo text-muted" v-if="permisos.ver_costo">
                                                        Gs. @{{ formatNumber(rep.costo_unitario) }}
                                                    </td>
                                                    <td class="text-right align-middle font-cairo">
                                                        Gs. @{{ formatNumber(rep.precio_unitario) }}
                                                    </td>
                                                    <td class="text-right align-middle font-cairo font-weight-bold">
                                                        Gs. @{{ formatNumber(rep.subtotal) }}
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <button type="button" class="btn btn-xs btn-outline-danger" @click="quitarRepuestoOrden(rep)" title="Quitar repuesto y reponer stock">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- PESTAÑA 3: EVIDENCIA FOTOGRÁFICA           -->
                    <!-- ========================================== -->
                    <div v-show="tabOrden === 'imagenes'">
                        <div class="card-pos">
                            <div class="card-pos-header">
                                <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                                    <h6 class="card-pos-title font-cairo mb-0">
                                        <i class="fas fa-camera text-info"></i> Galería Fotográfica del Equipo
                                    </h6>
                                    <select class="form-control form-control-sm" style="width: 170px;" v-model="nuevaFotoOrden.etapa">
                                        <option value="RECEPCION">Etapa: Recepción</option>
                                        <option value="DIAGNOSTICO">Etapa: Diagnóstico</option>
                                        <option value="REPARACION">Etapa: Reparación</option>
                                        <option value="ENTREGA">Etapa: Entrega</option>
                                        <option value="GARANTIA">Etapa: Garantía</option>
                                    </select>
                                    <input type="text" class="form-control form-control-sm" style="width: 240px;"
                                           v-model="nuevaFotoOrden.descripcion"
                                           placeholder="Comentario de la foto (opcional)...">
                                </div>

                                <div class="d-flex" style="gap: 6px;">
                                    <label class="btn btn-xs btn-primary mb-0 font-weight-bold" style="cursor: pointer;">
                                        <i class="fas fa-upload mr-1"></i> Subir Archivo
                                        <input type="file" accept="image/*" class="d-none" @change="onFileOrdenExistenteChange">
                                    </label>
                                    <button type="button" class="btn btn-xs btn-info font-weight-bold" @click="abrirModalWebcam(nuevaFotoOrden.etapa, ordenActiva.id)">
                                        <i class="fas fa-camera-retro mr-1"></i> Cámara Web
                                    </button>
                                    <button v-if="camaraConfig.configured" type="button" class="btn btn-xs btn-dark font-weight-bold"
                                            @click="capturarCamaraIp(nuevaFotoOrden.etapa, ordenActiva.id)" :disabled="subiendoImagen">
                                        <i class="fas fa-video mr-1"></i> Cámara IP
                                    </button>
                                </div>
                            </div>

                            <div class="card-pos-body">
                                <div v-if="!ordenActiva.imagenes || !ordenActiva.imagenes.length" class="text-center py-4 text-muted">
                                    <i class="fas fa-camera fa-2x mb-2 d-block"></i>
                                    No hay imágenes cargadas en esta orden. Usá los botones superiores para subir fotos desde tu PC/celular o capturar desde cámara.
                                </div>
                                <div v-else class="photo-grid">
                                    <div class="photo-card" v-for="img in ordenActiva.imagenes" :key="img.id">
                                        <img :src="img.url" @click="verFotoAmpliada(img.url, img.descripcion)">
                                        <button type="button" class="photo-del-btn" @click="eliminarImagenOrden(img)" title="Eliminar imagen">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <div class="photo-meta">
                                            <span class="badge badge-info mb-1" style="font-size: 0.65rem;">@{{ img.etapa }}</span>
                                            <div class="font-weight-bold text-truncate" :title="img.descripcion">@{{ img.descripcion || 'Evidencia' }}</div>
                                            <small class="text-muted">@{{ formatearFecha(img.created_at) }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- PESTAÑA 4: PAGOS, POS Y ENTREGA DE EQUIPO  -->
                    <!-- ========================================== -->
                    <div v-show="tabOrden === 'entrega'">
                        <div class="row">
                            <!-- Columna Izquierda: Pagos / Señas y Envío a POS -->
                            <div class="col-md-6">
                                <div class="card-pos">
                                    <div class="card-pos-header">
                                        <h6 class="card-pos-title font-cairo">
                                            <i class="fas fa-cash-register text-success"></i> Señas, Pagos y Facturación POS
                                        </h6>
                                        <button type="button" class="btn btn-xs btn-warning font-weight-bold font-cairo" @click="enviarOrdenAPuntoDeVenta(ordenActiva)">
                                            <i class="fas fa-shopping-cart mr-1"></i> Enviar a Punto de Venta (POS)
                                        </button>
                                    </div>
                                    <div class="card-pos-body">
                                        <!-- Formulario Registrar Pago Interno -->
                                        <div class="p-3 rounded border mb-3 bg-light" v-if="ordenActiva.saldo_pendiente > 0">
                                            <h6 class="small font-weight-bold text-uppercase text-success mb-2">
                                                <i class="fas fa-plus-circle mr-1"></i> Registrar Abono / Seña en esta Orden
                                            </h6>
                                            <div class="row">
                                                <div class="col-4 mb-2">
                                                    <label class="small text-muted mb-0">Tipo</label>
                                                    <select class="form-control form-control-sm" v-model="nuevoPago.tipo_pago">
                                                        <option value="ADELANTO">Seña / Adelanto</option>
                                                        <option value="ABONO">Abono Parcial</option>
                                                        <option value="COBRO_ENTREGA">Pago Total</option>
                                                    </select>
                                                </div>
                                                <div class="col-4 mb-2">
                                                    <label class="small text-muted mb-0">Medio</label>
                                                    <select class="form-control form-control-sm" v-model.number="nuevoPago.forma_cobro">
                                                        <option :value="1">Efectivo</option>
                                                        <option :value="2">Tarjeta</option>
                                                        <option :value="3">Transferencia/QR</option>
                                                    </select>
                                                </div>
                                                <div class="col-4 mb-2">
                                                    <label class="small text-muted mb-0">Monto (Gs.)</label>
                                                    <input type="number" class="form-control form-control-sm font-cairo font-weight-bold" v-model.number="nuevoPago.monto" min="1">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="chkCajaPagoModal" v-model="nuevoPago.registrar_caja">
                                                    <label class="custom-control-label small font-weight-bold" for="chkCajaPagoModal">Registrar entrada en Caja Abierta</label>
                                                </div>
                                                <button type="button" class="btn btn-xs btn-success font-weight-bold" @click="registrarPagoOrden">
                                                    <i class="fas fa-check mr-1"></i> Registrar Pago
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Tabla de Pagos Registrados -->
                                        <table class="table table-sm table-bordered mb-0" style="font-size: 0.8rem;">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Concepto</th>
                                                    <th class="text-right">Monto</th>
                                                    <th class="text-center" style="width: 35px;"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="!ordenActiva.pagos || !ordenActiva.pagos.length">
                                                    <td colspan="4" class="text-center py-3 text-muted">Sin pagos registrados aún.</td>
                                                </tr>
                                                <tr v-else v-for="p in ordenActiva.pagos" :key="p.id">
                                                    <td>@{{ formatearFecha(p.fecha_pago) }}</td>
                                                    <td>
                                                        <strong>@{{ p.tipo_pago }}</strong>
                                                        <small class="text-muted d-block">@{{ p.observacion }}</small>
                                                    </td>
                                                    <td class="text-right font-cairo font-weight-bold text-success">
                                                        Gs. @{{ formatNumber(p.monto) }}
                                                    </td>
                                                    <td class="text-center">
                                                        <button v-if="permisos.del" type="button" class="btn btn-xs btn-outline-danger" @click="eliminarPagoOrden(p)">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Columna Derecha: Entrega Formal del Celular y Activación de Garantía -->
                            <div class="col-md-6">
                                <div class="card-pos">
                                    <div class="card-pos-header" style="background: var(--dash-primary-light);">
                                        <h6 class="card-pos-title font-cairo text-success">
                                            <i class="fas fa-handshake"></i> Formalizar Entrega de Equipo y Emitir Garantía
                                        </h6>
                                    </div>
                                    <div class="card-pos-body">
                                        <div class="row">
                                            <div class="col-7 mb-2">
                                                <label class="small font-weight-bold text-muted mb-1">Persona que retira el equipo</label>
                                                <input type="text" class="form-control form-control-sm" v-model="formEntrega.retirado_por_nombre" placeholder="Nombre y apellido de quien retira">
                                            </div>
                                            <div class="col-5 mb-2">
                                                <label class="small font-weight-bold text-muted mb-1">Nro. de C.I. / Documento</label>
                                                <input type="text" class="form-control form-control-sm" v-model="formEntrega.retirado_por_ci" placeholder="Cédula de Identidad">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6 mb-2">
                                                <label class="small font-weight-bold text-muted mb-1">Cobertura de Garantía (Días)</label>
                                                <input type="number" class="form-control form-control-sm font-cairo font-weight-bold" v-model.number="formEntrega.dias_garantia" min="0" max="365">
                                            </div>
                                            <div class="col-6 mb-2 d-flex align-items-end">
                                                <div class="custom-control custom-switch mb-1">
                                                    <input type="checkbox" class="custom-control-input" id="swGarantiaEntrega" v-model="formEntrega.tiene_garantia">
                                                    <label class="custom-control-label small font-weight-bold" for="swGarantiaEntrega">Emitir Certificado con Garantía</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-2 rounded border mb-2 bg-light" v-if="ordenActiva.saldo_pendiente > 0">
                                            <div class="row align-items-center">
                                                <div class="col-6">
                                                    <label class="small font-weight-bold text-danger mb-0">Cobrar Saldo al Entregar (Gs.)</label>
                                                    <input type="number" class="form-control form-control-sm font-cairo font-weight-bold" v-model.number="formEntrega.monto_cobro_entrega" min="0">
                                                </div>
                                                <div class="col-6 pt-3">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="chkCajaEntrega" v-model="formEntrega.registrar_caja">
                                                        <label class="custom-control-label small font-weight-bold" for="chkCajaEntrega">Sumar a Caja Abierta</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold text-muted mb-1">Conformidad de Entrega</label>
                                            <input type="text" class="form-control form-control-sm" v-model="formEntrega.conformidad_cliente" placeholder="Equipo entregado encendido, probado y en conformidad...">
                                        </div>

                                        <button type="button" class="btn btn-success btn-block font-cairo font-weight-bold py-2 mt-2"
                                                :disabled="entregandoEquipo"
                                                @click="confirmarEntregaEquipo(true)">
                                            <i class="fas mr-1" :class="entregandoEquipo ? 'fa-spinner fa-spin' : 'fa-award'"></i>
                                            Registrar Entrega e Imprimir Certificado de Garantía
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========================================== -->
                    <!-- PESTAÑA 5: GARANTÍAS E HISTORIAL           -->
                    <!-- ========================================== -->
                    <div v-show="tabOrden === 'garantia'">
                        <div class="row">
                            <!-- Gestión de Garantías -->
                            <div class="col-md-6">
                                <div class="card-pos">
                                    <div class="card-pos-header">
                                        <h6 class="card-pos-title font-cairo">
                                            <i class="fas fa-shield-alt text-danger"></i> Cobertura y Reclamos de Garantía
                                        </h6>
                                        <span v-if="ordenActiva.fecha_vencimiento_garantia" class="badge" :class="garantiaActivaModal ? 'badge-success' : 'badge-danger'">
                                            @{{ garantiaActivaModal ? ('Garantía Vigente (' + diasRestantesModal + 'd)') : 'Garantía Vencida' }}
                                        </span>
                                    </div>
                                    <div class="card-pos-body">
                                        <!-- Formulario para abrir reclamo de garantía -->
                                        <div class="p-3 rounded border mb-3 bg-light">
                                            <h6 class="small font-weight-bold text-uppercase text-danger mb-2">
                                                <i class="fas fa-exclamation-circle mr-1"></i> Registrar Reclamo / Reingreso por Garantía
                                            </h6>
                                            <div class="form-group mb-2">
                                                <textarea class="form-control form-control-sm" rows="2" v-model="nuevaGarantia.motivo_reclamo"
                                                          placeholder="Describí la falla por la que el cliente reclama la garantía..."></textarea>
                                            </div>
                                            <div class="form-group mb-2">
                                                <input type="text" class="form-control form-control-sm" v-model="nuevaGarantia.evaluacion_tecnica"
                                                       placeholder="Evaluación preliminar (ej. Sin golpes visibles, entra en garantía)...">
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 6px;">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="chkCubreGarantia" v-model="nuevaGarantia.cubre_garantia">
                                                    <label class="custom-control-label small font-weight-bold" for="chkCubreGarantia">Cubierto por Garantía</label>
                                                </div>
                                                <div class="custom-control custom-checkbox" v-if="nuevaGarantia.cubre_garantia">
                                                    <input type="checkbox" class="custom-control-input" id="chkGenerarReingreso" v-model="nuevaGarantia.generar_reingreso">
                                                    <label class="custom-control-label small font-weight-bold text-primary" for="chkGenerarReingreso">Generar nueva Orden de Reingreso</label>
                                                </div>
                                                <button type="button" class="btn btn-xs btn-danger font-weight-bold" @click="registrarReclamoGarantia">
                                                    <i class="fas fa-shield-alt mr-1"></i> Registrar Garantía
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Listado de reclamos de garantía de esta orden -->
                                        <div v-if="!ordenActiva.garantias || !ordenActiva.garantias.length" class="text-center py-3 text-muted small">
                                            Esta orden no registra reclamos de garantía.
                                        </div>
                                        <div v-else>
                                            <div v-for="g in ordenActiva.garantias" :key="g.id" class="p-2 rounded border mb-2">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <strong class="small">Reclamo #@{{ g.id }} (@{{ formatearFecha(g.fecha_reclamo) }})</strong>
                                                    <span class="badge" :class="g.estado === 'RESUELTO' ? 'badge-success' : (g.estado === 'RECHAZADO' ? 'badge-danger' : 'badge-warning')">
                                                        @{{ g.estado }}
                                                    </span>
                                                </div>
                                                <div class="small mb-1"><strong>Motivo:</strong> @{{ g.motivo_reclamo }}</div>
                                                <div class="small text-muted" v-if="g.evaluacion_tecnica"><strong>Evaluación:</strong> @{{ g.evaluacion_tecnica }}</div>
                                                <div class="d-flex justify-content-between align-items-center mt-2 pt-1 border-top">
                                                    <div>
                                                        <button v-if="g.orden_reingreso" type="button" class="btn btn-xs btn-outline-primary" @click="abrirGestionOrden(g.orden_reingreso.id)">
                                                            Ver Reingreso @{{ g.orden_reingreso.codigo_orden }}
                                                        </button>
                                                    </div>
                                                    <div class="btn-group btn-group-sm" v-if="g.estado !== 'RESUELTO'">
                                                        <button type="button" class="btn btn-xs btn-outline-success" @click="cambiarEstadoGarantia(g, 'RESUELTO')">Marcar Resuelto</button>
                                                        <button type="button" class="btn btn-xs btn-outline-danger" @click="cambiarEstadoGarantia(g, 'RECHAZADO')">Rechazar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bitácora / Historial de la Orden -->
                            <div class="col-md-6">
                                <div class="card-pos">
                                    <div class="card-pos-header">
                                        <h6 class="card-pos-title font-cairo">
                                            <i class="fas fa-history text-secondary"></i> Bitácora y Trazabilidad de la Orden
                                        </h6>
                                        <button v-if="permisos.del && ordenActiva.estado !== 'ANULADO'" type="button" class="btn btn-xs btn-outline-danger" @click="anularOrden(ordenActiva)">
                                            <i class="fas fa-ban mr-1"></i> Anular Orden
                                        </button>
                                    </div>
                                    <div class="card-pos-body" style="max-height: 390px; overflow-y: auto;">
                                        <div class="timeline-custom" v-if="ordenActiva.historial && ordenActiva.historial.length">
                                            <div class="timeline-item-custom" v-for="h in ordenActiva.historial" :key="h.id">
                                                <div class="d-flex justify-content-between">
                                                    <strong class="small">@{{ h.titulo }}</strong>
                                                    <small class="text-muted">@{{ formatearFecha(h.created_at) }} @{{ formatearHora(h.created_at) }}</small>
                                                </div>
                                                <div class="small text-muted mt-1">@{{ h.comentario }}</div>
                                                <small class="text-primary" v-if="h.usuario"><i class="fas fa-user mr-1"></i>@{{ h.usuario.nom_usuarios }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- MODAL CÁMARA WEB HTML5 EN VIVO                               -->
    <!-- ============================================================ -->
    <div class="modal fade" id="modalWebcamReparacion" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header py-2 bg-dark text-white">
                    <h6 class="modal-title font-cairo mb-0"><i class="fas fa-camera-retro mr-1"></i> Capturar Fotografía con Cámara Web</h6>
                    <button type="button" class="close text-white" data-dismiss="modal" @click="detenerWebcam">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center p-3">
                    <video ref="webcamVideo" autoplay playsinline style="width: 100%; max-height: 320px; border-radius: 8px; background: #000;"></video>
                    <canvas ref="webcamCanvas" class="d-none"></canvas>
                    <div class="form-group mt-2 mb-0 text-left">
                        <label class="small font-weight-bold text-muted mb-1">Descripción de la foto</label>
                        <input type="text" class="form-control form-control-sm" v-model="webcamModal.descripcion" placeholder="Ej: Estado de pantalla en recepción...">
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal" @click="detenerWebcam">Cancelar</button>
                    <button type="button" class="btn btn-sm btn-success font-weight-bold" @click="tomarFotoWebcam" :disabled="subiendoImagen">
                        <i class="fas fa-camera mr-1"></i> Capturar y Guardar Foto
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- MODAL NUEVO CLIENTE RÁPIDO                                   -->
    <!-- ============================================================ -->
    <div class="modal fade" id="modalNuevoClienteReparacion" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header py-2" style="background: var(--dash-primary); color: #fff;">
                    <h6 class="modal-title font-cairo mb-0"><i class="fas fa-user-plus mr-1"></i> Registro Rápido de Cliente</h6>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label class="small font-weight-bold">Nombre y Apellido <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" v-model="nuevoCliente.nombre" placeholder="Nombre completo del cliente">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-2">
                            <label class="small font-weight-bold">C.I. o RUC <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" v-model="nuevoCliente.doc" placeholder="Nro. de documento">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="small font-weight-bold">Celular / WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" v-model="nuevoCliente.celular" placeholder="0981...">
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="small font-weight-bold">Dirección</label>
                        <input type="text" class="form-control form-control-sm" v-model="nuevoCliente.direccion" placeholder="Dirección o barrio">
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-sm btn-success font-weight-bold" @click="guardarNuevoCliente" :disabled="guardandoCliente">
                        <i class="fas fa-save mr-1"></i> Guardar y Seleccionar
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
            moduloActivo: {{ !empty($reparacionConfig['activo']) ? 'true' : 'false' }},
            vistaActiva: 'ordenes', // ordenes | recepcion | garantias
            tabOrden: 'diagnostico',
            sucursales: @json($sucursales),
            secciones: @json($secciones),
            tecnicos: @json($tecnicos),
            permisos: @json($permisosModulo),
            reparacionConfig: @json($reparacionConfig),
            camaraConfig: @json($camaraConfig),
            sucursalActual: {{ (int) ($sucursalActual ?? 1) }},
            articuloLibreId: {{ (int) ($articuloLibreId ?? 1) }},

            // Listado y KPIs
            cargandoOrdenes: false,
            ordenes: [],
            paginacion: { current_page: 1, last_page: 1, total: 0 },
            kpis: {
                recibidos: 0,
                en_taller: 0,
                listos_entrega: 0,
                entregados: 0,
                en_garantia: 0,
                saldo_por_cobrar: 0
            },
            filtros: {
                q: '',
                estado: 'TODOS',
                sucursal: '0',
                tecnico: '0',
                desde: '',
                hasta: ''
            },

            // Datos rápidos para chips
            marcasRapidas: ['Samsung', 'Apple iPhone', 'Xiaomi', 'Motorola', 'Honor', 'Huawei', 'Realme'],
            accesoriosRapidos: ['Funda / Estuche', 'Chip SIM', 'Memoria MicroSD', 'Bandeja SIM', 'Cargador'],
            fallasFrecuentes: ['Cambio de Pantalla / Módulo', 'No carga / Pin de carga', 'Cambio de Batería', 'Mojado / Sulfatado', 'No enciende', 'Tapa trasera rota', 'Software / FRP / Cuenta'],

            estadosFlujo: [
                { codigo: 'RECIBIDO', nombre: 'Recibido' },
                { codigo: 'EN_DIAGNOSTICO', nombre: 'En Diagnóstico' },
                { codigo: 'PRESUPUESTADO', nombre: 'Presupuestado' },
                { codigo: 'APROBADO', nombre: 'Aprobado' },
                { codigo: 'ESPERANDO_REPUESTO', nombre: 'Esp. Repuesto' },
                { codigo: 'EN_REPARACION', nombre: 'En Reparación' },
                { codigo: 'REPARADO', nombre: 'Reparado (Listo)' },
                { codigo: 'NO_REPARABLE', nombre: 'No Reparable' },
                { codigo: 'ENTREGADO', nombre: 'Entregado' }
            ],

            checklistDefinicion: [
                { key: 'enciende', label: 'Equipo Enciende', icon: 'fas fa-power-off' },
                { key: 'pantalla_sana', label: 'Pantalla sin roturas', icon: 'fas fa-mobile-alt' },
                { key: 'tactil_funciona', label: 'Táctil responde', icon: 'fas fa-hand-pointer' },
                { key: 'botones_ok', label: 'Botones físicos', icon: 'fas fa-sliders-h' },
                { key: 'camara_delantera', label: 'Cámara Frontal', icon: 'fas fa-camera' },
                { key: 'camara_trasera', label: 'Cámara Trasera', icon: 'fas fa-camera-retro' },
                { key: 'carga_ok', label: 'Pin de Carga OK', icon: 'fas fa-charging-station' },
                { key: 'altavoz_mic', label: 'Altavoz y Micrófono', icon: 'fas fa-volume-up' },
                { key: 'wifi_senal', label: 'WiFi y Señal Red', icon: 'fas fa-wifi' },
                { key: 'lector_huella', label: 'Huella / FaceID', icon: 'fas fa-fingerprint' },
                { key: 'bandeja_sim', label: 'Bandeja Porta SIM', icon: 'fas fa-sim-card' },
                { key: 'tapa_trasera_sana', label: 'Tapa Trasera Sana', icon: 'fas fa-shield-alt' },
                { key: 'rastros_humedad', label: 'Libre de Humedad', icon: 'fas fa-tint-slash' },
                { key: 'bateria_hinchada', label: 'Batería Normal', icon: 'fas fa-battery-full' }
            ],

            nodosPatron: [
                { id: 1, x: 25, y: 25 }, { id: 2, x: 60, y: 25 }, { id: 3, x: 95, y: 25 },
                { id: 4, x: 25, y: 60 }, { id: 5, x: 60, y: 60 }, { id: 6, x: 95, y: 60 },
                { id: 7, x: 25, y: 95 }, { id: 8, x: 60, y: 95 }, { id: 9, x: 95, y: 95 }
            ],

            // Cliente Picker
            clienteSeleccionado: null,
            clientePicker: {
                abierto: false,
                busqueda: '',
                cargando: false,
                resultados: [],
                timer: null
            },
            guardandoCliente: false,
            nuevoCliente: { nombre: '', doc: '', celular: '', direccion: '' },

            // Formulario de Recepción
            guardandoRecepcion: false,
            formRecepcion: {
                cliente_id: null,
                suc_cod: {{ (int) ($sucursalActual ?? 1) }},
                cod_usuario_tecnico: null,
                tipo_equipo: 'Celular',
                marca: '',
                modelo: '',
                color: '',
                imei_serie: '',
                tipo_bloqueo: 'NINGUNO',
                clave_bloqueo: '',
                patron_bloqueo: '',
                accesorios_dejados: '',
                checklist_recepcion: {},
                estado_fisico_obs: '',
                falla_reportada: '',
                diagnostico_tecnico: '',
                prioridad: 'NORMAL',
                fecha_prometida: '',
                costo_mano_obra: 0,
                monto_adelanto: 0,
                forma_cobro_adelanto: 1,
                registrar_caja_adelanto: true,
                dias_garantia: {{ (int) ($reparacionConfig['garantia_dias_default'] ?? 30) }},
                imagenes_iniciales: []
            },

            // Orden Activa en Modal
            ordenActiva: null,
            garantiaActivaModal: false,
            diasRestantesModal: 0,
            guardandoDiagnostico: false,
            formDiagnostico: {
                estado: 'RECIBIDO',
                cod_usuario_tecnico: null,
                diagnostico_tecnico: '',
                solucion_aplicada: '',
                observaciones_internas: '',
                costo_mano_obra: 0,
                descuento: 0,
                tiene_garantia: true,
                dias_garantia: 30
            },

            // Buscador de Repuestos
            buscadorRepuesto: {
                q: '',
                seccion: 0,
                cargando: false,
                resultados: [],
                timer: null
            },
            mostrarFormRepuestoLibre: false,
            repuestoLibre: {
                descripcion: '',
                cantidad: 1,
                costo_unitario: 0,
                precio_unitario: 0
            },

            // Subida de Fotos
            subiendoImagen: false,
            nuevaFotoOrden: {
                etapa: 'DIAGNOSTICO',
                descripcion: ''
            },
            webcamStream: null,
            webcamModal: {
                etapa: 'RECEPCION',
                reparacion_id: null,
                descripcion: ''
            },

            // Pagos, Entrega y Garantías
            nuevoPago: {
                tipo_pago: 'ABONO',
                forma_cobro: 1,
                monto: 0,
                registrar_caja: true,
                observacion: ''
            },
            entregandoEquipo: false,
            formEntrega: {
                retirado_por_nombre: '',
                retirado_por_ci: '',
                tiene_garantia: true,
                dias_garantia: 30,
                monto_cobro_entrega: 0,
                forma_cobro: 1,
                registrar_caja: true,
                conformidad_cliente: 'Equipo entregado funcionando y verificado por el cliente.'
            },
            nuevaGarantia: {
                motivo_reclamo: '',
                evaluacion_tecnica: '',
                cubre_garantia: true,
                generar_reingreso: true
            }
        },
        computed: {
            nodosSeleccionadosRecepcion: function () {
                if (!this.formRecepcion.patron_bloqueo) return [];
                return this.formRecepcion.patron_bloqueo
                    .split('-')
                    .map(function (x) { return parseInt(x, 10); })
                    .filter(function (n) { return n >= 1 && n <= 9; });
            },
            segmentosPatronRecepcion: function () {
                var seq = this.nodosSeleccionadosRecepcion;
                var lines = [];
                var map = {};
                this.nodosPatron.forEach(function (n) { map[n.id] = n; });
                for (var i = 0; i < seq.length - 1; i++) {
                    var p1 = map[seq[i]];
                    var p2 = map[seq[i + 1]];
                    if (p1 && p2) {
                        lines.push({ x1: p1.x, y1: p1.y, x2: p2.x, y2: p2.y });
                    }
                }
                return lines;
            }
        },
        methods: {
            formatNumber: function (val) {
                var n = Math.round(Number(val) || 0);
                return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            },
            labelEstado: function (est) {
                var mapa = {
                    'RECIBIDO': 'Recibido',
                    'EN_DIAGNOSTICO': 'En Diagnóstico',
                    'PRESUPUESTADO': 'Presupuestado',
                    'APROBADO': 'Aprobado',
                    'ESPERANDO_REPUESTO': 'Esp. Repuesto',
                    'EN_REPARACION': 'En Reparación',
                    'REPARADO': 'Reparado (Listo)',
                    'NO_REPARABLE': 'No Reparable',
                    'ENTREGADO': 'Entregado',
                    'EN_GARANTIA': 'En Garantía',
                    'ANULADO': 'Anulado'
                };
                return mapa[est] || est;
            },
            formatearFecha: function (dt) {
                if (!dt) return '-';
                var partes = dt.split(' ')[0].split('-');
                if (partes.length === 3) return partes[2] + '/' + partes[1] + '/' + partes[0];
                return dt;
            },
            formatearSoloFecha: function (f) {
                if (!f) return '-';
                var partes = f.split('-');
                if (partes.length === 3) return partes[2] + '/' + partes[1] + '/' + partes[0];
                return f;
            },
            formatearHora: function (dt) {
                if (!dt) return '';
                var partes = dt.split(' ');
                if (partes.length >= 2) return partes[1].substring(0, 5) + ' hs';
                return '';
            },
            esGarantiaVigenteLista: function (venc) {
                if (!venc) return false;
                var hoy = new Date();
                hoy.setHours(0, 0, 0, 0);
                var fv = new Date(venc + 'T00:00:00');
                return fv >= hoy;
            },

            /* Control Superusuario */
            toggleModuloSuperusuario: function () {
                var self = this;
                Swal.fire({
                    title: this.moduloActivo ? '¿Deshabilitar Módulo de Reparaciones?' : '¿Habilitar Módulo de Reparaciones?',
                    text: this.moduloActivo
                        ? 'El módulo dejará de estar disponible para los demás usuarios del sistema hasta que lo vuelvas a activar.'
                        : 'El módulo quedará habilitado inmediatamente en el menú principal para los roles con permiso.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: this.moduloActivo ? '#e11d48' : '#0a4d36',
                    confirmButtonText: this.moduloActivo ? 'Sí, deshabilitar' : 'Sí, habilitar',
                    cancelButtonText: 'Cancelar'
                }).then(function (res) {
                    if (res.value) {
                        axios.post('{{ route("reparacion.toggle_modulo") }}', {
                            activo: !self.moduloActivo ? 1 : 0
                        }).then(function (r) {
                            if (r.data && r.data.success) {
                                self.moduloActivo = !!r.data.activo;
                                Swal.fire('Actualizado', r.data.message, 'success');
                            }
                        });
                    }
                });
            },

            /* Vistas y Listado */
            cambiarVista: function (vista) {
                this.vistaActiva = vista;
                if (vista === 'ordenes' || vista === 'garantias') {
                    this.cargarOrdenes(1);
                }
            },
            filtrarPorKpi: function (estado) {
                this.vistaActiva = 'ordenes';
                this.filtros.estado = estado;
                this.cargarOrdenes(1);
            },
            limpiarFiltros: function () {
                this.filtros = {
                    q: '',
                    estado: 'TODOS',
                    sucursal: '0',
                    tecnico: '0',
                    desde: '',
                    hasta: ''
                };
                this.cargarOrdenes(1);
            },
            cargarOrdenes: function (page) {
                var self = this;
                this.cargandoOrdenes = true;
                axios.get('{{ route("reparacion.index") }}', {
                    params: {
                        page: page || 1,
                        q: this.filtros.q,
                        estado: this.filtros.estado,
                        sucursal: this.filtros.sucursal,
                        tecnico: this.filtros.tecnico,
                        desde: this.filtros.desde,
                        hasta: this.filtros.hasta,
                        solo_garantias: this.vistaActiva === 'garantias' ? 1 : 0
                    }
                }).then(function (res) {
                    if (res.data) {
                        var ord = res.data.ordenes || {};
                        self.ordenes = ord.data || [];
                        self.paginacion = {
                            current_page: ord.current_page || 1,
                            last_page: ord.last_page || 1,
                            total: ord.total || 0
                        };
                        if (res.data.kpis) {
                            self.kpis = res.data.kpis;
                        }
                    }
                }).finally(function () {
                    self.cargandoOrdenes = false;
                });
            },

            /* Patrón de Desbloqueo Interactivo */
            esNodoActivo: function (id) {
                return this.nodosSeleccionadosRecepcion.indexOf(id) !== -1;
            },
            esPrimerNodo: function (id) {
                return this.nodosSeleccionadosRecepcion.length > 0 && this.nodosSeleccionadosRecepcion[0] === id;
            },
            toggleNodoPatron: function (id) {
                var actual = this.nodosSeleccionadosRecepcion.slice();
                var idx = actual.indexOf(id);
                if (idx === -1) {
                    actual.push(id);
                } else if (idx === actual.length - 1) {
                    actual.pop();
                }
                this.formRecepcion.patron_bloqueo = actual.join('-');
            },
            limpiarPatronRecepcion: function () {
                this.formRecepcion.patron_bloqueo = '';
            },

            /* Checklist y Chips de Recepción */
            inicializarChecklistRecepcion: function () {
                var chk = {};
                this.checklistDefinicion.forEach(function (it) {
                    chk[it.key] = 'SI';
                });
                this.formRecepcion.checklist_recepcion = chk;
            },
            setChecklistMasivo: function (valor) {
                var chk = {};
                this.checklistDefinicion.forEach(function (it) {
                    chk[it.key] = valor;
                });
                this.formRecepcion.checklist_recepcion = chk;
            },
            toggleAccesorioChip: function (acc) {
                var actual = (this.formRecepcion.accesorios_dejados || '').trim();
                if (!actual) {
                    this.formRecepcion.accesorios_dejados = acc;
                } else if (actual.toLowerCase().indexOf(acc.toLowerCase()) === -1) {
                    this.formRecepcion.accesorios_dejados = actual + ', ' + acc;
                }
            },
            agregarFallaChip: function (falla) {
                var actual = (this.formRecepcion.falla_reportada || '').trim();
                if (!actual) {
                    this.formRecepcion.falla_reportada = falla;
                } else if (actual.toLowerCase().indexOf(falla.toLowerCase()) === -1) {
                    this.formRecepcion.falla_reportada = actual + '. ' + falla;
                }
            },

            /* Buscador de Clientes */
            abrirBuscadorCliente: function () {
                this.clientePicker.abierto = true;
                this.buscarClientes();
            },
            onBuscarClienteInput: function () {
                var self = this;
                this.clientePicker.abierto = true;
                clearTimeout(this.clientePicker.timer);
                this.clientePicker.timer = setTimeout(function () {
                    self.buscarClientes();
                }, 250);
            },
            buscarClientes: function () {
                var self = this;
                this.clientePicker.cargando = true;
                axios.get('{{ url("cliente/buscar") }}', {
                    params: { q: this.clientePicker.busqueda, limit: 25 }
                }).then(function (res) {
                    self.clientePicker.resultados = Array.isArray(res.data) ? res.data : [];
                }).finally(function () {
                    self.clientePicker.cargando = false;
                });
            },
            seleccionarCliente: function (c) {
                this.clienteSeleccionado = c;
                this.formRecepcion.cliente_id = c.CLIENTES_cod || c.clientes_cod;
                this.clientePicker.busqueda = c.cliente_nombre;
                this.clientePicker.abierto = false;
            },
            quitarCliente: function () {
                this.clienteSeleccionado = null;
                this.formRecepcion.cliente_id = null;
                this.clientePicker.busqueda = '';
            },
            abrirModalNuevoCliente: function () {
                this.nuevoCliente = {
                    nombre: this.clientePicker.busqueda || '',
                    doc: '',
                    celular: '',
                    direccion: ''
                };
                this.clientePicker.abierto = false;
                $('#modalNuevoClienteReparacion').modal('show');
            },
            guardarNuevoCliente: function () {
                if (!this.nuevoCliente.nombre.trim() || !this.nuevoCliente.doc.trim()) {
                    Swal.fire('Atención', 'Completá el nombre y documento del cliente.', 'warning');
                    return;
                }
                var self = this;
                this.guardandoCliente = true;
                axios.post('{{ url("cliente") }}', {
                    cliente: {
                        nombre: this.nuevoCliente.nombre,
                        doc: this.nuevoCliente.doc,
                        celular: this.nuevoCliente.celular,
                        telefono: '',
                        direccion: this.nuevoCliente.direccion,
                        correo: '',
                        idciudad: 1
                    }
                }).then(function (res) {
                    $('#modalNuevoClienteReparacion').modal('hide');
                    if (res.data && res.data.cliente) {
                        self.seleccionarCliente(res.data.cliente);
                    }
                }).catch(function () {
                    Swal.fire('Error', 'No se pudo registrar el cliente.', 'error');
                }).finally(function () {
                    self.guardandoCliente = false;
                });
            },

            /* Guardar Recepción */
            guardarRecepcion: function (imprimir) {
                if (!this.formRecepcion.cliente_id) {
                    Swal.fire('Atención', 'Seleccioná el cliente propietario del equipo.', 'warning');
                    return;
                }
                if (!this.formRecepcion.marca.trim() || !this.formRecepcion.modelo.trim()) {
                    Swal.fire('Atención', 'Completá la marca y modelo del celular.', 'warning');
                    return;
                }
                if (!this.formRecepcion.falla_reportada.trim()) {
                    Swal.fire('Atención', 'Describí la falla reportada o el motivo de ingreso.', 'warning');
                    return;
                }

                var self = this;
                this.guardandoRecepcion = true;

                axios.post('{{ route("reparacion.store") }}', this.formRecepcion)
                    .then(function (res) {
                        if (res.data && res.data.success) {
                            var ordenId = res.data.id;
                            var cod = res.data.codigo_orden;
                            Swal.fire({
                                icon: 'success',
                                title: '¡Recepción Registrada!',
                                text: 'Se generó la Orden de Servicio Técnico ' + cod,
                                confirmButtonColor: '#0a4d36'
                            }).then(function () {
                                if (imprimir) {
                                    window.open('{{ url("reparacion") }}/' + ordenId + '/pdf/orden?autoprint=1', '_blank');
                                }
                                self.resetearFormRecepcion();
                                self.cambiarVista('ordenes');
                            });
                        }
                    })
                    .catch(function (err) {
                        var msg = (err.response && err.response.data && err.response.data.message)
                            ? err.response.data.message
                            : 'No se pudo guardar la recepción.';
                        Swal.fire('Error', msg, 'error');
                    })
                    .finally(function () {
                        self.guardandoRecepcion = false;
                    });
            },
            resetearFormRecepcion: function () {
                this.clienteSeleccionado = null;
                this.clientePicker.busqueda = '';
                this.formRecepcion.cliente_id = null;
                this.formRecepcion.marca = '';
                this.formRecepcion.modelo = '';
                this.formRecepcion.color = '';
                this.formRecepcion.imei_serie = '';
                this.formRecepcion.tipo_bloqueo = 'NINGUNO';
                this.formRecepcion.clave_bloqueo = '';
                this.formRecepcion.patron_bloqueo = '';
                this.formRecepcion.accesorios_dejados = '';
                this.formRecepcion.estado_fisico_obs = '';
                this.formRecepcion.falla_reportada = '';
                this.formRecepcion.diagnostico_tecnico = '';
                this.formRecepcion.prioridad = 'NORMAL';
                this.formRecepcion.fecha_prometida = '';
                this.formRecepcion.costo_mano_obra = 0;
                this.formRecepcion.monto_adelanto = 0;
                this.formRecepcion.imagenes_iniciales = [];
                this.inicializarChecklistRecepcion();
            },

            /* Gestión Integral de Orden en Modal */
            abrirGestionOrden: function (id, tabInicial) {
                var self = this;
                axios.get('{{ url("reparacion") }}/' + id)
                    .then(function (res) {
                        if (res.data && res.data.reparacion) {
                            self.hidratarOrdenModal(res.data);
                            if (tabInicial) {
                                self.tabOrden = tabInicial;
                            }
                            $('#modalGestionOrden').modal('show');
                            self.buscarRepuestosCatalogo();
                        }
                    })
                    .catch(function () {
                        Swal.fire('Error', 'No se pudo cargar el detalle de la orden.', 'error');
                    });
            },
            hidratarOrdenModal: function (data) {
                var o = data.reparacion;
                this.ordenActiva = o;
                this.garantiaActivaModal = !!data.garantia_vigente;
                this.diasRestantesModal = data.dias_restantes_garantia || 0;

                this.formDiagnostico = {
                    estado: o.estado,
                    cod_usuario_tecnico: o.cod_usuario_tecnico,
                    diagnostico_tecnico: o.diagnostico_tecnico || '',
                    solucion_aplicada: o.solucion_aplicada || '',
                    observaciones_internas: o.observaciones_internas || '',
                    costo_mano_obra: Number(o.costo_mano_obra) || 0,
                    descuento: Number(o.descuento) || 0,
                    tiene_garantia: !!o.tiene_garantia,
                    dias_garantia: Number(o.dias_garantia) || 30
                };

                this.nuevoPago.monto = Math.max(0, Number(o.saldo_pendiente) || 0);

                this.formEntrega = {
                    retirado_por_nombre: o.retirado_por_nombre || (o.cliente ? o.cliente.cliente_nombre : ''),
                    retirado_por_ci: o.retirado_por_ci || (o.cliente ? (o.cliente.cliente_ci || o.cliente.cliente_ruc) : ''),
                    tiene_garantia: !!o.tiene_garantia,
                    dias_garantia: Number(o.dias_garantia) || 30,
                    monto_cobro_entrega: Math.max(0, Number(o.saldo_pendiente) || 0),
                    forma_cobro: 1,
                    registrar_caja: true,
                    conformidad_cliente: o.conformidad_cliente || 'Equipo entregado funcionando y en conformidad.'
                };
            },
            guardarDiagnosticoOrden: function () {
                var self = this;
                this.guardandoDiagnostico = true;
                axios.put('{{ url("reparacion") }}/' + this.ordenActiva.id + '/diagnostico', this.formDiagnostico)
                    .then(function (res) {
                        if (res.data && res.data.reparacion) {
                            self.hidratarOrdenModal(res.data);
                            self.cargarOrdenes(self.paginacion.current_page);
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Diagnóstico y estado guardados',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    })
                    .catch(function (err) {
                        var msg = (err.response && err.response.data && err.response.data.message) || 'No se pudo actualizar.';
                        Swal.fire('Error', msg, 'error');
                    })
                    .finally(function () {
                        self.guardandoDiagnostico = false;
                    });
            },

            /* Inventario de Repuestos en Modal */
            onBuscarRepuestosInput: function () {
                var self = this;
                clearTimeout(this.buscadorRepuesto.timer);
                this.buscadorRepuesto.timer = setTimeout(function () {
                    self.buscarRepuestosCatalogo();
                }, 250);
            },
            buscarRepuestosCatalogo: function () {
                var self = this;
                this.buscadorRepuesto.cargando = true;
                axios.get('{{ route("reparacion.repuestos.buscar") }}', {
                    params: {
                        q: this.buscadorRepuesto.q,
                        seccion: this.buscadorRepuesto.seccion,
                        suc_cod: this.ordenActiva ? this.ordenActiva.suc_cod : this.sucursalActual
                    }
                }).then(function (res) {
                    self.buscadorRepuesto.resultados = Array.isArray(res.data) ? res.data : [];
                }).finally(function () {
                    self.buscadorRepuesto.cargando = false;
                });
            },
            agregarRepuestoDeCatalogo: function (art) {
                var self = this;
                axios.post('{{ url("reparacion") }}/' + this.ordenActiva.id + '/repuestos', {
                    es_libre: false,
                    articulos_cod: art.ARTICULOS_cod || art.articulos_cod,
                    cantidad: 1,
                    precio_unitario: art.pre_venta1,
                    costo_unitario: art.producto_costo_compra || 0,
                    iva: art.iva || 10
                }).then(function (res) {
                    if (res.data && res.data.reparacion) {
                        self.hidratarOrdenModal(res.data);
                        self.buscarRepuestosCatalogo();
                        self.cargarOrdenes(self.paginacion.current_page);
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Repuesto asignado a la orden',
                            showConfirmButton: false,
                            timer: 1800
                        });
                    }
                }).catch(function (err) {
                    var msg = (err.response && err.response.data && err.response.data.message) || 'No se pudo asignar el repuesto.';
                    Swal.fire('Atención', msg, 'warning');
                });
            },
            agregarRepuestoLibreOrden: function () {
                if (!this.repuestoLibre.descripcion.trim()) {
                    Swal.fire('Atención', 'Ingresá la descripción del repuesto.', 'warning');
                    return;
                }
                var self = this;
                axios.post('{{ url("reparacion") }}/' + this.ordenActiva.id + '/repuestos', {
                    es_libre: true,
                    descripcion: this.repuestoLibre.descripcion,
                    cantidad: this.repuestoLibre.cantidad,
                    costo_unitario: this.repuestoLibre.costo_unitario,
                    precio_unitario: this.repuestoLibre.precio_unitario
                }).then(function (res) {
                    if (res.data && res.data.reparacion) {
                        self.hidratarOrdenModal(res.data);
                        self.mostrarFormRepuestoLibre = false;
                        self.repuestoLibre = { descripcion: '', cantidad: 1, costo_unitario: 0, precio_unitario: 0 };
                        self.cargarOrdenes(self.paginacion.current_page);
                    }
                }).catch(function (err) {
                    var msg = (err.response && err.response.data && err.response.data.message) || 'Error al agregar repuesto.';
                    Swal.fire('Error', msg, 'error');
                });
            },
            quitarRepuestoOrden: function (rep) {
                var self = this;
                axios.delete('{{ url("reparacion") }}/' + this.ordenActiva.id + '/repuestos/' + rep.id)
                    .then(function (res) {
                        if (res.data && res.data.reparacion) {
                            self.hidratarOrdenModal(res.data);
                            self.buscarRepuestosCatalogo();
                            self.cargarOrdenes(self.paginacion.current_page);
                        }
                    });
            },

            /* Subida y Captura de Imágenes */
            onFileRecepcionChange: function (e) {
                var file = e.target.files[0];
                if (!file) return;
                this.subirArchivoImagen(file, 'RECEPCION', 'Foto de recepción', null);
                e.target.value = '';
            },
            onFileOrdenExistenteChange: function (e) {
                var file = e.target.files[0];
                if (!file || !this.ordenActiva) return;
                this.subirArchivoImagen(file, this.nuevaFotoOrden.etapa, this.nuevaFotoOrden.descripcion, this.ordenActiva.id);
                e.target.value = '';
            },
            subirArchivoImagen: function (file, etapa, descripcion, reparacionId) {
                var self = this;
                this.subiendoImagen = true;
                var fd = new FormData();
                fd.append('imagen', file);
                fd.append('etapa', etapa);
                fd.append('descripcion', descripcion || '');
                if (reparacionId) {
                    fd.append('reparacion_id', reparacionId);
                }

                axios.post('{{ route("reparacion.imagen.subir") }}', fd)
                    .then(function (res) {
                        if (res.data && res.data.success) {
                            if (reparacionId && self.ordenActiva && res.data.imagen) {
                                self.ordenActiva.imagenes.unshift(res.data.imagen);
                                self.nuevaFotoOrden.descripcion = '';
                            } else {
                                self.formRecepcion.imagenes_iniciales.push({
                                    url: res.data.url,
                                    filename: res.data.filename,
                                    public_id: res.data.public_id,
                                    driver: res.data.driver,
                                    etapa: etapa,
                                    descripcion: res.data.descripcion
                                });
                            }
                        }
                    })
                    .catch(function (err) {
                        var msg = (err.response && err.response.data && err.response.data.message) || 'Error al subir la imagen.';
                        Swal.fire('Error', msg, 'error');
                    })
                    .finally(function () {
                        self.subiendoImagen = false;
                    });
            },
            capturarCamaraIp: function (etapa, reparacionId) {
                var self = this;
                this.subiendoImagen = true;
                axios.post('{{ route("reparacion.imagen.camara") }}', {
                    etapa: etapa,
                    reparacion_id: reparacionId,
                    descripcion: reparacionId ? this.nuevaFotoOrden.descripcion : 'Captura Cámara IP en recepción'
                }).then(function (res) {
                    if (res.data && res.data.success) {
                        if (reparacionId && self.ordenActiva && res.data.imagen) {
                            self.ordenActiva.imagenes.unshift(res.data.imagen);
                        } else {
                            self.formRecepcion.imagenes_iniciales.push(res.data);
                        }
                    }
                }).catch(function (err) {
                    var msg = (err.response && err.response.data && err.response.data.message) || 'No se pudo capturar desde la cámara IP.';
                    Swal.fire('Error de Cámara IP', msg, 'error');
                }).finally(function () {
                    self.subiendoImagen = false;
                });
            },
            abrirModalWebcam: function (etapa, reparacionId) {
                var self = this;
                this.webcamModal = {
                    etapa: etapa,
                    reparacion_id: reparacionId,
                    descripcion: reparacionId ? this.nuevaFotoOrden.descripcion : 'Foto tomada en recepción'
                };
                $('#modalWebcamReparacion').modal('show');
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' }, audio: false })
                        .then(function (stream) {
                            self.webcamStream = stream;
                            if (self.$refs.webcamVideo) {
                                self.$refs.webcamVideo.srcObject = stream;
                            }
                        })
                        .catch(function () {
                            Swal.fire('Cámara no disponible', 'No se pudo acceder a la cámara web del dispositivo.', 'warning');
                        });
                }
            },
            detenerWebcam: function () {
                if (this.webcamStream) {
                    this.webcamStream.getTracks().forEach(function (t) { t.stop(); });
                    this.webcamStream = null;
                }
            },
            tomarFotoWebcam: function () {
                var video = this.$refs.webcamVideo;
                var canvas = this.$refs.webcamCanvas;
                if (!video || !canvas || !video.videoWidth) return;

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                var ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                var dataUrl = canvas.toDataURL('image/jpeg', 0.88);

                var self = this;
                this.subiendoImagen = true;
                axios.post('{{ route("reparacion.imagen.base64") }}', {
                    base64: dataUrl,
                    etapa: this.webcamModal.etapa,
                    reparacion_id: this.webcamModal.reparacion_id,
                    descripcion: this.webcamModal.descripcion
                }).then(function (res) {
                    if (res.data && res.data.success) {
                        if (self.webcamModal.reparacion_id && self.ordenActiva && res.data.imagen) {
                            self.ordenActiva.imagenes.unshift(res.data.imagen);
                        } else {
                            self.formRecepcion.imagenes_iniciales.push(res.data);
                        }
                        self.detenerWebcam();
                        $('#modalWebcamReparacion').modal('hide');
                    }
                }).catch(function () {
                    Swal.fire('Error', 'No se pudo guardar la foto capturada.', 'error');
                }).finally(function () {
                    self.subiendoImagen = false;
                });
            },
            eliminarImagenOrden: function (img) {
                var self = this;
                axios.delete('{{ url("reparacion") }}/' + this.ordenActiva.id + '/imagen/' + img.id)
                    .then(function () {
                        var idx = self.ordenActiva.imagenes.indexOf(img);
                        if (idx !== -1) self.ordenActiva.imagenes.splice(idx, 1);
                    });
            },
            verFotoAmpliada: function (url, desc) {
                Swal.fire({
                    imageUrl: url,
                    imageAlt: desc || 'Evidencia fotográfica',
                    title: desc || 'Evidencia Fotográfica',
                    width: 680,
                    confirmButtonText: 'Cerrar',
                    confirmButtonColor: '#0a4d36'
                });
            },

            /* Pagos, POS y Entrega */
            registrarPagoOrden: function () {
                if (!this.nuevoPago.monto || this.nuevoPago.monto <= 0) {
                    Swal.fire('Atención', 'Ingresá un monto válido.', 'warning');
                    return;
                }
                var self = this;
                axios.post('{{ url("reparacion") }}/' + this.ordenActiva.id + '/pagos', this.nuevoPago)
                    .then(function (res) {
                        if (res.data && res.data.reparacion) {
                            self.hidratarOrdenModal(res.data);
                            self.cargarOrdenes(self.paginacion.current_page);
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Pago registrado correctamente',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    });
            },
            eliminarPagoOrden: function (p) {
                var self = this;
                axios.delete('{{ url("reparacion") }}/' + this.ordenActiva.id + '/pagos/' + p.id)
                    .then(function (res) {
                        if (res.data && res.data.reparacion) {
                            self.hidratarOrdenModal(res.data);
                            self.cargarOrdenes(self.paginacion.current_page);
                        }
                    });
            },
            enviarOrdenAPuntoDeVenta: function (orden) {
                axios.get('{{ url("reparacion") }}/' + orden.id + '/para-venta')
                    .then(function (res) {
                        if (res.data && res.data.success) {
                            sessionStorage.setItem('presupuesto_a_venta', JSON.stringify(res.data));
                            window.location.href = '{{ route("venta") }}';
                        }
                    })
                    .catch(function () {
                        Swal.fire('Error', 'No se pudo enviar la orden al Punto de Venta.', 'error');
                    });
            },
            confirmarEntregaEquipo: function (imprimirGarantia) {
                var self = this;
                this.entregandoEquipo = true;
                axios.post('{{ url("reparacion") }}/' + this.ordenActiva.id + '/entregar', this.formEntrega)
                    .then(function (res) {
                        if (res.data && res.data.reparacion) {
                            self.hidratarOrdenModal(res.data);
                            self.cargarOrdenes(self.paginacion.current_page);
                            if (imprimirGarantia) {
                                window.open('{{ url("reparacion") }}/' + self.ordenActiva.id + '/pdf/garantia?autoprint=1', '_blank');
                            }
                            Swal.fire('¡Equipo Entregado!', 'Se registró la entrega y se activó el certificado de garantía.', 'success');
                        }
                    })
                    .catch(function (err) {
                        var msg = (err.response && err.response.data && err.response.data.message) || 'Error al registrar entrega.';
                        Swal.fire('Error', msg, 'error');
                    })
                    .finally(function () {
                        self.entregandoEquipo = false;
                    });
            },

            /* Garantías */
            registrarReclamoGarantia: function () {
                if (!this.nuevaGarantia.motivo_reclamo.trim()) {
                    Swal.fire('Atención', 'Describí el motivo del reclamo de garantía.', 'warning');
                    return;
                }
                var self = this;
                axios.post('{{ url("reparacion") }}/' + this.ordenActiva.id + '/garantia', this.nuevaGarantia)
                    .then(function (res) {
                        if (res.data && res.data.reparacion) {
                            self.hidratarOrdenModal(res.data);
                            self.nuevaGarantia.motivo_reclamo = '';
                            self.nuevaGarantia.evaluacion_tecnica = '';
                            self.cargarOrdenes(self.paginacion.current_page);
                            Swal.fire('Garantía Registrada', 'El reclamo de garantía ha sido registrado.', 'success');
                        }
                    });
            },
            cambiarEstadoGarantia: function (g, nuevoEstado) {
                var self = this;
                axios.put('{{ url("reparacion") }}/' + this.ordenActiva.id + '/garantia/' + g.id, {
                    estado: nuevoEstado
                }).then(function (res) {
                    if (res.data && res.data.reparacion) {
                        self.hidratarOrdenModal(res.data);
                        self.cargarOrdenes(self.paginacion.current_page);
                    }
                });
            },
            anularOrden: function (orden) {
                var self = this;
                Swal.fire({
                    title: '¿Anular Orden ' + orden.codigo_orden + '?',
                    text: 'Se devolverán al stock todos los repuestos descontados en esta orden.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    confirmButtonText: 'Sí, anular orden',
                    cancelButtonText: 'Cancelar'
                }).then(function (r) {
                    if (r.value) {
                        axios.delete('{{ url("reparacion") }}/' + orden.id)
                            .then(function (res) {
                                $('#modalGestionOrden').modal('hide');
                                self.cargarOrdenes(self.paginacion.current_page);
                                Swal.fire('Anulada', res.data.message || 'Orden anulada.', 'success');
                            });
                    }
                });
            },

            /* Impresiones */
            imprimirOrden: function (id, formato) {
                window.open('{{ url("reparacion") }}/' + id + '/pdf/orden?formato=' + (formato || 'a4') + '&autoprint=1', '_blank');
            },
            imprimirEtiqueta: function (id) {
                window.open('{{ url("reparacion") }}/' + id + '/pdf/etiqueta?autoprint=1', '_blank');
            },
            imprimirGarantia: function (id) {
                window.open('{{ url("reparacion") }}/' + id + '/pdf/garantia?autoprint=1', '_blank');
            }
        },
        mounted: function () {
            this.inicializarChecklistRecepcion();
            this.cargarOrdenes(1);
            activarMenu('m_reparacion', '');

            var self = this;
            document.addEventListener('click', function (e) {
                var el = document.querySelector('.cliente-picker');
                if (el && !el.contains(e.target)) {
                    self.clientePicker.abierto = false;
                }
            });
        }
    });
</script>
@endsection
