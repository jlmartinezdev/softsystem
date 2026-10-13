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
    total: function total() {
      this.entrega = 0;
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
      } // Siguiente mes por defecto


      var nextDate = new Date(baseDate);
      nextDate.setMonth(nextDate.getMonth() + 1);
      this.primerVencimiento = this.formatDateIso(nextDate);
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
        nextDate.setMonth(nextDate.getMonth() + 1);
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
    generar: function generar() {
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
          // Redondear a múltiplos de 1.000
          montoCuotaBase = Math.round(montoCuotaBase / 1000) * 1000;
        }
      } // 3. Calcular fechas de vencimiento según frecuencia


      var dateCursor = this.primerVencimiento ? new Date(this.primerVencimiento + 'T00:00:00') : new Date();

      if (isNaN(dateCursor.getTime())) {
        dateCursor = new Date();
      }

      var nroOffset = entregaMonto > 0 ? 2 : 1;
      var totalAsignado = 0;

      for (var i = 0; i < cantidad; i++) {
        var cDate = new Date(dateCursor);

        if (i > 0) {
          if (this.frecuencia === 'semanal') {
            cDate.setDate(cDate.getDate() + i * 7);
          } else if (this.frecuencia === 'quincenal') {
            cDate.setDate(cDate.getDate() + i * 15);
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
          tipo: i === 0 && this.primeracuotaHoy && entregaMonto === 0 ? 'Entrega' : 'Cuota'
        });
      }

      this.emitCuotas();
    },
    onVencimientoItemChange: function onVencimientoItemChange(idx, nuevaFechaIso) {
      if (this.cuotas[idx]) {
        this.$set(this.cuotas[idx], 'vencimiento', this.toVencimientoFormat(nuevaFechaIso));
        this.emitCuotas();
      }
    },
    onMontoItemChange: function onMontoItemChange(idx) {
      if (this.cuotas[idx]) {
        var val = Number(this.cuotas[idx].monto) || 0;
        this.$set(this.cuotas[idx], 'monto', val);
        this.emitCuotas();
      }
    },
    eliminarCuota: function eliminarCuota(idx) {
      if (this.cuotas.length <= 1) {
        return;
      }

      this.cuotas.splice(idx, 1); // Renumerar cuotas

      var num = 1;
      this.cuotas.forEach(function (c) {
        c.nro = num++;
      });
      this.cant_cuota = this.cuotas.filter(function (c) {
        return c.tipo === 'Cuota';
      }).length;
      this.autoAjustarDiferencia();
    },
    autoAjustarDiferencia: function autoAjustarDiferencia() {
      var diff = this.diferenciaCuotas;
      if (Math.abs(diff) < 1 || !this.cuotas.length) return; // Ajustar en la última cuota que no sea Entrega

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
    emitCuotas: function emitCuotas() {
      this.$emit('cuotas', this.cuotas);
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
exports.push([module.i, "\n.cuota-summary-row .cuota-summary-card[data-v-5a0d83c4] {\n    background: #ffffff;\n    border: 1px solid #e2e8f0;\n    border-radius: 8px;\n    padding: 0.65rem 0.85rem;\n    display: flex;\n    flex-direction: column;\n}\n.cuota-summary-label[data-v-5a0d83c4] {\n    font-size: 0.75rem;\n    font-weight: 700;\n    text-transform: uppercase;\n    color: #64748b;\n    letter-spacing: 0.03em;\n}\n.cuota-summary-val[data-v-5a0d83c4] {\n    font-size: 1.15rem;\n    font-family: inherit;\n    font-variant-numeric: tabular-nums;\n    margin-top: 0.2rem;\n}\n.quick-entrega-chips[data-v-5a0d83c4], .quick-cuotas-chips[data-v-5a0d83c4] {\n    display: flex;\n    flex-wrap: wrap;\n}\n.cuota-table-wrapper[data-v-5a0d83c4] {\n    max-height: 280px;\n    overflow-y: auto;\n    background: #ffffff;\n}\n.cuota-table-wrapper table thead th[data-v-5a0d83c4] {\n    position: sticky;\n    top: 0;\n    background: #f1f5f9;\n    z-index: 2;\n    font-size: 0.8rem;\n    letter-spacing: 0.02em;\n}\n.cuota-status-bar[data-v-5a0d83c4] {\n    border-radius: 8px;\n    margin-top: 0.75rem;\n}\n.cuota-status-success[data-v-5a0d83c4] {\n    background-color: #e8f5e9 !important;\n    border: 1px solid #c8e6c9 !important;\n}\n.cuota-status-warning[data-v-5a0d83c4] {\n    background-color: #fff3cd !important;\n    border: 1px solid #ffeeba !important;\n}\n", ""]);

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
  return _c("div", { staticClass: "cuota-pos-container" }, [
    _c("div", { staticClass: "row cuota-summary-row mb-3" }, [
      _c("div", { staticClass: "col-sm-3 col-6 mb-2" }, [
        _c("div", { staticClass: "cuota-summary-card" }, [
          _c("span", { staticClass: "cuota-summary-label" }, [
            _vm._v("Total Venta")
          ]),
          _vm._v(" "),
          _c(
            "span",
            { staticClass: "cuota-summary-val font-weight-bold text-dark" },
            [_vm._v("Gs. " + _vm._s(_vm.formatGs(_vm.total)))]
          )
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "col-sm-3 col-6 mb-2" }, [
        _c("div", { staticClass: "cuota-summary-card" }, [
          _c("span", { staticClass: "cuota-summary-label" }, [
            _vm._v("Entrega / Anticipo")
          ]),
          _vm._v(" "),
          _c("span", { staticClass: "cuota-summary-val text-success" }, [
            _vm._v("Gs. " + _vm._s(_vm.formatGs(_vm.entrega)))
          ])
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "col-sm-3 col-6 mb-2" }, [
        _c("div", { staticClass: "cuota-summary-card" }, [
          _c("span", { staticClass: "cuota-summary-label" }, [
            _vm._v("Saldo a Financiar")
          ]),
          _vm._v(" "),
          _c("span", { staticClass: "cuota-summary-val text-primary" }, [
            _vm._v("Gs. " + _vm._s(_vm.formatGs(_vm.saldoNeto)))
          ])
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "col-sm-3 col-6 mb-2" }, [
        _c("div", { staticClass: "cuota-summary-card" }, [
          _c("span", { staticClass: "cuota-summary-label" }, [
            _vm._v("Total con Interés")
          ]),
          _vm._v(" "),
          _c("span", { staticClass: "cuota-summary-val text-info" }, [
            _vm._v("Gs. " + _vm._s(_vm.formatGs(_vm.saldoConInteres)))
          ])
        ])
      ])
    ]),
    _vm._v(" "),
    _c(
      "div",
      { staticClass: "cuota-config-box p-3 mb-3 border rounded bg-light" },
      [
        _c("div", { staticClass: "row align-items-end" }, [
          _c("div", { staticClass: "col-md-4 col-sm-6 mb-2" }, [
            _vm._m(0),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "input-group input-group-sm" },
              [
                _c("in-number", {
                  attrs: {
                    clases: "form-control font-weight-bold text-success",
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
            _c("div", { staticClass: "quick-entrega-chips mt-1" }, [
              _c(
                "button",
                {
                  staticClass: "btn btn-xs btn-outline-secondary mr-1 mb-1",
                  attrs: { type: "button" },
                  on: {
                    click: function($event) {
                      return _vm.setEntregaPct(0)
                    }
                  }
                },
                [_vm._v("0%")]
              ),
              _vm._v(" "),
              _c(
                "button",
                {
                  staticClass: "btn btn-xs btn-outline-secondary mr-1 mb-1",
                  attrs: { type: "button" },
                  on: {
                    click: function($event) {
                      return _vm.setEntregaPct(10)
                    }
                  }
                },
                [_vm._v("10%")]
              ),
              _vm._v(" "),
              _c(
                "button",
                {
                  staticClass: "btn btn-xs btn-outline-secondary mr-1 mb-1",
                  attrs: { type: "button" },
                  on: {
                    click: function($event) {
                      return _vm.setEntregaPct(20)
                    }
                  }
                },
                [_vm._v("20%")]
              ),
              _vm._v(" "),
              _c(
                "button",
                {
                  staticClass: "btn btn-xs btn-outline-secondary mr-1 mb-1",
                  attrs: { type: "button" },
                  on: {
                    click: function($event) {
                      return _vm.setEntregaPct(30)
                    }
                  }
                },
                [_vm._v("30%")]
              ),
              _vm._v(" "),
              _c(
                "button",
                {
                  staticClass: "btn btn-xs btn-outline-secondary mr-1 mb-1",
                  attrs: { type: "button" },
                  on: {
                    click: function($event) {
                      return _vm.setEntregaPct(50)
                    }
                  }
                },
                [_vm._v("50%")]
              )
            ])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "col-md-4 col-sm-6 mb-2" }, [
            _vm._m(1),
            _vm._v(" "),
            _c("div", { staticClass: "input-group input-group-sm" }, [
              _c("input", {
                directives: [
                  {
                    name: "model",
                    rawName: "v-model.number",
                    value: _vm.cant_cuota,
                    expression: "cant_cuota",
                    modifiers: { number: true }
                  }
                ],
                staticClass: "form-control font-weight-bold text-center",
                attrs: {
                  type: "number",
                  min: "1",
                  max: "60",
                  placeholder: "1"
                },
                domProps: { value: _vm.cant_cuota },
                on: {
                  change: _vm.generar,
                  input: function($event) {
                    if ($event.target.composing) {
                      return
                    }
                    _vm.cant_cuota = _vm._n($event.target.value)
                  },
                  blur: function($event) {
                    return _vm.$forceUpdate()
                  }
                }
              }),
              _vm._v(" "),
              _vm._m(2)
            ]),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "quick-cuotas-chips mt-1" },
              _vm._l([1, 2, 3, 4, 6, 10, 12, 18, 24], function(n) {
                return _c(
                  "button",
                  {
                    key: n,
                    staticClass: "btn btn-xs mr-1 mb-1",
                    class:
                      _vm.cant_cuota === n
                        ? "btn-primary"
                        : "btn-outline-secondary",
                    attrs: { type: "button" },
                    on: {
                      click: function($event) {
                        return _vm.elegirPlan(n)
                      }
                    }
                  },
                  [
                    _vm._v(
                      "\n                        " +
                        _vm._s(n) +
                        "\n                    "
                    )
                  ]
                )
              }),
              0
            )
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "col-md-4 col-sm-6 mb-2" }, [
            _vm._m(3),
            _vm._v(" "),
            _c(
              "select",
              {
                directives: [
                  {
                    name: "model",
                    rawName: "v-model",
                    value: _vm.frecuencia,
                    expression: "frecuencia"
                  }
                ],
                staticClass: "form-control form-control-sm",
                on: {
                  change: [
                    function($event) {
                      var $$selectedVal = Array.prototype.filter
                        .call($event.target.options, function(o) {
                          return o.selected
                        })
                        .map(function(o) {
                          var val = "_value" in o ? o._value : o.value
                          return val
                        })
                      _vm.frecuencia = $event.target.multiple
                        ? $$selectedVal
                        : $$selectedVal[0]
                    },
                    _vm.onFrecuenciaChange
                  ]
                }
              },
              [
                _c("option", { attrs: { value: "mensual" } }, [
                  _vm._v("Mensual (cada 30 días)")
                ]),
                _vm._v(" "),
                _c("option", { attrs: { value: "quincenal" } }, [
                  _vm._v("Quincenal (cada 15 días)")
                ]),
                _vm._v(" "),
                _c("option", { attrs: { value: "semanal" } }, [
                  _vm._v("Semanal (cada 7 días)")
                ])
              ]
            )
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "col-md-4 col-sm-6 mb-2" }, [
            _vm._m(4),
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
              staticClass: "form-control form-control-sm",
              attrs: { type: "date" },
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
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "col-md-4 col-sm-6 mb-2" }, [
            _vm._m(5),
            _vm._v(" "),
            _c("div", { staticClass: "input-group input-group-sm" }, [
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
                staticClass: "form-control font-weight-bold",
                attrs: {
                  type: "number",
                  min: "0",
                  max: "100",
                  step: "0.5",
                  placeholder: "0"
                },
                domProps: { value: _vm.interes },
                on: {
                  input: [
                    function($event) {
                      if ($event.target.composing) {
                        return
                      }
                      _vm.interes = _vm._n($event.target.value)
                    },
                    _vm.generar
                  ],
                  blur: function($event) {
                    return _vm.$forceUpdate()
                  }
                }
              }),
              _vm._v(" "),
              _vm._m(6)
            ])
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "col-md-4 col-sm-6 mb-2" }, [
            _c("div", { staticClass: "custom-control custom-checkbox mb-1" }, [
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
                [_vm._v("Redondear cuotas a miles")]
              )
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "custom-control custom-checkbox" }, [
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
                attrs: { type: "checkbox", id: "checkPrimeracuota" },
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
                  attrs: { for: "checkPrimeracuota" }
                },
                [_vm._v("1ª cuota hoy (al contado)")]
              )
            ])
          ])
        ])
      ]
    ),
    _vm._v(" "),
    _c(
      "div",
      {
        staticClass: "cuota-table-wrapper border rounded overflow-hidden mb-2"
      },
      [
        _c(
          "table",
          { staticClass: "table table-sm table-striped table-hover mb-0" },
          [
            _vm._m(7),
            _vm._v(" "),
            _c(
              "tbody",
              [
                _vm._l(_vm.cuotas, function(c, idx) {
                  return _c(
                    "tr",
                    {
                      key: idx,
                      class: { "table-success": c.tipo === "Entrega" }
                    },
                    [
                      _c(
                        "td",
                        { staticClass: "font-weight-bold align-middle" },
                        [
                          c.tipo === "Entrega"
                            ? _c("span", [_vm._v("Anticipo")])
                            : _c("span", [_vm._v("Cuota " + _vm._s(c.nro))])
                        ]
                      ),
                      _vm._v(" "),
                      _c("td", { staticClass: "align-middle" }, [
                        c.tipo === "Entrega"
                          ? _c("span", { staticClass: "badge badge-success" }, [
                              _c("i", { staticClass: "fas fa-check mr-1" }),
                              _vm._v(" Cobrado Hoy\n                        ")
                            ])
                          : _c("span", { staticClass: "badge badge-info" }, [
                              _vm._v(
                                "\n                            Crédito\n                        "
                              )
                            ])
                      ]),
                      _vm._v(" "),
                      _c("td", { staticClass: "align-middle" }, [
                        _c("input", {
                          staticClass:
                            "form-control form-control-sm d-inline-block",
                          staticStyle: { "max-width": "160px" },
                          attrs: { type: "date" },
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
                        "td",
                        {
                          staticClass: "text-right align-middle",
                          staticStyle: { "min-width": "140px" }
                        },
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
                      _c("td", { staticClass: "text-center align-middle" }, [
                        c.tipo === "Entrega"
                          ? _c(
                              "span",
                              {
                                staticClass: "text-muted",
                                attrs: { title: "Entrega inicial" }
                              },
                              [_c("i", { staticClass: "fas fa-lock" })]
                            )
                          : _c(
                              "button",
                              {
                                staticClass:
                                  "btn btn-link btn-xs text-danger p-0",
                                attrs: {
                                  type: "button",
                                  title: "Eliminar cuota"
                                },
                                on: {
                                  click: function($event) {
                                    return _vm.eliminarCuota(idx)
                                  }
                                }
                              },
                              [_c("i", { staticClass: "fas fa-times" })]
                            )
                      ])
                    ]
                  )
                }),
                _vm._v(" "),
                !_vm.cuotas.length ? _c("tr", [_vm._m(8)]) : _vm._e()
              ],
              2
            ),
            _vm._v(" "),
            _c("tfoot", { staticClass: "bg-light font-weight-bold" }, [
              _c("tr", [
                _c(
                  "td",
                  {
                    staticClass: "text-right align-middle",
                    attrs: { colspan: "3" }
                  },
                  [
                    _vm._v(
                      "\n                        Total Suma de Cuotas:\n                    "
                    )
                  ]
                ),
                _vm._v(" "),
                _c("td", { staticClass: "text-right align-middle" }, [
                  _vm._v(
                    "\n                        Gs. " +
                      _vm._s(_vm.formatGs(_vm.sumaCuotas)) +
                      "\n                    "
                  )
                ]),
                _vm._v(" "),
                _c("td")
              ])
            ])
          ]
        )
      ]
    ),
    _vm._v(" "),
    _c(
      "div",
      {
        staticClass:
          "cuota-status-bar d-flex flex-wrap justify-content-between align-items-center p-2 px-3 rounded shadow-sm",
        class: _vm.hayDiferencia
          ? "cuota-status-warning"
          : "cuota-status-success"
      },
      [
        _c(
          "div",
          { staticClass: "d-flex align-items-center" },
          [
            !_vm.hayDiferencia
              ? [
                  _c("i", {
                    staticClass: "fa fa-check-circle mr-2 text-success",
                    staticStyle: { "font-size": "1.15rem" }
                  }),
                  _vm._v(" "),
                  _c(
                    "span",
                    {
                      staticClass: "font-weight-bold",
                      staticStyle: { color: "#155724", "font-size": "0.9rem" }
                    },
                    [
                      _vm._v(
                        "\n                    Plan de cuotas balanceado perfectamente con el total.\n                "
                      )
                    ]
                  )
                ]
              : [
                  _c("i", {
                    staticClass: "fa fa-exclamation-triangle mr-2 text-warning",
                    staticStyle: { "font-size": "1.15rem" }
                  }),
                  _vm._v(" "),
                  _c(
                    "span",
                    {
                      staticClass: "font-weight-bold",
                      staticStyle: { color: "#856404", "font-size": "0.9rem" }
                    },
                    [
                      _vm._v(
                        "\n                    Diferencia de Gs. " +
                          _vm._s(_vm.formatGs(Math.abs(_vm.diferenciaCuotas))) +
                          "\n                    (" +
                          _vm._s(
                            _vm.diferenciaCuotas > 0
                              ? "Falta asignar a las cuotas"
                              : "Sobra en las cuotas"
                          ) +
                          ")\n                "
                      )
                    ]
                  )
                ]
          ],
          2
        ),
        _vm._v(" "),
        _vm.hayDiferencia
          ? _c("div", { staticClass: "mt-1 mt-sm-0" }, [
              _c(
                "button",
                {
                  staticClass:
                    "btn btn-sm btn-warning font-weight-bold shadow-sm",
                  attrs: { type: "button" },
                  on: { click: _vm.autoAjustarDiferencia }
                },
                [
                  _c("i", { staticClass: "fa fa-magic mr-1" }),
                  _vm._v(" Auto-ajustar diferencia\n            ")
                ]
              )
            ])
          : _vm._e()
      ]
    )
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "label",
      { staticClass: "font-weight-bold small text-muted mb-1" },
      [
        _c("i", { staticClass: "fas fa-hand-holding-usd text-success mr-1" }),
        _vm._v(" Entrega Inicial (Gs.)\n                ")
      ]
    )
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "label",
      { staticClass: "font-weight-bold small text-muted mb-1" },
      [
        _c("i", { staticClass: "fas fa-calendar-alt text-primary mr-1" }),
        _vm._v(" Cantidad de Cuotas\n                ")
      ]
    )
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "input-group-append" }, [
      _c("span", { staticClass: "input-group-text small" }, [_vm._v("cuotas")])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "label",
      { staticClass: "font-weight-bold small text-muted mb-1" },
      [
        _c("i", { staticClass: "fas fa-history text-secondary mr-1" }),
        _vm._v(" Frecuencia\n                ")
      ]
    )
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "label",
      { staticClass: "font-weight-bold small text-muted mb-1" },
      [
        _c("i", { staticClass: "far fa-calendar-check mr-1" }),
        _vm._v(" Primer Vencimiento\n                ")
      ]
    )
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "label",
      { staticClass: "font-weight-bold small text-muted mb-1" },
      [
        _c("i", { staticClass: "fas fa-percentage text-danger mr-1" }),
        _vm._v(" Interés / Recargo (%)\n                ")
      ]
    )
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "input-group-append" }, [
      _c("span", { staticClass: "input-group-text small" }, [_vm._v("%")])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", { staticClass: "thead-light" }, [
      _c("tr", [
        _c("th", { staticStyle: { width: "70px" } }, [_vm._v("# Cuota")]),
        _vm._v(" "),
        _c("th", { staticStyle: { width: "110px" } }, [_vm._v("Tipo")]),
        _vm._v(" "),
        _c("th", [_vm._v("Vencimiento")]),
        _vm._v(" "),
        _c(
          "th",
          { staticClass: "text-right", staticStyle: { width: "170px" } },
          [_vm._v("Monto (Gs.)")]
        ),
        _vm._v(" "),
        _c("th", { staticClass: "text-center", staticStyle: { width: "50px" } })
      ])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "td",
      { staticClass: "text-center py-4 text-muted", attrs: { colspan: "5" } },
      [
        _c("i", { staticClass: "far fa-calendar-times fa-2x mb-2 text-muted" }),
        _vm._v(" "),
        _c("p", { staticClass: "mb-0" }, [
          _vm._v("Configurá las cuotas para armar el plan de pago.")
        ])
      ]
    )
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