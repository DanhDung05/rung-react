<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewContactNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Contact $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function envelope(): Envelope
    {
        $subject = '[Rung Adventure] Liên hệ mới từ ' . $this->contact->name;
        if ($this->contact->subject) {
            $subject = '[Rung Adventure] ' . $this->contact->subject;
        }

        return new Envelope(
            subject: $subject,
            replyTo: [
                new Address($this->contact->email, $this->contact->name),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-contact',
            with: [
                'contact' => $this->contact,
                'url'     => route('admin.contacts.show', $this->contact),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}