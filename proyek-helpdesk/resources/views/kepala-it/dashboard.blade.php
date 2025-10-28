<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Kepala IT - Ringkasan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-sm font-medium text-gray-500">Total Laporan</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalReports }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-sm font-medium text-gray-500">Laporan Pending</h3>
                        <p class="mt-1 text-3xl font-semibold text-red-600">{{ $pendingReports }}</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-sm font-medium text-gray-500">Rata-rata Waktu Selesai</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $averageCompletionTime }}</p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900">Rekapan Status Laporan</h3>
                        <div class="mt-4" style="height: 300px;">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900">Rata-rata Rating Teknisi</h3>
                        <div class="mt-4" style="height: 300px;">
                            <canvas id="ratingChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900">Laporan per Kategori</h3>
                        <div class="mt-4" style="height: 300px;">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Semua Laporan Masuk</h3>

                    {{-- [PERUBAHAN BARU] Formulir Filter & Pencarian --}}
                    <form method="GET" action="{{ route('kepala.dashboard') }}">
                        <div class="flex flex-wrap items-center gap-3 mb-4">

                            {{-- Filter Status --}}
                            <select name="filter_status" class="rounded-md border-gray-300">
                                <option value="">-- Filter Status --</option>
                                @foreach(['pending', 'accepted', 'on_process', 'completed', 'rated', 'rejected', 'hold'] as $status)
                                    <option value="{{ $status }}" {{ request('filter_status') == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Filter Kategori --}}
                            <select name="filter_category" class="rounded-md border-gray-300">
                                 <option value="">-- Filter Kategori --</option>
                                 @foreach(['kerusakan', 'peminjaman', 'instalasi'] as $kategori)
                                    <option value="{{ $kategori }}" {{ request('filter_category') == $kategori ? 'selected' : '' }}>
                                        {{ ucfirst($kategori) }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Input Pencarian --}}
                            <x-text-input type="text" name="search" placeholder="Cari pelapor, deskripsi..." class="flex-grow min-w-[200px]" :value="$searchTerm ?? ''" />

                            {{-- Tombol Aksi --}}
                            <x-primary-button type="submit">Filter/Cari</x-primary-button>
                            <a href="{{ route('kepala.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-200">Reset</a>
                        </div>
                    </form>
                    {{-- [AKHIR FORMULIR FILTER & PENCARIAN] --}}

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                           <thead class="text-left">
                            <tr>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Pelapor</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Kategori</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Tanggal</th>
                                <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Status</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                           </thead>
                           <tbody class="divide-y divide-gray-200">
                            @forelse ($reports as $report)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                                    {{ $report->nama_pelapor }}
                                    <span class="text-xs text-gray-500 block">({{ $report->reporter->email }})</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $report->kategori }}</td>
                                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $report->tanggal_pengajuan }}</td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    <x-status-badge :status="$report->status" />
                                </td>
                                <td class="whitespace-nowrap px-4 py-2">
                                    <a href="{{ route('kepala.report.manage', $report) }}" class="inline-block rounded bg-primary hover:bg-primary-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-center text-gray-500">
                                    Belum ada laporan yang masuk.
                                </td>
                            </tr>
                            @endforelse
                           </tbody>
                        </table>
                    </div> {{-- Akhir dari div overflow-x-auto --}}

                    <div class="mt-4">
                        {{ $reports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Ambil data dari PHP Controller
        const statusLabels = @json($chartDataStatus['labels']);
        const statusData = @json($chartDataStatus['data']);
        const statusColorsData = @json($chartDataStatus['colors']);
        const ratingLabels = @json($chartDataRatings['labels']);
        const ratingData = @json($chartDataRatings['data']);
        const categoryLabels = @json($chartDataCategory['labels']);
        const categoryData = @json($chartDataCategory['data']);

        // Inisialisasi Grafik 1: Status Laporan (Doughnut)
        const ctxStatus = document.getElementById('statusChart')?.getContext('2d');
        if (ctxStatus) {
            new Chart(ctxStatus, {
                type: 'doughnut', data: { labels: statusLabels, datasets: [{ label: 'Jumlah Laporan', data: statusData, backgroundColor: statusColorsData, hoverOffset: 4 }] },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' } } }
            });
        }

       // Inisialisasi Grafik 2: Rating Teknisi (Bar)
        const ctxRating = document.getElementById('ratingChart')?.getContext('2d');
        if (ctxRating) {
            new Chart(ctxRating, {
                type: 'bar', data: { labels: ratingLabels, datasets: [{
                    label: 'Rata-rata Rating (1-5)', data: ratingData,
                    backgroundColor: 'rgb(75, 192, 192)', // Warna Solid Hijau Teal
                    borderColor: 'rgb(75, 192, 192)', // Border Sama
                    borderWidth: 1 }] },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true, max: 5 } },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y.toFixed(1);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Inisialisasi Grafik 3: Laporan per Kategori (Bar)
        const ctxCategory = document.getElementById('categoryChart')?.getContext('2d');
        if (ctxCategory) {
            new Chart(ctxCategory, {
                type: 'bar',
                data: { labels: categoryLabels, datasets: [{ label: 'Jumlah Laporan', data: categoryData, backgroundColor: 'rgba(54, 162, 235, 0.2)', borderColor: 'rgb(54, 162, 235)', borderWidth: 1 }] },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
            });
        }
    </script>
    @endpush
</x-app-layout>
