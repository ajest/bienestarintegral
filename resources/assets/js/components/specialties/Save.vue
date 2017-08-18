<template>
	<v-layout row wrap>
		<v-flex xs12 sm12 md12 lg12>			
			<form v-on:submit.prevent="saveSpecialty" v-if="checkExistence">
				<v-layout row wrap>
					<v-flex xs12 sm12 md9 lg10>
						<h2>{{ specialty.specialty.specialty ? specialty.specialty.specialty : 'Nueva Especialidad' }}</h2>
					</v-flex>									
					<v-flex xs12 sm12 md3 lg2 class="mt-5">
						<button id="submit-form" class="btn btn--raised theme--dark primary pull-right" :disabled="button_disabled">{{ saveButtonName }}</button>
						<v-btn class="pull-right" light medium to="/specialties">
				          	<v-icon dark>chevron_left</v-icon>
				        </v-btn>
					</v-flex>
					<v-flex xs12 sm12 md12 lg12>
						<h3>Información de la especialidad</h3>
						<div class="panel panel-danger" v-if="errors.length > 0">
							<div class="panel-body">
								Su formulario contiene los siguientes errores:
								<ul>
									<li v-for="error in errors"><strong>{{ error.specialty }}</strong>: {{ error.message }}</li>
								</ul>
							</div>
						</div>
						<div class="form-group col-md-6">
							<label for="specialty">Nombre *</label>
							<input id="specialty" type="text" v-model="specialty.specialty.specialty" placeholder="Ej. Lucas García" class="form-control" :class="validateRequired('specialty')" @focusin="setFlagError('specialty')" required>
						</div>
						<div class="form-group col-md-6">
							<label for="description">Descripción</label>
							<textarea id="description" v-model="specialty.specialty.description" placeholder="Ej. Los masajes son la serie de prácticas..." class="form-control" rows="4"></textarea>
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
				specialty: '',				
				save_button: 'Guardar',
				save_icon: 'glyphicon-floppy-disk',
				button_disabled: false,
				errors: [],
				flag_error: {
					specialty: false
				},
				active_element: 'settings'
			}
		},
		
		computed: {
			checkExistence: function () {
				if(!this.$route.params.id){
					this.specialty = {
						specialty: {
							specialty: '',
							description: ''
						}
					};
					
					return true;	
				}else{
					return this.specialty;
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
			if(this.$route.params.id){
				this.getSpecialty();
			}
			this.$emit('child_created', this.active_element);
		},
		methods: {
			getSpecialty(){
				var t = this;
				axios.get(t.baseUrl + '/specialties/detail/' + (t.$route.params.id ? t.$route.params.id : ''))
					.then(function (response) {
						if(!_.isEmpty(response.data)){
							t.specialty = response.data;
						}
					})
					.catch(function (error) {
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde', success: false, warning: false, danger: true, error:error});
					});
				
			},
			saveSpecialty(){
				var t = this;
				let message = 'La especialidad se cargó correctamente';
				let method = 'post';
				let url = '/specialties/store'
				t.save_icon = 'glyphicon-hourglass';
				t.save_button = 'Espere';
				t.button_disabled = true;
				
				if(t.$route.params.id){
					message = 'La especialidad se actualizó correctamente';
					method = 'put';
					url = '/specialties/' + t.$route.params.id;
				}
				
				axios({
					method: method,
					url: t.baseUrl + url,
					data: t.specialty.specialty
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
						t.$emit('complete', {message:  'Ha ocurrido un problema y no se ha podido editar la especialidad indicada. Por favor intente nuevamente más tarde', success: false, warning: false, danger: true, error:error});	
					}
					
					t.button_disabled = false;
					t.save_icon = 'glyphicon-floppy-disk';
					t.save_button = 'Guardar';
				});
			},
			// VALIDATION
			validateRequired(field) {
				if(!this.specialty.specialty[field] && this.flag_error[field]) return 'error-input';
			},

			setFlagError(field){
				this.flag_error[field] = true;
			}
		}
	}
</script>