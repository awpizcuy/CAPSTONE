<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Kepala IT - Ringkasan') }}
        </h2>
    </x-slot>

    <div class="space-y-6">

        {{-- Bagian Kartu Statistik --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-shadow hover:shadow-md">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Laporan</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalReports }}</p>
                        </div>
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900/20 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-shadow hover:shadow-md">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Laporan Pending</p>
                            <p class="mt-2 text-3xl font-bold text-red-600 dark:text-red-400">{{ $pendingReports }}</p>
                        </div>
                        <div class="p-3 bg-red-100 dark:bg-red-900/20 rounded-lg">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-shadow hover:shadow-md">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-rata Waktu Selesai</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $averageCompletionTime ?? 'N/A' }}</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bagian Utama: Filter dan Tabel Laporan --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Semua Laporan Masuk</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola dan monitor semua laporan yang masuk</p>
                    </div>
                </div>

                @if (session('success'))
                    <x-flash-message type="success">{{ session('success') }}</x-flash-message>
                @endif
                @if (session('error'))
                    <x-flash-message type="error">{{ session('error') }}</x-flash-message>
                @endif

                {{-- Formulir Filter & Pencarian --}}
                <form method="GET" action="{{ route('kepala.dashboard') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg mb-4">
                        {{-- Filter Status --}}
                        <select name="filter_status" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Filter Status</option>
                            @foreach(['pending', 'accepted', 'on_process', 'completed', 'rated', 'rejected', 'hold'] as $status)
                                <option value="{{ $status }}" {{ request('filter_status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Filter Kategori --}}
                        <select name="filter_category" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Filter Kategori</option>
                            @foreach(['kerusakan', 'peminjaman', 'instalasi'] as $kategori)
                                <option value="{{ $kategori }}" {{ request('filter_category') == $kategori ? 'selected' : '' }}>
                                    {{ ucfirst($kategori) }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Input Pencarian --}}
                        <x-text-input type="text" name="search" placeholder="Cari pelapor, deskripsi..." class="rounded-lg text-sm" :value="$searchTerm ?? ''" />
                        
                        {{-- Tombol Aksi --}}
                        <div class="flex items-center gap-3">
                            <x-primary-button type="submit" class="flex-1">Filter/Cari</x-primary-button>
                            <a href="{{ route('kepala.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                Reset
                            </a>
                        </div>
                    </div>

                    {{-- Filter Tanggal (Dipisah) --}}
                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg mb-6">
                        <label for="filter_date" class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
                            Filter Tanggal Pengajuan:
                        </label>
                        <input type="date" id="filter_date" name="date_from" value="{{ request('date_from') ?? ($dateFrom ?? '') }}" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>
                </form>

                {{-- Tabel Laporan --}}
                <div class="overflow-x-auto -mx-6 md:mx-0">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pelapor</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($reports as $report)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $report->nama_pelapor }}</div>
                                    @if($report->reporter)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $report->reporter->email }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                                        {{ ucfirst($report->kategori) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($report->tanggal_pengajuan)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-status-badge :status="$report->status" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('kepala.report.manage', $report) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada laporan yang sesuai dengan filter atau pencarian Anda.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                @if($reports->hasPages())
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    {{ $reports->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

</x-app-layout>
