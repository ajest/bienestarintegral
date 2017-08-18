<template>
	<transition name="fade">
		<v-layout row wrap>
			<v-flex xs12 sm12 md12 lg12 v-if="specialty">
				<v-layout row wrap>
					<v-flex xs12 sm12 md8 lg9>
						<h2><i class="material-icons icon-h2">group_work</i> {{ specialty.specialty }}</h2>
					</v-flex>
					<v-flex xs12 sm12 md4 lg3 class="mt-5">
						<v-btn class="pull-right pink" dark medium to="/specialties">
					    	<v-icon dark>date_range</v-icon>
						</v-btn>
						<v-btn class="pull-right" dark medium primary :to="{ name: 'specialties_edit', params: { id: specialty.id }}">
					    	<v-icon dark>edit</v-icon>
						</v-btn>
						<v-btn class="pull-right" light medium to="/specialties">
					    	<v-icon dark>chevron_left</v-icon>
						</v-btn>
					</v-flex>
					<v-flex xs12 sm12 md12 lg12>
						<v-layout row wrap>
							<v-flex xs12 sm12 md12 lg12>
								<h3>Información del turno</h3>
								<v-expansion-panel expand>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Nombre</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ specialty.specialty }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								    <v-expansion-panel-content v-bind:value="true">
										<div slot="header">Descripción</div>
										<v-card>
											<v-card-text class="green lighten-4">{{ specialty.description ? specialty.description : '---' }}</v-card-text>
										</v-card>
								    </v-expansion-panel-content>
								</v-expansion-panel>
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
				specialty: '',
				active_element: 'settings'
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

				axios.get(t.baseUrl + '/specialties/detail/' + t.$route.params.id)
					.then(function (response) {
						if(!_.isEmpty(response.data.specialty)){
							t.specialty = response.data.specialty;
						}
					})
					.catch(function (error) {
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde', success: false, warning: false, danger: true, error:error});
					});
			}
		}
	}
</script>