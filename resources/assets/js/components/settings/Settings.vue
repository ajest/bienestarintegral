<template>
	<v-layout row wrap>
		<v-flex xs12 sm12 md12 lg12>
			<h2><i class="material-icons icon-h2">settings</i> Opciones de configuración</h2>
		</v-flex>
		<v-flex xs12 sm12 md12 lg12>
			<v-list two-line subheader>
				<v-subheader inset>Uso frecuente</v-subheader>
				<v-list-tile v-for="item in frequent" v-bind:key="item.title" v-bind:to="{ path: item.url }">
					<v-list-tile-avatar>
						<v-icon v-bind:class="[item.iconClass]">{{ item.icon }}</v-icon>
					</v-list-tile-avatar>
					<v-list-tile-content>
						<v-list-tile-title>{{ item.title }}</v-list-tile-title>
						<v-list-tile-sub-title>{{ item.subtitle }}</v-list-tile-sub-title>
					</v-list-tile-content>
				</v-list-tile>
				<v-divider inset></v-divider>
				<v-subheader inset>Modelo de negocio</v-subheader>
					<v-list-tile v-for="item in specialties" v-bind:key="item.title" v-bind:to="{ path: item.url }">
						<v-list-tile-avatar>
							<v-icon v-bind:class="[item.iconClass]">{{ item.icon }}</v-icon>
						</v-list-tile-avatar>
					<v-list-tile-content :to="[item.url]">
						<v-list-tile-title>{{ item.title }}</v-list-tile-title>
						<v-list-tile-sub-title>{{ item.subtitle }}</v-list-tile-sub-title>
					</v-list-tile-content>
				</v-list-tile>
				<v-divider inset></v-divider>
				<v-subheader inset>Avanzado</v-subheader>
					<v-list-tile v-for="item in advanced" v-bind:key="item.title" v-bind:to="{ path: item.url }">
						<v-list-tile-avatar>
							<v-icon v-bind:class="[item.iconClass]">{{ item.icon }}</v-icon>
						</v-list-tile-avatar>
					<v-list-tile-content :to="[item.url]">
						<v-list-tile-title>{{ item.title }}</v-list-tile-title>
						<v-list-tile-sub-title>{{ item.subtitle }}</v-list-tile-sub-title>
					</v-list-tile-content>
				</v-list-tile>
			</v-list>
		</v-flex>
	</v-layout>
</template>
<script>
	export default {
		data () {
			return {
				active_element: 'settings',
				frequent: [
		          { url: '/appointments', icon: 'assignment_ind', iconClass: 'blue lighten-1 white--text', title: 'Turnos', subtitle: 'Funciona sincronizado con el calendario' },
		          { url: '/patients', icon: 'face', iconClass: 'blue lighten-1 white--text', title: 'Pacientes', subtitle: 'Incluye historial y próximos turnos' },
		          { url: '/professionals', icon: 'school', iconClass: 'blue lighten-1 white--text', title: 'Profesionales', subtitle: 'Incluye historial y próximos turnos' },
		          { url: '/series', icon: 'card_giftcard', iconClass: 'blue lighten-1 white--text', title: 'Promociones', subtitle: 'Incluye historial y próximos turnos' }
		        ],
		        specialties: [
		          { url: '/specialties', icon: 'group_work', iconClass: 'green white--text', title: 'Especialidades', subtitle: 'Especialidades generales de su modelo de negocios' },
		          { url: '/treatments', icon: 'healing', iconClass: 'green white--text', title: 'Tratamientos', subtitle: 'Tratamientos o sub-especialidades' },
		          { url: '/questions', icon: 'question_answer', iconClass: 'green white--text', title: 'Preguntas', subtitle: 'Preguntas para sumar información relacionada con los pacientes ó clientes' }
		        ],
		        advanced: [
		          { url: '/', icon: 'lock', iconClass: 'deep-orange darken-4 white--text', title: 'Autorización', subtitle: 'Determina qué profesionales pueden acceder al sistema y determina sus reglas de acceso' },
		          { url: '/', icon: 'send', iconClass: 'deep-orange darken-4 white--text', title: 'Envíos', subtitle: 'Envíos automáticos, historial de envios' },
		        ]
			}
		},
		
		created: function(){
			this.$emit('child_created', this.active_element);
		},

        props: ['message', 'success', 'warning', 'danger']
	}
</script>