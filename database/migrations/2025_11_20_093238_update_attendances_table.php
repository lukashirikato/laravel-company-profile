<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {

            // Tambah program (nullable dulu)
            if (!Schema::hasColumn('attendances', 'program')) {
                $table->string('program')->nullable()->after('customer_id');
            }

            // Tambah check in
            if (!Schema::hasColumn('attendances', 'check_in_at')) {
                $table->timestamp('check_in_at')->nullable()->after('program');
            }

            // Tambah check out
            if (!Schema::hasColumn('attendances', 'check_out_at')) {
                $table->timestamp('check_out_at')->nullable()->after('check_in_at');
            }

            // Ubah status jadi optional
            if (!Schema::hasColumn('attendances', 'status')) {
                $table->string('status')->nullable()->after('check_out_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['program', 'check_in_at', 'check_out_at']);
        });
    }
};
