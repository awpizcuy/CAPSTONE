<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\Resolution;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ReportCompletedByTechnician;

class TaskController extends Controller
{
    /**
     * Menampilkan dashboard Teknisi dengan daftar tugas
     */
    public function index(Request $request)
    {
        $teknisiId = Auth::id();
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

    // 1. Tugas Aktif (Accepted atau On Process)
        $tasksActiveQuery = Report::where('assigned_technician_id', $teknisiId)
                       ->whereIn('status', ['accepted', 'on_process'])
                       ->with('reporter')
                       ->orderBy('status', 'asc');

        if ($dateFrom || $dateTo) {
            if ($dateFrom && $dateTo) {
                $tasksActiveQuery->whereBetween('tanggal_pengajuan', [$dateFrom, $dateTo]);
            } elseif ($dateFrom) {
                $tasksActiveQuery->whereDate('tanggal_pengajuan', '>=', $dateFrom);
            } elseif ($dateTo) {
                $tasksActiveQuery->whereDate('tanggal_pengajuan', '<=', $dateTo);
            }
        }

        $tasksActive = $tasksActiveQuery->get();

    // 2. Tugas Riwayat (Completed atau Rated)
        $tasksHistoryQuery = Report::where('assigned_technician_id', $teknisiId)
                        ->whereIn('status', ['completed', 'rated'])
                        ->with('reporter')
                        ->orderBy('end_time', 'desc');

        if ($dateFrom || $dateTo) {
            if ($dateFrom && $dateTo) {
                $tasksHistoryQuery->whereBetween('tanggal_pengajuan', [$dateFrom, $dateTo]);
            } elseif ($dateFrom) {
                $tasksHistoryQuery->whereDate('tanggal_pengajuan', '>=', $dateFrom);
            } elseif ($dateTo) {
                $tasksHistoryQuery->whereDate('tanggal_pengajuan', '<=', $dateTo);
            }
        }

        $tasksHistory = $tasksHistoryQuery->paginate(10);

        $tasksClaimableQuery = Report::whereIn('status', ['pending', 'accepted'])
                          ->whereNull('assigned_technician_id')
                          ->with('reporter')
                          ->orderBy('created_at', 'asc');

        if ($dateFrom || $dateTo) {
            if ($dateFrom && $dateTo) {
                $tasksClaimableQuery->whereBetween('tanggal_pengajuan', [$dateFrom, $dateTo]);
            } elseif ($dateFrom) {
                $tasksClaimableQuery->whereDate('tanggal_pengajuan', '>=', $dateFrom);
            } elseif ($dateTo) {
                $tasksClaimableQuery->whereDate('tanggal_pengajuan', '<=', $dateTo);
            }
        }

        $tasksClaimable = $tasksClaimableQuery->get();

    // Kirim kedua set data
        return view('teknisi.dashboard', [
        'tasksActive' => $tasksActive,
        'tasksClaimable' => $tasksClaimable, // <-- Kirim data klaim
        'tasksHistory' => $tasksHistory,
        'dateFrom' => $dateFrom,
        'dateTo' => $dateTo,
    ]);
    }

    /**
     * Memulai pekerjaan (mengubah status ke 'on_process' dan set timer)
     */
    public function start(Report $report)
    {
        if ($report->assigned_technician_id != Auth::id()) {
            abort(403, 'ANDA TIDAK PUNYA AKSES KE TUGAS INI.');
        }

        $report->update([
        'status' => 'on_process',
        'start_time' => now()
    ]);

        return redirect()->route('teknisi.dashboard')
                         ->with('success', 'Pekerjaan telah dimulai!');
    }

    public function completeForm(Report $report)
    {
        // Keamanan: Pastikan tugas ini milik teknisi yang login
        if ($report->assigned_technician_id != Auth::id()) {
            abort(403, 'ANDA TIDAK PUNYA AKSES KE TUGAS INI.');
        }

        // Keamanan: Pastikan statusnya 'on_process'
        if ($report->status != 'on_process') {
             return redirect()->route('teknisi.dashboard')->with('error', 'Pekerjaan ini belum dimulai.');
        }

        return view('teknisi.complete', [
            'report' => $report
        ]);
    }

    public function storeCompletion(Request $request, Report $report)
    {
        // 1. Keamanan: Pastikan tugas ini milik teknisi yang login
        if ($report->assigned_technician_id != Auth::id() || $report->status != 'on_process') {
            abort(403, 'AKSI TIDAK DIIZINKAN.');
        }

        // 2. Validasi form
        $validated = $request->validate([
            'barang' => 'nullable|string|max:255',
            'qty' => 'nullable|integer|min:0',
            'deskripsi_pekerjaan' => 'required|string',
            'foto_before' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_after' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 3. Upload file foto
        // 'public/resolutions' akan menyimpan di folder 'storage/app/public/resolutions'
        $pathBefore = $request->file('foto_before')->store('resolutions');
        $pathAfter = $request->file('foto_after')->store('resolutions');

        // 4. Hitung Durasi & Hentikan Timer (gunakan Carbon via casts)
        $endTime = now();
        $durationMinutes = $report->start_time?->diffInMinutes($endTime) ?? 0;

        // 5. Update tabel 'reports'
        $report->update([
            'status' => 'completed',
            'end_time' => $endTime,
            'duration_minutes' => $durationMinutes,
        ]);

        // 6. Buat data di tabel 'resolutions'
        Resolution::create([
            'report_id' => $report->id,
            'barang' => $validated['barang'],
            'qty' => $validated['qty'],
            'deskripsi_pekerjaan' => $validated['deskripsi_pekerjaan'],
            'foto_before' => $pathBefore,
            'foto_after' => $pathAfter,
        ]);

        // 8 Kirim notifikasi ke Admin Gedung
        $report->refresh();
            if ($report->reporter) {
            $report->reporter->notify(new ReportCompletedByTechnician($report));
    }

        // 9. Kembalikan ke dashboard
        return redirect()->route('teknisi.dashboard')
                         ->with('success', 'Pekerjaan telah selesai dan laporan terkirim!');
    }

    public function claim(Report $report)
    {
    // 1. Cek Keamanan: Pastikan tugas ini bisa diklaim (status pending/accepted dan belum ada yang ditugaskan)
        if (!in_array($report->status, ['pending', 'accepted']) || $report->assigned_technician_id !== null) {
        return redirect()->route('teknisi.dashboard')->with('error', 'Tugas ini tidak tersedia untuk diklaim.');
    }

    // 2. Klaim Tugas dan set status ke on_process
        $report->update([
        'assigned_technician_id' => Auth::id(),
        'status' => 'on_process',
        'start_time' => now()
    ]);

    return redirect()->route('teknisi.dashboard')->with('success', 'Tugas berhasil diklaim dan telah dimulai!');
    }
}
