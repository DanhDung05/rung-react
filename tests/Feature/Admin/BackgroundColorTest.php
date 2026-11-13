<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackgroundColorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    public function test_palette_is_source_of_background_colors(): void
    {
        $this->actingAs($this->admin())
            ->put(route('admin.settings.update', 'palette'), [
                'palette_forest' => '#102c24',
                'palette_cream'  => '#efe7d2',
                'palette_white'  => '#fcfcfc',
                'palette_earth'  => '#a77942',
                'palette_text'   => '#1b2a22',
                'palette_muted'  => '#6b7a70',
                'palette_gold'   => '#c58b45',
                'palette_surface' => '#fafafa',
                'palette_border' => '#dddddd',
                'palette_on_dark' => '#eeeeee',
            ])->assertRedirect();

        $this->assertSame('#efe7d2', Setting::get('palette_cream'));

        $this->get('/')->assertInertia(fn ($page) => $page
            ->where('theme.palette.cream', '#efe7d2')
            ->where('theme.palette.white', '#fcfcfc')
            ->where('theme.palette.gold', '#c58b45')
            ->where('theme.palette.surface', '#fafafa')
            ->where('theme.palette.border', '#dddddd')
            ->where('theme.palette.on_dark', '#eeeeee'));
    }

    public function test_appearance_no_longer_writes_bg_keys(): void
    {
        Setting::set('bg_primary', '#111111', 'appearance');
        Setting::set('bg_alternate', '#222222', 'appearance');

        $this->actingAs($this->admin())
            ->put(route('admin.settings.update', 'appearance'), [
                'bg_primary'   => '#ff0000',
                'bg_alternate' => '#00ff00',
            ])->assertRedirect();

        $this->assertSame('#111111', Setting::get('bg_primary'));
        $this->assertSame('#222222', Setting::get('bg_alternate'));
    }

    public function test_invalid_palette_color_is_rejected(): void
    {
        $this->actingAs($this->admin())
            ->from(route('admin.settings.edit', 'palette'))
            ->put(route('admin.settings.update', 'palette'), [
                'palette_cream' => 'not-a-color',
            ])->assertRedirect(route('admin.settings.edit', 'palette'))
            ->assertSessionHasErrors('palette_cream');

        $this->assertNull(Setting::get('palette_cream'));
    }

    public function test_legacy_theme_heading_color_no_longer_overrides_typography(): void
    {
        Setting::set('theme_h1_color', '#307a55', 'theme');

        $this->get('/')->assertOk()->assertInertia(fn ($page) => $page
            ->where('theme.typography.h1.color', '')
            ->where('theme.typography.h2.color', ''));
    }

    public function test_explicit_typography_color_still_applies(): void
    {
        Setting::set('typo_h1_color', '#102c24', 'typography');

        $this->get('/')->assertOk()->assertInertia(fn ($page) => $page
            ->where('theme.typography.h1.color', '#102c24'));
    }

    public function test_boolean_setting_stays_off_after_reload(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->put(route('admin.settings.update', 'hero'), [
            'hero_overlay_enabled' => 0,
            'hero_image_blog' => null,
        ])->assertRedirect();

        $this->assertFalse((bool) Setting::get('hero_overlay_enabled'));

        $this->actingAs($admin)->get(route('admin.settings.edit', 'hero'))
            ->assertInertia(fn ($page) => $page->where('current.hero_overlay_enabled', false));
    }

    public function test_hero_overlay_can_be_disabled(): void
    {
        Setting::set('hero_overlay_enabled', '0', 'hero', 'boolean');

        $this->get('/')->assertOk()->assertInertia(fn ($page) => $page
            ->where('settings.hero_overlay_enabled', false));
    }
}
