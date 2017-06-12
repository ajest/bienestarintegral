<template>
	<div class="row">
		<div class="row col-md-12">
			<h1>Directorio pacientes <small><a href="#" class="font-size-14">Nuevo paciente [+]</a></small></h1>		
		</div>
		<div class="row col-md-12">
			<table class="table table-striped table-hover table-head-strong">
				<thead>
					<tr>
						<td>Nombre</td>
						<td>Email</td>
						<td>Teléfono</td>
						<td>Sexo</td>
						<td>Dirección</td>
						<td>Fecha alta</td>
						<td>Acciones</td>
					</tr>
				</thead>
				<transition-group name="list" tag="tbody">
					<tr v-for="patient in patients" v-bind:key="patients" class="list-item">
						<td>{{ patient.nombre }}</td>
						<td>{{ patient.email }}</td>
						<td>{{ patient.telefono }}</td>
						<td>{{ patient.sexo }}</td>
						<td>{{ patient.direccion }}</td>
						<td>{{ patient.fecha }}</td>
						<td>
							<a class="btn btn-success margin-list-button" :href="'/patients/' + patient.id" title="Ver Turno"><span class="glyphicon glyphicon-eye-open"></span></a>
							<a class="btn btn-primary margin-list-button" :href="'/patients/' + patient.id + '/edit'" title="Editar paciente"><span class="glyphicon glyphicon-pencil"></span></a>
							<a class="btn btn-danger margin-list-button" href="#" data-toggle="modal" data-target="#confirmDelete" title="Eliminar registro paciente" @click="confirmDelete(patient.id)"><span class="glyphicon glyphicon-remove"></span></a>
						</td>
					</tr>
				</transition-group>
			</table>
		</div>
		<pagination v-bind:last_page="last_page" v-bind:current_page="current_page" v-bind:url="url"></pagination>
		<popupdeleteconfirm v-on:success="operationSuccess()" v-bind:element_id="patient_id" v-bind:elements="patients" v-bind:url="url" v-bind:delete_text_confirm="delete_text_confirm"></popupdeleteconfirm>
	</div>	
</template>
<script>
	import Pagination from './partials/Pagination.vue';
	import PopupDeleteConfirm from './popups/PopupDeleteConfirm.vue';

	export default {
		data () {
			return {
				patients: [],
				last_page: 0,
				current_page: 1,
				patient_id: 0,
				url: '/patients/',
				delete_text_confirm: ['El registro del paciente y su historial de tratamientos ', null, null, null, 'van a ser eliminado. ¿Está seguro que desea eliminar el registro del paciente ', ' permanentemente', '?']
			}
		},
		
		created: function(){
			this.paginationCallback();
		},
		
		methods: {
			paginationCallback(){
				var t = this;

				axios.get('/patients/list/' + (t.$route.params.id ? t.$route.params.id : 1))
					.then(function (response) {
						if(!_.isEmpty(response.data.patients.data)){

							t.patients = [];

							_.forEach(response.data.patients.data, function(value) {
								t.patients.push({
										'id' : value.id,
										'nombre': value.name,
										'email': value.email,
										'telefono': value.tel,
										'sexo': value.gender,
										'direccion': value.address,
										'fecha': value.created_at
									});

								t.last_page 	= response.data.patients.last_page;
								t.current_page  = response.data.patients.current_page;
							});
						}
					})
					.catch(function (error) {
						console.log('Estamos teniendo problemas al resolver su solicitud. Por favor reintente más tarde');
					});
			},
			confirmDelete(id){
				this.patient_id = id;
			},

			operationSuccess(){
				var t = this;

				_.forEach(t.patients, function(value, i){
					if(value.id == t.patient_id){
						t.patients = t.patients.filter(function (item) {
						    return t.patient_id != item.id;
						});
					}
				});
			}
		},

		watch: {
		    '$route' (to, from) {
			    this.paginationCallback();
		    }
		},

		components: {
            'pagination' : Pagination,
            'popupdeleteconfirm' : PopupDeleteConfirm
        },

        props: ['message', 'success', 'warning', 'danger']
	}
</script>