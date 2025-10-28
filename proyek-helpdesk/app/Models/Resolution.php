<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resolution extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'report_id',
        'barang',
        'qty',
        'deskripsi_pekerjaan',
        'foto_before',
        'foto_after',
    ];

    /**
     * Mendapatkan data laporan induk.
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
