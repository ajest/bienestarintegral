<template>
	<v-layout row wrap>
		<v-flex xs12 sm12 md12 lg12>			
			<form v-on:submit.prevent="saveSeries" v-if="checkExistence">
				<v-layout row wrap>
					<v-flex xs12 sm12 md9 lg10>
						<h1>{{ specialty.specialty.specialty ? specialty.specialty.specialty : 'Nueva especialidad' }}</h1>
					</v-flex>
					<v-flex xs12 sm12 md3 lg2 class="mt-5">
						<button class="btn btn--raised theme--dark primary pull-right" :disabled="button_disabled">{{ saveButtonName }}</button>
						<v-btn class="pull-right" light medium to="/series">
				          	<v-icon dark>chevron_left</v-icon>
				        </v-btn>
					</v-flex>
					<v-flex xs12 sm12 md12 lg12>
						<h3>Información de la promoción</h3>
						<div class="panel panel-danger" v-if="errors.length > 0">
							<div class="panel-body">
								Su formulario contiene los siguientes errores:
								<ul>
									<li v-for="error in errors"><strong>{{ error.series }}</strong>: {{ error.message }}</li>
								</ul>
							</div>
						</div>
						<div class="form-group col-md-6">
							<label for="series">Nombre *</label>
							<input id="series" type="text" v-model="series.series.series" placeholder="Ej. 10 sesiones al precio de 9" class="form-control" :class="validateRequired('series')" @focusin="setFlagError('series')" required>
						</div>
						<div class="form-group col-md-6">
							<label for="cant">Cantidad</label>
							<input id="cant" type="text" v-model="series.series.cant" placeholder="Ej. 10" class="form-control" v-mask="'##'">
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
				series: {
					series: {
						cant: '',
						series: ''
					}
				},
				save_button: 'Guardar',
				save_icon: 'glyphicon-floppy-disk',
				button_disabled: false,
				errors: [],
				flag_error: {
					series: false
				},
				active_element: 'series'
			}
		},
		
		computed: {
			checkExistence: function () {
				if(!this.$route.params.id){
					this.series = {
						series: {
							series: '',
							cant: ''
						}
					};
					
					return true;	
				}else{
					return this.series;
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
				this.getSeries();
			}
			this.$emit('child_created', this.active_element);
		},
		methods: {
			getSeries(){
				var t = this;
				axios.get(t.baseUrl + '/series/detail/' + (t.$route.params.id ? t.$route.params.id : ''))
					.then(function (response) {
						if(!_.isEmpty(response.data)){
							t.series = response.data;
						}
					})
					.catch(function (error) {
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde', success: false, warning: false, danger: true, error:error});
					});
				
			},
			saveSeries(){
				var t = this;
				let message = 'La promoción se cargó correctamente';
				let method = 'post';
				let url = '/series/store'
				t.save_icon = 'glyphicon-hourglass';
				t.save_button = 'Espere';
				t.button_disabled = true;
				
				if(t.$route.params.id){
					message = 'La promoción se actualizó correctamente';
					method = 'put';
					url = '/series/' + t.$route.params.id;
				}
				
				axios({
					method: method,
					url: t.baseUrl + url,
					data: t.series.series
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
						t.$emit('complete', {message:  'Ha ocurrido un problema y no se ha podido editar la promoción indicada. Por favor intente nuevamente más tarde', success: false, warning: false, danger: true, error: error});	
					}
					
					t.button_disabled = false;
					t.save_icon = 'glyphicon-floppy-disk';
					t.save_button = 'Guardar';
				});
			},
			
			// VALIDATION
			validateRequired(field) {
				if(!this.series.series[field] && this.flag_error[field]) return 'error-input';
			},

			setFlagError(field){
				this.flag_error[field] = true;
			}
		}
	}
</script>