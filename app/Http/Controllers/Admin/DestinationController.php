<?php

namespace App\Http\Controllers\Admin;


use Inertia\Inertia;
use App\Helpers\CacheHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDestinationRequest;
use App\Http\Requests\Admin\UpdateDestinationRequest;
use App\Models\Destination;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    /**
     * Danh sách điểm đến.
     */
    public function index(Request $request)
    {
        $query = Destination::query()
            ->with(['parent'])
            ->withCount(['tours', 'children']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $destinations = $query
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $regions = Destination::query()
            ->whereNotNull('region')
            ->distinct()
            ->pluck('region');

        return Inertia::render('Admin/Destinations/Index', compact('destinations', 'regions'));
    }

    /**
     * Form tạo mới.
     */
    public function create()
    {
        $parents = Destination::orderBy('name')->get();

        return Inertia::render('Admin/Destinations/Create', compact('parents'));
    }

    /**
     * Lưu điểm đến mới.
     */
    public function store(StoreDestinationRequest $request)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            $data['image'] = app(ImageService::class)
                ->upload($request->file('image'), 'destinations');
        }

        Destination::create($data);

        // ✅ Xóa cache
        CacheHelper::clearDestinationCaches();
        CacheHelper::clearHomepage();

        return redirect()
            ->route('admin.destinations.index')
            ->with('success', 'Đã thêm điểm đến thành công.');
    }

    /**
     * Chi tiết điểm đến.
     */
    public function show(Destination $destination)
    {
        $destination->load(['parent', 'children', 'tours']);
        $destination->loadCount(['tours', 'children']);

        return Inertia::render('Admin/Destinations/Show', compact('destination'));
    }

    /**
     * Form chỉnh sửa.
     */
    public function edit(Destination $destination)
    {
        $parents = Destination::where('id', '!=', $destination->id)
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Destinations/Edit', compact('destination', 'parents'));
    }

    /**
     * Cập nhật điểm đến.
     */
    public function update(UpdateDestinationRequest $request, Destination $destination)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name'], $destination->id);
        }

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = $destination->published_at ?? now();
        }

        if ($request->hasFile('image')) {
            $data['image'] = app(ImageService::class)
                ->replace($request->file('image'), $destination->image, 'destinations');
        }

        $destination->update($data);

        // ✅ Xóa cache
        CacheHelper::clearDestinationCaches();
        CacheHelper::clearHomepage();

        return redirect()
            ->route('admin.destinations.index')
            ->with('success', 'Đã cập nhật điểm đến thành công.');
    }

    /**
     * XÓA MỀM.
     */
    public function destroy(Destination $destination)
    {
        $destination->delete();

        // ✅ Xóa cache
        CacheHelper::clearDestinationCaches();
        CacheHelper::clearHomepage();

        return back()->with('success', 'Đã chuyển điểm đến vào thùng rác.');
    }

    /**
     * THÙNG RÁC.
     */
    public function trash(Request $request)
    {
        $query = Destination::onlyTrashed()->with(['parent']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $destinations = $query
            ->orderByDesc('deleted_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Destinations/Trash', compact('destinations'));
    }

    /**
     * KHÔI PHỤC.
     */
    public function restore(int $id)
    {
        $destination = Destination::onlyTrashed()->findOrFail($id);
        $destination->restore();

        // ✅ Xóa cache
        CacheHelper::clearDestinationCaches();
        CacheHelper::clearHomepage();

        return back()->with('success', 'Đã khôi phục điểm đến "' . $destination->name . '".');
    }

    /**
     * XÓA CỨNG.
     */
    public function forceDestroy(int $id)
    {
        $destination = Destination::onlyTrashed()->findOrFail($id);

        $toursCount    = $destination->tours()->count();
        $childrenCount = $destination->children()->count();

        $hasLinks = ($toursCount + $childrenCount) > 0;

        if ($hasLinks) {
            $messages = [];
            if ($toursCount > 0) {
                $messages[] = $toursCount . ' hành trình';
            }
            if ($childrenCount > 0) {
                $messages[] = $childrenCount . ' điểm đến con';
            }

            return back()->with('error',
                'Không thể xóa vĩnh viễn điểm đến "' . $destination->name . '". '
                . 'Đang có ' . implode(', ', $messages) . ' liên kết tới điểm đến này. '
                . 'Vui lòng xử lý các liên kết trước.'
            );
        }

        $imagePath = $destination->image;
        $name      = $destination->name;
        $destination->forceDelete();

        if ($imagePath) {
            app(ImageService::class)->delete($imagePath);
        }

        // ✅ Xóa cache
        CacheHelper::clearDestinationCaches();
        CacheHelper::clearHomepage();

        return back()->with('success', 'Đã xóa vĩnh viễn điểm đến "' . $name . '".');
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
            $query = Destination::withTrashed()->where('slug', $slug);
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