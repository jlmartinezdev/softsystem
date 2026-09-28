@extends('layouts.app')
@section('title', 'Gestión de Usuarios')

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

    /* Primary POS Button */
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
        border-radius: 10px;
        transition: all 0.15s ease-in-out;
        text-decoration: none !important;
        box-shadow: 0 2px 6px rgba(10, 77, 54, 0.2);
        cursor: pointer;
    }
    .btn-pos-primary:hover {
        background: var(--dash-primary-dark);
        border-color: var(--dash-primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(10, 77, 54, 0.3);
    }

    /* KPI Summary Cards */
    .kpi-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
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
    .kpi-label {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--dash-text-muted);
        font-weight: 700;
        margin-bottom: 0.2rem;
    }
    .kpi-val {
        font-size: 1.45rem;
        font-weight: 700;
        color: var(--dash-text-main);
        line-height: 1;
        margin: 0;
    }

    /* Filters Card */
    .card-filter {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        padding: 0.85rem 1rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }
    .search-input-wrap {
        position: relative;
        flex: 1;
    }
    .search-input-wrap i.fa-search {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--dash-text-muted);
        font-size: 0.9rem;
    }
    .search-input-wrap input {
        width: 100%;
        padding: 0.5rem 1rem 0.5rem 2.4rem;
        border-radius: 10px;
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        font-size: 0.9rem;
        transition: all 0.15s ease;
    }
    .search-input-wrap input:focus {
        outline: none;
        border-color: var(--dash-primary);
        background: var(--dash-card-bg);
        box-shadow: 0 0 0 3px var(--dash-primary-light);
    }
    .filter-select {
        border-radius: 10px;
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        padding: 0.5rem 0.85rem;
        font-size: 0.88rem;
        font-weight: 600;
    }
    .filter-select:focus {
        outline: none;
        border-color: var(--dash-primary);
        background: var(--dash-card-bg);
    }

    /* Main Table Card */
    .card-table {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }
    .card-table-header {
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--dash-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--dash-card-bg);
    }
    .card-table-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--dash-text-main);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Modern Table */
    .dash-table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    .dash-table th {
        background: var(--dash-panel-bg);
        color: var(--dash-text-muted);
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 700;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--dash-border);
        border-top: none;
        white-space: nowrap;
    }
    .dash-table td {
        padding: 0.85rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--dash-border);
        color: var(--dash-text-main);
        font-size: 0.9rem;
    }
    .dash-table tbody tr {
        transition: background-color 0.12s ease;
    }
    .dash-table tbody tr:hover {
        background-color: var(--dash-primary-light);
    }
    body.dark-mode .dash-table tbody tr:hover {
        background-color: rgba(16, 185, 129, 0.08);
    }
    .dash-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* User Avatar and Badges */
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.92rem;
        color: #ffffff;
        flex-shrink: 0;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.12);
        letter-spacing: 0.5px;
    }
    .avatar-admin {
        background: linear-gradient(135deg, #10b981, #047857);
    }
    .avatar-vendedor {
        background: linear-gradient(135deg, #0284c7, #0369a1);
    }
    .avatar-stock {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }
    .avatar-default {
        background: linear-gradient(135deg, #64748b, #475569);
    }

    .user-info-name {
        font-weight: 700;
        color: var(--dash-text-main);
        line-height: 1.2;
    }
    .user-info-handle {
        font-size: 0.78rem;
        color: var(--dash-text-muted);
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    }
    .badge-you {
        font-size: 0.7rem;
        padding: 0.15rem 0.4rem;
        border-radius: 4px;
        background: var(--dash-primary-light);
        color: var(--dash-primary);
        border: 1px solid var(--dash-primary-border);
        font-weight: 700;
        margin-left: 0.35rem;
    }
    body.dark-mode .badge-you {
        background: #064e3b;
        color: #a7f3d0;
        border-color: #047857;
    }

    /* Role Badges */
    .badge-role {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.65rem;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.3px;
    }
    .badge-role-admin {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .badge-role-vendedor {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }
    .badge-role-stock {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    .badge-role-default {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    body.dark-mode .badge-role-admin {
        background: #064e3b;
        color: #a7f3d0;
        border-color: #047857;
    }
    body.dark-mode .badge-role-vendedor {
        background: #0c4a6e;
        color: #bae6fd;
        border-color: #0284c7;
    }
    body.dark-mode .badge-role-stock {
        background: #451a03;
        color: #fde68a;
        border-color: #78350f;
    }
    body.dark-mode .badge-role-default {
        background: #334155;
        color: #cbd5e1;
        border-color: #475569;
    }

    /* Action Buttons */
    .btn-action-group {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .btn-action {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        color: var(--dash-text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .btn-action-edit:hover {
        background: var(--dash-primary-light);
        border-color: var(--dash-primary);
        color: var(--dash-primary);
        transform: translateY(-1px);
    }
    .btn-action-delete:hover {
        background: var(--dash-danger-light);
        border-color: var(--dash-danger);
        color: var(--dash-danger);
        transform: translateY(-1px);
    }
    .btn-action[disabled] {
        opacity: 0.35;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* Modal Styling */
    .modal-moderno {
        background-color: var(--dash-card-bg);
        border: 1px solid var(--dash-border) !important;
        border-radius: 16px;
        color: var(--dash-text-main);
        overflow: hidden;
    }
    .modal-moderno .modal-header {
        border-bottom: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        padding: 1rem 1.25rem;
    }
    .modal-moderno .modal-footer {
        border-top: 1px solid var(--dash-border);
        padding: 0.85rem 1.25rem;
        background: var(--dash-panel-bg);
    }
    .form-section-title {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--dash-text-muted);
        font-weight: 700;
        margin-bottom: 0.65rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .modal-field-label {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--dash-text-main);
        margin-bottom: 0.25rem;
        display: block;
    }
    .modal-moderno .form-control {
        border-radius: 8px;
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem;
    }
    .modal-moderno .form-control:focus {
        border-color: var(--dash-primary);
        background: var(--dash-card-bg);
        box-shadow: 0 0 0 3px var(--dash-primary-light);
    }
    .password-wrapper {
        position: relative;
    }
    .password-toggle-btn {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        color: var(--dash-text-muted);
        cursor: pointer;
        padding: 2px 6px;
    }
    .password-toggle-btn:hover {
        color: var(--dash-primary);
    }

    /* Role Info Callout */
    .role-info-box {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        background: var(--dash-panel-bg);
        border: 1px dashed var(--dash-border);
        font-size: 0.78rem;
        color: var(--dash-text-muted);
        line-height: 1.35;
    }

    /* Empty state */
    .empty-state-box {
        padding: 3rem 1.5rem;
        text-align: center;
        color: var(--dash-text-muted);
    }
    .empty-state-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--dash-panel-bg);
        border: 1px solid var(--dash-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: var(--dash-text-muted);
        margin: 0 auto 0.75rem;
    }
</style>
@endsection

@section('main')
<div class="container-fluid px-3 px-md-4 py-3" id="app" v-cloak>
    <!-- Header -->
    <div class="dash-header">
        <div class="dash-header-left">
            <div class="dash-header-icon">
                <i class="fas fa-users-cog"></i>
            </div>
            <div>
                <h1 class="dash-header-title font-cairo">Gestión de Usuarios y Accesos</h1>
                <p class="dash-header-subtitle">Control de cuentas de operadores, asignación de roles y permisos del sistema</p>
            </div>
        </div>
        <div>
            <button type="button" class="btn-pos-primary font-cairo" @click="nuevoUsuario">
                <i class="fas fa-user-plus"></i> Nuevo Usuario
            </button>
        </div>
    </div>

    <!-- KPI Summary Row -->
    <div class="kpi-row">
        <div class="kpi-card">
            <div class="kpi-icon-box kpi-icon-green">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="kpi-label">Total Operadores</div>
                <div class="kpi-val font-cairo">@{{ usuarios.length }}</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon-box kpi-icon-blue">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div>
                <div class="kpi-label">Administradores</div>
                <div class="kpi-val font-cairo">@{{ totalAdmins }}</div>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon-box kpi-icon-amber">
                <i class="fas fa-cash-register"></i>
            </div>
            <div>
                <div class="kpi-label">Vendedores / Stock</div>
                <div class="kpi-val font-cairo">@{{ totalOperativos }}</div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card-filter">
        <div class="row align-items-center">
            <div class="col-md-5 mb-2 mb-md-0">
                <div class="search-input-wrap">
                    <i class="fa fa-search" aria-hidden="true"></i>
                    <label for="buscarUsuario" class="sr-only">Buscar usuario</label>
                    <input type="text" id="buscarUsuario" v-model="filtroTexto" placeholder="Buscar por nombre, @usuario, teléfono o cargo..." autocomplete="off">
                </div>
            </div>
            <div class="col-6 col-md-3 mb-2 mb-md-0">
                <select class="form-control filter-select" v-model="filtroRol">
                    <option value="">Todos los roles</option>
                    @foreach($roles as $r)
                        <option value="{{ $r->cod_rol }}">{{ $r->nom_rol }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3 mb-2 mb-md-0">
                <select class="form-control filter-select" v-model="filtroCargo">
                    <option value="">Todos los cargos</option>
                    @foreach($cargo as $c)
                        <option value="{{ $c->cod_cargo }}">{{ $c->nom_cargo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 text-md-right" v-if="filtroTexto || filtroRol || filtroCargo">
                <button type="button" class="btn btn-outline-secondary btn-sm w-100" @click="limpiarFiltros" title="Limpiar filtros">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card-table">
        <div class="card-table-header">
            <h2 class="card-table-title font-cairo">
                <i class="fas fa-list text-muted"></i> Lista de Operadores
            </h2>
            <span class="badge badge-light border text-muted" style="font-size: 0.8rem;">
                Mostrando @{{ usuariosFiltrados.length }} de @{{ usuarios.length }}
            </span>
        </div>
        <div class="table-responsive">
            <table class="table dash-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Operador</th>
                        <th>Rol de Acceso</th>
                        <th>Cargo</th>
                        <th>Contacto</th>
                        <th style="width: 110px;" class="text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody v-if="usuariosFiltrados.length">
                    <tr v-for="(u, idx) in usuariosFiltrados" :key="u.cod_usuarios">
                        <td class="text-muted font-weight-bold" style="font-size: 0.82rem;">
                            @{{ idx + 1 }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar mr-2" :class="getAvatarClass(u.cod_rol)">
                                    @{{ getIniciales(u.nom_usuarios) }}
                                </div>
                                <div>
                                    <div class="user-info-name">
                                        @{{ trim(u.nom_usuarios) }}
                                        <span class="badge-you" v-if="currentUserId && Number(u.cod_usuarios) === Number(currentUserId)">Tú</span>
                                    </div>
                                    <div class="user-info-handle">
                                        @@{{ trim(u.user_usuarios) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-role" :class="getRoleBadgeClass(u.cod_rol)">
                                <i :class="getRoleIcon(u.cod_rol)"></i>
                                @{{ u.nom_rol || 'Sin rol' }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted font-weight-600" style="font-size: 0.88rem;">
                                <i class="fas fa-briefcase mr-1 text-muted" style="font-size: 0.8rem;"></i>
                                @{{ u.nom_cargo || 'General' }}
                            </span>
                        </td>
                        <td>
                            <div>
                                <span v-if="u.tel_usuarios && trim(u.tel_usuarios) !== '-' && trim(u.tel_usuarios) !== '0'" class="d-inline-flex align-items-center mr-3 font-weight-600 text-dark-mode" style="font-size: 0.85rem;">
                                    <i class="fab fa-whatsapp mr-1 text-success"></i>
                                    <a :href="'https://wa.me/' + cleanPhone(u.tel_usuarios)" target="_blank" class="text-dark-mode text-decoration-none">
                                        @{{ trim(u.tel_usuarios) }}
                                    </a>
                                </span>
                                <span v-else class="text-muted small font-italic mr-2">Sin celular</span>

                                <span v-if="u.direcc_usuarios && trim(u.direcc_usuarios) !== '-'" class="d-inline-block text-muted small text-truncate" style="max-width: 180px;" :title="trim(u.direcc_usuarios)">
                                    <i class="fas fa-map-marker-alt mr-1 text-secondary"></i>
                                    @{{ trim(u.direcc_usuarios) }}
                                </span>
                            </div>
                        </td>
                        <td class="text-right">
                            <div class="btn-action-group justify-content-end">
                                <button type="button" class="btn-action btn-action-edit" @click="edit(u)" title="Editar datos y permisos">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button type="button" class="btn-action btn-action-delete"
                                    :disabled="currentUserId && Number(u.cod_usuarios) === Number(currentUserId)"
                                    @click="del(u)"
                                    :title="currentUserId && Number(u.cod_usuarios) === Number(currentUserId) ? 'No podés eliminar tu propia cuenta en sesión' : 'Eliminar usuario'">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="6" class="p-0">
                            <div class="empty-state-box">
                                <div class="empty-state-icon">
                                    <i class="fas fa-user-slash"></i>
                                </div>
                                <h5 class="font-weight-bold font-cairo mb-1">No se encontraron usuarios</h5>
                                <p class="small text-muted mb-2">No hay operadores que coincidan con los filtros aplicados.</p>
                                <button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold" @click="limpiarFiltros" v-if="filtroTexto || filtroRol || filtroCargo">
                                    <i class="fas fa-undo mr-1"></i> Restablecer filtros
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Formulario Usuario -->
    <div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content modal-moderno shadow-lg">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div class="modal-header-icon mr-2">
                            <i class="fas" :class="user.band == 0 ? 'fa-user-plus text-success' : 'fa-user-edit text-primary'"></i>
                        </div>
                        <div>
                            <h2 class="modal-title h5 mb-0 font-weight-bold font-cairo" id="modalUsuarioLabel">
                                @{{ user.band == 0 ? 'Crear Nuevo Usuario' : 'Editar Usuario: ' + user.nombre }}
                            </h2>
                            <p class="small text-muted mb-0">
                                @{{ user.band == 0 ? 'Completá los datos y credenciales para habilitar el acceso' : 'Actualizá los datos personales, contraseña o rol del operador' }}
                            </p>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <form @submit.prevent="save">
                        <!-- Sección 1: Datos Personales -->
                        <div class="mb-3">
                            <div class="form-section-title">
                                <i class="fas fa-id-card text-muted"></i> Datos Personales
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="usr-nombre" class="modal-field-label">Nombre y Apellido *</label>
                                    <input id="usr-nombre" class="form-control" type="text" placeholder="Ej: Juan Pérez" v-model.trim="user.nombre" required />
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="usr-celular" class="modal-field-label">Celular / Teléfono</label>
                                    <input id="usr-celular" class="form-control" type="text" placeholder="Ej: 0981 123456" v-model.trim="user.celular" />
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="usr-direccion" class="modal-field-label">Dirección</label>
                                    <input id="usr-direccion" class="form-control" type="text" placeholder="Ej: Asunción, Barrio San Vicente" v-model.trim="user.direccion" />
                                </div>
                            </div>
                        </div>

                        <!-- Sección 2: Credenciales de Acceso -->
                        <div class="mb-3">
                            <div class="form-section-title">
                                <i class="fas fa-key text-muted"></i> Credenciales de Acceso al Sistema
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="usr-usuario" class="modal-field-label">Nombre de Usuario (Login) *</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0 text-muted">@</span>
                                        </div>
                                        <input id="usr-usuario" class="form-control border-left-0" type="text" placeholder="Ej: jperez" v-model.trim="user.usuario" required autocomplete="off" />
                                    </div>
                                    <small class="text-muted">Nombre único para iniciar sesión.</small>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="usr-password" class="modal-field-label">
                                        Contraseña
                                        <span v-if="user.band == 0" class="text-danger">*</span>
                                    </label>
                                    <div class="password-wrapper">
                                        <input id="usr-password" class="form-control" :type="showPassword ? 'text' : 'password'" placeholder="Contraseña..." v-model="user.password" :required="user.band == 0" autocomplete="new-password" />
                                        <button type="button" class="password-toggle-btn" @click="showPassword = !showPassword" tabindex="-1" title="Ver / ocultar contraseña">
                                            <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted" v-if="user.band == 1">
                                        Dejá este campo vacío si deseás conservar la contraseña actual.
                                    </small>
                                    <small class="text-muted" v-else>
                                        Mínimo recomendado: 4 caracteres alfanuméricos.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Sección 3: Permisos y Cargo -->
                        <div class="mb-2">
                            <div class="form-section-title">
                                <i class="fas fa-user-shield text-muted"></i> Nivel de Permisos y Cargo
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="usr-rol" class="modal-field-label">Rol de Acceso *</label>
                                    <select id="usr-rol" class="form-control" v-model="user.rol" required>
                                        @foreach($roles as $r)
                                            <option value="{{ $r->cod_rol }}">{{ $r->nom_rol }}</option>
                                        @endforeach
                                    </select>
                                    <div class="role-info-box mt-2">
                                        <i class="fas fa-info-circle mr-1 text-primary"></i>
                                        @{{ getRoleDescription(user.rol) }}
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label for="usr-cargo" class="modal-field-label">Cargo / Puesto *</label>
                                    <select id="usr-cargo" class="form-control" v-model="user.cargo" required>
                                        @foreach($cargo as $c)
                                            <option value="{{ $c->cod_cargo }}">{{ $c->nom_cargo }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted d-block mt-2">Puesto o función formal del operador en el negocio.</small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-pos-primary font-cairo" @click="save" :disabled="guardando">
                        <i class="fas mr-1" :class="guardando ? 'fa-spinner fa-spin' : (user.band == 0 ? 'fa-check' : 'fa-save')"></i>
                        @{{ user.band == 0 ? 'Crear Usuario' : 'Actualizar Usuario' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    var app = new Vue({
        el: '#app',
        data: {
            usuarios: @json($usuario),
            roles: @json($roles),
            cargos: @json($cargo),
            currentUserId: {{ $currentUserId ?? 'null' }},
            filtroTexto: '',
            filtroRol: '',
            filtroCargo: '',
            showPassword: false,
            guardando: false,
            user: {
                codigo: '',
                nombre: '',
                celular: '',
                direccion: '',
                usuario: '',
                password: '',
                rol: {{ $roles[0]->cod_rol ?? 4 }},
                cargo: {{ $cargo[0]->cod_cargo ?? 4 }},
                band: 0
            }
        },
        computed: {
            usuariosFiltrados: function () {
                var q = (this.filtroTexto || '').toLowerCase().trim();
                var rol = this.filtroRol ? String(this.filtroRol) : '';
                var cargo = this.filtroCargo ? String(this.filtroCargo) : '';

                return this.usuarios.filter(function (u) {
                    if (rol && String(u.cod_rol) !== rol) return false;
                    if (cargo && String(u.cod_cargo) !== cargo) return false;

                    if (!q) return true;

                    var nom = (u.nom_usuarios || '').toLowerCase();
                    var usr = (u.user_usuarios || '').toLowerCase();
                    var tel = (u.tel_usuarios || '').toLowerCase();
                    var dir = (u.direcc_usuarios || '').toLowerCase();
                    var rnom = (u.nom_rol || '').toLowerCase();
                    var cnom = (u.nom_cargo || '').toLowerCase();

                    return nom.indexOf(q) !== -1
                        || usr.indexOf(q) !== -1
                        || tel.indexOf(q) !== -1
                        || dir.indexOf(q) !== -1
                        || rnom.indexOf(q) !== -1
                        || cnom.indexOf(q) !== -1;
                });
            },
            totalAdmins: function () {
                return this.usuarios.filter(function (u) {
                    return Number(u.cod_rol) === 4 || (u.nom_rol && u.nom_rol.toLowerCase().indexOf('admin') !== -1);
                }).length;
            },
            totalOperativos: function () {
                var total = this.usuarios.length;
                return Math.max(0, total - this.totalAdmins);
            }
        },
        methods: {
            trim: function (val) {
                return (val || '').toString().trim();
            },
            cleanPhone: function (val) {
                return (val || '').toString().replace(/[^0-9]/g, '');
            },
            getIniciales: function (nombre) {
                var clean = (nombre || '').toString().trim();
                if (!clean) return 'US';
                var parts = clean.split(/\s+/);
                if (parts.length >= 2) {
                    return (parts[0][0] + parts[1][0]).toUpperCase();
                }
                return clean.substring(0, 2).toUpperCase();
            },
            getAvatarClass: function (cod_rol) {
                var r = Number(cod_rol);
                if (r === 4) return 'avatar-admin';
                if (r === 5) return 'avatar-vendedor';
                if (r === 6) return 'avatar-stock';
                return 'avatar-default';
            },
            getRoleBadgeClass: function (cod_rol) {
                var r = Number(cod_rol);
                if (r === 4) return 'badge-role-admin';
                if (r === 5) return 'badge-role-vendedor';
                if (r === 6) return 'badge-role-stock';
                return 'badge-role-default';
            },
            getRoleIcon: function (cod_rol) {
                var r = Number(cod_rol);
                if (r === 4) return 'fas fa-shield-alt';
                if (r === 5) return 'fas fa-cash-register';
                if (r === 6) return 'fas fa-boxes';
                return 'fas fa-user';
            },
            getRoleDescription: function (cod_rol) {
                var r = Number(cod_rol);
                if (r === 4) {
                    return 'Administrador: Control total de informes, configuración SIFEN, cajas, compras y usuarios.';
                }
                if (r === 5) {
                    return 'Vendedor: Acceso enfocado en el punto de venta (POS), cobros, clientes y emisión de tickets.';
                }
                if (r === 6) {
                    return 'Ajuste de Stock: Permisos específicos para inventario, entradas/salidas y control de existencias.';
                }
                return 'Rol personalizado con los permisos definidos para este perfil de operador.';
            },
            limpiarFiltros: function () {
                this.filtroTexto = '';
                this.filtroRol = '';
                this.filtroCargo = '';
            },
            nuevoUsuario: function () {
                var defaultRol = this.roles && this.roles.length ? this.roles[0].cod_rol : 4;
                var defaultCargo = this.cargos && this.cargos.length ? this.cargos[0].cod_cargo : 4;

                this.user = {
                    codigo: '',
                    nombre: '',
                    celular: '',
                    direccion: '',
                    usuario: '',
                    password: '',
                    rol: defaultRol,
                    cargo: defaultCargo,
                    band: 0
                };
                this.showPassword = false;
                $('#modalUsuario').modal('show');
                this.$nextTick(function () {
                    var el = document.getElementById('usr-nombre');
                    if (el) el.focus();
                });
            },
            edit: function (u) {
                this.user = {
                    codigo: u.cod_usuarios,
                    nombre: this.trim(u.nom_usuarios),
                    celular: this.trim(u.tel_usuarios),
                    direccion: this.trim(u.direcc_usuarios),
                    usuario: this.trim(u.user_usuarios),
                    password: '',
                    rol: u.cod_rol,
                    cargo: u.cod_cargo,
                    band: 1
                };
                this.showPassword = false;
                $('#modalUsuario').modal('show');
                this.$nextTick(function () {
                    var el = document.getElementById('usr-nombre');
                    if (el) el.focus();
                });
            },
            save: function () {
                var self = this;
                var nombre = (this.user.nombre || '').trim();
                var usuario = (this.user.usuario || '').trim();

                if (!nombre || !usuario) {
                    Swal.fire('Campos requeridos', 'Completá el Nombre y el Nombre de Usuario para continuar.', 'warning');
                    return;
                }

                if (this.user.band === 0 && !this.user.password) {
                    Swal.fire('Contraseña requerida', 'Ingresá una contraseña para el nuevo usuario.', 'warning');
                    return;
                }

                this.guardando = true;

                axios.post('{{ url("usuario") }}', this.user)
                    .then(function (response) {
                        self.guardando = false;
                        $('#modalUsuario').modal('hide');
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: self.user.band === 0 ? 'Usuario creado exitosamente' : 'Usuario actualizado exitosamente',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 600);
                    })
                    .catch(function (error) {
                        self.guardando = false;
                        var msg = 'Ocurrió un error al guardar.';
                        if (error.response && error.response.data) {
                            if (error.response.data.error) {
                                msg = error.response.data.error;
                            } else if (error.response.data.message) {
                                msg = error.response.data.message;
                            }
                        }
                        Swal.fire('Atención', msg, 'error');
                    });
            },
            del: function (u) {
                var self = this;
                var nombre = this.trim(u.nom_usuarios);
                var id = u.cod_usuarios;

                Swal.fire({
                    title: '¿Eliminar este operador?',
                    html: 'Se eliminará el acceso para <strong>' + nombre + '</strong> (@' + this.trim(u.user_usuarios) + ').',
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Sí, eliminar',
                    confirmButtonColor: '#ef4444',
                    focusCancel: true
                }).then(function (result) {
                    if (result.value) {
                        axios.delete('{{ url("usuario") }}/' + id)
                            .then(function (r) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Usuario eliminado.',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                setTimeout(function () {
                                    location.reload();
                                }, 600);
                            })
                            .catch(function (e) {
                                var msg = 'No se pudo eliminar el usuario.';
                                if (e.response && e.response.data && e.response.data.error) {
                                    msg = e.response.data.error;
                                }
                                Swal.fire('No se puede eliminar', msg, 'error');
                            });
                    }
                });
            }
        }
    });

    activarMenu('m_mantenimiento', 'm_usuario');
</script>
@endsection