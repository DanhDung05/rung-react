<?php

namespace Tests\Feature\Admin;

use App\Models\Release;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReleaseHistoryTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'version'          => '2.3.0',
            'previous_version' => '2.2.0',
            'title'            => 'Bổ sung lịch sử cập nhật',
            'category'         => 'feature',
            'status'           => 'released',
            'content'          => "- Thêm trang lịch sử\n- Đồng bộ VERSION",
            'released_at'      => '2026-11-14',
        ], $overrides);
    }

    public function test_guest_cannot_access_release_history(): void
    {
        $this->get(route('admin.releases.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_release_history(): void
    {
        Release::create($this->payload() + ['created_by' => $this->admin()->id]);

        $this->actingAs($this->admin())
            ->get(route('admin.releases.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Admin/Releases/Index')
                ->has('releases.data', 1)
                ->where('currentVersion', trim(file_get_contents(base_path('VERSION')))));
    }

    public function test_admin_can_create_release(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.releases.store'), $this->payload())
            ->assertRedirect(route('admin.releases.index'));

        $this->assertDatabaseHas('releases', [
            'version' => '2.3.0',
            'status'  => 'released',
        ]);
    }

    public function test_duplicate_version_is_rejected(): void
    {
        Release::create($this->payload());

        $this->actingAs($this->admin())
            ->post(route('admin.releases.store'), $this->payload())
            ->assertSessionHasErrors('version');

        $this->assertSame(1, Release::where('version', '2.3.0')->count());
    }

    public function test_invalid_version_format_is_rejected(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.releases.store'), $this->payload(['version' => 'abc']))
            ->assertSessionHasErrors('version');
    }

    public function test_admin_can_update_and_delete_release(): void
    {
        $release = Release::create($this->payload());

        $this->actingAs($this->admin())
            ->put(route('admin.releases.update', $release), $this->payload(['title' => 'Đã sửa']))
            ->assertRedirect(route('admin.releases.show', $release));

        $this->assertSame('Đã sửa', $release->fresh()->title);

        $this->actingAs($this->admin())
            ->delete(route('admin.releases.destroy', $release))
            ->assertRedirect(route('admin.releases.index'));

        $this->assertDatabaseMissing('releases', ['id' => $release->id]);
    }

    public function test_filters_by_version_and_category(): void
    {
        Release::create($this->payload(['version' => '2.3.0', 'category' => 'feature']));
        Release::create($this->payload(['version' => '2.2.1', 'category' => 'fix', 'title' => 'Hotfix']));

        $this->actingAs($this->admin())
            ->get(route('admin.releases.index', ['category' => 'fix']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->has('releases.data', 1)->where('releases.data.0.version', '2.2.1'));
    }
}
