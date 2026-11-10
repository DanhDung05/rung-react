<?php

namespace Tests\Feature\Frontend;

use App\Models\Review;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * P0 — Nền tảng frontend public:
 *  - shared props mới (hero_images, about)
 *  - Tour/Show có reviews đã duyệt
 *  - trang lỗi public render bằng Inertia
 */
class PublicFoundationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_chia_se_hero_images_va_about_settings(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home')
                ->has('settings.hero_images')
                ->has('settings.hero_images.blog')
                ->has('settings.hero_images.tour')
                ->has('settings.hero_images.destination')
                ->has('settings.hero_images.about')
                ->has('settings.hero_images.contact')
                ->has('settings.hero_images.faq')
                ->has('settings.about')
                ->has('settings.about.hero_title')
                ->has('settings.about.mission_title')
                ->has('settings.about.values_content')
            );
    }

    public function test_tour_show_tra_ve_danh_gia_da_duyet(): void
    {
        $tour = Tour::create([
            'name'         => 'Tour Tây Bắc',
            'slug'         => 'tour-tay-bac',
            'status'       => 'published',
            'published_at' => now()->subDay(),
        ]);

        $user = User::factory()->create();

        Review::create([
            'tour_id' => $tour->id,
            'user_id' => $user->id,
            'rating'  => 5,
            'comment' => 'Tuyệt vời',
            'status'  => Review::STATUS_APPROVED,
        ]);

        Review::create([
            'tour_id' => $tour->id,
            'user_id' => User::factory()->create()->id,
            'rating'  => 1,
            'comment' => 'Chờ duyệt',
            'status'  => Review::STATUS_PENDING,
        ]);

        $this->get(route('tours.show', $tour->slug))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Tour/Show', false)
                ->has('tour.reviews', 1)
                ->where('tour.reviews.0.rating', 5)
                ->where('tour.reviews.0.user.id', $user->id)
            );
    }

    public function test_trang_404_public_render_bang_inertia(): void
    {
        $this->get('/duong-dan-khong-ton-tai')
            ->assertNotFound()
            ->assertInertia(fn (Assert $page) => $page->component('Errors/NotFound', false));
    }

    public function test_admin_khong_bi_anh_huong_boi_trang_loi_public(): void
    {
        // 404 trong admin vẫn trả 404 (không đổi hành vi khu quản trị).
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->actingAs($admin)
            ->get('/admin/cau-hinh/nhom-khong-ton-tai')
            ->assertNotFound();
    }
}
