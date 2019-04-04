
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import { Form, HasError, AlertError } from 'vform'

import Gate from './Gate';
Vue.prototype.$gate = new Gate(window.user);

import moment from 'moment';

window.Form = Form;

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

Vue.component('pagination',require('laravel-vue-pagination'));

import VueRouter from 'vue-router';
import VueProgressBar from 'vue-progressbar';
import Swal from 'sweetalert2';

window.Swal= Swal;

Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '2px'
})


const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});

window.Toast = Toast;

window.Fire = new Vue();


Vue.use(VueRouter);



let routes = [
  { path: '/dashboard', component: require('./components/Dashboard.vue') },
  { path: '/developer', component: require('./components/Developer.vue') },
  { path: '/profile', component: require('./components/Profile.vue') },
  { path: '/users', component: require('./components/Users.vue') },
  { path: '*', component: require('./components/404.vue') }
]

const router = new VueRouter({
	mode: 'history',
	routes
})


Vue.filter('upText',function (text) {
	return text.toUpperCase();
});

Vue.filter('myDate',function (created) {
	return moment(created).format('MMMM Do YYYY,h:mm:ss');
})




/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);

Vue.component(
    'not-found',
    require('./components/404.vue')
);



Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app',
    router,
    data: {
    	search : ''
    },
    methods: {
    	searchit() {
    		console.log("searching ...");
    		Fire.$emit("searching");
    	}
    }
});
