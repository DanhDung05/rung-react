<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category');
            $table->string('accent')->nullable();
            $table->text('description')->nullable();
            $table->string('slide_path');
            $table->string('full_path');
            $table->mediumText('placeholder')->nullable();
            $table->unsignedInteger('full_width');
            $table->unsignedInteger('full_height');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('sort_order');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_slides');
    }
};
