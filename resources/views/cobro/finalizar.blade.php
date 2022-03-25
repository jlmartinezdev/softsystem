<div class="modal fade" id="saveCuotas">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Finalizar Cobro</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 250px">
                <strong>Cobro de Cuota</strong>
                <hr>
                <center>
                    <p>Total a Cobrar</p>
                    <h2>@{{ format(cobro.total)}}</h2>
                    <p class="text-monospace">@{{ numeroaletra(cobro.total)}}</p>
                </center>
                
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" @click="finalizar"> <span class="fa fa-save"></span> Finalizar</button>
                <button class="btn btn-secondary"  data-dismiss="modal"><span class="fa fa-reply"></span> Cancelar</button>
            </div>
        </div>
    </div>
</div>