<?php

namespace Tests\Feature;

use App\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeGalleryMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_receives_curated_gallery_media(): void
    {
        Media::create([
            'collection' => 'gallery',
            'file_name'  => '01-soft-focus.webp',
            'file_path'  => 'media/gallery/the-window/01-soft-focus.webp',
            'mime_type'  => 'image/webp',
            'alt_text'   => 'Soft Focus',
            'sort_order' => 0,
        ]);

        $this->get('/')->assertOk()->assertInertia(fn ($page) => $page
            ->where('settings.home_gallery_media.0.url', '/storage/media/gallery/the-window/01-soft-focus.webp')
            ->where('settings.home_gallery_media.0.alt', 'Soft Focus'));
    }
}
