<template>
	<div class="row">
		<div class="row col-md-12">
			<h1>Directorio pacientes <small><a href="#" class="font-size-14">Nuevo paciente [+]</a></small></h1>		
		</div>
		<div class="row col-md-12">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<td>Nombre</td>
						<td>Email</td>
						<td>Teléfono</td>
						<td>Sexo</td>
						<td>Dirección</td>
						<td>Fecha alta</td>
						<td>Acciones</td>
					</tr>
				</thead>
				<tbody>				
					<tr v-for="patient in patients">
						<td>{{ patient.nombre }}</td>
						<td>{{ patient.email }}</td>
						<td>{{ patient.telefono }}</td>
						<td>{{ patient.sexo }}</td>
						<td>{{ patient.direccion }}</td>
						<td>{{ patient.fecha }}</td>
						<td>
							<a class="pull-right btn btn-primary margin-list-button" :href="'/patients/' + patient.id + '/edit'" title="Editar paciente"><span class="glyphicon glyphicon-pencil"></span></a>
							<a class="pull-right btn btn-success margin-list-button" :href="'/patients/' + patient.id" title="Ver Turno"><span class="glyphicon glyphicon-eye-open"></span></a>
							<a class="pull-right btn btn-danger margin-list-button" :href="'/patients/' + patient.id" title="Cancelar Turno"><span class="glyphicon glyphicon-remove"></span></a>
						</td>
					</tr>				
				</tbody>
			</table>
		</div>
		<nav aria-label="Page navigation">
			<ul class="pagination">
				<li :class="[ current_page == 1 ? 'disabled' : '' ]">
					<a v-on:click="getPreviousPage()" href="javascript:;" aria-label="Previous">
						<span aria-hidden="true">&laquo;</span>
					</a>
				</li>
				
				<li v-for="page in last_page" :class="[ page == current_page ? 'active' : '' ]">
					<router-link :to="'/patients/' + page"> {{ page }} </router-link>
				</li>
				
				<li :class="[ current_page == last_page ? 'disabled' : '' ]">
					<a v-on:click="getNextPage()" href="javascript:;" aria-label="Next">
						<span aria-hidden="true">&raquo;</span>
					</a>
				</li>
			</ul>
		</nav>
	</div>	
</template>
<script>
	export default {
		data () {
			return {
				patients: [],
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
				this.$router.push({ path: '/patients/' + (previous_page < 1 ? 1 : previous_page) });
			},

			getNextPage: function(){
				var next_page = this.current_page + 1;
				this.$router.push({ path: '/patients/' + (next_page > this.last_page ? this.last_page : next_page) });
			},
			paginationCallback(){
				var t = this;

				axios.get('/patients/list/' + (t.$route.params.id ? t.$route.params.id : 1))
					.then(function (response) {
						if(!_.isEmpty(response.data.patients.data)){

							t.patients = [];

							_.forEach(response.data.patients.data, function(value) {
								t.patients.push({
										'id' : value.id,
										'nombre': value.name,
										'email': value.email,
										'telefono': value.tel,
										'sexo': value.gender,
										'direccion': value.address,
										'fecha': value.created_at
									});

								t.last_page 	= response.data.patients.last_page;
								t.current_page  = response.data.patients.current_page;
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