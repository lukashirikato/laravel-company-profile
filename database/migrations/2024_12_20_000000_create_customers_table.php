<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Basic Info
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number')->unique()->nullable();

            // Membership
            $table->string('program')->nullable();
            $table->integer('quota')->default(0);
            $table->string('membership')->nullable();
            $table->string('preferred_membership')->nullable();

            // Login Member
            $table->string('password')->nullable();
            $table->boolean('is_verified')->default(false);

            // Biodata
            $table->date('birth_date')->nullable();

            // Signup Form
            $table->string('goals')->nullable();
            $table->text('kondisi_khusus')->nullable();
            $table->string('referensi')->nullable();
            $table->text('pengalaman')->nullable();

            // Muslimah
            $table->boolean('is_muslim')->default(false);

            // Voucher
            $table->string('voucher_code')->nullable();

            $table->rememberToken();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};