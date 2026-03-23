<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
    <footer class="bg-gray-900 text-white mt-20">
    <div class="max-w-6xl mx-auto px-6 py-10 grid md:grid-cols-3 gap-8">

        {{-- ABOUT --}}
        <div>
            <h2 class="text-xl font-bold mb-3">Portfolio</h2>
            <p class="text-gray-400 text-sm">
                Portfolio de desarrollo web. Aplicaciones modernas con Laravel y tecnologías actuales.
            </p>
        </div>

        {{-- NAV --}}
        <div>
            <h3 class="font-semibold mb-3">Navegación</h3>
            <ul class="space-y-2 text-gray-400 text-sm">
                <li><a href="/" class="hover:text-white">Inicio</a></li>
                <li><a href="/login" class="hover:text-white">Login</a></li>
            </ul>
        </div>

        {{-- CONTACT --}}
        <div>
            <h3 class="font-semibold mb-3">Contacto</h3>
            <ul class="space-y-2 text-gray-400 text-sm">
                <li>sp24es@gmail.com</li>
                <li><a href="https://github.com/SerhiiPoperechnyi" target="_blank" class="hover:text-white">GitHub</a></li>
                <li><a href="https://www.linkedin.com/in/serhii-poperechnyi-b786311b1/" target="_blank" class="hover:text-white">LinkedIn</a></li>
                <li><a href="https://wa.me/722642612" target="_blank" class="hover:text-white">WhatsApp</a></li>
                <li><a href="https://t.me/serg_1662" target="_blank" class="hover:text-white">Telegram</a></li>
              </ul>
        </div>

    </div>

    <div class="text-center text-gray-500 text-sm pb-6">
        © 2026 app-portfolio. Todos los derechos reservados.
    </div>
</footer>
</html>
