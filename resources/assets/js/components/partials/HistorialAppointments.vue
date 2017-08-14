<template>
	<div>
		<h3>Historial</h3>
		<v-expansion-panel expand>
		    <v-expansion-panel-content v-bind:value="true" v-for="history in appointments">
				<div slot="header">{{ history.date }} {{ history.hour }}hs</div>
				<v-card>
					<v-card-text class="blue-grey lighten-4">
						<div>
							Para <v-chip class="deep-orange white--text">{{ history.treatment.treatment }}<v-icon right>healing</v-icon></v-chip> con <v-chip v-if="checkHasProfessional(history)" class="pink white--text">{{ history.professional.name }}<v-icon right>school</v-icon></v-chip><v-chip v-if="checkHasPatient(history)" class="blue white--text">{{ history.patient.name }}<v-icon right>face</v-icon></v-chip>
							<v-btn class="pull-right" dark medium primary :to="{ name: 'appointments_detail', params: { id: history.id }}" title="Ver Turno">
						    	<v-icon dark>assignment_ind</v-icon>
							</v-btn>
							<div class="clearfix"></div>
						</div>
						<div><strong v-if="history.comments">Comentarios: </strong> {{ history.comments }} </div>
					</v-card-text>
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
			checkHasProfessional(history){
				if(_.isUndefined(history.professional)){
					return false;	
				}else{
					return true;
				}
			},
			checkHasPatient(history){
				if(_.isUndefined(history.patient)){
					return false;	
				}else{
					return true;
				}
			}
		},
		
		props: ['appointments']
	}
</script>