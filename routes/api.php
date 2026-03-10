<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ReportController;


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (JWT)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:api'])->group(function(){

    Route::post('logout',[AuthController::class,'logout']);
    Route::get('me',[AuthController::class,'me']);

    Route::apiResource('assets',AssetController::class);
    Route::apiResource('users',UserController::class);

    Route::get('reports',[ReportController::class,'index']);

});