<template>
	<transition name="fade">
		<div class="row col-md-12 section-detail" v-if="specialty">
			<h1><span class="glyphicon glyphicon-briefcase"></span> {{ specialty.specialty }} 
			<router-link to="/specialties" class="btn btn-default pull-right margin-left-small"><span class="glyphicon glyphicon-calendar"></span> </router-link>
			<router-link :to="{ name: 'specialties_edit', params: { id: specialty.id }}" class="btn btn-primary pull-right margin-left-small"><span class="glyphicon glyphicon-pencil"></span> Editar</router-link>
			<router-link to="/specialties" class="btn btn-success pull-right margin-left-small"><span class="glyphicon glyphicon-arrow-left"></span> Listado</router-link></h1>
			<hr />
			<div class="col-md-12">
				<div class="col-md-6">
					<table class="table table-hovered">
						<thead>
							<tr>
								<td span="2">
									<h3>Información del turno</h3>
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Name</td>
								<td><span class="label label-default">{{ specialty.specialty }}</span></td>
							</tr>
							<tr>
								<td>Description</td>
								<td>{{ specialty.description }}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
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
						t.$emit('complete', {message:  'Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde', success: false, warning: false, danger: true});
					});
			}
		}
	}
</script>