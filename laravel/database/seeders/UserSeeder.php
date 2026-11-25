<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/*
 * Membuat user untuk menguji login JWT.
 * Email    : user@example.com
 * Password : password
 * (Hanya untuk latihan di komputer sendiri; jangan dipakai pada aplikasi yang sebenarnya.)
 *
 * Password disimpan dalam bentuk HASH di database, bukan teks asli.
 * Aman dijalankan berulang kali: updateOrCreate mencocokkan berdasarkan email.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name'     => 'User',
                'password' => Hash::make('password'),
            ]
        );
    }
}
