<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Concerns\HandlesHoneypot;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\StoreTourInquiryRequest;
use App\Models\Contact;
use App\Models\Tour;
use App\Services\ContactNotifier;
use Illuminate\Http\RedirectResponse;

class TourInquiryController extends Controller
{
    use HandlesHoneypot;

    public function store(StoreTourInquiryRequest $request, string $slug, ContactNotifier $notifier): RedirectResponse
    {
        $tour = Tour::published()->where('slug', $slug)->firstOrFail();

        if ($this->isHoneypot($request)) {
            return back()->with('success', 'Cảm ơn bạn! Chúng tôi sẽ liên hệ tư vấn trong thời gian sớm nhất.');
        }

        $data = $request->validated();

        $contact = Contact::create([
            'tour_id'        => $tour->id,
            'name'           => $data['name'],
            'email'          => $data['email'],
            'phone'          => $data['phone'],
            'subject'        => 'Yêu cầu tư vấn: ' . $tour->name,
            'message'        => $data['message'] ?? '',
            'status'         => Contact::STATUS_NEW,
            'type'           => Contact::TYPE_TOUR_INQUIRY,
            'people_count'   => $data['people_count'],
            'preferred_date' => $data['preferred_date'] ?? null,
            'ip_address'     => $request->ip(),
            'user_agent'     => substr($request->userAgent() ?? '', 0, 500),
        ]);

        $notifier->notify($contact);

        return back()->with('success', 'Cảm ơn bạn! Chúng tôi sẽ liên hệ tư vấn trong thời gian sớm nhất.');
    }
}
