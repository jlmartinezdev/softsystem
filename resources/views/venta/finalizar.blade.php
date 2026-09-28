<div class="modal fade" id="finalizarventa" tabindex="-1" role="dialog" aria-labelledby="modalFinalizarLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
		<div class="modal-content shadow-lg border-0 modal-moderno">
			<div class="modal-header border-0 pb-1 pt-3 px-3">
				<div class="d-flex align-items-center">
					<div class="modal-header-icon mr-2">
						<i class="fas fa-cash-register text-success"></i>
					</div>
					<div>
						<h2 class="modal-title h5 mb-0 font-weight-bold font-cairo" id="modalFinalizarLabel">Cobrar Venta</h2>
						<p class="small text-muted mb-0" style="font-size: 0.8rem;">Seleccioná el medio de pago y completá la transacción</p>
					</div>
				</div>
				<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body p-3">
				<div class="row">
					<!-- Columna Izquierda: Total + Métodos de pago + Condición -->
					<div class="col-md-6 mb-3 mb-md-0 pr-md-2 d-flex flex-column justify-content-between">
						<!-- Total Hero Box -->
						<div class="pago-total text-center mb-2 p-2 rounded border">
							<p class="mb-0 text-muted small text-uppercase font-weight-bold tracking-wider" style="font-size: 0.72rem;">Total a Cobrar</p>
							<p class="pago-total-monto mb-0 text-success font-weight-bold font-cairo" style="font-size: 1.85rem; line-height: 1.15;">
								Gs. @{{ totalVenta }}
							</p>
							<p class="small text-muted mb-0 font-italic text-truncate" :title="numeroaletra(ventaCabecera.total)" style="font-size: 0.75rem;">
								@{{ numeroaletra(ventaCabecera.total) }}
							</p>
						</div>

						<!-- Medio de Pago -->
						<fieldset class="pago-grupo mb-2">
							<legend class="pago-grupo-title mb-1">
								<i class="fas fa-wallet mr-1 text-muted"></i> ¿Con qué medio paga?
							</legend>
							<div class="pago-metodos-grid">
								<button type="button" class="pago-metodo-tile"
									:class="{ selected: String(ventaCabecera.formacobro) === '1' }"
									@click="seleccionarFormaPago(1)">
									<div class="tile-icon">
										<i class="fas fa-money-bill-wave"></i>
									</div>
									<div class="tile-label">Efectivo</div>
								</button>
								<button type="button" class="pago-metodo-tile"
									:class="{ selected: String(ventaCabecera.formacobro) === '2' }"
									@click="seleccionarFormaPago(2)">
									<div class="tile-icon">
										<i class="fas fa-credit-card"></i>
									</div>
									<div class="tile-label">Tarjeta</div>
								</button>
								<button type="button" class="pago-metodo-tile"
									:class="{ selected: String(ventaCabecera.formacobro) === '3' }"
									@click="seleccionarFormaPago(3)">
									<div class="tile-icon">
										<i class="fas fa-exchange-alt"></i>
									</div>
									<div class="tile-label">Transferencia</div>
								</button>
								<button type="button" class="pago-metodo-tile"
									:class="{ selected: String(ventaCabecera.formacobro) === '4' }"
									@click="seleccionarFormaPago(4)">
									<div class="tile-icon">
										<i class="fas fa-qrcode"></i>
									</div>
									<div class="tile-label">QR</div>
								</button>
							</div>
						</fieldset>

						<!-- Condición de Venta -->
						<fieldset class="pago-grupo mb-0">
							<legend class="pago-grupo-title mb-1">
								<i class="fas fa-file-contract mr-1 text-muted"></i> Condición de Venta
							</legend>
							<div class="pago-condicion-switch">
								<button type="button" class="pago-chip"
									:class="{ selected: String(ventaCabecera.condicionventa) === '1' }"
									@click="seleccionarCondicionVenta(1)">
									<i class="fas fa-coins mr-1"></i> Contado
								</button>
								<button type="button" class="pago-chip"
									:class="{ selected: String(ventaCabecera.condicionventa) === '2' }"
									@click="seleccionarCondicionVenta(2)">
									<i class="fas fa-calendar-alt mr-1"></i> Crédito
								</button>
							</div>
						</fieldset>
					</div>

					<!-- Columna Derecha: Efectivo y vuelto / Crédito / Tarjeta -->
					<div class="col-md-6 pl-md-2 d-flex flex-column justify-content-between">
						<!-- Bloque Efectivo & Vuelto -->
						<div v-if="esEfectivo && !esCredito" class="pago-efectivo-card p-2 px-3 rounded border h-100 d-flex flex-column justify-content-between">
							<div>
								<label for="efectivo-recibido-modal" class="font-weight-bold small text-muted text-uppercase mb-1 d-block" style="font-size: 0.75rem;">
									<i class="fas fa-hand-holding-usd mr-1 text-success"></i> Monto recibido en efectivo (Gs.)
								</label>
								<in-number id="efectivo-recibido-modal" v-model="efectivoRecibido" :clases="'form-control form-control-md font-weight-bold font-cairo text-success search-input-efectivo'" placeholder="0" @change="calcularVuelto"></in-number>

								<!-- Billetes rápidos -->
								<template v-if="opcionesEfectivo.length">
									<div class="mt-2 d-flex flex-wrap gap-1">
										<button type="button" v-for="(op, i) in opcionesEfectivo" :key="i" @click="aplicarOpcionEfectivo(op.monto)" class="pago-billete font-weight-bold py-1 px-2" style="font-size: 0.8rem;">
											@{{ op.label }}
										</button>
									</div>
								</template>
							</div>

							<!-- Vuelto Banner -->
							<div class="pago-vuelto-box mt-2 p-2 px-3 rounded d-flex justify-content-between align-items-center">
								<span class="font-weight-bold vueltotext" style="font-size: 0.9rem;">Vuelto al Cliente:</span>
								<span class="text-success font-weight-bold font-cairo" style="font-size: 1.5rem; line-height: 1;">
									Gs. @{{ format(vuelto) }}
								</span>
							</div>
						</div>

						<!-- Bloque Crédito (Cuotas) -->
						<div v-else-if="esCredito" class="cuota-resumen-box p-3 rounded border bg-light-panel h-100 d-flex flex-column justify-content-between">
							<div>
								<div class="d-flex align-items-center justify-content-between mb-2">
									<span class="font-weight-bold text-uppercase small text-muted">
										<i class="fas fa-calendar-alt mr-1"></i> Plan a Crédito
									</span>
									<span class="badge badge-success" v-if="cuotas && cuotas.length">
										@{{ cuotas.length }} cuota(s)
									</span>
								</div>

								<div v-if="!cuotas || !cuotas.length" class="alert alert-warning py-2 px-3 mb-0">
									<div class="small font-weight-bold mb-2">
										<i class="fas fa-exclamation-triangle mr-1"></i> Faltan definir las cuotas del crédito.
									</div>
									<button type="button" class="btn btn-warning btn-sm font-weight-bold w-100" @click="abrirCuotas">
										<i class="fas fa-calculator mr-1"></i> Armar Cuotas
									</button>
								</div>
								<div v-else class="p-2 border rounded bg-white dark-mode-box">
									<div class="small font-weight-bold text-dark-mode mb-2">
										@{{ resumenCuotas }}
									</div>
									<button type="button" class="btn btn-outline-primary btn-sm font-weight-bold w-100" @click="abrirCuotas">
										<i class="fas fa-edit mr-1"></i> Modificar Cuotas
									</button>
								</div>
							</div>
							<div class="small text-muted mt-2 pt-2 border-top d-flex justify-content-between">
								<span>Total financiado:</span>
								<strong class="font-cairo text-success">Gs. @{{ totalVenta }}</strong>
							</div>
						</div>

						<!-- Bloque Tarjeta / Transferencia / QR -->
						<div v-else class="p-3 rounded border bg-light-panel h-100 d-flex flex-column justify-content-center align-items-center text-center">
							<div class="rounded-circle p-2 mb-2 bg-white shadow-sm text-success" style="font-size: 1.6rem; width: 52px; height: 52px; display: flex; align-items: center; justify-content: center;">
								<i class="fas fa-credit-card" v-if="String(ventaCabecera.formacobro) === '2'"></i>
								<i class="fas fa-exchange-alt" v-else-if="String(ventaCabecera.formacobro) === '3'"></i>
								<i class="fas fa-qrcode" v-else></i>
							</div>
							<h6 class="font-weight-bold mb-1 font-cairo">
								<span v-if="String(ventaCabecera.formacobro) === '2'">Cobro con Tarjeta</span>
								<span v-else-if="String(ventaCabecera.formacobro) === '3'">Transferencia Bancaria</span>
								<span v-else>Cobro con QR</span>
							</h6>
							<p class="small text-muted mb-0">
								Importe exacto: <strong class="text-success font-cairo">Gs. @{{ totalVenta }}</strong>.<br>No requiere vuelto.
							</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Footer con Acciones (En una sola fila compacta) -->
			<div class="modal-footer border-top bg-light-panel py-2 px-3 d-flex align-items-center justify-content-between">
				<template v-if="requestFinalizar">
					<div class="d-flex align-items-center justify-content-center w-100 py-1">
						<span class="spinner-border spinner-border-sm mr-2 text-success" role="status"></span>
						<span class="font-weight-bold text-success font-cairo">Procesando y registrando venta...</span>
					</div>
				</template>
				<template v-else>
					<div class="cobro-footer-salir d-flex align-items-center">
						<button type="button" class="btn btn-outline-secondary btn-sm mr-2" data-dismiss="modal">
							<i class="fas fa-arrow-left mr-1"></i> Volver
						</button>
						<button type="button" class="btn btn-link btn-sm text-muted px-1" @click="finalizar(false)">
							Guardar sin ticket
						</button>
					</div>
					<div class="cobro-footer-primario d-flex align-items-center">
						<span class="cobro-hint mr-3 d-none d-sm-inline text-muted small font-weight-bold">
							@{{ hintCobro }}
						</span>
						<button type="button" id="btn-cobrar-imprimir" class="btn btn-success font-weight-bold px-4 py-2 font-cairo shadow-sm" @click="finalizar(true)" :disabled="!puedeImprimirCobro">
							<i class="fas fa-print mr-1"></i> Cobrar e Imprimir
						</button>
					</div>
				</template>
			</div>
		</div>
	</div>
</div>

<!-- Modal Cuotas Crédito -->
<div class="modal fade" id="modalCuotas" tabindex="-1" role="dialog" aria-labelledby="modalCuotasLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content shadow-lg border-0 modal-moderno">
			<div class="modal-header bg-dark text-white border-0">
				<h5 class="modal-title font-weight-bold mb-0 font-cairo" id="modalCuotasLabel">
					<i class="fas fa-calendar-alt text-warning mr-2"></i> Plan de Cuotas a Crédito
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body p-3">
				<generar_cuota :total="ventaCabecera.total" :fecha="ventaCabecera.fecha" :calcularcuota="ventaCabecera.generarcuota" :datoscuota="tmpIndexPrecio" ref="generarcuota" @cuotas="setCuotas"/>
			</div>
			<div class="modal-footer bg-light-panel border-top">
				<button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">
					<i class="fas fa-times mr-1"></i> Cancelar
				</button>
				<button type="button" class="btn btn-success font-weight-bold font-cairo" @click="confirmarCuotas" :disabled="!(cuotas && cuotas.length)">
					<i class="fas fa-check mr-1"></i> Aplicar Plan de Cuotas
				</button>
			</div>
		</div>
	</div>
</div>
