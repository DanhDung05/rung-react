<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGallerySlideRequest;
use App\Http\Requests\Admin\UpdateGallerySlideRequest;
use App\Models\GallerySlide;
use App\Services\GallerySlideService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class GallerySlideController extends Controller
{
    public function __construct(private readonly GallerySlideService $service)
    {
        $this->middleware(function (Request $request, $next) {
            abort_unless($request->user()?->role === 'admin', 403, 'Chỉ quản trị viên mới được quản lý thư viện ảnh.');

            return $next($request);
        });
    }

    public function index()
    {
        $slides = GallerySlide::query()
            ->ordered()
            ->paginate(20)
            ->withQueryString()
            ->through(fn (GallerySlide $slide) => $this->present($slide));

        return Inertia::render('Admin/GallerySlides/Index', compact('slides'));
    }

    public function create()
    {
        return Inertia::render('Admin/GallerySlides/Create');
    }

    public function edit(GallerySlide $gallerySlide)
    {
        return Inertia::render('Admin/GallerySlides/Edit', [
            'slide' => $this->present($gallerySlide),
        ]);
    }

    private function present(GallerySlide $slide): array
    {
        return [
            'id'          => $slide->id,
            'title'       => $slide->title,
            'category'    => $slide->category,
            'accent'      => $slide->accent,
            'description' => $slide->description,
            'slide_url'   => Storage::disk('public')->url($slide->slide_path),
            'full_url'    => Storage::disk('public')->url($slide->full_path),
            'crop_x'      => $slide->crop_x,
            'crop_y'      => $slide->crop_y,
            'full_width'  => $slide->full_width,
            'full_height' => $slide->full_height,
            'sort_order'  => $slide->sort_order,
            'is_active'   => $slide->is_active,
        ];
    }
    public function store(StoreGallerySlideRequest $request)
    {
        $this->service->create($request->file('image'), $request->validated());

        return redirect()
            ->route('admin.gallery-slides.index')
            ->with('success', 'Đã thêm ảnh thư viện thành công.');
    }

    public function update(UpdateGallerySlideRequest $request, GallerySlide $gallerySlide)
    {
        $this->service->update($gallerySlide, $request->file('image'), $request->validated());

        return redirect()
            ->route('admin.gallery-slides.index')
            ->with('success', 'Đã cập nhật ảnh thư viện thành công.');
    }

    public function destroy(GallerySlide $gallerySlide)
    {
        $this->service->delete($gallerySlide);

        return back()->with('success', 'Đã xoá ảnh thư viện.');
    }

    public function move(GallerySlide $gallerySlide, string $direction)
    {
        $this->service->move($gallerySlide, $direction);

        return back()->with('success', 'Đã đổi thứ tự ảnh.');
    }
}
