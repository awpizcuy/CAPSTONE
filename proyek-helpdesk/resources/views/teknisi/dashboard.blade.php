<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Teknisi - Daftar Tugas') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 md:p-8">
                @if (session('success'))
                    <x-flash-message type="success">{{ session('success') }}</x-flash-message>
                @endif
                @if (session('error'))
                    <x-flash-message type="error">{{ session('error') }}</x-flash-message>
                @endif

                {{-- Filter tanggal pengajuan --}}
                <form method="GET" action="{{ route('teknisi.dashboard') }}" class="mb-6">
                    <div class="flex flex-wrap items-center gap-3 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <input type="date" name="date_from" value="{{ request('date_from') ?? ($dateFrom ?? '') }}" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        <span class="text-gray-500 dark:text-gray-400 text-sm font-medium">s.d.</span>
                        <input type="date" name="date_to" value="{{ request('date_to') ?? ($dateTo ?? '') }}" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        <x-primary-button type="submit">Terapkan</x-primary-button>
                        <a href="{{ route('teknisi.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            Reset
                        </a>
                    </div>
                </form>

                {{-- BAGIAN 1: TUGAS AKTIF --}}
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tugas Aktif</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tugas yang sedang Anda kerjakan</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto -mx-6 md:mx-0">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pelapor</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($tasksActive as $task)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $task->nama_pelapor }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $task->reporter->email ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                                            {{ ucfirst($task->kategori) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white max-w-md truncate">{{ $task->deskripsi_pengajuan }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-status-badge :status="$task->status" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if($task->status == 'accepted')
                                            <form method="POST" action="{{ route('teknisi.task.start', $task) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                                                    Mulai Kerjakan
                                                </button>
                                            </form>
                                        @elseif($task->status == 'on_process')
                                            <a href="{{ route('teknisi.task.completeForm', $task) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                                Selesaikan Pekerjaan
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400 font-medium">Anda belum memiliki tugas aktif.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- BAGIAN 1.5: TUGAS SIAP DIKLAIM --}}
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tugas Tersedia untuk Klaim</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ambil tugas yang tersedia</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto -mx-6 md:mx-0">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Laporan Masuk</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pelapor</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($tasksClaimable as $task)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-white max-w-md truncate">{{ $task->deskripsi_pengajuan }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                                            {{ ucfirst($task->kategori) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $task->nama_pelapor }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form method="POST" action="{{ route('teknisi.task.claim', $task) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                                Ambil Tugas
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400 font-medium">Tidak ada tugas yang tersedia untuk diklaim.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- BAGIAN 2: RIWAYAT PENGERJAAN --}}
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Pengerjaan</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Tugas yang telah selesai dan dinilai</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto -mx-6 md:mx-0">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Laporan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Durasi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($tasksHistory as $task)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $task->deskripsi_pengajuan }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pelapor: {{ $task->nama_pelapor }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                                            {{ ucfirst($task->kategori) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-status-badge :status="$task->status" />
                                        @if($task->status == 'rated')
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Rating: {{ $task->rating }} ⭐</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        @if($task->duration_minutes !== null)
                                            @php
                                                $totalMinutes = abs($task->duration_minutes);
                                                $hours = floor($totalMinutes / 60);
                                                $minutes = $totalMinutes % 60;
                                            @endphp
                                            {{ $hours }} Jam {{ $minutes }} Menit
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada riwayat pekerjaan selesai.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Link Pagination --}}
                    @if($tasksHistory->hasPages())
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        {{ $tasksHistory->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
