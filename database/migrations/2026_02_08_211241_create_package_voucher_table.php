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
        if (!Schema::hasTable('package_voucher')) {
            Schema::create('package_voucher', function (Blueprint $table) {
                $table->id();
                
                $table->unsignedBigInteger('package_id');
                $table->unsignedBigInteger('voucher_id');
                
                // Foreign keys
                $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
                $table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('cascade');
                
                // Unique constraint - satu paket tidak bisa terikat voucher yang sama 2x
                $table->unique(['package_id', 'voucher_id']);
                
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_voucher');
    }
};
