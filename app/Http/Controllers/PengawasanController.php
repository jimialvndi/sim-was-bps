<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengawasan;
use App\Models\SuratTugas;
use App\Models\Dokumentasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Wajib untuk transaksi database
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;




class PengawasanController extends Controller
{
    // 1. TAMPILKAN FORM
    public function create()
    {
        // Tidak perlu ambil data SuratTugas lagi
        return view('pengawasan.create');
    }

    // 2. SIMPAN DATA (LOGIC UTAMA)
    public function store(Request $request)
    {
        // --- TAMBAHKAN 2 BARIS INI DI PALING ATAS ---
        ini_set('memory_limit', '512M'); // Naikkan RAM ke 512MB
        ini_set('max_execution_time', 300); // Naikkan waktu tunggu ke 5 menit
        // --------------------------------------------
        $request->validate([
            'nomor_surat' => 'required',
            'judul_kegiatan' => 'required',
            'tanggal_kegiatan' => 'required|date',
            'scan_surat_tugas' => 'required|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
            // Validasi array (karena inputnya sekarang banyak)
            'permasalahan.*' => 'nullable|string',
            'solusi.*' => 'nullable|string',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:5120'
        ]);

        DB::beginTransaction();
        try {
            // 1. Upload Scan Surat Tugas
            $scanPath = $request->file('scan_surat_tugas')->store('scan_surat', 'public');

            // 2. PROSES DATA ARRAY KE JSON
            // Kita ambil input array, filter yang kosong, lalu encode ke JSON
            $permasalahanJson = json_encode(array_values(array_filter($request->permasalahan ?? [], fn($value) => !is_null($value) && $value !== '')));
            $solusiJson = json_encode(array_values(array_filter($request->solusi ?? [], fn($value) => !is_null($value) && $value !== '')));

            // 3. Simpan Data Pengawasan
            $pengawasan = Pengawasan::create([
                'user_id' => Auth::id(),
                'nomor_surat' => $request->nomor_surat,
                'judul_kegiatan' => $request->judul_kegiatan,
                'tanggal_kegiatan' => $request->tanggal_kegiatan,
                'kecamatan' => $request->kecamatan,
                'desa_kelurahan' => $request->desa_kelurahan,
                'objek_pengawasan' => $request->objek_pengawasan,
                'hasil_temuan' => '-',

                // SIMPAN SEBAGAI JSON
                'permasalahan' => $permasalahanJson,
                'solusi_saran' => $solusiJson,

                'scan_surat_tugas_path' => $scanPath,
            ]);

            // 4. Simpan Foto Dokumentasi (VERSI 3 - BARU)
            if ($request->hasFile('foto')) {
                // Pastikan folder penyimpanan ada
                if (!file_exists(storage_path('app/public/dokumentasi'))) {
                    mkdir(storage_path('app/public/dokumentasi'), 0777, true);
                }

                // Inisialisasi Manager Gambar (Versi 3)
                $manager = new ImageManager(new Driver());

                foreach ($request->file('foto') as $file) {
                    // A. Buat nama file unik
                    $filename = Str::random(20) . '.jpg';
                    
                    // B. Tentukan jalur penyimpanan fisik
                    $path = storage_path('app/public/dokumentasi/' . $filename);

                    // C. KOMPRESI GAMBAR (Syntax Baru)
                    // Baca file gambar
                    $image = $manager->read($file);
                    
                    // Kecilkan ukuran (Scale width 800px, height otomatis menyesuaikan)
                    $image->scale(width: 800);
                    
                    // Simpan dengan kualitas 60%
                    $image->save($path, quality: 60);

                    // D. Simpan path ke database
                    Dokumentasi::create([
                        'pengawasan_id' => $pengawasan->id,
                        'foto_path' => 'dokumentasi/' . $filename,
                        'keterangan' => 'Dokumentasi'
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('dashboard.pengawas')->with('success', 'Laporan berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['msg' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    // --- PDF 1: CETAK NOTULEN (Sesuai Gambar 1) ---
    public function cetakNotulen($id)
    {
        $pengawasan = Pengawasan::with('user')->findOrFail($id);
        $pdf = Pdf::loadView('pengawasan.pdf_notulen', compact('pengawasan'));
        $pdf->setPaper('a4', 'portrait');
        $cleanNomor = str_replace(['/', '\\'], '-', $pengawasan->nomor_surat);
        return $pdf->download('Notulen-'.$cleanNomor.'.pdf');
    }

    // 3. TAMPILKAN DETAIL LAPORAN (HTML)
    // --- PDF 2: CETAK DOKUMENTASI (Sesuai Gambar 2) ---
    public function cetakDokumentasi($id)
    {
        ini_set('memory_limit', '512M');
        $pengawasan = Pengawasan::with('dokumentasis')->findOrFail($id);
        $pdf = Pdf::loadView('pengawasan.pdf_dokumentasi', compact('pengawasan'));
        $pdf->setPaper('a4', 'portrait');
        $cleanNomor = str_replace(['/', '\\'], '-', $pengawasan->nomor_surat);
        return $pdf->download('Dokumentasi-'.$cleanNomor.'.pdf');
    }

    // 3. TAMPILKAN DETAIL LAPORAN (HTML)
    public function show($id)
    {
        // PERBAIKAN:
        // Hapus 'suratTugas.user', ganti menjadi 'user' saja.
        // Karena sekarang tabel pengawasan langsung terhubung ke user (pengawas).
        
        $pengawasan = Pengawasan::with(['user', 'dokumentasis'])->findOrFail($id);
        
        return view('pengawasan.show', compact('pengawasan'));
    }
}
