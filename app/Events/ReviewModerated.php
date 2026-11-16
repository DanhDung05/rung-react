<?php

namespace App\Events;

use App\Models\Review;
use App\Support\AdminBadges;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReviewModerated implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Review $review,
        public string $status,
        public array $counts = [],
    ) {
        $this->counts = $counts ?: AdminBadges::counts();
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('admin-notifications')];
    }

    public function broadcastAs(): string
    {
        return 'review-moderated';
    }

    public function broadcastWith(): array
    {
        return [
            'id'              => $this->review->id,
            'type'            => 'review_moderated',
            'title'           => 'Đánh giá đã được cập nhật',
            'status'          => $this->status,
            'time'            => now()->toIso8601String(),
            'contacts_new'    => $this->counts['contacts_new'] ?? 0,
            'reviews_pending' => $this->counts['reviews_pending'] ?? 0,
        ];
    }
}
