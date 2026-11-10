<?php

namespace App\Http\Controllers\Admin;


use Inertia\Inertia;
use App\Helpers\CacheHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSliderRequest;
use App\Http\Requests\Admin\UpdateSliderRequest;
use App\Models\Slider;
use App\Services\ImageService;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * Danh sách slider.
     */
    public function index(Request $request)
    {
        $query = Slider::query();

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        $sliders = $query
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $positions = Slider::query()
            ->whereNotNull('position')
            ->distinct()
            ->pluck('position');

        return Inertia::render('Admin/Sliders/Index', compact('sliders', 'positions'));
    }

    /**
     * Form tạo mới.
     */
    public function create()
    {
        return Inertia::render('Admin/Sliders/Create');
    }

    /**
     * Lưu slider mới.
     */
    public function store(StoreSliderRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = app(ImageService::class)
                ->upload($request->file('image'), 'sliders', 1200);
        }

        Slider::create($data);

        // ✅ Xóa cache
        CacheHelper::clearHomepage();

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Đã thêm slider thành công.');
    }

    /**
     * Chi tiết slider.
     */
    public function show(Slider $slider)
    {
        return Inertia::render('Admin/Sliders/Show', compact('slider'));
    }

    /**
     * Form chỉnh sửa.
     */
    public function edit(Slider $slider)
    {
        return Inertia::render('Admin/Sliders/Edit', compact('slider'));
    }

    /**
     * Cập nhật slider.
     */
    public function update(UpdateSliderRequest $request, Slider $slider)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = app(ImageService::class)
                ->replace($request->file('image'), $slider->image, 'sliders', 1200);
        }

        $slider->update($data);

        // ✅ Xóa cache
        CacheHelper::clearHomepage();

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Đã cập nhật slider thành công.');
    }

    /**
     * XÓA MỀM.
     */
    public function destroy(Slider $slider)
    {
        $slider->delete();

        // ✅ Xóa cache
        CacheHelper::clearHomepage();

        return back()->with('success', 'Đã chuyển slider vào thùng rác.');
    }

    /**
     * THÙNG RÁC.
     */
    public function trash(Request $request)
    {
        $query = Slider::onlyTrashed();

        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        $sliders = $query
            ->orderByDesc('deleted_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Sliders/Trash', compact('sliders'));
    }

    /**
     * KHÔI PHỤC.
     */
    public function restore(int $id)
    {
        $slider = Slider::onlyTrashed()->findOrFail($id);
        $slider->restore();

        // ✅ Xóa cache
        CacheHelper::clearHomepage();

        return back()->with('success', 'Đã khôi phục slider thành công.');
    }

    /**
     * XÓA CỨNG.
     */
    public function forceDestroy(int $id)
    {
        $slider = Slider::onlyTrashed()->findOrFail($id);

        $imagePath = $slider->image;
        $slider->forceDelete();

        if ($imagePath) {
            app(ImageService::class)->delete($imagePath);
        }

        // ✅ Xóa cache
        CacheHelper::clearHomepage();

        return back()->with('success', 'Đã xóa vĩnh viễn slider.');
    }
}