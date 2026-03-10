<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ======================
// HALAMAN AWAL
// ======================
Route::get('/', function () {
    return redirect('/login');
});


<<<<<<< HEAD
    return "Data berhasil masuk MongoDB!";
});

=======
// ======================
// AUTH (GUEST ONLY)
// ======================
Route::middleware('guest')->group(function () {

    // Tampilkan Form Login
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    // Proses Login
    Route::post('/login', [AuthController::class, 'login']);

    // Tampilkan Form Register
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    // Proses Register
    Route::post('/register', [AuthController::class, 'register']);
});


// ======================
// DASHBOARD (HARUS LOGIN)
// ======================
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
>>>>>>> 634474f5929f870be46bd431b41851f13ee79a64
