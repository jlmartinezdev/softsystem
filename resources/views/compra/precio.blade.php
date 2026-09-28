<div class="modal fade" id="preciocompra" tabindex="-1" role="dialog" aria-labelledby="precioCompraLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0 modal-moderno">
            <div class="modal-header border-bottom">
                <div class="d-flex align-items-center">
                    <div class="modal-header-icon mr-2">
                        <i class="fa fa-tags text-primary"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 font-cairo" id="precioCompraLabel">
                            Actualizar Costos y Precios de Venta
                        </h5>
                        <small class="text-muted">Ajustá el costo de compra y los márgenes de ganancia</small>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-3">
                <!-- Info del Artículo Seleccionado -->
                <div class="bg-light-panel border rounded p-2 mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <span class="text-muted small">Artículo:</span>
                        <strong class="font-cairo ml-1">@{{ articulo.descripcion }}</strong>
                    </div>
                    <div>
                        <span class="text-muted small">Código:</span>
                        <span class="badge badge-light border ml-1 font-family-monospace">@{{ articulo.codigo }}</span>
                    </div>
                </div>

                <div class="row">
                    <!-- Panel Izquierdo: Costos y Precios P1 a P5 -->
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="card card-modern h-100 p-3">
                            <fieldset class="form-group mb-3">
                                <label class="font-weight-bold small text-muted text-uppercase">Costo de Compra (Gs.) *</label>
                                <in-number id="costo" v-model="articulo.costo" placeholder="Precio Costo" :clases="inNumberClass"></in-number>
                            </fieldset>

                            <h6 class="font-weight-bold font-cairo small text-uppercase text-muted border-bottom pb-1 mb-2">
                                Precios de Venta al Público
                            </h6>

                            <div class="row">
                                <div class="col-6 form-group mb-2">
                                    <label class="small font-weight-bold mb-1">Precio 1 (%)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" onfocus="this.select()" v-on:keyup="setUtilPrecio('M',1)" v-model="articulo.m1" placeholder="%">
                                        <in-number v-model="articulo.p1" :clases="inNumberClass" placeholder="Precio" @change="setUtilPrecio('P',1)"></in-number>
                                    </div>
                                </div>

                                <div class="col-6 form-group mb-2">
                                    <label class="small font-weight-bold mb-1">Precio 2 (%)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" onfocus="this.select()" v-on:keyup="setUtilPrecio('M',2)" v-model="articulo.m2" placeholder="%">
                                        <input type="text" placeholder="Precio" onfocus="this.select()" v-on:keyup="setUtilPrecio('P',2)" v-model="articulo.p2" class="form-control">
                                    </div>
                                </div>

                                <div class="col-6 form-group mb-2">
                                    <label class="small font-weight-bold mb-1">Precio 3 (%)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" onfocus="this.select()" v-on:keyup="setUtilPrecio('M',3)" v-model="articulo.m3" placeholder="%">
                                        <input type="text" placeholder="Precio" onfocus="this.select()" v-on:keyup="setUtilPrecio('P',3)" v-model="articulo.p3" class="form-control">
                                    </div>
                                </div>

                                <div class="col-6 form-group mb-2">
                                    <label class="small font-weight-bold mb-1">Precio 4 (%)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" onfocus="this.select()" v-on:keyup="setUtilPrecio('M',4)" v-model="articulo.m4" placeholder="%">
                                        <input type="text" placeholder="Precio" onfocus="this.select()" v-on:keyup="setUtilPrecio('P',4)" v-model="articulo.p4" class="form-control">
                                    </div>
                                </div>

                                <div class="col-6 form-group mb-2">
                                    <label class="small font-weight-bold mb-1">Precio 5 (%)</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" class="form-control" onfocus="this.select()" v-on:keyup="setUtilPrecio('M',5)" v-model="articulo.m5" placeholder="%">
                                        <input type="text" placeholder="Precio" onfocus="this.select()" v-on:keyup="setUtilPrecio('P',5)" v-model="articulo.p5" class="form-control">
                                    </div>
                                </div>

                                <div class="col-6 form-group mb-2 d-flex align-items-end">
                                    <button type="button" @click="mostrarPrecios" class="btn btn-outline-primary btn-block btn-sm">
                                        <i class="fa fa-sliders-h mr-1"></i> Más Precios
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel Derecho: Historial de Compras -->
                    <div class="col-md-6">
                        <div class="card card-modern h-100">
                            <div class="card-header bg-light-panel py-2 px-3">
                                <span class="font-weight-bold small text-uppercase font-cairo">
                                    <i class="fa fa-history mr-1"></i> Historial de Compras Previas
                                </span>
                            </div>
                            <div class="card-body p-0 table-responsive" style="max-height: 280px;">
                                <table class="table table-sm table-hover table-striped mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Proveedor</th>
                                            <th>Fecha</th>
                                            <th class="text-right">Costo Gs.</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="articulos.length > 0">
                                        <tr v-for="(item, index) in articulos" :key="'hist-' + index">
                                            <td>@{{ item.proveedor_nombre }}</td>
                                            <td><small class="text-muted">@{{ item.compra_fecha }}</small></td>
                                            <td class="text-right font-weight-bold text-success">@{{ format(item.compra_precio) }}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">
                                                <i class="fa fa-info-circle mr-1"></i> Sin historial previo registrado
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light-panel border-top d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fa fa-times mr-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success font-weight-bold px-4" @click="update_precio">
                    <i class="fa fa-check mr-1"></i> Aplicar Precios
                </button>
            </div>
        </div>
    </div>
</div>
