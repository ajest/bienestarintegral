<template>
	<div class="row">
		<div class="col-sm-3 col-md-2 sidebar">
			<ul class="nav nav-sidebar">
				<li class="active"><a href="#">Calendario <span class="sr-only">(current)</span></a></li>
				<li><a href="#">Reportes</a></li>
			</ul>
			<ul class="nav nav-sidebar">
				<li><a href="">Turnos</a></li>
				<li><a href="">Pacientes</a></li>
				<li><a href="">Profesionales</a></li>
				<li><a href="">Promociones</a></li>
			</ul>
			<ul class="nav nav-sidebar">
				<li><a href="">Mi perfil</a></li>
				<li><a href="">Configuración</a></li>
				<li><a href="">Soporte</a></li>
			</ul>
		</div>
		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			<router-view 
				v-bind:message="message"
				v-bind:success="success"
				v-bind:warning="warning"
				v-bind:danger="danger"
				v-on:complete="operationComplete"></router-view>
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
				danger: false
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
			}
		}
	}
</script>