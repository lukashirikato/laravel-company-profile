<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Add the package_id column if it's missing
            if (!Schema::hasColumn('transactions', 'package_id')) {
                $table->unsignedBigInteger('package_id')->nullable()->after('customer_id');
                $table->index('package_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'package_id')) {
                // Dropping the column will also remove the index
                $table->dropColumn('package_id');
            }
        });
    }
};
