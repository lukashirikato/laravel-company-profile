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
    Schema::table('class_models', function (Blueprint $table) {
        $table->string('group_name')->nullable()->after('class_name');
        $table->string('type')->nullable()->after('group_name');
    });
}

public function down()
{
    Schema::table('class_models', function (Blueprint $table) {
        $table->dropColumn(['group_name', 'type']);
    });
}

};
