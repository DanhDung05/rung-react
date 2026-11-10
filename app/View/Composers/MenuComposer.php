<?php

namespace App\View\Composers;

use App\Models\Menu;
use Illuminate\View\View;

class MenuComposer
{
    /**
     * Chia sẻ menu ra layout header và footer.
     */
    public function compose(View $view): void
    {
        $view->with('mainMenu', $this->loadMenu('main'));
        $view->with('footerMenu', $this->loadMenu('footer'));
    }

    /**
     * Load menu theo location, chỉ lấy item đang hoạt động,
     * eager load children 2 cấp (cấp 2 và cấp 3) để tránh N+1.
     */
    protected function loadMenu(string $location): ?Menu
    {
        return Menu::query()
            ->where('location', $location)
            ->where('is_active', true)
            ->with([
                'items'                   => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
                'items.children'          => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
                'items.children.children' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
            ])
            ->first();
    }
}