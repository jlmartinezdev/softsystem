@extends('layouts.app')
@section('title', 'Inicio — ' . ($empresa->emp_nombre ?? 'VENTAPRO+'))
@section('style')
<style>
	@font-face {
		font-family: "Cairo";
		font-style: normal;
		font-weight: 700;
		font-display: swap;
		src: url({{ asset("webfonts/Cairo-Bold.ttf")}}) format("truetype");
	}
	.font-cairo { font-family: Cairo, sans-serif; }
	.home-title {
		color: #0a4d36;
		font-size: 1.5rem;
		line-height: 1.35;
		font-weight: 700;
	}
	.home-lead {
		margin: 6px 0 0;
		color: #2b3d35;
		font-size: 1rem;
		line-height: 1.5;
		max-width: 42rem;
	}
	.home-turno {
		margin: 20px 0 24px;
		padding: 16px 0;
		border-top: 1px solid #d5ddd8;
		border-bottom: 1px solid #d5ddd8;
	}
	.home-turno p {
		margin: 0 0 8px;
		color: #2b3d35;
		font-size: 1rem;
		line-height: 1.5;
		max-width: 46rem;
	}
	.home-turno p:last-child { margin-bottom: 0; }
	.home-turno .is-ready { color: #0a4d36; }
	.home-turno .is-blocked { color: #6b4f00; }
	.home-turno .turno-acciones {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		gap: 8px;
		margin-top: 12px;
	}
	.btn-venta {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		min-height: 48px;
		padding: 10px 18px;
		background: #0a4d36;
		border: 2px solid #0a4d36;
		color: #fff;
		font-size: 1.05rem;
		font-weight: 700;
		line-height: 1.5;
		border-radius: 8px;
		white-space: nowrap;
	}
	.btn-venta:hover,
	.btn-venta:focus {
		background: #083c2a;
		border-color: #083c2a;
		color: #fff;
		text-decoration: none;
	}
	.btn-venta-sm {
		min-height: 40px;
		padding: 6px 14px;
		font-size: 1rem;
	}
	.btn-venta-block {
		width: 100%;
		margin: 16px 0 8px;
	}
	.btn-cerrar-turno {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-height: 40px;
		padding: 6px 14px;
		background: #fff;
		border: 2px solid #6b4f00;
		color: #6b4f00;
		font-size: 1rem;
		font-weight: 700;
		line-height: 1.5;
		border-radius: 8px;
	}
	.btn-cerrar-turno:hover,
	.btn-cerrar-turno:focus {
		background: #6b4f00;
		border-color: #6b4f00;
		color: #fff;
		text-decoration: none;
	}
	.badge-contado,
	.badge-abierta {
		background: #0a4d36;
		color: #fff;
	}
	.badge-credito {
		background: #6b4f00;
		color: #fff;
	}
	.home-card .text-muted {
		color: #2b3d35 !important;
	}
	.home-card .card-header {
		background: #fff;
		border-bottom: 1px solid #d5ddd8;
	}
	.home-venta-nro {
		color: #0a4d36;
		font-weight: 700;
	}
	.home-venta-nro:hover,
	.home-venta-nro:focus {
		color: #083c2a;
	}
	.home-cliente {
		display: inline-block;
		max-width: 14rem;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		vertical-align: bottom;
	}
	.table tbody td {
		vertical-align: middle;
		line-height: 1.5;
	}
</style>
@endsection
@section('main')
@php
	$cajaTurno = $cajasAbiertas->first();
	$hoyCopy = $n_ventas_hoy . ' ' . ($n_ventas_hoy === 1 ? 'venta' : 'ventas')
		. ' · Gs. ' . number_format($total_ventas_hoy, 0, ',', '.');
@endphp
<div class="container-fluid py-3">
	<div class="content-header px-0">
		<div class="row mb-2 align-items-center">
			<div class="{{ $esAdministrador ? 'col-md-8' : 'col-12' }}">
				<h1 class="home-title font-cairo m-0">{{ $empresa->emp_nombre ?? 'VENTAPRO+' }}</h1>
				<p class="home-lead">
					Hola, {{ $usuario->nom_usuarios ?? 'usuario' }} · {{ date('d/m/Y') }}.
					@if (!$esAdministrador)
						@if ($cajaTurno)
							Tu caja está abierta. Cobrá el turno.
						@else
							Abrí caja para registrar ventas.
						@endif
					@endif
				</p>
			</div>
			@if ($esAdministrador)
				<div class="col-md-4 text-md-right mt-3 mt-md-0">
					<a href="{{ route('venta') }}" class="btn-venta">
						<span class="fa fa-shopping-cart" aria-hidden="true"></span> Nueva venta
					</a>
				</div>
			@endif
		</div>
	</div>

	@unless ($esAdministrador)
		@if ($cajaTurno)
			<a href="{{ route('venta') }}" class="btn-venta btn-venta-block">
				<span class="fa fa-shopping-cart" aria-hidden="true"></span> Nueva venta
			</a>
		@else
			<a href="{{ route('apertura') }}" class="btn-venta btn-venta-block">
				<span class="fa fa-lock-open" aria-hidden="true"></span> Abrir caja
			</a>
		@endif
	@endunless

	<div class="home-turno">
		@if ($cajaTurno)
			<p>
				<span class="badge badge-pill badge-abierta">Abierta</span>
				{{ $cajaTurno->suc_desc }} · {{ $cajaTurno->caja_descrip }}
			</p>
		@else
			<p>No hay caja abierta.</p>
		@endif

		<p class="{{ $sifenListo ? 'is-ready' : 'is-blocked' }}" role="status">
			@if (!$sifenActivo)
				Factura electrónica apagada.
				@if ($esAdministrador)
					<a href="{{ route('sifen.index') }}">Configurar SIFEN</a>
				@else
					Avisá al dueño.
				@endif
			@elseif ($sifenFalta)
				Falta {{ $sifenFalta }} para facturar.
				@if ($esAdministrador)
					<a href="{{ route('sifen.index') }}">Completar</a>
				@endif
			@elseif ($sifenAmbiente === 'test')
				Listo para factura electrónica (prueba SIFEN).
			@else
				Listo para factura electrónica SIFEN.
			@endif
		</p>

		<p>
			Hoy {{ $hoyCopy }}
			@if ($esAdministrador)
				· Por cobrar
				<a href="{{ route('infctacobrar') }}">Gs. {{ number_format($saldoCobrar, 0, ',', '.') }}</a>
			@endif
		</p>

		<div class="turno-acciones">
			@if ($cajaTurno)
				<a href="{{ route('cierre', $cajaTurno->nro_operacion) }}"
					class="btn-cerrar-turno"
					title="Vas a cerrar el turno.">
					Cerrar el turno
				</a>
				<a href="{{ route('caja.informe', $cajaTurno->nro_operacion) }}" class="btn btn-outline-secondary btn-sm">
					Movimientos
				</a>
			@elseif ($esAdministrador)
				<a href="{{ route('apertura') }}" class="btn-venta btn-venta-sm">Abrir caja</a>
			@endif
		</div>
	</div>

	<div class="card home-card">
		<div class="card-header d-flex justify-content-between align-items-center">
			<strong>Últimas ventas</strong>
			<a href="{{ route('infventa') }}" class="btn btn-outline-secondary btn-sm">Ver todas</a>
		</div>
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table table-sm table-striped mb-0">
					<thead>
						<tr>
							<th>#</th>
							<th>Fecha</th>
							<th>Cliente</th>
							<th>Tipo</th>
							@if ($esAdministrador)
								<th>Sucursal</th>
							@endif
							<th class="text-right">Total</th>
							<th><span class="sr-only">Acción</span></th>
						</tr>
					</thead>
					<tbody>
						@forelse ($ventasRecientes as $v)
							@php
								$ventaHref = $sifenListo
									? route('venta.facturar', $v->nro_fact_ventas)
									: route('infventa');
								$ventaAccion = $sifenListo ? 'Facturar' : 'Ver';
							@endphp
							<tr>
								<td>
									<a class="home-venta-nro" href="{{ $ventaHref }}">{{ $v->nro_fact_ventas }}</a>
								</td>
								<td>{{ $v->fecha }}</td>
								<td>
									<span class="home-cliente" title="{{ $v->cliente_nombre }}">{{ $v->cliente_nombre }}</span>
								</td>
								<td>
									@if ((string) $v->tipo_factura === '1')
										<span class="badge badge-contado">Contado</span>
									@else
										<span class="badge badge-credito">Crédito</span>
									@endif
								</td>
								@if ($esAdministrador)
									<td>{{ $v->suc_desc ?? '—' }}</td>
								@endif
								<td class="text-right">
									<strong>Gs. {{ number_format($v->venta_total, 0, ',', '.') }}</strong>
								</td>
								<td>
									<a href="{{ $ventaHref }}">{{ $ventaAccion }}</a>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="{{ $esAdministrador ? 7 : 6 }}" class="text-center py-4">
									<p class="mb-3" style="color: #2b3d35; line-height: 1.5;">Todavía no hay ventas registradas.</p>
									<a href="{{ route('venta') }}" class="btn-venta btn-venta-sm">Nueva venta</a>
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
	activarMenu('m_home', '');
</script>
@endsection
