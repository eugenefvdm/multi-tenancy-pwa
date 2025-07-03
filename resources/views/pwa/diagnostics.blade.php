<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PWA Raw Diagnostics</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

    @include('multi-tenancy-pwa::pwa.landing-page-styles')

    {{-- @include('pwa.manifest') --}}
</head>

<body>
    <div>

        <div class="content">
            <div>
                <br>
                <div class="text-3xl tracking-tight text-gray-900 sm:text-5xl">
                    <br>PWA Raw Diagnostics
                </div>

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
