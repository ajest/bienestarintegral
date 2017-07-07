<template>
	<h1>Dashboard</h1>
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