<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run(): void
    {
        $sliders = [
            [
                'title'    => '[SAMPLE] Khám phá Sapa',
                'subtitle' => 'Hành trình trekking 3N2Đ',
                'image'    => 'https://via.placeholder.com/1920x800/0ea5e9/ffffff?text=Slider+1',
                'link_url' => '/tour',
                'sort_order' => 1,
            ],
            [
                'title'    => '[SAMPLE] Chinh phục Fansipan',
                'subtitle' => 'Mái nhà Đông Dương 3143m',
                'image'    => 'https://via.placeholder.com/1920x800/f97316/ffffff?text=Slider+2',
                'link_url' => '/tour',
                'sort_order' => 2,
            ],
            [
                'title'    => '[SAMPLE] Hà Giang Loop',
                'subtitle' => 'Cung đường đẹp nhất Việt Nam',
                'image'    => 'https://via.placeholder.com/1920x800/16a34a/ffffff?text=Slider+3',
                'link_url' => '/tour',
                'sort_order' => 3,
            ],
            [
                'title'    => '[SAMPLE] Cắm trại Đà Lạt',
                'subtitle' => 'Trải nghiệm thiên nhiên',
                'image'    => 'https://via.placeholder.com/1920x800/7c3aed/ffffff?text=Slider+4',
                'link_url' => '/tour',
                'sort_order' => 4,
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::firstOrCreate(
                ['title' => $slider['title']],
                array_merge($slider, [
                    'link_target' => '_self',
                    'position'    => 'home_hero',
                    'is_active'   => true,
                ])
            );
        }

        $this->command->info('✓ Đã seed sliders: ' . count($sliders));
    }
}