<template>
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<router-link class="navbar-brand" to="/"><strong>Bienestar <span class="glyphicon glyphicon-leaf"></span></strong></router-link>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
				<a href="#" class="navbar-brand" @click="logout"> {{ authentication_text }} <span class="glyphicon" :class="authentication_icon"></span></a>
			</ul>
			<!--<form class="navbar-form navbar-right">
				<input type="text" class="form-control" placeholder="Buscar...">
			</form>-->
		</div>
	</div>
</template>
<script>
	import { mapState } from 'vuex';

	export default {
		data(){
			return {
				authentication_text: 'Ingresar',
				authentication_icon: 'glyphicon-log-in'
			}
		},
		
		computed: {
			...mapState({
		    	baseUrl: state => state.common.baseUrl,
		    	token: state => state.authentication.token,
		    	logged_professional: state => state.authentication.professional,
		    })
		},

		methods: {
			logout(){
				var t = this;

				t.$store.commit('clearLoginData');
				t.$router.push('/login');
			}
		},

		watch: {
		    '$route' (to, from) {
		    	var t = this;

				if(t.token){
					t.authentication_text = t.logged_professional.name;
					t.authentication_icon = 'glyphicon-log-out';
				}else{
					t.authentication_text = '',
					t.authentication_icon = ''
				}
		    }
		}
	}
</script>