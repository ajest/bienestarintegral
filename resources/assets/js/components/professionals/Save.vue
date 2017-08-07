<template>
	<form class="row col-md-12" v-on:submit.prevent="saveProfessional" v-if="checkExistence">
		<div class="row col-md-12">
			<h1>{{ professional.professional.name ? professional.professional.name : 'Nuevo Profesional' }} 
				<button class="pull-right btn btn-primary margin-left-small" :disabled="button_disabled"><span class="glyphicon " :class="save_icon"></span> {{ saveButtonName }}</button>
				<router-link to="/professionals" class="btn btn-success pull-right margin-left-small"><span class="glyphicon glyphicon-arrow-left"></span> Volver</router-link>
			</h1>
		</div>
		<hr />
		<div class="col-md-12">
			<h3>Información del profesional</h3>
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
				<input id="name" type="text" v-model="professional.professional.name" placeholder="Ej. Lucas García" class="form-control" :class="validateRequired('name')" @focusin="setFlagError('name')" required>
			</div>
			<div class="form-group col-md-6">
				<label for="email">Email *</label>
				<input id="email" type="email" v-model="professional.professional.email" placeholder="Ej. lucasgarcia@gmail.com" class="form-control" :class="validateRequired('email')" @focusin="setFlagError('email')" required>
			</div>
			<div class="form-group col-md-6">
				<label for="tel">Teléfono *</label>
				<input id="tel" type="text" v-model="professional.professional.tel" placeholder="Ej. +54 11 6890-8443" class="form-control" v-mask="'+## ## ####-####'" :class="validateRequired('tel')" @focusin="setFlagError('tel')" required>
			</div>
			<div class="form-group col-md-6">
				<label for="gender_id">Género *</label>
				<select id="gender_id" v-model="professional.professional.gender_id" class="form-control" :class="validateRequired('gender_id')" @focusin="setFlagError('gender_id')" required>
					<option value="">-- Seleccione Género --</option>
					<option value="1">Hombre</option>
					<option value="2">Mujer</option>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label for="address">Dirección *</label>
				<input id="address" type="text" v-model="professional.professional.address" placeholder="Ej. Posta de Pardo 1298" class="form-control" :class="validateRequired('address')" @focusin="setFlagError('address')" required>
			</div>
		</div>
	</form>
</template>
<script>
	import { mapState } from 'vuex';

	export default {
		data(){
			return {
				professional: '',				
				save_button: 'Guardar',
				save_icon: 'glyphicon-floppy-disk',
				button_disabled: false,
				errors: [],
				flag_error: {
					name: false,
					email: false,
					tel: false,
					gender_id: false,
					address: false
				},
				active_element: 'professional'
			}
		},
		
		computed: {
			checkExistence: function () {
				if(!this.$route.params.id){
					this.professional = {
						professional: {
							name: '',
							email: '',
							tel: '',
							gender_id: '',
							address: ''
						}
					};
					
					return true;	
				}else{
					return this.professional;
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
				this.getProfessional();
			}
			this.$emit('child_created', this.active_element);
		},
		methods: {
			getProfessional(){
				var t = this;
				axios.get(t.baseUrl + '/professionals/detail/' + (t.$route.params.id ? t.$route.params.id : ''))
					.then(function (response) {
						if(!_.isEmpty(response.data)){
							t.professional = response.data;
						}
					})
					.catch(function (error) {
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde', success: false, warning: false, danger: true, error:error});
					});
				
			},
			saveProfessional(){
				var t = this;
				let message = 'El profesional se cargó correctamente';
				let method = 'post';
				let url = '/professionals/store'
				t.save_icon = 'glyphicon-hourglass';
				t.save_button = 'Espere';
				t.button_disabled = true;
				
				if(t.$route.params.id){
					message = 'El profesional se actualizó correctamente';
					method = 'put';
					url = '/professionals/' + t.$route.params.id;
				}
				
				axios({
					method: method,
					url: t.baseUrl + url,
					data: t.professional.professional
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
						t.$emit('complete', {message:  'Ha ocurrido un problema y no se ha podido editar el profesional indicado. Por favor intente nuevamente más tarde', success: false, warning: false, danger: true, error:error});
					}
					
					t.button_disabled = false;
					t.save_icon = 'glyphicon-floppy-disk';
					t.save_button = 'Guardar';
				});
			},
			// VALIDATION
			validateRequired(field) {
				if(!this.professional.professional[field] && this.flag_error[field]) return 'error-input';
			},

			setFlagError(field){
				this.flag_error[field] = true;
			}
		}
	}
</script>