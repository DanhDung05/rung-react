<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * Kiểm thử tầng nền React/Inertia (Giai đoạn 2.0):
 *  - shared props: settings, theme, navigation, app
 *  - component name trả về đúng cho từng route
 *  - trang lỗi 404 hoạt động
 */
class InertiaFoundationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_trang_chu_chia_se_settings_theme_va_menu(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home')
                ->has('settings')
                ->has('settings.site_name')
                ->has('theme')
                ->has('theme.primary')
                ->has('navigation')
                ->has('navigation.main')
                ->has('navigation.footer')
                ->has('app.url')
                ->has('flash')
                ->has('auth')
            );
    }

    public function test_trang_chu_tra_ve_menu_that_tu_database(): void
    {
        $menu = \App\Models\Menu::create([
            'name'      => 'Menu chính',
            'location'  => 'main',
            'is_active' => true,
        ]);

        \App\Models\MenuItem::create([
            'menu_id'    => $menu->id,
            'label'      => 'Trang chủ',
            'url'        => '/',
            'target'     => '_self',
            'sort_order' => 0,
            'is_active'  => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home')
                ->where('navigation.main.0.label', 'Trang chủ')
                ->where('navigation.main.0.url', '/')
            );
    }

    public function test_admin_dashboard_render_dung_component_va_co_sidebar_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Dashboard')
                ->has('stats')
                ->has('stats.tours_total')
                ->where('auth.user.role', 'admin')
                ->where('auth.user.is_admin', true)
            );
    }

    public function test_editor_van_vao_duoc_dashboard(): void
    {
        $editor = User::factory()->create(['role' => 'editor', 'is_active' => true]);

        $this->actingAs($editor)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Dashboard')
                ->where('auth.user.is_admin', false)
            );
    }

    public function test_route_chua_co_page_component_van_tra_200(): void
    {
        // Tour/Index chưa được xây dựng ở GĐ2.0 → vẫn 200 (fallback GenericPage ở client).
        $this->get(route('tours.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Tour/Index', false)
                ->has('tours')
            );
    }

    public function test_trang_khong_ton_tai_tra_404(): void
    {
        $this->get('/khong-ton-tai-abc')
            ->assertNotFound();
    }
}
