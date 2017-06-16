<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Bienestar Integral</title>

        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    </head>
    <body>
        <div id="app">            
            <nav class="navbar navbar-inverse">
                <div class="container">
                    @yield('nav')
                </div>
            </nav>

            @yield('marketing')

            <div class="container">
              @yield('content')              
            </div> <!-- /container -->
            <footer class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12 bloque-footer">
                        <h3>Soporte</h3>
                        <p>Programador: <strong><a href="mailto:pablo.fumarola@gmail.com">Pablo Joel Fumarola</a></strong></p>
                        <p><a href="#">Preguntas Frecuentes</a></p>
                        <p><a href="#">Errores conocidos</a></p>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12 bloque-footer">
                        <h3>Tutoriales</h3>
                        <p><a href="#">Primeros pasos</a></p>
                        <p><a href="#">Conceptos avanzados</a></p>
                        <p><a href="#">Trucos</a></p>
                        <p><a href="#">Documentación</a></p>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12 bloque-footer">
                        <h3>Información útil</h3>
                        <p><a href="#">Historia del proyecto</a></p>
                        <p><a href="#">Proyección</a></p>
                        <p><a href="#">Alcance</a></p>
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 bloque-final">
                        <p>&copy; 2017 Company, Inc.</p>
                    </div>
                </div>
            </footer>
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