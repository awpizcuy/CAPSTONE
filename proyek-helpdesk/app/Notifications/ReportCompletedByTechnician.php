<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Report; // <-- Tambahkan ini

class ReportCompletedByTechnician extends Notification
{
    use Queueable;

    protected $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        // Ambil nama teknisi dari relasi
        $technicianName = $this->report->technician ? $this->report->technician->name : 'Teknisi';

        return [
            'report_id' => $this->report->id,
            'message' => 'Laporan Anda (' . $this->report->kategori . ') telah diselesaikan oleh ' . $technicianName . '. Silakan beri rating.',
            'url' => route('admin.report.rateForm', $this->report->id), // Link ke halaman rating
        ];
    }
}
