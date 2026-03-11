<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lokasi;
use Illuminate\Support\Facades\Validator;

class LokasiController extends Controller
{
    public function index()
    {
        $lokasi = Lokasi::orderBy('nama')->get();

        return response()->json([
            'success' => true,
            'data' => $lokasi
        ]);
    }

    public function show($id)
    {
        $lokasi = Lokasi::find($id);

        if(!$lokasi){
            return response()->json([
                'success'=>false,
                'message'=>'Lokasi tidak ditemukan'
            ],404);
        }

        return response()->json([
            'success'=>true,
            'data'=>$lokasi
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama'=>'required|string|max:100',
            'kode'=>'required|string|max:10',
            'deskripsi'=>'nullable|string'
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'errors'=>$validator->errors()
            ],422);
        }

        $lokasi = Lokasi::create($request->all());

        return response()->json([
            'success'=>true,
            'message'=>'Lokasi berhasil dibuat',
            'data'=>$lokasi
        ],201);
    }

    public function update(Request $request,$id)
    {
        $lokasi = Lokasi::find($id);

        if(!$lokasi){
            return response()->json([
                'success'=>false,
                'message'=>'Lokasi tidak ditemukan'
            ],404);
        }

        $lokasi->update($request->all());

        return response()->json([
            'success'=>true,
            'message'=>'Lokasi berhasil diupdate',
            'data'=>$lokasi
        ]);
    }

    public function destroy($id)
    {
        $lokasi = Lokasi::find($id);

        if(!$lokasi){
            return response()->json([
                'success'=>false,
                'message'=>'Lokasi tidak ditemukan'
            ],404);
        }

        $lokasi->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Lokasi berhasil dihapus'
        ]);
    }
}