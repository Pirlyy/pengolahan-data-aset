<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Asset;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    // ── GET /api/kategori ──────────────────────────────────
    // Ambil semua kategori + jumlah aset per kategori
    public function index()
    {
        try {
            $kategori = Kategori::orderBy('nama', 'asc')->get();

            // Tambahkan jumlah aset per kategori
            $kategori = $kategori->map(function ($k) {
                $k->jumlah_aset = Asset::where('kategori', $k->nama)->count();
                return $k;
            });

            return response()->json([
                'success' => true,
                'total'   => $kategori->count(),
                'data'    => $kategori
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kategori',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ── GET /api/kategori/{id} ─────────────────────────────
    // Detail satu kategori + list aset di dalamnya
    public function show($id)
    {
        try {
            $kategori = Kategori::find($id);

            if (!$kategori) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak ditemukan'
                ], 404);
            }

            // Ambil semua aset yang termasuk kategori ini
            $aset = Asset::where('kategori', $kategori->nama)
                         ->orderBy('created_at', 'desc')
                         ->get();

            return response()->json([
                'success' => true,
                'data'    => [
                    'kategori'    => $kategori,
                    'jumlah_aset' => $aset->count(),
                    'aset'        => $aset,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail kategori',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ── POST /api/kategori ─────────────────────────────────
    // Tambah kategori baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'        => 'required|string|max:100|unique:kategoris,nama',
            'kode'        => 'required|string|max:10|unique:kategoris,kode',
            'deskripsi'   => 'nullable|string',
            'icon'        => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $kategori = Kategori::create([
                'nama'      => $request->nama,
                'kode'      => strtoupper($request->kode),
                'deskripsi' => $request->deskripsi ?? '',
                'icon'      => $request->icon ?? 'box',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan',
                'data'    => $kategori
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan kategori',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ── PUT /api/kategori/{id} ─────────────────────────────
    // Update kategori
    public function update(Request $request, $id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama'      => 'required|string|max:100|unique:kategoris,nama,' . $id . ',_id',
            'kode'      => 'required|string|max:10|unique:kategoris,kode,' . $id . ',_id',
            'deskripsi' => 'nullable|string',
            'icon'      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $namaLama = $kategori->nama;

            $kategori->update([
                'nama'      => $request->nama,
                'kode'      => strtoupper($request->kode),
                'deskripsi' => $request->deskripsi ?? $kategori->deskripsi,
                'icon'      => $request->icon ?? $kategori->icon,
            ]);

            // ✅ Sinkronisasi nama kategori di semua aset yang menggunakan kategori ini
            if ($namaLama !== $request->nama) {
                Asset::where('kategori', $namaLama)
                     ->update(['kategori' => $request->nama]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil diperbarui',
                'data'    => $kategori->fresh()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui kategori',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ── DELETE /api/kategori/{id} ──────────────────────────
    // Hapus kategori (hanya jika tidak ada aset yang menggunakannya)
    public function destroy($id)
    {
        try {
            $kategori = Kategori::find($id);

            if (!$kategori) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori tidak ditemukan'
                ], 404);
            }

            // ✅ Cegah hapus jika masih ada aset yang menggunakan kategori ini
            $jumlahAset = Asset::where('kategori', $kategori->nama)->count();
            if ($jumlahAset > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Kategori tidak bisa dihapus karena masih digunakan oleh {$jumlahAset} aset.",
                ], 409); // 409 Conflict
            }

            $kategori->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kategori',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ── GET /api/kategori/statistik ────────────────────────
    // Statistik jumlah & nilai aset per kategori (untuk chart dashboard)
    public function statistik()
    {
        try {
            $kategoris = Kategori::orderBy('nama')->get();

            $data = $kategoris->map(function ($k) {
                $aset = Asset::where('kategori', $k->nama)->get();
                return [
                    'nama'        => $k->nama,
                    'kode'        => $k->kode,
                    'icon'        => $k->icon,
                    'jumlah_aset' => $aset->count(),
                    'total_nilai' => $aset->sum('nilai'),
                    'aktif'       => $aset->where('status', 'aktif')->count(),
                    'perbaikan'   => $aset->where('status', 'perbaikan')->count(),
                    'nonaktif'    => $aset->where('status', 'nonaktif')->count(),
                ];
            });

            return response()->json([
                'success' => true,
                'data'    => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik kategori',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}