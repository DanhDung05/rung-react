<?php

namespace Tests\Feature\Admin;

use App\Services\ChangelogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ChangelogServiceTest extends TestCase
{
    use RefreshDatabase;

    private string $path;

    protected function setUp(): void
    {
        parent::setUp();
        $this->path = storage_path('framework/testing/changelog-' . uniqid() . '.md');
        @mkdir(dirname($this->path), 0777, true);
    }

    protected function tearDown(): void
    {
        @unlink($this->path);
        parent::tearDown();
    }

    public function test_parses_multiple_versions_and_groups(): void
    {
        file_put_contents($this->path, <<<'MD'
# Changelog

## [2.3.1] - 2026-11-16
### Added
- Tính năng A
- Tính năng B
### Fixed
- Sửa lỗi C

## [2.3.0] - 2026-11-15
### Changed
- Thay đổi D
MD);

        $entries = (new ChangelogService($this->path))->entries();

        $this->assertCount(2, $entries);
        $this->assertSame('2.3.1', $entries[0]['version']);
        $this->assertSame('v2.3.1', $entries[0]['label']);
        $this->assertSame('2026-11-16', $entries[0]['date']);
        $this->assertSame(['Tính năng A', 'Tính năng B'], $entries[0]['groups'][0]['items']);
        $this->assertSame('Thêm', $entries[0]['groups'][0]['label']);
        $this->assertSame('Sửa lỗi', $entries[0]['groups'][1]['label']);
        $this->assertSame('2.3.0', $entries[1]['version']);
    }

    public function test_supports_current_changelog_date_heading_format(): void
    {
        $entries = (new ChangelogService())->entries();

        $this->assertNotEmpty($entries);
        $this->assertSame('2.6.0', $entries[0]['version']);
        $this->assertSame('2.6.0', trim(file_get_contents(base_path('VERSION'))));
    }

    public function test_missing_file_returns_empty_and_logs_warning(): void
    {
        Log::spy();

        $entries = (new ChangelogService($this->path . '.missing'))->entries();

        $this->assertSame([], $entries);
        Log::shouldHaveReceived('warning')->once();
    }

    public function test_changed_file_invalidates_cache_by_mtime(): void
    {
        file_put_contents($this->path, "## [1.0.0] - 2026-01-01\n### Added\n- Cũ\n");
        $service = new ChangelogService($this->path);
        $this->assertSame('1.0.0', $service->entries()[0]['version']);

        file_put_contents($this->path, "## [1.1.0] - 2026-01-02\n### Fixed\n- Mới\n");
        touch($this->path, time() + 5);

        $this->assertSame('1.1.0', $service->entries()[0]['version']);
    }
}
