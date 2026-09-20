@extends('layouts.app')
@section('title', 'Ofertas')
@section('style')
<style>
	.oferta-search-results {
		max-height: 220px;
		overflow-y: auto;
		border: 1px solid #dee2e6;
		border-radius: .25rem;
	}
	.oferta-search-results .list-group-item { cursor: pointer; padding: .45rem .75rem; }
	.oferta-search-results .list-group-item:hover { background: #f8f9fa; }
</style>
@endsection
@section('main')
<div class="container-fluid" id="app">
	<div class="content-header">
		<div class="row mb-2 align-items-center">
			<div class="col-md-8">
				<h4 class="m-0">Ofertas</h4>
				<p class="text-muted mb-0 small">Descuentos por cantidad y/o por rango de fechas.</p>
			</div>
			<div class="col-md-4 text-md-right">
				<a href="{{ route('articulo') }}" class="btn btn-outline-secondary btn-sm mr-1">
					<span class="fa fa-arrow-left"></span> Artículos
				</a>
				<button type="button" class="btn btn-primary btn-sm" @click="nuevo">
					<span class="fa fa-plus"></span> Nueva oferta
				</button>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-5">
			<div class="card card-outline card-primary">
				<div class="card-header"><strong>Listado</strong></div>
				<div class="card-body p-0">
					<table class="table table-sm table-striped mb-0">
						<thead>
							<tr>
								<th>Oferta</th>
								<th>Condición</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="o in ofertas" :key="o.id"
								:class="{ 'table-active': form.id === o.id }"
								style="cursor:pointer" @click="editar(o)">
								<td>
									<strong>@{{ o.nombre }}</strong>
									<div class="small text-muted">@{{ (o.articulo && o.articulo.producto_nombre) || ('#' + o.articulos_cod) }}</div>
									<div class="small text-muted" v-if="o.codigo">Cod: @{{ o.codigo }}</div>
									<span class="badge" :class="Number(o.activo) ? 'badge-success' : 'badge-secondary'">
										@{{ Number(o.activo) ? 'Activa' : 'Inactiva' }}
									</span>
								</td>
								<td class="small">
									<div>@{{ labelTipo(o) }}</div>
									<div class="text-success font-weight-bold">@{{ labelDescuento(o) }}</div>
								</td>
								<td class="text-right" @click.stop>
									<button class="btn btn-outline-danger btn-xs" @click="eliminar(o)">
										<span class="fa fa-trash"></span>
									</button>
								</td>
							</tr>
							<tr v-if="!ofertas.length">
								<td colspan="3" class="text-center text-muted py-4">Todavía no hay ofertas.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="col-lg-7">
			<div class="card card-outline" :class="form.id ? 'card-warning' : 'card-success'">
				<div class="card-header">
					<strong>@{{ form.id ? 'Editar oferta #' + form.id : 'Nueva oferta' }}</strong>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-8 form-group">
							<label>Nombre *</label>
							<input type="text" class="form-control form-control-sm" v-model.trim="form.nombre"
								placeholder="Ej: Promo 3x, Oferta de semana">
						</div>
						<div class="col-md-4 form-group">
							<label>Código de barras *</label>
							<input type="text" class="form-control form-control-sm"
								:class="{'is-invalid': codigoEstado === 'error', 'is-valid': codigoEstado === 'ok'}"
								v-model.trim="form.codigo"
								placeholder="Ej: OFT001"
								@blur="validarCodigo"
								@input="codigoEstado = ''">
							<small class="form-text" :class="codigoEstado === 'error' ? 'text-danger' : 'text-muted'"
								v-if="codigoMensaje">@{{ codigoMensaje }}</small>
						</div>
					</div>

					<div class="form-group">
						<label>Artículo *</label>
						<div v-if="form.articulo_nombre" class="alert alert-light border py-2 mb-2">
							<strong>@{{ form.articulo_nombre }}</strong>
							<span class="text-muted small ml-2">@{{ form.articulo_codigo }}</span>
							<span class="float-right">Gs. @{{ format(form.articulo_precio) }}</span>
							<button type="button" class="btn btn-link btn-sm text-danger p-0 ml-2" @click="limpiarArticulo">cambiar</button>
						</div>
						<template v-else>
							<div class="input-group input-group-sm">
								<input type="text" class="form-control" v-model.trim="buscar"
									placeholder="Buscar artículo..." @input="onBuscarInput" @keyup.enter="buscarArticulos">
								<div class="input-group-append">
									<button class="btn btn-outline-secondary" type="button" @click="buscarArticulos">
										<span class="fa" :class="buscando ? 'fa-spinner fa-spin' : 'fa-search'"></span>
									</button>
								</div>
							</div>
							<div class="oferta-search-results mt-1" v-if="resultados.length">
								<button type="button" class="list-group-item list-group-item-action"
									v-for="a in resultados" :key="a.ARTICULOS_cod" @click="elegirArticulo(a)">
									<div class="d-flex justify-content-between">
										<span>@{{ a.producto_nombre }}</span>
										<strong>Gs. @{{ format(a.pre_venta1) }}</strong>
									</div>
									<small class="text-muted">@{{ a.producto_c_barra || '—' }}</small>
								</button>
							</div>
						</template>
					</div>

					<div class="form-group">
						<label>Tipo de oferta *</label>
						<select class="form-control form-control-sm" v-model="form.tipo">
							<option value="cantidad">Descuento desde cierta cantidad</option>
							<option value="fecha">Descuento por fecha</option>
							<option value="ambos">Cantidad + fecha</option>
						</select>
					</div>

					<div class="row" v-if="form.tipo === 'cantidad' || form.tipo === 'ambos'">
						<div class="col-md-6 form-group">
							<label>Cantidad mínima *</label>
							<input type="number" class="form-control form-control-sm" v-model.number="form.cantidad_min"
								min="1" step="1" placeholder="Ej: 3">
						</div>
					</div>

					<div class="row" v-if="form.tipo === 'fecha' || form.tipo === 'ambos'">
						<div class="col-md-6 form-group">
							<label>Desde</label>
							<input type="date" class="form-control form-control-sm" v-model="form.fecha_desde">
						</div>
						<div class="col-md-6 form-group">
							<label>Hasta</label>
							<input type="date" class="form-control form-control-sm" v-model="form.fecha_hasta">
						</div>
					</div>

					<div class="row">
						<div class="col-md-6 form-group">
							<label>Tipo de descuento *</label>
							<select class="form-control form-control-sm" v-model="form.descuento_tipo">
								<option value="porcentaje">Porcentaje %</option>
								<option value="monto">Monto fijo (Gs.)</option>
								<option value="precio_fijo">Precio final fijo</option>
							</select>
						</div>
						<div class="col-md-6 form-group">
							<label>Valor *</label>
							<input type="number" class="form-control form-control-sm" v-model.number="form.descuento_valor"
								min="0" step="1" :placeholder="form.descuento_tipo === 'porcentaje' ? 'Ej: 10' : 'Ej: 5000'">
						</div>
					</div>

					<div class="alert alert-info small py-2" v-if="form.articulo_precio && form.descuento_valor >= 0">
						Precio lista: <strong>Gs. @{{ format(form.articulo_precio) }}</strong>
						→ con oferta: <strong>Gs. @{{ format(precioPreview) }}</strong>
					</div>

					<div class="custom-control custom-switch mb-3">
						<input type="checkbox" class="custom-control-input" id="ofertaActiva" v-model="form.activo">
						<label class="custom-control-label" for="ofertaActiva">Oferta activa</label>
					</div>

					<div class="form-group mb-0">
						<label>Observación</label>
						<input type="text" class="form-control form-control-sm" v-model.trim="form.observacion">
					</div>
				</div>
				<div class="card-footer">
					<button type="button" class="btn btn-secondary btn-sm" @click="nuevo">Limpiar</button>
					<button type="button" class="btn btn-success btn-sm float-right" @click="guardar" :disabled="guardando">
						<span class="fa" :class="guardando ? 'fa-spinner fa-spin' : 'fa-save'"></span>
						@{{ form.id ? 'Actualizar' : 'Guardar oferta' }}
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
	var Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500 });

	var app = new Vue({
		el: '#app',
		data: {
			ofertas: @json($ofertas),
			buscar: '',
			buscando: false,
			resultados: [],
			buscarTimer: null,
			guardando: false,
			codigoEstado: '',
			codigoMensaje: '',
			form: {
				id: null,
				nombre: '',
				codigo: '',
				articulos_cod: 0,
				articulo_nombre: '',
				articulo_codigo: '',
				articulo_precio: 0,
				tipo: 'cantidad',
				cantidad_min: 2,
				fecha_desde: '',
				fecha_hasta: '',
				descuento_tipo: 'porcentaje',
				descuento_valor: 10,
				activo: true,
				observacion: ''
			}
		},
		computed: {
			precioPreview: function () {
				var base = Number(this.form.articulo_precio) || 0;
				var v = Number(this.form.descuento_valor) || 0;
				if (this.form.descuento_tipo === 'monto') return Math.max(0, base - v);
				if (this.form.descuento_tipo === 'precio_fijo') return Math.max(0, v);
				return Math.max(0, Math.round(base * (1 - v / 100)));
			}
		},
		methods: {
			format: function (n) {
				return new Intl.NumberFormat('de-DE').format(Number(n) || 0);
			},
			labelTipo: function (o) {
				if (o.tipo === 'cantidad') return 'Desde ' + o.cantidad_min + ' u.';
				if (o.tipo === 'fecha') {
					return (o.fecha_desde || '…') + ' → ' + (o.fecha_hasta || '…');
				}
				return '≥' + o.cantidad_min + ' u. · ' + (o.fecha_desde || '…') + ' → ' + (o.fecha_hasta || '…');
			},
			labelDescuento: function (o) {
				if (o.descuento_tipo === 'monto') return '- Gs. ' + this.format(o.descuento_valor);
				if (o.descuento_tipo === 'precio_fijo') return 'Precio Gs. ' + this.format(o.descuento_valor);
				return o.descuento_valor + '% OFF';
			},
			blankForm: function () {
				return {
					id: null,
					nombre: '',
					codigo: '',
					articulos_cod: 0,
					articulo_nombre: '',
					articulo_codigo: '',
					articulo_precio: 0,
					tipo: 'cantidad',
					cantidad_min: 2,
					fecha_desde: '',
					fecha_hasta: '',
					descuento_tipo: 'porcentaje',
					descuento_valor: 10,
					activo: true,
					observacion: ''
				};
			},
			nuevo: function () {
				this.form = this.blankForm();
				this.resultados = [];
				this.buscar = '';
				this.codigoEstado = '';
				this.codigoMensaje = '';
			},
			editar: function (o) {
				var art = o.articulo || {};
				this.form = {
					id: o.id,
					nombre: o.nombre,
					codigo: o.codigo || '',
					articulos_cod: o.articulos_cod,
					articulo_nombre: art.producto_nombre || '',
					articulo_codigo: art.producto_c_barra || '',
					articulo_precio: Number(art.pre_venta1) || 0,
					tipo: o.tipo,
					cantidad_min: o.cantidad_min != null ? Number(o.cantidad_min) : 2,
					fecha_desde: o.fecha_desde || '',
					fecha_hasta: o.fecha_hasta || '',
					descuento_tipo: o.descuento_tipo,
					descuento_valor: Number(o.descuento_valor) || 0,
					activo: Number(o.activo) === 1,
					observacion: o.observacion || ''
				};
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
				return axios.get('{{ url('oferta/validar-codigo') }}', {
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
			limpiarArticulo: function () {
				this.form.articulos_cod = 0;
				this.form.articulo_nombre = '';
				this.form.articulo_codigo = '';
				this.form.articulo_precio = 0;
			},
			onBuscarInput: function () {
				var self = this;
				if (this.buscarTimer) clearTimeout(this.buscarTimer);
				this.buscarTimer = setTimeout(function () {
					if ((self.buscar || '').trim().length >= 2) self.buscarArticulos();
					else self.resultados = [];
				}, 300);
			},
			buscarArticulos: function () {
				var self = this;
				this.buscando = true;
				axios.get('{{ url('oferta/articulos') }}', { params: { buscar: this.buscar } })
					.then(function (r) {
						self.buscando = false;
						self.resultados = r.data || [];
					})
					.catch(function () {
						self.buscando = false;
						self.resultados = [];
					});
			},
			elegirArticulo: function (a) {
				this.form.articulos_cod = a.ARTICULOS_cod || a.articulos_cod;
				this.form.articulo_nombre = a.producto_nombre;
				this.form.articulo_codigo = a.producto_c_barra || '';
				this.form.articulo_precio = Number(a.pre_venta1) || 0;
				this.resultados = [];
				this.buscar = '';
			},
			payload: function () {
				return {
					nombre: this.form.nombre,
					codigo: this.form.codigo,
					articulos_cod: this.form.articulos_cod,
					tipo: this.form.tipo,
					cantidad_min: this.form.cantidad_min,
					fecha_desde: this.form.fecha_desde || null,
					fecha_hasta: this.form.fecha_hasta || null,
					descuento_tipo: this.form.descuento_tipo,
					descuento_valor: this.form.descuento_valor,
					activo: this.form.activo ? 1 : 0,
					observacion: this.form.observacion
				};
			},
			guardar: function () {
				var self = this;
				if (!this.form.nombre) {
					Swal.fire('Falta nombre', 'Indicá el nombre de la oferta.', 'warning');
					return;
				}
				if (!(this.form.codigo || '').trim()) {
					Swal.fire('Falta código', 'Indicá el código de barras de la oferta.', 'warning');
					return;
				}
				if (!this.form.articulos_cod) {
					Swal.fire('Falta artículo', 'Seleccioná un artículo.', 'warning');
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
						? axios.put('{{ url('oferta') }}/' + self.form.id, self.payload())
						: axios.post('{{ url('oferta') }}', self.payload());
					req.then(function (r) {
						self.guardando = false;
						Toast.fire({ icon: 'success', title: r.data.message || 'Guardado' });
						window.location.reload();
					}).catch(function (err) {
						self.guardando = false;
						var msg = (err.response && err.response.data && err.response.data.message)
							? err.response.data.message : 'No se pudo guardar';
						Swal.fire('Error', msg, 'error');
					});
				});
			},
			eliminar: function (o) {
				Swal.fire({
					title: 'Eliminar oferta?',
					text: o.nombre,
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Sí, eliminar',
					cancelButtonText: 'Cancelar'
				}).then(function (res) {
					if (!res.value) return;
					axios.delete('{{ url('oferta') }}/' + o.id).then(function () {
						Toast.fire({ icon: 'success', title: 'Oferta eliminada' });
						window.location.reload();
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
