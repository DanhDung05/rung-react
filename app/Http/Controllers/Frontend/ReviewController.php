<?php

namespace App\Http\Controllers\Frontend;

use App\Events\NewReviewNotification;
use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Tour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Tour $tour): RedirectResponse
    {
        $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'rating.required'  => 'Vui lòng chọn số sao.',
            'rating.min'       => 'Số sao tối thiểu là 1.',
            'rating.max'       => 'Số sao tối đa là 5.',
            'comment.required' => 'Vui lòng nhập nội dung đánh giá.',
            'comment.min'      => 'Nội dung đánh giá phải có ít nhất 10 ký tự.',
        ]);

        $exists = Review::where('tour_id', $tour->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($exists) {
            return back()->with('error', 'Bạn đã đánh giá tour này rồi.');
        }

        $review = Review::create([
            'tour_id' => $tour->id,
            'user_id' => auth()->id(),
            'rating'  => $request->rating,
            'comment' => $request->comment,
            'status'  => Review::STATUS_PENDING,
        ]);

        // Dispatch event broadcast (real-time)
        event(new NewReviewNotification($review));

        return back()->with('success', 'Cảm ơn bạn đã đánh giá! Đánh giá sẽ hiển thị sau khi được duyệt.');
    }
}