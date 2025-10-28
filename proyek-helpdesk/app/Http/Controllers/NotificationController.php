<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
    $notifications = Auth::user()
                         ->notifications() // Ambil query builder notifikasi
                         ->latest() // Urutkan dari terbaru (sama dengan orderBy('created_at', 'desc'))
                         ->paginate(15); // Tampilkan 15 per halaman

    return view('notifications.index', [
        'notifications' => $notifications
    ]);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
            // Redirect ke URL yang ada di data notifikasi
            return redirect($notification->data['url'] ?? route('dashboard'));
        }

        return redirect()->route('dashboard')->with('error', 'Notifikasi tidak ditemukan.');
    }

    public function markAllAsRead()
    {
    Auth::user()->unreadNotifications->markAsRead(); // Fungsi bawaan Laravel

    // Redirect kembali ke halaman sebelumnya
    return redirect()->back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
