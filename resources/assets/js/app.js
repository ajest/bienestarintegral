require('./bootstrap');

/**
 * Vue Application
 */

Vue.use(VueRouter);
Vue.use(Datepicker);

import Home 				from './components/Home.vue';

import Appointments 		from './components/appointments/Appointments.vue';
import AppointmentsDetail 	from './components/appointments/Detail.vue';
import AppointmentsSave 	from './components/appointments/Save.vue';

import Patients 			from './components/patients/Patients.vue';
import PatientsDetail 		from './components/patients/Detail.vue';

Vue.component('navtop', require('./components/Nav.vue'));
Vue.component('container', require('./components/Main.vue'));
Vue.component('datepicker', Datepicker);

const routes = [
	{ name: 'home', path: '/', component: Home },
	
	{ name: 'appointments', path: '/appointments', component: Appointments },
	{ path: '/appointments/:id', component: Appointments }, // Pagination
	{ name: 'appointments_detail', path: '/appointments/detail/:id', component: AppointmentsDetail },
	{ name: 'appointments_edit', path: '/appointments/edit/:id', component: AppointmentsSave },
	{ name: 'appointments_new', path: '/new/appointment', component: AppointmentsSave }, 
	
	{ name: 'patients', path: '/patients', component: Patients },
	{ path: '/patients/:id', component: Patients }, // Pagination
	{ name: 'patients_detail', path: '/patients/detail/:id', component: PatientsDetail }
];

const router = new VueRouter({
	routes
});

const app = new Vue({
    el: '#app',
    router
});