<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Tinymce extends Component
{
    public string $name;
    public ?string $value;
    public ?string $label;
    public int $height;
    public ?string $hint;

    public function __construct(
        string $name,
        ?string $value = null,
        ?string $label = null,
        int $height = 400,
        ?string $hint = null
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->label = $label;
        $this->height = $height;
        $this->hint = $hint;
    }

    public function render(): View
    {
        return view('components.tinymce');
    }
}