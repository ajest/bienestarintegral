<template>
	<transition name="fade">
		<div class="row col-md-12 section-detail" v-if="patient">
			<h1><span class="glyphicon glyphicon-briefcase"></span> {{ patient.title }} 
			<router-link to="/patients" class="btn btn-default pull-right margin-left-small"><span class="glyphicon glyphicon-calendar"></span> </router-link>
			<router-link :to="{ name: 'patients_edit', params: { id: patient.patient.id }}" class="btn btn-primary pull-right margin-left-small"><span class="glyphicon glyphicon-pencil"></span> Editar</router-link>
			<router-link to="/patients" class="btn btn-success pull-right margin-left-small"><span class="glyphicon glyphicon-arrow-left"></span> Listado</router-link></h1>
			<hr />
			<div class="col-md-12">
				<table class="table table-hovered">
					<thead>
						<tr>
							<td span="2">
								<h3>Información personal</h3>
							</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Nombre</td>
							<td><span class="label label-default">{{ patient.patient.name }}</span></td>
						</tr>
						<tr>
							<td>Email</td>
							<td><span class="label label-default">{{ patient.patient.email }}</span></td>
						</tr>
						<tr>
							<td>Teléfono</td>
							<td><span class="label label-default">{{ patient.patient.tel }}</span></td>
						</tr>
						<tr>
							<td>Domicilio</td>
							<td><span class="label label-default">{{ patient.patient.address }}</span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-12" v-if="Object.keys(patient.appointments).length">
				<table class="table table-hovered">
					<thead>
						<tr>
							<td span="2">
								<h3>Próximos Turnos</h3>
							</td>
						</tr>
					</thead>
					<tbody>
						<tr v-for="appointment in patient.appointments">
							<td>{{ appointment.date }} {{ appointment.hour }}hs</td>
							<td>para <span class="label label-success">{{ appointment.treatment.treatment }}</span> con <span class="label label-primary">{{ appointment.professional.name }}</span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-12" v-if="Object.keys(patient.history).length">
				<table class="table table-hovered">
					<thead>
						<tr>
							<td span="2">
								<h3>Historial</h3>
							</td>
						</tr>
					</thead>
					<tbody>
						<tr v-for="history in patient.history">
							<td>{{ history.date }} {{ history.hour }}hs</td>
							<td>
								<p>para <span class="label label-success">{{ history.treatment.treatment }}</span> con <span class="label label-primary">{{ history.professional.name }}</span><router-link class="btn btn-default pull-right" :to="{ name: 'appointments_detail', params: { id: history.id }}" title="Ver Turno"><span class="glyphicon glyphicon-eye-open"></span></router-link></p>
							<p><strong v-if="history.comments">Comentarios: </strong> {{ history.comments }} </p></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</transition>
</template>
<script>
	export default {
		data (){
			return {
				patient: '',
				active_element: 'patient'
			}
		},

		created: function(){
			this.getPatient();
			this.$emit('child_created', this.active_element);
		},

		methods: {
			getPatient(){
				var t = this; 

				axios.get('/patients/detail/' + t.$route.params.id)
					.then(function (response) {
						if(!_.isEmpty(response.data)){

							t.patient = response.data;
						}
					})
					.catch(function (error) {
						console.log('Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde');
					});
			}
		}
	}
</script>