<template>
	<transition name="fade">
		<v-layout row wrap>
			<v-flex xs12 sm12 md12 lg12 v-if="appointment">
				<v-layout row wrap>
					<v-flex xs12 sm12 md8 lg9>
						<h1><i class="material-icons icon-h1">assignment_ind</i> {{ appointment.title }}</h1>
					</v-flex>
					<v-flex xs12 sm12 md4 lg3 class="mt-5">
						<v-btn class="pull-right pink" dark medium to="/appointments">
					    	<v-icon dark>date_range</v-icon>
						</v-btn>
						<v-btn class="pull-right" dark medium primary :to="{ name: 'appointments_edit', params: { id: appointment.id }}">
					    	<v-icon dark>edit</v-icon>
						</v-btn>
						<v-btn class="pull-right" light medium to="/appointments">
					    	<v-icon dark>chevron_left</v-icon>
						</v-btn>
					</v-flex>
					<v-flex xs12 sm12 md12 lg12>
						<v-layout row wrap>
							<v-flex xs12 sm12 md6 lg6>
								<h3>Información del turno</h3>
								<v-expansion-panel expand>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Título</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ appointment.title ? appointment.title : '- Sin título -' }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Fecha y hora</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ appointment.date }} {{ appointment.hour }}hs</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Paciente</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ appointment.patient.name }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								</v-expansion-panel>
							</v-flex>
							<v-flex xs12 sm12 md6 lg6>
								<h3>Tratamiento</h3>
								<v-expansion-panel expand>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Profesional</div>
										<v-card>
											<v-card-text class="pink lighten-4">
												<v-chip class="pink white--text">{{ appointment.professional.name }}<v-icon right>school</v-icon></v-chip>
											</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Area / Especialidad</div>
										<v-card>
											<v-card-text class="pink lighten-4">
												<v-chip class="green white--text">{{ appointment.specialty.specialty }}<v-icon right>group_work</v-icon></v-chip>
											</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Tratamiento</div>
										<v-card>
											<v-card-text class="pink lighten-4">
												<v-chip class="deep-orange white--text">{{ appointment.treatment.treatment }}<v-icon right>healing</v-icon></v-chip>
											</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Promoción</div>
										<v-card>
											<v-card-text class="pink lighten-4">
												<v-chip class="purple white--text">{{ appointment.series.series ? appointment.series.series : '---' }}<v-icon right>card_giftcard</v-icon></v-chip>
											</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								</v-expansion-panel>
							</v-flex>
							<v-flex xs12 sm12 md12 lg12>
								<h3>Comentarios</h3>
								<p>{{ appointment.comments }}</p>
							</v-flex>
						</v-layout>
					</v-flex>
				</v-layout>
			</v-flex>			
		</v-layout>
	</transition>
</template>
<script>
	import { mapState } from 'vuex';

	export default {
		data (){
			return {
				appointment: '',
				active_element: 'appointment'
			}
		},

		computed: {
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

				axios.get(t.baseUrl + '/appointments/detail/' + t.$route.params.id)
					.then(function (response) {
						if(!_.isEmpty(response.data.appointment)){
							t.appointment = response.data.appointment;
						}
					})
					.catch(function (error) {
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde', success: false, warning: false, danger: true, error:error});
					});
			}
		}
	}
</script>