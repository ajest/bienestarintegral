<template>
	<div class="row">
		<div class="row col-md-12 body-principal">
			<h1><span class="glyphicon glyphicon-list-alt"></span> Directorio pacientes</h1>
		</div>
		<div class="row col-md-12">
			<div class="col-md-10">
				<div class="col-md-6">
					<div class="input-group">
						<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
						<input class="form-control" placeholder="Ingrese su búsqueda" v-model="search_in_table" @keyup="searchPatient">
						<span class="input-group-addon searching_in_table" v-show="searching_in_table">Buscando..</span>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<router-link :to="{ name: 'patients_new'}" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Nuevo paciente</router-link>
			</div>
		</div>
		<div class="row col-md-12">
			<div class="table-responsive">
				<table class="table table-striped table-hover table-head-strong table-bi">
					<thead>
						<tr>
							<td><router-link :to="url + '1/name'">Nombre</router-link></td>
							<td><router-link :to="url + '1/email'">Email</router-link></td>
							<td><router-link :to="url + '1/tel'">Teléfono</router-link></td>
							<td><router-link :to="url + '1/gender'">Sexo</router-link></td>
							<td><router-link :to="url + '1/address'">Dirección</router-link></td>
							<td><router-link :to="url + '1/date'">Fecha alta</router-link></td>
							<td>Acciones</td>
						</tr>
					</thead>
					<transition-group name="list" tag="tbody" v-if="patients.length > 0">
						<tr v-for="patient in patients" v-bind:key="patients" class="list-item">
							<td v-html="patient.nombre"></td>
							<td v-html="patient.email"></td>
							<td v-html="patient.telefono"></td>
							<td v-html="patient.sexo"></td>
							<td v-html="patient.direccion"></td>
							<td v-html="patient.fecha"></td>
							<td>
								<div class="three-buttons">								
									<router-link class="btn btn-success margin-list-button" :to="{ name: 'patients_detail', params: { id: patient.id }}" title="Ver Paciente"><span class="glyphicon glyphicon-eye-open"></span></router-link>
									<router-link class="btn btn-primary margin-list-button" :to="{ name: 'patients_edit', params: { id: patient.id }}" title="Editar Paciente"><span class="glyphicon glyphicon-pencil"></span></router-link>
									<a class="btn btn-danger margin-list-button" href="#" data-toggle="modal" data-target="#confirmDelete" title="Eliminar registro paciente" @click="confirmDelete(patient.id)"><span class="glyphicon glyphicon-remove"></span></a>
								</div>
							</td>
						</tr>
					</transition-group>
					<tbody v-else>
						<tr >
							<td colspan="7">No se han encontrado registros</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<pagination v-bind:last_page="last_page" v-bind:current_page="current_page" v-bind:url="url"></pagination>
		<popupdeleteconfirm v-on:success="operationSuccess" v-on:error="operationError" v-bind:element_id="patient_id" v-bind:elements="patients" v-bind:url="url" v-bind:delete_text_confirm="delete_text_confirm"></popupdeleteconfirm>
	</div>	
</template>
<script>
	import Pagination from '../partials/Pagination.vue';
	import PopupDeleteConfirm from '../popups/PopupDeleteConfirm.vue';

	import common from '../../mixins.js';

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
				active_element: 'patient'
			}
		},
		
		created: function(){
			this.paginationCallback();
			this.$emit('child_created', this.active_element);
		},
		
		methods: {
			paginationCallback(){
				var t = this;

				axios.get('/patients/list/' + (t.$route.params.id ? t.$route.params.id : 1) + '/' + (t.$route.params.field ? t.$route.params.field : ''))
					.then(function (response) {
						t.patients = [];

						if(!_.isEmpty(response.data.patients.data)){
							_.forEach(response.data.patients.data, function(value) {
								var index = '';
								var text_lenght = t.search_in_table.length;

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
								
								index = value.gender.indexOf(t.search_in_table);
								if(index >= 0){
							        value.gender = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.gender); 
							    }
								
								index = value.address.indexOf(t.search_in_table);
								if(index >= 0){
							        value.address = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.address); 
							    }

							    index = value.created_at.indexOf(t.search_in_table);
								if(index >= 0){
							        value.created_at = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.created_at); 
							    }

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
			},
			
			searchPatient(){
				var t = this;
				var time = 400;
				
			    clearTimeout(t.delayTimer);

			    t.delayTimer = setTimeout(function() {

			    	if(t.search_in_table){
			    		t.searching_in_table = true;
			    		axios.get('/patients/search', {params: {'term': (t.search_in_table ? t.search_in_table : '')}})
							.then(function (response) {
								t.patients = [];

								if(!_.isEmpty(response.data.patients)){
									_.forEach(response.data.patients, function(value) {
									    var index = '';
										var text_lenght = t.search_in_table.length;

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
										
										index = value.gender.indexOf(t.search_in_table);
										if(index >= 0){
									        value.gender = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.gender); 
									    }
										
										index = value.address.indexOf(t.search_in_table);
										if(index >= 0){
									        value.address = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.address); 
									    }

									    index = value.created_at.indexOf(t.search_in_table);
										if(index >= 0){
									        value.created_at = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.created_at); 
									    }

									    t.patients.push({
											'id' : value.id,
											'nombre': value.name,
											'email': value.email,
											'telefono': value.tel,
											'sexo': value.gender,
											'direccion': value.address,
											'fecha': value.created_at
										});
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
			    this.search_in_table = '';
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