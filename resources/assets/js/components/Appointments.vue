<template>
	<div class="row">		
		<div class="row col-md-12">
			<h1>Turnos <small><a href="#" class="font-size-14">Nuevo turno [+]</a></small></h1>
		</div>
		<div class="row col-md-12">
			<table class="table table-striped table-hover table-head-strong">
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
				<transition-group name="list" tag="tbody">
					<tr v-for="appointment in appointments" v-bind:key="appointment" class="list-item">
						<td>{{ appointment.titulo }}</td>
						<td>{{ appointment.paciente }}</td>
						<td>{{ appointment.profesional }}</td>
						<td>{{ appointment.area }}</td>
						<td>{{ appointment.tratamiento }}</td>
						<td>{{ appointment.fecha }}</td>
						<td>								
							<div class="three-buttons">
								<a class="btn btn-success margin-list-button" :href="'/appointments/' + appointment.id" title="Ver Turno"><span class="glyphicon glyphicon-eye-open"></span></a>
								<a class="btn btn-primary margin-list-button" :href="'/appointments/' + appointment.id + '/edit'" title="Editar paciente"><span class="glyphicon glyphicon-pencil"></span></a>							
								<a class="btn btn-danger margin-list-button" href="#" data-toggle="modal" data-target="#confirmDelete" title="Cancelar Turno" @click="confirmDelete(appointment.id)"><span class="glyphicon glyphicon-remove"></span></a>
							</div>							
						</td>
					</tr>
				</transition-group>
			</table>
			<pagination v-bind:last_page="last_page" v-bind:current_page="current_page" v-bind:url="url"></pagination>
			<popupdeleteconfirm v-on:success="operationSuccess()" v-bind:element_id="appointment_id" v-bind:elements="appointments" v-bind:url="url" v-bind:delete_text_confirm="delete_text_confirm"></popupdeleteconfirm>
		</div>
	</div>
</template>
<script>
	import Pagination from './partials/Pagination.vue';
	import PopupDeleteConfirm from './partials/PopupDeleteConfirm.vue';

	export default {
		data () {
			return {
				appointments: [],
				last_page: 0,
				current_page: 1,
				appointment_id: 0,
				url: '/appointments/', // For the delete
				delete_text_confirm: ['El turno', null, ' para el paciente ', null, 'va a ser eliminado. ¿Está seguro que desea eliminar el turno', ' permanentemente', '?']
			}
		},
		
		created: function(){
			this.paginationCallback();
		},
		
		methods: {
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
			},

			confirmDelete(id){
				this.appointment_id = id;
			},

			operationSuccess(){
				var t = this;

				_.forEach(t.appointments, function(value, i){
					if(value.id == t.appointment_id){
						t.appointments = t.appointments.filter(function (item) {
						    return t.appointment_id != item.id;
						});
					}
				});
			}
		},

		watch: {
		    '$route' (to, from) {
			    this.paginationCallback();
		    }
		},
        
        components: {
            'pagination' : Pagination,
            'popupdeleteconfirm' : PopupDeleteConfirm
        }
	}
</script>