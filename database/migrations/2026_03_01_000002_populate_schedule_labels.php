<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Populate schedule_label dari class_name untuk schedules yang label-nya kosong
     */
    public function up(): void
    {
        // Ambil semua schedule yang label-nya kosong
        $schedulesWithoutLabel = DB::table('schedules')
            ->whereNull('schedule_label')
            ->orWhere('schedule_label', '')
            ->get();

        foreach ($schedulesWithoutLabel as $schedule) {
            $label = 'Schedule ' . $schedule->id;
            
            // Jika ada class_id, coba ambil nama class
            if ($schedule->class_id) {
                $class = DB::table('class_models')
                    ->where('id', $schedule->class_id)
                    ->first();
                
                if ($class) {
                    // Format: ClassName + Day (e.g., "Mat Pilates - Friday")
                    $label = $class->class_name . ' - ' . ($schedule->day ?? 'TBA');
                }
            }
            
            // Update dengan label yang di-generate
            DB::table('schedules')
                ->where('id', $schedule->id)
                ->update(['schedule_label' => $label]);
        }
    }

    /**
     * Revert changes
     */
    public function down(): void
    {
        // No rollback needed - hanya populate data
    }
};
