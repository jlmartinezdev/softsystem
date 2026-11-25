<template>
<div class="cuota-pos">
    <div class="row">
        <!-- ============ Columna izquierda: configuración ============ -->
        <div class="col-lg-5 mb-3 mb-lg-0">
            <!-- Paso 1: Cantidad de cuotas -->
            <section class="cp-step">
                <div class="cp-step-head">
                    <span class="cp-step-num">1</span>
                    <span class="cp-step-title">¿En cuántas cuotas?</span>
                </div>
                <div class="cp-chips cp-chips-grid">
                    <button type="button" v-for="n in opcionesCuotas" :key="n"
                        class="cp-chip cp-chip-lg"
                        :class="{ active: Number(cant_cuota) === n }"
                        @click="elegirPlan(n)">{{ n }}</button>
                    <div class="cp-chip-input" :class="{ active: opcionesCuotas.indexOf(Number(cant_cuota)) === -1 }">
                        <input type="number" min="1" max="60" placeholder="Otra"
                            :value="opcionesCuotas.indexOf(Number(cant_cuota)) === -1 ? cant_cuota : ''"
                            @change="onCantidadOtra($event.target.value)" aria-label="Otra cantidad de cuotas" title="Otra cantidad (1 a 60)">
                    </div>
                </div>
            </section>

            <!-- Paso 2: Entrega inicial -->
            <section class="cp-step">
                <div class="cp-step-head">
                    <span class="cp-step-num">2</span>
                    <span class="cp-step-title">Entrega inicial</span>
                    <span class="cp-step-opt">opcional</span>
                </div>
                <div class="cp-inline">
                    <div class="input-group input-group-sm cp-money">
                        <div class="input-group-prepend"><span class="input-group-text">Gs.</span></div>
                        <in-number
                            v-model="entrega"
                            :clases="'form-control font-weight-bold'"
                            placeholder="0"
                            @change="onEntregaChange">
                        </in-number>
                    </div>
                    <div class="cp-chips cp-chips-nowrap">
                        <button type="button" v-for="p in opcionesEntrega" :key="p"
                            class="cp-chip"
                            :class="{ active: entregaPctActiva(p) }"
                            :title="p === 0 ? 'Sin entrega' : 'Entrega del ' + p + '%'"
                            @click="setEntregaPct(p)">{{ p }}%</button>
                    </div>
                </div>
            </section>

            <!-- Paso 3: Vencimientos -->
            <section class="cp-step">
                <div class="cp-step-head">
                    <span class="cp-step-num">3</span>
                    <span class="cp-step-title">Frecuencia y {{ primeraHoyActiva ? '2º' : '1er' }} vencimiento</span>
                </div>
                <div class="cp-inline">
                    <div class="cp-segmented" role="group" aria-label="Frecuencia de pago">
                        <button type="button" v-for="f in opcionesFrecuencia" :key="f.value"
                            :class="{ active: frecuencia === f.value }"
                            :title="f.title"
                            @click="setFrecuencia(f.value)">{{ f.label }}</button>
                    </div>
                    <input id="cpPrimerVenc" type="date" class="form-control form-control-sm cp-date"
                        :aria-label="primeraHoyActiva ? 'Segundo vencimiento' : 'Primer vencimiento'"
                        :title="primeraHoyActiva ? 'Segundo vencimiento' : 'Primer vencimiento'"
                        v-model="primerVencimiento" @change="generar">
                </div>
            </section>

            <!-- Paso 4: Opciones -->
            <section class="cp-step mb-0">
                <div class="cp-step-head">
                    <span class="cp-step-num">4</span>
                    <span class="cp-step-title">Opciones</span>
                    <span v-if="montoInteres > 0" class="cp-step-opt cp-neg">+ Gs. {{ formatGs(montoInteres) }} de interés</span>
                </div>
                <div class="cp-inline cp-options">
                    <div class="d-flex align-items-center">
                        <label class="cp-label mb-0 mr-2" for="cpInteres">Interés</label>
                        <div class="input-group input-group-sm cp-interes">
                            <input id="cpInteres" type="number" min="0" max="100" step="0.5" class="form-control font-weight-bold text-right"
                                v-model.number="interes" @change="onInteresChange" placeholder="0">
                            <div class="input-group-append"><span class="input-group-text">%</span></div>
                        </div>
                    </div>
                    <div class="cp-switches">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="checkRedondear" v-model="redondear" @change="generar">
                            <label class="custom-control-label small" for="checkRedondear">Redondear a miles</label>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="checkPrimeracuota" v-model="primeracuotaHoy"
                                :disabled="Number(entrega) > 0" @change="onPrimeracuotaHoyChange">
                            <label class="custom-control-label small" for="checkPrimeracuota"
                                :title="Number(entrega) > 0 ? 'No aplica cuando hay entrega inicial' : ''">1ª cuota hoy</label>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- ============ Columna derecha: resumen y cronograma ============ -->
        <div class="col-lg-7">
            <!-- Resumen -->
            <div class="cp-hero">
                <div class="cp-hero-main">
                    <span class="cp-hero-label">Total a pagar</span>
                    <span class="cp-hero-total">Gs. {{ formatGs(totalAFinanciar) }}</span>
                    <span class="cp-hero-sub">
                        <template v-if="Number(entrega) > 0">Entrega + </template>{{ cuotasPlan.length }} × <strong>Gs. {{ formatGs(montoCuotaReferencia) }}</strong><template v-if="cuotasDesiguales"> aprox.</template>
                        <template v-if="primeraHoyActiva"> · 1ª hoy</template>
                    </span>
                </div>
                <div class="cp-hero-grid">
                    <div><span>Total venta</span><strong>{{ formatGs(total) }}</strong></div>
                    <div><span>Entrega</span><strong class="cp-pos">{{ formatGs(entrega) }}</strong></div>
                    <div><span>Saldo</span><strong>{{ formatGs(saldoNeto) }}</strong></div>
                    <div><span>Interés</span><strong :class="{ 'cp-neg': montoInteres > 0 }">{{ formatGs(montoInteres) }}</strong></div>
                </div>
            </div>

            <!-- Cronograma -->
            <div class="cp-schedule-head">
                <span class="cp-step-title">Cronograma de pagos</span>
                <button v-if="editadoManual" type="button" class="btn btn-link btn-sm p-0 cp-reset" @click="restablecer"
                    title="Descartar las ediciones manuales y volver a calcular">
                    <i class="fas fa-undo-alt mr-1"></i> Restablecer
                </button>
            </div>
            <div class="cp-schedule">
                <div v-for="(c, idx) in cuotas" :key="idx" class="cp-row"
                    :class="{ 'cp-row-hoy': c.tipo === 'Entrega', 'cp-row-error': fechaFueraDeOrden(idx) }">
                    <div class="cp-row-badge">
                        <i v-if="esEntregaInicial(c)" class="fas fa-hand-holding-usd" title="Entrega inicial"></i>
                        <span v-else>{{ numeroVisible(idx) }}</span>
                    </div>
                    <div class="cp-row-info">
                        <span class="cp-row-title">{{ esEntregaInicial(c) ? 'Entrega inicial' : 'Cuota ' + numeroVisible(idx) }}</span>
                        <span class="cp-row-meta" :class="{ 'text-danger': fechaFueraDeOrden(idx) }">
                            <template v-if="fechaFueraDeOrden(idx)"><i class="fas fa-exclamation-circle mr-1"></i>Antes que la anterior</template>
                            <template v-else-if="c.tipo === 'Entrega'"><i class="fas fa-check mr-1"></i>Se cobra hoy</template>
                            <template v-else>{{ infoFecha(c.vencimiento) }}</template>
                        </span>
                    </div>
                    <div class="cp-row-date">
                        <input type="date" class="form-control form-control-sm"
                            :value="toInputDate(c.vencimiento)"
                            :disabled="c.tipo === 'Entrega'"
                            :aria-label="'Vencimiento de la cuota ' + numeroVisible(idx)"
                            @change="onVencimientoItemChange(idx, $event.target.value)">
                    </div>
                    <div class="cp-row-amount">
                        <in-number
                            v-model="c.monto"
                            :clases="'form-control form-control-sm text-right font-weight-bold'"
                            @change="onMontoItemChange(idx)">
                        </in-number>
                    </div>
                    <div class="cp-row-action">
                        <button v-if="c.tipo !== 'Entrega'" type="button" class="cp-icon-btn"
                            title="Quitar cuota" :disabled="cantidadCuotasCredito <= 1" @click="eliminarCuota(idx)">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
                <div v-if="!cuotas.length" class="cp-empty">
                    <i class="far fa-calendar-times fa-2x mb-2"></i>
                    <p class="mb-0">Elegí la cantidad de cuotas para armar el plan.</p>
                </div>
            </div>

            <!-- Balance -->
            <div class="cp-balance" :class="hayDiferencia ? 'is-warning' : 'is-ok'">
                <div class="d-flex align-items-center">
                    <i class="fas mr-2" :class="hayDiferencia ? 'fa-exclamation-triangle' : 'fa-check-circle'"></i>
                    <div>
                        <div class="font-weight-bold" v-if="!hayDiferencia">El plan cuadra con el total</div>
                        <div class="font-weight-bold" v-else>
                            {{ diferenciaCuotas > 0 ? 'Faltan' : 'Sobran' }} Gs. {{ formatGs(Math.abs(diferenciaCuotas)) }}
                        </div>
                        <div class="small">Suma de pagos: Gs. {{ formatGs(sumaCuotas) }}</div>
                    </div>
                </div>
                <button v-if="hayDiferencia" type="button" class="btn btn-sm btn-warning font-weight-bold" @click="autoAjustarDiferencia">
                    <i class="fas fa-magic mr-1"></i> Ajustar en la última cuota
                </button>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import inNumber from './in_number.vue';

export default {
    name: 'generar_cuota',
    components: {
        inNumber,
        'in-number': inNumber
    },
    props: ['total', 'fecha', 'datoscuota', 'calcularcuota'],
    data() {
        return {
            cant_cuota: 3,
            entrega: 0,
            interes: 0,
            frecuencia: 'mensual',
            primerVencimiento: '',
            primeracuotaHoy: false,
            redondear: true,
            editadoManual: false,
            opcionesCuotas: [1, 2, 3, 4, 6, 10, 12, 18, 24],
            opcionesEntrega: [0, 10, 20, 30, 50],
            opcionesFrecuencia: [
                { value: 'mensual', label: 'Mensual', title: 'El mismo día de cada mes' },
                { value: 'quincenal', label: 'Quincenal', title: 'Cada 15 días' },
                { value: 'semanal', label: 'Semanal', title: 'Cada 7 días' }
            ],
            cuotas: []
        };
    },
    computed: {
        saldoNeto() {
            var s = Number(this.total) - Number(this.entrega);
            return s > 0 ? s : 0;
        },
        montoInteres() {
            if (this.interes > 0 && this.saldoNeto > 0) {
                return Math.round((this.saldoNeto * this.interes) / 100);
            }
            return 0;
        },
        saldoConInteres() {
            return this.saldoNeto + this.montoInteres;
        },
        totalAFinanciar() {
            return this.saldoConInteres + Number(this.entrega);
        },
        sumaCuotas() {
            return this.cuotas.reduce((acc, c) => acc + (Number(c.monto) || 0), 0);
        },
        diferenciaCuotas() {
            return this.totalAFinanciar - this.sumaCuotas;
        },
        hayDiferencia() {
            return Math.abs(this.diferenciaCuotas) >= 1;
        },
        cantidadCuotasCredito() {
            return this.cuotas.filter(c => c.tipo !== 'Entrega').length;
        },
        primeraHoyActiva() {
            return this.primeracuotaHoy && !(Number(this.entrega) > 0);
        },
        cuotasPlan() {
            // Con entrega inicial, esa fila no es una cuota. Con "1ª cuota hoy", sí lo es.
            if (Number(this.entrega) > 0) {
                return this.cuotas.filter(c => c.tipo !== 'Entrega');
            }
            return this.cuotas;
        },
        montoCuotaReferencia() {
            var lista = this.cuotasPlan;
            return lista.length ? (Number(lista[0].monto) || 0) : 0;
        },
        cuotasDesiguales() {
            var lista = this.cuotasPlan.map(c => Number(c.monto) || 0);
            return lista.some(m => m !== lista[0]);
        }
    },
    watch: {
        datoscuota: {
            immediate: true,
            deep: true,
            handler(val) {
                if (val && val.cant_cuota) {
                    this.cant_cuota = Number(val.cant_cuota) || 1;
                }
            }
        },
        total(nuevo) {
            // Mantener la entrega cargada; solo ajustarla si supera el nuevo total.
            var t = Number(nuevo) || 0;
            if (Number(this.entrega) > t) {
                this.entrega = t;
            }
            this.initFechaVencimiento();
            this.generar();
        },
        fecha() {
            this.initFechaVencimiento();
            this.generar();
        },
        calcularcuota() {
            if (this.datoscuota && this.datoscuota.cant_cuota) {
                this.cant_cuota = Number(this.datoscuota.cant_cuota) || 1;
            }
            this.initFechaVencimiento();
            this.generar();
        }
    },
    methods: {
        formatGs(val) {
            return new Intl.NumberFormat('de-DE').format(Number(val) || 0);
        },
        initFechaVencimiento() {
            var baseDate = this.fecha ? new Date(this.fecha + 'T00:00:00') : new Date();
            if (isNaN(baseDate.getTime())) {
                baseDate = new Date();
            }
            this.primerVencimiento = this.formatDateIso(this.sumarMeses(baseDate, 1));
        },
        sumarMeses(fecha, meses) {
            var base = fecha instanceof Date ? new Date(fecha.getTime()) : new Date(fecha);
            if (isNaN(base.getTime())) {
                base = new Date();
            }
            var dia = base.getDate();
            var destino = new Date(base.getFullYear(), base.getMonth() + Number(meses || 0), 1);
            var ultimo = new Date(destino.getFullYear(), destino.getMonth() + 1, 0).getDate();
            destino.setDate(dia >= 29 ? ultimo : Math.min(dia, ultimo));
            return destino;
        },
        formatDateIso(d) {
            var year = d.getFullYear();
            var month = String(d.getMonth() + 1).padStart(2, '0');
            var day = String(d.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        },
        toInputDate(str) {
            if (!str) return '';
            str = String(str).trim();
            // Si viene DD-MM-YYYY
            var parts = str.split('-');
            if (parts.length === 3) {
                if (parts[0].length === 4) {
                    return str; // YYYY-MM-DD
                }
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
            return str;
        },
        toVencimientoFormat(str) {
            if (!str) return '';
            str = String(str).trim();
            var parts = str.split('-');
            if (parts.length === 3) {
                if (parts[0].length === 4) {
                    return `${parts[2]}-${parts[1]}-${parts[0]}`; // Convert YYYY-MM-DD to DD-MM-YYYY
                }
                return str;
            }
            return str;
        },
        setEntregaPct(pct) {
            if (pct <= 0) {
                this.entrega = 0;
            } else {
                this.entrega = Math.round((Number(this.total) * pct) / 100);
            }
            this.onEntregaChange();
        },
        onEntregaChange() {
            if (this.entrega > this.total) {
                this.entrega = this.total;
            }
            if (this.entrega < 0 || isNaN(this.entrega)) {
                this.entrega = 0;
            }
            if (Number(this.entrega) > 0) {
                // La 1ª cuota hoy no aplica cuando hay entrega inicial.
                this.primeracuotaHoy = false;
            }
            this.generar();
        },
        onFrecuenciaChange() {
            var baseDate = this.fecha ? new Date(this.fecha + 'T00:00:00') : new Date();
            var nextDate = new Date(baseDate);
            if (this.frecuencia === 'semanal') {
                nextDate.setDate(nextDate.getDate() + 7);
            } else if (this.frecuencia === 'quincenal') {
                nextDate.setDate(nextDate.getDate() + 15);
            } else {
                nextDate = this.sumarMeses(baseDate, 1);
            }
            this.primerVencimiento = this.formatDateIso(nextDate);
            this.generar();
        },
        onPrimeracuotaHoyChange() {
            this.generar();
        },
        elegirPlan(n) {
            this.cant_cuota = n;
            this.generar();
        },
        onCantidadOtra(val) {
            if (val === '' || val === null) return;
            this.cant_cuota = val;
            this.onCantidadChange();
        },
        onCantidadChange() {
            var n = parseInt(this.cant_cuota, 10);
            if (isNaN(n) || n < 1) n = 1;
            if (n > 60) n = 60;
            this.cant_cuota = n;
            this.generar();
        },
        onInteresChange() {
            var i = Number(this.interes);
            if (isNaN(i) || i < 0) i = 0;
            if (i > 100) i = 100;
            this.interes = i;
            this.generar();
        },
        setFrecuencia(f) {
            if (this.frecuencia === f) return;
            this.frecuencia = f;
            this.onFrecuenciaChange();
        },
        entregaPctActiva(pct) {
            var t = Number(this.total) || 0;
            var e = Number(this.entrega) || 0;
            if (pct === 0) return e === 0;
            return t > 0 && e === Math.round((t * pct) / 100);
        },
        esEntregaInicial(c) {
            return c.tipo === 'Entrega' && Number(this.entrega) > 0;
        },
        restablecer() {
            this.generar();
        },
        infoFecha(venc) {
            var iso = this.toInputDate(venc);
            if (!iso) return '';
            var d = new Date(iso + 'T00:00:00');
            if (isNaN(d.getTime())) return '';
            var dias = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
            var base = this.fecha ? new Date(this.fecha + 'T00:00:00') : new Date();
            if (isNaN(base.getTime())) base = new Date();
            base.setHours(0, 0, 0, 0);
            var diff = Math.round((d - base) / 86400000);
            var rel = diff === 0 ? 'hoy' : (diff > 0 ? 'en ' + diff + (diff === 1 ? ' día' : ' días') : 'hace ' + (-diff) + ' días');
            return dias[d.getDay()] + ' · ' + rel;
        },
        fechaFueraDeOrden(idx) {
            if (idx === 0 || !this.cuotas[idx] || !this.cuotas[idx - 1]) return false;
            var actual = this.toInputDate(this.cuotas[idx].vencimiento);
            var previa = this.toInputDate(this.cuotas[idx - 1].vencimiento);
            return !!(actual && previa && actual < previa);
        },
        generar() {
            this.editadoManual = false;
            this.cuotas = [];
            var cantidad = Number(this.cant_cuota) || 1;
            if (cantidad < 1) cantidad = 1;

            var saldo = this.saldoConInteres;
            var entregaMonto = Number(this.entrega) || 0;

            // Fecha base de la venta
            var fechaVentaIso = this.fecha || this.formatDateIso(new Date());

            // 1. Si hay Entrega Inicial, crear cuota de tipo Entrega
            if (entregaMonto > 0) {
                this.cuotas.push({
                    nro: 1,
                    interes: 0,
                    vencimiento: this.toVencimientoFormat(fechaVentaIso),
                    monto: entregaMonto,
                    tipo: 'Entrega'
                });
            }

            // Si saldo a financiar es 0 (ej. pagó 100% de entrega), terminamos
            if (saldo <= 0) {
                this.emitCuotas();
                return;
            }

            // 2. Calcular monto base por cuota
            var montoCuotaBase = 0;
            if (this.datoscuota && Number(this.datoscuota.monto_cuota) > 0 && entregaMonto === 0 && Number(this.interes) === 0 && Number(this.datoscuota.cant_cuota) === cantidad) {
                montoCuotaBase = Number(this.datoscuota.monto_cuota);
            } else {
                montoCuotaBase = Math.floor(saldo / cantidad);
                if (this.redondear && montoCuotaBase > 1000) {
                    // Redondear a múltiplos de 1.000, siempre que la última cuota no quede en cero o negativa
                    var redondeado = Math.round(montoCuotaBase / 1000) * 1000;
                    if (redondeado * (cantidad - 1) < saldo) {
                        montoCuotaBase = redondeado;
                    }
                }
            }

            // 3. Calcular fechas de vencimiento según frecuencia
            var dateCursor = this.primerVencimiento
                ? new Date(this.primerVencimiento + 'T00:00:00')
                : new Date();

            if (isNaN(dateCursor.getTime())) {
                dateCursor = new Date();
            }

            var nroOffset = (entregaMonto > 0) ? 2 : 1;
            var totalAsignado = 0;
            var primeraHoy = this.primeracuotaHoy && entregaMonto === 0;

            for (var i = 0; i < cantidad; i++) {
                var cDate;
                if (i === 0 && primeraHoy) {
                    cDate = new Date(fechaVentaIso + 'T00:00:00');
                } else {
                    var pasos = primeraHoy ? (i - 1) : i;
                    if (this.frecuencia === 'semanal') {
                        cDate = new Date(dateCursor);
                        if (pasos > 0) {
                            cDate.setDate(cDate.getDate() + (pasos * 7));
                        }
                    } else if (this.frecuencia === 'quincenal') {
                        cDate = new Date(dateCursor);
                        if (pasos > 0) {
                            cDate.setDate(cDate.getDate() + (pasos * 15));
                        }
                    } else {
                        var ancla = new Date(fechaVentaIso + 'T00:00:00');
                        if (isNaN(ancla.getTime())) {
                            ancla = new Date(dateCursor);
                        }
                        var autoPrimero = this.formatDateIso(this.sumarMeses(ancla, 1));
                        if (this.primerVencimiento && this.primerVencimiento !== autoPrimero) {
                            cDate = this.sumarMeses(dateCursor, pasos);
                        } else {
                            cDate = this.sumarMeses(ancla, primeraHoy ? i : (i + 1));
                        }
                    }
                }

                var montoLinea = montoCuotaBase;
                if (i === cantidad - 1) {
                    // La última cuota absorbe cualquier diferencia de redondeo
                    montoLinea = saldo - totalAsignado;
                }
                totalAsignado += montoLinea;

                this.cuotas.push({
                    nro: i + nroOffset,
                    interes: this.interes,
                    vencimiento: this.toVencimientoFormat(this.formatDateIso(cDate)),
                    monto: montoLinea,
                    tipo: (i === 0 && primeraHoy) ? 'Entrega' : 'Cuota'
                });
            }

            this.emitCuotas();
        },
        onVencimientoItemChange(idx, nuevaFechaIso) {
            if (this.cuotas[idx]) {
                this.$set(this.cuotas[idx], 'vencimiento', this.toVencimientoFormat(nuevaFechaIso));
                this.editadoManual = true;
                this.emitCuotas();
            }
        },
        onMontoItemChange(idx) {
            if (this.cuotas[idx]) {
                var val = Number(this.cuotas[idx].monto) || 0;
                this.$set(this.cuotas[idx], 'monto', val);
                this.editadoManual = true;
                this.emitCuotas();
            }
        },
        numeroVisible(idx) {
            if (!(Number(this.entrega) > 0)) {
                return idx + 1;
            }
            var n = 0;
            for (var i = 0; i <= idx; i++) {
                if (this.cuotas[i].tipo !== 'Entrega') n++;
            }
            return n;
        },
        eliminarCuota(idx) {
            if (!this.cuotas[idx] || this.cuotas[idx].tipo === 'Entrega' || this.cantidadCuotasCredito <= 1) {
                return;
            }
            this.cuotas.splice(idx, 1);
            // Renumerar cuotas
            var num = 1;
            this.cuotas.forEach(c => {
                c.nro = num++;
            });
            this.cant_cuota = this.cuotas.filter(c => c.tipo === 'Cuota').length + (this.primeraHoyActiva ? 1 : 0);
            this.editadoManual = true;
            this.autoAjustarDiferencia();
            this.emitCuotas();
        },
        autoAjustarDiferencia() {
            var diff = this.diferenciaCuotas;
            if (Math.abs(diff) < 1 || !this.cuotas.length) return;

            // Ajustar en la última cuota que no sea Entrega
            for (var i = this.cuotas.length - 1; i >= 0; i--) {
                if (this.cuotas[i].tipo !== 'Entrega') {
                    var nuevoMonto = (Number(this.cuotas[i].monto) || 0) + diff;
                    if (nuevoMonto > 0) {
                        this.cuotas[i].monto = nuevoMonto;
                        break;
                    }
                }
            }
            this.emitCuotas();
        },
        emitCuotas() {
            // Emitir copias para que el padre no comparta objetos con la edición del modal
            this.$emit('cuotas', this.cuotas.map(c => Object.assign({}, c)));
        },
        getEstado() {
            return {
                cant_cuota: this.cant_cuota,
                entrega: this.entrega,
                interes: this.interes,
                frecuencia: this.frecuencia,
                primerVencimiento: this.primerVencimiento,
                primeracuotaHoy: this.primeracuotaHoy,
                redondear: this.redondear,
                cuotas: this.cuotas.map(c => Object.assign({}, c))
            };
        },
        setEstado(estado) {
            if (!estado) return;
            this.cant_cuota = estado.cant_cuota;
            this.entrega = estado.entrega;
            this.interes = estado.interes;
            this.frecuencia = estado.frecuencia;
            this.primerVencimiento = estado.primerVencimiento;
            this.primeracuotaHoy = estado.primeracuotaHoy;
            this.redondear = estado.redondear;
            this.cuotas = (estado.cuotas || []).map(c => Object.assign({}, c));
        }
    },
    mounted() {
        if (this.datoscuota && this.datoscuota.cant_cuota) {
            this.cant_cuota = Number(this.datoscuota.cant_cuota) || 1;
        }
        this.initFechaVencimiento();
        this.generar();
    }
};
</script>

<style scoped>
.cuota-pos {
    --cp-primary: var(--dash-primary, #0a4d36);
    --cp-primary-light: var(--dash-primary-light, #eaf3ef);
    --cp-primary-border: var(--dash-primary-border, #c8dfd5);
    --cp-accent: var(--dash-accent, #b8860b);
    --cp-card: var(--dash-card-bg, #ffffff);
    --cp-panel: var(--dash-panel-bg, #f8fafc);
    --cp-border: var(--dash-border, #e2e8f0);
    --cp-text: var(--dash-text-main, #1e293b);
    --cp-muted: var(--dash-text-muted, #64748b);
    --cp-danger: var(--dash-danger, #e11d48);
    --cp-danger-light: var(--dash-danger-light, #fff1f2);
    --cp-ok: #16a34a;
    color: var(--cp-text);
}

/* ---- Pasos de configuración ---- */
.cp-step {
    background: var(--cp-panel);
    border: 1px solid var(--cp-border);
    border-radius: 12px;
    padding: 0.6rem 0.8rem;
    margin-bottom: 0.5rem;
}
.cp-step-head {
    display: flex;
    align-items: center;
    margin-bottom: 0.45rem;
}
.cp-inline {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.cp-inline > .cp-money {
    flex: 1 1 auto;
    width: auto;
    min-width: 120px;
}
.cp-inline > .cp-segmented {
    flex: 1 1 auto;
}
.cp-chips-nowrap {
    flex-wrap: nowrap;
    flex-shrink: 0;
}
.cp-chips-nowrap .cp-chip {
    padding: 0.2rem 0.45rem;
}
.cp-options {
    justify-content: space-between;
    flex-wrap: wrap;
}
.cp-switches {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
}
.cp-step-num {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: var(--cp-primary);
    color: #fff;
    font-size: 0.72rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.5rem;
    flex-shrink: 0;
}
.cp-step-title {
    font-weight: 700;
    font-size: 0.85rem;
}
.cp-step-opt {
    margin-left: auto;
    font-size: 0.7rem;
    color: var(--cp-muted);
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.cp-label {
    font-size: 0.8rem;
    color: var(--cp-muted);
    font-weight: 600;
}
.cp-hint {
    display: inline-block;
    margin-top: 0.45rem;
    font-size: 0.8rem;
    color: var(--cp-muted);
}
.cp-hint strong {
    color: var(--cp-primary);
}

/* ---- Chips ---- */
.cp-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 0.35rem;
}
.cp-chip {
    border: 1px solid var(--cp-border);
    background: var(--cp-card);
    color: var(--cp-text);
    border-radius: 999px;
    padding: 0.2rem 0.7rem;
    font-size: 0.78rem;
    font-weight: 600;
    line-height: 1.4;
    cursor: pointer;
    transition: background-color .12s, border-color .12s, color .12s;
}
.cp-chip:hover {
    border-color: var(--cp-primary);
    color: var(--cp-primary);
}
.cp-chip.active,
.cp-chip.active:hover {
    background: var(--cp-primary);
    border-color: var(--cp-primary);
    color: #fff;
}
.cp-chip-lg {
    min-width: 36px;
    padding: 0.25rem 0.5rem;
    font-size: 0.88rem;
    border-radius: 9px;
}
.cp-chip-input {
    border: 1px dashed var(--cp-border);
    border-radius: 10px;
    background: var(--cp-card);
    display: flex;
    align-items: center;
}
.cp-chip-input.active {
    border: 2px solid var(--cp-primary);
}
.cp-chips-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
}
.cp-chip-input input {
    width: 100%;
    min-width: 0;
    border: 0;
    background: transparent;
    color: var(--cp-text);
    text-align: center;
    font-weight: 700;
    font-size: 0.9rem;
    padding: 0.2rem 0.2rem;
    outline: none;
}

/* ---- Control segmentado ---- */
.cp-segmented {
    display: flex;
    background: var(--cp-card);
    border: 1px solid var(--cp-border);
    border-radius: 10px;
    padding: 3px;
}
.cp-segmented button {
    flex: 1;
    border: 0;
    background: transparent;
    color: var(--cp-muted);
    border-radius: 7px;
    padding: 0.3rem 0.4rem;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
}
.cp-segmented button.active {
    background: var(--cp-primary);
    color: #fff;
}
.cp-date {
    width: 150px;
    flex-shrink: 0;
}
.cp-interes {
    width: 105px;
}
.cp-money .input-group-text,
.cp-interes .input-group-text {
    font-size: 0.8rem;
}

/* ---- Resumen ---- */
.cp-hero {
    background: var(--cp-primary-light);
    border: 1px solid var(--cp-primary-border);
    border-radius: 12px;
    padding: 0.65rem 0.9rem;
    margin-bottom: 0.6rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}
.cp-hero-main {
    display: flex;
    flex-direction: column;
    flex: 1 1 auto;
    min-width: 0;
}
.cp-hero-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--cp-muted);
}
.cp-hero-total {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--cp-primary);
    font-variant-numeric: tabular-nums;
    line-height: 1.2;
}
.cp-hero-sub {
    font-size: 0.82rem;
    color: var(--cp-text);
}
.cp-hero-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(90px, auto));
    gap: 0.3rem 1rem;
    padding-left: 1rem;
    border-left: 1px solid var(--cp-primary-border);
    flex-shrink: 0;
}
.cp-hero-grid div {
    display: flex;
    flex-direction: column;
    min-width: 0;
}
.cp-hero-grid span {
    font-size: 0.68rem;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--cp-muted);
}
.cp-hero-grid strong {
    font-size: 0.85rem;
    font-variant-numeric: tabular-nums;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.cp-pos { color: var(--cp-ok); }
.cp-neg { color: var(--cp-accent); }

/* ---- Cronograma ---- */
.cp-schedule-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.4rem;
    min-height: 24px;
}
.cp-reset {
    font-size: 0.8rem;
    color: var(--cp-primary);
}
.cp-schedule {
    border: 1px solid var(--cp-border);
    border-radius: 12px;
    background: var(--cp-card);
    max-height: 300px;
    max-height: clamp(170px, calc(100vh - 400px), 380px);
    overflow-y: auto;
}
.cp-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.35rem 0.6rem;
    border-bottom: 1px solid var(--cp-border);
}
.cp-row:last-child {
    border-bottom: 0;
}
.cp-row:hover {
    background: var(--cp-panel);
}
.cp-row-hoy,
.cp-row-hoy:hover {
    background: rgba(22, 163, 74, 0.08);
}
.cp-row-error,
.cp-row-error:hover {
    background: var(--cp-danger-light);
}
.cp-row-badge {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: var(--cp-panel);
    border: 1px solid var(--cp-border);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.78rem;
    font-weight: 700;
    flex-shrink: 0;
}
.cp-row-hoy .cp-row-badge {
    background: var(--cp-ok);
    border-color: var(--cp-ok);
    color: #fff;
}
.cp-row-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    line-height: 1.25;
}
.cp-row-title {
    font-size: 0.84rem;
    font-weight: 600;
}
.cp-row-meta {
    font-size: 0.72rem;
    color: var(--cp-muted);
}
.cp-row-hoy .cp-row-meta {
    color: var(--cp-ok);
}
.cp-row-date {
    width: 145px;
    flex-shrink: 0;
}
.cp-row-amount {
    width: 125px;
    flex-shrink: 0;
}
.cp-row-action {
    width: 26px;
    flex-shrink: 0;
    text-align: center;
}
.cp-icon-btn {
    border: 0;
    background: transparent;
    color: var(--cp-muted);
    padding: 0.2rem 0.3rem;
    border-radius: 6px;
    cursor: pointer;
    opacity: 0.5;
}
.cp-row:hover .cp-icon-btn:not(:disabled),
.cp-icon-btn:focus {
    opacity: 1;
}
.cp-icon-btn:hover:not(:disabled) {
    color: var(--cp-danger);
    background: var(--cp-danger-light);
}
.cp-icon-btn:disabled {
    opacity: 0.2;
    cursor: not-allowed;
}
.cp-empty {
    text-align: center;
    color: var(--cp-muted);
    padding: 2rem 1rem;
}

/* ---- Balance ---- */
.cp-balance {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
    justify-content: space-between;
    border-radius: 12px;
    padding: 0.45rem 0.85rem;
    margin-top: 0.5rem;
    font-size: 0.85rem;
}
.cp-balance.is-ok {
    background: rgba(22, 163, 74, 0.1);
    border: 1px solid rgba(22, 163, 74, 0.3);
    color: #15803d;
}
.cp-balance.is-warning {
    background: rgba(234, 179, 8, 0.12);
    border: 1px solid rgba(234, 179, 8, 0.45);
    color: #854d0e;
}
.cp-balance .fas {
    font-size: 1.2rem;
}

@media (max-width: 575.98px) {
    .cp-hero { flex-direction: column; align-items: stretch; gap: 0.5rem; }
    .cp-hero-grid { grid-template-columns: repeat(2, 1fr); padding-left: 0; border-left: 0; padding-top: 0.5rem; border-top: 1px solid var(--cp-primary-border); }
    .cp-inline:not(.cp-options) { flex-wrap: wrap; }
    .cp-inline > .cp-money, .cp-inline > .cp-segmented { flex-basis: 100%; }
    .cp-date { width: 100%; }
    .cp-row { flex-wrap: wrap; }
    .cp-row-info { flex-basis: calc(100% - 75px); }
    .cp-row-date, .cp-row-amount { flex: 1 1 40%; width: auto; }
}
</style>
