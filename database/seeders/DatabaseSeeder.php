<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // First create roles
            RoleSeeder::class,
            
            // Then create admin user
            AdminSeeder::class,
            
            // Create master data
            ProgramStudiSeeder::class,
            TahunAjaranSeeder::class,
            BiayaPendaftaranSeeder::class,
            
            // Add sample announcements if needed
            // PengumumanSeeder::class,
        ]);
    }
}
