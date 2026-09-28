@extends('layouts.app')
@section('title', 'Gestionar Cobros')
@section('style')
    <link href="{{ asset('css/icheck-bootstrap.min.css') }}" rel="stylesheet">
    <style type="text/css">
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

        /* Botones POS */
        .btn-pos-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.55rem 1.25rem;
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
        .btn-pos-primary:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }
        .btn-pos-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.55rem 1rem;
            background: var(--dash-card-bg);
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

        /* Cards */
        .table-card {
            background: var(--dash-card-bg);
            border: 1px solid var(--dash-border);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }
        .table-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.9rem 1.25rem;
            background: #fdfdfd;
            border-bottom: 1px solid var(--dash-border);
        }

        /* Tablas */
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
            padding: 0.85rem 1rem;
            vertical-align: middle;
            white-space: nowrap;
        }
        .table-custom tbody td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--dash-border);
            color: var(--dash-text-main);
            font-size: 0.9rem;
        }
        .table-custom tbody tr {
            transition: background-color 0.15s;
        }
        .table-custom tbody tr:hover {
            background-color: var(--dash-primary-light);
        }

        /* Badges */
        .badge-stock-in {
            background-color: #dcfce7;
            color: #166534;
            font-weight: 700;
            border: 1px solid #bbf7d0;
            border-radius: 20px;
            padding: 4px 10px;
            font-size: 0.78rem;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }
        .badge-stock-out {
            background-color: #fee2e2;
            color: #991b1b;
            font-weight: 700;
            border: 1px solid #fecaca;
            border-radius: 20px;
            padding: 4px 10px;
            font-size: 0.78rem;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }
        .badge-barcode {
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, Courier, monospace;
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 3px 8px;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }
        .badge-section {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
            font-weight: 600;
            padding: 0.25rem 0.55rem;
            border-radius: 6px;
            font-size: 0.78rem;
            white-space: nowrap;
        }

        /* Buscador & Prepend */
        .search-prepend {
            background-color: var(--dash-card-bg) !important;
            border-color: var(--dash-border) !important;
            color: var(--dash-text-muted) !important;
            border-radius: 8px 0 0 8px !important;
        }
        .search-input {
            background-color: var(--dash-card-bg) !important;
            border-color: var(--dash-border) !important;
            color: var(--dash-text-main) !important;
            font-size: 0.95rem;
        }
        .search-input:focus {
            background-color: var(--dash-card-bg) !important;
            border-color: var(--dash-primary) !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2) !important;
        }

        /* Botón Acción Mini */
        .btn-action-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid var(--dash-border);
            background: var(--dash-card-bg);
            color: var(--dash-text-muted);
            transition: all 0.15s;
            cursor: pointer;
        }
        .btn-action-icon:hover {
            background: var(--dash-primary-light);
            color: var(--dash-primary);
            border-color: var(--dash-primary-border);
        }
        .btn-action-stock-delete {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #dc2626;
            transition: all 0.15s;
            cursor: pointer;
        }
        .btn-action-stock-delete:hover {
            background: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
        }

        /* Grid Ficha Cliente */
        .cobro-client-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 0.75rem;
        }

        /* Cajas Resumen Liquidación */
        .cobro-settlement-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.75rem;
        }
        .settlement-box-card {
            background: #f8fafc;
            border: 1px solid var(--dash-border);
            border-radius: 10px;
            padding: 0.85rem 1rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
            height: 100%;
        }
        .settlement-hero-card {
            background: var(--dash-primary-light) !important;
            border-color: var(--dash-primary-border) !important;
        }

        .input-millares {
            font-size: 14pt;
            font-weight: bold;
        }

        /* Modo Oscuro Global */
        body.dark-mode .table-card { background: var(--dash-card-bg); border-color: var(--dash-border); }
        body.dark-mode .table-card-head { background: var(--dash-card-bg); border-color: var(--dash-border); color: var(--dash-text-main); }
        body.dark-mode .table-custom thead th { background: #111827 !important; color: var(--dash-text-muted) !important; border-color: var(--dash-border) !important; }
        body.dark-mode .table-custom tbody tr { background-color: var(--dash-card-bg) !important; }
        body.dark-mode .table-custom tbody tr:hover { background-color: var(--dash-primary-light) !important; }
        body.dark-mode .table-custom tbody td { border-color: var(--dash-border) !important; color: var(--dash-text-main) !important; }
        body.dark-mode .badge-barcode { background: #111827; color: #cbd5e1; border-color: #374151; }
        body.dark-mode .badge-section { background: #111827; color: #9ca3af; border-color: #374151; }
        body.dark-mode .badge-stock-in { background-color: rgba(16, 185, 129, 0.2) !important; color: #6ee7b7 !important; border-color: #059669 !important; }
        body.dark-mode .badge-stock-out { background-color: rgba(239, 68, 68, 0.2) !important; color: #fca5a5 !important; border-color: #dc2626 !important; }
        body.dark-mode .btn-pos-secondary { background: #111827 !important; border-color: var(--dash-border) !important; color: var(--dash-text-main) !important; }
        body.dark-mode .btn-pos-secondary:hover { background: var(--dash-primary-light) !important; color: #34d399 !important; }
        body.dark-mode .btn-action-icon { background: #111827; border-color: var(--dash-border); color: var(--dash-text-muted); }
        body.dark-mode .btn-action-icon:hover { background: var(--dash-primary-light); color: #34d399; }
        body.dark-mode .btn-action-stock-delete { background: rgba(239, 68, 68, 0.15) !important; border-color: rgba(239, 68, 68, 0.3) !important; color: #fca5a5 !important; }
        body.dark-mode .btn-action-stock-delete:hover { background: #dc2626 !important; color: #ffffff !important; }
        body.dark-mode .settlement-box-card { background: #111827 !important; border-color: #374151 !important; }
        body.dark-mode .settlement-hero-card { background: rgba(16, 185, 129, 0.15) !important; border-color: #059669 !important; }
        body.dark-mode .form-control { background-color: #111827 !important; border-color: var(--dash-border) !important; color: var(--dash-text-main) !important; }
        body.dark-mode .input-group-text { background-color: #111827 !important; border-color: var(--dash-border) !important; color: var(--dash-text-muted) !important; }
        body.dark-mode .modal-content { background-color: #1f2937 !important; color: var(--dash-text-main) !important; border-color: var(--dash-border) !important; }
        body.dark-mode .modal-header, body.dark-mode .modal-footer { border-color: var(--dash-border) !important; background-color: #111827 !important; }
    </style>

@endsection
@section('main')
<div class="container-fluid px-3 py-3" id="app" v-cloak>
    <!-- Cabecera estilo Dashboard Inicio -->
    <div class="dash-header">
        <div>
            <h1 class="dash-header-title font-cairo">
                <i class="fa-solid fa-hand-holding-dollar mr-2"></i>Gestión de Cobros
            </h1>
            <p class="dash-header-subtitle">
                Administración de cuentas corrientes, cobro de cuotas y liquidación de pagos
            </p>
        </div>
        <div class="dash-header-badges">
            <span class="badge" :class="caja.estado == 'ABIERTA' ? 'badge-stock-in' : 'badge-stock-out'" style="font-size: 0.85rem; padding: 6px 14px;">
                <i class="fa-solid fa-cash-register mr-1.5"></i>
                Caja @{{ caja.estado }} <template v-if="caja.nrooperacion">#@{{ caja.nrooperacion }}</template>
            </span>
            <span class="badge badge-section" style="font-size: 0.85rem; padding: 6px 12px;">
                <i class="fa-regular fa-calendar mr-1.5"></i>@{{ cobro.fecha ? formatFecha(cobro.fecha) : '' }}
            </span>
            <button v-if="cliente.id && cliente.id != 0" type="button" class="btn-pos-secondary btn-sm py-1.5 px-3" @click="cancelar" title="Limpiar y buscar otro cliente">
                <i class="fa-solid fa-rotate-left mr-1"></i> Cambiar Cliente
            </button>
        </div>
    </div>

    <!-- Tarjeta 1: Buscador y Ficha de Cliente -->
    <div class="table-card mb-4">
        <!-- Buscador Superior -->
        <div class="p-3 border-bottom" style="background: var(--dash-card-bg);">
            <div class="row align-items-center">
                <div class="col-12 col-lg-8">
                    <label class="small font-weight-bold text-muted mb-1">
                        <i class="fa-solid fa-magnifying-glass mr-1"></i> BUSCAR CLIENTE (POR C.I., RUC O NOMBRE)
                    </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text search-prepend">
                                <i class="fa-solid fa-user-tag text-muted"></i>
                            </span>
                        </div>
                        <input type="text"
                               v-model="txtbuscar"
                               tabindex="1"
                               @keyup.enter="buscar(false)"
                               class="form-control search-input font-weight-bold"
                               id="txtbuscar"
                               placeholder="Escribí el documento o nombre y presioná Enter..." />
                        <div class="input-group-append">
                            <button class="btn btn-pos-primary" type="button" @click="buscar(false)">
                                <template v-if="request.buscar">
                                    <span class="spinner-border spinner-border-sm mr-1" role="status"></span> Buscando...
                                </template>
                                <template v-else>
                                    <i class="fa-solid fa-magnifying-glass mr-1"></i> Buscar
                                </template>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 mt-3 mt-lg-0 d-flex align-items-center justify-content-lg-end">
                    <!-- Switch Mostrar Cuentas Canceladas -->
                    <div class="custom-control custom-switch" style="padding-left: 2.75rem;">
                        <input type="checkbox" class="custom-control-input" id="mostrarCanceladas" v-model="mostrarCuentasCanceladas">
                        <label class="custom-control-label font-weight-bold text-muted" for="mostrarCanceladas" style="cursor: pointer; user-select: none;">
                            Mostrar cuentas canceladas
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ficha del Cliente Activo -->
        <div class="p-3" v-if="cliente.id && cliente.id != 0" style="background: #fafbfe;">
            <div class="cobro-client-grid">
                <!-- Info Cliente -->
                <div class="settlement-box-card">
                    <div class="text-muted small font-weight-bold text-uppercase mb-1">
                        <i class="fa-solid fa-id-card mr-1 text-primary"></i> Cliente Seleccionado
                    </div>
                    <div class="font-cairo font-weight-bold" style="font-size: 1.15rem; color: var(--dash-text-main);">
                        @{{ cliente.nombre }}
                    </div>
                    <div class="mt-1 d-flex align-items-center flex-wrap gap-1">
                        <span class="badge-barcode mr-1">
                            <i class="fa fa-fingerprint mr-1 text-muted"></i>@{{ cliente.documento }}
                        </span>
                        <span class="badge-section">
                            <i class="fa fa-receipt mr-1 text-muted"></i>@{{ ctasFiltradas.length }} cuentas
                        </span>
                    </div>
                </div>

                <!-- Total Cobrado -->
                <div class="settlement-box-card">
                    <div class="text-muted small font-weight-bold text-uppercase mb-1">
                        <i class="fa-solid fa-circle-check mr-1 text-success"></i> Total Cobrado Histórico
                    </div>
                    <div class="font-cairo font-weight-bold" style="font-size: 1.3rem; color: #166534;">
                        Gs. @{{ format(totalCobradoFiltrado) }}
                    </div>
                    <small class="text-muted">Monto amortizado en cuentas</small>
                </div>

                <!-- Saldo Pendiente -->
                <div class="settlement-box-card" style="border-left: 4px solid #dc2626;">
                    <div class="text-muted small font-weight-bold text-uppercase mb-1">
                        <i class="fa-solid fa-circle-exclamation mr-1 text-danger"></i> Saldo Pendiente Total
                    </div>
                    <div class="font-cairo font-weight-bold" style="font-size: 1.3rem; color: #dc2626;">
                        Gs. @{{ format(totalSaldoFiltrado) }}
                    </div>
                    <small class="text-muted">Total exigible a liquidar</small>
                </div>

                <!-- Acción Cobro Parcial Directo -->
                <div class="settlement-box-card justify-content-center align-items-center text-center p-3" v-if="totalSaldoFiltrado > 0 && cuotasAcobrar.length == 0">
                    <button type="button" class="btn-pos-primary w-100 py-2.5" @click="modalCobroParcial">
                        <i class="fa-solid fa-money-bill-transfer mr-1"></i> Cobro Parcial Rápido
                    </button>
                    <small class="text-muted mt-1.5 d-block">Distribuir importe a las cuotas más antiguas</small>
                </div>
            </div>
        </div>

        <!-- Estado Inicial: Sin Cliente -->
        <div class="p-4 text-center text-muted" v-else>
            <div class="mb-2" style="font-size: 2.2rem; color: var(--dash-text-muted); opacity: 0.6;">
                <i class="fa-solid fa-user-magnifying-glass"></i>
            </div>
            <div class="font-weight-bold" style="color: var(--dash-text-main); font-size: 1.05rem;">Ningún cliente seleccionado</div>
            <p class="small text-muted mb-0">Escribí arriba el número de cédula, RUC o nombre del cliente y presioná Enter para consultar sus cuentas.</p>
        </div>
    </div>

    <!-- Tarjeta 2: Cuentas a Crédito del Cliente -->
    <div class="table-card mb-4" v-if="cliente.id && cliente.id != 0">
        <div class="table-card-head">
            <div class="d-flex align-items-center">
                <h6 class="font-weight-bold mb-0 font-cairo" style="color: var(--dash-text-main); font-size: 1.05rem;">
                    <i class="fa-solid fa-file-invoice-dollar mr-2 text-primary"></i> Cuentas a Crédito Registradas
                </h6>
                <span class="badge badge-section ml-2">@{{ ctasFiltradas.length }} venta(s)</span>
            </div>
            <div v-if="totalSaldoFiltrado > 0 && cuotasAcobrar.length == 0">
                <button type="button" class="btn-pos-secondary btn-sm" @click="modalCobroParcial">
                    <i class="fa-solid fa-hand-holding-dollar mr-1"></i> Cobro Parcial
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-custom mb-0" id="estado_cuenta">
                <thead>
                    <tr>
                        <th>N° Venta / Factura</th>
                        <th>Fecha Venta</th>
                        <th class="text-right">Total Venta</th>
                        <th class="text-center">Cuotas</th>
                        <th class="text-right">Cobrado</th>
                        <th class="text-right">Saldo Pendiente</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center" style="width: 140px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="ctasFiltradas.length == 0">
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fa-solid fa-box-open fa-2x mb-2 text-muted"></i>
                            <div class="font-weight-bold">No se encontraron cuentas para mostrar</div>
                            <small v-if="!mostrarCuentasCanceladas">Marcá la opción "Mostrar cuentas canceladas" si deseás ver el historial saldado.</small>
                        </td>
                    </tr>
                    <tr v-for="(c, index) in ctasFiltradas" :key="c.nro_fact_ventas">
                        <td>
                            <div class="font-weight-bold font-cairo" style="color: var(--dash-text-main);">
                                <i class="fa-solid fa-receipt text-muted mr-1.5"></i>#@{{ c.nro_fact_ventas }}
                            </div>
                        </td>
                        <td>
                            <span class="text-muted small">
                                <i class="fa-regular fa-calendar mr-1"></i>@{{ c.venta_fecha }}
                            </span>
                        </td>
                        <td class="text-right font-weight-bold font-cairo">
                            Gs. @{{ format(c.total) }}
                        </td>
                        <td class="text-center">
                            <span class="badge badge-section">
                                @{{ checkCantidad(c.pagada, c.nro_fact_ventas) }} de @{{ checkCantidad(c.cuotas, c.nro_fact_ventas) }} pagadas
                            </span>
                        </td>
                        <td class="text-right text-success font-weight-bold font-cairo">
                            Gs. @{{ format(c.cobrado) }}
                        </td>
                        <td class="text-right font-weight-bold font-cairo" :class="Number(c.saldo) > 0 ? 'text-danger' : 'text-muted'" style="font-size: 1rem;">
                            Gs. @{{ format(c.saldo) }}
                        </td>
                        <td class="text-center">
                            <span v-if="c.saldo == 0" class="badge-stock-in">
                                <i class="fa-solid fa-circle-check mr-1"></i>CANCELADO
                            </span>
                            <span v-else class="badge-stock-out">
                                <i class="fa-solid fa-clock mr-1"></i>PENDIENTE
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex align-items-center gap-1">
                                <button type="button"
                                        class="btn-pos-primary btn-sm py-1 px-2.5 mr-1"
                                        v-if="c.saldo > 0"
                                        @click="showCuotas(c.nro_fact_ventas)"
                                        title="Seleccionar cuotas para cobrar">
                                    <i class="fa-solid fa-list-check"></i>
                                </button>
                                <button type="button"
                                        class="btn-action-icon mr-1"
                                        @click="showDetalle(index)"
                                        title="Ver detalle de artículos de la venta">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                <a :href="'{{ url('documento/extractocuenta') }}/' + c.nro_fact_ventas"
                                   target="_blank"
                                   class="btn-action-icon"
                                   title="Imprimir extracto / estado de cuenta">
                                    <i class="fa-solid fa-print"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tarjeta 3: Cuotas Seleccionadas para Liquidar -->
    <div class="table-card mb-4" v-if="cuotasAcobrar.length > 0">
        <div class="table-card-head" style="border-top: 4px solid var(--dash-primary);">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-cart-arrow-down text-success mr-2 fa-lg"></i>
                <div>
                    <h6 class="font-weight-bold mb-0 font-cairo" style="color: var(--dash-text-main); font-size: 1.05rem;">
                        Cuotas a Liquidar en este Cobro
                    </h6>
                    <small class="text-muted">Verificá los importes y vencimientos antes de confirmar</small>
                </div>
            </div>
            <button type="button" class="btn btn-link btn-xs text-danger font-weight-bold" @click="limpiarCuotasAcobrar">
                <i class="fa-solid fa-trash-can mr-1"></i> Quitar todas
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th class="text-center"># Cuota</th>
                        <th># Venta</th>
                        <th>Vencimiento</th>
                        <th class="text-right">Monto Cuota</th>
                        <th class="text-center">Días Mora</th>
                        <th class="text-right">Interés</th>
                        <th class="text-right">Ya Cobrado</th>
                        <th class="text-right">Saldo Cuota</th>
                        <th class="text-right" style="background: var(--dash-primary-light);">A Cobrar</th>
                        <th class="text-center" style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(c, index) in cuotasAcobrar" :key="index">
                        <td class="text-center font-weight-bold font-cairo">
                            <span class="badge badge-section">Cuota @{{ checkCantidad(c.nro_cuotas, c.nro_fact_ventas) }}</span>
                        </td>
                        <td>
                            <span class="font-weight-bold font-cairo text-muted">#@{{ c.nro_fact_ventas }}</span>
                        </td>
                        <td>
                            <span class="text-muted small">
                                <i class="fa-regular fa-calendar mr-1"></i>@{{ formatFecha(c.fecha_venc) }}
                            </span>
                        </td>
                        <td class="text-right font-weight-bold font-cairo">
                            Gs. @{{ format(c.monto_cuota) }}
                        </td>
                        <td class="text-center">
                            <span :class="Number(diferenciaFecha(c.fecha_venc, c.monto_saldo, c.estado_interes)) > 0 ? 'badge-stock-out' : 'badge-section'">
                                @{{ diferenciaFecha(c.fecha_venc, c.monto_saldo, c.estado_interes) }}
                            </span>
                        </td>
                        <td class="text-right font-weight-bold font-cairo text-warning">
                            Gs. @{{ format(c.interes) }}
                        </td>
                        <td class="text-right text-muted small">
                            Gs. @{{ format(c.monto_cobrado) }}
                        </td>
                        <td class="text-right text-muted small">
                            Gs. @{{ format(c.monto_saldo) }}
                        </td>
                        <td class="text-right font-weight-bold font-cairo" style="background: var(--dash-primary-light); color: var(--dash-primary); font-size: 1rem;">
                            Gs. @{{ format(parseInt(c.acobrar) + c.interes) }}
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn-action-stock-delete" @click="quitarCuotaAcobrar(index)" title="Quitar esta cuota del cobro">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tarjeta 4: Panel de Liquidación y Finalización -->
    <div class="table-card mb-4" v-if="cliente.id && cliente.id != 0">
        <div class="table-card-head" style="background: var(--dash-card-bg);">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-calculator text-primary mr-2 fa-lg"></i>
                <div>
                    <h6 class="font-weight-bold mb-0 font-cairo" style="color: var(--dash-text-main); font-size: 1.05rem;">
                        Panel de Liquidación y Totales
                    </h6>
                    <small class="text-muted">Ajustá la fecha, condiciones de mora y confirmá la recaudación</small>
                </div>
            </div>
            <div>
                <span class="badge" :class="caja.estado == 'ABIERTA' ? 'badge-stock-in' : 'badge-stock-out'" style="font-size: 0.82rem;">
                    Caja: @{{ caja.estado }}
                </span>
            </div>
        </div>

        <div class="p-3">
            <!-- Fila de Parámetros de Cobro -->
            <div class="row align-items-center mb-3">
                <div class="col-12 col-md-3 mb-2 mb-md-0">
                    <label class="small font-weight-bold text-muted mb-1">
                        <i class="fa-regular fa-calendar mr-1"></i> FECHA DE COBRO
                    </label>
                    <input type="date"
                           id="fecha"
                           class="form-control font-weight-bold"
                           v-model="cobro.fecha"
                           style="border-radius: 8px;">
                </div>

                <div class="col-12 col-md-3 mb-2 mb-md-0">
                    <label class="small font-weight-bold text-muted mb-1">
                        <i class="fa-solid fa-percent mr-1"></i> COBRAR INTERÉS POR MORA
                    </label>
                    <select class="form-control font-weight-bold"
                            id="interes"
                            v-model="cobro.cobrarInteres"
                            @change="changeMontoInteres"
                            style="border-radius: 8px;">
                        <option :value="true">SÍ - Aplicar Interés</option>
                        <option :value="false">NO - Exonerar Interés</option>
                    </select>
                </div>

                <div class="col-12 col-md-6 mb-2 mb-md-0">
                    <label class="small font-weight-bold text-muted mb-1">
                        <i class="fa-solid fa-sliders mr-1"></i> ACCIONES RÁPIDAS DE INTERÉS
                    </label>
                    <div class="d-flex flex-wrap gap-1">
                        <button type="button" class="btn-pos-secondary btn-sm py-1.5 px-2.5 mr-1" @click="setInteres" title="Fijar importe manual de interés">
                            <i class="fa fa-pen-to-square mr-1 text-primary"></i> Monto Manual
                        </button>
                        <button type="button" class="btn-pos-secondary btn-sm py-1.5 px-2.5 mr-1" @click="cobro.isInteresFija = false; changeMontoInteres();" title="Recalcular con la tasa del sistema">
                            <i class="fa fa-rotate mr-1 text-warning"></i> Automático
                        </button>
                        <button type="button" class="btn-pos-secondary btn-sm py-1.5 px-2.5" @click="cobrarInteres" title="Cobrar únicamente el interés y cerrar liquidación">
                            <i class="fa fa-receipt mr-1 text-success"></i> Solo Interés
                        </button>
                    </div>
                </div>
            </div>

            <!-- Fila de Resumen de Montos -->
            <div class="cobro-settlement-grid mb-2">
                <!-- Total Cuotas -->
                <div class="settlement-box-card">
                    <label class="small font-weight-bold text-muted mb-1">
                        <i class="fa-solid fa-coins mr-1 text-primary"></i> TOTAL CUOTAS
                    </label>
                    <div class="font-cairo font-weight-bold" style="font-size: 1.25rem; color: var(--dash-text-main);">
                        Gs. @{{ totalCuota }}
                    </div>
                    <small class="text-muted">Importe neto de cuotas</small>
                </div>

                <!-- Total Interés -->
                <div class="settlement-box-card">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <label class="small font-weight-bold text-muted mb-0">
                            <i class="fa-solid fa-clock-rotate-left mr-1 text-warning"></i> INTERÉS MORA
                        </label>
                        <span v-if="cobro.isInteresFija" class="badge badge-warning" style="font-size: 0.65rem;">Fijo</span>
                    </div>
                    <div class="font-cairo font-weight-bold" style="font-size: 1.25rem; color: #b45309;">
                        Gs. @{{ format(cobro.totalInteres) }}
                    </div>
                    <small class="text-muted">Recargo por retraso</small>
                </div>

                <!-- Saldo Restante -->
                <div class="settlement-box-card">
                    <label class="small font-weight-bold text-muted mb-1">
                        <i class="fa-solid fa-wallet mr-1 text-danger"></i> SALDO RESTANTE
                    </label>
                    <div class="font-cairo font-weight-bold" style="font-size: 1.25rem; color: #dc2626;">
                        Gs. @{{ format(cobro.saldonuevo) }}
                    </div>
                    <small class="text-muted">Deuda tras este pago</small>
                </div>

                <!-- Hero: Total a Cobrar -->
                <div class="settlement-box-card settlement-hero-card">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <label class="small font-weight-bold mb-0" style="color: var(--dash-primary);">
                            <i class="fa-solid fa-money-bill-wave mr-1"></i> TOTAL A COBRAR
                        </label>
                        <span class="badge badge-success" style="font-size: 0.68rem;">Efectivo / Caja</span>
                    </div>
                    <div class="font-cairo font-weight-bold" style="font-size: 1.45rem; color: var(--dash-primary);">
                        Gs. @{{ totalCobrar }}
                    </div>
                    <small style="color: var(--dash-primary); font-weight: 600;">Monto total a percibir</small>
                </div>
            </div>
        </div>

        <!-- Footer con Botones de Acción -->
        <div class="d-flex align-items-center justify-content-between p-3 border-top" style="background: var(--dash-card-bg);">
            <button type="button" class="btn-pos-secondary" @click="cancelar">
                <i class="fa fa-times mr-1.5"></i> Cancelar Operación
            </button>
            <button type="button"
                    class="btn-pos-primary"
                    style="font-size: 1.05rem; padding: 0.65rem 1.6rem;"
                    @click="showFinalizar"
                    :disabled="request.finalizar || cuotasAcobrar.length == 0">
                <template v-if="request.finalizar">
                    <span class="spinner-border spinner-border-sm mr-2" role="status"></span> Procesando Cobro...
                </template>
                <template v-else>
                    <i class="fa-solid fa-check-circle mr-2 fa-lg"></i> FINALIZAR COBRO
                </template>
            </button>
        </div>
    </div>

    @include('cobro.cuotas')
    @include('venta.detalle')
    @include('cobro.finalizar')
    @include('cobro.cliente')
    @include('cobro.cobro-parcial')
</div>
@endsection
@section('script')
    <script>
        var app = new Vue({
            el: "#app",
            // components: {
            //     'in-number': inNumber
            // },
            data: {
                request: {
                    buscar: false,
                    cuota: false,
                    cliente: false,
                    finalizar: false
                },
                txtbuscar: '',
                inNumberClass: {
                    input: 'form-control input-millares'
                },
                montoParcial: 0,
                caja: {
                    estado: 'CERRADO',
                    id: 0,
                    nrooperacion: 0
                },
                filtro: {
                    orden: 'ASC',
                    busquedapor: 'cliente',
                    ordenarpor: '0',
                    tipo: ''
                },
                cobro: {
                    total: 0,
                    saldo: 0,
                    saldonuevo: 0,
                    cobrado: 0,
                    entrega: 0,
                    fecha: '',
                    idSucursal: 0,
                    totalInteres: 0,
                    cobrarInteres: false,
                    isInteresFija: false,
                    interesFija: 0
                },
                cliente: {
                    id: 0,
                    documento: 0,
                    nombre: '.-'
                },
                descontarCantidad: 0,
                txtcliente: '',
                ctas: [],
                articulos: [],
                cuotas: [],
                cuotasAcobrar: [],
                clientes: [],
                allCuota: [],
                venta: {},
                 onlyInteres: false,
                 idVenta: 0,
                 cobroParcialAllCtas: false,
                 mostrarCuentasCanceladas: false,
            },
            methods: {
                buscar: function(parm) {
                    if (this.txtbuscar.length < 1)
                        return

                    var t = parseFloat(this.txtbuscar);
                    if (isNaN(t)) {
                        this.cuotas = [];
                        this.cuotasAcobrar = [];
                        this.txtcliente = this.txtbuscar
                        $('#busquedaCliente').modal('show');
                        this.buscarCliente();
                        setTimeout(() => {
                            document.getElementById('txtcliente').focus();
                        }, 500);

                    } else {
                        this.request.buscar = true;
                        this.filtro.busquedapor = "ci";
                        this.getCta(this.txtbuscar);
                    }

                },
                checkPrimeraCuota: function(cantidad, nroventa) {
                    let primeracuota = this.allCuota.find(cuota => cuota.nro_fact_ventas == nroventa && cuota
                        .nro_cuotas == 1);

                    if (primeracuota == undefined) {
                        return cantidad - 1;
                    }
                    if (primeracuota.monto_cuota == 0 && primeracuota.monto_saldo == 0) {
                        return cantidad - 1;
                    } else {
                        return cantidad;
                    }
                },
                checkCantidad: function(cantidad, nroventa) {
                    let primeracuota = this.allCuota.find(cuota => cuota.nro_fact_ventas == nroventa && cuota
                        .nro_cuotas == 1);

                    if (primeracuota == undefined) {
                        return cantidad;
                    }
                    if (primeracuota.monto_cuota == 0 && primeracuota.monto_saldo == 0) {
                        return cantidad - 1;
                    } else {
                        return cantidad;
                    }
                },
                getCta: function(abuscar) {
                    axios.get('ctas_cobrar/buscar', {
                            params: {
                                buscar: abuscar,
                                buscarpor: this.filtro.busquedapor,
                                ordenarpor: this.filtro.ordenarpor,
                                ord: this.filtro.orden,
                                tipo: "cliente",
                                from: "cobro"
                            }
                        })
                        .then(response => {
                            this.request.buscar = false;
                            if (response.data.ctas.length == 0) {
                                Swal.fire('No posee cuenta a Cobrar!', this.filtro.ci ? 'Documento Nro: ' +
                                    abuscar : 'Cliente: ' + abuscar, 'info');
                            } else {
                                this.cobro.cobrado = 0;
                                this.cobro.saldo = 0;
                                this.ctas = response.data.ctas;
                                this.articulos = response.data.articulos;
                                this.allCuota = response.data.cuotas;
                                if (this.ctas.length > 0) {
                                    this.cliente.id = this.ctas[0].cliente_ruc;
                                    this.cliente.nombre = this.ctas[0].cliente_nombre;
                                    this.cliente.documento = this.ctas[0].cliente_ruc;
                                }
                                for (i = 0; i < this.ctas.length; i++) {
                                    this.cobro.saldo += parseInt(this.ctas[i].saldo);
                                    this.cobro.cobrado += parseInt(this.ctas[i].cobrado);
                                }

                                // this.paginacion= response.data.paginacion;
                                //this.paginacion.pagina_actual=1;
                            }
                            this.request.buscar = false;
                            //this.error=response.data;
                        })
                        .catch(e => {
                            this.request.buscar = false;
                            this.error = e.message;
                        });
                },
                buscarCliente: function() {
                    if (this.txtcliente.length > 0) {
                        var doc = '';
                        var nom = '';
                        if (isNaN(parseFloat(this.txtcliente))) {
                            nom = this.txtcliente;
                        } else {
                            doc = this.txtcliente;
                        }
                        this.request.cliente = true;
                        axios.get('cliente/buscar', {
                                params: {
                                    documento: doc,
                                    nombre: nom
                                }
                            })
                            .then(response => {
                                this.clientes = response.data;
                                this.request.cliente = false;
                            })
                            .catch(error => {
                                this.request.cliente = false;
                                console.log(error.message);
                            })
                    }
                },
                selectCliente: function(ci) {
                    this.cuotasAcobrar = [];
                    this.filtro.busquedapor = "ci";
                    $('#busquedaCliente').modal('hide');
                    this.getCta(ci);
                },
                getCuotas: function(nroventa) {
                    this.cuotas = this.allCuota.filter(function(cuota) {
                        return cuota.nro_fact_ventas == nroventa
                    })
                },
                cancelar: function() {
                    this.cliente = {
                        id: 0,
                        documento: 0,
                        nombre: '.-'
                    };
                    this.ctas = [];
                    this.cobro = {
                        total: 0,
                        saldo: 0,
                        saldonuevo: 0,
                        cobrado: 0,
                        entrega: 0,
                        fecha: '',
                        idSucursal: 0,
                        totalInteres: 0,
                        cobrarInteres: true,
                        isInteresFija: false,
                        interesFija: 0
                    };
                    this.descontarCantidad = 0;
                    this.articulos = [];
                    this.txtbuscar = '';
                    this.cuotas = [];
                    this.cuotasAcobrar = [];
                    this.getFecha();
                    this.getApertura();
                },
                finalizar: function() {
                    if (this.request.finalizar) {
                        return false;
                    }
                    this.request.finalizar = true;
                    axios.post('cobro', {
                            cuotas: this.cuotasAcobrar,
                            cobro: this.cobro,
                            onlyInteres: this.onlyInteres
                        })
                        .then(response => {
                            if (response.data > 0) {
                                window.location.assign('{{ env('APP_URL') }}' + 'documento/recibocobro/' +
                                    response.data);
                            } else {
                                window.location.assign('{{ env('APP_URL') }}' + 'documento/recibocobro/' +
                                    response.data);
                            }
                        })
                        .catch(error => {
                            this.request.finalizar = false;
                            console.log(error.message);
                        })

                },
                addCuota: function() {
                    for (i = 0; i < this.cuotas.length; i++) {
                        if (this.cuotas[i].check) {
                            let validar = this.cuotasAcobrar.findIndex(x => x.nro_cuotas == this.cuotas[i]
                                .nro_cuotas && x.nro_fact_ventas == this.cuotas[i].nro_fact_ventas);
                            if (validar == -1) {
                                this.pushCuota(this.cuotas[i]);
                            }
                        }
                    }
                    $('#selCuotas').modal('hide');

                },
                pushCuota: function(cuota) {
                    let c = {
                        estado: cuota.estado,
                        fecha_venc: cuota.fecha_venc,
                        interes: this.cobro.cobrarInteres && cuota.estado_interes == '0' ? this
                            .setMontoInteres(cuota.fecha_venc, cuota
                                .monto_cuota) : 0,
                        monto_cobrado: cuota.monto_cobrado,
                        monto_cuota: cuota.monto_cuota,
                        monto_saldo: cuota.monto_saldo,
                        nro_cuotas: cuota.nro_cuotas,
                        nro_fact_ventas: cuota.nro_fact_ventas,
                        acobrar: cuota.monto_saldo,
                        estado_interes: cuota.estado_interes
                    }
                    this.cuotasAcobrar.push(c);
                },
                quitarCuotaAcobrar: function(index) {
                    let cuota = this.cuotasAcobrar[index];
                    let found = this.cuotas.find(c => c.nro_cuotas == cuota.nro_cuotas && c.nro_fact_ventas == cuota.nro_fact_ventas);
                    if (found) {
                        found.check = false;
                    }
                    this.cuotasAcobrar.splice(index, 1);
                },
                limpiarCuotasAcobrar: function() {
                    for (let i = 0; i < this.cuotas.length; i++) {
                        this.cuotas[i].check = false;
                    }
                    this.cuotasAcobrar = [];
                },
                abrirCobroParcialDesdeCuotas() {
                    if(this.cuotasAcobrar.length == 0){
                    this.cobroParcialAllCtas = false;
                    $('#selCuotas').modal('hide');
                    // Abrir modal de cobro parcial
                    $('#cobroParcial').modal('show');
                    }
                },
                cobroParcial: function() {
                    $('#cobroParcial').modal('hide');
                    $('#txtbuscar').focus();
                    if (this.montoParcial > 0) {
                        if (this.montoParcial > this.totalSaldoFiltrado) {
                            this.montoParcial = 0;
                            Swal.fire('Datos incorrecto...', 'Monto ingresado es mayor al saldo!', 'error');
                            return false;
                        }
                        
                        // Determinar qué cuotas usar según cobroParcialAllCtas
                        let cuotasAUsar = [];
                        if (this.cobroParcialAllCtas) {
                            // Usar todas las cuotas (allCuota)
                            cuotasAUsar = this.allCuota;
                            if (this.ctas.length > 1) {
                                //ordenar allcuota por fecha de vencimiento
                                cuotasAUsar.sort(function(a, b) {
                                    return app.convertToDate(a.fecha_venc) > app.convertToDate(b
                                        .fecha_venc) ? 1 : app.convertToDate(a.fecha_venc) < app
                                        .convertToDate(b.fecha_venc) ? -1 : 0;
                                });
                            }
                        } else {
                            // Usar solo las cuotas del idVenta específico
                            cuotasAUsar = this.allCuota.filter(function(cuota) {
                                return cuota.nro_fact_ventas == app.idVenta;
                            });
                            // Ordenar por fecha de vencimiento
                            cuotasAUsar.sort(function(a, b) {
                                return app.convertToDate(a.fecha_venc) > app.convertToDate(b
                                    .fecha_venc) ? 1 : app.convertToDate(a.fecha_venc) < app
                                    .convertToDate(b.fecha_venc) ? -1 : 0;
                            });
                        }
                        
                        let iCuotasAcobrar = [];
                        let sumatoria = 0;

                        for (i = 0; i < cuotasAUsar
                            .length; i++) { //mientras cuotas seleccionada sea menor a monto a cobrar

                            if (sumatoria < this.montoParcial) {
                                if (cuotasAUsar[i].monto_saldo > 0) { //Verifica si cuota ya se cobro
                                    iCuotasAcobrar[i] = true // Marcar posicion en array como agregado (TRUE)
                                    sumatoria += parseInt(cuotasAUsar[i].monto_saldo)
                                } else {
                                    iCuotasAcobrar[i] = false
                                }
                            } else {
                                iCuotasAcobrar[i] = false;
                            }
                        }

                        for (i = 0; i < iCuotasAcobrar.length; i++) {
                            if (iCuotasAcobrar[i]) {
                                this.pushCuota(cuotasAUsar[i]);
                            }
                        }

                        /* for (i = 0; i < this.cuotasAcobrar.length; i++) {
                            sumatoria += this.cuotasAcobrar[i].interes;
                        } */

                        if (sumatoria >= this.montoParcial) {
                            let lastIndex = this.cuotasAcobrar.length - 1;
                            let lastAcobrar = this.cuotasAcobrar[lastIndex].acobrar;
                            let monto_resto = sumatoria - this.montoParcial;

                            this.cuotasAcobrar[lastIndex].acobrar = lastAcobrar - monto_resto;
                        }

                    }
                },
                //funcion para convertir string YYYY-mm-dd a date
                convertToDate: function(date) {
                    var parts = date.split("-");
                    return new Date(parts[0], parts[1] - 1, parts[2]);
                },

                modalCobroParcial: function() {
                    this.cobroParcialAllCtas = true;
                    if (this.totalSaldoFiltrado > 0 && this.cuotasAcobrar.length == 0) {
                        $('#cobroParcial').modal('show');
                        $('#txtparcial').focus();
                    }

                },
                detalleVenta: function(nroventa) {
                    return this.articulos.filter(function(venta) {
                        return venta.nro_fact_ventas == nroventa
                    })
                },
                format: function(numero) {
                    return new Intl.NumberFormat("de-DE").format(numero);
                },
                formatFecha: function(fecha) {
                    const f = fecha.split("-");
                    return f[2] + "/" + f[1] + "/" + f[0];
                },
                subFecha: function(startFecha) {
                    const fechaInicio = new Date(startFecha).getTime();
                    const fechaFin = new Date().getTime();
                    if (fechaInicio > fechaFin) {
                        return 0;
                    }
                    const diff = fechaFin - fechaInicio;
                    return parseInt(diff / (1000 * 60 * 60 * 24));
                   
                },
                diferenciaFecha: function(fecha_vent, monto_saldo, isInteres) {
                    if (isInteres == '1') {
                        return "-";
                    }
                    //2016-07-12
                    const dia = this.subFecha(fecha_vent)

                    //let diferenciaFecha = 0;
                    if (monto_saldo > 0) {
                        return dia
                    } else {
                        return "-"
                    }
                },
                validarContador(cantidad, index, monto) {
                    if (index == 0 && parseInt(monto) > 0) {
                        this.descontarCantidad = 1;
                    }
                    return cantidad - this.descontarCantidad;
                },
                getFecha: function() {

                    var f = new Date();
                    var dia = f.getDate();
                    var mes = (f.getMonth() + 1);
                    this.cobro.fecha = f.getFullYear() + "-" + mes.toString().padStart(2, "0") + "-" +
                        dia.toString().padStart(2, "0");
                    //this.filtrovalue= this.meses[mes];
                },
                checkCuota: function(index) {
                    const check = document.getElementById('check' + index).checked;
                    if (!check) {
                        let validar = this.cuotasAcobrar.findIndex(x => x.nro_cuotas == this.cuotas[
                                index].nro_cuotas && x.nro_fact_ventas == this.cuotas[index]
                            .nro_fact_ventas);
                        if (validar > -1) {
                            this.cuotasAcobrar.splice(validar, 1);
                        }

                    }
                    this.cuotas[index].check = check;
                },
                showCuotas: function(idVenta) {
                    this.idVenta = idVenta;
                    $('#selCuotas').modal('show');
                    this.getCuotas(idVenta);
                },
                showDetalle: function(i) {
                    this.venta = this.ctas[i];

                    $('#frmdetalle').modal('show');
                },
                showFinalizar: function() {
                    if (this.cuotasAcobrar.length > 0)
                        $('#saveCuotas').modal('show');
                },
                getApertura: function() {
                    let idSucursal = $('#sucursal').attr('data-id');
                    this.cobro.idSucursal = idSucursal;
                    if (idSucursal != null) {
                        axios.get('aperturacierre/' + idSucursal)
                            .then(response => {
                                if (response.data) {
                                    this.caja.nrooperacion = response.data.nro_operacion;
                                    this.cobro.nro_operacion = response.data.nro_operacion;
                                    this.caja.estado = 'ABIERTA';
                                } else {
                                    this.caja.estado = 'CERRADA';
                                }
                            })
                            .catch(error => {
                                console.log(error);
                            })
                    }
                },
                numeroaletra: function(n) {
                    return NumeroALetras.NumeroALetras(n);
                },
                setMontoInteres: function(vencimiento, monto) {
                    //Informe de venta misma funcion
                    let montoInteres = 0;
                    const interes_mora = 100;
                    const tmp_vencimiento = this.subFecha(vencimiento);

                    if (interes_mora > 0 && tmp_vencimiento > 5) {
                        montoInteres = (monto * interes_mora) / 100;
                        montoInteres = montoInteres / 360;
                        montoInteres = montoInteres * tmp_vencimiento;
                    }
                    return parseInt(montoInteres);
                },
                setInteres: async function() {
                    if (this.cobro.total == 0) {
                        return false;
                    }
                    const {
                        value: interes
                    } = await Swal.fire({
                        title: 'Ingrese el monto del interes',
                        input: 'number',
                        //inputLabel: 'Interes Fijo',
                        inputPlaceholder: 'Interes'
                    })

                    if (parseInt(interes) > 0) {
                        this.cobro.isInteresFija = true;
                        this.cobro.interesFija = parseInt(interes);
                        this.changeMontoInteres();
                    }
                },
                changeMontoInteres: function() {
                    console.log("ChangeCobrar Interes")
                    if (this.cobro.isInteresFija) {
                        let l = this.cuotasAcobrar.length;
                        for (let i = 0; i < l; i++) {
                            this.cuotasAcobrar[i].interes = this.cobro.cobrarInteres ? this.cobro.interesFija /
                                l : 0;
                        }
                    } else {
                        for (let i = 0; i < this.cuotasAcobrar.length; i++) {
                            this.cuotasAcobrar[i].interes = this.cobro.cobrarInteres ? this.setMontoInteres(this
                                .cuotasAcobrar[i].fecha_venc, this.cuotasAcobrar[i].monto_cuota) : 0;
                        }
                    }
                },
                cobrarInteres: function() {
                    if (this.cobro.total == 0) {
                        return false;
                    }
                    this.onlyInteres = true;
                    this.finalizar();
                }
            },
             computed: {
                 totalCobrar: function() {
                     let total = 0;
                     let interes = 0;
                     let saldo = 0;

                     if (this.cuotasAcobrar.length > 0) {
                         for (i = 0; i < this.cuotasAcobrar.length; i++) {
                             total += parseInt(this.cuotasAcobrar[i].acobrar) + parseInt(this.cuotasAcobrar[i]
                                 .interes);
                             interes += this.cuotasAcobrar[i].interes;
                         }
                         saldo = this.cobro.saldo - (total - interes);
                     }
                     if (this.cobro.isInteresFija && this.cobro.cobrarInteres) { //Si interes es fija
                         total -= interes;
                         interes = this.cobro.interesFija;
                         total += interes;
                         //saldo += this.montoParcial > 0 ? 0 : interes;
                     }

                     this.cobro.total = total;
                     this.cobro.totalInteres = interes;
                     this.cobro.saldonuevo = saldo;
                     //this.changeMontoInteres();
                     return this.format(total);
                 },
                 cuentasConSaldoPendiente: function() {
                     return this.ctas.filter(function(cuenta) {
                         return parseInt(cuenta.saldo) > 0;
                     }).length;
                 },
                 ctasFiltradas: function() {
                     if (this.mostrarCuentasCanceladas) {
                         return this.ctas; // Mostrar todas las cuentas
                     } else {
                         return this.ctas.filter(function(cuenta) {
                             return parseInt(cuenta.saldo) > 0; // Solo cuentas con saldo pendiente
                         });
                     }
                 },
                 totalCobradoFiltrado: function() {
                     let total = 0;
                     for (let i = 0; i < this.ctasFiltradas.length; i++) {
                         total += parseInt(this.ctasFiltradas[i].cobrado);
                     }
                     return total;
                 },
                 totalSaldoFiltrado: function() {
                     let total = 0;
                     for (let i = 0; i < this.ctasFiltradas.length; i++) {
                         total += parseInt(this.ctasFiltradas[i].saldo);
                     }
                     return total;
                 },
                totalCuota: function() {
                    let total = 0;
                    if (this.montoParcial > 0 && this.cobro.isInteresFija) {
                        total = this.cobro.total - this.cobro.interesFija;
                    } else {
                        total = this.cobro.total - this.cobro.totalInteres;
                    }
                    return this.format(total);
                }
            },
            mounted() {
                this.getFecha();
                this.getApertura();
            }
        });
        activarMenu('m_cobro', '');
    </script>
@endsection
