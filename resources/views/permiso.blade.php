@extends('layouts.app')
@section('title', 'Gestión de Privilegios y Permisos')

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

    /* Topbar */
    .view-topbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.85rem;
        margin-bottom: 1.25rem;
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
        font-size: 0.85rem;
        color: var(--dash-text-muted);
        margin: 0;
    }

    /* Role Cards Grid */
    .roles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 0.85rem;
        margin-bottom: 1.25rem;
    }
    .role-card {
        background: var(--dash-card-bg);
        border: 2px solid var(--dash-border);
        border-radius: 14px;
        padding: 1rem 1.15rem;
        cursor: pointer;
        transition: all 0.18s ease;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .role-card:hover {
        transform: translateY(-2px);
        border-color: var(--dash-primary);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
    }
    .role-card.active {
        border-color: var(--dash-primary);
        background: var(--dash-primary-light);
        box-shadow: 0 4px 14px rgba(10, 77, 54, 0.15);
    }
    .role-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.4rem;
    }
    .role-card-title {
        font-size: 1rem;
        font-weight: 800;
        color: var(--dash-text-main);
        margin: 0;
    }
    .role-card.active .role-card-title {
        color: var(--dash-primary);
    }
    .role-card-desc {
        font-size: 0.78rem;
        color: var(--dash-text-muted);
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    .role-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.75rem;
        font-weight: 700;
        padding-top: 0.4rem;
        border-top: 1px dashed var(--dash-border);
    }

    /* Cards */
    .card-modern {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        padding: 1.15rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }

    /* Navigation Tabs */
    .nav-tabs-modern {
        display: flex;
        gap: 0.5rem;
        border-bottom: 2px solid var(--dash-border);
        margin-bottom: 1rem;
    }
    .nav-tab-item {
        border: none;
        background: transparent;
        color: var(--dash-text-muted);
        font-weight: 700;
        font-size: 0.9rem;
        padding: 0.65rem 1.15rem;
        border-radius: 10px 10px 0 0;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        transition: all 0.15s ease;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
    }
    .nav-tab-item:hover {
        color: var(--dash-primary);
    }
    .nav-tab-item.active {
        color: var(--dash-primary);
        border-bottom-color: var(--dash-primary);
        background: var(--dash-panel-bg);
    }

    /* Filter Chips */
    .chip-filter {
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.3rem 0.75rem;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .chip-filter:hover,
    .chip-filter.active {
        background: var(--dash-primary-light);
        border-color: var(--dash-primary);
        color: var(--dash-primary);
    }

    /* Matrix Table */
    .table-matrix {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .table-matrix th {
        background: var(--dash-panel-bg);
        color: var(--dash-text-muted);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.7rem 0.85rem;
        border-top: 1px solid var(--dash-border);
        border-bottom: 1px solid var(--dash-border);
        text-align: center;
    }
    .table-matrix th:first-child {
        text-align: left;
    }
    .table-matrix td {
        padding: 0.65rem 0.85rem;
        border-bottom: 1px solid var(--dash-border);
        vertical-align: middle;
        font-size: 0.85rem;
        text-align: center;
    }
    .table-matrix td:first-child {
        text-align: left;
    }
    .table-matrix tr:hover td {
        background: var(--dash-panel-bg);
    }

    /* Category divider row in matrix */
    .category-divider-row td {
        background: var(--dash-panel-bg) !important;
        font-weight: 800;
        font-size: 0.82rem;
        text-transform: uppercase;
        color: var(--dash-primary);
        padding: 0.45rem 0.85rem;
        border-top: 1px solid var(--dash-border);
        border-bottom: 1px solid var(--dash-border);
        text-align: left !important;
    }

    /* Custom Checkbox Switch */
    .perm-switch {
        position: relative;
        display: inline-block;
        width: 38px;
        height: 20px;
        margin: 0;
        vertical-align: middle;
    }
    .perm-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .perm-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: .2s ease;
        border-radius: 20px;
    }
    .perm-slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .2s ease;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }
    input:checked + .perm-slider {
        background-color: #10b981;
    }
    input:checked + .perm-slider:before {
        transform: translateX(18px);
    }
    body.dark-mode .perm-slider {
        background-color: #475569;
    }
    body.dark-mode input:checked + .perm-slider {
        background-color: #059669;
    }

    /* Action Cards Grid */
    .action-cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 1rem;
    }
    .action-privilege-card {
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.85rem;
        transition: all 0.15s ease;
    }
    .action-privilege-card:hover {
        border-color: var(--dash-primary);
        background: var(--dash-card-bg);
    }
    .action-privilege-info h6 {
        font-size: 0.92rem;
        font-weight: 800;
        margin: 0 0 0.25rem 0;
        color: var(--dash-text-main);
    }
    .action-privilege-info p {
        font-size: 0.78rem;
        color: var(--dash-text-muted);
        margin: 0 0 0.35rem 0;
        line-height: 1.35;
    }
    .action-privilege-badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 4px;
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        color: var(--dash-primary);
        display: inline-block;
    }

    /* Sticky Save Bar */
    .save-bar-floating {
        position: fixed;
        bottom: 20px;
        right: 25px;
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(10px);
        color: #ffffff;
        border-radius: 35px;
        padding: 0.6rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1.2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        z-index: 1040;
    }
    .btn-save-floating {
        background: #10b981;
        color: #ffffff;
        border: none;
        border-radius: 25px;
        padding: 0.55rem 1.35rem;
        font-weight: 800;
        font-size: 0.9rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        transition: all 0.15s ease;
    }
    .btn-save-floating:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    /* SQL Code Display Box */
    .sql-code-box {
        background: #0f172a;
        color: #38bdf8;
        font-family: 'Courier New', Courier, monospace;
        font-size: 0.8rem;
        padding: 1rem;
        border-radius: 8px;
        max-height: 380px;
        overflow-y: auto;
        white-space: pre;
    }

    /* Modals */
    .modal-moderno {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 16px;
        color: var(--dash-text-main);
        overflow: hidden;
    }
    .modal-moderno .modal-header {
        border-bottom: 1px solid var(--dash-border);
        padding: 1rem 1.25rem;
    }
    .modal-moderno .modal-footer {
        border-top: 1px solid var(--dash-border);
        padding: 0.85rem 1.25rem;
    }
</style>
@endsection

@section('main')
<div id="app" v-cloak class="container-fluid py-3">

    <!-- Topbar Navigation -->
    <div class="view-topbar">
        <div class="view-title-box">
            <h3 class="font-cairo">
                <i class="fas fa-user-shield text-success"></i> Privilegios de Acceso y Acciones
            </h3>
            <p>Control granular de pantallas, permisos CRUD y privilegios especiales por rol en el sistema.</p>
        </div>

        <div class="d-flex align-items-center flex-wrap gap-2">
            <!-- Botón Ver Script SQL -->
            <button type="button" class="btn btn-sm btn-outline-info mr-2" @click="abrirModalSql">
                <i class="fas fa-database mr-1"></i> Formato SQL
            </button>

            <!-- Botón Copiar Permisos -->
            <button type="button" class="btn btn-sm btn-outline-secondary mr-2" @click="abrirModalCopiar">
                <i class="fas fa-copy mr-1"></i> Clonar Permisos
            </button>

            <!-- Botón Nuevo Rol -->
            <button type="button" class="btn btn-sm btn-primary" @click="abrirModalNuevoRol">
                <i class="fas fa-plus mr-1"></i> Nuevo Rol
            </button>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- SELECTOR DE ROLES INTERACTIVO              -->
    <!-- ========================================== -->
    <div class="roles-grid">
        <div
            v-for="r in roles"
            :key="r.cod_rol"
            class="role-card"
            :class="{ active: rolSeleccionado && rolSeleccionado.cod_rol === r.cod_rol }"
            @click="seleccionarRol(r)"
        >
            <div>
                <div class="role-card-header">
                    <h5 class="role-card-title font-cairo">
                        <i class="fas fa-shield-alt mr-1" :style="{ color: r.color_rol || '#0a4d36' }"></i>
                        @{{ r.nom_rol }}
                    </h5>
                    <span class="badge" :class="r.cod_rol === 4 ? 'badge-primary' : 'badge-light'">
                        @{{ r.cod_rol === 4 ? 'Administrador' : 'Rol Sistema' }}
                    </span>
                </div>
                <div class="role-card-desc">
                    @{{ r.descripcion_rol || 'Perfil operativo para usuarios con acceso asignado.' }}
                </div>
            </div>

            <div class="role-card-footer">
                <span class="text-muted">
                    <i class="fas fa-users mr-1"></i> @{{ r.us_count || 0 }} usuario(s)
                </span>
                <span v-if="rolSeleccionado && rolSeleccionado.cod_rol === r.cod_rol" class="text-success font-weight-bold">
                    <i class="fas fa-check-circle mr-1"></i> Editando
                </span>
                <span v-else class="text-muted small">
                    Clic para configurar
                </span>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- PANEL DE CONFIGURACIÓN DEL ROL ACTIVO      -->
    <!-- ========================================== -->
    <div class="card-modern" v-if="rolSeleccionado">
        <!-- Header con detalles del rol y acciones de lote -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pb-3 mb-3 border-bottom">
            <div>
                <span class="small text-muted font-weight-bold text-uppercase">Configurando Permisos para:</span>
                <h4 class="font-cairo font-weight-bold mb-0 text-primary">
                    <i class="fas fa-user-tag mr-1"></i> @{{ rolSeleccionado.nom_rol }}
                    <small class="text-muted" style="font-size: 0.85rem;" v-if="rolSeleccionado.cod_rol === 4">
                        (Rol Administrador: Acceso total garantizado por sistema)
                    </small>
                </h4>
            </div>

            <!-- Botones de Acción Rápida por Lote -->
            <div class="d-flex align-items-center flex-wrap gap-2">
                <button type="button" class="btn btn-xs btn-outline-success font-weight-bold" @click="aplicarLote('TODO_PERMITIDO')">
                    <i class="fas fa-check-double mr-1"></i> Permitir Todo
                </button>
                <button type="button" class="btn btn-xs btn-outline-info font-weight-bold" @click="aplicarLote('SOLO_LECTURA')">
                    <i class="fas fa-eye mr-1"></i> Solo Lectura
                </button>
                <button type="button" class="btn btn-xs btn-outline-danger font-weight-bold" @click="aplicarLote('DENEGADO')">
                    <i class="fas fa-times mr-1"></i> Desmarcar Todo
                </button>
                <button type="button" class="btn btn-xs btn-outline-secondary font-weight-bold" @click="editarRolActual">
                    <i class="fas fa-edit mr-1"></i> Editar Rol
                </button>
                <button
                    type="button"
                    class="btn btn-xs btn-outline-danger font-weight-bold"
                    v-if="rolSeleccionado.cod_rol !== 4 && rolSeleccionado.cod_rol !== 5"
                    @click="eliminarRolActual"
                >
                    <i class="fas fa-trash-alt mr-1"></i> Eliminar
                </button>
            </div>
        </div>

        <!-- Pestañas de Matriz: Módulos vs Acciones Especiales -->
        <div class="nav-tabs-modern">
            <button
                type="button"
                class="nav-tab-item"
                :class="{ active: tabActiva === 'modulos' }"
                @click="tabActiva = 'modulos'"
            >
                <i class="fas fa-th-list"></i> Pantallas y Módulos del Sistema
                <span class="badge badge-secondary ml-1">@{{ formulariosFiltrados.length }}</span>
            </button>
            <button
                type="button"
                class="nav-tab-item"
                :class="{ active: tabActiva === 'acciones' }"
                @click="tabActiva = 'acciones'"
            >
                <i class="fas fa-key"></i> Privilegios Operativos Especiales
                <span class="badge badge-warning ml-1">@{{ acciones.length }}</span>
            </button>
        </div>

        <!-- ============================================== -->
        <!-- SUB-TAB 1: MATRIZ DE MÓDULOS (CRUD + EXPORT)   -->
        <!-- ============================================== -->
        <div v-show="tabActiva === 'modulos'">
            <!-- Filtro de Categorías y Buscador -->
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div class="d-flex flex-wrap align-items-center gap-1">
                    <button
                        type="button"
                        class="chip-filter"
                        :class="{ active: categoriaFiltro === 'TODOS' }"
                        @click="categoriaFiltro = 'TODOS'"
                    >
                        Todos (@{{ formularios.length }})
                    </button>
                    <button
                        type="button"
                        class="chip-filter"
                        v-for="cat in categoriasDisponibles"
                        :key="cat"
                        :class="{ active: categoriaFiltro === cat }"
                        @click="categoriaFiltro = cat"
                    >
                        @{{ cat }}
                    </button>
                </div>

                <!-- Input Buscador -->
                <div style="width: 250px;">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-right-0 text-muted"><i class="fas fa-search"></i></span>
                        </div>
                        <input
                            type="text"
                            class="form-control border-left-0"
                            placeholder="Filtrar módulo..."
                            v-model="busquedaModulo"
                        >
                    </div>
                </div>
            </div>

            <!-- Tabla Matriz de Permisos -->
            <div class="table-responsive border rounded">
                <table class="table table-matrix mb-0">
                    <thead>
                        <tr>
                            <th style="min-width: 220px;">Módulo / Pantalla</th>
                            <th style="width: 105px;" title="Acceso y visualización de la pantalla">
                                <i class="fas fa-door-open mr-1 text-info"></i> Acceso / Ver
                            </th>
                            <th style="width: 105px;" title="Permite registrar o agregar nuevos registros">
                                <i class="fas fa-plus-circle mr-1 text-success"></i> Crear
                            </th>
                            <th style="width: 105px;" title="Permite modificar registros existentes">
                                <i class="fas fa-edit mr-1 text-warning"></i> Modificar
                            </th>
                            <th style="width: 105px;" title="Permite borrar o anular registros">
                                <i class="fas fa-trash-alt mr-1 text-danger"></i> Eliminar
                            </th>
                            <th style="width: 105px;" title="Permite exportar a Excel o imprimir">
                                <i class="fas fa-file-excel mr-1 text-primary"></i> Exportar
                            </th>
                            <th style="width: 85px;">Fila</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="mod in formulariosFiltrados">
                            <tr :key="mod.for_codigo">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2 text-primary" style="font-size: 1.1rem; width: 22px; text-align: center;">
                                            <i :class="mod.for_icono || 'fas fa-window-maximize'"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">@{{ mod.for_titulo }}</div>
                                            <div class="small text-muted">
                                                <code style="font-size: 0.75rem;">@{{ mod.for_nombre }}</code>
                                                <span class="badge badge-light border ml-1" v-if="mod.for_categoria">@{{ mod.for_categoria }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Switch: Abrir / Acceso -->
                                <td>
                                    <label class="perm-switch" title="Habilitar acceso general">
                                        <input
                                            type="checkbox"
                                            v-model="permisosMap[mod.for_codigo].per_open"
                                            :true-value="'1'"
                                            :false-value="'0'"
                                            @change="onOpenChanged(mod.for_codigo)"
                                        >
                                        <span class="perm-slider"></span>
                                    </label>
                                </td>

                                <!-- Switch: Agregar / Crear -->
                                <td>
                                    <label class="perm-switch" title="Permitir crear registros">
                                        <input
                                            type="checkbox"
                                            v-model="permisosMap[mod.for_codigo].per_add"
                                            :true-value="'1'"
                                            :false-value="'0'"
                                            :disabled="permisosMap[mod.for_codigo].per_open !== '1'"
                                        >
                                        <span class="perm-slider"></span>
                                    </label>
                                </td>

                                <!-- Switch: Editar / Modificar -->
                                <td>
                                    <label class="perm-switch" title="Permitir modificar registros">
                                        <input
                                            type="checkbox"
                                            v-model="permisosMap[mod.for_codigo].per_edit"
                                            :true-value="'1'"
                                            :false-value="'0'"
                                            :disabled="permisosMap[mod.for_codigo].per_open !== '1'"
                                        >
                                        <span class="perm-slider"></span>
                                    </label>
                                </td>

                                <!-- Switch: Eliminar / Anular -->
                                <td>
                                    <label class="perm-switch" title="Permitir eliminar o anular">
                                        <input
                                            type="checkbox"
                                            v-model="permisosMap[mod.for_codigo].per_del"
                                            :true-value="'1'"
                                            :false-value="'0'"
                                            :disabled="permisosMap[mod.for_codigo].per_open !== '1'"
                                        >
                                        <span class="perm-slider"></span>
                                    </label>
                                </td>

                                <!-- Switch: Exportar / Imprimir -->
                                <td>
                                    <label class="perm-switch" title="Permitir exportar a Excel / PDF">
                                        <input
                                            type="checkbox"
                                            v-model="permisosMap[mod.for_codigo].per_export"
                                            :true-value="'1'"
                                            :false-value="'0'"
                                            :disabled="permisosMap[mod.for_codigo].per_open !== '1'"
                                        >
                                        <span class="perm-slider"></span>
                                    </label>
                                </td>

                                <!-- Toggle Fila Completa -->
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-xs btn-outline-secondary"
                                        @click="toggleFilaCompleta(mod.for_codigo)"
                                        title="Alternar toda la fila"
                                    >
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- SUB-TAB 2: PRIVILEGIOS Y ACCIONES ESPECIALES   -->
        <!-- ============================================== -->
        <div v-show="tabActiva === 'acciones'">
            <div class="mb-3">
                <p class="small text-muted mb-0">
                    <i class="fas fa-info-circle text-info mr-1"></i>
                    Las acciones especiales regulan operaciones sensibles en tiempo de ejecución (como vender a crédito, alterar precios o reabrir cajas).
                </p>
            </div>

            <div class="action-cards-grid">
                <div class="action-privilege-card" v-for="acc in acciones" :key="acc.cod_per">
                    <div class="action-privilege-info">
                        <h6>
                            <i class="fas fa-lock mr-1 text-warning"></i>
                            @{{ acc.nombre_accion }}
                        </h6>
                        <p>@{{ acc.descripcion_accion }}</p>
                        <div class="d-flex align-items-center gap-2">
                            <span class="action-privilege-badge">
                                Módulo: @{{ acc.formulario ? acc.formulario.for_titulo : 'Sistema' }}
                            </span>
                            <code class="small text-muted">@{{ acc.clave_accion }}</code>
                        </div>
                    </div>

                    <div>
                        <label class="perm-switch" :title="'Autorizar: ' + acc.nombre_accion">
                            <input
                                type="checkbox"
                                v-model="accionesMap[acc.cod_per]"
                                :true-value="1"
                                :false-value="0"
                            >
                            <span class="perm-slider"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- BARRA FLOTANTE PARA GUARDAR CAMBIOS        -->
    <!-- ========================================== -->
    <div class="save-bar-floating" v-if="rolSeleccionado">
        <div>
            <span class="small font-weight-bold text-light">Editando Privilegios:</span>
            <span class="font-cairo font-weight-bold ml-1 text-warning">@{{ rolSeleccionado.nom_rol }}</span>
        </div>

        <button
            type="button"
            class="btn-save-floating font-cairo"
            :disabled="guardando"
            @click="guardarPermisos"
        >
            <i class="fas fa-spinner fa-spin" v-if="guardando"></i>
            <i class="fas fa-save" v-else></i>
            <span>@{{ guardando ? 'Guardando...' : 'Guardar Privilegios' }}</span>
        </button>
    </div>

    <!-- ========================================== -->
    <!-- MODAL: NUEVO / EDITAR ROL                  -->
    <!-- ========================================== -->
    <div class="modal fade" id="modalRol" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-moderno">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold font-cairo mb-0">
                        <i class="fas fa-user-tag text-primary mr-1"></i>
                        @{{ modalRolData.cod_rol ? 'Editar Rol' : 'Crear Nuevo Rol' }}
                    </h5>
                    <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label class="small font-weight-bold">Nombre del Rol <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" v-model="modalRolData.nom_rol" placeholder="Ej: Cajero Principal, Supervisor, Encargado...">
                    </div>

                    <div class="form-group mb-2">
                        <label class="small font-weight-bold">Descripción del Perfil</label>
                        <textarea class="form-control form-control-sm" rows="2" v-model="modalRolData.descripcion_rol" placeholder="Responsabilidades o nivel de acceso del rol..."></textarea>
                    </div>

                    <div class="form-group mb-0">
                        <label class="small font-weight-bold">Color Identificador</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" class="form-control form-control-sm" style="width: 50px; height: 34px; padding: 2px;" v-model="modalRolData.color_rol">
                            <input type="text" class="form-control form-control-sm" v-model="modalRolData.color_rol">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-sm btn-success" :disabled="guardandoRol" @click="guardarRolSubmit">
                        <i class="fas fa-spinner fa-spin" v-if="guardandoRol"></i>
                        <i class="fas fa-check mr-1" v-else></i> Guardar Rol
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL: COPIAR PERMISOS ENTRE ROLES         -->
    <!-- ========================================== -->
    <div class="modal fade" id="modalCopiar" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-moderno">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold font-cairo mb-0">
                        <i class="fas fa-copy text-info mr-1"></i> Clonar Privilegios entre Roles
                    </h5>
                    <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p class="small text-muted mb-3">
                        Copiá todos los permisos de módulos y acciones especiales desde un rol existente hacia otro rol.
                    </p>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold">Rol de Origen (Plantilla a copiar):</label>
                        <select class="form-control form-control-sm" v-model="copiarData.origen">
                            <option v-for="r in roles" :key="'o_' + r.cod_rol" :value="r.cod_rol">
                                @{{ r.nom_rol }}
                            </option>
                        </select>
                    </div>

                    <div class="text-center my-2 text-muted">
                        <i class="fas fa-arrow-down fa-lg"></i>
                    </div>

                    <div class="form-group mb-0">
                        <label class="small font-weight-bold">Rol de Destino (Recibirá los permisos):</label>
                        <select class="form-control form-control-sm" v-model="copiarData.destino">
                            <option v-for="r in roles" :key="'d_' + r.cod_rol" :value="r.cod_rol" :disabled="r.cod_rol === copiarData.origen">
                                @{{ r.nom_rol }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-sm btn-info" :disabled="copiandoPermisos" @click="ejecutarCopia">
                        <i class="fas fa-spinner fa-spin" v-if="copiandoPermisos"></i>
                        <i class="fas fa-copy mr-1" v-else></i> Clonar Permisos
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL: SCRIPT SQL DE MODIFICACIÓN          -->
    <!-- ========================================== -->
    <div class="modal fade" id="modalSql" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content modal-moderno">
                <div class="modal-header d-flex align-items-center justify-content-between">
                    <h5 class="modal-title font-weight-bold font-cairo mb-0">
                        <i class="fas fa-database text-info mr-1"></i> Script SQL de Modificación y Esquema
                    </h5>
                    <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">
                            Sentencias DDL / DML para actualizar las tablas <code>formularios</code>, <code>permiso</code>, <code>accion</code> y <code>accion_rol</code>:
                        </small>
                        <button type="button" class="btn btn-xs btn-outline-info" @click="copiarSqlClipboard">
                            <i class="fas fa-clipboard mr-1"></i> Copiar SQL
                        </button>
                    </div>

                    <div class="sql-code-box" id="sqlBox">-- 1. MODIFICACIONES EN TABLA `formularios`
ALTER TABLE `formularios`
  ADD COLUMN IF NOT EXISTS `for_categoria` VARCHAR(60) NULL DEFAULT 'OPERACIONES' AFTER `for_submenu`,
  ADD COLUMN IF NOT EXISTS `for_icono` VARCHAR(60) NULL DEFAULT 'fas fa-window-maximize' AFTER `for_categoria`,
  ADD COLUMN IF NOT EXISTS `for_ruta` VARCHAR(100) NULL AFTER `for_icono`,
  ADD COLUMN IF NOT EXISTS `for_orden` INT(11) NOT NULL DEFAULT 0 AFTER `for_ruta`,
  ADD COLUMN IF NOT EXISTS `activo` TINYINT(1) NOT NULL DEFAULT 1 AFTER `for_orden`;

-- 2. MODIFICACIONES EN TABLA `permiso`
ALTER TABLE `permiso`
  ADD COLUMN IF NOT EXISTS `per_export` CHAR(1) NOT NULL DEFAULT '0' COMMENT '1: Permite exportar/imprimir' AFTER `per_del`,
  ADD COLUMN IF NOT EXISTS `created_at` TIMESTAMP NULL DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP NULL DEFAULT NULL;

-- 3. MODIFICACIONES EN TABLA `accion`
ALTER TABLE `accion`
  ADD COLUMN IF NOT EXISTS `clave_accion` VARCHAR(60) NULL AFTER `for_codigo`,
  ADD INDEX IF NOT EXISTS `idx_accion_clave` (`clave_accion`);

-- 4. CREACIÓN DE TABLA `accion_rol`
CREATE TABLE IF NOT EXISTS `accion_rol` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cod_per` INT(10) UNSIGNED NOT NULL,
  `cod_rol` INT(10) UNSIGNED NOT NULL,
  `permitido` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `accion_rol_unique` (`cod_per`, `cod_rol`),
  CONSTRAINT `fk_accion_rol_cod_per` FOREIGN KEY (`cod_per`) REFERENCES `accion` (`cod_per`) ON DELETE CASCADE,
  CONSTRAINT `fk_accion_rol_cod_rol` FOREIGN KEY (`cod_rol`) REFERENCES `roles` (`cod_rol`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. MODIFICACIONES EN TABLA `roles`
ALTER TABLE `roles`
  ADD COLUMN IF NOT EXISTS `descripcion_rol` VARCHAR(150) NULL AFTER `nom_rol`,
  ADD COLUMN IF NOT EXISTS `color_rol` VARCHAR(25) NULL DEFAULT '#0a4d36' AFTER `descripcion_rol`,
  ADD COLUMN IF NOT EXISTS `activo` TINYINT(1) NOT NULL DEFAULT 1 AFTER `color_rol`;</div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-sm btn-info" @click="copiarSqlClipboard">
                        <i class="fas fa-copy mr-1"></i> Copiar al Portapapeles
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
            tabActiva: 'modulos',
            roles: @json($roles),
            formularios: @json($formularios),
            acciones: @json($acciones),
            rolSeleccionado: null,

            // Permisos mapeados por for_codigo: { for_codigo: { per_open, per_add, per_edit, per_del, per_export } }
            permisosMap: {},

            // Acciones mapeadas por cod_per: { cod_per: 1 / 0 }
            accionesMap: {},

            categoriaFiltro: 'TODOS',
            busquedaModulo: '',
            guardando: false,

            // Modal Rol
            guardandoRol: false,
            modalRolData: {
                cod_rol: null,
                nom_rol: '',
                descripcion_rol: '',
                color_rol: '#0a4d36'
            },

            // Modal Copiar
            copiandoPermisos: false,
            copiarData: {
                origen: 4,
                destino: 5
            }
        },
        computed: {
            categoriasDisponibles: function () {
                var cats = [];
                this.formularios.forEach(function (f) {
                    if (f.for_categoria && cats.indexOf(f.for_categoria) === -1) {
                        cats.push(f.for_categoria);
                    }
                });
                return cats;
            },
            formulariosFiltrados: function () {
                var cat = this.categoriaFiltro;
                var q = this.busquedaModulo.trim().toLowerCase();

                return this.formularios.filter(function (f) {
                    var matchCat = (cat === 'TODOS') || (f.for_categoria === cat);
                    var matchQ = true;
                    if (q) {
                        var tit = (f.for_titulo || '').toLowerCase();
                        var nom = (f.for_nombre || '').toLowerCase();
                        matchQ = tit.indexOf(q) !== -1 || nom.indexOf(q) !== -1;
                    }
                    return matchCat && matchQ;
                });
            }
        },
        methods: {
            inicializarEstructuras: function () {
                var pMap = {};
                this.formularios.forEach(function (f) {
                    pMap[f.for_codigo] = {
                        per_open: '0',
                        per_add: '0',
                        per_edit: '0',
                        per_del: '0',
                        per_export: '0'
                    };
                });
                this.permisosMap = pMap;

                var aMap = {};
                this.acciones.forEach(function (a) {
                    aMap[a.cod_per] = 0;
                });
                this.accionesMap = aMap;
            },
            seleccionarRol: function (r) {
                this.rolSeleccionado = r;
                this.cargarPermisosDeRol(r.cod_rol);
            },
            cargarPermisosDeRol: function (codRol) {
                var self = this;
                this.inicializarEstructuras();

                axios.get('{{ url("permiso/rol") }}/' + codRol)
                    .then(function (response) {
                        if (response.data && response.data.success) {
                            var pDb = response.data.permisos || {};
                            var aDb = response.data.acciones || {};

                            // Asignar permisos
                            self.formularios.forEach(function (f) {
                                if (pDb[f.for_codigo]) {
                                    var row = pDb[f.for_codigo];
                                    self.$set(self.permisosMap, f.for_codigo, {
                                        per_open: String(row.per_open || '0'),
                                        per_add: String(row.per_add || '0'),
                                        per_edit: String(row.per_edit || '0'),
                                        per_del: String(row.per_del || '0'),
                                        per_export: String(row.per_export || '0')
                                    });
                                } else {
                                    // Si es Administrador y no tenía fila, por defecto activar
                                    var val = (codRol === 4) ? '1' : '0';
                                    self.$set(self.permisosMap, f.for_codigo, {
                                        per_open: val,
                                        per_add: val,
                                        per_edit: val,
                                        per_del: val,
                                        per_export: val
                                    });
                                }
                            });

                            // Asignar acciones
                            self.acciones.forEach(function (a) {
                                if (aDb[a.cod_per] !== undefined) {
                                    self.$set(self.accionesMap, a.cod_per, Number(aDb[a.cod_per].permitido || 0));
                                } else {
                                    self.$set(self.accionesMap, a.cod_per, (codRol === 4 ? 1 : 0));
                                }
                            });
                        }
                    })
                    .catch(function (error) {
                        console.error('Error al cargar permisos del rol:', error);
                    });
            },
            onOpenChanged: function (forCodigo) {
                // Si se desmarca per_open, desmarcar los demás permisos de ese módulo
                if (this.permisosMap[forCodigo].per_open === '0') {
                    this.permisosMap[forCodigo].per_add = '0';
                    this.permisosMap[forCodigo].per_edit = '0';
                    this.permisosMap[forCodigo].per_del = '0';
                    this.permisosMap[forCodigo].per_export = '0';
                }
            },
            toggleFilaCompleta: function (forCodigo) {
                var p = this.permisosMap[forCodigo];
                var nuevo = (p.per_open === '1') ? '0' : '1';
                p.per_open = nuevo;
                p.per_add = nuevo;
                p.per_edit = nuevo;
                p.per_del = nuevo;
                p.per_export = nuevo;
            },
            aplicarLote: function (tipo) {
                var self = this;
                var forms = this.formulariosFiltrados;

                forms.forEach(function (f) {
                    var p = self.permisosMap[f.for_codigo];
                    if (tipo === 'TODO_PERMITIDO') {
                        p.per_open = '1';
                        p.per_add = '1';
                        p.per_edit = '1';
                        p.per_del = '1';
                        p.per_export = '1';
                    } else if (tipo === 'SOLO_LECTURA') {
                        p.per_open = '1';
                        p.per_add = '0';
                        p.per_edit = '0';
                        p.per_del = '0';
                        p.per_export = '1';
                    } else if (tipo === 'DENEGADO') {
                        p.per_open = '0';
                        p.per_add = '0';
                        p.per_edit = '0';
                        p.per_del = '0';
                        p.per_export = '0';
                    }
                });

                if (this.tabActiva === 'acciones') {
                    this.acciones.forEach(function (a) {
                        self.$set(self.accionesMap, a.cod_per, (tipo === 'TODO_PERMITIDO' ? 1 : 0));
                    });
                }
            },
            guardarPermisos: function () {
                if (!this.rolSeleccionado) return;

                var self = this;
                this.guardando = true;

                axios.post('{{ route("permiso.store") }}', {
                    cod_rol: this.rolSeleccionado.cod_rol,
                    permisos: this.permisosMap,
                    acciones: this.accionesMap
                }).then(function (response) {
                    if (response.data && response.data.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Privilegios actualizados correctamente para ' + self.rolSeleccionado.nom_rol,
                            showConfirmButton: false,
                            timer: 2500
                        });
                    } else {
                        Swal.fire('Error', response.data.message || 'No se pudieron guardar los permisos.', 'error');
                    }
                }).catch(function (error) {
                    console.error(error);
                    Swal.fire('Error', 'Ocurrió un error al guardar los permisos.', 'error');
                }).finally(function () {
                    self.guardando = false;
                });
            },

            /* Modales Roles */
            abrirModalNuevoRol: function () {
                this.modalRolData = {
                    cod_rol: null,
                    nom_rol: '',
                    descripcion_rol: '',
                    color_rol: '#0a4d36'
                };
                $('#modalRol').modal('show');
            },
            editarRolActual: function () {
                if (!this.rolSeleccionado) return;
                this.modalRolData = {
                    cod_rol: this.rolSeleccionado.cod_rol,
                    nom_rol: this.rolSeleccionado.nom_rol,
                    descripcion_rol: this.rolSeleccionado.descripcion_rol,
                    color_rol: this.rolSeleccionado.color_rol || '#0a4d36'
                };
                $('#modalRol').modal('show');
            },
            guardarRolSubmit: function () {
                if (!this.modalRolData.nom_rol.trim()) {
                    Swal.fire('Atención', 'Ingresá el nombre del rol.', 'warning');
                    return;
                }

                var self = this;
                this.guardandoRol = true;

                axios.post('{{ route("permiso.rol.guardar") }}', this.modalRolData)
                    .then(function (response) {
                        if (response.data && response.data.success) {
                            $('#modalRol').modal('hide');
                            var rolGuardado = response.data.rol;

                            var idx = self.roles.findIndex(function (r) { return r.cod_rol === rolGuardado.cod_rol; });
                            if (idx !== -1) {
                                self.$set(self.roles, idx, rolGuardado);
                            } else {
                                self.roles.push(rolGuardado);
                            }

                            self.seleccionarRol(rolGuardado);

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: response.data.message,
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                        Swal.fire('Error', 'No se pudo guardar el rol.', 'error');
                    })
                    .finally(function () {
                        self.guardandoRol = false;
                    });
            },
            eliminarRolActual: function () {
                if (!this.rolSeleccionado) return;
                var self = this;

                Swal.fire({
                    title: '¿Eliminar el rol ' + this.rolSeleccionado.nom_rol + '?',
                    text: 'Se eliminarán sus permisos asociados.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#e11d48'
                }).then(function (result) {
                    if (result.value) {
                        axios.delete('{{ url("permiso/rol") }}/' + self.rolSeleccionado.cod_rol)
                            .then(function (response) {
                                if (response.data && response.data.success) {
                                    self.roles = self.roles.filter(function (r) { return r.cod_rol !== self.rolSeleccionado.cod_rol; });
                                    self.seleccionarRol(self.roles[0]);
                                    Swal.fire('Eliminado', 'Rol eliminado correctamente.', 'success');
                                } else {
                                    Swal.fire('Atención', response.data.message, 'warning');
                                }
                            })
                            .catch(function (error) {
                                var msg = (error.response && error.response.data && error.response.data.message)
                                    ? error.response.data.message
                                    : 'No se pudo eliminar el rol.';
                                Swal.fire('Error', msg, 'error');
                            });
                    }
                });
            },

            /* Clonar Permisos */
            abrirModalCopiar: function () {
                if (this.rolSeleccionado) {
                    this.copiarData.destino = this.rolSeleccionado.cod_rol;
                }
                $('#modalCopiar').modal('show');
            },
            ejecutarCopia: function () {
                var self = this;
                this.copiandoPermisos = true;

                axios.post('{{ route("permiso.copiar") }}', this.copiarData)
                    .then(function (response) {
                        if (response.data && response.data.success) {
                            $('#modalCopiar').modal('hide');
                            var rolDest = self.roles.find(function (r) { return r.cod_rol === self.copiarData.destino; });
                            if (rolDest) {
                                self.seleccionarRol(rolDest);
                            }
                            Swal.fire('Completado', 'Permisos clonados exitosamente.', 'success');
                        } else {
                            Swal.fire('Error', response.data.message || 'No se pudieron clonar los permisos.', 'error');
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                        Swal.fire('Error', 'Ocurrió un error al copiar permisos.', 'error');
                    })
                    .finally(function () {
                        self.copiandoPermisos = false;
                    });
            },

            /* Script SQL */
            abrirModalSql: function () {
                $('#modalSql').modal('show');
            },
            copiarSqlClipboard: function () {
                var el = document.getElementById('sqlBox');
                if (el) {
                    navigator.clipboard.writeText(el.innerText).then(function () {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Script SQL copiado al portapapeles',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    });
                }
            }
        },
        mounted: function () {
            this.inicializarEstructuras();
            var rolInicial = this.roles.find(function (r) { return r.cod_rol === {{ $rolSeleccionadoId ?? 4 }}; }) || this.roles[0];
            if (rolInicial) {
                this.seleccionarRol(rolInicial);
            }
        }
    });

    activarMenu('m_mantenimiento', 'm_permiso');
</script>
@endsection
