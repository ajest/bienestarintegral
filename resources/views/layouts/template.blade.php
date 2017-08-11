<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Sistema de administración de negocios para relax y bienestar">
		<meta name="author" content="Pablo Fumarola">
		<meta name="robots" content="nofollow" />
		<title>Bienestar Integral</title>
		<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
		<link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet" type="text/css">
		<link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet" type="text/css">
	</head>
	<body>		
		<v-app id="app" standalone>
			@yield('content')
			<v-footer class="green">
				<span class="white--text">Bienestar integral © 2017</span>
		    </v-footer>
		</v-app>
	</body>
	<script>
		window.Laravel =  <?php echo json_encode([
			'csrfToken' => csrf_token(),
		]); ?>
	</script>
	<script src="{{ mix('js/manifest.js') }}"></script>
	<script src="{{ mix('js/vendor.js') }}"></script>
	<script src="{{ mix('js/app.js') }}"></script>
	@yield('assets_jquery')
</html>