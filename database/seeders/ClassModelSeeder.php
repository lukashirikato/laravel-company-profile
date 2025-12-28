<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassModel;

class ClassModelSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Muaythai Intermediate MIX
            ['class_name' => 'Muaythai Intermediate Mix Class 1', 'level' => 'Intermediate'],
            ['class_name' => 'Muaythai Intermediate Mix Class 2', 'level' => 'Intermediate'],
            ['class_name' => 'Muaythai Intermediate Mix Class 3', 'level' => 'Intermediate'],
            ['class_name' => 'Muaythai Intermediate Mix Class 4', 'level' => 'Intermediate'],

            // Pilates MIX
            ['class_name' => 'Pilates Mix Class 1', 'level' => 'Intermediate'],
            ['class_name' => 'Pilates Mix Class 2', 'level' => 'Intermediate'],
            ['class_name' => 'Pilates Mix Class 3', 'level' => 'Intermediate'],
            ['class_name' => 'Pilates Mix Class 4', 'level' => 'Intermediate'],
        ];

        foreach ($data as $item) {
            ClassModel::create($item);
        }
    }
}
