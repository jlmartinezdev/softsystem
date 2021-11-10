<div class="modal fade" id="finalizarcompra">
	<div class="modal-dialog " role="document">
		<div class="modal-content">
			<div class="modal-header bg-dark text-white">
				<span class="modal-title">Confirmar Compra...</span>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">Cerrar</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="row m-2">
					<div class="col-sm-6">
						<div class="form-group">
							Forma de Pago
							<select class="form-control form-control-sm" @change="saveDatos" v-model="compraCabecera.formacobro">
								<option value="1">Efectivo</option>
								<option value="2">Tarjeta</option>
							</select>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							Condicion de Compra
							<select class="form-control form-control-sm" @change="saveDatos" v-model="compraCabecera.condicioncompra">
								<option value="1">Contado</option>
								<option value="2">Credito</option>
							</select>
						</div>
					</div>
				</div>
				<hr>
				<div class="text-center">
					<p class="text-muted">Total a Pagar</p>
					<h2>@{{totalCompra}}</h2>
					<p>@{{ numeroaletra(compraCabecera.total) }}</p>
				</div>

				
			</div>
			<div class="modal-footer">
				<button class="btn btn-success" @click="finalizar"><span class="fa fa-check"></span> CONFIRMAR COMPRA</button>
				<button class="btn btn-secondary" data-dismiss="modal"><span class="fa fa-reply"></span> CANCELAR</button>
			</div>
		</div>
	</div>
</div>