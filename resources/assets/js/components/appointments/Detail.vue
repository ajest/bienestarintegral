<template>
	<transition name="fade">
		<div class="row col-md-12 section-detail" v-if="appointment">
			<h1><span class="glyphicon glyphicon-briefcase"></span> {{ appointment.title }} 
			<router-link to="/appointments" class="btn btn-default pull-right margin-left-small"><span class="glyphicon glyphicon-calendar"></span> </router-link>
			<router-link to="/appointments" class="btn btn-primary pull-right margin-left-small"><span class="glyphicon glyphicon-pencil"></span> Editar</router-link>
			<router-link to="/appointments" class="btn btn-success pull-right margin-left-small"><span class="glyphicon glyphicon-arrow-left"></span> Volver</router-link></h1>
			<hr />
			<div class="col-md-12">
				<div class="col-md-6">
					<table class="table table-hovered">
						<thead>
							<tr>
								<td span="2">
									<h3>Información del turno</h3>
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Titulo</td>
								<td><span class="label label-default">{{ appointment.title }}</span></td>
							</tr>
							<tr>
								<td>Fecha y hora</td>
								<td><span class="label label-default">{{ appointment.date }} {{ appointment.hour }}hs</span></td>
							</tr>
							<tr>
								<td>Paciente</td>
								<td><span class="label label-default">{{ appointment.patient.name }}</span></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-md-6">
					<table class="table table-hovered">
						<thead>
							<tr>
								<td span="2">
									<h3>Tratamiento</h3>
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Profesional</td>
								<td><span class="label label-primary">{{ appointment.professional.name }}</span></td>
							</tr>
							<tr>
								<td>Area / Especialidad</td>
								<td><span class="label label-warning">{{ appointment.specialty.specialty }}</span></td>
							</tr>
							<tr>
								<td>Tratamiento</td>
								<td><span class="label label-success">{{ appointment.treatment.treatment }}</span></td>
							</tr>
							<tr>
								<td>Promoción</td>
								<td><span class="label label-danger">{{ appointment.series.series }}</span></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</transition>
</template>
<script>
	export default {
		data (){
			return {
				appointment: ''
			}
		},

		created: function(){
			this.getAppointment();
		},

		methods: {
			getAppointment(){
				var t = this; 

				axios.get('/appointments/detail/' + t.$route.params.id)
					.then(function (response) {
						if(!_.isEmpty(response.data.appointment)){

							t.appointment = response.data.appointment;
						}
					})
					.catch(function (error) {
						console.log('Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde');
					});
			}
		}
	}
</script>