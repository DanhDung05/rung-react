<?php

namespace App\View\Components;

use App\Models\Tour;
use Illuminate\View\Component;
use Illuminate\View\View;

class TourCard extends Component
{
    public Tour $tour;
    public bool $showCategory;
    public bool $showExcerpt;

    /**
     * @param  Tour  $tour  Tour model
     * @param  bool  $showCategory  Hiển thị tên danh mục
     * @param  bool  $showExcerpt  Hiển thị mô tả ngắn
     */
    public function __construct(
        Tour $tour,
        bool $showCategory = true,
        bool $showExcerpt = true
    ) {
        $this->tour = $tour;
        $this->showCategory = $showCategory;
        $this->showExcerpt = $showExcerpt;
    }

    public function render(): View
    {
        return view('components.tour-card');
    }
}