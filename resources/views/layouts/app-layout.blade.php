<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf_token" content="{{ csrf_token() }}">

        <link rel="shortcut icon" href="{{ asset('asset/img/icon.png') }}" />
        <title>@yield('pagina') | Torre de Control - Distribuciones AXA</title>

        <x-css></x-css>

    </head>
    <body class="sb-nav-fixed">

        @routes
        <x-header></x-header>

        <div id="layoutSidenav">
            <x-sidebar></x-sidebar>

            <div id="layoutSidenav_content" class="mt-5 mr-5">
                <main class="mb-5">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <x-js>
            {{$js}}
        </x-js>

    </body>
</html>
