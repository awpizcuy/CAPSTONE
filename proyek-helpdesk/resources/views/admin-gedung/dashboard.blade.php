<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin Gedung') }}
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

                    <a href="{{ route('admin.report.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Buat Laporan Baru
                    </a>

                    <div class="mt-6 overflow-x-auto">
                        <h3 class="text-lg font-medium">Daftar Laporan Anda</h3>
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm mt-2">
                            <thead class="text-left">
                                <tr>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Kategori</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Deskripsi</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Status</th>
                                    <th class="px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($laporan as $report)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $report->kategori }}</td>
                                    <td class="px-4 py-2 text-gray-700 max-w-xs truncate">{{ $report->deskripsi_pengajuan }}</td>
                                    <td class="whitespace-nowrap px-4 py-2">
                                        <x-status-badge :status="$report->status" />
                                        {{-- Jika status rated, tampilkan bintang --}}
                                        @if($report->status == 'rated')
                                            <span class="text-xs text-gray-500 block">({{ $report->rating }} ⭐)</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2">
                                        @if($report->status == 'completed')
                                            <a href="{{ route('admin.report.rateForm', $report) }}" class="inline-block rounded bg-indigo-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700">
                                                Lihat & Beri Rating
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs">Tidak ada aksi</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-center text-gray-500">
                                        Anda belum membuat laporan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
