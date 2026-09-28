<!-- Modal de Búsqueda de Clientes -->
<div class="modal fade" id="busquedaCliente" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header d-flex align-items-center justify-content-between p-3" style="background: var(--dash-card-bg); border-bottom: 1px solid var(--dash-border);">
                <div class="d-flex align-items-center">
                    <div class="mr-2.5" style="width: 38px; height: 38px; border-radius: 10px; background: var(--dash-primary-light); color: var(--dash-primary); display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-users-viewfinder fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold font-cairo mb-0" style="color: var(--dash-text-main); font-size: 1.15rem;">
                            Búsqueda de Clientes
                        </h5>
                        <small class="text-muted">Escribí el nombre, apellido o número de documento</small>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-3" style="background: var(--dash-card-bg);">
                <!-- Campo de búsqueda -->
                <div class="mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text search-prepend">
                                <i class="fa-solid fa-magnifying-glass text-muted"></i>
                            </span>
                        </div>
                        <input type="text"
                               v-model="txtcliente"
                               @keyup.enter="buscarCliente()"
                               id="txtcliente"
                               class="form-control search-input font-weight-bold"
                               placeholder="Escribí nombre o C.I. y presioná Enter..." />
                        <div class="input-group-append">
                            <button class="btn btn-pos-primary" type="button" @click="buscarCliente()">
                                <template v-if="request.cliente">
                                    <span class="spinner-border spinner-border-sm mr-1" role="status"></span> Buscando...
                                </template>
                                <template v-else>
                                    <i class="fa-solid fa-search mr-1"></i> Buscar
                                </template>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Resultados -->
                <div class="table-responsive border rounded-lg" style="border-radius: 10px;">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Documento / C.I.</th>
                                <th>Nombre y Apellido</th>
                                <th>Dirección</th>
                                <th>Celular</th>
                                <th class="text-center" style="width: 70px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="c in clientes" :key="c.cliente_ci">
                                <td>
                                    <span class="badge-barcode">
                                        <i class="fa fa-id-card mr-1 text-muted"></i>@{{ c.cliente_ci }}
                                    </span>
                                </td>
                                <td class="font-weight-bold font-cairo" style="color: var(--dash-text-main);">
                                    @{{ c.cliente_nombre }}
                                </td>
                                <td class="text-muted small">
                                    @{{ c.cliente_direccion || 'Sin dirección' }}
                                </td>
                                <td>
                                    <span v-if="c.cliente_cel" class="badge-section">
                                        <i class="fa fa-phone mr-1 text-muted"></i>@{{ c.cliente_cel }}
                                    </span>
                                    <span v-else class="text-muted small">-</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn-pos-primary btn-sm py-1 px-2.5"
                                            @click="selectCliente(c.cliente_ci)"
                                            title="Seleccionar este cliente">
                                        <i class="fa-solid fa-user-check"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!clientes.length">
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-user-xmark fa-2x mb-2 text-muted"></i>
                                    <div class="font-weight-bold">Sin resultados</div>
                                    <small>Ingresá un término de búsqueda para ver los clientes disponibles.</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer p-2.5 px-3" style="background: var(--dash-card-bg); border-top: 1px solid var(--dash-border);">
                <button type="button" class="btn-pos-secondary" data-dismiss="modal">
                    <i class="fa fa-times mr-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>