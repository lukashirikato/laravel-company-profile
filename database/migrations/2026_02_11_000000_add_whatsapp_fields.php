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
        // Add WhatsApp fields to customers table if doesn't exist
        if (!Schema::hasColumn('customers', 'phone_number')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('phone_number')->nullable()->after('email');
                $table->boolean('whatsapp_notifications_enabled')->default(true)->after('phone_number');
                $table->index('phone_number');
            });
        }

        // Add WhatsApp tracking fields to orders table if doesn't exist
        if (!Schema::hasColumn('orders', 'whatsapp_notification_sent')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->boolean('whatsapp_notification_sent')->default(false)->after('expired_at');
                $table->timestamp('whatsapp_notification_sent_at')->nullable()->after('whatsapp_notification_sent');
            });
        }

        // Create WhatsApp messages log table
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('phone_number');
            $table->longText('message');
            $table->enum('type', ['payment', 'booking', 'reminder', 'other'])->default('other');
            $table->enum('status', ['sent', 'failed', 'pending'])->default('pending');
            $table->integer('retry_count')->default(0);
            $table->json('api_response')->nullable();
            $table->string('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            // Indexes for better query performance
            $table->index('customer_id');
            $table->index('phone_number');
            $table->index('type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop WhatsApp messages table
        Schema::dropIfExists('whatsapp_messages');

        // Remove columns from orders table
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'whatsapp_notification_sent')) {
                    $table->dropColumn(['whatsapp_notification_sent', 'whatsapp_notification_sent_at']);
                }
            });
        }

        // Remove columns from customers table
        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table) {
                if (Schema::hasColumn('customers', 'phone_number')) {
                    $table->dropIndex(['phone_number']);
                    $table->dropColumn(['phone_number', 'whatsapp_notifications_enabled']);
                }
            });
        }
    }
};
