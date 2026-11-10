<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Breadcrumb extends Component
{
    public array $items;
    public string $variant; // 'default' hoặc 'light' (dùng cho hero tối)

    public function __construct(array $items = [], string $variant = 'default')
    {
        $hasHome = !empty($items) && (($items[0]['url'] ?? '') === url('/') || ($items[0]['label'] ?? '') === 'Trang chủ');

        if (!$hasHome) {
            array_unshift($items, [
                'label' => 'Trang chủ',
                'url'   => url('/'),
            ]);
        }

        $this->items = array_values(array_filter($items, function ($item) {
            return !empty($item['label']);
        }));
        $this->variant = $variant;
    }

    public function render(): View
    {
        return view('components.breadcrumb');
    }
}