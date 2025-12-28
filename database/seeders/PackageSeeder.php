<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('packages')->insert([

            // ===========================
            // Exclusive Class Program
            // ===========================
            [
                'name' => 'Exclusive Class Program',
                'price' => 850000,
                'quota' => null,
                'duration_days' => 30,
                'type' => 'membership',
                'description' => 'Muaythai, Mat Pilates, Body Shaping. Akses unlimited selama 30 hari.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ===========================
            // Reformer Pilates - Single Visit Group Class
            // ===========================
            [
                'name' => 'Reformer Pilates Single Visit (Single)',
                'price' => 400000,
                'quota' => 1,
                'duration_days' => 1,
                'type' => 'session',
                'description' => 'Single visit group class.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Reformer Pilates Single Visit (Double)',
                'price' => 700000,
                'quota' => 2,
                'duration_days' => 1,
                'type' => 'session',
                'description' => 'Double visit group class.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Reformer Pilates Single Visit (Triple)',
                'price' => 900000,
                'quota' => 3,
                'duration_days' => 1,
                'type' => 'session',
                'description' => 'Triple visit group class.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ===========================
            // Single Visit Class
            // ===========================
            [
                'name' => 'Single Visit Class (Single)',
                'price' => 150000,
                'quota' => 1,
                'duration_days' => 1,
                'type' => 'session',
                'description' => 'Single class visit.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Single Visit Class (Bundle 2)',
                'price' => 275000,
                'quota' => 2,
                'duration_days' => 1,
                'type' => 'session',
                'description' => 'Bundle 2 class visit.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Single Visit Class (Bundle 4)',
                'price' => 525000,
                'quota' => 4,
                'duration_days' => 1,
                'type' => 'session',
                'description' => 'Bundle 4 class visit.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ===========================
            // Reformer Pilates Packages
            // ===========================
            [
                'name' => 'Reformer Pilates Package (1 Visit)',
                'price' => 400000,
                'quota' => 1,
                'duration_days' => 1,
                'type' => 'session',
                'description' => 'Single visit Reformer Pilates.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Reformer Pilates Package (4 Sessions / 15 Days)',
                'price' => 1400000,
                'quota' => 4,
                'duration_days' => 15,
                'type' => 'session',
                'description' => '4 sessions Reformer Pilates, berlaku 15 hari.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Reformer Pilates Package (4 Sessions / 30 Days)',
                'price' => 1540000,
                'quota' => 4,
                'duration_days' => 30,
                'type' => 'session',
                'description' => '4 sessions Reformer Pilates, berlaku 30 hari.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Reformer Pilates Package (8 Sessions / 30 Days)',
                'price' => 2200000,
                'quota' => 8,
                'duration_days' => 30,
                'type' => 'session',
                'description' => '8 sessions Reformer Pilates, berlaku 30 hari.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Reformer Pilates Package (8 Sessions / 60 Days)',
                'price' => 2640000,
                'quota' => 8,
                'duration_days' => 60,
                'type' => 'session',
                'description' => '8 sessions Reformer Pilates, berlaku 60 hari.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ===========================
            // Private Programs
            // ===========================
            [
                'name' => 'Private Program',
                'price' => 0,
                'quota' => null,
                'duration_days' => null,
                'type' => 'contact',
                'description' => 'Private Muaythai, Mat Pilates, Body Shaping. Hubungi team kami.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Private Group Program',
                'price' => 0,
                'quota' => null,
                'duration_days' => null,
                'type' => 'contact',
                'description' => 'Private group Muaythai, Mat Pilates, Body Shaping. Hubungi team kami.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
