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
        Schema::create('package_schedules', function (Blueprint $table) {
    $table->id();
    $table->foreignId('package_id')->constrained()->cascadeOnDelete();
    $table->foreignId('schedule_id')->constrained()->cascadeOnDelete();
    $table->timestamps();

    $table->unique(['package_id', 'schedule_id']);
});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_schedules');
    }
};
