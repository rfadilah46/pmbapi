<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TahunAjaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create current academic year (2024 Ganjil) as active
        TahunAjaran::create([
            'tahun' => '2024',
            'semester' => 'Ganjil',
            'aktif' => true
        ]);

        // Create next semester
        TahunAjaran::create([
            'tahun' => '2024',
            'semester' => 'Genap',
            'aktif' => false
        ]);

        // Create next year
        TahunAjaran::create([
            'tahun' => '2025',
            'semester' => 'Ganjil',
            'aktif' => false
        ]);
    }
}
