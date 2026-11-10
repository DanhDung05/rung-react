<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Danh mục tour
        $tourCategories = [
            ['name' => '[SAMPLE] Trekking',      'sort_order' => 1],
            ['name' => '[SAMPLE] Cắm trại',       'sort_order' => 2],
            ['name' => '[SAMPLE] Khám phá',       'sort_order' => 3],
            ['name' => '[SAMPLE] Team building',  'sort_order' => 4],
            ['name' => '[SAMPLE] Tour gia đình',  'sort_order' => 5],
        ];

        foreach ($tourCategories as $cat) {
            Category::firstOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'type'        => 'tour',
                    'name'        => $cat['name'],
                    'description' => 'Danh mục mẫu cho tour du lịch.',
                    'sort_order'  => $cat['sort_order'],
                    'is_active'   => true,
                ]
            );
        }

        // Danh mục post
        $postCategories = [
            ['name' => '[SAMPLE] Cẩm nang',      'sort_order' => 1],
            ['name' => '[SAMPLE] Kinh nghiệm',    'sort_order' => 2],
            ['name' => '[SAMPLE] Điểm đến',       'sort_order' => 3],
            ['name' => '[SAMPLE] Tin tức',        'sort_order' => 4],
        ];

        foreach ($postCategories as $cat) {
            Category::firstOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'type'        => 'post',
                    'name'        => $cat['name'],
                    'description' => 'Danh mục mẫu cho bài viết.',
                    'sort_order'  => $cat['sort_order'],
                    'is_active'   => true,
                ]
            );
        }

        $this->command->info('✓ Đã seed categories:');
        $this->command->line('   - Tour: ' . count($tourCategories));
        $this->command->line('   - Post: ' . count($postCategories));
    }
}