<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Pengadaan extends Model
{
    protected $connection = 'mongodb'; //db connection name sesuai config/database.php
    protected $collection = 'pengadaans';

    protected $fillable = [
        // Data permintaan
        'kode_pengadaan',
        'nama_aset',
        'kategori',
        'jumlah',
        'estimasi_nilai',
        'lokasi_tujuan',
        'keperluan',
        'prioritas',       // rendah | sedang | tinggi | urgent
        'catatan',

        // Pemohon
        'pemohon',
        'pemohon_id',

        // Status & persetujuan
        'status',          // menunggu | disetujui | ditolak | direvisi | selesai
        'disetujui_oleh',
        'disetujui_id',
        'disetujui_at',
        'catatan_persetujuan',

        // Penolakan
        'ditolak_oleh',
        'ditolak_at',
        'alasan_penolakan',

        // Revisi
        'catatan_revisi',

        // Pencatatan aset
        'aset_id',
        'kode_aset',
        'nilai_aktual',
        'dicatat_oleh',
        'dicatat_at',

        // Riwayat perubahan status (array of objects)
        'riwayat',
    ];

    protected $casts = [
        'jumlah'         => 'integer',
        'estimasi_nilai' => 'float',
        'nilai_aktual'   => 'float',
        'riwayat'        => 'array',
    ];
}