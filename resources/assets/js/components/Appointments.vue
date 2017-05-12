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
							<a class="pull-right btn btn-primary margin-list-button" :href="'/appointments/' + appointment.id + '/edit'" title="Editar paciente"><span class="glyphicon glyphicon-pencil"></span></a>
							<a class="pull-right btn btn-success margin-list-button" :href="'/appointments/' + appointment.id" title="Ver Turno"><span class="glyphicon glyphicon-eye-open"></span></a>
							<a class="pull-right btn btn-danger margin-list-button" :href="'/appointments/' + appointment.id" title="Cancelar Turno"><span class="glyphicon glyphicon-remove"></span></a>
						</td>
					</tr>					
				</tbody>
			</table>
			<nav aria-label="Page navigation">
				<ul class="pagination">
					<li :class="[ current_page == 1 ? 'disabled' : '' ]">
						<a v-on:click="getPreviousPage()" href="javascript:;" aria-label="Previous">
							<span aria-hidden="true">&laquo;</span>
						</a>
					</li>
					
					<li v-for="page in last_page" :class="[ page == current_page ? 'active' : '' ]">
						<router-link :to="'/appointments/' + page"> {{ page }} </router-link>
					</li>
					
					<li :class="[ current_page == last_page ? 'disabled' : '' ]">
						<a v-on:click="getNextPage()" href="javascript:;" aria-label="Next">
							<span aria-hidden="true">&raquo;</span>
						</a>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</template>
<script>
	export default {
		data () {
			return {
				appointments: [],
				last_page: 0,
				current_page: 1
			}
		},
		
		created: function(){
			this.paginationCallback();
		},
		
		methods: {
			getPreviousPage: function(){
				var previous_page = this.current_page - 1;
				this.$router.push({ path: '/appointments/' + (previous_page < 1 ? 1 : previous_page) });
			},

			getNextPage: function(){
				var next_page = this.current_page + 1;
				this.$router.push({ path: '/appointments/' + (next_page > this.last_page ? this.last_page : next_page) });
			},
			paginationCallback(){
				var t = this;

				axios.get('/appointments/list/' + (t.$route.params.id ? t.$route.params.id : 1))
					.then(function (response) {
						if(!_.isEmpty(response.data.appointments.data)){

							t.appointments = [];

							_.forEach(response.data.appointments.data, function(value) {
								t.appointments.push({
										'id' : value.id,
										'titulo': value.title,
										'paciente': value.patient.name,
										'profesional': value.professional.name,
										'area': value.specialty.specialty,
										'tratamiento': value.treatment.treatment,
										'fecha': value.date
									});

								t.last_page 	= response.data.appointments.last_page;
								t.current_page  = response.data.appointments.current_page;
							});
						}
					})
					.catch(function (error) {
						console.log('Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde');
					});
			}
		},

		watch: {
		    '$route' (to, from) {
			    this.paginationCallback();
		    }
		}
	}
</script>