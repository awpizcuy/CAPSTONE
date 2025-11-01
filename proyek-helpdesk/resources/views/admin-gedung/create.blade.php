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

                <form method="POST" action="{{ route('admin.report.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="kategori" :value="__('Kategori Laporan')" />
                            <select id="kategori" name="kategori" class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 transition" onchange="toggleFotoField()">
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
                            <x-text-input id="tanggal_pengajuan" class="mt-2 block w-full" type="date" name="tanggal_pengajuan" :value="\Illuminate\Support\Carbon::now(config('app.timezone'))->toDateString()" readonly />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tanggal otomatis diisi hari ini.</p>
                        </div>

                        <div>
                            <x-input-label for="deskripsi_pengajuan" :value="__('Deskripsi / Catatan')" />
                            <textarea id="deskripsi_pengajuan" name="deskripsi_pengajuan" rows="5" class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 transition" required>{{ old('deskripsi_pengajuan') }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi_pengajuan')" class="mt-2" />
                        </div>

                        <div id="foto_container" class="hidden">
                            <x-input-label for="foto_awal" :value="__('Foto Kondisi Awal')" />
                            <div class="mt-2">
                                <input type="file" id="foto_awal" name="foto_awal"
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-medium
                                    file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900 dark:file:text-indigo-300
                                    hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800
                                    transition"
                                    accept="image/*"
                                />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Upload foto kondisi awal untuk laporan instalasi atau kerusakan (opsional)
                                </p>
                                <x-input-error :messages="$errors->get('foto_awal')" class="mt-2" />

                                <div id="image_preview" class="hidden mt-4">
                                    <img id="preview" src="#" alt="Preview" class="max-w-sm rounded-lg border border-gray-200 dark:border-gray-600"/>
                                </div>
                            </div>
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

    <script>
        // Function to toggle visibility of foto field
        function toggleFotoField() {
            const kategori = document.getElementById('kategori').value;
            const fotoContainer = document.getElementById('foto_container');
            const fotoField = document.getElementById('foto_awal');

            if (kategori === 'instalasi' || kategori === 'kerusakan') {
                fotoContainer.classList.remove('hidden');
                // Clear preview when switching to non-photo category
                document.getElementById('image_preview').classList.add('hidden');
                document.getElementById('preview').src = '';
            } else {
                fotoContainer.classList.add('hidden');
                // Clear file input and preview when switching to non-photo category
                if (fotoField) {
                    fotoField.value = '';
                }
                document.getElementById('image_preview').classList.add('hidden');
                document.getElementById('preview').src = '';
            }
        }

        // Image preview functionality
        document.getElementById('foto_awal').addEventListener('change', function(evt) {
            const file = evt.target.files[0];
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('image_preview');

            if (file) {
                // Show preview only for image files
                if (file.type.startsWith('image/')) {
                    preview.src = URL.createObjectURL(file);
                    previewContainer.classList.remove('hidden');

                    // Clean up object URL after image loads
                    preview.onload = function() {
                        URL.revokeObjectURL(preview.src);
                    };
                } else {
                    evt.target.value = ''; // Clear non-image file
                    alert('Mohon pilih file gambar saja (JPG, PNG, atau JPEG)');
                }
            } else {
                preview.src = '';
                previewContainer.classList.add('hidden');
            }
        });

        // Run on page load to set initial state
        document.addEventListener('DOMContentLoaded', function() {
            toggleFotoField();
        });
    </script>
</x-app-layout>
