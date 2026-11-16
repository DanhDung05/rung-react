<?php

namespace App\Services;

use App\Events\NewContactNotification as NewContactBroadcast;
use App\Mail\NewContactNotification as NewContactMail;
use App\Models\Contact;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Gửi thông báo liên hệ/tư vấn cho admin: email queue + broadcast realtime.
 * Mọi lỗi đều được bắt để không làm hỏng form của khách.
 */
class ContactNotifier
{
    public function notify(Contact $contact): void
    {
        $this->sendMail($contact);
        $this->broadcast($contact);
    }

    protected function sendMail(Contact $contact): void
    {
        $rawEmails = Setting::get('contact_notification_emails');

        if (empty($rawEmails)) {
            $rawEmails = Setting::get('contact_email', config('mail.from.address'));
        }

        $adminEmails = array_filter(array_map('trim', explode(',', (string) $rawEmails)));

        if (empty($adminEmails)) {
            Log::warning('Không có email admin để gửi thông báo contact', ['contact_id' => $contact->id]);

            return;
        }

        try {
            Mail::to($adminEmails)->queue(new NewContactMail($contact));
        } catch (\Throwable $e) {
            Log::error('Đẩy email thông báo contact thất bại', [
                'contact_id' => $contact->id,
                'error'      => $e->getMessage(),
            ]);
        }
    }

    protected function broadcast(Contact $contact): void
    {
        try {
            event(new NewContactBroadcast($contact));
        } catch (\Throwable $e) {
            Log::warning('Broadcast contact thất bại (bỏ qua)', [
                'contact_id' => $contact->id,
                'error'      => $e->getMessage(),
            ]);
        }
    }
}
