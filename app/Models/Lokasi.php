<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Lokasi extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'lokasis';

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi'
    ];
}