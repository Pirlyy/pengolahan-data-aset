<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengadaan;
use App\Models\Asset;
use App\Models\Kategori;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PengadaanController extends Controller
{
    // ── Ambil user yang sedang login ──
    private function currentUser()
    {
        return JWTAuth::parseToken()->authenticate();
    }

    // ── GET /api/pengadaan ─────────────────────────────────
    // Ambil semua pengadaan (support filter status & search)
    public function index(Request $request)
    {
        try {
            $query = Pengadaan::query();

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter by kategori
            if ($request->filled('kategori')) {
                $query->where('kategori', $request->kategori);
            }

            // Search by nama atau kode
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama_aset',      'like', "%{$search}%")
                      ->orWhere('kode_pengadaan','like', "%{$search}%")
                      ->orWhere('pemohon',       'like', "%{$search}%");
                });
            }

            $pengadaan = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'total'   => $pengadaan->count(),
                'data'    => $pengadaan
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data pengadaan',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ── GET /api/pengadaan/{id} ────────────────────────────
    // Detail satu pengadaan
    public function show($id)
    {
        try {
            $pengadaan = Pengadaan::find($id);

            if (!$pengadaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pengadaan tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data'    => $pengadaan
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail pengadaan',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ── POST /api/pengadaan ────────────────────────────────
    // Step 1: Input permintaan pengadaan baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_aset'    => 'required|string|max:255',
            'kategori'     => 'required|string',
            'jumlah'       => 'required|integer|min:1',
            'estimasi_nilai'=> 'required|numeric|min:0',
            'lokasi_tujuan'=> 'required|string',
            'keperluan'    => 'required|string',
            'prioritas'    => 'required|in:rendah,sedang,tinggi,urgent',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $user = $this->currentUser();

            // Generate kode pengadaan otomatis: PGD-2024-001
            $tahun  = date('Y');
            $urutan = Pengadaan::whereYear('created_at', $tahun)->count() + 1;
            $kode   = 'PGD-' . $tahun . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);

            $pengadaan = Pengadaan::create([
                'kode_pengadaan' => $kode,
                'nama_aset'      => $request->nama_aset,
                'kategori'       => $request->kategori,
                'jumlah'         => $request->jumlah,
                'estimasi_nilai' => $request->estimasi_nilai,
                'lokasi_tujuan'  => $request->lokasi_tujuan,
                'keperluan'      => $request->keperluan,
                'prioritas'      => $request->prioritas,
                'status'         => 'menunggu',   // ← status awal selalu menunggu
                'pemohon'        => $user->name,
                'pemohon_id'     => (string) $user->_id,
                'catatan'        => $request->catatan ?? '',
                'riwayat'        => [
                    [
                        'aksi'      => 'Permintaan diajukan',
                        'oleh'      => $user->name,
                        'waktu'     => now()->toDateTimeString(),
                        'keterangan'=> 'Permintaan pengadaan baru dibuat',
                    ]
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permintaan pengadaan berhasil diajukan',
                'data'    => $pengadaan
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengajukan permintaan',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ── PUT /api/pengadaan/{id}/setujui ───────────────────
    // Step 2: Persetujuan pengadaan (setujui)
    public function setujui(Request $request, $id)
    {
        try {
            $pengadaan = Pengadaan::find($id);

            if (!$pengadaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pengadaan tidak ditemukan'
                ], 404);
            }

            // Hanya bisa disetujui jika masih menunggu atau direvisi
            if (!in_array($pengadaan->status, ['menunggu', 'direvisi'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengadaan dengan status "' . $pengadaan->status . '" tidak bisa disetujui'
                ], 409);
            }

            $user = $this->currentUser();

            // Tambah ke riwayat
            $riwayat   = $pengadaan->riwayat ?? [];
            $riwayat[] = [
                'aksi'      => 'Disetujui',
                'oleh'      => $user->name,
                'waktu'     => now()->toDateTimeString(),
                'keterangan'=> $request->keterangan ?? 'Pengadaan disetujui',
            ];

            $pengadaan->update([
                'status'       => 'disetujui',
                'disetujui_oleh'=> $user->name,
                'disetujui_id' => (string) $user->_id,
                'disetujui_at' => now()->toDateTimeString(),
                'catatan_persetujuan' => $request->keterangan ?? '',
                'riwayat'      => $riwayat,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengadaan berhasil disetujui',
                'data'    => $pengadaan->fresh()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui pengadaan',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ── PUT /api/pengadaan/{id}/tolak ─────────────────────
    // Step 2b: Tolak pengadaan
    public function tolak(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'alasan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Alasan penolakan wajib diisi',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $pengadaan = Pengadaan::find($id);

            if (!$pengadaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pengadaan tidak ditemukan'
                ], 404);
            }

            if (!in_array($pengadaan->status, ['menunggu', 'direvisi'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengadaan dengan status "' . $pengadaan->status . '" tidak bisa ditolak'
                ], 409);
            }

            $user      = $this->currentUser();
            $riwayat   = $pengadaan->riwayat ?? [];
            $riwayat[] = [
                'aksi'      => 'Ditolak',
                'oleh'      => $user->name,
                'waktu'     => now()->toDateTimeString(),
                'keterangan'=> $request->alasan,
            ];

            $pengadaan->update([
                'status'          => 'ditolak',
                'ditolak_oleh'    => $user->name,
                'ditolak_at'      => now()->toDateTimeString(),
                'alasan_penolakan'=> $request->alasan,
                'riwayat'         => $riwayat,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengadaan berhasil ditolak',
                'data'    => $pengadaan->fresh()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak pengadaan',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ── PUT /api/pengadaan/{id}/revisi ────────────────────
    // Step 2c: Minta revisi pengadaan
    public function revisi(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'catatan_revisi' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Catatan revisi wajib diisi',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $pengadaan = Pengadaan::find($id);

            if (!$pengadaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pengadaan tidak ditemukan'
                ], 404);
            }

            if ($pengadaan->status !== 'menunggu') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pengadaan berstatus "menunggu" yang bisa diminta revisi'
                ], 409);
            }

            $user      = $this->currentUser();
            $riwayat   = $pengadaan->riwayat ?? [];
            $riwayat[] = [
                'aksi'      => 'Diminta Revisi',
                'oleh'      => $user->name,
                'waktu'     => now()->toDateTimeString(),
                'keterangan'=> $request->catatan_revisi,
            ];

            $pengadaan->update([
                'status'         => 'direvisi',
                'catatan_revisi' => $request->catatan_revisi,
                'riwayat'        => $riwayat,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permintaan revisi berhasil dikirim',
                'data'    => $pengadaan->fresh()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal meminta revisi',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ── POST /api/pengadaan/{id}/catat-aset ───────────────
    // Step 3: Pencatatan aset baru setelah pengadaan disetujui
    public function catatAset(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_aset'  => 'required|string|unique:assets,kode',
            'nama_aset'  => 'required|string|max:255',
            'lokasi'     => 'required|string',
            'nilai_aktual'=> 'required|numeric|min:0',
            'deskripsi'  => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $pengadaan = Pengadaan::find($id);

            if (!$pengadaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pengadaan tidak ditemukan'
                ], 404);
            }

            // ✅ Hanya bisa dicatat jika sudah disetujui
            if ($pengadaan->status !== 'disetujui') {
                return response()->json([
                    'success' => false,
                    'message' => 'Aset hanya bisa dicatat jika pengadaan sudah disetujui'
                ], 409);
            }

            // ✅ Cek apakah aset sudah pernah dicatat untuk pengadaan ini
            if ($pengadaan->aset_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aset untuk pengadaan ini sudah pernah dicatat'
                ], 409);
            }

            $user = $this->currentUser();

            // Buat aset baru dari data pengadaan
            $aset = Asset::create([
                'kode'        => $request->kode_aset,
                'nama'        => $request->nama_aset,
                'kategori'    => $pengadaan->kategori,
                'lokasi'      => $request->lokasi,
                'nilai'       => $request->nilai_aktual,
                'status'      => 'aktif',
                'deskripsi'   => $request->deskripsi ?? 'Aset dari pengadaan ' . $pengadaan->kode_pengadaan,
            ]);

            // Update status pengadaan menjadi selesai
            $riwayat   = $pengadaan->riwayat ?? [];
            $riwayat[] = [
                'aksi'      => 'Aset Dicatat',
                'oleh'      => $user->name,
                'waktu'     => now()->toDateTimeString(),
                'keterangan'=> 'Aset baru dicatat dengan kode ' . $request->kode_aset,
            ];

            $pengadaan->update([
                'status'       => 'selesai',
                'aset_id'      => (string) $aset->_id,
                'kode_aset'    => $request->kode_aset,
                'nilai_aktual' => $request->nilai_aktual,
                'dicatat_oleh' => $user->name,
                'dicatat_at'   => now()->toDateTimeString(),
                'riwayat'      => $riwayat,
            ]);

            return response()->json([
                'success'  => true,
                'message'  => 'Aset baru berhasil dicatat dari pengadaan',
                'data'     => [
                    'pengadaan' => $pengadaan->fresh(),
                    'aset'      => $aset,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat aset',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ── DELETE /api/pengadaan/{id} ────────────────────────
    // Hapus pengadaan (hanya yang masih menunggu/ditolak)
    public function destroy($id)
    {
        try {
            $pengadaan = Pengadaan::find($id);

            if (!$pengadaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pengadaan tidak ditemukan'
                ], 404);
            }

            // Tidak bisa hapus yang sudah disetujui atau selesai
            if (in_array($pengadaan->status, ['disetujui', 'selesai'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengadaan yang sudah disetujui atau selesai tidak bisa dihapus'
                ], 409);
            }

            $pengadaan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pengadaan berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pengadaan',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ── GET /api/pengadaan/statistik ──────────────────────
    // Statistik pengadaan untuk dashboard
    public function statistik()
    {
        try {
            return response()->json([
                'success' => true,
                'data'    => [
                    'total'     => Pengadaan::count(),
                    'menunggu'  => Pengadaan::where('status', 'menunggu')->count(),
                    'disetujui' => Pengadaan::where('status', 'disetujui')->count(),
                    'ditolak'   => Pengadaan::where('status', 'ditolak')->count(),
                    'direvisi'  => Pengadaan::where('status', 'direvisi')->count(),
                    'selesai'   => Pengadaan::where('status', 'selesai')->count(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}