<template>
	<v-layout row wrap>
		<v-flex>
			<h2><i class="material-icons icon-h2">dashboard</i> Dashboard</h2>
			<h3>Versión actual: Alpha 1.0</h3>
			<p>Si encuentra algun error (bug <i class="material-icons">bug_report</i>), puede comunicarse con el <a href="mailto:pablo.fumarola@gmail.com">administrador</a>. Muchas gracias</p>
			<hr>
			<h4>Características que serán añadidas en la próxima versión</h4>
			<ul>
				<li>Navegación mejorada entre secciones</li>
				<li>Envios automáticos de emails para turnos</li>
			</ul>
			<hr>
			<h4>Características que serán añadidas en el futuro</h4>
			<ul>
				<li>Calendario interactivo</li>
				<li>Reportes</li>
				<li>Control de autorización para profesionales</li>
			</ul>
		</v-flex>
	</v-layout>
</template>
<script>
	export default {
		data () {
			return {
				active_element: 'calendar'
			}
		},
		
		created: function(){
			this.$emit('child_created', this.active_element);
		}
	}
</script>