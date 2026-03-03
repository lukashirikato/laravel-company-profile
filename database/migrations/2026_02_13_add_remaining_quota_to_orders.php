<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tambah field remaining_quota
            if (!Schema::hasColumn('orders', 'remaining_quota')) {
                $table->integer('remaining_quota')->nullable()->after('quota_applied');
            }
        });

        // Isi remaining_quota dengan quota dari package untuk orders yang sudah ada
        DB::statement('
            UPDATE orders 
            INNER JOIN packages ON orders.package_id = packages.id
            SET orders.remaining_quota = COALESCE(packages.quota, 0)
            WHERE orders.status = "paid"
        ');
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'remaining_quota')) {
                $table->dropColumn('remaining_quota');
            }
        });
    }
};
