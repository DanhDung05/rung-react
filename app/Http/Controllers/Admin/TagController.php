<?php

namespace App\Http\Controllers\Admin;


use Inertia\Inertia;
use App\Helpers\CacheHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTagRequest;
use App\Http\Requests\Admin\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Danh sách tags.
     */
    public function index(Request $request)
    {
        $query = Tag::query()->withCount('posts');

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $tags = $query
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Tags/Index', compact('tags'));
    }

    /**
     * Form tạo mới.
     */
    public function create()
    {
        return Inertia::render('Admin/Tags/Create');
    }

    /**
     * Lưu tag mới.
     */
    public function store(StoreTagRequest $request)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }

        Tag::create($data);

        // ✅ Xóa cache post (vì tags ảnh hưởng sidebar post)
        CacheHelper::clearPostCaches();

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Đã thêm tag thành công.');
    }

    /**
     * Chi tiết tag.
     */
    public function show(Tag $tag)
    {
        $tag->loadCount('posts');
        $tag->load(['posts' => function ($q) {
            $q->latest()->limit(10);
        }]);

        return Inertia::render('Admin/Tags/Show', compact('tag'));
    }

    /**
     * Form chỉnh sửa.
     */
    public function edit(Tag $tag)
    {
        return Inertia::render('Admin/Tags/Edit', compact('tag'));
    }

    /**
     * Cập nhật tag.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name'], $tag->id);
        }

        $tag->update($data);

        // ✅ Xóa cache post
        CacheHelper::clearPostCaches();

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Đã cập nhật tag thành công.');
    }

    /**
     * XÓA MỀM.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        // ✅ Xóa cache post
        CacheHelper::clearPostCaches();

        return back()->with('success', 'Đã chuyển tag vào thùng rác.');
    }

    /**
     * THÙNG RÁC.
     */
    public function trash(Request $request)
    {
        $query = Tag::onlyTrashed();

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $tags = $query
            ->orderByDesc('deleted_at')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Tags/Trash', compact('tags'));
    }

    /**
     * KHÔI PHỤC.
     */
    public function restore(int $id)
    {
        $tag = Tag::onlyTrashed()->findOrFail($id);
        $tag->restore();

        // ✅ Xóa cache post
        CacheHelper::clearPostCaches();

        return back()->with('success', 'Đã khôi phục tag "' . $tag->name . '".');
    }

    /**
     * XÓA CỨNG — Chặn nếu có post liên kết.
     */
    public function forceDestroy(int $id)
    {
        $tag = Tag::onlyTrashed()->findOrFail($id);

        $postsCount = $tag->posts()->count();

        if ($postsCount > 0) {
            return back()->with('error',
                'Không thể xóa vĩnh viễn tag "' . $tag->name . '". '
                . 'Đang có ' . $postsCount . ' bài viết sử dụng tag này. '
                . 'Vui lòng gỡ tag khỏi bài viết trước.'
            );
        }

        $name = $tag->name;
        $tag->forceDelete();

        // ✅ Xóa cache post
        CacheHelper::clearPostCaches();

        return back()->with('success', 'Đã xóa vĩnh viễn tag "' . $name . '".');
    }

    /**
     * Sinh slug duy nhất.
     */
    protected function generateSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug     = $baseSlug;
        $counter  = 1;

        while (true) {
            $query = Tag::withTrashed()->where('slug', $slug);
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