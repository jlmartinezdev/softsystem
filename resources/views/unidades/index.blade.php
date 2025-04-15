@extends('layouts.app')

@section('main')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3><i class="fas fa-balance-scale"></i> Unidades</h3>
                        <a href="{{ route('unidades.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nueva Unidad
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-tag"></i> Nombre</th>
                                <th><i class="fas fa-text-height"></i> Abreviatura</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unidades as $unidad)
                                <tr>
                                    <td>{{ $unidad->uni_codigo }}</td>
                                    <td>{{ $unidad->uni_nombre }}</td>
                                    <td>{{ $unidad->uni_abreviatura }}</td>
                                    <td>
                                        <a href="{{ route('unidades.edit', $unidad->uni_codigo) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <form action="{{ route('unidades.destroy', $unidad->uni_codigo) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro de eliminar esta unidad?')">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
@section('script')
<script>
    activarMenu('m_mantenimiento','m_unidades');
</script>
@endsection
