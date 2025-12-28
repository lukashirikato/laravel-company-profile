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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // ID pesanan internal
            $table->string('order_id')->unique(); 

            // ID transaksi internal (opsional kalau dipakai di controller)
            $table->string('transaction_id')->unique();

            // Relasi customer
            $table->unsignedBigInteger('customer_id');
            $table->string('customer_name')->nullable();

            // Relasi package
            $table->unsignedBigInteger('package_id')->nullable();

            // Jumlah pembayaran
            $table->decimal('amount', 10, 2);

            // Status transaksi
            $table->string('status', 50)->default('pending');

            $table->string('payment_type')->nullable();
            $table->string('description')->nullable();

            // Khusus Midtrans
            $table->string('midtrans_transaction_id')->nullable(); // transaction_id dari Midtrans
            $table->string('fraud_status')->nullable();            // accept / deny / challenge
            $table->string('signature_key')->nullable();           // keamanan callback Midtrans

            $table->timestamps();

            // Index
            $table->index('order_id');
            $table->index('customer_id');
            $table->index('package_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
