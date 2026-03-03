<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Tambah kolom untuk auto-checkout feature
            if (!Schema::hasColumn('attendances', 'auto_checkout_at')) {
                $table->timestamp('auto_checkout_at')->nullable()->after('check_out_at')
                    ->comment('Waktu auto-checkout (check_in + 60 menit)');
            }

            if (!Schema::hasColumn('attendances', 'duration_minutes')) {
                $table->integer('duration_minutes')->nullable()->after('auto_checkout_at')
                    ->comment('Durasi latihan dalam menit (check_out - check_in)');
            }

            if (!Schema::hasColumn('attendances', 'checkout_type')) {
                $table->enum('checkout_type', ['manual', 'auto', 'system'])->nullable()->after('duration_minutes')
                    ->comment('Tipe checkout: manual (oleh staff), auto (sistem auto 60 menit), system (force by admin)');
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'checkout_type')) {
                $table->dropColumn('checkout_type');
            }

            if (Schema::hasColumn('attendances', 'duration_minutes')) {
                $table->dropColumn('duration_minutes');
            }

            if (Schema::hasColumn('attendances', 'auto_checkout_at')) {
                $table->dropColumn('auto_checkout_at');
            }
        });
    }
};
