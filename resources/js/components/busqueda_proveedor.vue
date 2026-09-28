<template>
    <div class="busqueda-proveedor-wrapper">
        <div class="modal fade" id="busquedaProveedor" tabindex="-1" role="dialog" aria-labelledby="busquedaProveedorLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content modal-moderno shadow-lg border-0">
                    
                    <!-- Modal Header Moderno -->
                    <div class="modal-header d-flex align-items-center justify-content-between p-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="modal-header-icon mr-2.5">
                                <i class="fa fa-truck-loading text-success"></i>
                            </div>
                            <div>
                                <h5 class="modal-title font-weight-bold font-cairo mb-0" id="busquedaProveedorLabel">
                                    Seleccionar Proveedor
                                </h5>
                                <small class="text-muted">
                                    Buscá por razón social, RUC o teléfono · Clic para seleccionar
                                </small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <a href="/proveedor" target="_blank" class="btn btn-outline-primary btn-sm mr-2 font-weight-bold d-none d-sm-inline-flex align-items-center" title="Abrir catálogo de proveedores en nueva pestaña">
                                <i class="fa fa-plus mr-1"></i> Nuevo Proveedor
                            </a>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body p-3">
                        <!-- Buscador Inteligente -->
                        <div class="buscador-toolbar mb-3">
                            <div class="input-group search-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text search-prepend border-right-0">
                                        <i class="fa fa-search text-muted"></i>
                                    </span>
                                </div>
                                <input
                                    type="text"
                                    ref="searchInput"
                                    v-model="txtproveedor"
                                    @input="onSearchInput"
                                    @keyup.enter="buscarInmediato"
                                    class="form-control search-input border-left-0"
                                    placeholder="Escribí nombre de fantasía, razón social, RUC o teléfono..."
                                    autocomplete="off"
                                />
                                <div class="input-group-append" v-if="txtproveedor">
                                    <button class="btn btn-clear border-left-0 border-right-0" type="button" @click="limpiarBusqueda" title="Borrar búsqueda">
                                        <i class="fa fa-times text-muted"></i>
                                    </button>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-pos-primary px-3 font-weight-bold" type="button" @click="buscarInmediato">
                                        <template v-if="requestSend">
                                            <span class="spinner-border spinner-border-sm mr-1" role="status"></span> Buscando...
                                        </template>
                                        <template v-else>
                                            <i class="fa fa-search mr-1"></i> Buscar
                                        </template>
                                    </button>
                                </div>
                            </div>

                            <!-- Meta info & contadores -->
                            <div class="d-flex justify-content-between align-items-center mt-2 px-1">
                                <span class="small text-muted font-weight-bold">
                                    <i class="fa fa-building mr-1 text-success"></i> {{ proveedores.length }} proveedor(es) disponible(s)
                                </span>
                                <span v-if="requestSend" class="badge badge-success-soft">
                                    <i class="fa fa-circle-notch fa-spin mr-1"></i> Consultando...
                                </span>
                            </div>
                        </div>

                        <!-- Tabla Moderna de Resultados -->
                        <div class="table-responsive border rounded-lg proveedor-tabla-wrap">
                            <table class="table table-hover table-proveedores mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 130px;">RUC</th>
                                        <th>Razón Social / Nombre</th>
                                        <th>Contacto / Teléfono</th>
                                        <th>Dirección</th>
                                        <th class="text-right" style="width: 100px;">Acción</th>
                                    </tr>
                                </thead>
                                <tbody v-if="requestSend && !proveedores.length">
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="spinner-border text-success mb-2" role="status"></div>
                                            <div class="font-weight-bold text-muted">Cargando lista de proveedores...</div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else-if="proveedores.length">
                                    <tr
                                        v-for="p in proveedores"
                                        :key="p.PROVEEDOR_cod"
                                        class="proveedor-fila"
                                        @click="selproveedor(p)"
                                        title="Clic para seleccionar este proveedor"
                                    >
                                        <td>
                                            <span class="badge badge-ruc">
                                                <i class="fa fa-id-card mr-1 text-muted"></i>{{ p.proveedor_ruc || 'S/ RUC' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="proveedor-nombre font-cairo font-weight-bold">
                                                {{ p.proveedor_nombre }}
                                            </div>
                                            <small class="text-muted">ID: #{{ p.PROVEEDOR_cod }}</small>
                                        </td>
                                        <td>
                                            <span v-if="p.proveedor_telef" class="badge badge-telef">
                                                <i class="fa fa-phone mr-1 text-muted"></i>{{ p.proveedor_telef }}
                                            </span>
                                            <span v-else class="text-muted small">Sin teléfono</span>
                                        </td>
                                        <td>
                                            <span class="small text-muted text-truncate d-inline-block" style="max-width: 220px;" :title="p.proveedor_direc">
                                                <i class="fa fa-map-marker-alt mr-1 text-muted" v-if="p.proveedor_direc"></i>
                                                {{ p.proveedor_direc || 'Sin dirección' }}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-seleccionar font-weight-bold"
                                                @click.stop="selproveedor(p)"
                                            >
                                                <i class="fa fa-check mr-1"></i> Elegir
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="empty-icon mb-2">
                                                <i class="fa fa-building fa-3x text-muted" style="opacity: 0.35;"></i>
                                            </div>
                                            <h6 class="font-weight-bold mb-1 font-cairo">No encontramos proveedores</h6>
                                            <p class="small text-muted mb-3" v-if="txtproveedor">
                                                No hay resultados para "<strong>{{ txtproveedor }}</strong>". Probá con otro término o RUC.
                                            </p>
                                            <p class="small text-muted mb-3" v-else>
                                                No hay proveedores disponibles en la base de datos.
                                            </p>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button
                                                    v-if="txtproveedor"
                                                    type="button"
                                                    class="btn btn-outline-secondary btn-sm mr-2"
                                                    @click="limpiarBusqueda"
                                                >
                                                    <i class="fa fa-undo mr-1"></i> Ver todos los proveedores
                                                </button>
                                                <a href="/proveedor" target="_blank" class="btn btn-success btn-sm">
                                                    <i class="fa fa-plus mr-1"></i> Registrar Nuevo Proveedor
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal Footer Moderno -->
                    <div class="modal-footer d-flex align-items-center justify-content-between p-2.5 border-top bg-light-panel">
                        <span class="small text-muted">
                            <i class="fa fa-lightbulb mr-1 text-warning"></i> Podés hacer clic en cualquier parte de la fila para seleccionarlo al instante.
                        </span>
                        <button type="button" class="btn btn-secondary px-3 font-weight-bold" data-dismiss="modal">
                            <i class="fa fa-times mr-1"></i> Cerrar
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'busquedaproveedor',
    data() {
        return {
            proveedores: [],
            proveedor: {},
            txtproveedor: '',
            requestSend: false,
            searchTimer: null
        };
    },
    methods: {
        onSearchInput() {
            clearTimeout(this.searchTimer);
            this.searchTimer = setTimeout(() => {
                this.buscar();
            }, 250);
        },
        buscarInmediato() {
            clearTimeout(this.searchTimer);
            this.buscar();
        },
        buscar() {
            var self = this;
            self.requestSend = true;
            var term = (self.txtproveedor || '').trim();
            var url = term.length > 0 ? 'proveedor/buscar' : 'proveedor/all';
            var params = term.length > 0 ? { nombre: term } : {};

            axios.get(url, { params: params })
                .then(response => {
                    self.requestSend = false;
                    self.proveedores = response.data || [];
                })
                .catch(error => {
                    self.requestSend = false;
                    console.error('Error al buscar proveedor:', error.message);
                });
        },
        limpiarBusqueda() {
            this.txtproveedor = '';
            this.buscarInmediato();
            this.$nextTick(() => {
                if (this.$refs.searchInput) {
                    this.$refs.searchInput.focus();
                }
            });
        },
        selproveedor(proveedor) {
            this.$emit('set_proveedor', proveedor);
        }
    },
    mounted() {
        this.buscar();
        var self = this;
        // Autoenfoque al abrir el modal
        if (typeof $ !== 'undefined') {
            $('#busquedaProveedor').on('shown.bs.modal', function () {
                if (self.$refs.searchInput) {
                    self.$refs.searchInput.focus();
                    self.$refs.searchInput.select();
                }
            });
        }
    }
};
</script>

<style scoped>
.modal-moderno {
    border-radius: 16px;
    overflow: hidden;
    background: var(--dash-card-bg, #ffffff);
    color: var(--dash-text-main, #1c2430);
    border: 1px solid var(--dash-border, #e2e8f0) !important;
}

body.dark-mode .modal-moderno {
    background: #1f2937;
    color: #f3f4f6;
    border-color: #374151 !important;
}

.modal-header-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: var(--dash-primary-light, #eaf3ef);
    border: 1px solid var(--dash-primary-border, #c8dfd5);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
}

body.dark-mode .modal-header-icon {
    background: #064e3b;
    border-color: #047857;
}

.bg-light-panel {
    background-color: var(--dash-panel-bg, #f8fafc) !important;
}

body.dark-mode .bg-light-panel {
    background-color: #111827 !important;
}

/* Buscador Toolbar */
.search-group {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    border-radius: 10px;
}

.search-prepend {
    background-color: var(--dash-panel-bg, #f8fafc) !important;
    border-color: var(--dash-border, #e2e8f0) !important;
    border-top-left-radius: 10px !important;
    border-bottom-left-radius: 10px !important;
}

body.dark-mode .search-prepend {
    background-color: #111827 !important;
    border-color: #374151 !important;
}

.search-input {
    background-color: var(--dash-panel-bg, #f8fafc) !important;
    border-color: var(--dash-border, #e2e8f0) !important;
    color: var(--dash-text-main, #1c2430) !important;
    font-size: 0.95rem;
    padding: 0.55rem 0.85rem;
}

body.dark-mode .search-input {
    background-color: #111827 !important;
    border-color: #374151 !important;
    color: #f3f4f6 !important;
}

.search-input:focus {
    background-color: var(--dash-card-bg, #ffffff) !important;
    border-color: var(--dash-primary, #0a4d36) !important;
    box-shadow: 0 0 0 3px var(--dash-primary-light, #eaf3ef) !important;
}

.btn-clear {
    background-color: var(--dash-panel-bg, #f8fafc);
    border-color: var(--dash-border, #e2e8f0);
}

body.dark-mode .btn-clear {
    background-color: #111827;
    border-color: #374151;
}

.btn-pos-primary {
    background: linear-gradient(135deg, var(--dash-primary, #0a4d36), var(--dash-primary-dark, #073827));
    color: #ffffff !important;
    border: none;
    border-top-right-radius: 10px !important;
    border-bottom-right-radius: 10px !important;
    transition: all 0.15s ease;
}

.btn-pos-primary:hover {
    filter: brightness(1.08);
}

.badge-success-soft {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
    font-size: 0.78rem;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
}

body.dark-mode .badge-success-soft {
    background: #064e3b;
    color: #a7f3d0;
    border-color: #047857;
}

/* Tabla de Proveedores */
.proveedor-tabla-wrap {
    max-height: 52vh;
    overflow-y: auto;
    border-color: var(--dash-border, #e2e8f0) !important;
    border-radius: 12px;
}

body.dark-mode .proveedor-tabla-wrap {
    border-color: #374151 !important;
}

.table-proveedores thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    background: var(--dash-panel-bg, #f8fafc);
    color: var(--dash-text-muted, #64748b);
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    font-weight: 700;
    border-bottom: 2px solid var(--dash-border, #e2e8f0);
    padding: 0.65rem 0.75rem;
}

body.dark-mode .table-proveedores thead th {
    background: #111827;
    color: #9ca3af;
    border-bottom-color: #374151;
}

.proveedor-fila {
    cursor: pointer;
    transition: background-color 0.15s ease, transform 0.1s ease;
}

.proveedor-fila:hover {
    background-color: var(--dash-primary-light, #eaf3ef) !important;
}

body.dark-mode .proveedor-fila:hover {
    background-color: rgba(16, 185, 129, 0.12) !important;
}

.table-proveedores tbody td {
    vertical-align: middle;
    padding: 0.65rem 0.75rem;
    border-top: 1px solid var(--dash-border, #e2e8f0);
    color: var(--dash-text-main, #1c2430);
}

body.dark-mode .table-proveedores tbody td {
    border-top-color: #374151;
    color: #f3f4f6;
}

.proveedor-nombre {
    font-size: 0.95rem;
    line-height: 1.2;
    color: var(--dash-text-main, #1c2430);
}

body.dark-mode .proveedor-nombre {
    color: #f9fafb;
}

.badge-ruc {
    font-family: monospace;
    font-weight: 700;
    font-size: 0.85rem;
    background: var(--dash-panel-bg, #f8fafc);
    border: 1px solid var(--dash-border, #e2e8f0);
    color: var(--dash-text-main, #1c2430);
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
}

body.dark-mode .badge-ruc {
    background: #111827;
    border-color: #374151;
    color: #e5e7eb;
}

.badge-telef {
    font-size: 0.82rem;
    background: var(--dash-panel-bg, #f8fafc);
    border: 1px solid var(--dash-border, #e2e8f0);
    color: var(--dash-text-muted, #64748b);
    padding: 0.2rem 0.45rem;
    border-radius: 6px;
}

body.dark-mode .badge-telef {
    background: #111827;
    border-color: #374151;
    color: #9ca3af;
}

.btn-seleccionar {
    background: #ffffff;
    border: 1px solid var(--dash-primary-border, #c8dfd5);
    color: var(--dash-primary-dark, #073827);
    border-radius: 8px;
    padding: 0.35rem 0.75rem;
    transition: all 0.15s ease;
}

body.dark-mode .btn-seleccionar {
    background: #1f2937;
    border-color: #047857;
    color: #10b981;
}

.proveedor-fila:hover .btn-seleccionar,
.btn-seleccionar:hover {
    background: var(--dash-primary, #0a4d36) !important;
    border-color: var(--dash-primary, #0a4d36) !important;
    color: #ffffff !important;
    transform: scale(1.02);
    box-shadow: 0 2px 6px rgba(10, 77, 54, 0.25);
}

@media (max-width: 576px) {
    .table-proveedores thead th:nth-child(3),
    .table-proveedores tbody td:nth-child(3),
    .table-proveedores thead th:nth-child(4),
    .table-proveedores tbody td:nth-child(4) {
        display: none;
    }
}
</style>
