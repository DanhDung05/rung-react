<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Tour;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TourSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy category tour
        $categoryTrekking = Category::where('name', '[SAMPLE] Trekking')->first();
        $categoryCamping  = Category::where('name', '[SAMPLE] Cắm trại')->first();
        $categoryExplore  = Category::where('name', '[SAMPLE] Khám phá')->first();

        // Lấy destinations
        $sapa     = Destination::where('name', '[SAMPLE] Sapa')->first();
        $haGiang  = Destination::where('name', '[SAMPLE] Hà Giang')->first();
        $fansipan = Destination::where('name', '[SAMPLE] Fansipan')->first();
        $daLat    = Destination::where('name', '[SAMPLE] Đà Lạt')->first();

        $tours = [
            [
                'name'             => '[SAMPLE] Trekking Sapa 3N2Đ',
                'category_id'      => $categoryTrekking?->id,
                'excerpt'          => 'Hành trình trekking khám phá Sapa với những thửa ruộng bậc thang tuyệt đẹp.',
                'duration_days'    => 3,
                'duration_nights'  => 2,
                'distance_km'      => 45,
                'altitude_min'     => 200,
                'altitude_max'     => 1800,
                'difficulty'       => 'moderate',
                'price_reference'  => 3500000,
                'start_point'      => 'Hà Nội',
                'end_point'        => 'Sapa',
                'best_time'        => 'Tháng 9 - Tháng 11',
                'is_featured'      => true,
                'destinations'     => [$sapa?->id],
                'itineraries'      => [
                    ['day' => 1, 'title' => 'Hà Nội → Sapa', 'desc' => 'Di chuyển từ Hà Nội đến Sapa, nghỉ ngơi tại homestay.', 'distance' => 0, 'altitude' => 1600],
                    ['day' => 2, 'title' => 'Trekking bản làng', 'desc' => 'Trekking qua các bản làng dân tộc, tham quan ruộng bậc thang.', 'distance' => 25, 'altitude' => 1800],
                    ['day' => 3, 'title' => 'Sapa → Hà Nội', 'desc' => 'Tham quan chợ địa phương, di chuyển về Hà Nội.', 'distance' => 20, 'altitude' => 200],
                ],
            ],
            [
                'name'             => '[SAMPLE] Chinh phục Fansipan 4N3Đ',
                'category_id'      => $categoryTrekking?->id,
                'excerpt'          => 'Chinh phục đỉnh Fansipan — mái nhà Đông Dương.',
                'duration_days'    => 4,
                'duration_nights'  => 3,
                'distance_km'      => 60,
                'altitude_min'     => 300,
                'altitude_max'     => 3143,
                'difficulty'       => 'hard',
                'price_reference'  => 5500000,
                'start_point'      => 'Hà Nội',
                'end_point'        => 'Fansipan',
                'best_time'        => 'Tháng 10 - Tháng 3',
                'is_featured'      => true,
                'destinations'     => [$fansipan?->id, $sapa?->id],
                'itineraries'      => [
                    ['day' => 1, 'title' => 'Hà Nội → Lào Cai', 'desc' => 'Tàu đêm Hà Nội - Lào Cai.', 'distance' => 0, 'altitude' => 50],
                    ['day' => 2, 'title' => 'Bắt đầu leo Fansipan', 'desc' => 'Trekking từ chân núi lên trạm nghỉ 2200m.', 'distance' => 20, 'altitude' => 2200],
                    ['day' => 3, 'title' => 'Chinh phục đỉnh', 'desc' => 'Lên đỉnh Fansipan 3143m, ngắm bình minh.', 'distance' => 15, 'altitude' => 3143],
                    ['day' => 4, 'title' => 'Xuống núi → Hà Nội', 'desc' => 'Xuống núi, về Hà Nội.', 'distance' => 25, 'altitude' => 50],
                ],
            ],
            [
                'name'             => '[SAMPLE] Hà Giang Loop 3N2Đ',
                'category_id'      => $categoryExplore?->id,
                'excerpt'          => 'Khám phá Hà Giang — mảnh đất địa đầu Tổ quốc.',
                'duration_days'    => 3,
                'duration_nights'  => 2,
                'distance_km'      => 350,
                'altitude_min'     => 200,
                'altitude_max'     => 1500,
                'difficulty'       => 'moderate',
                'price_reference'  => 4200000,
                'start_point'      => 'Hà Giang',
                'end_point'        => 'Hà Giang',
                'best_time'        => 'Tháng 10 - Tháng 11',
                'is_featured'      => true,
                'destinations'     => [$haGiang?->id],
                'itineraries'      => [
                    ['day' => 1, 'title' => 'Hà Giang → Đồng Văn', 'desc' => 'Khởi hành, tham quan các cung đường đèo.', 'distance' => 120, 'altitude' => 1500],
                    ['day' => 2, 'title' => 'Đồng Văn → Mèo Vạc', 'desc' => 'Chinh phục Mã Pí Lèng, sông Nho Quế.', 'distance' => 130, 'altitude' => 1000],
                    ['day' => 3, 'title' => 'Mèo Vạc → Hà Giang', 'desc' => 'Về lại Hà Giang, kết thúc hành trình.', 'distance' => 100, 'altitude' => 200],
                ],
            ],
            [
                'name'             => '[SAMPLE] Cắm trại Đà Lạt 2N1Đ',
                'category_id'      => $categoryCamping?->id,
                'excerpt'          => 'Cắm trại bên hồ, đốt lửa trại, ngắm sao trời.',
                'duration_days'    => 2,
                'duration_nights'  => 1,
                'distance_km'      => 15,
                'altitude_min'     => 1400,
                'altitude_max'     => 1600,
                'difficulty'       => 'easy',
                'price_reference'  => 1500000,
                'start_point'      => 'Đà Lạt',
                'end_point'        => 'Đà Lạt',
                'best_time'        => 'Quanh năm',
                'is_featured'      => false,
                'destinations'     => [$daLat?->id],
                'itineraries'      => [
                    ['day' => 1, 'title' => 'Đà Lạt → Hồ Tuyền Lâm', 'desc' => 'Di chuyển đến hồ, dựng trại, đốt lửa trại.', 'distance' => 10, 'altitude' => 1500],
                    ['day' => 2, 'title' => 'Hồ Tuyền Lâm → Đà Lạt', 'desc' => 'Trekking nhẹ, tham quan thác, về Đà Lạt.', 'distance' => 5, 'altitude' => 1400],
                ],
            ],
            [
                'name'             => '[SAMPLE] Khám phá Ninh Bình 2N1Đ',
                'category_id'      => $categoryExplore?->id,
                'excerpt'          => 'Tràng An, Tam Cốc, Hang Múa — vẻ đẹp non nước hữu tình.',
                'duration_days'    => 2,
                'duration_nights'  => 1,
                'distance_km'      => 20,
                'altitude_min'     => 50,
                'altitude_max'     => 500,
                'difficulty'       => 'easy',
                'price_reference'  => 1800000,
                'start_point'      => 'Hà Nội',
                'end_point'        => 'Ninh Bình',
                'best_time'        => 'Tháng 2 - Tháng 5',
                'is_featured'      => false,
                'destinations'     => [],
                'itineraries'      => [
                    ['day' => 1, 'title' => 'Hà Nội → Ninh Bình', 'desc' => 'Tham quan Tràng An, Hang Múa.', 'distance' => 10, 'altitude' => 500],
                    ['day' => 2, 'title' => 'Tam Cốc → Hà Nội', 'desc' => 'Đi thuyền Tam Cốc, về Hà Nội.', 'distance' => 10, 'altitude' => 50],
                ],
            ],
            [
                'name'             => '[SAMPLE] Trekking Tà Năng 2N1Đ',
                'category_id'      => $categoryTrekking?->id,
                'excerpt'          => 'Cung trekking đẹp nhất Việt Nam — Tà Năng - Phan Dũng.',
                'duration_days'    => 2,
                'duration_nights'  => 1,
                'distance_km'      => 25,
                'altitude_min'     => 500,
                'altitude_max'     => 1300,
                'difficulty'       => 'moderate',
                'price_reference'  => 2200000,
                'start_point'      => 'Đà Lạt',
                'end_point'        => 'Phan Dũng',
                'best_time'        => 'Tháng 12 - Tháng 3',
                'is_featured'      => true,
                'destinations'     => [$daLat?->id],
                'itineraries'      => [
                    ['day' => 1, 'title' => 'Khởi hành trekking', 'desc' => 'Trekking qua đồi cỏ, cắm trại giữa rừng.', 'distance' => 15, 'altitude' => 1300],
                    ['day' => 2, 'title' => 'Về Phan Dũng', 'desc' => 'Trekking xuống Phan Dũng, kết thúc.', 'distance' => 10, 'altitude' => 500],
                ],
            ],
            [
                'name'             => '[SAMPLE] Cắm trại Ba Vì 2N1Đ',
                'category_id'      => $categoryCamping?->id,
                'excerpt'          => 'Cắm trại cuối tuần gần Hà Nội.',
                'duration_days'    => 2,
                'duration_nights'  => 1,
                'distance_km'      => 10,
                'altitude_min'     => 100,
                'altitude_max'     => 1100,
                'difficulty'       => 'easy',
                'price_reference'  => 1200000,
                'start_point'      => 'Hà Nội',
                'end_point'        => 'Ba Vì',
                'best_time'        => 'Quanh năm',
                'is_featured'      => false,
                'destinations'     => [],
                'itineraries'      => [
                    ['day' => 1, 'title' => 'Hà Nội → Ba Vì', 'desc' => 'Dựng trại, đốt lửa, BBQ.', 'distance' => 5, 'altitude' => 1100],
                    ['day' => 2, 'title' => 'Ba Vì → Hà Nội', 'desc' => 'Tham quan vườn quốc gia, về Hà Nội.', 'distance' => 5, 'altitude' => 100],
                ],
            ],
        ];

        $totalItineraries = 0;

        foreach ($tours as $tourData) {
            $destinations   = $tourData['destinations'];
            $itineraries    = $tourData['itineraries'];
            unset($tourData['destinations'], $tourData['itineraries']);

            $slug = Str::slug($tourData['name']);

            // Tạo tour
            $tour = Tour::firstOrCreate(
                ['slug' => $slug],
                array_merge($tourData, [
                    'content'      => 'Nội dung chi tiết của tour mẫu. Đây là dữ liệu demo.',
                    'notes'        => 'Lưu ý: Đây là dữ liệu mẫu, không phải tour thật.',
                    'status'       => 'published',
                    'published_at' => now(),
                    'sort_order'   => 0,
                    'view_count'   => 0,
                ])
            );

            // Gắn destinations
            $validDestinations = array_filter($destinations); // Bỏ null
            if (!empty($validDestinations)) {
                $tour->destinations()->sync($validDestinations);
            }

            // Tạo itineraries (chỉ khi tour mới tạo)
            if ($tour->itineraries()->count() === 0) {
                foreach ($itineraries as $index => $iti) {
                    $tour->itineraries()->create([
                        'day_number'  => $iti['day'],
                        'title'       => $iti['title'],
                        'description' => $iti['desc'],
                        'distance_km' => $iti['distance'],
                        'altitude'    => $iti['altitude'],
                        'sort_order'  => $index,
                    ]);
                    $totalItineraries++;
                }
            }
        }

        $this->command->info('✓ Đã seed tours: ' . count($tours));
        $this->command->line('   - Itineraries: ' . $totalItineraries);
    }
}