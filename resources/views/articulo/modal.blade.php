<!-- Modal Unificado de Crear / Editar / Duplicar Artículo -->
<div class="modal fade" id="modalArticulo" tabindex="-1" role="dialog" aria-labelledby="modalArticuloTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <!-- Modal Header -->
            <div class="modal-header text-white" :style="isnew ? 'background: linear-gradient(135deg, #0a4d36 0%, #073827 100%);' : 'background: linear-gradient(135deg, #1c2430 0%, #0f172a 100%);'" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <div class="d-flex align-items-center">
                    <div class="mr-3" style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                        <i :class="isnew ? 'fa fa-plus fa-lg' : 'fa fa-pen-to-square fa-lg'"></i>
                    </div>
                    <div>
                        <h5 class="modal-title font-weight-bold mb-0" id="modalArticuloTitle">
                            @{{ isnew ? 'Nuevo Artículo' : 'Editar Artículo' }}
                        </h5>
                        <small class="text-white-50" v-if="!isnew && articulo.codigo">
                            Código interno: #@{{ articulo.codigo }}
                        </small>
                        <small class="text-white-50" v-else>
                            Completá la información del producto
                        </small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9; text-shadow: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-0">
                <!-- Navigation Tabs -->
                <style>
    body.dark-mode #modalArticulo .modal-content {
        background-color: #1f2937 !important;
        color: #f3f4f6 !important;
        border: 1px solid #374151 !important;
    }
    body.dark-mode #modalArticulo .modal-body,
    body.dark-mode #modalArticulo .modal-footer {
        background-color: #1f2937 !important;
        border-color: #374151 !important;
        color: #f3f4f6 !important;
    }
    body.dark-mode #tabArticulo {
        background-color: #111827 !important;
        border-color: #374151 !important;
    }
    body.dark-mode #tabArticulo .nav-link {
        color: #9ca3af !important;
    }
    body.dark-mode #tabArticulo .nav-link.active {
        background-color: #059669 !important;
        color: #ffffff !important;
    }
    body.dark-mode #modalArticulo .card.bg-light,
    body.dark-mode #modalArticulo .card-body.bg-light {
        background-color: #111827 !important;
        border: 1px solid #374151 !important;
    }
    body.dark-mode #modalArticulo .form-control {
        background-color: #111827 !important;
        color: #f3f4f6 !important;
        border-color: #374151 !important;
    }
    body.dark-mode #modalArticulo .form-control:focus {
        background-color: #0b1120 !important;
        color: #ffffff !important;
        border-color: #10b981 !important;
    }
    body.dark-mode #modalArticulo .input-group-text {
        background-color: #111827 !important;
        color: #9ca3af !important;
        border-color: #374151 !important;
    }
    body.dark-mode #modalArticulo .table {
        color: #f3f4f6 !important;
    }
    body.dark-mode #modalArticulo .table thead th {
        background-color: #111827 !important;
        color: #9ca3af !important;
        border-color: #374151 !important;
    }
    body.dark-mode #modalArticulo .table td {
        border-color: #374151 !important;
    }

            #tabArticulo .nav-link.active { background-color: var(--dash-primary, #0a4d36) !important; color: #ffffff !important; box-shadow: 0 2px 4px rgba(10,77,54,0.2); }
            #tabArticulo .nav-link { color: var(--dash-text-muted, #64748b); border-radius: 8px; }
        
    /* Stock Tab Modern Styles */
    .stock-form-card {
        background: #f8fafc;
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        padding: 1.15rem 1.25rem;
        margin-bottom: 1.25rem;
    }
    body.dark-mode .stock-form-card {
        background: #111827 !important;
        border-color: #374151 !important;
    }
    .stock-table-card {
        border: 1px solid var(--dash-border);
        border-radius: 12px;
        overflow: hidden;
        background: var(--dash-card-bg);
    }
    body.dark-mode .stock-table-card {
        border-color: #374151 !important;
        background: #1f2937 !important;
    }
    .stock-table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .stock-table thead th {
        background: #f1f5f9;
        color: var(--dash-text-muted);
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1px solid var(--dash-border);
        border-top: none;
        padding: 0.75rem 1rem;
        white-space: nowrap;
        vertical-align: middle;
    }
    body.dark-mode .stock-table thead th {
        background: #111827 !important;
        color: #9ca3af !important;
        border-color: #374151 !important;
    }
    .stock-table tbody td {
        padding: 0.8rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--dash-border);
        color: var(--dash-text-main);
        font-size: 0.9rem;
    }
    body.dark-mode .stock-table tbody td {
        border-color: #374151 !important;
        color: #f3f4f6 !important;
    }
    .stock-table tbody tr:hover {
        background-color: var(--dash-primary-light);
    }
    .btn-action-stock-edit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid #c8dfd5;
        background: #eaf3ef;
        color: #0a4d36;
        transition: all 0.15s;
        cursor: pointer;
    }
    .btn-action-stock-edit:hover {
        background: #0a4d36;
        color: #ffffff;
        border-color: #0a4d36;
    }
    body.dark-mode .btn-action-stock-edit {
        background: rgba(16, 185, 129, 0.15) !important;
        border-color: rgba(16, 185, 129, 0.3) !important;
        color: #34d399 !important;
    }
    body.dark-mode .btn-action-stock-edit:hover {
        background: #059669 !important;
        color: #ffffff !important;
    }
    .btn-action-stock-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #dc2626;
        transition: all 0.15s;
        cursor: pointer;
    }
    .btn-action-stock-delete:hover {
        background: #dc2626;
        color: #ffffff;
        border-color: #dc2626;
    }
    body.dark-mode .btn-action-stock-delete {
        background: rgba(239, 68, 68, 0.15) !important;
        border-color: rgba(239, 68, 68, 0.3) !important;
        color: #fca5a5 !important;
    }
    body.dark-mode .btn-action-stock-delete:hover {
        background: #dc2626 !important;
        color: #ffffff !important;
    }
</style>
        <ul class="nav nav-pills nav-fill bg-light p-2 border-bottom" id="tabArticulo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold py-2" id="tab-general-tab" data-toggle="pill" href="#tab-general" role="tab" aria-controls="tab-general" aria-selected="true">
                            <i class="fa fa-info-circle mr-1"></i> 1. General
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-2" id="tab-precios-tab" data-toggle="pill" href="#tab-precios" role="tab" aria-controls="tab-precios" aria-selected="false">
                            <i class="fa fa-tag mr-1"></i> 2. Precios & Margen
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold py-2" id="tab-stock-tab" data-toggle="pill" href="#tab-stock" role="tab" aria-controls="tab-stock" aria-selected="false">
                            <i class="fa fa-boxes-stacked mr-1"></i> 3. Stock & Depósito
                        </a>
                    </li>
                </ul>

                <div class="tab-content p-3" id="tabArticuloContent">
                    <!-- ================= TAB 1: GENERAL ================= -->
                    <div class="tab-pane fade show active" id="tab-general" role="tabpanel" aria-labelledby="tab-general-tab">
                        <div class="row">
                            <!-- Código de Barra y Generador -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-semibold text-secondary small mb-1">
                                    CÓDIGO DE BARRA
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fa fa-barcode text-muted"></i></span>
                                    </div>
                                    <input type="text"
                                           v-model="articulo.c_barra"
                                           @blur="validar_codigo_de_barra"
                                           @keyup.enter="validar_codigo_de_barra"
                                           class="form-control"
                                           name="c_barra"
                                           placeholder="Ej: 784000123456" />
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" @click="generarCodigoBarra" title="Generar código automático">
                                            <i class="fa fa-wand-magic-sparkles"></i> Auto
                                        </button>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Si se deja vacío, el sistema generará uno al guardar.</small>
                            </div>

                            <!-- Ubicación -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-semibold text-secondary small mb-1">
                                    UBICACIÓN / ESTANTE
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fa fa-location-dot text-muted"></i></span>
                                    </div>
                                    <input type="text"
                                           v-model="articulo.ubicacion"
                                           class="form-control"
                                           placeholder="Ej: Estante A-2, Pasillo 3" />
                                </div>
                            </div>

                            <!-- Descripción / Nombre del Producto -->
                            <div class="col-12 mb-3">
                                <label class="font-weight-semibold text-secondary small mb-1">
                                    DESCRIPCIÓN / NOMBRE DEL PRODUCTO <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fa fa-box text-muted"></i></span>
                                    </div>
                                    <input type="text"
                                           v-model="articulo.descripcion"
                                           ref="inputDescripcion"
                                           id="txtArticuloDescripcion"
                                           class="form-control font-weight-bold"
                                           placeholder="Ej: Coca Cola 2L Descartable"
                                           required />
                                </div>
                            </div>

                            <!-- Sección / Categoría -->
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-semibold text-secondary small mb-1">
                                    SECCIÓN / CATEGORÍA <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fa fa-folder text-muted"></i></span>
                                    </div>
                                    <select v-model="articulo.seccion" class="form-control">
                                        <option value="0" disabled>-- Seleccionar Sección --</option>
                                        @foreach($secciones as $seccion)
                                            <option value="{{ $seccion['present_cod'] }}">{{ $seccion['present_descripcion'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Unidad de Medida -->
                            <div class="col-md-4 mb-3">
                                <label class="font-weight-semibold text-secondary small mb-1">
                                    UNIDAD DE MEDIDA <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white"><i class="fa fa-scale-balanced text-muted"></i></span>
                                    </div>
                                    <select v-model="articulo.unidad" class="form-control">
                                        <option value="0" disabled>-- Seleccionar Unidad --</option>
                                        @foreach($unidades as $unidad)
                                            <option value="{{ $unidad['uni_codigo'] }}">{{ $unidad['uni_nombre'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Factor -->
                            <div class="col-md-2 mb-3">
                                <label class="font-weight-semibold text-secondary small mb-1">
                                    FACTOR
                                </label>
                                <input type="number"
                                       v-model.number="articulo.factor"
                                       class="form-control text-center"
                                       min="1"
                                       step="1"
                                       placeholder="1" />
                            </div>

                            <!-- Datos adicionales colapsables -->
                            <div class="col-12 mt-2">
                                <button class="btn btn-sm btn-link text-decoration-none text-muted p-0" type="button" data-toggle="collapse" data-target="#collapseDetallesExtra">
                                    <i class="fa fa-chevron-down mr-1"></i> Indicaciones y Modo de uso (Opcional)
                                </button>
                                <div class="collapse mt-2" id="collapseDetallesExtra">
                                    <div class="card card-body bg-light border-0 py-2">
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label class="small text-muted mb-1 font-weight-bold">Indicaciones / Observaciones</label>
                                                <textarea v-model="articulo.indicaciones" class="form-control form-control-sm" rows="2" placeholder="Indicaciones del artículo..."></textarea>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label class="small text-muted mb-1 font-weight-bold">Modo de Uso / Posología</label>
                                                <textarea v-model="articulo.modouso" class="form-control form-control-sm" rows="2" placeholder="Dosis o modo de uso..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= TAB 2: PRECIOS & MÁRGENES ================= -->
                    <div class="tab-pane fade" id="tab-precios" role="tabpanel" aria-labelledby="tab-precios-tab">
                        <!-- Costo de Compra -->
                        <div class="card border-0 bg-light shadow-sm p-3 mb-3">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <label class="font-weight-bold text-dark mb-1">
                                        <i class="fa fa-cart-shopping text-primary mr-1"></i> PRECIO DE COMPRA (COSTO) <span class="text-danger">*</span>
                                    </label>
                                    <div class="small text-muted mb-2 mb-md-0">Base para calcular los márgenes y precios de venta</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group input-group-lg">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text font-weight-bold bg-white text-primary">Gs.</span>
                                        </div>
                                        <in-number v-model="articulo.costo"
                                                   placeholder="0"
                                                   @change="setPrecioVenta"
                                                   class="form-control text-right font-weight-bold"
                                                   style="font-size: 1.25rem;"></in-number>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Precio Principal (P1) -->
                        <div class="card mb-3 shadow-sm" style="border: 2px solid var(--dash-primary, #0a4d36); border-radius: 10px;">
                            <div class="card-header text-white py-2 d-flex justify-content-between align-items-center" style="background: var(--dash-primary, #0a4d36); border-radius: 8px 8px 0 0;">
                                <span class="font-weight-bold"><i class="fa fa-star mr-1"></i> PRECIO PRINCIPAL (CONTADO / LISTA 1)</span>
                                <span class="badge badge-light font-weight-bold" style="color: var(--dash-primary, #0a4d36);">Predeterminado</span>
                            </div>
                            <div class="card-body py-3">
                                <div class="row align-items-center">
                                    <div class="col-md-4 mb-2 mb-md-0">
                                        <label class="small text-muted font-weight-bold mb-1">MARGEN UTILIDAD %</label>
                                        <div class="input-group">
                                            <input type="number"
                                                   v-model.number="articulo.m1"
                                                   @keyup="setUtilPrecio('M', 1)"
                                                   @change="setUtilPrecio('M', 1)"
                                                   class="form-control text-center font-weight-bold"
                                                   placeholder="Margen %" />
                                            <div class="input-group-append">
                                                <span class="input-group-text bg-light font-weight-bold">%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="small text-muted font-weight-bold mb-1">PRECIO DE VENTA FINAL 1 <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text font-weight-bold bg-white text-success">Gs.</span>
                                            </div>
                                            <in-number v-model="articulo.p1"
                                                       placeholder="0"
                                                       @change="setUtilPrecio('P', 1)"
                                                       class="form-control text-right font-weight-bold text-success"
                                                       style="font-size: 1.3rem;"></in-number>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Precios 2 a 5 Plegables -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <button class="btn btn-outline-secondary btn-sm" type="button" @click="toggleMasPrecios = !toggleMasPrecios">
                                    <i :class="toggleMasPrecios ? 'fa fa-eye-slash mr-1' : 'fa fa-layer-group mr-1'"></i>
                                    @{{ toggleMasPrecios ? 'Ocultar Precios Adicionales (2 al 5)' : 'Configurar Precios 2 al 5 (Mayorista, etc.)' }}
                                </button>
                                <button class="btn btn-outline-info btn-sm" type="button" @click="mostrarPrecios">
                                    <i class="fa fa-credit-card mr-1"></i> Precios a Crédito / Cuotas
                                </button>
                            </div>

                            <div v-show="toggleMasPrecios" class="card border-0 bg-light p-3">
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless mb-0">
                                        <thead>
                                            <tr class="text-muted small">
                                                <th style="width: 25%;">Lista de Precio</th>
                                                <th style="width: 30%;">Margen %</th>
                                                <th style="width: 45%;">Precio Venta (Gs.)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="i in [2, 3, 4, 5]" :key="'p-' + i">
                                                <td class="align-middle font-weight-bold text-secondary">
                                                    Precio @{{ i }}
                                                </td>
                                                <td class="align-middle">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number"
                                                               v-model.number="articulo['m' + i]"
                                                               @keyup="setUtilPrecio('M', i)"
                                                               @change="setUtilPrecio('M', i)"
                                                               class="form-control text-center"
                                                               placeholder="0" />
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">%</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <in-number v-model="articulo['p' + i]"
                                                               @change="setUtilPrecio('P', i)"
                                                               class="form-control form-control-sm text-right font-weight-bold"
                                                               placeholder="0"></in-number>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= TAB 3: STOCK & DEPÓSITO ================= -->
                    <div class="tab-pane fade" id="tab-stock" role="tabpanel" aria-labelledby="tab-stock-tab">
                        <!-- Cabecera Informativa con Total KPI -->
                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 p-2 px-3 rounded-lg" style="background: var(--dash-primary-light); border: 1px solid var(--dash-primary-border); border-radius: 10px;">
                            <div class="d-flex align-items-center">
                                <div class="mr-2" style="width: 36px; height: 36px; border-radius: 8px; background: var(--dash-primary); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                                    <i class="fa-solid fa-boxes-stacked"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-0" style="color: var(--dash-primary);">Control de Stock por Sucursales</h6>
                                    <small class="text-muted">Asigná cantidades disponibles, lotes y fechas de vencimiento</small>
                                </div>
                            </div>
                            <div class="mt-2 mt-sm-0">
                                <span class="badge-stock-in" style="font-size: 0.9rem; padding: 5px 12px;">
                                    <i class="fa-solid fa-circle-check mr-1"></i>
                                    Stock Total: <strong class="ml-1 font-cairo">@{{ totalStock }}</strong> unid.
                                </span>
                            </div>
                        </div>

                        <!-- Formulario de entrada de stock para sucursales -->
                        <div class="stock-form-card">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <i :class="bandstock == 1 ? 'fa-solid fa-pen-to-square text-warning mr-2' : 'fa-solid fa-circle-plus text-success mr-2'"></i>
                                    <span class="font-weight-bold" style="color: var(--dash-text-main); font-size: 0.95rem;">
                                        @{{ bandstock == 1 ? 'Modificar Registro de Stock' : 'Asignar Stock a Sucursal' }}
                                    </span>
                                </div>
                                <span class="badge badge-warning py-1 px-2" v-if="bandstock == 1">
                                    <i class="fa fa-pen mr-1"></i> Editando Registro
                                </span>
                            </div>

                            <div class="row">
                                <!-- Sucursal -->
                                <div class="col-md-4 col-sm-6 mb-2">
                                    <label class="small text-muted font-weight-bold mb-1">
                                        <i class="fa-solid fa-store mr-1 text-muted"></i> Sucursal
                                    </label>
                                    <select v-model="stock.sucursal" class="form-control select-seccion" style="border-radius: 8px; font-size: 0.9rem;">
                                        <template v-for="suc in sucursales">
                                            <option :value="suc.suc_cod">@{{ suc.suc_desc }}</option>
                                        </template>
                                    </select>
                                </div>

                                <!-- Cantidad Stock -->
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <label class="small text-muted font-weight-bold mb-1">
                                        <i class="fa-solid fa-cubes mr-1 text-muted"></i> Cantidad Stock
                                    </label>
                                    <input type="number"
                                           v-model.number="stock.cantidad"
                                           onfocus="this.select()"
                                           class="form-control font-weight-bold text-center"
                                           min="0"
                                           placeholder="0"
                                           style="border-radius: 8px; font-size: 0.95rem;" />
                                </div>

                                <!-- Lote -->
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <label class="small text-muted font-weight-bold mb-1">
                                        <i class="fa-solid fa-barcode mr-1 text-muted"></i> Lote
                                    </label>
                                    <input type="text"
                                           v-model="stock.lotenew"
                                           class="form-control"
                                           placeholder="Ej: LOTE-01 o S/N"
                                           style="border-radius: 8px; font-size: 0.9rem;" />
                                </div>

                                <!-- Vencimiento -->
                                <div class="col-md-2 col-sm-6 mb-2">
                                    <label class="small text-muted font-weight-bold mb-1">
                                        <i class="fa-solid fa-calendar mr-1 text-muted"></i> Vencimiento
                                    </label>
                                    <input type="date"
                                           v-model="stock.vencimiento"
                                           class="form-control"
                                           style="border-radius: 8px; font-size: 0.9rem;" />
                                </div>
                            </div>

                            <div class="d-flex justify-content-end align-items-center mt-3 pt-2 border-top">
                                <button v-if="bandstock == 1" class="btn-pos-secondary mr-2" type="button" @click="limpiarCamposStock">
                                    <i class="fa fa-times mr-1"></i> Cancelar Edición
                                </button>
                                <button v-if="bandstock == 0" class="btn-pos-primary" type="button" @click="addStock">
                                    <i class="fa fa-plus-circle mr-1"></i> Asignar Stock
                                </button>
                                <button v-else class="btn-pos-primary" type="button" @click="updateStockA">
                                    <i class="fa fa-check mr-1"></i> Guardar Cambios
                                </button>
                            </div>
                        </div>

                        <!-- Tabla de stocks asignados -->
                        <div class="stock-table-card">
                            <div class="table-responsive">
                                <table class="table stock-table mb-0">
                                    <thead>
                                        <tr>
                                            <th><i class="fa-solid fa-store mr-1 text-muted"></i> Sucursal</th>
                                            <th class="text-center" style="width: 140px;"><i class="fa-solid fa-cubes mr-1 text-muted"></i> Cantidad</th>
                                            <th style="width: 170px;"><i class="fa-solid fa-barcode mr-1 text-muted"></i> Lote</th>
                                            <th style="width: 190px;"><i class="fa-solid fa-calendar mr-1 text-muted"></i> Vencimiento</th>
                                            <th class="text-center" style="width: 110px;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="!stocks || stocks.length === 0">
                                            <td colspan="5" class="text-center py-4">
                                                <div class="text-muted mb-2" style="font-size: 1.6rem;">
                                                    <i class="fa-solid fa-boxes-stacked"></i>
                                                </div>
                                                <div class="font-weight-bold" style="color: var(--dash-text-main);">Sin stock asignado a sucursales</div>
                                                <div class="small text-muted">Completá los campos superiores para asignar cantidades a cada local (se guardará con 0 si no se asigna).</div>
                                            </td>
                                        </tr>
                                        <tr v-for="s in stocks" :key="s.id">
                                            <td class="align-middle">
                                                <div class="font-weight-bold" style="color: var(--dash-text-main);">
                                                    <i class="fa-solid fa-shop text-muted mr-1"></i>@{{ getByIdSucursal(s.sucursal) }}
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span v-if="s.cantidad <= 0" class="badge-stock-out">
                                                    <i class="fa-solid fa-circle-xmark mr-1"></i>0 Agotado
                                                </span>
                                                <span v-else-if="s.cantidad <= 5" class="badge-stock-low">
                                                    <i class="fa-solid fa-triangle-exclamation mr-1"></i>@{{ s.cantidad }} Bajo
                                                </span>
                                                <span v-else class="badge-stock-in">
                                                    <i class="fa-solid fa-circle-check mr-1"></i>@{{ s.cantidad }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge-barcode">
                                                    <i class="fa fa-barcode mr-1 text-muted"></i>@{{ s.lotenew || 'S/N' }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <span v-if="s.vencimiento && s.vencimiento !== 'Sin vencimiento'" class="small text-secondary font-weight-semibold">
                                                    <i class="fa-regular fa-calendar-check text-success mr-1"></i>@{{ s.vencimiento }}
                                                </span>
                                                <span v-else class="text-muted small">
                                                    <i class="fa-solid fa-infinity mr-1 text-muted"></i>Sin vencimiento
                                                </span>
                                            </td>
                                            <td class="align-middle text-center text-nowrap">
                                                <button type="button" class="btn-action-stock-edit mr-1" @click="editStockA(s)" title="Editar este registro">
                                                    <i class="fa fa-pen"></i>
                                                </button>
                                                <button type="button" class="btn-action-stock-delete" @click="delStockA(s.id)" title="Eliminar este registro">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Barra de Resumen en Pie de Tabla -->
                            <div v-if="stocks && stocks.length > 0" class="d-flex align-items-center justify-content-between p-2 px-3 border-top" style="background: var(--dash-card-bg);">
                                <span class="text-muted small">
                                    <i class="fa-solid fa-layer-group mr-1 text-muted"></i>
                                    <strong>@{{ stocks.length }}</strong> sucursal(es) registrada(s)
                                </span>
                                <span class="font-weight-bold font-cairo" style="color: var(--dash-primary); font-size: 1rem;">
                                    Total en Stock: <span class="badge-stock-in ml-1" style="font-size: 0.92rem;">@{{ totalStock }} unid.</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-light d-flex justify-content-between align-items-center py-2">
                <div>
                    <!-- Enlace directo para abrir ABM Completo si se requiere cargar fotos o gestión avanzada -->
                    <a v-if="!isnew && articulo.codigo"
                       :href="'{{ url('articulo/cm') }}/' + articulo.codigo"
                       class="btn btn-link btn-sm text-secondary p-0"
                       title="Abrir formulario completo con fotos y cámara">
                        <i class="fa fa-arrow-up-right-from-square mr-1"></i> Abrir en ABM Avanzado con fotos
                    </a>
                    <a v-else
                       href="{{ route('articulo.cm') }}"
                       class="btn btn-link btn-sm text-secondary p-0"
                       title="Abrir formulario completo con fotos y cámara">
                        <i class="fa fa-arrow-up-right-from-square mr-1"></i> Abrir en ABM Avanzado con fotos
                    </a>
                </div>
                <div>
                    <button type="button" class="btn-pos-secondary px-3 mr-2" data-dismiss="modal">
                        <i class="fa fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="button"
                            class="btn-pos-primary px-4"
                            :disabled="saving"
                            @click="saveArticulo">
                        <span v-if="saving">
                            <i class="fa fa-spinner fa-spin mr-1"></i> Guardando...
                        </span>
                        <span v-else>
                            <i class="fa fa-check mr-1"></i> @{{ isnew ? 'Guardar Artículo' : 'Actualizar Cambios' }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
