<?php

namespace App\Events;

use App\Models\Contact;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
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
        return [new Channel('admin-notifications')];
    }

    public function broadcastAs(): string
    {
        return 'new-contact';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->contact->id,
            'name' => $this->contact->name,
            'email' => $this->contact->email,
            'message' => \Illuminate\Support\Str::limit($this->contact->message, 60),
            'time' => $this->contact->created_at->diffForHumans(),
        ];
    }
}