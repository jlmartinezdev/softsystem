@extends('layouts.app')
@section('title', 'Combos')
@section('style')
<style>
	.combo-item-row td { vertical-align: middle; }
	.combo-search-results {
		max-height: 220px;
		overflow-y: auto;
		border: 1px solid #dee2e6;
		border-radius: .25rem;
	}
	.combo-search-results .list-group-item { cursor: pointer; padding: .45rem .75rem; }
	.combo-search-results .list-group-item:hover { background: #f8f9fa; }
</style>
@endsection
@section('main')
<div class="container-fluid" id="app">
	<div class="content-header">
		<div class="row mb-2 align-items-center">
			<div class="col-md-8">
				<h4 class="m-0">Combos</h4>
				<p class="text-muted mb-0 small">Armá packs con varios artículos y un precio redondeado.</p>
			</div>
			<div class="col-md-4 text-md-right">
				<a href="{{ route('articulo') }}" class="btn btn-outline-secondary btn-sm mr-1">
					<span class="fa fa-arrow-left"></span> Artículos
				</a>
				<button type="button" class="btn btn-primary btn-sm" @click="nuevo">
					<span class="fa fa-plus"></span> Nuevo combo
				</button>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-5">
			<div class="card card-outline card-primary">
				<div class="card-header"><strong>Listado</strong></div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-sm table-striped mb-0">
							<thead>
								<tr>
									<th>Nombre</th>
									<th class="text-right">Precio</th>
									<th>Estado</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="c in combos" :key="c.id"
									:class="{ 'table-active': form.id === c.id }"
									style="cursor:pointer" @click="editar(c)">
									<td>
										<strong>@{{ c.nombre }}</strong>
										<div class="small text-muted" v-if="c.codigo">@{{ c.codigo }}</div>
										<div class="small text-muted">@{{ (c.items || []).length }} ítem(s)</div>
									</td>
									<td class="text-right">
										<strong>Gs. @{{ format(c.precio) }}</strong>
										<div class="small text-muted" v-if="c.precio_lista > c.precio">
											Lista @{{ format(c.precio_lista) }}
										</div>
									</td>
									<td>
										<span class="badge" :class="Number(c.activo) ? 'badge-success' : 'badge-secondary'">
											@{{ Number(c.activo) ? 'Activo' : 'Inactivo' }}
										</span>
									</td>
									<td class="text-right" @click.stop>
										<button class="btn btn-outline-danger btn-xs" @click="eliminar(c)" title="Eliminar">
											<span class="fa fa-trash"></span>
										</button>
									</td>
								</tr>
								<tr v-if="!combos.length">
									<td colspan="4" class="text-center text-muted py-4">Todavía no hay combos.</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-7">
			<div class="card card-outline" :class="form.id ? 'card-warning' : 'card-success'">
				<div class="card-header d-flex justify-content-between align-items-center">
					<strong>@{{ form.id ? 'Editar combo #' + form.id : 'Nuevo combo' }}</strong>
					<span class="badge badge-light" v-if="ahorro > 0">Ahorro Gs. @{{ format(ahorro) }}</span>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-8 form-group">
							<label>Nombre *</label>
							<input type="text" class="form-control form-control-sm" v-model.trim="form.nombre"
								placeholder="Ej: Combo almuerzo">
						</div>
						<div class="col-md-4 form-group">
							<label>Código de barras *</label>
							<input type="text" class="form-control form-control-sm"
								:class="{'is-invalid': codigoEstado === 'error', 'is-valid': codigoEstado === 'ok'}"
								v-model.trim="form.codigo"
								placeholder="Ej: CMB001"
								@blur="validarCodigo"
								@input="codigoEstado = ''">
							<small class="form-text" :class="codigoEstado === 'error' ? 'text-danger' : 'text-muted'"
								v-if="codigoMensaje">@{{ codigoMensaje }}</small>
						</div>
					</div>

					<div class="form-group">
						<label>Buscar artículo para agregar</label>
						<div class="input-group input-group-sm">
							<input type="text" class="form-control" v-model.trim="buscar"
								placeholder="Nombre o código..." @keyup.enter="buscarArticulos"
								@input="onBuscarInput">
							<div class="input-group-append">
								<button class="btn btn-outline-secondary" type="button" @click="buscarArticulos"
									:disabled="buscando">
									<span class="fa" :class="buscando ? 'fa-spinner fa-spin' : 'fa-search'"></span>
								</button>
							</div>
						</div>
						<div class="combo-search-results mt-1" v-if="resultados.length">
							<button type="button" class="list-group-item list-group-item-action"
								v-for="a in resultados" :key="a.ARTICULOS_cod" @click="agregarItem(a)">
								<div class="d-flex justify-content-between">
									<span>@{{ a.producto_nombre }}</span>
									<strong>Gs. @{{ format(a.pre_venta1) }}</strong>
								</div>
								<small class="text-muted">@{{ a.producto_c_barra || '—' }}</small>
							</button>
						</div>
					</div>

					<div class="table-responsive">
						<table class="table table-sm table-bordered">
							<thead>
								<tr>
									<th>Artículo</th>
									<th style="width:90px">Cant.</th>
									<th class="text-right">P. ref.</th>
									<th class="text-right">Subtotal</th>
									<th style="width:40px"></th>
								</tr>
							</thead>
							<tbody>
								<tr class="combo-item-row" v-for="(it, idx) in form.items" :key="it.articulos_cod">
									<td>
										<strong>@{{ it.nombre }}</strong>
										<div class="small text-muted">@{{ it.codigo || '—' }}</div>
									</td>
									<td>
										<input type="number" class="form-control form-control-sm" min="0.01" step="0.01"
											v-model.number="it.cantidad" @change="recalcular">
									</td>
									<td class="text-right">Gs. @{{ format(it.precio_ref) }}</td>
									<td class="text-right">Gs. @{{ format(it.cantidad * it.precio_ref) }}</td>
									<td class="text-center">
										<button type="button" class="btn btn-link btn-sm text-danger" @click="quitarItem(idx)">
											<span class="fa fa-times"></span>
										</button>
									</td>
								</tr>
								<tr v-if="!form.items.length">
									<td colspan="5" class="text-center text-muted">Agregá al menos 2 artículos.</td>
								</tr>
							</tbody>
							<tfoot v-if="form.items.length">
								<tr>
									<th colspan="3" class="text-right">Suma lista</th>
									<th class="text-right">Gs. @{{ format(precioLista) }}</th>
									<th></th>
								</tr>
							</tfoot>
						</table>
					</div>

					<div class="row align-items-end">
						<div class="col-md-4 form-group">
							<label>Suma artículos</label>
							<input type="text" class="form-control form-control-sm" :value="'Gs. ' + format(precioLista)" disabled>
						</div>
						<div class="col-md-4 form-group">
							<label>Precio combo *</label>
							<div class="input-group input-group-sm">
								<div class="input-group-prepend"><span class="input-group-text">Gs.</span></div>
								<input type="number" class="form-control" v-model.number="form.precio" min="1" step="1"
									@input="precioEditado = true">
							</div>
							
						</div>
						<div class="col-md-4 form-group">
							<label>Redondear (opcional)</label>
							<div class="input-group input-group-sm">
								<select class="form-control" v-model.number="form.multiplo">
									<option :value="100">100</option>
									<option :value="500">500</option>
									<option :value="1000">1.000</option>
									<option :value="5000">5.000</option>
								</select>
								<div class="input-group-append">
									<button type="button" class="btn btn-outline-info" @click="aplicarRedondeo" title="Aplicar redondeo">
										<span class="fa fa-magic"></span>
									</button>
								</div>
							</div>
						</div>
					</div>

					<div class="mb-2" v-if="form.precio !== precioLista">
						<button type="button" class="btn btn-link btn-sm p-0" @click="usarSuma">
							<span class="fa fa-undo"></span> Volver a la suma (Gs. @{{ format(precioLista) }})
						</button>
					</div>

					<div class="custom-control custom-switch mb-3">
						<input type="checkbox" class="custom-control-input" id="comboActivo" v-model="form.activo">
						<label class="custom-control-label" for="comboActivo">Combo activo (visible en venta)</label>
					</div>

					<div class="form-group mb-0">
						<label>Observación</label>
						<input type="text" class="form-control form-control-sm" v-model.trim="form.observacion"
							placeholder="Opcional">
					</div>
				</div>
				<div class="card-footer">
					<button type="button" class="btn btn-secondary btn-sm" @click="nuevo">Limpiar</button>
					<button type="button" class="btn btn-success btn-sm float-right" @click="guardar" :disabled="guardando">
						<span class="fa" :class="guardando ? 'fa-spinner fa-spin' : 'fa-save'"></span>
						@{{ form.id ? 'Actualizar' : 'Guardar combo' }}
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
	var Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 2500
	});

	var app = new Vue({
		el: '#app',
		data: {
			combos: @json($combos),
			buscar: '',
			buscando: false,
			resultados: [],
			buscarTimer: null,
			guardando: false,
			precioEditado: false,
			codigoEstado: '',
			codigoMensaje: '',
			form: {
				id: null,
				nombre: '',
				codigo: '',
				precio: 0,
				multiplo: 500,
				activo: true,
				observacion: '',
				items: []
			}
		},
		computed: {
			precioLista: function () {
				var total = 0;
				this.form.items.forEach(function (it) {
					total += (Number(it.cantidad) || 0) * (Number(it.precio_ref) || 0);
				});
				return total;
			},
			ahorro: function () {
				return Math.max(0, this.precioLista - (Number(this.form.precio) || 0));
			}
		},
		methods: {
			format: function (n) {
				return new Intl.NumberFormat('de-DE').format(Number(n) || 0);
			},
			blankForm: function () {
				return {
					id: null,
					nombre: '',
					codigo: '',
					precio: 0,
					multiplo: 500,
					activo: true,
					observacion: '',
					items: []
				};
			},
			nuevo: function () {
				this.form = this.blankForm();
				this.precioEditado = false;
				this.resultados = [];
				this.buscar = '';
				this.codigoEstado = '';
				this.codigoMensaje = '';
			},
			editar: function (c) {
				this.form = {
					id: c.id,
					nombre: c.nombre,
					codigo: c.codigo || '',
					precio: Number(c.precio) || 0,
					multiplo: 500,
					activo: Number(c.activo) === 1,
					observacion: c.observacion || '',
					items: (c.items || []).map(function (it) {
						var art = it.articulo || {};
						return {
							articulos_cod: it.articulos_cod,
							nombre: art.producto_nombre || ('#' + it.articulos_cod),
							codigo: art.producto_c_barra || '',
							cantidad: Number(it.cantidad) || 1,
							precio_ref: Number(it.precio_ref) || Number(art.pre_venta1) || 0
						};
					})
				};
				this.precioEditado = Number(c.precio) !== Number(c.precio_lista);
				this.resultados = [];
				this.codigoEstado = '';
				this.codigoMensaje = '';
			},
			validarCodigo: function () {
				var self = this;
				var codigo = (this.form.codigo || '').trim();
				if (!codigo) {
					this.codigoEstado = 'error';
					this.codigoMensaje = 'El código de barras es obligatorio.';
					return Promise.resolve(false);
				}
				return axios.get('{{ url('combo/validar-codigo') }}', {
					params: { codigo: codigo, id: this.form.id || null }
				}).then(function (r) {
					var d = r.data || {};
					self.codigoEstado = d.ok ? 'ok' : 'error';
					self.codigoMensaje = d.message || '';
					return !!d.ok;
				}).catch(function () {
					self.codigoEstado = 'error';
					self.codigoMensaje = 'No se pudo validar el código.';
					return false;
				});
			},
			onBuscarInput: function () {
				var self = this;
				if (this.buscarTimer) clearTimeout(this.buscarTimer);
				this.buscarTimer = setTimeout(function () {
					if ((self.buscar || '').trim().length >= 2) {
						self.buscarArticulos();
					} else {
						self.resultados = [];
					}
				}, 300);
			},
			buscarArticulos: function () {
				var self = this;
				this.buscando = true;
				axios.get('{{ url('combo/articulos') }}', { params: { buscar: this.buscar } })
					.then(function (r) {
						self.buscando = false;
						self.resultados = r.data || [];
					})
					.catch(function () {
						self.buscando = false;
						self.resultados = [];
					});
			},
			agregarItem: function (a) {
				var cod = a.ARTICULOS_cod || a.articulos_cod;
				var exists = this.form.items.findIndex(function (x) { return String(x.articulos_cod) === String(cod); });
				if (exists !== -1) {
					this.form.items[exists].cantidad = Number(this.form.items[exists].cantidad) + 1;
				} else {
					this.form.items.push({
						articulos_cod: cod,
						nombre: a.producto_nombre,
						codigo: a.producto_c_barra || '',
						cantidad: 1,
						precio_ref: Number(a.pre_venta1) || 0
					});
				}
				this.resultados = [];
				this.buscar = '';
				this.recalcular();
			},
			quitarItem: function (idx) {
				this.form.items.splice(idx, 1);
				this.recalcular();
			},
			usarSuma: function () {
				this.precioEditado = false;
				this.form.precio = this.precioLista;
			},
			recalcular: function () {
				// Si el usuario no tocó el precio (o pidió volver a la suma), usar suma de artículos
				if (!this.precioEditado) {
					this.form.precio = this.precioLista;
				}
			},
			aplicarRedondeo: function () {
				var multiplo = Number(this.form.multiplo) || 500;
				var lista = this.precioLista;
				this.form.precio = Math.round(lista / multiplo) * multiplo;
				if (this.form.precio <= 0 && lista > 0) {
					this.form.precio = multiplo;
				}
				this.precioEditado = true;
			},
			payload: function () {
				return {
					nombre: this.form.nombre,
					codigo: this.form.codigo,
					precio: this.form.precio,
					multiplo: this.form.multiplo,
					activo: this.form.activo ? 1 : 0,
					observacion: this.form.observacion,
					items: this.form.items.map(function (it) {
						return {
							articulos_cod: it.articulos_cod,
							cantidad: it.cantidad,
							precio_ref: it.precio_ref
						};
					})
				};
			},
			guardar: function () {
				var self = this;
				if (!this.form.nombre) {
					Swal.fire('Falta nombre', 'Indicá el nombre del combo.', 'warning');
					return;
				}
				if (!(this.form.codigo || '').trim()) {
					Swal.fire('Falta código', 'Indicá el código de barras del combo.', 'warning');
					return;
				}
				if (this.form.items.length < 2) {
					Swal.fire('Faltan artículos', 'Seleccioná al menos 2 artículos.', 'warning');
					return;
				}
				if (!(this.form.precio > 0)) {
					Swal.fire('Falta precio', 'Definí el precio del combo.', 'warning');
					return;
				}
				this.guardando = true;
				this.validarCodigo().then(function (ok) {
					if (!ok) {
						self.guardando = false;
						Swal.fire('Código inválido', self.codigoMensaje || 'Revisá el código de barras.', 'warning');
						return;
					}
					var req = self.form.id
						? axios.put('{{ url('combo') }}/' + self.form.id, self.payload())
						: axios.post('{{ url('combo') }}', self.payload());

					req.then(function (r) {
						self.guardando = false;
						Toast.fire({ icon: 'success', title: r.data.message || 'Guardado' });
						window.location.reload();
					}).catch(function (err) {
						self.guardando = false;
						var msg = (err.response && err.response.data && err.response.data.message)
							? err.response.data.message
							: 'No se pudo guardar';
						Swal.fire('Error', msg, 'error');
					});
				});
			},
			eliminar: function (c) {
				var self = this;
				Swal.fire({
					title: 'Eliminar combo?',
					text: c.nombre,
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Sí, eliminar',
					cancelButtonText: 'Cancelar'
				}).then(function (res) {
					if (!res.value) return;
					axios.delete('{{ url('combo') }}/' + c.id)
						.then(function () {
							Toast.fire({ icon: 'success', title: 'Combo eliminado' });
							window.location.reload();
						})
						.catch(function () {
							Swal.fire('Error', 'No se pudo eliminar', 'error');
						});
				});
			}
		},
		mounted: function () {
			activarMenu('m_articulo', '');
		}
	});
</script>
@endsection
