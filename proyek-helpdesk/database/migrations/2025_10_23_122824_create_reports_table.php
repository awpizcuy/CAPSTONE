<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // Pelapor (Admin Gedung)
            $table->foreignId('assigned_technician_id')->nullable()->constrained('users'); // Teknisi
            $table->enum('kategori', ['peminjaman', 'instalasi', 'kerusakan']);
            $table->string('nama_pelapor');
            $table->date('tanggal_pengajuan');
            $table->text('deskripsi_pengajuan');
            $table->enum('status', ['pending', 'accepted', 'rejected', 'hold', 'on_process', 'completed', 'rated'])->default('pending');
            $table->text('status_note')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->tinyInteger('rating')->nullable();
            $table->text('rating_feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
