@extends('layouts.app')
@section('title', $localIncompleto ? 'Inicio' : ('Inicio — ' . $nombreLocal))
@section('style')
<style>
	@font-face {
		font-family: "Cairo";
		font-style: normal;
		font-weight: 700;
		font-display: swap;
		src: url({{ asset("webfonts/Cairo-Bold.ttf")}}) format("truetype");
	}
	.font-cairo { font-family: 'Cairo', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }

	:root {
		--dash-primary: #0a4d36;
		--dash-primary-dark: #073827;
		--dash-primary-light: #eaf3ef;
		--dash-primary-border: #c8dfd5;
		--dash-accent: #b8860b;
		--dash-accent-light: #fef8eb;
		--dash-text-main: #1c2430;
		--dash-text-muted: #64748b;
		--dash-card-bg: #ffffff;
		--dash-border: #e2e8f0;
	}

	body.dark-mode {
		--dash-primary-light: #162a22;
		--dash-primary-border: #234d3c;
		--dash-accent-light: #2b2515;
		--dash-text-main: #e2e8f0;
		--dash-text-muted: #94a3b8;
		--dash-card-bg: #1f2937;
		--dash-border: #374151;
	}

	.dash-container {
		padding: 0.75rem 0 2rem;
	}

	/* Cabecera */
	.dash-header {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		justify-content: space-between;
		gap: 1rem;
		margin-bottom: 1.25rem;
		padding-bottom: 1rem;
		border-bottom: 1px solid var(--dash-border);
	}
	.dash-header-title {
		font-size: 1.6rem;
		font-weight: 700;
		color: var(--dash-primary);
		margin: 0;
		line-height: 1.2;
	}
	.dash-header-subtitle {
		font-size: 0.95rem;
		color: var(--dash-text-muted);
		margin: 0.25rem 0 0;
	}
	.dash-header-badges {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		gap: 0.5rem;
	}
	.badge-pill-custom {
		display: inline-flex;
		align-items: center;
		gap: 0.4rem;
		padding: 0.45rem 0.85rem;
		border-radius: 9999px;
		font-size: 0.825rem;
		font-weight: 600;
		text-decoration: none !important;
	}
	.badge-sifen-ok {
		background: #dcfce7;
		color: #166534;
		border: 1px solid #bbf7d0;
	}
	.badge-sifen-warn {
		background: #fef3c7;
		color: #92400e;
		border: 1px solid #fde68a;
	}
	.badge-sifen-off {
		background: #f1f5f9;
		color: #64748b;
		border: 1px solid #e2e8f0;
	}
	.badge-role {
		background: #e0f2fe;
		color: #0369a1;
		border: 1px solid #bae6fd;
	}
	.badge-local {
		background: var(--dash-primary-light);
		color: var(--dash-primary);
		border: 1px solid var(--dash-primary-border);
	}

	/* Turno Hero Banner */
	.turno-hero {
		background: #ffffff;
		border: 1px solid var(--dash-border);
		border-radius: 12px;
		padding: 1.25rem 1.5rem;
		margin-bottom: 1.5rem;
		box-shadow: 0 2px 6px rgba(0,0,0,0.03);
		position: relative;
		overflow: hidden;
	}
	.turno-hero-open {
		border-left: 5px solid var(--dash-primary);
		background: linear-gradient(to right, rgba(10, 77, 54, 0.03), transparent);
	}
	.turno-hero-closed {
		border-left: 5px solid var(--dash-accent);
		background: linear-gradient(to right, rgba(184, 134, 11, 0.04), transparent);
	}
	.turno-status-dot {
		display: inline-block;
		width: 10px;
		height: 10px;
		border-radius: 50%;
		background-color: #22c55e;
		box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);
		animation: pulse-dot 2s infinite;
	}
	@keyframes pulse-dot {
		0%, 100% { box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.25); }
		50% { box-shadow: 0 0 0 6px rgba(34, 197, 94, 0.08); }
	}
	.turno-hero-title {
		font-size: 1.15rem;
		font-weight: 700;
		color: var(--dash-text-main);
		margin-bottom: 0.35rem;
		display: flex;
		align-items: center;
		gap: 0.5rem;
	}
	.turno-hero-details {
		display: flex;
		flex-wrap: wrap;
		gap: 1.25rem;
		color: var(--dash-text-muted);
		font-size: 0.9rem;
	}
	.turno-hero-item strong {
		color: var(--dash-text-main);
	}
	.turno-hero-actions {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		gap: 0.5rem;
		margin-top: 1rem;
	}

	/* Botones Principales */
	.btn-pos-primary {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 0.5rem;
		padding: 0.6rem 1.4rem;
		background: var(--dash-primary);
		border: 1px solid var(--dash-primary);
		color: #ffffff !important;
		font-weight: 700;
		font-size: 1rem;
		border-radius: 8px;
		transition: all 0.15s ease-in-out;
		text-decoration: none !important;
	}
	.btn-pos-primary:hover, .btn-pos-primary:focus {
		background: var(--dash-primary-dark);
		border-color: var(--dash-primary-dark);
		transform: translateY(-1px);
		box-shadow: 0 4px 10px rgba(10, 77, 54, 0.25);
	}
	.btn-pos-secondary {
		display: inline-flex;
		align-items: center;
		gap: 0.4rem;
		padding: 0.55rem 1rem;
		background: #ffffff;
		border: 1px solid var(--dash-border);
		color: var(--dash-text-main) !important;
		font-weight: 600;
		font-size: 0.9rem;
		border-radius: 8px;
		text-decoration: none !important;
		transition: all 0.15s;
	}
	.btn-pos-secondary:hover {
		background: var(--dash-primary-light);
		border-color: var(--dash-primary-border);
		color: var(--dash-primary) !important;
	}
	.btn-pos-danger {
		display: inline-flex;
		align-items: center;
		gap: 0.4rem;
		padding: 0.55rem 1rem;
		background: #ffffff;
		border: 1px solid #fca5a5;
		color: #b91c1c !important;
		font-weight: 600;
		font-size: 0.9rem;
		border-radius: 8px;
		text-decoration: none !important;
		transition: all 0.15s;
	}
	.btn-pos-danger:hover {
		background: #fef2f2;
		border-color: #ef4444;
	}

	/* Tarjetas KPI */
	.kpi-card {
		background: var(--dash-card-bg);
		border: 1px solid var(--dash-border);
		border-radius: 12px;
		padding: 1.15rem 1.25rem;
		margin-bottom: 1.25rem;
		box-shadow: 0 2px 6px rgba(0,0,0,0.02);
		transition: transform 0.15s ease, box-shadow 0.15s ease;
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		height: calc(100% - 1.25rem);
	}
	.kpi-card:hover {
		transform: translateY(-2px);
		box-shadow: 0 6px 14px rgba(0,0,0,0.06);
	}
	.kpi-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 0.6rem;
	}
	.kpi-label {
		font-size: 0.85rem;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.03em;
		color: var(--dash-text-muted);
	}
	.kpi-icon-box {
		width: 38px;
		height: 38px;
		border-radius: 8px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 1.1rem;
	}
	.kpi-icon-green { background: #dcfce7; color: #166534; }
	.kpi-icon-blue { background: #e0f2fe; color: #0284c7; }
	.kpi-icon-amber { background: #fef3c7; color: #b45309; }
	.kpi-icon-purple { background: #f3e8ff; color: #7e22ce; }
	
	.kpi-value {
		font-size: 1.55rem;
		font-weight: 800;
		color: var(--dash-text-main);
		line-height: 1.2;
		font-variant-numeric: tabular-nums;
	}
	.kpi-subtext {
		margin-top: 0.4rem;
		font-size: 0.825rem;
		color: var(--dash-text-muted);
		display: flex;
		align-items: center;
		gap: 0.35rem;
	}

	/* Accesos Rápidos */
	.quick-actions-bar {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
		gap: 0.75rem;
		margin-bottom: 1.5rem;
	}
	.quick-action-btn {
		background: var(--dash-card-bg);
		border: 1px solid var(--dash-border);
		border-radius: 10px;
		padding: 0.85rem 0.5rem;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		gap: 0.45rem;
		color: var(--dash-text-main) !important;
		text-decoration: none !important;
		transition: all 0.15s ease-in-out;
		font-size: 0.825rem;
		font-weight: 600;
		text-align: center;
	}
	.quick-action-btn i {
		font-size: 1.35rem;
		color: var(--dash-primary);
		transition: transform 0.15s;
	}
	.quick-action-btn:hover {
		background: var(--dash-primary);
		color: #ffffff !important;
		border-color: var(--dash-primary);
		transform: translateY(-2px);
		box-shadow: 0 4px 10px rgba(10,77,54,0.18);
	}
	.quick-action-btn:hover i {
		color: #ffffff;
		transform: scale(1.1);
	}

	/* Gráfico de Barras SVG */
	.chart-card {
		background: var(--dash-card-bg);
		border: 1px solid var(--dash-border);
		border-radius: 12px;
		padding: 1.25rem;
		margin-bottom: 1.5rem;
		box-shadow: 0 2px 6px rgba(0,0,0,0.02);
	}
	.chart-card-head {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		justify-content: space-between;
		gap: 0.5rem;
		margin-bottom: 1.25rem;
	}
	.chart-card-title {
		font-size: 1.05rem;
		font-weight: 700;
		color: var(--dash-text-main);
		margin: 0;
		display: flex;
		align-items: center;
		gap: 0.5rem;
	}
	.chart-stats-pills {
		display: flex;
		flex-wrap: wrap;
		gap: 0.5rem;
	}
	.chart-stat-pill {
		font-size: 0.8rem;
		padding: 0.25rem 0.65rem;
		background: var(--dash-primary-light);
		color: var(--dash-primary);
		border-radius: 6px;
		font-weight: 600;
	}

	.svg-barchart-container {
		display: flex;
		align-items: flex-end;
		justify-content: space-between;
		height: 200px;
		padding: 1rem 0.5rem 0;
		border-bottom: 1px solid var(--dash-border);
		gap: 8px;
	}
	.svg-bar-col {
		flex: 1;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: flex-end;
		height: 100%;
		position: relative;
		cursor: pointer;
	}
	.svg-bar-track {
		width: 100%;
		max-width: 44px;
		height: 100%;
		display: flex;
		align-items: flex-end;
		background: rgba(0,0,0,0.02);
		border-radius: 6px 6px 0 0;
		position: relative;
	}
	.svg-bar-fill {
		width: 100%;
		background: #10b981;
		border-radius: 5px 5px 0 0;
		transition: height 0.4s ease-out, background 0.15s;
		min-height: 4px;
		position: relative;
	}
	.svg-bar-col:hover .svg-bar-fill {
		background: var(--dash-primary);
	}
	.svg-bar-col.is-today .svg-bar-fill {
		background: var(--dash-primary);
		box-shadow: 0 0 0 2px var(--dash-accent);
	}
	.svg-bar-tooltip {
		position: absolute;
		bottom: 105%;
		left: 50%;
		transform: translateX(-50%);
		background: #1c2430;
		color: #ffffff;
		padding: 0.35rem 0.6rem;
		border-radius: 6px;
		font-size: 0.75rem;
		white-space: nowrap;
		pointer-events: none;
		opacity: 0;
		visibility: hidden;
		transition: opacity 0.15s, transform 0.15s;
		z-index: 10;
		box-shadow: 0 4px 10px rgba(0,0,0,0.2);
	}
	.svg-bar-tooltip::after {
		content: "";
		position: absolute;
		top: 100%;
		left: 50%;
		margin-left: -4px;
		border-width: 4px;
		border-style: solid;
		border-color: #1c2430 transparent transparent transparent;
	}
	.svg-bar-col:hover .svg-bar-tooltip {
		opacity: 1;
		visibility: visible;
		transform: translateX(-50%) translateY(-4px);
	}
	.svg-bar-label {
		margin-top: 0.5rem;
		font-size: 0.75rem;
		font-weight: 600;
		color: var(--dash-text-muted);
		text-align: center;
		line-height: 1.2;
	}
	.svg-bar-col.is-today .svg-bar-label {
		color: var(--dash-primary);
		font-weight: 700;
	}

	/* Panel de Cajas Abiertas & Stock */
	.side-panel-card {
		background: var(--dash-card-bg);
		border: 1px solid var(--dash-border);
		border-radius: 12px;
		padding: 1.25rem;
		margin-bottom: 1.5rem;
		box-shadow: 0 2px 6px rgba(0,0,0,0.02);
	}
	.side-panel-title {
		font-size: 1rem;
		font-weight: 700;
		color: var(--dash-text-main);
		margin-bottom: 1rem;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}
	.active-caja-item {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 0.65rem 0.5rem;
		border-bottom: 1px solid var(--dash-border);
	}
	.active-caja-item:last-child {
		border-bottom: none;
	}
	.active-caja-user {
		font-weight: 600;
		font-size: 0.875rem;
		color: var(--dash-text-main);
	}
	.active-caja-sub {
		font-size: 0.775rem;
		color: var(--dash-text-muted);
	}

	/* Tabla de Últimas Ventas */
	.table-card {
		background: var(--dash-card-bg);
		border: 1px solid var(--dash-border);
		border-radius: 12px;
		overflow: hidden;
		box-shadow: 0 2px 6px rgba(0,0,0,0.02);
		margin-bottom: 2rem;
	}
	.table-card-head {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 1rem 1.25rem;
		border-bottom: 1px solid var(--dash-border);
	}
	.table-card-title {
		font-size: 1.05rem;
		font-weight: 700;
		color: var(--dash-text-main);
		margin: 0;
		display: flex;
		align-items: center;
		gap: 0.5rem;
	}
	.table-recent thead th {
		background: #f8fafc;
		border-top: none;
		border-bottom: 1px solid var(--dash-border);
		color: var(--dash-text-muted);
		font-size: 0.785rem;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.03em;
		padding: 0.75rem 1rem;
	}
	body.dark-mode .table-recent thead th {
		background: #1e293b;
	}
	.table-recent tbody td {
		padding: 0.85rem 1rem;
		vertical-align: middle;
		border-top: 1px solid var(--dash-border);
		font-size: 0.9rem;
		color: var(--dash-text-main);
	}
	.badge-tipo-contado {
		background: #dcfce7;
		color: #15803d;
		font-weight: 700;
		font-size: 0.75rem;
		padding: 0.3em 0.6em;
		border-radius: 6px;
	}
	.badge-tipo-credito {
		background: #fef3c7;
		color: #b45309;
		font-weight: 700;
		font-size: 0.75rem;
		padding: 0.3em 0.6em;
		border-radius: 6px;
	}
	.venta-total-cell {
		font-weight: 700;
		font-variant-numeric: tabular-nums;
		color: var(--dash-text-main);
	}
</style>
@endsection

@section('main')
@php
	$cajaTurno = $cajasAbiertas->first();
	$ctaHref = $cajaTurno ? route('venta') : route('apertura');
	$ctaLabel = $cajaTurno ? 'Nueva Venta (F2)' : 'Abrir Caja';
	$ctaIcon = $cajaTurno ? 'fa-shopping-cart' : 'fa-lock-open';

	$diasNombreLargo = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
	$diaActualLargo = $diasNombreLargo[(int) date('w')];
	$fechaFormateada = $diaActualLargo . ', ' . date('d') . ' de ' . $mes . ' de ' . date('Y');
@endphp

<div class="dash-container">
	{{-- 1. Cabecera Principal y Badges de Estado --}}
	<div class="dash-header">
		<div>
			<h1 class="dash-header-title font-cairo">
				Buen día, {{ $usuario->nom_usuarios ?? 'Usuario' }}
			</h1>
			<p class="dash-header-subtitle">
				<i class="far fa-calendar-alt mr-1"></i> {{ $fechaFormateada }}
			</p>
		</div>

		<div class="dash-header-badges">
			{{-- Local --}}
			@if (!$localIncompleto)
				<span class="badge-pill-custom badge-local">
					<i class="fas fa-store"></i> {{ $nombreLocal }}
				</span>
			@elseif ($esAdministrador)
				<a href="{{ route('empresa.index') }}" class="badge-pill-custom badge-sifen-warn">
					<i class="fas fa-exclamation-circle"></i> Configurar nombre del local
				</a>
			@endif

			{{-- Rol --}}
			<span class="badge-pill-custom badge-role">
				<i class="fas fa-user-shield"></i> {{ $esAdministrador ? 'Administrador' : 'Cajero / Ventas' }}
			</span>

			{{-- Estado SIFEN --}}
			@if (!$sifenActivo)
				<a href="{{ $esAdministrador ? route('sifen.index') : '#' }}" class="badge-pill-custom badge-sifen-off" title="Facturación electrónica apagada">
					<i class="fas fa-power-off"></i> SIFEN Inactivo
				</a>
			@elseif ($sifenFalta)
				<a href="{{ $esAdministrador ? route('sifen.index') : '#' }}" class="badge-pill-custom badge-sifen-warn" title="Falta configuración para emitir comprobantes">
					<i class="fas fa-exclamation-triangle"></i> SIFEN: Falta {{ $sifenFalta }}
				</a>
			@elseif ($sifenAmbiente === 'test')
				<span class="badge-pill-custom badge-sifen-ok" title="Listo en ambiente de homologación / prueba">
					<i class="fas fa-check-circle"></i> SIFEN (Pruebas)
				</span>
			@else
				<span class="badge-pill-custom badge-sifen-ok" title="Facturación electrónica lista y en producción">
					<i class="fas fa-check-circle"></i> SIFEN Producción
				</span>
			@endif
		</div>
	</div>

	{{-- 2. Hero Banner: Estado de Turno de Caja --}}
	@if ($cajaTurno)
		<div class="turno-hero turno-hero-open">
			<div class="row align-items-center">
				<div class="col-lg-8">
					<div class="turno-hero-title">
						<span class="turno-status-dot"></span>
						<span>Turno de Caja Abierto</span>
					</div>
					<div class="turno-hero-details">
						<div class="turno-hero-item">
							<i class="fas fa-building mr-1"></i> Sucursal: <strong>{{ $cajaTurno->suc_desc }}</strong>
						</div>
						<div class="turno-hero-item">
							<i class="fas fa-cash-register mr-1"></i> Caja: <strong>{{ $cajaTurno->caja_descrip }}</strong>
						</div>
						<div class="turno-hero-item">
							<i class="far fa-clock mr-1"></i> Apertura: <strong>{{ date('d/m/Y', strtotime($cajaTurno->apert_fecha)) }} {{ $cajaTurno->apert_hora }}</strong>
						</div>
						<div class="turno-hero-item">
							<i class="fas fa-coins mr-1"></i> Fondo: <strong>Gs. {{ number_format($cajaTurno->apert_monto, 0, ',', '.') }}</strong>
						</div>
					</div>
				</div>
				<div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
					<div class="turno-hero-actions justify-content-lg-end">
						<a href="{{ route('venta') }}" class="btn-pos-primary" title="Abrir Punto de Venta (Tecla rápida F2)">
							<i class="fas fa-shopping-cart"></i> Nueva Venta (F2)
						</a>
						<a href="{{ route('caja.informe', $cajaTurno->nro_operacion) }}" class="btn-pos-secondary" title="Ver movimientos y arqueo de este turno">
							<i class="fas fa-file-invoice-dollar"></i> Arqueo
						</a>
						<a href="{{ route('cierre', $cajaTurno->nro_operacion) }}" class="btn-pos-danger" title="Cerrar el turno de caja">
							<i class="fas fa-lock"></i> Cerrar Caja
						</a>
					</div>
				</div>
			</div>
		</div>
	@else
		<div class="turno-hero turno-hero-closed">
			<div class="row align-items-center">
				<div class="col-lg-8">
					<div class="turno-hero-title text-warning">
						<i class="fas fa-lock mr-2"></i> No tenés una caja abierta en este momento
					</div>
					<p class="mb-0 text-muted" style="font-size: 0.95rem;">
						Para cobrar en el mostrador, emitir facturas y registrar ingresos, iniciá tu turno abriendo caja.
					</p>
				</div>
				<div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
					<a href="{{ route('apertura') }}" class="btn-pos-primary">
						<i class="fas fa-lock-open mr-1"></i> Abrir Caja / Iniciar Turno
					</a>
				</div>
			</div>
		</div>
	@endif

	{{-- 3. Accesos Rápidos Operativos --}}
	<div class="quick-actions-bar">
		<a href="{{ route('venta') }}" class="quick-action-btn">
			<i class="fas fa-shopping-cart"></i>
			<span>Punto de Venta</span>
		</a>
		<a href="{{ route('apertura') }}" class="quick-action-btn">
			<i class="fas fa-cash-register"></i>
			<span>Apertura / Cierre</span>
		</a>
		<a href="{{ route('cobro') }}" class="quick-action-btn">
			<i class="fas fa-hand-holding-usd"></i>
			<span>Registrar Cobro</span>
		</a>
		<a href="{{ route('articulo') }}" class="quick-action-btn">
			<i class="fas fa-boxes"></i>
			<span>Artículos / Stock</span>
		</a>
		<a href="{{ route('cliente.index') }}" class="quick-action-btn">
			<i class="fas fa-users"></i>
			<span>Clientes</span>
		</a>
		@if ($esAdministrador)
			<a href="{{ route('compra') }}" class="quick-action-btn">
				<i class="fas fa-truck-loading"></i>
				<span>Compras</span>
			</a>
			<a href="{{ route('infventa') }}" class="quick-action-btn">
				<i class="fas fa-chart-bar"></i>
				<span>Informes Ventas</span>
			</a>
			<a href="{{ route('sifen.index') }}" class="quick-action-btn">
				<i class="fas fa-file-invoice"></i>
				<span>Config SIFEN</span>
			</a>
		@else
			<a href="{{ route('infventa') }}" class="quick-action-btn">
				<i class="fas fa-receipt"></i>
				<span>Mis Ventas</span>
			</a>
		@endif
	</div>

	{{-- 4. Tarjetas KPI de Métricas Clave --}}
	<div class="row">
		{{-- Ventas de Hoy --}}
		<div class="col-xl-3 col-sm-6">
			<div class="kpi-card">
				<div>
					<div class="kpi-header">
						<span class="kpi-label">Ventas de Hoy</span>
						<div class="kpi-icon-box kpi-icon-green">
							<i class="far fa-calendar-check"></i>
						</div>
					</div>
					<div class="kpi-value font-cairo">
						Gs. {{ number_format($total_ventas_hoy, 0, ',', '.') }}
					</div>
				</div>
				<div class="kpi-subtext">
					<i class="fas fa-tag text-muted"></i>
					<span>{{ $n_ventas_hoy }} {{ $n_ventas_hoy === 1 ? 'venta realizada' : 'ventas realizadas' }}</span>
				</div>
			</div>
		</div>

		{{-- Ventas del Mes --}}
		<div class="col-xl-3 col-sm-6">
			<div class="kpi-card">
				<div>
					<div class="kpi-header">
						<span class="kpi-label">Ventas {{ $mes }}</span>
						<div class="kpi-icon-box kpi-icon-blue">
							<i class="fas fa-chart-line"></i>
						</div>
					</div>
					<div class="kpi-value font-cairo">
						Gs. {{ number_format($total_ventas_mes, 0, ',', '.') }}
					</div>
				</div>
				<div class="kpi-subtext">
					<i class="fas fa-receipt text-muted"></i>
					<span>{{ $n_ventas }} transacciones acumuladas</span>
				</div>
			</div>
		</div>

		{{-- Cobros del Mes --}}
		<div class="col-xl-3 col-sm-6">
			<div class="kpi-card">
				<div>
					<div class="kpi-header">
						<span class="kpi-label">Cobros {{ $mes }}</span>
						<div class="kpi-icon-box kpi-icon-amber">
							<i class="fas fa-hand-holding-usd"></i>
						</div>
					</div>
					<div class="kpi-value font-cairo">
						Gs. {{ number_format($total_cobros_mes, 0, ',', '.') }}
					</div>
				</div>
				<div class="kpi-subtext">
					<i class="fas fa-check text-success"></i>
					<span>{{ $n_cobros }} cobros de crédito</span>
				</div>
			</div>
		</div>

		{{-- Cuentas por Cobrar --}}
		<div class="col-xl-3 col-sm-6">
			<div class="kpi-card">
				<div>
					<div class="kpi-header">
						<span class="kpi-label">Por Cobrar (Pendiente)</span>
						<div class="kpi-icon-box kpi-icon-purple">
							<i class="fas fa-clock"></i>
						</div>
					</div>
					<div class="kpi-value font-cairo">
						Gs. {{ number_format($saldoCobrar, 0, ',', '.') }}
					</div>
				</div>
				<div class="kpi-subtext">
					@if ($cuotasVencidas > 0)
						<a href="{{ route('infctacobrar') }}" class="text-danger font-weight-bold">
							<i class="fas fa-exclamation-triangle"></i> {{ $cuotasVencidas }} cuotas vencidas
						</a>
					@else
						<span class="text-success">
							<i class="fas fa-check-circle"></i> Cartera de créditos al día
						</span>
					@endif
				</div>
			</div>
		</div>
	</div>

	{{-- 5. Fila: Gráfico de Ventas de la Semana + Panel Lateral --}}
	<div class="row">
		{{-- Gráfico de Ventas de los últimos 7 días --}}
		<div class="col-lg-8">
			<div class="chart-card">
				<div class="chart-card-head">
					<h3 class="chart-card-title">
						<i class="fas fa-chart-bar text-success"></i>
						<span>Evolución de Ventas (Últimos 7 Días)</span>
					</h3>
					<div class="chart-stats-pills">
						<span class="chart-stat-pill">
							Semana: <strong>Gs. {{ number_format($totalVentasSemana, 0, ',', '.') }}</strong>
						</span>
						<span class="chart-stat-pill">
							Promedio: <strong>Gs. {{ number_format($promedioVentaDiaria, 0, ',', '.') }}/día</strong>
						</span>
					</div>
				</div>

				@if ($chartTieneVentas)
					<div class="svg-barchart-container">
						@foreach ($ventasChart as $bar)
							@php
								$barPct = $maxVentaChart > 0 ? round(($bar['total'] / $maxVentaChart) * 100) : 0;
								$displayHeight = $bar['total'] > 0 ? max($barPct, 8) : 2;
							@endphp
							<div class="svg-bar-col {{ $bar['es_hoy'] ? 'is-today' : '' }}">
								<div class="svg-bar-tooltip">
									<strong>{{ $bar['dia_nombre'] }} {{ $bar['fecha_full'] }}</strong><br>
									Gs. {{ number_format($bar['total'], 0, ',', '.') }}<br>
									<span style="opacity: 0.85;">{{ $bar['cantidad'] }} {{ $bar['cantidad'] === 1 ? 'venta' : 'ventas' }}</span>
								</div>
								<div class="svg-bar-track">
									<div class="svg-bar-fill" style="height: {{ $displayHeight }}%;"></div>
								</div>
								<div class="svg-bar-label">
									{{ $bar['dia_nombre'] }}<br>
									<span style="font-size: 0.7rem; font-weight: normal;">{{ $bar['fecha_corta'] }}</span>
								</div>
							</div>
						@endforeach
					</div>
				@else
					<div class="text-center py-5 text-muted">
						<i class="fas fa-chart-line fa-3x mb-3 text-muted" style="opacity: 0.4;"></i>
						<p class="mb-2">Aún no se registran ventas en los últimos 7 días.</p>
						<a href="{{ $ctaHref }}" class="btn-pos-primary btn-sm">
							<i class="fas fa-plus"></i> Registrar primera venta
						</a>
					</div>
				@endif
			</div>
		</div>

		{{-- Panel Lateral: Monitor de Cajas Activas y Resumen de Stock --}}
		<div class="col-lg-4">
			@if ($esAdministrador)
				{{-- Monitor de Cajas Abiertas --}}
				<div class="side-panel-card">
					<div class="side-panel-title">
						<span><i class="fas fa-cash-register text-success mr-1"></i> Cajas Activas en Vivo</span>
						<span class="badge badge-pill badge-success">{{ $todasCajasAbiertas->count() }}</span>
					</div>
					@forelse ($todasCajasAbiertas as $cajaItem)
						<div class="active-caja-item">
							<div>
								<div class="active-caja-user">
									<i class="fas fa-user-circle text-muted mr-1"></i> {{ $cajaItem->nom_usuarios }}
								</div>
								<div class="active-caja-sub">
									{{ $cajaItem->suc_desc }} · {{ $cajaItem->caja_descrip }}
								</div>
							</div>
							<div class="text-right">
								<a href="{{ route('caja.informe', $cajaItem->nro_operacion) }}" class="btn btn-outline-secondary btn-xs" title="Ver movimientos">
									<i class="fas fa-eye"></i> Arqueo
								</a>
							</div>
						</div>
					@empty
						<div class="text-center py-3 text-muted" style="font-size: 0.85rem;">
							<i class="fas fa-door-closed mb-2"></i><br>
							No hay cajas abiertas en el local.
						</div>
					@endforelse
				</div>

				{{-- Resumen Rápido de Stock --}}
				<div class="side-panel-card">
					<div class="side-panel-title">
						<span><i class="fas fa-boxes text-info mr-1"></i> Estado del Catálogo</span>
						<a href="{{ route('articulo') }}" class="btn btn-link btn-sm p-0 text-muted" style="font-size: 0.8rem;">Ver todos</a>
					</div>
					<div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
						<span class="text-muted" style="font-size: 0.875rem;">Artículos Registrados:</span>
						<strong class="font-cairo" style="font-size: 1.1rem;">{{ $totalArticulos }}</strong>
					</div>
					<div class="d-flex justify-content-between align-items-center">
						<span class="text-muted" style="font-size: 0.875rem;">Sin Stock (Agotados):</span>
						@if ($articulosSinStock > 0)
							<a href="{{ route('articulo') }}" class="badge badge-danger" style="font-size: 0.825rem;">
								{{ $articulosSinStock }} artículos
							</a>
						@else
							<span class="badge badge-success" style="font-size: 0.825rem;">0 agotados</span>
						@endif
					</div>
				</div>
			@else
				{{-- Atajos y Consejos para Cajero --}}
				<div class="side-panel-card">
					<div class="side-panel-title">
						<span><i class="fas fa-keyboard text-primary mr-1"></i> Atajos de Mostrador</span>
					</div>
					<ul class="list-unstyled mb-0" style="font-size: 0.875rem;">
						<li class="mb-2 pb-2 border-bottom d-flex justify-content-between">
							<span>Punto de Venta:</span>
							<kbd>F2</kbd>
						</li>
						<li class="mb-2 pb-2 border-bottom d-flex justify-content-between">
							<span>Consultar Precio:</span>
							<a href="{{ route('articulo') }}" class="text-primary font-weight-bold">Artículos</a>
						</li>
						<li class="d-flex justify-content-between">
							<span>Cobrar Cuota:</span>
							<a href="{{ route('cobro') }}" class="text-primary font-weight-bold">Cobranzas</a>
						</li>
					</ul>
				</div>
			@endif
		</div>
	</div>

	{{-- 6. Tabla de Últimas Ventas --}}
	<div class="table-card">
		<div class="table-card-head">
			<h3 class="table-card-title">
				<i class="fas fa-history text-secondary"></i>
				<span>Últimas Ventas Registradas</span>
			</h3>
			<a href="{{ route('infventa') }}" class="btn btn-outline-secondary btn-sm">
				<i class="fas fa-list mr-1"></i> Ver todas las ventas
			</a>
		</div>

		<div class="table-responsive">
			<table class="table table-hover table-recent mb-0">
				<thead>
					<tr>
						<th># Venta</th>
						<th>Fecha y Hora</th>
						<th>Cliente</th>
						<th>Condición</th>
						@if ($esAdministrador)
							<th>Sucursal</th>
						@endif
						<th class="text-right">Total Facturado</th>
						<th class="text-center" style="width: 130px;">Acciones</th>
					</tr>
				</thead>
				<tbody>
					@forelse ($ventasRecientes as $v)
						@php
							$ventaHref = $sifenListo
								? route('venta.facturar', $v->nro_fact_ventas)
								: route('infventa');
						@endphp
						<tr>
							<td>
								<a href="{{ $ventaHref }}" class="font-weight-bold text-success">
									#{{ $v->nro_fact_ventas }}
								</a>
							</td>
							<td>
								<i class="far fa-clock text-muted mr-1"></i> {{ $v->fecha }}
							</td>
							<td>
								<strong>{{ $v->cliente_nombre }}</strong>
								@if (!empty($v->documento))
									<div class="text-muted" style="font-size: 0.775rem;">Doc: {{ $v->documento }}</div>
								@endif
							</td>
							<td>
								@if ((string) $v->tipo_factura === '1')
									<span class="badge-tipo-contado">Contado</span>
								@else
									<span class="badge-tipo-credito">Crédito</span>
								@endif
							</td>
							@if ($esAdministrador)
								<td>
									<span class="text-muted">{{ $v->suc_desc ?? '—' }}</span>
								</td>
							@endif
							<td class="text-right venta-total-cell">
								Gs. {{ number_format($v->venta_total, 0, ',', '.') }}
							</td>
							<td class="text-center">
								<div class="btn-group btn-group-sm">
									@if ($sifenListo)
										<a href="{{ route('venta.facturar', $v->nro_fact_ventas) }}" class="btn btn-outline-success btn-xs" title="Facturar electrónicamente">
											<i class="fas fa-bolt"></i> SIFEN
										</a>
									@endif
									<a href="{{ url('ticket/venta/' . $v->nro_fact_ventas) }}" target="_blank" class="btn btn-outline-secondary btn-xs" title="Imprimir Ticket">
										<i class="fas fa-print"></i> Ticket
									</a>
								</div>
							</td>
						</tr>
					@empty
						<tr>
							<td colspan="{{ $esAdministrador ? 7 : 6 }}" class="text-center py-5">
								<i class="fas fa-shopping-basket fa-2x text-muted mb-2" style="opacity: 0.5;"></i>
								<p class="text-muted mb-3">Todavía no hay ventas registradas en el sistema.</p>
								<a href="{{ $ctaHref }}" class="btn-pos-primary btn-sm">
									<i class="fas fa-plus mr-1"></i> {{ $ctaLabel }}
								</a>
							</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript">
	activarMenu('m_home', '');

	// Atajo de teclado: F2 para ir a Punto de Venta
	document.addEventListener('keydown', function(e) {
		if (e.key === 'F2') {
			e.preventDefault();
			window.location.href = "{{ route('venta') }}";
		}
	});
</script>
@endsection
