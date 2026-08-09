@extends('layouts.app')
@section('title', 'Detalle de movimiento Caja')
@section('main')
<div class="container-fluid">
	<div class="content-header">
		<div class="row mb-2 align-items-center">
			<div class="col-md-8">
				<h4 class="m-0">Movimientos de caja</h4>
				<p class="text-muted mb-0 small">
					@if (count($movimiento) > 0)
						Operación N° {{ $movimiento[0]->nro_operacion }}
					@else
						Sin movimientos
					@endif
				</p>
			</div>
			<div class="col-md-4 text-md-right">
				<a href="{{ route('apertura') }}" class="btn btn-outline-secondary btn-sm">
					<span class="fa fa-reply"></span> Volver
				</a>
			</div>
		</div>
	</div>

	@php
		$entradas = collect($movimiento)->where('mov_tipo', 'Entrada')->sum('mov_monto');
		$salidas = collect($movimiento)->where('mov_tipo', 'Salida')->sum('mov_monto');
		$saldo = $entradas - $salidas;
	@endphp

	<div class="row mb-3">
		<div class="col-md-4">
			<div class="info-box mb-3">
				<span class="info-box-icon bg-success"><i class="fa fa-arrow-down"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Entradas</span>
					<span class="info-box-number">Gs. {{ number_format($entradas, 0, ',', '.') }}</span>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="info-box mb-3">
				<span class="info-box-icon bg-danger"><i class="fa fa-arrow-up"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Salidas</span>
					<span class="info-box-number">Gs. {{ number_format($salidas, 0, ',', '.') }}</span>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="info-box mb-3">
				<span class="info-box-icon bg-primary"><i class="fa fa-balance-scale"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Saldo</span>
					<span class="info-box-number">Gs. {{ number_format($saldo, 0, ',', '.') }}</span>
				</div>
			</div>
		</div>
	</div>

	<div class="card card-outline card-dark">
		<div class="card-header">
			<strong>Detalle de movimientos</strong>
		</div>
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table table-sm table-striped mb-0">
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
						@forelse ($movimiento as $i => $m)
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
										Gs. @if ($m->mov_tipo == 'Salida' && $m->mov_monto > 0) - @endif
										{{ number_format($m->mov_monto, 0, ',', '.') }}
									</strong>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="5" class="text-center text-muted py-4">
									No hay movimientos para esta operación.
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script type="text/javascript">
	activarMenu('m_caja', 'm_movimiento');
</script>
@endsection
