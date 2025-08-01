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
    Schema::table('schedules', function (Blueprint $table) {
        $table->boolean('show_on_landing')->default(false);
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
   public function down()
{
    Schema::table('schedules', function (Blueprint $table) {
        $table->dropColumn('show_on_landing');
    });
    }
};
