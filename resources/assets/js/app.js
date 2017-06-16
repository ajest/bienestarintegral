require('./bootstrap');

/**
 * Vue Application
 */

Vue.use(VueRouter);

import Appointments 		from './components/appointments/Appointments.vue';
import AppointmentsDetail 	from './components/appointments/Detail.vue';
import Home 				from './components/Home.vue';
import Patients 			from './components/patients/Patients.vue';

Vue.component('navtop', require('./components/Nav.vue'));
Vue.component('container', require('./components/Main.vue'));

const routes = [
	{ name: 'home', path: '/', component: Home },
	{ name: 'appointments', path: '/appointments', component: Appointments },
	{ path: '/appointments/:id', component: Appointments },
	{ name: 'appointments_detail', path: '/appointments/detail/:id', component: AppointmentsDetail },
	{ name: 'patients', path: '/patients', component: Patients },
	{ name: 'patients_detail', path: '/patients/:id', component: Patients },
];

const router = new VueRouter({
	routes
});

const app = new Vue({
    el: '#app',
    router
});