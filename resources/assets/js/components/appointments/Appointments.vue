<template>
	<div class="row">		
		<div class="row col-md-12">
			<h1><span class="glyphicon glyphicon-briefcase"></span> Turnos <router-link :to="{ name: 'appointments_new'}" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Nuevo turno</router-link></h1>
		</div>
		<div class="row col-md-12">
			<table class="table table-striped table-hover table-head-strong table-bi">
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
								<router-link class="btn btn-success margin-list-button" :to="{ name: 'appointments_detail', params: { id: appointment.id }}" title="Ver Turno"><span class="glyphicon glyphicon-eye-open"></span></router-link>							
								<router-link class="btn btn-primary margin-list-button" :to="{ name: 'appointments_edit', params: { id: appointment.id }}" title="Editar Turno"><span class="glyphicon glyphicon-pencil"></span></router-link>
								<a class="btn btn-danger margin-list-button" href="#" data-toggle="modal" data-target="#confirmDelete" title="Cancelar Turno" @click="confirmDelete(appointment.id)"><span class="glyphicon glyphicon-remove"></span></a>
							</div>							
						</td>
					</tr>
				</transition-group>
			</table>
		</div>
		<pagination v-bind:last_page="last_page" v-bind:current_page="current_page" v-bind:url="url"></pagination>
		<popupdeleteconfirm v-on:success="operationSuccess" v-on:error="operationError" v-bind:element_id="appointment_id" v-bind:elements="appointments" v-bind:url="url" v-bind:delete_text_confirm="delete_text_confirm"></popupdeleteconfirm>
	</div>
</template>
<script>
	import Pagination from '../partials/Pagination.vue';
	import PopupDeleteConfirm from '../popups/PopupDeleteConfirm.vue';

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
								let no_asigned_text = '-NO ASIGNADO-';

								t.appointments.push({
										'id' : value.id,
										'titulo': value.title ? value.title : '-SIN TÍTULO-',
										'paciente': value.patient.name,
										'profesional': value.professional ? value.professional.name : no_asigned_text,
										'area': value.specialty ? value.specialty.specialty : no_asigned_text,
										'tratamiento': value.treatment ? value.treatment.treatment : no_asigned_text,
										'fecha': value.date + ' ' + value.hour
									});

								t.last_page 	= response.data.appointments.last_page;
								t.current_page  = response.data.appointments.current_page;
							});
						}
					})
					.catch(function (error) {
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Intente nuevamente más tarde', success: false, warning: false, danger: true});
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

				t.$emit('complete', {message:  'La operación se realizó correctamente', success: true, warning: false, danger: false});
			},

			operationError(){
				t.$emit('complete', {message:  'Ha ocurrido un error inesperado. Intente nuevamente más tarde', success: false, warning: false, danger: true});	
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
        },

        props: ['message', 'success', 'warning', 'danger']
	}
</script>