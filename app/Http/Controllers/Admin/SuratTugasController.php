<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratTugas;
use App\Models\User;

class SuratTugasController extends Controller
{
    // 1. TAMPILKAN DAFTAR SURAT TUGAS
    public function index()
    {
        $suratTugas = SuratTugas::with('user')->latest()->get();
        return view('admin.surat_tugas.index', compact('suratTugas'));
    }

    // 2. FORM TAMBAH SURAT
    public function create()
    {
        // Ambil daftar user yang rolenya 'pengawas'
        $pengawas = User::where('role', 'pengawas')->get();
        return view('admin.surat_tugas.create', compact('pengawas'));
    }

    // 3. SIMPAN DATA KE DATABASE
    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat' => 'required|unique:surat_tugas,nomor_surat',
            'judul_tugas' => 'required',
            'user_id' => 'required|exists:users,id',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
        ]);

        // Simpan (File path kita kosongkan dulu/strip jika belum ada upload PDF fisik)
        SuratTugas::create([
            'nomor_surat' => $request->nomor_surat,
            'judul_tugas' => $request->judul_tugas,
            'user_id' => $request->user_id,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'file_path' => '-' // Sementara strip dulu
        ]);

        return redirect()->route('admin.surat-tugas.index')->with('success', 'Surat Tugas Berhasil Dibuat');
    }

    // 4. HAPUS DATA
    public function destroy($id)
    {
        SuratTugas::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data dihapus');
    }
}