<?php

namespace App\Http\Controllers\HeadIT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Notifications\ReportAssignedToYou;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    /**
     * Menampilkan dashboard Kepala IT dengan semua laporan, grafik, dan fitur pencarian
     */
    public function index(Request $request)
    {
        $searchTerm = $request->query('search');
        $filterStatus = $request->query('filter_status');
        $filterCategory = $request->query('filter_category');

        // Mulai query builder untuk Report
        $query = Report::with('reporter');

        // Tambahkan Filter Status
        if ($filterStatus) {
            $query->where('status', $filterStatus);
        }

        // Tambahkan Filter Kategori
        if ($filterCategory) {
            $query->where('kategori', $filterCategory);
        }

        // Tambahkan Pencarian Nama/Email/Deskripsi
        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_pelapor', 'like', '%' . $searchTerm . '%')
                  ->orWhere('kategori', 'like', '%' . $searchTerm . '%')
                  ->orWhere('deskripsi_pengajuan', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('reporter', function($reporterQuery) use ($searchTerm) {
                      $reporterQuery->where('email', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Ambil data laporan yang difilter dengan pagination
        $reports = $query->orderBy('created_at', 'desc')
                         ->paginate(10)
                         ->withQueryString();


        // === PENGUMPULAN DATA GRAFIK & KARTU (STATISTIK) ===

        // 1. Grafik Rekapan Status Laporan (Donut Chart)
        $statusCounts = Report::select('status', DB::raw('count(*) as total'))
                              ->groupBy('status')
                              ->pluck('total', 'status');
        $statusColors = [
            'pending' => 'rgb(255, 99, 132)',
            'accepted' => 'rgb(54, 162, 235)',
            'on_process' => 'rgb(255, 205, 86)',
            'completed' => 'rgb(75, 192, 192)',
            'rated' => 'rgb(153, 102, 255)',
            'rejected' => 'rgb(201, 203, 207)',
            'hold' => 'rgb(255, 159, 64)',
        ];
        $chartColorsStatus = $statusCounts->keys()->map(fn($status) => $statusColors[$status] ?? 'rgb(100, 100, 100)')->values();
        $chartDataStatus = [ // <--- PASTIKAN DEFINISI INI TIDAK TERPOTONG
            'labels' => $statusCounts->keys()->map(fn($status) => ucfirst(str_replace('_', ' ', $status))),
            'data' => $statusCounts->values(),
            'colors' => $chartColorsStatus,
        ];

        // 2. Grafik Rata-rata Rating Teknisi (Bar Chart)
        $teknisiRatings = User::where('role', 'teknisi')
            ->whereHas('tasks', fn($q) => $q->whereNotNull('rating'))
            ->withAvg('tasks', 'rating')
            ->get()
            ->filter(fn($t) => !is_null($t->tasks_avg_rating));
        $chartDataRatings = [
            'labels' => $teknisiRatings->pluck('name'),
            'data' => $teknisiRatings->pluck('tasks_avg_rating'),
        ];

        // 3. Data untuk Kartu Statistik
        $totalReports = Report::count();
        $pendingReports = Report::where('status', 'pending')->count();
        $averageCompletionMinutes = Report::whereIn('status', ['completed', 'rated'])
                                         ->whereNotNull('duration_minutes')
                                         ->avg('duration_minutes');
        $averageCompletionTime = $averageCompletionMinutes
            ? Carbon::now()->addMinutes((float)$averageCompletionMinutes)->diffForHumans(Carbon::now(), true, false, 2)
            : 'N/A';

        // 4. Data untuk Grafik Laporan per Kategori (Bar Chart)
        $categoryCounts = Report::select('kategori', DB::raw('count(*) as total'))
                                ->groupBy('kategori')
                                ->pluck('total', 'kategori');
        $chartDataCategory = [
            'labels' => $categoryCounts->keys()->map(fn($k) => ucfirst($k)),
            'data' => $categoryCounts->values(),
        ];


        // === KIRIM SEMUA DATA KE VIEW ===
        return view('kepala-it.dashboard', [
            'reports' => $reports,
            'chartDataStatus' => $chartDataStatus,
            'chartDataRatings' => $chartDataRatings,
            'totalReports' => $totalReports,
            'pendingReports' => $pendingReports,
            'averageCompletionTime' => $averageCompletionTime,
            'chartDataCategory' => $chartDataCategory,
            'searchTerm' => $searchTerm
        ]);
    }


    /**
     * Menampilkan halaman untuk me-manage laporan
     */
    public function manage(Report $report)
    {
        $technicians = User::where('role', 'teknisi')->get();

        return view('kepala-it.manage', [
            'report' => $report,
            'technicians' => $technicians
        ]);
    }


    /**
     * Menyimpan perubahan dari halaman manage
     */
    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
        'status' => ['required', Rule::in(['accepted', 'hold', 'rejected'])],
        'status_note' => 'nullable|string',
        // Kita hapus Rule::requiredIf dan ganti dengan logic NULL di bawah
        'assigned_technician_id' => [
            'nullable',
            'exists:users,id',
        ],
    ]);

    // 2. Siapkan data update
    $updateData = [
        'status' => $validated['status'],
        'status_note' => $validated['status_note'],
        'assigned_technician_id' => $validated['assigned_technician_id'],
    ];

    if ($request->status == 'accepted' && !$request->filled('assigned_technician_id')) {
        $updateData['assigned_technician_id'] = null;
    }

    // 3. Update data laporan di database
    $report->update($updateData);

    // 4. Kirim Notifikasi ke Teknisi JIKA status 'accepted' DAN ditugaskan ke seseorang
    if ($request->status == 'accepted' && $report->assigned_technician_id !== null) {
        $technician = User::find($report->assigned_technician_id);
        if ($technician) {
            $report->refresh(); // Refresh data laporan
            $technician->notify(new ReportAssignedToYou($report));
        }
    }

    // 5. Kembalikan ke dashboard Kepala IT dengan pesan sukses
    return redirect()->route('kepala.dashboard')
                     ->with('success', 'Status laporan telah berhasil diperbarui!');
    }

}
