<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * Nhóm B3 — Điểm đến + Danh mục.
 */
class TaxonomyManagementTest extends TestCase
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

    // ===================== DESTINATION =====================

    public function test_admin_xem_danh_sach_diem_den(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.destinations.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Destinations/Index')
                ->has('destinations')
                ->has('regions')
            );
    }

    public function test_admin_tao_diem_den_va_tu_sinh_slug(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.destinations.store'), [
                'name'   => 'Sapa',
                'slug'   => '',
                'region' => 'Miền Bắc',
                'status' => 'published',
            ])
            ->assertRedirect(route('admin.destinations.index'));

        $destination = Destination::where('slug', 'sapa')->first();

        $this->assertNotNull($destination);
        $this->assertSame('published', $destination->status);
        $this->assertNotNull($destination->published_at);
    }

    public function test_admin_sua_va_xoa_mem_diem_den(): void
    {
        $admin = $this->admin();
        $destination = Destination::create(['name' => 'Cũ', 'slug' => 'cu', 'status' => 'draft']);

        $this->actingAs($admin)
            ->put(route('admin.destinations.update', $destination->id), ['name' => 'Mới', 'slug' => 'moi', 'status' => 'draft'])
            ->assertRedirect(route('admin.destinations.index'));

        $this->assertSame('Mới', $destination->fresh()->name);

        $this->actingAs($admin)
            ->delete(route('admin.destinations.destroy', $destination->id))
            ->assertRedirect();

        $this->assertSoftDeleted('destinations', ['id' => $destination->id]);
    }

    public function test_khong_xoa_vinh_vien_diem_den_con_tour(): void
    {
        $admin = $this->admin();
        $destination = Destination::create(['name' => 'Sapa', 'slug' => 'sapa', 'status' => 'draft']);
        $tour = Tour::create(['name' => 'Tour Sapa', 'slug' => 'tour-sapa', 'status' => 'draft']);
        $tour->destinations()->sync([$destination->id]);
        $destination->delete();

        $this->actingAs($admin)
            ->delete(route('admin.destinations.force-destroy', $destination->id))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('destinations', ['id' => $destination->id]);
    }

    // ===================== CATEGORY =====================

    public function test_admin_xem_danh_sach_danh_muc(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.categories.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Admin/Categories/Index')->has('categories'));
    }

    public function test_admin_tao_danh_muc_bai_viet(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.categories.store'), [
                'type'      => 'post',
                'name'      => 'Cẩm nang du lịch',
                'slug'      => '',
                'is_active' => 1,
                'sort_order' => 0,
            ])
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', ['slug' => 'cam-nang-du-lich', 'type' => 'post']);
    }

    public function test_admin_sua_danh_muc(): void
    {
        $admin = $this->admin();
        $category = Category::create(['type' => 'tour', 'name' => 'Cũ', 'slug' => 'cu']);

        $this->actingAs($admin)
            ->put(route('admin.categories.update', $category->id), [
                'type' => 'tour',
                'name' => 'Mới',
                'slug' => 'moi',
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.categories.index'));

        $this->assertSame('Mới', $category->fresh()->name);
    }

    public function test_validate_danh_muc_bat_buoc_ten_va_loai(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.categories.store'), [])
            ->assertSessionHasErrors(['type', 'name']);
    }
}
