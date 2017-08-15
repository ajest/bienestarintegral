<template>
	<v-layout row wrap>
		<v-flex xs12 sm12 md12 lg12>
			<form v-on:submit.prevent="savePatient" v-if="checkExistence">
				<v-layout row wrap>
					<v-flex xs12 sm12 md9 lg10>
						<h1>{{ patient.patient.name ? patient.patient.name : 'Nuevo Paciente' }}</h1>
					</v-flex>
					<v-flex xs12 sm12 md3 lg2 class="mt-5">
						<button class="btn btn--raised theme--dark primary pull-right" :disabled="button_disabled">{{ saveButtonName }}</button>
						<v-btn class="pull-right" light medium to="/patients">
				          	<v-icon dark>chevron_left</v-icon>
				        </v-btn>
					</v-flex>
					<v-flex xs12 sm12 md12 lg12>
						<h3>Información del paciente</h3>
						<div class="panel panel-danger" v-if="errors.length > 0">
							<div class="panel-body">
								Su formulario contiene los siguientes errores:
								<ul>
									<li v-for="error in errors"><strong>{{ error.name }}</strong>: {{ error.message }}</li>
								</ul>
							</div>
						</div>
						<div class="form-group col-md-6">
							<label for="name">Nombre *</label>
							<input id="name" type="text" v-model="patient.patient.name" placeholder="Ej. Lucas García" class="form-control" :class="validateRequired('name')" @focusin="setFlagError('name')" required>
						</div>
						<div class="form-group col-md-6">
							<label for="email">Email *</label>
							<input id="email" type="email" v-model="patient.patient.email" placeholder="Ej. lucasgarcia@gmail.com" class="form-control" :class="validateRequired('email')" @focusin="setFlagError('email')" required>
						</div>
						<div class="form-group col-md-6">
							<label for="cellphone">Celular *</label>
							<input id="cellphone" type="text" v-model="patient.patient.cellphone" placeholder="Ej. +54 11 6890-8443" class="form-control" :class="validateRequired('cellphone')" v-mask="'+## ## ####-####'" @focusin="setFlagError('cellphone')" required>
						</div>
						<div class="form-group col-md-6">
							<label for="tel" >Teléfono</label>
							<input id="tel" type="text" v-model="patient.patient.tel" placeholder="Ej. +54 11 6890-8443" class="form-control" v-mask="'+## ## ####-####'">
						</div>
						<div class="form-group col-md-6">
							<label for="dni">DNI</label>
							<input id="dni" type="text" v-model="patient.patient.dni" placeholder="Ej. 34539064" class="form-control" v-mask="'########'">
						</div>
						<div class="form-group col-md-6">
							<label for="civil_status_id">Estado Civil </label>
							<select id="civil_status_id" v-model="patient.patient.civil_status_id" class="form-control">
								<option value="">-- Seleccione Estado Civil --</option>
								<option value="1">Soltero</option>
								<option value="2">Casado</option>
							</select>
						</div>
						<div class="form-group col-md-6">
							<label for="gender_id">Género *</label>
							<select id="gender_id" v-model="patient.patient.gender_id" class="form-control" :class="validateRequired('gender_id')" @focusin="setFlagError('gender_id')" required>
								<option value="">-- Seleccione Género --</option>
								<option value="1">Hombre</option>
								<option value="2">Mujer</option>
							</select>
						</div>
						<div class="form-group col-md-6">
							<label for="address">Dirección *</label>
							<input id="address" type="text" v-model="patient.patient.address" placeholder="Ej. Posta de Pardo 1298" class="form-control" :class="validateRequired('address')" @focusin="setFlagError('address')" required>
						</div>
						<div class="form-group col-md-6">
							<label for="birthdate">Fecha de nacimiento</label>
							<datepicker id="birthdate" v-model="patient.patient.birthdate" language="es" format="dd/MM/yyyy" input-class="form-control" placeholder="Ej. 15/07/2017" :highlighted="datepicker_highlighted" :class="validateRequired('birthdate')" @focusin="setFlagError('birthdate')" :required="true"></datepicker>
						</div>
						<div class="form-group col-md-6">
							<label for="area">Localidad</label>
							<input id="area" type="text" v-model="patient.patient.area" placeholder="Ej. CABA" class="form-control">
						</div>
						<div class="form-group col-md-6">
							<label for="facebook">Facebook (URL)</label>
							<input id="facebook" type="text" v-model="patient.patient.facebook" placeholder="Ej. https://www.facebook.com/profile.php?id=100010682533022" class="form-control">
						</div>
						<div class="form-group col-md-12">
							<label for="comments">Comentarios</label>
							<textarea id="comments" v-model="patient.patient.comments" placeholder="Ej. Señor que viene todos los viernes" class="form-control" rows="4"></textarea>
						</div>
					</v-flex>
					<v-flex xs12 sm12 md12 lg12 v-if="Object.keys(patient.specialties).length">
						<h3>Ficha paciente</h3>
						<v-tabs class="elevation-1">
							<v-tabs-bar slot="activators" class="green lighten-2">
								<v-tabs-slider class="pink"></v-tabs-slider>
								<v-tabs-item v-for="specialty in patient.specialties" :key="specialty[0].specialty_id" :href="'#tab-' + specialty[0].specialty_id">
									{{ specialty[0].specialty ? specialty[0].specialty.specialty : 'Generales' }}
								</v-tabs-item>
							</v-tabs-bar>
							<v-tabs-content v-for="specialty in patient.specialties" :id="'tab-' + specialty[0].specialty_id">
								<v-card flat>
									<v-card-text>
										<v-text-field v-for="question in specialty"
							              :name="question.id"
							              :label="question.question"
							              v-model="questions[question.id]"
							            ></v-text-field>
									</v-card-text>
								</v-card>
							</v-tabs-content>
						</v-tabs>
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
				patient: '',				
				save_button: 'Guardar',
				save_icon: 'glyphicon-floppy-disk',
				button_disabled: false,
				errors: [],
				datepicker_highlighted: {
			        dates: [
			        	new Date()
			        ] 
			    },
			    flag_error: {
					name: false,
					email: false,
					cellphone: false,
					gender_id: false,
					address: false,
					birthdate: false
				},
				active_element: 'patient',
				questions: {}
			}
		},
		
		computed: {
			checkExistence: function () {
				if(!this.$route.params.id){
					this.patient = {
						patient: {
							name: '',
							email: '',
							cellphone: '',
							tel: '',
							dni: '',
							civil_status_id: '',
							area: '',
							facebook: '',
							birthdate: new Date(),
							gender_id: '',
							address: '',
							comments: ''
						},
						specialties: {}
					};
					
					return true;	
				}else{
					return this.patient;
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
			this.getPatient();
			this.$emit('child_created', this.active_element);
		},
		methods: {
			getPatient(){
				var t = this;
				axios.get(t.baseUrl + '/patients/detail/' + (t.$route.params.id ? t.$route.params.id : ''))
					.then(function (response) {
						if(!_.isEmpty(response.data)){
							if(!_.isEmpty(response.data.specialties)){
								_.forEach(response.data.specialties, function(specialty) {
									_.forEach(specialty, function(question) {
										var id = question.id;
										let answer = _.find(response.data.answers, function(o) { return o.question_id == id; });
										t.questions[id] = answer && !_.isUndefined(answer) ? answer.answer : '';
									});	
								});
							}

							if(_.isEmpty(response.data.patient)){
								t.patient.specialties = response.data.specialties;
							}else{
								t.patient = response.data;
								t.patient.patient.date = new Date(t.patient.patient_date.d, t.patient.patient_date.m, t.patient.patient_date.y);
							}
						}
					})
					.catch(function (error) {
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde', success: false, warning: false, danger: true, error:error});
					});				
			},
			savePatient(){
				var t = this;
				let message = 'El paciente se cargó correctamente';
				let method = 'post';
				let url = '/patients/store'
				t.save_icon = 'glyphicon-hourglass';
				t.save_button = 'Espere';
				t.button_disabled = true;
				
				if(t.$route.params.id){
					message = 'El paciente se actualizó correctamente';
					method = 'put';
					url = '/patients/' + t.$route.params.id;
				}
				
				t.patient.patient['questions'] = t.questions;

				axios({
					method: method,
					url: t.baseUrl + url,
					data: t.patient.patient
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
						t.$emit('complete', {message:  'Ha ocurrido un problema y no se ha podido editar el paciente indicado. Por favor intente nuevamente más tarde', success: false, warning: false, danger: true, error:error});	
					}
					
					t.button_disabled = false;
					t.save_icon = 'glyphicon-floppy-disk';
					t.save_button = 'Guardar';
				});
			},
			
			// VALIDATION
			validateRequired(field) {
				if(!this.patient.patient[field] && this.flag_error[field]) return 'error-input';
			},

			setFlagError(field){
				this.flag_error[field] = true;
			}
		}
	}
</script>