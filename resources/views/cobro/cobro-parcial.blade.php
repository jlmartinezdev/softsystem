<!-- Modal de Cobro Parcial -->
<div class="modal fade" id="cobroParcial" tabindex="-1" role="dialog" aria-labelledby="cobroParcialLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px; overflow: hidden;">
            <!-- Header -->
            <div class="modal-header d-flex align-items-center justify-content-between p-3" style="background: var(--dash-card-bg); border-bottom: 1px solid var(--dash-border);">
                <div class="d-flex align-items-center">
                    <div class="mr-2.5" style="width: 38px; height: 38px; border-radius: 10px; background: var(--dash-primary-light); color: var(--dash-primary); display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-money-bill-transfer fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold font-cairo mb-0" id="cobroParcialLabel" style="color: var(--dash-text-main); font-size: 1.15rem;">
                            Cobro Parcial
                        </h5>
                        <small class="text-muted">Amortización de importe sobre cuotas</small>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body p-3" style="background: var(--dash-card-bg);">
                <!-- Información del cliente -->
                <div class="settlement-box-card mb-3 p-3">
                    <div class="text-muted small font-weight-bold text-uppercase mb-1">
                        <i class="fa-solid fa-user-circle mr-1 text-primary"></i> Cliente
                    </div>
                    <div class="font-cairo font-weight-bold" style="font-size: 1.1rem; color: var(--dash-text-main);">
                        @{{ cliente.nombre }}
                    </div>
                    <div class="small text-muted">
                        <i class="fa fa-fingerprint mr-1"></i> Documento: @{{ cliente.documento }}
                    </div>
                </div>

                <!-- Información de saldo -->
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="settlement-box-card p-3 text-center" style="border-left: 3px solid #dc2626;">
                            <div class="text-muted small font-weight-bold text-uppercase mb-1">Saldo Total</div>
                            <div class="font-cairo font-weight-bold" style="font-size: 1.15rem; color: #dc2626;">
                                Gs. @{{ format(totalSaldoFiltrado) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="settlement-box-card p-3 text-center" style="border-left: 3px solid #16a34a;">
                            <div class="text-muted small font-weight-bold text-uppercase mb-1">Ya Cobrado</div>
                            <div class="font-cairo font-weight-bold" style="font-size: 1.15rem; color: #166534;">
                                Gs. @{{ format(totalCobradoFiltrado) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campo de entrada -->
                <div class="mb-3">
                    <label for="txtparcial" class="font-weight-bold small text-muted mb-1">
                        <i class="fa-solid fa-calculator mr-1"></i> MONTO A COBRAR
                    </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text font-weight-bold font-cairo" style="background: var(--dash-primary-light); color: var(--dash-primary); border-color: var(--dash-border); border-radius: 8px 0 0 8px;">
                                Gs.
                            </span>
                        </div>
                        <in-number 
                            id="txtparcial" 
                            v-model="montoParcial" 
                            placeholder="0" 
                            :clases="inNumberClass"
                            class="form-control font-weight-bold font-cairo"
                            style="border-radius: 0 8px 8px 0; font-size: 1.25rem; color: var(--dash-primary);"
                        ></in-number>
                    </div>
                    <small class="form-text text-muted mt-1">
                        <i class="fa fa-info-circle mr-1"></i>
                        El monto debe ser menor o igual al saldo pendiente total.
                    </small>
                </div>

                <!-- Indicador de tipo de cobro -->
                <div class="alert alert-warning border-0 p-2.5 mb-0" style="border-radius: 8px;" v-if="!cobroParcialAllCtas">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-exclamation-triangle fa-lg mr-2 text-warning"></i>
                        <div>
                            <strong class="d-block" style="font-size: 0.88rem;">Cobro Parcial Específico</strong>
                            <small>Se aplicará únicamente a las cuotas de la venta seleccionada.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer d-flex align-items-center justify-content-between p-3" style="background: var(--dash-card-bg); border-top: 1px solid var(--dash-border);">
                <button type="button" class="btn-pos-secondary" data-dismiss="modal">
                    <i class="fa fa-times mr-1"></i> Cancelar
                </button>
                <button class="btn-pos-primary" type="button" @click="cobroParcial">
                    <i class="fa fa-check mr-1.5"></i> Aplicar y Distribuir Monto
                </button>
            </div>
        </div>
    </div>
</div>