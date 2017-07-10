<template>
	<!-- Modal Confirm -->
	<div class="modal fade" id="confirmDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Confirmar</h4>
	      </div>
	      <div class="modal-body">
	        {{ getMessage }} <strong v-if="from_el"> {{ from_el }} </strong> {{ second }} <strong v-if="for_el"> {{ for_el }} </strong> {{ third }} <strong v-if="emp"> {{ emp }}</strong>{{ final }}
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="deleteInDB()">Confirmar</button>
	      </div>
	    </div>
	  </div>
	</div>
</template>
<script>
	export default {
		data () {
			return {
				second: "",
				third: "",
				final: "",
				from_el: "",
				for_el: "",
				emp: ""
			}
		},

		computed: {
			getMessage: function () {
				var t = this;
				var element = t.delete_text_confirm[0] ? t.delete_text_confirm[0] : '¿Está seguro que desea eliminar este registro?';

				_.forEach(t.elements, function(value, i){
					if(value.id == t.element_id){
						t.from_el = t.elements[i].titulo;
						t.second = t.delete_text_confirm[2];
						t.for_el = t.elements[i].paciente;
						t.third = t.delete_text_confirm[4];
						t.emp = t.delete_text_confirm[5];
						t.final = t.delete_text_confirm[6];
					}
				});

				return element;
		    }
		},

		methods: {
			deleteInDB(){
				var t = this;

				axios.delete(t.url + t.element_id)					
					.then(function (response) {
						if(response.data.status){
							t.$emit('success');
						}
					})					
					.catch(function (error) {
						t.$emit('error');
					});				
			}
		},

		props: ['element_id', 'elements', 'url', 'delete_text_confirm']
	}
</script>