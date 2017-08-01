<template>
	<div class="row">		
		<div class="row col-md-12 body-principal">
			<h1><span class="glyphicon glyphicon-road"></span> Especialidades</h1>
		</div>
		<div class="row col-md-12">
			<div class="col-md-10">
				<div class="col-md-6">
					<div class="input-group">
						<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
						<input class="form-control" placeholder="Ingrese su búsqueda" v-model="search_in_table" @keyup="searchSpecialties">
						<span class="input-group-addon searching_in_table" v-show="searching_in_table">Buscando..</span>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<router-link :to="{ name: 'specialties_new'}" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Nueva especialidad</router-link>
			</div>
		</div>
		<div class="row col-md-12">
			<div class="table-responsive">
				<table class="table table-striped table-hover table-head-strong table-bi">
					<thead>
						<tr>
							<td><router-link :to="url + '1/specialty'">Especialidad</router-link></td>
							<td><router-link :to="url + '1/description'">Descripción</router-link></td>
							<td>Acciones</td>
						</tr>
					</thead>
					<transition-group name="list" tag="tbody" v-if="specialties.length > 0">
						<tr v-for="specialty in specialties" v-bind:key="specialty" class="list-item">
							<td v-html="specialty.specialty"></td>
							<td v-html="specialty.description"></td>
							<td>								
								<div class="three-buttons">
									<router-link class="btn btn-success margin-list-button" :to="{ name: 'specialties_detail', params: { id: specialty.id }}" title="Ver Especialidad"><span class="glyphicon glyphicon-eye-open"></span></router-link>							
									<router-link class="btn btn-primary margin-list-button" :to="{ name: 'specialties_edit', params: { id: specialty.id }}" title="Editar Especialidad"><span class="glyphicon glyphicon-pencil"></span></router-link>
									<a class="btn btn-danger margin-list-button" href="#" data-toggle="modal" data-target="#confirmDelete" title="Cancelar Especialidad" @click="confirmDelete(specialty.id)"><span class="glyphicon glyphicon-remove"></span></a>
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
		<pagination v-bind:last_page="last_page" v-bind:current_page="current_page" v-bind:url="url"></pagination>
		<popupdeleteconfirm v-on:success="operationSuccess" v-on:error="operationError" v-bind:element_id="specialty_id" v-bind:elements="specialties" v-bind:url="url" v-bind:delete_text_confirm="delete_text_confirm" v-bind:baseUrl="baseUrl"></popupdeleteconfirm>
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
			this.url = this.baseUrl + this.url;
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
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Intente nuevamente más tarde', success: false, warning: false, danger: true});
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
								t.$emit('complete', {message:  'No se ha podido resolver su solicitud. Quizás usted esté ingresando caracteres no permitidos. Si no es asi, pruebe nuevamente más tarde o comuníquese con el administrador del sistema.', success: false, warning: false, danger: true});
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

        props: ['message', 'success', 'warning', 'danger'],

        mixins: [common]
	}
</script>