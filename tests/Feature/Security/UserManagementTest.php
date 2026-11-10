<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Kiểm thử bảo mật module Người dùng.
 *
 * Mục tiêu chính: xác nhận lỗ hổng "editor tự nâng quyền thành admin" đã được vá.
 * Trước khi vá, tất cả test 403 dưới đây đều thất bại (trả 200/302 và role bị đổi).
 */
class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Không cần Vite manifest khi render Inertia trong test.
        $this->withoutVite();
    }

    // ============================================================
    // HELPERS
    // ============================================================

    private function makeAdmin(): User
    {
        return User::factory()->create([
            'role'      => 'admin',
            'is_active' => true,
        ]);
    }

    private function makeEditor(): User
    {
        return User::factory()->create([
            'role'      => 'editor',
            'is_active' => true,
        ]);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name'      => 'Ten Moi',
            'email'     => 'moi@example.com',
            'role'      => 'editor',
            'is_active' => true,
        ], $overrides);
    }

    // ============================================================
    // KHÁCH CHƯA ĐĂNG NHẬP
    // ============================================================

    public function test_khach_chua_dang_nhap_bi_chuyen_ve_login(): void
    {
        $this->get(route('admin.users.index'))
            ->assertRedirect(route('login'));
    }

    // ============================================================
    // EDITOR — KHÔNG ĐƯỢC QUẢN LÝ NGƯỜI DÙNG
    // ============================================================

    public function test_editor_khong_duoc_xem_danh_sach_nguoi_dung(): void
    {
        $this->actingAs($this->makeEditor())
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    public function test_editor_khong_duoc_mo_form_tao_nguoi_dung(): void
    {
        $this->actingAs($this->makeEditor())
            ->get(route('admin.users.create'))
            ->assertForbidden();
    }

    /**
     * TEST HỒI QUY CHÍNH: trước khi vá, editor đổi role của chính mình thành admin được.
     */
    public function test_editor_khong_the_tu_nang_quyen_thanh_admin(): void
    {
        $editor = $this->makeEditor();

        $this->actingAs($editor)
            ->put(route('admin.users.update', $editor->id), $this->payload([
                'name'  => $editor->name,
                'email' => $editor->email,
                'role'  => 'admin',
            ]))
            ->assertForbidden();

        $this->assertSame('editor', $editor->fresh()->role);
    }

    public function test_editor_khong_the_tao_tai_khoan_admin(): void
    {
        $this->actingAs($this->makeEditor())
            ->post(route('admin.users.store'), $this->payload([
                'email'                 => 'newadmin@example.com',
                'password'              => 'secret123',
                'password_confirmation' => 'secret123',
                'role'                  => 'admin',
            ]))
            ->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => 'newadmin@example.com']);
    }

    public function test_editor_khong_duoc_sua_nguoi_dung_khac(): void
    {
        $editor = $this->makeEditor();
        $other  = User::factory()->create(['role' => 'user', 'is_active' => true]);

        $this->actingAs($editor)
            ->put(route('admin.users.update', $other->id), $this->payload([
                'name'  => $other->name,
                'email' => $other->email,
            ]))
            ->assertForbidden();

        $this->assertSame('user', $other->fresh()->role);
    }

    public function test_editor_khong_duoc_bat_tat_tai_khoan(): void
    {
        $editor = $this->makeEditor();
        $other  = User::factory()->create(['role' => 'user', 'is_active' => true]);

        $this->actingAs($editor)
            ->patch(route('admin.users.toggle-active', $other->id))
            ->assertForbidden();

        $this->assertTrue((bool) $other->fresh()->is_active);
    }

    // ============================================================
    // ADMIN — ĐƯỢC PHÉP, NHƯNG KHÔNG TỰ ĐỔI VAI TRÒ
    // ============================================================

    public function test_admin_duoc_xem_danh_sach_nguoi_dung(): void
    {
        $this->actingAs($this->makeAdmin())
            ->get(route('admin.users.index'))
            ->assertOk();
    }

    public function test_admin_duoc_nang_quyen_editor(): void
    {
        $admin  = $this->makeAdmin();
        $editor = $this->makeEditor();

        $this->actingAs($admin)
            ->put(route('admin.users.update', $editor->id), $this->payload([
                'name'  => $editor->name,
                'email' => $editor->email,
                'role'  => 'admin',
            ]))
            ->assertRedirect(route('admin.users.index'));

        $this->assertSame('admin', $editor->fresh()->role);
    }

    public function test_admin_khong_the_tu_doi_vai_tro_cua_minh(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->put(route('admin.users.update', $admin->id), $this->payload([
                'name'  => $admin->name,
                'email' => $admin->email,
                'role'  => 'editor',
            ]))
            ->assertForbidden();

        $this->assertSame('admin', $admin->fresh()->role);
    }

    public function test_admin_khong_the_tu_vo_hieu_hoa_tai_khoan(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->patch(route('admin.users.toggle-active', $admin->id))
            ->assertRedirect();

        $this->assertTrue((bool) $admin->fresh()->is_active);
    }
}
