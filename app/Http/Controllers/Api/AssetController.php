<?php

use App\Models\Asset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class AssetController extends Controller
{
    // Pengadaan (Create Asset)
    public function store(Request $request)
    {
        $asset = Asset::create([
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'lokasi' => $request->lokasi,
            'status' => 'aktif',
            'tanggal_pengadaan' => now()
        ]);

        return response()->json($asset);
    }

    // Mutasi (update lokasi)
    public function update(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);
        $asset->update([
            'lokasi' => $request->lokasi
        ]);

        return response()->json($asset);
    }

    // Pemeliharaan
    public function maintenance($id)
    {
        $asset = Asset::findOrFail($id);
        $asset->update([
            'status' => 'maintenance'
        ]);

        return response()->json($asset);
    }
}