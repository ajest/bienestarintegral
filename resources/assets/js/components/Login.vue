<template>
	<form v-on:submit.prevent="logIn">
		<div class="row col-lg-offset-2 col-lg-5 col-md-10">
			<div class="panel panel-default col-md-12">
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<h1>Iniciar sesión</h1>
							<hr />
						</div>
						<div class="panel panel-danger col-md-12" v-if="errors.length > 0">
							<div class="panel-body">
								Acceso denegado
								<ul>
									<li v-for="error in errors"><strong>{{ error.name }}</strong>: {{ error.message }}</li>
								</ul>
							</div>
						</div>
						<div class="form-group col-md-12">
							<label for="email">Email</label>
							<input @focusin="setFlagError('email')" :class="validateRequired('email')" class="form-control" type="email" id="email" v-model="email" placeholder="Ingrese su e-mail" required />
						</div>
						<div class="form-group col-md-12">
							<label for="password">Contraseña</label>
							<input @focusin="setFlagError('password')" :class="validateRequired('password')" class="form-control" type="password" id="password" v-model="password" placeholder="Ingrese su contraseña" required />
						</div>
						<div class="form-group col-md-12">
							<div class="checkbox">
							    <label>
							    	<input type="checkbox" id="remember_me" v-model="remember_me" :disabled="hasEmail" /> Recordar mi casilla de email
							    </label>
						  	</div>
						</div>
						<div class="form-group col-md-12">
							<button class="btn btn-primary" :disabled="button_disabled"><span class="glyphicon " :class="save_icon"></span> {{ saveButtonName }}</button>
						</div>			
					</div>
				</div>
			</div>
		</div>
	</form>
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
				save_icon: 'glyphicon-log-in',
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
				t.save_icon = 'glyphicon-hourglass';
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
					t.save_icon = 'glyphicon-log-in';
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