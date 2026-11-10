<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

/**
 * Nhóm B2 — Bài viết + Tag.
 */
class ContentManagementTest extends TestCase
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

    private function postCategory(): Category
    {
        return Category::create(['type' => 'post', 'name' => 'Cẩm nang', 'slug' => 'cam-nang']);
    }

    // ===================== POST =====================

    public function test_admin_xem_danh_sach_bai_viet(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.posts.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Posts/Index')
                ->has('posts')
                ->has('categories')
                ->has('authors')
            );
    }

    public function test_admin_tao_bai_viet_voi_tag(): void
    {
        $admin = $this->admin();
        $category = $this->postCategory();
        $tag = Tag::create(['name' => 'Trekking', 'slug' => 'trekking']);

        $this->actingAs($admin)
            ->post(route('admin.posts.store'), [
                'user_id'     => $admin->id,
                'category_id' => $category->id,
                'title'       => 'Kinh nghiệm trekking',
                'slug'        => '',
                'status'      => 'published',
                'tag_ids'     => [$tag->id],
            ])
            ->assertRedirect(route('admin.posts.index'));

        $post = Post::where('slug', 'kinh-nghiem-trekking')->first();

        $this->assertNotNull($post);
        $this->assertSame('published', $post->status);
        $this->assertNotNull($post->published_at);
        $this->assertCount(1, $post->tags);
    }

    public function test_admin_sua_bai_viet(): void
    {
        $admin = $this->admin();
        $category = $this->postCategory();
        $post = Post::create(['user_id' => $admin->id, 'category_id' => $category->id, 'title' => 'Cũ', 'slug' => 'cu', 'status' => 'draft']);

        $this->actingAs($admin)
            ->put(route('admin.posts.update', $post->id), [
                'user_id'     => $admin->id,
                'category_id' => $category->id,
                'title'       => 'Mới',
                'slug'        => 'moi',
                'status'      => 'draft',
            ])
            ->assertRedirect(route('admin.posts.index'));

        $this->assertSame('Mới', $post->fresh()->title);
    }

    public function test_admin_xoa_mem_bai_viet(): void
    {
        $admin = $this->admin();
        $category = $this->postCategory();
        $post = Post::create(['user_id' => $admin->id, 'category_id' => $category->id, 'title' => 'Xóa', 'slug' => 'xoa', 'status' => 'draft']);

        $this->actingAs($admin)
            ->delete(route('admin.posts.destroy', $post->id))
            ->assertRedirect();

        $this->assertSoftDeleted('posts', ['id' => $post->id]);
    }

    public function test_validate_bai_viet_thieu_tieu_de_va_danh_muc(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.posts.store'), ['status' => 'draft'])
            ->assertSessionHasErrors(['user_id', 'category_id', 'title']);
    }

    // ===================== TAG =====================

    public function test_admin_xem_danh_sach_tag(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.tags.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Admin/Tags/Index')->has('tags'));
    }

    public function test_admin_tao_tag_va_tu_sinh_slug(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.tags.store'), ['name' => 'Du lịch bền vững', 'slug' => ''])
            ->assertRedirect(route('admin.tags.index'));

        $this->assertDatabaseHas('tags', ['slug' => 'du-lich-ben-vung']);
    }

    public function test_admin_sua_va_xoa_tag(): void
    {
        $admin = $this->admin();
        $tag = Tag::create(['name' => 'Tên cũ', 'slug' => 'ten-cu']);

        $this->actingAs($admin)
            ->put(route('admin.tags.update', $tag->id), ['name' => 'Tên mới', 'slug' => 'ten-moi'])
            ->assertRedirect(route('admin.tags.index'));

        $this->assertSame('Tên mới', $tag->fresh()->name);

        $this->actingAs($admin)
            ->delete(route('admin.tags.destroy', $tag->id))
            ->assertRedirect();

        $this->assertSoftDeleted('tags', ['id' => $tag->id]);
    }
}
