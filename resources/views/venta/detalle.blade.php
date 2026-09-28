<!-- Modal de Detalle de Venta -->
<div class="modal fade" id="frmdetalle" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header d-flex align-items-center justify-content-between p-3" style="background: var(--dash-card-bg); border-bottom: 1px solid var(--dash-border);">
                <div class="d-flex align-items-center">
                    <div class="mr-2.5" style="width: 38px; height: 38px; border-radius: 10px; background: var(--dash-primary-light); color: var(--dash-primary); display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-receipt fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold font-cairo mb-0" style="color: var(--dash-text-main); font-size: 1.15rem;">
                            Detalle de Venta #@{{ venta.nro_fact_ventas }}
                        </h5>
                        <small class="text-muted">
                            <i class="fa-regular fa-calendar mr-1"></i>@{{ venta.venta_fecha }} &bull;
                            <i class="fa-regular fa-user mr-1 ml-1"></i>@{{ venta.cliente_nombre }}
                        </small>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-3" style="background: var(--dash-card-bg);">
                <div class="table-responsive border rounded-lg" style="border-radius: 10px;">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Descripción del Producto</th>
                                <th class="text-center" style="width: 70px;">Cant.</th>
                                <th class="text-right">Precio Unit.</th>
                                <th class="text-right">Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="d in detalleVenta(venta.nro_fact_ventas)" :key="d.producto_c_barra">
                                <td>
                                    <span class="badge-barcode">@{{ d.producto_c_barra }}</span>
                                </td>
                                <td class="font-weight-bold" style="color: var(--dash-text-main);">
                                    @{{ d.producto_nombre }}
                                </td>
                                <td class="text-center font-weight-bold font-cairo">
                                    @{{ parseInt(d.venta_cantidad) }}
                                </td>
                                <td class="text-right text-muted small">
                                    Gs. @{{ new Intl.NumberFormat("de-DE").format(d.venta_precio) }}
                                </td>
                                <td class="text-right font-weight-bold font-cairo">
                                    Gs. @{{ new Intl.NumberFormat("de-DE").format(d.venta_cantidad * d.venta_precio) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer d-flex align-items-center justify-content-between p-3" style="background: var(--dash-card-bg); border-top: 1px solid var(--dash-border);">
                <div>
                    <span class="text-muted small font-weight-bold text-uppercase mr-2">Total de la Venta:</span>
                    <span class="font-cairo font-weight-bold" style="font-size: 1.25rem; color: var(--dash-primary);">
                        Gs. @{{ new Intl.NumberFormat("de-DE").format(venta.total || 0) }}
                    </span>
                </div>
                <button type="button" class="btn-pos-secondary" data-dismiss="modal">
                    <i class="fa fa-times mr-1"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
