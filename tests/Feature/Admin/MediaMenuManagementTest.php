<?php

namespace Tests\Feature\Admin;

use App\Models\Media;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * Nhóm D — Media + Menu.
 */
class MediaMenuManagementTest extends TestCase
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

    // ===================== MEDIA =====================

    public function test_admin_xem_thu_vien_anh(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.media.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Media/Index')
                ->has('media')
                ->has('collections')
                ->has('mediableTypes')
                ->has('trashedCount')
            );
    }

    public function test_admin_xoa_mem_va_khoi_phuc_media(): void
    {
        $admin = $this->admin();
        $media = Media::create([
            'collection' => 'gallery',
            'file_name'  => 'anh.jpg',
            'file_path'  => 'media/tours/2026/10/anh.jpg',
        ]);

        $this->actingAs($admin)->delete(route('admin.media.destroy', $media->id))->assertRedirect();
        $this->assertSoftDeleted('media', ['id' => $media->id]);

        $this->actingAs($admin)->patch(route('admin.media.restore', $media->id))->assertRedirect();
        $this->assertDatabaseHas('media', ['id' => $media->id, 'deleted_at' => null]);
    }

    // ===================== MENU =====================

    public function test_admin_xem_danh_sach_menu(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.menus.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Admin/Menus/Index')->has('menus'));
    }

    public function test_admin_tao_va_sua_menu(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.menus.store'), ['name' => 'Menu chính', 'location' => 'main', 'is_active' => 1])
            ->assertRedirect(route('admin.menus.index'));

        $menu = Menu::where('location', 'main')->first();
        $this->assertNotNull($menu);

        $this->actingAs($admin)
            ->put(route('admin.menus.update', $menu->id), ['name' => 'Menu header', 'location' => 'main', 'is_active' => 1])
            ->assertRedirect(route('admin.menus.index'));

        $this->assertSame('Menu header', $menu->fresh()->name);
    }

    public function test_admin_luu_cau_truc_menu_nhieu_cap(): void
    {
        $menu = Menu::create(['name' => 'Menu chính', 'location' => 'main', 'is_active' => true]);

        $this->actingAs($this->admin())
            ->put(route('admin.menus.items.save', $menu->id), [
                'items' => [
                    ['label' => 'Trang chủ', 'url' => '/', 'target' => '_self', 'parent_key' => null, 'is_active' => 1],
                    ['label' => 'Tour', 'route_name' => 'tours.index', 'target' => '_self', 'parent_key' => null, 'is_active' => 1],
                    ['label' => 'Tour miền Bắc', 'url' => '/tour/mien-bac', 'target' => '_self', 'parent_key' => 1, 'is_active' => 1],
                ],
            ])
            ->assertRedirect(route('admin.menus.items', $menu->id));

        $this->assertSame(3, MenuItem::where('menu_id', $menu->id)->count());

        $tour = MenuItem::where('label', 'Tour')->first();
        $child = MenuItem::where('label', 'Tour miền Bắc')->first();

        $this->assertSame($tour->id, $child->parent_id);
    }

    public function test_admin_tao_menu_trung_vi_tri_bao_loi(): void
    {
        Menu::create(['name' => 'Đã có', 'location' => 'main', 'is_active' => true]);

        $this->actingAs($this->admin())
            ->post(route('admin.menus.store'), ['name' => 'Trùng', 'location' => 'main', 'is_active' => 1])
            ->assertSessionHasErrors(['location']);
    }
}
