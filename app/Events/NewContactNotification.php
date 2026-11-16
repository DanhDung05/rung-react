<?php

namespace App\Events;

use App\Models\Contact;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Support\AdminBadges;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewContactNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $contact;

    public array $counts = [];

    public function __construct(Contact $contact, array $counts = [])
    {
        $this->contact = $contact;
        $this->counts = $counts ?: AdminBadges::counts();
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('admin-notifications')];
    }

    public function broadcastAs(): string
    {
        return 'new-contact';
    }

    /**
     * Chỉ dữ liệu tối thiểu — không email, SĐT, nội dung tin nhắn.
     */
    public function broadcastWith(): array
    {
        return [
            'id'    => $this->contact->id,
            'type'  => $this->contact->type === 'tour_inquiry' ? 'tour_inquiry' : 'contact',
            'title' => $this->contact->type === 'tour_inquiry'
                ? 'Yêu cầu tư vấn tour mới'
                : 'Liên hệ mới từ website',
            'time'  => $this->contact->created_at?->diffForHumans() ?? now()->diffForHumans(),
            'contacts_new' => $this->counts['contacts_new'] ?? 0,
            'reviews_pending' => $this->counts['reviews_pending'] ?? 0,
        ];
    }
}
