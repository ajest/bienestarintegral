<template>
	<transition name="fade">
		<v-layout row wrap>
			<v-flex xs12 sm12 md12 lg12 v-if="patient">
				<v-layout row wrap>
					<v-flex xs12 sm12 md8 lg9>
						<h2><i class="material-icons icon-h2">face</i> {{ patient.patient.name }}</h2>
					</v-flex>
					<v-flex xs12 sm12 md4 lg3 class="mt-5">
						<v-btn class="pull-right pink" dark medium to="/patients">
					    	<v-icon dark>date_range</v-icon>
						</v-btn>
						<v-btn class="pull-right" dark medium primary :to="{ name: 'patients_edit', params: { id:  patient.patient.id }}">
					    	<v-icon dark>edit</v-icon>
						</v-btn>
						<v-btn class="pull-right" light medium to="/patients">
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
											<v-card-text class="green lighten-4">{{ patient.patient.name }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Email</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ patient.patient.email }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Teléfonos</div>
										<v-card>
											<v-card-text class="green lighten-4">
												<div>Casa: {{ patient.patient.tel }}</div>
												<div>Celular: {{ patient.patient.cellphone }}</div>
											</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Domicilio</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ patient.patient.address }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Género</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ patient.patient.gender == '1' ? 'Hombre' : 'Mujer'  }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">DNI</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ patient.patient.dni }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Estado Civil</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ patient.patient.civil_state ? 'Casado' : 'Soltero' }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Año de nacimiento</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ patient.patient.birthdate }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Localidad</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ patient.patient.area }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Comentarios</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ patient.patient.comments }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								</v-expansion-panel>
							</v-flex>
							<v-flex xs12 sm12 md6 lg6 v-if="Object.keys(patient.appointments).length">
								<nextappointments v-bind:appointments="patient.appointments"></nextappointments>
							</v-flex>
							<v-flex xs12 sm12 md6 lg6 v-if="Object.keys(patient.history).length">
								<historialappointments v-bind:appointments="patient.history"></historialappointments>
							</v-flex>
							<v-flex xs12 sm12 md12 lg12 v-if="Object.keys(patient.specialties).length">
								<h3>Ficha paciente</h3>
								<v-tabs class="elevation-1">
									<v-tabs-bar slot="activators" class="green lighten-2">
										<v-tabs-slider class="pink"></v-tabs-slider>
										<v-tabs-item v-for="specialty in patient.specialties" :key="specialty[0].specialty_id" :href="'#tab-' + specialty[0].specialty_id">
											{{ specialty[0].specialty ? specialty[0].specialty.specialty : 'Generales' }}
										</v-tabs-item>
									</v-tabs-bar>
									<v-tabs-content v-for="specialty in patient.specialties" :id="'tab-' + specialty[0].specialty_id">
										<v-list>
										  <template v-for="question in specialty">
									          <v-list-tile>
									            <v-list-tile-content>
									              <v-list-tile-sub-title>{{ question.question }}</v-list-tile-sub-title>
									              <v-list-tile-title>{{ questions[question.id] ? questions[question.id] : '---' }}</v-list-tile-title>
									            </v-list-tile-content>
									          </v-list-tile>
									          <v-divider></v-divider>
										  </template>
								        </v-list>
									</v-tabs-content>
								</v-tabs>
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
				patient: '',
				active_element: 'patient',
				questions: {}
			}
		},

		computed: {
			...mapState({
		    	baseUrl: state => state.common.baseUrl
		    })
		},

		created: function(){
			this.getPatient();
			this.$emit('child_created', this.active_element);
		},

		methods: {
			getPatient(){
				var t = this; 

				axios.get(t.baseUrl + '/patients/detail/' + t.$route.params.id)
					.then(function (response) {
						if(!_.isEmpty(response.data)){
							if(!_.isEmpty(response.data.specialties)){
								_.forEach(response.data.specialties, function(specialty) {
									_.forEach(specialty, function(question) {
										var id = question.id;
										let answer = _.find(response.data.answers, function(o) { return o.question_id == id; });
										t.questions[id] = answer && !_.isUndefined(answer) ? answer.answer : '';
									});	
								});
							}

							t.patient = response.data;
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