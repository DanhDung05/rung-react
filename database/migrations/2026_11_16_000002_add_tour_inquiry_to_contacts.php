<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->foreignId('tour_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('people_count')->nullable()->after('phone');
            $table->date('preferred_date')->nullable()->after('people_count');
            $table->string('type', 20)->default('general')->after('status');

            $table->index('tour_id');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['tour_id']);
            $table->dropIndex(['tour_id']);
            $table->dropIndex(['type']);
            $table->dropColumn(['tour_id', 'people_count', 'preferred_date', 'type']);
        });
    }
};
