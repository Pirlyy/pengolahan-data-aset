<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use Illuminate\Support\Facades\Validator;

class AssetController extends Controller
{
    // ── GET /api/aset ──────────────────────────────────────
    // Ambil semua aset (support filter & search)
    public function index(Request $request)
    {
        try {
            $query = Asset::query();

            // Filter by kategori
            if ($request->filled('kategori')) {
                $query->where('kategori', $request->kategori);
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Search by nama, kode, atau lokasi
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama',   'like', "%{$search}%")
                      ->orWhere('kode',   'like', "%{$search}%")
                      ->orWhere('lokasi', 'like', "%{$search}%");
                });
            }

            $aset = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'total'   => $aset->count(),
                'data'    => $aset
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data aset',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ── GET /api/aset/{id} ─────────────────────────────────
    // Ambil detail satu aset
    public function show($id)
    {
        try {
            $aset = Asset::find($id);

            if (!$aset) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aset tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data'    => $aset
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail aset',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ── POST /api/aset ─────────────────────────────────────
    // Tambah aset baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode'      => 'required|string|unique:assets,kode',
            'nama'      => 'required|string|max:255',
            'kategori'  => 'required|string',
            'lokasi'    => 'required|string',
            'nilai'     => 'required|numeric|min:0',
            'status'    => 'required|in:aktif,dipinjam,perbaikan,nonaktif',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $aset = Asset::create([
                'kode'      => $request->kode,
                'nama'      => $request->nama,
                'kategori'  => $request->kategori,
                'lokasi'    => $request->lokasi,
                'nilai'     => $request->nilai,
                'status'    => $request->status,
                'deskripsi' => $request->deskripsi ?? '',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Aset berhasil ditambahkan',
                'data'    => $aset
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan aset',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ── PUT /api/aset/{id} ─────────────────────────────────
    // Update aset
    public function update(Request $request, $id)
    {
        $aset = Asset::find($id);

        if (!$aset) {
            return response()->json([
                'success' => false,
                'message' => 'Aset tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'kode'      => 'required|string|unique:assets,kode,' . $id . ',_id',
            'nama'      => 'required|string|max:255',
            'kategori'  => 'required|string',
            'lokasi'    => 'required|string',
            'nilai'     => 'required|numeric|min:0',
            'status'    => 'required|in:aktif,dipinjam,perbaikan,nonaktif',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $aset->update([
                'kode'      => $request->kode,
                'nama'      => $request->nama,
                'kategori'  => $request->kategori,
                'lokasi'    => $request->lokasi,
                'nilai'     => $request->nilai,
                'status'    => $request->status,
                'deskripsi' => $request->deskripsi ?? $aset->deskripsi,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Aset berhasil diperbarui',
                'data'    => $aset->fresh()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui aset',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ── DELETE /api/aset/{id} ──────────────────────────────
    // Hapus aset
    public function destroy($id)
    {
        try {
            $aset = Asset::find($id);

            if (!$aset) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aset tidak ditemukan'
                ], 404);
            }

            $aset->delete();

            return response()->json([
                'success' => true,
                'message' => 'Aset berhasil dihapus'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus aset',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ── GET /api/aset/statistik ────────────────────────────
    // Statistik untuk dashboard
    public function statistik()
    {
        try {
            $total      = Asset::count();
            $aktif      = Asset::where('status', 'aktif')->count();
            $dipinjam   = Asset::where('status', 'dipinjam')->count();
            $perbaikan  = Asset::where('status', 'perbaikan')->count();
            $nonaktif   = Asset::where('status', 'nonaktif')->count();
            $totalNilai = Asset::sum('nilai');

            return response()->json([
                'success' => true,
                'data'    => [
                    'total'       => $total,
                    'aktif'       => $aktif,
                    'dipinjam'    => $dipinjam,
                    'perbaikan'   => $perbaikan,
                    'nonaktif'    => $nonaktif,
                    'total_nilai' => $totalNilai,
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