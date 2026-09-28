@extends('layouts.app')
@section('title', 'Nueva Unidad de Medida')

@section('style')
<style>
    @font-face {
        font-family: "Cairo";
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url({{ asset("webfonts/Cairo-Bold.ttf") }}) format("truetype");
    }
    .font-cairo {
        font-family: 'Cairo', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    :root {
        --dash-primary: #0a4d36;
        --dash-primary-dark: #073827;
        --dash-primary-light: #eaf3ef;
        --dash-primary-border: #c8dfd5;
        --dash-text-main: #1c2430;
        --dash-text-muted: #64748b;
        --dash-card-bg: #ffffff;
        --dash-panel-bg: #f8fafc;
        --dash-border: #e2e8f0;
    }
    body.dark-mode {
        --dash-primary: #10b981;
        --dash-primary-dark: #059669;
        --dash-primary-light: #064e3b;
        --dash-primary-border: #047857;
        --dash-text-main: #f1f5f9;
        --dash-text-muted: #94a3b8;
        --dash-card-bg: #1e293b;
        --dash-panel-bg: #0f172a;
        --dash-border: #334155;
    }
    .modern-form-card {
        background: var(--dash-card-bg);
        border: 1px solid var(--dash-border);
        border-radius: 16px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }
    .modern-form-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .modern-form-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: var(--dash-primary-light);
        color: var(--dash-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .pos-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--dash-text-main);
        margin-bottom: 0.35rem;
    }
    .pos-input {
        width: 100%;
        padding: 0.6rem 0.9rem;
        border-radius: 10px;
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        font-size: 0.92rem;
        transition: all 0.15s ease;
    }
    .pos-input:focus {
        outline: none;
        border-color: var(--dash-primary);
        background: var(--dash-card-bg);
        box-shadow: 0 0 0 3px var(--dash-primary-light);
    }
    .btn-pos-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1.35rem;
        background: var(--dash-primary);
        border: 1px solid var(--dash-primary);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.9rem;
        border-radius: 10px;
        transition: all 0.15s;
        cursor: pointer;
    }
    .btn-pos-primary:hover {
        background: var(--dash-primary-dark);
        border-color: var(--dash-primary-dark);
    }
    .btn-pos-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 1.2rem;
        border-radius: 10px;
        border: 1px solid var(--dash-border);
        background: var(--dash-panel-bg);
        color: var(--dash-text-main);
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none !important;
    }
    .btn-pos-secondary:hover {
        background: var(--dash-card-bg);
        color: var(--dash-primary);
    }
</style>
@endsection

@section('main')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9 col-sm-12">
            <div class="modern-form-card">
                <div class="modern-form-header">
                    <div class="modern-form-icon">
                        <i class="fa fa-plus-circle"></i>
                    </div>
                    <div>
                        <h4 class="font-cairo mb-0 text-dark dark:text-white" style="font-weight: 700;">Nueva Unidad de Medida</h4>
                        <small class="text-muted">Registre una nueva unidad métrica o de empaque</small>
                    </div>
                </div>

                <div class="p-4">
                    <form method="POST" action="{{ route('unidades.store') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label class="pos-label" for="uni_nombre">
                                Nombre de la Unidad <span class="text-danger">*</span>
                            </label>
                            <input id="uni_nombre"
                                   type="text"
                                   class="pos-input @error('uni_nombre') is-invalid @enderror"
                                   name="uni_nombre"
                                   value="{{ old('uni_nombre') }}"
                                   placeholder="Ej: KILOGRAMO, UNIDAD, LITRO, CAJA..."
                                   required
                                   autofocus>
                            @error('uni_nombre')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="pos-label" for="uni_abreviatura">
                                Abreviatura / Símbolo <span class="text-danger">*</span>
                            </label>
                            <input id="uni_abreviatura"
                                   type="text"
                                   class="pos-input @error('uni_abreviatura') is-invalid @enderror"
                                   name="uni_abreviatura"
                                   value="{{ old('uni_abreviatura') }}"
                                   placeholder="Ej: KG, UN, L, CJ, DOC..."
                                   maxlength="10"
                                   required>
                            @error('uni_abreviatura')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-between pt-2">
                            <a href="{{ route('unidades.index') }}" class="btn-pos-secondary font-cairo">
                                <i class="fa fa-arrow-left"></i> Volver a la lista
                            </a>
                            <button type="submit" class="btn-pos-primary font-cairo">
                                <i class="fa fa-save"></i> Guardar Unidad
                            </button>
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
