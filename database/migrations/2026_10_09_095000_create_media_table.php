<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('mediable_type')->nullable();
            $table->unsignedBigInteger('mediable_id')->nullable();
            $table->string('collection', 100)->default('default');
            $table->string('file_name');
            $table->string('file_path', 500);
            $table->string('mime_type', 100)->nullable();
            $table->unsignedInteger('file_size')->nullable();
            $table->string('alt_text')->nullable();
            $table->string('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['mediable_type', 'mediable_id'], 'media_mediable_index');
            $table->index('collection');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};