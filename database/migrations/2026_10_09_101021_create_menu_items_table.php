<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')
                  ->constrained('menus')
                  ->cascadeOnDelete();
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('menu_items')
                  ->nullOnDelete();
            $table->string('label');
            $table->string('url', 500)->nullable();
            $table->string('route_name', 100)->nullable();
            $table->enum('target', ['_self', '_blank'])->default('_self');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('menu_id');
            $table->index('parent_id');
            $table->index('sort_order');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};