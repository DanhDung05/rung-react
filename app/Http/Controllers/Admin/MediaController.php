<?php

namespace App\Http\Controllers\Admin;


use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Danh sách media (chưa xóa).
     */
    public function index(Request $request)
    {
        $query = Media::query()
            ->with('mediable')
            ->orderByDesc('created_at');

        if ($request->filled('collection')) {
            $query->where('collection', $request->collection);
        }

        if ($request->filled('mediable_type')) {
            $query->where('mediable_type', $request->mediable_type);
        }

        if ($request->filled('keyword')) {
            $query->where('file_name', 'like', '%' . $request->keyword . '%');
        }

        $media = $query->paginate(24)->withQueryString();

        $collections = Media::query()
            ->select('collection')
            ->distinct()
            ->pluck('collection');

        $mediableTypes = Media::query()
            ->select('mediable_type')
            ->whereNotNull('mediable_type')
            ->distinct()
            ->pluck('mediable_type');

        $trashedCount = Media::onlyTrashed()->count();

        return Inertia::render('Admin/Media/Index', compact(
            'media',
            'collections',
            'mediableTypes',
            'trashedCount'
        ));
    }

    /**
     * Thùng rác — media đã xóa mềm.
     */
    public function trash(Request $request)
    {
        $query = Media::onlyTrashed()
            ->with('mediable')
            ->orderByDesc('deleted_at');

        if ($request->filled('collection')) {
            $query->where('collection', $request->collection);
        }

        if ($request->filled('keyword')) {
            $query->where('file_name', 'like', '%' . $request->keyword . '%');
        }

        $media = $query->paginate(24)->withQueryString();

        $collections = Media::onlyTrashed()
            ->select('collection')
            ->distinct()
            ->pluck('collection');

        return Inertia::render('Admin/Media/Trash', compact('media', 'collections'));
    }

    /**
     * XÓA MỀM — chuyển vào thùng rác, giữ file vật lý.
     * Hỗ trợ cả AJAX (từ form tour) và form submit.
     */
    public function destroy(Media $media): JsonResponse|RedirectResponse
    {
        $media->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Đã chuyển ảnh vào thùng rác.');
    }

    /**
     * KHÔI PHỤC — từ thùng rác.
     */
    public function restore(int $id): RedirectResponse
    {
        $media = Media::onlyTrashed()->findOrFail($id);
        $media->restore();

        return back()->with('success', 'Đã khôi phục ảnh "' . $media->file_name . '".');
    }

    /**
     * XÓA VĨNH VIỄN — xóa file vật lý + record.
     */
    public function forceDestroy(int $id): RedirectResponse
    {
        $media = Media::onlyTrashed()->findOrFail($id);

        // Xóa file gốc
        if ($media->file_path && Storage::disk('public')->exists($media->file_path)) {
            Storage::disk('public')->delete($media->file_path);
        }

        // Xóa thumbnail (nếu có)
        $thumbPath = $this->getThumbPath($media->file_path);
        if ($thumbPath && Storage::disk('public')->exists($thumbPath)) {
            Storage::disk('public')->delete($thumbPath);
        }

        $fileName = $media->file_name;
        $media->forceDelete();

        return back()->with('success', 'Đã xóa vĩnh viễn ảnh "' . $fileName . '".');
    }

    /**
     * Sinh đường dẫn thumbnail từ path gốc.
     */
    protected function getThumbPath(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        $info = pathinfo($path);
        return $info['dirname'] . '/thumbs/' . $info['basename'];
    }
}