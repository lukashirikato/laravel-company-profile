<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            $table->id();

            $table->string('name');
            $table->string('email')->unique();

            $table->timestamp('email_verified_at')->nullable();

            $table->string('password');

            // Role Admin / Staff
            $table->string('role')->default('member');

            // Admin access
            $table->boolean('is_admin')->default(false);

            // Approval system
            $table->boolean('is_approved')->default(false);

            // Optional
            $table->string('phone')->nullable();

            // Optional verification
            $table->boolean('is_verified')->default(false);

            $table->rememberToken();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};