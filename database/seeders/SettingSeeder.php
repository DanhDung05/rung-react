<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $groups = config('settings.groups', []);

        foreach ($groups as $groupKey => $group) {
            foreach ($group['fields'] as $key => $field) {
                // Tìm kể cả bản ghi đã soft delete
                $setting = Setting::withTrashed()->where('key', $key)->first();

                if ($setting) {
                    // Nếu đã soft delete → restore
                    if ($setting->trashed()) {
                        $setting->restore();
                    }

                    // Chỉ update nếu value đang null (không ghi đè giá trị admin đã đổi)
                    if (is_null($setting->value) && isset($field['default'])) {
                        $setting->update([
                            'value'       => $field['default'],
                            'group'       => $groupKey,
                            'type'        => $this->resolveType($field['type']),
                            'description' => $field['label'] ?? null,
                        ]);
                    }
                } else {
                    // Chưa có → tạo mới
                    Setting::create([
                        'key'         => $key,
                        'value'       => $field['default'] ?? null,
                        'group'       => $groupKey,
                        'type'        => $this->resolveType($field['type']),
                        'description' => $field['label'] ?? null,
                    ]);
                }
            }
        }

        $this->command->info('✓ Đã seed settings thành công!');
    }

    /**
     * Chuyển type từ config sang type hợp lệ trong DB.
     */
    protected function resolveType(string $type): string
    {
        // Các type không có trong ENUM → dùng 'string'
        $allowedTypes = ['string', 'text', 'boolean', 'number', 'json', 'color', 'image', 'select'];

        return in_array($type, $allowedTypes) ? $type : 'string';
    }
}