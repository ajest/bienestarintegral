<template>
	<transition name="fade">
		<v-layout row wrap>
			<v-flex xs12 sm12 md12 lg12 v-if="treatment">
				<v-layout row wrap>
					<v-flex xs12 sm12 md8 lg9>
						<h1><i class="material-icons icon-h1">healing</i> {{ treatment.treatment.treatment }}</h1>
					</v-flex>
					<v-flex xs12 sm12 md4 lg3 class="mt-5">
						<v-btn class="pull-right pink" dark medium to="/treatments">
					    	<v-icon dark>date_range</v-icon>
						</v-btn>
						<v-btn class="pull-right" dark medium primary :to="{ name: 'treatments_edit', params: { id: treatment.treatment.id }}">
					    	<v-icon dark>edit</v-icon>
						</v-btn>
						<v-btn class="pull-right" light medium to="/treatments">
					    	<v-icon dark>chevron_left</v-icon>
						</v-btn>
					</v-flex>
					<v-flex xs12 sm12 md12 lg12>
						<v-layout row wrap>
							<v-flex xs12 sm12 md6 lg6>
								<h3>Información del tratamiento</h3>
								<v-expansion-panel expand>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Nombre</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ treatment.treatment.treatment }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Especialidad</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ treatment.specialty.specialty }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Descripción</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ treatment.treatment.description ? treatment.treatment.description : '---' }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								</v-expansion-panel>
							</v-flex>
							<v-flex xs12 sm12 md6 lg6 v-if="Object.keys(treatment.appointments).length">
								<nextappointments v-bind:appointments="treatment.appointments"></nextappointments>
							</v-flex>
							<v-flex xs12 sm12 md6 lg6 v-if="Object.keys(treatment.history).length">
								<historialappointments v-bind:appointments="treatment.history"></historialappointments>
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
				treatment: '',
				active_element: 'settings'
			}
		},

		computed: {
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

				axios.get(t.baseUrl + '/treatments/detail/' + t.$route.params.id)
					.then(function (response) {
						if(!_.isEmpty(response.data)){
							t.treatment = response.data;
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