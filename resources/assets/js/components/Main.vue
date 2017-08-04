<template>
	<div class="row">
		<div class="col-sm-3 col-md-2 sidebar" v-if="sidebar">
			<!--<ul class="nav nav-sidebar">
				<li :class="[active_element == 'calendar' ? 'active' : '']"><router-link to="/"><span class="glyphicon glyphicon-calendar"></span> Calendario <span class="sr-only">(current)</span></router-link></li>
				<li :class="[active_element == 'reports' ? 'active' : '']"><router-link to="/"><span class="glyphicon glyphicon-stats"></span> Reportes</router-link></li>
			</ul>-->
			<ul class="nav nav-sidebar">
				<li :class="[active_element == 'appointment' ? 'active' : '']"><router-link to="/appointments"><span class="glyphicon glyphicon-briefcase"></span> Turnos</router-link></li>
				<li :class="[active_element == 'patient' ? 'active' : '']"><router-link to="/patients"><span class="glyphicon glyphicon-user"></span> Pacientes</router-link></li>
				<li :class="[active_element == 'professional' ? 'active' : '']"><router-link to="/professionals"><span class="glyphicon glyphicon-education"></span> Profesionales</router-link></li>
				<li :class="[active_element == 'series' ? 'active' : '']"><router-link to="/series"><span class="glyphicon glyphicon-star-empty"></span> Promociones</router-link></li>
			</ul>
			<ul class="nav nav-sidebar">
				<!--<li :class="[active_element == 'account' ? 'active' : '']"><router-link to="/"><span class="glyphicon glyphicon-heart-empty"></span> Mi perfil</router-link></li>-->
				<li :class="[active_element == 'settings' ? 'active' : '']"><router-link to="/settings"><span class="glyphicon glyphicon-wrench"></span> Configuración</router-link></li>
			</ul>
		</div>
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
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
		</div>
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
				sidebar: false
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
		    	token: state => state.authentication.token
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
			}
		},

		watch: {
		    '$route' (to, from) {
		    	var t = this;

		    	if(!t.token){
		    		t.$router.push('login');
		    		this.sidebar = false;
		    	}else{
		    		let instance = window.axios.create();
    				instance.defaults.headers.common['Authorization'] = 'bearer ' + t.token;
    				this.sidebar = true;
		    	}
		    }
		}
	}
</script>