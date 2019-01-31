<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.App = {!! json_encode([
        'user' => Auth::user(),
        'signedId'  => Auth::check()
        ]) !!};
    </script>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

    <style>
        body {
            padding-bottom: 100px;
        }
        .py-4 {
            margin-top: 30px;
        }
        .level {
            display:flex;
            align-items:center;
        }
        .flex{
            flex:1;
        }
        .page-header {
            padding-bottom: 9px;
            margin: 40px 0 20px;
            border-bottom: 1px solid #eee;
        }
        [v-cloak] {display:none}
    </style>
</head>
<body>
    <div id="app">
        @include('layouts.nav')

        <main class="py-4">
            @yield('content')
        </main>

        <flash message="{{ session('flash') }}"></flash>
        {{--<flash message="Temporary message"></flash>--}}
    </div>
</body>
</html>
