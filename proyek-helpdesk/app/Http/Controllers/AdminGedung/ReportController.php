<?php

namespace App\Http\Controllers\AdminGedung;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Report;
use App\Models\User;
use App\Notifications\NewReportSubmitted;

class ReportController extends Controller
{
    /**
     * Menampilkan dashboard Admin Gedung
     */
    public function index(Request $request)
    {
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
        $filterStatus = $request->query('filter_status');
        $filterCategory = $request->query('filter_category');

        $query = Report::where('user_id', Auth::id());

        if ($dateFrom || $dateTo) {
            if ($dateFrom && $dateTo) {
                $query->whereBetween('tanggal_pengajuan', [$dateFrom, $dateTo]);
            } elseif ($dateFrom) {
                $query->whereDate('tanggal_pengajuan', '>=', $dateFrom);
            } elseif ($dateTo) {
                $query->whereDate('tanggal_pengajuan', '<=', $dateTo);
            }
        }

        // Tambahkan Filter Status
        if ($filterStatus) {
            $query->where('status', $filterStatus);
        }

        // Tambahkan Filter Kategori
        if ($filterCategory) {
            $query->where('kategori', $filterCategory);
        }

        $laporan = $query->orderBy('created_at', 'desc')->get();

        // STATS
        $totalReports = Report::where('user_id', Auth::id())->count();
        $pendingReports = Report::where('user_id', Auth::id())->where('status', 'pending')->count();
        $completedReports = Report::where('user_id', Auth::id())->whereIn('status', ['completed', 'rated'])->count();
        $rejectedReports = Report::where('user_id', Auth::id())->where('status', 'rejected')->count();


        // Kirim data ke view
        return view('admin-gedung.dashboard', [
            'laporan' => $laporan,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'filterStatus' => $filterStatus,
            'filterCategory' => $filterCategory,
            'totalReports' => $totalReports,
            'pendingReports' => $pendingReports,
            'completedReports' => $completedReports,
            'rejectedReports' => $rejectedReports,
        ]);
    }

    /**
     * Menampilkan form untuk membuat laporan baru
     */
    public function create()
    {
        return view('admin-gedung.create');
    }

    /**
     * Menyimpan laporan baru ke database dan mengirim notifikasi
     */
    public function store(Request $request)
    {
        // 1. Validasi data yang masuk
        $validated = $request->validate([
            'kategori' => 'required|string|in:peminjaman,instalasi,kerusakan',
            'nama_pelapor' => 'required|string|max:255',
            'deskripsi_pengajuan' => 'required|string',
            'foto_awal' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        // 2. Persiapkan data untuk disimpan
        $reportData = [
            'user_id' => Auth::id(),
            'kategori' => $validated['kategori'],
            'nama_pelapor' => $validated['nama_pelapor'],
            'tanggal_pengajuan' => now()->toDateString(),
            'deskripsi_pengajuan' => $validated['deskripsi_pengajuan'],
            'status' => 'pending'
        ];

        // 3. Upload dan simpan foto jika ada
        if ($request->hasFile('foto_awal') && in_array($validated['kategori'], ['instalasi', 'kerusakan'])) {
            $reportData['foto_awal'] = $request->file('foto_awal')->store('reports/initial', 'public');
        }

        // 4. Buat record baru di database
        $newReport = Report::create($reportData);

        // 5. Kirim Notifikasi ke semua Kepala IT
        $kepalaITUsers = User::where('role', 'kepala_it')->get();
        foreach ($kepalaITUsers as $user) {
            $user->notify(new NewReportSubmitted($newReport));
        }

        // 6. Alihkan kembali ke dashboard dengan pesan sukses
        return redirect()->route('admin.dashboard')
                        ->with('success', 'Laporan Anda telah berhasil dikirim!');
    }

    /**
     * Menampilkan halaman untuk memberi rating
     */
    public function rateForm(Report $report)
    {
        // Keamanan: Pastikan laporan ini milik user yang login
        if ($report->user_id != Auth::id()) {
            abort(403, 'ANDA TIDAK PUNYA AKSES KE LAPORAN INI.');
        }

        // Keamanan: Pastikan statusnya 'completed'
        if ($report->status != 'completed') {
             return redirect()->route('admin.dashboard')->with('error', 'Laporan ini belum selesai dikerjakan.');
        }

        // Ambil data resolusi (foto, dll)
        $report->load('resolution');

        return view('admin-gedung.rate', [
            'report' => $report
        ]);
    }

    /**
     * Menyimpan rating dan feedback dari Admin Gedung
     */
    public function storeRating(Request $request, Report $report)
    {
        // Keamanan: Pastikan laporan ini milik user yang login
        if ($report->user_id != Auth::id() || $report->status != 'completed') {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        // 1. Validasi
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'rating_feedback' => 'nullable|string',
        ]);

        // 2. Update laporan dengan rating dan ubah status
        $report->update([
            'rating' => $validated['rating'],
            'rating_feedback' => $validated['rating_feedback'],
            'status' => 'rated' // Status final!
        ]);

        // 3. Kembalikan ke dashboard
        return redirect()->route('admin.dashboard')
                         ->with('success', 'Rating telah berhasil diberikan. Terima kasih!');
    }
}
