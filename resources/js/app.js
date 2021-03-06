/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('solicitud-component', require('./components/SolicitudComponent.vue').default);
Vue.component('create-solicitud-component', require('./components/solicituds/create.vue').default);

Vue.component('select-sol', require('./components/solicituds/select.vue').default);
Vue.component('select-ter', require('./components/solicituds/terceros.vue').default);
Vue.component('select-ben', require('./components/solicituds/beneficiario.vue').default);
Vue.component('select-ins', require('./components/solicituds/institucion.vue').default);

Vue.component('indices-graf', require('./components/solicituds/graficas/indices.vue').default);
Vue.component('indices-grafs', require('./components/solicituds/graficas/status.vue').default);
Vue.component('indices-grafsti', require('./components/solicituds/graficas/tipo.vue').default);

import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

import vSelect from 'vue-select'

import HighchartsVue from 'highcharts-vue'

// Import Bootstrap an BootstrapVue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import 'vue-select/dist/vue-select.css';

// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

Vue.use(HighchartsVue)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('v-select', vSelect)

const app = new Vue({
    el: '#app',
});
