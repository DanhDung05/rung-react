<?php

namespace App\Http\Controllers\Admin;


use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePageRequest;
use App\Http\Requests\Admin\UpdatePageRequest;
use App\Models\Page;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Danh sách trang tĩnh.
     */
    public function index(Request $request)
    {
        $query = Page::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        $pages = $query
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Pages/Index', compact('pages'));
    }

    /**
     * Form tạo mới.
     */
    public function create()
    {
        return Inertia::render('Admin/Pages/Create');
    }

    /**
     * Lưu trang mới.
     */
    public function store(StorePageRequest $request)
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
                ->upload($request->file('featured_image'), 'pages');
        }

        Page::create($data);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Đã thêm trang tĩnh thành công.');
    }

    /**
     * Chi tiết trang.
     */
    public function show(Page $page)
    {
        return Inertia::render('Admin/Pages/Show', compact('page'));
    }

    /**
     * Form chỉnh sửa.
     */
    public function edit(Page $page)
    {
        return Inertia::render('Admin/Pages/Edit', compact('page'));
    }

    /**
     * Cập nhật trang.
     */
    public function update(UpdatePageRequest $request, Page $page)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['title'], $page->id);
        }

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = $page->published_at ?? now();
        }

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = app(ImageService::class)
                ->replace($request->file('featured_image'), $page->featured_image, 'pages');
        }

        $page->update($data);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Đã cập nhật trang tĩnh thành công.');
    }

    /**
     * XÓA MỀM.
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return back()->with('success', 'Đã chuyển trang vào thùng rác.');
    }

    /**
     * THÙNG RÁC.
     */
    public function trash(Request $request)
    {
        $query = Page::onlyTrashed();

        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        $pages = $query
            ->orderByDesc('deleted_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Pages/Trash', compact('pages'));
    }

    /**
     * KHÔI PHỤC.
     */
    public function restore(int $id)
    {
        $page = Page::onlyTrashed()->findOrFail($id);
        $page->restore();

        return back()->with('success', 'Đã khôi phục trang "' . $page->title . '".');
    }

    /**
     * XÓA CỨNG — Page độc lập, luôn xóa được.
     */
    public function forceDestroy(int $id)
    {
        $page = Page::onlyTrashed()->findOrFail($id);

        $imagePath = $page->featured_image;
        $title     = $page->title;

        $page->forceDelete();

        if ($imagePath) {
            app(ImageService::class)->delete($imagePath);
        }

        return back()->with('success', 'Đã xóa vĩnh viễn trang "' . $title . '".');
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
            $query = Page::withTrashed()->where('slug', $slug);
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