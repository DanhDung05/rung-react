<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_slides', function (Blueprint $table) {
            $table->unsignedTinyInteger('crop_x')->default(50)->after('placeholder');
            $table->unsignedTinyInteger('crop_y')->default(50)->after('crop_x');
        });
    }

    public function down(): void
    {
        Schema::table('gallery_slides', function (Blueprint $table) {
            $table->dropColumn(['crop_x', 'crop_y']);
        });
    }
};
