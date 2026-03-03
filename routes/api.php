<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ReportController;

Route::apiResource('assets', AssetController::class);
Route::apiResource('users', UserController::class);
Route::get('reports', [ReportController::class, 'index']);

Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth.jwt'])->group(function () {
    Route::apiResource('assets', AssetController::class);
    Route::apiResource('users', UserController::class);
});