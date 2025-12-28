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
        // Panggil seeder yang kamu buat
        $this->call([
            CustomerSeeder::class, VoucherSeeder::class
        ]);

        // Jika ada seeder lain di masa depan, tinggal tambahkan di sini
        // Example:
        // UserSeeder::class,
        // ProductSeeder::class,
    }
}
