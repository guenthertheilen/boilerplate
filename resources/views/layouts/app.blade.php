@inject('authorizer', 'App\Services\Authorizer')
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="columns">
    <div class="column is-8 is-offset-2">
        @include("layouts.header")
        @include("layouts.flash")
        @yield('content')
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
