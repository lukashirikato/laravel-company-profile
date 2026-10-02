<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('customer_follow_ups')) {
            return;
        }

        // Raw SQL: doctrine/dbal tidak bisa mengubah kolom bertipe ENUM di MySQL,
        // sehingga ->change() gagal dengan "Unknown database type enum requested".
        // Perlebar kolom agar bisa menyimpan key template apa pun (mis. followup_birthday).
        DB::statement("ALTER TABLE `customer_follow_ups` MODIFY `template_used` VARCHAR(100) NOT NULL DEFAULT 'default'");
    }

    public function down(): void
    {
        if (!Schema::hasTable('customer_follow_ups')) {
            return;
        }

        DB::statement("ALTER TABLE `customer_follow_ups` MODIFY `template_used` ENUM('default', 'promotion', 'newclass', 'checkup') NOT NULL DEFAULT 'default'");
    }
};
