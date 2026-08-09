@extends('layouts.app')
@section('title', 'Cierre de Caja')
@section('main')
<div class="container-fluid">
	<div class="content-header">
		<div class="row mb-2 align-items-center">
			<div class="col-md-8">
				<h4 class="m-0">Cierre de Caja</h4>
				<p class="text-muted mb-0 small">
					Operación #{{ $apertura->nro_operacion }} — arqueo del turno.
				</p>
			</div>
			<div class="col-md-4 text-md-right">
				<a href="{{ route('apertura') }}" class="btn btn-outline-secondary btn-sm">
					<span class="fa fa-reply"></span> Volver
				</a>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-3">
			<div class="info-box mb-3">
				<span class="info-box-icon bg-info"><i class="fa fa-door-open"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Apertura</span>
					<span class="info-box-number">Gs. {{ number_format($apertura->apert_monto, 0, ',', '.') }}</span>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="info-box mb-3">
				<span class="info-box-icon bg-success"><i class="fa fa-arrow-down"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Entradas</span>
					<span class="info-box-number">Gs. {{ number_format($entradas, 0, ',', '.') }}</span>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="info-box mb-3">
				<span class="info-box-icon bg-danger"><i class="fa fa-arrow-up"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Salidas</span>
					<span class="info-box-number">Gs. {{ number_format($salidas, 0, ',', '.') }}</span>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="info-box mb-3">
				<span class="info-box-icon bg-primary"><i class="fa fa-balance-scale"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Esperado</span>
					<span class="info-box-number">Gs. {{ number_format($esperado, 0, ',', '.') }}</span>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-5">
			<div class="card card-outline card-info">
				<form method="POST" action="{{ route('apertura.close') }}" id="formCierreCaja">
					@csrf
					<input type="hidden" name="nro_operacion" value="{{ $apertura->nro_operacion }}">
					<input type="hidden" id="esperado" value="{{ $esperado }}">

					<div class="card-header">
						<strong><span class="fa fa-lock"></span> Confirmar cierre</strong>
					</div>
					<div class="card-body">
						<div class="row mb-3">
							<div class="col-6">
								<span class="text-muted small d-block">Sucursal</span>
								<strong>{{ $apertura->suc_desc }}</strong>
							</div>
							<div class="col-6">
								<span class="text-muted small d-block">Caja</span>
								<strong>{{ $apertura->caja_descrip }}</strong>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-6">
								<span class="text-muted small d-block">Usuario</span>
								<strong>{{ $apertura->nom_usuarios }}</strong>
							</div>
							<div class="col-6">
								<span class="text-muted small d-block">Apertura</span>
								<strong>{{ date('d/m/Y', strtotime($apertura->apert_fecha)) }} {{ $apertura->apert_hora }}</strong>
							</div>
						</div>

						<div class="alert alert-light border mb-3">
							<div class="d-flex justify-content-between align-items-center">
								<span>Esperado en caja</span>
								<strong>Gs. {{ number_format($esperado, 0, ',', '.') }}</strong>
							</div>
						</div>

						<div class="form-group">
							<label for="monto">Monto contado (cierre)</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">Gs.</span>
								</div>
								<input type="number" class="form-control" name="monto" id="monto"
									placeholder="0" min="0" step="1" required value="{{ old('monto') }}"
									oninput="calcDiff()">
							</div>
						</div>

						<div class="alert mb-0" id="diffBox">
							<div class="d-flex justify-content-between align-items-center">
								<span>Diferencia</span>
								<strong id="diferencia">Gs. 0</strong>
							</div>
							<small id="diffHint" class="text-muted">Ingresá el monto contado para ver el arqueo.</small>
						</div>
					</div>
					<div class="card-footer">
						<button class="btn btn-success" type="submit" id="btnCerrarCaja">
							<span class="fa fa-save"></span> Cerrar Caja
						</button>
						<a href="{{ route('apertura') }}" class="btn btn-secondary">
							<span class="fa fa-reply"></span> Atrás
						</a>
					</div>
				</form>
			</div>
		</div>

		<div class="col-lg-7">
			<div class="card card-outline card-secondary">
				<div class="card-header">
					<strong><span class="fa fa-list"></span> Movimientos del turno</strong>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-sm table-striped mb-0">
							<thead>
								<tr>
									<th>#</th>
									<th>Tipo</th>
									<th>Fecha</th>
									<th>Concepto</th>
									<th class="text-right">Monto</th>
								</tr>
							</thead>
							<tbody>
								@forelse ($movimientos as $i => $m)
									<tr class="{{ $m->mov_tipo == 'Entrada' ? 'text-success' : 'text-danger' }}">
										<td>{{ $i + 1 }}</td>
										<td>
											{{ $m->mov_tipo }}
											@if ($m->mov_tipo == 'Entrada')
												<span class="fa fa-arrow-left"></span>
											@else
												<span class="fa fa-arrow-right"></span>
											@endif
										</td>
										<td>{{ $m->mov_fecha }}</td>
										<td>{{ $m->mov_concepto }}</td>
										<td class="text-right">
											<strong>
												Gs.
												@if ($m->mov_tipo == 'Salida' && $m->mov_monto > 0)-@endif
												{{ number_format($m->mov_monto, 0, ',', '.') }}
											</strong>
										</td>
									</tr>
								@empty
									<tr>
										<td colspan="5" class="text-center text-muted py-4">
											Sin movimientos registrados.
										</td>
									</tr>
								@endforelse
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
<script>
	function formatGs(n) {
		var neg = n < 0;
		var abs = Math.abs(Math.round(n));
		var s = abs.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
		return (neg ? '-Gs. ' : 'Gs. ') + s;
	}

	function calcDiff() {
		var esperado = Number(document.getElementById('esperado').value || 0);
		var contado = Number(document.getElementById('monto').value || 0);
		var diff = contado - esperado;
		var box = document.getElementById('diffBox');
		var label = document.getElementById('diferencia');
		var hint = document.getElementById('diffHint');

		label.textContent = formatGs(diff);
		box.classList.remove('alert-success', 'alert-danger', 'alert-info', 'alert-secondary');

		if (document.getElementById('monto').value === '') {
			box.classList.add('alert-secondary');
			hint.textContent = 'Ingresá el monto contado para ver el arqueo.';
			label.textContent = 'Gs. 0';
			return;
		}

		if (diff === 0) {
			box.classList.add('alert-success');
			hint.textContent = 'Arqueo cuadrado.';
		} else if (diff < 0) {
			box.classList.add('alert-danger');
			hint.textContent = 'Faltante en caja.';
		} else {
			box.classList.add('alert-info');
			hint.textContent = 'Sobrante en caja.';
		}
	}

	document.getElementById('formCierreCaja').addEventListener('submit', function (e) {
		e.preventDefault();
		var form = this;
		var esperado = Number(document.getElementById('esperado').value || 0);
		var montoEl = document.getElementById('monto');
		var contado = Number(montoEl.value || 0);

		if (montoEl.value === '' || isNaN(contado) || contado < 0) {
			Swal.fire({
				icon: 'warning',
				title: 'Monto inválido',
				text: 'Ingresá el monto contado para cerrar la caja.',
				confirmButtonText: 'Entendido'
			});
			montoEl.focus();
			return;
		}

		var diff = contado - esperado;
		var diffLabel = formatGs(diff);
		var estadoArqueo = diff === 0 ? 'Arqueo cuadrado' : (diff < 0 ? 'Faltante en caja' : 'Sobrante en caja');
		var icono = diff === 0 ? 'question' : 'warning';

		Swal.fire({
			icon: icono,
			title: '¿Confirmar cierre de caja?',
			html:
				'<div class="text-left small">' +
				'<p class="mb-1"><strong>Operación:</strong> #{{ $apertura->nro_operacion }}</p>' +
				'<p class="mb-1"><strong>Esperado:</strong> ' + formatGs(esperado) + '</p>' +
				'<p class="mb-1"><strong>Contado:</strong> ' + formatGs(contado) + '</p>' +
				'<p class="mb-1"><strong>Diferencia:</strong> ' + diffLabel + '</p>' +
				'<p class="mb-0 text-muted">' + estadoArqueo + '</p>' +
				'</div>',
			showCancelButton: true,
			confirmButtonColor: '#28a745',
			cancelButtonColor: '#6c757d',
			confirmButtonText: 'Sí, cerrar caja',
			cancelButtonText: 'Cancelar',
			reverseButtons: true
		}).then(function (result) {
			if (result.value || result.isConfirmed) {
				Swal.fire({
					title: 'Cerrando caja...',
					text: 'Procesando cierre y resumen',
					allowOutsideClick: false,
					allowEscapeKey: false,
					onBeforeOpen: function () {
						Swal.showLoading();
					},
					didOpen: function () {
						Swal.showLoading();
					}
				});
				form.submit();
			}
		});
	});

	@if ($errors->any())
	Swal.fire({
		icon: 'error',
		title: 'No se pudo cerrar',
		html: {!! json_encode(implode('<br>', $errors->all())) !!},
		confirmButtonText: 'Entendido'
	});
	@endif

	calcDiff();
	activarMenu('m_caja', 'm_apertura');
</script>
@endsection
