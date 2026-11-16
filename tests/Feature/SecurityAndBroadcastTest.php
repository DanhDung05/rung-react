<?php

namespace Tests\Feature;

use App\Events\NewContactNotification;
use App\Events\NewReviewNotification;
use App\Models\Contact;
use App\Models\Review;
use App\Models\Tour;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SecurityAndBroadcastTest extends TestCase
{
    use RefreshDatabase;

    private function admin(bool $active = true): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => $active]);
    }

    public function test_contact_throttle_blocks_sixth_request(): void
    {
        Mail::fake();

        for ($i = 0; $i < 5; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => '10.20.30.40'])->post('/lien-he', [
                'name'    => 'Khách ' . $i,
                'email'   => "contact{$i}@example.com",
                'message' => 'Nội dung liên hệ đủ dài để hợp lệ.',
            ]);
        }

        $this->withServerVariables(['REMOTE_ADDR' => '10.20.30.40'])->postJson('/lien-he', [
            'name'    => 'Khách cuối',
            'email'   => 'contact9@example.com',
            'message' => 'Nội dung liên hệ đủ dài để hợp lệ.',
        ])->assertStatus(429);
    }

    public function test_contact_honeypot_does_not_create_record(): void
    {
        Mail::fake();

        $this->post('/lien-he', [
            'name'    => 'Bot',
            'email'   => 'bot@example.com',
            'message' => 'Nội dung spam đủ dài cho hợp lệ.',
            'website' => 'https://spam.test',
        ])->assertSessionHas('success');

        $this->assertDatabaseCount('contacts', 0);
        Mail::assertNothingQueued();
    }

    public function test_review_throttle_blocks_fourth_request(): void
    {
        $tour = Tour::create(['name' => 'Tour Test', 'slug' => 'tour-test', 'status' => 'published']);

        for ($i = 0; $i < 3; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => '10.20.30.50'])->post(route('tours.review.store', $tour->slug), [
                'rating'       => 5,
                'comment'      => 'Đánh giá hợp lệ với nội dung đủ dài.',
                'author_name'  => 'Khách ' . $i,
                'author_email' => "review{$i}@example.com",
            ]);
        }

        $this->withServerVariables(['REMOTE_ADDR' => '10.20.30.50'])->postJson(route('tours.review.store', $tour->slug), [
            'rating'       => 5,
            'comment'      => 'Đánh giá thứ tư phải bị chặn bởi giới hạn.',
            'author_name'  => 'Khách 4',
            'author_email' => 'review4@example.com',
        ])->assertStatus(429);
    }

    public function test_login_throttled_after_five_failures(): void
    {
        $user = User::factory()->create(['email' => 'login@example.com', 'role' => 'user', 'is_active' => true]);

        for ($i = 0; $i < 5; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => '10.20.30.60'])->post('/dang-nhap', [
                'email'    => $user->email,
                'password' => 'wrong-password',
            ])->assertSessionHasErrors('email');
        }

        $this->withServerVariables(['REMOTE_ADDR' => '10.20.30.60'])->post('/dang-nhap', [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');
    }

    public function test_inactive_account_with_correct_password_gets_disabled_message(): void
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => false]);

        $this->post('/dang-nhap', [
            'email'    => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors(['email' => 'Tài khoản của bạn đã bị vô hiệu hóa. Vui lòng liên hệ quản trị viên.']);
    }

    public function test_inactive_account_with_wrong_password_does_not_reveal_status(): void
    {
        $user = User::factory()->create(['role' => 'user', 'is_active' => false]);

        $this->post('/dang-nhap', [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors(['email' => 'Email hoặc mật khẩu không đúng.']);
    }

    public function test_contact_event_uses_private_channel_and_minimal_payload(): void
    {
        $contact = Contact::create([
            'name'    => 'Khách Bí Mật',
            'email'   => 'secret-contact@example.com',
            'phone'   => '0912345678',
            'message' => 'Nội dung riêng tư',
            'status'  => 'new',
            'type'    => 'tour_inquiry',
        ]);

        $event = new NewContactNotification($contact);
        $payload = $event->broadcastWith();

        $this->assertInstanceOf(PrivateChannel::class, $event->broadcastOn()[0]);
        $this->assertSame(['id', 'type', 'title', 'time'], array_keys($payload));
        $this->assertStringNotContainsString('secret-contact@example.com', json_encode($payload));
        $this->assertStringNotContainsString('0912345678', json_encode($payload));
    }

    public function test_review_event_uses_private_channel_and_minimal_payload(): void
    {
        $tour = Tour::create(['name' => 'Tour Test', 'slug' => 'tour-test', 'status' => 'published']);
        $review = Review::create([
            'tour_id'      => $tour->id,
            'author_name'  => 'Khách Bí Mật',
            'author_email' => 'secret-review@example.com',
            'rating'       => 5,
            'comment'      => 'Nội dung đánh giá riêng tư',
            'status'       => 'pending',
        ]);

        $event = new NewReviewNotification($review);
        $payload = $event->broadcastWith();

        $this->assertInstanceOf(PrivateChannel::class, $event->broadcastOn()[0]);
        $this->assertSame(['id', 'type', 'title', 'time'], array_keys($payload));
        $this->assertStringNotContainsString('secret-review@example.com', json_encode($payload));
        $this->assertStringNotContainsString('Nội dung đánh giá riêng tư', json_encode($payload));
    }

    private function authChannel(User $user): \Illuminate\Testing\TestResponse
    {
        return $this->actingAs($user)->post('/broadcasting/auth', [
            'socket_id' => '1234.5678',
            'channel_name' => 'private-admin-notifications',
        ]);
    }

    public function test_admin_can_authorize_private_channel(): void
    {
        $this->authChannel($this->admin())->assertOk();
    }

    public function test_admin_channel_helper_enforces_role_and_active(): void
    {
        $editor = User::factory()->create(['role' => 'editor', 'is_active' => true]);
        $inactive = $this->admin(false);
        $member = User::factory()->create(['role' => 'user', 'is_active' => true]);

        $this->assertTrue(\App\Support\AdminChannel::allows($this->admin()));
        $this->assertTrue(\App\Support\AdminChannel::allows($editor));
        $this->assertFalse(\App\Support\AdminChannel::allows($inactive));
        $this->assertFalse(\App\Support\AdminChannel::allows($member));
    }
}