<template>
	<div>
		<router-view 
			v-bind:message="message"
			v-bind:success="success"
			v-bind:warning="warning"
			v-bind:danger="danger"
			v-on:complete="operationComplete"></router-view>
		
		<transition name="fade">			
			<div v-if="message">
				<div class="g-msg" :class="classObjectContainer">
					<span><i class="glyphicon" :class="classObjectIcon"></i> {{ message }}</span>
				</div>
			</div>
		</transition>
	</div>
</template>
<script>
	export default {
		data () {
			return {
				message: '',
				success: true,
				warning: false,
				danger: false
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
			operationComplete(e){
				var t = this;

				t.message = e.message;
				t.success = e.success;
				t.warning = e.warning;
				t.danger  = e.danger;
				
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