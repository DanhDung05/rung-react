<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_destination', function (Blueprint $table) {
            $table->foreignId('tour_id')
                  ->constrained('tours')
                  ->cascadeOnDelete();
            $table->foreignId('destination_id')
                  ->constrained('destinations')
                  ->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['tour_id', 'destination_id']);
            $table->index('destination_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_destination');
    }
};