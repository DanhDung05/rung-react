<?php

namespace App\Http\Controllers;

use App\Helpers\CacheHelper;
use Barryvdh\Debugbar\Facades\Debugbar;
use App\Models\GallerySlide;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class GalleryCarouselController extends Controller
{
    /**
     * Trang carousel độc lập (không qua Inertia, không layout app).
     */
    public function show()
    {
        // Trang iframe độc lập: không để Debugbar chèn thanh che controls.
        if (class_exists(Debugbar::class)) {
            Debugbar::disable();
        }

        $slides = Cache::remember(CacheHelper::GALLERY_CAROUSEL_KEY, 600, function () {
            return GallerySlide::active()->ordered()->get();
        });

        if ($slides->isEmpty()) {
            return response(
                '<!doctype html><html lang="vi"><head><meta charset="utf-8"><meta name="robots" content="noindex"><title>Thư viện ảnh</title></head><body><p>Chưa có ảnh trong thư viện.</p></body></html>',
                200
            )->header('Content-Type', 'text/html; charset=UTF-8');
        }

        $works = [];
        $stories = [];
        $sizes = [];

        foreach ($slides as $slide) {
            $slideUrl = Storage::disk('public')->url($slide->slide_path);
            $fullUrl = Storage::disk('public')->url($slide->full_path);

            $works[] = [
                $slide->title,
                $slide->category,
                $slideUrl,
                (string) ($slide->placeholder ?? ''),
                $fullUrl,
            ];

            $stories[$fullUrl] = [
                'accent'      => $slide->accent,
                'description' => $slide->description,
            ];

            $sizes[$fullUrl] = [
                (int) $slide->full_width,
                (int) $slide->full_height,
            ];
        }

        $data = [
            'works'              => $works,
            'stories'            => (object) $stories,
            'originalImageSizes' => (object) $sizes,
        ];

        return response()
            ->view('gallery-carousel', compact('data'))
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }
}
