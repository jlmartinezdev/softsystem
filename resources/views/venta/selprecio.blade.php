<div class="modal fade" id="selPrecio" tabindex="-1" role="dialog" aria-labelledby="selPrecioLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content shadow-lg border-0 modal-moderno">
			<div class="modal-header border-0 pb-0">
				<div class="d-flex align-items-center">
					<div class="modal-header-icon mr-2">
						<i class="fas fa-tags text-success"></i>
					</div>
					<div>
						<h5 class="modal-title font-weight-bold mb-0" id="selPrecioLabel">Elegí el Precio de Venta</h5>
						<span class="small text-muted text-truncate d-block" style="max-width: 320px;">
							@{{ preciosContado.articulo }}
						</span>
					</div>
				</div>
				<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body pt-3">
				<!-- Selector de Tipo: Contado o Crédito -->
				<div class="pago-condicion-switch mb-3" role="group" aria-label="Contado o crédito">
					<button type="button" class="pago-chip"
						:class="{ selected: precioVista === 'contado' }"
						@click="precioVista = 'contado'">
						<i class="fas fa-coins mr-1"></i> Contado
					</button>
					<button type="button" class="pago-chip"
						:class="{ selected: precioVista === 'credito' }"
						@click="precioVista = 'credito'">
						<i class="fas fa-calendar-alt mr-1"></i> Crédito
					</button>
				</div>

				<!-- Opciones Contado -->
				<div v-show="precioVista === 'contado'">
					<template v-if="articulo && articulo.es_combo">
						<div class="precio-listas-grid">
							<button type="button" class="precio-card-btn"
								:class="{ on: tmpIndexPrecio.iPrecio === 'CO1' }"
								@click="elegirListaContado(1)">
								<div class="precio-card-header">
									<span class="precio-card-name">Precio Contado</span>
									<i class="fas fa-check-circle check-active" v-if="tmpIndexPrecio.iPrecio === 'CO1'"></i>
								</div>
								<strong class="precio-card-monto font-cairo">
									Gs. @{{ format(articulo.precio_contado || articulo.p1 || articulo.precio) }}
								</strong>
								<span class="precio-card-note">Combo al contado</span>
							</button>
						</div>
					</template>
					<template v-else>
						<div class="precio-listas-grid">
							<button type="button" class="precio-card-btn"
								v-for="n in listasContado"
								:key="'CO'+n"
								:class="{ on: tmpIndexPrecio.iPrecio === 'CO'+n }"
								@click="elegirListaContado(n)">
								<div class="precio-card-header">
									<span class="precio-card-name">Precio @{{ n }}</span>
									<i class="fas fa-check-circle check-active" v-if="tmpIndexPrecio.iPrecio === 'CO'+n"></i>
								</div>
								<strong class="precio-card-monto font-cairo">
									Gs. @{{ format(preciosContado['p'+n]) }}
								</strong>
								<span class="precio-card-note">
									@{{ n === 1 ? 'Minorista / Estándar' : (n === 2 ? 'Mayorista' : 'Especial') }}
								</span>
							</button>
						</div>
					</template>
				</div>

				<!-- Opciones Crédito -->
				<div v-show="precioVista === 'credito'">
					<template v-if="articulo && articulo.es_combo">
						<div class="precio-listas-grid" v-if="Number(articulo.precio_credito) > 0">
							<button type="button" class="precio-card-btn"
								:class="{ on: tmpIndexPrecio.iPrecio === 'CR0' }"
								@click="elegirListaCredito(0)">
								<div class="precio-card-header">
									<span class="precio-card-name">Precio Crédito</span>
									<i class="fas fa-check-circle check-active" v-if="tmpIndexPrecio.iPrecio === 'CR0'"></i>
								</div>
								<strong class="precio-card-monto font-cairo">
									Gs. @{{ format(articulo.precio_credito) }}
								</strong>
								<span class="precio-card-note">Combo a crédito</span>
							</button>
						</div>
						<div class="p-3 text-center text-muted border rounded" v-else>
							<i class="fas fa-info-circle mr-1"></i> Este combo no tiene configurado precio a crédito.
						</div>
					</template>
					<template v-else>
						<div class="precio-listas-grid" v-if="preciosCreditoConMonto.length">
							<button type="button" class="precio-card-btn"
								v-for="(p, index) in preciosCreditoConMonto"
								:key="'CR'+p.index"
								:class="{ on: tmpIndexPrecio.iPrecio === 'CR'+p.index }"
								@click="elegirListaCredito(p.index)">
								<div class="precio-card-header">
									<span class="precio-card-name">Plan @{{ p.index + 2 }} cuotas</span>
									<i class="fas fa-check-circle check-active" v-if="tmpIndexPrecio.iPrecio === 'CR'+p.index"></i>
								</div>
								<strong class="precio-card-monto font-cairo">
									Gs. @{{ format(p.p) }}
								</strong>
								<span class="precio-card-note">Cuota: Gs. @{{ format(p.c) }}</span>
							</button>
						</div>
						<div class="p-3 text-center text-muted border rounded" v-else>
							<i class="fas fa-info-circle mr-1"></i> Este artículo no tiene precio a crédito configurado.
						</div>
					</template>
					<p class="small text-muted mt-2 mb-0 font-italic">
						<i class="fas fa-info-circle mr-1"></i> Al seleccionar esta opción, la venta se configurará en condición crédito.
					</p>
				</div>
			</div>
			<div class="modal-footer bg-light-panel border-top py-2">
				<button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">
					<i class="fas fa-times mr-1"></i> Cerrar
				</button>
			</div>
		</div>
	</div>
</div>
