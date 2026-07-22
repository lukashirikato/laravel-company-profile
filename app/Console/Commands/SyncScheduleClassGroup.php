<?php

namespace App\Console\Commands;

use App\Models\Schedule;
use App\Models\ScheduleLabelMapping;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncScheduleClassGroup extends Command
{
    protected $signature = 'schedules:sync-class-group';

    protected $description = 'Isi class_group_id di tabel schedules berdasarkan class_id atau mapping Schedule Label dari database';

    public function handle()
    {
        $this->info('Mulai sinkronisasi class_group_id ke schedules...');

        // FASE 1: Isi dari class_id (relasi ke class_models)
        $updatedFromClass = Schedule::query()
            ->whereNull('class_group_id')
            ->whereNotNull('class_id')
            ->update([
                'class_group_id' => DB::raw('(
                    SELECT class_group_id
                    FROM class_models
                    WHERE class_models.id = schedules.class_id
                )'),
            ]);

        $this->info("Fase 1 (via class_id): {$updatedFromClass} jadwal diperbarui.");

        // FASE 2: Isi dari schedule_label (mapping dari tabel schedule_label_mappings)
        $updatedFromLabel = 0;
        $mappings = ScheduleLabelMapping::whereNotNull('class_group_id')
            ->pluck('class_group_id', 'label');

        if ($mappings->isNotEmpty()) {
            $schedulesWithoutGroup = Schedule::whereNull('class_group_id')
                ->whereNotNull('schedule_label')
                ->get();

            foreach ($schedulesWithoutGroup as $schedule) {
                $groupId = $mappings[$schedule->schedule_label] ?? null;

                if ($groupId) {
                    $schedule->class_group_id = $groupId;
                    $schedule->save();
                    $updatedFromLabel++;
                }
            }
        }

        $this->info("Fase 2 (via schedule_label): {$updatedFromLabel} jadwal diperbarui.");

        // TOTAL
        $totalUpdated = $updatedFromClass + $updatedFromLabel;
        $scheduledWithGroup = Schedule::whereNotNull('class_group_id')->count();
        $totalSchedules = Schedule::count();

        $this->table(
            ['Metric', 'Value'],
            [
                ['Total schedules', $totalSchedules],
                ['Sudah punya class_group_id', $scheduledWithGroup],
                ['Belum punya class_group_id', $totalSchedules - $scheduledWithGroup],
                ['Diperbarui via class_id', $updatedFromClass],
                ['Diperbarui via schedule_label', $updatedFromLabel],
            ]
        );

        return Command::SUCCESS;
    }
}
