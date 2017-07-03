require('./bootstrap');

/**
 * Vue Application
 */

Vue.use(VueRouter);
Vue.use(Datepicker);
Vue.use(VueMask);

Vue.component('navtop', require('./components/Nav.vue'));
Vue.component('container', require('./components/Main.vue'));
Vue.component('datepicker', Datepicker);

const routes = [
	{ name: 'home', path: '/', component: () => System.import('./components/Home.vue') },
	
	{ name: 'appointments', path: '/appointments', component: () => System.import('./components/appointments/Appointments.vue') },
	{ path: '/appointments/:id', component: () => System.import('./components/appointments/Appointments.vue') }, // Pagination
	{ path: '/appointments/:id/:field', component: () => System.import('./components/appointments/Appointments.vue') }, // Pagination with order
	{ name: 'appointments_detail', path: '/appointment/detail/:id', component: () => System.import('./components/appointments/Detail.vue') },
	{ name: 'appointments_edit', path: '/appointment/edit/:id', component: () => System.import('./components/appointments/Save.vue') },
	{ name: 'appointments_new', path: '/new/appointment', component: () => System.import('./components/appointments/Save.vue') },
	
	{ name: 'patients', path: '/patients', component: () => System.import('./components/patients/Patients.vue') },
	{ path: '/patients/:id', component: () => System.import('./components/patients/Patients.vue') }, // Pagination
	{ path: '/patients/:id/:field', component: () => System.import('./components/patients/Patients.vue') }, // Pagination with order
	{ name: 'patients_detail', path: '/patient/detail/:id', component: () => System.import('./components/patients/Detail.vue') },
	{ name: 'patients_edit', path: '/appointment/edit/:id', component: () => System.import('./components/appointments/Save.vue') },
	{ name: 'patients_new', path: '/new/patient', component: () => System.import('./components/patients/Save.vue') }
];

const router = new VueRouter({
	routes
});

const app = new Vue({
    el: '#app',
    router
});