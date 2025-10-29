<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buat Laporan Baru') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="mb-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Formulir Laporan Baru</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Isi formulir di bawah untuk membuat laporan baru</p>
                </div>

                @if (session('error'))
                    <x-flash-message type="error">{{ session('error') }}</x-flash-message>
                @endif

                <form method="POST" action="{{ route('admin.report.store') }}">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="kategori" :value="__('Kategori Laporan')" />
                            <select id="kategori" name="kategori" class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 transition">
                                <option value="peminjaman">Peminjaman</option>
                                <option value="instalasi">Instalasi</option>
                                <option value="kerusakan">Kerusakan</option>
                            </select>
                            <x-input-error :messages="$errors->get('kategori')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="nama_pelapor" :value="__('Nama Pelapor')" />
                            <x-text-input id="nama_pelapor" class="mt-2 block w-full" type="text" name="nama_pelapor" :value="old('nama_pelapor', auth()->user()->name)" required autofocus />
                            <x-input-error :messages="$errors->get('nama_pelapor')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tanggal_pengajuan" :value="__('Tanggal Pengajuan')" />
                            <x-text-input id="tanggal_pengajuan" class="mt-2 block w-full" type="date" name="tanggal_pengajuan" :value="old('tanggal_pengajuan', date('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('tanggal_pengajuan')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="deskripsi_pengajuan" :value="__('Deskripsi / Catatan')" />
                            <textarea id="deskripsi_pengajuan" name="deskripsi_pengajuan" rows="5" class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 transition" required>{{ old('deskripsi_pengajuan') }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi_pengajuan')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Kirim Laporan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
