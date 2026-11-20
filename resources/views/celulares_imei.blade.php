@extends('layouts.app')
@section('title', 'Equipos por IMEI y Plan Canje (Trade-In)')

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
        --dash-danger: #e11d48;
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
        --dash-danger: #fb7185;
        --dash-card-bg: #1f2937;
        --dash-border: #374151;
        --dash-text-main: #f3f4f6;
        --dash-text-muted: #9ca3af;
        --dash-panel-bg: #111827;
    }

    [v-cloak] { display: none !important; }

    #app {
        color: var(--dash-text-main);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

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
        flex-wrap: wrap;
    }
    .nav-pill-btn {
        border: none;
        background: transparent;
        color: var(--dash-text-muted);
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.45rem 1rem;
        border-radius: 999px;
        cursor: pointer;
        transition: all 0.18s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .nav-pill-btn.active {
        background: var(--dash-primary);
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(10, 77, 54, 0.25);
    }

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
        font-size: 1.2rem;
        font-weight: 800;
        line-height: 1.1;
        color: var(--dash-text-main);
    }
    .kpi-lbl {
        font-size: 0.74rem;
        font-weight: 700;
        color: var(--dash-text-muted);
        text-transform: uppercase;
    }

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

    .badge-estado {
        font-size: 0.72rem;
        font-weight: 800;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        text-transform: uppercase;
    }
    .est-EN_STOCK { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .est-EN_REACONDICIONAMIENTO { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .est-RESERVADO { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .est-VENDIDO { background: #0a4d36; color: #ffffff; border: 1px solid #073827; }
    .est-BAJA { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }

    .chip-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        margin-top: 0.35rem;
    }
    .quick-chip {
        font-size: 0.73rem;
        font-weight: 700;
        padding: 0.18rem 0.55rem;
        border-radius: 999px;
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        cursor: pointer;
    }
    .quick-chip:hover, .quick-chip.active {
        background: var(--dash-primary-light);
        border-color: var(--dash-primary);
        color: var(--dash-primary);
    }

    .cliente-picker { position: relative; }
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
        max-height: 260px;
        overflow-y: auto;
    }
    .cliente-picker-item {
        padding: 0.5rem 0.85rem;
        border-bottom: 1px solid var(--dash-border);
        cursor: pointer;
    }
    .cliente-picker-item:hover { background: var(--dash-primary-light); }

    .photo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(135px, 1fr));
        gap: 0.7rem;
    }
    .photo-card {
        border: 1px solid var(--dash-border);
        border-radius: 9px;
        overflow: hidden;
        background: var(--dash-panel-bg);
        position: relative;
    }
    .photo-card img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        cursor: pointer;
        display: block;
    }
    .photo-del-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        border: none;
        background: rgba(225, 29, 72, 0.9);
        color: #fff;
        font-size: 0.7rem;
        cursor: pointer;
    }
</style>
@endsection

@section('main')
<div id="app" v-cloak class="pt-2 pb-4">

    <!-- Banner si el módulo está deshabilitado (Solo visible para Superadministrador) -->
    <div v-if="!moduloActivo" class="alert alert-danger d-flex align-items-center justify-content-between shadow-sm mb-3" style="border-radius: 12px;">
        <div>
            <i class="fas fa-power-off fa-lg mr-2"></i>
            <strong>Módulo Deshabilitado:</strong> Estás visualizando esta pantalla como <strong>Superadministrador</strong>. Los demás roles no pueden acceder hasta que lo habilites.
        </div>
        <button type="button" class="btn btn-sm btn-light font-weight-bold text-danger" @click="toggleModuloSuperusuario">
            <i class="fas fa-check-circle mr-1"></i> Habilitar Módulo Ahora
        </button>
    </div>

    <!-- TOPBAR -->
    <div class="view-topbar">
        <div class="view-title-box">
            <h3 class="font-cairo">
                <i class="fas fa-exchange-alt text-primary"></i>
                Equipos por IMEI y Plan Canje (Trade-In)
                @if (!empty($esSuperAdministrador))
                    <span class="badge ml-2" :class="moduloActivo ? 'badge-success' : 'badge-danger'"
                          style="font-size: 0.7rem; cursor: pointer;"
                          title="Control Superadministrador: Clic para habilitar/deshabilitar el módulo"
                          @click="toggleModuloSuperusuario">
                        <i class="fas fa-crown mr-1"></i>@{{ moduloActivo ? 'Módulo Activo' : 'Módulo Inactivo' }}
                    </span>
                @endif
            </h3>
            <p>Control de stock de celulares por IMEI, recepción de usados en Plan Canje, reacondicionamiento en Taller, contratos y garantías.</p>
        </div>

        <div class="d-flex align-items-center flex-wrap" style="gap: 0.5rem;">
            <div class="nav-pills-custom">
                <button type="button" class="nav-pill-btn" :class="{ active: vistaActiva === 'stock' }" @click="cambiarVista('stock')">
                    <i class="fas fa-mobile-alt"></i>
                    <span>Vitrina / Stock IMEI</span>
                </button>
                <button type="button" class="nav-pill-btn" :class="{ active: vistaActiva === 'canje' }" @click="cambiarVista('canje')" v-if="permisos.compra_canje">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Plan Canje / Recibir Usado</span>
                </button>
                <button type="button" class="nav-pill-btn" :class="{ active: vistaActiva === 'ingreso' }" @click="cambiarVista('ingreso')" v-if="permisos.add">
                    <i class="fas fa-plus-circle"></i>
                    <span>Ingresar Equipo Nuevo</span>
                </button>
                <button type="button" class="nav-pill-btn" :class="{ active: vistaActiva === 'historial_canjes' }" @click="cambiarVista('historial_canjes')">
                    <i class="fas fa-file-contract"></i>
                    <span>Contratos de Canje</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- VISTA 1: VITRINA E INVENTARIO DE EQUIPOS POR IMEI            -->
    <!-- ============================================================ -->
    <div v-show="vistaActiva === 'stock'">

        <!-- KPIs -->
        <div class="kpi-grid">
            <div class="kpi-box" :class="{ active: filtros.estado === 'EN_STOCK' }" @click="filtrarStockPorEstado('EN_STOCK')">
                <div class="kpi-icon" style="background: #dcfce7; color: #15803d;">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo">@{{ kpis.en_stock }}</div>
                    <div class="kpi-lbl">En Vitrina / Stock</div>
                </div>
            </div>

            <div class="kpi-box" :class="{ active: filtros.estado === 'EN_REACONDICIONAMIENTO' }" @click="filtrarStockPorEstado('EN_REACONDICIONAMIENTO')">
                <div class="kpi-icon" style="background: #fef3c7; color: #d97706;">
                    <i class="fas fa-tools"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo">@{{ kpis.en_taller }}</div>
                    <div class="kpi-lbl">En Taller (Reacond.)</div>
                </div>
            </div>

            <div class="kpi-box" :class="{ active: filtros.estado === 'VENDIDO' }" @click="filtrarStockPorEstado('VENDIDO')">
                <div class="kpi-icon" style="background: #eaf3ef; color: #0a4d36;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo">@{{ kpis.vendidos }}</div>
                    <div class="kpi-lbl">Vendidos c/ Garantía</div>
                </div>
            </div>

            <div class="kpi-box" @click="cambiarVista('historial_canjes')">
                <div class="kpi-icon" style="background: #e0f2fe; color: #0284c7;">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo">@{{ kpis.recibidos_canje }}</div>
                    <div class="kpi-lbl">Tomados en Canje</div>
                </div>
            </div>

            <div class="kpi-box" @click="filtrarStockPorEstado('EN_STOCK')">
                <div class="kpi-icon" style="background: #f3e8ff; color: #7e22ce;">
                    <i class="fas fa-tags"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo" style="font-size: 1rem;">Gs. @{{ formatNumber(kpis.valor_venta_stock) }}</div>
                    <div class="kpi-lbl">Valor Vitrina</div>
                </div>
            </div>

            <div class="kpi-box" v-if="permisos.ver_costo" @click="filtrarStockPorEstado('TODOS')">
                <div class="kpi-icon" style="background: #ffe4e6; color: #e11d48;">
                    <i class="fas fa-coins"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo" style="font-size: 1rem;">Gs. @{{ formatNumber(kpis.costo_invertido_stock) }}</div>
                    <div class="kpi-lbl">Capital Invertido</div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card-pos">
            <div class="card-pos-body py-3">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-2">
                        <label class="small font-weight-bold text-muted mb-1">Buscar por IMEI, Marca, Modelo, GB o Cliente</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                            </div>
                            <input type="text" class="form-control" v-model="filtros.q"
                                   @keyup.enter="cargarEquipos(1)"
                                   placeholder="Escanear o escribir IMEI, iPhone 13, 128 GB, código EQ-...">
                        </div>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label class="small font-weight-bold text-muted mb-1">Estado del Equipo</label>
                        <select class="form-control form-control-sm" v-model="filtros.estado" @change="cargarEquipos(1)">
                            <option value="TODOS">Todos los estados</option>
                            <option value="EN_STOCK">En Vitrina / Disponible</option>
                            <option value="EN_REACONDICIONAMIENTO">En Taller (Reacondicionamiento)</option>
                            <option value="RESERVADO">Reservado / Pendiente POS</option>
                            <option value="VENDIDO">Vendido</option>
                            <option value="BAJA">Dado de Baja</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label class="small font-weight-bold text-muted mb-1">Condición</label>
                        <select class="form-control form-control-sm" v-model="filtros.condicion" @change="cargarEquipos(1)">
                            <option value="TODOS">Todas las condiciones</option>
                            <option value="NUEVO">Nuevo / Sellado</option>
                            <option value="SEMINUEVO">Seminuevo</option>
                            <option value="USADO_CANJE">Recibido en Plan Canje</option>
                            <option value="REACONDICIONADO">Reacondicionado por Taller</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-2 d-flex" style="gap: 4px;">
                        <button type="button" class="btn btn-sm btn-success btn-block font-weight-bold" @click="cargarEquipos(1)">
                            <i class="fas fa-search mr-1"></i> Buscar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Equipos por IMEI -->
        <div class="card-pos">
            <div class="card-pos-header">
                <h5 class="card-pos-title font-cairo">
                    <i class="fas fa-mobile-alt text-success"></i> Inventario de Equipos Individualizados por IMEI (@{{ paginacion.total }})
                </h5>
                <div class="d-flex" style="gap: 6px;">
                    <button v-if="permisos.compra_canje" type="button" class="btn btn-xs btn-primary font-weight-bold" @click="cambiarVista('canje')">
                        <i class="fas fa-exchange-alt mr-1"></i> Nuevo Plan Canje
                    </button>
                    <button v-if="permisos.add" type="button" class="btn btn-xs btn-success font-weight-bold" @click="cambiarVista('ingreso')">
                        <i class="fas fa-plus mr-1"></i> Ingresar Equipo
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.84rem;">
                    <thead class="bg-light">
                        <tr>
                            <th>Código</th>
                            <th>Equipo / Capacidad</th>
                            <th>IMEI 1 / IMEI 2</th>
                            <th class="text-center">Condición / Batería</th>
                            <th>Origen / Comprador</th>
                            <th class="text-right" v-if="permisos.ver_costo">Costo Total</th>
                            <th class="text-right">Precio Venta</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center" style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="cargandoEquipos">
                            <td :colspan="permisos.ver_costo ? 9 : 8" class="text-center py-4 text-muted">
                                <i class="fas fa-spinner fa-spin mr-1 text-success"></i> Cargando inventario por IMEI...
                            </td>
                        </tr>
                        <tr v-else-if="!equipos.length">
                            <td :colspan="permisos.ver_costo ? 9 : 8" class="text-center py-4 text-muted">
                                No se encontraron celulares registrados con esos filtros.
                            </td>
                        </tr>
                        <tr v-else v-for="eq in equipos" :key="eq.id" style="cursor: pointer;" @click="abrirModalEquipo(eq.id)">
                            <td class="font-weight-bold font-cairo text-primary">
                                @{{ eq.codigo_interno }}
                                <div class="small text-muted">@{{ formatearFecha(eq.fecha_ingreso) }}</div>
                            </td>
                            <td>
                                <div class="font-weight-bold">@{{ eq.marca }} @{{ eq.modelo }}</div>
                                <small class="text-muted">
                                    <span v-if="eq.capacidad_gb" class="badge badge-light border mr-1">@{{ eq.capacidad_gb }}</span>
                                    <span v-if="eq.color">@{{ eq.color }}</span>
                                </small>
                            </td>
                            <td class="font-monospace">
                                <div class="font-weight-bold">@{{ eq.imei_1 }}</div>
                                <small class="text-muted" v-if="eq.imei_2">2: @{{ eq.imei_2 }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-light border font-weight-bold">@{{ eq.condicion_ingreso }} (@{{ eq.grado_estetico }})</span>
                                <div class="small mt-1">
                                    <i class="fas fa-battery-three-quarters" :class="eq.bateria_salud >= 85 ? 'text-success' : 'text-warning'"></i>
                                    <strong>@{{ eq.bateria_salud }}%</strong>
                                </div>
                            </td>
                            <td>
                                <div v-if="eq.estado === 'VENDIDO' && eq.cliente_comprador" class="small">
                                    <span class="badge badge-success mr-1">Comprador</span>
                                    <strong>@{{ eq.cliente_comprador.cliente_nombre }}</strong>
                                    <div class="text-muted" v-if="eq.fecha_vencimiento_garantia">
                                        Garantía hasta: @{{ formatearSoloFecha(eq.fecha_vencimiento_garantia) }}
                                    </div>
                                </div>
                                <div v-else-if="eq.cliente_origen" class="small">
                                    <span class="badge badge-info mr-1">Canje</span>
                                    @{{ eq.cliente_origen.cliente_nombre }}
                                </div>
                                <div v-else-if="eq.proveedor" class="small">
                                    <span class="badge badge-secondary mr-1">Prov.</span>
                                    @{{ eq.proveedor.proveedor_nombre }}
                                </div>
                                <span v-else class="small text-muted">@{{ eq.origen_tipo }}</span>
                            </td>
                            <td class="text-right font-cairo" v-if="permisos.ver_costo">
                                <div class="font-weight-bold">Gs. @{{ formatNumber(eq.costo_total) }}</div>
                                <small class="text-warning" v-if="eq.costo_reacondicionamiento > 0" title="Incluye costo de reacondicionamiento en taller">
                                    +Taller: Gs. @{{ formatNumber(eq.costo_reacondicionamiento) }}
                                </small>
                            </td>
                            <td class="text-right font-cairo">
                                <div class="font-weight-bold text-success">Gs. @{{ formatNumber(eq.estado === 'VENDIDO' ? (eq.precio_vendido || eq.precio_venta_contado) : eq.precio_venta_contado) }}</div>
                                <small class="text-primary" v-if="permisos.ver_costo && eq.precio_venta_contado > eq.costo_total">
                                    Margen: Gs. @{{ formatNumber((eq.estado === 'VENDIDO' ? (eq.precio_vendido || eq.precio_venta_contado) : eq.precio_venta_contado) - eq.costo_total) }}
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge-estado" :class="'est-' + eq.estado">@{{ eq.estado }}</span>
                                <div v-if="eq.reparacion_actual" class="small mt-1">
                                    <a :href="'{{ url('reparacion') }}'" class="badge badge-warning text-dark">
                                        <i class="fas fa-tools mr-1"></i>@{{ eq.reparacion_actual.codigo_orden }}
                                    </a>
                                </div>
                            </td>
                            <td class="text-center" @click.stop>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary" @click="abrirModalEquipo(eq.id)" title="Ficha completa, Taller y Venta">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-dark" @click="imprimirStickerVitrina(eq.id)" title="Imprimir Etiqueta / Sticker de Vitrina">
                                        <i class="fas fa-tag"></i>
                                    </button>
                                    <button v-if="eq.estado === 'EN_STOCK'" type="button" class="btn btn-outline-success" @click="enviarEquipoAPos(eq)" title="Facturar en Punto de Venta (POS)">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                    <button v-if="eq.estado === 'VENDIDO'" type="button" class="btn btn-outline-success" @click="imprimirCertificadoGarantia(eq.id)" title="Imprimir Certificado de Garantía por IMEI">
                                        <i class="fas fa-award"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- VISTA 2: PLAN CANJE (TRADE-IN) Y COMPRA DE CELULAR USADO     -->
    <!-- ============================================================ -->
    <div v-show="vistaActiva === 'canje'">
        <div class="row">
            <!-- Columna Izquierda: Cliente y Celular Usado que Entrega al Local -->
            <div class="col-lg-7">
                <div class="card-pos">
                    <div class="card-pos-header">
                        <h5 class="card-pos-title font-cairo">
                            <i class="fas fa-mobile-alt text-primary"></i> 1. Equipo Usado que Entrega el Cliente (Ingreso por IMEI)
                        </h5>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn font-weight-bold"
                                    :class="formCanje.tipo_operacion === 'PLAN_CANJE' ? 'btn-primary' : 'btn-outline-primary'"
                                    @click="formCanje.tipo_operacion = 'PLAN_CANJE'">
                                <i class="fas fa-exchange-alt mr-1"></i> Plan Canje (Parte de Pago)
                            </button>
                            <button type="button" class="btn font-weight-bold"
                                    :class="formCanje.tipo_operacion === 'COMPRA_DIRECTA' ? 'btn-primary' : 'btn-outline-primary'"
                                    @click="formCanje.tipo_operacion = 'COMPRA_DIRECTA'">
                                <i class="fas fa-hand-holding-usd mr-1"></i> Compra Directa de Usado
                            </button>
                        </div>
                    </div>

                    <div class="card-pos-body">
                        <!-- Selector de Cliente Titular -->
                        <div class="form-group mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="small font-weight-bold text-muted mb-0">Cliente Titular que Entrega el Equipo (Firmante del Contrato) <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-xs btn-outline-success font-weight-bold" @click="abrirModalNuevoCliente">
                                    <i class="fas fa-user-plus mr-1"></i> Crear Cliente Rápido
                                </button>
                            </div>
                            <div class="cliente-picker">
                                <input type="text" class="form-control"
                                       v-model="clientePicker.busqueda"
                                       @focus="abrirBuscadorCliente"
                                       @input="onBuscarClienteInput"
                                       placeholder="Buscar cliente por Nombre, Cédula de Identidad o RUC...">
                                <div v-if="clienteSeleccionado" class="mt-2 p-2 rounded border d-flex justify-content-between align-items-center" style="background: var(--dash-primary-light);">
                                    <div>
                                        <strong class="text-success"><i class="fas fa-user-check mr-1"></i>@{{ clienteSeleccionado.cliente_nombre }}</strong>
                                        <span class="small text-muted ml-2">CI/RUC: @{{ clienteSeleccionado.cliente_ci || clienteSeleccionado.cliente_ruc || '-' }}</span>
                                        <span class="small text-muted ml-2" v-if="clienteSeleccionado.cliente_cel">Cel: @{{ clienteSeleccionado.cliente_cel }}</span>
                                    </div>
                                    <button type="button" class="btn btn-xs btn-outline-danger" @click="quitarCliente"><i class="fas fa-times"></i></button>
                                </div>
                                <div class="cliente-picker-dropdown" v-show="clientePicker.abierto">
                                    <div class="cliente-picker-item" v-for="c in clientePicker.resultados" :key="c.CLIENTES_cod || c.clientes_cod" @click="seleccionarCliente(c)">
                                        <div class="font-weight-bold">@{{ c.cliente_nombre }}</div>
                                        <small class="text-muted">CI/RUC: @{{ c.cliente_ci || c.cliente_ruc || '-' }} · Cel: @{{ c.cliente_cel || '-' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Datos del Celular Usado Recibido -->
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label class="small font-weight-bold text-muted mb-1">Marca <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" v-model="formCanje.usado.marca" placeholder="Apple iPhone, Samsung...">
                                <div class="chip-list">
                                    <span class="quick-chip" v-for="m in marcasRapidas" :key="m" @click="formCanje.usado.marca = m">@{{ m }}</span>
                                </div>
                            </div>
                            <div class="col-md-5 mb-2">
                                <label class="small font-weight-bold text-muted mb-1">Modelo Exacto <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" v-model="formCanje.usado.modelo" placeholder="Ej: iPhone 12 Pro, Galaxy S22...">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="small font-weight-bold text-muted mb-1">Capacidad (GB)</label>
                                <input type="text" class="form-control" v-model="formCanje.usado.capacidad_gb" placeholder="128 GB">
                                <div class="chip-list">
                                    <span class="quick-chip" v-for="gb in capacidadesRapidas" :key="gb" @click="formCanje.usado.capacidad_gb = gb">@{{ gb }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-5 mb-2">
                                <label class="small font-weight-bold text-muted mb-1">IMEI Principal (1) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control font-monospace font-weight-bold" v-model="formCanje.usado.imei_1" placeholder="Marcá *#06# para verificar IMEI">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="small font-weight-bold text-muted mb-1">Color</label>
                                <input type="text" class="form-control" v-model="formCanje.usado.color" placeholder="Ej: Grafito, Azul, Sierra Blue">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="small font-weight-bold text-muted mb-1">Salud Batería (%)</label>
                                <input type="number" class="form-control font-cairo font-weight-bold" v-model.number="formCanje.usado.bateria_salud" min="1" max="100">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-4 mb-2">
                                <label class="small font-weight-bold text-muted mb-1">Grado Estético</label>
                                <select class="form-control" v-model="formCanje.usado.grado_estetico">
                                    <option value="A+">Grado A+ (Impecable)</option>
                                    <option value="A">Grado A (Detalles mínimos)</option>
                                    <option value="B">Grado B (Desgaste normal)</option>
                                    <option value="C">Grado C (A reacondicionar)</option>
                                </select>
                            </div>
                            <div class="col-md-8 mb-2">
                                <label class="small font-weight-bold text-muted mb-1">Observaciones / Detalles del Usado</label>
                                <input type="text" class="form-control" v-model="formCanje.usado.observaciones_equipo" placeholder="Ej: Sin cuentas iCloud/Google, pantalla original...">
                            </div>
                        </div>

                        <!-- Conexión directa con Servicio Técnico para Reacondicionamiento -->
                        <div class="p-3 rounded border mt-2 mb-3" style="background: #fffbeb; border-color: #fde68a !important;">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="swEnviarTallerCanje" v-model="formCanje.enviar_a_taller">
                                <label class="custom-control-label font-weight-bold text-dark" for="swEnviarTallerCanje">
                                    <i class="fas fa-tools text-warning mr-1"></i> Enviar este equipo recibido directamente al Taller de Servicio Técnico para Reacondicionamiento
                                </label>
                            </div>
                            <div v-if="formCanje.enviar_a_taller" class="mt-2 pt-2 border-top">
                                <label class="small font-weight-bold text-muted mb-1">Trabajo de Reacondicionamiento a Realizar en Taller</label>
                                <input type="text" class="form-control form-control-sm" v-model="formCanje.motivo_taller"
                                       placeholder="Ej: Cambio de batería nueva, limpieza general y cambio de vidrio templado...">
                            </div>
                        </div>

                        <!-- Fotos del Equipo y de la Cédula de Identidad -->
                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap" style="gap: 6px;">
                                <span class="small font-weight-bold text-muted text-uppercase">
                                    <i class="fas fa-id-card mr-1 text-primary"></i> Fotos del Equipo Recibido y C.I. del Titular
                                </span>
                                <div class="d-flex align-items-center" style="gap: 6px;">
                                    <select class="form-control form-control-sm" style="width: 145px;" v-model="tipoFotoCanje">
                                        <option value="EQUIPO">Foto Equipo</option>
                                        <option value="CI_FRENTE">C.I. Frente</option>
                                        <option value="CI_DORSO">C.I. Dorso</option>
                                        <option value="IMEI_CAPTURA">Pantalla IMEI</option>
                                    </select>
                                    <label class="btn btn-xs btn-primary mb-0 font-weight-bold" style="cursor: pointer;">
                                        <i class="fas fa-upload mr-1"></i> Subir
                                        <input type="file" accept="image/*" class="d-none" @change="onFileCanjeChange">
                                    </label>
                                    <button type="button" class="btn btn-xs btn-info font-weight-bold" @click="abrirWebcam('CANJE')">
                                        <i class="fas fa-camera mr-1"></i> Cámara Web
                                    </button>
                                </div>
                            </div>

                            <div class="photo-grid" v-if="formCanje.imagenes_canje.length">
                                <div class="photo-card" v-for="(img, idx) in formCanje.imagenes_canje" :key="idx">
                                    <img :src="img.url">
                                    <button type="button" class="photo-del-btn" @click="formCanje.imagenes_canje.splice(idx, 1)">&times;</button>
                                    <div class="p-1 text-center small font-weight-bold">@{{ img.tipo }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Equipo que se Lleva y Liquidación de Diferencia -->
            <div class="col-lg-5">
                <div class="card-pos">
                    <div class="card-pos-header">
                        <h5 class="card-pos-title font-cairo">
                            <i class="fas fa-calculator text-success"></i> 2. Tasación, Equipo a Entregar y Contrato
                        </h5>
                    </div>
                    <div class="card-pos-body">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="small font-weight-bold text-primary mb-1">Valor Reconocido por el Usado (Gs.)</label>
                                <input type="number" class="form-control font-cairo font-weight-bold border-primary"
                                       v-model.number="formCanje.valor_tasacion_usado" min="0" step="10000">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="small font-weight-bold text-muted mb-1">Precio Venta Futuro del Usado</label>
                                <input type="number" class="form-control font-cairo"
                                       v-model.number="formCanje.usado.precio_venta_sugerido" min="0" step="10000">
                            </div>
                        </div>

                        <!-- Selector de Equipo que el Cliente se lleva (Solo en PLAN_CANJE) -->
                        <div v-if="formCanje.tipo_operacion === 'PLAN_CANJE'" class="mb-3 p-3 rounded border" style="background: var(--dash-panel-bg);">
                            <label class="small font-weight-bold text-success mb-1">
                                <i class="fas fa-mobile-alt mr-1"></i> Seleccionar Equipo en Stock que el Cliente se Lleva <span class="text-danger">*</span>
                            </label>
                            <select class="form-control mb-2 font-weight-bold" v-model="formCanje.celular_llevado_id" @change="onSeleccionarEquipoLlevado">
                                <option :value="null">-- Elegir Celular Disponible en Vitrina --</option>
                                <option v-for="eq in stockDisponibleCanje" :key="eq.id" :value="eq.id">
                                    [@{{ eq.codigo_interno }}] @{{ eq.marca }} @{{ eq.modelo }} @{{ eq.capacidad_gb }} (IMEI: @{{ eq.imei_1 }}) — Gs. @{{ formatNumber(eq.precio_venta_contado) }}
                                </option>
                            </select>

                            <div class="row" v-if="formCanje.celular_llevado_id">
                                <div class="col-6">
                                    <label class="small font-weight-bold text-muted mb-1">Precio del Equipo Llevado</label>
                                    <input type="number" class="form-control form-control-sm font-cairo font-weight-bold"
                                           v-model.number="formCanje.precio_equipo_llevado" min="0">
                                </div>
                                <div class="col-6">
                                    <label class="small font-weight-bold text-muted mb-1">Descuento Adicional</label>
                                    <input type="number" class="form-control form-control-sm font-cairo"
                                           v-model.number="formCanje.descuento_adicional" min="0">
                                </div>
                            </div>
                        </div>

                        <!-- Resumen Financiero del Canje -->
                        <div class="p-3 rounded border mb-3 font-cairo" style="background: var(--dash-primary-light);">
                            <div v-if="formCanje.tipo_operacion === 'PLAN_CANJE'">
                                <div class="d-flex justify-content-between py-1">
                                    <span>Precio Equipo que se Lleva:</span>
                                    <strong>Gs. @{{ formatNumber(formCanje.precio_equipo_llevado) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between py-1 text-primary">
                                    <span>(-) Valor Tasado por su Usado:</span>
                                    <strong>- Gs. @{{ formatNumber(formCanje.valor_tasacion_usado) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between py-1 text-danger" v-if="formCanje.descuento_adicional > 0">
                                    <span>(-) Descuento Adicional:</span>
                                    <strong>- Gs. @{{ formatNumber(formCanje.descuento_adicional) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between pt-2 mt-1 border-top h5 mb-0 font-weight-bold text-success">
                                    <span>DIFERENCIA A COBRAR:</span>
                                    <span>Gs. @{{ formatNumber(diferenciaCanjeCalculada) }}</span>
                                </div>
                            </div>
                            <div v-else>
                                <div class="d-flex justify-content-between h5 mb-0 font-weight-bold text-primary">
                                    <span>MONTO A PAGAR AL CLIENTE:</span>
                                    <span>Gs. @{{ formatNumber(formCanje.valor_tasacion_usado) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="chkCajaCanje" v-model="formCanje.registrar_caja">
                            <label class="custom-control-label small font-weight-bold" for="chkCajaCanje">
                                Registrar movimiento en Caja Abierta (@{{ formCanje.tipo_operacion === 'PLAN_CANJE' ? 'Entrada de la diferencia' : 'Salida por compra de usado' }})
                            </label>
                        </div>

                        <button type="button" class="btn btn-success btn-block font-cairo font-weight-bold py-2 mb-2"
                                :disabled="guardandoCanje"
                                @click="procesarPlanCanje('DIRECTO')">
                            <i class="fas mr-1" :class="guardandoCanje ? 'fa-spinner fa-spin' : 'fa-file-contract'"></i>
                            Confirmar Operación e Imprimir Contrato Legal
                        </button>

                        <button v-if="formCanje.tipo_operacion === 'PLAN_CANJE'"
                                type="button" class="btn btn-warning btn-block font-cairo font-weight-bold text-dark"
                                :disabled="guardandoCanje"
                                @click="procesarPlanCanje('POS')">
                            <i class="fas fa-shopping-cart mr-1"></i>
                            Registrar Canje y Enviar Diferencia al Punto de Venta (POS)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- VISTA 3: INGRESO DE EQUIPO NUEVO / SEMINUEVO A STOCK         -->
    <!-- ============================================================ -->
    <div v-show="vistaActiva === 'ingreso'">
        <div class="card-pos">
            <div class="card-pos-header">
                <h5 class="card-pos-title font-cairo">
                    <i class="fas fa-plus-circle text-success"></i> Alta de Equipo en Inventario por IMEI
                </h5>
            </div>
            <div class="card-pos-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-muted mb-1">Marca <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" v-model="formIngreso.marca" placeholder="Apple iPhone, Samsung...">
                        <div class="chip-list">
                            <span class="quick-chip" v-for="m in marcasRapidas" :key="m" @click="formIngreso.marca = m">@{{ m }}</span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-muted mb-1">Modelo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" v-model="formIngreso.modelo" placeholder="Ej: iPhone 15 Pro Max, Galaxy S24...">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="small font-weight-bold text-muted mb-1">Capacidad</label>
                        <input type="text" class="form-control" v-model="formIngreso.capacidad_gb" placeholder="256 GB">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-muted mb-1">Color</label>
                        <input type="text" class="form-control" v-model="formIngreso.color" placeholder="Natural Titanium, Negro...">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-muted mb-1">IMEI Principal (1) / Serie <span class="text-danger">*</span></label>
                        <input type="text" class="form-control font-monospace font-weight-bold" v-model="formIngreso.imei_1" placeholder="Escanear o escribir IMEI 1">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-muted mb-1">IMEI Secundario (2)</label>
                        <input type="text" class="form-control font-monospace" v-model="formIngreso.imei_2" placeholder="Opcional">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-muted mb-1">Condición de Ingreso</label>
                        <select class="form-control" v-model="formIngreso.condicion_ingreso">
                            <option value="NUEVO">Nuevo / Sellado</option>
                            <option value="SEMINUEVO">Seminuevo</option>
                            <option value="REACONDICIONADO">Reacondicionado</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="small font-weight-bold text-muted mb-1">Salud Batería (%)</label>
                        <input type="number" class="form-control font-cairo font-weight-bold" v-model.number="formIngreso.bateria_salud" min="1" max="100">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3" v-if="permisos.ver_costo">
                        <label class="small font-weight-bold text-muted mb-1">Costo de Compra (Gs.)</label>
                        <input type="number" class="form-control font-cairo" v-model.number="formIngreso.costo_adquisicion" min="0" step="10000">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-success mb-1">Precio Venta Contado (Gs.) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control font-cairo font-weight-bold border-success" v-model.number="formIngreso.precio_venta_contado" min="0" step="10000">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-muted mb-1">Precio Lista / Crédito (Gs.)</label>
                        <input type="number" class="form-control font-cairo" v-model.number="formIngreso.precio_venta_credito" min="0" step="10000">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-muted mb-1">Garantía al Vender (Días)</label>
                        <input type="number" class="form-control font-cairo" v-model.number="formIngreso.dias_garantia" min="0" max="730">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-muted mb-1">Proveedor</label>
                        <select class="form-control" v-model="formIngreso.PROVEEDOR_cod">
                            <option :value="null">-- Sin proveedor específico --</option>
                            <option v-for="p in proveedores" :key="p.PROVEEDOR_cod || p.proveedor_cod" :value="p.PROVEEDOR_cod || p.proveedor_cod">@{{ p.proveedor_nombre }}</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-muted mb-1">Accesorios Incluidos</label>
                        <input type="text" class="form-control" v-model="formIngreso.accesorios_incluidos" placeholder="Ej: Caja sellada, cable original...">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="small font-weight-bold text-muted mb-1">Observaciones</label>
                        <input type="text" class="form-control" v-model="formIngreso.observaciones_equipo" placeholder="Notas adicionales...">
                    </div>
                </div>

                <div class="text-right mt-2">
                    <button type="button" class="btn btn-success font-cairo font-weight-bold px-4"
                            :disabled="guardandoIngreso"
                            @click="guardarEquipoNuevo(true)">
                        <i class="fas fa-tag mr-1"></i> Guardar e Imprimir Sticker de Vitrina
                    </button>
                    <button type="button" class="btn btn-outline-primary font-cairo font-weight-bold ml-2"
                            :disabled="guardandoIngreso"
                            @click="guardarEquipoNuevo(false)">
                        <i class="fas fa-save mr-1"></i> Solo Guardar en Stock
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- VISTA 4: HISTORIAL DE CONTRATOS DE PLAN CANJE                -->
    <!-- ============================================================ -->
    <div v-show="vistaActiva === 'historial_canjes'">
        <div class="card-pos">
            <div class="card-pos-header">
                <h5 class="card-pos-title font-cairo">
                    <i class="fas fa-file-contract text-primary"></i> Historial de Operaciones y Contratos de Plan Canje
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.84rem;">
                    <thead class="bg-light">
                        <tr>
                            <th>Contrato</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Cliente Cedente</th>
                            <th>Equipo Usado Recibido (IMEI)</th>
                            <th class="text-right">Tasación Usado</th>
                            <th>Equipo Llevado</th>
                            <th class="text-right">Diferencia</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="!canjes.length">
                            <td colspan="9" class="text-center py-4 text-muted">No hay contratos de Plan Canje registrados aún.</td>
                        </tr>
                        <tr v-else v-for="cj in canjes" :key="cj.id">
                            <td class="font-weight-bold font-cairo text-primary">@{{ cj.codigo_canje }}</td>
                            <td>@{{ formatearFecha(cj.fecha_operacion) }}</td>
                            <td>
                                <span class="badge" :class="cj.tipo_operacion === 'PLAN_CANJE' ? 'badge-primary' : 'badge-info'">
                                    @{{ cj.tipo_operacion }}
                                </span>
                            </td>
                            <td>
                                <div class="font-weight-bold">@{{ cj.cliente ? cj.cliente.cliente_nombre : '-' }}</div>
                                <small class="text-muted">CI: @{{ cj.cliente ? (cj.cliente.cliente_ci || cj.cliente.cliente_ruc) : '-' }}</small>
                            </td>
                            <td>
                                <div class="font-weight-bold" v-if="cj.celular_entregado">
                                    @{{ cj.celular_entregado.marca }} @{{ cj.celular_entregado.modelo }} @{{ cj.celular_entregado.capacidad_gb }}
                                </div>
                                <small class="font-monospace text-muted" v-if="cj.celular_entregado">IMEI: @{{ cj.celular_entregado.imei_1 }}</small>
                            </td>
                            <td class="text-right font-cairo font-weight-bold text-primary">
                                Gs. @{{ formatNumber(cj.valor_tasacion_usado) }}
                            </td>
                            <td>
                                <div v-if="cj.celular_llevado">
                                    <strong>@{{ cj.celular_llevado.marca }} @{{ cj.celular_llevado.modelo }}</strong>
                                    <small class="d-block font-monospace text-muted">IMEI: @{{ cj.celular_llevado.imei_1 }}</small>
                                </div>
                                <span v-else class="text-muted small">Compra Directa</span>
                            </td>
                            <td class="text-right font-cairo font-weight-bold text-success">
                                Gs. @{{ formatNumber(cj.diferencia_a_pagar) }}
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-xs btn-outline-primary font-weight-bold" @click="imprimirContratoCanje(cj.id)" title="Imprimir Contrato Legal con Declaración Jurada">
                                    <i class="fas fa-print mr-1"></i> Contrato
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- MODAL DETALLE DE EQUIPO POR IMEI, TALLER Y VENTA             -->
    <!-- ============================================================ -->
    <div class="modal fade" id="modalDetalleImei" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content" v-if="equipoActivo">
                <div class="modal-header py-2 px-3 d-flex justify-content-between align-items-center" style="background: var(--dash-primary); color: #fff;">
                    <div>
                        <h5 class="modal-title font-cairo font-weight-bold mb-0">
                            <i class="fas fa-mobile-alt mr-1"></i> @{{ equipoActivo.codigo_interno }} — @{{ equipoActivo.marca }} @{{ equipoActivo.modelo }} @{{ equipoActivo.capacidad_gb }}
                            <span class="badge-estado ml-2" :class="'est-' + equipoActivo.estado">@{{ equipoActivo.estado }}</span>
                        </h5>
                        <small class="font-monospace">IMEI: @{{ equipoActivo.imei_1 }}</small>
                    </div>
                    <div>
                        <button type="button" class="btn btn-xs btn-light font-weight-bold mr-1" @click="imprimirStickerVitrina(equipoActivo.id)">
                            <i class="fas fa-tag mr-1"></i> Sticker Vitrina
                        </button>
                        <button v-if="equipoActivo.estado === 'VENDIDO'" type="button" class="btn btn-xs btn-warning font-weight-bold mr-2" @click="imprimirCertificadoGarantia(equipoActivo.id)">
                            <i class="fas fa-award mr-1"></i> Certificado Garantía
                        </button>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                </div>

                <div class="modal-body p-3">
                    <div class="row">
                        <!-- Columna Izquierda: Ficha Técnica y Conexión con Taller -->
                        <div class="col-md-7">
                            <div class="card-pos">
                                <div class="card-pos-header">
                                    <h6 class="card-pos-title font-cairo"><i class="fas fa-sliders-h text-primary"></i> Especificaciones y Costos del IMEI</h6>
                                </div>
                                <div class="card-pos-body">
                                    <div class="row">
                                        <div class="col-4 mb-2">
                                            <label class="small font-weight-bold text-muted mb-1">Marca</label>
                                            <input type="text" class="form-control form-control-sm" v-model="equipoActivo.marca">
                                        </div>
                                        <div class="col-5 mb-2">
                                            <label class="small font-weight-bold text-muted mb-1">Modelo</label>
                                            <input type="text" class="form-control form-control-sm" v-model="equipoActivo.modelo">
                                        </div>
                                        <div class="col-3 mb-2">
                                            <label class="small font-weight-bold text-muted mb-1">Capacidad</label>
                                            <input type="text" class="form-control form-control-sm" v-model="equipoActivo.capacidad_gb">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-5 mb-2">
                                            <label class="small font-weight-bold text-muted mb-1">IMEI 1</label>
                                            <input type="text" class="form-control form-control-sm font-monospace" v-model="equipoActivo.imei_1">
                                        </div>
                                        <div class="col-4 mb-2">
                                            <label class="small font-weight-bold text-muted mb-1">Color</label>
                                            <input type="text" class="form-control form-control-sm" v-model="equipoActivo.color">
                                        </div>
                                        <div class="col-3 mb-2">
                                            <label class="small font-weight-bold text-muted mb-1">Batería (%)</label>
                                            <input type="number" class="form-control form-control-sm font-cairo" v-model.number="equipoActivo.bateria_salud">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4 mb-2" v-if="permisos.ver_costo">
                                            <label class="small font-weight-bold text-muted mb-1">Costo Adquisición</label>
                                            <input type="number" class="form-control form-control-sm font-cairo" v-model.number="equipoActivo.costo_adquisicion">
                                        </div>
                                        <div class="col-4 mb-2">
                                            <label class="small font-weight-bold text-success mb-1">Precio Venta Contado</label>
                                            <input type="number" class="form-control form-control-sm font-cairo font-weight-bold" v-model.number="equipoActivo.precio_venta_contado">
                                        </div>
                                        <div class="col-4 mb-2">
                                            <label class="small font-weight-bold text-muted mb-1">Días de Garantía</label>
                                            <input type="number" class="form-control form-control-sm font-cairo" v-model.number="equipoActivo.dias_garantia">
                                        </div>
                                    </div>
                                    <div class="text-right mt-2">
                                        <button type="button" class="btn btn-sm btn-primary font-weight-bold" @click="actualizarDatosEquipo">
                                            <i class="fas fa-save mr-1"></i> Guardar Cambios del Equipo
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Panel de Reacondicionamiento en Servicio Técnico -->
                            <div class="card-pos">
                                <div class="card-pos-header" style="background: #fffbeb;">
                                    <h6 class="card-pos-title font-cairo text-dark">
                                        <i class="fas fa-tools text-warning"></i> Reacondicionamiento en Servicio Técnico
                                    </h6>
                                    <span v-if="permisos.ver_costo" class="badge badge-warning font-cairo">
                                        Costo Reacond. Acumulado: Gs. @{{ formatNumber(equipoActivo.costo_reacondicionamiento) }}
                                    </span>
                                </div>
                                <div class="card-pos-body">
                                    <div v-if="equipoActivo.estado === 'EN_REACONDICIONAMIENTO'">
                                        <div class="alert alert-warning py-2 px-3 mb-2 small">
                                            <strong>En Taller:</strong> Este celular se encuentra actualmente en Servicio Técnico
                                            <span v-if="equipoActivo.reparacion_actual">
                                                (Orden <strong>@{{ equipoActivo.reparacion_actual.codigo_orden }}</strong> — Costo repuestos en taller: <strong>Gs. @{{ formatNumber(equipoActivo.reparacion_actual.costo_repuestos) }}</strong>).
                                            </span>
                                        </div>
                                        <div class="row align-items-end">
                                            <div class="col-6">
                                                <label class="small font-weight-bold text-muted mb-1">Costo de Taller a Sumar al Equipo (Gs.)</label>
                                                <input type="number" class="form-control form-control-sm font-cairo font-weight-bold"
                                                       v-model.number="formTallerModal.costo_adicional">
                                            </div>
                                            <div class="col-6">
                                                <button type="button" class="btn btn-sm btn-success btn-block font-weight-bold" @click="finalizarTallerEquipo">
                                                    <i class="fas fa-check-circle mr-1"></i> Finalizar y Poner en Vitrina
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else-if="equipoActivo.estado === 'EN_STOCK'">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" v-model="formTallerModal.motivo"
                                                   placeholder="Motivo para enviar a taller (ej. Cambio de batería, limpieza)...">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-warning font-weight-bold" @click="enviarEquipoATaller">
                                                    <i class="fas fa-tools mr-1"></i> Enviar a Taller
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Columna Derecha: Venta Directa / POS y Trazabilidad -->
                        <div class="col-md-5">
                            <div class="card-pos" v-if="equipoActivo.estado === 'EN_STOCK' || equipoActivo.estado === 'RESERVADO'">
                                <div class="card-pos-header" style="background: var(--dash-primary-light);">
                                    <h6 class="card-pos-title font-cairo text-success">
                                        <i class="fas fa-shopping-cart"></i> Vender este Equipo por IMEI
                                    </h6>
                                </div>
                                <div class="card-pos-body">
                                    <button type="button" class="btn btn-warning btn-block font-cairo font-weight-bold text-dark mb-2"
                                            @click="enviarEquipoAPos(equipoActivo)">
                                        <i class="fas fa-cash-register mr-1"></i> Transferir al Punto de Venta (POS) para Facturar
                                    </button>
                                    <small class="text-muted d-block text-center">Carga automáticamente el modelo, IMEI y precio en la pantalla de Ventas.</small>
                                </div>
                            </div>

                            <!-- Historial de Trazabilidad del IMEI -->
                            <div class="card-pos mb-0">
                                <div class="card-pos-header">
                                    <h6 class="card-pos-title font-cairo"><i class="fas fa-history text-secondary"></i> Trazabilidad del IMEI</h6>
                                </div>
                                <div class="card-pos-body" style="max-height: 290px; overflow-y: auto;">
                                    <div v-for="h in equipoActivo.historial" :key="h.id" class="p-2 rounded border mb-2 bg-light small">
                                        <div class="d-flex justify-content-between">
                                            <strong>@{{ h.titulo }}</strong>
                                            <span class="text-muted">@{{ formatearFecha(h.created_at) }}</span>
                                        </div>
                                        <div class="text-muted mt-1">@{{ h.detalle }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cámara Web HTML5 -->
    <div class="modal fade" id="modalWebcamImei" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header py-2 bg-dark text-white">
                    <h6 class="modal-title font-cairo mb-0"><i class="fas fa-camera mr-1"></i> Capturar Foto</h6>
                    <button type="button" class="close text-white" data-dismiss="modal" @click="detenerWebcam"><span>&times;</span></button>
                </div>
                <div class="modal-body text-center p-3">
                    <video ref="webcamVideo" autoplay playsinline style="width:100%;max-height:300px;border-radius:8px;background:#000;"></video>
                    <canvas ref="webcamCanvas" class="d-none"></canvas>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal" @click="detenerWebcam">Cancelar</button>
                    <button type="button" class="btn btn-sm btn-success font-weight-bold" @click="capturarFotoWebcam">
                        <i class="fas fa-camera mr-1"></i> Capturar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Cliente Rápido -->
    <div class="modal fade" id="modalNuevoClienteImei" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header py-2" style="background: var(--dash-primary); color: #fff;">
                    <h6 class="modal-title font-cairo mb-0"><i class="fas fa-user-plus mr-1"></i> Nuevo Cliente</h6>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label class="small font-weight-bold">Nombre y Apellido *</label>
                        <input type="text" class="form-control form-control-sm" v-model="nuevoCliente.nombre">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-2">
                            <label class="small font-weight-bold">C.I. / RUC *</label>
                            <input type="text" class="form-control form-control-sm" v-model="nuevoCliente.doc">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="small font-weight-bold">Celular *</label>
                            <input type="text" class="form-control form-control-sm" v-model="nuevoCliente.celular">
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="small font-weight-bold">Dirección</label>
                        <input type="text" class="form-control form-control-sm" v-model="nuevoCliente.direccion">
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-sm btn-success font-weight-bold" @click="guardarNuevoCliente">Guardar</button>
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
            moduloActivo: {{ !empty($imeiConfig['activo']) ? 'true' : 'false' }},
            vistaActiva: 'stock', // stock | canje | ingreso | historial_canjes
            sucursales: @json($sucursales),
            proveedores: @json($proveedores),
            tecnicos: @json($tecnicos),
            permisos: @json($permisosModulo),
            imeiConfig: @json($imeiConfig),
            sucursalActual: {{ (int) ($sucursalActual ?? 1) }},

            marcasRapidas: ['Apple iPhone', 'Samsung', 'Xiaomi', 'Motorola', 'Honor', 'Realme'],
            capacidadesRapidas: ['64 GB', '128 GB', '256 GB', '512 GB', '1 TB'],

            cargandoEquipos: false,
            equipos: [],
            canjes: [],
            stockDisponibleCanje: [],
            paginacion: { current_page: 1, last_page: 1, total: 0 },
            kpis: {
                en_stock: 0,
                en_taller: 0,
                vendidos: 0,
                recibidos_canje: 0,
                valor_venta_stock: 0,
                costo_invertido_stock: 0
            },
            filtros: {
                q: '',
                estado: 'TODOS',
                condicion: 'TODOS',
                sucursal: '0'
            },

            // Cliente Picker
            clienteSeleccionado: null,
            clientePicker: {
                abierto: false,
                busqueda: '',
                resultados: [],
                timer: null
            },
            nuevoCliente: { nombre: '', doc: '', celular: '', direccion: '' },

            // Formulario Plan Canje
            guardandoCanje: false,
            tipoFotoCanje: 'EQUIPO',
            formCanje: {
                tipo_operacion: 'PLAN_CANJE',
                cliente_id: null,
                suc_cod: {{ (int) ($sucursalActual ?? 1) }},
                valor_tasacion_usado: 0,
                celular_llevado_id: null,
                precio_equipo_llevado: 0,
                descuento_adicional: 0,
                registrar_caja: true,
                enviar_a_taller: false,
                motivo_taller: 'Revisión y reacondicionamiento de equipo recibido en Plan Canje.',
                usado: {
                    marca: '',
                    modelo: '',
                    capacidad_gb: '128 GB',
                    color: '',
                    imei_1: '',
                    imei_2: '',
                    grado_estetico: 'A',
                    bateria_salud: 88,
                    libre_operadora: true,
                    icloud_google_limpio: true,
                    observaciones_equipo: '',
                    precio_venta_sugerido: 0
                },
                imagenes_canje: []
            },

            // Formulario Ingreso Nuevo
            guardandoIngreso: false,
            formIngreso: {
                suc_cod: {{ (int) ($sucursalActual ?? 1) }},
                marca: '',
                modelo: '',
                capacidad_gb: '128 GB',
                color: '',
                imei_1: '',
                imei_2: '',
                condicion_ingreso: 'NUEVO',
                bateria_salud: 100,
                costo_adquisicion: 0,
                precio_venta_contado: 0,
                precio_venta_credito: 0,
                dias_garantia: {{ (int) ($imeiConfig['garantia_nuevo_dias'] ?? 180) }},
                PROVEEDOR_cod: null,
                accesorios_incluidos: 'Caja y accesorios originales',
                observaciones_equipo: ''
            },

            // Modal Equipo
            equipoActivo: null,
            formTallerModal: {
                motivo: 'Reacondicionamiento técnico para vitrina.',
                costo_adicional: 0
            },
            webcamStream: null
        },
        computed: {
            diferenciaCanjeCalculada: function () {
                var p = Number(this.formCanje.precio_equipo_llevado) || 0;
                var t = Number(this.formCanje.valor_tasacion_usado) || 0;
                var d = Number(this.formCanje.descuento_adicional) || 0;
                return Math.max(0, p - t - d);
            }
        },
        methods: {
            formatNumber: function (val) {
                var n = Math.round(Number(val) || 0);
                return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
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

            toggleModuloSuperusuario: function () {
                var self = this;
                axios.post('{{ route("imei.toggle_modulo") }}', {
                    activo: !this.moduloActivo ? 1 : 0
                }).then(function (r) {
                    if (r.data && r.data.success) {
                        self.moduloActivo = !!r.data.activo;
                        Swal.fire('Actualizado', r.data.message, 'success');
                    }
                });
            },

            cambiarVista: function (vista) {
                this.vistaActiva = vista;
                if (vista === 'stock') {
                    this.cargarEquipos(1);
                } else if (vista === 'canje') {
                    this.cargarStockDisponibleCanje();
                } else if (vista === 'historial_canjes') {
                    this.cargarHistorialCanjes();
                }
            },
            filtrarStockPorEstado: function (est) {
                this.vistaActiva = 'stock';
                this.filtros.estado = est;
                this.cargarEquipos(1);
            },
            cargarEquipos: function (page) {
                var self = this;
                this.cargandoEquipos = true;
                axios.get('{{ route("imei.index") }}', {
                    params: {
                        lista: 'equipos',
                        page: page || 1,
                        q: this.filtros.q,
                        estado: this.filtros.estado,
                        condicion: this.filtros.condicion,
                        sucursal: this.filtros.sucursal
                    }
                }).then(function (res) {
                    if (res.data) {
                        var eq = res.data.equipos || {};
                        self.equipos = eq.data || [];
                        self.paginacion = {
                            current_page: eq.current_page || 1,
                            last_page: eq.last_page || 1,
                            total: eq.total || 0
                        };
                        if (res.data.kpis) self.kpis = res.data.kpis;
                    }
                }).finally(function () {
                    self.cargandoEquipos = false;
                });
            },
            cargarStockDisponibleCanje: function () {
                var self = this;
                axios.get('{{ route("imei.stock.buscar") }}')
                    .then(function (res) {
                        self.stockDisponibleCanje = Array.isArray(res.data) ? res.data : [];
                    });
            },
            cargarHistorialCanjes: function () {
                var self = this;
                axios.get('{{ route("imei.index") }}', {
                    params: { lista: 'canjes' }
                }).then(function (res) {
                    if (res.data && res.data.canjes) {
                        self.canjes = res.data.canjes.data || [];
                    }
                });
            },

            /* Cliente Picker */
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
                axios.get('{{ url("cliente/buscar") }}', {
                    params: { q: this.clientePicker.busqueda, limit: 20 }
                }).then(function (res) {
                    self.clientePicker.resultados = Array.isArray(res.data) ? res.data : [];
                });
            },
            seleccionarCliente: function (c) {
                this.clienteSeleccionado = c;
                this.formCanje.cliente_id = c.CLIENTES_cod || c.clientes_cod;
                this.clientePicker.busqueda = c.cliente_nombre;
                this.clientePicker.abierto = false;
            },
            quitarCliente: function () {
                this.clienteSeleccionado = null;
                this.formCanje.cliente_id = null;
                this.clientePicker.busqueda = '';
            },
            abrirModalNuevoCliente: function () {
                this.nuevoCliente = { nombre: this.clientePicker.busqueda || '', doc: '', celular: '', direccion: '' };
                this.clientePicker.abierto = false;
                $('#modalNuevoClienteImei').modal('show');
            },
            guardarNuevoCliente: function () {
                if (!this.nuevoCliente.nombre.trim() || !this.nuevoCliente.doc.trim()) {
                    Swal.fire('Atención', 'Completá nombre y documento del cliente.', 'warning');
                    return;
                }
                var self = this;
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
                    $('#modalNuevoClienteImei').modal('hide');
                    if (res.data && res.data.cliente) {
                        self.seleccionarCliente(res.data.cliente);
                    }
                });
            },

            /* Plan Canje */
            onSeleccionarEquipoLlevado: function () {
                var self = this;
                var eq = this.stockDisponibleCanje.find(function (x) { return x.id === self.formCanje.celular_llevado_id; });
                if (eq) {
                    this.formCanje.precio_equipo_llevado = Number(eq.precio_venta_contado) || 0;
                }
            },
            onFileCanjeChange: function (e) {
                var file = e.target.files[0];
                if (!file) return;
                var self = this;
                var fd = new FormData();
                fd.append('imagen', file);
                fd.append('tipo', this.tipoFotoCanje);
                axios.post('{{ route("imei.imagen.subir") }}', fd).then(function (res) {
                    if (res.data && res.data.success) {
                        self.formCanje.imagenes_canje.push(res.data);
                    }
                });
                e.target.value = '';
            },
            abrirWebcam: function () {
                var self = this;
                $('#modalWebcamImei').modal('show');
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' }, audio: false })
                        .then(function (stream) {
                            self.webcamStream = stream;
                            if (self.$refs.webcamVideo) self.$refs.webcamVideo.srcObject = stream;
                        });
                }
            },
            detenerWebcam: function () {
                if (this.webcamStream) {
                    this.webcamStream.getTracks().forEach(function (t) { t.stop(); });
                    this.webcamStream = null;
                }
            },
            capturarFotoWebcam: function () {
                var video = this.$refs.webcamVideo;
                var canvas = this.$refs.webcamCanvas;
                if (!video || !canvas || !video.videoWidth) return;
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                var dataUrl = canvas.toDataURL('image/jpeg', 0.88);
                var self = this;
                axios.post('{{ route("imei.imagen.base64") }}', {
                    base64: dataUrl,
                    tipo: this.tipoFotoCanje
                }).then(function (res) {
                    if (res.data && res.data.success) {
                        self.formCanje.imagenes_canje.push(res.data);
                        self.detenerWebcam();
                        $('#modalWebcamImei').modal('hide');
                    }
                });
            },
            procesarPlanCanje: function (modoCobro) {
                if (!this.formCanje.cliente_id) {
                    Swal.fire('Atención', 'Seleccioná el cliente que entrega el celular usado.', 'warning');
                    return;
                }
                if (!this.formCanje.usado.marca.trim() || !this.formCanje.usado.modelo.trim() || !this.formCanje.usado.imei_1.trim()) {
                    Swal.fire('Atención', 'Completá la marca, modelo e IMEI del equipo recibido en canje.', 'warning');
                    return;
                }
                if (this.formCanje.tipo_operacion === 'PLAN_CANJE' && !this.formCanje.celular_llevado_id) {
                    Swal.fire('Atención', 'Seleccioná el equipo en vitrina que el cliente se lleva.', 'warning');
                    return;
                }

                var self = this;
                this.guardandoCanje = true;
                var payload = Object.assign({}, this.formCanje, { modo_cobro: modoCobro });

                axios.post('{{ route("imei.canje.store") }}', payload)
                    .then(function (res) {
                        if (res.data && res.data.success) {
                            var canjeId = res.data.canje_id;
                            window.open('{{ url("celulares-imei/canje") }}/' + canjeId + '/pdf?autoprint=1', '_blank');

                            if (modoCobro === 'POS' && res.data.pos_payload) {
                                sessionStorage.setItem('presupuesto_a_venta', JSON.stringify(res.data.pos_payload));
                                window.location.href = '{{ route("venta") }}';
                                return;
                            }

                            Swal.fire('¡Operación Completada!', res.data.message, 'success');
                            self.cambiarVista('stock');
                        }
                    })
                    .catch(function (err) {
                        var msg = (err.response && err.response.data && err.response.data.message) || 'Error al registrar operación.';
                        Swal.fire('Atención', msg, 'warning');
                    })
                    .finally(function () {
                        self.guardandoCanje = false;
                    });
            },

            /* Alta de Equipo Nuevo */
            guardarEquipoNuevo: function (imprimirSticker) {
                if (!this.formIngreso.marca.trim() || !this.formIngreso.modelo.trim() || !this.formIngreso.imei_1.trim()) {
                    Swal.fire('Atención', 'Completá marca, modelo e IMEI del equipo.', 'warning');
                    return;
                }
                var self = this;
                this.guardandoIngreso = true;
                axios.post('{{ route("imei.store") }}', this.formIngreso)
                    .then(function (res) {
                        if (res.data && res.data.success) {
                            if (imprimirSticker) {
                                window.open('{{ url("celulares-imei") }}/' + res.data.id + '/pdf/etiqueta?autoprint=1', '_blank');
                            }
                            Swal.fire('Registrado', res.data.message, 'success');
                            self.formIngreso.imei_1 = '';
                            self.formIngreso.imei_2 = '';
                            self.cambiarVista('stock');
                        }
                    })
                    .catch(function (err) {
                        var msg = (err.response && err.response.data && err.response.data.message) || 'No se pudo registrar el equipo.';
                        Swal.fire('Atención', msg, 'warning');
                    })
                    .finally(function () {
                        self.guardandoIngreso = false;
                    });
            },

            /* Modal Detalle de Equipo, Taller y Venta */
            abrirModalEquipo: function (id) {
                var self = this;
                axios.get('{{ url("celulares-imei") }}/' + id)
                    .then(function (res) {
                        if (res.data && res.data.equipo) {
                            self.equipoActivo = res.data.equipo;
                            if (self.equipoActivo.reparacion_actual) {
                                self.formTallerModal.costo_adicional = Number(self.equipoActivo.reparacion_actual.costo_repuestos) || 0;
                            } else {
                                self.formTallerModal.costo_adicional = 0;
                            }
                            $('#modalDetalleImei').modal('show');
                        }
                    });
            },
            actualizarDatosEquipo: function () {
                var self = this;
                axios.put('{{ url("celulares-imei") }}/' + this.equipoActivo.id, this.equipoActivo)
                    .then(function (res) {
                        if (res.data && res.data.equipo) {
                            self.equipoActivo = res.data.equipo;
                            self.cargarEquipos(self.paginacion.current_page);
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Datos del IMEI actualizados',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    });
            },
            enviarEquipoATaller: function () {
                var self = this;
                axios.post('{{ url("celulares-imei") }}/' + this.equipoActivo.id + '/enviar-taller', {
                    motivo_taller: this.formTallerModal.motivo
                }).then(function (res) {
                    if (res.data && res.data.equipo) {
                        self.equipoActivo = res.data.equipo;
                        self.cargarEquipos(self.paginacion.current_page);
                        Swal.fire('Enviado a Taller', 'Se creó la orden de reacondicionamiento en Servicio Técnico.', 'success');
                    }
                });
            },
            finalizarTallerEquipo: function () {
                var self = this;
                axios.post('{{ url("celulares-imei") }}/' + this.equipoActivo.id + '/finalizar-taller', {
                    costo_reacondicionamiento_adicional: this.formTallerModal.costo_adicional
                }).then(function (res) {
                    if (res.data && res.data.equipo) {
                        self.equipoActivo = res.data.equipo;
                        self.cargarEquipos(self.paginacion.current_page);
                        Swal.fire('¡Listo para la Venta!', 'Se sumó el costo de reacondicionamiento y el equipo volvió a vitrina.', 'success');
                    }
                });
            },
            enviarEquipoAPos: function (eq) {
                axios.get('{{ url("celulares-imei") }}/' + eq.id + '/para-venta')
                    .then(function (res) {
                        if (res.data && res.data.success) {
                            sessionStorage.setItem('presupuesto_a_venta', JSON.stringify(res.data));
                            window.location.href = '{{ route("venta") }}';
                        }
                    });
            },

            /* Impresiones */
            imprimirStickerVitrina: function (id) {
                window.open('{{ url("celulares-imei") }}/' + id + '/pdf/etiqueta?autoprint=1', '_blank');
            },
            imprimirContratoCanje: function (canjeId) {
                window.open('{{ url("celulares-imei/canje") }}/' + canjeId + '/pdf?autoprint=1', '_blank');
            },
            imprimirCertificadoGarantia: function (id) {
                window.open('{{ url("celulares-imei") }}/' + id + '/pdf/garantia?autoprint=1', '_blank');
            }
        },
        mounted: function () {
            this.cargarEquipos(1);
            activarMenu('m_imei', '');

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
