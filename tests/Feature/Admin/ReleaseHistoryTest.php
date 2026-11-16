<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Services\ChangelogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ReleaseHistoryTest extends TestCase
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

    public function test_guest_cannot_access_release_history(): void
    {
        $this->get(route('admin.releases.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_changelog_timeline(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.releases.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Releases/Index')
                ->has('entries')
                ->where('currentVersion', trim(file_get_contents(base_path('VERSION'))))
                ->where('entries.0.version', '2.4.3'));
    }

    public function test_editor_can_view_release_history(): void
    {
        $editor = User::factory()->create(['role' => 'editor', 'is_active' => true]);

        $this->actingAs($editor)->get(route('admin.releases.index'))->assertOk();
    }

    public function test_can_open_specific_version(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.releases.show', '2.3.1'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Releases/Show')
                ->where('entry.version', '2.3.1')
                ->has('entry.groups'));
    }

    public function test_unknown_version_returns_404(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.releases.show', '9.9.9'))
            ->assertNotFound();
    }

    public function test_filters_entries_by_change_group(): void
    {
        $response = $this->actingAs($this->admin())
            ->get(route('admin.releases.index', ['group' => 'added']))
            ->assertOk();

        $props = $response->viewData('page')['props'];
        $this->assertNotEmpty($props['entries']);

        foreach ($props['entries'] as $entry) {
            $this->assertContains('added', array_column($entry['groups'], 'key'));
        }
    }

    public function test_missing_changelog_still_returns_empty_page(): void
    {
        $this->mock(ChangelogService::class, function ($mock) {
            $mock->shouldReceive('entries')->andReturn([]);
        });

        $this->actingAs($this->admin())
            ->get(route('admin.releases.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->where('entries', []));
    }

    public function test_create_routes_were_removed(): void
    {
        $this->assertFalse(\Illuminate\Support\Facades\Route::has('admin.releases.create'));
        $this->assertFalse(\Illuminate\Support\Facades\Route::has('admin.releases.store'));
        $this->assertFalse(\Illuminate\Support\Facades\Route::has('admin.releases.edit'));
        $this->assertFalse(\Illuminate\Support\Facades\Route::has('admin.releases.update'));
        $this->assertFalse(\Illuminate\Support\Facades\Route::has('admin.releases.destroy'));
    }
}
