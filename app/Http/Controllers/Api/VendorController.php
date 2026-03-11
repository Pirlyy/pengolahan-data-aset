<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function index()
    {
        $vendor = Vendor::orderBy('nama')->get();

        return response()->json([
            'success'=>true,
            'data'=>$vendor
        ]);
    }

    public function show($id)
    {
        $vendor = Vendor::find($id);

        if(!$vendor){
            return response()->json([
                'success'=>false,
                'message'=>'Vendor tidak ditemukan'
            ],404);
        }

        return response()->json([
            'success'=>true,
            'data'=>$vendor
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nama'=>'required|string|max:100',
            'alamat'=>'nullable|string',
            'telepon'=>'nullable|string',
            'email'=>'nullable|email',
            'kontak_person'=>'nullable|string'
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'errors'=>$validator->errors()
            ],422);
        }

        $vendor = Vendor::create($request->all());

        return response()->json([
            'success'=>true,
            'message'=>'Vendor berhasil dibuat',
            'data'=>$vendor
        ],201);
    }

    public function update(Request $request,$id)
    {
        $vendor = Vendor::find($id);

        if(!$vendor){
            return response()->json([
                'success'=>false,
                'message'=>'Vendor tidak ditemukan'
            ],404);
        }

        $vendor->update($request->all());

        return response()->json([
            'success'=>true,
            'message'=>'Vendor berhasil diupdate',
            'data'=>$vendor
        ]);
    }

    public function destroy($id)
    {
        $vendor = Vendor::find($id);

        if(!$vendor){
            return response()->json([
                'success'=>false,
                'message'=>'Vendor tidak ditemukan'
            ],404);
        }

        $vendor->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Vendor berhasil dihapus'
        ]);
    }
}