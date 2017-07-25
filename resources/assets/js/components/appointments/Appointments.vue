<template>
	<div class="row">		
		<div class="row col-md-12 body-principal">
			<h1><span class="glyphicon glyphicon-briefcase"></span> Turnos</h1>
		</div>
		<div class="row col-md-12">
			<div class="col-md-10">
				<div class="col-md-6">
					<div class="input-group">
						<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
						<input class="form-control" placeholder="Ingrese su búsqueda" v-model="search_in_table" @keyup="searchAppointment">
						<span class="input-group-addon searching_in_table" v-show="searching_in_table">Buscando..</span>
					</div>
				</div>
				<div class="col-md-6">
					<select v-model="filtros.status" class="form-control" @change="filterStatusCallback">
						<option value="0">Todos</option>
						<option value="1">Pendientes</option>
						<option value="2">Anteriores</option>
					</select>
				</div>
			</div>
			<div class="col-md-2">
				<router-link :to="{ name: 'appointments_new'}" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Nuevo turno</router-link>
			</div>
		</div>
		<div class="row col-md-12">
			<div class="table-responsive">
				<table class="table table-striped table-hover table-head-strong table-bi">
					<thead>
						<tr>
							<td><router-link :to="url + '1/title'">Título</router-link></td>
							<td><router-link :to="url + '1/patient'">Paciente</router-link></td>
							<td><router-link :to="url + '1/professional'">Profesional</router-link></td>
							<td><router-link :to="url + '1/specialty'">Area</router-link></td>
							<td><router-link :to="url + '1/treatment'">Tratamiento</router-link></td>
							<td><router-link :to="url + '1/date'">Fecha</router-link></td>
							<td>Acciones</td>
						</tr>
					</thead>
					<transition-group name="list" tag="tbody" v-if="appointments.length > 0">
						<tr v-for="appointment in appointments" v-bind:key="appointment" class="list-item">
							<td v-html="appointment.titulo"></td>
							<td v-html="appointment.paciente"></td>
							<td v-html="appointment.profesional"></td>
							<td v-html="appointment.area"></td>
							<td v-html="appointment.tratamiento"></td>
							<td v-html="appointment.fecha"></td>
							<td>								
								<div class="three-buttons">
									<router-link class="btn btn-success margin-list-button" :to="{ name: 'appointments_detail', params: { id: appointment.id }}" title="Ver Turno"><span class="glyphicon glyphicon-eye-open"></span></router-link>							
									<router-link class="btn btn-primary margin-list-button" :to="{ name: 'appointments_edit', params: { id: appointment.id }}" title="Editar Turno"><span class="glyphicon glyphicon-pencil"></span></router-link>
									<a class="btn btn-danger margin-list-button" href="#" data-toggle="modal" data-target="#confirmDelete" title="Cancelar Turno" @click="confirmDelete(appointment.id)"><span class="glyphicon glyphicon-remove"></span></a>
								</div>							
							</td>
						</tr>
					</transition-group>
					<tbody v-else>
						<tr >
							<td colspan="7">{{ no_data_msg }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<pagination v-bind:last_page="last_page" v-bind:current_page="current_page" v-bind:url="url" v-bind:search_in_table="search_in_table"></pagination>
		<popupdeleteconfirm v-on:success="operationSuccess" v-on:error="operationError" v-bind:element_id="appointment_id" v-bind:elements="appointments" v-bind:url="url" v-bind:delete_text_confirm="delete_text_confirm"></popupdeleteconfirm>
	</div>
</template>
<script>
	import Pagination from '../partials/Pagination.vue';
	import PopupDeleteConfirm from '../popups/PopupDeleteConfirm.vue';
	
	import common from '../../mixins.js';

	import { mapState } from 'vuex';

	export default {
		data () {
			return {
				appointments: [],
				last_page: 0,
				current_page: 1,
				appointment_id: 0,
				url: '/appointments/', // For the delete
				delete_text_confirm: ['El turno', null, ' para el paciente ', null, 'va a ser eliminado. ¿Está seguro que desea eliminar el turno', ' permanentemente', '?'],
				searching_in_table: false,
				search_in_table: '',
				delayTimer: '',
				opened_highlighted_tag: '<strong>',
				closed_highlighted_tag: '</strong>',
				filtros: {
					status: 0
				},
				active_element: 'appointment',
				no_data_msg: 'Cargando..'
			}
		},
		
		computed: {
			...mapState({
		    	baseUrl: state => state.common.baseUrl
		    })
		},
		
		created: function(){
			this.paginationCallback();
			this.$emit('child_created', this.active_element);
		},
		
		methods: {
			paginationCallback(){
				var t = this;

				axios.get(t.baseUrl + '/appointments/list/' + (t.$route.params.id ? t.$route.params.id : 1) + '/' + (t.$route.params.field ? t.$route.params.field : ''), 
					{
						params: {
							'filtro_status': t.filtros.status,
							'term': t.search_in_table
						}
					})
					.then(function (response) {
						t.appointments = [];

						if(!_.isEmpty(response.data.appointments.data)){
							_.forEach(response.data.appointments.data, function(value) {
								
								var index = value.patient.name.indexOf(t.search_in_table);
								var text_lenght = t.search_in_table.length;

								let no_asigned_text = '-NO ASIGNADO-';

								if(index >= 0){
							        value.patient.name = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.patient.name); 
							    }

							    if(!_.isEmpty(value.title)){
							    	index = value.title.indexOf(t.search_in_table);
								    if(index >= 0){ 
								        value.title = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.title);
								    }
							    }

							    if(!_.isEmpty(value.professional)){
								    index = value.professional.name.indexOf(t.search_in_table);
								    if(index >= 0){ 
								        value.professional.name = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.professional.name); 
								    }
							    }

							    if(!_.isEmpty(value.professional)){
								    index = value.specialty.specialty.indexOf(t.search_in_table);
								    if(index >= 0){ 
								        value.specialty.specialty = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.specialty.specialty); 
								    }
							    }

							    if(!_.isEmpty(value.professional)){
								    index = value.treatment.treatment.indexOf(t.search_in_table);
								    if(index >= 0){ 
								        value.treatment.treatment = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.treatment.treatment);
								    }
							    }

							    index = value.date.indexOf(t.search_in_table);
							    if(index >= 0){ 
							        value.date = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.date); 
							    }

							    index = value.hour.indexOf(t.search_in_table);
							    if(index >= 0){ 
							        value.hour = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.hour);
							    }

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
								t.no_data_msg 	= 'No se han encontrado registros';
							});
						}
					})
					.catch(function (error) {
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Intente nuevamente más tarde', success: false, warning: false, danger: true});
						t.no_data_msg = 'No se han encontrado registros';
					});
			},

			searchAppointment(){
				var t = this;
				var time = 400;
				
			    clearTimeout(t.delayTimer);

			    t.delayTimer = setTimeout(function() {

			    	if(t.search_in_table){
			    		t.searching_in_table = true;
			    		axios.get(t.baseUrl + '/appointments/search', {params: {'filtro_status': t.filtros.status, 'term': (t.search_in_table ? t.search_in_table : '')}})
							.then(function (response) {
								t.appointments = [];

								if(!_.isEmpty(response.data.appointments)){
									_.forEach(response.data.appointments, function(value) {

										var index = value.patient.name.indexOf(t.search_in_table);
										var text_lenght = t.search_in_table.length;
										
										let no_asigned_text = '-NO ASIGNADO-';

									    if(index >= 0){
									        value.patient.name = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.patient.name); 
									    }

									    if(!_.isEmpty(value.title)){
									    	index = value.title.indexOf(t.search_in_table);
										    if(index >= 0){ 
										        value.title = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.title);
										    }
									    }

									    if(!_.isEmpty(value.professional)){
										    index = value.professional.name.indexOf(t.search_in_table);
										    if(index >= 0){ 
										        value.professional.name = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.professional.name); 
										    }
									    }

									    if(!_.isEmpty(value.professional)){
										    index = value.specialty.specialty.indexOf(t.search_in_table);
										    if(index >= 0){ 
										        value.specialty.specialty = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.specialty.specialty); 
										    }
									    }

									    if(!_.isEmpty(value.professional)){
										    index = value.treatment.treatment.indexOf(t.search_in_table);
										    if(index >= 0){ 
										        value.treatment.treatment = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.treatment.treatment);
										    }
									    }

									    index = value.date.indexOf(t.search_in_table);
									    if(index >= 0){ 
									        value.date = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.date); 
									    }

									    index = value.hour.indexOf(t.search_in_table);
									    if(index >= 0){ 
									        value.hour = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.hour);
									    }									    

										t.appointments.push({
											'id' : value.id,
											'titulo': value.title ? value.title : '-SIN TÍTULO-',
											'paciente': value.patient.name,
											'profesional': value.professional ? value.professional.name : no_asigned_text,
											'area': value.specialty ? value.specialty.specialty : no_asigned_text,
											'tratamiento': value.treatment ? value.treatment.treatment : no_asigned_text,
											'fecha': value.date + ' ' + value.hour
										});

										t.last_page = response.data.lastPage;
										t.current_page = 1;
									});
								}
								t.searching_in_table = false;
							})
							.catch(function (error) {
								t.$emit('complete', {message:  'No se ha podido resolver su solicitud. Quizás usted esté ingresando caracteres no permitidos. Si no es asi, pruebe nuevamente más tarde o comuníquese con el administrador del sistema.', success: false, warning: false, danger: true});
								t.searching_in_table = false;
							});

			    	}else{
			    		t.paginationCallback();
			    	}

			    }, time);
			},

			filterStatusCallback(){
				this.$router.push({ path: '/appointments/1' });		
				this.paginationCallback();	
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
				this.$emit('complete', {message:  'Ha ocurrido un error inesperado. Intente nuevamente más tarde', success: false, warning: false, danger: true});	
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

        props: ['message', 'success', 'warning', 'danger'],

        mixins: [common]
	}
</script>