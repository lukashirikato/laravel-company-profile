<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Backfill `remaining_quota` for paid/active orders from their package quota.
     *
     * @return void
     */
    public function up(): void
    {
        // Update orders that are paid/active/settlement/success but have no remaining_quota
        DB::statement("UPDATE orders o INNER JOIN packages p ON o.package_id = p.id SET o.remaining_quota = p.quota, o.quota_applied = 1 WHERE o.status IN ('paid','active','settlement','success') AND (o.remaining_quota IS NULL OR o.remaining_quota = 0)");
    }

    /**
     * Reverse the migrations.
     * This will set back remaining_quota to NULL for orders updated by this migration.
     *
     * @return void
     */
    public function down(): void
    {
        DB::statement("UPDATE orders o INNER JOIN packages p ON o.package_id = p.id SET o.remaining_quota = NULL, o.quota_applied = 0 WHERE o.status IN ('paid','active','settlement','success') AND o.remaining_quota = p.quota");
    }
};
