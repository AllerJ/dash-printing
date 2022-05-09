<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('title')</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    
    <link rel="manifest" href="/manifest.json?timed=37" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="application-name" content="Print Pricer" />
    <meta name="apple-mobile-web-app-title" content="Print Pricer" />
    <meta name="msapplication-starturl" content="/login" />
    <link rel="apple-touch-icon" href="/icon-384x384.png">
    
    
    <link href="https://printing.dashcg.com/iphone12.jpg" rel="apple-touch-startup-image" />
    
    
    <link rel="icon" type="image/png" sizes="32x32" href="https://printing.dashcg.com/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="https://printing.dashcg.com/favicon-72x72.png">

    <!-- Styles -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">

    <link href="{{ route('lit.css') }}" rel="stylesheet"/>

    @foreach(lit_app()->getStyles() as $path)
        <link href="{{ $path }}{{ asset_time() }}" rel="stylesheet">
    @endforeach

    @include('litstack::partials.google_analytics')
</head>

<body class="overflow-hidden">
     @yield('content')

    <script src="{{ Lit::route('app2.js') }}" defer></script>
</body>
</html>
