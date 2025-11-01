<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Beri Rating Pekerjaan: ') }} {{ $report->kategori }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">

                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Laporan Penyelesaian dari Teknisi</h3>
                    <div class="mt-4 space-y-3 leading-relaxed">
                        <p class="text-sm md:text-base"><span class="text-gray-700 dark:text-gray-300 font-semibold">Laporan Awal:</span> {{ $report->deskripsi_pengajuan }}</p>

                        @if ($report->resolution)
                            <p class="text-sm md:text-base"><span class="text-gray-700 dark:text-gray-300 font-semibold">Teknisi:</span> {{ $report->technician->name ?? 'N/A' }}</p>
                            <p class="text-sm md:text-base"><span class="text-gray-700 dark:text-gray-300 font-semibold">Durasi Kerja:</span> {{ $report->duration_minutes ?? 'N/A' }} Menit</p>
                            <p class="text-sm md:text-base"><span class="text-gray-700 dark:text-gray-300 font-semibold">Barang:</span> {{ $report->resolution->barang ?? '-' }} (Qty: {{ $report->resolution->qty ?? 0 }})</p>
                            <p class="text-gray-700 dark:text-gray-300 font-semibold">Deskripsi Pekerjaan:</p>
                            <p class="p-4 md:p-5 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700 text-sm md:text-base">{{ $report->resolution->deskripsi_pekerjaan }}</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                <div>
                                    <h4 class="font-medium">Foto Sebelum</h4>
                                    @php
                                        $beforePath = $report->resolution?->foto_before;
                                        $beforeUrl = null;
                                        if ($beforePath) {
                                            $beforeUrl = (\Illuminate\Support\Facades\Storage::disk('public')->exists($beforePath))
                                                ? \Illuminate\Support\Facades\Storage::disk('public')->url($beforePath)
                                                : asset('storage/' . ltrim($beforePath, '/'));
                                        }
                                    @endphp
                                    @if($beforeUrl)
                                        <img src="{{ $beforeUrl }}" alt="Foto Sebelum" class="mt-2 rounded-lg border border-gray-200 dark:border-gray-700 w-full object-cover max-h-96" />
                                    @else
                                        <div class="mt-2 h-48 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 flex items-center justify-center text-sm text-gray-500">Tidak ada foto</div>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-medium">Foto Sesudah</h4>
                                    @php
                                        $afterPath = $report->resolution?->foto_after;
                                        $afterUrl = null;
                                        if ($afterPath) {
                                            $afterUrl = (\Illuminate\Support\Facades\Storage::disk('public')->exists($afterPath))
                                                ? \Illuminate\Support\Facades\Storage::disk('public')->url($afterPath)
                                                : asset('storage/' . ltrim($afterPath, '/'));
                                        }
                                    @endphp
                                    @if($afterUrl)
                                        <img src="{{ $afterUrl }}" alt="Foto Sesudah" class="mt-2 rounded-lg border border-gray-200 dark:border-gray-700 w-full object-cover max-h-96" />
                                    @else
                                        <div class="mt-2 h-48 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 flex items-center justify-center text-sm text-gray-500">Tidak ada foto</div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="text-red-500">Error: Data detail pekerjaan tidak ditemukan.</p>
                        @endif
                    </div>
                    <hr class="my-8">

                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Formulir Rating Anda</h3>
                    <form method="POST" action="{{ route('admin.report.storeRating', $report) }}" class="mt-4">
                        @csrf

                        <div class="mt-4">
                            <x-input-label for="rating" :value="__('Rating (1-5 Bintang)')" />
                            <select id="rating" name="rating" class="mt-2 block w-full md:w-1/2 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500" required>
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
                            <textarea id="rating_feedback" name="rating_feedback" rows="4" class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500">{{ old('rating_feedback') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition">Batal</a>
                            <x-primary-button>
                                {{ __('Kirim Rating') }}
                            </x-primary-button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</x-app-layout>
