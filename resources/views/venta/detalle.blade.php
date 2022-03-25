<div class="modal fade" id="frmdetalle">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h6 class="modal-title">Detalle Venta</h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
            <div class="modal-body">
                <span class="pr-3"><span class="fa fa-grip-horizontal text-primary"></span><strong> Nro de
                        Venta: @{{ venta.nro_fact_ventas }} |</strong></span>
                <span class="pr-3"><span class="fa fa-calendar text-warning"></span><strong>
                        Fecha:@{{ venta.venta_fecha }} | </strong></span>
                <span><span class="fa fa-user-circle text-info"></span><strong> Cliente:
                        @{{ venta.cliente_nombre }}</strong></span>
                <br><br>
                <table class="table table-sm">
                    <tr>
                        <th>Codigo</th>
                        <th>Descripcion</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Importe</th>
                    </tr>
                    <template v-for="d in detalleVenta(venta.nro_fact_ventas)">
                        <tr>
                            <td>@{{ d.producto_c_barra }}</td>
                            <td>@{{ d.producto_nombre }}</td>
                            <td>@{{ parseInt(d.venta_cantidad) }}</td>
                            <td>@{{ new Intl.NumberFormat("de-DE").format(d.venta_precio) }}</td>
                            <td>@{{ new Intl.NumberFormat("de-DE").format(d.venta_cantidad * d.venta_precio) }}</td>
                        </tr>
                    </template>
                </table>
            </div>
            <div class="modal-footer">
                <strong>Total @{{ new Intl.NumberFormat("de-DE").format(venta.total) }}</strong>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><span
                        class="fa fa-times"></span> Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
