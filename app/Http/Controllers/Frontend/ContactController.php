<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Concerns\HandlesHoneypot;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\StoreContactRequest;
use App\Models\Contact;
use App\Services\ContactNotifier;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class ContactController extends Controller
{
    use HandlesHoneypot;

    public function show()
    {
        return Inertia::render('Pages/Contact');
    }

    public function store(StoreContactRequest $request, ContactNotifier $notifier): RedirectResponse
    {
        if ($this->isHoneypot($request)) {
            return redirect()
                ->route('contact')
                ->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.');
        }

        $data = $request->validated();

        $contact = Contact::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'] ?? null,
            'subject'    => $data['subject'] ?? null,
            'message'    => $data['message'],
            'status'     => Contact::STATUS_NEW,
            'type'       => Contact::TYPE_GENERAL,
            'ip_address' => $request->ip(),
            'user_agent' => substr($request->userAgent() ?? '', 0, 500),
        ]);

        $notifier->notify($contact);

        return redirect()
            ->route('contact')
            ->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.');
    }
}
