<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Beri Rating Pekerjaan: ') }} {{ $report->kategori }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-medium text-gray-900">Laporan Penyelesaian dari Teknisi</h3>
                    <div class="mt-4 space-y-2">
                        <p><strong>Laporan Awal:</strong> {{ $report->deskripsi_pengajuan }}</p>

                        @if ($report->resolution)
                            <p><strong>Teknisi:</strong> {{ $report->technician->name ?? 'N/A' }}</p>
                            <p><strong>Durasi Kerja:</strong> {{ $report->duration_minutes ?? 'N/A' }} Menit</p>
                            <p><strong>Barang:</strong> {{ $report->resolution->barang ?? '-' }} (Qty: {{ $report->resolution->qty ?? 0 }})</p>
                            <p><strong>Deskripsi Pekerjaan:</strong></p>
                            <p class="p-3 bg-gray-50 rounded-md border">{{ $report->resolution->deskripsi_pekerjaan }}</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <h4 class="font-medium">Foto Sebelum</h4>
                                    <img src="{{ Storage::url($report->resolution->foto_before) }}" alt="Foto Sebelum" class="mt-2 rounded-md border">
                                </div>
                                <div>
                                    <h4 class="font-medium">Foto Sesudah</h4>
                                    <img src="{{ Storage::url($report->resolution->foto_after) }}" alt="Foto Sesudah" class="mt-2 rounded-md border">
                                </div>
                            </div>
                        @else
                            <p class="text-red-500">Error: Data detail pekerjaan tidak ditemukan.</p>
                        @endif
                    </div>
                    <hr class="my-8">

                    <h3 class="text-lg font-medium text-gray-900">Formulir Rating Anda</h3>
                    <form method="POST" action="{{ route('admin.report.storeRating', $report) }}">
                        @csrf

                        <div class="mt-4">
                            <x-input-label for="rating" :value="__('Rating (1-5 Bintang)')" />
                            <select id="rating" name="rating" class="block mt-1 w-full md:w-1/2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">-- Pilih Rating --</option>
                                <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                                <option value="4">⭐⭐⭐⭐ (Puas)</option>
                                <option value="3">⭐⭐⭐ (Cukup)</option>
                                <option value="2">⭐⭐ (Kurang)</option>
                                <option value="1">⭐ (Sangat Kurang)</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="rating_feedback" :value="__('Feedback / Ulasan (Opsional)')" />
                            <textarea id="rating_feedback" name="rating_feedback" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('rating_feedback') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Kirim Rating') }}
                            </x-primary-button>
                        </div>
                    </form>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
