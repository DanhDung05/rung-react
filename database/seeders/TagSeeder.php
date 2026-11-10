<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => '[SAMPLE] Trekking'],
            ['name' => '[SAMPLE] Cắm trại'],
            ['name' => '[SAMPLE] Sapa'],
            ['name' => '[SAMPLE] Hà Giang'],
            ['name' => '[SAMPLE] Đà Lạt'],
            ['name' => '[SAMPLE] Kinh nghiệm'],
            ['name' => '[SAMPLE] Cẩm nang'],
            ['name' => '[SAMPLE] Ẩm thực'],
            ['name' => '[SAMPLE] Văn hóa'],
            ['name' => '[SAMPLE] Nhiếp ảnh'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['slug' => Str::slug($tag['name'])],
                [
                    'name'        => $tag['name'],
                    'description' => 'Tag mẫu cho bài viết.',
                ]
            );
        }

        $this->command->info('✓ Đã seed tags: ' . count($tags));
    }
}