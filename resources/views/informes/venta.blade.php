@extends('layouts.app')
@section('title', 'Informe de Ventas')
@section('style')
<style>
	.chart { min-height: 250px; }
	.modal-body { max-height: 70vh; overflow-y: auto; }
	.vgt-table { font-size: 0.9rem; }
</style>
@endsection
@section('main')
<div class="container-fluid" id="app">
	<div class="content-header">
		<div class="row mb-2 align-items-center">
			<div class="col-md-8">
				<h4 class="m-0">Informe de ventas</h4>
				<p class="text-muted mb-0 small">Consultá ventas por fecha, cliente, gráfica o artículos vendidos.</p>
			</div>
			<div class="col-md-4 text-md-right">
				<a href="{{ route('infventa.imprimir') }}" class="btn btn-outline-secondary btn-sm">
					<span class="fa fa-print"></span> Ir a imprimir
				</a>
			</div>
		</div>
	</div>

	<div class="row mb-3" v-show="activeTab === 'fecha'">
		<div class="col-md-3">
			<div class="info-box mb-3">
				<span class="info-box-icon bg-info"><i class="fa fa-shopping-cart"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Ventas</span>
					<span class="info-box-number">@{{ totalVenta }}</span>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="info-box mb-3">
				<span class="info-box-icon bg-primary"><i class="fa fa-money-bill"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Monto total</span>
					<span class="info-box-number" style="font-size: 1.1rem;">Gs. @{{ formatGs(totalGuaranies) }}</span>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="info-box mb-3">
				<span class="info-box-icon bg-success"><i class="fa fa-hand-holding-usd"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Contado</span>
					<span class="info-box-number" style="font-size: 1.1rem;">Gs. @{{ formatGs(totalContado) }}</span>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="info-box mb-3">
				<span class="info-box-icon bg-warning"><i class="fa fa-file-invoice-dollar"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Crédito</span>
					<span class="info-box-number" style="font-size: 1.1rem;">Gs. @{{ formatGs(totalCredito) }}</span>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-outline card-primary">
		<div class="card-header p-0 border-bottom-0">
			<ul class="nav nav-tabs" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" href="#frmlista" data-toggle="tab" @click="activeTab = 'fecha'">
						<span class="fa fa-calendar"></span> Fecha
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#frmcliente" data-toggle="tab" @click="activeTab = 'cliente'">
						<span class="fa fa-users"></span> Cliente
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#frmchart" data-toggle="tab" @click="activeTab = 'chart'">
						<span class="fa fa-chart-line"></span> Gráfica
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#frmarticulo" data-toggle="tab" @click="activeTab = 'articulo'">
						<span class="fa fa-boxes"></span> Artículos vendidos
					</a>
				</li>
			</ul>
		</div>
		<div class="card-body">
			<div class="tab-content">
				{{-- FECHA --}}
				<div class="tab-pane fade show active" id="frmlista" role="tabpanel">
					<div class="row align-items-end mb-3">
						<div class="col-md-2 form-group mb-md-0">
							<label class="small mb-1">Desde</label>
							<input type="date" class="form-control form-control-sm" v-model="fecha.desde">
						</div>
						<div class="col-md-2 form-group mb-md-0">
							<label class="small mb-1">Hasta</label>
							<input type="date" class="form-control form-control-sm" v-model="fecha.hasta">
						</div>
						<div class="col-md-3 form-group mb-md-0">
							<label class="small mb-1">Sucursal</label>
							<select class="form-control form-control-sm" v-model="idSucursal">
								<option value="0">Todas</option>
								@foreach ($sucursales as $s)
									<option value="{{ $s['suc_cod'] }}">{{ $s['suc_desc'] }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-5 form-group mb-0">
							<button @click="getVenta" class="btn btn-primary btn-sm" :disabled="requestSend">
								<template v-if="requestSend">
									<span class="spinner-border spinner-border-sm"></span> Buscando...
								</template>
								<template v-else>
									<span class="fa fa-search"></span> Buscar
								</template>
							</button>
							<button type="button" class="btn btn-outline-secondary btn-sm" @click="limpiarFecha">
								<span class="fa fa-times"></span> Limpiar
							</button>
						</div>
					</div>

					<vue-good-table
						:columns="columns"
						:rows="rows"
						:search-options="{ enabled: true, placeholder: 'Filtrar en la tabla...' }"
						:pagination-options="{ enabled: true, perPage: 15, perPageDropdown: [10, 15, 25, 50] }"
						style-class="vgt-table striped condensed" />
				</div>

				{{-- CLIENTE --}}
				<div class="tab-pane fade" id="frmcliente" role="tabpanel">
					<div class="row align-items-end mb-3">
						<div class="col-md-5 form-group mb-md-0">
							<label class="small mb-1">Cliente</label>
							<input type="text" class="form-control form-control-sm" v-model.trim="txtbuscar"
								placeholder="Nombre, CI o N° de venta..."
								@keyup.enter="buscar('cliente')" tabindex="1">
						</div>
						<div class="col-md-3 form-group mb-md-0">
							<label class="small mb-1">Sucursal</label>
							<select class="form-control form-control-sm" v-model="idSucursal">
								<option value="0">Todas</option>
								@foreach ($sucursales as $s)
									<option value="{{ $s['suc_cod'] }}">{{ $s['suc_desc'] }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-4 form-group mb-0">
							<button @click="buscar('cliente')" class="btn btn-primary btn-sm" :disabled="requestSend">
								<template v-if="requestSend">
									<span class="spinner-border spinner-border-sm"></span> Buscando...
								</template>
								<template v-else>
									<span class="fa fa-search"></span> Buscar
								</template>
							</button>
						</div>
					</div>

					<div class="table-responsive">
						<table class="table table-sm table-hover table-striped mb-0">
							<thead>
								<tr>
									<th>N° Venta</th>
									<th>Fecha</th>
									<th>Cliente</th>
									<th>Condición</th>
									<th>Documento</th>
									<th class="text-right">Total</th>
									<th>Sucursal</th>
									<th>Opciones</th>
								</tr>
							</thead>
							<tbody>
								<tr v-if="!clientes.length">
									<td colspan="8" class="text-center text-muted py-4">
										Buscá un cliente para ver sus ventas.
									</td>
								</tr>
								<tr v-for="venta in clientes" :key="'c-' + venta.nro_fact_ventas">
									<td><strong>@{{ venta.nro_fact_ventas }}</strong></td>
									<td>@{{ venta.fecha }}</td>
									<td>@{{ venta.cliente_nombre }}</td>
									<td>
										<span class="badge"
											:class="venta.tipo_factura == 1 || venta.tipo_factura == '1' ? 'badge-success' : 'badge-warning'">
											@{{ venta.tipo_factura == 1 || venta.tipo_factura == '1' ? 'Contado' : 'Crédito' }}
										</span>
									</td>
									<td>@{{ venta.documento }}</td>
									<td class="text-right font-weight-bold">Gs. @{{ formatGs(venta.venta_total) }}</td>
									<td>@{{ venta.suc_desc }}</td>
									<td>
										<div class="btn-group">
											<button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle"
												data-toggle="dropdown">
												<span class="fa fa-bars"></span>
											</button>
											<div class="dropdown-menu dropdown-menu-right shadow">
												<button type="button" class="dropdown-item"
													@click="showDetalle(venta.nro_fact_ventas, 'clientes')">
													<span class="fa fa-file-alt text-info"></span> Detalle
												</button>
												<a :href="'{{ url('pdf/boletaventa') }}/' + venta.nro_fact_ventas"
													class="dropdown-item" target="_blank">
													<span class="fa fa-file-pdf text-danger"></span> Imprimir
												</a>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				{{-- GRAFICA --}}
				<div class="tab-pane fade" id="frmchart" role="tabpanel">
					<div class="row align-items-end mb-3">
						<div class="col-md-2 form-group mb-md-0">
							<label class="small mb-1">Año</label>
							<select class="form-control form-control-sm" v-model="chart.anho">
								@for ($i = 2017; $i <= date('Y'); $i++)
									<option value="{{ $i }}">{{ $i }}</option>
								@endfor
							</select>
						</div>
						<div class="col-md-3 form-group mb-md-0">
							<label class="small mb-1">Mes</label>
							<select class="form-control form-control-sm" v-model="chart.mes">
								<option value="0">Seleccione un mes</option>
								<option v-for="(m, index) in meses" :key="index" :value="index + 1">@{{ m }}</option>
								<option value="13">Todos los meses</option>
							</select>
						</div>
						<div class="col-md-4 form-group mb-0">
							<button @click="getVentaAgrupado(true)" class="btn btn-primary btn-sm" :disabled="requestSend">
								<template v-if="requestSend">
									<span class="spinner-border spinner-border-sm"></span> Buscando...
								</template>
								<template v-else>
									<span class="fa fa-search"></span> Generar gráfica
								</template>
							</button>
						</div>
					</div>

					<div v-if="!datos.length" class="alert alert-light border text-muted mb-3">
						No hay datos para mostrar con el filtro seleccionado.
					</div>
					<div class="chart border rounded p-2 mb-4" id="line_chart_1"></div>
					<div class="chart border rounded p-2" id="column_chart_1"></div>
				</div>

				{{-- ARTICULOS --}}
				<div class="tab-pane fade" id="frmarticulo" role="tabpanel">
					<div class="row align-items-end mb-3">
						<div class="col-md-2 form-group mb-md-0">
							<label class="small mb-1">Desde</label>
							<input type="date" class="form-control form-control-sm" v-model="articulo.desde">
						</div>
						<div class="col-md-2 form-group mb-md-0">
							<label class="small mb-1">Hasta</label>
							<input type="date" class="form-control form-control-sm" v-model="articulo.hasta">
						</div>
						<div class="col-md-3 form-group mb-md-0">
							<label class="small mb-1">Sucursal</label>
							<select class="form-control form-control-sm" v-model="idSucursalArt">
								<option value="0">Todas</option>
								@foreach ($sucursales as $s)
									<option value="{{ $s['suc_cod'] }}">{{ $s['suc_desc'] }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-5 form-group mb-0">
							<button @click="getArticulo" class="btn btn-primary btn-sm" :disabled="requestSend">
								<template v-if="requestSend">
									<span class="spinner-border spinner-border-sm"></span> Buscando...
								</template>
								<template v-else>
									<span class="fa fa-search"></span> Buscar
								</template>
							</button>
						</div>
					</div>

					<div class="row mb-3" v-if="articulos.length">
						<div class="col-md-4">
							<div class="small text-muted">Artículos</div>
							<strong>@{{ articulos.length }}</strong>
						</div>
						<div class="col-md-4">
							<div class="small text-muted">Unidades vendidas</div>
							<strong>@{{ totalUnidadesVendidas }}</strong>
						</div>
					</div>

					<div class="table-responsive">
						<table class="table table-sm table-striped table-hover mb-0">
							<thead>
								<tr>
									<th>Código</th>
									<th>Descripción</th>
									<th>Sección</th>
									<th class="text-right">Cant. vendida</th>
									<th class="text-right">En stock</th>
								</tr>
							</thead>
							<tbody>
								<tr v-if="!articulos.length">
									<td colspan="5" class="text-center text-muted py-4">
										No hay resultados para el rango seleccionado.
									</td>
								</tr>
								<tr v-for="(a, i) in articulos" :key="i" :class="{ 'text-danger': Number(a.cantidad) == 0 }">
									<td>@{{ a.producto_c_barra }}</td>
									<td>@{{ a.producto_nombre }}</td>
									<td>@{{ a.present_descripcion }}</td>
									<td class="text-right font-weight-bold">@{{ parseInt(a.vendida) }}</td>
									<td class="text-right">@{{ a.cantidad }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	{{-- MODAL DETALLE --}}
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
							<span class="text-muted small d-block">N° venta</span>
							<strong>@{{ venta.nro_fact_ventas }}</strong>
						</div>
						<div class="col-sm-3 border-right text-center">
							<span class="text-muted small d-block">Fecha</span>
							<strong>@{{ venta.fecha }}</strong>
						</div>
						<div class="col-sm-6 text-center">
							<span class="text-muted small d-block">Cliente</span>
							<strong>@{{ venta.cliente_nombre }}</strong>
							<small class="d-block text-muted" v-if="venta.cliente_ruc">RUC/CI: @{{ venta.cliente_ruc }}</small>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-md-3">
							<span class="text-muted small d-block">Condición</span>
							<span class="badge"
								:class="venta.tipo_factura == 1 || venta.tipo_factura == '1' ? 'badge-success' : 'badge-warning'">
								@{{ venta.tipo_factura == 1 || venta.tipo_factura == '1' ? 'Contado' : 'Crédito' }}
							</span>
						</div>
						<div class="col-md-3">
							<span class="text-muted small d-block">Documento</span>
							<strong>@{{ venta.documento || '—' }}</strong>
						</div>
						<div class="col-md-3">
							<span class="text-muted small d-block">Descuento</span>
							<strong>Gs. @{{ formatGs(venta.venta_descuento) }}</strong>
						</div>
						<div class="col-md-3">
							<span class="text-muted small d-block">Total</span>
							<strong>Gs. @{{ formatGs(venta.venta_total) }}</strong>
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
						<div class="table-responsive">
							<table class="table table-sm table-striped">
								<thead>
									<tr>
										<th>N°</th>
										<th>Vencimiento</th>
										<th class="text-right">Cuota</th>
										<th class="text-right">Cobrado</th>
										<th class="text-right">Saldo</th>
										<th>Mora</th>
										<th class="text-right">Interés</th>
										<th>Estado</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="(c, i) in cuotas" :key="i">
										<td>@{{ c.nro_cuotas }}</td>
										<td>@{{ Funciones.formatFecha(c.fecha_venc) }}</td>
										<td class="text-right">Gs. @{{ formatGs(c.monto_cuota) }}</td>
										<td class="text-right">Gs. @{{ formatGs(c.monto_cobrado) }}</td>
										<td class="text-right">Gs. @{{ formatGs(c.monto_saldo) }}</td>
										<td>@{{ Funciones.diferenciaFecha(c.fecha_venc, c.monto_saldo) }}</td>
										<td class="text-right">Gs. @{{ formatGs(Funciones.setMontointeres(c.fecha_venc, c.monto_cuota, c.monto_saldo)) }}</td>
										<td>
											<span class="badge"
												:class="Number(c.monto_cobrado) == Number(c.monto_cuota) ? 'badge-success' : 'badge-danger'">
												@{{ Number(c.monto_cobrado) == Number(c.monto_cuota) ? 'Cobrado' : 'Pendiente' }}
											</span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="row">
							<div class="col-6">Cobrado: <strong>Gs. @{{ formatGs(Cuenta.montoCobrado) }}</strong></div>
							<div class="col-6">Saldo: <strong>Gs. @{{ formatGs(Cuenta.saldo) }}</strong></div>
						</div>
					</template>
				</div>
				<div class="modal-footer">
					<a v-if="venta.nro_fact_ventas"
						:href="'{{ url('pdf/boletaventa') }}/' + venta.nro_fact_ventas"
						class="btn btn-outline-primary btn-sm" target="_blank">
						<span class="fa fa-file-pdf"></span> Comprobante
					</a>
					<a v-if="venta.tipo_factura == 2 || venta.tipo_factura == '2'"
						:href="'{{ url('documento/extractocuenta') }}/' + venta.nro_fact_ventas"
						class="btn btn-outline-success btn-sm" target="_blank">
						<span class="fa fa-print"></span> Extracto
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
<script type="text/javascript" src="{{ asset('chart/raphael.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('chart/morris.min.js') }}"></script>
<script type="text/javascript">
	Vue.prototype.Funciones = window.Funciones;
	var app = new Vue({
		el: '#app',
		data: {
			activeTab: 'fecha',
			meses: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre'],
			fecha: { desde: '', hasta: '' },
			articulo: { desde: '', hasta: '' },
			chart: {
				mes: '1',
				anho: {{ date('Y') }},
				byYear: false
			},
			txtbuscar: '',
			venta: {},
			detalleVenta: [],
			ventas: [],
			clientes: [],
			articulos: [],
			cuotas: [],
			Cuenta: { cantitad: 0, montoCuota: 0, saldo: 0, cobrado: 0, montoCobrado: 0 },
			error: '',
			datos: [],
			isVisibleChart: false,
			requestSend: false,
			idSucursal: 0,
			idSucursalArt: 0,
			columns: [
				{ label: 'N°', field: 'codigo', type: 'number', width: '80px' },
				{ label: 'Fecha', field: 'fecha' },
				{ label: 'Cliente', field: 'cliente' },
				{ label: 'Condición', field: 'tipoHtml', html: true },
				{ label: 'Documento', field: 'documento' },
				{ label: 'Total', field: 'total', tdClass: 'font-weight-bold text-right' },
				{ label: 'Sucursal', field: 'sucursal' },
				{ label: 'Opciones', field: 'detalle', html: true, sortable: false, width: '90px' }
			],
			rows: []
		},
		methods: {
			formatGs: function (n) {
				return new Intl.NumberFormat('de-DE').format(Math.round(Number(n) || 0));
			},
			getFecha: function (flag) {
				var f = new Date();
				var dia = flag == 1 ? 1 : f.getDate();
				var mes = (f.getMonth() + 1);
				if (flag == 2) {
					this.chart.mes = mes;
					return;
				}
				return f.getFullYear() + '-' + mes.toString().padStart(2, '0') + '-' + dia.toString().padStart(2, '0');
			},
			limpiarFecha: function () {
				this.fecha = {
					desde: this.getFecha(1),
					hasta: this.getFecha(0)
				};
				this.idSucursal = 0;
				this.getVenta();
			},
			buscar: function () {
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
						this.clientes = response.data || [];
					})
					.catch(e => {
						this.requestSend = false;
						this.error = e.message;
					});
			},
			showChart: function () {
				if (this.isVisibleChart || !this.datos.length) {
					return;
				}
				var config = {
					element: 'line_chart_1',
					resize: true,
					data: this.datos,
					xkey: 'fecha',
					ykeys: ['total'],
					labels: ['Total monto venta'],
					fillOpacity: 0.6,
					lineColors: ['#007bff'],
					hideHover: 'auto'
				};
				Morris.Area(config);
				config.element = 'column_chart_1';
				Morris.Bar(config);
				this.isVisibleChart = true;
			},
			getVenta: function () {
				this.requestSend = true;
				axios.get('{{ url('infventa/fecha') }}', {
						params: {
							alld: this.fecha.desde,
							allh: this.fecha.hasta,
							alls: this.idSucursal
						}
					})
					.then(response => {
						this.requestSend = false;
						this.rows = [];
						this.ventas = response.data || [];
						for (let i = 0; i < this.ventas.length; i++) {
							var v = this.ventas[i];
							var esContado = v.tipo_factura == 1 || v.tipo_factura == '1';
							this.rows.push({
								codigo: v.nro_fact_ventas,
								fecha: v.fecha,
								cliente: v.cliente_nombre,
								tipoHtml: esContado
									? '<span class="badge badge-success">Contado</span>'
									: '<span class="badge badge-warning">Crédito</span>',
								documento: v.documento,
								total: 'Gs. ' + this.formatGs(v.venta_total),
								sucursal: v.suc_desc,
								detalle: '<div class="btn-group">' +
									'<button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">' +
									'<span class="fa fa-bars"></span></button>' +
									'<div class="dropdown-menu dropdown-menu-right shadow">' +
									'<button type="button" class="dropdown-item" onclick="app.showDetalle(' + v.nro_fact_ventas + ',\'ventas\')">' +
									'<span class="fa fa-file-alt text-info"></span> Detalle</button>' +
									'<a href="{{ url('pdf/boletaventa') }}/' + v.nro_fact_ventas + '" class="dropdown-item" target="_blank">' +
									'<span class="fa fa-file-pdf text-danger"></span> Imprimir</a>' +
									'</div></div>'
							});
						}
					})
					.catch(e => {
						this.requestSend = false;
						this.error = e.message;
					});
			},
			getVentaAgrupado: function (show) {
				this.requestSend = true;
				var lineEl = document.getElementById('line_chart_1');
				var colEl = document.getElementById('column_chart_1');
				if (lineEl) lineEl.innerHTML = '&nbsp;';
				if (colEl) colEl.innerHTML = '&nbsp;';
				axios.post('{{ url('infventa/chart') }}', { chart: this.chart })
					.then(response => {
						this.requestSend = false;
						this.datos = response.data || [];
						if (show) {
							this.isVisibleChart = false;
							this.showChart();
						}
					})
					.catch(e => {
						this.requestSend = false;
						this.error = e.message;
					});
			},
			showDetalle: function (id, tab) {
				var list = tab === 'ventas' ? this.ventas : this.clientes;
				var idx = list.findIndex(x => x.nro_fact_ventas == id);
				if (idx < 0) return;
				this.venta = list[idx];
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
			getArticulo: function () {
				this.requestSend = true;
				axios.get('{{ url('infventa/articulo') }}', {
						params: {
							artd: this.articulo.desde,
							arth: this.articulo.hasta,
							arts: this.idSucursalArt
						}
					})
					.then(response => {
						this.requestSend = false;
						this.articulos = response.data || [];
					})
					.catch(e => {
						this.requestSend = false;
						this.error = e.message;
					});
			}
		},
		computed: {
			totalVenta: function () {
				return this.ventas.length;
			},
			totalGuaranies: function () {
				var total = 0;
				for (var i = 0; i < this.ventas.length; i++) {
					total += parseFloat(this.ventas[i].venta_total || 0);
				}
				return total;
			},
			totalContado: function () {
				var total = 0;
				for (var i = 0; i < this.ventas.length; i++) {
					var v = this.ventas[i];
					if (v.tipo_factura == 1 || v.tipo_factura == '1') {
						total += parseFloat(v.venta_total || 0);
					}
				}
				return total;
			},
			totalCredito: function () {
				var total = 0;
				for (var i = 0; i < this.ventas.length; i++) {
					var v = this.ventas[i];
					if (v.tipo_factura != 1 && v.tipo_factura != '1') {
						total += parseFloat(v.venta_total || 0);
					}
				}
				return total;
			},
			totalUnidadesVendidas: function () {
				var total = 0;
				for (var i = 0; i < this.articulos.length; i++) {
					total += parseInt(this.articulos[i].vendida || 0);
				}
				return total;
			}
		},
		mounted() {
			this.getFecha(2);
			this.fecha = {
				desde: this.getFecha(1),
				hasta: this.getFecha(0)
			};
			this.articulo = {
				desde: this.getFecha(1),
				hasta: this.getFecha(0)
			};
			this.getVenta();
			this.getVentaAgrupado(false);
		}
	});

	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		if (e.target.getAttribute('href') === '#frmchart') {
			app.showChart();
		}
	});

	activarMenu('m_informe', 'm_iventa');
</script>
@endsection
