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
use Carbon\CarbonInterval;

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
        $dateFrom = $request->query('date_from');

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

        // Tambahkan Filter Tanggal (berdasarkan tanggal_pengajuan - mulai dari tanggal yang dipilih)
        if ($dateFrom) {
            $query->whereDate('tanggal_pengajuan', '>=', $dateFrom);
        }

        // Ambil data laporan yang difilter dengan pagination
        $reports = $query->orderBy('created_at', 'desc')
                            ->paginate(10)
                            ->withQueryString();


        // === PENGUMPULAN DATA KARTU (STATISTIK) ===

        // Data untuk Kartu Statistik (sesuai file view)
        $totalReports = Report::count();
        $pendingReports = Report::where('status', 'pending')->count();
        $averageMinutes = Report::whereIn('status', ['completed', 'rated'])
                                ->whereNotNull('start_time')
                                ->whereNotNull('end_time')
                                ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, start_time, end_time)) as avg_duration'))
                                ->value('avg_duration');

        $averageCompletionTime = "N/A";

        if ($averageMinutes && $averageMinutes > 0) {
            $totalMinutes = round($averageMinutes);
            $averageCompletionTime = CarbonInterval::minutes($totalMinutes)
                                        ->cascade()
                                        ->forHumans(['short' => true]);
        }

        // Kirim data ke view
        return view('kepala-it.dashboard', [
            'reports' => $reports,
            'totalReports' => $totalReports,
            'pendingReports' => $pendingReports,
            'averageCompletionTime' => $averageCompletionTime,

            // Variabel untuk filter (agar tetap ada di form)
            'searchTerm' => $searchTerm,
            'filterStatus' => $filterStatus,
            'filterCategory' => $filterCategory,
            'dateFrom' => $dateFrom,
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

    /**
     * Cetak/Export tampilan laporan selesai ke halaman print-friendly.
     * Pengguna dapat menyimpan sebagai PDF via dialog print browser.
     */
    public function printView(Report $report)
    {
        // Batasi hanya untuk status selesai atau dinilai
        if (!in_array($report->status, ['completed', 'rated'])) {
            return redirect()->route('kepala.report.manage', $report)
                             ->with('error', 'Hanya laporan yang telah selesai yang dapat dicetak.');
        }

        // Pastikan relasi tersedia
        $report->load(['reporter', 'technician', 'resolution']);

        return view('kepala-it.report-print', [
            'report' => $report,
        ]);
    }

}
