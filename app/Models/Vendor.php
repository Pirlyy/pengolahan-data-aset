<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Vendor extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'vendors';

    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
        'email',
        'kontak_person'
    ];
}