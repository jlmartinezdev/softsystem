<!-- Modal Unificado de Crear / Editar / Duplicar Artículo (estilos en articulo/modal_style.blade.php) -->
<div class="modal fade" id="modalArticulo" tabindex="-1" role="dialog" aria-labelledby="modalArticuloTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content border-0 shadow-lg">
            <!-- Encabezado -->
            <div class="modal-header am-header">
                <div class="d-flex align-items-center" style="min-width: 0;">
                    <div class="am-header-icon mr-3" :class="{ 'is-edit': !isnew }">
                        <i :class="isnew ? 'fa fa-plus' : 'fa fa-pen-to-square'"></i>
                    </div>
                    <div style="min-width: 0;">
                        <h5 class="modal-title am-title mb-0" id="modalArticuloTitle">
                            @{{ isnew ? (duplicando ? 'Duplicar artículo' : 'Nuevo artículo') : 'Editar artículo' }}
                        </h5>
                        <span class="am-subtitle" v-if="!isnew && articulo.codigo">
                            <span class="am-code">#@{{ articulo.codigo }}</span>@{{ articulo.descripcion }}
                        </span>
                        <span class="am-subtitle" v-else-if="duplicando">Revisá los datos de la copia antes de guardar</span>
                        <span class="am-subtitle" v-else>Completá los datos básicos y el precio de venta</span>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Pestañas -->
            <ul class="nav" id="tabArticulo" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab-general-tab" data-toggle="pill" href="#tab-general" role="tab" aria-controls="tab-general" aria-selected="true">
                        <i class="fa fa-circle-info"></i> General
                        <span class="am-tab-alert" v-if="intentoGuardar && !articulo.descripcion" title="Falta la descripción"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-precios-tab" data-toggle="pill" href="#tab-precios" role="tab" aria-controls="tab-precios" aria-selected="false">
                        <i class="fa fa-tag"></i> Precios
                        <span class="am-tab-alert" v-if="intentoGuardar && !articulo.p1" title="Falta el precio de venta"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-stock-tab" data-toggle="pill" href="#tab-stock" role="tab" aria-controls="tab-stock" aria-selected="false">
                        <i class="fa fa-boxes-stacked"></i> Stock
                        <span class="am-tab-count" v-if="stocks && stocks.length">@{{ totalStock }}</span>
                    </a>
                </li>
            </ul>

            <!-- Cuerpo -->
            <div class="modal-body p-0">
                <div class="tab-content" id="tabArticuloContent">
                    <!-- ================= GENERAL ================= -->
                    <div class="tab-pane fade show active" id="tab-general" role="tabpanel" aria-labelledby="tab-general-tab">
                        <div class="row">
                            <!-- Descripción -->
                            <div class="col-12 mb-3">
                                <label class="am-label" for="txtArticuloDescripcion">Descripción del producto<span class="am-req">*</span></label>
                                <input type="text"
                                       v-model="articulo.descripcion"
                                       ref="inputDescripcion"
                                       id="txtArticuloDescripcion"
                                       class="form-control am-input-main"
                                       :class="{ 'is-invalid': intentoGuardar && !articulo.descripcion }"
                                       placeholder="Ej: Coca Cola 2L Descartable"
                                       required />
                                <div class="am-invalid-msg" v-if="intentoGuardar && !articulo.descripcion">Ingresá el nombre del producto.</div>
                            </div>

                            <!-- Código de barra -->
                            <div class="col-md-7 mb-3">
                                <label class="am-label" for="txtArticuloCBarra">Código de barra</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-barcode"></i></span>
                                    </div>
                                    <input type="text"
                                           id="txtArticuloCBarra"
                                           v-model="articulo.c_barra"
                                           @blur="validar_codigo_de_barra"
                                           @keyup.enter="validar_codigo_de_barra"
                                           class="form-control"
                                           name="c_barra"
                                           placeholder="Escaneá o escribí el código" />
                                    <div class="input-group-append">
                                        <button class="btn btn-am-ghost" type="button" @click="generarCodigoBarra" title="Generar un código automático">
                                            <i class="fa fa-wand-magic-sparkles mr-1"></i>Generar
                                        </button>
                                    </div>
                                </div>
                                <div class="am-help">Si lo dejás vacío, se genera uno al guardar.</div>
                            </div>

                            <!-- Ubicación -->
                            <div class="col-md-5 mb-3">
                                <label class="am-label" for="txtArticuloUbicacion">Ubicación</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-location-dot"></i></span>
                                    </div>
                                    <input type="text"
                                           id="txtArticuloUbicacion"
                                           v-model="articulo.ubicacion"
                                           class="form-control"
                                           placeholder="Ej: Estante A-2" />
                                </div>
                            </div>
                        </div>

                        <div class="am-section-title">Clasificación</div>
                        <div class="row">
                            <!-- Sección -->
                            <div class="col-md-5 mb-3">
                                <label class="am-label" for="selArticuloSeccion">Sección / categoría<span class="am-req">*</span></label>
                                <select id="selArticuloSeccion" v-model="articulo.seccion" class="form-control">
                                    <option value="0" disabled>Seleccionar sección</option>
                                    @foreach($secciones as $seccion)
                                        <option value="{{ $seccion['present_cod'] }}">{{ $seccion['present_descripcion'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Unidad -->
                            <div class="col-md-4 col-8 mb-3">
                                <label class="am-label" for="selArticuloUnidad">Unidad de medida<span class="am-req">*</span></label>
                                <select id="selArticuloUnidad" v-model="articulo.unidad" class="form-control">
                                    <option value="0" disabled>Seleccionar unidad</option>
                                    @foreach($unidades as $unidad)
                                        <option value="{{ $unidad['uni_codigo'] }}">{{ $unidad['uni_nombre'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Factor -->
                            <div class="col-md-3 col-4 mb-3">
                                <label class="am-label" for="txtArticuloFactor" title="Cantidad de unidades que contiene (ej: caja de 12)">
                                    Factor <i class="fa fa-circle-question text-muted small"></i>
                                </label>
                                <input type="number"
                                       id="txtArticuloFactor"
                                       v-model.number="articulo.factor"
                                       class="form-control text-center"
                                       min="1"
                                       step="1"
                                       placeholder="1" />
                            </div>
                        </div>

                        <!-- Habilitado -->
                        <div class="am-toggle-card mb-3">
                            <div style="min-width: 0;">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           id="articuloVisibleBuscador"
                                           v-model="articulo.activo"
                                           :true-value="1"
                                           :false-value="0">
                                    <label class="custom-control-label" for="articuloVisibleBuscador">Artículo habilitado</label>
                                </div>
                                <div class="am-help ml-0">Si lo apagás, no aparece en Artículos ni en el buscador de ventas, compras y presupuestos.</div>
                            </div>
                            <span class="am-pill" :class="Number(articulo.activo) === 0 ? 'am-pill-off' : 'am-pill-ok'">
                                @{{ Number(articulo.activo) === 0 ? 'Oculto' : 'Visible' }}
                            </span>
                        </div>

                        <!-- Datos adicionales -->
                        <button class="btn btn-link am-collapse-btn" type="button" data-toggle="collapse" data-target="#collapseDetallesExtra" aria-expanded="false" aria-controls="collapseDetallesExtra">
                            <i class="fa fa-chevron-down mr-1"></i> Indicaciones y modo de uso
                            <span class="font-weight-normal">(opcional)</span>
                        </button>
                        <div class="collapse mt-2" id="collapseDetallesExtra">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="am-label">Indicaciones / observaciones</label>
                                    <textarea v-model="articulo.indicaciones" class="form-control form-control-sm" rows="2" placeholder="Indicaciones del artículo..."></textarea>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="am-label">Modo de uso / posología</label>
                                    <textarea v-model="articulo.modouso" class="form-control form-control-sm" rows="2" placeholder="Dosis o modo de uso..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= PRECIOS ================= -->
                    <div class="tab-pane fade" id="tab-precios" role="tabpanel" aria-labelledby="tab-precios-tab">
                        <!-- Costo -->
                        <div class="am-price-card is-cost">
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <div class="am-price-title"><i class="fa fa-cart-shopping mr-1 text-muted"></i> Precio de compra (costo)<span class="am-req text-danger">*</span></div>
                                    <div class="am-help mt-0">Base para calcular márgenes y precios de venta</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text font-weight-bold">Gs.</span>
                                        </div>
                                        <in-number v-model="articulo.costo"
                                                   placeholder="0"
                                                   @change="setPrecioVenta"
                                                   class="form-control am-price-input"></in-number>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Precio principal -->
                        <div class="am-price-card is-main">
                            <div class="am-price-head">
                                <span class="am-price-title"><i class="fa fa-star mr-1" style="color: var(--am-primary);"></i> Precio de venta principal</span>
                                <span class="am-badge-main">Lista 1 · Contado</span>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-5 mb-2 mb-md-0">
                                    <label class="am-label" for="txtArticuloM1">Margen</label>
                                    <div class="input-group">
                                        <input type="number"
                                               id="txtArticuloM1"
                                               v-model.number="articulo.m1"
                                               @keyup="setUtilPrecio('M', 1)"
                                               @change="setUtilPrecio('M', 1)"
                                               class="form-control text-center font-weight-bold"
                                               placeholder="0" />
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 col-7 mb-2 mb-md-0">
                                    <label class="am-label">Precio de venta<span class="am-req">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text font-weight-bold">Gs.</span>
                                        </div>
                                        <in-number v-model="articulo.p1"
                                                   placeholder="0"
                                                   @change="setUtilPrecio('P', 1)"
                                                   :class="{ 'is-invalid': intentoGuardar && !articulo.p1 }"
                                                   class="form-control am-price-input"
                                                   style="color: var(--am-primary);"></in-number>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <label class="am-label d-none d-md-block">&nbsp;</label>
                                    <div class="am-profit" :class="gananciaClase(1)">
                                        <span class="am-profit-label">@{{ gananciaPrecio(1) < 0 ? 'Pérdida por unidad' : 'Ganancia por unidad' }}</span>
                                        <span class="am-profit-value">@{{ articulo.p1 > 0 && articulo.costo > 0 ? 'Gs. ' + separador(Math.abs(gananciaPrecio(1))) : '—' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="am-margin-chips" aria-label="Márgenes rápidos">
                                <span class="am-help mt-0 mr-1">Margen rápido:</span>
                                <button type="button" v-for="m in [20, 30, 40, 50, 100]" :key="'m' + m"
                                        class="am-chip" :class="{ active: Number(articulo.m1) === m }"
                                        @click="articulo.m1 = m; setUtilPrecio('M', 1)">@{{ m }}%</button>
                            </div>
                            <div class="am-invalid-msg" v-if="intentoGuardar && !articulo.p1">Ingresá el precio de venta.</div>
                        </div>

                        <!-- Otras listas -->
                        <div class="d-flex justify-content-between align-items-center mb-2 precios-extra-acciones">
                            <button class="btn btn-am-ghost btn-sm" type="button" @click="toggleMasPrecios = !toggleMasPrecios" :aria-expanded="toggleMasPrecios ? 'true' : 'false'">
                                <i class="fa mr-1" :class="toggleMasPrecios ? 'fa-chevron-up' : 'fa-layer-group'"></i>
                                @{{ toggleMasPrecios ? 'Ocultar listas 2 a 5' : 'Listas de precio 2 a 5 (mayorista, etc.)' }}
                            </button>
                            <button class="btn btn-am-ghost btn-sm" type="button" @click="mostrarPrecios">
                                <i class="fa fa-credit-card mr-1"></i> Precios a crédito / cuotas
                            </button>
                        </div>

                        <div v-show="toggleMasPrecios" class="am-lists">
                            <div class="am-list-row is-head">
                                <span>Lista</span>
                                <span>Margen</span>
                                <span>Precio de venta</span>
                                <span class="text-right">Ganancia</span>
                            </div>
                            <div class="am-list-row" v-for="i in [2, 3, 4, 5]" :key="'p-' + i">
                                <span class="am-list-name">Lista @{{ i }}</span>
                                <div class="input-group input-group-sm">
                                    <input type="number"
                                           v-model.number="articulo['m' + i]"
                                           @keyup="setUtilPrecio('M', i)"
                                           @change="setUtilPrecio('M', i)"
                                           class="form-control text-center"
                                           :aria-label="'Margen lista ' + i"
                                           placeholder="0" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Gs.</span>
                                    </div>
                                    <in-number v-model="articulo['p' + i]"
                                               @change="setUtilPrecio('P', i)"
                                               class="form-control form-control-sm text-right font-weight-bold"
                                               placeholder="0"></in-number>
                                </div>
                                <span class="am-list-profit" :class="gananciaClase(i)">
                                    @{{ articulo['p' + i] > 0 && articulo.costo > 0 ? (gananciaPrecio(i) < 0 ? '−' : '+') + ' Gs. ' + separador(Math.abs(gananciaPrecio(i))) : '—' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- ================= STOCK ================= -->
                    <div class="tab-pane fade" id="tab-stock" role="tabpanel" aria-labelledby="tab-stock-tab">
                        <div class="am-stock-summary">
                            <div class="am-stock-kpi">
                                <strong>@{{ totalStock || 0 }}</strong>
                                <span>unidades en @{{ stocks ? stocks.length : 0 }} @{{ stocks && stocks.length === 1 ? 'sucursal' : 'sucursales' }}</span>
                            </div>
                            <button type="button" class="btn btn-am-ghost btn-sm btn-crear-stock" @click="abrirNuevoStock" v-if="!formStockNuevo">
                                <i class="fa fa-plus mr-1"></i> Agregar stock
                            </button>
                        </div>

                        <!-- Formulario de nuevo stock -->
                        <div class="stock-form-card" v-if="formStockNuevo">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fa-solid fa-circle-plus mr-2" style="color: var(--am-primary);"></i>
                                <span class="font-weight-bold" style="font-size: 0.9rem;">Nuevo stock</span>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-sm-6 mb-2">
                                    <label class="am-label">Sucursal</label>
                                    <select v-model="stock.sucursal" class="form-control form-control-sm select-seccion">
                                        <option v-for="suc in sucursales" :key="'n-' + suc.suc_cod" :value="suc.suc_cod">@{{ suc.suc_desc }}</option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-6 mb-2">
                                    <label class="am-label">Cantidad</label>
                                    <input type="number"
                                           v-model.number="stock.cantidad"
                                           onfocus="this.select()"
                                           class="form-control form-control-sm font-weight-bold text-center"
                                           min="0"
                                           placeholder="0" />
                                </div>
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <label class="am-label">Lote</label>
                                    <input type="text"
                                           v-model="stock.lotenew"
                                           class="form-control form-control-sm"
                                           placeholder="Ej: LOTE-01 o S/N" />
                                </div>
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <label class="am-label">Vencimiento</label>
                                    <input type="date"
                                           v-model="stock.vencimiento"
                                           class="form-control form-control-sm" />
                                </div>
                            </div>
                            <div class="d-flex justify-content-end align-items-center mt-1">
                                <button class="btn-pos-secondary mr-2" type="button" @click="limpiarCamposStock">
                                    Cancelar
                                </button>
                                <button class="btn-pos-primary" type="button" @click="addStock">
                                    <i class="fa fa-check"></i> Agregar
                                </button>
                            </div>
                        </div>

                        <!-- Stock actual -->
                        <div class="stock-table-card">
                            <div class="table-responsive">
                                <table class="table stock-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Sucursal</th>
                                            <th class="text-center" style="width: 130px;">Cantidad</th>
                                            <th style="width: 160px;">Lote</th>
                                            <th style="width: 170px;">Vencimiento</th>
                                            <th class="text-center" style="width: 90px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="!stocks || stocks.length === 0">
                                            <td colspan="5" class="text-center py-4 stock-empty">
                                                <div class="text-muted mb-2" style="font-size: 1.6rem;">
                                                    <i class="fa-solid fa-boxes-stacked"></i>
                                                </div>
                                                <div class="font-weight-bold" style="color: var(--dash-text-main);">Sin stock cargado</div>
                                                <div class="small text-muted">Usá “Agregar stock” para cargar la cantidad inicial.</div>
                                            </td>
                                        </tr>
                                        <tr v-for="s in stocks" :key="s.id" :class="{ 'stock-row-edit': bandstock == 1 && stock.id == s.id }">
                                            <td class="align-middle" data-label="Sucursal">
                                                <select v-if="bandstock == 1 && stock.id == s.id" v-model="stock.sucursal" class="form-control form-control-sm">
                                                    <option v-for="suc in sucursales" :key="suc.suc_cod" :value="suc.suc_cod">@{{ suc.suc_desc }}</option>
                                                </select>
                                                <div v-else class="font-weight-bold" style="color: var(--dash-text-main);">
                                                    <i class="fa-solid fa-shop text-muted mr-1"></i>@{{ getByIdSucursal(s.sucursal) }}
                                                </div>
                                            </td>
                                            <td class="align-middle text-center" data-label="Cantidad">
                                                <input v-if="bandstock == 1 && stock.id == s.id"
                                                       type="number"
                                                       v-model.number="stock.cantidad"
                                                       onfocus="this.select()"
                                                       class="form-control form-control-sm font-weight-bold text-center"
                                                       placeholder="0"
                                                       style="max-width: 110px; margin: 0 auto;">
                                                <template v-else>
                                                    <span v-if="s.cantidad <= 0" class="badge-stock-out">
                                                        <i class="fa-solid fa-circle-xmark mr-1"></i>0 Agotado
                                                    </span>
                                                    <span v-else-if="s.cantidad <= 5" class="badge-stock-low">
                                                        <i class="fa-solid fa-triangle-exclamation mr-1"></i>@{{ s.cantidad }} Bajo
                                                    </span>
                                                    <span v-else class="badge-stock-in">
                                                        <i class="fa-solid fa-circle-check mr-1"></i>@{{ s.cantidad }}
                                                    </span>
                                                </template>
                                            </td>
                                            <td class="align-middle" data-label="Lote">
                                                <input v-if="bandstock == 1 && stock.id == s.id"
                                                       type="text"
                                                       v-model="stock.lotenew"
                                                       class="form-control form-control-sm"
                                                       placeholder="S/N">
                                                <span v-else class="badge-barcode">
                                                    @{{ s.lotenew || 'S/N' }}
                                                </span>
                                            </td>
                                            <td class="align-middle" data-label="Vencimiento">
                                                <input v-if="bandstock == 1 && stock.id == s.id"
                                                       type="date"
                                                       v-model="stock.vencimiento"
                                                       class="form-control form-control-sm">
                                                <template v-else>
                                                    <span v-if="s.vencimiento && s.vencimiento !== 'Sin vencimiento' && String(s.vencimiento).substring(0, 10) !== '2030-01-01'" class="small font-weight-semibold">
                                                        <i class="fa-regular fa-calendar-check text-success mr-1"></i>@{{ String(s.vencimiento).substring(0, 10) }}
                                                    </span>
                                                    <span v-else class="text-muted small">
                                                        <i class="fa-solid fa-infinity mr-1"></i>Sin vencimiento
                                                    </span>
                                                </template>
                                            </td>
                                            <td class="align-middle text-center text-nowrap" data-label="Acciones">
                                                <template v-if="bandstock == 1 && stock.id == s.id">
                                                    <button type="button" class="btn-action-stock-edit mr-1" @click="updateStockA" title="Guardar este stock">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn-action-stock-edit" @click="limpiarCamposStock" title="Cancelar">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </template>
                                                <template v-else>
                                                    <button type="button" class="btn-action-stock-edit mr-1" @click="editStockA(s)" title="Editar este stock">
                                                        <i class="fa fa-pen"></i>
                                                    </button>
                                                    <button type="button" class="btn-action-stock-delete" @click="delStockA(s.id)" title="Eliminar este registro">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </template>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie -->
            <div class="modal-footer am-footer d-flex justify-content-between align-items-center">
                <div class="articulo-modal-extra">
                    <a v-if="!isnew && articulo.codigo"
                       :href="'{{ url('articulo/cm') }}/' + articulo.codigo"
                       class="btn btn-link btn-sm p-0"
                       title="Abrir el formulario completo con fotos y cámara">
                        <i class="fa fa-arrow-up-right-from-square mr-1"></i> Formulario avanzado (fotos)
                    </a>
                    <a v-else
                       href="{{ route('articulo.cm') }}"
                       class="btn btn-link btn-sm p-0"
                       title="Abrir el formulario completo con fotos y cámara">
                        <i class="fa fa-arrow-up-right-from-square mr-1"></i> Formulario avanzado (fotos)
                    </a>
                </div>
                <div class="articulo-modal-acciones">
                    <button type="button" class="btn-pos-secondary px-3 mr-2" data-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button"
                            class="btn-pos-primary px-4"
                            :disabled="saving"
                            @click="saveArticulo">
                        <span v-if="saving">
                            <i class="fa fa-spinner fa-spin mr-1"></i> Guardando...
                        </span>
                        <span v-else>
                            <i class="fa fa-check mr-1"></i> @{{ isnew ? 'Guardar artículo' : 'Guardar cambios' }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
