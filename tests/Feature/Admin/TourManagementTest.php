<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * Nhóm B1 — CRUD Tour.
 */
class TourManagementTest extends TestCase
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

    private function category(): Category
    {
        return Category::create(['type' => 'tour', 'name' => 'Trekking', 'slug' => 'trekking']);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'category_id' => $this->category()->id,
            'name'        => 'Tour Tây Bắc',
            'slug'        => '',
            'excerpt'     => 'Mô tả ngắn',
            'content'     => '<p>Nội dung</p>',
            'status'      => 'published',
            'sort_order'  => 0,
            'is_featured' => 1,
        ], $overrides);
    }

    public function test_admin_xem_danh_sach_tour(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.tours.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Tours/Index')
                ->has('tours')
                ->has('categories')
            );
    }

    public function test_admin_mo_form_tao_tour(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.tours.create'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Tours/Create')
                ->has('categories')
                ->has('destinations')
            );
    }

    public function test_admin_tao_tour_va_tu_sinh_slug_ngay_xuat_ban(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.tours.store'), $this->payload())
            ->assertRedirect(route('admin.tours.index'));

        $tour = Tour::where('slug', 'tour-tay-bac')->first();

        $this->assertNotNull($tour);
        $this->assertSame('published', $tour->status);
        $this->assertNotNull($tour->published_at);
        $this->assertTrue((bool) $tour->is_featured);
    }

    public function test_admin_sua_tour(): void
    {
        $tour = Tour::create(['name' => 'Tên cũ', 'slug' => 'ten-cu', 'status' => 'draft']);
        $category = $this->category();

        $this->actingAs($this->admin())
            ->put(route('admin.tours.update', $tour->id), [
                'category_id' => $category->id,
                'name'        => 'Tên mới',
                'slug'        => 'ten-moi',
                'status'      => 'draft',
            ])
            ->assertRedirect(route('admin.tours.index'));

        $this->assertSame('Tên mới', $tour->fresh()->name);
        $this->assertSame('ten-moi', $tour->fresh()->slug);
    }

    public function test_admin_xoa_mem_va_khoi_phuc_tour(): void
    {
        $tour = Tour::create(['name' => 'Tour Xóa', 'slug' => 'tour-xoa', 'status' => 'draft']);

        $this->actingAs($this->admin())
            ->delete(route('admin.tours.destroy', $tour->id))
            ->assertRedirect();

        $this->assertSoftDeleted('tours', ['id' => $tour->id]);

        $this->actingAs($this->admin())
            ->patch(route('admin.tours.restore', $tour->id))
            ->assertRedirect();

        $this->assertDatabaseHas('tours', ['id' => $tour->id, 'deleted_at' => null]);
    }

    public function test_admin_xoa_vinh_vien_tour_trong_thung_rac(): void
    {
        $tour = Tour::create(['name' => 'Tour Hủy', 'slug' => 'tour-huy', 'status' => 'draft']);
        $tour->delete();

        $this->actingAs($this->admin())
            ->delete(route('admin.tours.force-destroy', $tour->id))
            ->assertRedirect();

        $this->assertDatabaseMissing('tours', ['id' => $tour->id]);
    }

    public function test_validate_bat_buoc_ten_va_danh_muc(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.tours.store'), ['status' => 'draft'])
            ->assertSessionHasErrors(['name', 'category_id']);
    }

    public function test_editor_van_quan_ly_duoc_tour(): void
    {
        $editor = User::factory()->create(['role' => 'editor', 'is_active' => true]);

        $this->actingAs($editor)
            ->get(route('admin.tours.index'))
            ->assertOk();
    }
}
