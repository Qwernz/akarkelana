<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        // Menggunakan orderByRaw untuk mengurutkan role secara spesifik: admin pertama, kasir kedua, user ketiga.
        // Setelah role, diurutkan berdasarkan nama (A-Z) agar lebih rapi.
        $users = \App\Models\User::orderByRaw("FIELD(role, 'admin', 'kasir', 'user') ASC")
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,kasir,user',
        ]);

        $user = User::findOrFail($id);

        // Proteksi agar admin tidak mengubah rolenya sendiri secara tidak sengaja (opsional)
        if (Auth::id() == $user->id && $request->role !== 'admin') {
            return back()->with('error', 'Anda tidak bisa menurunkan role Anda sendiri!');
        }

        $user->update([
            'role' => $request->role
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Role user berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Proteksi: Email admin utama tidak boleh dihapus
        if ($user->email === 'admin@akarkelana.com') {
            return redirect()->back()->with('error', 'Akun Super Admin tidak dapat dihapus!');
        }

        $user->delete();
        return back()->with('success', 'Akun user berhasil dihapus!');
    }
}
