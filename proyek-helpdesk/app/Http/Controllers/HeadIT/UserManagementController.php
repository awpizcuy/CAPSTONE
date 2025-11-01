<?php

namespace App\Http\Controllers\HeadIT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    public function index()
    {
        // Tampilkan hanya users dengan role admin_gedung
        $users = User::where('role', 'admin_gedung')->orderBy('name')->paginate(20);
        return view('kepala-it.admin-gedung.index', compact('users'));
    }

    public function create()
    {
        return view('kepala-it.admin-gedung.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        // generate password otomatis
        $plain = Str::random(10);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $plain,
            'role' => 'admin_gedung',
        ]);

        // flash plain password once so kepala-it can note it
        session()->flash('created_user_password', $plain);
        session()->flash('created_user_email', $user->email);

        return redirect()->route('kepala.users.index')->with('success', 'Akun admin gedung berhasil dibuat. Password ditampilkan sekali di bawah.');
    }

    public function edit(User $user)
    {
        abort_unless($user->role === 'admin_gedung', 404);
        return view('kepala-it.admin-gedung.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        abort_unless($user->role === 'admin_gedung', 404);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|string|min:6',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = $data['password']; // will be hashed by model
        }
        $user->save();

        return redirect()->route('kepala.users.index')->with('success', 'Akun diperbarui.');
    }

    public function destroy(User $user)
    {
        abort_unless($user->role === 'admin_gedung', 404);
        $user->delete();
        return redirect()->route('kepala.users.index')->with('success', 'Akun dihapus.');
    }
}
