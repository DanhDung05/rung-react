<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class HeroBanner extends Component
{
    public ?string $image;
    public string $title;
    public array $breadcrumb;
    public string $variant;
    public string $height;
    public string $overlay;
    public string $alignment;
    public bool $showBreadcrumb;

    public function __construct(
        ?string $image = null,
        string $title = '',
        array $breadcrumb = [],
        string $variant = 'full',
        ?string $height = null,
        ?string $overlay = null,
        string $alignment = 'left',
        bool $showBreadcrumb = true
    ) {
        $this->image = $image;
        $this->title = $title;
        $this->breadcrumb = $breadcrumb;
        $this->variant = $variant;
        $this->alignment = $alignment;
        $this->showBreadcrumb = $showBreadcrumb;

        $this->height = $height ?? match ($variant) {
            'compact' => 'h-[500px] lg:h-[600px]',
            'minimal' => 'h-[400px] lg:h-[500px]',
            default   => 'h-[500px] lg:h-[600px]',
        };

        // Overlay giống trang chủ: tối nhẹ 30% (bg-black/30)
        $this->overlay = $overlay ?? 'bg-black/30';
    }

    public function render(): View
    {
        return view('components.hero-banner');
    }
}