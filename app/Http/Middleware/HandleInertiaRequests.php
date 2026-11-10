<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Services\NavigationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * Root view của Inertia — KHÔNG được xóa.
     */
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Dữ liệu chia sẻ cho MỌI trang React.
     *
     * Nguyên tắc:
     *  - Trả mảng thuần, không trả Eloquent model (tránh lộ field nhạy cảm).
     *  - Bọc try/catch: nếu DB/cache lỗi thì trang vẫn render với giá trị mặc định.
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => fn () => $this->currentUser($request),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
                'info'    => fn () => $request->session()->get('info'),
            ],
            'settings'   => fn () => $this->settings(),
            'theme'      => fn () => $this->theme(),
            'navigation' => fn () => $this->navigation(),
            'app'        => [
                'name' => config('app.name'),
                'url'  => rtrim(config('app.url'), '/'),
            ],
        ]);
    }

    /**
     * Thông tin user tối thiểu — không trả toàn bộ model.
     *
     * @return array<string, mixed>|null
     */
    protected function currentUser(Request $request): ?array
    {
        $user = $request->user();

        if (! $user) {
            return null;
        }

        return [
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'role'       => $user->role,
            'is_admin'   => $user->isAdmin(),
            'is_active'  => (bool) $user->is_active,
            'avatar_url' => $this->publicUrl($user->avatar),
        ];
    }

    /**
     * Cấu hình website (settings) cần cho giao diện.
     *
     * @return array<string, mixed>
     */
    protected function settings(): array
    {
        $defaults = [
            'site_name'        => config('app.name', 'Rung Adventure'),
            'site_description' => '',
            'logo_url'         => null,
            'favicon_url'      => null,
            'contact_email'    => null,
            'contact_phone'    => null,
            'contact_address'  => null,
            'social'           => [],
            'seo_title'        => config('app.name', 'Rung Adventure'),
            'seo_description'  => '',
            'seo_keywords'     => null,
        ];

        try {
            return [
                'site_name'        => Setting::get('site_name', $defaults['site_name']),
                'site_description' => Setting::get('site_description', ''),
                'logo_url'         => $this->publicUrl(Setting::get('site_logo')),
                'favicon_url'      => $this->publicUrl(Setting::get('site_favicon')),
                'contact_email'    => Setting::get('contact_email'),
                'contact_phone'    => Setting::get('contact_phone'),
                'contact_address'  => Setting::get('contact_address'),
                'social'           => [
                    'facebook'  => Setting::get('social_facebook'),
                    'instagram' => Setting::get('social_instagram'),
                    'youtube'   => Setting::get('social_youtube'),
                    'tiktok'    => Setting::get('social_tiktok'),
                ],
                'seo_title'        => Setting::get('seo_default_title', $defaults['site_name']),
                'seo_description'  => Setting::get('seo_default_description', ''),
                'seo_keywords'     => Setting::get('seo_keywords'),
            ];
        } catch (\Throwable $e) {
            report($e);

            return $defaults;
        }
    }

    /**
     * Màu sắc / font heading do admin cấu hình.
     *
     * @return array<string, mixed>
     */
    protected function theme(): array
    {
        $defaults = [
            'primary'   => '#307a55',
            'secondary' => '#f97316',
            'headings'  => [],
        ];

        try {
            $headings = [];

            foreach (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'] as $tag) {
                $headings[$tag] = [
                    'color' => Setting::get("theme_{$tag}_color", '#0f172a'),
                    'font'  => Setting::get("theme_{$tag}_font", 'Plus Jakarta Sans'),
                ];
            }

            return [
                'primary'   => Setting::get('theme_primary_color', $defaults['primary']),
                'secondary' => Setting::get('theme_secondary_color', $defaults['secondary']),
                'headings'  => $headings,
            ];
        } catch (\Throwable $e) {
            report($e);

            return $defaults;
        }
    }

    /**
     * Menu động (main + footer).
     *
     * @return array<string, array<int, array<string, mixed>>>
     */
    protected function navigation(): array
    {
        $service = app(NavigationService::class);

        return [
            'main'   => $service->tree('main'),
            'footer' => $service->tree('footer'),
        ];
    }

    /**
     * Chuyển path tương đối trên disk public thành URL đầy đủ.
     */
    protected function publicUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}
