<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Asset extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'assets'; // ← nama collection di MongoDB

    protected $fillable = [
        'kode',
        'nama',
        'kategori',
        'lokasi',
        'nilai',
        'status',
        'deskripsi',
    ];

    protected $casts = [
        'nilai' => 'integer',
    ];
}