<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Report; // <-- Tambahkan ini

class NewReportSubmitted extends Notification
{
    use Queueable;

    protected $report; // Properti untuk menyimpan data laporan

    /**
     * Create a new notification instance.
     */
    public function __construct(Report $report) // Terima objek Report
    {
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Kita hanya pakai database
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'report_id' => $this->report->id,
            'message' => 'Laporan baru (' . $this->report->kategori . ') telah diajukan oleh ' . $this->report->nama_pelapor . '.',
            'url' => route('kepala.report.manage', $this->report->id), // Link ke halaman manage
        ];
    }
}
