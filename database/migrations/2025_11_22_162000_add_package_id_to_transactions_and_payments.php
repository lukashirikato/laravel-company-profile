<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add package_id to transactions table
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                if (!Schema::hasColumn('transactions', 'package_id')) {
                    $table->unsignedBigInteger('package_id')->nullable()->after('customer_id');
                    $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null');
                }
            });
        }

        // If payments table exists, add package_id as well
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (!Schema::hasColumn('payments', 'package_id')) {
                    $table->unsignedBigInteger('package_id')->nullable()->after('customer_id');
                    $table->foreign('package_id')->references('id')->on('packages')->onDelete('set null');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                if (Schema::hasColumn('transactions', 'package_id')) {
                    $table->dropForeign(['package_id']);
                    $table->dropColumn('package_id');
                }
            });
        }

        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (Schema::hasColumn('payments', 'package_id')) {
                    $table->dropForeign(['package_id']);
                    $table->dropColumn('package_id');
                }
            });
        }
    }
};
