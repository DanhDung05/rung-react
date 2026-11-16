<?php

namespace App\Events;

use App\Models\Contact;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewContactNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
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
        ];
    }
}
