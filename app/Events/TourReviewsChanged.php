<?php

namespace App\Events;

use App\Models\Review;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Cập nhật công khai cho danh sách đánh giá của một tour.
 * Chỉ chứa dữ liệu đánh giá đã công khai (không email/SĐT).
 */
class TourReviewsChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $tourId,
        public string $action,
        public ?Review $review = null,
    ) {
    }

    public function broadcastOn(): array
    {
        return [new Channel('tour.' . $this->tourId . '.reviews')];
    }

    public function broadcastAs(): string
    {
        return 'reviews.changed';
    }

    public function broadcastWith(): array
    {
        $review = $this->review;

        return [
            'tour_id'   => $this->tourId,
            'action'    => $this->action,
            'review_id' => $review?->id,
            'review'  => $review && $this->action !== 'deleted' && $review->status === Review::STATUS_APPROVED
                ? [
                    'id'          => $review->id,
                    'author_name' => $review->author_name ?: ($review->user?->name ?: 'Khách hàng'),
                    'rating'      => $review->rating,
                    'comment'     => $review->comment,
                    'created_at'  => $review->created_at?->toIso8601String(),
                ]
                : null,
        ];
    }
}
