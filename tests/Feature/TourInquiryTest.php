<?php

namespace Tests\Feature;

use App\Mail\NewContactNotification;
use App\Models\Contact;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TourInquiryTest extends TestCase
{
    use RefreshDatabase;

    private function tour(string $status = 'published'): Tour
    {
        return Tour::create([
            'name'   => 'Tour Hà Giang',
            'slug'   => 'tour-ha-giang',
            'status' => $status,
            'published_at' => $status === 'published' ? now() : null,
        ]);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name'           => 'Khách Tư Vấn',
            'email'          => 'lead@example.com',
            'phone'          => '0912345678',
            'people_count'   => 3,
            'preferred_date' => now()->addDays(10)->toDateString(),
            'message'        => 'Muốn đi cuối tuần, cần xe đưa đón.',
        ], $overrides);
    }

    public function test_valid_inquiry_creates_contact_and_queues_mail(): void
    {
        Mail::fake();
        $tour = $this->tour();

        $this->post(route('tours.inquiry.store', $tour->slug), $this->payload())
            ->assertRedirect()
            ->assertSessionHas('success');

        $contact = Contact::first();

        $this->assertNotNull($contact);
        $this->assertSame('tour_inquiry', $contact->type);
        $this->assertSame($tour->id, $contact->tour_id);
        $this->assertSame(3, $contact->people_count);
        $this->assertSame(now()->addDays(10)->toDateString(), $contact->preferred_date->toDateString());
        $this->assertStringContainsString('Tour Hà Giang', $contact->subject);
        Mail::assertQueued(NewContactNotification::class);
    }

    public function test_draft_tour_cannot_receive_inquiry(): void
    {
        $this->post(route('tours.inquiry.store', 'tour-ha-giang'), $this->payload())
            ->assertNotFound();
    }

    public function test_past_date_is_rejected(): void
    {
        $tour = $this->tour();

        $this->post(route('tours.inquiry.store', $tour->slug), $this->payload([
            'preferred_date' => now()->subDay()->toDateString(),
        ]))->assertSessionHasErrors('preferred_date');

        $this->assertDatabaseCount('contacts', 0);
    }

    public function test_invalid_phone_is_rejected(): void
    {
        $tour = $this->tour();

        $this->post(route('tours.inquiry.store', $tour->slug), $this->payload(['phone' => 'abc']))
            ->assertSessionHasErrors('phone');
    }

    public function test_honeypot_does_not_create_contact(): void
    {
        $tour = $this->tour();

        $this->post(route('tours.inquiry.store', $tour->slug), $this->payload(['website' => 'spam.test']))
            ->assertSessionHas('success');

        $this->assertDatabaseCount('contacts', 0);
    }

    public function test_contact_throttle_blocks_sixth_request(): void
    {
        $tour = $this->tour();

        for ($i = 0; $i < 5; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => '10.10.10.5'])
                ->post(route('tours.inquiry.store', $tour->slug), $this->payload(['email' => "lead{$i}@example.com"]));
        }

        $this->withServerVariables(['REMOTE_ADDR' => '10.10.10.5'])
            ->post(route('tours.inquiry.store', $tour->slug), $this->payload(['email' => 'lead9@example.com']))
            ->assertStatus(429);
    }

    public function test_admin_contact_page_shows_tour_inquiry_details(): void
    {
        $tour = $this->tour();
        $contact = Contact::create([
            'tour_id'        => $tour->id,
            'name'           => 'Khách A',
            'email'          => 'a@example.com',
            'phone'          => '0987654321',
            'subject'        => 'Yêu cầu tư vấn: ' . $tour->name,
            'message'        => 'Tư vấn giúp tôi',
            'status'         => 'new',
            'type'           => 'tour_inquiry',
            'people_count'   => 4,
            'preferred_date' => now()->addDays(5),
        ]);

        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->actingAs($admin)
            ->get(route('admin.contacts.show', $contact))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('contact.type', 'tour_inquiry')
                ->where('contact.tour.name', 'Tour Hà Giang')
                ->where('contact.people_count', 4));
    }
}
