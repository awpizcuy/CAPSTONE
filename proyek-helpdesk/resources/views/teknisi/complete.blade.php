<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Selesaikan Pekerjaan: ') }} {{ ucfirst($report->kategori) }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Laporan Penyelesaian</h3>
                    <div class="mt-3 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Laporan awal:</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $report->deskripsi_pengajuan }}</p>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto Kerusakan / Kondisi Awal</p>
                        @php
                            $fotoBeforePath = $report->foto_awal;
                            $fotoBeforeUrl = null;
                            if ($fotoBeforePath) {
                                $fotoBeforeUrl = (\Illuminate\Support\Facades\Storage::disk('public')->exists($fotoBeforePath))
                                    ? \Illuminate\Support\Facades\Storage::disk('public')->url($fotoBeforePath)
                                    : asset('storage/' . ltrim($fotoBeforePath, '/'));
                            }
                        @endphp
                        @if($fotoBeforeUrl)
                            <img src="{{ $fotoBeforeUrl }}" alt="Foto Kerusakan" class="rounded-lg border w-full max-h-72 object-cover mt-2" />
                        @else
                            <div class="h-40 rounded-lg border border-dashed flex items-center justify-center text-sm text-gray-500">Tidak ada foto kerusakan</div>
                        @endif
                    </div>
                </div>

                @if ($errors->any())
                    <x-flash-message type="error">Mohon periksa kembali form yang Anda isi.</x-flash-message>
                @endif

                <form method="POST" action="{{ route('teknisi.task.storeCompletion', $report) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="barang" :value="__('Barang (yang diganti/dipinjam/diinstal)')" />
                            <x-text-input id="barang" class="mt-2 block w-full" type="text" name="barang" :value="old('barang')" />
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Kosongkan jika tidak ada.</p>
                            <x-input-error :messages="$errors->get('barang')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="qty" :value="__('Jumlah (Qty)')" />
                            <x-text-input id="qty" class="mt-2 block w-full" type="number" name="qty" :value="old('qty', 1)" min="1" />
                            <x-input-error :messages="$errors->get('qty')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="deskripsi_pekerjaan" :value="__('Deskripsi Pekerjaan yang Dilakukan')" />
                            <textarea id="deskripsi_pekerjaan" name="deskripsi_pekerjaan" rows="5" class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 transition" required>{{ old('deskripsi_pekerjaan') }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi_pekerjaan')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="foto_before" :value="__('Foto Sebelum Dikerjakan')" />
                                <input id="foto_before" name="foto_before" type="file" accept="image/*" class="mt-2 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/20 dark:file:text-indigo-300 file:cursor-pointer border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg" required>
                                <x-input-error :messages="$errors->get('foto_before')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="foto_after" :value="__('Foto Setelah Dikerjakan')" />
                                <input id="foto_after" name="foto_after" type="file" accept="image/*" class="mt-2 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/20 dark:file:text-indigo-300 file:cursor-pointer border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg" required>
                                <x-input-error :messages="$errors->get('foto_after')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <divSetup flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('teknisi.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Selesai & Kirim Laporan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
