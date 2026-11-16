<?php

namespace App\Http\Controllers\Admin;


use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Danh sách đánh giá (chưa xóa).
     */
    public function index(Request $request)
    {
        $query = Review::with(['tour', 'user:id,name,email'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reviews = $query->paginate(15)->withQueryString();

        return Inertia::render('Admin/Reviews/Index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['status' => Review::STATUS_APPROVED]);
        app(\App\Services\AdminEventBroadcaster::class)->reviewModerated($review, 'approved');
        return back()->with('success', 'Đã duyệt đánh giá.');
    }

    public function reject(Review $review)
    {
        $review->update(['status' => Review::STATUS_REJECTED]);
        app(\App\Services\AdminEventBroadcaster::class)->reviewModerated($review, 'rejected');
        return back()->with('success', 'Đã từ chối đánh giá.');
    }

    /**
     * XÓA MỀM — chuyển vào thùng rác.
     */
    public function destroy(Review $review)
    {
        $review->delete();
        app(\App\Services\AdminEventBroadcaster::class)->reviewModerated($review, 'deleted');
        return back()->with('success', 'Đã chuyển đánh giá vào thùng rác.');
    }

    /**
     * THÙNG RÁC — danh sách đã xóa mềm.
     */
    public function trash(Request $request)
    {
        $query = Review::onlyTrashed()->with(['tour', 'user:id,name,email']);

        if ($request->filled('keyword')) {
            $query->where('comment', 'like', '%' . $request->keyword . '%');
        }

        $reviews = $query
            ->orderByDesc('deleted_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Reviews/Trash', compact('reviews'));
    }

    /**
     * KHÔI PHỤC — từ thùng rác.
     */
    public function restore(int $id)
    {
        $review = Review::onlyTrashed()->findOrFail($id);
        $review->restore();
        app(\App\Services\AdminEventBroadcaster::class)->reviewModerated($review, 'restored');

        return back()->with('success', 'Đã khôi phục đánh giá.');
    }

    /**
     * XÓA CỨNG — xóa vĩnh viễn.
     */
    public function forceDestroy(int $id)
    {
        $review = Review::onlyTrashed()->findOrFail($id);
        $review->forceDelete();
        app(\App\Services\AdminEventBroadcaster::class)->reviewModerated($review, 'deleted');

        return back()->with('success', 'Đã xóa vĩnh viễn đánh giá.');
    }
}