<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveMenuItemsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items'              => ['nullable', 'array'],
            'items.*.label'      => ['nullable', 'string', 'max:255'],
            'items.*.url'        => ['nullable', 'string', 'max:500'],
            'items.*.route_name' => ['nullable', 'string', 'max:100'],
            'items.*.target'     => ['nullable', Rule::in(['_self', '_blank'])],
            'items.*.parent_key' => ['nullable'],
            'items.*.is_active'  => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.*.label.max' => 'Tên item không vượt quá 255 ký tự.',
            'items.*.target.in' => 'Cách mở link không hợp lệ.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $items = $this->input('items', []);

        $normalized = [];

        foreach ($items as $index => $item) {
            // Bỏ qua nếu không có label
            if (empty($item['label'])) {
                continue;
            }

            // LƯU Ý: giữ nguyên $index gốc, KHÔNG dùng []
            $normalized[$index] = [
                'label'      => $item['label'] ?? '',
                'url'        => $item['url'] ?? null,
                'route_name' => $item['route_name'] ?? null,
                'target'     => $item['target'] ?? '_self',
                'parent_key' => (isset($item['parent_key']) && $item['parent_key'] !== '' && $item['parent_key'] !== null)
                    ? (int) $item['parent_key']
                    : null,
                'is_active'  => !empty($item['is_active']),
            ];
        }

        $this->merge(['items' => $normalized]);
    }
}