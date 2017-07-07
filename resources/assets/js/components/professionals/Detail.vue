<template>
	<transition name="fade">
		<div class="row col-md-12 section-detail" v-if="professional">
			<h1><span class="glyphicon glyphicon-briefcase"></span> {{ professional.professional.name }} 
			<router-link to="/professionals" class="btn btn-default pull-right margin-left-small"><span class="glyphicon glyphicon-calendar"></span> </router-link>
			<router-link :to="{ name: 'professionals_edit', params: { id: professional.professional.id }}" class="btn btn-primary pull-right margin-left-small"><span class="glyphicon glyphicon-pencil"></span> Editar</router-link>
			<router-link to="/professionals" class="btn btn-success pull-right margin-left-small"><span class="glyphicon glyphicon-arrow-left"></span> Listado</router-link></h1>
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
							<td><span class="label label-default">{{ professional.professional.name }}</span></td>
						</tr>
						<tr>
							<td>Email</td>
							<td><span class="label label-default">{{ professional.professional.email }}</span></td>
						</tr>
						<tr>
							<td>Teléfono</td>
							<td><span class="label label-default">{{ professional.professional.tel }}</span></td>
						</tr>
						<tr>
							<td>Domicilio</td>
							<td><span class="label label-default">{{ professional.professional.address }}</span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-12" v-if="Object.keys(professional.appointments).length">
				<table class="table table-hovered">
					<thead>
						<tr>
							<td span="2">
								<h3>Próximos Turnos</h3>
							</td>
						</tr>
					</thead>
					<tbody>
						<tr v-for="appointment in professional.appointments">
							<td>{{ appointment.date }} {{ appointment.hour }}hs</td>
							<td><span class="label label-success">{{ appointment.treatment.treatment }}</span> para <span class="label label-primary">{{ appointment.patient.name }}</span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-12" v-if="Object.keys(professional.history).length">
				<table class="table table-hovered">
					<thead>
						<tr>
							<td span="2">
								<h3>Historial</h3>
							</td>
						</tr>
					</thead>
					<tbody>
						<tr v-for="history in professional.history">
							<td>{{ history.date }} {{ history.hour }}hs</td>
							<td>
								<p><span class="label label-success">{{ history.treatment.treatment }}</span> para <span class="label label-primary">{{ history.patient.name }}</span><router-link class="btn btn-default pull-right" :to="{ name: 'appointments_detail', params: { id: history.id }}" title="Ver Turno"><span class="glyphicon glyphicon-eye-open"></span></router-link></p>
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
				professional: '',
				active_element: 'professional'
			}
		},

		created: function(){
			this.getProfessional();
			this.$emit('child_created', this.active_element);
		},

		methods: {
			getProfessional(){
				var t = this; 

				axios.get('/professionals/detail/' + t.$route.params.id)
					.then(function (response) {
						if(!_.isEmpty(response.data)){

							t.professional = response.data;
						}
					})
					.catch(function (error) {
						console.log('Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde');
					});
			}
		}
	}
</script>