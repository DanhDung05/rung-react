<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')
                  ->constrained('tours')
                  ->cascadeOnDelete();
            $table->integer('day_number');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->integer('altitude')->nullable();
            $table->string('meals', 100)->nullable();
            $table->string('accommodation')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('tour_id');
            $table->index('day_number');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_itineraries');
    }
};