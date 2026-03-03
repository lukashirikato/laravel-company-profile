<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // QR Token untuk scan absensi
            $table->string('qr_token')->nullable()->unique()->after('email');
            $table->timestamp('qr_generated_at')->nullable()->after('qr_token');
            $table->boolean('qr_active')->default(true)->after('qr_generated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['qr_token', 'qr_generated_at', 'qr_active']);
        });
    }
};
