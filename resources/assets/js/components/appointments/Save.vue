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
			<h3>Información del Turno</h3>
			<div class="panel panel-danger" v-if="errors.length > 0">
				<div class="panel-body">
					Su formulario contiene los siguientes errores:
					<ul>
						<li v-for="error in errors"><strong>{{ error.name }}</strong>: {{ error.message }}</li>
					</ul>
				</div>
			</div>
			<div class="form-group col-md-6">
				<label for="title">Titulo</label>
				<input id="title" type="text" v-model="appointment.appointment.title" placeholder="Ej. Turno Urgente" class="form-control">
			</div>
			<div class="form-group col-md-6">
				<label for="date">Fecha *</label>
				<datepicker id="date" v-model="appointment.appointment.date" language="es" format="dd/MM/yyyy" input-class="form-control" placeholder="Ej. 15/07/2017" :highlighted="datepicker_highlighted" :class="validateRequired('date')" :required="true" @focusin="setFlagError('date')"></datepicker>
			</div>
			<div class="form-group col-md-6">
				<label for="hour">Hora *</label>
				<input id="hour" type="text" v-model="appointment.appointment.hour" placeholder="Ej. 19:00" class="form-control" :class="validateRequired('hour')" v-mask="'##:##'" @focusin="setFlagError('hour')" required>
			</div>
			<div class="form-group col-md-6">
				<label for="professional_id">Profesional</label>
				<select id="professional_id" v-model="appointment.appointment.professional_id" class="form-control">
					<option value="">-- Seleccione Profesional --</option>
					<option v-for="professional in appointment.all.professionals" v-bind:value="professional.id">
						{{ professional.name }}
					</option>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label for="patient_id">Paciente *</label>
				<select id="patient_id" v-model="appointment.appointment.patient_id" class="form-control" :class="validateRequired('patient_id')" @focusin="setFlagError('patient_id')" required>
					<option value="">-- Seleccione Paciente --</option>
					<option v-for="patient in appointment.all.patients" v-bind:value="patient.id">
						{{ patient.name }}
					</option>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label for="specialty_id">Especialidad</label>
				<select id="specialty_id" v-model="appointment.appointment.specialty_id" class="form-control">
					<option value="">-- Seleccione Especialidad --</option>
					<option v-for="specialty in appointment.all.specialties" v-bind:value="specialty.id">
						{{ specialty.specialty }}
					</option>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label for="treatment_id">Tratamiento</label>
				<select id="treatment_id" v-model="appointment.appointment.treatment_id" class="form-control">
					<option value="">-- Seleccione Tratamiento --</option>
					<option v-for="treatment in appointment.all.treatments" v-bind:value="treatment.id">
						{{ treatment.treatment }}
					</option>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label for="series_id">Promoción</label>
				<select id="series_id" v-model="appointment.appointment.series_id" class="form-control">
					<option value="">-- Seleccione Promoción --</option>
					<option v-for="serie in appointment.all.series" v-bind:value="serie.id">
						{{ serie.series }}
					</option>
				</select>
			</div>
			<div class="form-group col-md-12">
				<label for="comments">Comentarios</label>
				<textarea id="comments" v-model="appointment.appointment.comments" placeholder="Ej. Describa el turno" class="form-control" rows="4"> {{ appointment.appointment.comments }} </textarea>
			</div>
		</div>
	</form>
</template>
<script>
	import { mapState } from 'vuex';

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
			    },
			    flag_error: {
					date: false,
					hour: false,
					patient_id: false
				},
				active_element: 'appointment'
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
			},
			...mapState({
		    	baseUrl: state => state.common.baseUrl
		    })
		},
		
		created: function(){
			this.getAppointment();
			this.$emit('child_created', this.active_element);
		},
		
		methods: {
			getAppointment(){
				var t = this;
				axios.get(t.baseUrl + '/appointments/detail/' + (t.$route.params.id ? t.$route.params.id : ''))
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
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde', success: false, warning: false, danger: true, error:error});
					});
				
			},
			saveAppointment(){
				var t = this;
				let message = 'El turno se cargó correctamente';
				let method = 'post';
				let url = '/appointments/store'
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
					url: t.baseUrl + url,
					data: t.appointment.appointment
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
						t.$emit('complete', {message:  'Ha ocurrido un problema y no se ha podido editar el turno indicado. Por favor intente nuevamente más tarde', success: false, warning: false, danger: true, error:error});	
					}
					
					t.button_disabled = false;
					t.save_icon = 'glyphicon-floppy-disk';
					t.save_button = 'Guardar';
				});
			},
			
			// VALIDATION
			validateRequired(field) {
				if(!this.appointment.appointment[field] && this.flag_error[field]) return 'error-input';
			},

			setFlagError(field){
				this.flag_error[field] = true;
			}
		}
	}
</script>