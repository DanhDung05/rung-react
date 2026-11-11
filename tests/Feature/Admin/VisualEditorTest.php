<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class VisualEditorTest extends TestCase
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
        $this->get(route('admin.visual-editor.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_open_editor(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.visual-editor.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Admin/VisualEditor/Index')->has('config.sections'));
    }

    public function test_draft_is_hidden_from_guests_and_publish_snapshots(): void
    {
        $admin = $this->admin();
        $values = ['home.welcome.title' => ['text' => 'Bản nháp', 'style' => ['color' => '#102c24']]];

        $this->actingAs($admin)->post(route('admin.visual-editor.draft'), ['values' => $values])->assertRedirect();

        $this->assertNotEmpty(Setting::get('ve_draft_home'));
        $this->assertEmpty(Setting::get('ve_pub_home'));

        // Khách chỉ nhận published (rỗng)
        $this->get('/')->assertOk();

        $this->actingAs($admin)->post(route('admin.visual-editor.publish'), ['values' => $values])->assertRedirect();
        $this->assertNotEmpty(Setting::get('ve_pub_home'));
        $this->assertEmpty(Setting::get('ve_draft_home'));

        $this->get('/')->assertOk();
        $this->assertSame($values, Setting::get('ve_pub_home'));

        // Publish lần 2 tạo snapshot bản cũ
        $values2 = ['home.welcome.title' => ['text' => 'Bản 2', 'style' => []]];
        $this->actingAs($admin)->post(route('admin.visual-editor.publish'), ['values' => $values2])->assertRedirect();

        $history = Setting::get('ve_hist_home');
        $history = is_array($history) ? $history : json_decode($history, true);
        $this->assertCount(1, $history);
        $this->assertSame($values, $history[0]['values']);

        $this->actingAs($admin)->post(route('admin.visual-editor.restore'), ['index' => 0])->assertRedirect();
        $this->assertSame($values, Setting::get('ve_pub_home'));
    }

    public function test_admin_can_preview_draft(): void
    {
        $this->get('/?preview=1')->assertOk();
    }
}
