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
						<div class="mb-3">
							<label class="small text-muted mb-2 d-block">Forma de pago y condición</label>
							<div class="pago-fila">
								<div class="pago-metodos">
									<button type="button" class="pago-metodo"
										:class="{ selected: String(ventaCabecera.formacobro) === '1' }"
										@click="seleccionarFormaPago(1)">
										<span class="pago-check" v-if="String(ventaCabecera.formacobro) === '1'">
											<i class="fa fa-check"></i>
										</span>
										<span class="pago-icon"><i class="fa fa-money-bill-wave"></i></span>
										<span class="pago-label">Efectivo</span>
									</button>
									<button type="button" class="pago-metodo"
										:class="{ selected: String(ventaCabecera.formacobro) === '2' }"
										@click="seleccionarFormaPago(2)">
										<span class="pago-check" v-if="String(ventaCabecera.formacobro) === '2'">
											<i class="fa fa-check"></i>
										</span>
										<span class="pago-icon"><i class="fa fa-credit-card"></i></span>
										<span class="pago-label">Tarjeta</span>
									</button>
									<button type="button" class="pago-metodo"
										:class="{ selected: String(ventaCabecera.formacobro) === '3' }"
										@click="seleccionarFormaPago(3)">
										<span class="pago-check" v-if="String(ventaCabecera.formacobro) === '3'">
											<i class="fa fa-check"></i>
										</span>
										<span class="pago-icon"><i class="fa fa-university"></i></span>
										<span class="pago-label">Transferencia</span>
									</button>
									<button type="button" class="pago-metodo"
										:class="{ selected: String(ventaCabecera.formacobro) === '4' }"
										@click="seleccionarFormaPago(4)">
										<span class="pago-check" v-if="String(ventaCabecera.formacobro) === '4'">
											<i class="fa fa-check"></i>
										</span>
										<span class="pago-icon"><i class="fa fa-qrcode"></i></span>
										<span class="pago-label">QR</span>
									</button>
								</div>

								<div class="pago-separador" aria-hidden="true"></div>

								<div class="pago-condiciones">
									<button type="button" class="pago-metodo"
										:class="{ selected: String(ventaCabecera.condicionventa) === '1' }"
										@click="seleccionarCondicionVenta(1)">
										<span class="pago-check" v-if="String(ventaCabecera.condicionventa) === '1'">
											<i class="fa fa-check"></i>
										</span>
										<span class="pago-icon"><i class="fa fa-hand-holding-usd"></i></span>
										<span class="pago-label">Contado</span>
									</button>
									<button type="button" class="pago-metodo"
										:class="{ selected: String(ventaCabecera.condicionventa) === '2' }"
										@click="seleccionarCondicionVenta(2)">
										<span class="pago-check" v-if="String(ventaCabecera.condicionventa) === '2'">
											<i class="fa fa-check"></i>
										</span>
										<span class="pago-icon"><i class="fa fa-calendar-alt"></i></span>
										<span class="pago-label">Crédito</span>
									</button>
								</div>
							</div>
						</div>

						<div class="row">
						<div class="bg-light rounded-lg border p-4 mb-4 text-center col-6">
							<p class="small text-uppercase text-muted mb-1">Total a pagar</p>
							<h2 class="mb-1 font-weight-bold text-dark">@{{ totalVenta }}</h2>
							<p class="small text-muted mb-0">@{{ numeroaletra(ventaCabecera.total) }}</p>
						</div>

						<div class="col-6 align-items-end">
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
