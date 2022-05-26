<!-- PASAR A TEMPLATE -->
<div class="modal fade" id="cobroParcial">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<strong>Ingrese monto a Cobrar</strong>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">Cerrar</span>
				</button>
			</div>
			<div class="modal-body" style="height: 100px">
                <in-number id="txtparcial"  v-model="montoParcial" placeholder="Monto a Cobrar" :clases="inNumberClass"></in-number>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" @click="cobroParcial"><span class="fa fa-check"></span> Procesar</button>
            </div>
        </div>
    </div>
</div>