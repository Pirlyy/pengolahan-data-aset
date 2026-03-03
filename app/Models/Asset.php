<?php

use MongoDB\Laravel\Eloquent\Model;

class Asset extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'assets';

    protected $fillable = [
        'nama',
        'kategori',
        'lokasi',
        'status',
        'tanggal_pengadaan'
    ];
}