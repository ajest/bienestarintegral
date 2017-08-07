<template>
	<form class="row col-md-12" v-on:submit.prevent="saveTreatment" v-if="checkExistence">
		<div class="row col-md-12">
			<h1>{{ treatment.treatment.treatment ? treatment.treatment.treatment : 'Nueva tratamiento' }} 
				<button class="pull-right btn btn-primary margin-left-small" :disabled="button_disabled"><span class="glyphicon " :class="save_icon"></span> {{ saveButtonName }}</button>
				<router-link to="/treatments" class="btn btn-success pull-right margin-left-small"><span class="glyphicon glyphicon-arrow-left"></span> Volver</router-link>
			</h1>
		</div>
		<hr />
		<div class="col-md-12">
			<h3>Información del tratamiento</h3>
			<div class="panel panel-danger" v-if="errors.length > 0">
				<div class="panel-body">
					Su formulario contiene los siguientes errores:
					<ul>
						<li v-for="error in errors"><strong>{{ error.treatment }}</strong>: {{ error.message }}</li>
					</ul>
				</div>
			</div>
			<div class="form-group col-md-6">
				<label for="treatment">Tratamiento *</label>
				<input id="treatment" type="text" v-model="treatment.treatment.treatment" placeholder="Ej. Reflexología" class="form-control" :class="validateRequired('treatment')" @focusin="setFlagError('treatment')" required>
			</div>
			<div class="form-group col-md-6">
				<label for="specialty_id">Especialidad</label>
				<select id="specialty_id" v-model="treatment.treatment.specialty_id" class="form-control">
					<option value="">-- Seleccione Especialidad --</option>
					<option v-for="specialty in treatment.all.specialties" v-bind:value="specialty.id">
						{{ specialty.specialty }}
					</option>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label for="description">Descripción</label>
				<textarea id="description" v-model="treatment.treatment.description" placeholder="Ej. La reflexología permite ..." class="form-control" rows="4"></textarea>
			</div>
		</div>
	</form>
</template>
<script>
	import { mapState } from 'vuex';

	export default {
		data(){
			return {
				treatment: '',				
				save_button: 'Guardar',
				save_icon: 'glyphicon-floppy-disk',
				button_disabled: false,
				errors: [],
				flag_error: {
					treatment: false
				},
				active_element: 'settings'
			}
		},
		
		computed: {
			checkExistence: function () {
				if(!this.$route.params.id){
					this.treatment = {
						treatment: {
							treatment: '',
							description: '',
							specialty_id: ''
						},
						all: {
							specialties: ''
						}
					};
					
					return true;	
				}else{
					return this.treatment;
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
			this.getTreatment();
			this.$emit('child_created', this.active_element);
		},
		methods: {
			getTreatment(){
				var t = this;
				axios.get(t.baseUrl + '/treatments/detail/' + (t.$route.params.id ? t.$route.params.id : ''))
					.then(function (response) {
						if(!_.isEmpty(response.data)){
							if(_.isEmpty(response.data.treatment)){
								t.treatment.all = response.data.all;
							}else{
								t.treatment = response.data;
							}
						}
					})
					.catch(function (error) {
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde', success: false, warning: false, danger: true, error:error});
					});
				
			},
			saveTreatment(){
				var t = this;
				let message = 'El tratamiento se cargó correctamente';
				let method = 'post';
				let url = '/treatments/store'
				t.save_icon = 'glyphicon-hourglass';
				t.save_button = 'Espere';
				t.button_disabled = true;
				
				if(t.$route.params.id){
					message = 'El tratamiento se actualizó correctamente';
					method = 'put';
					url = '/treatments/' + t.$route.params.id;
				}
				
				axios({
					method: method,
					url: t.baseUrl + url,
					data: t.treatment.treatment
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
						t.$emit('complete', {message:  'Ha ocurrido un problema y no se ha podido editar el tratamiento indicado. Por favor intente nuevamente más tarde', success: false, warning: false, danger: true, error:error});	
					}
					
					t.button_disabled = false;
					t.save_icon = 'glyphicon-floppy-disk';
					t.save_button = 'Guardar';
				});
			},
			
			// VALIDATION
			validateRequired(field) {
				if(!this.treatment.treatment[field] && this.flag_error[field]) return 'error-input';
			},

			setFlagError(field){
				this.flag_error[field] = true;
			}
		}
	}
</script>