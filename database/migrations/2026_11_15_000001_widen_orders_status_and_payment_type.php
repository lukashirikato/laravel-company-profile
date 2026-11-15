<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom `orders.status` dan `orders.payment_type` sebelumnya berupa ENUM
     * sempit sehingga penyimpanan status Midtrans (mis. "active",
     * "waiting_admin") dan metode pembayaran (mis. "bank_transfer", "bca_va")
     * gagal / terpotong. Perluas menjadi VARCHAR tanpa menghapus data.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `orders` MODIFY `payment_type` VARCHAR(50) NULL DEFAULT NULL");
            DB::statement("ALTER TABLE `orders` MODIFY `status` VARCHAR(30) NOT NULL DEFAULT 'pending'");

            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_type', 50)->nullable()->change();
            $table->string('status', 30)->default('pending')->change();
        });
    }

    public function down(): void
    {
        // Sengaja tidak mengembalikan ke ENUM: data lama bisa berisi nilai
        // yang tidak valid untuk ENUM sempit dan akan terpotong.
    }
};
