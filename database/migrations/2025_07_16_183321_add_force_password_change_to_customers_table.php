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
    Schema::table('customers', function (Blueprint $table) {
        $table->boolean('force_password_change')->default(false);
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
public function down()
{
    Schema::table('customers', function (Blueprint $table) {
        $table->dropColumn('force_password_change');
    });
}
};
