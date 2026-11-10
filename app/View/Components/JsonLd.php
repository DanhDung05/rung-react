<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class JsonLd extends Component
{
    public array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function render(): View
    {
        return view('components.json-ld');
    }
}