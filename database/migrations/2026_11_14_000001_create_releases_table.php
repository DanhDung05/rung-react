<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('releases', function (Blueprint $table) {
            $table->id();
            $table->string('version', 32)->unique();
            $table->string('previous_version', 32)->nullable();
            $table->string('title');
            $table->string('category', 24)->default('improvement');
            $table->string('status', 24)->default('development');
            $table->text('content');
            $table->date('released_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'released_at']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('releases');
    }
};
