<div class="modal fade" id="finalizarventa">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content shadow">
			<div class="modal-header border-0 pb-0">
				<h5 class="modal-title d-flex align-items-center">
					<span class="fa fa-check-circle text-success mr-2"></span>
					Confirmar venta
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body pt-2">
				<nav class="mb-3">
					<div class="nav nav-tabs nav-fill" role="tablist">
						<a class="nav-item nav-link active" data-toggle="tab" href="#fin" role="tab">Finalizar</a>
						<a class="nav-item nav-link" :class="{ disabled: ventaCabecera.condicionventa=='1' }" data-toggle="tab" href="#generar" role="tab">Generar cuota</a>
					</div>
				</nav>

				<div class="tab-content">
					<div class="tab-pane fade active show" id="fin" role="tabpanel">
						<div class="row mb-3">
							<div class="col-md-6">
								<label class="small text-muted mb-1">Forma de pago</label>
								<select class="form-control form-control-sm" @change="saveDatos" v-model="ventaCabecera.formacobro">
									<option value="1">Efectivo</option>
									<option value="2">Tarjeta</option>
									<option value="3">Transferencia</option>
									<option value="4">QR</option>
								</select>
							</div>
							<div class="col-md-6">
								<label class="small text-muted mb-1">Condición de venta</label>
								<select class="form-control form-control-sm" @change="saveDatos" v-model="ventaCabecera.condicionventa">
									<option value="1">Contado</option>
									<option value="2">Crédito</option>
								</select>
							</div>
						</div>
						<div class="row">
						<div class="bg-light rounded-lg border p-4 mb-4 text-center col-6">
							<p class="small text-uppercase text-muted mb-1">Total a pagar</p>
							<h2 class="mb-1 font-weight-bold text-dark">@{{ totalVenta }}</h2>
							<p class="small text-muted mb-0">@{{ numeroaletra(ventaCabecera.total) }}</p>
						</div>

						<div class="col-6 align-items-end">
							<!-- Espacio y-3 -->
							<div class="mb-3">
								
								
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<span class="input-group-text bg-white font-weight-bold text-success">Monto recibido Gs.</span>
									</div>
									<in-number id="efectivo-recibido-modal" v-model="efectivoRecibido" :clases="'form-control form-control-sm'" placeholder="0" @change="calcularVuelto"></in-number>
								</div>
								<template v-if="opcionesEfectivo.length">
									<div class="mt-3 d-flex flex-wrap">
										<button type="button" v-for="(op, i) in opcionesEfectivo" :key="i" @click="aplicarOpcionEfectivo(op.monto)" class="btn btn-sm btn-outline-primary mr-1 mb-2">@{{ op.label }}</button>
									</div>
								</template>
								
							</div>
							<div class="mt-3">
								
								<div class="input-group input-group-sm">
									<div class="input-group-prepend">
										<span class="input-group-text bg-white font-weight-bold text-success">Vuelto Gs.</span>
									</div>
									<input type="text" class="form-control form-control-sm font-weight-bold" :value="format(vuelto)" readonly>
								</div>
							</div>
						</div>
						</div>
					</div>
					<div class="tab-pane fade" id="generar" role="tabpanel">
						<generar_cuota :total="ventaCabecera.total" :fecha="ventaCabecera.fecha" :calcularcuota="ventaCabecera.generarcuota" :datoscuota="tmpIndexPrecio" ref="generarcuota" @cuotas="setCuotas"/>
					</div>
				</div>
			</div>
			<div class="modal-footer border-top bg-light">
				<template v-if="requestFinalizar">
					<span class="spinner-border spinner-border-sm text-primary mr-2" role="status"></span>
					<span class="text-muted">Finalizando...</span>
				</template>
				<template v-else>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">
						<span class="fa fa-times mr-1"></span> Cancelar
					</button>
					<button type="button" class="btn btn-primary" @click="finalizar(false)">
						<span class="fa fa-check mr-1"></span> Finalizar venta
					</button>
					<button type="button" class="btn btn-success" @click="finalizar(true)">
						<span class="fa fa-print mr-1"></span> Finalizar e imprimir
					</button>
				</template>
			</div>
		</div>
	</div>
</div>