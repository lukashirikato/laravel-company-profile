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
    Schema::create('vouchers', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique();
        $table->enum('type', ['percent', 'nominal']); // percent (%) or nominal (Rp)
        $table->integer('value'); // 20 -> artinya 20% jika percent, atau 50000 jika nominal
        $table->integer('max_discount')->nullable(); // untuk limit diskon percent
        $table->integer('usage_limit')->nullable(); // null = unlimited
        $table->integer('used_count')->default(0);
        $table->date('valid_from')->nullable();
        $table->date('valid_until')->nullable();
        $table->boolean('active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    
public function down()
{
    Schema::dropIfExists('vouchers');
}
};
