<?php

namespace Tests\Feature\Admin;

use App\Models\Contact;
use App\Models\Review;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * Nhóm C — Liên hệ + Đánh giá.
 */
class EngagementManagementTest extends TestCase
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

    private function contact(string $status = 'new'): Contact
    {
        return Contact::create([
            'name'    => 'Khách Hàng',
            'email'   => 'khach@example.com',
            'message' => 'Tôi muốn đặt tour',
            'status'  => $status,
        ]);
    }

    private function review(User $user, string $status = 'pending'): Review
    {
        $tour = Tour::create(['name' => 'Tour A', 'slug' => 'tour-a', 'status' => 'draft']);

        return Review::create([
            'tour_id' => $tour->id,
            'user_id' => $user->id,
            'rating'  => 5,
            'comment' => 'Tour rất tuyệt vời',
            'status'  => $status,
        ]);
    }

    // ===================== CONTACT =====================

    public function test_admin_xem_hop_thu_lien_he(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.contacts.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Contacts/Index')
                ->has('contacts')
                ->has('newCount')
            );
    }

    public function test_xem_chi_tiet_tu_dong_chuyen_new_sang_read(): void
    {
        $contact = $this->contact('new');

        $this->actingAs($this->admin())
            ->get(route('admin.contacts.show', $contact->id))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Admin/Contacts/Show'));

        $this->assertSame('read', $contact->fresh()->status);
    }

    public function test_admin_cap_nhat_trang_thai_lien_he(): void
    {
        $contact = $this->contact('read');

        $this->actingAs($this->admin())
            ->patch(route('admin.contacts.update-status', $contact->id), ['status' => 'replied'])
            ->assertRedirect();

        $this->assertSame('replied', $contact->fresh()->status);
    }

    public function test_validate_trang_thai_lien_he(): void
    {
        $contact = $this->contact();

        $this->actingAs($this->admin())
            ->patch(route('admin.contacts.update-status', $contact->id), ['status' => 'khong-hop-le'])
            ->assertSessionHasErrors(['status']);
    }

    // ===================== REVIEW =====================

    public function test_admin_xem_danh_sach_danh_gia(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.reviews.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Admin/Reviews/Index')->has('reviews'));
    }

    public function test_admin_duyet_danh_gia(): void
    {
        $admin = $this->admin();
        $review = $this->review($admin);

        $this->actingAs($admin)
            ->patch(route('admin.reviews.approve', $review->id))
            ->assertRedirect();

        $this->assertSame('approved', $review->fresh()->status);
    }

    public function test_admin_tu_choi_danh_gia(): void
    {
        $admin = $this->admin();
        $review = $this->review($admin);

        $this->actingAs($admin)
            ->patch(route('admin.reviews.reject', $review->id))
            ->assertRedirect();

        $this->assertSame('rejected', $review->fresh()->status);
    }

    public function test_admin_xoa_mem_va_khoi_phuc_danh_gia(): void
    {
        $admin = $this->admin();
        $review = $this->review($admin, 'approved');

        $this->actingAs($admin)->delete(route('admin.reviews.destroy', $review->id))->assertRedirect();
        $this->assertSoftDeleted('reviews', ['id' => $review->id]);

        $this->actingAs($admin)->patch(route('admin.reviews.restore', $review->id))->assertRedirect();
        $this->assertDatabaseHas('reviews', ['id' => $review->id, 'deleted_at' => null]);
    }

    public function test_editor_van_xu_ly_duoc_lien_he_va_danh_gia(): void
    {
        $editor = User::factory()->create(['role' => 'editor', 'is_active' => true]);

        $this->actingAs($editor)->get(route('admin.contacts.index'))->assertOk();
        $this->actingAs($editor)->get(route('admin.reviews.index'))->assertOk();
    }
}
