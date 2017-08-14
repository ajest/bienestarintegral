<template>
	<v-layout row wrap>
		<v-flex xs12 sm12 md12 lg12>
			<h1><i class="material-icons icon-h1">card_giftcard</i> Promociones</h1>
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
					              @keyup.native="searchSeries"
					            ></v-text-field>
							</div>
						</v-flex>
					</v-layout>
				</v-flex>
				<v-flex xs12 sm12 md2 lg2 hidden-sm-and-down>
					<v-btn  fab big dark class="pink pull-right" :to="{ name: 'series_new'}">
						<v-icon dark>add</v-icon>
				    </v-btn>
				</v-flex>
				<v-flex hidden-md-and-up>
					<v-btn class="pink zindex20" dark medium fixed bottom right fab :to="{ name: 'series_new'}">
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
									<td><router-link :to="url + '1/series'">Promoción</router-link></td>
									<td><router-link :to="url + '1/cant'">Cantidad</router-link></td>
									<td>Acciones</td>
								</tr>
							</thead>
							<transition-group name="list" tag="tbody" v-if="series.length > 0">
								<tr v-for="serie in series" v-bind:key="serie.id" class="list-item">
									<td v-html="serie.series"></td>
									<td v-html="serie.cant"></td>
									<td class="no-padding">
										<div class="three-buttons">
											<v-btn class="green--text" icon :to="{ name: 'series_detail', params: { id: serie.id }}" title="Ver Promoción">
							                	<v-icon>remove_red_eye</v-icon>
							                </v-btn>
											<v-btn class="blue--text" icon :to="{ name: 'series_edit', params: { id: serie.id }}" title="Editar Promoción">
							                	<v-icon>edit</v-icon>
							                </v-btn>
											<v-btn class="red--text" icon data-toggle="modal" data-target="#confirmDelete" title="Cancelar Promoción" @click="confirmDelete(serie.id)">
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
		<popupdeleteconfirm v-on:success="operationSuccess" v-on:error="operationError" v-bind:element_id="series_id" v-bind:elements="series" v-bind:url="url" v-bind:delete_text_confirm="delete_text_confirm" v-bind:baseUrl="baseUrl"></popupdeleteconfirm>
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
				series: [],
				last_page: 0,
				current_page: 1,
				series_id: 0,
				url: '/series/', // For the delete
				delete_text_confirm: ['La promoción seleccionada ', null, null, null, 'va a ser eliminada.', ' Esta operación no tiene retorno.', ' ¿Está seguro que desea realizar esta operación?'],
				searching_in_table: false,
				search_in_table: '',
				delayTimer: '',
				opened_highlighted_tag: '<strong>',
				closed_highlighted_tag: '</strong>',
				filtros: {
					status: 0
				},
				active_element: 'series',
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

				axios.get(t.baseUrl + '/series/list/' + (t.$route.params.id ? t.$route.params.id : 1) + '/' + (t.$route.params.field ? t.$route.params.field : ''), 
					{
						params: {
							'filtro_status': t.filtros.status,
							'term': t.search_in_table
						}
					})
					.then(function (response) {
						t.series = [];

						if(!_.isEmpty(response.data.series.data)){
							_.forEach(response.data.series.data, function(value) {
								t.series.push({
									'id' : value.id,
									'series': value.series,
									'cant': value.cant
								});

								t.last_page 	= response.data.series.last_page;
								t.current_page  = response.data.series.current_page;
								t.no_data_msg 	= 'No se han encontrado registros';
							});
						}else{
							t.no_data_msg 	= 'No hay promociones cargadas';	
						}
					})
					.catch(function (error) {
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Intente nuevamente más tarde', success: false, warning: false, danger: true, error: error});
						t.no_data_msg 	= 'No se han encontrado registros';
					});
			},

			searchSeries(){
				var t = this;
				var time = 400;
				
			    clearTimeout(t.delayTimer);

			    t.delayTimer = setTimeout(function() {

			    	if(t.search_in_table){
			    		t.searching_in_table = true;
			    		axios.get(t.baseUrl + '/series/search', {params: {'filtro_status': t.filtros.status, 'term': (t.search_in_table ? t.search_in_table : '')}})
							.then(function (response) {
								t.series = [];

								if(!_.isEmpty(response.data.series.data)){
									_.forEach(response.data.series.data, function(value) {
										
										var index = '';
										var text_lenght = t.search_in_table.length;									

										index = value.series.indexOf(t.search_in_table);
										if(index >= 0){
									        value.series = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.series); 
									    }

									    var value_cant = value.cant;
									    value.cant = value.cant.toString();
									    index = value.cant.indexOf(t.search_in_table);
										if(index >= 0){
									        value_cant = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.cant); 
									    }

									    t.series.push({
											'id' : value.id,
											'series': value.series,
											'cant': value_cant
										});

										t.last_page = response.data.series.last_page;
										t.current_page = 1;
									});
								}else{
									t.no_data_msg 	= 'No se han encontrado promociones bajo el término: ' + t.search_in_table;	
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
				this.$router.push({ path: '/series/1' });		
				this.paginationCallback();	
			},

			confirmDelete(id){
				this.series_id = id;
			},

			operationSuccess(){
				var t = this;

				_.forEach(t.series, function(value, i){
					if(value.id == t.series_id){
						t.series = t.series.filter(function (item) {
						    return t.series_id != item.id;
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