<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Cache;

/**
 * Xây dựng cây menu động (tối đa 3 cấp) để chia sẻ cho Inertia.
 *
 * - Cache theo location (1 giờ) để không query mỗi request.
 * - Trả về mảng thuần (không phải Eloquent model) để serialize an toàn.
 * - Nếu DB lỗi, trả mảng rỗng để trang vẫn render được.
 */
class NavigationService
{
    public const CACHE_PREFIX = 'navigation.';

    public const CACHE_TTL = 3600;

    /**
     * Trả về cây menu theo location.
     *
     * @return array<int, array<string, mixed>>
     */
    public function tree(string $location): array
    {
        try {
            return Cache::remember(
                self::CACHE_PREFIX . $location,
                self::CACHE_TTL,
                fn () => $this->build($location)
            );
        } catch (\Throwable $e) {
            report($e);

            return [];
        }
    }

    /**
     * Xóa cache menu (gọi sau khi admin thay đổi menu).
     */
    public static function flush(): void
    {
        foreach (['main', 'footer', 'top'] as $location) {
            Cache::forget(self::CACHE_PREFIX . $location);
        }
    }

    /**
     * Query và dựng cây.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function build(string $location): array
    {
        $menu = Menu::query()
            ->where('location', $location)
            ->where('is_active', true)
            ->first();

        if (! $menu) {
            return [];
        }

        $items = MenuItem::query()
            ->where('menu_id', $menu->id)
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->with(['children' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('sort_order')
                    ->with(['children' => function ($q) {
                        $q->where('is_active', true)->orderBy('sort_order');
                    }]);
            }])
            ->get();

        return $items->map(fn (MenuItem $item) => $this->transform($item))->all();
    }

    /**
     * Chuyển 1 item (và con của nó) thành mảng.
     *
     * @return array<string, mixed>
     */
    protected function transform(MenuItem $item): array
    {
        return [
            'id'       => $item->id,
            'label'    => $item->label,
            'url'      => $item->resolved_url,
            'target'   => $item->target ?: '_self',
            'children' => $item->children
                ->map(fn (MenuItem $child) => $this->transform($child))
                ->all(),
        ];
    }
}
