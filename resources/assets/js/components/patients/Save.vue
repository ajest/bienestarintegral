<template>
	<form class="row col-md-12" v-on:submit.prevent="savePatient" v-if="checkExistence">
		<div class="row col-md-12">
			<h1>{{ patient.patient.name ? patient.patient.name : 'Nuevo Paciente' }} 
				<button class="pull-right btn btn-primary margin-left-small" :disabled="button_disabled"><span class="glyphicon " :class="save_icon"></span> {{ saveButtonName }}</button>
				<router-link to="/patients" class="btn btn-success pull-right margin-left-small"><span class="glyphicon glyphicon-arrow-left"></span> Volver</router-link>
			</h1>
		</div>
		<hr />
		<div class="col-md-12">
			<h3>Información personal</h3>
			<div class="panel panel-danger" v-if="errors.length > 0">
				<div class="panel-body">
					Su formulario contiene los siguientes errores:
					<ul>
						<li v-for="error in errors"><strong>{{ error.name }}</strong>: {{ error.message }}</li>
					</ul>
				</div>
			</div>
			<div class="form-group col-md-6">
				<label>Nombre *</label>
				<input type="text" v-model="patient.patient.name" placeholder="Ej. Lucas García" class="form-control" required>
			</div>
			<div class="form-group col-md-6">
				<label>Email *</label>
				<input type="email" v-model="patient.patient.email" placeholder="Ej. lucasgarcia@gmail.com" class="form-control" required>
			</div>
			<div class="form-group col-md-6">
				<label>Celular *</label>
				<input type="text" v-model="patient.patient.cellphone" placeholder="Ej. +54 11 6890-8443" class="form-control" v-mask="'+## ## ####-####'" required>
			</div>
			<div class="form-group col-md-6">
				<label>Teléfono</label>
				<input type="text" v-model="patient.patient.tel" placeholder="Ej. +54 11 6890-8443" class="form-control" v-mask="'+## ## ####-####'">
			</div>
			<div class="form-group col-md-6">
				<label>DNI</label>
				<input type="text" v-model="patient.patient.dni" placeholder="Ej. 34539064" class="form-control" v-mask="'########'">
			</div>
			<div class="form-group col-md-6">
				<label>Estado Civil </label>
				<select v-model="patient.patient.civil_status_id" class="form-control">
					<option value="1">Soltero</option>
					<option value="2">Casado</option>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label>Sexo *</label>
				<select v-model="patient.patient.gender_id" class="form-control" required>
					<option value="1">Hombre</option>
					<option value="2">Mujer</option>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label>Dirección *</label>
				<input type="text" v-model="patient.patient.address" placeholder="Ej. Posta de Pardo 1298" class="form-control" required>
			</div>
			<div class="form-group col-md-6">
				<label>Fecha de nacimiento</label>
				<datepicker v-model="patient.patient.birthdate" language="es" format="dd/MM/yyyy" input-class="form-control" placeholder="Ej. 15/07/2017" :highlighted="datepicker_highlighted" :class="validateRequired('date')" :required="true"></datepicker>
			</div>
			<div class="form-group col-md-6">
				<label>Localidad</label>
				<input type="text" v-model="patient.patient.area" placeholder="Ej. CABA" class="form-control">
			</div>
			<div class="form-group col-md-6">
				<label>Facebook (URL)</label>
				<input type="text" v-model="patient.patient.facebook" placeholder="Ej. https://www.facebook.com/profile.php?id=100010682533022" class="form-control">
			</div>
			<div class="form-group col-md-12">
				<label>Comentarios</label>
				<textarea v-model="patient.patient.comments" placeholder="Ej. Señor que viene todos los viernes" class="form-control" rows="4"></textarea>
			</div>
		</div>
	</form>
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
				active_element: 'patient'
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
						}
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
			if(this.$route.params.id){
				this.getPatient();
			}
			this.$emit('child_created', this.active_element);
		},
		methods: {
			getPatient(){
				var t = this;
				axios.get(t.baseUrl + '/patients/detail/' + (t.$route.params.id ? t.$route.params.id : ''))
					.then(function (response) {
						if(!_.isEmpty(response.data)){
							if(_.isEmpty(response.data.patient)){
								t.patient.all = response.data.all;
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
				let url = 'patients/store'
				t.save_icon = 'glyphicon-hourglass';
				t.save_button = 'Espere';
				t.button_disabled = true;
				
				if(t.$route.params.id){
					message = 'El paciente se actualizó correctamente';
					method = 'put';
					url = '/patients/' + t.$route.params.id;
				}
				
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
				if(!this.patient.patient[field]) return 'error-input';
			}
		}
	}
</script>