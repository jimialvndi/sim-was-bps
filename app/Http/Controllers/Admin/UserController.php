<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Auth; // Tambahkan ini biar aman

class UserController extends Controller
{
    // 1. LIHAT SEMUA USER
    public function index()
    {
        // Gunakan Auth::id() agar editor tidak merah
        $users = User::where('id', '!=', Auth::id())->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    // 2. FORM TAMBAH USER
    public function create()
    {
        return view('admin.users.create');
    }

    // 3. SIMPAN USER BARU
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|numeric|unique:users,nip',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,ketua,pengawas',
            'jabatan' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    // 4. HAPUS USER
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus!');
    }
    // 5. TAMPILKAN FORM EDIT
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // 6. SIMPAN PERUBAHAN (UPDATE)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            // unique:users,email,$id artinya: Cek email unik, tapi abaikan punya user ini sendiri
            'email' => 'required|email|unique:users,email,'.$id, 
            'nip' => 'required|numeric',
            'role' => 'required|in:admin,ketua,pengawas',
            'jabatan' => 'required',
            'password' => 'nullable|min:6' // Password boleh kosong (artinya tidak diubah)
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'role' => $request->role,
            'jabatan' => $request->jabatan,
        ];

        // Hanya update password jika kolom diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui!');
    }
}