<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Helpdesk - PT. Lemigas</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .hero-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url("{{ asset('images/assets.png') }}");
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="antialiased bg-gray-100 dark:bg-gray-900">
    <div class="relative min-h-screen">
        <!-- Hero Section -->
        <div class="relative hero-bg min-h-screen flex flex-col items-center justify-center text-white">
            <div class="absolute top-0 left-0 p-6">
                <img src="{{ asset('images/lemigas.png') }}" alt="Logo Lemigas" class="h-12 w-auto">
            </div>
            <div class="absolute top-0 right-0 p-6">
                @auth
                    <a href="{{ url('/dashboard') }}" class="font-semibold text-white hover:text-gray-200">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="font-semibold text-white hover:text-gray-200">Log in</a>
                @endauth
            </div>

            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-bold tracking-tight">Sistem Helpdesk IT</h1>
                <p class="mt-4 text-lg md:text-xl text-gray-200 max-w-2xl mx-auto">
                    Solusi terintegrasi untuk melaporkan dan mengelola semua kebutuhan teknis di lingkungan PT. Lemigas.
                </p>
                <div class="mt-8">
                    <a href="{{ route('login') }}" class="inline-block bg-primary text-white font-bold text-lg px-8 py-3 rounded-lg hover:bg-primary-600 transition duration-300">
                        Masuk ke Sistem
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="bg-gray-100 dark:bg-gray-900 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Statistik Laporan</h2>
                    <p class="mt-2 text-md text-gray-600 dark:text-gray-400">Ringkasan status laporan saat ini di seluruh sistem.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Total Laporan -->
                    <div class="bg-primary-50 dark:bg-primary-900/50 p-6 rounded-xl shadow-md text-center">
                        <div class="p-3 inline-block bg-primary-100 dark:bg-primary-800/50 rounded-lg mb-4">
                            <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <p class="text-4xl font-bold text-primary-600 dark:text-primary-300">{{ $totalReports }}</p>
                        <p class="mt-1 text-sm font-medium text-primary-600 dark:text-primary-400">Total Laporan</p>
                    </div>
                    <!-- Laporan Pending -->
                    <div class="bg-orange-50 dark:bg-orange-900/50 p-6 rounded-xl shadow-md text-center">
                        <div class="p-3 inline-block bg-orange-100 dark:bg-orange-800/50 rounded-lg mb-4">
                            <svg class="w-8 h-8 text-orange-500 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-4xl font-bold text-orange-500 dark:text-orange-300">{{ $pendingReports }}</p>
                        <p class="mt-1 text-sm font-medium text-orange-600 dark:text-orange-400">Laporan Pending</p>
                    </div>
                    <!-- Laporan Selesai -->
                    <div class="bg-green-50 dark:bg-green-900/50 p-6 rounded-xl shadow-md text-center">
                        <div class="p-3 inline-block bg-green-100 dark:bg-green-800/50 rounded-lg mb-4">
                           <svg class="w-8 h-8 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-4xl font-bold text-green-500 dark:text-green-300">{{ $completedReports }}</p>
                        <p class="mt-1 text-sm font-medium text-green-600 dark:text-green-400">Laporan Selesai</p>
                    </div>
                    <!-- Laporan Ditolak -->
                    <div class="bg-red-50 dark:bg-red-900/50 p-6 rounded-xl shadow-md text-center">
                        <div class="p-3 inline-block bg-red-100 dark:bg-red-800/50 rounded-lg mb-4">
                            <svg class="w-8 h-8 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        </div>
                        <p class="text-4xl font-bold text-red-500 dark:text-red-300">{{ $rejectedReports }}</p>
                        <p class="mt-1 text-sm font-medium text-red-600 dark:text-red-400">Laporan Ditolak</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
