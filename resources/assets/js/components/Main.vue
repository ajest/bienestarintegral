<template>
	<div class="row">
		<div class="col-sm-3 col-md-2 sidebar">
			<ul class="nav nav-sidebar">
				<li :class="[active_element == 'calendar' ? 'active' : '']"><router-link to="/"><span class="glyphicon glyphicon-calendar"></span> Calendario <span class="sr-only">(current)</span></router-link></li>
				<li :class="[active_element == 'reports' ? 'active' : '']"><router-link to="/"><span class="glyphicon glyphicon-stats"></span> Reportes</router-link></li>
			</ul>
			<ul class="nav nav-sidebar">
				<li :class="[active_element == 'appointment' ? 'active' : '']"><router-link to="/appointments"><span class="glyphicon glyphicon-briefcase"></span> Turnos</router-link></li>
				<li :class="[active_element == 'patient' ? 'active' : '']"><router-link to="/patients"><span class="glyphicon glyphicon-user"></span> Pacientes</router-link></li>
				<li :class="[active_element == 'professional' ? 'active' : '']"><router-link to="/professionals"><span class="glyphicon glyphicon-education"></span> Profesionales</router-link></li>
				<li :class="[active_element == 'series' ? 'active' : '']"><router-link to="/series"><span class="glyphicon glyphicon-star-empty"></span> Promociones</router-link></li>
			</ul>
			<ul class="nav nav-sidebar">
				<li :class="[active_element == 'account' ? 'active' : '']"><router-link to="/"><span class="glyphicon glyphicon-heart-empty"></span> Mi perfil</router-link></li>
				<li :class="[active_element == 'settings' ? 'active' : '']"><router-link to="/settings"><span class="glyphicon glyphicon-wrench"></span> Configuración</router-link></li>
			</ul>
		</div>
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<router-view 
				v-bind:message="message"
				v-bind:success="success"
				v-bind:warning="warning"
				v-bind:danger="danger"
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
	export default {
		data () {
			return {
				message: '',
				success: true,
				warning: false,
				danger: false,
				active_element: 'calendar'
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
			}
		},

		methods: {
			operationComplete(e){
				var t = this;

				t.message = e.message;
				t.success = e.success;
				t.warning = e.warning;
				t.danger  = e.danger;
				
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
		}
	}
</script>