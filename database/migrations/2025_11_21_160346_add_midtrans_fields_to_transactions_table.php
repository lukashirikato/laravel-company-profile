<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->after('id');
            $table->string('payment_type')->nullable()->after('description');
            $table->string('midtrans_transaction_id')->nullable()->after('payment_type');
            $table->string('fraud_status')->nullable()->after('midtrans_transaction_id');
            $table->string('signature_key')->nullable()->after('fraud_status');

            // relasi opsional
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn([
                'order_id',
                'payment_type',
                'midtrans_transaction_id',
                'fraud_status',
                'signature_key',
            ]);
        });
    }
};
