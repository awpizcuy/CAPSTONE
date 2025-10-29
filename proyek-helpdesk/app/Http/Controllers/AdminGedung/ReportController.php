<?php

namespace App\Http\Controllers\AdminGedung;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\User; // <-- TAMBAHKAN INI
use App\Notifications\NewReportSubmitted; // <-- TAMBAHKAN INI

class ReportController extends Controller
{
    /**
     * Menampilkan dashboard Admin Gedung
     */
    public function index(Request $request)
    {
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

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

        $laporan = $query->orderBy('created_at', 'desc')->get();

        // Kirim data ke view
        return view('admin-gedung.dashboard', [
            'laporan' => $laporan,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
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
        $request->validate([
            'kategori' => 'required|string|in:peminjaman,instalasi,kerusakan',
            'nama_pelapor' => 'required|string|max:255',
            'tanggal_pengajuan' => 'required|date',
            'deskripsi_pengajuan' => 'required|string',
        ]);

        // 2. Simpan data ke database dan simpan ke variabel
        $newReport = Report::create([ // <-- Simpan ke variabel $newReport
            'user_id' => Auth::id(), // Ambil ID user yang sedang login
            'kategori' => $request->kategori,
            'nama_pelapor' => $request->nama_pelapor,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'deskripsi_pengajuan' => $request->deskripsi_pengajuan,
            'status' => 'pending', // Status awal
        ]);

        // 3. [BARU] Kirim Notifikasi ke semua Kepala IT
        $kepalaITUsers = User::where('role', 'kepala_it')->get();
        foreach ($kepalaITUsers as $user) {
            $user->notify(new NewReportSubmitted($newReport)); // Gunakan variabel $newReport
        }

        // 4. Alihkan kembali ke dashboard dengan pesan sukses
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
