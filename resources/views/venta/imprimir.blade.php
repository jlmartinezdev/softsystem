@extends('layouts.app')
@section('title', 'Imprimir Ventas')
@section('style')
<style>
	.modal-dialog { overflow-y: initial !important; }
	.modal-body { max-height: 70vh; overflow-y: auto; }
	.dropdown-item.disabled,
	.dropdown-item:disabled {
		pointer-events: none;
		opacity: .55;
	}
</style>
@endsection
@section('main')
<div class="container-fluid" id="app">
	<div class="content-header">
		<div class="row mb-2 align-items-center">
			<div class="col-md-8">
				<h4 class="m-0">Imprimir ventas</h4>
				<p class="text-muted mb-0 small">Buscá ventas por cliente o número y generá ticket, comprobante o factura electrónica.</p>
			</div>
		</div>
	</div>

	@if (session('success'))
		<div class="alert alert-success alert-dismissible fade show">
			<span class="fa fa-check-circle"></span> {{ session('success') }}
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		</div>
	@endif

	@if (session('error'))
		<div class="alert alert-danger alert-dismissible fade show">
			<span class="fa fa-exclamation-circle"></span> {{ session('error') }}
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		</div>
	@endif

	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="row align-items-center">
				<div class="col-md-7">
					<strong><span class="fa fa-print"></span> Listado de ventas</strong>
				</div>
				<div class="col-md-5">
					<div class="input-group input-group-sm">
						<input type="text" v-model="txtbuscar" @keyup.enter="getVenta()" class="form-control"
							placeholder="Buscar por cliente o N° de venta..." tabindex="1" autofocus>
						<div class="input-group-append">
							<button class="btn btn-primary" @click="getVenta()" :disabled="requestSend">
								<template v-if="requestSend">
									<span class="spinner-border spinner-border-sm" role="status"></span>
								</template>
								<template v-else>
									<span class="fa fa-search"></span>
								</template>
								Buscar
							</button>
							<button class="btn btn-outline-secondary" type="button" @click="limpiarBusqueda" title="Limpiar">
								<span class="fa fa-times"></span>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table table-sm table-hover table-striped mb-0">
					<thead>
						<tr>
							<th style="width: 70px;">Opciones</th>
							<th>N° Venta</th>
							<th>Fecha / Hora</th>
							<th>Cliente</th>
							<th>Celular</th>
							<th>Tipo</th>
							<th>Condición</th>
							<th class="text-right">Total</th>
						</tr>
					</thead>
					<tbody>
						<template v-if="ventas.length === 0 && !requestSend">
							<tr>
								<td colspan="8" class="text-center text-muted py-4">
									No hay resultados para mostrar.
								</td>
							</tr>
						</template>
						<template v-if="requestSend">
							<tr>
								<td colspan="8" class="text-center text-muted py-4">
									<span class="spinner-border spinner-border-sm"></span> Buscando...
								</td>
							</tr>
						</template>
						<tr v-for="venta in ventas" :key="venta.nro_fact_ventas">
							<td>
								<div class="btn-group">
									<button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle"
										data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<span class="fa fa-bars"></span>
									</button>
									<div class="dropdown-menu dropdown-menu-left shadow">
										<button type="button" class="dropdown-item" @click="showDetalle(venta)">
											<span class="fa fa-file-alt text-info"></span> Detalle
										</button>
										<a :href="'{{ url('ticket/venta') }}/' + venta.nro_fact_ventas"
											class="dropdown-item" target="_blank">
											<span class="fa fa-receipt text-primary"></span> Ticket
										</a>
										<a :href="'{{ url('pdf/boletaventa') }}/' + venta.nro_fact_ventas"
											class="dropdown-item" target="_blank">
											<span class="fa fa-print text-secondary"></span> Comprobante
										</a>
										<div class="dropdown-divider"></div>
										<a v-if="sifenActivo"
											:href="'{{ url('venta/facturar') }}/' + venta.nro_fact_ventas"
											class="dropdown-item">
											<span class="fa fa-file-invoice text-success"></span> Factura electrónica (SIFEN)
										</a>
										<span v-else class="dropdown-item disabled" title="SIFEN está desactivado en la configuración">
											<span class="fa fa-file-invoice"></span> Factura electrónica (SIFEN)
											<small class="d-block text-muted">Desactivado</small>
										</span>
									</div>
								</div>
							</td>
							<td><strong>@{{ venta.nro_fact_ventas }}</strong></td>
							<td>@{{ venta.fecha }}</td>
							<td>@{{ venta.cliente_nombre }}</td>
							<td>@{{ venta.cliente_cel || '—' }}</td>
							<td>@{{ venta.documento }}</td>
							<td>
								<span class="badge"
									:class="venta.tipo_factura == 1 || venta.tipo_factura == '1' ? 'badge-success' : 'badge-warning'">
									@{{ venta.tipo_factura == 1 || venta.tipo_factura == '1' ? 'Contado' : 'Crédito' }}
								</span>
							</td>
							<td class="text-right font-weight-bold">
								Gs. @{{ formatGs(venta.venta_total) }}
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="card-footer small text-muted" v-if="ventas.length">
			@{{ ventas.length }} venta(s) encontrada(s)
			<span v-if="!sifenActivo" class="float-right">
				<span class="fa fa-info-circle"></span> Facturación electrónica SIFEN desactivada
			</span>
		</div>
	</div>

	<div class="modal fade" id="frmdetalle">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header bg-dark text-white">
					<h6 class="modal-title mb-0">Detalle de venta #@{{ venta.nro_fact_ventas }}</h6>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row mb-3">
						<div class="col-sm-3 border-right text-center">
							<span class="text-muted small d-block">N° de venta</span>
							<strong>@{{ venta.nro_fact_ventas }}</strong>
						</div>
						<div class="col-sm-3 border-right text-center">
							<span class="text-muted small d-block">Fecha</span>
							<strong>@{{ venta.fecha }}</strong>
						</div>
						<div class="col-sm-6 text-center">
							<span class="text-muted small d-block">Cliente</span>
							<strong>@{{ venta.cliente_nombre }}</strong>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-4">
							Condición: <strong>@{{ venta.tipo_factura == 1 || venta.tipo_factura == '1' ? 'Contado' : 'Crédito' }}</strong>
						</div>
						<div class="col-4">
							Descuento: <strong>Gs. @{{ formatGs(venta.venta_descuento) }}</strong>
						</div>
						<div class="col-4">
							Total: <strong>Gs. @{{ formatGs(venta.venta_total) }}</strong>
						</div>
					</div>

					<span class="badge badge-info mb-2">Artículos</span>
					<table class="table table-sm table-striped">
						<thead>
							<tr>
								<th>Código</th>
								<th>Descripción</th>
								<th class="text-right">Cant.</th>
								<th class="text-right">Precio</th>
								<th class="text-right">Importe</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(d, i) in detalleVenta" :key="i">
								<td>@{{ d.producto_c_barra }}</td>
								<td>@{{ d.producto_nombre }}</td>
								<td class="text-right">@{{ parseInt(d.venta_cantidad) }}</td>
								<td class="text-right">Gs. @{{ formatGs(d.venta_precio) }}</td>
								<td class="text-right font-weight-bold">Gs. @{{ formatGs(d.venta_cantidad * d.venta_precio) }}</td>
							</tr>
							<tr v-if="!detalleVenta || !detalleVenta.length">
								<td colspan="5" class="text-center text-muted">Sin detalle</td>
							</tr>
						</tbody>
					</table>

					<template v-if="venta.tipo_factura == 2 || venta.tipo_factura == '2'">
						<span class="badge badge-success mb-2">Cuotas</span>
						<table class="table table-sm table-striped">
							<thead>
								<tr>
									<th>N° Cuota</th>
									<th>Vencimiento</th>
									<th class="text-right">Monto</th>
									<th class="text-right">Cobrado</th>
									<th class="text-right">Saldo</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(c, i) in cuotas" :key="i">
									<td>@{{ c.nro_cuotas }}</td>
									<td>@{{ formatFecha(c.fecha_venc) }}</td>
									<td class="text-right">Gs. @{{ formatGs(c.monto_cuota) }}</td>
									<td class="text-right">Gs. @{{ formatGs(c.monto_cobrado) }}</td>
									<td class="text-right">Gs. @{{ formatGs(c.monto_saldo) }}</td>
								</tr>
							</tbody>
						</table>
						<div class="row">
							<div class="col-6">
								Monto cobrado: <strong>Gs. @{{ formatGs(Cuenta.montoCobrado) }}</strong>
							</div>
							<div class="col-6">
								Saldo: <strong>Gs. @{{ formatGs(Cuenta.saldo) }}</strong>
							</div>
						</div>
					</template>
				</div>
				<div class="modal-footer">
					<a v-if="venta.nro_fact_ventas" :href="'{{ url('ticket/venta') }}/' + venta.nro_fact_ventas"
						class="btn btn-outline-primary btn-sm" target="_blank">
						<span class="fa fa-receipt"></span> Ticket
					</a>
					<a v-if="venta.nro_fact_ventas" :href="'{{ url('pdf/boletaventa') }}/' + venta.nro_fact_ventas"
						class="btn btn-outline-secondary btn-sm" target="_blank">
						<span class="fa fa-print"></span> Comprobante
					</a>
					<a v-if="sifenActivo && venta.nro_fact_ventas"
						:href="'{{ url('venta/facturar') }}/' + venta.nro_fact_ventas"
						class="btn btn-outline-success btn-sm">
						<span class="fa fa-file-invoice"></span> SIFEN
					</a>
					<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
						<span class="fa fa-times"></span> Cerrar
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script>
	var app = new Vue({
		el: '#app',
		data: {
			ventas: [],
			cuotas: [],
			Cuenta: {
				cantitad: 0,
				montoCuota: 0,
				saldo: 0,
				cobrado: 0,
				montoCobrado: 0
			},
			txtbuscar: '',
			requestSend: false,
			idSucursal: 1,
			error: '',
			venta: {},
			detalleVenta: [],
			sifenActivo: {{ $sifenActivo ? 'true' : 'false' }}
		},
		methods: {
			formatGs: function (n) {
				return new Intl.NumberFormat('de-DE').format(Math.round(Number(n) || 0));
			},
			limpiarBusqueda: function () {
				this.txtbuscar = '';
				this.getVenta();
			},
			showDetalle: function (venta) {
				this.venta = venta;
				this.detalleVenta = [];
				this.cuotas = [];
				$('#frmdetalle').modal('show');
				this.getDetalle();
			},
			getDetalle: function () {
				axios.get('{{ url('infventa/detalle') }}/' + this.venta.nro_fact_ventas)
					.then(response => {
						this.detalleVenta = response.data;
					})
					.catch(error => {
						console.log(error.message);
					});
				if (this.venta.tipo_factura == '2' || this.venta.tipo_factura == 2) {
					this.getCta();
				}
			},
			getCta: function () {
				axios.get('{{ url('cuotas') }}/' + this.venta.nro_fact_ventas)
					.then(response => {
						const c = response.data;
						this.cuotas = c;
						let saldo = 0;
						let cobrado = 0;
						for (let i = 0; i < c.length; i++) {
							saldo += parseInt(c[i].monto_saldo);
							cobrado += parseInt(c[i].monto_cobrado);
						}
						this.Cuenta.saldo = saldo;
						this.Cuenta.montoCobrado = cobrado;
					})
					.catch(error => {
						console.log(error.message);
					});
			},
			getVenta: function () {
				this.requestSend = true;
				const isNumber = isNaN(parseFloat(this.txtbuscar)) ? 0 : 1;

				axios.get('{{ route('infventa.cliente') }}', {
						params: {
							cliente: this.txtbuscar,
							alls: this.idSucursal,
							isNumber: isNumber
						}
					})
					.then(response => {
						this.requestSend = false;
						this.ventas = response.data;
					})
					.catch(e => {
						this.requestSend = false;
						this.error = e.message;
					});
			},
			getSucursal: function () {
				var obj = document.getElementById('sucursal');
				if (obj && obj.getAttribute('data-id') != null) {
					this.idSucursal = obj.getAttribute('data-id');
				}
			},
			formatFecha: function (fecha) {
				if (!fecha) return '';
				const f = fecha.split('-');
				return f[2] + '/' + f[1] + '/' + f[0];
			}
		},
		mounted() {
			this.getSucursal();
			this.getVenta();
		}
	});
</script>
@endsection
