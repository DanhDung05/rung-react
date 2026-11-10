<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * Nhóm E — Người dùng (phần giao diện và thao tác, quyền đã test ở UserManagementTest).
 */
class UserPagesTest extends TestCase
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

    public function test_cac_trang_nguoi_dung_render_dung_component(): void
    {
        $admin = $this->admin();
        $target = User::factory()->create(['role' => 'editor', 'is_active' => true]);

        $this->actingAs($admin)->get(route('admin.users.index'))
            ->assertOk()->assertInertia(fn (Assert $page) => $page->component('Admin/Users/Index')->has('users'));

        $this->actingAs($admin)->get(route('admin.users.create'))
            ->assertOk()->assertInertia(fn (Assert $page) => $page->component('Admin/Users/Create'));

        $this->actingAs($admin)->get(route('admin.users.show', $target->id))
            ->assertOk()->assertInertia(fn (Assert $page) => $page->component('Admin/Users/Show')->has('user'));

        $this->actingAs($admin)->get(route('admin.users.edit', $target->id))
            ->assertOk()->assertInertia(fn (Assert $page) => $page->component('Admin/Users/Edit')->has('user'));
    }

    public function test_admin_bat_tat_tai_khoan_khac(): void
    {
        $admin = $this->admin();
        $target = User::factory()->create(['role' => 'editor', 'is_active' => true]);

        $this->actingAs($admin)
            ->patch(route('admin.users.toggle-active', $target->id))
            ->assertRedirect();

        $this->assertFalse((bool) $target->fresh()->is_active);
    }

    public function test_cap_nhat_khong_nhap_mat_khau_thi_giu_nguyen(): void
    {
        $admin = $this->admin();
        $target = User::factory()->create(['role' => 'editor', 'is_active' => true]);
        $originalHash = $target->password;

        $this->actingAs($admin)
            ->put(route('admin.users.update', $target->id), [
                'name'      => 'Tên mới',
                'email'     => $target->email,
                'role'      => 'editor',
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertSame('Tên mới', $target->fresh()->name);
        $this->assertSame($originalHash, $target->fresh()->password);
    }

    public function test_cap_nhat_co_mat_khau_thi_doi_hash(): void
    {
        $admin = $this->admin();
        $target = User::factory()->create(['role' => 'editor', 'is_active' => true]);

        $this->actingAs($admin)
            ->put(route('admin.users.update', $target->id), [
                'name'                  => $target->name,
                'email'                 => $target->email,
                'role'                  => 'editor',
                'is_active'             => 1,
                'password'              => 'matkhaumoi123',
                'password_confirmation' => 'matkhaumoi123',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertTrue(Hash::check('matkhaumoi123', $target->fresh()->password));
    }

    public function test_tao_tai_khoan_moi(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.users.store'), [
                'name'                  => 'Biên Tập Mới',
                'email'                 => 'bientap@example.com',
                'password'              => 'matkhau123456',
                'password_confirmation' => 'matkhau123456',
                'role'                  => 'editor',
                'is_active'             => 1,
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['email' => 'bientap@example.com', 'role' => 'editor']);
    }
}
