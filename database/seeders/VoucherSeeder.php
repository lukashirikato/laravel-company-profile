<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoucherSeeder extends Seeder
{
    public function run()
    {
        DB::table('vouchers')->insert([
            [
                'code' => 'SEHATBET',
                'type' => 'percent',
                'value' => 10,
                'max_discount' => 50000,
                'usage_limit' => 999,
                'used_count' => 0,
                'valid_from' => '2025-01-01',
                'valid_until' => '2025-12-31',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'code' => 'BUGARBET',
                'type' => 'nominal',
                'value' => 50000,
                'max_discount' => null,
                'usage_limit' => 50,
                'used_count' => 0,
                'valid_from' => '2025-01-01',
                'valid_until' => '2025-12-31',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
