<?php

namespace App\Console\Commands;

use App\Models\AdminNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanupAdminNotifications extends Command
{
    protected $signature = 'admin-notifications:cleanup {--days=30}';
    protected $description = 'Hapus notifikasi admin yang lebih lama dari N hari (default 30)';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoff = Carbon::now()->subDays($days);

        $deleted = AdminNotification::where('created_at', '<', $cutoff)->delete();

        $this->info("✅ Deleted {$deleted} admin notifications older than {$days} days.");
        return self::SUCCESS;
    }
}
