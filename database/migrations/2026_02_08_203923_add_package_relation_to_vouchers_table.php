<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tambah kolom di tabel vouchers
        Schema::table('vouchers', function (Blueprint $table) {
            $table->enum('applicable_to', ['all', 'specific'])->default('all')->after('active');
        });

        // Buat tabel pivot untuk relasi many-to-many
        Schema::create('package_voucher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->foreignId('voucher_id')->constrained('vouchers')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['package_id', 'voucher_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('package_voucher');
        
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn('applicable_to');
        });
    }
};