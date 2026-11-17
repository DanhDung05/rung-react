<?php

namespace Tests\Feature;

use App\Helpers\CacheHelper;
use App\Models\GallerySlide;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryCarouselTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    private function slide(array $overrides = []): GallerySlide
    {
        static $sequence = 0;
        $sequence++;

        return GallerySlide::create(array_merge([
            'title'        => 'Slide ' . $sequence,
            'category'     => 'Vùng miền / Loại hình',
            'accent'       => 'Accent ' . $sequence,
            'description'  => 'Description ' . $sequence,
            'slide_path'   => 'media/gallery/the-window/0' . $sequence . '-slide.webp',
            'full_path'    => 'media/gallery/the-window/full/0' . $sequence . '-full.jpg',
            'placeholder'  => 'data:image/webp;base64,AAAA',
            'full_width'   => 1800,
            'full_height'  => 2700,
            'sort_order'   => $sequence,
            'is_active'    => true,
        ], $overrides));
    }

    private function payload(string $html): array
    {
        $this->assertSame(1, preg_match('/window\.DLSlideData = (\{.*\});/s', $html, $matches));

        return json_decode($matches[1], true, 512, JSON_THROW_ON_ERROR);
    }

    public function test_route_renders_standalone_carousel_with_expected_json_shape(): void
    {
        $first = $this->slide(['sort_order' => 1, 'title' => 'First']);
        $second = $this->slide(['sort_order' => 2, 'title' => 'Second']);

        $response = $this->get(route('gallery-carousel'));

        $response->assertOk()
            ->assertHeader('Content-Type', 'text/html; charset=UTF-8')
            ->assertDontSee('data-page', false);

        $html = $response->getContent();
        $this->assertStringContainsString('window.DLSlideData =', $html);
        $this->assertStringContainsString('assets/site.js', $html);
        $this->assertStringNotContainsString('assets/gallery-data.js', $html);

        $data = $this->payload($html);

        $this->assertCount(2, $data['works']);
        $this->assertCount(5, $data['works'][0]);
        $this->assertSame(['First', 'Vùng miền / Loại hình', Storage::disk('public')->url($first->slide_path), 'data:image/webp;base64,AAAA', Storage::disk('public')->url($first->full_path)], $data['works'][0]);

        $fullUrl = Storage::disk('public')->url($second->full_path);
        $this->assertArrayHasKey($fullUrl, $data['stories']);
        $this->assertSame(['accent' => 'Accent 2', 'description' => 'Description 2'], $data['stories'][$fullUrl]);
        $this->assertSame([1800, 2700], $data['originalImageSizes'][$fullUrl]);
        $this->assertIsInt($data['originalImageSizes'][$fullUrl][0]);
        $this->assertIsInt($data['originalImageSizes'][$fullUrl][1]);
    }

    public function test_inactive_slides_are_not_included_and_order_follows_sort_order(): void
    {
        $this->slide(['sort_order' => 20, 'title' => 'Later']);
        $this->slide(['sort_order' => 10, 'title' => 'Earlier']);
        $this->slide(['sort_order' => 1, 'title' => 'Hidden', 'is_active' => false]);

        $data = $this->payload($this->get(route('gallery-carousel'))->getContent());

        $this->assertCount(2, $data['works']);
        $this->assertSame(['Earlier', 'Later'], array_column($data['works'], 0));
    }

    public function test_zero_active_slides_returns_safe_empty_page_without_site_js(): void
    {
        $response = $this->get(route('gallery-carousel'));

        $response->assertOk()
            ->assertSee('Chưa có ảnh trong thư viện.')
            ->assertDontSee('assets/site.js', false)
            ->assertDontSee('window.DLSlideData', false)
            ->assertDontSee('data-page', false);
    }

    public function test_special_characters_are_escaped_in_embedded_json(): void
    {
        $title = '<script>alert("x")</script> & <b>nháy \' đôi "';
        $slide = $this->slide(['title' => $title]);

        $response = $this->get(route('gallery-carousel'));
        $html = $response->getContent();

        $response->assertOk();
        $this->assertStringNotContainsString('<script>alert("x")</script>', $html);

        $data = $this->payload($html);
        $this->assertSame($title, $data['works'][0][0]);
    }

    public function test_saving_or_deleting_a_slide_flushes_carousel_cache(): void
    {
        $slide = $this->slide();
        Cache::put(CacheHelper::GALLERY_CAROUSEL_KEY, 'stale', 600);
        $this->assertTrue(Cache::has(CacheHelper::GALLERY_CAROUSEL_KEY));

        $slide->update(['title' => 'Updated']);
        $this->assertFalse(Cache::has(CacheHelper::GALLERY_CAROUSEL_KEY));

        Cache::put(CacheHelper::GALLERY_CAROUSEL_KEY, 'stale', 600);
        $slide->delete();
        $this->assertFalse(Cache::has(CacheHelper::GALLERY_CAROUSEL_KEY));
    }
}
