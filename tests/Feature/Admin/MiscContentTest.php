<?php

namespace Tests\Feature\Admin;

use App\Models\Faq;
use App\Models\Page;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * Nhóm B4 — Trang tĩnh + FAQ + Slider.
 */
class MiscContentTest extends TestCase
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

    // ===================== PAGE =====================

    public function test_admin_tao_trang_tinh(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.pages.store'), [
                'title'   => 'Chính sách bảo mật',
                'slug'    => '',
                'content' => '<p>Nội dung</p>',
                'status'  => 'published',
            ])
            ->assertRedirect(route('admin.pages.index'));

        $page = Page::where('slug', 'chinh-sach-bao-mat')->first();

        $this->assertNotNull($page);
        $this->assertSame('published', $page->status);
        $this->assertNotNull($page->published_at);
    }

    public function test_admin_sua_trang_tinh(): void
    {
        $admin = $this->admin();
        $page = Page::create(['title' => 'Cũ', 'slug' => 'cu', 'status' => 'draft']);

        $this->actingAs($admin)
            ->put(route('admin.pages.update', $page->id), ['title' => 'Mới', 'slug' => 'moi', 'status' => 'draft'])
            ->assertRedirect(route('admin.pages.index'));

        $this->assertSame('Mới', $page->fresh()->title);
    }

    public function test_admin_xoa_mem_trang_tinh(): void
    {
        $admin = $this->admin();
        $page = Page::create(['title' => 'Xóa', 'slug' => 'xoa', 'status' => 'draft']);

        $this->actingAs($admin)->delete(route('admin.pages.destroy', $page->id))->assertRedirect();

        $this->assertSoftDeleted('pages', ['id' => $page->id]);
    }

    // ===================== FAQ =====================

    public function test_admin_tao_cau_hoi(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.faqs.store'), [
                'category'   => 'Đặt tour',
                'question'   => 'Làm sao để đặt tour?',
                'answer'     => 'Bạn có thể liên hệ qua form.',
                'sort_order' => 1,
                'is_active'  => 1,
            ])
            ->assertRedirect(route('admin.faqs.index'));

        $this->assertDatabaseHas('faqs', ['question' => 'Làm sao để đặt tour?', 'is_active' => 1]);
    }

    public function test_validate_faq_bat_buoc_cau_hoi_va_tra_loi(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.faqs.store'), [])
            ->assertSessionHasErrors(['question', 'answer']);
    }

    public function test_admin_sua_faq(): void
    {
        $admin = $this->admin();
        $faq = Faq::create(['question' => 'Cũ?', 'answer' => 'Cũ', 'is_active' => true]);

        $this->actingAs($admin)
            ->put(route('admin.faqs.update', $faq->id), ['question' => 'Mới?', 'answer' => 'Mới', 'is_active' => 0, 'sort_order' => 2])
            ->assertRedirect(route('admin.faqs.index'));

        $this->assertSame('Mới?', $faq->fresh()->question);
        $this->assertFalse((bool) $faq->fresh()->is_active);
    }

    // ===================== SLIDER =====================

    public function test_admin_xem_danh_sach_slider(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.sliders.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Admin/Sliders/Index')->has('sliders')->has('positions'));
    }

    public function test_admin_tao_slider_voi_anh(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())
            ->post(route('admin.sliders.store'), [
                'title'      => 'Khám phá Tây Bắc',
                'subtitle'   => 'Mùa lúa chín',
                'image'      => UploadedFile::fake()->image('banner.jpg', 1200, 600),
                'position'   => 'home_hero',
                'link_target' => '_self',
                'sort_order' => 1,
                'is_active'  => 1,
            ])
            ->assertRedirect(route('admin.sliders.index'));

        $slider = Slider::first();

        $this->assertNotNull($slider);
        $this->assertNotNull($slider->image);
        Storage::disk('public')->assertExists($slider->image);
    }

    public function test_tao_slider_thieu_anh_bao_loi(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.sliders.store'), ['position' => 'home_hero', 'link_target' => '_self'])
            ->assertSessionHasErrors(['image']);
    }
}
