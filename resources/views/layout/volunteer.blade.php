<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name')) - {{ config('app.name') }}</title>

    @include('layout.partials.volunteer.styles')
    @yield('styles')
</head>
<body>
<div id="layout-wrapper" class="half-expand">
    @include('layout.partials.volunteer.navbar')

    <main class="main-content">
        <div class="page-content">
            @yield('contents')
        </div>

        @include('layout.partials.volunteer.footer')
    </main>
</div>

@include('layout.partials.volunteer.scripts')
@yield('scripts')

<script>
    $(document).ready(function () {
        $(document).on('change', 'input.volunteer-availability[name=availability]', function () {
            $.ajax({
                url: "{{ route('volunteer.availability') }}",
                type: 'GET',
                success: function (response) {
                    console.log(response)
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
</body>
</html>
