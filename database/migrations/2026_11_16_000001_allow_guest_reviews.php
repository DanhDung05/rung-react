<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // FK/index cũ có thể chặn việc sửa unique trên MySQL; thao tác idempotent.
        foreach ([
            'ALTER TABLE reviews DROP FOREIGN KEY reviews_user_id_foreign',
            'ALTER TABLE reviews ADD INDEX reviews_tour_id_index (tour_id)',
            'ALTER TABLE reviews DROP INDEX reviews_tour_id_user_id_unique',
        ] as $statement) {
            try {
                DB::statement($statement);
            } catch (\Throwable $e) {
                // bỏ qua nếu đã thực hiện ở lần chạy trước
            }
        }

        if (! Schema::hasColumn('reviews', 'author_name')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->string('author_name', 100)->nullable()->after('user_id');
            });
        }

        if (! Schema::hasColumn('reviews', 'author_email')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->string('author_email', 255)->nullable()->after('author_name');
            });
        }

        DB::statement('ALTER TABLE reviews MODIFY user_id BIGINT UNSIGNED NULL');

        try {
            DB::statement('ALTER TABLE reviews ADD CONSTRAINT reviews_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
        } catch (\Throwable $e) {
            // FK đã tồn tại
        }

        try {
            DB::statement('ALTER TABLE reviews ADD UNIQUE reviews_tour_email_unique (tour_id, author_email)');
        } catch (\Throwable $e) {
            // unique đã tồn tại
        }
    }

    public function down(): void
    {
        foreach ([
            'ALTER TABLE reviews DROP INDEX reviews_tour_email_unique',
            'ALTER TABLE reviews DROP FOREIGN KEY reviews_user_id_foreign',
        ] as $statement) {
            try {
                DB::statement($statement);
            } catch (\Throwable $e) {
                //
            }
        }

        DB::table('reviews')->whereNull('user_id')->delete();
        DB::statement('ALTER TABLE reviews MODIFY user_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE reviews ADD CONSTRAINT reviews_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE reviews ADD UNIQUE reviews_tour_id_user_id_unique (tour_id, user_id)');

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['author_name', 'author_email']);
        });
    }
};
