<?php

namespace Tests\Feature;

use App\Models\Review;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestReviewTest extends TestCase
{
    use RefreshDatabase;

    private function tour(): Tour
    {
        return Tour::create(['name' => 'Tour Sapa', 'slug' => 'tour-sapa', 'status' => 'published', 'published_at' => now()]);
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    public function test_guest_can_submit_review_pending(): void
    {
        $tour = $this->tour();

        $this->post(route('tours.review.store', $tour->slug), [
            'rating'       => 5,
            'comment'      => 'Hành trình rất tuyệt vời và đáng nhớ.',
            'author_name'  => 'Khách Vãng Lai',
            'author_email' => 'guest@example.com',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'tour_id'      => $tour->id,
            'user_id'      => null,
            'author_name'  => 'Khách Vãng Lai',
            'author_email' => 'guest@example.com',
            'status'       => 'pending',
        ]);
    }

    public function test_duplicate_guest_email_for_same_tour_rejected(): void
    {
        $tour = $this->tour();
        $payload = [
            'rating'       => 4,
            'comment'      => 'Trải nghiệm lần đầu rất thú vị.',
            'author_name'  => 'Khách A',
            'author_email' => 'same@example.com',
        ];

        $this->post(route('tours.review.store', $tour->slug), $payload);
        $this->post(route('tours.review.store', $tour->slug), $payload)->assertSessionHas('error');

        $this->assertSame(1, Review::where('tour_id', $tour->id)->count());
    }

    public function test_public_tour_payload_does_not_expose_reviewer_email(): void
    {
        $tour = $this->tour();
        $review = Review::create([
            'tour_id'      => $tour->id,
            'author_name'  => 'Khách Ẩn',
            'author_email' => 'secret@example.com',
            'rating'       => 5,
            'comment'      => 'Tuyệt vời, sẽ quay lại lần nữa.',
            'status'       => Review::STATUS_APPROVED,
        ]);

        $response = $this->get(route('tours.show', $tour->slug))->assertOk();
        $response->assertDontSee('secret@example.com');

        $reviews = $response->viewData('page')['props']['tour']['reviews'];
        $this->assertSame('Khách Ẩn', $reviews[0]['author_name']);
        $this->assertArrayNotHasKey('author_email', $reviews[0]);
    }

    public function test_only_approved_reviews_are_public(): void
    {
        $tour = $this->tour();
        $review = Review::create([
            'tour_id'      => $tour->id,
            'author_name'  => 'Khách Chờ',
            'author_email' => 'pending@example.com',
            'rating'       => 4,
            'comment'      => 'Đang chờ duyệt đánh giá này.',
            'status'       => Review::STATUS_PENDING,
        ]);

        $response = $this->get(route('tours.show', $tour->slug))->assertOk();
        $this->assertCount(0, $response->viewData('page')['props']['tour']['reviews']);

        $this->actingAs($this->admin())->patch(route('admin.reviews.approve', $review));

        $response = $this->get(route('tours.show', $tour->slug))->assertOk();
        $this->assertCount(1, $response->viewData('page')['props']['tour']['reviews']);
    }

    public function test_logged_in_user_review_uses_account_identity(): void
    {
        $tour = $this->tour();
        $user = User::factory()->create(['name' => 'Người Dùng', 'email' => 'member@example.com', 'role' => 'user', 'is_active' => true]);

        $this->actingAs($user)->post(route('tours.review.store', $tour->slug), [
            'rating'  => 5,
            'comment' => 'Đánh giá từ tài khoản đã đăng nhập.',
        ]);

        $this->assertDatabaseHas('reviews', [
            'tour_id'     => $tour->id,
            'user_id'     => $user->id,
            'author_name' => 'Người Dùng',
        ]);
    }

    public function test_honeypot_does_not_create_review(): void
    {
        $tour = $this->tour();

        $this->post(route('tours.review.store', $tour->slug), [
            'rating'       => 5,
            'comment'      => 'Đây là bình luận spam từ bot.',
            'author_name'  => 'Bot',
            'author_email' => 'bot@example.com',
            'website'      => 'https://spam.example.com',
        ])->assertSessionHas('success');

        $this->assertDatabaseCount('reviews', 0);
    }
}
