<template>
	<transition name="fade">
		<v-layout row wrap>
			<v-flex xs12 sm12 md12 lg12 v-if="professional">
				<v-layout row wrap>
					<v-flex xs12 sm12 md8 lg9>
						<h2><i class="material-icons icon-h2">school</i> {{ professional.professional.name }}</h2>
					</v-flex>
					<v-flex xs12 sm12 md4 lg3 class="mt-5">
						<v-btn class="pull-right pink" dark medium to="/professionals">
					    	<v-icon dark>date_range</v-icon>
						</v-btn>
						<v-btn class="pull-right" dark medium primary :to="{ name: 'professionals_edit', params: { id:  professional.professional.id }}">
					    	<v-icon dark>edit</v-icon>
						</v-btn>
						<v-btn class="pull-right" light medium to="/professionals">
					    	<v-icon dark>chevron_left</v-icon>
						</v-btn>
					</v-flex>
					<v-flex xs12 sm12 md12 lg12>
						<v-layout row wrap>
							<v-flex xs12 sm12 md6 lg6>
								<h3>Información del paciente</h3>
								<v-expansion-panel expand>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Nombre</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ professional.professional.name }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Email</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ professional.professional.email }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Teléfonos</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ professional.professional.tel }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Domicilio</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ professional.professional.address }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								</v-expansion-panel>
							</v-flex>
							<v-flex xs12 sm12 md6 lg6 v-if="Object.keys(professional.appointments).length">
								<nextappointments v-bind:appointments="professional.appointments"></nextappointments>
							</v-flex>
							<v-flex xs12 sm12 md6 lg6 v-if="Object.keys(professional.history).length">
								<historialappointments v-bind:appointments="professional.history"></historialappointments>
							</v-flex>
						</v-layout>
					</v-flex>
				</v-layout>
			</v-flex>
		</v-layout>
	</transition>
</template>
<script>
	import NextAppointments from '../partials/NextAppointments.vue';
	import HistorialAppointments from '../partials/HistorialAppointments.vue';

	import { mapState } from 'vuex';

	export default {
		data (){
			return {
				professional: '',
				active_element: 'professional'
			}
		},

		computed: {
			...mapState({
		    	baseUrl: state => state.common.baseUrl
		    })
		},

		created: function(){
			this.getProfessional();
			this.$emit('child_created', this.active_element);
		},

		methods: {
			getProfessional(){
				var t = this; 

				axios.get(t.baseUrl + '/professionals/detail/' + t.$route.params.id)
					.then(function (response) {
						if(!_.isEmpty(response.data)){

							t.professional = response.data;
						}
					})
					.catch(function (error) {						
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde', success: false, warning: false, danger: true, error:error});
					});
			}
		},

		components: {
            'nextappointments' : NextAppointments,
            'historialappointments' : HistorialAppointments
        }
	}
</script>