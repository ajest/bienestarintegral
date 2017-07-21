<template>
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<router-link class="navbar-brand" to="/"><strong>Bienestar Integral</strong></router-link>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
				<router-link class="navbar-brand" to="/appointments"><span class="glyphicon glyphicon-briefcase"></span></router-link>
				<router-link class="navbar-brand" to="/patients"><span class="glyphicon glyphicon-list-alt"></span></router-link>
			</ul>
			<!--<form class="navbar-form navbar-right">
				<input type="text" class="form-control" placeholder="Buscar...">
			</form>-->
		</div>
	</div>
</template>