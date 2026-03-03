<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use Illuminate\Support\Facades\Cache;

class OptimizeSchedule extends Command
{
    protected $signature = 'schedule:optimize';
    protected $description = 'Optimize schedule data for Filament';

    public function handle()
    {
        $this->info('🔄 Starting schedule optimization...');

        try {
            // Cache package options
            $packages = \App\Models\Package::orderBy('name')->pluck('name', 'id')->toArray();
            Cache::put('packages_select_options', $packages, 3600);
            $this->info('✓ Cached package options: ' . count($packages) . ' items');

            // Cache class options
            $classes = \App\Models\ClassModel::orderBy('class_name')->pluck('class_name', 'id')->toArray();
            Cache::put('classes_select_options', $classes, 3600);
            $this->info('✓ Cached class options: ' . count($classes) . ' items');

            // Get schedule stats
            $total = Schedule::count();
            $visible = Schedule::where('show_on_landing', 1)->count();
            $hidden = Schedule::where('show_on_landing', 0)->count();

            $this->info('📊 Schedule Statistics:');
            $this->info("  Total Schedules: {$total}");
            $this->info("  Visible on Landing: {$visible}");
            $this->info("  Hidden from Landing: {$hidden}");

            $this->info('✅ Schedule optimization completed successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }
}
