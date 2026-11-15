<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // Dipanggil oleh:  php artisan db:seed
    public function run(): void
    {
        $this->call(ProgramSeeder::class);
    }
}
