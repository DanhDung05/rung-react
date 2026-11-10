<?php

namespace App\View\Components;

use App\Models\Destination;
use Illuminate\View\Component;
use Illuminate\View\View;

class DestinationCard extends Component
{
    public Destination $destination;
    public string $variant;

    /**
     * @param  Destination  $destination  Destination model
     * @param  string  $variant  Kiểu card: 'default' | 'compact' | 'featured'
     */
    public function __construct(
        Destination $destination,
        string $variant = 'default'
    ) {
        $this->destination = $destination;
        $this->variant = $variant;
    }

    public function render(): View
    {
        return view('components.destination-card');
    }
}