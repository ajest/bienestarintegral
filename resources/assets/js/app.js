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
	{ name: 'patients_edit', path: '/patient/edit/:id', component: () => System.import('./components/patients/Save.vue') },
	{ name: 'patients_new', path: '/new/patient', component: () => System.import('./components/patients/Save.vue') },

	{ name: 'professionals', path: '/professionals', component: () => System.import('./components/professionals/Professionals.vue') },
	{ path: '/professionals/:id', component: () => System.import('./components/professionals/Professionals.vue') }, // Pagination
	{ path: '/professionals/:id/:field', component: () => System.import('./components/professionals/Professionals.vue') }, // Pagination with order
	{ name: 'professionals_detail', path: '/professional/detail/:id', component: () => System.import('./components/professionals/Detail.vue') },
	{ name: 'professionals_edit', path: '/professional/edit/:id', component: () => System.import('./components/professionals/Save.vue') },
	{ name: 'professionals_new', path: '/new/professional', component: () => System.import('./components/professionals/Save.vue') },

	{ name: 'series', path: '/series', component: () => System.import('./components/series/Series.vue') },
	{ path: '/series/:id', component: () => System.import('./components/series/Series.vue') }, // Pagination
	{ path: '/series/:id/:field', component: () => System.import('./components/series/Series.vue') }, // Pagination with order
	{ name: 'series_detail', path: '/serie/detail/:id', component: () => System.import('./components/series/Detail.vue') },
	{ name: 'series_edit', path: '/serie/edit/:id', component: () => System.import('./components/series/Save.vue') },
	{ name: 'series_new', path: '/new/serie', component: () => System.import('./components/series/Save.vue') },

	{ name: 'specialties', path: '/specialties', component: () => System.import('./components/specialties/Specialties.vue') },
	{ path: '/specialties/:id', component: () => System.import('./components/specialties/Specialties.vue') }, // Pagination
	{ path: '/specialties/:id/:field', component: () => System.import('./components/specialties/Specialties.vue') }, // Pagination with order
	{ name: 'specialties_detail', path: '/specialty/detail/:id', component: () => System.import('./components/specialties/Detail.vue') },
	{ name: 'specialties_edit', path: '/specialty/edit/:id', component: () => System.import('./components/specialties/Save.vue') },
	{ name: 'specialties_new', path: '/new/specialty', component: () => System.import('./components/specialties/Save.vue') },

	{ path: '/settings', component: () => System.import('./components/settings/Settings.vue') }
];

const router = new VueRouter({
	routes
});

const app = new Vue({
    el: '#app',
    router
});