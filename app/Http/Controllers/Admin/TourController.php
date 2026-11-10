<?php

namespace App\Http\Controllers\Admin;


use Inertia\Inertia;
use App\Helpers\CacheHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTourRequest;
use App\Http\Requests\Admin\UpdateTourRequest;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Media;
use App\Models\Tour;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TourController extends Controller
{
    /**
     * Danh sách tour.
     */
    public function index(Request $request)
    {
        $query = Tour::query()
            ->with(['category'])
            ->withCount(['itineraries', 'destinations']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $tours = $query
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $categories = Category::where('type', 'tour')
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Tours/Index', compact('tours', 'categories'));
    }

    /**
     * Form tạo mới.
     */
    public function create()
    {
        $categories   = Category::where('type', 'tour')->orderBy('name')->get();
        $destinations = Destination::orderBy('name')->get();

        return Inertia::render('Admin/Tours/Create', compact('categories', 'destinations'));
    }

    /**
     * Lưu tour mới.
     */
    public function store(StoreTourRequest $request)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = app(ImageService::class)
                ->upload($request->file('featured_image'), 'tours');
        }

        $destinationIds = $data['destination_ids'] ?? [];
        $itineraries    = $data['itineraries'] ?? [];
        unset($data['destination_ids'], $data['itineraries']);

        DB::transaction(function () use ($data, $destinationIds, $itineraries, $request) {
            $tour = Tour::create($data);

            if (!empty($destinationIds)) {
                $tour->destinations()->sync($destinationIds);
            }

            $this->saveItineraries($tour, $itineraries);

            // Upload ảnh phụ (gallery)
            $this->uploadGallery($tour, $request);
        });

        CacheHelper::clearTourCaches();
        CacheHelper::clearHomepage();

        return redirect()
            ->route('admin.tours.index')
            ->with('success', 'Đã thêm tour thành công.');
    }

    /**
     * Chi tiết tour.
     */
    public function show(Tour $tour)
    {
        $tour->load(['category', 'destinations', 'itineraries', 'media']);

        return Inertia::render('Admin/Tours/Show', compact('tour'));
    }

    /**
     * Form chỉnh sửa.
     */
    public function edit(Tour $tour)
    {
        $categories   = Category::where('type', 'tour')->orderBy('name')->get();
        $destinations = Destination::orderBy('name')->get();
        $tour->load(['destinations', 'itineraries', 'media']);

        return Inertia::render('Admin/Tours/Edit', compact('tour', 'categories', 'destinations'));
    }

    /**
     * Cập nhật tour.
     */
    public function update(UpdateTourRequest $request, Tour $tour)
    {
        $data = $request->validated();

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['name'], $tour->id);
        }

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = $tour->published_at ?? now();
        }

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = app(ImageService::class)
                ->replace($request->file('featured_image'), $tour->featured_image, 'tours');
        }

        $destinationIds = $data['destination_ids'] ?? [];
        $itineraries    = $data['itineraries'] ?? [];
        unset($data['destination_ids'], $data['itineraries']);

        DB::transaction(function () use ($tour, $data, $destinationIds, $itineraries, $request) {
            $tour->update($data);

            $tour->destinations()->sync($destinationIds);

            $tour->itineraries()->forceDelete();
            $this->saveItineraries($tour, $itineraries);

            // Upload ảnh phụ mới (nếu có)
            $this->uploadGallery($tour, $request);
        });

        CacheHelper::clearTourCaches();
        CacheHelper::clearHomepage();

        return redirect()
            ->route('admin.tours.index')
            ->with('success', 'Đã cập nhật tour thành công.');
    }

    /**
     * XÓA MỀM.
     */
    public function destroy(Tour $tour)
    {
        $tour->delete();

        CacheHelper::clearTourCaches();
        CacheHelper::clearHomepage();

        return back()->with('success', 'Đã chuyển tour vào thùng rác.');
    }

    /**
     * THÙNG RÁC.
     */
    public function trash(Request $request)
    {
        $query = Tour::onlyTrashed()->with(['category']);

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $tours = $query
            ->orderByDesc('deleted_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Tours/Trash', compact('tours'));
    }

    /**
     * KHÔI PHỤC.
     */
    public function restore(int $id)
    {
        $tour = Tour::onlyTrashed()->findOrFail($id);
        $tour->restore();

        CacheHelper::clearTourCaches();
        CacheHelper::clearHomepage();

        return back()->with('success', 'Đã khôi phục tour "' . $tour->name . '".');
    }

    /**
     * XÓA CỨNG.
     */
    public function forceDestroy(int $id)
    {
        $tour = Tour::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($tour) {
            $tour->destinations()->detach();
            $tour->itineraries()->forceDelete();

            // Xóa ảnh phụ (cả file vật lý + thumbnail + record)
            foreach ($tour->media as $media) {
                // Xóa file gốc
                if ($media->file_path && Storage::disk('public')->exists($media->file_path)) {
                    Storage::disk('public')->delete($media->file_path);
                }

                // Xóa thumbnail (nếu có)
                $thumbPath = $this->getThumbPath($media->file_path);
                if ($thumbPath && Storage::disk('public')->exists($thumbPath)) {
                    Storage::disk('public')->delete($thumbPath);
                }

                $media->forceDelete();
            }

            $imagePath = $tour->featured_image;
            $name      = $tour->name;

            $tour->forceDelete();

            if ($imagePath) {
                app(ImageService::class)->delete($imagePath);
            }
        });

        CacheHelper::clearTourCaches();
        CacheHelper::clearHomepage();

        return back()->with('success', 'Đã xóa vĩnh viễn tour "' . $id . '".');
    }

    // ============================================================
    // HELPERS
    // ============================================================

    protected function saveItineraries(Tour $tour, array $itineraries): void
    {
        foreach ($itineraries as $index => $item) {
            if (empty($item['title'])) {
                continue;
            }

            $tour->itineraries()->create([
                'day_number'    => $item['day_number'] ?? ($index + 1),
                'title'         => $item['title'],
                'description'   => $item['description'] ?? null,
                'distance_km'   => $item['distance_km'] ?? null,
                'altitude'      => $item['altitude'] ?? null,
                'meals'         => $item['meals'] ?? null,
                'accommodation' => $item['accommodation'] ?? null,
                'sort_order'    => $index,
            ]);
        }
    }

    /**
     * Upload ảnh phụ (gallery) cho tour.
     * Đã refactor dùng ImageService — có thumbnail, path chuẩn.
     */
    protected function uploadGallery(Tour $tour, Request $request): void
    {
        if (!$request->hasFile('gallery')) {
            return;
        }

        $imageService = app(ImageService::class);
        $maxSort = $tour->media()->max('sort_order') ?? 0;

        foreach ($request->file('gallery') as $file) {
            if (!$file->isValid()) {
                continue;
            }

            // Lưu file qua ImageService (có thumbnail)
            $path = $imageService->upload($file, 'tours/gallery', 400);

            $tour->media()->create([
                'collection' => Media::COLLECTION_GALLERY,
                'file_name'  => basename($path),
                'file_path'  => $path,
                'mime_type'  => $file->getMimeType(),
                'file_size'  => $file->getSize(),
                'alt_text'   => $tour->name,
                'sort_order' => ++$maxSort,
            ]);
        }
    }

    /**
     * Sinh đường dẫn thumbnail từ path gốc.
     * (Giống ImageService::getThumbPath)
     */
    protected function getThumbPath(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        $info = pathinfo($path);
        return $info['dirname'] . '/thumbs/' . $info['basename'];
    }

    protected function generateSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug     = $baseSlug;
        $counter  = 1;

        while (true) {
            $query = Tour::withTrashed()->where('slug', $slug);
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