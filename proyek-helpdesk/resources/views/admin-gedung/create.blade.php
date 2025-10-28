<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Laporan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="focus:border-primary focus:ring-primary overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('admin.report.store') }}">
                        @csrf

                        <div class="mt-4">
                            <x-input-label for="kategori" :value="__('Kategori Laporan')" />
                            <select id="kategori" name="kategori" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="peminjaman">Peminjaman</option>
                                <option value="instalasi">Instalasi</option>
                                <option value="kerusakan">Kerusakan</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="nama_pelapor" :value="__('Nama Pelapor')" />
                            <x-text-input id="nama_pelapor" class="block mt-1 w-full" type="text" name="nama_pelapor" :value="old('nama_pelapor', auth()->user()->name)" required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="tanggal_pengajuan" :value="__('Tanggal Pengajuan')" />
                            <x-text-input id="tanggal_pengajuan" class="block mt-1 w-full" type="date" name="tanggal_pengajuan" :value="old('tanggal_pengajuan', date('Y-m-d'))" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="deskripsi_pengajuan" :value="__('Deskripsi / Catatan')" />
                            <textarea id="deskripsi_pengajuan" name="deskripsi_pengajuan" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('deskripsi_pengajuan') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Kirim Laporan') }}
                            </x-primary-button>
                        </div>
                    </form>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
