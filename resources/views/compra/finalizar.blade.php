<div class="modal fade" id="finalizarcompra" tabindex="-1" role="dialog" aria-labelledby="finalizarCompraLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0 modal-moderno">
            <div class="modal-header border-bottom">
                <div class="d-flex align-items-center">
                    <div class="modal-header-icon mr-2">
                        <i class="fa fa-shopping-cart text-primary"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0 font-cairo" id="finalizarCompraLabel">
                            Confirmar Compra
                        </h5>
                        <small class="text-muted">Definí las condiciones de pago y confirmá el ingreso</small>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body p-4">
                <div class="row mb-3">
                    <div class="col-sm-6 form-group">
                        <label class="font-weight-bold small text-muted text-uppercase">Forma de Pago *</label>
                        <select class="form-control form-control-modern" @change="saveDatos" v-model="compraCabecera.formacobro">
                            <option value="1">Efectivo</option>
                            <option value="2">Tarjeta</option>
                            <option value="3">Transferencia / Cheque</option>
                        </select>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label class="font-weight-bold small text-muted text-uppercase">Condición de Compra *</label>
                        <select class="form-control form-control-modern" @change="saveDatos" v-model="compraCabecera.condicioncompra">
                            <option value="1">Contado</option>
                            <option value="2">Crédito</option>
                        </select>
                    </div>
                </div>

                <!-- Resumen de Monto -->
                <div class="bg-light-panel border rounded-lg p-3 text-center mb-3">
                    <span class="text-muted small text-uppercase font-weight-bold d-block mb-1">Total a Pagar</span>
                    <h2 class="font-cairo text-success font-weight-bold mb-1">Gs. @{{ totalCompra }}</h2>
                    <small class="text-muted font-italic d-block" v-if="compraCabecera.total">
                        @{{ numeroaletra(compraCabecera.total) }}
                    </small>
                </div>

                <div class="small text-muted d-flex justify-content-between px-1">
                    <span>Proveedor: <strong>@{{ compraCabecera.proveedor || 'No especificado' }}</strong></span>
                    <span>Factura: <strong>@{{ (compraCabecera.factura_n1 || '001') + '-' + (compraCabecera.factura_n2 || '001') + '-' + (compraCabecera.factura_n3 || '0000000') }}</strong></span>
                </div>
            </div>

            <div class="modal-footer bg-light-panel border-top d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                    <i class="fa fa-times mr-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success font-weight-bold px-4" @click="finalizar">
                    <i class="fa fa-check mr-1"></i> Confirmar e Ingresar
                </button>
            </div>
        </div>
    </div>
</div>