<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Laporan: ') }} {{ ucfirst($report->kategori) }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Detail Laporan --}}
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Detail Laporan</h3>
                        <div class="space-y-4">
                            <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pelapor</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $report->nama_pelapor }} ({{ $report->reporter->email }})</p>
                            </div>
                            <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($report->tanggal_pengajuan)->format('d M Y') }}</p>
                            </div>
                            <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Status Saat Ini</p>
                                <p class="mt-1">
                                    <x-status-badge :status="$report->status" />
                                </p>
                            </div>
                            <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Deskripsi</p>
                                <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $report->deskripsi_pengajuan }}</p>
                            </div>
                            {{-- Foto Kerusakan Awal --}}
                            <div class="inline-block p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Foto Kerusakan Awal</p>
                                @php
                                    $fotoBeforePath = $report->foto_awal ?? ($report->resolution?->foto_before ?? null);
                                    $fotoBeforeUrl = null;
                                    if ($fotoBeforePath) {
                                        $fotoBeforeUrl = (\Illuminate\Support\Facades\Storage::disk('public')->exists($fotoBeforePath))
                                            ? \Illuminate\Support\Facades\Storage::disk('public')->url($fotoBeforePath)
                                            : asset('storage/' . ltrim($fotoBeforePath, '/'));
                                    }
                                @endphp
                                @if($fotoBeforeUrl)
                                    {{-- Menghapus w-full dan object-contain agar lebar gambar otomatis --}}
                                    <img src="{{ $fotoBeforeUrl }}" alt="Foto Kerusakan Awal" class="mt-2 rounded-lg max-h-64" />
                                @else
                                    <div class="mt-2 h-40 rounded-lg border border-dashed border-gray-300 dark:border-gray-700 flex items-center justify-center text-sm text-gray-500">Tidak ada foto kerusakan</div>
                                @endif
                            </div>
                            {{-- === AKHIR BLOK FOTO === --}}

                        </div>
                    </div>

                    {{-- Tindakan / Informasi Tambahan --}}
                    <div>
                        @php
                            $canManage = $report->status == 'pending' && $report->assigned_technician_id == null;
                        @endphp

                        @if ($canManage)
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Tindakan</h3>

                            @if (session('success'))
                                <x-flash-message type="success">{{ session('success') }}</x-flash-message>
                            @endif

                            <form method="POST" action="{{ route('kepala.report.update', $report) }}">
                                @csrf

                                <div class="space-y-6">
                                    <div>
                                        <x-input-label for="status" :value="__('Ubah Status Laporan')" />
                                        <select id="status" name="status" class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 transition" required>
                                            <option value="accepted" {{ $report->status == 'accepted' ? 'selected' : '' }}>Accept</option>
                                            <option value="hold" {{ $report->status == 'hold' ? 'selected' : '' }}>Hold</option>
                                            <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="assigned_technician_id" :value="__('Tugaskan ke Teknisi')" />
                                        <select id="assigned_technician_id" name="assigned_technician_id" class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 transition">
                                            <option value="">-- Pilih Teknisi --</option>
                                            @foreach ($technicians as $teknisi)
                                                <option value="{{ $teknisi->id }}" {{ $report->assigned_technician_id == $teknisi->id ? 'selected' : '' }}>
                                                    {{ $teknisi->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Hanya wajib diisi jika status "Accept".</p>
                                        <x-input-error :messages="$errors->get('assigned_technician_id')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="status_note" :value="__('Catatan (Alasan Reject/Hold)')" />
                                        <textarea id="status_note" name="status_note" rows="4" class="mt-2 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 transition">{{ old('status_note', $report->status_note) }}</textarea>
                                        <x-input-error :messages="$errors->get('status_note')" class="mt-2" />
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <a href="{{ route('kepala.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                        Batal
                                    </a>
                                    <x-primary-button>
                                        {{ __('Update Laporan') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        @else
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Informasi Status</h3>

                            <div class="space-y-4">
                                @if ($report->assigned_technician_id)
                                    <div class="p-4 bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-200 dark:border-blue-800">
                                        <p class="text-sm font-medium text-blue-900 dark:text-blue-300">Teknisi yang Ditugaskan</p>
                                        <p class="mt-1 text-sm text-blue-700 dark:text-blue-400">
                                            @if ($report->technician)
                                                {{ $report->technician->name }} ({{ $report->technician->email }})
                                            @else
                                                Teknisi tidak ditemukan
                                            @endif
                                        </p>
                                    </div>
                                @endif

                                @if ($report->status_note)
                                    <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</p>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ $report->status_note }}</p>
                                    </div>
                                @endif

                                @if (in_array($report->status, ['completed', 'rated']))
                                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                        <p class="text-sm font-medium text-green-900 dark:text-green-300">Laporan Telah Selesai</p>
                                        <p class="mt-1 text-sm text-green-700 dark:text-green-400">
                                            Laporan ini telah selesai dikerjakan dan tidak dapat diubah lagi.
                                        </p>
                                    </div>

                                    @if($report->status === 'rated')
                                    <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                                        <p class="text-sm font-medium text-yellow-900 dark:text-yellow-300">Rating dari Pelapor</p>
                                        <div class="mt-2 flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $report->rating)
                                                    <svg class="w-5 h-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-gray-300 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endif
                                            @endfor
                                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                                ({{ $report->rating }} dari 5)
                                            </span>
                                        </div>
                                        @if($report->rating_feedback)
                                            <p class="mt-2 text-sm text-yellow-800 dark:text-yellow-200">
                                                "{{ $report->rating_feedback }}"
                                            </p>
                                        @endif
                                    </div>
                                    @endif

                                    <div>
                                        <a href="{{ route('kepala.report.print', $report) }}" target="_blank" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                            Cetak / Simpan PDF
                                        </a>
                                    </div>

                                    {{-- Foto Before/After --}}
                                    @if ($report->resolution)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                            <div>
                                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Foto Sebelum</p>
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
                                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Foto Sesudah</p>
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
                                    @endif

                                    @if ($report->resolution)
                                        <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ringkasan Pekerjaan</p>
                                            @if ($report->resolution->barang)
                                                <p class="text-sm text-gray-900 dark:text-white"><strong>Barang:</strong> {{ $report->resolution->barang }}</p>
                                            @endif
                                            @if ($report->resolution->qty)
                                                <p class="text-sm text-gray-900 dark:text-white"><strong>Jumlah:</strong> {{ $report->resolution->qty }}</p>
                                            @endif
                                            <p class="text-sm text-gray-900 dark:text-white mt-2"><strong>Deskripsi:</strong></p>
                                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $report->resolution->deskripsi_pekerjaan }}</p>
                                        </div>
                                    @endif
                                @elseif (in_array($report->status, ['accepted', 'on_process']))
                                    <div class="p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-200 dark:border-orange-800">
                                        <p class="text-sm font-medium text-orange-900 dark:text-orange-300">Laporan Sedang Diproses</p>
                                        <p class="mt-1 text-sm text-orange-700 dark:text-orange-400">
                                            Laporan ini sedang dikerjakan oleh teknisi dan tidak dapat diubah statusnya.
                                        </p>
                                    </div>
                                @elseif ($report->status == 'rejected')
                                    <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                        <p class="text-sm font-medium text-red-900 dark:text-red-300">Laporan Ditolak</p>
                                        <p class="mt-1 text-sm text-red-700 dark:text-red-400">
                                            Laporan ini telah ditolak dan tidak dapat diubah lagi.
                                        </p>
                                    </div>
                                @endif

                                <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <a href="{{ route('kepala.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                        Kembali ke Dashboard
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>


            </div>
        </div>
    </div>
</x-app-layout>
