<template>
	<v-layout row wrap>
		<v-flex xs12 sm12 md12 lg12>
			<h1><i class="material-icons icon-h1">face</i> Pacientes</h1>
		</v-flex>
		<v-flex xs12 sm12 md12 lg12>
			<v-layout row wrap>
				<v-flex xs12 sm12 md10 lg10>
					<v-layout row wrap>
						<v-flex md6 lg6>
							<div>
								<v-text-field
					              name="input-1-3"
					              placeholder="Ingrese su búsqueda"
					              single-line
					              append-icon="search"
					              v-model="search_in_table"
					              @keyup.native="searchPatient"
					            ></v-text-field>
							</div>
						</v-flex>
					</v-layout>
				</v-flex>
				<v-flex xs12 sm12 md2 lg2 hidden-sm-and-down>
					<v-btn  fab big dark class="pink pull-right" :to="{ name: 'patients_new'}">
						<v-icon dark>add</v-icon>
				    </v-btn>
				</v-flex>
				<v-flex hidden-md-and-up>
					<v-btn class="pink zindex20" dark medium fixed bottom right fab :to="{ name: 'patients_new'}">
			          	<v-icon dark>add</v-icon>
			        </v-btn>
				</v-flex>
				<v-progress-linear v-bind:indeterminate="true" v-show="searching_in_table"></v-progress-linear>
			</v-layout>
		</v-flex>
		<v-flex xs12 sm12 md12 lg12>
			<v-layout row wrap>
				<div class="card__text">
					<div class="table__overflow elevation-2">
						<table class="datatable table custom-table">
							<thead>
								<tr>
									<td><router-link :to="url + '1/name'">Nombre</router-link></td>
									<td><router-link :to="url + '1/email'">Email</router-link></td>
									<td><router-link :to="url + '1/cellphone'">Celular</router-link></td>
									<td><router-link :to="url + '1/tel'">Teléfono</router-link></td>
									<td><router-link :to="url + '1/dni'">DNI</router-link></td>
									<td><router-link :to="url + '1/civil_status_id'">Estado Civil</router-link></td>
									<td><router-link :to="url + '1/gender_id'">Género</router-link></td>
									<td><router-link :to="url + '1/address'">Dirección</router-link></td>
									<td><router-link :to="url + '1/birthdate'">Nacimiento</router-link></td>
									<td><router-link :to="url + '1/area'">Localidad</router-link></td>
									<td><router-link :to="url + '1/date'">Fecha alta</router-link></td>
									<td>Acciones</td>
								</tr>
							</thead>
							<transition-group name="list" tag="tbody" v-if="patients.length > 0">
								<tr v-for="patient in patients" v-bind:key="patient.id" class="list-item">
									<td v-html="patient.nombre"></td>
									<td v-html="patient.email"></td>
									<td v-html="patient.celular"></td>
									<td v-html="patient.telefono"></td>
									<td v-html="patient.dni"></td>
									<td v-html="patient.estado_civil"></td>
									<td v-html="patient.sexo"></td>
									<td v-html="patient.direccion"></td>
									<td v-html="patient.nacimiento"></td>
									<td v-html="patient.localidad"></td>
									<td v-html="patient.fecha"></td>
									<td class="no-padding">
										<div class="three-buttons">
											<v-btn class="green--text" icon :to="{ name: 'patients_detail', params: { id: patient.id }}" title="Ver Paciente">
							                	<v-icon>remove_red_eye</v-icon>
							                </v-btn>
											<v-btn class="blue--text" icon :to="{ name: 'patients_edit', params: { id: patient.id }}" title="Editar Paciente">
							                	<v-icon>edit</v-icon>
							                </v-btn>
											<v-btn class="red--text" icon data-toggle="modal" data-target="#confirmDelete" title="Cancelar Paciente" @click="confirmDelete(patient.id)">
							                	<v-icon>delete</v-icon>
							                </v-btn>
										</div>
									</td>
								</tr>
							</transition-group>
							<tbody v-else>
								<tr>
									<td colspan="12">{{ no_data_msg }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</v-layout>
		</v-flex>
		<v-flex xs12 sm12 md12 lg12 class="mt-2">
			<pagination v-bind:last_page="last_page" v-bind:current_page="current_page" v-bind:url="url" v-bind:search_in_table="search_in_table"></pagination>
		</v-flex>
		<popupdeleteconfirm v-on:success="operationSuccess" v-on:error="operationError" v-bind:element_id="patient_id" v-bind:elements="patients" v-bind:url="url" v-bind:delete_text_confirm="delete_text_confirm" v-bind:baseUrl="baseUrl"></popupdeleteconfirm>
	</v-layout>	
</template>
<script>
	import Pagination from '../partials/Pagination.vue';
	import PopupDeleteConfirm from '../popups/PopupDeleteConfirm.vue';

	import common from '../../mixins.js';

	import { mapState } from 'vuex';

	export default {
		data () {
			return {
				patients: [],
				last_page: 0,
				current_page: 1,
				patient_id: 0,
				url: '/patients/',
				delete_text_confirm: ['El registro del paciente y su historial de tratamientos ', null, null, null, 'van a ser eliminados. ¿Está seguro que desea eliminar el registro del paciente ', ' permanentemente', '?'],
				searching_in_table: false,
				search_in_table: '',
				delayTimer: '',
				opened_highlighted_tag: '<strong>',
				closed_highlighted_tag: '</strong>',
				active_element: 'patient',
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

				axios.get(t.baseUrl + '/patients/list/' + (t.$route.params.id ? t.$route.params.id : 1) + '/' + (t.$route.params.field ? t.$route.params.field : ''),
					{
						params: {
							'term': t.search_in_table
						}
					})
					.then(function (response) {
						t.patients = [];

						if(!_.isEmpty(response.data.patients.data)){
							_.forEach(response.data.patients.data, function(value) {
								var index = '';
								var text_lenght = t.search_in_table.length;
								var value_dni = value.dni;
								var value_civil_status = !_.isNull(value.civil_status) ? value.civil_status.civil_status : '';

								if(t.search_in_table){
									index = value.name.indexOf(t.search_in_table);
									if(index >= 0){
								        value.name = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.name); 
								    }
								    
								    index = value.email.indexOf(t.search_in_table);
									if(index >= 0){
								        value.email = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.email); 
								    }

								    index = value.tel.indexOf(t.search_in_table);
									if(index >= 0){
								        value.tel = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.tel); 
								    }
									
									index = value.cellphone.indexOf(t.search_in_table);
									if(index >= 0){
								        value.cellphone = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.cellphone); 
								    }

								    index = value.gender.gender.indexOf(t.search_in_table);
									if(index >= 0){
								        value.gender.gender = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.gender.gender); 
								    }
									
									index = value.address.indexOf(t.search_in_table);
									if(index >= 0){
								        value.address = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.address); 
								    }								
									
									value.dni = value.dni.toString();
									index = value.dni.indexOf(t.search_in_table);
									if(index >= 0){
								        value_dni = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.dni); 
								    }

								    value.civil_status = !_.isNull(value.civil_status) ? value.civil_status.toString() : '';
								    index = value.civil_status.indexOf(t.search_in_table);
									if(index >= 0){
								        value_civil_status = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.civil_status); 
								    }
									
									index = value.birthdate.indexOf(t.search_in_table);
									if(index >= 0){
								        value.birthdate = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.birthdate); 
								    }
									
									index = value.area.indexOf(t.search_in_table);
									if(index >= 0){
								        value.area = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.area); 
								    }

								    index = value.created_at.indexOf(t.search_in_table);
									if(index >= 0){
								        value.created_at = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.created_at); 
								    }
								}

								t.patients.push({
									'id' : value.id,
									'nombre': value.name,
									'email': value.email,
									'celular': value.cellphone,
									'telefono': value.tel,
									'sexo': value.gender.gender,
									'direccion': value.address,
									'dni': value_dni,
									'estado_civil': value_civil_status ? value_civil_status : '---',
									'nacimiento': value.birthdate,
									'localidad': value.area,
									'fecha': value.created_at
								});

								t.last_page 	= response.data.patients.last_page;
								t.current_page  = response.data.patients.current_page;
							});
						}else{
							t.no_data_msg 	= 'No hay pacientes cargados';	
						}
					})
					.catch(function (error) {
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Intente nuevamente más tarde', success: false, warning: false, danger: true, error: error});
					});
			},
			
			searchPatient(){
				var t = this;
				var time = 400;
				
			    clearTimeout(t.delayTimer);

			    t.delayTimer = setTimeout(function() {

			    	if(t.search_in_table){
			    		t.searching_in_table = true;
			    		axios.get(t.baseUrl + '/patients/search', {params: {'term': (t.search_in_table ? t.search_in_table : '')}})
							.then(function (response) {
								t.patients = [];

								if(!_.isEmpty(response.data.patients.data)){
									_.forEach(response.data.patients.data, function(value) {
									    var index = '';
										var text_lenght = t.search_in_table.length;
										var value_dni = value.dni;
										var value_civil_status = !_.isNull(value.civil_status) ? value.civil_status.civil_status : '';

										index = value.name.indexOf(t.search_in_table);
										if(index >= 0){
									        value.name = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.name); 
									    }
									    
									    index = value.email.indexOf(t.search_in_table);
										if(index >= 0){
									        value.email = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.email); 
									    }

									    index = value.tel.indexOf(t.search_in_table);
										if(index >= 0){
									        value.tel = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.tel); 
									    }
										
										index = value.cellphone.indexOf(t.search_in_table);
										if(index >= 0){
									        value.cellphone = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.cellphone); 
									    }

									    index = value.gender.gender.indexOf(t.search_in_table);
										if(index >= 0){
									        value.gender.gender = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.gender.gender); 
									    }
										
										index = value.address.indexOf(t.search_in_table);
										if(index >= 0){
									        value.address = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.address); 
									    }								
										
										value.dni = value.dni.toString();
										index = value.dni.indexOf(t.search_in_table);
										if(index >= 0){
									        value_dni = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.dni); 
									    }

									    value.civil_status = !_.isNull(value.civil_status) ? value.civil_status.toString() : '';
									    index = value.civil_status.indexOf(t.search_in_table);
										if(index >= 0){
									        value_civil_status = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.civil_status); 
									    }
										
										index = value.birthdate.indexOf(t.search_in_table);
										if(index >= 0){
									        value.birthdate = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.birthdate); 
									    }
										
										index = value.area.indexOf(t.search_in_table);
										if(index >= 0){
									        value.area = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.area); 
									    }

									    index = value.created_at.indexOf(t.search_in_table);
										if(index >= 0){
									        value.created_at = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.created_at); 
									    }

									    t.patients.push({
											'id' : value.id,
											'nombre': value.name,
											'email': value.email,
											'celular': value.cellphone,
											'telefono': value.tel,
											'sexo': value.gender.gender,
											'direccion': value.address,
											'dni': value_dni,
											'estado_civil': value_civil_status ? value_civil_status : '---',
											'nacimiento': value.birthdate,
											'localidad': value.area,
											'fecha': value.created_at
										});
	
										t.last_page = response.data.patients.last_page;
										t.current_page = 1;
									});
								}else{
									t.no_data_msg 	= 'No se han encontrado pacientes bajo el término: ' + t.search_in_table;	
								}
								t.searching_in_table = false;
							})
							.catch(function (error) {
								t.$emit('complete', {message:  'No se ha podido resolver su solicitud. Quizás usted esté ingresando caracteres no permitidos. Si no es asi, pruebe nuevamente más tarde o comuníquese con el administrador del sistema.', success: false, warning: false, danger: true, error: error});
								t.searching_in_table = false;
								t.no_data_msg = 'No se han encontrado registros';
							});

			    	}else{
			    		t.paginationCallback();
			    	}

			    }, time);
			},

			confirmDelete(id){
				this.patient_id = id;
			},

			operationSuccess(){
				var t = this;

				_.forEach(t.patients, function(value, i){
					if(value.id == t.patient_id){
						t.patients = t.patients.filter(function (item) {
						    return t.patient_id != item.id;
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

        props: ['message', 'success', 'warning', 'danger', 'error'],

        mixins: [common]
	}
</script>