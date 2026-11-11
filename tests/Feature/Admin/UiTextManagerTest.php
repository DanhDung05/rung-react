<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class UiTextManagerTest extends TestCase
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

    public function test_guest_redirected(): void
    {
        $this->get(route('admin.ui-texts.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_open_manager(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.ui-texts.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/UiTexts/Index')
                ->where('page', 'tour.index')
                ->has('config.sections')
                ->has('values'));
    }

    public function test_admin_can_update_and_reset_section(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->put(route('admin.ui-texts.update', ['page' => 'tour.index', 'section' => 'filters']), [
            'search_label'       => 'Tìm tour',
            'search_placeholder' => 'Nhập tên tour',
            'category_label'     => 'Nhóm',
            'category_all'       => 'Tất cả nhóm',
            'difficulty_label'   => 'Độ khó',
            'difficulty_all'     => 'Mọi độ khó',
            'sort_label'         => 'Sắp xếp',
            'sort_newest'        => 'Mới nhất',
            'sort_price_asc'     => 'Giá tăng',
            'sort_price_desc'    => 'Giá giảm',
            'sort_popular'       => 'Nổi bật',
            'reset'              => 'Bỏ lọc',
        ])->assertRedirect();

        $this->assertSame('Tìm tour', Setting::get('ui_tour.index_filters_search_label'));

        $this->actingAs($admin)->post(route('admin.ui-texts.reset', ['page' => 'tour.index', 'section' => 'filters']))->assertRedirect();

        $this->assertSame('Tìm kiếm', Setting::get('ui_tour.index_filters_search_label'));
    }
}
