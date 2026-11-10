<?php

namespace App\Http\Controllers\Admin;


use Inertia\Inertia;
use App\Helpers\CacheHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Danh sách danh mục (chỉ hiện chưa xóa).
     */
    public function index(Request $request)
    {
        $query = Category::query()
            ->with(['parent'])
            ->withCount(['tours', 'posts', 'children']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $categories = $query
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Categories/Index', compact('categories'));
    }

    /**
     * Form tạo mới.
     */
    public function create()
    {
        $parents = Category::orderBy('name')->get();

        return Inertia::render('Admin/Categories/Create', compact('parents'));
    }

    /**
     * Lưu danh mục mới.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }

        if ($request->hasFile('image')) {
            $data['image'] = app(ImageService::class)
                ->upload($request->file('image'), 'categories');
        }

        Category::create($data);

        // ✅ Xóa cache tour + post (category ảnh hưởng cả 2)
        CacheHelper::clearTourCaches();
        CacheHelper::clearPostCaches();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Đã thêm danh mục thành công.');
    }

    /**
     * Chi tiết danh mục.
     */
    public function show(Category $category)
    {
        $category->load(['parent', 'children']);
        $category->loadCount(['tours', 'posts']);

        return Inertia::render('Admin/Categories/Show', compact('category'));
    }

    /**
     * Form chỉnh sửa.
     */
    public function edit(Category $category)
    {
        $parents = Category::where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Categories/Edit', compact('category', 'parents'));
    }

    /**
     * Cập nhật danh mục.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name'], $category->id);
        }

        if ($request->hasFile('image')) {
            $data['image'] = app(ImageService::class)
                ->replace($request->file('image'), $category->image, 'categories');
        }

        $category->update($data);

        // ✅ Xóa cache tour + post
        CacheHelper::clearTourCaches();
        CacheHelper::clearPostCaches();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Đã cập nhật danh mục thành công.');
    }

    /**
     * XÓA MỀM — Từ trang danh sách.
     * Luôn cho phép, không cần kiểm tra liên kết.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        // ✅ Xóa cache tour + post
        CacheHelper::clearTourCaches();
        CacheHelper::clearPostCaches();

        return back()->with('success', 'Đã chuyển danh mục vào thùng rác.');
    }

    /**
     * THÙNG RÁC — Danh sách đã xóa mềm.
     */
    public function trash(Request $request)
    {
        $query = Category::onlyTrashed()->with(['parent']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $categories = $query
            ->orderByDesc('deleted_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Categories/Trash', compact('categories'));
    }

    /**
     * KHÔI PHỤC — Từ thùng rác.
     */
    public function restore(int $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        // ✅ Xóa cache tour + post
        CacheHelper::clearTourCaches();
        CacheHelper::clearPostCaches();

        return back()->with('success', 'Đã khôi phục danh mục "' . $category->name . '".');
    }

    /**
     * XÓA CỨNG — Từ thùng rác.
     * CHỈ cho phép khi không có tour/post/con đang dùng.
     */
    public function forceDestroy(int $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);

        $toursCount    = $category->tours()->count();
        $postsCount    = $category->posts()->count();
        $childrenCount = $category->children()->count();

        $hasLinks = ($toursCount + $postsCount + $childrenCount) > 0;

        if ($hasLinks) {
            $messages = [];
            if ($toursCount > 0) {
                $messages[] = $toursCount . ' hành trình';
            }
            if ($postsCount > 0) {
                $messages[] = $postsCount . ' bài viết';
            }
            if ($childrenCount > 0) {
                $messages[] = $childrenCount . ' danh mục con';
            }

            return back()->with('error',
                'Không thể xóa vĩnh viễn danh mục "' . $category->name . '". '
                . 'Đang có ' . implode(', ', $messages) . ' liên kết tới danh mục này. '
                . 'Vui lòng xử lý các liên kết trước.'
            );
        }

        $imagePath = $category->image;
        $name      = $category->name;
        $category->forceDelete();

        if ($imagePath) {
            app(ImageService::class)->delete($imagePath);
        }

        // ✅ Xóa cache tour + post
        CacheHelper::clearTourCaches();
        CacheHelper::clearPostCaches();

        return back()->with('success', 'Đã xóa vĩnh viễn danh mục "' . $name . '".');
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
            $query = Category::withTrashed()->where('slug', $slug);
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