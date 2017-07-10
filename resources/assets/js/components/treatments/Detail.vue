<template>
	<transition name="fade">
		<div class="row col-md-12 section-detail" v-if="treatment">
			<h1><span class="glyphicon glyphicon-leaf"></span> {{ treatment.treatment.treatment }} 
			<router-link to="/treatments" class="btn btn-default pull-right margin-left-small"><span class="glyphicon glyphicon-calendar"></span> </router-link>
			<router-link :to="{ name: 'treatments_edit', params: { id: treatment.treatment.id }}" class="btn btn-primary pull-right margin-left-small"><span class="glyphicon glyphicon-pencil"></span> Editar</router-link>
			<router-link to="/treatments" class="btn btn-success pull-right margin-left-small"><span class="glyphicon glyphicon-arrow-left"></span> Listado</router-link></h1>
			<hr />
			<div class="col-md-12">
				<div class="col-md-6">
					<table class="table table-hovered">
						<thead>
							<tr>
								<td span="2">
									<h3>Información del tratamiento</h3>
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Name</td>
								<td><span class="label label-default">{{ treatment.treatment.treatment }}</span></td>
							</tr>
							<tr>
								<td>Especialidad</td>
								<td><span class="label label-warning">{{ treatment.specialty.specialty }}</span></td>
							</tr>
							<tr>
								<td>Description</td>
								<td>{{ treatment.treatment.description }}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-12" v-if="Object.keys(treatment.appointments).length">
				<table class="table table-hovered">
					<thead>
						<tr>
							<td span="2">
								<h3>Próximos Turnos</h3>
							</td>
						</tr>
					</thead>
					<tbody>
						<tr v-for="appointment in treatment.appointments">
							<td>{{ appointment.date }} {{ appointment.hour }}hs</td>
							<td><span class="label label-success">{{ treatment.treatment.treatment }}</span> para <span class="label label-primary">{{ appointment.patient.name }}</span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-12" v-if="Object.keys(treatment.history).length">
				<table class="table table-hovered">
					<thead>
						<tr>
							<td span="2">
								<h3>Historial</h3>
							</td>
						</tr>
					</thead>
					<tbody>
						<tr v-for="history in treatment.history">
							<td>{{ history.date }} {{ history.hour }}hs</td>
							<td>
								<p><span class="label label-success">{{ treatment.treatment.treatment }}</span> para <span class="label label-primary">{{ history.patient.name }}</span><router-link class="btn btn-default pull-right" :to="{ name: 'appointments_detail', params: { id: history.id }}" title="Ver Turno"><span class="glyphicon glyphicon-eye-open"></span></router-link></p>
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
				treatment: '',
				active_element: 'settings'
			}
		},

		created: function(){
			this.getTreatment();
			this.$emit('child_created', this.active_element);
		},

		methods: {
			getTreatment(){
				var t = this; 

				axios.get('/treatments/detail/' + t.$route.params.id)
					.then(function (response) {
						if(!_.isEmpty(response.data)){
							t.treatment = response.data;
						}
					})
					.catch(function (error) {
						console.log('Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde');
					});
			}
		}
	}
</script>