<?php

namespace App\View\Components;

use App\Models\Post;
use Illuminate\View\Component;
use Illuminate\View\View;

class PostCard extends Component
{
    public Post $post;
    public string $variant;

    /**
     * @param  Post  $post  Post model
     * @param  string  $variant  Kiểu card: 'default' | 'horizontal' | 'compact'
     */
    public function __construct(
        Post $post,
        string $variant = 'default'
    ) {
        $this->post = $post;
        $this->variant = $variant;
    }

    public function render(): View
    {
        return view('components.post-card');
    }
}