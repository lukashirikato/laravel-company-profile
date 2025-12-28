<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Relasi ke customer
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

            // Relasi ke package
            $table->unsignedBigInteger('package_id');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');

            // ORDER CODE (WAJIB untuk Midtrans)
            $table->string('order_code')->unique();

            // Amount total pembayaran
            $table->integer('amount');

            // Status pembayaran (fleksibel untuk Midtrans)
            $table->string('status')->default('pending');

            // Metode pembayaran dari Midtrans (bank_transfer, qris, gopay, dll)
            $table->string('payment_type')->nullable();

            // Snap token (optional)
            $table->string('snap_token')->nullable();

            // ID transaksi Midtrans
            $table->string('transaction_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
