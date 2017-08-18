<template>
	<v-layout row wrap>
		<v-flex xs12 sm12 md12 lg12>			
			<form v-on:submit.prevent="saveQuestion" v-if="checkExistence">
				<v-layout row wrap>
					<v-flex xs12 sm12 md9 lg10>
						<h2>{{ question.question.question ? question.question.question : 'Nueva pregunta' }} </h2>
					</v-flex>
					<v-flex xs12 sm12 md3 lg2 class="mt-5">
						<button id="submit-form" class="btn btn--raised theme--dark primary pull-right" :disabled="button_disabled">{{ saveButtonName }}</button>
						<v-btn class="pull-right" light medium to="/questions">
				          	<v-icon dark>chevron_left</v-icon>
				        </v-btn>
					</v-flex>
					<v-flex xs12 sm12 md12 lg12>
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
							<label for="question">Pregunta *</label>
							<input id="question" type="text" v-model="question.question.question" placeholder="Ej. Reflexología" class="form-control" :class="validateRequired('question')" @focusin="setFlagError('question')" required>
						</div>
						<div class="form-group col-md-6">
							<label for="specialty_id">Especialidad</label>
							<select id="specialty_id" v-model="question.question.specialty_id" class="form-control">
								<option value="">-- Seleccione Especialidad --</option>
								<option v-for="specialty in question.all.specialties" v-bind:value="specialty.id">
									{{ specialty.specialty }}
								</option>
							</select>
						</div>
					</v-flex>
				</v-layout>					
			</form>
		</v-flex>
	</v-layout>
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
				flag_error: {
					question: false
				},
				active_element: 'settings'
			}
		},
		
		computed: {
			checkExistence: function () {
				if(!this.$route.params.id){
					this.question = {
						question: {
							question: '',
							description: '',
							specialty_id: ''
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
				let url = '/questions/store'
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
				if(!this.question.question[field] && this.flag_error[field]) return 'error-input';
			},

			setFlagError(field){
				this.flag_error[field] = true;
			}
		}
	}
</script>