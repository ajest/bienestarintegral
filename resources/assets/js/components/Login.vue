<template>
	<v-layout row wrap>
		<v-flex md4 offset-md4>
			<form v-on:submit.prevent="logIn">
				<div class="panel panel-default">
					<v-layout class="panel-body" row wrap>
						<v-flex xs12 sm12 md12 lg12>
							<h1>Iniciar sesión</h1>
							<v-divider></v-divider>
						</v-flex>
						<v-flex v-if="errors.length > 0" xs12 sm12 md12 lg12>
							<v-alert info v-for="error in errors" value="true">
						    	<strong>{{ error.name }}</strong>: {{ error.message }}
						    </v-alert>	
						</v-flex>
						<v-flex class="form-group" xs12 sm12 md12 lg12 :class="hasErrors">
							<label for="email">Email</label>
							<input @focusin="setFlagError('email')" :class="validateRequired('email')" class="form-control" type="email" id="email" v-model="email" placeholder="Ingrese su e-mail" required />
						</v-flex>
						<v-flex class="form-group" xs12 sm12 md12 lg12>
							<label for="password">Contraseña</label>
							<input @focusin="setFlagError('password')" :class="validateRequired('password')" class="form-control" type="password" id="password" v-model="password" placeholder="Ingrese su contraseña" required />
						</v-flex>
						<v-flex class="form-group" xs12 sm12 md12 lg12>
							<div class="checkbox">
							    <label>
							    	<input type="checkbox" id="remember_me" v-model="remember_me" :disabled="hasEmail" /> Recordar mi casilla de email
							    </label>
						  	</div>
						</v-flex>
						<v-flex class="form-group" xs12 sm12 md12 lg12>
							<button class="btn btn--raised theme--light" :disabled="button_disabled" style="position: relative;">
								<div class="btn__content">{{ saveButtonName }}</div>
							</button>
						</v-flex>			
					</v-layout>
				</div>
			</form>
		</v-flex>
	</v-layout>
</template>
<script>
	import { mapState } from 'vuex';

	export default {
		data() {
			return {
				email: '',
				password: '',
				remember_me: false,
				save_button: 'Ingresar',
				button_disabled: false,
				errors: [],
				flag_error: {
					email: false,
					password: false
				},
				active_element: 'login',
			}
		},

		computed: {
			saveButtonName: function () {
				let message = this.save_button;
				if(this.$route.params.id){
					message = 'Actualizar';
				}
				return message;
			},
			...mapState({
		    	baseUrl: state => state.common.baseUrl,
		    	token: state => state.authentication.token,
		    	login_email: state => state.authentication.login_email,
		    }),

		    hasEmail () {
		    	return this.email ? false : true
		    },

		    hasErrors(){
		    	return this.errors.length > 0 ? 'mt-3' : false
		    }
		},

		created: function(){
			this.$emit('child_created', this.active_element);
			this.loginEmail();
		},

		methods: {
			logIn(){
				var t = this;
				let method = 'post';
				let url = '/professionals/signin'
				t.save_button = 'Ingresando..';
				t.button_disabled = true;

				if(t.remember_me) t.$store.commit('setLoginEmail', {
					login_email: t.email
				});

				axios({
					method: method,
					url: t.baseUrl + url,
					data: {
						email: t.email,
						password: t.password
					}
				})
			  	.then(function (response) {
			  		if(response.data.token){
			  			t.$store.dispatch('sendToken', {
			  				'token': response.data.token,
			  				'professional': response.data.professional
			  			});
			  			t.$router.go(-1);
			  		}
				})
				.catch(function (error) {
					t.errors = [];

					if(error.response.data.error){
						t.errors.push({
							'name': 'Error',
							'message': error.response.data.error
						});
					}else{
						if(error.response.data){
							_.forEach(error.response.data, function(message, index){
								t.errors.push({
									'name': index,
									'message': message[0]
								});
							});
						}
					}
					
					t.$emit('complete', {message: 'No ha sido posible ingresar. Verifique su email ó contraseña', success: false, warning: true, danger: false});	
					
					t.button_disabled = false;
					t.save_button = 'Ingresar';
				});
			},

			loginEmail(){
		    	if(this.login_email) this.email = this.login_email;
		    },
			
			// VALIDATION
			validateRequired(field) {
				if(!this[field] && this.flag_error[field]) return 'error-input';
			},

			setFlagError(field){
				this.flag_error[field] = true;
			}
		}
	}
</script>