<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\KategoriController;

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
    // ⚠️ /aset/statistik harus di atas /aset/{id}
    Route::get('/aset/statistik',   [AssetController::class, 'statistik']);
    Route::get('/aset',             [AssetController::class, 'index']);
    Route::get('/aset/{id}',        [AssetController::class, 'show']);
    Route::post('/aset',            [AssetController::class, 'store']);
    Route::put('/aset/{id}',        [AssetController::class, 'update']);
    Route::delete('/aset/{id}',     [AssetController::class, 'destroy']);

    // ── Kategori ──────────────────────────────────────────
    // ⚠️ /kategori/statistik harus di atas /kategori/{id}
    Route::get('/kategori/statistik',  [KategoriController::class, 'statistik']);
    Route::get('/kategori',            [KategoriController::class, 'index']);
    Route::get('/kategori/{id}',       [KategoriController::class, 'show']);
    Route::post('/kategori',           [KategoriController::class, 'store']);
    Route::put('/kategori/{id}',       [KategoriController::class, 'update']);
    Route::delete('/kategori/{id}',    [KategoriController::class, 'destroy']);

});