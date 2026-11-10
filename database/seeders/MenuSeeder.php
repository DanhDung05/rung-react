<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Menu chính
        $mainMenu = Menu::firstOrCreate(
            ['location' => 'main'],
            [
                'name'        => '[SAMPLE] Menu chính',
                'description' => 'Menu điều hướng chính',
                'is_active'   => true,
            ]
        );

        // Chỉ tạo items nếu menu mới (chưa có items)
        if ($mainMenu->allItems()->count() === 0) {
            $mainItems = [
                ['label' => 'Trang chủ',  'url' => '/',           'sort_order' => 1],
                ['label' => 'Hành trình', 'url' => '/tour',       'sort_order' => 2],
                ['label' => 'Điểm đến',   'url' => '/diem-den',   'sort_order' => 3],
                ['label' => 'Blog',       'url' => '/bai-viet',   'sort_order' => 4],
                ['label' => 'Giới thiệu', 'url' => '/gioi-thieu', 'sort_order' => 5],
                ['label' => 'Liên hệ',    'url' => '/lien-he',    'sort_order' => 6],
            ];

            foreach ($mainItems as $item) {
                $mainMenu->allItems()->create(array_merge($item, [
                    'target'    => '_self',
                    'is_active' => true,
                ]));
            }

            $this->command->line('   - Main menu items: ' . count($mainItems));
        }

        // Menu footer
        $footerMenu = Menu::firstOrCreate(
            ['location' => 'footer'],
            [
                'name'        => '[SAMPLE] Menu footer',
                'description' => 'Menu chân trang',
                'is_active'   => true,
            ]
        );

        if ($footerMenu->allItems()->count() === 0) {
            $footerItems = [
                ['label' => 'Giới thiệu',       'url' => '/gioi-thieu',              'sort_order' => 1],
                ['label' => 'Liên hệ',           'url' => '/lien-he',                 'sort_order' => 2],
                ['label' => 'Câu hỏi thường gặp','url' => '/faq',                     'sort_order' => 3],
                ['label' => 'Điều khoản',        'url' => '/trang-tinh/dieu-khoan',    'sort_order' => 4],
                ['label' => 'Chính sách bảo mật','url' => '/trang-tinh/chinh-sach-bao-mat', 'sort_order' => 5],
            ];

            foreach ($footerItems as $item) {
                $footerMenu->allItems()->create(array_merge($item, [
                    'target'    => '_self',
                    'is_active' => true,
                ]));
            }

            $this->command->line('   - Footer menu items: ' . count($footerItems));
        }

        $this->command->info('✓ Đã seed menus: 2');
    }
}