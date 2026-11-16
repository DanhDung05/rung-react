<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ThrottleResponseTest extends TestCase
{
    use RefreshDatabase;

    private function payload(int $index = 0): array
    {
        return [
            'name'    => 'Khách ' . $index,
            'email'   => "throttle{$index}@example.com",
            'message' => 'Nội dung liên hệ đủ dài để vượt qua validation.',
        ];
    }

    public function test_inertia_form_gets_redirect_with_vietnamese_error(): void
    {
        Mail::fake();

        for ($i = 0; $i < 5; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => '10.30.40.50'])->post('/lien-he', $this->payload($i));
        }

        $response = $this->withServerVariables(['REMOTE_ADDR' => '10.30.40.50'])
            ->withHeaders(['X-Inertia' => 'true'])
            ->from('/lien-he')
            ->post('/lien-he', $this->payload(9));

        $response->assertRedirect('/lien-he');
        $response->assertSessionHas('error', fn ($message) => str_contains($message, 'Bạn thao tác quá nhanh'));
    }

    public function test_json_request_still_receives_429(): void
    {
        Mail::fake();

        for ($i = 0; $i < 5; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => '10.30.40.60'])->post('/lien-he', $this->payload($i));
        }

        $this->withServerVariables(['REMOTE_ADDR' => '10.30.40.60'])
            ->postJson('/lien-he', $this->payload(9))
            ->assertStatus(429);
    }
}
