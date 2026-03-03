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
        // Tambah kolom untuk tracking class bookings terpisah dari check-in quota
        Schema::table('orders', function (Blueprint $table) {
            // Kolom untuk sisa kelas yang bisa di-book
            if (!Schema::hasColumn('orders', 'remaining_classes')) {
                $table->integer('remaining_classes')->nullable()->comment('Sisa kelas yang bisa di-book (tidak berkurang saat check-in)');
            }
        });

        // Jika ada order existing, set remaining_classes = remaining_quota
        // Ini memastikan data existing tidak null
        \DB::update('
            UPDATE orders 
            SET remaining_classes = COALESCE(remaining_quota, 0)
            WHERE remaining_classes IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('remaining_classes');
        });
    }
};
