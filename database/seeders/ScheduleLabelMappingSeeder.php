<?php

namespace Database\Seeders;

use App\Models\ClassGroup;
use App\Models\ScheduleLabelMapping;
use Illuminate\Database\Seeder;

class ScheduleLabelMappingSeeder extends Seeder
{
    private const INITIAL_LABELS = [
        'Muaythai Intermediate' => 'Muaythai',
        'Mat Pilates'           => 'Mat Pilates',
        'Mix Class (1)'         => 'Mix Class 1',
        'Mix Class (2)'         => 'Mix Class 2',
        'Mix Class (3)'         => 'Mix Class 3',
        'Mix Class (4)'         => 'Mix Class 4',
        'Muaythai Beginner'     => 'Muaythai',
    ];

    public function run(): void
    {
        foreach (self::INITIAL_LABELS as $label => $groupName) {
            $groupId = null;

            if ($groupName) {
                $group = ClassGroup::where('name', $groupName)->first();
                $groupId = $group?->id;
            }

            ScheduleLabelMapping::firstOrCreate(
                ['label' => $label],
                ['class_group_id' => $groupId]
            );
        }

        $this->command?->info('Schedule label mappings seeded successfully.');
    }
}
