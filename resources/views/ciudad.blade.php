@extends('layouts.app')
@section('title', 'Gestión de Ciudades')

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
        --dash-text-main: #1c2430;
        --dash-text-muted: #64748b;
        --dash-card-bg: #ffffff;
        --dash-panel-bg: #f8fafc;
        --dash-border: #e2e8f0;
        --dash-success: #10b981;
        --dash-info: #0284c7;
        --dash-warning: #f59e0b;
        --dash-danger: #ef4444;
    }

    body.dark-mode {
        --dash-primary: #10b981;
        --dash-primary-dark: #059669;
        --dash-primary-light: #064e3b;
        --dash-primary-border: #047857;
        --dash-accent: #f59e0b;
        --dash-text-main: #f1f5f9;
        --dash-text-muted: #94a3b8;
        --dash-card-bg: #1e293b;
        --dash-panel-bg: #0f172a;
        --dash-border: #334155;
        --dash-success: #34d399;
        --dash-info: #38bdf8;
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
        font-size: 1.35rem;
        box-shadow: 0 2px 8px rgba(10, 77, 54, 0.12);
        flex-shrink: 0;
    }
    .dash-header-title {
        font-size: 1.55rem;
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

    /* Actions and Buttons */
    .btn-custom {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1.15rem;
        font-size: 0.88rem;
        font-weight: 600;
        border-radius: 8px;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-custom-primary {
        background: var(--dash-primary);
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(10, 77, 54, 0.25);
    }
    .btn-custom-primary:hover {
        background: var(--dash-primary-dark);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(10, 77, 54, 0.35);
    }
    .btn-custom-outline {
        background: transparent;
        border-color: var(--dash-border);
        color: var(--dash-text-main);
    }
    .btn-custom-outline:hover {
        background: var(--dash-panel-bg);
        border-color: var(--dash-text-muted);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
        margin-bottom: 1.25rem;
    }
    .stat-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        padding: 1.1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.06);
    }
    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .stat-icon.emerald { background: #eaf3ef; color: #0a4d36; }
    .stat-icon.blue    { background: #e0f2fe; color: #0284c7; }
    .stat-icon.amber   { background: #fef3c7; color: #d97706; }
    .stat-icon.purple  { background: #f3e8ff; color: #7e22ce; }

    body.dark-mode .stat-icon.emerald { background: rgba(16, 185, 129, 0.2); color: #34d399; }
    body.dark-mode .stat-icon.blue    { background: rgba(56, 189, 248, 0.2); color: #38bdf8; }
    body.dark-mode .stat-icon.amber   { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }
    body.dark-mode .stat-icon.purple  { background: rgba(168, 85, 247, 0.2); color: #c084fc; }

    .stat-val {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dash-text-main);
        line-height: 1.1;
    }
    .stat-label {
        font-size: 0.82rem;
        color: var(--dash-text-muted);
        margin-top: 0.25rem;
    }

    /* Main Table Card */
    .dash-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    /* Filter Toolbar */
    .filter-bar {
        padding: 1rem 1.25rem;
        background: var(--dash-panel-bg);
        border-bottom: 1px solid var(--dash-border);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.85rem;
    }
    .search-box {
        position: relative;
        flex: 1 1 260px;
        max-width: 380px;
    }
    .search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--dash-text-muted);
        font-size: 0.9rem;
    }
    .search-input {
        width: 100%;
        padding: 0.5rem 0.85rem 0.5rem 2.2rem;
        font-size: 0.88rem;
        border: 1px solid var(--dash-border);
        border-radius: 8px;
        background: var(--dash-card-bg);
        color: var(--dash-text-main);
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .search-input:focus {
        border-color: var(--dash-primary);
        box-shadow: 0 0 0 3px rgba(10, 77, 54, 0.15);
    }
    .filter-select {
        padding: 0.5rem 2rem 0.5rem 0.85rem;
        font-size: 0.88rem;
        border: 1px solid var(--dash-border);
        border-radius: 8px;
        background: var(--dash-card-bg);
        color: var(--dash-text-main);
        outline: none;
        cursor: pointer;
    }
    .filter-select:focus {
        border-color: var(--dash-primary);
    }

    /* Table styles */
    .table-responsive-custom {
        overflow-x: auto;
    }
    .dash-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.88rem;
        color: var(--dash-text-main);
    }
    .dash-table th {
        background: var(--dash-panel-bg);
        color: var(--dash-text-muted);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.76rem;
        letter-spacing: 0.04em;
        padding: 0.85rem 1.15rem;
        border-bottom: 1px solid var(--dash-border);
        white-space: nowrap;
    }
    .dash-table td {
        padding: 0.9rem 1.15rem;
        border-bottom: 1px solid var(--dash-border);
        vertical-align: middle;
    }
    .dash-table tbody tr {
        transition: background-color 0.15s ease;
    }
    .dash-table tbody tr:hover {
        background-color: var(--dash-primary-light);
    }

    /* Badges & Tags */
    .tag-depart {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.65rem;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 600;
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
    }
    body.dark-mode .tag-depart {
        background: #1e293b;
        color: #cbd5e1;
        border-color: #475569;
    }

    .pill-counter {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.55rem;
        border-radius: 12px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .pill-counter.active {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }
    .pill-counter.inactive {
        background: #f8fafc;
        color: #94a3b8;
        border: 1px solid #e2e8f0;
    }
    body.dark-mode .pill-counter.active {
        background: rgba(56, 189, 248, 0.2);
        color: #7dd3fc;
        border-color: rgba(56, 189, 248, 0.3);
    }
    body.dark-mode .pill-counter.inactive {
        background: #0f172a;
        color: #64748b;
        border-color: #334155;
    }

    /* Actions buttons */
    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        color: var(--dash-text-muted);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-action.edit:hover {
        background: #0284c7;
        color: #ffffff;
        border-color: #0284c7;
    }
    .btn-action.del:hover {
        background: #ef4444;
        color: #ffffff;
        border-color: #ef4444;
    }

    /* Pagination Bar */
    .pagination-bar {
        padding: 0.85rem 1.25rem;
        background: var(--dash-panel-bg);
        border-top: 1px solid var(--dash-border);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        font-size: 0.85rem;
        color: var(--dash-text-muted);
    }
    .page-btn {
        padding: 0.35rem 0.7rem;
        border: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        color: var(--dash-text-main);
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.82rem;
        transition: all 0.15s ease;
    }
    .page-btn:hover:not(:disabled) {
        border-color: var(--dash-primary);
        color: var(--dash-primary);
    }
    .page-btn.active {
        background: var(--dash-primary);
        border-color: var(--dash-primary);
        color: #ffffff;
        font-weight: 700;
    }
    .page-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
        color: var(--dash-text-muted);
    }
    .empty-state i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 0.75rem;
    }

    /* Modal Styling */
    .modal-content-custom {
        background: var(--dash-card-bg);
        color: var(--dash-text-main);
        border-radius: 14px;
        border: 1px solid var(--dash-border);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        overflow: hidden;
    }
    .modal-header-custom {
        background: linear-gradient(135deg, var(--dash-primary) 0%, #115e45 100%);
        color: #ffffff;
        padding: 1rem 1.4rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .modal-title-custom {
        font-size: 1.15rem;
        font-weight: 700;
        margin: 0;
    }
    .modal-close-custom {
        background: transparent;
        border: 0;
        color: #ffffff;
        font-size: 1.25rem;
        cursor: pointer;
        opacity: 0.8;
        transition: opacity 0.15s ease;
    }
    .modal-close-custom:hover {
        opacity: 1;
    }
    .modal-body-custom {
        padding: 1.4rem;
    }

    .form-group-custom {
        margin-bottom: 1.1rem;
    }
    .form-label-custom {
        display: block;
        font-weight: 600;
        font-size: 0.85rem;
        color: var(--dash-text-main);
        margin-bottom: 0.4rem;
    }
    .form-control-custom {
        width: 100%;
        padding: 0.6rem 0.9rem;
        font-size: 0.92rem;
        border: 1px solid var(--dash-border);
        border-radius: 8px;
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-control-custom:focus {
        border-color: var(--dash-primary);
        box-shadow: 0 0 0 3px rgba(10, 77, 54, 0.15);
        background: var(--dash-card-bg);
    }

    [v-cloak] { display: none !important; }
</style>
@endsection

@section('main')
<div class="container-fluid" id="app" v-cloak>

    <!-- Header Section -->
    <div class="dash-header">
        <div class="dash-header-left">
            <div class="dash-header-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div>
                <h1 class="dash-header-title font-cairo">Ciudades y Localidades</h1>
                <p class="dash-header-subtitle">Administre el catálogo geográfico para asignación en clientes, proveedores y sucursales.</p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="btn-custom btn-custom-outline" @click="imprimirListado" title="Imprimir catálogo actual">
                <i class="fas fa-print"></i> Imprimir
            </button>
            <button class="btn-custom btn-custom-primary font-cairo" @click="abrirModalCrear">
                <i class="fas fa-plus-circle"></i> Nueva Ciudad
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon emerald">
                <i class="fas fa-city"></i>
            </div>
            <div>
                <div class="stat-val">@{{ totalCiudades }}</div>
                <div class="stat-label">Ciudades Registradas</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-map"></i>
            </div>
            <div>
                <div class="stat-val">@{{ totalDepartamentos }}</div>
                <div class="stat-label">Departamentos Cubiertos</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="stat-val">{{ $totalClientesAsignados ?? 0 }}</div>
                <div class="stat-label">Clientes con Ciudad</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-truck"></i>
            </div>
            <div>
                <div class="stat-val">{{ $totalProveedoresAsignados ?? 0 }}</div>
                <div class="stat-label">Proveedores con Ciudad</div>
            </div>
        </div>
    </div>

    <!-- Main Card & Data Table -->
    <div class="dash-card">
        <!-- Filter Toolbar -->
        <div class="filter-bar">
            <!-- Search -->
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input
                    type="text"
                    class="search-input"
                    v-model="filtroTexto"
                    placeholder="Buscar por ciudad o departamento..."
                    @keydown.esc="filtroTexto = ''"
                >
            </div>

            <!-- Filter Department -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <select class="filter-select" v-model="filtroDepartamento">
                    <option value="">Todos los departamentos</option>
                    <option v-for="d in departamentos" :key="d.depart_codigo" :value="d.depart_codigo">
                        @{{ d.depart_nombre }}
                    </option>
                </select>

                <!-- Filter with records -->
                <select class="filter-select" v-model="filtroVinculos">
                    <option value="todos">Todos los registros</option>
                    <option value="con_clientes">Con clientes</option>
                    <option value="con_proveedores">Con proveedores</option>
                    <option value="sin_vinculos">Sin registros vinculados</option>
                </select>

                <!-- Order by -->
                <select class="filter-select" v-model="ordenarPor">
                    <option value="nombre_asc">Ciudad (A &rarr; Z)</option>
                    <option value="nombre_desc">Ciudad (Z &rarr; A)</option>
                    <option value="departamento">Departamento</option>
                    <option value="clientes_desc">Más clientes</option>
                </select>
            </div>
        </div>

        <!-- Table Content -->
        <div class="table-responsive-custom">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th style="width: 60px; text-align: center;">#</th>
                        <th>Ciudad / Localidad</th>
                        <th>Departamento</th>
                        <th style="text-align: center;">Clientes</th>
                        <th style="text-align: center;">Proveedores</th>
                        <th style="width: 110px; text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody v-if="ciudadesPaginadas.length > 0">
                    <tr v-for="(c, index) in ciudadesPaginadas" :key="c.CIUDAD_cod">
                        <td style="text-align: center; color: var(--dash-text-muted); font-size: 0.8rem;">
                            @{{ (paginaActual - 1) * porPagina + index + 1 }}
                        </td>
                        <td>
                            <strong style="color: var(--dash-text-main);">@{{ c.ciudad_nombre }}</strong>
                        </td>
                        <td>
                            <span class="tag-depart">
                                <i class="fas fa-map-pin" style="color: #64748b;"></i>
                                @{{ c.departamento ? c.departamento.depart_nombre : 'Sin departamento' }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <span
                                class="pill-counter"
                                :class="(c.clientes_count && c.clientes_count > 0) ? 'active' : 'inactive'"
                                :title="(c.clientes_count || 0) + ' clientes asignados'"
                            >
                                <i class="fas fa-user" style="font-size: 0.72rem;"></i>
                                @{{ c.clientes_count || 0 }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <span
                                class="pill-counter"
                                :class="(c.proveedores_count && c.proveedores_count > 0) ? 'active' : 'inactive'"
                                :title="(c.proveedores_count || 0) + ' proveedores asignados'"
                            >
                                <i class="fas fa-truck" style="font-size: 0.72rem;"></i>
                                @{{ c.proveedores_count || 0 }}
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div class="d-flex align-items-center justify-content-end gap-1">
                                <button
                                    class="btn-action edit"
                                    @click="abrirModalEditar(c)"
                                    title="Editar ciudad"
                                >
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button
                                    class="btn-action del"
                                    @click="confirmarEliminar(c)"
                                    title="Eliminar ciudad"
                                >
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fas fa-map-marked-alt"></i>
                                <h4 class="font-cairo" style="font-size: 1.15rem; margin: 0 0 0.35rem;">No se encontraron ciudades</h4>
                                <p style="font-size: 0.88rem; margin: 0 0 1rem;">No hay registros que coincidan con los filtros seleccionados.</p>
                                <button class="btn-custom btn-custom-outline" @click="limpiarFiltros">
                                    <i class="fas fa-sync-alt"></i> Limpiar filtros
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        <div class="pagination-bar" v-if="ciudadesFiltradas.length > 0">
            <div>
                Mostrando
                <strong>@{{ (paginaActual - 1) * porPagina + 1 }}</strong> -
                <strong>@{{ Math.min(paginaActual * porPagina, ciudadesFiltradas.length) }}</strong>
                de <strong>@{{ ciudadesFiltradas.length }}</strong> ciudades
                <span v-if="ciudadesFiltradas.length !== ciudades.length">
                    (filtradas de un total de @{{ ciudades.length }})
                </span>
            </div>

            <div class="d-flex align-items-center gap-1">
                <button
                    class="page-btn"
                    :disabled="paginaActual <= 1"
                    @click="paginaActual--"
                >
                    <i class="fas fa-chevron-left"></i>
                </button>

                <button
                    v-for="p in paginasVisibles"
                    :key="p"
                    class="page-btn"
                    :class="{ active: p === paginaActual }"
                    @click="paginaActual = p"
                >
                    @{{ p }}
                </button>

                <button
                    class="page-btn"
                    :disabled="paginaActual >= totalPaginas"
                    @click="paginaActual++"
                >
                    <i class="fas fa-chevron-right"></i>
                </button>

                <select class="filter-select ml-2" v-model.number="porPagina" style="padding: 0.25rem 1.6rem 0.25rem 0.55rem; font-size: 0.8rem;">
                    <option :value="10">10 por pág.</option>
                    <option :value="25">25 por pág.</option>
                    <option :value="50">50 por pág.</option>
                    <option :value="100">100 por pág.</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Modal Crear / Editar Ciudad -->
    <div class="modal fade" id="modalCiudad" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title-custom font-cairo">
                        <i :class="modoEdicion ? 'fas fa-edit' : 'fas fa-plus-circle'" class="mr-1"></i>
                        @{{ modoEdicion ? 'Editar Ciudad' : 'Registrar Nueva Ciudad' }}
                    </h5>
                    <button type="button" class="modal-close-custom" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="guardarCiudad">
                    <div class="modal-body modal-body-custom">
                        <!-- Alerta informativa al editar ciudad con relaciones -->
                        <div
                            class="alert alert-warning py-2 px-3 mb-3 d-flex align-items-center gap-2"
                            style="font-size: 0.82rem; border-radius: 8px;"
                            v-if="modoEdicion && (form.clientes_count > 0 || form.proveedores_count > 0)"
                        >
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>
                                Esta ciudad está vinculada a
                                <strong>@{{ form.clientes_count }} clientes</strong> y
                                <strong>@{{ form.proveedores_count }} proveedores</strong>. El cambio de nombre se reflejará automáticamente en todos ellos.
                            </span>
                        </div>

                        <!-- Campo Departamento -->
                        <div class="form-group-custom">
                            <label class="form-label-custom">
                                Departamento <span class="text-danger">*</span>
                            </label>
                            <select
                                class="form-control-custom"
                                v-model="form.depart_codigo"
                                required
                            >
                                <option value="" disabled>-- Seleccione el departamento --</option>
                                <option
                                    v-for="d in departamentos"
                                    :key="d.depart_codigo"
                                    :value="d.depart_codigo"
                                >
                                    @{{ d.depart_nombre }}
                                </option>
                            </select>
                        </div>

                        <!-- Campo Nombre de Ciudad -->
                        <div class="form-group-custom">
                            <label class="form-label-custom">
                                Nombre de la Ciudad / Localidad <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                ref="inputCiudadNombre"
                                class="form-control-custom"
                                v-model="form.ciudad_nombre"
                                placeholder="Ej: ASUNCION, LUQUE, ENCARNACION..."
                                required
                                maxlength="100"
                                @input="form.ciudad_nombre = form.ciudad_nombre.toUpperCase()"
                            >
                        </div>
                    </div>

                    <div class="modal-footer" style="border-top: 1px solid var(--dash-border); padding: 0.85rem 1.4rem;">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="btn-custom btn-custom-primary font-cairo"
                            :disabled="guardando || !form.ciudad_nombre.trim() || !form.depart_codigo"
                        >
                            <i class="fas fa-spinner fa-spin mr-1" v-if="guardando"></i>
                            <i class="fas fa-save mr-1" v-else></i>
                            @{{ modoEdicion ? 'Actualizar Ciudad' : 'Guardar Ciudad' }}
                        </button>
                    </div>
                </form>
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
            ciudades: {!! json_encode($ciudades ?? []) !!},
            departamentos: {!! json_encode($departamentos ?? []) !!},

            filtroTexto: '',
            filtroDepartamento: '',
            filtroVinculos: 'todos',
            ordenarPor: 'nombre_asc',

            paginaActual: 1,
            porPagina: 25,

            modoEdicion: false,
            guardando: false,
            form: {
                CIUDAD_cod: null,
                ciudad_nombre: '',
                depart_codigo: '',
                clientes_count: 0,
                proveedores_count: 0
            }
        },
        computed: {
            totalCiudades: function () {
                return this.ciudades.length;
            },
            totalDepartamentos: function () {
                var deps = {};
                this.ciudades.forEach(function (c) {
                    if (c.depart_codigo) deps[c.depart_codigo] = true;
                });
                return Object.keys(deps).length;
            },
            ciudadesFiltradas: function () {
                var texto = (this.filtroTexto || '').trim().toLowerCase();
                var depFiltro = this.filtroDepartamento;
                var vincFiltro = this.filtroVinculos;

                var res = this.ciudades.filter(function (c) {
                    // Filtro texto
                    var coincideTexto = true;
                    if (texto !== '') {
                        var nomCiudad = (c.ciudad_nombre || '').toLowerCase();
                        var nomDepart = (c.departamento && c.departamento.depart_nombre ? c.departamento.depart_nombre : '').toLowerCase();
                        coincideTexto = nomCiudad.indexOf(texto) !== -1 || nomDepart.indexOf(texto) !== -1;
                    }

                    // Filtro departamento
                    var coincideDep = true;
                    if (depFiltro !== '') {
                        coincideDep = String(c.depart_codigo) === String(depFiltro);
                    }

                    // Filtro vínculos
                    var coincideVinc = true;
                    var cCount = parseInt(c.clientes_count || 0, 10);
                    var pCount = parseInt(c.proveedores_count || 0, 10);
                    if (vincFiltro === 'con_clientes') {
                        coincideVinc = cCount > 0;
                    } else if (vincFiltro === 'con_proveedores') {
                        coincideVinc = pCount > 0;
                    } else if (vincFiltro === 'sin_vinculos') {
                        coincideVinc = cCount === 0 && pCount === 0;
                    }

                    return coincideTexto && coincideDep && coincideVinc;
                });

                // Ordenamiento
                var sort = this.ordenarPor;
                res.sort(function (a, b) {
                    if (sort === 'nombre_asc') {
                        return (a.ciudad_nombre || '').localeCompare(b.ciudad_nombre || '');
                    } else if (sort === 'nombre_desc') {
                        return (b.ciudad_nombre || '').localeCompare(a.ciudad_nombre || '');
                    } else if (sort === 'departamento') {
                        var depA = a.departamento ? a.departamento.depart_nombre : '';
                        var depB = b.departamento ? b.departamento.depart_nombre : '';
                        return depA.localeCompare(depB) || (a.ciudad_nombre || '').localeCompare(b.ciudad_nombre || '');
                    } else if (sort === 'clientes_desc') {
                        var cntA = parseInt(a.clientes_count || 0, 10);
                        var cntB = parseInt(b.clientes_count || 0, 10);
                        return cntB - cntA;
                    }
                    return 0;
                });

                return res;
            },
            totalPaginas: function () {
                return Math.ceil(this.ciudadesFiltradas.length / this.porPagina) || 1;
            },
            ciudadesPaginadas: function () {
                var start = (this.paginaActual - 1) * this.porPagina;
                return this.ciudadesFiltradas.slice(start, start + this.porPagina);
            },
            paginasVisibles: function () {
                var total = this.totalPaginas;
                var current = this.paginaActual;
                var paginas = [];

                var inicio = Math.max(1, current - 2);
                var fin = Math.min(total, current + 2);

                for (var i = inicio; i <= fin; i++) {
                    paginas.push(i);
                }
                return paginas;
            }
        },
        watch: {
            filtroTexto: function () { this.paginaActual = 1; },
            filtroDepartamento: function () { this.paginaActual = 1; },
            filtroVinculos: function () { this.paginaActual = 1; },
            ordenarPor: function () { this.paginaActual = 1; }
        },
        methods: {
            limpiarFiltros: function () {
                this.filtroTexto = '';
                this.filtroDepartamento = '';
                this.filtroVinculos = 'todos';
                this.ordenarPor = 'nombre_asc';
                this.paginaActual = 1;
            },
            abrirModalCrear: function () {
                this.modoEdicion = false;
                this.form = {
                    CIUDAD_cod: null,
                    ciudad_nombre: '',
                    depart_codigo: this.departamentos.length > 0 ? this.departamentos[0].depart_codigo : '',
                    clientes_count: 0,
                    proveedores_count: 0
                };
                $('#modalCiudad').modal('show');
                var self = this;
                this.$nextTick(function () {
                    if (self.$refs.inputCiudadNombre) {
                        self.$refs.inputCiudadNombre.focus();
                    }
                });
            },
            abrirModalEditar: function (ciudad) {
                this.modoEdicion = true;
                this.form = {
                    CIUDAD_cod: ciudad.CIUDAD_cod,
                    ciudad_nombre: ciudad.ciudad_nombre,
                    depart_codigo: ciudad.depart_codigo,
                    clientes_count: ciudad.clientes_count || 0,
                    proveedores_count: ciudad.proveedores_count || 0
                };
                $('#modalCiudad').modal('show');
                var self = this;
                this.$nextTick(function () {
                    if (self.$refs.inputCiudadNombre) {
                        self.$refs.inputCiudadNombre.focus();
                    }
                });
            },
            guardarCiudad: function () {
                var nombre = (this.form.ciudad_nombre || '').trim();
                var dep = this.form.depart_codigo;

                if (!nombre) {
                    Swal.fire('Atención', 'Ingrese el nombre de la ciudad.', 'warning');
                    return;
                }
                if (!dep) {
                    Swal.fire('Atención', 'Seleccione un departamento.', 'warning');
                    return;
                }

                var self = this;
                this.guardando = true;

                if (this.modoEdicion) {
                    // Actualizar
                    axios.post('{{ url("ciudad") }}/' + this.form.CIUDAD_cod, {
                        ciudad: nombre,
                        departamento: dep
                    })
                    .then(function (response) {
                        self.guardando = false;
                        $('#modalCiudad').modal('hide');

                        var updated = response.data.ciudad;
                        var idx = self.ciudades.findIndex(function (item) {
                            return item.CIUDAD_cod === self.form.CIUDAD_cod;
                        });
                        if (idx !== -1) {
                            self.$set(self.ciudades, idx, updated);
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Actualizado',
                            text: response.data.message || 'Ciudad actualizada correctamente.',
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    })
                    .catch(function (error) {
                        self.guardando = false;
                        var msg = (error.response && error.response.data && error.response.data.message)
                            ? error.response.data.message
                            : 'No se pudo actualizar la ciudad.';
                        Swal.fire('Error', msg, 'error');
                    });
                } else {
                    // Crear
                    axios.post('{{ url("ciudad") }}', {
                        ciudad: nombre,
                        departamento: dep
                    })
                    .then(function (response) {
                        self.guardando = false;
                        $('#modalCiudad').modal('hide');

                        var nueva = response.data.ciudad;
                        self.ciudades.unshift(nueva);

                        Swal.fire({
                            icon: 'success',
                            title: 'Registrado',
                            text: response.data.message || 'Ciudad creada exitosamente.',
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    })
                    .catch(function (error) {
                        self.guardando = false;
                        var msg = (error.response && error.response.data && error.response.data.message)
                            ? error.response.data.message
                            : 'No se pudo registrar la ciudad.';
                        Swal.fire('Error', msg, 'error');
                    });
                }
            },
            confirmarEliminar: function (ciudad) {
                var cCount = parseInt(ciudad.clientes_count || 0, 10);
                var pCount = parseInt(ciudad.proveedores_count || 0, 10);

                if (cCount > 0 || pCount > 0) {
                    var msgs = [];
                    if (cCount > 0) msgs.push(cCount + ' cliente(s)');
                    if (pCount > 0) msgs.push(pCount + ' proveedor(es)');

                    Swal.fire({
                        icon: 'warning',
                        title: 'No se puede eliminar',
                        html: 'La ciudad <strong>' + ciudad.ciudad_nombre + '</strong> está vinculada a <strong>' + msgs.join(' y ') + '</strong>.<br><br><small class="text-muted">Para eliminarla, primero reasigne los clientes o proveedores a otra ciudad.</small>',
                        confirmButtonText: 'Entendido'
                    });
                    return;
                }

                var self = this;
                Swal.fire({
                    title: '¿Eliminar ciudad?',
                    html: '¿Confirma que desea eliminar la ciudad <strong>' + ciudad.ciudad_nombre + '</strong>?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then(function (result) {
                    if (result.value) {
                        axios.delete('{{ url("ciudad") }}/' + ciudad.CIUDAD_cod)
                            .then(function (response) {
                                self.ciudades = self.ciudades.filter(function (item) {
                                    return item.CIUDAD_cod !== ciudad.CIUDAD_cod;
                                });

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Eliminado',
                                    text: response.data.message || 'Ciudad eliminada.',
                                    timer: 2000,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end'
                                });
                            })
                            .catch(function (error) {
                                var msg = (error.response && error.response.data && error.response.data.message)
                                    ? error.response.data.message
                                    : 'No se pudo eliminar el registro.';
                                Swal.fire('Error', msg, 'error');
                            });
                    }
                });
            },
            imprimirListado: function () {
                window.print();
            }
        },
        mounted: function () {
            activarMenu('m_mantenimiento', 'm_ciudad');
        }
    });
</script>
@endsection