<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Selesaikan Pekerjaan: ') }} {{ $report->kategori }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-medium text-gray-900">Laporan Penyelesaian</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Laporan awal: "{{ $report->deskripsi_pengajuan }}"
                    </p>

                    <form method="POST" action="{{ route('teknisi.task.storeCompletion', $report) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mt-4">
                            <x-input-label for="barang" :value="__('Barang (yang diganti/dipinjam/diinstal)')" />
                            <x-text-input id="barang" class="block mt-1 w-full" type="text" name="barang" :value="old('barang')" />
                            <p class="mt-1 text-sm text-gray-500">Kosongkan jika tidak ada.</p>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="qty" :value="__('Jumlah (Qty)')" />
                            <x-text-input id="qty" class="block mt-1 w-full" type="number" name="qty" :value="old('qty', 1)" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="deskripsi_pekerjaan" :value="__('Deskripsi Pekerjaan yang Dilakukan')" />
                            <textarea id="deskripsi_pekerjaan" name="deskripsi_pekerjaan" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('deskripsi_pekerjaan') }}</textarea>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="foto_before" :value="__('Foto Sebelum Dikerjakan')" />
                            <input id="foto_before" name="foto_before" type="file" class="block mt-1 w-full" required>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="foto_after" :value="__('Foto Setelah Dikerjakan')" />
                            <input id="foto_after" name="foto_after" type="file" class="block mt-1 w-full" required>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Selesai & Kirim Laporan') }}
                            </x-primary-button>
                        </div>
                    </form>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
