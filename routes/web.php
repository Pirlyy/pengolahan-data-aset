<?php

use Illuminate\Support\Facades\Route;

//redirect root ke login
Route::redirect('/', '/login');

// ✅ Login & Register — tanpa middleware apapun
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

// ✅ Dashboard — tanpa middleware auth session
// Proteksi dilakukan di sisi JavaScript (cek token di localStorage)
Route::view('/dashboard', 'dashboard')->name('dashboard');