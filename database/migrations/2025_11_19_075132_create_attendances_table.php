<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade'); // relasi ke tabel customers
            $table->date('date'); // tanggal presensi
            $table->time('check_in')->nullable(); // jam masuk
            $table->time('check_out')->nullable(); // jam keluar
            $table->string('status')->default('present'); // hadir, izin, alpha, dsb
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
