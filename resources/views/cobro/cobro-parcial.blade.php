<!-- Modal de Cobro Parcial -->
<div class="modal fade" id="cobroParcial" tabindex="-1" role="dialog" aria-labelledby="cobroParcialLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content shadow-lg border-0">
            <!-- Header mejorado -->
            <div class="modal-header bg-gradient-primary text-white border-0">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fa fa-money-bill-wave fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 font-weight-bold" id="cobroParcialLabel">
                            Cobro Parcial
                        </h5>
                        <small class="opacity-75">Ingrese el monto a cobrar</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true" class="fa fa-times"></span>
                </button>
            </div>

            <!-- Body mejorado -->
            <div class="modal-body p-4">
                <!-- Información del cliente -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-light border-0 shadow-sm">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-user-circle fa-lg mr-3 text-info"></i>
                                <div>
                                    <strong>Cliente:</strong> @{{ cliente.nombre }}<br>
                                    <small class="text-muted">Documento: @{{ cliente.documento }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de saldo -->
                <div class="row mb-4">
                    <div class="col-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center p-3">
                                <i class="fa fa-wallet fa-2x text-success mb-2"></i>
                                <h6 class="card-title text-muted mb-1">Saldo Total</h6>
                                <h4 class="text-success font-weight-bold mb-0">@{{ format(totalSaldoFiltrado) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center p-3">
                                <i class="fa fa-check-circle fa-2x text-primary mb-2"></i>
                                <h6 class="card-title text-muted mb-1">Ya Cobrado</h6>
                                <h4 class="text-primary font-weight-bold mb-0">@{{ format(totalCobradoFiltrado) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campo de entrada mejorado -->
                <div class="form-group">
                    <label for="txtparcial" class="font-weight-bold text-dark">
                        <i class="fa fa-edit mr-2"></i>Monto a Cobrar
                    </label>
                    <div class="input-group input-group-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-primary text-white border-0">
                                <i class="fa fa-dollar-sign"></i>
                            </span>
                        </div>
                        <in-number 
                            id="txtparcial" 
                            v-model="montoParcial" 
                            placeholder="Ingrese el monto..." 
                            :clases="inNumberClass"
                            class="form-control form-control-lg shadow-sm"
                        ></in-number>
                    </div>
                    <small class="form-text text-muted">
                        <i class="fa fa-info-circle mr-1"></i>
                        El monto debe ser menor o igual al saldo pendiente
                    </small>
                </div>

                <!-- Indicador de tipo de cobro -->
                <div class="alert alert-warning border-0 shadow-sm" v-if="!cobroParcialAllCtas">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-exclamation-triangle fa-lg mr-3"></i>
                        <div>
                            <strong>Cobro Parcial Específico</strong><br>
                            <small>Se aplicará solo a las cuotas de la venta seleccionada</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer mejorado -->
            <div class="modal-footer bg-light border-0 p-4">
                <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-dismiss="modal">
                    <i class="fa fa-times mr-2"></i>Cancelar
                </button>
                <button class="btn btn-success btn-lg px-4 shadow-sm" @click="cobroParcial">
                    <i class="fa fa-check mr-2"></i>Procesar Cobro
                </button>
            </div>
        </div>
    </div>
</div>