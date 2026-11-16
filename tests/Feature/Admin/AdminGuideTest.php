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

    public function test_admin_can_open_guide_with_markdown(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.guide.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Guide/Index')
                ->has('docs', 7)
                ->where('current.slug', 'admin-guide')
                ->where('current.title', 'Hướng dẫn quản trị')
                ->where('current.html', fn ($html) => str_contains($html, '<h2') && ! str_contains($html, '<script')));
    }

    public function test_editor_can_open_guide(): void
    {
        $editor = User::factory()->create(['role' => 'editor', 'is_active' => true]);

        $this->actingAs($editor)->get(route('admin.guide.index'))->assertOk();
    }

    public function test_can_open_specific_document(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.guide.index', ['doc' => 'releases']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('current.slug', 'releases')
                ->where('current.title', 'Phát hành & version'));
    }

    public function test_unknown_document_falls_back_to_admin_guide(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.guide.index', ['doc' => '../.env']))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->where('current.slug', 'admin-guide'));
    }

    public function test_internal_markdown_links_are_rewritten(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.guide.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('current.html', fn ($html) => str_contains($html, '/admin/huong-dan?doc=admin-features')));
    }
}
