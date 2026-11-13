<?php

namespace App\Http\Controllers\Admin;


use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingRequest;
use App\Models\Setting;
use App\Services\ImageService;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /** Key giữ dữ liệu nhưng ẩn khỏi Admin (đã có cấu hình thay thế). */
    private const HIDDEN_KEYS = [
        'theme_h1_color', 'theme_h1_font', 'theme_h2_color', 'theme_h2_font',
        'theme_h3_color', 'theme_h3_font', 'theme_h4_color', 'theme_h4_font',
        'theme_h5_color', 'theme_h5_font', 'theme_h6_color', 'theme_h6_font',
    ];
    /**
     * Tổng quan các nhóm cấu hình.
     */
    public function index()
    {
        // Chỉ admin được xem cấu hình hệ thống.
        $this->authorize('viewAny', Setting::class);

        $groups = config('settings.groups', []);

        // Ẩn nhóm đã thay thế và field legacy khỏi danh sách
        $hiddenGroups = ['appearance'];
        $groups = array_diff_key($groups, array_flip($hiddenGroups));

        foreach ($groups as $key => $group) {
            $groups[$key]['fields'] = array_diff_key($group['fields'], array_flip(self::HIDDEN_KEYS));
        }

        return Inertia::render('Admin/Settings/Index', compact('groups'));
    }

    /**
     * Form cấu hình theo nhóm.
     */
    public function edit(string $group)
    {
        $this->authorize('view', Setting::class);

        $groups = config('settings.groups', []);

        if (!isset($groups[$group])) {
            abort(404, 'Nhóm cấu hình không tồn tại.');
        }

        $groupConfig = $groups[$group];

        // Lấy giá trị hiện tại từ DB
        $values = Setting::where('group', $group)
            ->pluck('value', 'key')
            ->toArray();

        // Merge với default
        $current = [];
        foreach ($groupConfig['fields'] as $key => $field) {
            $current[$key] = $values[$key] ?? ($field['default'] ?? null);
        }

        return Inertia::render('Admin/Settings/Edit', compact('group', 'groupConfig', 'current'));
    }

    /**
     * Lưu cấu hình nhóm.
     */
    public function update(UpdateSettingRequest $request, string $group)
    {
        $this->authorize('update', Setting::class);

        $groups = config('settings.groups', []);

        if (!isset($groups[$group])) {
            abort(404, 'Nhóm cấu hình không tồn tại.');
        }

        $groupConfig = $groups[$group];
        $data        = $request->validated();
        $replacedImages = [];

        foreach ($groupConfig['fields'] as $key => $field) {
            if (in_array($key, self::HIDDEN_KEYS, true)) {
                continue;
            }

            $value = $data[$key] ?? null;

            // Xử lý ảnh upload
            if ($field['type'] === 'image') {
                $file = $request->file($key);

                if ($file && ! $file->isValid()) {
                    throw ValidationException::withMessages([
                        $key => 'Khong tai duoc anh len may chu: ' . $file->getErrorMessage() . '. Kiem tra dung luong toi da (upload_max_filesize=' . ini_get('upload_max_filesize') . ').',
                    ]);
                }

                if ($request->hasFile($key)) {
                    // Xóa ảnh cũ
                    $oldPath = Setting::where('key', $key)->value('value');
                    if ($oldPath) {
                        $replacedImages[$key] = $oldPath;
                    }

                    // Upload ảnh mới
                    $value = app(ImageService::class)
                        ->upload($request->file($key), 'settings', 0);
                } else {
                    // Không upload mới → giữ giá trị cũ
                    $value = Setting::where('key', $key)->value('value');
                }
            }

            // Xử lý boolean
            if ($field['type'] === 'boolean') {
                $value = isset($data[$key]) ? '1' : '0';
            }

            // Xử lý color (đảm bảo bắt đầu bằng #)
            if ($field['type'] === 'color' && $value && !str_starts_with($value, '#')) {
                $value = '#' . $value;
            }

            // Lưu vào DB
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value'       => $value,
                    'group'       => $group,
                    'type'        => $field['type'] === 'image' ? 'string' : $field['type'],
                    'description' => $field['label'] ?? null,
                ]
            );
        }

        // Xóa cache settings
        foreach ($replacedImages as $oldImage) {
            if ($oldImage) {
                app(ImageService::class)->delete($oldImage);
            }
        }

        Setting::flushCache();

        return redirect()
            ->route('admin.settings.edit', $group)
            ->with('success', 'Đã lưu cấu hình thành công.');
    }
}