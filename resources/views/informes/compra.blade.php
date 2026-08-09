@extends('layouts.app')
@section('title', 'Informe de Compras')
@section('style')
<style>
	.modal-body { max-height: 70vh; overflow-y: auto; }
	.vgt-table { font-size: 0.9rem; }
</style>
@endsection
@section('main')
<div class="container-fluid" id="app">
	<div class="content-header">
		<div class="row mb-2 align-items-center">
			<div class="col-md-8">
				<h4 class="m-0">Informe de compras</h4>
				<p class="text-muted mb-0 small">Consultá compras por rango de fechas, sucursal y proveedor.</p>
			</div>
		</div>
	</div>

	<div class="row mb-3">
		<div class="col-md-3">
			<div class="info-box mb-3">
				<span class="info-box-icon bg-info"><i class="fa fa-shopping-basket"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Compras</span>
					<span class="info-box-number">@{{ totalCompra }}</span>
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
		<div class="card-header">
			<strong><span class="fa fa-filter"></span> Filtros</strong>
		</div>
		<div class="card-body">
			<div class="row align-items-end">
				<div class="col-md-2 form-group mb-md-0">
					<label class="small mb-1">Desde</label>
					<input type="date" class="form-control form-control-sm" v-model="fecha.desde">
				</div>
				<div class="col-md-2 form-group mb-md-0">
					<label class="small mb-1">Hasta</label>
					<input type="date" class="form-control form-control-sm" v-model="fecha.hasta">
				</div>
				<div class="col-md-2 form-group mb-md-0">
					<label class="small mb-1">Sucursal</label>
					<select class="form-control form-control-sm" v-model="idSucursal">
						<option value="0">Todas</option>
						@foreach ($sucursales as $s)
							<option value="{{ $s['suc_cod'] }}">{{ $s['suc_desc'] }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-3 form-group mb-md-0">
					<label class="small mb-1">Proveedor</label>
					<input type="text" class="form-control form-control-sm" v-model.trim="txtproveedor"
						placeholder="Nombre del proveedor..." @keyup.enter="getCompra" tabindex="1">
				</div>
				<div class="col-md-3 form-group mb-0">
					<button @click="getCompra" class="btn btn-primary btn-sm" :disabled="requestSend">
						<template v-if="requestSend">
							<span class="spinner-border spinner-border-sm"></span> Buscando...
						</template>
						<template v-else>
							<span class="fa fa-search"></span> Buscar
						</template>
					</button>
					<button type="button" class="btn btn-outline-secondary btn-sm" @click="limpiarFiltros" title="Limpiar">
						<span class="fa fa-times"></span> Limpiar
					</button>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-outline card-dark">
		<div class="card-header d-flex justify-content-between align-items-center">
			<strong><span class="fa fa-list"></span> Resultado</strong>
			<span class="small text-muted" v-if="compras.length">@{{ compras.length }} registro(s)</span>
		</div>
		<div class="card-body">
			<vue-good-table
				:columns="columns"
				:rows="rows"
				:search-options="{ enabled: true, placeholder: 'Filtrar en la tabla...' }"
				:pagination-options="{ enabled: true, perPage: 15, perPageDropdown: [10, 15, 25, 50] }"
				style-class="vgt-table striped condensed" />
		</div>
	</div>

	<div class="modal fade" id="frmdetalle">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header bg-dark text-white">
					<h6 class="modal-title mb-0">Detalle de compra #@{{ compra.compra_cod }}</h6>
					<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row mb-3">
						<div class="col-sm-3 border-right text-center">
							<span class="text-muted small d-block">N° compra</span>
							<strong>@{{ compra.compra_cod }}</strong>
						</div>
						<div class="col-sm-3 border-right text-center">
							<span class="text-muted small d-block">Fecha</span>
							<strong>@{{ compra.compra_fecha }}</strong>
						</div>
						<div class="col-sm-6 text-center">
							<span class="text-muted small d-block">Proveedor</span>
							<strong>@{{ compra.proveedor_nombre }}</strong>
							<small class="d-block text-muted" v-if="compra.proveedor_ruc">RUC: @{{ compra.proveedor_ruc }}</small>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-md-3">
							<span class="text-muted small d-block">Factura</span>
							<strong>@{{ compra.compra_factura || '—' }}</strong>
						</div>
						<div class="col-md-3">
							<span class="text-muted small d-block">Condición</span>
							<span class="badge"
								:class="compra.compra_tipo_factura == 1 || compra.compra_tipo_factura == '1' ? 'badge-success' : 'badge-warning'">
								@{{ compra.compra_tipo_factura == 1 || compra.compra_tipo_factura == '1' ? 'Contado' : 'Crédito' }}
							</span>
						</div>
						<div class="col-md-3">
							<span class="text-muted small d-block">Sucursal</span>
							<strong>@{{ compra.suc_desc }}</strong>
						</div>
						<div class="col-md-3">
							<span class="text-muted small d-block">Total</span>
							<strong>Gs. @{{ formatGs(compra.total) }}</strong>
						</div>
					</div>

					<span class="badge badge-info mb-2">Artículos</span>
					<table class="table table-sm table-striped mb-0">
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
							<tr v-for="(d, i) in detalleCompra" :key="i">
								<td>@{{ d.producto_c_barra }}</td>
								<td>@{{ d.producto_nombre }}</td>
								<td class="text-right">@{{ parseInt(d.compra_cantidad) }}</td>
								<td class="text-right">Gs. @{{ formatGs(d.compra_precio) }}</td>
								<td class="text-right font-weight-bold">Gs. @{{ formatGs(d.compra_cantidad * d.compra_precio) }}</td>
							</tr>
							<tr v-if="!detalleCompra || !detalleCompra.length">
								<td colspan="5" class="text-center text-muted py-3">Sin detalle</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<strong class="mr-auto">Total: Gs. @{{ formatGs(compra.total) }}</strong>
					<a v-if="compra.compra_cod"
						:href="'{{ url('pdf/boletacompra') }}/' + compra.compra_cod"
						class="btn btn-outline-primary btn-sm" target="_blank">
						<span class="fa fa-file-pdf"></span> Imprimir
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
<script type="text/javascript">
	var app = new Vue({
		el: '#app',
		data: {
			fecha: { desde: '', hasta: '' },
			txtproveedor: '',
			compra: {},
			detalleCompra: [],
			compras: [],
			error: '',
			requestSend: false,
			idSucursal: 0,
			columns: [
				{ label: 'N°', field: 'codigo', type: 'number', width: '80px' },
				{ label: 'Fecha', field: 'fecha' },
				{ label: 'Proveedor', field: 'proveedor' },
				{ label: 'Factura', field: 'factura' },
				{ label: 'Condición', field: 'tipoHtml', html: true },
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
				return f.getFullYear() + '-' + mes.toString().padStart(2, '0') + '-' + dia.toString().padStart(2, '0');
			},
			limpiarFiltros: function () {
				this.fecha = {
					desde: this.getFecha(1),
					hasta: this.getFecha(0)
				};
				this.txtproveedor = '';
				this.idSucursal = 0;
				this.getCompra();
			},
			getCompra: function () {
				this.requestSend = true;
				axios.get('{{ url('infcompra/fecha') }}', {
						params: {
							alld: this.fecha.desde,
							allh: this.fecha.hasta,
							alls: this.idSucursal,
							proveedor: this.txtproveedor
						}
					})
					.then(response => {
						this.requestSend = false;
						this.rows = [];
						this.compras = response.data || [];
						for (let i = 0; i < this.compras.length; i++) {
							var c = this.compras[i];
							var esContado = c.compra_tipo_factura == 1 || c.compra_tipo_factura == '1';
							this.rows.push({
								codigo: c.compra_cod,
								fecha: c.compra_fecha,
								proveedor: c.proveedor_nombre,
								factura: c.compra_factura || '—',
								tipoHtml: esContado
									? '<span class="badge badge-success">Contado</span>'
									: '<span class="badge badge-warning">Crédito</span>',
								total: 'Gs. ' + this.formatGs(c.total),
								sucursal: c.suc_desc,
								detalle: '<div class="btn-group">' +
									'<button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">' +
									'<span class="fa fa-bars"></span></button>' +
									'<div class="dropdown-menu dropdown-menu-right shadow">' +
									'<button type="button" class="dropdown-item" onclick="app.showDetalle(' + c.compra_cod + ')">' +
									'<span class="fa fa-file-alt text-info"></span> Detalle</button>' +
									'<a href="{{ url('pdf/boletacompra') }}/' + c.compra_cod + '" class="dropdown-item" target="_blank">' +
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
			showDetalle: function (id) {
				var idx = this.compras.findIndex(x => x.compra_cod == id);
				if (idx < 0) return;
				this.compra = this.compras[idx];
				this.detalleCompra = [];
				$('#frmdetalle').modal('show');
				this.getDetalle();
			},
			getDetalle: function () {
				axios.get('{{ url('infcompra/detalle') }}/' + this.compra.compra_cod)
					.then(response => {
						this.detalleCompra = response.data;
					})
					.catch(() => {});
			}
		},
		computed: {
			totalCompra: function () {
				return this.compras.length;
			},
			totalGuaranies: function () {
				var total = 0;
				for (var i = 0; i < this.compras.length; i++) {
					total += parseFloat(this.compras[i].total || 0);
				}
				return total;
			},
			totalContado: function () {
				var total = 0;
				for (var i = 0; i < this.compras.length; i++) {
					var c = this.compras[i];
					if (c.compra_tipo_factura == 1 || c.compra_tipo_factura == '1') {
						total += parseFloat(c.total || 0);
					}
				}
				return total;
			},
			totalCredito: function () {
				var total = 0;
				for (var i = 0; i < this.compras.length; i++) {
					var c = this.compras[i];
					if (c.compra_tipo_factura != 1 && c.compra_tipo_factura != '1') {
						total += parseFloat(c.total || 0);
					}
				}
				return total;
			}
		},
		mounted() {
			this.fecha = {
				desde: this.getFecha(1),
				hasta: this.getFecha(0)
			};
			this.getCompra();
		}
	});
	activarMenu('m_informe', 'm_icompra');
</script>
@endsection
