<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        // Cấp 1 — Vùng miền
        $regions = [
            [
                'name'     => '[SAMPLE] Miền Bắc',
                'region'   => 'Miền Bắc',
                'children' => [
                    [
                        'name'     => '[SAMPLE] Sapa',
                        'province' => 'Lào Cai',
                        'latitude' => 22.3364,
                        'longitude'=> 103.8438,
                        'altitude' => 1600,
                    ],
                    [
                        'name'     => '[SAMPLE] Hà Giang',
                        'province' => 'Hà Giang',
                        'latitude' => 22.8025,
                        'longitude'=> 104.9784,
                        'altitude' => 1200,
                    ],
                    [
                        'name'     => '[SAMPLE] Ninh Bình',
                        'province' => 'Ninh Bình',
                        'latitude' => 20.2506,
                        'longitude'=> 105.9745,
                        'altitude' => 100,
                    ],
                    [
                        'name'     => '[SAMPLE] Fansipan',
                        'province' => 'Lào Cai',
                        'latitude' => 22.3033,
                        'longitude'=> 103.7750,
                        'altitude' => 3143,
                    ],
                ],
            ],
            [
                'name'     => '[SAMPLE] Miền Trung',
                'region'   => 'Miền Trung',
                'children' => [
                    [
                        'name'     => '[SAMPLE] Đà Nẵng',
                        'province' => 'Đà Nẵng',
                        'latitude' => 16.0544,
                        'longitude'=> 108.2022,
                        'altitude' => 10,
                    ],
                    [
                        'name'     => '[SAMPLE] Hội An',
                        'province' => 'Quảng Nam',
                        'latitude' => 15.8801,
                        'longitude'=> 108.3380,
                        'altitude' => 5,
                    ],
                    [
                        'name'     => '[SAMPLE] Huế',
                        'province' => 'Thừa Thiên Huế',
                        'latitude' => 16.4637,
                        'longitude'=> 107.5909,
                        'altitude' => 15,
                    ],
                ],
            ],
            [
                'name'     => '[SAMPLE] Miền Nam',
                'region'   => 'Miền Nam',
                'children' => [
                    [
                        'name'     => '[SAMPLE] Đà Lạt',
                        'province' => 'Lâm Đồng',
                        'latitude' => 11.9404,
                        'longitude'=> 108.4583,
                        'altitude' => 1500,
                    ],
                    [
                        'name'     => '[SAMPLE] Phú Quốc',
                        'province' => 'Kiên Giang',
                        'latitude' => 10.2899,
                        'longitude'=> 103.9840,
                        'altitude' => 50,
                    ],
                ],
            ],
        ];

        $totalRegions = 0;
        $totalChildren = 0;

        foreach ($regions as $regionData) {
            // Tạo vùng miền (cấp 1)
            $region = Destination::firstOrCreate(
                ['slug' => Str::slug($regionData['name'])],
                [
                    'name'        => $regionData['name'],
                    'region'      => $regionData['region'],
                    'description' => 'Vùng du lịch mẫu.',
                    'status'      => 'published',
                    'published_at'=> now(),
                    'is_featured' => true,
                    'sort_order'  => 0,
                ]
            );

            $totalRegions++;

            // Tạo điểm đến con (cấp 2)
            foreach ($regionData['children'] as $index => $childData) {
                Destination::firstOrCreate(
                    ['slug' => Str::slug($childData['name'])],
                    [
                        'parent_id'   => $region->id,
                        'name'        => $childData['name'],
                        'province'    => $childData['province'] ?? null,
                        'latitude'    => $childData['latitude'] ?? null,
                        'longitude'   => $childData['longitude'] ?? null,
                        'altitude'    => $childData['altitude'] ?? null,
                        'region'      => $regionData['region'],
                        'description' => 'Điểm đến mẫu. ' . $childData['name'],
                        'status'      => 'published',
                        'published_at'=> now(),
                        'is_featured' => $index < 2, // 2 cái đầu nổi bật
                        'sort_order'  => $index,
                    ]
                );

                $totalChildren++;
            }
        }

        $this->command->info('✓ Đã seed destinations:');
        $this->command->line("   - Vùng miền: {$totalRegions}");
        $this->command->line("   - Điểm đến: {$totalChildren}");
    }
}