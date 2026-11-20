@extends('layouts.app')
@section('title', 'Comisiones y Liquidaciones de Técnicos y Vendedores')

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
        grid-template-columns: repeat(auto-fit, minmax(185px, 1fr));
        gap: 0.85rem;
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
        transition: all 0.15s ease;
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
        font-size: 1.18rem;
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

    .mod-pill {
        font-size: 0.7rem;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 999px;
        text-transform: uppercase;
    }
    .mod-TALLER { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .mod-EQUIPO_IMEI { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .mod-VENTA_POS { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .mod-COBRANZA { background: #f3e8ff; color: #7e22ce; border: 1px solid #e9d5ff; }
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
                <i class="fas fa-percentage text-success"></i>
                Comisiones de Técnicos y Vendedores
                @if (!empty($esSuperAdministrador))
                    <span class="badge ml-2" :class="moduloActivo ? 'badge-success' : 'badge-danger'"
                          style="font-size: 0.7rem; cursor: pointer;"
                          title="Control Superadministrador: Clic para habilitar/deshabilitar el módulo"
                          @click="toggleModuloSuperusuario">
                        <i class="fas fa-crown mr-1"></i>@{{ moduloActivo ? 'Módulo Activo' : 'Módulo Inactivo' }}
                    </span>
                @endif
            </h3>
            <p>Cálculo y liquidación auditable de comisiones por Mano de Obra de Taller, Venta de Equipos por IMEI, Punto de Venta y Cobranzas.</p>
        </div>

        <div class="d-flex align-items-center flex-wrap" style="gap: 0.5rem;">
            <div class="nav-pills-custom">
                <button type="button" class="nav-pill-btn" :class="{ active: vistaActiva === 'liquidar' }" @click="cambiarVista('liquidar')">
                    <i class="fas fa-calculator"></i>
                    <span>Calcular / Liquidar</span>
                </button>
                <button type="button" class="nav-pill-btn" :class="{ active: vistaActiva === 'historial' }" @click="cambiarVista('historial')">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Liquidaciones Emitidas</span>
                </button>
                <button type="button" class="nav-pill-btn" :class="{ active: vistaActiva === 'tasas' }" @click="cambiarVista('tasas')" v-if="permisos.configurar_tasas">
                    <i class="fas fa-sliders-h"></i>
                    <span>Porcentajes por Empleado</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- VISTA 1: CALCULAR Y EMITIR LIQUIDACIÓN DE COMISIONES         -->
    <!-- ============================================================ -->
    <div v-show="vistaActiva === 'liquidar'">

        <!-- Barra de Parámetros de Cálculo -->
        <div class="card-pos">
            <div class="card-pos-body py-3">
                <div class="row align-items-end">
                    <div class="col-md-3 mb-2">
                        <label class="small font-weight-bold text-muted mb-1"><i class="fas fa-user-tie mr-1"></i> Técnico / Vendedor</label>
                        <select class="form-control form-control-sm font-weight-bold" v-model="calcFiltro.cod_usuarios" @change="calcularComisionesPendientes">
                            <option v-for="e in empleados" :key="e.cod_usuarios" :value="e.cod_usuarios">
                                @{{ e.nom_usuarios }} (@{{ e.nom_rol || e.user_usuarios }})
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="small font-weight-bold text-muted mb-1">Fecha Desde</label>
                        <input type="date" class="form-control form-control-sm" v-model="calcFiltro.desde">
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="small font-weight-bold text-muted mb-1">Fecha Hasta</label>
                        <input type="date" class="form-control form-control-sm" v-model="calcFiltro.hasta">
                    </div>

                    <div class="col-md-2 mb-2">
                        <label class="small font-weight-bold text-muted mb-1">Sucursal</label>
                        <select class="form-control form-control-sm" v-model="calcFiltro.suc_cod">
                            <option value="0">Todas las sucursales</option>
                            <option v-for="s in sucursales" :key="s.suc_cod" :value="s.suc_cod">@{{ s.suc_desc }}</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-2 d-flex" style="gap: 6px;">
                        <button type="button" class="btn btn-sm btn-success btn-block font-cairo font-weight-bold"
                                :disabled="calculandoPendientes"
                                @click="calcularComisionesPendientes">
                            <i class="fas mr-1" :class="calculandoPendientes ? 'fa-spinner fa-spin' : 'fa-sync-alt'"></i>
                            Calcular Pendientes
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPIs del Cálculo Actual -->
        <div class="kpi-grid">
            <div class="kpi-box" style="cursor: pointer;" @click="filtroModuloCalc = 'TALLER'">
                <div class="kpi-icon" style="background: #fef3c7; color: #d97706;">
                    <i class="fas fa-tools"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo">Gs. @{{ formatNumber(resumenCalc.comision_taller) }}</div>
                    <div class="kpi-lbl">1. Taller (@{{ resumenCalc.cant_taller }} rep.)</div>
                </div>
            </div>

            <div class="kpi-box" style="cursor: pointer;" @click="filtroModuloCalc = 'EQUIPO_IMEI'">
                <div class="kpi-icon" style="background: #e0f2fe; color: #0284c7;">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo">Gs. @{{ formatNumber(resumenCalc.comision_imei) }}</div>
                    <div class="kpi-lbl">2. Equipos IMEI (@{{ resumenCalc.cant_imei }})</div>
                </div>
            </div>

            <div class="kpi-box" style="cursor: pointer;" @click="filtroModuloCalc = 'VENTA_POS'">
                <div class="kpi-icon" style="background: #dcfce7; color: #15803d;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo">Gs. @{{ formatNumber(resumenCalc.comision_ventas) }}</div>
                    <div class="kpi-lbl">3. Ventas POS (@{{ resumenCalc.cant_ventas }})</div>
                </div>
            </div>

            <div class="kpi-box" style="cursor: pointer;" @click="filtroModuloCalc = 'COBRANZA'">
                <div class="kpi-icon" style="background: #f3e8ff; color: #7e22ce;">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo">Gs. @{{ formatNumber(resumenCalc.comision_cobranzas) }}</div>
                    <div class="kpi-lbl">4. Cobranzas (@{{ resumenCalc.cant_cobranzas }})</div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Tabla de Operaciones Pendientes de Liquidar -->
            <div class="col-lg-8">
                <div class="card-pos">
                    <div class="card-pos-header">
                        <h5 class="card-pos-title font-cairo">
                            <i class="fas fa-list-check text-success"></i>
                            Operaciones Pendientes de Liquidar (@{{ itemsPendientesFiltrados.length }})
                        </h5>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn" :class="filtroModuloCalc === 'TODOS' ? 'btn-success' : 'btn-outline-secondary'" @click="filtroModuloCalc = 'TODOS'">Todos</button>
                            <button type="button" class="btn" :class="filtroModuloCalc === 'TALLER' ? 'btn-success' : 'btn-outline-secondary'" @click="filtroModuloCalc = 'TALLER'">Taller</button>
                            <button type="button" class="btn" :class="filtroModuloCalc === 'EQUIPO_IMEI' ? 'btn-success' : 'btn-outline-secondary'" @click="filtroModuloCalc = 'EQUIPO_IMEI'">IMEI</button>
                            <button type="button" class="btn" :class="filtroModuloCalc === 'VENTA_POS' ? 'btn-success' : 'btn-outline-secondary'" @click="filtroModuloCalc = 'VENTA_POS'">POS</button>
                            <button type="button" class="btn" :class="filtroModuloCalc === 'COBRANZA' ? 'btn-success' : 'btn-outline-secondary'" @click="filtroModuloCalc = 'COBRANZA'">Cobros</button>
                        </div>
                    </div>

                    <div class="table-responsive" style="max-height: 460px; overflow-y: auto;">
                        <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.82rem;">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 35px;" class="text-center">
                                        <input type="checkbox" :checked="todosSeleccionados" @change="toggleSeleccionarTodos($event.target.checked)">
                                    </th>
                                    <th>Módulo</th>
                                    <th>Comprobante</th>
                                    <th>Descripción</th>
                                    <th class="text-right">Base Comis.</th>
                                    <th class="text-center">%</th>
                                    <th class="text-right" style="width: 120px;">Comisión (Gs.)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="calculandoPendientes">
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-spinner fa-spin mr-1 text-success"></i> Calculando operaciones pendientes...
                                    </td>
                                </tr>
                                <tr v-else-if="!itemsPendientesFiltrados.length">
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        No hay operaciones pendientes de liquidación para este empleado en el período seleccionado.
                                    </td>
                                </tr>
                                <tr v-else v-for="(it, idx) in itemsPendientesFiltrados" :key="it.origen_modulo + '-' + it.referencia_id">
                                    <td class="text-center">
                                        <input type="checkbox" v-model="it.incluido">
                                    </td>
                                    <td>
                                        <span class="mod-pill" :class="'mod-' + it.origen_modulo">@{{ it.origen_modulo }}</span>
                                    </td>
                                    <td class="font-monospace font-weight-bold">
                                        @{{ it.codigo_comprobante }}
                                        <small class="d-block text-muted">@{{ formatearFecha(it.fecha_operacion) }}</small>
                                    </td>
                                    <td>@{{ it.descripcion }}</td>
                                    <td class="text-right font-cairo">Gs. @{{ formatNumber(it.base_comisionable) }}</td>
                                    <td class="text-center font-cairo">@{{ it.porcentaje_aplicado }}%</td>
                                    <td class="text-right">
                                        <input type="number" class="form-control form-control-sm text-right font-cairo font-weight-bold"
                                               v-model.number="it.monto_comision" min="0" step="500">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Panel Derecho: Cierre y Pago de Liquidación -->
            <div class="col-lg-4">
                <div class="card-pos">
                    <div class="card-pos-header" style="background: var(--dash-primary-light);">
                        <h5 class="card-pos-title font-cairo text-success">
                            <i class="fas fa-cash-register"></i> Cierre y Pago de Liquidación
                        </h5>
                    </div>
                    <div class="card-pos-body">
                        <div v-if="descuentoGarantiasSugerido > 0" class="alert alert-warning py-2 px-3 mb-3 small">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Se detectaron costos por garantías de taller en el período por <strong>Gs. @{{ formatNumber(descuentoGarantiasSugerido) }}</strong>.
                            <button type="button" class="btn btn-xs btn-warning font-weight-bold ml-1"
                                    @click="aplicarDeduccionGarantia">
                                Aplicar Descuento
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-2">
                                <label class="small font-weight-bold text-success mb-1">(+) Bono / Premio (Gs.)</label>
                                <input type="number" class="form-control form-control-sm font-cairo font-weight-bold"
                                       v-model.number="formLiquidacion.bono_premio" min="0" step="5000">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="small font-weight-bold text-muted mb-1">Motivo del Bono</label>
                                <input type="text" class="form-control form-control-sm"
                                       v-model="formLiquidacion.motivo_bono" placeholder="Ej: Meta alcanzada">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-2">
                                <label class="small font-weight-bold text-danger mb-1">(-) Adelanto / Vale (Gs.)</label>
                                <input type="number" class="form-control form-control-sm font-cairo font-weight-bold"
                                       v-model.number="formLiquidacion.descuento_adelanto" min="0" step="5000">
                            </div>
                            <div class="col-6 mb-2">
                                <label class="small font-weight-bold text-muted mb-1">Motivo Descuento</label>
                                <input type="text" class="form-control form-control-sm"
                                       v-model="formLiquidacion.motivo_descuento" placeholder="Ej: Vale / Garantía">
                            </div>
                        </div>

                        <div class="p-3 rounded border my-3 font-cairo" style="background: var(--dash-panel-bg);">
                            <div class="d-flex justify-content-between py-1">
                                <span>Subtotal Comisiones (@{{ resumenCalc.total_items }} ítems):</span>
                                <strong>Gs. @{{ formatNumber(resumenCalc.subtotal) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between py-1 text-success" v-if="formLiquidacion.bono_premio > 0">
                                <span>(+) Bono / Premio:</span>
                                <strong>+ Gs. @{{ formatNumber(formLiquidacion.bono_premio) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between py-1 text-danger" v-if="formLiquidacion.descuento_adelanto > 0">
                                <span>(-) Adelantos / Descuentos:</span>
                                <strong>- Gs. @{{ formatNumber(formLiquidacion.descuento_adelanto) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between pt-2 mt-1 border-top h5 mb-0 font-weight-bold text-success">
                                <span>NETO A PAGAR:</span>
                                <span>Gs. @{{ formatNumber(netoLiquidacionCalculado) }}</span>
                            </div>
                        </div>

                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="chkCajaLiquidacion" v-model="formLiquidacion.registrar_caja">
                            <label class="custom-control-label small font-weight-bold" for="chkCajaLiquidacion">
                                Registrar pago como Salida en Caja Abierta
                            </label>
                        </div>

                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted mb-1">Observaciones del Recibo</label>
                            <input type="text" class="form-control form-control-sm" v-model="formLiquidacion.observaciones"
                                   placeholder="Nota opcional impresa en el recibo...">
                        </div>

                        <button v-if="permisos.generar_liquidacion"
                                type="button"
                                class="btn btn-success btn-block font-cairo font-weight-bold py-2"
                                :disabled="guardandoLiquidacion || resumenCalc.total_items === 0"
                                @click="emitirLiquidacion">
                            <i class="fas mr-1" :class="guardandoLiquidacion ? 'fa-spinner fa-spin' : 'fa-print'"></i>
                            Emitir Liquidación e Imprimir Recibo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- VISTA 2: HISTORIAL DE LIQUIDACIONES EMITIDAS                 -->
    <!-- ============================================================ -->
    <div v-show="vistaActiva === 'historial'">
        <div class="kpi-grid">
            <div class="kpi-box">
                <div class="kpi-icon" style="background: #dcfce7; color: #15803d;">
                    <i class="fas fa-money-check-alt"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo">Gs. @{{ formatNumber(kpisMes.total_pagado_mes) }}</div>
                    <div class="kpi-lbl">Total Pagado (Mes)</div>
                </div>
            </div>
            <div class="kpi-box">
                <div class="kpi-icon" style="background: #e0f2fe; color: #0284c7;">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo">@{{ kpisMes.liquidaciones_mes }}</div>
                    <div class="kpi-lbl">Liquidaciones (Mes)</div>
                </div>
            </div>
            <div class="kpi-box">
                <div class="kpi-icon" style="background: #fef3c7; color: #d97706;">
                    <i class="fas fa-tools"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo">Gs. @{{ formatNumber(kpisMes.total_taller_mes) }}</div>
                    <div class="kpi-lbl">Comisiones Taller (Mes)</div>
                </div>
            </div>
            <div class="kpi-box">
                <div class="kpi-icon" style="background: #f3e8ff; color: #7e22ce;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div>
                    <div class="kpi-num font-cairo">Gs. @{{ formatNumber(kpisMes.total_ventas_mes) }}</div>
                    <div class="kpi-lbl">Comisiones Ventas/IMEI</div>
                </div>
            </div>
        </div>

        <div class="card-pos">
            <div class="card-pos-header">
                <h5 class="card-pos-title font-cairo">
                    <i class="fas fa-history text-primary"></i> Historial de Liquidaciones de Comisiones Emitidas
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.84rem;">
                    <thead class="bg-light">
                        <tr>
                            <th>Código</th>
                            <th>Emisión</th>
                            <th>Técnico / Vendedor</th>
                            <th>Período Liquidado</th>
                            <th class="text-right">Taller</th>
                            <th class="text-right">Ventas / IMEI / Cob.</th>
                            <th class="text-right">Bonos / Desc.</th>
                            <th class="text-right">Neto Pagado</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="!liquidaciones.length">
                            <td colspan="10" class="text-center py-4 text-muted">No se han emitido liquidaciones aún.</td>
                        </tr>
                        <tr v-else v-for="l in liquidaciones" :key="l.id">
                            <td class="font-weight-bold font-cairo text-primary">@{{ l.codigo_liquidacion }}</td>
                            <td>@{{ formatearFecha(l.fecha_liquidacion) }}</td>
                            <td>
                                <div class="font-weight-bold">@{{ l.empleado ? l.empleado.nom_usuarios : '-' }}</div>
                                <small class="text-muted">@{{ l.detalles_count || 0 }} operaciones</small>
                            </td>
                            <td>@{{ formatearSoloFecha(l.fecha_desde) }} al @{{ formatearSoloFecha(l.fecha_hasta) }}</td>
                            <td class="text-right font-cairo">Gs. @{{ formatNumber(l.comision_taller) }}</td>
                            <td class="text-right font-cairo">Gs. @{{ formatNumber(Number(l.comision_ventas) + Number(l.comision_imei) + Number(l.comision_cobranzas)) }}</td>
                            <td class="text-right font-cairo">
                                <span v-if="l.bono_premio > 0" class="text-success d-block">+Gs. @{{ formatNumber(l.bono_premio) }}</span>
                                <span v-if="l.descuento_adelanto > 0" class="text-danger d-block">-Gs. @{{ formatNumber(l.descuento_adelanto) }}</span>
                                <span v-if="l.bono_premio == 0 && l.descuento_adelanto == 0" class="text-muted">-</span>
                            </td>
                            <td class="text-right font-cairo font-weight-bold text-success">
                                Gs. @{{ formatNumber(l.total_neto_pagar) }}
                            </td>
                            <td class="text-center">
                                <span class="badge" :class="l.estado === 'PAGADO' ? 'badge-success' : 'badge-secondary'">@{{ l.estado }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary" @click="imprimirReciboLiquidacion(l.id)" title="Imprimir Recibo Oficial A4">
                                        <i class="fas fa-print"></i>
                                    </button>
                                    <button v-if="permisos.del && l.estado !== 'ANULADO'" type="button" class="btn btn-outline-danger" @click="anularLiquidacion(l)" title="Anular y liberar operaciones">
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

    <!-- ============================================================ -->
    <!-- VISTA 3: CONFIGURACIÓN DE PORCENTAJES POR TÉCNICO / VENDEDOR -->
    <!-- ============================================================ -->
    <div v-show="vistaActiva === 'tasas'">
        <div class="card-pos">
            <div class="card-pos-header">
                <h5 class="card-pos-title font-cairo">
                    <i class="fas fa-user-cog text-primary"></i> Esquema de Porcentajes y Bases de Comisión por Empleado
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0" style="font-size: 0.82rem;">
                    <thead class="bg-light">
                        <tr>
                            <th>Empleado / Rol</th>
                            <th>1. Servicio Técnico (Taller)</th>
                            <th>2. Equipos IMEI / Plan Canje</th>
                            <th>3. Ventas POS (Contado / Créd.)</th>
                            <th>4. Cobranzas Cuotas</th>
                            <th class="text-center" style="width: 110px;">Guardar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="e in empleados" :key="e.cod_usuarios" v-if="configsMap[e.cod_usuarios]">
                            <td>
                                <div class="font-weight-bold">@{{ e.nom_usuarios }}</div>
                                <span class="badge badge-light border">@{{ e.nom_rol || e.user_usuarios }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center mb-1" style="gap: 6px;">
                                    <input type="checkbox" v-model="configsMap[e.cod_usuarios].comisiona_taller">
                                    <div class="input-group input-group-sm" style="width: 95px;">
                                        <input type="number" class="form-control font-cairo font-weight-bold" v-model.number="configsMap[e.cod_usuarios].porc_taller" min="0" max="100" step="0.5">
                                        <div class="input-group-append"><span class="input-group-text">%</span></div>
                                    </div>
                                    <select class="form-control form-control-sm" v-model="configsMap[e.cod_usuarios].base_taller">
                                        <option value="MANO_OBRA">s/ Mano de Obra</option>
                                        <option value="UTILIDAD_NETA">s/ Utilidad Neta</option>
                                        <option value="TOTAL_ORDEN">s/ Total Orden</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center mb-1" style="gap: 6px;">
                                    <input type="checkbox" v-model="configsMap[e.cod_usuarios].comisiona_imei">
                                    <div class="input-group input-group-sm" style="width: 95px;">
                                        <input type="number" class="form-control font-cairo font-weight-bold" v-model.number="configsMap[e.cod_usuarios].porc_imei" min="0" max="100" step="0.5">
                                        <div class="input-group-append"><span class="input-group-text">%</span></div>
                                    </div>
                                    <select class="form-control form-control-sm" v-model="configsMap[e.cod_usuarios].base_imei">
                                        <option value="UTILIDAD_NETA">s/ Ganancia</option>
                                        <option value="PRECIO_VENTA">s/ Precio Venta</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center" style="gap: 6px;">
                                    <input type="checkbox" v-model="configsMap[e.cod_usuarios].comisiona_ventas">
                                    <div>
                                        <small class="text-muted d-block">Contado %</small>
                                        <input type="number" class="form-control form-control-sm font-cairo" style="width: 75px;"
                                               v-model.number="configsMap[e.cod_usuarios].porc_venta_contado" min="0" max="100" step="0.5">
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Crédito %</small>
                                        <input type="number" class="form-control form-control-sm font-cairo" style="width: 75px;"
                                               v-model.number="configsMap[e.cod_usuarios].porc_venta_credito" min="0" max="100" step="0.5">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center" style="gap: 6px;">
                                    <input type="checkbox" v-model="configsMap[e.cod_usuarios].comisiona_cobranzas">
                                    <div class="input-group input-group-sm" style="width: 95px;">
                                        <input type="number" class="form-control font-cairo font-weight-bold"
                                               v-model.number="configsMap[e.cod_usuarios].porc_cobranzas" min="0" max="100" step="0.5">
                                        <div class="input-group-append"><span class="input-group-text">%</span></div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-success font-weight-bold"
                                        @click="guardarConfigEmpleado(e.cod_usuarios)">
                                    <i class="fas fa-save mr-1"></i> Guardar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
            moduloActivo: {{ !empty($comisionConfig['activo']) ? 'true' : 'false' }},
            vistaActiva: 'liquidar', // liquidar | historial | tasas
            empleados: @json($empleados),
            configsMap: @json($configsUsuarios),
            sucursales: @json($sucursales),
            permisos: @json($permisosModulo),
            sucursalActual: {{ (int) ($sucursalActual ?? 1) }},

            // Parámetros de Cálculo
            calculandoPendientes: false,
            filtroModuloCalc: 'TODOS',
            calcFiltro: {
                cod_usuarios: {{ $empleados->first() ? (int) $empleados->first()->cod_usuarios : (int) Auth::user()->cod_usuarios }},
                desde: '{{ date("Y-m-01") }}',
                hasta: '{{ date("Y-m-d") }}',
                suc_cod: '0'
            },
            itemsPendientes: [],
            descuentoGarantiasSugerido: 0,

            // Formulario de Cierre de Liquidación
            guardandoLiquidacion: false,
            formLiquidacion: {
                bono_premio: 0,
                motivo_bono: '',
                descuento_adelanto: 0,
                motivo_descuento: '',
                registrar_caja: true,
                observaciones: ''
            },

            // Historial de Liquidaciones
            liquidaciones: [],
            kpisMes: {
                total_pagado_mes: 0,
                liquidaciones_mes: 0,
                total_taller_mes: 0,
                total_ventas_mes: 0
            }
        },
        computed: {
            itemsPendientesFiltrados: function () {
                var f = this.filtroModuloCalc;
                if (f === 'TODOS') return this.itemsPendientes;
                return this.itemsPendientes.filter(function (x) {
                    return x.origen_modulo === f;
                });
            },
            todosSeleccionados: function () {
                var list = this.itemsPendientesFiltrados;
                if (!list.length) return false;
                return list.every(function (x) { return !!x.incluido; });
            },
            resumenCalc: function () {
                var r = {
                    cant_taller: 0, comision_taller: 0,
                    cant_imei: 0, comision_imei: 0,
                    cant_ventas: 0, comision_ventas: 0,
                    cant_cobranzas: 0, comision_cobranzas: 0,
                    total_items: 0, subtotal: 0
                };
                this.itemsPendientes.forEach(function (it) {
                    if (!it.incluido) return;
                    var c = Math.max(0, Number(it.monto_comision) || 0);
                    r.total_items++;
                    r.subtotal += c;
                    if (it.origen_modulo === 'TALLER') {
                        r.cant_taller++;
                        r.comision_taller += c;
                    } else if (it.origen_modulo === 'EQUIPO_IMEI') {
                        r.cant_imei++;
                        r.comision_imei += c;
                    } else if (it.origen_modulo === 'COBRANZA') {
                        r.cant_cobranzas++;
                        r.comision_cobranzas += c;
                    } else {
                        r.cant_ventas++;
                        r.comision_ventas += c;
                    }
                });
                return r;
            },
            netoLiquidacionCalculado: function () {
                var sub = this.resumenCalc.subtotal;
                var b = Math.max(0, Number(this.formLiquidacion.bono_premio) || 0);
                var d = Math.max(0, Number(this.formLiquidacion.descuento_adelanto) || 0);
                return Math.max(0, (sub + b) - d);
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
                axios.post('{{ route("comision.toggle_modulo") }}', {
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
                if (vista === 'liquidar') {
                    this.calcularComisionesPendientes();
                } else if (vista === 'historial') {
                    this.cargarHistorialLiquidaciones();
                }
            },

            toggleSeleccionarTodos: function (checked) {
                this.itemsPendientesFiltrados.forEach(function (it) {
                    it.incluido = checked;
                });
            },

            calcularComisionesPendientes: function () {
                var self = this;
                this.calculandoPendientes = true;
                axios.get('{{ route("comision.calcular") }}', {
                    params: this.calcFiltro
                }).then(function (res) {
                    if (res.data && res.data.success) {
                        self.itemsPendientes = res.data.items || [];
                        self.descuentoGarantiasSugerido = Number(res.data.descuento_garantias_sugerido) || 0;
                    }
                }).finally(function () {
                    self.calculandoPendientes = false;
                });
            },

            aplicarDeduccionGarantia: function () {
                this.formLiquidacion.descuento_adelanto = this.descuentoGarantiasSugerido;
                this.formLiquidacion.motivo_descuento = 'Deducción por garantías de taller';
            },

            emitirLiquidacion: function () {
                var seleccionados = this.itemsPendientes.filter(function (x) { return !!x.incluido; });
                if (!seleccionados.length) {
                    Swal.fire('Atención', 'Seleccioná al menos una operación para liquidar.', 'warning');
                    return;
                }

                var self = this;
                this.guardandoLiquidacion = true;

                axios.post('{{ route("comision.liquidar") }}', {
                    cod_usuarios: this.calcFiltro.cod_usuarios,
                    suc_cod: this.calcFiltro.suc_cod !== '0' ? this.calcFiltro.suc_cod : this.sucursalActual,
                    fecha_desde: this.calcFiltro.desde,
                    fecha_hasta: this.calcFiltro.hasta,
                    bono_premio: this.formLiquidacion.bono_premio,
                    motivo_bono: this.formLiquidacion.motivo_bono,
                    descuento_adelanto: this.formLiquidacion.descuento_adelanto,
                    motivo_descuento: this.formLiquidacion.motivo_descuento,
                    registrar_caja: this.formLiquidacion.registrar_caja,
                    observaciones: this.formLiquidacion.observaciones,
                    items: seleccionados
                }).then(function (res) {
                    if (res.data && res.data.success) {
                        window.open('{{ url("comisiones/liquidacion") }}/' + res.data.id + '/pdf?autoprint=1', '_blank');
                        Swal.fire('¡Liquidación Emitida!', res.data.message, 'success');
                        self.formLiquidacion.bono_premio = 0;
                        self.formLiquidacion.motivo_bono = '';
                        self.formLiquidacion.descuento_adelanto = 0;
                        self.formLiquidacion.motivo_descuento = '';
                        self.formLiquidacion.observaciones = '';
                        self.calcularComisionesPendientes();
                        self.cargarHistorialLiquidaciones();
                    }
                }).catch(function (err) {
                    var msg = (err.response && err.response.data && err.response.data.message) || 'Error al emitir liquidación.';
                    Swal.fire('Error', msg, 'error');
                }).finally(function () {
                    self.guardandoLiquidacion = false;
                });
            },

            cargarHistorialLiquidaciones: function () {
                var self = this;
                axios.get('{{ route("comision.index") }}')
                    .then(function (res) {
                        if (res.data) {
                            if (res.data.liquidaciones) {
                                self.liquidaciones = res.data.liquidaciones.data || [];
                            }
                            if (res.data.kpis) {
                                self.kpisMes = res.data.kpis;
                            }
                        }
                    });
            },

            imprimirReciboLiquidacion: function (id) {
                window.open('{{ url("comisiones/liquidacion") }}/' + id + '/pdf?autoprint=1', '_blank');
            },

            anularLiquidacion: function (l) {
                var self = this;
                Swal.fire({
                    title: '¿Anular Liquidación ' + l.codigo_liquidacion + '?',
                    text: 'Las operaciones incluidas quedarán liberadas para volver a calcularse.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    confirmButtonText: 'Sí, anular',
                    cancelButtonText: 'Cancelar'
                }).then(function (r) {
                    if (r.value) {
                        axios.delete('{{ url("comisiones/liquidacion") }}/' + l.id)
                            .then(function (res) {
                                Swal.fire('Anulada', res.data.message, 'success');
                                self.cargarHistorialLiquidaciones();
                                self.calcularComisionesPendientes();
                            });
                    }
                });
            },

            guardarConfigEmpleado: function (codUsuario) {
                var cfg = this.configsMap[codUsuario];
                if (!cfg) return;
                axios.put('{{ url("comisiones/config") }}/' + codUsuario, cfg)
                    .then(function (res) {
                        if (res.data && res.data.success) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: res.data.message,
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    });
            }
        },
        mounted: function () {
            this.calcularComisionesPendientes();
            this.cargarHistorialLiquidaciones();
            activarMenu('m_comision', '');
        }
    });
</script>
@endsection
