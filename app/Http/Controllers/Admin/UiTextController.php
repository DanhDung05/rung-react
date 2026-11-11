<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

/**
 * Quản lý nội dung hiển thị (text/label/placeholder) của các trang public.
 * Chỉ dùng Settings key-value, không đổi logic.
 */
class UiTextController extends Controller
{
    protected function pages(): array
    {
        return config('ui_texts', []);
    }

    protected function pageConfig(string $page): array
    {
        $pages = $this->pages();

        abort_unless(isset($pages[$page]), 404, 'Trang không tồn tại.');

        return $pages[$page];
    }

    protected function values(string $page): array
    {
        $values = [];

        foreach ($this->pageConfig($page)['sections'] as $section => $config) {
            foreach ($config['fields'] as $field => $meta) {
                $values[$section][$field] = Setting::get("ui_{$page}_{$section}_{$field}", $meta['default'] ?? '');
            }
        }

        return $values;
    }

    public function index(?string $page = 'tour.index')
    {
        $this->authorize('update', Setting::class);

        $page = $page ?: 'tour.index';
        $config = $this->pageConfig($page);

        return Inertia::render('Admin/UiTexts/Index', [
            'page'  => $page,
            'pages' => collect($this->pages())->map(fn ($item, $key) => ['key' => $key, 'label' => $item['label']])->values(),
            'config' => $config,
            'values' => $this->values($page),
        ]);
    }

    public function update(Request $request, string $page, string $section)
    {
        $this->authorize('update', Setting::class);

        $config = $this->pageConfig($page);
        abort_unless(isset($config['sections'][$section]), 404);

        $rules = [];
        $fields = $config['sections'][$section]['fields'];

        foreach ($fields as $field => $meta) {
            $rules[$field] = ['nullable', 'string', 'max:500'];
        }

        Validator::make($request->all(), $rules)->validate();

        foreach ($fields as $field => $meta) {
            Setting::set("ui_{$page}_{$section}_{$field}", $request->input($field), 'ui', 'string');
        }

        return back()->with('success', 'Đã lưu "' . $config['sections'][$section]['label'] . '".');
    }

    public function reset(string $page, string $section)
    {
        $this->authorize('update', Setting::class);

        $config = $this->pageConfig($page);
        abort_unless(isset($config['sections'][$section]), 404);

        foreach ($config['sections'][$section]['fields'] as $field => $meta) {
            Setting::set("ui_{$page}_{$section}_{$field}", $meta['default'] ?? '', 'ui', 'string');
        }

        return back()->with('success', 'Đã khôi phục mặc định.');
    }
}
