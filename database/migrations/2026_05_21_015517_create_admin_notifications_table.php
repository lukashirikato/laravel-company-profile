<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type', 60);          // member_registered, payment_success, dll
            $table->string('icon', 30)->nullable();
            $table->string('color', 20)->default('cherry'); // cherry|pink|green|amber|red
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();    // payload bebas (link, ids, dll)
            $table->unsignedBigInteger('related_id')->nullable();
            $table->string('related_type', 80)->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index('type');
            $table->index('read_at');
            $table->index(['related_type', 'related_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};
