<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schedule_label_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('label')->unique();
            $table->foreignId('class_group_id')->nullable()->constrained('class_groups')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedule_label_mappings');
    }
};
