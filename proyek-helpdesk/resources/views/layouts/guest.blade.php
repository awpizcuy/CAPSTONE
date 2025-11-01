<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Helpdesk - PT. Lemigas</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-indigo-50 via-white to-cyan-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800 p-6">
            <div class="mb-6 text-center">
                <a href="/">
                    <x-application-logo class="w-16 h-16 mx-auto fill-current text-indigo-600 dark:text-indigo-400" />
                </a>
                <h1 class="mt-4 text-2xl font-semibold text-gray-900 dark:text-white">Helpdesk LEMIGAS</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Silakan masuk untuk melanjutkan</p>
            </div>

            <div class="w-full sm:max-w-md bg-white/90 dark:bg-gray-800/90 backdrop-blur shadow-lg sm:rounded-2xl p-6">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
