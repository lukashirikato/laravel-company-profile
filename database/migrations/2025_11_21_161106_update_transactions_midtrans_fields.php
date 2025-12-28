<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            // Jika kolom belum ada, tambahkan
            if (!Schema::hasColumn('transactions', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable()->after('id');
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            }

            if (!Schema::hasColumn('transactions', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('status');
            }

            if (!Schema::hasColumn('transactions', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('payment_type');
            }

            if (!Schema::hasColumn('transactions', 'midtrans_transaction_id')) {
                $table->string('midtrans_transaction_id')->nullable()->after('transaction_id');
            }

            if (!Schema::hasColumn('transactions', 'fraud_status')) {
                $table->string('fraud_status')->nullable()->after('midtrans_transaction_id');
            }

            if (!Schema::hasColumn('transactions', 'signature_key')) {
                $table->string('signature_key')->nullable()->after('fraud_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn([
                'order_id',
                'payment_type',
                'transaction_id',
                'midtrans_transaction_id',
                'fraud_status',
                'signature_key',
            ]);
        });
    }
};
