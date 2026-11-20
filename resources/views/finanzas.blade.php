@extends('layouts.app')
@section('title', 'Gastos Operativos, Cuentas a Pagar y Estado de Resultados')

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
        --rep-primary: #0a4d36;
        --rep-primary-dark: #073827;
        --rep-primary-light: #eaf3ef;
        --rep-card-bg: #ffffff;
        --rep-border: #e2e8f0;
        --rep-text-main: #1e293b;
        --rep-text-muted: #64748b;
        --rep-panel-bg: #f8fafc;
    }

    body.dark-mode {
        --rep-primary: #10b981;
        --rep-primary-dark: #059669;
        --rep-primary-light: #064e3b;
        --rep-card-bg: #1f2937;
        --rep-border: #374151;
        --rep-text-main: #f3f4f6;
        --rep-text-muted: #9ca3af;
        --rep-panel-bg: #111827;
    }

    [v-cloak] {
        display: none !important;
    }

    #app {
        color: var(--rep-text-main);
    }

    .view-topbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.85rem;
        margin-bottom: 1rem;
        padding-bottom: 0.85rem;
        border-bottom: 1px solid var(--rep-border);
    }

    .view-title-box h3 {
        font-size: 1.4rem;
        font-weight: 800;
        margin: 0;
        color: var(--rep-text-main);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .view-title-box p {
        font-size: 0.84rem;
        color: var(--rep-text-muted);
        margin: 0;
    }

    .card-modern {
        background: var(--rep-card-bg);
        border: 1px solid var(--rep-border);
        border-radius: 14px;
        padding: 1.1rem;
        margin-bottom: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 0.85rem;
        margin-bottom: 1rem;
    }

    .kpi-card {
        background: var(--rep-card-bg);
        border: 1px solid var(--rep-border);
        border-radius: 12px;
        padding: 0.95rem 1.1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.15s ease;
    }

    .kpi-label {
        font-size: 0.74rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--rep-text-muted);
        letter-spacing: 0.3px;
    }

    .kpi-val {
        font-size: 1.35rem;
        font-weight: 900;
        color: var(--rep-text-main);
        line-height: 1.15;
    }

    .kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .unit-card {
        background: var(--rep-card-bg);
        border: 1px solid var(--rep-border);
        border-radius: 13px;
        padding: 1rem;
        height: 100%;
    }

    .unit-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.38rem 0;
        border-bottom: 1px dashed var(--rep-border);
        font-size: 0.85rem;
    }

    .unit-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .table-fin th {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 800;
        color: var(--rep-text-muted);
        background: var(--rep-panel-bg);
        border-bottom: 2px solid var(--rep-border);
        padding: 0.65rem 0.7rem;
    }

    .table-fin td {
        vertical-align: middle !important;
        font-size: 0.85rem;
        padding: 0.6rem 0.7rem;
        border-top: 1px solid var(--rep-border);
    }

    .nav-tabs-fin {
        display: flex;
        gap: 0.45rem;
        border-bottom: 2px solid var(--rep-border);
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .nav-tab-btn {
        border: none;
        background: transparent;
        color: var(--rep-text-muted);
        font-weight: 700;
        font-size: 0.88rem;
        padding: 0.55rem 1.05rem;
        border-radius: 8px 8px 0 0;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .nav-tab-btn.active {
        color: var(--rep-primary);
        border-bottom-color: var(--rep-primary);
        background: var(--rep-primary-light);
    }

    .net-profit-banner {
        border-radius: 14px;
        padding: 1.25rem 1.4rem;
        border: 2px solid #0a4d36;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        color: #064e3b;
    }

    body.dark-mode .net-profit-banner {
        background: linear-gradient(135deg, #064e3b 0%, #022c22 100%);
        border-color: #10b981;
        color: #ecfdf5;
    }

    .net-profit-banner.loss {
        border-color: #e11d48;
        background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
        color: #881337;
    }

    .progress-thin {
        height: 7px;
        border-radius: 999px;
        background: #e2e8f0;
        overflow: hidden;
    }
</style>
@endsection

@section('main')
<div id="app" v-cloak class="container-fluid py-2">

    <!-- TOPBAR -->
    <div class="view-topbar">
        <div class="view-title-box">
            <h3 class="font-cairo">
                <i class="fas fa-chart-pie text-info"></i>
                Gastos Operativos, Cuentas a Pagar y Estado de Resultados
                @if($esSuperAdministrador)
                    <span class="badge ml-2" :class="moduloActivo ? 'badge-success' : 'badge-danger'" style="font-size: 0.72rem;">
                        @{{ moduloActivo ? 'MÓDULO ACTIVO' : 'MÓDULO DESHABILITADO' }}
                    </span>
                @endif
            </h3>
            <p>Control integral de gastos fijos/variables, deudas con proveedores y cálculo de Ganancia Neta Real del negocio.</p>
        </div>

        <div class="d-flex align-items-center flex-wrap" style="gap: 0.5rem;">
            @if($esSuperAdministrador)
            <button type="button" class="btn btn-sm font-weight-bold"
                    :class="moduloActivo ? 'btn-outline-danger' : 'btn-warning'"
                    @click="toggleModuloSuperadmin">
                <i class="fas fa-power-off mr-1"></i>
                @{{ moduloActivo ? 'Desactivar Módulo' : 'Activar Módulo' }}
            </button>
            @endif

            <button v-if="permisos.registrar_gasto" type="button" class="btn btn-sm btn-danger font-cairo font-weight-bold shadow-sm" @click="abrirModalNuevoGasto">
                <i class="fas fa-receipt mr-1"></i> Registrar Gasto
            </button>

            <button v-if="permisos.cuentas_pagar" type="button" class="btn btn-sm btn-primary font-cairo font-weight-bold shadow-sm" @click="abrirModalNuevaCtaPagar">
                <i class="fas fa-file-invoice-dollar mr-1"></i> Nueva Factura Proveedor
            </button>

            <button v-if="permisos.ver_resultados" type="button" class="btn btn-sm btn-success font-cairo font-weight-bold shadow-sm" @click="imprimirEstadoResultados">
                <i class="fas fa-print mr-1"></i> Imprimir Estado de Resultados A4
            </button>
        </div>
    </div>

    <!-- PESTAÑAS PRINCIPALES -->
    <div class="nav-tabs-fin">
        <button v-if="permisos.ver_resultados" type="button" class="nav-tab-btn font-cairo" :class="{ active: tabPrincipal === 'resultados' }" @click="cambiarTab('resultados')">
            <i class="fas fa-chart-line"></i> 1. Estado de Resultados (Ganancia Neta Real)
        </button>
        <button type="button" class="nav-tab-btn font-cairo" :class="{ active: tabPrincipal === 'gastos' }" @click="cambiarTab('gastos')">
            <i class="fas fa-wallet"></i> 2. Gastos Operativos Fijos y Variables
        </button>
        <button v-if="permisos.cuentas_pagar" type="button" class="nav-tab-btn font-cairo" :class="{ active: tabPrincipal === 'ctas_pagar' }" @click="cambiarTab('ctas_pagar')">
            <i class="fas fa-truck-loading"></i> 3. Cuentas a Pagar a Proveedores
            <span v-if="kpisCtasPagar.vencidas_cant > 0" class="badge badge-danger ml-1">@{{ kpisCtasPagar.vencidas_cant }} venc.</span>
        </button>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 1: ESTADO DE RESULTADOS (GANANCIA NETA REAL)                          -->
    <!-- ========================================================================= -->
    <div v-if="tabPrincipal === 'resultados' && permisos.ver_resultados">
        <!-- Barra de Filtros de Período -->
        <div class="card-modern py-2 px-3 mb-3">
            <div class="row align-items-end">
                <div class="col-md-2 col-sm-6 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Fecha Desde</label>
                    <input type="date" class="form-control form-control-sm" v-model="filtroRes.desde">
                </div>
                <div class="col-md-2 col-sm-6 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Fecha Hasta</label>
                    <input type="date" class="form-control form-control-sm" v-model="filtroRes.hasta">
                </div>
                <div class="col-md-3 col-sm-6 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Sucursal</label>
                    <select class="form-control form-control-sm" v-model="filtroRes.suc_cod">
                        <option value="0">-- Consolidado Todas las Sucursales --</option>
                        <option v-for="s in sucursales" :key="s.suc_cod" :value="s.suc_cod">@{{ s.suc_desc }}</option>
                    </select>
                </div>
                <div class="col-md-5 col-sm-12 mb-2 d-flex flex-wrap align-items-center justify-content-end" style="gap: 0.35rem;">
                    <button type="button" class="btn btn-xs btn-outline-secondary" @click="setPeriodoRapido('ESTE_MES')">Este Mes</button>
                    <button type="button" class="btn btn-xs btn-outline-secondary" @click="setPeriodoRapido('MES_ANTERIOR')">Mes Anterior</button>
                    <button type="button" class="btn btn-xs btn-outline-secondary" @click="setPeriodoRapido('ANIO')">Año Actual</button>
                    <button type="button" class="btn btn-sm btn-primary font-cairo font-weight-bold ml-1" :disabled="cargandoResultados" @click="cargarEstadoResultados">
                        <i class="fas mr-1" :class="cargandoResultados ? 'fa-spinner fa-spin' : 'fa-sync-alt'"></i>
                        Calcular Resultados
                    </button>
                </div>
            </div>
        </div>

        <!-- KPIs Ejecutivos del Estado de Resultados -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div>
                    <div class="kpi-label">Facturación Total del Período</div>
                    <div class="kpi-val font-cairo text-primary">Gs. @{{ formatNumber(resultados.ingresos_totales) }}</div>
                    <div class="small text-muted">POS + Servicio Técnico + IMEI</div>
                </div>
                <div class="kpi-icon" style="background: #e0f2fe; color: #0284c7;">
                    <i class="fas fa-cash-register"></i>
                </div>
            </div>

            <div class="kpi-card">
                <div>
                    <div class="kpi-label">Utilidad Bruta Operativa</div>
                    <div class="kpi-val font-cairo text-success">Gs. @{{ formatNumber(resultados.utilidad_bruta_total) }}</div>
                    <div class="small text-muted">Costo Directo: Gs. @{{ formatNumber(resultados.costos_directos_totales) }}</div>
                </div>
                <div class="kpi-icon" style="background: #dcfce7; color: #15803d;">
                    <i class="fas fa-chart-bar"></i>
                </div>
            </div>

            <div class="kpi-card">
                <div>
                    <div class="kpi-label">Comisiones + Gastos Operativos</div>
                    <div class="kpi-val font-cairo text-danger">
                        - Gs. @{{ formatNumber((Number(resultados.total_comisiones) || 0) + (Number(resultados.total_gastos_operativos) || 0)) }}
                    </div>
                    <div class="small text-muted">
                        Com.: Gs. @{{ formatNumber(resultados.total_comisiones) }} | Gastos: Gs. @{{ formatNumber(resultados.total_gastos_operativos) }}
                    </div>
                </div>
                <div class="kpi-icon" style="background: #ffe4e6; color: #be123c;">
                    <i class="fas fa-arrow-trend-down"></i>
                </div>
            </div>

            <div class="kpi-card" :style="resultados.ganancia_neta_real >= 0 ? 'border: 2px solid #16a34a;' : 'border: 2px solid #e11d48;'">
                <div>
                    <div class="kpi-label font-weight-bold" :class="resultados.ganancia_neta_real >= 0 ? 'text-success' : 'text-danger'">
                        @{{ resultados.ganancia_neta_real >= 0 ? 'GANANCIA NETA REAL' : 'RESULTADO NETO (PÉRDIDA)' }}
                    </div>
                    <div class="kpi-val font-cairo" :class="resultados.ganancia_neta_real >= 0 ? 'text-success' : 'text-danger'">
                        Gs. @{{ formatNumber(resultados.ganancia_neta_real) }}
                    </div>
                    <div class="small font-weight-bold">
                        Margen Neto Real: @{{ resultados.margen_neto_porc }}%
                    </div>
                </div>
                <div class="kpi-icon" :style="resultados.ganancia_neta_real >= 0 ? 'background:#16a34a;color:#fff;' : 'background:#e11d48;color:#fff;'">
                    <i class="fas fa-trophy"></i>
                </div>
            </div>
        </div>

        <!-- Desglose por Unidad de Negocio (3 Columnas) -->
        <div class="row mb-3">
            <!-- 1. Punto de Venta POS -->
            <div class="col-md-4 mb-3">
                <div class="unit-card">
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <h6 class="font-cairo font-weight-bold mb-0">
                            <i class="fas fa-shopping-cart text-primary mr-1"></i> 1. Punto de Venta (POS)
                        </h6>
                        <span class="badge badge-light border">@{{ resultados.cant_ventas_pos || 0 }} venta(s)</span>
                    </div>
                    <div class="unit-row">
                        <span class="text-muted">Ingresos Facturados POS</span>
                        <span class="font-weight-bold">Gs. @{{ formatNumber(resultados.ingresos_ventas_pos) }}</span>
                    </div>
                    <div class="unit-row">
                        <span class="text-muted">(-) Costo de Mercadería Vendida</span>
                        <span class="text-danger">- Gs. @{{ formatNumber(resultados.costo_ventas_pos) }}</span>
                    </div>
                    <div class="unit-row pt-2">
                        <span class="font-cairo font-weight-bold">(=) Utilidad Bruta POS</span>
                        <span class="font-cairo font-weight-bold text-success" style="font-size: 1rem;">
                            Gs. @{{ formatNumber(resultados.utilidad_ventas_pos) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- 2. Servicio Técnico y Reparación -->
            <div class="col-md-4 mb-3">
                <div class="unit-card">
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <h6 class="font-cairo font-weight-bold mb-0">
                            <i class="fas fa-tools text-warning mr-1"></i> 2. Servicio Técnico (Taller)
                        </h6>
                        <span class="badge badge-light border">@{{ resultados.cant_reparaciones || 0 }} orden(es)</span>
                    </div>
                    <div class="unit-row">
                        <span class="text-muted">Ingresos Cobrados Taller</span>
                        <span class="font-weight-bold">Gs. @{{ formatNumber(resultados.ingresos_taller) }}</span>
                    </div>
                    <div class="unit-row">
                        <span class="text-muted">(-) Costo de Repuestos Utilizados</span>
                        <span class="text-danger">- Gs. @{{ formatNumber(resultados.costo_repuestos_taller) }}</span>
                    </div>
                    <div class="unit-row" v-if="resultados.costo_garantias_taller > 0">
                        <span class="text-muted">(-) Costo Garantías Asumidas</span>
                        <span class="text-danger">- Gs. @{{ formatNumber(resultados.costo_garantias_taller) }}</span>
                    </div>
                    <div class="unit-row pt-2">
                        <span class="font-cairo font-weight-bold">(=) Utilidad Bruta Taller</span>
                        <span class="font-cairo font-weight-bold text-success" style="font-size: 1rem;">
                            Gs. @{{ formatNumber(resultados.utilidad_taller) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- 3. Equipos por IMEI y Plan Canje -->
            <div class="col-md-4 mb-3">
                <div class="unit-card">
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <h6 class="font-cairo font-weight-bold mb-0">
                            <i class="fas fa-mobile-alt text-info mr-1"></i> 3. Equipos IMEI / Plan Canje
                        </h6>
                        <span class="badge badge-light border">@{{ resultados.cant_equipos_imei || 0 }} equipo(s)</span>
                    </div>
                    <div class="unit-row">
                        <span class="text-muted">Ventas Netas Equipos IMEI</span>
                        <span class="font-weight-bold">Gs. @{{ formatNumber(resultados.ingresos_imei) }}</span>
                    </div>
                    <div class="unit-row">
                        <span class="text-muted">(-) Costo Compra / Canje + Taller</span>
                        <span class="text-danger">- Gs. @{{ formatNumber(resultados.costo_imei) }}</span>
                    </div>
                    <div class="unit-row pt-2">
                        <span class="font-cairo font-weight-bold">(=) Utilidad Bruta Equipos IMEI</span>
                        <span class="font-cairo font-weight-bold text-success" style="font-size: 1rem;">
                            Gs. @{{ formatNumber(resultados.utilidad_imei) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desglose de Gastos Operativos por Categoría y Banner Final -->
        <div class="row">
            <div class="col-lg-7 mb-3">
                <div class="card-modern h-100 mb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="font-cairo font-weight-bold mb-0">
                            <i class="fas fa-list-ul text-danger mr-1"></i>
                            Desglose de Egresos Operativos y Comisiones del Período
                        </h6>
                        <button v-if="permisos.registrar_gasto" type="button" class="btn btn-xs btn-outline-danger font-weight-bold" @click="abrirModalNuevoGasto">
                            <i class="fas fa-plus mr-1"></i> Cargar Gasto
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-fin mb-0">
                            <thead>
                                <tr>
                                    <th>Categoría de Egreso</th>
                                    <th class="text-center">Tipo</th>
                                    <th class="text-center">Reg.</th>
                                    <th style="width: 140px;">Participación</th>
                                    <th class="text-right">Monto Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="resultados.total_comisiones > 0">
                                    <td>
                                        <i class="fas fa-user-check text-purple mr-1" style="color: #7e22ce;"></i>
                                        <strong>Comisiones Liquidadas a Personal</strong>
                                    </td>
                                    <td class="text-center"><span class="badge badge-light border">VARIABLE</span></td>
                                    <td class="text-center">-</td>
                                    <td>
                                        <div class="progress-thin">
                                            <div style="height:100%;background:#7e22ce;" :style="{ width: calcularPorcEgreso(resultados.total_comisiones) + '%' }"></div>
                                        </div>
                                    </td>
                                    <td class="text-right font-weight-bold text-danger">
                                        - Gs. @{{ formatNumber(resultados.total_comisiones) }}
                                    </td>
                                </tr>
                                <tr v-for="gcat in resultados.gastos_por_categoria" :key="gcat.id">
                                    <td>
                                        <i :class="gcat.icono || 'fas fa-receipt'" class="mr-1" :style="{ color: gcat.color || '#0a4d36' }"></i>
                                        <strong>@{{ gcat.nombre }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge" :class="gcat.tipo === 'FIJO' ? 'badge-info' : 'badge-secondary'">@{{ gcat.tipo }}</span>
                                    </td>
                                    <td class="text-center">@{{ gcat.cantidad }}</td>
                                    <td>
                                        <div class="progress-thin">
                                            <div style="height:100%;" :style="{ width: calcularPorcEgreso(gcat.total) + '%', background: gcat.color || '#e11d48' }"></div>
                                        </div>
                                    </td>
                                    <td class="text-right font-weight-bold text-danger">
                                        - Gs. @{{ formatNumber(gcat.total) }}
                                    </td>
                                </tr>
                                <tr v-if="(!resultados.gastos_por_categoria || resultados.gastos_por_categoria.length === 0) && !resultados.total_comisiones">
                                    <td colspan="5" class="text-center text-muted py-3">
                                        No se registraron gastos operativos ni comisiones en este período.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 mb-3">
                <div class="net-profit-banner h-100 d-flex flex-column justify-content-between" :class="{ loss: resultados.ganancia_neta_real < 0 }">
                    <div>
                        <div class="small font-weight-bold text-uppercase mb-1">
                            <i class="fas fa-balance-scale mr-1"></i> Resumen Contable de Ganancia Neta Real
                        </div>
                        <h4 class="font-cairo font-weight-bold mb-3">
                            @{{ resultados.ganancia_neta_real >= 0 ? 'Utilidad Neta Disponible' : 'Déficit Operativo del Período' }}
                        </h4>

                        <div class="d-flex justify-content-between py-1 border-bottom" style="border-color: rgba(0,0,0,0.1) !important;">
                            <span>(+) Utilidad Bruta Ventas POS:</span>
                            <strong>Gs. @{{ formatNumber(resultados.utilidad_ventas_pos) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom" style="border-color: rgba(0,0,0,0.1) !important;">
                            <span>(+) Utilidad Bruta Servicio Técnico:</span>
                            <strong>Gs. @{{ formatNumber(resultados.utilidad_taller) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom" style="border-color: rgba(0,0,0,0.1) !important;">
                            <span>(+) Utilidad Bruta Equipos IMEI:</span>
                            <strong>Gs. @{{ formatNumber(resultados.utilidad_imei) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom font-weight-bold" style="border-color: rgba(0,0,0,0.15) !important;">
                            <span>(=) Total Utilidad Bruta Operativa:</span>
                            <span>Gs. @{{ formatNumber(resultados.utilidad_bruta_total) }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom text-danger" style="border-color: rgba(0,0,0,0.1) !important;">
                            <span>(-) Comisiones Pagadas al Personal:</span>
                            <strong>- Gs. @{{ formatNumber(resultados.total_comisiones) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom text-danger" style="border-color: rgba(0,0,0,0.15) !important;">
                            <span>(-) Gastos Operativos (Alquiler, ANDE, etc.):</span>
                            <strong>- Gs. @{{ formatNumber(resultados.total_gastos_operativos) }}</strong>
                        </div>
                    </div>

                    <div class="mt-3 pt-2 d-flex justify-content-between align-items-end">
                        <div>
                            <div class="small font-weight-bold text-uppercase">(=) Ganancia Neta Real</div>
                            <div class="small">Rentabilidad Neta: <strong>@{{ resultados.margen_neto_porc }}%</strong></div>
                        </div>
                        <div class="font-cairo font-weight-bold" style="font-size: 1.75rem; line-height: 1;">
                            Gs. @{{ formatNumber(resultados.ganancia_neta_real) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 2: GASTOS OPERATIVOS FIJOS Y VARIABLES                                -->
    <!-- ========================================================================= -->
    <div v-if="tabPrincipal === 'gastos'">
        <div class="card-modern">
            <div class="row align-items-end mb-3">
                <div class="col-md-2 col-sm-6 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Desde</label>
                    <input type="date" class="form-control form-control-sm" v-model="filtroGastos.desde" @change="cargarGastos(1)">
                </div>
                <div class="col-md-2 col-sm-6 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Hasta</label>
                    <input type="date" class="form-control form-control-sm" v-model="filtroGastos.hasta" @change="cargarGastos(1)">
                </div>
                <div class="col-md-2 col-sm-6 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Categoría</label>
                    <select class="form-control form-control-sm" v-model="filtroGastos.categoria_id" @change="cargarGastos(1)">
                        <option value="0">-- Todas --</option>
                        <option v-for="c in categorias" :key="c.id" :value="c.id">@{{ c.nombre }} (@{{ c.tipo }})</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-6 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Buscar concepto o comprobante</label>
                    <input type="text" class="form-control form-control-sm" placeholder="Ej: ANDE, Alquiler, Factura..." v-model="filtroGastos.buscar" @keyup.enter="cargarGastos(1)">
                </div>
                <div class="col-md-3 col-sm-12 mb-2 d-flex justify-content-end" style="gap: 0.4rem;">
                    <button type="button" class="btn btn-sm btn-outline-primary font-weight-bold" @click="cargarGastos(1)">
                        <i class="fas fa-search mr-1"></i> Filtrar
                    </button>
                    <button v-if="permisos.registrar_gasto" type="button" class="btn btn-sm btn-outline-secondary font-weight-bold" @click="abrirModalCategoria">
                        <i class="fas fa-tags mr-1"></i> + Categoría
                    </button>
                    <button v-if="permisos.registrar_gasto" type="button" class="btn btn-sm btn-danger font-cairo font-weight-bold" @click="abrirModalNuevoGasto">
                        <i class="fas fa-plus mr-1"></i> Nuevo Gasto
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-fin mb-0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Fecha</th>
                            <th>Categoría</th>
                            <th>Concepto / Detalle del Gasto</th>
                            <th>Comprobante / Proveedor</th>
                            <th class="text-center">Caja</th>
                            <th class="text-right">Monto (Gs.)</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="!gastosList.length">
                            <td colspan="8" class="text-center text-muted py-4">
                                No se encontraron gastos operativos registrados con los filtros actuales.
                            </td>
                        </tr>
                        <tr v-for="g in gastosList" :key="g.id">
                            <td class="font-weight-bold">@{{ g.codigo_gasto }}</td>
                            <td>@{{ g.fecha_gasto ? g.fecha_gasto.substring(0, 16) : '-' }}</td>
                            <td>
                                <span v-if="g.categoria" class="badge badge-light border">
                                    <i :class="g.categoria.icono || 'fas fa-tag'" class="mr-1" :style="{ color: g.categoria.color || '#0a4d36' }"></i>
                                    @{{ g.categoria.nombre }}
                                </span>
                            </td>
                            <td>
                                <div class="font-weight-bold">@{{ g.concepto }}</div>
                                <div v-if="g.observacion" class="small text-muted">@{{ g.observacion }}</div>
                            </td>
                            <td>
                                <div v-if="g.nro_comprobante" class="small font-weight-bold">Comp.: @{{ g.nro_comprobante }}</div>
                                <div v-if="g.proveedor" class="small text-muted">@{{ g.proveedor.proveedor_nombre }}</div>
                                <span v-if="!g.nro_comprobante && !g.proveedor" class="text-muted small">-</span>
                            </td>
                            <td class="text-center">
                                <span v-if="g.registrar_caja" class="badge badge-warning" :title="'Op. Caja #' + g.nro_operacion_caja">Salida Caja</span>
                                <span v-else class="badge badge-light border">Administrativo</span>
                            </td>
                            <td class="text-right font-cairo font-weight-bold text-danger" style="font-size: 0.95rem;">
                                Gs. @{{ formatNumber(g.monto) }}
                            </td>
                            <td class="text-right">
                                <button v-if="permisos.del" type="button" class="btn btn-xs btn-outline-danger" title="Anular Gasto" @click="anularGasto(g)">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 3: CUENTAS A PAGAR A PROVEEDORES                                      -->
    <!-- ========================================================================= -->
    <div v-if="tabPrincipal === 'ctas_pagar' && permisos.cuentas_pagar">
        <!-- KPIs Cuentas a Pagar -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div>
                    <div class="kpi-label">Deuda Total Pendiente</div>
                    <div class="kpi-val font-cairo text-danger">Gs. @{{ formatNumber(kpisCtasPagar.deuda_total_pendiente) }}</div>
                    <div class="small text-muted">Saldo total por pagar a proveedores</div>
                </div>
                <div class="kpi-icon" style="background: #ffe4e6; color: #be123c;">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
            </div>

            <div class="kpi-card">
                <div>
                    <div class="kpi-label">Facturas / Cuotas Vencidas</div>
                    <div class="kpi-val font-cairo text-danger">
                        @{{ kpisCtasPagar.vencidas_cant || 0 }} cuota(s)
                    </div>
                    <div class="small text-danger font-weight-bold">Gs. @{{ formatNumber(kpisCtasPagar.vencidas_monto) }} vencidos</div>
                </div>
                <div class="kpi-icon" style="background: #fef2f2; color: #dc2626;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>

            <div class="kpi-card">
                <div>
                    <div class="kpi-label">Próximas a Vencer (5 días)</div>
                    <div class="kpi-val font-cairo text-warning">@{{ kpisCtasPagar.proximas_vencer_cant || 0 }} cuota(s)</div>
                    <div class="small text-muted">Atención prioritaria</div>
                </div>
                <div class="kpi-icon" style="background: #fef3c7; color: #d97706;">
                    <i class="fas fa-clock"></i>
                </div>
            </div>

            <div class="kpi-card">
                <div>
                    <div class="kpi-label">Pagado a Proveedores (Este Mes)</div>
                    <div class="kpi-val font-cairo text-success">Gs. @{{ formatNumber(kpisCtasPagar.pagado_mes) }}</div>
                    <div class="small text-muted">Abonos registrados en el mes</div>
                </div>
                <div class="kpi-icon" style="background: #dcfce7; color: #15803d;">
                    <i class="fas fa-check-double"></i>
                </div>
            </div>
        </div>

        <div class="card-modern">
            <div class="row align-items-end mb-3">
                <div class="col-md-3 col-sm-6 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Proveedor</label>
                    <select class="form-control form-control-sm" v-model="filtroCtas.proveedor_cod" @change="cargarCtasPagar(1)">
                        <option value="0">-- Todos los Proveedores --</option>
                        <option v-for="p in proveedores" :key="p.PROVEEDOR_cod" :value="p.PROVEEDOR_cod">@{{ p.proveedor_nombre }}</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-6 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Estado de Cuenta</label>
                    <select class="form-control form-control-sm" v-model="filtroCtas.estado" @change="cargarCtasPagar(1)">
                        <option value="PENDIENTE_SALDO">Con Saldo Pendiente (Pendientes + Parciales)</option>
                        <option value="VENCIDAS">Solo Vencidas</option>
                        <option value="PAGADO">Pagadas Totalmente</option>
                        <option value="TODOS">Todas</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-6 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Buscar N° Factura / Concepto</label>
                    <input type="text" class="form-control form-control-sm" placeholder="N° factura o código..." v-model="filtroCtas.buscar" @keyup.enter="cargarCtasPagar(1)">
                </div>
                <div class="col-md-3 col-sm-6 mb-2 d-flex justify-content-end" style="gap: 0.4rem;">
                    <button type="button" class="btn btn-sm btn-outline-primary font-weight-bold" @click="cargarCtasPagar(1)">
                        <i class="fas fa-search mr-1"></i> Filtrar
                    </button>
                    <button type="button" class="btn btn-sm btn-primary font-cairo font-weight-bold" @click="abrirModalNuevaCtaPagar">
                        <i class="fas fa-plus mr-1"></i> Registrar Factura
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-fin mb-0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Proveedor</th>
                            <th>Factura / Concepto</th>
                            <th class="text-center">Cuota</th>
                            <th>Vencimiento</th>
                            <th class="text-right">Monto Total</th>
                            <th class="text-right">Pagado</th>
                            <th class="text-right">Saldo Pendiente</th>
                            <th class="text-center">Estado</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="!ctasPagarList.length">
                            <td colspan="10" class="text-center text-muted py-4">
                                No se encontraron cuentas a pagar con los filtros seleccionados.
                            </td>
                        </tr>
                        <tr v-for="c in ctasPagarList" :key="c.id">
                            <td class="font-weight-bold">
                                @{{ c.codigo_cta }}
                                <div v-if="c.compra_cod" class="small text-info">Compra #@{{ c.compra_cod }}</div>
                            </td>
                            <td>
                                <div class="font-weight-bold">@{{ c.proveedor ? c.proveedor.proveedor_nombre : 'Sin proveedor' }}</div>
                                <div class="small text-muted" v-if="c.proveedor && c.proveedor.proveedor_ruc">RUC: @{{ c.proveedor.proveedor_ruc }}</div>
                            </td>
                            <td>
                                <div class="font-weight-bold">Fact.: @{{ c.nro_factura_proveedor || 'S/N' }}</div>
                                <div class="small text-muted">@{{ c.concepto }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-light border">@{{ c.cuota_nro }} / @{{ c.total_cuotas }}</span>
                            </td>
                            <td>
                                <div class="font-weight-bold">@{{ c.fecha_vencimiento || '-' }}</div>
                                <span v-if="esVencida(c)" class="badge badge-danger">VENCIDA</span>
                            </td>
                            <td class="text-right">Gs. @{{ formatNumber(c.monto_total) }}</td>
                            <td class="text-right text-success">Gs. @{{ formatNumber(c.monto_pagado) }}</td>
                            <td class="text-right font-cairo font-weight-bold text-danger" style="font-size: 0.95rem;">
                                Gs. @{{ formatNumber(c.monto_saldo) }}
                            </td>
                            <td class="text-center">
                                <span class="badge"
                                      :class="{
                                          'badge-warning': c.estado === 'PENDIENTE',
                                          'badge-info': c.estado === 'PARCIAL',
                                          'badge-success': c.estado === 'PAGADO',
                                          'badge-secondary': c.estado === 'ANULADO'
                                      }">
                                    @{{ c.estado }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="btn-group">
                                    <button v-if="c.monto_saldo > 0 && c.estado !== 'ANULADO'"
                                            type="button"
                                            class="btn btn-xs btn-success font-weight-bold"
                                            title="Registrar Pago a Proveedor"
                                            @click="abrirModalPagoCta(c)">
                                        <i class="fas fa-hand-holding-usd mr-1"></i> Pagar
                                    </button>
                                    <button type="button"
                                            class="btn btn-xs btn-outline-info"
                                            title="Ver Historial de Pagos"
                                            @click="verDetalleCta(c.id)">
                                        <i class="fas fa-list"></i>
                                    </button>
                                    <button v-if="permisos.del && c.monto_pagado == 0"
                                            type="button"
                                            class="btn btn-xs btn-outline-danger"
                                            title="Anular Cuenta"
                                            @click="anularCtaPagar(c)">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 1: REGISTRAR NUEVO GASTO OPERATIVO                                  -->
    <!-- ========================================================================= -->
    <div class="modal fade" id="modalNuevoGasto" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white py-2">
                    <h6 class="modal-title font-cairo font-weight-bold">
                        <i class="fas fa-receipt mr-1"></i> Registrar Gasto Operativo
                    </h6>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-7">
                            <label class="small font-weight-bold">Categoría del Gasto *</label>
                            <div class="input-group input-group-sm">
                                <select class="form-control" v-model="formGasto.categoria_id">
                                    <option v-for="c in categorias" :key="c.id" :value="c.id">@{{ c.nombre }} (@{{ c.tipo }})</option>
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" title="Crear Categoría" @click="abrirModalCategoria">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-5">
                            <label class="small font-weight-bold">Fecha del Gasto *</label>
                            <input type="datetime-local" class="form-control form-control-sm" v-model="formGasto.fecha_gasto">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold">Concepto / Descripción del Gasto *</label>
                        <input type="text" class="form-control form-control-sm" placeholder="Ej: Pago factura ANDE mes actual, Alquiler local, Insumos..." v-model="formGasto.concepto">
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">Monto del Gasto (Gs.) *</label>
                            <input type="number" class="form-control form-control-sm font-weight-bold" min="1" step="500" v-model.number="formGasto.monto">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">N° Factura / Comprobante</label>
                            <input type="text" class="form-control form-control-sm" placeholder="Opcional" v-model="formGasto.nro_comprobante">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">Proveedor / Beneficiario</label>
                            <select class="form-control form-control-sm" v-model="formGasto.PROVEEDOR_cod">
                                <option value="">-- Sin Proveedor específico --</option>
                                <option v-for="p in proveedores" :key="p.PROVEEDOR_cod" :value="p.PROVEEDOR_cod">@{{ p.proveedor_nombre }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">Forma de Pago</label>
                            <select class="form-control form-control-sm" v-model.number="formGasto.forma_pago">
                                <option :value="1">Efectivo</option>
                                <option :value="2">Transferencia Bancaria</option>
                                <option :value="3">Tarjeta / QR</option>
                            </select>
                        </div>
                    </div>

                    <div class="custom-control custom-checkbox mt-1">
                        <input type="checkbox" class="custom-control-input" id="chkGastoCaja" v-model="formGasto.registrar_caja">
                        <label class="custom-control-label small font-weight-bold" for="chkGastoCaja">
                            Descontar automáticamente como Salida en Caja Abierta de la sucursal
                        </label>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-sm btn-danger font-cairo font-weight-bold" :disabled="guardandoGasto" @click="guardarGasto">
                        <i class="fas mr-1" :class="guardandoGasto ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                        Guardar Gasto Operativo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 2: CREAR NUEVA CATEGORÍA DE GASTO                                   -->
    <!-- ========================================================================= -->
    <div class="modal fade" id="modalCategoriaGasto" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white py-2">
                    <h6 class="modal-title font-cairo font-weight-bold">Nueva Categoría de Gasto</h6>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="small font-weight-bold">Nombre de Categoría *</label>
                        <input type="text" class="form-control form-control-sm" placeholder="Ej: Publicidad, Limpieza..." v-model="formCategoria.nombre">
                    </div>
                    <div class="form-group mb-0">
                        <label class="small font-weight-bold">Tipo de Gasto</label>
                        <select class="form-control form-control-sm" v-model="formCategoria.tipo">
                            <option value="FIJO">FIJO (Mensual recurrente)</option>
                            <option value="VARIABLE">VARIABLE</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-primary font-weight-bold" @click="guardarCategoria">Guardar Categoría</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 3: REGISTRAR NUEVA CUENTA A PAGAR A PROVEEDOR                       -->
    <!-- ========================================================================= -->
    <div class="modal fade" id="modalNuevaCtaPagar" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white py-2">
                    <h6 class="modal-title font-cairo font-weight-bold">
                        <i class="fas fa-file-invoice-dollar mr-1"></i> Registrar Factura / Cuenta a Pagar a Proveedor
                    </h6>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="small font-weight-bold">Proveedor *</label>
                        <select class="form-control form-control-sm" v-model="formCta.PROVEEDOR_cod">
                            <option value="">-- Seleccione Proveedor --</option>
                            <option v-for="p in proveedores" :key="p.PROVEEDOR_cod" :value="p.PROVEEDOR_cod">
                                @{{ p.proveedor_nombre }} @{{ p.proveedor_ruc ? '(' + p.proveedor_ruc + ')' : '' }}
                            </option>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">N° Factura del Proveedor</label>
                            <input type="text" class="form-control form-control-sm" placeholder="Ej: 001-001-0045210" v-model="formCta.nro_factura_proveedor">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">Monto Total Factura (Gs.) *</label>
                            <input type="number" class="form-control form-control-sm font-weight-bold" min="1" step="1000" v-model.number="formCta.monto_total">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold">Concepto / Detalle *</label>
                        <input type="text" class="form-control form-control-sm" placeholder="Ej: Compra de lote de repuestos y pantallas a crédito" v-model="formCta.concepto">
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="small font-weight-bold">Fecha Emisión *</label>
                            <input type="date" class="form-control form-control-sm" v-model="formCta.fecha_emision">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="small font-weight-bold">1° Vencimiento *</label>
                            <input type="date" class="form-control form-control-sm" v-model="formCta.fecha_vencimiento">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="small font-weight-bold">Cantidad de Cuotas</label>
                            <input type="number" class="form-control form-control-sm" min="1" max="36" v-model.number="formCta.cantidad_cuotas">
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-sm btn-primary font-cairo font-weight-bold" :disabled="guardandoCta" @click="guardarCtaPagar">
                        <i class="fas mr-1" :class="guardandoCta ? 'fa-spinner fa-spin' : 'fa-save'"></i>
                        Generar Cuenta a Pagar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 4: REGISTRAR PAGO PARCIAL O TOTAL A PROVEEDOR                       -->
    <!-- ========================================================================= -->
    <div class="modal fade" id="modalPagoCtaPagar" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content" v-if="ctaSeleccionada">
                <div class="modal-header bg-success text-white py-2">
                    <h6 class="modal-title font-cairo font-weight-bold">
                        <i class="fas fa-hand-holding-usd mr-1"></i> Registrar Pago a Proveedor (@{{ ctaSeleccionada.codigo_cta }})
                    </h6>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="p-2 mb-3 rounded border" style="background: var(--rep-panel-bg);">
                        <div class="font-weight-bold">@{{ ctaSeleccionada.proveedor ? ctaSeleccionada.proveedor.proveedor_nombre : 'Proveedor' }}</div>
                        <div class="small text-muted">Factura: @{{ ctaSeleccionada.nro_factura_proveedor || 'S/N' }} — Cuota @{{ ctaSeleccionada.cuota_nro }}/@{{ ctaSeleccionada.total_cuotas }}</div>
                        <div class="d-flex justify-content-between mt-1 pt-1 border-top">
                            <span>Saldo Pendiente:</span>
                            <strong class="text-danger">Gs. @{{ formatNumber(ctaSeleccionada.monto_saldo) }}</strong>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">Monto a Abonar (Gs.) *</label>
                            <input type="number" class="form-control form-control-sm font-weight-bold" min="1" :max="ctaSeleccionada.monto_saldo" v-model.number="formPagoCta.monto">
                        </div>
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">Forma de Pago</label>
                            <select class="form-control form-control-sm" v-model.number="formPagoCta.forma_pago">
                                <option :value="1">Efectivo</option>
                                <option :value="2">Transferencia Bancaria</option>
                                <option :value="3">Cheque / QR</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold">N° Recibo / Comprobante de Pago</label>
                        <input type="text" class="form-control form-control-sm" placeholder="N° recibo o transferencia..." v-model="formPagoCta.nro_recibo_comprobante">
                    </div>

                    <div class="form-group">
                        <label class="small font-weight-bold">Observación</label>
                        <input type="text" class="form-control form-control-sm" v-model="formPagoCta.observacion">
                    </div>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="chkPagoProvCaja" v-model="formPagoCta.registrar_caja">
                        <label class="custom-control-label small font-weight-bold" for="chkPagoProvCaja">
                            Registrar salida de dinero en Caja Abierta de la sucursal
                        </label>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-sm btn-success font-cairo font-weight-bold" :disabled="guardandoPagoCta" @click="confirmarPagoCta">
                        <i class="fas mr-1" :class="guardandoPagoCta ? 'fa-spinner fa-spin' : 'fa-check-circle'"></i>
                        Confirmar Pago
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 5: HISTORIAL DE PAGOS DE UNA CUENTA A PAGAR                         -->
    <!-- ========================================================================= -->
    <div class="modal fade" id="modalDetalleCtaPagar" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" v-if="ctaDetalle">
                <div class="modal-header bg-info text-white py-2">
                    <h6 class="modal-title font-cairo font-weight-bold">
                        Historial de Pagos — @{{ ctaDetalle.codigo_cta }} (@{{ ctaDetalle.proveedor ? ctaDetalle.proveedor.proveedor_nombre : '' }})
                    </h6>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Fecha Pago</th>
                                    <th>Comprobante</th>
                                    <th>Registrado por</th>
                                    <th>Observación</th>
                                    <th class="text-right">Monto Abonado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="!ctaDetalle.pagos || !ctaDetalle.pagos.length">
                                    <td colspan="5" class="text-center text-muted py-3">Sin pagos registrados para esta cuenta.</td>
                                </tr>
                                <tr v-for="p in ctaDetalle.pagos" :key="p.id">
                                    <td>@{{ p.fecha_pago }}</td>
                                    <td>@{{ p.nro_recibo_comprobante || '-' }}</td>
                                    <td>@{{ p.usuario ? p.usuario.nom_usuarios : '-' }}</td>
                                    <td>@{{ p.observacion || '-' }}</td>
                                    <td class="text-right font-weight-bold text-success">Gs. @{{ formatNumber(p.monto) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
            moduloActivo: {{ !empty($finanzasConfig['modulo_finanzas_activo']) ? 'true' : 'false' }},
            permisos: @json($permisosModulo),
            categorias: @json($categorias),
            proveedores: @json($proveedores),
            sucursales: @json($sucursales),
            sucursalActual: '{{ $sucursalActual }}',
            tabPrincipal: '{{ $permisosModulo["ver_resultados"] ? "resultados" : "gastos" }}',

            // Tab 1: Estado de Resultados
            cargandoResultados: false,
            filtroRes: {
                desde: '{{ date("Y-m-01") }}',
                hasta: '{{ date("Y-m-d") }}',
                suc_cod: '0'
            },
            resultados: {
                cant_ventas_pos: 0,
                ingresos_ventas_pos: 0,
                costo_ventas_pos: 0,
                utilidad_ventas_pos: 0,
                cant_reparaciones: 0,
                ingresos_taller: 0,
                costo_repuestos_taller: 0,
                costo_garantias_taller: 0,
                utilidad_taller: 0,
                cant_equipos_imei: 0,
                ingresos_imei: 0,
                costo_imei: 0,
                utilidad_imei: 0,
                ingresos_totales: 0,
                costos_directos_totales: 0,
                utilidad_bruta_total: 0,
                total_comisiones: 0,
                gastos_por_categoria: [],
                total_gastos_operativos: 0,
                ganancia_neta_real: 0,
                margen_neto_porc: 0
            },

            // Tab 2: Gastos Operativos
            gastosList: [],
            filtroGastos: {
                desde: '{{ date("Y-m-01") }}',
                hasta: '{{ date("Y-m-d") }}',
                categoria_id: '0',
                buscar: ''
            },
            guardandoGasto: false,
            formGasto: {
                categoria_id: {{ $categorias->first()->id ?? 'null' }},
                fecha_gasto: '{{ date("Y-m-d\TH:i") }}',
                concepto: '',
                monto: '',
                nro_comprobante: '',
                PROVEEDOR_cod: '',
                forma_pago: 1,
                registrar_caja: true
            },
            formCategoria: {
                nombre: '',
                tipo: 'VARIABLE'
            },

            // Tab 3: Cuentas a Pagar
            ctasPagarList: [],
            kpisCtasPagar: {
                deuda_total_pendiente: 0,
                vencidas_monto: 0,
                vencidas_cant: 0,
                proximas_vencer_cant: 0,
                pagado_mes: 0
            },
            filtroCtas: {
                proveedor_cod: '0',
                estado: 'PENDIENTE_SALDO',
                buscar: ''
            },
            guardandoCta: false,
            formCta: {
                PROVEEDOR_cod: '',
                nro_factura_proveedor: '',
                concepto: '',
                monto_total: '',
                fecha_emision: '{{ date("Y-m-d") }}',
                fecha_vencimiento: '{{ date("Y-m-d", strtotime("+30 days")) }}',
                cantidad_cuotas: 1
            },
            ctaSeleccionada: null,
            guardandoPagoCta: false,
            formPagoCta: {
                monto: 0,
                forma_pago: 1,
                nro_recibo_comprobante: '',
                observacion: 'Pago a proveedor',
                registrar_caja: true
            },
            ctaDetalle: null
        },
        methods: {
            formatNumber: function (val) {
                var n = Math.round(Number(val) || 0);
                return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            },
            cambiarTab: function (tab) {
                this.tabPrincipal = tab;
                if (tab === 'resultados') {
                    this.cargarEstadoResultados();
                } else if (tab === 'gastos') {
                    this.cargarGastos(1);
                } else if (tab === 'ctas_pagar') {
                    this.cargarCtasPagar(1);
                }
            },
            setPeriodoRapido: function (tipo) {
                var hoy = new Date();
                var y = hoy.getFullYear();
                var m = hoy.getMonth();
                var pad = function (n) { return n < 10 ? '0' + n : '' + n; };
                var fmt = function (d) { return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()); };

                if (tipo === 'ESTE_MES') {
                    this.filtroRes.desde = fmt(new Date(y, m, 1));
                    this.filtroRes.hasta = fmt(hoy);
                } else if (tipo === 'MES_ANTERIOR') {
                    this.filtroRes.desde = fmt(new Date(y, m - 1, 1));
                    this.filtroRes.hasta = fmt(new Date(y, m, 0));
                } else if (tipo === 'ANIO') {
                    this.filtroRes.desde = y + '-01-01';
                    this.filtroRes.hasta = fmt(hoy);
                }
                this.cargarEstadoResultados();
            },
            cargarEstadoResultados: function () {
                if (!this.permisos.ver_resultados) return;
                var self = this;
                this.cargandoResultados = true;
                axios.get('{{ route("finanzas.resultados") }}', {
                    params: this.filtroRes
                }).then(function (res) {
                    if (res.data && res.data.success) {
                        self.resultados = res.data.resultados;
                    }
                }).catch(function () {
                    Swal.fire('Error', 'No se pudo calcular el Estado de Resultados.', 'error');
                }).finally(function () {
                    self.cargandoResultados = false;
                });
            },
            calcularPorcEgreso: function (monto) {
                var total = (Number(this.resultados.total_comisiones) || 0) + (Number(this.resultados.total_gastos_operativos) || 0);
                if (total <= 0) return 0;
                return Math.min(100, Math.round((Number(monto) / total) * 100));
            },
            imprimirEstadoResultados: function () {
                var url = '{{ route("finanzas.pdf.resultados") }}?desde=' + encodeURIComponent(this.filtroRes.desde)
                    + '&hasta=' + encodeURIComponent(this.filtroRes.hasta)
                    + '&suc_cod=' + encodeURIComponent(this.filtroRes.suc_cod);
                window.open(url, '_blank');
            },

            // Gastos Operativos
            cargarGastos: function (page) {
                var self = this;
                axios.get('{{ route("finanzas.index") }}', {
                    params: {
                        tab: 'gastos',
                        page: page || 1,
                        desde: this.filtroGastos.desde,
                        hasta: this.filtroGastos.hasta,
                        categoria_id: this.filtroGastos.categoria_id,
                        buscar: this.filtroGastos.buscar
                    }
                }).then(function (res) {
                    if (res.data && res.data.gastos) {
                        self.gastosList = res.data.gastos.data || [];
                    }
                });
            },
            abrirModalNuevoGasto: function () {
                this.formGasto.concepto = '';
                this.formGasto.monto = '';
                this.formGasto.nro_comprobante = '';
                $('#modalNuevoGasto').modal('show');
            },
            guardarGasto: function () {
                if (!this.formGasto.concepto || !this.formGasto.monto) {
                    Swal.fire('Atención', 'Completá el concepto y el monto del gasto.', 'warning');
                    return;
                }
                var self = this;
                this.guardandoGasto = true;
                axios.post('{{ route("finanzas.gasto.store") }}', this.formGasto)
                    .then(function (res) {
                        if (res.data && res.data.success) {
                            $('#modalNuevoGasto').modal('hide');
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: res.data.message,
                                showConfirmButton: false,
                                timer: 2200
                            });
                            self.cargarGastos(1);
                            if (self.permisos.ver_resultados) {
                                self.cargarEstadoResultados();
                            }
                        }
                    })
                    .catch(function (err) {
                        var msg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : 'No se pudo registrar el gasto.';
                        Swal.fire('Error', msg, 'error');
                    })
                    .finally(function () {
                        self.guardandoGasto = false;
                    });
            },
            anularGasto: function (g) {
                var self = this;
                Swal.fire({
                    title: '¿Anular gasto ' + g.codigo_gasto + '?',
                    text: g.concepto + ' (Gs. ' + this.formatNumber(g.monto) + ')',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    confirmButtonText: 'Sí, anular'
                }).then(function (r) {
                    if (r.value) {
                        axios.delete('{{ url("finanzas/gasto") }}/' + g.id).then(function (res) {
                            if (res.data && res.data.success) {
                                self.cargarGastos(1);
                                self.cargarEstadoResultados();
                                Swal.fire('Anulado', res.data.message, 'success');
                            }
                        });
                    }
                });
            },
            abrirModalCategoria: function () {
                this.formCategoria.nombre = '';
                $('#modalCategoriaGasto').modal('show');
            },
            guardarCategoria: function () {
                if (!this.formCategoria.nombre) return;
                var self = this;
                axios.post('{{ route("finanzas.categoria.store") }}', this.formCategoria).then(function (res) {
                    if (res.data && res.data.success) {
                        self.categorias.push(res.data.categoria);
                        self.formGasto.categoria_id = res.data.categoria.id;
                        $('#modalCategoriaGasto').modal('hide');
                    }
                });
            },

            // Cuentas a Pagar
            cargarCtasPagar: function (page) {
                var self = this;
                axios.get('{{ route("finanzas.index") }}', {
                    params: {
                        tab: 'ctas_pagar',
                        page: page || 1,
                        proveedor_cod: this.filtroCtas.proveedor_cod,
                        estado: this.filtroCtas.estado,
                        buscar: this.filtroCtas.buscar
                    }
                }).then(function (res) {
                    if (res.data && res.data.cuentas) {
                        self.ctasPagarList = res.data.cuentas.data || [];
                        if (res.data.kpis_ctas_pagar) {
                            self.kpisCtasPagar = res.data.kpis_ctas_pagar;
                        }
                    }
                });
            },
            esVencida: function (c) {
                if (!c.fecha_vencimiento || c.monto_saldo <= 0 || c.estado === 'PAGADO' || c.estado === 'ANULADO') return false;
                return c.fecha_vencimiento < '{{ date("Y-m-d") }}';
            },
            abrirModalNuevaCtaPagar: function () {
                this.formCta.nro_factura_proveedor = '';
                this.formCta.concepto = '';
                this.formCta.monto_total = '';
                this.formCta.cantidad_cuotas = 1;
                $('#modalNuevaCtaPagar').modal('show');
            },
            guardarCtaPagar: function () {
                if (!this.formCta.PROVEEDOR_cod || !this.formCta.monto_total || !this.formCta.concepto) {
                    Swal.fire('Atención', 'Seleccioná el proveedor, concepto y monto total.', 'warning');
                    return;
                }
                var self = this;
                this.guardandoCta = true;
                axios.post('{{ route("finanzas.ctapagar.store") }}', this.formCta)
                    .then(function (res) {
                        if (res.data && res.data.success) {
                            $('#modalNuevaCtaPagar').modal('hide');
                            self.tabPrincipal = 'ctas_pagar';
                            self.cargarCtasPagar(1);
                            Swal.fire('Registrado', res.data.message, 'success');
                        }
                    })
                    .catch(function (err) {
                        var msg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : 'Error al registrar cuenta a pagar.';
                        Swal.fire('Error', msg, 'error');
                    })
                    .finally(function () {
                        self.guardandoCta = false;
                    });
            },
            abrirModalPagoCta: function (c) {
                this.ctaSeleccionada = c;
                this.formPagoCta.monto = Number(c.monto_saldo);
                this.formPagoCta.nro_recibo_comprobante = '';
                this.formPagoCta.observacion = 'Pago de factura ' + (c.nro_factura_proveedor || c.codigo_cta);
                $('#modalPagoCtaPagar').modal('show');
            },
            confirmarPagoCta: function () {
                if (!this.ctaSeleccionada || !this.formPagoCta.monto || this.formPagoCta.monto <= 0) return;
                var self = this;
                this.guardandoPagoCta = true;
                axios.post('{{ url("finanzas/cta-pagar") }}/' + this.ctaSeleccionada.id + '/pagar', this.formPagoCta)
                    .then(function (res) {
                        if (res.data && res.data.success) {
                            $('#modalPagoCtaPagar').modal('hide');
                            self.cargarCtasPagar(1);
                            Swal.fire('Pago Registrado', res.data.message, 'success');
                        }
                    })
                    .catch(function (err) {
                        var msg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : 'No se pudo registrar el pago.';
                        Swal.fire('Error', msg, 'error');
                    })
                    .finally(function () {
                        self.guardandoPagoCta = false;
                    });
            },
            verDetalleCta: function (id) {
                var self = this;
                axios.get('{{ url("finanzas/cta-pagar") }}/' + id).then(function (res) {
                    if (res.data && res.data.success) {
                        self.ctaDetalle = res.data.cuenta;
                        $('#modalDetalleCtaPagar').modal('show');
                    }
                });
            },
            anularCtaPagar: function (c) {
                var self = this;
                Swal.fire({
                    title: '¿Anular cuenta ' + c.codigo_cta + '?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    confirmButtonText: 'Sí, anular'
                }).then(function (r) {
                    if (r.value) {
                        axios.delete('{{ url("finanzas/cta-pagar") }}/' + c.id).then(function (res) {
                            if (res.data && res.data.success) {
                                self.cargarCtasPagar(1);
                                Swal.fire('Anulada', res.data.message, 'success');
                            }
                        });
                    }
                });
            },
            toggleModuloSuperadmin: function () {
                var self = this;
                axios.post('{{ route("finanzas.toggle_modulo") }}', {
                    activo: !this.moduloActivo ? 1 : 0
                }).then(function (res) {
                    if (res.data && res.data.success) {
                        self.moduloActivo = !!res.data.activo;
                        Swal.fire('Actualizado', res.data.message, 'success');
                    }
                });
            }
        },
        mounted: function () {
            if (this.permisos.ver_resultados) {
                this.cargarEstadoResultados();
            }
            this.cargarGastos(1);
            if (this.permisos.cuentas_pagar) {
                this.cargarCtasPagar(1);
            }
        }
    });

    activarMenu('m_finanzas', '');
</script>
@endsection
