@extends('layouts.app')

@section('main')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-plus-circle"></i> Crear Nueva Unidad</h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('unidades.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="uni_nombre" class="col-md-4 col-form-label text-md-right">
                                <i class="fas fa-tag"></i> Nombre
                            </label>
                            <div class="col-md-6">
                                <input id="uni_nombre" type="text" class="form-control @error('uni_nombre') is-invalid @enderror" name="uni_nombre" value="{{ old('uni_nombre') }}" required autocomplete="uni_nombre" autofocus>
                                @error('uni_nombre')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="uni_abreviatura" class="col-md-4 col-form-label text-md-right">
                                <i class="fas fa-text-height"></i> Abreviatura
                            </label>
                            <div class="col-md-6">
                                <input id="uni_abreviatura" type="text" class="form-control @error('uni_abreviatura') is-invalid @enderror" name="uni_abreviatura" value="{{ old('uni_abreviatura') }}" required autocomplete="uni_abreviatura">
                                @error('uni_abreviatura')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar
                                </button>
                                <a href="{{ route('unidades.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
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


