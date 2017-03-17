<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">

        <title>BienestarIntegral</title>

        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    </head>
    <body>
        <nav class="navbar navbar-inverse">
            <div class="container">
                @yield('nav')
            </div>
        </nav>

        @yield('marketing')

        <div class="container">
          @yield('content')
          <footer>
            <p>&copy; 2017 Company, Inc.</p>
          </footer>
        </div> <!-- /container -->
    </body>
    <link rel="stylesheet" type="text/css" href="{{ asset('js/app.js') }}">
</html>