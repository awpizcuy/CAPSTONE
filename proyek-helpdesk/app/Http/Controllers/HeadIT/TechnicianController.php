<?php

namespace App\Http\Controllers\HeadIT;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use App\Models\Report;

class TechnicianController extends Controller
{
    /**
     * Menampilkan daftar semua teknisi.
     */
    public function index(Request $request) // Tambahkan Request $request
    {
    // Ambil kata kunci pencarian dari URL (?search=...)
    $searchTerm = $request->query('search');

    // Mulai query builder untuk User
    $query = User::where('role', 'teknisi');

    // Jika ada kata kunci pencarian, tambahkan kondisi WHERE
    if ($searchTerm) {
        $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'like', '%' . $searchTerm . '%')
              ->orWhere('email', 'like', '%' . $searchTerm . '%');
        });
    }
    // Lanjutkan dengan menghitung tugas dan pagination
    $technicians = $query->withCount(['tasks' => function ($subQuery) {
                                $subQuery->whereIn('status', ['completed', 'rated']);
                            }])
                            ->paginate(10)
                            ->withQueryString(); // Penting agar pagination tetap menyertakan query ?search=

    // Kirim data teknisi DAN kata kunci pencarian ke view
    return view('kepala-it.technicians.index', [
        'technicians' => $technicians,
        'searchTerm' => $searchTerm // Kirim ini ke view
    ]);
    }

    /**
     * Menampilkan form untuk menambah teknisi baru.
     */
    public function create()
    {
        return view('kepala-it.technicians.create');
    }

    /**
 * Menyimpan teknisi baru ke database.
 */
public function store(Request $request)
{
    // 1. Validasi data input
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    // 2. Buat user baru
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'teknisi'
    ]);

    // 3. Redirect kembali ke halaman index
    return redirect()->route('kepala.technicians.index')
                     ->with('success', 'Teknisi baru telah berhasil ditambahkan!');
    }

    /**
 * Menampilkan form untuk mengedit teknisi.
 */
    public function edit(User $technician)
    {
        // Pastikan kita hanya bisa mengedit teknisi
        if ($technician->role != 'teknisi') {
            abort(404);
        }

        return view('kepala-it.technicians.edit', [
            'technician' => $technician
        ]);
    }

    /**
 * Memperbarui data teknisi di database.
 */
    public function update(Request $request, User $technician)
    {
    // 1. Validasi data
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($technician->id)],
        'password' => ['nullable', 'confirmed', Rules\Password::defaults()], // Password opsional
    ]);

    // 2. Update data user
    $technician->update([
        'name' => $request->name,
        'email' => $request->email,
    ]);

    // 3. Hanya update password JIKA diisi
    if ($request->filled('password')) {
        $technician->update([
            'password' => Hash::make($request->password)
        ]);
    }

    // 4. Redirect kembali ke halaman index
    return redirect()->route('kepala.technicians.index')
                     ->with('success', 'Data teknisi telah berhasil diperbarui!');
    }

    /**
 * Menghapus teknisi dari database.
 */
public function destroy(User $technician)
{
    // 1. Pastikan yang dihapus adalah teknisi
    if ($technician->role != 'teknisi') {
        abort(404);
    }

    // 2. [PENGAMAN] Cek apakah teknisi masih punya tugas
    // Kita gunakan relasi 'tasks()' yang sudah kita buat di Model User
    if ($technician->tasks()->count() > 0) {
        return redirect()->route('kepala.technicians.index')
                         ->with('error', 'Gagal! Teknisi ini masih memiliki tugas aktif dan tidak bisa dihapus.');
    }

    // 3. Jika aman, hapus teknisi
    $technician->delete();

    // 4. Redirect kembali dengan pesan sukses
    return redirect()->route('kepala.technicians.index')
                     ->with('success', 'Teknisi telah berhasil dihapus.');
    }
}
