<template>
	<nav aria-label="Page navigation">
		<ul class="pagination">
			<li :class="[ current_page == 1 ? 'disabled' : '' ]">
				<a @click="getPreviousPage()" href="javascript:;" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
				</a>
			</li>
			
			<li v-for="page in last_page" :class="[ page == current_page ? 'active' : '' ]">
				<router-link :to="url + page"> {{ page }} </router-link>
			</li>
			
			<li :class="[ current_page == last_page ? 'disabled' : '' ]">
				<a @click="getNextPage()" href="javascript:;" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
				</a>
			</li>
		</ul>
	</nav>
</template>
<script>
	export default {
		data () {
			return {
				
			}
		},

		methods: {
			getPreviousPage: function(){
				var previous_page = this.current_page - 1;
				this.$router.push({ path: this.url + (previous_page < 1 ? 1 : previous_page) });
			},

			getNextPage: function(){
				var next_page = this.current_page + 1;
				this.$router.push({ path: this.url + (next_page > this.last_page ? this.last_page : next_page) });
			}
		},
		
		props: ['current_page', 'last_page', 'url']
	}
</script>