<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PWA Diagnostics</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- If styles are not working, rather use the cdn but beware not for production - see warning in inspect }}
    {{-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> --}}

    {{-- Throught trail and error I find @livewireStyles are not needed on this page but scripts are - probably because of Alpine.js }}
    {{-- @livewireStyles --}}

    @include('multi-tenancy-pwa::pwa.landing-page-styles')

    <link rel="manifest" href="/manifest.json">
</head>

<body>
    <div>

        <div class="content">
            <div>
                <br>
                <div class="text-3xl tracking-tight text-gray-900 sm:text-5xl">
                    <br>PWA Diagnostics
                </div>

                {{-- Auth will only work if the route is wrapped by web middleware --}}
                <div class="flex justify-center mt-2">
                    {{ Auth::user() ? 'Auth::user() is `' . Auth::user()->name . '`' : 'Auth::user() is null' }}
                </div>

                <div class="flex justify-center mt-2">
                    @include('multi-tenancy-pwa::pwa.browser-info')
                </div>

                <div class="flex justify-center mt-2">
                    @include('multi-tenancy-pwa::pwa.install-button')
                </div>

                <div class="flex justify-center mt-2">
                    @include('multi-tenancy-pwa::pwa.notification-buttons')
                </div>

                <div class="flex justify-center mt-2">
                    @include('multi-tenancy-pwa::pwa.online-status')
                </div>

                <div class="flex justify-center">
                    @include('multi-tenancy-pwa::pwa.battery-status')
                </div>

                <div class="flex justify-center">
                    @include('multi-tenancy-pwa::pwa.device-type')
                </div>

                <div class="flex justify-center mt-2">
                    Use Inspect, Console, in the browser to see additional diagnostics.
                </div>

                {{-- <div>
                    @include('multi-tenancy-pwa::pwa.pwa-icons')
                </div> --}}
            </div>
        </div>
    </div>
    @livewireScripts
</body>

</html>
