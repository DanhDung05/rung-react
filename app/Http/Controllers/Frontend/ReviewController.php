<?php

namespace App\Http\Controllers\Frontend;

use App\Services\AdminEventBroadcaster;
use App\Http\Controllers\Concerns\HandlesHoneypot;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\StoreReviewRequest;
use App\Models\Review;
use App\Models\Tour;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    use HandlesHoneypot;

    public function store(StoreReviewRequest $request, Tour $tour): RedirectResponse
    {
        if ($this->isHoneypot($request)) {
            return back()->with('success', 'Cảm ơn bạn đã đánh giá! Đánh giá sẽ hiển thị sau khi được duyệt.');
        }

        $data = $request->validated();
        $user = $request->user();

        $authorName  = $user?->name ?: trim($data['author_name'] ?? '');
        $authorEmail = $user?->email ?: trim($data['author_email'] ?? '');

        $duplicate = Review::query()
            ->where('tour_id', $tour->id)
            ->when($user, fn ($query) => $query->where('user_id', $user->id))
            ->when(! $user, fn ($query) => $query->where('author_email', $authorEmail))
            ->exists();

        if ($duplicate) {
            return back()->with('error', 'Bạn đã đánh giá tour này rồi.');
        }

        $review = Review::create([
            'tour_id'      => $tour->id,
            'user_id'      => $user?->id,
            'author_name'  => $authorName,
            'author_email' => $authorEmail,
            'rating'       => $data['rating'],
            'comment'      => $data['comment'],
            'status'       => Review::STATUS_PENDING,
        ]);

        try {
            app(AdminEventBroadcaster::class)->newReview($review);
        } catch (\Throwable $e) {
            Log::warning('Broadcast review thất bại (bỏ qua)', [
                'review_id' => $review->id,
                'error'     => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Cảm ơn bạn đã đánh giá! Đánh giá sẽ hiển thị sau khi được duyệt.');
    }
}
