<template>
	<div>
		<v-navigation-drawer 
			temporary
			v-model="drawer"
      		:mini-variant.sync="mini" 
      		light 
      		overflow 
      		v-if="sidebar">
		      	<!--<ul class="nav nav-sidebar">
					<li :class="[active_element == 'calendar' ? 'active' : '']"><router-link to="/"><span class="glyphicon glyphicon-calendar"></span> Calendario <span class="sr-only">(current)</span></router-link></li>
					<li :class="[active_element == 'reports' ? 'active' : '']"><router-link to="/"><span class="glyphicon glyphicon-stats"></span> Reportes</router-link></li>
				</ul>-->
			<v-list class="pa-0">
				<v-list-tile avatar tag="div">
					<v-list-tile-action>
						<v-icon>account_circle</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>{{ authentication_text }}</v-list-tile-title>
					</v-list-tile-content>
					<v-list-tile-action>
						<v-btn icon dark @click.native.stop="mini = !mini">
							<v-icon>chevron_left</v-icon>
						</v-btn>
					</v-list-tile-action>
				</v-list-tile>
			</v-list>
			<v-list class="pt-0" dense>
				<v-divider></v-divider>
				<v-list-tile to="/" :class="[active_element == 'calendar' ? 'active' : '']">
					<v-list-tile-action>
						<v-icon>dashboard</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Dashboard</v-list-tile-title>
					</v-list-tile-content>
				</v-list-tile>
				<v-list-tile to="/appointments" :class="[active_element == 'appointment' ? 'active' : '']">
					<v-list-tile-action>
						<v-icon>assignment_ind</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Turnos</v-list-tile-title>
					</v-list-tile-content>
				</v-list-tile>
				<v-list-tile to="/patients" :class="[active_element == 'patient' ? 'active' : '']">
					<v-list-tile-action>
						<v-icon>face</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Pacientes</v-list-tile-title>
					</v-list-tile-content>
				</v-list-tile>
				<v-list-tile to="/professionals" :class="[active_element == 'professional' ? 'active' : '']">
					<v-list-tile-action>
						<v-icon>school</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Profesionales</v-list-tile-title>
					</v-list-tile-content>
				</v-list-tile>
				<v-list-tile to="/series" :class="[active_element == 'series' ? 'active' : '']">
					<v-list-tile-action>
						<v-icon>card_giftcard</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Promociones</v-list-tile-title>
					</v-list-tile-content>
				</v-list-tile>
				<v-list-tile to="/settings" :class="[active_element == 'settings' ? 'active' : '']">
					<v-list-tile-action>
						<v-icon>settings</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Configuración</v-list-tile-title>
					</v-list-tile-content>
				</v-list-tile>
				<v-divider></v-divider>
				<v-list-tile @click="logout">
					<v-list-tile-action>
						<v-icon>exit_to_app</v-icon>
					</v-list-tile-action>
					<v-list-tile-content>
						<v-list-tile-title>Cerrar sesión</v-list-tile-title>
					</v-list-tile-content>
				</v-list-tile>
			</v-list>
		</v-navigation-drawer>
		<v-toolbar fixed class="green" dark>
			<v-toolbar-side-icon v-if="sidebar" @click.native.stop="drawer = !drawer"></v-toolbar-side-icon>
			<v-toolbar-title to="/">Bienestar</v-toolbar-title>
		</v-toolbar>
		<main>
			<v-container fluid>
				<router-view
					v-bind:message="message"
					v-bind:success="success"
					v-bind:warning="warning"
					v-bind:danger="danger"
					v-bind:error="error"
					v-on:complete="operationComplete"
					v-on:child_created="setActiveElement"></router-view>
				<transition name="fade">			
					<div v-if="message">
						<div class="g-msg" :class="classObjectContainer">
							<span><i class="glyphicon" :class="classObjectIcon"></i> {{ message }}</span>
						</div>
					</div>
				</transition>
			</v-container>
		</main>
	</div>
</template>
<script>
	import { mapState } from 'vuex';

	export default {
		data () {
			return {
				message: '',
				success: true,
				warning: false,
				danger: false,
				error: {},
				active_element: 'calendar',
				sidebar: false,
				//Nav
				authentication_text: 'Ingresar',
				authentication_icon: 'glyphicon-log-in',
				// Vuetify
				drawer: null,
				mini: false,
        		right: null
			}
		},

		computed: {
			classObjectContainer: function () {
				return {
					'global-message': this.success,
					'global-message-warning': this.warning,
					'global-message-danger': this.danger
				}
			},
			classObjectIcon: function () {
				return {
					'glyphicon-ok': this.success,
					'glyphicon-warning-sign': this.warning,
					'glyphicon-remove': this.danger
				}
			},
			...mapState({
		    	baseUrl: state => state.common.baseUrl,
		    	token: state => state.authentication.token,
		    	logged_professional: state => state.authentication.professional
		    })
		},

		created: function(){
			if(!this.token){
				this.sidebar = false;
			}
		},

		methods: {
			operationComplete(e){
				var t = this;

				t.message = e.message;
				t.success = e.success;
				t.warning = e.warning;
				t.danger  = e.danger;

				if(!_.isEmpty(e.error)){
					if(!_.isEmpty(e.error.response.data.error)){
						switch(e.error.response.data.error){
							case 'token_not_provided':
							case 'token_expired':
								t.message = 'La sesión ha expirado. Por favor vuelva a ingresar. Muchas gracias';
								t.success = false;
								t.warning = true;
								t.danger  = false;
								t.$store.commit('clearLoginData');
								t.$router.push('/login');
								break;
							case 'user_not_found':
								t.message = 'Su usuario ha sido eliminado. Disculpe las molestias';
								t.success = false;
								t.warning = true;
								t.danger  = false;
								t.$store.commit('clearLoginData');
								t.$router.push('/login');
								break;
						}
					}
				}
				
				setTimeout(function(){
					t.message = '';
					t.success = true;
					t.warning = false;
					t.danger  = false;
				}, 8000);
			},

			setActiveElement(e){
				this.active_element = e;
			},

			logout(){
				var t = this;

				t.$store.commit('clearLoginData');
				t.$router.push('/login');
			}
		},

		watch: {
		    '$route' (to, from) {
		    	var t = this;

		    	if(!t.token){
		    		t.$router.push('/login');
		    		this.sidebar = false;
		    		t.authentication_text = '',
					t.authentication_icon = ''
		    	}else{
		    		let instance = window.axios.create();
    				instance.defaults.headers.common['Authorization'] = 'bearer ' + t.token;
    				this.sidebar = true;
    				t.authentication_text = t.logged_professional.name;
					t.authentication_icon = 'glyphicon-log-out';
		    	}
		    }
		}
	}
</script>