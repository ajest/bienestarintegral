<template>
	<v-layout row wrap>
		<v-flex xs12 sm12 md12 lg12>
			<h1><i class="material-icons icon-h1">healing</i> Preguntas</h1>
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
					              @keyup.native="searchTreatment"
					            ></v-text-field>
							</div>
						</v-flex>
					</v-layout>
				</v-flex>
				<v-flex xs12 sm12 md2 lg2 hidden-sm-and-down>
					<v-btn  fab big dark class="pink pull-right" :to="{ name: 'treatments_new'}">
						<v-icon dark>add</v-icon>
				    </v-btn>
				</v-flex>
				<v-flex hidden-md-and-up>
					<v-btn class="pink zindex20" dark medium fixed bottom right fab :to="{ name: 'treatments_new'}">
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
									<td><router-link :to="url + '1/treatment'">Tratamiento</router-link></td>
									<td><router-link :to="url + '1/specialty'">Especialidad</router-link></td>
									<td><router-link :to="url + '1/description'">Descripción</router-link></td>
									<td>Acciones</td>
								</tr>
							</thead>
							<transition-group name="list" tag="tbody" v-if="treatments.length > 0">
								<tr v-for="treatment in treatments" v-bind:key="treatment.id" class="list-item">
									<td v-html="treatment.treatment"></td>
									<td v-html="treatment.specialty"></td>
									<td v-html="treatment.description"></td>
									<td class="no-padding">
										<div class="three-buttons">
											<v-btn class="green--text" icon :to="{ name: 'treatments_detail', params: { id: treatment.id }}" title="Ver Tratamiento">
							                	<v-icon>remove_red_eye</v-icon>
							                </v-btn>
											<v-btn class="blue--text" icon :to="{ name: 'treatments_edit', params: { id: treatment.id }}" title="Editar Tratamiento">
							                	<v-icon>edit</v-icon>
							                </v-btn>
											<v-btn class="red--text" icon data-toggle="modal" data-target="#confirmDelete" title="Cancelar Tratamiento" @click="confirmDelete(treatment.id)">
							                	<v-icon>delete</v-icon>
							                </v-btn>
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
			</v-layout>
		</v-flex>
		<v-flex xs12 sm12 md12 lg12 class="mt-2">
			<pagination v-bind:last_page="last_page" v-bind:current_page="current_page" v-bind:url="url"></pagination>
		</v-flex>
		<popupdeleteconfirm v-on:success="operationSuccess" v-on:error="operationError" v-bind:element_id="treatment_id" v-bind:elements="treatments" v-bind:url="url" v-bind:delete_text_confirm="delete_text_confirm" v-bind:baseUrl="baseUrl"></popupdeleteconfirm>
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
				treatments: [],
				last_page: 0,
				current_page: 1,
				treatment_id: 0,
				url: '/treatments/', // For the delete
				delete_text_confirm: ['El tratamiento ', null, null, null, 'va a ser eliminado. ¿Está seguro que desea eliminar el registro', ' permanentemente', '?'],
				searching_in_table: false,
				search_in_table: '',
				delayTimer: '',
				opened_highlighted_tag: '<strong>',
				closed_highlighted_tag: '</strong>',
				filtros: {
					status: 0
				},
				active_element: 'settings',
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

				axios.get(t.baseUrl + '/treatments/list/' + (t.$route.params.id ? t.$route.params.id : 1) + '/' + (t.$route.params.field ? t.$route.params.field : ''), 
					{
						params: {
							'filtro_status': t.filtros.status,
							'term': t.search_in_table
						}
					})
					.then(function (response) {
						t.treatments = [];

						if(!_.isEmpty(response.data.treatments.data)){
							_.forEach(response.data.treatments.data, function(value) {

								var index = value.treatment.indexOf(t.search_in_table);
								var text_lenght = t.search_in_table.length;

								if(index >= 0){
							        value.treatment = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.treatment); 
							    }

							    if(!_.isEmpty(value.specialty)){									    	
								    index = value.specialty.specialty.indexOf(t.search_in_table);
									if(index >= 0){
								        value.specialty.specialty = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.specialty.specialty); 
								    }
							    }

							    index = value.description.indexOf(t.search_in_table);
								if(index >= 0){
							        value.description = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.description); 
							    }

								t.treatments.push({
									'id' : value.id,
									'treatment': value.treatment,
									'specialty': value.specialty ? value.specialty.specialty : '-SIN ESPECIALIDAD-',
									'description': value.description
								});

								t.last_page 	= response.data.treatments.last_page;
								t.current_page  = response.data.treatments.current_page;
							});
						}else{
							t.no_data_msg 	= 'No hay tratamientos cargados';	
						}
					})
					.catch(function (error) {
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Intente nuevamente más tarde', success: false, warning: false, danger: true, error: error});
						t.no_data_msg 	= 'No se han encontrado registros';
					});
			},

			searchTreatment(){
				var t = this;
				var time = 400;
				
			    clearTimeout(t.delayTimer);

			    t.delayTimer = setTimeout(function() {

			    	if(t.search_in_table){
			    		t.searching_in_table = true;
			    		axios.get(t.baseUrl + '/treatments/search', {params: {'filtro_status': t.filtros.status, 'term': (t.search_in_table ? t.search_in_table : '')}})
							.then(function (response) {
								t.treatments = [];

								if(!_.isEmpty(response.data.treatments.data)){
									_.forEach(response.data.treatments.data, function(value) {

										var index = value.treatment.indexOf(t.search_in_table);
										var text_lenght = t.search_in_table.length;

										if(index >= 0){
									        value.treatment = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.treatment); 
									    }

									    if(!_.isEmpty(value.specialty)){									    	
										    index = value.specialty.specialty.indexOf(t.search_in_table);
											if(index >= 0){
										        value.specialty.specialty = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.specialty.specialty); 
										    }
									    }

									    index = value.description.indexOf(t.search_in_table);
										if(index >= 0){
									        value.description = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.description); 
									    }

									    t.treatments.push({
											'id' : value.id,
											'treatment': value.treatment,
											'specialty': value.specialty ? value.specialty.specialty : '-SIN ESPECIALIDAD-',
											'description': value.description
										});

										t.last_page = response.data.treatments.last_page;
										t.current_page = 1;
									});
								}else{
									t.no_data_msg 	= 'No se han encontrado tratamientos bajo el término: ' + t.search_in_table;	
								}
								t.searching_in_table = false;
							})
							.catch(function (error) {
								t.$emit('complete', {message:  'No se ha podido resolver su solicitud. Quizás usted esté ingresando caracteres no permitidos. Si no es asi, pruebe nuevamente más tarde o comuníquese con el administrador del sistema.', success: false, warning: false, danger: true, error: error});
								t.searching_in_table = false;
							});

			    	}else{
			    		t.paginationCallback();
			    	}

			    }, time);
			},

			filterStatusCallback(){
				this.$router.push({ path: '/treatments/1' });		
				this.paginationCallback();	
			},

			confirmDelete(id){
				this.treatment_id = id;
			},

			operationSuccess(){
				var t = this;

				_.forEach(t.treatments, function(value, i){
					if(value.id == t.treatment_id){
						t.treatments = t.treatments.filter(function (item) {
						    return t.treatment_id != item.id;
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