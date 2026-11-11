<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AdminGuideTest extends TestCase
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
        $this->get(route('admin.guide.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_open_guide(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.guide.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Guide/Index')
                ->has('guide.noi-dung.items')
                ->where('guide.noi-dung.items.0.path', '/admin/tour'));
    }

    public function test_editor_can_open_guide(): void
    {
        $editor = User::factory()->create(['role' => 'editor', 'is_active' => true]);

        $this->actingAs($editor)->get(route('admin.guide.index'))->assertOk();
    }
}
