<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class HomeSectionEditorTest extends TestCase
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
        $this->get(route('admin.home-sections.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_open_editor(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.home-sections.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/HomeSections/Index')
                ->where('section', 'gallery')
                ->has('config.fields')
                ->has('values'));
    }

    public function test_admin_can_update_and_reset(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->put(route('admin.home-sections.update', 'gallery'), [
            'enabled'      => true,
            'background'   => 'white',
            'label_text'   => 'Album',
            'title'        => 'Khoảnh khắc mới',
            'accent'       => 'núi rừng',
            'title_color'  => '#102c24',
            'accent_color' => '#a77942',
            'label_color'  => '#a77942',
            'label_on'     => true,
            'lead'         => 'Mô tả mới',
            'button_text'  => 'Xem thêm',
            'button_url'   => '/bai-viet',
            'button_on'    => true,
        ])->assertRedirect();

        $this->assertSame('Khoảnh khắc mới', Setting::get('home_gallery_title'));
        $this->assertSame('white', Setting::get('home_gallery_background'));
        $this->assertTrue((bool) Setting::get('home_gallery_enabled'));

        $this->actingAs($admin)->post(route('admin.home-sections.reset', 'gallery'))->assertRedirect();

        $this->assertSame('Những khoảnh khắc', Setting::get('home_gallery_title'));
        $this->assertSame('cream', Setting::get('home_gallery_background'));
    }
}
