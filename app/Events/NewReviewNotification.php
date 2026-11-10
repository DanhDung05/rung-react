<?php

namespace App\Events;

use App\Models\Review;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewReviewNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function broadcastOn(): array
    {
        return [new Channel('admin-notifications')];
    }

    public function broadcastAs(): string
    {
        return 'new-review';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->review->id,
            'tour_name' => $this->review->tour->name ?? 'Tour',
            'rating' => $this->review->rating,
            'comment' => \Illuminate\Support\Str::limit($this->review->comment, 60),
            'time' => $this->review->created_at->diffForHumans(),
        ];
    }
}