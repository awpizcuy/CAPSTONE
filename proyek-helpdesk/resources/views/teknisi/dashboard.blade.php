<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Teknisi - Daftar Tugas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- ========================================================== --}}
                    {{-- BAGIAN 1: TUGAS AKTIF (ACTIVE TASKS) --}}
                    {{-- ========================================================== --}}
                    <h3 class="text-lg font-medium mb-4">Tugas Aktif dan Tugas Baru</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                                <tr>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Pelapor</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Kategori</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Deskripsi</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Status</th>
                                    <th class="px-4 py-2">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                {{-- Gunakan tasksActive --}}
                                @forelse ($tasksActive as $task)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                        {{ $task->nama_pelapor }}
                                        <span class="text-xs text-gray-500 block">({{ $task->reporter->email }})</span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $task->kategori }}</td>
                                    <td class="px-4 py-2 text-gray-700 max-w-xs truncate">{{ $task->deskripsi_pengajuan }}</td>
                                    <td class="whitespace-nowrap px-4 py-2">
                                        <x-status-badge :status="$task->status" />
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2">

                                        @if($task->status == 'accepted')
                                        <form method="POST" action="{{ route('teknisi.task.start', $task) }}">
                                            @csrf
                                            <button type="submit" class="inline-block rounded bg-green-600 px-4 py-2 text-xs font-medium text-white hover:bg-green-700">
                                                Mulai Kerjakan
                                            </button>
                                        </form>
                                        @elseif($task->status == 'on_process')
                                            <a href="{{ route('teknisi.task.completeForm', $task) }}" class="inline-block rounded bg-primary hover:bg-primary-600 px-4 py-2 text-xs font-medium text-white hover:bg-primary hover:bg-primary-600">
                                                Selesaikan Pekerjaan
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-2 text-center text-gray-500">
                                        Anda belum memiliki tugas aktif.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- ========================================================== --}}
                {{-- BAGIAN 1.5: TUGAS SIAP DIKLAIM (CLAIMABLE TASKS) --}}
                {{-- ========================================================== --}}
                <div class="mt-10">
                    <h3 class="text-lg font-medium mb-4">Tugas Tersedia untuk Klaim</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                                <tr>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Laporan Masuk</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Kategori</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Pelapor</th>
                                    <th class="px-4 py-2">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                @forelse ($tasksClaimable as $task)
                                <tr>
                                    <td class="px-4 py-2 text-gray-700 max-w-xs truncate">{{ $task->deskripsi_pengajuan }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $task->kategori }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ $task->nama_pelapor }}</td>
                                    <td class="whitespace-nowrap px-4 py-2">
                                        {{-- Tombol Klaim Tugas --}}
                                        <form method="POST" action="{{ route('teknisi.task.claim', $task) }}">
                                            @csrf
                                            <button type="submit" class="inline-block rounded bg-indigo-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700">
                                                Ambil Tugas
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-center text-gray-500">
                                        Tidak ada tugas yang tersedia untuk diklaim.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                    {{-- ========================================================== --}}
                    {{-- BAGIAN 2: RIWAYAT PENGERJAAN (HISTORY) --}}
                    {{-- ========================================================== --}}
                    <div class="mt-10">
                        <h3 class="text-lg font-medium mb-4">Riwayat Pengerjaan (Selesai dan Dinilai)</h3>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                                <thead class="text-left">
                                    <tr>
                                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Laporan</th>
                                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Kategori</th>
                                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Status</th>
                                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Durasi</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    @forelse ($tasksHistory as $task)
                                    <tr>
                                        <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                            {{ $task->deskripsi_pengajuan }}
                                            <span class="block text-xs text-gray-500">Pelapor: {{ $task->nama_pelapor }}</span>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $task->kategori }}</td>
                                        <td class="whitespace-nowrap px-4 py-2">
                                            <x-status-badge :status="$task->status" />
                                            @if($task->status == 'rated')
                                                <span class="text-xs text-gray-500 block">Rating: {{ $task->rating }} ⭐</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-2 text-gray-700">
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
                                        <td colspan="4" class="px-4 py-2 text-center text-gray-500">
                                            Belum ada riwayat pekerjaan selesai.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Link Pagination --}}
                        <div class="mt-4">
                            {{ $tasksHistory->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
