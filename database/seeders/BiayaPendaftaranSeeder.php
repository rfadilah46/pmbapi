<?php

namespace Database\Seeders;

use App\Models\BiayaPendaftaran;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BiayaPendaftaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create active registration fee
        BiayaPendaftaran::create([
            'nominal' => 150000.00,
            'deskripsi' => 'Biaya pendaftaran mahasiswa baru tahun 2024',
            'aktif' => true
        ]);

        // Create historical registration fees
        BiayaPendaftaran::create([
            'nominal' => 125000.00,
            'deskripsi' => 'Biaya pendaftaran mahasiswa baru tahun 2023',
            'aktif' => false
        ]);
    }
}
