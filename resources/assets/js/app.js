require('./bootstrap');

/**
 * Vue Application
 */

Vue.use(VueRouter);

import Appointments from './components/Appointments.vue';
import Home 		from './components/Home.vue';
import Patients 	from './components/Patients.vue';

Vue.component('navtop', require('./components/Nav.vue'));
Vue.component('container', require('./components/Main.vue'));

const routes = [
	{ path: '/', component: Home },
	{ path: '/appointments', component: Appointments },
	{ path: '/appointments/:id', component: Appointments },
	{ path: '/patients', component: Patients }
]

const router = new VueRouter({
	routes
});

const app = new Vue({
    el: '#app',
    router
});