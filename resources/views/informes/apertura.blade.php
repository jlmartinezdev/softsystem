@extends('layouts.app')
@section('title','Detalle de movimiento Caja')
@section('main')
<div id="app">
	<div class="container">
        <span class="bg-dark text-white d-block p-2 text-center">Informe movimiento caja Operacion N° {{$movimiento[0]->nro_operacion}}</span>
        <br>
        
        <div class="card mt-2 table-responsive-sm">
            <table class="table table-sm table-striped">
                <tr>
                    <th>#</th>
                    <th>Tipo</th>
                    <th>Fecha - Hora</th>
                    <th>Descripcion</th>
                    <th>Monto</th>
                </tr>
                @foreach ($movimiento as $i => $m)
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
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
	
    activarMenu('m_caja','m_movimiento');
</script>
@endsection