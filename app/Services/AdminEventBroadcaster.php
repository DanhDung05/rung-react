<?php

namespace App\Services;

use App\Events\ContactStatusChanged;
use App\Events\NewContactNotification;
use App\Events\NewReviewNotification;
use App\Events\ReviewModerated;
use App\Models\Contact;
use App\Models\Review;
use App\Support\AdminBadges;
use Illuminate\Support\Facades\Log;

/**
 * Phát sự kiện realtime Admin; lỗi broadcast không làm hỏng thao tác chính.
 */
class AdminEventBroadcaster
{
    public function newContact(Contact $contact): void
    {
        AdminBadges::flush();
        $this->safe(new NewContactNotification($contact, AdminBadges::counts()));
    }

    public function newReview(Review $review): void
    {
        AdminBadges::flush();
        $this->safe(new NewReviewNotification($review, AdminBadges::counts()));
    }

    public function contactStatus(Contact $contact, string $status): void
    {
        AdminBadges::flush();
        $this->safe(new ContactStatusChanged($contact, $status, AdminBadges::counts()));
    }

    public function reviewModerated(Review $review, string $status): void
    {
        AdminBadges::flush();
        $this->safe(new ReviewModerated($review, $status, AdminBadges::counts()));
    }

    private function safe(object $event): void
    {
        try {
            broadcast($event)->toOthers();
        } catch (\Throwable $e) {
            Log::warning('Broadcast admin thất bại (bỏ qua)', [
                'event' => $event::class,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
