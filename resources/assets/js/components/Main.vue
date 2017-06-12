<template>
	<div>
		<router-view 
			v-bind:message="message"
			v-bind:success="success"
			v-bind:warning="warning"
			v-bind:danger="danger"
			v-on:complete="operationComplete()"></router-view>
		
		<div v-show="message">
			<div :class="classObjectContainer">
				<span><i class="glyphicon" :class="classObjectIcon"></i> {{ message }}</span>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		data () {
			return {
				message: '',
				success: false,
				warning: false,
				danger: true
			}
		},
		
		computed: {
			classObjectContainer: function () {
				return {
					'global-message': this.success,
					'global-message-warning': this.warning,
					'global-message-danger': this.danger
				}
			},
			classObjectIcon: function () {
				return {
					'glyphicon-ok': this.success,
					'glyphicon-warning-sign': this.warning,
					'glyphicon-remove': this.danger
				}
			}
		},

		methods: {
			operationComplete(){
				var t = this;

				t.message = 'La operacion se realizó correctamente';
				
				setTimeout(function(){
					t.message = '';
					t.success = true;
					t.warning = false;
					t.danger  = false;
				}, 8000);
			}
		}
	}
</script>