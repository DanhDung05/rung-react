<?php

namespace Tests\Feature\Admin;

use App\Models\Contact;
use App\Models\Post;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * Nhóm A — Dashboard.
 * Xác nhận số liệu lấy từ database thật, không hardcode.
 */
class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    public function test_khach_chua_dang_nhap_bi_chuyen_ve_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_dashboard_tra_ve_so_lieu_that_tu_database(): void
    {
        $admin = $this->admin();
        $author = User::factory()->create(['role' => 'editor', 'is_active' => true]);

        Tour::create(['name' => 'Tour Một', 'slug' => 'tour-mot', 'status' => 'draft']);
        Tour::create(['name' => 'Tour Hai', 'slug' => 'tour-hai', 'status' => 'draft']);

        Post::create([
            'user_id' => $author->id,
            'title'   => 'Bài Một',
            'slug'    => 'bai-mot',
            'status'  => 'draft',
        ]);

        Contact::create([
            'name'    => 'Khách A',
            'email'   => 'khach@example.com',
            'message' => 'Nội dung liên hệ',
            'status'  => 'new',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Dashboard')
                ->where('stats.tours_total', 2)
                ->where('stats.posts_total', 1)
                ->where('stats.contacts_new', 1)
                ->where('stats.users_total', 2)
                ->has('chartContacts', 7)
                ->has('topTours')
                ->has('topPosts')
                ->has('latestContacts')
                ->has('latestTours')
                ->has('latestPosts')
                ->has('tourByDifficulty')
                ->has('trash')
                ->has('menus')
            );
    }

    public function test_dashboard_dem_dung_thung_rac(): void
    {
        $admin = $this->admin();

        $tour = Tour::create(['name' => 'Tour Xóa', 'slug' => 'tour-xoa', 'status' => 'draft']);
        $tour->delete();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->where('trash.tours', 1));
    }

    public function test_editor_van_xem_duoc_dashboard(): void
    {
        $editor = User::factory()->create(['role' => 'editor', 'is_active' => true]);

        $this->actingAs($editor)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->where('auth.user.role', 'editor'));
    }

    public function test_stats_endpoint_tra_json_cho_admin(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->getJson(route('admin.dashboard.stats'))
            ->assertOk()
            ->assertJsonStructure([
                'stats' => ['contacts_total', 'contacts_new', 'tours_total', 'posts_total'],
                'latestContacts',
            ]);
    }

    public function test_bieu_do_lien_he_luon_co_7_ngay(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->has('chartContacts', 7));
    }
}
