<template>
	<v-layout row wrap>
		<v-flex>
			<h1>Dashboard</h1>
				<h3>Pablo Fumarola</h3>
				<blockquote>Estoy muy contento con este sistema, porque es super sencillo de usar y me permite adminsitrar mi negocio de forma rápida, desde cualquier lugar y usando cualquier dispositivo moderno. Gracias Bienestar integral por hacer esto posible!</blockquote>
				<h3>Mariana Curio</h3>
				<blockquote>Nuestro centro de estética y masajes ahora está bien organizado. Mucho de lo que teniamos que hacer nosotros mismos, y que desde luego consumía tiempo, ahora lo hace por nosotros el sistema. Envia mensajes, mails, administra historial y organiza todo lo que necesitamos para que nuestra empresa siga creciendo.</blockquote>
				<h3>David Lebenfisz</h3>
				<blockquote>Debo reconocer que yo no estaba muy familiarizado con las nuevas herramientas que nos provee actualmente la web, por eso prefería administrar mi negocio con herramientas ya obsoletas o recurrir al papel y la birome. Pero Bienestar Integral me mostró que cuando un sistema está bien pensado, no debería ser dificil de utilizar. ¡Lo recomiendo!</blockquote>
				<h3>Yanina Vasquez</h3>
				<blockquote>Soy pedicura, deseo tener un negocio propio y, como estoy empezando, no podía invertir mucho dinero. Pero necesitaba soluciones. Me resultó grata la noticia de que Bienestar Integral me brindaba todo lo que yo necesitaba, con un precio super económico! Parece impensado pagar un monto tan bajo y recibir tal comodidad en un software de este calibre</blockquote>
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