<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title'   => '[SAMPLE] Giới thiệu',
                'content' => '<h2>Về Rung Adventure</h2><p>Đây là nội dung mẫu của trang giới thiệu. Vui lòng thay đổi nội dung thật.</p>',
            ],
            [
                'title'   => '[SAMPLE] Liên hệ',
                'content' => '<h2>Liên hệ với chúng tôi</h2><p>Đây là nội dung mẫu của trang liên hệ.</p>',
            ],
            [
                'title'   => '[SAMPLE] Điều khoản sử dụng',
                'content' => '<h2>Điều khoản sử dụng</h2><p>Nội dung mẫu của trang điều khoản.</p>',
            ],
            [
                'title'   => '[SAMPLE] Chính sách bảo mật',
                'content' => '<h2>Chính sách bảo mật</h2><p>Nội dung mẫu của trang chính sách bảo mật.</p>',
            ],
            [
                'title'   => '[SAMPLE] Chính sách hoàn tiền',
                'content' => '<h2>Chính sách hoàn tiền</h2><p>Nội dung mẫu của trang chính sách hoàn tiền.</p>',
            ],
        ];

        foreach ($pages as $page) {
            Page::firstOrCreate(
                ['slug' => Str::slug($page['title'])],
                array_merge($page, [
                    'template'     => 'default',
                    'status'       => 'published',
                    'published_at' => now(),
                ])
            );
        }

        $this->command->info('✓ Đã seed pages: ' . count($pages));
    }
}