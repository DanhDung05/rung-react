<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('categories')
                  ->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('featured_image')->nullable();
            $table->integer('duration_days')->nullable();
            $table->integer('duration_nights')->nullable();
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->integer('altitude_min')->nullable();
            $table->integer('altitude_max')->nullable();
            $table->enum('difficulty', ['easy', 'moderate', 'hard', 'expert'])->nullable();
            $table->bigInteger('price_reference')->unsigned()->nullable();
            $table->string('start_point')->nullable();
            $table->string('end_point')->nullable();
            $table->string('best_time')->nullable();
            $table->longText('notes')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('category_id');
            $table->index('status');
            $table->index('is_featured');
            $table->index('difficulty');
            $table->index('published_at');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};