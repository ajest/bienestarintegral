<template>
	<div class="row">
		<div class="row col-md-12 body-principal">
			<h1><span class="glyphicon glyphicon-wrench"></span> Opciones de configuración</h1>
		</div>
		<div class="row col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-4 col-md-6 item-setting-principal"><router-link to="/appointments"><span class="glyphicon glyphicon-briefcase"></span> Turnos</router-link></div>
						<div class="col-lg-4 col-md-6 item-setting-principal"><router-link to="/patients"><span class="glyphicon glyphicon-user"></span> Pacientes</router-link></div>
						<div class="col-lg-4 col-md-6 item-setting-principal"><router-link to="/professionals"><span class="glyphicon glyphicon-education"></span> Profesionales</router-link></div>
						<div class="col-lg-4 col-md-6 item-setting-principal"><router-link to="/series"><span class="glyphicon glyphicon-star-empty"></span> Promociones</router-link></div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-4 col-md-6 item-setting item-setting-specialties"><router-link to="/specialties"><span class="glyphicon glyphicon-road"></span> Especialidades</router-link></div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-4 col-md-6 item-setting item-setting-auth"><router-link to="/authorization"><span class="glyphicon glyphicon-lock"></span> Autorización</router-link></div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-4 col-md-6 item-setting item-setting-emails"><router-link to="/authorization"><span class="glyphicon glyphicon-envelope"></span> Envios</router-link></div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</template>
<script>
	export default {
		data () {
			return {
				active_element: 'settings'
			}
		},
		
		created: function(){
			this.$emit('child_created', this.active_element);
		},

        props: ['message', 'success', 'warning', 'danger']
	}
</script>