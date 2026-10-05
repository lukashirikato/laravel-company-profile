<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Normalisasi kolom `role` pada tabel users ke 3 role resmi:
     * user, admin, owner.
     *
     * - Menambahkan kolom role jika belum ada (default: user).
     * - Role lama "member" / null / nilai tak dikenal menjadi "user".
     */
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('user')->after('password');
            });
        }

        DB::table('users')
            ->where(function ($query) {
                $query->whereNull('role')
                    ->orWhereNotIn('role', ['user', 'admin', 'owner']);
            })
            ->update(['role' => 'user']);
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
