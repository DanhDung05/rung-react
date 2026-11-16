<?php

namespace Tests\Feature;

use App\Events\ContactStatusChanged;
use App\Events\ReviewModerated;
use App\Events\TourReviewsChanged;
use App\Models\Contact;
use App\Models\Review;
use App\Models\Tour;
use App\Models\User;
use App\Services\AdminEventBroadcaster;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminRealtimeTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    private function contact(string $status = 'new'): Contact
    {
        return Contact::create([
            'name'    => 'Khách A',
            'email'   => 'a@example.com',
            'phone'   => '0912345678',
            'message' => 'Nội dung riêng tư',
            'status'  => $status,
            'type'    => 'general',
        ]);
    }

    private function review(): Review
    {
        $tour = Tour::create(['name' => 'Tour A', 'slug' => 'tour-a', 'status' => 'published', 'published_at' => now()]);

        return Review::create([
            'tour_id'      => $tour->id,
            'author_name'  => 'Khách B',
            'author_email' => 'b@example.com',
            'rating'       => 5,
            'comment'      => 'Nội dung đánh giá riêng tư',
            'status'       => 'pending',
        ]);
    }

    public function test_contact_status_event_is_private_and_has_no_pii(): void
    {
        $event = new ContactStatusChanged($this->contact(), 'replied', ['contacts_new' => 2, 'reviews_pending' => 3]);
        $payload = $event->broadcastWith();

        $this->assertInstanceOf(PrivateChannel::class, $event->broadcastOn()[0]);
        $this->assertSame(['id', 'type', 'title', 'status', 'time', 'contacts_new', 'reviews_pending'], array_keys($payload));
        $this->assertSame(2, $payload['contacts_new']);
        $this->assertSame(3, $payload['reviews_pending']);
        $this->assertStringNotContainsString('a@example.com', json_encode($payload));
        $this->assertStringNotContainsString('0912345678', json_encode($payload));
    }

    public function test_review_moderated_event_is_private_and_has_no_content(): void
    {
        $event = new ReviewModerated($this->review(), 'approved', ['contacts_new' => 0, 'reviews_pending' => 1]);
        $payload = $event->broadcastWith();

        $this->assertInstanceOf(PrivateChannel::class, $event->broadcastOn()[0]);
        $this->assertSame('review_moderated', $payload['type']);
        $this->assertSame('approved', $payload['status']);
        $this->assertStringNotContainsString('Nội dung đánh giá riêng tư', json_encode($payload));
        $this->assertStringNotContainsString('b@example.com', json_encode($payload));
    }

    public function test_opening_contact_marks_read_and_broadcasts_status(): void
    {
        $this->mock(AdminEventBroadcaster::class, function ($mock) {
            $mock->shouldReceive('contactStatus')->once()->withArgs(fn ($contact, $status) => $status === 'read');
        });

        $contact = $this->contact('new');

        $this->actingAs($this->admin())->get(route('admin.contacts.show', $contact))->assertOk();

        $this->assertSame('read', $contact->fresh()->status);
    }

    public function test_review_moderation_actions_broadcast_statuses(): void
    {
        $this->mock(AdminEventBroadcaster::class, function ($mock) {
            $mock->shouldReceive('reviewModerated')->once()->withArgs(fn ($review, $status) => $status === 'approved');
            $mock->shouldReceive('reviewModerated')->once()->withArgs(fn ($review, $status) => $status === 'rejected');
            $mock->shouldReceive('reviewModerated')->once()->withArgs(fn ($review, $status) => $status === 'deleted');
            $mock->shouldReceive('reviewModerated')->once()->withArgs(fn ($review, $status) => $status === 'restored');
            $mock->shouldReceive('tourReviewsChanged')->times(4);
        });

        $admin = $this->admin();
        $review = $this->review();

        $this->actingAs($admin)->patch(route('admin.reviews.approve', $review));
        $this->actingAs($admin)->patch(route('admin.reviews.reject', $review));
        $this->actingAs($admin)->delete(route('admin.reviews.destroy', $review));
        $this->actingAs($admin)->patch(route('admin.reviews.restore', $review->id));
    }

    public function test_broken_broadcast_does_not_break_status_update(): void
    {
        config(['broadcasting.default' => 'driver-khong-ton-tai']);

        $contact = $this->contact('new');

        $this->actingAs($this->admin())
            ->patch(route('admin.contacts.update-status', $contact), ['status' => 'replied'])
            ->assertRedirect();

        $this->assertSame('replied', $contact->fresh()->status);
    }

    public function test_admin_events_private_and_public_review_event_has_no_pii(): void
    {
        $review = $this->review();
        $publicEvent = new TourReviewsChanged($review->tour_id, 'approved', $review);
        $this->assertInstanceOf(Channel::class, $publicEvent->broadcastOn()[0]);

        foreach (File::files(app_path('Events')) as $file) {
            if ($file->getFilename() === 'TourReviewsChanged.php') {
                continue;
            }

            $content = File::get($file->getPathname());
            $this->assertStringNotContainsString('new Channel(', $content, $file->getFilename());
            $this->assertStringContainsString('PrivateChannel', $content, $file->getFilename());
        }
    }

    public function test_public_review_event_payload_has_no_email(): void
    {
        $review = $this->review();
        $review->update(['status' => 'approved']);

        $payload = (new TourReviewsChanged($review->tour_id, 'approved', $review))->broadcastWith();

        $this->assertSame('approved', $payload['action']);
        $this->assertSame('Khách B', $payload['review']['author_name']);
        $this->assertStringNotContainsString('b@example.com', json_encode($payload));
    }

    public function test_admin_badges_not_shared_for_guests(): void
    {
        $this->get('/')->assertOk()->assertInertia(fn ($page) => $page->where('admin_badges', null));
    }

    public function test_admin_badges_counts_for_admin(): void
    {
        Cache::flush();
        $this->contact('new');
        $this->contact('read');
        $this->review();

        $this->actingAs($this->admin())
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('admin_badges.contacts_new', 1)
                ->where('admin_badges.reviews_pending', 1));
    }
}
