<template>
	<div class="row">		
		<div class="row col-md-12 body-principal">
			<h1><span class="glyphicon glyphicon-leaf"></span> Tratamientos</h1>
		</div>
		<div class="row col-md-12">
			<div class="col-md-10">
				<div class="col-md-6">
					<div class="input-group">
						<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
						<input class="form-control" placeholder="Ingrese su búsqueda" v-model="search_in_table" @keyup="searchTreatment">
						<span class="input-group-addon searching_in_table" v-show="searching_in_table">Buscando..</span>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<router-link :to="{ name: 'treatments_new'}" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Nuevo tratamiento</router-link>
			</div>
		</div>
		<div class="row col-md-12">
			<div class="table-responsive">
				<table class="table table-striped table-hover table-head-strong table-bi">
					<thead>
						<tr>
							<td><router-link :to="url + '1/treatment'">Tratamiento</router-link></td>
							<td><router-link :to="url + '1/specialty'">Especialidad</router-link></td>
							<td><router-link :to="url + '1/description'">Descripción</router-link></td>
							<td>Acciones</td>
						</tr>
					</thead>
					<transition-group name="list" tag="tbody" v-if="treatments.length > 0">
						<tr v-for="treatment in treatments" v-bind:key="treatment" class="list-item">
							<td v-html="treatment.treatment"></td>
							<td v-html="treatment.specialty"></td>
							<td v-html="treatment.description"></td>
							<td>								
								<div class="three-buttons">
									<router-link class="btn btn-success margin-list-button" :to="{ name: 'treatments_detail', params: { id: treatment.id }}" title="Ver Tratamiento"><span class="glyphicon glyphicon-eye-open"></span></router-link>							
									<router-link class="btn btn-primary margin-list-button" :to="{ name: 'treatments_edit', params: { id: treatment.id }}" title="Editar Tratamiento"><span class="glyphicon glyphicon-pencil"></span></router-link>
									<a class="btn btn-danger margin-list-button" href="#" data-toggle="modal" data-target="#confirmDelete" title="Cancelar Tratamiento" @click="confirmDelete(treatment.id)"><span class="glyphicon glyphicon-remove"></span></a>
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
		<popupdeleteconfirm v-on:success="operationSuccess" v-on:error="operationError" v-bind:element_id="treatment_id" v-bind:elements="treatments" v-bind:url="url" v-bind:delete_text_confirm="delete_text_confirm"></popupdeleteconfirm>
	</div>
</template>
<script>
	import Pagination from '../partials/Pagination.vue';
	import PopupDeleteConfirm from '../popups/PopupDeleteConfirm.vue';
	
	import common from '../../mixins.js';

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
				active_element: 'settings'
			}
		},
		
		created: function(){
			this.paginationCallback();
			this.$emit('child_created', this.active_element);
		},
		
		methods: {
			paginationCallback(){
				var t = this;

				axios.get('/treatments/list/' + (t.$route.params.id ? t.$route.params.id : 1) + '/' + (t.$route.params.field ? t.$route.params.field : ''), 
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
								t.treatments.push({
									'id' : value.id,
									'treatment': value.treatment,
									'specialty': value.specialty.specialty,
									'description': value.description
								});

								t.last_page 	= response.data.treatments.last_page;
								t.current_page  = response.data.treatments.current_page;
							});
						}
					})
					.catch(function (error) {
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Intente nuevamente más tarde', success: false, warning: false, danger: true});
					});
			},

			searchTreatment(){
				var t = this;
				var time = 400;
				
			    clearTimeout(t.delayTimer);

			    t.delayTimer = setTimeout(function() {

			    	if(t.search_in_table){
			    		t.searching_in_table = true;
			    		axios.get('/treatments/search', {params: {'filtro_status': t.filtros.status, 'term': (t.search_in_table ? t.search_in_table : '')}})
							.then(function (response) {
								t.treatments = [];

								if(!_.isEmpty(response.data.treatments)){
									_.forEach(response.data.treatments, function(value) {

										var index = '';
										var text_lenght = t.search_in_table.length;									

										index = value.treatment.indexOf(t.search_in_table);
										if(index >= 0){
									        value.treatment = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.treatment); 
									    }

									    index = value.specialty.specialty.indexOf(t.search_in_table);
										if(index >= 0){
									        value.specialty.specialty = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.specialty.specialty); 
									    }

									    index = value.description.indexOf(t.search_in_table);
										if(index >= 0){
									        value.description = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.description); 
									    }

									    t.treatments.push({
											'id' : value.id,
											'treatment': value.treatment,
											'specialty': value.specialty.specialty,
											'description': value.description
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