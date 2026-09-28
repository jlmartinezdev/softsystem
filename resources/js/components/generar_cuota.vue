<template>
<div class="cuota-pos-container">
    <!-- 1. Tarjetas de Resumen Financiero -->
    <div class="row cuota-summary-row mb-3">
        <div class="col-sm-3 col-6 mb-2">
            <div class="cuota-summary-card">
                <span class="cuota-summary-label">Total Venta</span>
                <span class="cuota-summary-val font-weight-bold text-dark">Gs. {{ formatGs(total) }}</span>
            </div>
        </div>
        <div class="col-sm-3 col-6 mb-2">
            <div class="cuota-summary-card">
                <span class="cuota-summary-label">Entrega / Anticipo</span>
                <span class="cuota-summary-val text-success">Gs. {{ formatGs(entrega) }}</span>
            </div>
        </div>
        <div class="col-sm-3 col-6 mb-2">
            <div class="cuota-summary-card">
                <span class="cuota-summary-label">Saldo a Financiar</span>
                <span class="cuota-summary-val text-primary">Gs. {{ formatGs(saldoNeto) }}</span>
            </div>
        </div>
        <div class="col-sm-3 col-6 mb-2">
            <div class="cuota-summary-card">
                <span class="cuota-summary-label">Total con Interés</span>
                <span class="cuota-summary-val text-info">Gs. {{ formatGs(saldoConInteres) }}</span>
            </div>
        </div>
    </div>

    <!-- 2. Formulario de Configuración del Crédito -->
    <div class="cuota-config-box p-3 mb-3 border rounded bg-light">
        <div class="row align-items-end">
            <!-- Entrega Inicial -->
            <div class="col-md-4 col-sm-6 mb-2">
                <label class="font-weight-bold small text-muted mb-1">
                    <i class="fas fa-hand-holding-usd text-success mr-1"></i> Entrega Inicial (Gs.)
                </label>
                <div class="input-group input-group-sm">
                    <in-number
                        v-model="entrega"
                        :clases="'form-control font-weight-bold text-success'"
                        placeholder="0"
                        @change="onEntregaChange">
                    </in-number>
                </div>
                <div class="quick-entrega-chips mt-1">
                    <button type="button" class="btn btn-xs btn-outline-secondary mr-1 mb-1" @click="setEntregaPct(0)">0%</button>
                    <button type="button" class="btn btn-xs btn-outline-secondary mr-1 mb-1" @click="setEntregaPct(10)">10%</button>
                    <button type="button" class="btn btn-xs btn-outline-secondary mr-1 mb-1" @click="setEntregaPct(20)">20%</button>
                    <button type="button" class="btn btn-xs btn-outline-secondary mr-1 mb-1" @click="setEntregaPct(30)">30%</button>
                    <button type="button" class="btn btn-xs btn-outline-secondary mr-1 mb-1" @click="setEntregaPct(50)">50%</button>
                </div>
            </div>

            <!-- Cantidad de Cuotas -->
            <div class="col-md-4 col-sm-6 mb-2">
                <label class="font-weight-bold small text-muted mb-1">
                    <i class="fas fa-calendar-alt text-primary mr-1"></i> Cantidad de Cuotas
                </label>
                <div class="input-group input-group-sm">
                    <input type="number" min="1" max="60" class="form-control font-weight-bold text-center"
                        v-model.number="cant_cuota" @change="generar" placeholder="1">
                    <div class="input-group-append">
                        <span class="input-group-text small">cuotas</span>
                    </div>
                </div>
                <div class="quick-cuotas-chips mt-1">
                    <button type="button" v-for="n in [1, 2, 3, 4, 6, 10, 12, 18, 24]" :key="n"
                        class="btn btn-xs mr-1 mb-1"
                        :class="cant_cuota === n ? 'btn-primary' : 'btn-outline-secondary'"
                        @click="elegirPlan(n)">
                        {{ n }}
                    </button>
                </div>
            </div>

            <!-- Frecuencia de Vencimiento -->
            <div class="col-md-4 col-sm-6 mb-2">
                <label class="font-weight-bold small text-muted mb-1">
                    <i class="fas fa-history text-secondary mr-1"></i> Frecuencia
                </label>
                <select class="form-control form-control-sm" v-model="frecuencia" @change="onFrecuenciaChange">
                    <option value="mensual">Mensual (cada 30 días)</option>
                    <option value="quincenal">Quincenal (cada 15 días)</option>
                    <option value="semanal">Semanal (cada 7 días)</option>
                </select>
            </div>

            <!-- Fecha 1er Vencimiento -->
            <div class="col-md-4 col-sm-6 mb-2">
                <label class="font-weight-bold small text-muted mb-1">
                    <i class="far fa-calendar-check mr-1"></i> Primer Vencimiento
                </label>
                <input type="date" class="form-control form-control-sm" v-model="primerVencimiento" @change="generar">
            </div>

            <!-- Interés / Recargo % -->
            <div class="col-md-4 col-sm-6 mb-2">
                <label class="font-weight-bold small text-muted mb-1">
                    <i class="fas fa-percentage text-danger mr-1"></i> Interés / Recargo (%)
                </label>
                <div class="input-group input-group-sm">
                    <input type="number" min="0" max="100" step="0.5" class="form-control font-weight-bold"
                        v-model.number="interes" @input="generar" placeholder="0">
                    <div class="input-group-append">
                        <span class="input-group-text small">%</span>
                    </div>
                </div>
            </div>

            <!-- Opciones Rápidas -->
            <div class="col-md-4 col-sm-6 mb-2">
                <div class="custom-control custom-checkbox mb-1">
                    <input type="checkbox" class="custom-control-input" id="checkRedondear" v-model="redondear" @change="generar">
                    <label class="custom-control-label small" for="checkRedondear">Redondear cuotas a miles</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="checkPrimeracuota" v-model="primeracuotaHoy" @change="onPrimeracuotaHoyChange">
                    <label class="custom-control-label small" for="checkPrimeracuota">1ª cuota hoy (al contado)</label>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Tabla Interactiva de Cuotas Generadas -->
    <div class="cuota-table-wrapper border rounded overflow-hidden mb-2">
        <table class="table table-sm table-striped table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th style="width: 70px;"># Cuota</th>
                    <th style="width: 110px;">Tipo</th>
                    <th>Vencimiento</th>
                    <th class="text-right" style="width: 170px;">Monto (Gs.)</th>
                    <th class="text-center" style="width: 50px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(c, idx) in cuotas" :key="idx" :class="{ 'table-success': c.tipo === 'Entrega' }">
                    <td class="font-weight-bold align-middle">
                        <span v-if="c.tipo === 'Entrega'">Anticipo</span>
                        <span v-else>Cuota {{ c.nro }}</span>
                    </td>
                    <td class="align-middle">
                        <span v-if="c.tipo === 'Entrega'" class="badge badge-success">
                            <i class="fas fa-check mr-1"></i> Cobrado Hoy
                        </span>
                        <span v-else class="badge badge-info">
                            Crédito
                        </span>
                    </td>
                    <td class="align-middle">
                        <input type="date" class="form-control form-control-sm d-inline-block" style="max-width: 160px;"
                            :value="toInputDate(c.vencimiento)"
                            @change="onVencimientoItemChange(idx, $event.target.value)">
                    </td>
                    <td class="text-right align-middle" style="min-width: 140px;">
                        <in-number
                            v-model="c.monto"
                            :clases="'form-control form-control-sm text-right font-weight-bold'"
                            @change="onMontoItemChange(idx)">
                        </in-number>
                    </td>
                    <td class="text-center align-middle">
                        <span v-if="c.tipo === 'Entrega'" class="text-muted" title="Entrega inicial">
                            <i class="fas fa-lock"></i>
                        </span>
                        <button v-else type="button" class="btn btn-link btn-xs text-danger p-0" title="Eliminar cuota" @click="eliminarCuota(idx)">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
                <tr v-if="!cuotas.length">
                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="far fa-calendar-times fa-2x mb-2 text-muted"></i>
                        <p class="mb-0">Configurá las cuotas para armar el plan de pago.</p>
                    </td>
                </tr>
            </tbody>
            <tfoot class="bg-light font-weight-bold">
                <tr>
                    <td colspan="3" class="text-right align-middle">
                        Total Suma de Cuotas:
                    </td>
                    <td class="text-right align-middle">
                        Gs. {{ formatGs(sumaCuotas) }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- 4. Barra de Alerta y Validación de Diferencias -->
    <div class="cuota-status-bar d-flex flex-wrap justify-content-between align-items-center p-2 px-3 rounded shadow-sm"
        :class="hayDiferencia ? 'cuota-status-warning' : 'cuota-status-success'">
        <div class="d-flex align-items-center">
            <template v-if="!hayDiferencia">
                <i class="fa fa-check-circle mr-2 text-success" style="font-size: 1.15rem;"></i>
                <span class="font-weight-bold" style="color: #155724; font-size: 0.9rem;">
                    Plan de cuotas balanceado perfectamente con el total.
                </span>
            </template>
            <template v-else>
                <i class="fa fa-exclamation-triangle mr-2 text-warning" style="font-size: 1.15rem;"></i>
                <span class="font-weight-bold" style="color: #856404; font-size: 0.9rem;">
                    Diferencia de Gs. {{ formatGs(Math.abs(diferenciaCuotas)) }}
                    ({{ diferenciaCuotas > 0 ? 'Falta asignar a las cuotas' : 'Sobra en las cuotas' }})
                </span>
            </template>
        </div>
        <div v-if="hayDiferencia" class="mt-1 mt-sm-0">
            <button type="button" class="btn btn-sm btn-warning font-weight-bold shadow-sm" @click="autoAjustarDiferencia">
                <i class="fa fa-magic mr-1"></i> Auto-ajustar diferencia
            </button>
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
        }
    },
    watch: {
        total() {
            this.entrega = 0;
            this.initFechaVencimiento();
            this.generar();
        },
        fecha() {
            this.initFechaVencimiento();
            this.generar();
        },
        calcularcuota() {
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
            // Siguiente mes por defecto
            var nextDate = new Date(baseDate);
            nextDate.setMonth(nextDate.getMonth() + 1);
            this.primerVencimiento = this.formatDateIso(nextDate);
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
                nextDate.setMonth(nextDate.getMonth() + 1);
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
        generar() {
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
            var montoCuotaBase = Math.floor(saldo / cantidad);
            if (this.redondear && montoCuotaBase > 1000) {
                // Redondear a múltiplos de 1.000
                montoCuotaBase = Math.round(montoCuotaBase / 1000) * 1000;
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

            for (var i = 0; i < cantidad; i++) {
                var cDate = new Date(dateCursor);
                if (i > 0) {
                    if (this.frecuencia === 'semanal') {
                        cDate.setDate(cDate.getDate() + (i * 7));
                    } else if (this.frecuencia === 'quincenal') {
                        cDate.setDate(cDate.getDate() + (i * 15));
                    } else {
                        // Mensual
                        cDate.setMonth(cDate.getMonth() + i);
                    }
                } else if (this.primeracuotaHoy && entregaMonto === 0) {
                    cDate = new Date(fechaVentaIso + 'T00:00:00');
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
                    tipo: (i === 0 && this.primeracuotaHoy && entregaMonto === 0) ? 'Entrega' : 'Cuota'
                });
            }

            this.emitCuotas();
        },
        onVencimientoItemChange(idx, nuevaFechaIso) {
            if (this.cuotas[idx]) {
                this.$set(this.cuotas[idx], 'vencimiento', this.toVencimientoFormat(nuevaFechaIso));
                this.emitCuotas();
            }
        },
        onMontoItemChange(idx) {
            if (this.cuotas[idx]) {
                var val = Number(this.cuotas[idx].monto) || 0;
                this.$set(this.cuotas[idx], 'monto', val);
                this.emitCuotas();
            }
        },
        eliminarCuota(idx) {
            if (this.cuotas.length <= 1) {
                return;
            }
            this.cuotas.splice(idx, 1);
            // Renumerar cuotas
            var num = 1;
            this.cuotas.forEach(c => {
                c.nro = num++;
            });
            this.cant_cuota = this.cuotas.filter(c => c.tipo === 'Cuota').length;
            this.autoAjustarDiferencia();
        },
        autoAjustarDiferencia() {
            var diff = this.diferenciaCuotas;
            if (Math.abs(diff) < 1 || !this.cuotas.length) return;

            // Ajustar en la última cuota que no sea Entrega
            for (var i = this.cuotas.length - 1; i >= 0; i--) {
                if (this.cuotas[i].tipo !== 'Entrega') {
                    var nuevoMonto = this.cuotas[i].monto + diff;
                    if (nuevoMonto > 0) {
                        this.cuotas[i].monto = nuevoMonto;
                        break;
                    }
                }
            }
            this.emitCuotas();
        },
        emitCuotas() {
            this.$emit('cuotas', this.cuotas);
        }
    },
    mounted() {
        this.initFechaVencimiento();
        this.generar();
    }
};
</script>

<style scoped>
.cuota-summary-row .cuota-summary-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.65rem 0.85rem;
    display: flex;
    flex-direction: column;
}
.cuota-summary-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
    letter-spacing: 0.03em;
}
.cuota-summary-val {
    font-size: 1.15rem;
    font-family: inherit;
    font-variant-numeric: tabular-nums;
    margin-top: 0.2rem;
}
.quick-entrega-chips, .quick-cuotas-chips {
    display: flex;
    flex-wrap: wrap;
}
.cuota-table-wrapper {
    max-height: 280px;
    overflow-y: auto;
    background: #ffffff;
}
.cuota-table-wrapper table thead th {
    position: sticky;
    top: 0;
    background: #f1f5f9;
    z-index: 2;
    font-size: 0.8rem;
    letter-spacing: 0.02em;
}
.cuota-status-bar {
    border-radius: 8px;
    margin-top: 0.75rem;
}
.cuota-status-success {
    background-color: #e8f5e9 !important;
    border: 1px solid #c8e6c9 !important;
}
.cuota-status-warning {
    background-color: #fff3cd !important;
    border: 1px solid #ffeeba !important;
}
</style>
