<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Laporan: ') }} {{ $report->kategori }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Detail Laporan</h3>
                        <div class="mt-4 space-y-2">
                            <p><strong>Pelapor:</strong> {{ $report->nama_pelapor }} ({{ $report->reporter->email }})</p>
                            <p><strong>Tanggal:</strong> {{ $report->tanggal_pengajuan }}</p>
                            <p><strong>Status Saat Ini:</strong> {{ $report->status }}</p>
                            <p><strong>Deskripsi:</strong></p>
                            <p class="p-3 bg-gray-50 rounded-md border">{{ $report->deskripsi_pengajuan }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Tindakan</h3>

                        <form method="POST" action="{{ route('kepala.report.update', $report) }}">
                            @csrf <div class="mt-4">
                                <x-input-label for="status" :value="__('Ubah Status Laporan')" />
                                <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="accepted" {{ $report->status == 'accepted' ? 'selected' : '' }}>Accept</option>
                                    <option value="hold" {{ $report->status == 'hold' ? 'selected' : '' }}>Hold</option>
                                    <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                                </select>
                            </div>

                            <div class="mt-4">
                                <x-input-label for="assigned_technician_id" :value="__('Tugaskan ke Teknisi')" />
                                <select id="assigned_technician_id" name="assigned_technician_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">-- Pilih Teknisi --</option>
                                    @foreach ($technicians as $teknisi)
                                        <option value="{{ $teknisi->id }}" {{ $report->assigned_technician_id == $teknisi->id ? 'selected' : '' }}>
                                            {{ $teknisi->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-sm text-gray-500">Hanya wajib diisi jika status "Accept".</p>
                            </div>

                            <div class="mt-4">
                                <x-input-label for="status_note" :value="__('Catatan (Alasan Reject/Hold)')" />
                                <textarea id="status_note" name="status_note" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('status_note', $report->status_note) }}</textarea>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button>
                                    {{ __('Update Laporan') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
