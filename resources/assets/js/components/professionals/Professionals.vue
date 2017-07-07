<template>
	<div class="row">		
		<div class="row col-md-12 body-principal">
			<h1><span class="glyphicon glyphicon-education"></span> Profesionales</h1>
		</div>
		<div class="row col-md-12">
			<div class="col-md-10">
				<div class="col-md-6">
					<div class="input-group">
						<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
						<input class="form-control" placeholder="Ingrese su búsqueda" v-model="search_in_table" @keyup="searchProfessional">
						<span class="input-group-addon searching_in_table" v-show="searching_in_table">Buscando..</span>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<router-link :to="{ name: 'professionals_new'}" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Nuevo profesional</router-link>
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
							<td>Acciones</td>
						</tr>
					</thead>
					<transition-group name="list" tag="tbody" v-if="professionals.length > 0">
						<tr v-for="professional in professionals" v-bind:key="professional" class="list-item">
							<td v-html="professional.nombre"></td>
							<td v-html="professional.email"></td>
							<td v-html="professional.tel"></td>
							<td v-html="professional.gender"></td>
							<td v-html="professional.address"></td>
							<td>								
								<div class="three-buttons">
									<router-link class="btn btn-success margin-list-button" :to="{ name: 'professionals_detail', params: { id: professional.id }}" title="Ver Profesional"><span class="glyphicon glyphicon-eye-open"></span></router-link>							
									<router-link class="btn btn-primary margin-list-button" :to="{ name: 'professionals_edit', params: { id: professional.id }}" title="Editar Profesional"><span class="glyphicon glyphicon-pencil"></span></router-link>
									<a class="btn btn-danger margin-list-button" href="#" data-toggle="modal" data-target="#confirmDelete" title="Cancelar Profesional" @click="confirmDelete(professional.id)"><span class="glyphicon glyphicon-remove"></span></a>
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
		<popupdeleteconfirm v-on:success="operationSuccess" v-on:error="operationError" v-bind:element_id="professional_id" v-bind:elements="professionals" v-bind:url="url" v-bind:delete_text_confirm="delete_text_confirm"></popupdeleteconfirm>
	</div>
</template>
<script>
	import Pagination from '../partials/Pagination.vue';
	import PopupDeleteConfirm from '../popups/PopupDeleteConfirm.vue';
	
	import common from '../../mixins.js';

	export default {
		data () {
			return {
				professionals: [],
				last_page: 0,
				current_page: 1,
				professional_id: 0,
				url: '/professionals/', // For the delete
				delete_text_confirm: ['El registro del profesional y su historial de servicios ', null, null, null, 'van a ser eliminados. ¿Está seguro que desea eliminar el registro del profesional ', ' permanentemente', '?'],
				searching_in_table: false,
				search_in_table: '',
				delayTimer: '',
				opened_highlighted_tag: '<strong>',
				closed_highlighted_tag: '</strong>',
				filtros: {
					status: 0
				},
				active_element: 'professional'
			}
		},
		
		created: function(){
			this.paginationCallback();
			this.$emit('child_created', this.active_element);
		},
		
		methods: {
			paginationCallback(){
				var t = this;

				axios.get('/professionals/list/' + (t.$route.params.id ? t.$route.params.id : 1) + '/' + (t.$route.params.field ? t.$route.params.field : ''), 
					{
						params: {
							'filtro_status': t.filtros.status,
							'term': t.search_in_table
						}
					})
					.then(function (response) {
						t.professionals = [];

						if(!_.isEmpty(response.data.professionals.data)){
							_.forEach(response.data.professionals.data, function(value) {
								t.professionals.push({
									'id' : value.id,
									'nombre': value.name,
									'email': value.email,
									'tel': value.tel,
									'gender': value.gender,
									'address': value.address
								});

								t.last_page 	= response.data.professionals.last_page;
								t.current_page  = response.data.professionals.current_page;
							});
						}
					})
					.catch(function (error) {
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Intente nuevamente más tarde', success: false, warning: false, danger: true});
					});
			},

			searchProfessional(){
				var t = this;
				var time = 400;
				
			    clearTimeout(t.delayTimer);

			    t.delayTimer = setTimeout(function() {

			    	if(t.search_in_table){
			    		t.searching_in_table = true;
			    		axios.get('/professionals/search', {params: {'filtro_status': t.filtros.status, 'term': (t.search_in_table ? t.search_in_table : '')}})
							.then(function (response) {
								t.professionals = [];

								if(!_.isEmpty(response.data.professionals)){
									_.forEach(response.data.professionals, function(value) {

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

									    t.professionals.push({
											'id' : value.id,
											'nombre': value.name,
											'email': value.email,
											'tel': value.tel,
											'gender': value.gender,
											'address': value.address
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
				this.$router.push({ path: '/professionals/1' });		
				this.paginationCallback();	
			},

			confirmDelete(id){
				this.professional_id = id;
			},

			operationSuccess(){
				var t = this;

				_.forEach(t.professionals, function(value, i){
					if(value.id == t.professional_id){
						t.professionals = t.professionals.filter(function (item) {
						    return t.professional_id != item.id;
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