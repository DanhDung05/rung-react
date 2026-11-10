<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL: dùng raw SQL để đổi enum
        DB::statement("ALTER TABLE `settings` MODIFY COLUMN `type` ENUM(
            'string', 'text', 'boolean', 'number', 'json', 'color', 'image', 'select'
        ) NOT NULL DEFAULT 'string'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `settings` MODIFY COLUMN `type` ENUM(
            'string', 'text', 'boolean', 'number', 'json'
        ) NOT NULL DEFAULT 'string'");
    }
};