<template>
	<form class="row col-md-12" v-on:submit.prevent="saveAppointment" v-if="checkExistence">
		<div class="row col-md-12">
			<h1>{{ appointment.appointment.title ? appointment.appointment.title : 'Nuevo Turno' }} 
				<button class="pull-right btn btn-primary margin-left-small" :disabled="button_disabled"><span class="glyphicon " :class="save_icon"></span> {{ saveButtonName }}</button>
				<router-link to="/appointments" class="btn btn-success pull-right margin-left-small"><span class="glyphicon glyphicon-arrow-left"></span> Volver</router-link>
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
				<label>Titulo</label>
				<input type="text" v-model="appointment.appointment.title" placeholder="Ej. Turno Urgente" class="form-control">
			</div>
			<div class="form-group col-md-6">
				<label>Fecha *</label>
				<datepicker v-model="appointment.appointment.date" language="es" format="dd/MM/yyyy" input-class="form-control" placeholder="Ej. 15/07/2017" :highlighted="datepicker_highlighted" :class="validateRequired('date')" :required="true"></datepicker>
			</div>
			<div class="form-group col-md-6">
				<label>Hora *</label>
				<input type="text" v-model="appointment.appointment.hour" placeholder="Ej. 19:00" class="form-control" :class="validateRequired('hour')" v-mask="'##:##'" required>
			</div>
			<div class="form-group col-md-6">
				<label>Profesional</label>
				<select v-model="appointment.appointment.professional_id" class="form-control">
					<option value="">-- Seleccione Profesional --</option>
					<option v-for="professional in appointment.all.professionals" v-bind:value="professional.id">
						{{ professional.name }}
					</option>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label>Paciente *</label>
				<select v-model="appointment.appointment.patient_id" class="form-control" :class="validateRequired('patient_id')" required>
					<option value="">-- Seleccione Paciente --</option>
					<option v-for="patient in appointment.all.patients" v-bind:value="patient.id">
						{{ patient.name }}
					</option>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label>Especialidad</label>
				<select v-model="appointment.appointment.specialty_id" class="form-control">
					<option value="">-- Seleccione Especialidad --</option>
					<option v-for="specialty in appointment.all.specialties" v-bind:value="specialty.id">
						{{ specialty.specialty }}
					</option>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label>Tratamiento</label>
				<select v-model="appointment.appointment.treatment_id" class="form-control">
					<option value="">-- Seleccione Tratamiento --</option>
					<option v-for="treatment in appointment.all.treatments" v-bind:value="treatment.id">
						{{ treatment.treatment }}
					</option>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label>Promoción</label>
				<select v-model="appointment.appointment.series_id" class="form-control">
					<option value="">-- Seleccione Promoción --</option>
					<option v-for="serie in appointment.all.series" v-bind:value="serie.id">
						{{ serie.series }}
					</option>
				</select>
			</div>
		</div>
	</form>
</template>
<script>
	export default {
		data(){
			return {
				appointment: '',				
				save_button: 'Guardar',
				save_icon: 'glyphicon-floppy-disk',
				button_disabled: false,
				errors: [],
			    datepicker_highlighted: {
			        dates: [
			        	new Date()
			        ] 
			    }
			}
		},
		
		computed: {
			checkExistence: function () {
				if(!this.$route.params.id){
					this.appointment = {
						appointment: {
							title: '',
							date: new Date(),
							hour: '',
							professional_id: '',
							patient_id: '',
							specialty_id: '',
							treatment_id: '',
							series_id: ''
						},
						all: {
							patients: '',
							professionals: '',
							series: '',
							specialties: '',
							treatments: ''
						}
					};
					
					return true;	
				}else{
					return this.appointment;
				}
			},
			saveButtonName: function () {
				let message = this.save_button;
				if(this.$route.params.id){
					message = 'Actualizar';
				}
				return message;
			}
		},

		created: function(){
			this.getAppointment();
		},

		methods: {
			getAppointment(){
				var t = this;

				axios.get('/appointments/detail/' + (t.$route.params.id ? t.$route.params.id : ''))
					.then(function (response) {
						if(!_.isEmpty(response.data)){
							if(_.isEmpty(response.data.appointment)){
								t.appointment.all = response.data.all;
							}else{
								t.appointment = response.data;
								t.appointment.appointment.date = new Date(t.appointment.appointment_date.d, t.appointment.appointment_date.m, t.appointment.appointment_date.y);
							}
						}
					})
					.catch(function (error) {
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde', success: false, warning: false, danger: true});
					});
				
			},

			saveAppointment(){
				var t = this;
				let message = 'El turno se cargó correctamente';
				let method = 'post';
				let url = 'appointments/store'

				t.save_icon = 'glyphicon-hourglass';
				t.save_button = 'Espere';
				t.button_disabled = true;

				if(t.$route.params.id){
					message = 'El turno se actualizó correctamente';
					method = 'put';
					url = '/appointments/' + t.$route.params.id;
				}

				axios({
					method: method,
					url: url,
					data: t.appointment.appointment
				})
			  	.then(function (response) {
					t.$emit('complete', {message:  message, success: true, warning: false, danger: false});
					t.$router.go(-1);
				})
				.catch(function (error) {
					if(error.response.data){
						_.forEach(error.response.data, function(message, index){
							t.errors.push({
								'name': index,
								'message': message[0]
							});
						});

					}else{
						t.$emit('complete', {message:  'Ha ocurrido un problema y no se ha podido editar el turno indicado. Por favor intente nuevamente más tarde', success: false, warning: false, danger: true});	
					}
					
					t.button_disabled = false;
					t.save_icon = 'glyphicon-floppy-disk';
					t.save_button = 'Guardar';
				});
			},

			// VALIDATION
			validateRequired(field) {
				if(!this.appointment.appointment[field]) return 'error-input';
			}
		}
	}
</script>