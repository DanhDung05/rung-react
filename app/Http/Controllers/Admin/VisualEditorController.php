<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * Visual Editor (GĐ1 — trang chủ).
 * - Draft và Published tách biệt trong Settings JSON.
 * - Publish tạo snapshot published cũ (tối đa 5) để khôi phục.
 */
class VisualEditorController extends Controller
{
    public const DRAFT = 've_draft_home';
    public const PUBLISHED = 've_pub_home';
    public const HISTORY = 've_hist_home';

    protected function config(): array
    {
        return config('visual_editor.home', []);
    }

    protected function decode($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        $data = json_decode((string) $value, true);

        return is_array($data) ? $data : [];
    }

    public function index()
    {
        $this->authorize('update', Setting::class);

        return Inertia::render('Admin/VisualEditor/Index', [
            'page'      => 'home',
            'config'    => $this->config(),
            'published' => $this->decode(Setting::get(self::PUBLISHED)),
            'draft'     => $this->decode(Setting::get(self::DRAFT)),
            'versions'  => array_values($this->decode(Setting::get(self::HISTORY))),
        ]);
    }

    public function draft(Request $request)
    {
        $this->authorize('update', Setting::class);

        Setting::set(self::DRAFT, json_encode($request->input('values', []), JSON_UNESCAPED_UNICODE), 'visual', 'json');

        return back()->with('success', 'Đã lưu bản nháp.');
    }

    public function publish(Request $request)
    {
        $this->authorize('update', Setting::class);

        $values = $request->input('values', []);
        $current = Setting::get(self::PUBLISHED);

        if ($current) {
            $history = $this->decode(Setting::get(self::HISTORY));
            array_unshift($history, ['at' => now()->toDateTimeString(), 'values' => $this->decode($current)]);
            $history = array_slice($history, 0, 5);
            Setting::set(self::HISTORY, json_encode($history, JSON_UNESCAPED_UNICODE), 'visual', 'json');
        }

        Setting::set(self::PUBLISHED, json_encode($values, JSON_UNESCAPED_UNICODE), 'visual', 'json');
        Setting::set(self::DRAFT, '{}', 'visual', 'json');

        return back()->with('success', 'Đã xuất bản thay đổi.');
    }

    public function restore(Request $request)
    {
        $this->authorize('update', Setting::class);

        $history = $this->decode(Setting::get(self::HISTORY));
        $index = (int) $request->input('index', 0);

        abort_unless(isset($history[$index]['values']), 404, 'Phiên bản không tồn tại.');

        $snapshot = $history[$index]['values'];

        $current = Setting::get(self::PUBLISHED);

        if ($current) {
            array_unshift($history, ['at' => now()->toDateTimeString(), 'values' => $this->decode($current)]);
        }

        Setting::set(self::HISTORY, json_encode(array_slice($history, 0, 5), JSON_UNESCAPED_UNICODE), 'visual', 'json');
        Setting::set(self::PUBLISHED, json_encode($snapshot, JSON_UNESCAPED_UNICODE), 'visual', 'json');

        return back()->with('success', 'Đã khôi phục phiên bản trước.');
    }
}
