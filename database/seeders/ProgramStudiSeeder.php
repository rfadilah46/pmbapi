<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programStudies = [
            [
                'nama' => 'Teknik Informatika',
                'deskripsi' => 'Program studi yang mempelajari tentang teknologi komputer, pengembangan perangkat lunak, dan sistem informasi.'
            ],
            [
                'nama' => 'Sistem Informasi',
                'deskripsi' => 'Program studi yang mempelajari tentang pengelolaan sistem informasi dalam organisasi dan bisnis.'
            ],
            [
                'nama' => 'Manajemen Informatika',
                'deskripsi' => 'Program studi yang mempelajari tentang pengelolaan teknologi informasi dalam konteks bisnis.'
            ],
            [
                'nama' => 'Teknik Komputer',
                'deskripsi' => 'Program studi yang mempelajari tentang perangkat keras komputer dan sistem embedded.'
            ],
            [
                'nama' => 'Teknologi Informasi',
                'deskripsi' => 'Program studi yang mempelajari tentang penerapan teknologi informasi dalam berbagai bidang.'
            ]
        ];

        foreach ($programStudies as $prodi) {
            ProgramStudi::create($prodi);
        }
    }
}
