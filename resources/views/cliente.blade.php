@extends('layouts.app')
@section('title', 'Clientes')
@section('style')
<style>
	.cliente-abm .cliente-avatar {
		width: 34px;
		height: 34px;
		border-radius: 50%;
		background: #e9ecef;
		color: #495057;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		font-size: 0.85rem;
		flex: 0 0 34px;
	}
	.cliente-abm .search-wrap {
		position: relative;
	}
	.cliente-abm .search-wrap .fa-search {
		position: absolute;
		left: 12px;
		top: 50%;
		transform: translateY(-50%);
		color: #6c757d;
		z-index: 2;
	}
	.cliente-abm .search-wrap input {
		padding-left: 2.1rem;
	}
	.cliente-abm .cliente-search {
		padding: 0.5rem 0.75rem !important;
		flex: 0 0 auto !important;
	}
	.cliente-abm .cliente-list-body {
		flex: 1 1 auto;
		max-height: 65vh;
		overflow-y: auto;
		padding: 0.5rem !important;
		background: #f4f6f9;
	}
	.cliente-abm .cliente-card {
		display: flex;
		align-items: flex-start;
		gap: 0.65rem;
		background: #fff;
		border: 1px solid #e3e6ea;
		border-radius: 0.5rem;
		padding: 0.65rem 0.7rem;
		margin-bottom: 0.45rem;
		cursor: pointer;
		transition: border-color .15s, box-shadow .15s;
	}
	.cliente-abm .cliente-card:last-child {
		margin-bottom: 0;
	}
	.cliente-abm .cliente-card:hover {
		border-color: #adb5bd;
		box-shadow: 0 1px 4px rgba(0,0,0,.06);
	}
	.cliente-abm .cliente-card.active {
		border-color: #007bff;
		box-shadow: 0 0 0 1px rgba(0,123,255,.2);
		background: #f8fbff;
	}
	.cliente-abm .cliente-card-body {
		flex: 1 1 auto;
		min-width: 0;
	}
	.cliente-abm .cliente-card-actions {
		flex: 0 0 auto;
		padding-top: 0.1rem;
	}
</style>
@endsection
@section('main')
<div class="container-fluid cliente-abm" id="app">
	<div class="content-header">
		<div class="row mb-2 align-items-center">
			<div class="col-md-8">
				<h4 class="m-0">Clientes</h4>
				<p class="text-muted mb-0 small">Alta, edición y búsqueda de clientes.</p>
			</div>
			<div class="col-md-4 text-md-right">
				<button type="button" class="btn btn-primary btn-sm" @click="nuevo">
					<span class="fa fa-plus"></span> Nuevo cliente
				</button>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-5 mb-3">
			<div class="card card-outline card-primary">
				<div class="card-header d-flex align-items-center justify-content-between">
					<strong>Listado</strong>
					<span class="badge badge-light" v-if="clientes.length">@{{ clientes.length }}</span>
				</div>
				<div class="card-body border-bottom cliente-search">
					<div class="search-wrap">
						<i class="fa fa-search"></i>
						<input type="text" class="form-control form-control-sm"
							v-model.trim="txtbuscar"
							@input="onBuscarInput"
							@keyup.enter="buscar"
							placeholder="Buscar por nombre, CI o RUC...">
					</div>
				</div>
				<div class="card-body p-0 cliente-list-body">
					<div v-if="requestSend" class="text-center text-muted py-4">
						<span class="spinner-border spinner-border-sm"></span> Buscando...
					</div>
					<div v-else-if="!clientes.length" class="text-center text-muted py-4">
						No se encontraron clientes.
					</div>
					<template v-else>
						<div class="cliente-card"
							v-for="c in clientes"
							:key="c.clientes_cod"
							:class="{ active: form.id == c.clientes_cod }"
							@click="editar(c)">
							<span class="cliente-avatar">
								<i class="fa fa-user"></i>
							</span>
							<div class="cliente-card-body">
								<strong>@{{ c.cliente_nombre }}</strong>
								<div class="small text-muted">
									@{{ c.cliente_ci || c.cliente_ruc || 'Sin documento' }}
									<span v-if="c.cliente_cel"> · @{{ c.cliente_cel }}</span>
								</div>
								<div class="small text-muted" v-if="c.cliente_direccion">
									@{{ c.cliente_direccion }}
								</div>
							</div>
							<div class="cliente-card-actions" @click.stop>
								<button type="button" class="btn btn-link btn-sm text-danger p-0"
									@click="eliminar(c)" title="Eliminar">
									<span class="fa fa-trash"></span>
								</button>
							</div>
						</div>
					</template>
				</div>
			</div>
		</div>

		<div class="col-lg-7 mb-3">
			<div class="card card-outline" :class="esEdicion ? 'card-warning' : 'card-success'">
				<div class="card-header d-flex justify-content-between align-items-center">
					<strong>@{{ esEdicion ? ('Editar cliente #' + form.id) : 'Nuevo cliente' }}</strong>
					<button type="button" class="btn btn-outline-secondary btn-sm" @click="nuevo" v-if="esEdicion">
						<span class="fa fa-plus"></span> Limpiar
					</button>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-4 form-group">
							<label>Documento *</label>
							<input type="text" class="form-control form-control-sm" v-model.trim="form.doc"
								placeholder="C.I. o R.U.C">
						</div>
						<div class="col-md-8 form-group">
							<label>Nombre y apellido *</label>
							<input type="text" class="form-control form-control-sm" v-model.trim="form.nombre"
								placeholder="Nombre completo">
						</div>
					</div>

					<div class="row">
						<div class="col-md-8 form-group">
							<label>Dirección</label>
							<input type="text" class="form-control form-control-sm" v-model.trim="form.direccion"
								placeholder="Dirección">
						</div>
						<div class="col-md-4 form-group">
							<label>Ciudad</label>
							<select class="form-control form-control-sm" v-model="form.idciudad">
								@foreach ($ciudades as $ciudad)
									<option value="{{ $ciudad['CIUDAD_cod'] }}">{{ $ciudad['ciudad_nombre'] }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 form-group">
							<label>Celular</label>
							<input type="text" class="form-control form-control-sm" v-model.trim="form.celular"
								placeholder="Celular">
						</div>
						<div class="col-md-4 form-group">
							<label>Teléfono</label>
							<input type="text" class="form-control form-control-sm" v-model.trim="form.telefono"
								placeholder="Teléfono">
						</div>
						<div class="col-md-4 form-group">
							<label>Correo</label>
							<input type="email" class="form-control form-control-sm" v-model.trim="form.correo"
								placeholder="correo@dominio.com">
						</div>
					</div>

					<hr class="my-2">
					<p class="small text-muted mb-2">Datos adicionales (opcional)</p>

					<div class="row">
						<div class="col-md-4 form-group">
							<label>Cel. familiar</label>
							<input type="text" class="form-control form-control-sm" v-model.trim="form.celfamiliar"
								placeholder="Celular familiar">
						</div>
						<div class="col-md-4 form-group">
							<label>Profesión / ocupación</label>
							<input type="text" class="form-control form-control-sm" v-model.trim="form.ocupacion"
								placeholder="Profesión">
						</div>
						<div class="col-md-4 form-group">
							<label>Ref. laboral</label>
							<input type="text" class="form-control form-control-sm" v-model.trim="form.reflaboral"
								placeholder="Referencia laboral">
						</div>
					</div>
				</div>
				<div class="card-footer d-flex justify-content-between">
					<button type="button" class="btn btn-secondary btn-sm" @click="nuevo">
						<span class="fa fa-eraser"></span> Limpiar
					</button>
					<button type="button" class="btn btn-success btn-sm" @click="guardar" :disabled="guardando">
						<span class="fa" :class="guardando ? 'fa-spinner fa-spin' : 'fa-save'"></span>
						@{{ esEdicion ? 'Actualizar' : 'Guardar cliente' }}
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
		timer: 2200
	});

	var app = new Vue({
		el: '#app',
		data: {
			clientes: [],
			requestSend: false,
			guardando: false,
			txtbuscar: '',
			buscarTimer: null,
			form: {
				id: null,
				doc: '',
				nombre: '',
				direccion: '',
				celular: '',
				telefono: '',
				correo: '',
				idciudad: '{{ optional($ciudades->first())->CIUDAD_cod ?? 1 }}',
				celfamiliar: '',
				ocupacion: '',
				reflaboral: ''
			}
		},
		computed: {
			esEdicion: function () {
				return this.form.id !== null && this.form.id !== undefined && this.form.id !== '';
			}
		},
		methods: {
			blankForm: function () {
				return {
					id: null,
					doc: '',
					nombre: '',
					direccion: '',
					celular: '',
					telefono: '',
					correo: '',
					idciudad: '{{ optional($ciudades->first())->CIUDAD_cod ?? 1 }}',
					celfamiliar: '',
					ocupacion: '',
					reflaboral: ''
				};
			},
			nuevo: function () {
				this.form = this.blankForm();
			},
			onBuscarInput: function () {
				var self = this;
				if (this.buscarTimer) clearTimeout(this.buscarTimer);
				this.buscarTimer = setTimeout(function () {
					self.buscar();
				}, 300);
			},
			buscar: function () {
				var self = this;
				this.requestSend = true;
				axios.get('{{ url('cliente/buscar') }}', {
					params: {
						q: (this.txtbuscar || '').trim(),
						limit: 80
					}
				}).then(function (response) {
					self.requestSend = false;
					self.clientes = response.data || [];
				}).catch(function () {
					self.requestSend = false;
					self.clientes = [];
					Swal.fire('Error', 'No se pudo buscar clientes', 'error');
				});
			},
			editar: function (c) {
				var id = c.clientes_cod;
				if (id === undefined || id === null) {
					id = c.CLIENTES_cod;
				}
				this.form = {
					id: id,
					doc: c.cliente_ci || c.cliente_ruc || '',
					nombre: c.cliente_nombre || '',
					direccion: c.cliente_direccion || '',
					celular: c.cliente_cel || '',
					telefono: c.cliente_telef || '',
					correo: c.cliente_correo || '',
					idciudad: c.CIUDAD_cod || c.ciudad_cod || this.blankForm().idciudad,
					celfamiliar: c.cliente_referente_nombre || '',
					ocupacion: c.cliente_profesion || '',
					reflaboral: c.cliente_referencia_laboral || ''
				};
			},
			payload: function () {
				return {
					cliente: {
						id: this.form.id,
						doc: this.form.doc,
						nombre: this.form.nombre,
						direccion: this.form.direccion || '',
						celular: this.form.celular || '',
						telefono: this.form.telefono || '',
						correo: this.form.correo || '',
						idciudad: this.form.idciudad || 1,
						celfamiliar: this.form.celfamiliar || '',
						ocupacion: this.form.ocupacion || '',
						reflaboral: this.form.reflaboral || ''
					}
				};
			},
			guardar: function () {
				var self = this;
				if (!(this.form.nombre || '').trim() || !(this.form.doc || '').trim()) {
					Swal.fire('Campos vacíos', 'Completá documento y nombre.', 'warning');
					return;
				}
				this.guardando = true;
				var esEdicion = this.esEdicion;
				var req = esEdicion
					? axios.post('{{ url('cliente/update') }}', this.payload())
					: axios.post('{{ url('cliente') }}', this.payload());

				req.then(function () {
					self.guardando = false;
					Toast.fire({
						icon: 'success',
						title: esEdicion ? 'Cliente actualizado' : 'Cliente creado'
					});
					var keepNombre = self.form.nombre;
					self.buscar();
					if (!esEdicion) {
						self.txtbuscar = keepNombre;
						self.$nextTick(function () {
							self.buscar();
							self.nuevo();
						});
					}
				}).catch(function (err) {
					self.guardando = false;
					var msg = (err.response && err.response.data && (err.response.data.message || err.response.data.msg))
						|| 'No se pudo guardar el cliente';
					Swal.fire('Error', msg, 'error');
				});
			},
			eliminar: function (c) {
				var self = this;
				var id = c.clientes_cod;
				if (id === undefined || id === null) {
					id = c.CLIENTES_cod;
				}
				Swal.fire({
					title: '¿Eliminar cliente?',
					text: c.cliente_nombre,
					icon: 'question',
					showCancelButton: true,
					cancelButtonText: 'Cancelar',
					confirmButtonText: 'Sí, eliminar',
					confirmButtonClass: 'bg-danger'
				}).then(function (result) {
					if (!result.value) return;
					axios.delete('{{ url('cliente') }}/' + id)
						.then(function () {
							Toast.fire({ icon: 'success', title: 'Cliente eliminado' });
							if (self.form.id == id) self.nuevo();
							self.buscar();
						})
						.catch(function () {
							Swal.fire(
								'No se puede eliminar',
								'Este cliente está registrado en ventas u otros movimientos.',
								'error'
							);
						});
				});
			}
		},
		mounted: function () {
			this.buscar();
		}
	});
	activarMenu('m_mantenimiento', 'm_cliente');
</script>
@endsection
