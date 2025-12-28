<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Add nullable slug column first
        Schema::table('packages', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // 2) Backfill slug values from the name, ensuring uniqueness
        $packages = DB::table('packages')->get();
        foreach ($packages as $p) {
            $base = Str::slug($p->name ?: ('package-' . $p->id));
            $slug = $base;
            $i = 1;
            while (DB::table('packages')->where('slug', $slug)->where('id', '<>', $p->id)->exists()) {
                $slug = $base . '-' . $i;
                $i++;
            }
            DB::table('packages')->where('id', $p->id)->update(['slug' => $slug]);
        }

        // 3) Make the slug column unique and not nullable
        Schema::table('packages', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
