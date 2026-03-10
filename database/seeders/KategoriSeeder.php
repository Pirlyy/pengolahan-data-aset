<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama sebelum seed ulang
        Kategori::truncate();

        $kategoris = [
            [
                'nama'      => 'Tanah',
                'kode'      => 'TNH',
                'deskripsi' => 'Aset berupa lahan atau tanah milik instansi',
                'icon'      => 'map',
            ],
            [
                'nama'      => 'Bangunan',
                'kode'      => 'BNG',
                'deskripsi' => 'Aset berupa gedung, kantor, gudang, dan konstruksi permanen',
                'icon'      => 'building',
            ],
            [
                'nama'      => 'Kendaraan',
                'kode'      => 'KND',
                'deskripsi' => 'Aset berupa kendaraan operasional roda dua maupun roda empat',
                'icon'      => 'car',
            ],
            [
                'nama'      => 'Peralatan',
                'kode'      => 'PRL',
                'deskripsi' => 'Aset berupa peralatan kerja, mesin, dan perlengkapan teknis',
                'icon'      => 'tool',
            ],
            [
                'nama'      => 'Elektronik',
                'kode'      => 'ELK',
                'deskripsi' => 'Aset berupa perangkat elektronik seperti komputer, laptop, printer',
                'icon'      => 'monitor',
            ],
            [
                'nama'      => 'Furnitur',
                'kode'      => 'FRN',
                'deskripsi' => 'Aset berupa meja, kursi, lemari, dan perlengkapan kantor lainnya',
                'icon'      => 'grid',
            ],
            [
                'nama'      => 'Infrastruktur',
                'kode'      => 'INF',
                'deskripsi' => 'Aset berupa jaringan, server, dan infrastruktur IT',
                'icon'      => 'server',
            ],
            [
                'nama'      => 'Fasilitas',
                'kode'      => 'FSL',
                'deskripsi' => 'Aset berupa AC, genset, dan fasilitas pendukung operasional',
                'icon'      => 'zap',
            ],
        ];

        foreach ($kategoris as $k) {
            Kategori::create($k);
        }

        $this->command->info('✅ Kategori berhasil di-seed: ' . count($kategoris) . ' data');
    }
}