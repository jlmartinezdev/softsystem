/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Vuex =require('vuex')
window.Swal = require('sweetalert2')
window.NumeroALetras = require('./numeroaletra');
window.Funciones = require('./funciones');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

Vue.component('vPagination', require('./components/vue-plain-pagination.vue').default);
Vue.component('registro_mostrado', require('./components/registro_mostrado.vue').default);
Vue.component('inNumber',require('./components/in_number.vue').default);
Vue.component('Searcharticulo',require('./components/Autocomplete.vue').default);