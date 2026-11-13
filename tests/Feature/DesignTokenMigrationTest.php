<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DesignTokenMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_dry_run_writes_nothing(): void
    {
        Setting::set('bg_primary', '#111111', 'appearance');

        $this->artisan('tokens:migrate', ['--dry-run' => true])->assertSuccessful();

        $this->assertNull(Setting::get('palette_cream'));
    }

    public function test_migrates_only_empty_targets(): void
    {
        Setting::set('bg_primary', '#111111', 'appearance');
        Setting::set('bg_alternate', '#222222', 'appearance');
        Setting::set('theme_h1_color', '#333333', 'theme');
        Setting::set('palette_cream', '#abcdef', 'palette');

        $this->artisan('tokens:migrate')->assertSuccessful();

        $this->assertSame('#abcdef', Setting::get('palette_cream'));
        $this->assertSame('#222222', Setting::get('palette_white'));
        $this->assertSame('#333333', Setting::get('typo_h1_color'));

        // Idempotent: chạy lại không đổi
        $this->artisan('tokens:migrate')->assertSuccessful();
        $this->assertSame('#abcdef', Setting::get('palette_cream'));
        $this->assertSame('#222222', Setting::get('palette_white'));

        // Legacy giữ nguyên
        $this->assertSame('#111111', Setting::get('bg_primary'));
    }
}
