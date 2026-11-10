<?php

namespace App\Http\Controllers\Admin;


use Inertia\Inertia;
use App\Helpers\CacheHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Danh sách bài viết.
     */
    public function index(Request $request)
    {
        $query = Post::query()
            ->with(['author', 'category'])
            ->withCount(['tags']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        $posts = $query
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $categories = Category::where('type', 'post')->orderBy('name')->get();
        $authors    = User::orderBy('name')->get();

        return Inertia::render('Admin/Posts/Index', compact('posts', 'categories', 'authors'));
    }

    /**
     * Form tạo mới.
     */
    public function create()
    {
        $categories = Category::where('type', 'post')->orderBy('name')->get();
        $tags       = Tag::orderBy('name')->get();
        $authors    = User::orderBy('name')->get();

        return Inertia::render('Admin/Posts/Create', compact('categories', 'tags', 'authors'));
    }

    /**
     * Lưu bài viết mới.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['title']);
        }

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = app(ImageService::class)
                ->upload($request->file('featured_image'), 'posts');
        }

        $tagIds = $data['tag_ids'] ?? [];
        unset($data['tag_ids']);

        DB::transaction(function () use ($data, $tagIds) {
            $post = Post::create($data);

            if (!empty($tagIds)) {
                $post->tags()->sync($tagIds);
            }
        });

        // ✅ Xóa cache
        CacheHelper::clearPostCaches();
        CacheHelper::clearHomepage();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Đã thêm bài viết thành công.');
    }

    /**
     * Chi tiết bài viết.
     */
    public function show(Post $post)
    {
        $post->load(['author', 'category', 'tags']);
        $post->loadCount(['tags']);

        return Inertia::render('Admin/Posts/Show', compact('post'));
    }

    /**
     * Form chỉnh sửa.
     */
    public function edit(Post $post)
    {
        $categories = Category::where('type', 'post')->orderBy('name')->get();
        $tags       = Tag::orderBy('name')->get();
        $authors    = User::orderBy('name')->get();
        $post->load(['tags']);

        return Inertia::render('Admin/Posts/Edit', compact('post', 'categories', 'tags', 'authors'));
    }

    /**
     * Cập nhật bài viết.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['title'], $post->id);
        }

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = $post->published_at ?? now();
        }

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = app(ImageService::class)
                ->replace($request->file('featured_image'), $post->featured_image, 'posts');
        }

        $tagIds = $data['tag_ids'] ?? [];
        unset($data['tag_ids']);

        DB::transaction(function () use ($post, $data, $tagIds) {
            $post->update($data);
            $post->tags()->sync($tagIds);
        });

        // ✅ Xóa cache
        CacheHelper::clearPostCaches();
        CacheHelper::clearHomepage();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Đã cập nhật bài viết thành công.');
    }

    /**
     * XÓA MỀM.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        // ✅ Xóa cache
        CacheHelper::clearPostCaches();
        CacheHelper::clearHomepage();

        return back()->with('success', 'Đã chuyển bài viết vào thùng rác.');
    }

    /**
     * THÙNG RÁC.
     */
    public function trash(Request $request)
    {
        $query = Post::onlyTrashed()->with(['author', 'category']);

        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        $posts = $query
            ->orderByDesc('deleted_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Posts/Trash', compact('posts'));
    }

    /**
     * KHÔI PHỤC.
     */
    public function restore(int $id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $post->restore();

        // ✅ Xóa cache
        CacheHelper::clearPostCaches();
        CacheHelper::clearHomepage();

        return back()->with('success', 'Đã khôi phục bài viết "' . $post->title . '".');
    }

    /**
     * XÓA CỨNG — Chặn nếu có tags liên kết.
     */
    public function forceDestroy(int $id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);

        $tagsCount = $post->tags()->count();

        if ($tagsCount > 0) {
            return back()->with('error',
                'Không thể xóa vĩnh viễn bài viết "' . $post->title . '". '
                . 'Đang có ' . $tagsCount . ' tag liên kết. '
                . 'Vui lòng gỡ tag trước khi xóa.'
            );
        }

        $imagePath = $post->featured_image;
        $title     = $post->title;

        $post->forceDelete();

        if ($imagePath) {
            app(ImageService::class)->delete($imagePath);
        }

        // ✅ Xóa cache
        CacheHelper::clearPostCaches();
        CacheHelper::clearHomepage();

        return back()->with('success', 'Đã xóa vĩnh viễn bài viết "' . $title . '".');
    }

    /**
     * Sinh slug duy nhất.
     */
    protected function generateSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug     = $baseSlug;
        $counter  = 1;

        while (true) {
            $query = Post::withTrashed()->where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if (!$query->exists()) {
                break;
            }
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }
}