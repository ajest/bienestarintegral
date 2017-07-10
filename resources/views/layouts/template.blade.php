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
	</head>
	<body>
		<div id="app">            
			<nav class="navbar navbar-inverse navbar-fixed-top">				
				@yield('nav')
			</nav>
			<div class="container-fluid">
			  @yield('content')
			  <footer>
			  	<div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 bloque-final">
					<p>&copy; <?php echo date('Y'); ?> Bienestar Integral</p>
				</div>
			  </footer>       
			</div> <!-- /container -->
		</div>
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