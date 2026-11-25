<!-- Modal Precios a Crédito (estilos en articulo/modal_style.blade.php) -->
<div class="modal fade" id="precioArticulo" tabindex="-1" role="dialog" aria-labelledby="precioArticuloTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content border-0 shadow-lg">
            <!-- Encabezado -->
            <div class="modal-header am-header">
                <div class="d-flex align-items-center" style="min-width: 0;">
                    <div class="am-header-icon mr-3">
                        <i class="fa fa-credit-card"></i>
                    </div>
                    <div style="min-width: 0;">
                        <h5 class="modal-title am-title mb-0" id="precioArticuloTitle">Precios a crédito</h5>
                        <span class="am-subtitle">
                            <template v-if="articulo.descripcion && !viewPrecio">@{{ articulo.descripcion }} · </template>Costo <strong>Gs. @{{ separador(articulo.costo || 0) }}</strong>
                            <template v-if="!viewPrecio && articulo.p1 > 0"> · Contado <strong>Gs. @{{ separador(articulo.p1) }}</strong></template>
                        </span>
                    </div>
                </div>
                <button type="button" class="close" @click="cerrarPrecios" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Cuerpo -->
            <div class="modal-body p-0">
                <div class="pc-toolbar">
                    <div class="am-help mt-0">
                        <i class="fa fa-circle-info mr-1"></i>
                        <template v-if="viewPrecio">Vista de consulta. Para cambiarlos, editá el artículo.</template>
                        <template v-else>Cargá el margen o el precio total; la cuota se calcula sola.</template>
                    </div>
                    <div class="pc-switches" v-if="!viewPrecio">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="checkRedondearPrecio" v-model="chprecio" name="precio" @change="recalcularCredito">
                            <label class="custom-control-label" for="checkRedondearPrecio">Redondear precios</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="checkRedondearCuota" v-model="chcuota" name="cuota" @change="recalcularCredito">
                            <label class="custom-control-label" for="checkRedondearCuota">Redondear cuotas</label>
                        </div>
                    </div>
                </div>

                <fieldset :disabled="viewPrecio" class="pc-table">
                    <div class="pc-row is-head">
                        <span>Plan</span>
                        <span>Margen</span>
                        <span>Precio total</span>
                        <span>Valor de cuota</span>
                    </div>
                    <div class="pc-row" v-for="(pr, idx) in precios" :key="'pc-' + idx" :class="{ 'is-empty': !(Number(pr.p) > 0) }">
                        <span class="pc-plan">
                            <span class="pc-plan-num">@{{ idx + 2 }}</span>
                            <span class="pc-plan-label">cuotas</span>
                        </span>
                        <div class="input-group input-group-sm">
                            <input class="form-control text-center"
                                   v-model="pr.m"
                                   type="number"
                                   min="0"
                                   :aria-label="'Margen para ' + (idx + 2) + ' cuotas'"
                                   v-on:keyup="setPrecio(idx); setCuota(idx)"
                                   placeholder="0">
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Gs.</span>
                            </div>
                            <in-number v-model="pr.p" placeholder="0" @change="setMargen(idx)"
                                       class="form-control form-control-sm text-right font-weight-bold"></in-number>
                        </div>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text pc-times">@{{ idx + 2 }} ×</span>
                            </div>
                            <in-number v-model="pr.c" placeholder="0"
                                       class="form-control form-control-sm text-right"></in-number>
                        </div>
                    </div>
                </fieldset>
            </div>

            <!-- Pie -->
            <div class="modal-footer am-footer d-flex justify-content-between align-items-center">
                <span class="am-help mt-0">
                    <strong>@{{ precios.filter(function(p) { return Number(p.p) > 0; }).length }}</strong> de @{{ precios.length }} planes con precio
                </span>
                <button type="button" @click="cerrarPrecios" class="btn-pos-primary px-4">
                    <template v-if="viewPrecio">Cerrar</template>
                    <template v-else><i class="fa fa-check"></i> Listo, volver al artículo</template>
                </button>
            </div>
        </div>
    </div>
</div>
