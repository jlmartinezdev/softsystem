@extends('layouts.app')
@section('title', 'Apertura/Cierre de Caja')
@section('main')
<div class="container-fluid">
	<div class="content-header">
		<div class="row mb-2 align-items-center">
			<div class="col-md-8">
				<h4 class="m-0">Apertura / Cierre de Caja</h4>
				<p class="text-muted mb-0 small">Gestioná el turno de caja, el monto inicial y el historial de operaciones.</p>
			</div>
		</div>
	</div>

	@if (session('success'))
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				Swal.fire({
					icon: 'success',
					title: 'Listo',
					text: @json(session('success')),
					confirmButtonText: 'OK'
				});
			});
		</script>
	@endif

	@if (session('error'))
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: @json(session('error')),
					confirmButtonText: 'Entendido'
				});
			});
		</script>
	@endif

	@if ($errors->any())
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				Swal.fire({
					icon: 'error',
					title: 'Revisá los datos',
					html: `{!! implode('<br>', $errors->all()) !!}`,
					confirmButtonText: 'Entendido'
				});
			});
		</script>
	@endif

	<div class="row">
		<div class="col-lg-4">
			<div class="card card-outline card-success">
				<div class="card-header">
					<strong><span class="fa fa-lock-open"></span> Abrir caja</strong>
				</div>
				<form method="POST" action="{{ route('apertura.add') }}" id="formAperturaCaja">
					@csrf
					<input type="hidden" value="{{ Auth::user()->cod_usuarios }}" name="usuario">
					<div class="card-body">
						@if ($cajaAbierta)
							<div class="alert alert-warning small mb-3">
								Ya hay una caja abierta en la sucursal seleccionada. Cerrala antes de abrir otra.
							</div>
						@endif

						<div class="form-group">
							<label for="selsucursal">Sucursal</label>
							@php
								$sucursalSeleccionada = optional($cajaAbierta)->suc_cod
									?? (request()->filled('sucursal') ? request('sucursal') : optional($sucursales->first())['suc_cod']);
							@endphp
							<select class="form-control" name="sucursal" id="selsucursal" required
								{{ $cajaAbierta ? 'disabled' : '' }}>
								@foreach ($sucursales as $sucursal)
									<option value="{{ $sucursal['suc_cod'] }}"
										{{ (string) $sucursalSeleccionada === (string) $sucursal['suc_cod'] ? 'selected' : '' }}>
										{{ $sucursal['suc_desc'] }}
									</option>
								@endforeach
							</select>
							@if ($cajaAbierta)
								<input type="hidden" name="sucursal" value="{{ $cajaAbierta->suc_cod }}">
							@endif
						</div>

						<div class="form-group">
							<label for="selcaja">Caja</label>
							<select class="form-control" name="caja" id="selcaja" required
								{{ $cajaAbierta ? 'disabled' : '' }}>
								@foreach ($cajas as $caja)
									<option value="{{ $caja['caja_cod'] }}">{{ $caja['caja_descrip'] }}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group mb-0">
							<label for="monto">Monto de apertura</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">Gs.</span>
								</div>
								<input type="number" class="form-control" name="monto" id="monto"
									placeholder="0" min="0" step="1" required
									value="{{ old('monto') }}"
									{{ $cajaAbierta ? 'disabled' : '' }}>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<button type="submit" class="btn btn-success font-weight-bold"
							{{ $cajaAbierta ? 'disabled' : '' }}>
							<span class="fa fa-lock-open"></span> ABRIR CAJA
						</button>
					</div>
				</form>
			</div>
		</div>

		<div class="col-lg-8">
			@if ($cajaAbierta)
				<div class="card card-outline card-info">
					<div class="card-header d-flex justify-content-between align-items-center">
						<strong><span class="fa fa-cash-register"></span> Caja en curso</strong>
						<span class="badge badge-success badge-pill">Abierta</span>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-4 mb-3">
								<span class="text-muted small d-block">Operación</span>
								<strong>#{{ $cajaAbierta->nro_operacion }}</strong>
							</div>
							<div class="col-md-4 mb-3">
								<span class="text-muted small d-block">Sucursal</span>
								<strong>{{ $cajaAbierta->suc_desc }}</strong>
							</div>
							<div class="col-md-4 mb-3">
								<span class="text-muted small d-block">Caja</span>
								<strong>{{ $cajaAbierta->caja_descrip }}</strong>
							</div>
							<div class="col-md-4 mb-3">
								<span class="text-muted small d-block">Usuario</span>
								<strong>{{ $cajaAbierta->nom_usuarios }}</strong>
							</div>
							<div class="col-md-4 mb-3">
								<span class="text-muted small d-block">Fecha / Hora</span>
								<strong>{{ date('d/m/Y', strtotime($cajaAbierta->apert_fecha)) }} {{ $cajaAbierta->apert_hora }}</strong>
							</div>
							<div class="col-md-4 mb-3">
								<span class="text-muted small d-block">Monto apertura</span>
								<strong>Gs. {{ number_format($cajaAbierta->apert_monto, 0, ',', '.') }}</strong>
							</div>
						</div>
					</div>
					<div class="card-footer">
						<a href="{{ route('cierre', $cajaAbierta->nro_operacion) }}" class="btn btn-outline-info">
							<span class="fa fa-lock"></span> Cerrar caja
						</a>
						<a href="{{ route('caja.informe', $cajaAbierta->nro_operacion) }}" class="btn btn-outline-secondary">
							<span class="fa fa-file"></span> Ver movimientos
						</a>
					</div>
				</div>
			@else
				<div class="card card-outline card-secondary">
					<div class="card-body text-center py-5">
						<span class="fa fa-lock fa-2x text-muted mb-3 d-block"></span>
						<h5 class="mb-1">No hay caja abierta</h5>
						<p class="text-muted mb-0 small">Completá el formulario para iniciar el turno.</p>
					</div>
				</div>
			@endif
		</div>
	</div>

	<div class="card card-outline card-primary">
		<div class="card-header">
			<strong><span class="fa fa-history"></span> Historial de aperturas</strong>
		</div>
		<div class="card-body">
			<form method="GET" action="{{ route('apertura') }}" class="mb-3">
				<div class="row align-items-end">
					<div class="col-md-3 form-group">
						<label class="small mb-1">Sucursal</label>
						<select name="sucursal" class="form-control form-control-sm">
							<option value="">Todas</option>
							@foreach ($sucursales as $sucursal)
								<option value="{{ $sucursal['suc_cod'] }}"
									{{ (string) request('sucursal') === (string) $sucursal['suc_cod'] ? 'selected' : '' }}>
									{{ $sucursal['suc_desc'] }}
								</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-2 form-group">
						<label class="small mb-1">Estado</label>
						<select name="estado" class="form-control form-control-sm">
							<option value="all" {{ request('estado', 'all') === 'all' ? 'selected' : '' }}>Todos</option>
							<option value="1" {{ request('estado') === '1' ? 'selected' : '' }}>Abierta</option>
							<option value="0" {{ request('estado') === '0' ? 'selected' : '' }}>Cerrada</option>
						</select>
					</div>
					<div class="col-md-2 form-group">
						<label class="small mb-1">Desde</label>
						<input type="date" name="desde" class="form-control form-control-sm" value="{{ request('desde') }}">
					</div>
					<div class="col-md-2 form-group">
						<label class="small mb-1">Hasta</label>
						<input type="date" name="hasta" class="form-control form-control-sm" value="{{ request('hasta') }}">
					</div>
					<div class="col-md-3 form-group">
						<button type="submit" class="btn btn-sm btn-primary">
							<span class="fa fa-filter"></span> Filtrar
						</button>
						<a href="{{ route('apertura') }}" class="btn btn-sm btn-outline-secondary">Limpiar</a>
					</div>
				</div>
			</form>

			<div class="table-responsive">
				<table class="table table-striped table-hover table-sm mb-0">
					<thead>
						<tr>
							<th>Nº</th>
							<th>Sucursal</th>
							<th>Caja</th>
							<th>Usuario</th>
							<th class="text-right">Monto</th>
							<th>Fecha</th>
							<th>Hora</th>
							<th>Estado</th>
							<th>Opción</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($aperturas as $apertura)
							<tr>
								<td>{{ $apertura['nro_operacion'] }}</td>
								<td>{{ $apertura['suc_desc'] }}</td>
								<td>{{ $apertura['caja_descrip'] }}</td>
								<td>{{ $apertura['nom_usuarios'] }}</td>
								<td class="text-right">Gs. {{ number_format($apertura['apert_monto'], 0, ',', '.') }}</td>
								<td>{{ date('d/m/Y', strtotime($apertura['apert_fecha'])) }}</td>
								<td>{{ $apertura['apert_hora'] }}</td>
								<td>
									@if ($apertura['apert_estado'] == '1')
										<span class="badge badge-success">Abierta</span>
									@else
										<span class="badge badge-secondary">Cerrada</span>
									@endif
								</td>
								<td>
									@if ($apertura['apert_estado'] == '1')
										<a href="{{ route('cierre', $apertura['nro_operacion']) }}"
											class="btn btn-outline-info btn-sm" title="Cerrar Caja">
											<span class="fa fa-lock"></span> Cerrar
										</a>
									@else
										<a href="{{ route('caja.informe', $apertura['nro_operacion']) }}"
											class="btn btn-outline-success btn-sm">
											<span class="fa fa-file"></span> Detalle
										</a>
									@endif
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="9" class="text-center text-muted py-4">
									No hay aperturas registradas con esos filtros.
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
		@if ($aperturas->hasPages())
			<div class="card-footer">
				{{ $aperturas->links() }}
			</div>
		@endif
	</div>
</div>
@endsection
@section('script')
<script type="text/javascript">
	var formApertura = document.getElementById('formAperturaCaja');
	if (formApertura) {
		formApertura.addEventListener('submit', function (e) {
			e.preventDefault();
			var form = this;
			var monto = document.getElementById('monto');
			if (!monto || monto.value === '' || Number(monto.value) < 0) {
				Swal.fire({
					icon: 'warning',
					title: 'Monto inválido',
					text: 'Ingresá el monto de apertura.',
					confirmButtonText: 'Entendido'
				});
				return;
			}

			Swal.fire({
				icon: 'question',
				title: '¿Confirmar apertura de caja?',
				text: 'Monto inicial: Gs. ' + Number(monto.value).toLocaleString('de-DE'),
				showCancelButton: true,
				confirmButtonColor: '#28a745',
				cancelButtonColor: '#6c757d',
				confirmButtonText: 'Sí, abrir',
				cancelButtonText: 'Cancelar',
				reverseButtons: true
			}).then(function (result) {
				if (result.value || result.isConfirmed) {
					form.submit();
				}
			});
		});
	}

	activarMenu('m_caja', 'm_apertura');
</script>
@endsection
