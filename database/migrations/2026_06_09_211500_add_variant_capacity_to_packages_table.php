<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->string('package_group')->nullable()->after('type');
            $table->string('variant_label')->nullable()->after('package_group');
            $table->unsignedTinyInteger('participant_count')->default(1)->after('variant_label');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'package_group',
                'variant_label',
                'participant_count',
            ]);
        });
    }
};