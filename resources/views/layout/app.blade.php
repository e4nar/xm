<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" type="text/css" href="{{asset('css/app.css').getModifiedDate("css/app.css")}}" />

    @include('../parts/scripts')

</head>
<body>
    <div class="container">
        <div id="app">
            @yield('content')
        </div>
    </div>

    @yield('scripts')
    <script type="text/javascript" src="{{ asset('js/app.js').getModifiedDate("js/app.js") }}"></script>
</body>
</html>