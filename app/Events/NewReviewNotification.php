<?php

namespace App\Events;

use App\Models\Review;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Support\AdminBadges;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewReviewNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $review;

    public array $counts = [];

    public function __construct(Review $review, array $counts = [])
    {
        $this->review = $review;
        $this->counts = $counts ?: AdminBadges::counts();
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('admin-notifications')];
    }

    public function broadcastAs(): string
    {
        return 'new-review';
    }

    /**
     * Chỉ dữ liệu tối thiểu — không email, nội dung, tên khách.
     */
    public function broadcastWith(): array
    {
        return [
            'id'    => $this->review->id,
            'type'  => 'review',
            'title' => 'Đánh giá mới cần duyệt',
            'time'  => $this->review->created_at?->diffForHumans() ?? now()->diffForHumans(),
            'contacts_new' => $this->counts['contacts_new'] ?? 0,
            'reviews_pending' => $this->counts['reviews_pending'] ?? 0,
        ];
    }
}
