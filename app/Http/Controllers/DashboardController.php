<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Pengawasan;
use App\Models\SuratTugas;

class DashboardController extends Controller
{
    /**
     * 1. DASHBOARD ADMIN
     * Fokus: Manajemen User & Pengaturan Sistem
     */
    public function index()
{
    $role = Auth::user()->role;
    
    if ($role == 'admin') {
        return redirect()->route('dashboard.admin');
    } elseif ($role == 'ketua') {
        return redirect()->route('dashboard.ketua');
    } elseif ($role == 'pengawas') {
        return redirect()->route('dashboard.pengawas');
    } else {
        return abort(403, 'Role tidak dikenali');
    }
}
    public function indexAdmin()
    {
        // 1. CEK KEAMANAN (Manual Gatekeeper)
        // Hanya Admin (Ketua Tim) dan Ketua (Kepala BPS) yang boleh lihat halaman ini
        if (Auth::user()->role == 'pengawas') {
            return redirect()->route('dashboard.pengawas');
        }

        // 2. LOGIKA UTAMA (Sama seperti sebelumnya)
        $totalUser = User::count();
        $totalPengawas = User::where('role', 'pengawas')->count();
        $totalLaporan = Pengawasan::count();

        // Logika Filter Bulan (yang baru saja kita buat)
        $query = Pengawasan::with('user')->orderBy('created_at', 'desc');
        if (request()->filled('bulan_filter')) {
            $parts = explode('-', request()->bulan_filter);
            $query->whereYear('tanggal_kegiatan', $parts[0])->whereMonth('tanggal_kegiatan', $parts[1]);
        }
        $seluruhLaporan = $query->get();

        return view('dashboard.admin', compact('totalUser', 'totalPengawas', 'totalLaporan', 'seluruhLaporan'));
    }

    /**
     * 2. DASHBOARD KETUA
     * Fokus: Monitoring Global & Statistik
     */
    public function indexKetua()
    {
        // a. Hitung Ringkasan Data
        $totalKegiatan = Pengawasan::count();
        $totalPengawas = User::where('role', 'pengawas')->count();
        $kegiatanBulanIni = Pengawasan::whereMonth('tanggal_kegiatan', date('m'))
                            ->whereYear('tanggal_kegiatan', date('Y'))
                            ->count();

        // b. Ambil 5 Laporan Terbaru (Eager Loading dengan 'suratTugas.user' agar hemat query)
        $laporanTerbaru = Pengawasan::with('user') 
                            ->latest()
                            ->take(5)
                            ->get();

        return view('dashboard.ketua', compact(
            'totalKegiatan', 
            'totalPengawas', 
            'kegiatanBulanIni', 
            'laporanTerbaru'
        ));
    }

    /**
     * 3. DASHBOARD PENGAWAS
     * Fokus: Tugas Pribadi & Upload Laporan
     */
    public function indexPengawas()
    {
        $userId = Auth::id();

        // LOGIKA BARU: 
        // Tidak perlu ambil SuratTugas dari admin lagi.
        // Cukup ambil riwayat laporan (Pengawasan) yang pernah dibuat user ini.
        
        $riwayatPengawasan = Pengawasan::where('user_id', $userId)
                                ->orderBy('tanggal_kegiatan', 'desc')
                                ->get();

        // Variable 'suratTugasSaya' kita HAPUS karena sudah tidak dipakai
        return view('dashboard.pengawas', compact('riwayatPengawasan'));
    }
}