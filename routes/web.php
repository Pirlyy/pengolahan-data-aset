<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
 use Illuminate\Support\Facades\DB;

Route::get('/test-mongo', function () {
    DB::connection('mongodb')->table('test')->insert([
        'nama' => 'Firly',
        'created_at' => now()
    ]);

    return "Data berhasil masuk MongoDB!";
});

