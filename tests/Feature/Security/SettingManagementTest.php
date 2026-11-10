<?php

namespace Tests\Feature\Security;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * Kiểm thử phân quyền module Cài đặt hệ thống.
 *
 * Trước khi vá: editor truy cập trực tiếp /admin/cau-hinh vẫn xem và sửa được
 * (middleware 'admin' cho cả admin + editor, FormRequest authorize() = true).
 */
class SettingManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    private function makeEditor(): User
    {
        return User::factory()->create(['role' => 'editor', 'is_active' => true]);
    }

    // ============================================================
    // KHÁCH
    // ============================================================

    public function test_khach_chua_dang_nhap_bi_chuyen_ve_login(): void
    {
        $this->get(route('admin.settings.index'))
            ->assertRedirect(route('login'));
    }

    // ============================================================
    // EDITOR — PHẢI 403 Ở MỌI ĐIỂM VÀO
    // ============================================================

    public function test_editor_khong_duoc_xem_danh_sach_cau_hinh(): void
    {
        $this->actingAs($this->makeEditor())
            ->get(route('admin.settings.index'))
            ->assertForbidden();
    }

    public function test_editor_khong_duoc_mo_bat_ky_nhom_cau_hinh_nao(): void
    {
        $editor = $this->makeEditor();
        $groups = array_keys(config('settings.groups', []));

        $this->assertNotEmpty($groups, 'Phải có ít nhất một nhóm cấu hình.');

        foreach ($groups as $group) {
            $this->actingAs($editor)
                ->get(route('admin.settings.edit', $group))
                ->assertForbidden();
        }
    }

    public function test_editor_khong_duoc_cap_nhat_cau_hinh(): void
    {
        Setting::create([
            'key'   => 'site_name',
            'value' => 'Gia tri goc',
            'group' => 'general',
            'type'  => 'string',
        ]);

        $this->actingAs($this->makeEditor())
            ->put(route('admin.settings.update', 'general'), ['site_name' => 'Bi hack'])
            ->assertForbidden();

        $this->assertSame('Gia tri goc', Setting::where('key', 'site_name')->value('value'));
    }

    public function test_editor_nhan_403_truoc_khi_kiem_tra_nhom_khong_ton_tai(): void
    {
        // Không được lộ thông tin nhóm có tồn tại hay không.
        $this->actingAs($this->makeEditor())
            ->get('/admin/cau-hinh/nhom-khong-ton-tai')
            ->assertForbidden();
    }

    // ============================================================
    // ADMIN — ĐƯỢC PHÉP
    // ============================================================

    public function test_admin_duoc_xem_danh_sach_cau_hinh(): void
    {
        $this->actingAs($this->makeAdmin())
            ->get(route('admin.settings.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Settings/Index', false)
                ->has('groups')
            );
    }

    public function test_admin_duoc_mo_nhom_cau_hinh(): void
    {
        $this->actingAs($this->makeAdmin())
            ->get(route('admin.settings.edit', 'general'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Settings/Edit', false)
                ->where('group', 'general')
                ->has('groupConfig.fields')
                ->has('current')
            );
    }

    public function test_admin_duoc_cap_nhat_cau_hinh(): void
    {
        $this->actingAs($this->makeAdmin())
            ->put(route('admin.settings.update', 'general'), [
                'site_name'        => 'Rung Adventure Test',
                'site_description' => 'Mo ta test',
            ])
            ->assertRedirect(route('admin.settings.edit', 'general'));

        $this->assertSame('Rung Adventure Test', Setting::where('key', 'site_name')->value('value'));

        // Cache settings phải được xóa để giá trị mới có hiệu lực ngay.
        $this->assertSame('Rung Adventure Test', Setting::get('site_name'));
    }

    public function test_admin_mo_nhom_khong_ton_tai_nhan_404(): void
    {
        $this->actingAs($this->makeAdmin())
            ->get('/admin/cau-hinh/nhom-khong-ton-tai')
            ->assertNotFound();
    }
}
