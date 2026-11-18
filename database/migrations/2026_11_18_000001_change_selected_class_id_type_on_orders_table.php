<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * selected_class_id menyimpan key kelas checkout (contoh: "mix_class_1"),
     * bukan ID numerik. Ubah tipe agar tidak gagal insert.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('selected_class_id', 100)->nullable()->change();
        });
    }

    public function down(): void
    {
        // Tidak dikembalikan ke bigint: data string akan terpotong/error.
        // Perubahan tipe ini hanya memperlebar, bukan menghapus data.
    }
};
