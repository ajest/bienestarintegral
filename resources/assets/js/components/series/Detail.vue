<template>
	<transition name="fade">
		<div class="row col-md-12 section-detail" v-if="series">
			<h1><span class="glyphicon glyphicon-star-empty"></span> {{ series.series.series }} 
			<router-link to="/series" class="btn btn-default pull-right margin-left-small"><span class="glyphicon glyphicon-calendar"></span> </router-link>
			<router-link :to="{ name: 'series_edit', params: { id: series.series.id }}" class="btn btn-primary pull-right margin-left-small"><span class="glyphicon glyphicon-pencil"></span> Editar</router-link>
			<router-link to="/series" class="btn btn-success pull-right margin-left-small"><span class="glyphicon glyphicon-arrow-left"></span> Listado</router-link></h1>
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
							<td><span class="label label-default">{{ series.series.series }}</span></td>
						</tr>
						<tr>
							<td>Cantidad máxima de usos</td>
							<td><span class="label label-default">{{ series.series.cant }}</span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-12" v-if="Object.keys(series.appointments).length">
				<table class="table table-hovered">
					<thead>
						<tr>
							<td span="2">
								<h3>Próximos Turnos</h3>
							</td>
						</tr>
					</thead>
					<tbody>
						<tr v-for="appointment in series.appointments">
							<td>{{ appointment.date }} {{ appointment.hour }}hs</td>
							<td><span class="label label-success">{{ appointment.treatment.treatment }}</span> para <span class="label label-primary">{{ appointment.patient.name }}</span></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-12" v-if="Object.keys(series.history).length">
				<table class="table table-hovered">
					<thead>
						<tr>
							<td span="2">
								<h3>Historial</h3>
							</td>
						</tr>
					</thead>
					<tbody>
						<tr v-for="history in series.history">
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
				series: '',
				active_element: 'series'
			}
		},

		created: function(){
			this.getSeries();
			this.$emit('child_created', this.active_element);
		},

		methods: {
			getSeries(){
				var t = this; 

				axios.get('/series/detail/' + t.$route.params.id)
					.then(function (response) {
						if(!_.isEmpty(response.data)){

							t.series = response.data;
						}
					})
					.catch(function (error) {
						console.log('Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde');
					});
			}
		}
	}
</script>