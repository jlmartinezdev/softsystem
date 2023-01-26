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
/******/ 	return __webpack_require__(__webpack_require__.s = 6);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/funciones.js":
/*!***********************************!*\
  !*** ./resources/js/funciones.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

var subFecha = function subFecha(startFecha) {
  var fechaInicio = new Date(startFecha).getTime();
  var fechaFin = new Date().getTime();

  if (fechaInicio > fechaFin) {
    return 0;
  }

  var diff = fechaFin - fechaInicio;
  return parseInt(diff / (1000 * 60 * 60 * 24));
};

var setMontointeres = function setMontointeres(vencimiento, monto, isSaldo) {
  if (isSaldo == 0) return 0; //Informe de venta misma funcion

  var montoInteres = 0;
  var interes_mora = 100;
  var tmp_vencimiento = subFecha(vencimiento);

  if (interes_mora > 0 && tmp_vencimiento > 5) {
    montoInteres = monto * interes_mora / 100;
    montoInteres = montoInteres / 360;
    montoInteres = montoInteres * tmp_vencimiento;
  }

  return parseInt(montoInteres);
};

var formatFecha = function formatFecha(fecha) {
  var f = fecha.split("-");
  return f[2] + "/" + f[1] + "/" + f[0];
};

var diferenciaFecha = function diferenciaFecha(fecha_vent, monto_saldo) {
  //2016-07-12
  var dia = subFecha(fecha_vent); //let diferenciaFecha = 0;

  if (monto_saldo > 0) {
    return dia;
  } else {
    return "-";
  }
};

module.exports = {
  setMontointeres: setMontointeres,
  subFecha: subFecha,
  formatFecha: formatFecha,
  diferenciaFecha: diferenciaFecha
};

/***/ }),

/***/ 6:
/*!*****************************************!*\
  !*** multi ./resources/js/funciones.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\laragon\www\softsystem\resources\js\funciones.js */"./resources/js/funciones.js");


/***/ })

/******/ });