<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

/**
 * Trình chỉnh sửa trực quan các section trang chủ.
 * Chỉ dùng Settings key-value, không cần bảng mới.
 */
class HomeSectionController extends Controller
{
    protected function sections(): array
    {
        return config('home_sections', []);
    }

    protected function sectionConfig(string $section): array
    {
        $sections = $this->sections();

        abort_unless(isset($sections[$section]), 404, 'Section không tồn tại.');

        return $sections[$section];
    }

    protected function values(string $section): array
    {
        $values = [];

        foreach ($this->sectionConfig($section)['fields'] as $key => $field) {
            $values[$key] = Setting::get("home_{$section}_{$key}", $field['default'] ?? null);
        }

        return $values;
    }

    public function index(?string $section = 'gallery')
    {
        $this->authorize('update', Setting::class);

        $section = $section ?: 'gallery';
        $config  = $this->sectionConfig($section);        return Inertia::render('Admin/HomeSections/Index', [
            'section' => $section,
            'config'  => $config,
            'values'  => $this->values($section),
            'preview' => [
                'label'   => 'Thư viện',
                'title'   => 'Những khoảnh khắc',
                'accent'  => 'đường rừng',
            ],
        ]);
    }

    public function update(Request $request, string $section)
    {
        $this->authorize('update', Setting::class);

        $config = $this->sectionConfig($section);
        $rules  = [];
        $data   = [];

        foreach ($config['fields'] as $key => $field) {
            $input = $request->input($key);

            switch ($field['type']) {
                case 'boolean':
                    $input = $request->boolean($key);
                    $data[$key] = $input ? '1' : '0';
                    break;

                case 'color':
                    $input = (string) $input;
                    $rules[$key] = ['nullable', 'regex:/^#?[0-9A-Fa-f]{6}$/'];
                    $data[$key] = $input && ! str_starts_with($input, '#') ? '#' . $input : $input;
                    break;

                case 'select':
                    $rules[$key] = ['nullable', 'in:' . implode(',', array_keys($field['options'] ?? []))];
                    $data[$key] = $input;
                    break;

                default:
                    $rules[$key] = ['nullable', 'string', 'max:500'];
                    $data[$key] = $input;
            }
        }

        Validator::make($request->all(), $rules, [
            '*.regex' => 'Màu phải có dạng #RRGGBB.',
            '*.in'    => 'Giá trị không hợp lệ.',
        ])->validate();

        foreach ($data as $key => $value) {
            Setting::set(
                "home_{$section}_{$key}",
                $value,
                'home',
                $config['fields'][$key]['type'] === 'boolean' ? 'boolean' : 'string'
            );
        }

        return back()->with('success', 'Đã lưu section "' . $config['label'] . '".');
    }

    public function reset(string $section)
    {
        $this->authorize('update', Setting::class);

        $config = $this->sectionConfig($section);



        foreach ($config['fields'] as $key => $field) {
            $default = $field['default'] ?? null;
            Setting::set(
                "home_{$section}_{$key}",
                is_bool($default) ? ($default ? '1' : '0') : (string) $default,
                'home',
                $field['type'] === 'boolean' ? 'boolean' : 'string'
            );
        }

        return back()->with('success', 'Đã khôi phục mặc định cho "' . $config['label'] . '".');
    }
}
