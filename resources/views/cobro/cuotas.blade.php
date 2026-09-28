<!-- Modal de Selección de Cuotas de la Venta -->
<div class="modal fade" id="selCuotas" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header d-flex align-items-center justify-content-between p-3" style="background: var(--dash-card-bg); border-bottom: 1px solid var(--dash-border);">
                <div class="d-flex align-items-center">
                    <div class="mr-2.5" style="width: 38px; height: 38px; border-radius: 10px; background: var(--dash-primary-light); color: var(--dash-primary); display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-list-check fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold font-cairo mb-0" style="color: var(--dash-text-main); font-size: 1.15rem;">
                            Seleccionar Cuotas para Cobro
                        </h5>
                        <small class="text-muted">Venta a Crédito #@{{ idVenta }}</small>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-3" style="background: var(--dash-card-bg);">
                <!-- Sección de Artículos de la Venta -->
                <div class="table-card mb-3">
                    <div class="table-card-head py-2 px-3">
                        <h6 class="font-weight-bold mb-0 font-cairo" style="font-size: 0.92rem; color: var(--dash-text-main);">
                            <i class="fa-solid fa-cart-shopping mr-1.5 text-primary"></i> Artículos de la Venta #@{{ idVenta }}
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th class="text-center" style="width: 70px;">Cant.</th>
                                    <th class="text-right">Precio Unit.</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="detalleVenta(idVenta).length == 0">
                                    <td colspan="5" class="text-center text-muted py-2">No hay artículos para mostrar</td>
                                </tr>
                                <tr v-for="articulo in detalleVenta(idVenta)" :key="articulo.producto_c_barra">
                                    <td>
                                        <span class="badge-barcode">@{{ articulo.producto_c_barra }}</span>
                                    </td>
                                    <td class="font-weight-bold" style="color: var(--dash-text-main);">
                                        @{{ articulo.producto_nombre }}
                                    </td>
                                    <td class="text-center font-weight-bold font-cairo">
                                        @{{ parseInt(articulo.venta_cantidad) }}
                                    </td>
                                    <td class="text-right text-muted small">
                                        Gs. @{{ format(articulo.venta_precio) }}
                                    </td>
                                    <td class="text-right font-weight-bold font-cairo">
                                        Gs. @{{ format(articulo.venta_cantidad * articulo.venta_precio) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Sección de Cuotas -->
                <div class="table-card">
                    <div class="table-card-head py-2 px-3">
                        <h6 class="font-weight-bold mb-0 font-cairo" style="font-size: 0.92rem; color: var(--dash-text-main);">
                            <i class="fa-solid fa-calendar-check mr-1.5 text-primary"></i> Cuotas del Plan de Financiación
                        </h6>
                        <small class="text-muted">Marcá las cuotas a incluir en el cobro</small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 45px;">Sel.</th>
                                    <th class="text-center" style="width: 65px;">N°</th>
                                    <th>Vencimiento</th>
                                    <th class="text-right">Cuota</th>
                                    <th class="text-right">Cobrado</th>
                                    <th class="text-right">Saldo</th>
                                    <th class="text-center">Mora</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="request.cuota">
                                    <td colspan="8" class="text-center py-4">
                                        <span class="spinner-border spinner-border-sm mr-2 text-primary" role="status"></span>
                                        Cargando cuotas...
                                    </td>
                                </tr>
                                <template v-for="(cuota, index) in cuotas">
                                    <tr v-if="!(parseInt(cuota.monto_cuota) == 0 && index == 0)" :key="index">
                                        <td class="text-center">
                                            <template v-if="cuota.monto_cobrado != cuota.monto_cuota">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox"
                                                           @click="checkCuota(index)"
                                                           v-model="cuota.check"
                                                           :id="'check'+index">
                                                    <label :for="'check'+index"></label>
                                                </div>
                                            </template>
                                            <template v-else>
                                                <i class="fa-solid fa-check text-muted"></i>
                                            </template>
                                        </td>
                                        <td class="text-center font-weight-bold font-cairo">
                                            <span class="badge badge-section">
                                                @{{ checkPrimeraCuota(cuota.nro_cuotas, cuota.nro_fact_ventas) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted small">
                                                <i class="fa-regular fa-calendar mr-1"></i>@{{ formatFecha(cuota.fecha_venc) }}
                                            </span>
                                        </td>
                                        <td class="text-right font-weight-bold font-cairo">
                                            Gs. @{{ format(cuota.monto_cuota) }}
                                        </td>
                                        <td class="text-right text-muted small">
                                            Gs. @{{ format(cuota.monto_cobrado) }}
                                        </td>
                                        <td class="text-right font-weight-bold font-cairo" :class="Number(cuota.monto_cuota - cuota.monto_cobrado) > 0 ? 'text-danger' : 'text-muted'">
                                            Gs. @{{ format(cuota.monto_cuota - cuota.monto_cobrado) }}
                                        </td>
                                        <td class="text-center">
                                            <span :class="Number(diferenciaFecha(cuota.fecha_venc, cuota.monto_saldo, cuota.estado_interes)) > 0 ? 'badge-stock-out' : 'badge-section'">
                                                @{{ diferenciaFecha(cuota.fecha_venc, cuota.monto_saldo, cuota.estado_interes) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span v-if="cuota.monto_cobrado == cuota.monto_cuota" class="badge-stock-in">
                                                <i class="fa-solid fa-circle-check mr-1"></i>Cobrado
                                            </span>
                                            <span v-else class="badge-stock-out">
                                                <i class="fa-solid fa-clock mr-1"></i>Pendiente
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer d-flex align-items-center justify-content-between p-2.5 px-3" style="background: var(--dash-card-bg); border-top: 1px solid var(--dash-border);">
                <div>
                    <template v-if="cuentasConSaldoPendiente > 1">
                        <button type="button" class="btn-pos-secondary btn-sm" @click="abrirCobroParcialDesdeCuotas">
                            <i class="fa-solid fa-money-bill mr-1 text-warning"></i> Cobro Parcial Solo esta Venta
                        </button>
                    </template>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn-pos-secondary mr-1" data-dismiss="modal">
                        <i class="fa fa-times mr-1"></i> Cancelar
                    </button>
                    <button class="btn-pos-primary" type="button" @click="addCuota">
                        <i class="fa fa-check mr-1"></i> Aceptar Cuotas
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
