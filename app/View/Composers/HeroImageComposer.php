<?php

namespace App\View\Composers;

use App\Models\Setting;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HeroImageComposer
{
    /**
     * Map view name → setting key.
     */
    protected array $viewSettingMap = [
        'blog.index'         => 'hero_image_blog',
        'tour.index'         => 'hero_image_tour',
        'destination.index'  => 'hero_image_destination',
        'pages.about'        => 'hero_image_about',
        'pages.contact'      => 'hero_image_contact',
        'pages.faq'          => 'hero_image_faq',
    ];

    /**
     * Chia sẻ ảnh hero cho các view index/pages.
     * Ưu tiên setting riêng; fallback về ảnh đầu tiên của slider.
     */
    public function compose(View $view): void
    {
        $viewName = $view->getName();
        $settingKey = $this->viewSettingMap[$viewName] ?? null;

        if (!$settingKey) {
            return;
        }

        // 1. Lấy từ setting
        $path = Setting::get($settingKey);

        if ($path) {
            $view->with('heroImage', Storage::disk('public')->url($path));
            return;
        }

        // 2. Fallback: ảnh đầu tiên của slider
        $slider = Slider::active()
            ->ordered()
            ->first();

        $view->with('heroImage', $slider?->image_url);
    }
}