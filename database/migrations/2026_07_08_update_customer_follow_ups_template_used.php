<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_follow_ups', function (Blueprint $table) {
            $table->string('template_used', 100)->default('default')->change();
        });
    }

    public function down(): void
    {
        Schema::table('customer_follow_ups', function (Blueprint $table) {
            $table->enum('template_used', ['default', 'promotion', 'newclass', 'checkup'])->default('default')->change();
        });
    }
};
