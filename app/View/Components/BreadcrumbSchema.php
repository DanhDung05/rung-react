<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class BreadcrumbSchema extends Component
{
    public array $items;

    public function __construct(array $items = [])
    {
        $this->items = array_values(array_filter($items));
    }

    public function render(): View
    {
        return view('components.breadcrumb-schema');
    }
}