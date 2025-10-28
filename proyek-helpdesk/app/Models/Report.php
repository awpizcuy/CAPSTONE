<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'assigned_technician_id', // Untuk Kepala IT nanti
        'kategori',
        'nama_pelapor',
        'tanggal_pengajuan',
        'deskripsi_pengajuan',
        'status',
        'status_note',          // Untuk Kepala IT nanti
        'start_time',           // Untuk Teknisi nanti
        'end_time',             // Untuk Teknisi nanti
        'duration_minutes',     // Untuk Teknisi nanti
        'rating',               // Untuk Admin nanti
        'rating_feedback',      // Untuk Admin nanti
    ];

    /**
     * Mendapatkan data user (Admin Gedung) yang membuat laporan.
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Mendapatkan data user (Teknisi) yang ditugaskan.
     */
    public function technician()
    {
        return $this->belongsTo(User::class, 'assigned_technician_id');
    }

    public function resolution()
    {
    return $this->hasOne(Resolution::class);
    }
}
