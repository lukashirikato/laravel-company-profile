<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();

            // Nama package
            $table->string('name');

            // Harga dalam angka (IDR)
            $table->integer('price');

            // Kuota sesi (boleh null, karena beberapa paket unlimited)
            $table->integer('quota')->nullable();

            // Durasi hari (boleh null)
            $table->integer('duration_days')->nullable();

            // Jenis paket: monthly / visit / sessions / private / group
            $table->string('type')->nullable();

            // Deskripsi tambahan (opsional)
            $table->text('description')->nullable();

            // Slug untuk SEO & routing
            $table->string('slug')->unique()->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
