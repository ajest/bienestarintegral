<template>
	<form class="row col-md-12" v-on:submit.prevent="saveSpecialty" v-if="checkExistence">
		<div class="row col-md-12">
			<h1>{{ specialty.specialty.specialty ? specialty.specialty.specialty : 'Nueva especialidad' }} 
				<button class="pull-right btn btn-primary margin-left-small" :disabled="button_disabled"><span class="glyphicon " :class="save_icon"></span> {{ saveButtonName }}</button>
				<router-link to="/specialties" class="btn btn-success pull-right margin-left-small"><span class="glyphicon glyphicon-arrow-left"></span> Volver</router-link>
			</h1>
		</div>
		<hr />
		<div class="col-md-12">
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
				<label>Nombre *</label>
				<input type="text" v-model="specialty.specialty.specialty" placeholder="Ej. Lucas García" class="form-control" required>
			</div>
			<div class="form-group col-md-6">
				<label>Descripción</label>
				<textarea v-model="specialty.specialty.description" placeholder="Ej. Los masajes son la serie de prácticas..." class="form-control" rows="4"></textarea>
			</div>
		</div>
	</form>
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
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde', success: false, warning: false, danger: true});
					});
				
			},
			saveSpecialty(){
				var t = this;
				let message = 'La especialidad se cargó correctamente';
				let method = 'post';
				let url = 'specialties/store'
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
					if(error.response.data){
						_.forEach(error.response.data, function(message, index){
							t.errors.push({
								'name': index,
								'message': message[0]
							});
						});
					}else{
						t.$emit('complete', {message:  'Ha ocurrido un problema y no se ha podido editar la especialidad indicada. Por favor intente nuevamente más tarde', success: false, warning: false, danger: true});	
					}
					
					t.button_disabled = false;
					t.save_icon = 'glyphicon-floppy-disk';
					t.save_button = 'Guardar';
				});
			},
			// VALIDATION
			validateRequired(field) {
				if(!this.specialty.specialty[field]) return 'error-input';
			}
		}
	}
</script>