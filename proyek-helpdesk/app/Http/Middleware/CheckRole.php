<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Pastikan ini ada

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('login');
        }

        // Cek apakah role user ada di dalam daftar $roles yang diizinkan
        foreach ($roles as $role) {
            if ($request->user()->role == $role) {
                return $next($request);
            }
        }

        // Jika tidak punya izin, lempar ke halaman 403 (Forbidden)
        abort(403, 'ANDA TIDAK PUNYA AKSES.');
    }
}
