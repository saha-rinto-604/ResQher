<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name'))</title>

    @include('layout.partials.user.styles')
    @yield('styles')
</head>
<body>
<div id="layout-wrapper" class="half-expand">
    @include('layout.partials.user.navbar')
{{--    @include('layout.partials.sidebar')--}}

    <main class="main-content">
        <div class="page-content">
            @yield('contents')
        </div>

        @include('layout.partials.user.footer')
    </main>
</div>

@include('layout.partials.user.scripts')
@yield('scripts')
</body>
</html>
