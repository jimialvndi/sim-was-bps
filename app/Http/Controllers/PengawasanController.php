<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengawasan;
use App\Models\SuratTugas;
use App\Models\Dokumentasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

// HAPUS library Intervention Image karena Cloudinary sudah otomatis mengurus gambar
// use Intervention\Image\ImageManager;
// use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class PengawasanController extends Controller
{
    // 1. TAMPILKAN FORM
    public function create()
    {
        return view('pengawasan.create');
    }

    // 2. SIMPAN DATA (MIGRASI KE CLOUDINARY)
    public function store(Request $request)
    {
        // Setup Memory Limit
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        $request->validate([
            'nomor_surat' => 'required',
            'judul_kegiatan' => 'required',
            'tanggal_kegiatan' => 'required|date',
            // Validasi file tetap sama
            'scan_surat_tugas' => 'required|mimes:pdf,jpg,jpeg,png|max:10240', // Max 10MB (Cloudinary kuat nampung besar)
            'permasalahan.*' => 'nullable|string',
            'solusi.*' => 'nullable|string',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:10240' // Max 10MB
        ]);

        DB::beginTransaction();
        try {
            // --- BAGIAN 1: UPLOAD SCAN SURAT KE CLOUDINARY ---
            // Fungsi 'storeOnCloudinary' otomatis upload & dapatkan link https
            $uploadSurat = $request->file('scan_surat_tugas')->storeOnCloudinary('arsip_surat');
            
            // Ambil URL aman (https) dan Public ID (jika nanti butuh hapus)
            $urlSurat = $uploadSurat->getSecurePath();
            $idSurat  = $uploadSurat->getPublicId();

            // Format JSON untuk permasalahan & solusi
            $permasalahanJson = json_encode(array_values(array_filter($request->permasalahan ?? [], fn($value) => !is_null($value) && $value !== '')));
            $solusiJson = json_encode(array_values(array_filter($request->solusi ?? [], fn($value) => !is_null($value) && $value !== '')));

            // Simpan ke Database
            $pengawasan = Pengawasan::create([
                'user_id' => Auth::id(),
                'nomor_surat' => $request->nomor_surat,
                'judul_kegiatan' => $request->judul_kegiatan,
                'tanggal_kegiatan' => $request->tanggal_kegiatan,
                'kecamatan' => $request->kecamatan,
                'desa_kelurahan' => $request->desa_kelurahan,
                'objek_pengawasan' => $request->objek_pengawasan,
                'hasil_temuan' => '-',
                'permasalahan' => $permasalahanJson,
                'solusi_saran' => $solusiJson,
                'scan_surat_tugas_path' => $urlSurat, // ISI LANGSUNG DENGAN URL HTTPS
            ]);

            // --- BAGIAN 2: UPLOAD FOTO DOKUMENTASI KE CLOUDINARY ---
            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $file) {
                    // Upload ke folder 'dokumentasi' di Cloudinary
                    // Cloudinary otomatis resize/optimasi di server mereka, jadi kita tidak butuh ImageManager lokal lagi
                    $uploadFoto = $file->storeOnCloudinary('dokumentasi');
                    
                    $urlFoto = $uploadFoto->getSecurePath();
                    $idFoto  = $uploadFoto->getPublicId();

                    Dokumentasi::create([
                        'pengawasan_id' => $pengawasan->id,
                        'foto_path' => $urlFoto, // Simpan URL HTTPS
                        'keterangan' => 'Dokumentasi' // Bisa Anda tambah kolom public_id di DB jika mau, opsional
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('dashboard.pengawas')->with('success', 'Laporan berhasil disimpan di Cloudinary!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['msg' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    // 3. DOWNLOAD PDF NOTULEN
    public function cetakNotulen($id)
    {
        // Pastikan PHP boleh baca URL remote (untuk gambar logo/ttd jika ada)
        ini_set('allow_url_fopen', 1);

        $pengawasan = Pengawasan::with('user')->findOrFail($id);
        
        $pdf = Pdf::loadView('pengawasan.pdf_notulen', compact('pengawasan'));
        $pdf->setPaper('a4', 'portrait');
        
        $cleanNomor = str_replace(['/', '\\'], '-', $pengawasan->nomor_surat);
        return $pdf->download('Notulen-' . $cleanNomor . '.pdf');
    }

    // 4. DOWNLOAD PDF DOKUMENTASI
    public function cetakDokumentasi($id)
    {
        ini_set('memory_limit', '512M');
        ini_set('allow_url_fopen', 1); // Wajib ON agar DomPDF bisa ambil gambar dari Cloudinary

        $pengawasan = Pengawasan::with('dokumentasis')->findOrFail($id);
        
        $pdf = Pdf::loadView('pengawasan.pdf_dokumentasi', compact('pengawasan'));
        $pdf->setPaper('a4', 'portrait');
        
        $cleanNomor = str_replace(['/', '\\'], '-', $pengawasan->nomor_surat);
        return $pdf->download('Dokumentasi-' . $cleanNomor . '.pdf');
    }

    // 5. DETAIL (SHOW)
    public function show($id)
    {
        $pengawasan = Pengawasan::with(['user', 'dokumentasis'])->findOrFail($id);
        return view('pengawasan.show', compact('pengawasan'));
    }
}