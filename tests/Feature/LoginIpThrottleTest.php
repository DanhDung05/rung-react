<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class LoginIpThrottleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('login-ip:10.40.50.70');
        RateLimiter::clear('login-ip:10.40.50.71');
    }

    public function test_twenty_one_failures_from_same_ip_are_blocked_even_with_different_emails(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => '10.40.50.70'])->post('/dang-nhap', [
                'email'    => "unknown{$i}@example.com",
                'password' => 'wrong-password',
            ])->assertSessionHasErrors('email');
        }

        $response = $this->withServerVariables(['REMOTE_ADDR' => '10.40.50.70'])->post('/dang-nhap', [
            'email'    => 'another@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString('quá nhiều lần', $response->getSession()->get('errors')->first('email'));
    }

    public function test_other_ip_is_not_affected(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $this->withServerVariables(['REMOTE_ADDR' => '10.40.50.70'])->post('/dang-nhap', [
                'email'    => "unknown{$i}@example.com",
                'password' => 'wrong-password',
            ]);
        }

        $this->withServerVariables(['REMOTE_ADDR' => '10.40.50.71'])->post('/dang-nhap', [
            'email'    => 'someone@example.com',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors(['email' => 'Email hoặc mật khẩu không đúng.']);
    }
}
