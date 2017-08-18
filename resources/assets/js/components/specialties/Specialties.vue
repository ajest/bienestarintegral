<template>
	<v-layout row wrap>
		<v-flex xs12 sm12 md12 lg12>
			<h2><i class="material-icons icon-h2">group_work</i> Especialidades</h2>
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
					              @keyup.native="searchSpecialties"
					            ></v-text-field>
							</div>
						</v-flex>
					</v-layout>
				</v-flex>
				<v-flex xs12 sm12 md2 lg2 hidden-sm-and-down>
					<v-btn  fab big dark class="new-specialty pink pull-right" :to="{ name: 'specialties_new'}">
						<v-icon dark>add</v-icon>
				    </v-btn>
				</v-flex>
				<v-flex hidden-md-and-up>
					<v-btn class="new-specialty pink zindex20" dark medium fixed bottom right fab :to="{ name: 'specialties_new'}">
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
									<td><router-link :to="url + '1/specialty'">Especialidad</router-link></td>
									<td><router-link :to="url + '1/description'">Descripción</router-link></td>
									<td>Acciones</td>
								</tr>
							</thead>
							<transition-group name="list" tag="tbody" v-if="specialties.length > 0">
								<tr v-for="specialty in specialties" v-bind:key="specialty.id" class="list-item">
									<td v-html="specialty.specialty"></td>
									<td v-html="specialty.description"></td>
									<td class="no-padding">
										<div class="three-buttons">
											<v-btn class="green--text" icon :to="{ name: 'specialties_detail', params: { id: specialty.id }}" title="Ver Especialidad">
							                	<v-icon>remove_red_eye</v-icon>
							                </v-btn>
											<v-btn class="blue--text" icon :to="{ name: 'specialties_edit', params: { id: specialty.id }}" title="Editar Especialidad">
							                	<v-icon>edit</v-icon>
							                </v-btn>
											<v-btn class="red--text" icon data-toggle="modal" data-target="#confirmDelete" title="Cancelar Especialidad" @click="confirmDelete(specialty.id)">
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
		<popupdeleteconfirm v-on:success="operationSuccess" v-on:error="operationError" v-bind:element_id="specialty_id" v-bind:elements="specialties" v-bind:url="url" v-bind:delete_text_confirm="delete_text_confirm" v-bind:baseUrl="baseUrl"></popupdeleteconfirm>		
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
				specialties: [],
				last_page: 0,
				current_page: 1,
				specialty_id: 0,
				url: '/specialties/', // For the delete
				delete_text_confirm: ['La especialidad seleccionada ', null, null, null, 'va a ser eliminada.', ' Esta operación no tiene retorno.', ' ¿Está seguro que desea realizar esta operación?'],
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

				axios.get(t.baseUrl + '/specialties/list/' + (t.$route.params.id ? t.$route.params.id : 1) + '/' + (t.$route.params.field ? t.$route.params.field : ''), 
					{
						params: {
							'filtro_status': t.filtros.status,
							'term': t.search_in_table
						}
					})
					.then(function (response) {
						t.specialties = [];

						if(!_.isEmpty(response.data.specialties.data)){
							_.forEach(response.data.specialties.data, function(value) {
								t.specialties.push({
									'id' : value.id,
									'specialty': value.specialty,
									'description': value.description
								});

								t.last_page 	= response.data.specialties.last_page;
								t.current_page  = response.data.specialties.current_page;
							});
						}else{
							t.no_data_msg 	= 'No hay especialidades cargadas';	
						}
					})
					.catch(function (error) {
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Intente nuevamente más tarde', success: false, warning: false, danger: true, error: error});
						t.no_data_msg 	= 'No se han encontrado registros';
					});
			},

			searchSpecialties(){
				var t = this;
				var time = 400;
				
			    clearTimeout(t.delayTimer);

			    t.delayTimer = setTimeout(function() {

			    	if(t.search_in_table){
			    		t.searching_in_table = true;
			    		axios.get(t.baseUrl + '/specialties/search', {params: {'filtro_status': t.filtros.status, 'term': (t.search_in_table ? t.search_in_table : '')}})
							.then(function (response) {
								t.specialties = [];

								if(!_.isEmpty(response.data.specialties.data)){
									_.forEach(response.data.specialties.data, function(value) {
										
										var index = '';
										var text_lenght = t.search_in_table.length;									

										index = value.specialty.indexOf(t.search_in_table);
										if(index >= 0){
									        value.specialty = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.specialty); 
									    }

									    index = value.description.indexOf(t.search_in_table);
										if(index >= 0){
									        value.description = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.description); 
									    }

									    t.specialties.push({
											'id' : value.id,
											'specialty': value.specialty,
											'description': value.description
										});

										t.last_page = response.data.specialties.last_page;
										t.current_page = 1;
									});
								}else{
									t.no_data_msg 	= 'No se han encontrado especialidades bajo el término: ' + t.search_in_table;	
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
				this.$router.push({ path: '/specialties/1' });
				this.paginationCallback();	
			},

			confirmDelete(id){
				this.specialty_id = id;
			},

			operationSuccess(){
				var t = this;

				_.forEach(t.specialties, function(value, i){
					if(value.id == t.specialty_id){
						t.specialties = t.specialties.filter(function (item) {
						    return t.specialty_id != item.id;
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