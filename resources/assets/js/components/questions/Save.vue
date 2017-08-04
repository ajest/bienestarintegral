<template>
	<form class="row col-md-12" v-on:submit.prevent="saveQuestion" v-if="checkExistence">
		<div class="row col-md-12">
			<h1>{{ question.question.question ? question.question.question : 'Nueva pregunta' }} 
				<button class="pull-right btn btn-primary margin-left-small" :disabled="button_disabled"><span class="glyphicon " :class="save_icon"></span> {{ saveButtonName }}</button>
				<router-link to="/questions" class="btn btn-success pull-right margin-left-small"><span class="glyphicon glyphicon-arrow-left"></span> Volver</router-link>
			</h1>
		</div>
		<hr />
		<div class="col-md-12">
			<h3>Información de la pregunta</h3>
			<div class="panel panel-danger" v-if="errors.length > 0">
				<div class="panel-body">
					Su formulario contiene los siguientes errores:
					<ul>
						<li v-for="error in errors"><strong>{{ error.question }}</strong>: {{ error.message }}</li>
					</ul>
				</div>
			</div>
			<div class="form-group col-md-6">
				<label>Pregunta *</label>
				<input type="text" v-model="question.question.question" placeholder="Ej. Reflexología" class="form-control" required>
			</div>
			<div class="form-group col-md-6">
				<label>Especialidad</label>
				<select v-model="question.question.specialty_id" class="form-control">
					<option value="">-- Seleccione Especialidad --</option>
					<option v-for="specialty in question.all.specialties" v-bind:value="specialty.id">
						{{ specialty.specialty }}
					</option>
				</select>
			</div>
		</div>
	</form>
</template>
<script>
	import { mapState } from 'vuex';

	export default {
		data(){
			return {
				question: '',				
				save_button: 'Guardar',
				save_icon: 'glyphicon-floppy-disk',
				button_disabled: false,
				errors: [],
				active_element: 'settings'
			}
		},
		
		computed: {
			checkExistence: function () {
				if(!this.$route.params.id){
					this.question = {
						question: {
							question: '',
							description: ''
						},
						all: {
							specialties: ''
						}
					};
					
					return true;	
				}else{
					return this.question;
				}
			},
			saveButtonName: function () {
				let message = this.save_button;
				if(this.$route.params.id){
					message = 'Actualizar';
				}
				return message;
			},
			...mapState({
		    	baseUrl: state => state.common.baseUrl
		    })
		},
		created: function(){
			this.getQuestion();			
			this.$emit('child_created', this.active_element);
		},
		methods: {
			getQuestion(){
				var t = this;
				axios.get(t.baseUrl + '/questions/detail/' + (t.$route.params.id ? t.$route.params.id : ''))
					.then(function (response) {
						if(!_.isEmpty(response.data)){
							if(_.isEmpty(response.data.question)){
								t.question.all = response.data.all;
							}else{
								t.question = response.data;
							}
						}
					})
					.catch(function (error) {
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde', success: false, warning: false, danger: true, error:error});
					});
				
			},
			saveQuestion(){
				var t = this;
				let message = 'La pregunta se cargó correctamente';
				let method = 'post';
				let url = 'questions/store'
				t.save_icon = 'glyphicon-hourglass';
				t.save_button = 'Espere';
				t.button_disabled = true;
				
				if(t.$route.params.id){
					message = 'La pregunta se actualizó correctamente';
					method = 'put';
					url = '/questions/' + t.$route.params.id;
				}
				
				axios({
					method: method,
					url: t.baseUrl + url,
					data: t.question.question
				})
			  	.then(function (response) {
					t.$emit('complete', {message:  message, success: true, warning: false, danger: false});
					t.$router.go(-1);
				})
				.catch(function (error) {
					t.errors = [];

					if(error.response.status != 401){
						_.forEach(error.response.data, function(message, index){
							t.errors.push({
								'name': index,
								'message': message[0]
							});
						});
					}else{
						t.$emit('complete', {message:  'Ha ocurrido un problema y no se ha podido editar la pregunta indicada. Por favor intente nuevamente más tarde', success: false, warning: false, danger: true, error:error});	
					}
					
					t.button_disabled = false;
					t.save_icon = 'glyphicon-floppy-disk';
					t.save_button = 'Guardar';
				});
			},
			// VALIDATION
			validateRequired(field) {
				if(!this.question.question[field]) return 'error-input';
			}
		}
	}
</script>