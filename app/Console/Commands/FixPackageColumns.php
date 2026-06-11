<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixPackageColumns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'packages:fix-columns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add missing package variant columns';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'package_group')) {
                $table->string('package_group')->nullable()->after('type');
            }

            if (!Schema::hasColumn('packages', 'variant_label')) {
                $table->string('variant_label')->nullable()->after('package_group');
            }

            if (!Schema::hasColumn('packages', 'participant_count')) {
                $table->unsignedTinyInteger('participant_count')->default(1)->after('variant_label');
            }
        });

        $this->info('Package columns are ready.');

        return Command::SUCCESS;
    }
}
