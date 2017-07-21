<template>
	<div class="row">		
		<div class="row col-md-12 body-principal">
			<h1><span class="glyphicon glyphicon-list"></span> Preguntas</h1>
		</div>
		<div class="row col-md-12">
			<div class="col-md-10">
				<div class="col-md-6">
					<div class="input-group">
						<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
						<input class="form-control" placeholder="Ingrese su búsqueda" v-model="search_in_table" @keyup="searchQuestion">
						<span class="input-group-addon searching_in_table" v-show="searching_in_table">Buscando..</span>
					</div>
				</div>
			</div>
			<div class="col-md-2">
				<router-link :to="{ name: 'questions_new'}" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Nueva Pregunta</router-link>
			</div>
		</div>
		<div class="row col-md-12">
			<div class="table-responsive">
				<table class="table table-striped table-hover table-head-strong table-bi">
					<thead>
						<tr>
							<td><router-link :to="url + '1/question'">Pregunta</router-link></td>
							<td><router-link :to="url + '1/specialty'">Especialidad</router-link></td>
							<td>Acciones</td>
						</tr>
					</thead>
					<transition-group name="list" tag="tbody" v-if="questions.length > 0">
						<tr v-for="question in questions" v-bind:key="question" class="list-item">
							<td v-html="question.question"></td>
							<td v-html="question.specialty"></td>
							<td>								
								<div class="three-buttons">
									<router-link class="btn btn-primary margin-list-button" :to="{ name: 'questions_edit', params: { id: question.id }}" title="Editar Tratamiento"><span class="glyphicon glyphicon-pencil"></span></router-link>
									<a class="btn btn-danger margin-list-button" href="#" data-toggle="modal" data-target="#confirmDelete" title="Cancelar Tratamiento" @click="confirmDelete(question.id)"><span class="glyphicon glyphicon-remove"></span></a>
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
		<popupdeleteconfirm v-on:success="operationSuccess" v-on:error="operationError" v-bind:element_id="question_id" v-bind:elements="questions" v-bind:url="url" v-bind:delete_text_confirm="delete_text_confirm"></popupdeleteconfirm>
	</div>
</template>
<script>
	import Pagination from '../partials/Pagination.vue';
	import PopupDeleteConfirm from '../popups/PopupDeleteConfirm.vue';
	
	import common from '../../mixins.js';

	export default {
		data () {
			return {
				questions: [],
				last_page: 0,
				current_page: 1,
				question_id: 0,
				url: '/questions/', // For the delete
				delete_text_confirm: ['La pregunta ', null, null, null, 'va a ser eliminada. ¿Está seguro que desea eliminar el registro', ' permanentemente', '?'],
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

				axios.get('/questions/list/' + (t.$route.params.id ? t.$route.params.id : 1) + '/' + (t.$route.params.field ? t.$route.params.field : ''), 
					{
						params: {
							'filtro_status': t.filtros.status,
							'term': t.search_in_table
						}
					})
					.then(function (response) {
						t.questions = [];

						if(!_.isEmpty(response.data.questions.data)){
							_.forEach(response.data.questions.data, function(value) {
								t.questions.push({
									'id' : value.id,
									'question': value.question,
									'specialty': value.specialty.specialty
								});

								t.last_page 	= response.data.questions.last_page;
								t.current_page  = response.data.questions.current_page;
							});
						}
					})
					.catch(function (error) {
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Intente nuevamente más tarde', success: false, warning: false, danger: true});
					});
			},

			searchQuestion(){
				var t = this;
				var time = 400;
				
			    clearTimeout(t.delayTimer);

			    t.delayTimer = setTimeout(function() {

			    	if(t.search_in_table){
			    		t.searching_in_table = true;
			    		axios.get('/questions/search', {params: {'filtro_status': t.filtros.status, 'term': (t.search_in_table ? t.search_in_table : '')}})
							.then(function (response) {
								t.questions = [];

								if(!_.isEmpty(response.data.questions.data)){
									_.forEach(response.data.questions.data, function(value) {

										var index = '';
										var text_lenght = t.search_in_table.length;									

										index = value.question.indexOf(t.search_in_table);
										if(index >= 0){
									        value.question = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.question); 
									    }

									    index = value.specialty.specialty.indexOf(t.search_in_table);
										if(index >= 0){
									        value.specialty.specialty = t.highlightText(index, text_lenght, t.opened_highlighted_tag, t.closed_highlighted_tag, value.specialty.specialty); 
									    }

									    t.questions.push({
											'id' : value.id,
											'question': value.question,
											'specialty': value.specialty.specialty
										});

										t.last_page = response.data.questions.last_page;
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
				this.$router.push({ path: '/questions/1' });		
				this.paginationCallback();	
			},

			confirmDelete(id){
				this.question_id = id;
			},

			operationSuccess(){
				var t = this;

				_.forEach(t.questions, function(value, i){
					if(value.id == t.question_id){
						t.questions = t.questions.filter(function (item) {
						    return t.question_id != item.id;
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