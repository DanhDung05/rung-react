<?php

namespace Tests\Feature;

use App\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Tests\TestCase;

class TrustedProxyTest extends TestCase
{
    protected function tearDown(): void
    {
        Request::setTrustedProxies([], -1);
        parent::tearDown();
    }

    private function resolveIp(?string $trusted, string $forwarded = '203.0.113.9'): string
    {
        config(['app.trusted_proxies' => $trusted]);

        $request = Request::create('/', 'GET', [], [], [], [
            'REMOTE_ADDR'           => '10.0.0.1',
            'HTTP_X_FORWARDED_FOR'  => $forwarded,
        ]);

        $middleware = new TrustProxies();

        return $middleware->handle($request, fn (Request $req) => response($req->ip()))->getContent();
    }

    public function test_forwarded_ip_ignored_when_no_trusted_proxy(): void
    {
        $this->assertSame('10.0.0.1', $this->resolveIp(null));
        $this->assertSame('10.0.0.1', $this->resolveIp(''));
    }

    public function test_forwarded_ip_used_for_trusted_proxy(): void
    {
        $this->assertSame('203.0.113.9', $this->resolveIp('10.0.0.1'));
    }

    public function test_wildcard_trusts_every_proxy(): void
    {
        $this->assertSame('203.0.113.9', $this->resolveIp('*'));
    }

    public function test_cidr_list_is_supported(): void
    {
        $this->assertSame('203.0.113.9', $this->resolveIp('10.0.0.0/24'));
    }
}
