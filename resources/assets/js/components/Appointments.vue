<template>
	<div class="row">		
		<div class="row col-md-12">
			<h1>Turnos <small><a href="#" class="font-size-14">Nuevo turno [+]</a></small></h1>
		</div>
		<div class="row col-md-12">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<td>Título</td>
						<td>Paciente</td>
						<td>Profesional</td>
						<td>Área</td>
						<td>Tratamiento</td>
						<td>Fecha</td>
						<td>Acciones</td>
					</tr>
				</thead>
				<tbody>					
					<tr v-for="appointment in appointments">
						<td>{{ appointment.titulo }}</td>
						<td>{{ appointment.paciente }}</td>
						<td>{{ appointment.profesional }}</td>
						<td>{{ appointment.area }}</td>
						<td>{{ appointment.tratamiento }}</td>
						<td>{{ appointment.fecha }}</td>
						<td>
							<a class="pull-right btn btn-success" :href="'/appointments/' + appointment.id" title="Ver Turno">Ver</a>
							<a class="pull-right btn btn-primary" :href="'/appointments/' + appointment.id + '/edit'" title="Editar paciente">Editar</a>
						</td>
					</tr>					
				</tbody>
			</table>
		</div>
	</div>
</template>
<script>
	export default {
		data () {
			return {
				appointments: []
			}
		},
		created: function(){

			var t = this;

			axios.get('/appointments/list')
				.then(function (response) {
					if(!_.isEmpty(response.data.appointments.data)){
						_.forEach(response.data.appointments.data, function(value) {							
							t.appointments.push({
									'id' : value.id,
									'titulo': value.title,
									'paciente': value.patient.name,
									'profesional': value.professional.name,
									'area': value.area,
									'tratamiento': value.treatment.treatment,
									'fecha': value.date
								});
						});
					}
				})
				.catch(function (error) {
					console.log('Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde');
				});
		}
	}
</script>