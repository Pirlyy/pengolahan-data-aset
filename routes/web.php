<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::view('/login','auth.login')->name('login');
Route::view('/register','auth.register')->name('register');