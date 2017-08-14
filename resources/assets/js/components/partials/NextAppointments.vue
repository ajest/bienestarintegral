<template>
	<div>
		<h3>Próximos Turnos</h3>
		<v-expansion-panel expand>
		    <v-expansion-panel-content v-bind:value="true" v-for="appointment in appointments">
				<div slot="header">{{ appointment.date }} {{ appointment.hour }}hs</div>
				<v-card>
					<v-card-text class="teal lighten-4">Para <v-chip v-if="checkHasTreatment(appointment)" class="deep-orange white--text">{{ appointment.treatment.treatment }}<v-icon right>healing</v-icon></v-chip> con <v-chip v-if="checkHasProfessional(appointment)" class="pink white--text">{{ appointment.professional.name }}<v-icon right>school</v-icon></v-chip><v-chip v-if="checkHasPatient(appointment)" class="blue white--text">{{ appointment.patient.name }}<v-icon right>face</v-icon></v-chip></v-card-text>
				</v-card>
		    </v-expansion-panel-content>
		</v-expansion-panel>
	</div>
</template>
<script>
	export default {
		data () {
			return {}
		},

		methods: {
			checkHasProfessional(appointment){
				if(_.isUndefined(appointment.professional)){
					return false;	
				}else{
					return true;
				}
			},
			checkHasPatient(appointment){
				if(_.isUndefined(appointment.patient)){
					return false;	
				}else{
					return true;
				}
			},
			checkHasTreatment(appointment){
				if(_.isUndefined(appointment.treatment)){
					return false;	
				}else{
					return true;
				}
			}
		},
		
		props: ['appointments']
	}
</script>