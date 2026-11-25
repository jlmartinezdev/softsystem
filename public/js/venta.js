/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 4);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/generar_cuota.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/generar_cuota.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _in_number_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./in_number.vue */ "./resources/js/components/in_number.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
  name: 'generar_cuota',
  components: {
    inNumber: _in_number_vue__WEBPACK_IMPORTED_MODULE_0__["default"],
    'in-number': _in_number_vue__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  props: ['total', 'fecha', 'datoscuota', 'calcularcuota'],
  data: function data() {
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
      opcionesFrecuencia: [{
        value: 'mensual',
        label: 'Mensual',
        title: 'El mismo día de cada mes'
      }, {
        value: 'quincenal',
        label: 'Quincenal',
        title: 'Cada 15 días'
      }, {
        value: 'semanal',
        label: 'Semanal',
        title: 'Cada 7 días'
      }],
      cuotas: []
    };
  },
  computed: {
    saldoNeto: function saldoNeto() {
      var s = Number(this.total) - Number(this.entrega);
      return s > 0 ? s : 0;
    },
    montoInteres: function montoInteres() {
      if (this.interes > 0 && this.saldoNeto > 0) {
        return Math.round(this.saldoNeto * this.interes / 100);
      }

      return 0;
    },
    saldoConInteres: function saldoConInteres() {
      return this.saldoNeto + this.montoInteres;
    },
    totalAFinanciar: function totalAFinanciar() {
      return this.saldoConInteres + Number(this.entrega);
    },
    sumaCuotas: function sumaCuotas() {
      return this.cuotas.reduce(function (acc, c) {
        return acc + (Number(c.monto) || 0);
      }, 0);
    },
    diferenciaCuotas: function diferenciaCuotas() {
      return this.totalAFinanciar - this.sumaCuotas;
    },
    hayDiferencia: function hayDiferencia() {
      return Math.abs(this.diferenciaCuotas) >= 1;
    },
    cantidadCuotasCredito: function cantidadCuotasCredito() {
      return this.cuotas.filter(function (c) {
        return c.tipo !== 'Entrega';
      }).length;
    },
    primeraHoyActiva: function primeraHoyActiva() {
      return this.primeracuotaHoy && !(Number(this.entrega) > 0);
    },
    cuotasPlan: function cuotasPlan() {
      // Con entrega inicial, esa fila no es una cuota. Con "1ª cuota hoy", sí lo es.
      if (Number(this.entrega) > 0) {
        return this.cuotas.filter(function (c) {
          return c.tipo !== 'Entrega';
        });
      }

      return this.cuotas;
    },
    montoCuotaReferencia: function montoCuotaReferencia() {
      var lista = this.cuotasPlan;
      return lista.length ? Number(lista[0].monto) || 0 : 0;
    },
    cuotasDesiguales: function cuotasDesiguales() {
      var lista = this.cuotasPlan.map(function (c) {
        return Number(c.monto) || 0;
      });
      return lista.some(function (m) {
        return m !== lista[0];
      });
    }
  },
  watch: {
    datoscuota: {
      immediate: true,
      deep: true,
      handler: function handler(val) {
        if (val && val.cant_cuota) {
          this.cant_cuota = Number(val.cant_cuota) || 1;
        }
      }
    },
    total: function total(nuevo) {
      // Mantener la entrega cargada; solo ajustarla si supera el nuevo total.
      var t = Number(nuevo) || 0;

      if (Number(this.entrega) > t) {
        this.entrega = t;
      }

      this.initFechaVencimiento();
      this.generar();
    },
    fecha: function fecha() {
      this.initFechaVencimiento();
      this.generar();
    },
    calcularcuota: function calcularcuota() {
      if (this.datoscuota && this.datoscuota.cant_cuota) {
        this.cant_cuota = Number(this.datoscuota.cant_cuota) || 1;
      }

      this.initFechaVencimiento();
      this.generar();
    }
  },
  methods: {
    formatGs: function formatGs(val) {
      return new Intl.NumberFormat('de-DE').format(Number(val) || 0);
    },
    initFechaVencimiento: function initFechaVencimiento() {
      var baseDate = this.fecha ? new Date(this.fecha + 'T00:00:00') : new Date();

      if (isNaN(baseDate.getTime())) {
        baseDate = new Date();
      }

      this.primerVencimiento = this.formatDateIso(this.sumarMeses(baseDate, 1));
    },
    sumarMeses: function sumarMeses(fecha, meses) {
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
    formatDateIso: function formatDateIso(d) {
      var year = d.getFullYear();
      var month = String(d.getMonth() + 1).padStart(2, '0');
      var day = String(d.getDate()).padStart(2, '0');
      return "".concat(year, "-").concat(month, "-").concat(day);
    },
    toInputDate: function toInputDate(str) {
      if (!str) return '';
      str = String(str).trim(); // Si viene DD-MM-YYYY

      var parts = str.split('-');

      if (parts.length === 3) {
        if (parts[0].length === 4) {
          return str; // YYYY-MM-DD
        }

        return "".concat(parts[2], "-").concat(parts[1], "-").concat(parts[0]);
      }

      return str;
    },
    toVencimientoFormat: function toVencimientoFormat(str) {
      if (!str) return '';
      str = String(str).trim();
      var parts = str.split('-');

      if (parts.length === 3) {
        if (parts[0].length === 4) {
          return "".concat(parts[2], "-").concat(parts[1], "-").concat(parts[0]); // Convert YYYY-MM-DD to DD-MM-YYYY
        }

        return str;
      }

      return str;
    },
    setEntregaPct: function setEntregaPct(pct) {
      if (pct <= 0) {
        this.entrega = 0;
      } else {
        this.entrega = Math.round(Number(this.total) * pct / 100);
      }

      this.onEntregaChange();
    },
    onEntregaChange: function onEntregaChange() {
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
    onFrecuenciaChange: function onFrecuenciaChange() {
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
    onPrimeracuotaHoyChange: function onPrimeracuotaHoyChange() {
      this.generar();
    },
    elegirPlan: function elegirPlan(n) {
      this.cant_cuota = n;
      this.generar();
    },
    onCantidadOtra: function onCantidadOtra(val) {
      if (val === '' || val === null) return;
      this.cant_cuota = val;
      this.onCantidadChange();
    },
    onCantidadChange: function onCantidadChange() {
      var n = parseInt(this.cant_cuota, 10);
      if (isNaN(n) || n < 1) n = 1;
      if (n > 60) n = 60;
      this.cant_cuota = n;
      this.generar();
    },
    onInteresChange: function onInteresChange() {
      var i = Number(this.interes);
      if (isNaN(i) || i < 0) i = 0;
      if (i > 100) i = 100;
      this.interes = i;
      this.generar();
    },
    setFrecuencia: function setFrecuencia(f) {
      if (this.frecuencia === f) return;
      this.frecuencia = f;
      this.onFrecuenciaChange();
    },
    entregaPctActiva: function entregaPctActiva(pct) {
      var t = Number(this.total) || 0;
      var e = Number(this.entrega) || 0;
      if (pct === 0) return e === 0;
      return t > 0 && e === Math.round(t * pct / 100);
    },
    esEntregaInicial: function esEntregaInicial(c) {
      return c.tipo === 'Entrega' && Number(this.entrega) > 0;
    },
    restablecer: function restablecer() {
      this.generar();
    },
    infoFecha: function infoFecha(venc) {
      var iso = this.toInputDate(venc);
      if (!iso) return '';
      var d = new Date(iso + 'T00:00:00');
      if (isNaN(d.getTime())) return '';
      var dias = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
      var base = this.fecha ? new Date(this.fecha + 'T00:00:00') : new Date();
      if (isNaN(base.getTime())) base = new Date();
      base.setHours(0, 0, 0, 0);
      var diff = Math.round((d - base) / 86400000);
      var rel = diff === 0 ? 'hoy' : diff > 0 ? 'en ' + diff + (diff === 1 ? ' día' : ' días') : 'hace ' + -diff + ' días';
      return dias[d.getDay()] + ' · ' + rel;
    },
    fechaFueraDeOrden: function fechaFueraDeOrden(idx) {
      if (idx === 0 || !this.cuotas[idx] || !this.cuotas[idx - 1]) return false;
      var actual = this.toInputDate(this.cuotas[idx].vencimiento);
      var previa = this.toInputDate(this.cuotas[idx - 1].vencimiento);
      return !!(actual && previa && actual < previa);
    },
    generar: function generar() {
      this.editadoManual = false;
      this.cuotas = [];
      var cantidad = Number(this.cant_cuota) || 1;
      if (cantidad < 1) cantidad = 1;
      var saldo = this.saldoConInteres;
      var entregaMonto = Number(this.entrega) || 0; // Fecha base de la venta

      var fechaVentaIso = this.fecha || this.formatDateIso(new Date()); // 1. Si hay Entrega Inicial, crear cuota de tipo Entrega

      if (entregaMonto > 0) {
        this.cuotas.push({
          nro: 1,
          interes: 0,
          vencimiento: this.toVencimientoFormat(fechaVentaIso),
          monto: entregaMonto,
          tipo: 'Entrega'
        });
      } // Si saldo a financiar es 0 (ej. pagó 100% de entrega), terminamos


      if (saldo <= 0) {
        this.emitCuotas();
        return;
      } // 2. Calcular monto base por cuota


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
      } // 3. Calcular fechas de vencimiento según frecuencia


      var dateCursor = this.primerVencimiento ? new Date(this.primerVencimiento + 'T00:00:00') : new Date();

      if (isNaN(dateCursor.getTime())) {
        dateCursor = new Date();
      }

      var nroOffset = entregaMonto > 0 ? 2 : 1;
      var totalAsignado = 0;
      var primeraHoy = this.primeracuotaHoy && entregaMonto === 0;

      for (var i = 0; i < cantidad; i++) {
        var cDate;

        if (i === 0 && primeraHoy) {
          cDate = new Date(fechaVentaIso + 'T00:00:00');
        } else {
          var pasos = primeraHoy ? i - 1 : i;

          if (this.frecuencia === 'semanal') {
            cDate = new Date(dateCursor);

            if (pasos > 0) {
              cDate.setDate(cDate.getDate() + pasos * 7);
            }
          } else if (this.frecuencia === 'quincenal') {
            cDate = new Date(dateCursor);

            if (pasos > 0) {
              cDate.setDate(cDate.getDate() + pasos * 15);
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
              cDate = this.sumarMeses(ancla, primeraHoy ? i : i + 1);
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
          tipo: i === 0 && primeraHoy ? 'Entrega' : 'Cuota'
        });
      }

      this.emitCuotas();
    },
    onVencimientoItemChange: function onVencimientoItemChange(idx, nuevaFechaIso) {
      if (this.cuotas[idx]) {
        this.$set(this.cuotas[idx], 'vencimiento', this.toVencimientoFormat(nuevaFechaIso));
        this.editadoManual = true;
        this.emitCuotas();
      }
    },
    onMontoItemChange: function onMontoItemChange(idx) {
      if (this.cuotas[idx]) {
        var val = Number(this.cuotas[idx].monto) || 0;
        this.$set(this.cuotas[idx], 'monto', val);
        this.editadoManual = true;
        this.emitCuotas();
      }
    },
    numeroVisible: function numeroVisible(idx) {
      if (!(Number(this.entrega) > 0)) {
        return idx + 1;
      }

      var n = 0;

      for (var i = 0; i <= idx; i++) {
        if (this.cuotas[i].tipo !== 'Entrega') n++;
      }

      return n;
    },
    eliminarCuota: function eliminarCuota(idx) {
      if (!this.cuotas[idx] || this.cuotas[idx].tipo === 'Entrega' || this.cantidadCuotasCredito <= 1) {
        return;
      }

      this.cuotas.splice(idx, 1); // Renumerar cuotas

      var num = 1;
      this.cuotas.forEach(function (c) {
        c.nro = num++;
      });
      this.cant_cuota = this.cuotas.filter(function (c) {
        return c.tipo === 'Cuota';
      }).length + (this.primeraHoyActiva ? 1 : 0);
      this.editadoManual = true;
      this.autoAjustarDiferencia();
      this.emitCuotas();
    },
    autoAjustarDiferencia: function autoAjustarDiferencia() {
      var diff = this.diferenciaCuotas;
      if (Math.abs(diff) < 1 || !this.cuotas.length) return; // Ajustar en la última cuota que no sea Entrega

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
    emitCuotas: function emitCuotas() {
      // Emitir copias para que el padre no comparta objetos con la edición del modal
      this.$emit('cuotas', this.cuotas.map(function (c) {
        return Object.assign({}, c);
      }));
    },
    getEstado: function getEstado() {
      return {
        cant_cuota: this.cant_cuota,
        entrega: this.entrega,
        interes: this.interes,
        frecuencia: this.frecuencia,
        primerVencimiento: this.primerVencimiento,
        primeracuotaHoy: this.primeracuotaHoy,
        redondear: this.redondear,
        cuotas: this.cuotas.map(function (c) {
          return Object.assign({}, c);
        })
      };
    },
    setEstado: function setEstado(estado) {
      if (!estado) return;
      this.cant_cuota = estado.cant_cuota;
      this.entrega = estado.entrega;
      this.interes = estado.interes;
      this.frecuencia = estado.frecuencia;
      this.primerVencimiento = estado.primerVencimiento;
      this.primeracuotaHoy = estado.primeracuotaHoy;
      this.redondear = estado.redondear;
      this.cuotas = (estado.cuotas || []).map(function (c) {
        return Object.assign({}, c);
      });
    }
  },
  mounted: function mounted() {
    if (this.datoscuota && this.datoscuota.cant_cuota) {
      this.cant_cuota = Number(this.datoscuota.cant_cuota) || 1;
    }

    this.initFechaVencimiento();
    this.generar();
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/in_number.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/in_number.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

//
//
//
var defaultClasses = {
  input: "form-control form-control-sm"
};
/* harmony default export */ __webpack_exports__["default"] = ({
  name: "inNumber",
  props: {
    value: {},
    placeholder: {},
    clases: {},
    id: {},
    tabindex: {},
    enabled: {}
  },
  data: function data() {
    return {
      text: ""
    };
  },
  computed: {
    inputClass: function inputClass() {
      if (typeof this.clases === 'string' && this.clases) {
        return this.clases;
      }

      if (this.clases && _typeof(this.clases) === 'object' && this.clases.input) {
        return this.clases.input;
      }

      return defaultClasses.input;
    }
  },
  watch: {
    value: function value(newVal) {
      if (newVal === null || newVal === undefined || newVal === '') {
        this.text = '';
        return;
      }

      this.text = this.addCommas(newVal.toString().replace(/,/g, '').replace(/\./g, ''));
    }
  },
  methods: {
    addCommas: function addCommas(nStr) {
      if (nStr === null || nStr === undefined) return '';
      var x, x1, x2;
      nStr += '';
      x = nStr.split('.');
      x1 = x[0];
      x2 = x.length > 1 ? ',' + x[1] : '';
      var rgx = /(\d+)(\d{3})/;

      while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
      }

      return x1 + x2;
    },
    isNumber: function isNumber(evt) {
      evt = evt ? evt : window.event;
      var charCode = evt.which ? evt.which : evt.keyCode;

      if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 46) {
        evt.preventDefault();
      } else {
        return true;
      }
    },
    keyup: function keyup() {
      if (this.text === "" || this.text === null) {
        this.$emit("input", 0);
        this.$emit("change", true);
        return true;
      }

      var clean = this.text.toString().replace(/,/g, '').replace(/\./g, '');

      if (clean.length > 1 && clean.substr(0, 1) === "0") {
        clean = clean.substr(1);
      }

      var parsed = parseInt(clean, 10);
      if (isNaN(parsed)) parsed = 0;
      this.$emit("input", parsed);
      this.$emit("change", true);
    }
  },
  mounted: function mounted() {
    if (this.value !== null && this.value !== undefined && this.value !== '') {
      this.text = this.addCommas(this.value.toString().replace(/,/g, '').replace(/\./g, ''));
    } else {
      this.text = '';
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/generar_cuota.vue?vue&type=style&index=0&id=5a0d83c4&scoped=true&lang=css&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/generar_cuota.vue?vue&type=style&index=0&id=5a0d83c4&scoped=true&lang=css& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.cuota-pos[data-v-5a0d83c4] {\n    --cp-primary: var(--dash-primary, #0a4d36);\n    --cp-primary-light: var(--dash-primary-light, #eaf3ef);\n    --cp-primary-border: var(--dash-primary-border, #c8dfd5);\n    --cp-accent: var(--dash-accent, #b8860b);\n    --cp-card: var(--dash-card-bg, #ffffff);\n    --cp-panel: var(--dash-panel-bg, #f8fafc);\n    --cp-border: var(--dash-border, #e2e8f0);\n    --cp-text: var(--dash-text-main, #1e293b);\n    --cp-muted: var(--dash-text-muted, #64748b);\n    --cp-danger: var(--dash-danger, #e11d48);\n    --cp-danger-light: var(--dash-danger-light, #fff1f2);\n    --cp-ok: #16a34a;\n    color: var(--cp-text);\n}\n\n/* ---- Pasos de configuración ---- */\n.cp-step[data-v-5a0d83c4] {\n    background: var(--cp-panel);\n    border: 1px solid var(--cp-border);\n    border-radius: 12px;\n    padding: 0.6rem 0.8rem;\n    margin-bottom: 0.5rem;\n}\n.cp-step-head[data-v-5a0d83c4] {\n    display: flex;\n    align-items: center;\n    margin-bottom: 0.45rem;\n}\n.cp-inline[data-v-5a0d83c4] {\n    display: flex;\n    align-items: center;\n    gap: 0.5rem;\n}\n.cp-inline > .cp-money[data-v-5a0d83c4] {\n    flex: 1 1 auto;\n    width: auto;\n    min-width: 120px;\n}\n.cp-inline > .cp-segmented[data-v-5a0d83c4] {\n    flex: 1 1 auto;\n}\n.cp-chips-nowrap[data-v-5a0d83c4] {\n    flex-wrap: nowrap;\n    flex-shrink: 0;\n}\n.cp-chips-nowrap .cp-chip[data-v-5a0d83c4] {\n    padding: 0.2rem 0.45rem;\n}\n.cp-options[data-v-5a0d83c4] {\n    justify-content: space-between;\n    flex-wrap: wrap;\n}\n.cp-switches[data-v-5a0d83c4] {\n    display: flex;\n    flex-direction: column;\n    gap: 0.15rem;\n}\n.cp-step-num[data-v-5a0d83c4] {\n    width: 22px;\n    height: 22px;\n    border-radius: 50%;\n    background: var(--cp-primary);\n    color: #fff;\n    font-size: 0.72rem;\n    font-weight: 700;\n    display: inline-flex;\n    align-items: center;\n    justify-content: center;\n    margin-right: 0.5rem;\n    flex-shrink: 0;\n}\n.cp-step-title[data-v-5a0d83c4] {\n    font-weight: 700;\n    font-size: 0.85rem;\n}\n.cp-step-opt[data-v-5a0d83c4] {\n    margin-left: auto;\n    font-size: 0.7rem;\n    color: var(--cp-muted);\n    text-transform: uppercase;\n    letter-spacing: 0.04em;\n}\n.cp-label[data-v-5a0d83c4] {\n    font-size: 0.8rem;\n    color: var(--cp-muted);\n    font-weight: 600;\n}\n.cp-hint[data-v-5a0d83c4] {\n    display: inline-block;\n    margin-top: 0.45rem;\n    font-size: 0.8rem;\n    color: var(--cp-muted);\n}\n.cp-hint strong[data-v-5a0d83c4] {\n    color: var(--cp-primary);\n}\n\n/* ---- Chips ---- */\n.cp-chips[data-v-5a0d83c4] {\n    display: flex;\n    flex-wrap: wrap;\n    gap: 0.35rem;\n}\n.cp-chip[data-v-5a0d83c4] {\n    border: 1px solid var(--cp-border);\n    background: var(--cp-card);\n    color: var(--cp-text);\n    border-radius: 999px;\n    padding: 0.2rem 0.7rem;\n    font-size: 0.78rem;\n    font-weight: 600;\n    line-height: 1.4;\n    cursor: pointer;\n    transition: background-color .12s, border-color .12s, color .12s;\n}\n.cp-chip[data-v-5a0d83c4]:hover {\n    border-color: var(--cp-primary);\n    color: var(--cp-primary);\n}\n.cp-chip.active[data-v-5a0d83c4],\n.cp-chip.active[data-v-5a0d83c4]:hover {\n    background: var(--cp-primary);\n    border-color: var(--cp-primary);\n    color: #fff;\n}\n.cp-chip-lg[data-v-5a0d83c4] {\n    min-width: 36px;\n    padding: 0.25rem 0.5rem;\n    font-size: 0.88rem;\n    border-radius: 9px;\n}\n.cp-chip-input[data-v-5a0d83c4] {\n    border: 1px dashed var(--cp-border);\n    border-radius: 10px;\n    background: var(--cp-card);\n    display: flex;\n    align-items: center;\n}\n.cp-chip-input.active[data-v-5a0d83c4] {\n    border: 2px solid var(--cp-primary);\n}\n.cp-chips-grid[data-v-5a0d83c4] {\n    display: grid;\n    grid-template-columns: repeat(5, 1fr);\n}\n.cp-chip-input input[data-v-5a0d83c4] {\n    width: 100%;\n    min-width: 0;\n    border: 0;\n    background: transparent;\n    color: var(--cp-text);\n    text-align: center;\n    font-weight: 700;\n    font-size: 0.9rem;\n    padding: 0.2rem 0.2rem;\n    outline: none;\n}\n\n/* ---- Control segmentado ---- */\n.cp-segmented[data-v-5a0d83c4] {\n    display: flex;\n    background: var(--cp-card);\n    border: 1px solid var(--cp-border);\n    border-radius: 10px;\n    padding: 3px;\n}\n.cp-segmented button[data-v-5a0d83c4] {\n    flex: 1;\n    border: 0;\n    background: transparent;\n    color: var(--cp-muted);\n    border-radius: 7px;\n    padding: 0.3rem 0.4rem;\n    font-size: 0.8rem;\n    font-weight: 600;\n    cursor: pointer;\n}\n.cp-segmented button.active[data-v-5a0d83c4] {\n    background: var(--cp-primary);\n    color: #fff;\n}\n.cp-date[data-v-5a0d83c4] {\n    width: 150px;\n    flex-shrink: 0;\n}\n.cp-interes[data-v-5a0d83c4] {\n    width: 105px;\n}\n.cp-money .input-group-text[data-v-5a0d83c4],\n.cp-interes .input-group-text[data-v-5a0d83c4] {\n    font-size: 0.8rem;\n}\n\n/* ---- Resumen ---- */\n.cp-hero[data-v-5a0d83c4] {\n    background: var(--cp-primary-light);\n    border: 1px solid var(--cp-primary-border);\n    border-radius: 12px;\n    padding: 0.65rem 0.9rem;\n    margin-bottom: 0.6rem;\n    display: flex;\n    align-items: center;\n    gap: 1rem;\n}\n.cp-hero-main[data-v-5a0d83c4] {\n    display: flex;\n    flex-direction: column;\n    flex: 1 1 auto;\n    min-width: 0;\n}\n.cp-hero-label[data-v-5a0d83c4] {\n    font-size: 0.72rem;\n    font-weight: 700;\n    text-transform: uppercase;\n    letter-spacing: 0.05em;\n    color: var(--cp-muted);\n}\n.cp-hero-total[data-v-5a0d83c4] {\n    font-size: 1.5rem;\n    font-weight: 800;\n    color: var(--cp-primary);\n    font-variant-numeric: tabular-nums;\n    line-height: 1.2;\n}\n.cp-hero-sub[data-v-5a0d83c4] {\n    font-size: 0.82rem;\n    color: var(--cp-text);\n}\n.cp-hero-grid[data-v-5a0d83c4] {\n    display: grid;\n    grid-template-columns: repeat(2, minmax(90px, auto));\n    gap: 0.3rem 1rem;\n    padding-left: 1rem;\n    border-left: 1px solid var(--cp-primary-border);\n    flex-shrink: 0;\n}\n.cp-hero-grid div[data-v-5a0d83c4] {\n    display: flex;\n    flex-direction: column;\n    min-width: 0;\n}\n.cp-hero-grid span[data-v-5a0d83c4] {\n    font-size: 0.68rem;\n    text-transform: uppercase;\n    letter-spacing: 0.04em;\n    color: var(--cp-muted);\n}\n.cp-hero-grid strong[data-v-5a0d83c4] {\n    font-size: 0.85rem;\n    font-variant-numeric: tabular-nums;\n    white-space: nowrap;\n    overflow: hidden;\n    text-overflow: ellipsis;\n}\n.cp-pos[data-v-5a0d83c4] { color: var(--cp-ok);\n}\n.cp-neg[data-v-5a0d83c4] { color: var(--cp-accent);\n}\n\n/* ---- Cronograma ---- */\n.cp-schedule-head[data-v-5a0d83c4] {\n    display: flex;\n    align-items: center;\n    justify-content: space-between;\n    margin-bottom: 0.4rem;\n    min-height: 24px;\n}\n.cp-reset[data-v-5a0d83c4] {\n    font-size: 0.8rem;\n    color: var(--cp-primary);\n}\n.cp-schedule[data-v-5a0d83c4] {\n    border: 1px solid var(--cp-border);\n    border-radius: 12px;\n    background: var(--cp-card);\n    max-height: 300px;\n    max-height: clamp(170px, calc(100vh - 400px), 380px);\n    overflow-y: auto;\n}\n.cp-row[data-v-5a0d83c4] {\n    display: flex;\n    align-items: center;\n    gap: 0.5rem;\n    padding: 0.35rem 0.6rem;\n    border-bottom: 1px solid var(--cp-border);\n}\n.cp-row[data-v-5a0d83c4]:last-child {\n    border-bottom: 0;\n}\n.cp-row[data-v-5a0d83c4]:hover {\n    background: var(--cp-panel);\n}\n.cp-row-hoy[data-v-5a0d83c4],\n.cp-row-hoy[data-v-5a0d83c4]:hover {\n    background: rgba(22, 163, 74, 0.08);\n}\n.cp-row-error[data-v-5a0d83c4],\n.cp-row-error[data-v-5a0d83c4]:hover {\n    background: var(--cp-danger-light);\n}\n.cp-row-badge[data-v-5a0d83c4] {\n    width: 28px;\n    height: 28px;\n    border-radius: 8px;\n    background: var(--cp-panel);\n    border: 1px solid var(--cp-border);\n    display: flex;\n    align-items: center;\n    justify-content: center;\n    font-size: 0.78rem;\n    font-weight: 700;\n    flex-shrink: 0;\n}\n.cp-row-hoy .cp-row-badge[data-v-5a0d83c4] {\n    background: var(--cp-ok);\n    border-color: var(--cp-ok);\n    color: #fff;\n}\n.cp-row-info[data-v-5a0d83c4] {\n    flex: 1;\n    min-width: 0;\n    display: flex;\n    flex-direction: column;\n    line-height: 1.25;\n}\n.cp-row-title[data-v-5a0d83c4] {\n    font-size: 0.84rem;\n    font-weight: 600;\n}\n.cp-row-meta[data-v-5a0d83c4] {\n    font-size: 0.72rem;\n    color: var(--cp-muted);\n}\n.cp-row-hoy .cp-row-meta[data-v-5a0d83c4] {\n    color: var(--cp-ok);\n}\n.cp-row-date[data-v-5a0d83c4] {\n    width: 145px;\n    flex-shrink: 0;\n}\n.cp-row-amount[data-v-5a0d83c4] {\n    width: 125px;\n    flex-shrink: 0;\n}\n.cp-row-action[data-v-5a0d83c4] {\n    width: 26px;\n    flex-shrink: 0;\n    text-align: center;\n}\n.cp-icon-btn[data-v-5a0d83c4] {\n    border: 0;\n    background: transparent;\n    color: var(--cp-muted);\n    padding: 0.2rem 0.3rem;\n    border-radius: 6px;\n    cursor: pointer;\n    opacity: 0.5;\n}\n.cp-row:hover .cp-icon-btn[data-v-5a0d83c4]:not(:disabled),\n.cp-icon-btn[data-v-5a0d83c4]:focus {\n    opacity: 1;\n}\n.cp-icon-btn[data-v-5a0d83c4]:hover:not(:disabled) {\n    color: var(--cp-danger);\n    background: var(--cp-danger-light);\n}\n.cp-icon-btn[data-v-5a0d83c4]:disabled {\n    opacity: 0.2;\n    cursor: not-allowed;\n}\n.cp-empty[data-v-5a0d83c4] {\n    text-align: center;\n    color: var(--cp-muted);\n    padding: 2rem 1rem;\n}\n\n/* ---- Balance ---- */\n.cp-balance[data-v-5a0d83c4] {\n    display: flex;\n    flex-wrap: wrap;\n    gap: 0.5rem;\n    align-items: center;\n    justify-content: space-between;\n    border-radius: 12px;\n    padding: 0.45rem 0.85rem;\n    margin-top: 0.5rem;\n    font-size: 0.85rem;\n}\n.cp-balance.is-ok[data-v-5a0d83c4] {\n    background: rgba(22, 163, 74, 0.1);\n    border: 1px solid rgba(22, 163, 74, 0.3);\n    color: #15803d;\n}\n.cp-balance.is-warning[data-v-5a0d83c4] {\n    background: rgba(234, 179, 8, 0.12);\n    border: 1px solid rgba(234, 179, 8, 0.45);\n    color: #854d0e;\n}\n.cp-balance .fas[data-v-5a0d83c4] {\n    font-size: 1.2rem;\n}\n@media (max-width: 575.98px) {\n.cp-hero[data-v-5a0d83c4] { flex-direction: column; align-items: stretch; gap: 0.5rem;\n}\n.cp-hero-grid[data-v-5a0d83c4] { grid-template-columns: repeat(2, 1fr); padding-left: 0; border-left: 0; padding-top: 0.5rem; border-top: 1px solid var(--cp-primary-border);\n}\n.cp-inline[data-v-5a0d83c4]:not(.cp-options) { flex-wrap: wrap;\n}\n.cp-inline > .cp-money[data-v-5a0d83c4], .cp-inline > .cp-segmented[data-v-5a0d83c4] { flex-basis: 100%;\n}\n.cp-date[data-v-5a0d83c4] { width: 100%;\n}\n.cp-row[data-v-5a0d83c4] { flex-wrap: wrap;\n}\n.cp-row-info[data-v-5a0d83c4] { flex-basis: calc(100% - 75px);\n}\n.cp-row-date[data-v-5a0d83c4], .cp-row-amount[data-v-5a0d83c4] { flex: 1 1 40%; width: auto;\n}\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/css-loader/lib/css-base.js":
/*!*************************************************!*\
  !*** ./node_modules/css-loader/lib/css-base.js ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
module.exports = function(useSourceMap) {
	var list = [];

	// return the list of modules as css string
	list.toString = function toString() {
		return this.map(function (item) {
			var content = cssWithMappingToString(item, useSourceMap);
			if(item[2]) {
				return "@media " + item[2] + "{" + content + "}";
			} else {
				return content;
			}
		}).join("");
	};

	// import a list of modules into the list
	list.i = function(modules, mediaQuery) {
		if(typeof modules === "string")
			modules = [[null, modules, ""]];
		var alreadyImportedModules = {};
		for(var i = 0; i < this.length; i++) {
			var id = this[i][0];
			if(typeof id === "number")
				alreadyImportedModules[id] = true;
		}
		for(i = 0; i < modules.length; i++) {
			var item = modules[i];
			// skip already imported module
			// this implementation is not 100% perfect for weird media query combinations
			//  when a module is imported multiple times with different media queries.
			//  I hope this will never occur (Hey this way we have smaller bundles)
			if(typeof item[0] !== "number" || !alreadyImportedModules[item[0]]) {
				if(mediaQuery && !item[2]) {
					item[2] = mediaQuery;
				} else if(mediaQuery) {
					item[2] = "(" + item[2] + ") and (" + mediaQuery + ")";
				}
				list.push(item);
			}
		}
	};
	return list;
};

function cssWithMappingToString(item, useSourceMap) {
	var content = item[1] || '';
	var cssMapping = item[3];
	if (!cssMapping) {
		return content;
	}

	if (useSourceMap && typeof btoa === 'function') {
		var sourceMapping = toComment(cssMapping);
		var sourceURLs = cssMapping.sources.map(function (source) {
			return '/*# sourceURL=' + cssMapping.sourceRoot + source + ' */'
		});

		return [content].concat(sourceURLs).concat([sourceMapping]).join('\n');
	}

	return [content].join('\n');
}

// Adapted from convert-source-map (MIT)
function toComment(sourceMap) {
	// eslint-disable-next-line no-undef
	var base64 = btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap))));
	var data = 'sourceMappingURL=data:application/json;charset=utf-8;base64,' + base64;

	return '/*# ' + data + ' */';
}


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/generar_cuota.vue?vue&type=style&index=0&id=5a0d83c4&scoped=true&lang=css&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--6-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--6-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/generar_cuota.vue?vue&type=style&index=0&id=5a0d83c4&scoped=true&lang=css& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../node_modules/css-loader??ref--6-1!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/src??ref--6-2!../../../node_modules/vue-loader/lib??vue-loader-options!./generar_cuota.vue?vue&type=style&index=0&id=5a0d83c4&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/generar_cuota.vue?vue&type=style&index=0&id=5a0d83c4&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/style-loader/lib/addStyles.js":
/*!****************************************************!*\
  !*** ./node_modules/style-loader/lib/addStyles.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/

var stylesInDom = {};

var	memoize = function (fn) {
	var memo;

	return function () {
		if (typeof memo === "undefined") memo = fn.apply(this, arguments);
		return memo;
	};
};

var isOldIE = memoize(function () {
	// Test for IE <= 9 as proposed by Browserhacks
	// @see http://browserhacks.com/#hack-e71d8692f65334173fee715c222cb805
	// Tests for existence of standard globals is to allow style-loader
	// to operate correctly into non-standard environments
	// @see https://github.com/webpack-contrib/style-loader/issues/177
	return window && document && document.all && !window.atob;
});

var getTarget = function (target, parent) {
  if (parent){
    return parent.querySelector(target);
  }
  return document.querySelector(target);
};

var getElement = (function (fn) {
	var memo = {};

	return function(target, parent) {
                // If passing function in options, then use it for resolve "head" element.
                // Useful for Shadow Root style i.e
                // {
                //   insertInto: function () { return document.querySelector("#foo").shadowRoot }
                // }
                if (typeof target === 'function') {
                        return target();
                }
                if (typeof memo[target] === "undefined") {
			var styleTarget = getTarget.call(this, target, parent);
			// Special case to return head of iframe instead of iframe itself
			if (window.HTMLIFrameElement && styleTarget instanceof window.HTMLIFrameElement) {
				try {
					// This will throw an exception if access to iframe is blocked
					// due to cross-origin restrictions
					styleTarget = styleTarget.contentDocument.head;
				} catch(e) {
					styleTarget = null;
				}
			}
			memo[target] = styleTarget;
		}
		return memo[target]
	};
})();

var singleton = null;
var	singletonCounter = 0;
var	stylesInsertedAtTop = [];

var	fixUrls = __webpack_require__(/*! ./urls */ "./node_modules/style-loader/lib/urls.js");

module.exports = function(list, options) {
	if (typeof DEBUG !== "undefined" && DEBUG) {
		if (typeof document !== "object") throw new Error("The style-loader cannot be used in a non-browser environment");
	}

	options = options || {};

	options.attrs = typeof options.attrs === "object" ? options.attrs : {};

	// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
	// tags it will allow on a page
	if (!options.singleton && typeof options.singleton !== "boolean") options.singleton = isOldIE();

	// By default, add <style> tags to the <head> element
        if (!options.insertInto) options.insertInto = "head";

	// By default, add <style> tags to the bottom of the target
	if (!options.insertAt) options.insertAt = "bottom";

	var styles = listToStyles(list, options);

	addStylesToDom(styles, options);

	return function update (newList) {
		var mayRemove = [];

		for (var i = 0; i < styles.length; i++) {
			var item = styles[i];
			var domStyle = stylesInDom[item.id];

			domStyle.refs--;
			mayRemove.push(domStyle);
		}

		if(newList) {
			var newStyles = listToStyles(newList, options);
			addStylesToDom(newStyles, options);
		}

		for (var i = 0; i < mayRemove.length; i++) {
			var domStyle = mayRemove[i];

			if(domStyle.refs === 0) {
				for (var j = 0; j < domStyle.parts.length; j++) domStyle.parts[j]();

				delete stylesInDom[domStyle.id];
			}
		}
	};
};

function addStylesToDom (styles, options) {
	for (var i = 0; i < styles.length; i++) {
		var item = styles[i];
		var domStyle = stylesInDom[item.id];

		if(domStyle) {
			domStyle.refs++;

			for(var j = 0; j < domStyle.parts.length; j++) {
				domStyle.parts[j](item.parts[j]);
			}

			for(; j < item.parts.length; j++) {
				domStyle.parts.push(addStyle(item.parts[j], options));
			}
		} else {
			var parts = [];

			for(var j = 0; j < item.parts.length; j++) {
				parts.push(addStyle(item.parts[j], options));
			}

			stylesInDom[item.id] = {id: item.id, refs: 1, parts: parts};
		}
	}
}

function listToStyles (list, options) {
	var styles = [];
	var newStyles = {};

	for (var i = 0; i < list.length; i++) {
		var item = list[i];
		var id = options.base ? item[0] + options.base : item[0];
		var css = item[1];
		var media = item[2];
		var sourceMap = item[3];
		var part = {css: css, media: media, sourceMap: sourceMap};

		if(!newStyles[id]) styles.push(newStyles[id] = {id: id, parts: [part]});
		else newStyles[id].parts.push(part);
	}

	return styles;
}

function insertStyleElement (options, style) {
	var target = getElement(options.insertInto)

	if (!target) {
		throw new Error("Couldn't find a style target. This probably means that the value for the 'insertInto' parameter is invalid.");
	}

	var lastStyleElementInsertedAtTop = stylesInsertedAtTop[stylesInsertedAtTop.length - 1];

	if (options.insertAt === "top") {
		if (!lastStyleElementInsertedAtTop) {
			target.insertBefore(style, target.firstChild);
		} else if (lastStyleElementInsertedAtTop.nextSibling) {
			target.insertBefore(style, lastStyleElementInsertedAtTop.nextSibling);
		} else {
			target.appendChild(style);
		}
		stylesInsertedAtTop.push(style);
	} else if (options.insertAt === "bottom") {
		target.appendChild(style);
	} else if (typeof options.insertAt === "object" && options.insertAt.before) {
		var nextSibling = getElement(options.insertAt.before, target);
		target.insertBefore(style, nextSibling);
	} else {
		throw new Error("[Style Loader]\n\n Invalid value for parameter 'insertAt' ('options.insertAt') found.\n Must be 'top', 'bottom', or Object.\n (https://github.com/webpack-contrib/style-loader#insertat)\n");
	}
}

function removeStyleElement (style) {
	if (style.parentNode === null) return false;
	style.parentNode.removeChild(style);

	var idx = stylesInsertedAtTop.indexOf(style);
	if(idx >= 0) {
		stylesInsertedAtTop.splice(idx, 1);
	}
}

function createStyleElement (options) {
	var style = document.createElement("style");

	if(options.attrs.type === undefined) {
		options.attrs.type = "text/css";
	}

	if(options.attrs.nonce === undefined) {
		var nonce = getNonce();
		if (nonce) {
			options.attrs.nonce = nonce;
		}
	}

	addAttrs(style, options.attrs);
	insertStyleElement(options, style);

	return style;
}

function createLinkElement (options) {
	var link = document.createElement("link");

	if(options.attrs.type === undefined) {
		options.attrs.type = "text/css";
	}
	options.attrs.rel = "stylesheet";

	addAttrs(link, options.attrs);
	insertStyleElement(options, link);

	return link;
}

function addAttrs (el, attrs) {
	Object.keys(attrs).forEach(function (key) {
		el.setAttribute(key, attrs[key]);
	});
}

function getNonce() {
	if (false) {}

	return __webpack_require__.nc;
}

function addStyle (obj, options) {
	var style, update, remove, result;

	// If a transform function was defined, run it on the css
	if (options.transform && obj.css) {
	    result = typeof options.transform === 'function'
		 ? options.transform(obj.css) 
		 : options.transform.default(obj.css);

	    if (result) {
	    	// If transform returns a value, use that instead of the original css.
	    	// This allows running runtime transformations on the css.
	    	obj.css = result;
	    } else {
	    	// If the transform function returns a falsy value, don't add this css.
	    	// This allows conditional loading of css
	    	return function() {
	    		// noop
	    	};
	    }
	}

	if (options.singleton) {
		var styleIndex = singletonCounter++;

		style = singleton || (singleton = createStyleElement(options));

		update = applyToSingletonTag.bind(null, style, styleIndex, false);
		remove = applyToSingletonTag.bind(null, style, styleIndex, true);

	} else if (
		obj.sourceMap &&
		typeof URL === "function" &&
		typeof URL.createObjectURL === "function" &&
		typeof URL.revokeObjectURL === "function" &&
		typeof Blob === "function" &&
		typeof btoa === "function"
	) {
		style = createLinkElement(options);
		update = updateLink.bind(null, style, options);
		remove = function () {
			removeStyleElement(style);

			if(style.href) URL.revokeObjectURL(style.href);
		};
	} else {
		style = createStyleElement(options);
		update = applyToTag.bind(null, style);
		remove = function () {
			removeStyleElement(style);
		};
	}

	update(obj);

	return function updateStyle (newObj) {
		if (newObj) {
			if (
				newObj.css === obj.css &&
				newObj.media === obj.media &&
				newObj.sourceMap === obj.sourceMap
			) {
				return;
			}

			update(obj = newObj);
		} else {
			remove();
		}
	};
}

var replaceText = (function () {
	var textStore = [];

	return function (index, replacement) {
		textStore[index] = replacement;

		return textStore.filter(Boolean).join('\n');
	};
})();

function applyToSingletonTag (style, index, remove, obj) {
	var css = remove ? "" : obj.css;

	if (style.styleSheet) {
		style.styleSheet.cssText = replaceText(index, css);
	} else {
		var cssNode = document.createTextNode(css);
		var childNodes = style.childNodes;

		if (childNodes[index]) style.removeChild(childNodes[index]);

		if (childNodes.length) {
			style.insertBefore(cssNode, childNodes[index]);
		} else {
			style.appendChild(cssNode);
		}
	}
}

function applyToTag (style, obj) {
	var css = obj.css;
	var media = obj.media;

	if(media) {
		style.setAttribute("media", media)
	}

	if(style.styleSheet) {
		style.styleSheet.cssText = css;
	} else {
		while(style.firstChild) {
			style.removeChild(style.firstChild);
		}

		style.appendChild(document.createTextNode(css));
	}
}

function updateLink (link, options, obj) {
	var css = obj.css;
	var sourceMap = obj.sourceMap;

	/*
		If convertToAbsoluteUrls isn't defined, but sourcemaps are enabled
		and there is no publicPath defined then lets turn convertToAbsoluteUrls
		on by default.  Otherwise default to the convertToAbsoluteUrls option
		directly
	*/
	var autoFixUrls = options.convertToAbsoluteUrls === undefined && sourceMap;

	if (options.convertToAbsoluteUrls || autoFixUrls) {
		css = fixUrls(css);
	}

	if (sourceMap) {
		// http://stackoverflow.com/a/26603875
		css += "\n/*# sourceMappingURL=data:application/json;base64," + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + " */";
	}

	var blob = new Blob([css], { type: "text/css" });

	var oldSrc = link.href;

	link.href = URL.createObjectURL(blob);

	if(oldSrc) URL.revokeObjectURL(oldSrc);
}


/***/ }),

/***/ "./node_modules/style-loader/lib/urls.js":
/*!***********************************************!*\
  !*** ./node_modules/style-loader/lib/urls.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {


/**
 * When source maps are enabled, `style-loader` uses a link element with a data-uri to
 * embed the css on the page. This breaks all relative urls because now they are relative to a
 * bundle instead of the current page.
 *
 * One solution is to only use full urls, but that may be impossible.
 *
 * Instead, this function "fixes" the relative urls to be absolute according to the current page location.
 *
 * A rudimentary test suite is located at `test/fixUrls.js` and can be run via the `npm test` command.
 *
 */

module.exports = function (css) {
  // get current location
  var location = typeof window !== "undefined" && window.location;

  if (!location) {
    throw new Error("fixUrls requires window.location");
  }

	// blank or null?
	if (!css || typeof css !== "string") {
	  return css;
  }

  var baseUrl = location.protocol + "//" + location.host;
  var currentDir = baseUrl + location.pathname.replace(/\/[^\/]*$/, "/");

	// convert each url(...)
	/*
	This regular expression is just a way to recursively match brackets within
	a string.

	 /url\s*\(  = Match on the word "url" with any whitespace after it and then a parens
	   (  = Start a capturing group
	     (?:  = Start a non-capturing group
	         [^)(]  = Match anything that isn't a parentheses
	         |  = OR
	         \(  = Match a start parentheses
	             (?:  = Start another non-capturing groups
	                 [^)(]+  = Match anything that isn't a parentheses
	                 |  = OR
	                 \(  = Match a start parentheses
	                     [^)(]*  = Match anything that isn't a parentheses
	                 \)  = Match a end parentheses
	             )  = End Group
              *\) = Match anything and then a close parens
          )  = Close non-capturing group
          *  = Match anything
       )  = Close capturing group
	 \)  = Match a close parens

	 /gi  = Get all matches, not the first.  Be case insensitive.
	 */
	var fixedCss = css.replace(/url\s*\(((?:[^)(]|\((?:[^)(]+|\([^)(]*\))*\))*)\)/gi, function(fullMatch, origUrl) {
		// strip quotes (if they exist)
		var unquotedOrigUrl = origUrl
			.trim()
			.replace(/^"(.*)"$/, function(o, $1){ return $1; })
			.replace(/^'(.*)'$/, function(o, $1){ return $1; });

		// already a full url? no change
		if (/^(#|data:|http:\/\/|https:\/\/|file:\/\/\/|\s*$)/i.test(unquotedOrigUrl)) {
		  return fullMatch;
		}

		// convert the url to a full url
		var newUrl;

		if (unquotedOrigUrl.indexOf("//") === 0) {
		  	//TODO: should we add protocol?
			newUrl = unquotedOrigUrl;
		} else if (unquotedOrigUrl.indexOf("/") === 0) {
			// path should be relative to the base url
			newUrl = baseUrl + unquotedOrigUrl; // already starts with '/'
		} else {
			// path should be relative to current directory
			newUrl = currentDir + unquotedOrigUrl.replace(/^\.\//, ""); // Strip leading './'
		}

		// send back the fixed url(...)
		return "url(" + JSON.stringify(newUrl) + ")";
	});

	// send back the fixed css
	return fixedCss;
};


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/generar_cuota.vue?vue&type=template&id=5a0d83c4&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/generar_cuota.vue?vue&type=template&id=5a0d83c4&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "cuota-pos" }, [
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "col-lg-5 mb-3 mb-lg-0" }, [
        _c("section", { staticClass: "cp-step" }, [
          _vm._m(0),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "cp-chips cp-chips-grid" },
            [
              _vm._l(_vm.opcionesCuotas, function(n) {
                return _c(
                  "button",
                  {
                    key: n,
                    staticClass: "cp-chip cp-chip-lg",
                    class: { active: Number(_vm.cant_cuota) === n },
                    attrs: { type: "button" },
                    on: {
                      click: function($event) {
                        return _vm.elegirPlan(n)
                      }
                    }
                  },
                  [_vm._v(_vm._s(n))]
                )
              }),
              _vm._v(" "),
              _c(
                "div",
                {
                  staticClass: "cp-chip-input",
                  class: {
                    active:
                      _vm.opcionesCuotas.indexOf(Number(_vm.cant_cuota)) === -1
                  }
                },
                [
                  _c("input", {
                    attrs: {
                      type: "number",
                      min: "1",
                      max: "60",
                      placeholder: "Otra",
                      "aria-label": "Otra cantidad de cuotas",
                      title: "Otra cantidad (1 a 60)"
                    },
                    domProps: {
                      value:
                        _vm.opcionesCuotas.indexOf(Number(_vm.cant_cuota)) ===
                        -1
                          ? _vm.cant_cuota
                          : ""
                    },
                    on: {
                      change: function($event) {
                        return _vm.onCantidadOtra($event.target.value)
                      }
                    }
                  })
                ]
              )
            ],
            2
          )
        ]),
        _vm._v(" "),
        _c("section", { staticClass: "cp-step" }, [
          _vm._m(1),
          _vm._v(" "),
          _c("div", { staticClass: "cp-inline" }, [
            _c(
              "div",
              { staticClass: "input-group input-group-sm cp-money" },
              [
                _vm._m(2),
                _vm._v(" "),
                _c("in-number", {
                  attrs: {
                    clases: "form-control font-weight-bold",
                    placeholder: "0"
                  },
                  on: { change: _vm.onEntregaChange },
                  model: {
                    value: _vm.entrega,
                    callback: function($$v) {
                      _vm.entrega = $$v
                    },
                    expression: "entrega"
                  }
                })
              ],
              1
            ),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "cp-chips cp-chips-nowrap" },
              _vm._l(_vm.opcionesEntrega, function(p) {
                return _c(
                  "button",
                  {
                    key: p,
                    staticClass: "cp-chip",
                    class: { active: _vm.entregaPctActiva(p) },
                    attrs: {
                      type: "button",
                      title: p === 0 ? "Sin entrega" : "Entrega del " + p + "%"
                    },
                    on: {
                      click: function($event) {
                        return _vm.setEntregaPct(p)
                      }
                    }
                  },
                  [_vm._v(_vm._s(p) + "%")]
                )
              }),
              0
            )
          ])
        ]),
        _vm._v(" "),
        _c("section", { staticClass: "cp-step" }, [
          _c("div", { staticClass: "cp-step-head" }, [
            _c("span", { staticClass: "cp-step-num" }, [_vm._v("3")]),
            _vm._v(" "),
            _c("span", { staticClass: "cp-step-title" }, [
              _vm._v(
                "Frecuencia y " +
                  _vm._s(_vm.primeraHoyActiva ? "2º" : "1er") +
                  " vencimiento"
              )
            ])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "cp-inline" }, [
            _c(
              "div",
              {
                staticClass: "cp-segmented",
                attrs: { role: "group", "aria-label": "Frecuencia de pago" }
              },
              _vm._l(_vm.opcionesFrecuencia, function(f) {
                return _c(
                  "button",
                  {
                    key: f.value,
                    class: { active: _vm.frecuencia === f.value },
                    attrs: { type: "button", title: f.title },
                    on: {
                      click: function($event) {
                        return _vm.setFrecuencia(f.value)
                      }
                    }
                  },
                  [_vm._v(_vm._s(f.label))]
                )
              }),
              0
            ),
            _vm._v(" "),
            _c("input", {
              directives: [
                {
                  name: "model",
                  rawName: "v-model",
                  value: _vm.primerVencimiento,
                  expression: "primerVencimiento"
                }
              ],
              staticClass: "form-control form-control-sm cp-date",
              attrs: {
                id: "cpPrimerVenc",
                type: "date",
                "aria-label": _vm.primeraHoyActiva
                  ? "Segundo vencimiento"
                  : "Primer vencimiento",
                title: _vm.primeraHoyActiva
                  ? "Segundo vencimiento"
                  : "Primer vencimiento"
              },
              domProps: { value: _vm.primerVencimiento },
              on: {
                change: _vm.generar,
                input: function($event) {
                  if ($event.target.composing) {
                    return
                  }
                  _vm.primerVencimiento = $event.target.value
                }
              }
            })
          ])
        ]),
        _vm._v(" "),
        _c("section", { staticClass: "cp-step mb-0" }, [
          _c("div", { staticClass: "cp-step-head" }, [
            _c("span", { staticClass: "cp-step-num" }, [_vm._v("4")]),
            _vm._v(" "),
            _c("span", { staticClass: "cp-step-title" }, [_vm._v("Opciones")]),
            _vm._v(" "),
            _vm.montoInteres > 0
              ? _c("span", { staticClass: "cp-step-opt cp-neg" }, [
                  _vm._v(
                    "+ Gs. " +
                      _vm._s(_vm.formatGs(_vm.montoInteres)) +
                      " de interés"
                  )
                ])
              : _vm._e()
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "cp-inline cp-options" }, [
            _c("div", { staticClass: "d-flex align-items-center" }, [
              _c(
                "label",
                {
                  staticClass: "cp-label mb-0 mr-2",
                  attrs: { for: "cpInteres" }
                },
                [_vm._v("Interés")]
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "input-group input-group-sm cp-interes" },
                [
                  _c("input", {
                    directives: [
                      {
                        name: "model",
                        rawName: "v-model.number",
                        value: _vm.interes,
                        expression: "interes",
                        modifiers: { number: true }
                      }
                    ],
                    staticClass: "form-control font-weight-bold text-right",
                    attrs: {
                      id: "cpInteres",
                      type: "number",
                      min: "0",
                      max: "100",
                      step: "0.5",
                      placeholder: "0"
                    },
                    domProps: { value: _vm.interes },
                    on: {
                      change: _vm.onInteresChange,
                      input: function($event) {
                        if ($event.target.composing) {
                          return
                        }
                        _vm.interes = _vm._n($event.target.value)
                      },
                      blur: function($event) {
                        return _vm.$forceUpdate()
                      }
                    }
                  }),
                  _vm._v(" "),
                  _vm._m(3)
                ]
              )
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "cp-switches" }, [
              _c("div", { staticClass: "custom-control custom-switch" }, [
                _c("input", {
                  directives: [
                    {
                      name: "model",
                      rawName: "v-model",
                      value: _vm.redondear,
                      expression: "redondear"
                    }
                  ],
                  staticClass: "custom-control-input",
                  attrs: { type: "checkbox", id: "checkRedondear" },
                  domProps: {
                    checked: Array.isArray(_vm.redondear)
                      ? _vm._i(_vm.redondear, null) > -1
                      : _vm.redondear
                  },
                  on: {
                    change: [
                      function($event) {
                        var $$a = _vm.redondear,
                          $$el = $event.target,
                          $$c = $$el.checked ? true : false
                        if (Array.isArray($$a)) {
                          var $$v = null,
                            $$i = _vm._i($$a, $$v)
                          if ($$el.checked) {
                            $$i < 0 && (_vm.redondear = $$a.concat([$$v]))
                          } else {
                            $$i > -1 &&
                              (_vm.redondear = $$a
                                .slice(0, $$i)
                                .concat($$a.slice($$i + 1)))
                          }
                        } else {
                          _vm.redondear = $$c
                        }
                      },
                      _vm.generar
                    ]
                  }
                }),
                _vm._v(" "),
                _c(
                  "label",
                  {
                    staticClass: "custom-control-label small",
                    attrs: { for: "checkRedondear" }
                  },
                  [_vm._v("Redondear a miles")]
                )
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "custom-control custom-switch" }, [
                _c("input", {
                  directives: [
                    {
                      name: "model",
                      rawName: "v-model",
                      value: _vm.primeracuotaHoy,
                      expression: "primeracuotaHoy"
                    }
                  ],
                  staticClass: "custom-control-input",
                  attrs: {
                    type: "checkbox",
                    id: "checkPrimeracuota",
                    disabled: Number(_vm.entrega) > 0
                  },
                  domProps: {
                    checked: Array.isArray(_vm.primeracuotaHoy)
                      ? _vm._i(_vm.primeracuotaHoy, null) > -1
                      : _vm.primeracuotaHoy
                  },
                  on: {
                    change: [
                      function($event) {
                        var $$a = _vm.primeracuotaHoy,
                          $$el = $event.target,
                          $$c = $$el.checked ? true : false
                        if (Array.isArray($$a)) {
                          var $$v = null,
                            $$i = _vm._i($$a, $$v)
                          if ($$el.checked) {
                            $$i < 0 && (_vm.primeracuotaHoy = $$a.concat([$$v]))
                          } else {
                            $$i > -1 &&
                              (_vm.primeracuotaHoy = $$a
                                .slice(0, $$i)
                                .concat($$a.slice($$i + 1)))
                          }
                        } else {
                          _vm.primeracuotaHoy = $$c
                        }
                      },
                      _vm.onPrimeracuotaHoyChange
                    ]
                  }
                }),
                _vm._v(" "),
                _c(
                  "label",
                  {
                    staticClass: "custom-control-label small",
                    attrs: {
                      for: "checkPrimeracuota",
                      title:
                        Number(_vm.entrega) > 0
                          ? "No aplica cuando hay entrega inicial"
                          : ""
                    }
                  },
                  [_vm._v("1ª cuota hoy")]
                )
              ])
            ])
          ])
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "col-lg-7" }, [
        _c("div", { staticClass: "cp-hero" }, [
          _c("div", { staticClass: "cp-hero-main" }, [
            _c("span", { staticClass: "cp-hero-label" }, [
              _vm._v("Total a pagar")
            ]),
            _vm._v(" "),
            _c("span", { staticClass: "cp-hero-total" }, [
              _vm._v("Gs. " + _vm._s(_vm.formatGs(_vm.totalAFinanciar)))
            ]),
            _vm._v(" "),
            _c(
              "span",
              { staticClass: "cp-hero-sub" },
              [
                Number(_vm.entrega) > 0 ? [_vm._v("Entrega + ")] : _vm._e(),
                _vm._v(_vm._s(_vm.cuotasPlan.length) + " × "),
                _c("strong", [
                  _vm._v(
                    "Gs. " + _vm._s(_vm.formatGs(_vm.montoCuotaReferencia))
                  )
                ]),
                _vm.cuotasDesiguales ? [_vm._v(" aprox.")] : _vm._e(),
                _vm._v(" "),
                _vm.primeraHoyActiva ? [_vm._v(" · 1ª hoy")] : _vm._e()
              ],
              2
            )
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "cp-hero-grid" }, [
            _c("div", [
              _c("span", [_vm._v("Total venta")]),
              _c("strong", [_vm._v(_vm._s(_vm.formatGs(_vm.total)))])
            ]),
            _vm._v(" "),
            _c("div", [
              _c("span", [_vm._v("Entrega")]),
              _c("strong", { staticClass: "cp-pos" }, [
                _vm._v(_vm._s(_vm.formatGs(_vm.entrega)))
              ])
            ]),
            _vm._v(" "),
            _c("div", [
              _c("span", [_vm._v("Saldo")]),
              _c("strong", [_vm._v(_vm._s(_vm.formatGs(_vm.saldoNeto)))])
            ]),
            _vm._v(" "),
            _c("div", [
              _c("span", [_vm._v("Interés")]),
              _c("strong", { class: { "cp-neg": _vm.montoInteres > 0 } }, [
                _vm._v(_vm._s(_vm.formatGs(_vm.montoInteres)))
              ])
            ])
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "cp-schedule-head" }, [
          _c("span", { staticClass: "cp-step-title" }, [
            _vm._v("Cronograma de pagos")
          ]),
          _vm._v(" "),
          _vm.editadoManual
            ? _c(
                "button",
                {
                  staticClass: "btn btn-link btn-sm p-0 cp-reset",
                  attrs: {
                    type: "button",
                    title:
                      "Descartar las ediciones manuales y volver a calcular"
                  },
                  on: { click: _vm.restablecer }
                },
                [
                  _c("i", { staticClass: "fas fa-undo-alt mr-1" }),
                  _vm._v(" Restablecer\n                ")
                ]
              )
            : _vm._e()
        ]),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "cp-schedule" },
          [
            _vm._l(_vm.cuotas, function(c, idx) {
              return _c(
                "div",
                {
                  key: idx,
                  staticClass: "cp-row",
                  class: {
                    "cp-row-hoy": c.tipo === "Entrega",
                    "cp-row-error": _vm.fechaFueraDeOrden(idx)
                  }
                },
                [
                  _c("div", { staticClass: "cp-row-badge" }, [
                    _vm.esEntregaInicial(c)
                      ? _c("i", {
                          staticClass: "fas fa-hand-holding-usd",
                          attrs: { title: "Entrega inicial" }
                        })
                      : _c("span", [_vm._v(_vm._s(_vm.numeroVisible(idx)))])
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "cp-row-info" }, [
                    _c("span", { staticClass: "cp-row-title" }, [
                      _vm._v(
                        _vm._s(
                          _vm.esEntregaInicial(c)
                            ? "Entrega inicial"
                            : "Cuota " + _vm.numeroVisible(idx)
                        )
                      )
                    ]),
                    _vm._v(" "),
                    _c(
                      "span",
                      {
                        staticClass: "cp-row-meta",
                        class: { "text-danger": _vm.fechaFueraDeOrden(idx) }
                      },
                      [
                        _vm.fechaFueraDeOrden(idx)
                          ? [
                              _c("i", {
                                staticClass: "fas fa-exclamation-circle mr-1"
                              }),
                              _vm._v("Antes que la anterior")
                            ]
                          : c.tipo === "Entrega"
                          ? [
                              _c("i", { staticClass: "fas fa-check mr-1" }),
                              _vm._v("Se cobra hoy")
                            ]
                          : [_vm._v(_vm._s(_vm.infoFecha(c.vencimiento)))]
                      ],
                      2
                    )
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "cp-row-date" }, [
                    _c("input", {
                      staticClass: "form-control form-control-sm",
                      attrs: {
                        type: "date",
                        disabled: c.tipo === "Entrega",
                        "aria-label":
                          "Vencimiento de la cuota " + _vm.numeroVisible(idx)
                      },
                      domProps: { value: _vm.toInputDate(c.vencimiento) },
                      on: {
                        change: function($event) {
                          return _vm.onVencimientoItemChange(
                            idx,
                            $event.target.value
                          )
                        }
                      }
                    })
                  ]),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "cp-row-amount" },
                    [
                      _c("in-number", {
                        attrs: {
                          clases:
                            "form-control form-control-sm text-right font-weight-bold"
                        },
                        on: {
                          change: function($event) {
                            return _vm.onMontoItemChange(idx)
                          }
                        },
                        model: {
                          value: c.monto,
                          callback: function($$v) {
                            _vm.$set(c, "monto", $$v)
                          },
                          expression: "c.monto"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "cp-row-action" }, [
                    c.tipo !== "Entrega"
                      ? _c(
                          "button",
                          {
                            staticClass: "cp-icon-btn",
                            attrs: {
                              type: "button",
                              title: "Quitar cuota",
                              disabled: _vm.cantidadCuotasCredito <= 1
                            },
                            on: {
                              click: function($event) {
                                return _vm.eliminarCuota(idx)
                              }
                            }
                          },
                          [_c("i", { staticClass: "fas fa-trash-alt" })]
                        )
                      : _vm._e()
                  ])
                ]
              )
            }),
            _vm._v(" "),
            !_vm.cuotas.length
              ? _c("div", { staticClass: "cp-empty" }, [
                  _c("i", { staticClass: "far fa-calendar-times fa-2x mb-2" }),
                  _vm._v(" "),
                  _c("p", { staticClass: "mb-0" }, [
                    _vm._v("Elegí la cantidad de cuotas para armar el plan.")
                  ])
                ])
              : _vm._e()
          ],
          2
        ),
        _vm._v(" "),
        _c(
          "div",
          {
            staticClass: "cp-balance",
            class: _vm.hayDiferencia ? "is-warning" : "is-ok"
          },
          [
            _c("div", { staticClass: "d-flex align-items-center" }, [
              _c("i", {
                staticClass: "fas mr-2",
                class: _vm.hayDiferencia
                  ? "fa-exclamation-triangle"
                  : "fa-check-circle"
              }),
              _vm._v(" "),
              _c("div", [
                !_vm.hayDiferencia
                  ? _c("div", { staticClass: "font-weight-bold" }, [
                      _vm._v("El plan cuadra con el total")
                    ])
                  : _c("div", { staticClass: "font-weight-bold" }, [
                      _vm._v(
                        "\n                            " +
                          _vm._s(
                            _vm.diferenciaCuotas > 0 ? "Faltan" : "Sobran"
                          ) +
                          " Gs. " +
                          _vm._s(_vm.formatGs(Math.abs(_vm.diferenciaCuotas))) +
                          "\n                        "
                      )
                    ]),
                _vm._v(" "),
                _c("div", { staticClass: "small" }, [
                  _vm._v(
                    "Suma de pagos: Gs. " + _vm._s(_vm.formatGs(_vm.sumaCuotas))
                  )
                ])
              ])
            ]),
            _vm._v(" "),
            _vm.hayDiferencia
              ? _c(
                  "button",
                  {
                    staticClass: "btn btn-sm btn-warning font-weight-bold",
                    attrs: { type: "button" },
                    on: { click: _vm.autoAjustarDiferencia }
                  },
                  [
                    _c("i", { staticClass: "fas fa-magic mr-1" }),
                    _vm._v(" Ajustar en la última cuota\n                ")
                  ]
                )
              : _vm._e()
          ]
        )
      ])
    ])
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "cp-step-head" }, [
      _c("span", { staticClass: "cp-step-num" }, [_vm._v("1")]),
      _vm._v(" "),
      _c("span", { staticClass: "cp-step-title" }, [
        _vm._v("¿En cuántas cuotas?")
      ])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "cp-step-head" }, [
      _c("span", { staticClass: "cp-step-num" }, [_vm._v("2")]),
      _vm._v(" "),
      _c("span", { staticClass: "cp-step-title" }, [_vm._v("Entrega inicial")]),
      _vm._v(" "),
      _c("span", { staticClass: "cp-step-opt" }, [_vm._v("opcional")])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "input-group-prepend" }, [
      _c("span", { staticClass: "input-group-text" }, [_vm._v("Gs.")])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "input-group-append" }, [
      _c("span", { staticClass: "input-group-text" }, [_vm._v("%")])
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/in_number.vue?vue&type=template&id=49ac9868&":
/*!************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/in_number.vue?vue&type=template&id=49ac9868& ***!
  \************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("input", {
    directives: [
      { name: "model", rawName: "v-model", value: _vm.text, expression: "text" }
    ],
    class: _vm.inputClass,
    attrs: {
      id: _vm.id,
      tabindex: _vm.tabindex,
      onfocus: "this.select()",
      placeholder: _vm.placeholder,
      type: "text"
    },
    domProps: { value: _vm.text },
    on: {
      keyup: _vm.keyup,
      keypress: function($event) {
        return _vm.isNumber($event)
      },
      input: function($event) {
        if ($event.target.composing) {
          return
        }
        _vm.text = $event.target.value
      }
    }
  })
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return normalizeComponent; });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () { injectStyles.call(this, this.$root.$options.shadowRoot) }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ "./resources/js/components/generar_cuota.vue":
/*!***************************************************!*\
  !*** ./resources/js/components/generar_cuota.vue ***!
  \***************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _generar_cuota_vue_vue_type_template_id_5a0d83c4_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./generar_cuota.vue?vue&type=template&id=5a0d83c4&scoped=true& */ "./resources/js/components/generar_cuota.vue?vue&type=template&id=5a0d83c4&scoped=true&");
/* harmony import */ var _generar_cuota_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./generar_cuota.vue?vue&type=script&lang=js& */ "./resources/js/components/generar_cuota.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _generar_cuota_vue_vue_type_style_index_0_id_5a0d83c4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./generar_cuota.vue?vue&type=style&index=0&id=5a0d83c4&scoped=true&lang=css& */ "./resources/js/components/generar_cuota.vue?vue&type=style&index=0&id=5a0d83c4&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _generar_cuota_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _generar_cuota_vue_vue_type_template_id_5a0d83c4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _generar_cuota_vue_vue_type_template_id_5a0d83c4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "5a0d83c4",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/generar_cuota.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/generar_cuota.vue?vue&type=script&lang=js&":
/*!****************************************************************************!*\
  !*** ./resources/js/components/generar_cuota.vue?vue&type=script&lang=js& ***!
  \****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_generar_cuota_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./generar_cuota.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/generar_cuota.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_generar_cuota_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/generar_cuota.vue?vue&type=style&index=0&id=5a0d83c4&scoped=true&lang=css&":
/*!************************************************************************************************************!*\
  !*** ./resources/js/components/generar_cuota.vue?vue&type=style&index=0&id=5a0d83c4&scoped=true&lang=css& ***!
  \************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_generar_cuota_vue_vue_type_style_index_0_id_5a0d83c4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/style-loader!../../../node_modules/css-loader??ref--6-1!../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../node_modules/postcss-loader/src??ref--6-2!../../../node_modules/vue-loader/lib??vue-loader-options!./generar_cuota.vue?vue&type=style&index=0&id=5a0d83c4&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/generar_cuota.vue?vue&type=style&index=0&id=5a0d83c4&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_generar_cuota_vue_vue_type_style_index_0_id_5a0d83c4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_generar_cuota_vue_vue_type_style_index_0_id_5a0d83c4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_generar_cuota_vue_vue_type_style_index_0_id_5a0d83c4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_generar_cuota_vue_vue_type_style_index_0_id_5a0d83c4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_6_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_6_2_node_modules_vue_loader_lib_index_js_vue_loader_options_generar_cuota_vue_vue_type_style_index_0_id_5a0d83c4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ "./resources/js/components/generar_cuota.vue?vue&type=template&id=5a0d83c4&scoped=true&":
/*!**********************************************************************************************!*\
  !*** ./resources/js/components/generar_cuota.vue?vue&type=template&id=5a0d83c4&scoped=true& ***!
  \**********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_generar_cuota_vue_vue_type_template_id_5a0d83c4_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./generar_cuota.vue?vue&type=template&id=5a0d83c4&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/generar_cuota.vue?vue&type=template&id=5a0d83c4&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_generar_cuota_vue_vue_type_template_id_5a0d83c4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_generar_cuota_vue_vue_type_template_id_5a0d83c4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/in_number.vue":
/*!***********************************************!*\
  !*** ./resources/js/components/in_number.vue ***!
  \***********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _in_number_vue_vue_type_template_id_49ac9868___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./in_number.vue?vue&type=template&id=49ac9868& */ "./resources/js/components/in_number.vue?vue&type=template&id=49ac9868&");
/* harmony import */ var _in_number_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./in_number.vue?vue&type=script&lang=js& */ "./resources/js/components/in_number.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _in_number_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _in_number_vue_vue_type_template_id_49ac9868___WEBPACK_IMPORTED_MODULE_0__["render"],
  _in_number_vue_vue_type_template_id_49ac9868___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/in_number.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/in_number.vue?vue&type=script&lang=js&":
/*!************************************************************************!*\
  !*** ./resources/js/components/in_number.vue?vue&type=script&lang=js& ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_in_number_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./in_number.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/in_number.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_in_number_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/in_number.vue?vue&type=template&id=49ac9868&":
/*!******************************************************************************!*\
  !*** ./resources/js/components/in_number.vue?vue&type=template&id=49ac9868& ***!
  \******************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_in_number_vue_vue_type_template_id_49ac9868___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./in_number.vue?vue&type=template&id=49ac9868& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/in_number.vue?vue&type=template&id=49ac9868&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_in_number_vue_vue_type_template_id_49ac9868___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_in_number_vue_vue_type_template_id_49ac9868___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/venta.js":
/*!*******************************!*\
  !*** ./resources/js/venta.js ***!
  \*******************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

Vue.component('inNumber', __webpack_require__(/*! ./components/in_number.vue */ "./resources/js/components/in_number.vue")["default"]);
Vue.component('in-number', __webpack_require__(/*! ./components/in_number.vue */ "./resources/js/components/in_number.vue")["default"]);
Vue.component('generar_cuota', __webpack_require__(/*! ./components/generar_cuota.vue */ "./resources/js/components/generar_cuota.vue")["default"]);

/***/ }),

/***/ 4:
/*!*************************************!*\
  !*** multi ./resources/js/venta.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\laragon\www\softsystem\resources\js\venta.js */"./resources/js/venta.js");


/***/ })

/******/ });