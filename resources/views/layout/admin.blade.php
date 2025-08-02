<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name'))</title>

    @include('layout.partials.admin.styles')
    @yield('styles')
</head>
<body>
<div id="layout-wrapper">
    @include('layout.partials.admin.navbar')
    @include('layout.partials.admin.sidebar')

    <main class="main-content">
        <div class="page-content">
            @yield('contents')
        </div>

        @include('layout.partials.admin.footer')
    </main>
</div>

@include('layout.partials.admin.scripts')
@yield('scripts')
</body>
</html>
