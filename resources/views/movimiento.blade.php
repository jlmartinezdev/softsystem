@extends('layouts.app')
@section('title', 'Movimiento de Caja')
@section('main')
<div class="container-fluid" id="app">
	<div class="content-header">
		<div class="row mb-2 align-items-center">
			<div class="col-md-8">
				<h4 class="m-0">Entrada / Salida de caja</h4>
				<p class="text-muted mb-0 small">Registrá ingresos y egresos del turno abierto.</p>
			</div>
			<div class="col-md-4 text-md-right">
				<a href="{{ route('apertura') }}" class="btn btn-outline-secondary btn-sm">
					<span class="fa fa-cash-register"></span> Apertura / Cierre
				</a>
			</div>
		</div>
	</div>

	<div class="row mb-3">
		<div class="col-md-3">
			<div class="info-box mb-3">
				<span class="info-box-icon bg-success"><i class="fa fa-arrow-down"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Entradas</span>
					<span class="info-box-number">Gs. @{{ formatGs(totales.entradas) }}</span>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="info-box mb-3">
				<span class="info-box-icon bg-danger"><i class="fa fa-arrow-up"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Salidas</span>
					<span class="info-box-number">Gs. @{{ formatGs(totales.salidas) }}</span>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="info-box mb-3">
				<span class="info-box-icon bg-primary"><i class="fa fa-balance-scale"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Saldo en caja</span>
					<span class="info-box-number">Gs. @{{ formatGs(totales.saldo) }}</span>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="info-box mb-3">
				<span class="info-box-icon" :class="cajaAbierta ? 'bg-info' : 'bg-secondary'">
					<i class="fa" :class="cajaAbierta ? 'fa-lock-open' : 'fa-lock'"></i>
				</span>
				<div class="info-box-content">
					<span class="info-box-text">Estado</span>
					<span class="info-box-number" style="font-size: 1.1rem;">
						<span class="badge badge-pill" :class="cajaAbierta ? 'badge-success' : 'badge-secondary'">
							@{{ movimiento.caja }}
						</span>
						<small class="d-block text-muted mt-1" v-if="cajaAbierta">
							Op. #@{{ movimiento.nro_operacion }}
						</small>
					</span>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-4">
			<div class="card card-outline" :class="cajaAbierta ? 'card-primary' : 'card-secondary'">
				<div class="card-header">
					<strong><span class="fa fa-exchange-alt"></span> Nuevo movimiento</strong>
				</div>
				<div class="card-body">
					<div class="alert alert-warning small" v-if="!cajaAbierta">
						No hay caja abierta en esta sucursal.
						<a href="{{ route('apertura') }}" class="alert-link">Abrir caja</a> para registrar movimientos.
					</div>

					<div class="form-group">
						<label class="d-block">Tipo</label>
						<div class="btn-group btn-block" role="group">
							<button type="button" class="btn"
								:class="movimiento.tipo === 'Entrada' ? 'btn-success' : 'btn-outline-success'"
								:disabled="!cajaAbierta || guardando"
								@click="movimiento.tipo = 'Entrada'">
								<span class="fa fa-arrow-down"></span> Entrada
							</button>
							<button type="button" class="btn"
								:class="movimiento.tipo === 'Salida' ? 'btn-danger' : 'btn-outline-danger'"
								:disabled="!cajaAbierta || guardando"
								@click="movimiento.tipo = 'Salida'">
								<span class="fa fa-arrow-up"></span> Salida
							</button>
						</div>
					</div>

					<div class="form-group">
						<label for="descripcion">Descripción</label>
						<input type="text" id="descripcion" class="form-control"
							placeholder="Ej: Pago proveedor, retiro, ingreso..."
							v-model.trim="movimiento.descripcion"
							:disabled="!cajaAbierta || guardando"
							@keyup.enter="focusMonto"
							ref="descripcion"
							maxlength="255">
					</div>

					<div class="form-group mb-0">
						<label for="monto">Monto</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">Gs.</span>
							</div>
							<input type="number" id="monto" class="form-control"
								placeholder="0" min="1" step="1"
								v-model.number="movimiento.monto"
								:disabled="!cajaAbierta || guardando"
								@keyup.enter="store"
								ref="monto">
						</div>
						<small class="text-muted" v-if="movimiento.tipo === 'Salida' && cajaAbierta">
							Saldo disponible: Gs. @{{ formatGs(totales.saldo) }}
						</small>
					</div>
				</div>
				<div class="card-footer">
					<button type="button" class="btn btn-block font-weight-bold"
						:class="movimiento.tipo === 'Entrada' ? 'btn-success' : 'btn-danger'"
						:disabled="!cajaAbierta || guardando"
						@click="store">
						<span class="fa" :class="guardando ? 'fa-spinner fa-spin' : 'fa-save'"></span>
						@{{ guardando ? 'Guardando...' : (movimiento.tipo === 'Entrada' ? 'REGISTRAR ENTRADA' : 'REGISTRAR SALIDA') }}
					</button>
					<a v-if="cajaAbierta" :href="'{{ url('cierre') }}/' + movimiento.nro_operacion"
						class="btn btn-outline-info btn-block btn-sm mt-2">
						<span class="fa fa-lock"></span> Ir a cierre de caja
					</a>
				</div>
			</div>
		</div>

		<div class="col-lg-8">
			<div class="card card-outline card-dark">
				<div class="card-header d-flex justify-content-between align-items-center">
					<strong><span class="fa fa-list"></span> Movimientos del turno</strong>
					<a v-if="cajaAbierta"
						:href="'{{ url('caja/movimiento') }}/' + movimiento.nro_operacion"
						class="btn btn-outline-secondary btn-sm">
						<span class="fa fa-file"></span> Ver informe
					</a>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-sm table-striped table-hover mb-0">
							<thead>
								<tr>
									<th>#</th>
									<th>Tipo</th>
									<th>Fecha - Hora</th>
									<th>Descripción</th>
									<th class="text-right">Monto</th>
								</tr>
							</thead>
							<tbody>
								<template v-if="movimientos.length">
									<tr v-for="(m, i) in movimientos" :key="i"
										:class="m.mov_tipo === 'Entrada' ? 'text-success' : 'text-danger'">
										<td>@{{ i + 1 }}</td>
										<td>
											<span class="badge"
												:class="m.mov_tipo === 'Entrada' ? 'badge-success' : 'badge-danger'">
												@{{ m.mov_tipo }}
											</span>
										</td>
										<td>@{{ m.mov_fecha }}</td>
										<td>@{{ m.mov_concepto }}</td>
										<td class="text-right">
											<strong>
												Gs.
												<template v-if="m.mov_tipo === 'Salida' && m.mov_monto > 0">-</template>
												@{{ formatGs(m.mov_monto) }}
											</strong>
										</td>
									</tr>
								</template>
								<tr v-else>
									<td colspan="5" class="text-center text-muted py-4">
										<template v-if="cajaAbierta">Aún no hay movimientos en este turno.</template>
										<template v-else>Abrí una caja para ver y registrar movimientos.</template>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script type="text/javascript">
	var app = new Vue({
		el: '#app',
		data: {
			movimientos: [],
			guardando: false,
			movimiento: {
				idSucursal: 0,
				nro_operacion: '-',
				caja: 'CERRADA',
				tipo: 'Entrada',
				descripcion: '',
				monto: ''
			}
		},
		computed: {
			cajaAbierta: function () {
				return this.movimiento.caja === 'ABIERTA' && this.movimiento.nro_operacion !== '-';
			},
			totales: function () {
				var entradas = 0;
				var salidas = 0;
				for (var i = 0; i < this.movimientos.length; i++) {
					var m = this.movimientos[i];
					var monto = parseFloat(m.mov_monto || 0);
					if (m.mov_tipo === 'Entrada') {
						entradas += monto;
					} else {
						salidas += monto;
					}
				}
				return {
					entradas: entradas,
					salidas: salidas,
					saldo: entradas - salidas
				};
			}
		},
		methods: {
			formatGs: function (n) {
				return new Intl.NumberFormat('de-DE').format(Math.round(Number(n) || 0));
			},
			focusMonto: function () {
				if (this.$refs.monto) {
					this.$refs.monto.focus();
				}
			},
			getApertura: function () {
				var idSucursal = $('#sucursal').attr('data-id');
				this.movimiento.idSucursal = idSucursal;
				if (idSucursal != null) {
					axios.get('aperturacierre/' + idSucursal)
						.then(response => {
							if (response.data) {
								this.movimiento.nro_operacion = response.data.nro_operacion;
								this.movimiento.caja = 'ABIERTA';
								this.getMovimiento();
								this.$nextTick(() => {
									if (this.$refs.descripcion) {
										this.$refs.descripcion.focus();
									}
								});
							} else {
								this.movimiento.caja = 'CERRADA';
								this.movimiento.nro_operacion = '-';
								this.movimientos = [];
							}
						})
						.catch(error => {
							console.log(error);
							Swal.fire('Error', 'No se pudo consultar el estado de caja.', 'error');
						});
				}
			},
			getMovimiento: function () {
				if (this.movimiento.nro_operacion != '-') {
					axios.get('movimiento/' + this.movimiento.nro_operacion)
						.then(response => {
							this.movimientos = response.data;
						})
						.catch(error => {
							console.log(error.message);
						});
				}
			},
			store: function () {
				if (!this.cajaAbierta) {
					Swal.fire('Caja cerrada', 'No se puede guardar el movimiento.', 'warning');
					return;
				}
				if (!this.movimiento.descripcion || !(this.movimiento.monto > 0)) {
					Swal.fire('Complete los campos', 'Ingresá descripción y un monto mayor a cero.', 'warning');
					return;
				}
				if (this.movimiento.tipo === 'Salida' && this.movimiento.monto > this.totales.saldo) {
					Swal.fire(
						'Saldo insuficiente',
						'El monto supera el saldo en caja (Gs. ' + this.formatGs(this.totales.saldo) + ').',
						'warning'
					);
					return;
				}

				this.guardando = true;
				axios.post('movimiento', { data: this.movimiento })
					.then(response => {
						if (response.data && response.data.ok === false) {
							Swal.fire('No se pudo guardar', response.data.message || 'Error', 'warning');
							return;
						}
						this.movimiento.descripcion = '';
						this.movimiento.monto = '';
						this.movimientos = (response.data && response.data.movimientos)
							? response.data.movimientos
							: response.data;
						Swal.fire({
							toast: true,
							position: 'top-end',
							icon: 'success',
							title: 'Movimiento registrado',
							showConfirmButton: false,
							timer: 1800
						});
						this.$nextTick(() => {
							if (this.$refs.descripcion) {
								this.$refs.descripcion.focus();
							}
						});
					})
					.catch(error => {
						var msg = 'No se pudo guardar el movimiento.';
						if (error.response && error.response.data && error.response.data.message) {
							msg = error.response.data.message;
						}
						Swal.fire('Error', msg, 'error');
					})
					.finally(() => {
						this.guardando = false;
					});
			}
		},
		mounted() {
			this.getApertura();
		}
	});
	activarMenu('m_caja', 'm_movimiento');
</script>
@endsection
