<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\PengadaanController;

// ── Route Publik (tanpa token) ─────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// ── Route Privat (butuh JWT token) ────────────────────────
Route::middleware('auth:api')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('/logout',  [AuthController::class, 'logout']);
        Route::get('/me',       [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });

    // ── Aset ──────────────────────────────────────────────
    Route::get('/aset/statistik',  [AssetController::class, 'statistik']);
    Route::get('/aset',            [AssetController::class, 'index']);
    Route::get('/aset/{id}',       [AssetController::class, 'show']);
    Route::post('/aset',           [AssetController::class, 'store']);
    Route::put('/aset/{id}',       [AssetController::class, 'update']);
    Route::delete('/aset/{id}',    [AssetController::class, 'destroy']);

    // ── Kategori ──────────────────────────────────────────
    Route::get('/kategori/statistik', [KategoriController::class, 'statistik']);
    Route::get('/kategori',           [KategoriController::class, 'index']);
    Route::get('/kategori/{id}',      [KategoriController::class, 'show']);
    Route::post('/kategori',          [KategoriController::class, 'store']);
    Route::put('/kategori/{id}',      [KategoriController::class, 'update']);
    Route::delete('/kategori/{id}',   [KategoriController::class, 'destroy']);

    // ── Pengadaan ─────────────────────────────────────────
    // ⚠️ Route spesifik harus di atas route dengan {id}
    Route::get('/pengadaan/statistik',        [PengadaanController::class, 'statistik']);  // Statistik
    Route::get('/pengadaan',                  [PengadaanController::class, 'index']);       // List semua
    Route::post('/pengadaan',                 [PengadaanController::class, 'store']);       // Step 1: Input permintaan
    Route::get('/pengadaan/{id}',             [PengadaanController::class, 'show']);        // Detail
    Route::put('/pengadaan/{id}/setujui',     [PengadaanController::class, 'setujui']);    // Step 2a: Setujui
    Route::put('/pengadaan/{id}/tolak',       [PengadaanController::class, 'tolak']);      // Step 2b: Tolak
    Route::put('/pengadaan/{id}/revisi',      [PengadaanController::class, 'revisi']);     // Step 2c: Revisi
    Route::post('/pengadaan/{id}/catat-aset', [PengadaanController::class, 'catatAset']); // Step 3: Catat aset
    Route::delete('/pengadaan/{id}',          [PengadaanController::class, 'destroy']);    // Hapus

});