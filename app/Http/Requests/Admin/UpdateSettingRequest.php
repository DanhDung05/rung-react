<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $group  = $this->route('group');
        $groups = config('settings.groups', []);

        if (!isset($groups[$group])) {
            return [];
        }

        $rules = [];

        foreach ($groups[$group]['fields'] as $key => $field) {
            $rules[$key] = $this->buildRules($field, $key);
        }

        return $rules;
    }

    /**
     * Xây dựng rules theo type.
     */
    protected function buildRules(array $field, string $key): array
    {
        $required = !empty($field['required']);
        $rules    = [];

        switch ($field['type']) {
            case 'string':
                $rules[] = $required ? 'required' : 'nullable';
                $rules[] = 'string';
                $rules[] = 'max:255';
                break;

            case 'text':
                $rules[] = $required ? 'required' : 'nullable';
                $rules[] = 'string';
                $rules[] = 'max:5000';
                break;

            case 'boolean':
                $rules[] = 'nullable';
                $rules[] = 'boolean';
                break;

            case 'number':
                $rules[] = $required ? 'required' : 'nullable';
                $rules[] = 'numeric';
                break;

            case 'json':
                $rules[] = 'nullable';
                $rules[] = 'json';
                break;

            case 'image':
                $rules[] = 'nullable';
                $rules[] = 'image';
                $rules[] = 'mimes:jpg,jpeg,png,webp,svg';
                $rules[] = 'max:20480';
                break;

            case 'color':
                $rules[] = 'nullable';
                $rules[] = 'string';
                $rules[] = 'regex:/^#?[0-9A-Fa-f]{6}$/';
                break;

            case 'select':
                $rules[] = 'nullable';
                $rules[] = 'string';
                if (!empty($field['options'])) {
                    $rules[] = Rule::in(array_keys($field['options']));
                }
                break;

            default:
                $rules[] = 'nullable';
                $rules[] = 'string';
                break;
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            '*.image'  => 'File phải là ảnh.',
            '*.mimes'  => 'Ảnh phải có định dạng JPG, JPEG, PNG, WEBP hoặc SVG.',
            '*.max'    => 'Ảnh không vượt quá 20MB.',
            '*.regex'  => 'Màu sắc phải có định dạng hex, ví dụ #0ea5e9.',
        ];
    }
}