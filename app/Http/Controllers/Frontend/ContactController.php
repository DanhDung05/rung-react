<?php

namespace App\Http\Controllers\Frontend;


use Inertia\Inertia;
use App\Events\NewContactNotification as NewContactBroadcast; // Đổi alias để tránh trùng tên với Mailable
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\StoreContactRequest;
use App\Mail\NewContactNotification; // Dùng cho gửi mail
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return Inertia::render('Pages/Contact');
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $contact = Contact::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'] ?? null,
            'subject'    => $data['subject'] ?? null,
            'message'    => $data['message'],
            'status'     => Contact::STATUS_NEW,
            'ip_address' => $request->ip(),
            'user_agent' => substr($request->userAgent() ?? '', 0, 500),
        ]);

        // 1. Gửi email qua queue
        $this->sendAdminNotification($contact);

        // 2. BẮN SỰ KIỆN REAL-TIME (ĐÂY LÀ PHẦN BỊ THIẾU)
        event(new NewContactBroadcast($contact));

        return redirect()
            ->route('contact')
            ->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.');
    }

    protected function sendAdminNotification(Contact $contact): void
    {
        $rawEmails = \App\Models\Setting::get('contact_notification_emails');
        if (empty($rawEmails)) {
            $rawEmails = \App\Models\Setting::get('contact_email', config('mail.from.address'));
        }

        $adminEmails = array_filter(
            array_map('trim', explode(',', (string) $rawEmails))
        );

        if (empty($adminEmails)) {
            Log::warning('Không có email admin để gửi thông báo contact', [
                'contact_id' => $contact->id,
            ]);
            return;
        }

        try {
            Mail::to($adminEmails)->queue(new NewContactNotification($contact));
            Log::info('Đã đẩy email thông báo contact vào queue', [
                'contact_id' => $contact->id,
                'recipients' => $adminEmails,
            ]);
        } catch (\Throwable $e) {
            Log::error('Đẩy email thông báo contact vào queue thất bại', [
                'contact_id' => $contact->id,
                'recipients' => $adminEmails,
                'error'      => $e->getMessage(),
            ]);
        }
    }
}