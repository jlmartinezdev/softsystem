<!-- Modal de Confirmación y Finalización de Cobro -->
<div class="modal fade" id="saveCuotas" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header d-flex align-items-center justify-content-between p-3" style="background: var(--dash-card-bg); border-bottom: 1px solid var(--dash-border);">
                <div class="d-flex align-items-center">
                    <div class="mr-2.5" style="width: 38px; height: 38px; border-radius: 10px; background: var(--dash-primary-light); color: var(--dash-primary); display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-receipt fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold font-cairo mb-0" style="color: var(--dash-text-main); font-size: 1.15rem;">
                            Confirmar Cobro
                        </h5>
                        <small class="text-muted">Comprobante de recaudación en caja</small>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar" style="outline: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-4 text-center" style="background: var(--dash-card-bg);">
                <div class="settlement-box-card settlement-hero-card p-4 mx-auto text-center" style="max-width: 440px;">
                    <span class="text-muted small font-weight-bold text-uppercase mb-1">
                        <i class="fa-solid fa-money-bill-wave mr-1 text-success"></i> IMPORTE TOTAL A COBRAR
                    </span>
                    <div class="font-cairo font-weight-bold my-2" style="font-size: 2.2rem; color: var(--dash-primary);">
                        Gs. @{{ format(cobro.total) }}
                    </div>
                    <div class="p-2.5 rounded border small font-weight-bold text-muted" style="background: var(--dash-card-bg); line-height: 1.4;">
                        <i class="fa-solid fa-spell-check mr-1 text-primary"></i> @{{ numeroaletra(cobro.total) }}
                    </div>
                </div>
                <p class="small text-muted mt-3 mb-0">
                    Al confirmar, se registrará el cobro en la caja activa y se emitirá el recibo oficial de dinero.
                </p>
            </div>

            <div class="modal-footer d-flex align-items-center justify-content-between p-3" style="background: var(--dash-card-bg); border-top: 1px solid var(--dash-border);">
                <button type="button" class="btn-pos-secondary" data-dismiss="modal" :disabled="request.finalizar">
                    <i class="fa fa-times mr-1"></i> Cancelar
                </button>
                <button type="button" class="btn-pos-primary" @click="finalizar" :disabled="request.finalizar">
                    <template v-if="request.finalizar">
                        <span class="spinner-border spinner-border-sm mr-2" role="status"></span> Registrando...
                    </template>
                    <template v-else>
                        <i class="fa-solid fa-floppy-disk mr-1.5"></i> Registrar y Emitir Recibo
                    </template>
                </button>
            </div>
        </div>
    </div>
</div>